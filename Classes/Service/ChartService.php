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
		protected $settings = array();

		/**
		 * @var Tx_Extbase_Object_ObjectManager
		 */
		protected $objectManager;

		/**
		 * @var array
		 */
		protected $renderers = array();


		/**
		 * Initialize the service
		 *
		 * @return void
		 */
		public function __construct() {
				// Find registered renderers
			$this->availableRenderers = $this->getRegisteredRenderers();

				// Get TypoScript settings
			if (TYPO3_MODE == 'FE') {
				$this->settings = Tx_SpCharts_Utility_TypoScript::getSetupForPid($GLOBALS['TSFE']->id, 'plugin.tx_spcharts.settings');
			} else {
				$pid = Tx_SpCharts_Utility_Backend::getPageId();
				$this->settings = Tx_SpCharts_Utility_TypoScript::getSetupForPid($pid, 'module.tx_spcharts.settings');
			}
			$this->settings = Tx_SpCharts_Utility_TypoScript::parse($this->settings);

				// Get object manager
			$this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');

				// Load required libraries
			$this->loadLibraries();
		}


		/**
		 * Renders a chart
		 *
		 * @param string $type The type of chart to render
		 * @param array $data The rows and cols to show
		 * @return string The rendered chart
		 */
		public function renderChart($type, array $data) {
			$renderer = $this->getRenderer($type);
			if (!empty($renderer)) {
				return $renderer->render($data);
			}
			return '';
		}


		/**
		 * Return renderer by given type
		 *
		 * @param string $type The chart type
		 * @return Tx_SpCharts_Chart_ChartInterface The renderer
		 */
		public function getRenderer($type) {
			$type = strtolower(trim($type));
			if (empty($type)) {
				throw new Exception('No valid chart type given');
			}

				// Return existing renderer
			if (!empty($this->renderers[$type])) {
				return $this->renderers[$type];
			}

				// Make an instance of given chart
			if (empty($this->availableRenderers[$type]['class'])) {
				throw new Exception('No chart renderer found for type "' . $type . '"');
			}
			$class = $this->availableRenderers[$type]['class'];
			$renderer = $this->objectManager->get($class);
			if (empty($renderer) || !$renderer instanceof Tx_SpCharts_Chart_ChartInterface) {
				throw new Exception('Class "' . $class . '" is a not valid chart renderer');
			}
			$renderer->setConfiguration($this->settings);

			return $this->renderers[$type] = $renderer;
		}


		/**
		 * Returns all registered renderers
		 *
		 * @return array The renderers
		 */
		protected function getRegisteredRenderers() {
			if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts']['chartRenderers'])
			 || !is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts']['chartRenderers'])) {
				return array();
			}
			return $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts']['chartRenderers'];
		}


		/**
		 * Load required JS and CSS files into page renderer
		 *
		 * @return void
		 */
		protected function loadLibraries() {
			$pageRenderer = $this->objectManager->get('t3lib_PageRenderer');

				// Add stylesheets
			if (!empty($this->settings['stylesheet']) && is_array($this->settings['stylesheet'])) {
				foreach($this->settings['stylesheet'] as $file) {
					$pageRenderer->addCssFile($this->getRelativePath($file));
				}
			}

				// Add javascript libraries
			if (!empty($this->settings['javascript']) && is_array($this->settings['javascript'])) {
				$libraries = array_reverse($this->settings['javascript']);
				foreach($libraries as $key => $file) {
					$file = $this->getRelativePath($file);
					$pageRenderer->addJsLibrary($key, $file, 'text/javascript', FALSE, TRUE);
				}
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