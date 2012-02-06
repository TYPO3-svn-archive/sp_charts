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
	 * Renderer for column chart
	 */
	class Tx_SpCharts_Chart_ColumnChart extends Tx_SpCharts_Chart_AbstractChart {

		/**
		 * @var array
		 */
		protected $plugins = array(
			'barRenderer',
			'categoryAxisRenderer',
			'pointLabels',
			'highlighter',
		);

		/**
		 * @var string
		 */
		protected $options = '
			seriesDefaults: {
				renderer: jQuery.jqplot.BarRenderer,
				pointLabels: {
					show: false
				},
				rendererOptions: {
					fillToZero: true,
					barWidth: %1$s,
					shadowDepth: 3
				}
			},
			axes: {
				xaxis: {
					renderer: jQuery.jqplot.CategoryAxisRenderer,
					ticks: %2$s
				}
			},
			grid: {
				gridLineColor: \'%3$s\',
				background: \'%4$s\',
				borderColor: \'%5$s\',
				borderWidth: %6$s,
				shadow: false
			},
			highlighter: {
				show: true,
				showMarker: false,
				sizeAdjust: 7.5,
				tooltipLocation: \'n\',
				tooltipAxes: \'y\'
			}
		';


		/**
		 * Build the chart options
		 *
		 * @return string Chart options
		 */
		protected function getChartOptions() {
				// Get titles from first set of lines
			$titles = '[]';
			if (!empty($this->sets) && is_array($this->sets)) {
				$firstSet = reset($this->sets);
				$titles = json_encode(array_keys($firstSet));
			}

			return sprintf(
				$this->options,
				$this->configuration['barWidth'],
				$titles,
				$this->configuration['gridLineColor'],
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

				// Get all sets
			$sets = array();
			foreach ($this->sets as $lines) {
				$lines = array_reverse($lines, TRUE);
				$sets[] = array_values($lines);
			}

			return $sets;
		}

	}
?>