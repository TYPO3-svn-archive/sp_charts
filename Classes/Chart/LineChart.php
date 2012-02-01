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
	 * Renderer for line chart
	 */
	class Tx_SpCharts_Chart_LineChart extends Tx_SpCharts_Chart_AbstractChart {

		/**
		 * @var string
		 */
		protected $options = '
			axes: {
				yaxis: {
					renderer: jQuery.jqplot.CategoryAxisRenderer
				}
			},
			grid: {
				gridLineColor: \'%1$s\',
				background: \'%2$s\',
				borderColor: \'%3$s\',
				borderWidth: 0.5,
				shadow: false
			},
			highlighter: {
				show: true,
				showMarker: true,
				sizeAdjust: 7.5,
				tooltipLocation: \'nw\',
				tooltipAxes: \'x\'
			},
			cursor: {
				show: false
			}
		';


		/**
		 * Render the chart
		 *
		 * @param array $values The data to show
		 * @return string The rendered chart
		 */
		public function render($data) {
			$sets = array();

				// Get sets
			foreach ($data as $set) {
				$bars = array();
				foreach ($set as $bar) {
					if (!isset($bars[$bar[0]])) {
						$bars[$bar[0]] = (int) $bar[1];
					} else {
						$bars[$bar[0]] += (int) $bar[1];
					}
				}

				$set = array();
				foreach ($bars as $key => $value) {
					$set[] = array($value, $key);
				}

				$sets[] = $set;
			}

				// Get options
			$options = sprintf(
				$this->options,
				$this->colors['gridLine'],
				$this->colors['background'],
				$this->colors['border']
			);

			return $this->renderChart($sets, $options);
		}

	}
?>