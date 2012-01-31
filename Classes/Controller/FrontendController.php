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
	 * Controller for the frontend plugin
	 */
	class Tx_SpCharts_Controller_FrontendController extends Tx_SpCharts_ontroller_AbstractController {

		/**
		 * @var array
		 */
		protected $plugin;


		/**
		 * Initialize the current action
		 *
		 * @return void
		 */
		protected function initializeAction() {
				// Pre-parse TypoScript setup
			$this->settings = Tx_SpCharts_Utility_TypoScript::parse($this->settings);

				// Get information about current plugin
			$contentObject = $this->configurationManager->getContentObject();
			$this->plugin = (!empty($contentObject->data) ? $contentObject->data : array());
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
			$this->view->assign('plugin',   $this->plugin);
		}

	}
?>