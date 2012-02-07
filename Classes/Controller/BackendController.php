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
	 * Controller for the backend module
	 */
	class Tx_SpCharts_Controller_BackendController extends Tx_SpCharts_Controller_AbstractController {

		/**
		 * @var string
		 */
		protected $extensionName = 'SpCharts';


		/**
		 * Process a request
		 *
		 * @param Tx_Extbase_MVC_RequestInterface $request The request object
		 * @param Tx_Extbase_MVC_ResponseInterface $response The response
		 * @return void
		 */
		public function processRequest(Tx_Extbase_MVC_RequestInterface $request, Tx_Extbase_MVC_ResponseInterface $response) {
			$this->template = t3lib_div::makeInstance('template');
			$this->pageRenderer = $this->template->getPageRenderer();

			if (empty($GLOBALS['SOBE'])) {
				$GLOBALS['SOBE'] = new stdClass();
			}

			if (empty($GLOBALS['SOBE']->doc)) {
				$GLOBALS['SOBE']->doc = $this->template;
			}

			parent::processRequest($request, $response);
		}


		/**
		 * Initialize the current action
		 *
		 * @return void
		 */
		protected function initializeAction() {
				// Forward to list action if page id is empty
			$pageId = Tx_SpCharts_Utility_Backend::getPageId();
			$action = $this->request->getControllerActionName();
			if (empty($pageId) && $action !== 'index') {
				$this->forward('index');
			}

				// Pre-parse TypoScript setup
			if (!empty($this->settings) && is_array($this->settings)) {
				$this->settings = Tx_SpCharts_Utility_TypoScript::parse($this->settings);
			}

				// Override default page renderer in chart service (singleton)
			$chartService = $this->objectManager->get('Tx_SpCharts_Service_ChartService');
			$chartService->setPageRenderer($this->pageRenderer);
			$chartService->setConfiguration($this->settings);

				// Set default styles
			$setup = Tx_SpCharts_Utility_TypoScript::getSetupForPid($pageId, 'module.tx_spcharts');
			if (!empty($setup['_CSS_DEFAULT_STYLE'])) {
				$this->pageRenderer->addCssInlineBlock('spcharts', $setup['_CSS_DEFAULT_STYLE'], TRUE);
			}
		}


		/**
		 * Display an info message
		 *
		 * @return void
		 */
		public function indexAction() {
			// Nothing to do here, only for template output...
		}


		/**
		 * Display the chart
		 *
		 * @return void
		 */
		public function showAction() {
			$sets = $this->getSets();

				// Get demo data if configuration is empty
			if (empty($sets) && empty($this->settings['disableDemoSets'])) {
				$sets = array(array(
						'Firefox'           => 380,
						'Internet Explorer' => 312,
						'Google Chrome'     => 484,
						'Safari'            => 284,
						'Opera'             => 200,
				),
				array(
						'Firefox'           => 456,
						'Internet Explorer' => 485,
						'Google Chrome'     => 375,
						'Safari'            => 247,
						'Opera'             => 178,
				));
			}

			$this->view->assign('sets', $sets);
			$this->view->assign('settings', $this->settings);
		}

	}
?>