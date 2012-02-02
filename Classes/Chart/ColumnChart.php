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
		protected $cssFiles = array(
			'jqplot'   => 'EXT:sp_charts/Resources/Public/Javascript/jqPlot/jquery.jqplot.css',
			'spcharts' => 'EXT:sp_charts/Resources/Public/Stylesheet/Charts.css',
		);

		/**
		 * @var array
		 */
		protected $jsFiles = array(
			'jquery'                      => 'EXT:sp_charts/Resources/Public/Javascript/jqPlot/jquery.min.js',
			'jqplot'                      => 'EXT:sp_charts/Resources/Public/Javascript/jqPlot/jquery.jqplot.min.js',
			'jqplot_barRenderer'          => 'EXT:sp_charts/Resources/Public/Javascript/jqPlot/plugins/jqplot.barRenderer.min.js',
			'jqplot_categoryAxisRenderer' => 'EXT:sp_charts/Resources/Public/Javascript/jqPlot/plugins/jqplot.categoryAxisRenderer.min.js',
			'jqplot_pointLabels'          => 'EXT:sp_charts/Resources/Public/Javascript/jqPlot/plugins/jqplot.pointLabels.min.js',
			'jqplot_highlighter'          => 'EXT:sp_charts/Resources/Public/Javascript/jqPlot/plugins/jqplot.highlighter.min.js',
			'spcharts'                    => 'EXT:sp_charts/Resources/Public/Javascript/Chart.js',
		);

		/**
		 * @var array
		 */
		protected $colors = array(
			'gridLine'   => '#B9B9B9',
			'background' => '#F8F8F8',
			'border'     => '#515151',
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
					barWidth: 25,
					shadowDepth: 3
				}
			},
			axes: {
				xaxis: {
					renderer: jQuery.jqplot.CategoryAxisRenderer,
					ticks: %4$s
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
				showMarker: false,
				sizeAdjust: 7.5,
				tooltipLocation: \'n\',
				tooltipAxes: \'y\'
			}
		';


		/**
		 * Build the chart options
		 * 
		 * @param array $configuration TypoScript configuration
		 * @return string Chart options
		 */
		protected function getChartOptions(array $configuration) {
			$colors = $this->colors;
			if (!empty($configuration['colors.']) && is_array($configuration['colors.'])) {
				foreach ($configuration['colors.'] as $key => $color) {
					if (!empty($configuration['colors.'][$key . '.']) && !empty($this->contentObject)) {
						$colors[$key] = $this->contentObject->cObjGetSingle($color, $configuration['colors.'][$key . '.']);
					}
				}
			}
			return sprintf($this->options, $colors['gridLine'], $colors['background'], $colors['border']);
		}


		/**
		 * Build the chart content
		 *
		 * @param array $configuration TypoScript configuration
		 * @return array The chart content
		 */
		protected function getChartValues(array $configuration) {
			if (empty($configuration['values.'])) {
				return array();
			}

			die('TODO: ' . get_class($this));

/*
			$titles = array();
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

				ksort($bars);

				if (empty($titles)) {
					$titles = array_keys($bars);
				}

				$sets[] = array_values($bars);
			}

				// Get options
			$options = sprintf(
				$this->options,
				$this->colors['gridLine'],
				$this->colors['background'],
				$this->colors['border'],
				json_encode($titles)
			);

			return $this->renderChart($sets, $options);
*/
		}

	}
?>