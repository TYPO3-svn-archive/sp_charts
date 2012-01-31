<?php
	$extensionImagePath = t3lib_extMgm::extRelPath('sp_charts') . 'Resources/Public/Images/';

	return array(
		'bar'    => $extensionImagePath . 'Chart/Bar.gif',
		'column' => $extensionImagePath . 'Chart/Column.gif',
		'donut'  => $extensionImagePath . 'Chart/Donut.gif',
		'line'   => $extensionImagePath . 'Chart/Line.gif',
		'pie'    => $extensionImagePath . 'Chart/Pie.gif',
	);
?>