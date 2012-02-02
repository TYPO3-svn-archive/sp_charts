<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}

		// Add new content element
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array('CHART', 'Tx_SpCharts_Content_Chart');

		// Default configuration for the CHART content element
	$defaultConfiguration = array(
		'type'            => 'line',
		'disableJQueryJs' => FALSE,
		'disableJqPlotJs' => FALSE,
		'disableChartJs'  => FALSE,
		'jsRootPath'      => 'EXT:sp_charts/Resources/Public/Javascript/',
		'gridLineColor'   => '#B9B9B9',
		'backgroundColor' => '#F8F8F8',
		'borderColor'     => '#515151',
		'borderWidth'     => 0.5,
		'barWidth'        => 15,
		'lineWidth'       => 2.5,
		'showLegend'      => TRUE,
		'equalSign'       => '=',
		'separator'       => ';',
		'chartRenderers'  => array(
			'bar' => array(
				'class' => 'Tx_SpCharts_Chart_BarChart',
				'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_bar',
				'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Bar.gif',
			),
			'column' => array(
				'class' => 'Tx_SpCharts_Chart_ColumnChart',
				'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_column',
				'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Column.gif',
			),
			'donut' => array(
				'class' => 'Tx_SpCharts_Chart_DonutChart',
				'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_donut',
				'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Donut.gif',
			),
			'line' => array(
				'class' => 'Tx_SpCharts_Chart_LineChart',
				'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_line',
				'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Line.gif',
			),
			'pie' => array(
				'class' => 'Tx_SpCharts_Chart_PieChart',
				'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_pie',
				'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Pie.gif',
			),
		),
	);

		// Set configuration for the CHART content element
	if (!empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]) && is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY])) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY] = array_merge_recursive(
			$defaultConfiguration,
			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]
		);
	} else {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY] = $defaultConfiguration;
	}

		// Get extension configuration
	$extensionConfiguration = array();
	if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
	}

		// Make plugin available in frontend
	if (!empty($extensionConfiguration['enableDemoPlugin'])) {
		Tx_Extbase_Utility_Extension::configurePlugin(
			$_EXTKEY,
			'Frontend',
			array(
				'Frontend' => 'show',
			),
			array()
		);
	}

?>