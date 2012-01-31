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

		// Define renderers for the charts
	if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers'] = array();
	}
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['bar']    = 'Tx_SpCharts_Chart_BarChart';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['column'] = 'Tx_SpCharts_Chart_ColumnChart';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['donut']  = 'Tx_SpCharts_Chart_DonutChart';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['line']   = 'Tx_SpCharts_Chart_LineChart';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['pie']    = 'Tx_SpCharts_Chart_PieChart';

?>