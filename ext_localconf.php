<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}

		// Make plugin available in frontend
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Frontend',
		array(
			'Frontend' => 'show',
		),
		array()
	);

		// Add new content element
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] =
		array('CHART', 'Tx_SpCharts_Content_Chart');

		// Define renderers for the charts
	if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers'] = array();
	}

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['bar'] = array(
		'class' => 'Tx_SpCharts_Chart_BarChart',
		'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_bar',
		'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Bar.gif',
	);

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['column'] = array(
		'class' => 'Tx_SpCharts_Chart_ColumnChart',
		'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_column',
		'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Column.gif',
	);

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['donut'] = array(
		'class' => 'Tx_SpCharts_Chart_DonutChart',
		'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_donut',
		'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Donut.gif',
	);

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['line'] = array(
		'class' => 'Tx_SpCharts_Chart_LineChart',
		'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_line',
		'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Line.gif',
	);

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['pie'] = array(
		'class' => 'Tx_SpCharts_Chart_PieChart',
		'title' => 'LLL:EXT:sp_charts/Resources/Private/Language/locallang.xml:chart_pie',
		'image' => 'EXT:sp_charts/Resources/Public/Images/Chart/Pie.gif',
	);

?>