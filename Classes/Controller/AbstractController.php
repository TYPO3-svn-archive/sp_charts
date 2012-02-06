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
		 * Build array from TypoScript set configuration
		 *
		 * @return array The sets
		 */
		protected function getSets() {
			if (empty($this->settings['sets']) || !is_array($this->settings['sets'])) {
				return array();
			}

			$sets = array();
			$separator = (!empty($this->settings['separator']) ? $this->settings['separator'] : ';');
			$equalSign = (!empty($this->settings['equalSign']) ? $this->settings['equalSign'] : '=');

				// Get sets
			foreach ($this->settings['sets'] as $name => $set) {
				if (empty($set)) {
					continue;
				}

					// Build lines
				$lines = array();
				$set = t3lib_div::trimExplode($separator, $set);
				foreach ($set as $line) {
					$line = t3lib_div::trimExplode($equalSign, $line);
					if (empty($line) || count($line) !== 2) {
						continue;
					}
					if (!isset($line[0])) {
						$lines[$line[0]] = $line[1];
					} else {
						$lines[$line[0]] += $line[1];
					}
				}

				$sets[$name] = $lines;
			}

			return $sets;
		}

	}
?>