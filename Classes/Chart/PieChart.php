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
	 * Renderer for pie chart
	 */
	class Tx_SpCharts_Chart_PieChart extends Tx_SpCharts_Chart_AbstractChart {

		/**
		 * @var array
		 */
		protected $plugins = array(
			'pieRenderer',
			'categoryAxisRenderer',
			'pointLabels',
			'highlighter',
		);

		/**
		 * @var string
		 */
		protected $options = '
			seriesDefaults:{
				renderer: jQuery.jqplot.PieRenderer,
				rendererOptions: {
					showDataLabels: true,
					fill: true,
					startAngle: -90,
					sliceMargin: 5,
					lineWidth: 1,
					padding: 15,
					dataLabels: \'value\',
					dataLabelPositionFactor: 1.1
				}
			},
			legend: {
				show: %1$s,
				location: \'e\'
			},
			grid: {
				drawGridLines: false,
				background: \'%2$s\',
				borderColor: \'%3$s\',
				borderWidth: %4$s,
				shadow: false
			}
		';


		/**
		 * Build the chart options
		 *
		 * @return string Chart options
		 */
		protected function getChartOptions() {
			return sprintf(
				$this->options,
				(!empty($this->configuration['showLegend']) ? 'true' : 'false'),
				$this->configuration['backgroundColor'],
				$this->configuration['borderColor'],
				$this->configuration['borderWidth']
			);
		}


		/**
		 * Build the chart content
		 *
		 * @return string Chart content
		 */
		protected function getChartContent() {
			if (empty($this->sets) || !is_array($this->sets)) {
				return array();
			}

				// Get first set
			$lines = reset($this->sets);
			$set = array();
			foreach ($lines as $title => $value) {
				$set[] = array($title, $value);
			}

			return array($set);
		}

	}
?>