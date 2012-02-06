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
	 * Abstract chart
	 */
	abstract class Tx_SpCharts_Chart_AbstractChart implements Tx_SpCharts_Chart_ChartInterface {

		/**
		 * @var array
		 */
		protected $plugins = array();

		/**
		 * @var array
		 */
		protected $sets = array();

		/**
		 * @var array
		 */
		protected $configuration = array();

		/**
		 * @var string
		 */
		protected $html = '
			<div class="spcharts-chart spcharts-chart-%1$s" id="spcharts-chart-%2$s"></div>
			<script type="text/javascript">
				charts[\'spcharts-chart-%2$s\'] = {
					data: %3$s,
					options: {%4$s}
				};
			</script>
		';


		/**
		 * Returns an array of required plugins
		 *
		 * @return array Plugin names
		 */
		public function getPlugins() {
			return (is_array($this->plugins) ? $this->plugins : array());
		}


		/**
		 * Render the chart
		 *
		 * @param array $sets The sets to render
		 * @param array $configuration TypoScript configuration
		 * @return string The rendered chart
		 */
		public function render(array $sets, array $configuration) {
			if (empty($sets)) {
				return '';
			}

				// Set attributes
			$this->sets = $sets;
			$this->configuration = $configuration;

				// Get chart type
			$type = substr(strtolower(get_class($this)), 0, -5);
			$type = substr($type, strrpos($type, '_') + 1);

				// Get chart content
			$options = $this->getChartOptions();
			$values = $this->getChartContent();
			$values = json_encode($values);

			return sprintf($this->html, $type, uniqid(), $values, $options);
		}


		/**
		 * Build the chart options
		 *
		 * @return string Chart options
		 */
		abstract protected function getChartOptions();


		/**
		 * Build the chart content
		 *
		 * @return string Chart content
		 */
		abstract protected function getChartContent();

	}
?>