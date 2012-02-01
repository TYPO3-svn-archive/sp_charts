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
	 * Abstract controller
	 */
	class Tx_SpCharts_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {

		/**
		 * Get configured data
		 * 
		 * @param array $settings The TypoScript setup
		 * @return array The data to show
		 */
		protected function getConfiguredData(array $settings) {
			$data = array();

			if (!empty($settings['chartSetup']) && is_array($settings['chartSetup'])) {
				foreach($settings['chartSetup'] as $set) {
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

			return $data;
		}


		/**
		 * Get all data to show in charts
		 * 
		 * @param array $settings The TypoScript setup
		 * @return array The data to show
		 */
		protected function getData(array $settings) {
			$data = $this->getConfiguredData($settings);

				// Get demo data if configuration is empty
			if (empty($data) && empty($settings['hideDemoData'])) {
				$data = array(array(
					array('Firefox',           380),
					array('Internet Explorer', 312),
					array('Google Chrome',     484),
					array('Safari',            284),
					array('Opera',             200),
				));
			}

			return $data;
		}

	}
?>