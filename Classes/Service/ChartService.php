<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2012 Kai Vogel <kai.vogel@speedprogs.de>, Speedprogs.de
	 *
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published
	 *  by the Free Software Foundation; either version 3 of the License,
	 *  or (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ********************************************************************/

	/**
	 * Chart service
	 */
	class Tx_SpCharts_Service_ChartService implements t3lib_Singleton {

		/**
		 * @var array
		 */
		protected $availableRenderers = array();

		/**
		 * @var array
		 */
		protected $initializedRenderers = array();

		/**
		 * @var t3lib_PageRenderer
		 */
		protected $pageRenderer;

		/**
		 * @var array
		 */
		protected $configuration = array(
			'disableJQueryJs' => FALSE,
			'disableJqPlotJs' => FALSE,
			'disableChartJs'  => FALSE,
			'jsRootPath'      => 'EXT:sp_charts/Resources/Public/Javascript/',
			'gridLineColor'   => '#B9B9B9',
			'backgroundColor' => '#FFFFFF',
			'borderColor'     => '#515151',
			'borderWidth'     => 0.5,
			'barWidth'        => 15,
			'lineWidth'       => 2.5,
			'showLegend'      => TRUE,
			'equalSign'       => '=',
			'separator'       => ';',
		);

		/**
		 * @var string
		 */
		protected $pluginScheme = 'jqPlot/plugins/jqplot.%1$s.min.js';

		/**
		 * @var array
		 */
		protected $css = array(
			'jqplot' => 'jqPlot/jquery.jqplot.css',
		);

		/**
		 * @var array
		 */
		protected $js = array(
			'jquery' => 'jqPlot/jquery.min.js',
			'jqplot' => 'jqPlot/jquery.jqplot.min.js',
			'charts' => 'Chart.js',
		);


		/**
		 * Initialize
		 *
		 * @return void
		 */
		public function __construct() {
				// Get all available renderers
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts']['chartRenderers'])
			 && is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts']['chartRenderers'])) {
				$this->availableRenderers = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts']['chartRenderers'];
			}

				// Get page renderer
			if (!empty($GLOBALS['TSFE']) && get_class($GLOBALS['TSFE']) === 'tslib_fe') {
				$this->pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
			} else if (class_exists('t3lib_PageRenderer')) {
				$this->pageRenderer = t3lib_div::makeInstance('t3lib_PageRenderer');
			}
		}


		/**
		 * Set page renderer
		 *
		 * @param array $pageRenderer The page renderer
		 * @return void
		 */
		public function setPageRenderer(t3lib_PageRenderer $pageRenderer) {
			$this->pageRenderer = $pageRenderer;
		}


		/**
		 * Set configuration
		 *
		 * @param array $configuration TypoScript configuration
		 * @return void
		 */
		public function setConfiguration(array $configuration) {
			$this->configuration = $configuration;
		}


		/**
		 * Render a chart
		 *
		 * @param array $sets The sets of lines to render
		 * @param string $type Chart type
		 * @param array $configuration Configuration array
		 * @return string HTML content of the chart
		 */
		public function renderChart(array $sets, $type, array $configuration = array()) {
			if (empty($sets)) {
				return '';
			}

				// Get renderer
			$renderer = $this->getRenderer(strtolower(trim($type)));
			if (empty($renderer)) {
				return '';
			}

				// Get configuration
			if (!empty($configuration)) {
				$configuration = Tx_Extbase_Utility_Arrays::arrayMergeRecursiveOverrule($this->configuration, $configuration);
			} else {
				$configuration = $this->configuration;
			}

				// Add default chart js
			$jsRootPath = rtrim($configuration['jsRootPath'], '/') . '/';
			if (empty($configuration['disableChartJs'])) {
				$this->addJsFile('spcharts', $jsRootPath . $this->js['charts']);
			}

				// Add jqPlot
			if (empty($configuration['disableJqPlotJs'])) {
				$this->addPlugins($renderer->getPlugins(), $jsRootPath, 'jqplot_');
				$this->addJsFile('jqplot', $jsRootPath . $this->js['jqplot']);
				$this->addCssFile('jqplot', $jsRootPath . $this->css['jqplot']);
			}

				// Add jQuery
			if (empty($configuration['disableJQueryJs'])) {
				$this->addJsFile('jquery', $jsRootPath . $this->js['jquery']);
			}

			return $renderer->render($sets, $configuration);
		}


		/**
		 * Return renderer by given type
		 *
		 * @param string $type The chart type
		 * @return Tx_SpCharts_Chart_ChartInterface The renderer
		 */
		public function getRenderer($type) {
			if (empty($type)) {
				throw new Exception('Can not load a chart renderer with an empty type');
			}

				// Return existing renderer
			if (!empty($this->initializedRenderers[$type])) {
				return $this->initializedRenderers[$type];
			}

				// Make an instance of given chart
			if (empty($this->availableRenderers[$type]['class'])) {
				throw new Exception('No chart renderer for type "' . $type . '" found');
			}
			$renderer = t3lib_div::makeInstance($this->availableRenderers[$type]['class']);
			if (empty($renderer) || !$renderer instanceof Tx_SpCharts_Chart_ChartInterface) {
				throw new Exception('Chart renderer for type "' . $type . '" is not an instance of the "Tx_SpCharts_Chart_ChartInterface"');
			}

			return $this->initializedRenderers[$type] = $renderer;
		}


		/**
		 * Add stylesheet file to page renderer
		 *
		 * @param string $key The key for the file
		 * @param string $fileName The path to file
		 * @return void
		 */
		protected function addCssFile($key, $fileName) {
			if (empty($key) || empty($fileName) || empty($this->pageRenderer)) {
				return;
			}
			$this->pageRenderer->addCssFile($this->getRelativePath($fileName));
		}


		/**
		 * Add JavaScript file to page renderer
		 *
		 * Notice:
		 *   Add files in reverse order, because they are written in the
		 *   first place (forceOnTop) to prevent conflicts with other
		 *   JavaScript libraries
		 *
		 * @param string $key The key for the file
		 * @param string $fileName The path to file
		 * @param boolean $forceOnTop Inserted at begin
		 * @return void
		 */
		protected function addJsFile($key, $fileName, $forceOnTop = TRUE) {
			if (empty($key) || empty($fileName) || empty($this->pageRenderer)) {
				return;
			}
			$fileName = $this->getRelativePath($fileName);
			$this->pageRenderer->addJsLibrary($key, $fileName, 'text/javascript', FALSE, $forceOnTop);
		}


		/**
		 * Add jqPlot plugins to page renderer
		 *
		 * @param string $jsRootPath The root path of the JavaScript files
		 * @param string $prefix Prefix for the key
		 * @return void
		 */
		protected function addPlugins(array $plugins, $jsRootPath, $prefix = '') {
			if (empty($plugins)) {
				return;
			}

			krsort($plugins);
			foreach ($plugins as $name) {
				$fileName = $jsRootPath . sprintf($this->pluginScheme, trim($name));
				$this->addJsFile($prefix . strtolower(trim($name)), $fileName, FALSE);
			}
		}


		/**
		 * Get relative path
		 *
		 * @param string $fileName The file name
		 * @return string Relative path
		 */
		protected function getRelativePath($fileName) {
			if (empty($fileName)) {
				return '';
			}

			$backPath = '';
			if (TYPO3_MODE != 'FE') {
				$backPath = '../';
				if (!empty($GLOBALS['SOBE']) && !empty($GLOBALS['SOBE']->doc)) {
					$backPath = $GLOBALS['SOBE']->doc->backpath . '../';
				}
			}

			$fileName = t3lib_div::getFileAbsFileName($fileName);
			return str_replace(PATH_site, $backPath, $fileName);
		}

	}
?>