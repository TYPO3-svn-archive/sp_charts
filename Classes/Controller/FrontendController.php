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
			if (!empty($this->settings['chartSetup']) && is_string($this->settings['chartSetup'])) {
				$this->settings['chartSetup'] = Tx_SpCharts_Utility_TypoScript::parseTypoScriptString($this->settings['chartSetup']);
			}
		}


		/**
		 * Display the chart
		 *
		 * @return void
		 */
		public function showAction() {
				// Get data to show in chart
			$data = array();
			if (!empty($this->settings['chartSetup']) && is_array($this->settings['chartSetup'])) {
				foreach($this->settings['chartSetup'] as $set) {
					if (!is_array($set)) {
						throw new Exception('Chart setup is not well-formed');
					}
					$bars = array();
					foreach ($set as $bar) {
						if (!isset($bar['title'], $bar['value'])) {
							throw new Exception('Chart setup for one bar is not well-formed');
						}
						$bars[] = array($bar['title'], $bar['value']);
					}
					$data[] = $bars;
				}
			}

				// Show demo data if empty
			if (empty($data) && !empty($this->settings['showDemoData'])) {
				$data = array(array(
					array('Firefox', 180),
					array('Internet Explorer', 112),
					array('Google Chrome', 684),
					array('Safari', 84),
					array('Opera', 200),
				));
			}

			$this->view->assign('data', $data);
			$this->view->assign('settings', $this->settings);
		}

	}
?>