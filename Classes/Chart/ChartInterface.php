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
	 * Interface for charts
	 */
	interface Tx_SpCharts_Chart_ChartInterface extends t3lib_Singleton {

		/**
		 * Returns an array of required plugins
		 *
		 * @return array Plugin names
		 */
		public function getPlugins();


		/**
		 * Render the chart
		 *
		 * @param array $sets The sets to render
		 * @param array $configuration TypoScript configuration
		 * @return string The rendered chart
		 */
		public function render(array $sets, array $configuration);

	}
?>