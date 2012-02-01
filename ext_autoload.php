<?php
	$extensionClassesPath = t3lib_extMgm::extPath('sp_charts', 'Classes/');

	return array(
		'tx_spcharts_chart_abstractchart'                     => $extensionClassesPath . 'Chart/AbstractChart.php',
		'tx_spcharts_chart_barchart'                          => $extensionClassesPath . 'Chart/BarChart.php',
		'tx_spcharts_chart_chartinterface'                    => $extensionClassesPath . 'Chart/ChartInterface.php',
		'tx_spcharts_chart_columnchart'                       => $extensionClassesPath . 'Chart/ColumnChart.php',
		'tx_spcharts_chart_donutchart'                        => $extensionClassesPath . 'Chart/DonutChart.php',
		'tx_spcharts_chart_linechart'                         => $extensionClassesPath . 'Chart/LineChart.php',
		'tx_spcharts_chart_piechart'                          => $extensionClassesPath . 'Chart/PieChart.php',
		'tx_spcharts_controller_abstractcontroller'           => $extensionClassesPath . 'Controller/AbstractController.php',
		'tx_spcharts_controller_backendcontroller'            => $extensionClassesPath . 'Controller/BackendController.php',
		'tx_spcharts_controller_frontendcontroller'           => $extensionClassesPath . 'Controller/FrontendController.php',
		'tx_spcharts_utility_backend'                         => $extensionClassesPath . 'Utility/Backend.php',
		'tx_spcharts_utility_tca'                             => $extensionClassesPath . 'Utility/Tca.php',
		'tx_spcharts_utility_typoscript'                      => $extensionClassesPath . 'Utility/TypoScript.php',
		'tx_spcharts_viewhelpers_chartviewhelper'             => $extensionClassesPath . 'ViewHelpers/ChartViewHelper.php',
		'tx_spcharts_viewhelpers_spriteiconviewhelper'        => $extensionClassesPath . 'ViewHelpers/SpriteIconViewHelper.php',
	);
?>