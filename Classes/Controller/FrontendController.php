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
	class Tx_SpCharts_Controller_FrontendController extends Tx_SpCharts_Controller_AbstractController {

		/**
		 * Initialize the current action
		 *
		 * @return void
		 */
		protected function initializeAction() {
				// Pre-parse TypoScript setup
			$this->settings = Tx_SpCharts_Utility_TypoScript::parse($this->settings);

				// Parse chart setup in plugin settings
			if (!empty($this->settings['flexformSets']) && is_string($this->settings['flexformSets'])) {
				$this->settings['sets'] = Tx_SpCharts_Utility_TypoScript::parseTypoScriptString($this->settings['flexformSets']);
				unset($this->settings['flexformSets']);
			}
		}


		/**
		 * Display the chart
		 *
		 * @return void
		 */
		public function showAction() {
			$this->view->assign('sets', $this->getSets());
			$this->view->assign('settings', $this->settings);
		}

	}
?>