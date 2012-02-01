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
	class Tx_SpCharts_Service_ChartService {

		/**
		 * @var string
		 */
		protected $extensionKey = 'sp_charts';

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
				// Find configured renderers
			if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$this->extensionKey]['chartRenderers'])
			 || !is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$this->extensionKey]['chartRenderers'])) {
				throw new Exception('No chart renderers definined');
			}
			$this->availableRenderers = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$this->extensionKey]['chartRenderers'];
		}


		/**
		 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
		 * @return void
		 */
		public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) {
			$settings = $configurationManager->getConfiguration(
				Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
			);
			if (!empty($settings)) {
				$this->settings = Tx_SpCharts_Utility_TypoScript::parse($settings);
			}
		}


		/**
		 * @var Tx_Extbase_Object_ObjectManager $objectManager
		 * @return void
		 */
		public function injectObjectManager(Tx_Extbase_Object_ObjectManager $objectManager) {
			$this->objectManager = $objectManager;
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
			if (empty($this->availableRenderers[$type])) {
				throw new Exception('No chart renderer found for type "' . $type . '"');
			}
			$renderer = $this->objectManager->get($this->availableRenderers[$type]);
			if (empty($renderer) || !$renderer instanceof Tx_SpCharts_Chart_ChartInterface) {
				throw new Exception('Class "' . $this->availableRenderers[$type] . '" is a not valid chart renderer');
			}
			$renderer->setConfiguration($this->settings);

			return $this->renderers[$type] = $renderer;
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

	}
?>