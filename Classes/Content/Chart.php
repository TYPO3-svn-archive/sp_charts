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
	 * CHART content object
	 */
	class Tx_SpCharts_Content_Chart implements t3lib_Singleton {

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
		protected $configuration = array();


		/**
		 * Initialize
		 *
		 * @return void
		 */
		public function __construct() {
				// Get default configuration
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts'])
			 && is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts'])) {
				$this->configuration = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sp_charts'];
			}

				// Get all available renderers
			if (!empty($this->configuration['chartRenderers']) && is_array($this->configuration['chartRenderers'])) {
				$this->availableRenderers = $this->configuration['chartRenderers'];
				unset($this->configuration['chartRenderers']);
			}

				// Get page renderer
			if (!empty($GLOBALS['TSFE'])) {
				$this->pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
			} else if (class_exists('t3lib_PageRenderer')) {
					// Get the singleton instance
				$this->pageRenderer = t3lib_div::makeInstance('t3lib_PageRenderer');
			}
		}


		/**
		 * Render the content object "CHART"
		 *
		 * @param string $name The content object name
		 * @param array $configuration TypoScript configuration for the content object
		 * @param string $tsKey Label used for the internal debugging tracking
		 * @param tslib_cObj $parent Reference to parent object
		 * @return string Chart content
		 */
		public function cObjGetSingleExt($name, array $configuration, $tsKey, tslib_cObj $contentObject) {
			if (strtoupper($name) !== 'CHART') {
				return '';
			}

				// Merge with default configuration
			$configuration = array_merge($this->configuration, $configuration);

				// Get chart type
			$type = $configuration['type'];
			if (!empty($configuration['type.'])) {
				$type = $contentObject->stdWrap($type, $configuration['type.']);
			}

				// Get chart renderer
			$renderer = $this->getRenderer($type);
			if (empty($renderer)) {
				return '';
			}

				// Equip renderer with required attributes
			$renderer->setContentObject($contentObject);
			$renderer->setPageRenderer($this->pageRenderer);

				// Get chart code
			$content = $renderer->render($configuration);

				// stdWrap
			if (!empty($configuration['stdWrap.'])) {
				$content = $contentObject->stdWrap($content, $configuration['stdWrap.']);
			}

			return $content;
		}


		/**
		 * Return renderer by given type
		 *
		 * @param string $type The chart type
		 * @return Tx_SpCharts_Chart_ChartInterface The renderer
		 */
		protected function getRenderer($type) {
			$type = strtolower(trim($type));
			if (empty($type)) {
				return NULL;
			}

				// Return existing renderer
			if (!empty($this->initializedRenderers[$type])) {
				return $this->initializedRenderers[$type];
			}

				// Make an instance of given chart
			if (empty($this->availableRenderers[$type]['class'])) {
				return NULL;
			}
			$renderer = t3lib_div::makeInstance($this->availableRenderers[$type]['class']);
			if (empty($renderer) || !$renderer instanceof Tx_SpCharts_Chart_ChartInterface) {
				return NULL;
			}

			return $this->initializedRenderers[$type] = $renderer;
		}

	}
?>