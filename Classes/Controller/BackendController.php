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
		 * @var integer
		 */
		protected $pageId = 0;


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
			$this->pageId = Tx_SpCharts_Utility_Backend::getPageId();

				// Forward to list action if page id is empty
			$action = $this->request->getControllerActionName();
			if (empty($this->pageId) && $action !== 'index') {
				$this->forward('index');
			}

				// Pre-parse TypoScript setup
			if (!empty($this->settings) && is_array($this->settings)) {
				$this->settings = Tx_SpCharts_Utility_TypoScript::parse($this->settings);
			}

				// Add stylesheets
			if (!empty($this->settings['stylesheet']) && is_array($this->settings['stylesheet'])) {
				foreach($this->settings['stylesheet'] as $file) {
					$this->pageRenderer->addCssFile($this->getRelativePath($file));
				}
			}

				// Add javascript libraries
			if (!empty($this->settings['javascript']) && is_array($this->settings['javascript'])) {
				$libraries = array_reverse($this->settings['javascript']);
				foreach($libraries as $key => $file) {
					$file = $this->getRelativePath($file);
					$this->pageRenderer->addJsLibrary($key, $file, 'text/javascript', FALSE, TRUE);
				}
			}
		}


		/**
		 * Display an info message
		 *
		 * @return void
		 */
		public function indexAction() {

		}


		/**
		 * Display the chart
		 *
		 * @return void
		 */
		public function showAction() {
			$demoData = array(
				array('AddThis',   180),
				array('AddThis',   112),
				array('Ask',       684),
				array('Bluedot',   84),
				array('Bluedot',   200),
				array('Delicious', 480),
				array('Delicidous', 480),
				array('Deliscious', 480),
				array('Delicdious', 480),
				array('Delsicious', 480),
				array('Delsicious', 480),
				array('Delsicfious', 480),
				array('Delicihous', 480),
				array('Delficious', 480),
				array('Delicious', 480),
				array('Deliscious', 480),
				array('Delgicious', 4860),
				array('Deliecious', 4580),
			);

			$this->view->assign('data',     $demoData);
			$this->view->assign('settings', $this->settings);
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

			$backPath = $this->doc->backpath . '../';
			$fileName = t3lib_div::getFileAbsFileName($fileName);

			return str_replace(PATH_site, $backPath, $fileName);
		}

	}
?>