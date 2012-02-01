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
	 * View helper for charts
	 */
	class Tx_SpCharts_ViewHelpers_ChartViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

		/**
		 * @var Tx_SpCharts_Service_ChartService
		 */
		protected $chartService;


		/**
		 * @var Tx_Extbase_Object_ObjectManager $objectManager
		 * @return void
		 */
		public function injectChartService(Tx_SpCharts_Service_ChartService $chartService) {
			$this->chartService = $chartService;
		}


		/**
		 * Renders a chart
		 *
		 * @param array $data The rows and cols to show
		 * @param string $type The type of chart to render
		 * @return string The rendered chart
		 */
		public function render($data = NULL, $type = 'bar') {
			if ($data === NULL) {
				$data = $this->renderChildren();
			}

			return $this->chartService->renderChart($type, $data);
		}

	}
?>