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
	 * Abstract chart
	 */
	abstract class Tx_SpCharts_Chart_AbstractChart implements Tx_SpCharts_Chart_ChartInterface {

		/**
		 * @var tslib_cObj
		 */
		protected $contentObject;

		/**
		 * @var t3lib_PageRenderer
		 */
		protected $pageRenderer;

		/**
		 * @var array
		 */
		protected $plugins = array();

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
		 * @var string
		 */
		protected $html = '
			<div class="spcharts-chart spcharts-chart-%1$s" id="spcharts-chart-%2$s"></div>
			<script type="text/javascript">
				charts[\'spcharts-chart-%2$s\'] = {
					data: %3$s,
					options: {%4$s}
				};
			</script>
		';


		/**
		 * Set content object
		 *
		 * @param tslib_cObj $contentObject The content object
		 * @return void
		 */
		public function setContentObject(tslib_cObj $contentObject) {
			$this->contentObject = $contentObject;
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
		 * Render the chart
		 *
		 * @param array $configuration TypoScript configuration
		 * @return string The rendered chart
		 */
		public function render(array $configuration) {
				// Get root path for js files
			$jsRootPath = $configuration['jsRootPath'];
			if (!empty($configuration['jsRootPath.']) && !empty($this->contentObject)) {
				$jsRootPath = $this->contentObject->stdWrap($jsRootPath, $configuration['jsRootPath.']);
			}
			$jsRootPath = rtrim($jsRootPath, '/') . '/';

				// Add default chart js
			if (empty($configuration['disableChartJs'])) {
				$this->addJsFile('spcharts', $jsRootPath . $this->js['charts']);
			}

				// Get chart type
			$type = substr(strtolower(get_class($this)), 0, -5);
			$type = substr($type, strrpos($type, '_') + 1);

				// Get chart content
			$this->buildSetsFromTs($configuration);
			$options = $this->getChartOptions($configuration);
			$values  = $this->getChartContent($configuration);
			$values  = json_encode($values);
			$content = sprintf($this->html, $type, uniqid(), $values, $options);

				// Add jqPlot
			if (empty($configuration['disableJqPlotJs'])) {
				$this->addPlugins($jsRootPath, 'jqplot_');
				$this->addJsFile('jqplot', $jsRootPath . $this->js['jqplot']);
				$this->addCssFile('jqplot', $jsRootPath . $this->css['jqplot']);
			}

				// Add jQuery
			if (empty($configuration['disableJQueryJs'])) {
				$this->addJsFile('jquery', $jsRootPath . $this->js['jquery']);
			}

			return $content;
		}


		/**
		 * Build the chart options
		 *
		 * @param array $configuration TypoScript configuration
		 * @return string Chart options
		 */
		abstract protected function getChartOptions(array $configuration);


		/**
		 * Build the chart content
		 *
		 * @param array $configuration TypoScript configuration
		 * @return string Chart content
		 */
		abstract protected function getChartContent(array $configuration);


		/**
		 * Build array from TypoScript set configuration
		 *
		 * @param array $configuration TypoScript configuration
		 * @return void
		 */
		protected function buildSetsFromTs(array &$configuration) {
			if (empty($configuration['sets.']) || !is_array($configuration['sets.'])) {
				return;
			}

				// Get sets
			foreach ($configuration['sets.'] as $name => $set) {
					// Parse set
				$setConfiguration = array();
				if (!empty($configuration['sets.'][$name . '.'])) {
					$setConfiguration = $configuration['sets.'][$name . '.'];
					unset($configuration['sets.'][$name . '.']);
				}
				if (!empty($this->contentObject)) {
					$set = $this->contentObject->cObjGetSingle($set, $setConfiguration);
				}
				if (empty($set)) {
					unset($configuration['sets.'][$name]);
					continue;
				}

					// Build lines
				$lines = array();
				$set = explode($configuration['separator'], $set);
				foreach ($set as $line) {
					$line = explode($configuration['equalSign'], $line);
					if (empty($line) || count($line) !== 2) {
						continue;
					}
					if (!isset($line[0])) {
						$lines[$line[0]] = $line[1];
					} else {
						$lines[$line[0]] += $line[1];
					}
				}

				$configuration['sets.'][$name] = $lines;
			}
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
		 * @return void
		 */
		protected function addJsFile($key, $fileName) {
			if (empty($key) || empty($fileName) || empty($this->pageRenderer)) {
				return;
			}
			$fileName = $this->getRelativePath($fileName);
			$this->pageRenderer->addJsLibrary($key, $fileName, 'text/javascript', FALSE, TRUE);
		}


		/**
		 * Add jqPlot plugins to page renderer
		 *
		 * @param string $jsRootPath The root path of the JavaScript files
		 * @param string $prefix Prefix for the key
		 * @return void
		 */
		protected function addPlugins($jsRootPath, $prefix = '') {
			if (empty($this->plugins) || !is_array($this->plugins)) {
				return;
			}

			$plugins = $this->plugins;
			krsort($plugins);

			foreach ($plugins as $name) {
				$fileName = $jsRootPath . sprintf($this->pluginScheme, trim($name));
				$this->addJsFile($prefix . strtolower(trim($name)), $fileName);
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