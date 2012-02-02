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
		 * @var tslib_cObj
		 */
		protected $contentObject;

		/**
		 * @var t3lib_PageRenderer
		 */
		protected $pageRenderer;

		/**
		 * @var array
		 */
		protected $cssFiles = array();

		/**
		 * @var array
		 */
		protected $jsFiles = array();

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
		 * Set content object
		 *
		 * @param tslib_cObj $contentObject The content object
		 * @return void
		 */
		public function setContentObject(tslib_cObj $contentObject) {
			$this->contentObject = $contentObject;
		}


		/**
		 * Set page renderer
		 *
		 * @param array $pageRenderer The page renderer
		 * @return void
		 */
		public function setPageRenderer(t3lib_PageRenderer $pageRenderer) {
			$this->pageRenderer = $pageRenderer;
		}


		/**
		 * Render the chart
		 *
		 * @param array $configuration TypoScript configuration
		 * @return string The rendered chart
		 */
		public function render(array $configuration) {
				// Add CSS files to page renderer
			if (!empty($this->cssFiles) && !empty($this->pageRenderer)) {
				foreach($this->cssFiles as $key => $fileName) {
					$fileName = str_replace(PATH_site, '', t3lib_div::getFileAbsFileName($fileName));
					$this->pageRenderer->addCssFile($fileName);
				}
			}

				// Add JS files to page renderer
			if (!empty($this->jsFiles) && !empty($this->pageRenderer)) {
				$files = array_reverse($this->jsFiles);
				foreach($files as $key => $fileName) {
					$fileName = str_replace(PATH_site, '', t3lib_div::getFileAbsFileName($fileName));
					$this->pageRenderer->addJsLibrary($key, $fileName, 'text/javascript', FALSE, TRUE);
				}
			}

				// Get chart type
			$type = substr(strtolower(get_class($this)), 0, -5);
			$type = substr($type, strrpos($type, '_') + 1);

				// Get chart
			$options = $this->getChartOptions($configuration);
			$values  = $this->getChartValues($configuration);
			$values  = json_encode($values);

			return sprintf($this->html, $type, uniqid(), $values, $options);
		}


		/**
		 * Build the chart options
		 * 
		 * @param array $configuration TypoScript configuration
		 * @return string Chart options
		 */
		abstract protected function getChartOptions(array $configuration);


		/**
		 * Build the chart options
		 * 
		 * @param array $configuration TypoScript configuration
		 * @return string Chart options
		 */
		abstract protected function getChartValues(array $configuration);

	}
?>