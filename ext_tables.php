<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}


		// Get extension configuration
	$extensionConfiguration = array();
	if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
	}


		// Add plugin to list
	Tx_Extbase_Utility_Extension::registerPlugin(
		$_EXTKEY,
		'Frontend',
		'Chart'
	);


		// Add flexform field
	$identifier = str_replace('_', '', $_EXTKEY) . '_frontend';
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$identifier] = 'layout,select_key,recursive,pages';
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$identifier] = 'pi_flexform';
	t3lib_extMgm::addPiFlexFormValue($identifier, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Chart.xml');


		// Add static TypoScript files
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Chart Configuration');


		// Add plugin to new content element wizard
	t3lib_extMgm::addPageTSConfig("
		mod.wizards.newContentElement.wizardItems.special {\n
			elements." . $identifier . " {\n
				icon        = " . t3lib_extMgm::extRelPath($_EXTKEY) . "Resources/Public/Images/Wizard.gif\n
				title       = LLL:EXT:" . $_EXTKEY . "/Resources/Private/Language/locallang.xml:plugin.title\n
				description = LLL:EXT:" . $_EXTKEY . "/Resources/Private/Language/locallang.xml:plugin.description\n\n
				tt_content_defValues {\n
					CType = list\n
					list_type = " . $identifier . "\n
				}\n
			}\n\n
			show := addToList(" . $identifier . ")\n
		}
	");


		// Add charts module to backend
	if (!empty($extensionConfiguration['enableDemoModule'])) {
		Tx_Extbase_Utility_Extension::registerModule(
			$_EXTKEY,
			'web',
			$identifier,
			'',
			array(
				'Backend' => 'show,index',
			),
			array(
				'access' => 'user,group',
				'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
				'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml',
				'navigationComponentId' => 'typo3-pagetree',
			)
		);
	}


		// Add sprite icons
	$iconFile = t3lib_extMgm::extPath($_EXTKEY)  . 'ext_icons.php';
	if (file_exists($iconFile)) {
		t3lib_SpriteManager::addSingleIcons(require($iconFile), str_replace('_', '', $_EXTKEY));
	}

?>