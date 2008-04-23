<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster menu configuration
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_menuconf'];

// --[ Translate GET data ]--
$section = (isset($_GET['section']) ? $_GET['section'] : 'util' );

// --[ Write submitted menu configuration to DB if applicable ]--
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	$query = "UPDATE `" . $roster->db->table('menu') . "` SET `config` = '" . $_POST['arrayput'] . "' WHERE `section` = '" . $section . "';";

	$result = $roster->db->query($query);

	if( !$result )
	{
		$save_status = messagebox('Failed to update ' . $section . ' menu configuration due to a database error. MySQL said: <br />' . $roster->db->error(),$roster->locale->act['pagebar_menuconf'],'sred');
	}

	if( $roster->db->affected_rows() > 0 ) // the config row was actually changed
	{
		$save_status = '<span style="color:#0099FF;font-size:11px;">' . sprintf($roster->locale->act['menuconf_changes_saved'],$section) . '</span>';
	}
	else
	{
		$save_status = '<span style="color:#0099FF;font-size:11px;">' . $roster->locale->act['menuconf_no_changes_saved'] . '</span>';
	}
}

// --[ Fetch button list from DB ]--
$query = "SELECT `mb`.*, `a`.`basename`, `a`.`active`
	FROM `" . $roster->db->table('menu_button') . "` AS mb
	LEFT JOIN `" . $roster->db->table('addon') . "` AS a
	ON `mb`.`addon_id` = `a`.`addon_id`;";

$result = $roster->db->query($query);

if( !$result )
{
	die_quietly('Could not fetch buttons from database. MySQL said:<br />' . $roster->db->error(),$roster->locale->act['pagebar_menuconf'],__FILE__,__LINE__,$query);
}

$palet = array();
$dhtml_reg = '';
while( $row = $roster->db->fetch($result) )
{
	$palet['b' . $row['button_id']] = $row;
	$dhtml_reg .= ', "b' . $row['button_id'] . '"';
}

$roster->db->free_result($result);

// --[ Fetch menu configuration from DB ]--
$query = "SELECT * FROM " . $roster->db->table('menu') . " WHERE `section` = '" . $section . "';";

$result = $roster->db->query($query);

if( !$result )
{
	die_quietly('Could not fetch menu configuration from database. MySQL said:<br />' . $roster->db->error(),$roster->locale->act['pagebar_menuconf'],__FILE__,__LINE__,$query);
}

if( $roster->db->num_rows($result) )
{
	$row = $roster->db->fetch($result);

	$roster->db->free_result($result);
}
else
{
	$row = array('config' => '');
}

// --[ Distribute buttons between the button list and the unused buttons panel ]--
$arrayHeight = 0;
$arrayButtons = array();
foreach( explode(':',$row['config']) as $pos=>$button )
{
	if( isset($palet[$button]) )
	{
		$config[] = $button;
		$arrayButtons[] = $palet[$button];
		unset($palet[$button]);
	}
}
$arrayHeight = 1;
$arrayWidth = count($arrayButtons);

// --[ Strip out of scope buttons fromt he palet ]--
foreach( $palet as $id => $button )
{
	if( $button['scope'] != $section )
	{
		unset($palet[$id]);
		continue;
	}
}

$paletHeight = 1;
$paletWidth = count($palet);



// --[ Render configuration screen. ]--
$roster->output['html_head'] .= '	<script type="text/javascript" src="' . ROSTER_PATH . 'js/wz_dragdrop.js"></script>' . "\n";
$roster->output['html_head'] .= '	<script type="text/javascript" src="' . ROSTER_PATH . 'js/menuconf.js"></script>' . "\n";

$roster->tpl->assign_vars(array(
	'L_MENU_SELECT' => $roster->locale->act['menuconf_sectionselect'],
	'L_ADD_BUTTON'  => $roster->locale->act['menuconf_add_button'],
	'L_DRAG_DELETE' => $roster->locale->act['menuconf_drag_delete'],

	'UNUSED_PALLET' => messagebox('<div id="palet" style="width:' . (40*$paletWidth+5) . 'px;height:' . (40*$paletHeight+5) . 'px;"></div>',$roster->locale->act['menuconf_unused_buttons'],'sblue'),

	'U_FORM_ACTION' => makelink('&amp;section=' . $section),

	'L_CONFIRM_SUBMIT' => $roster->locale->act['confirm_config_submit'],
	'L_SAVE_SETTINGS'  => $roster->locale->act['config_submit_button'],
	'L_URL' => $roster->locale->act['url'],
	'L_TITLE' => $roster->locale->act['title'],
	'L_ICON' => $roster->locale->act['installer_icon'],

	'DHTML_REG' => $dhtml_reg,

	'SAVE_STATUS'  => ( isset($save_status) ? $save_status : '' ),
	'SECTION'      => $section,

	'ARRAY_WIDTH' => $arrayWidth,
	'ARRAY_HEIGHT' => $arrayHeight,
	'PALLET_WIDTH' => $paletWidth,
	'PALLET_HEIGHT' => $paletHeight,

	'BUTTON_GRID_WIDTH' => (40*$arrayWidth+5),
	'BUTTON_GRID_HEIGHT' => (40*$arrayHeight+5),
	)
);


// --[ Section select. ]--
$query = "SELECT `section` FROM " . $roster->db->table('menu') . ";";
$result = $roster->db->query($query);

if( !$result )
{
	die_quietly('Could not fetch section list from database for the selection dialog. MySQL said:<br />' . $roster->db->error(),$roster->locale->act['pagebar_menuconf'],__FILE__,__LINE__,$query);
}

while( $row = $roster->db->fetch($result) )
{
	$roster->tpl->assign_block_vars('section_select',array(
		'SELECTED' => ( $row['section'] == $section ? true : false ),
		'NAME' => $row['section'],
		)
	);
}

$roster->db->free_result($result);


// --[ Main grid design ]--
foreach( $arrayButtons as $pos => $button )
{
	// Save current locale array
	// Since we add all locales for button name localization, we save the current locale array
	// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
	$localetemp = $roster->locale->wordings;

	if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
	{
		// Include addon's locale files if they exist
		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS . $button['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
		}
	}

	if( !empty($button['icon']) )
	{
		if( strpos($button['icon'],'.') !== false )
		{
			$button['icon'] = ROSTER_PATH . 'addons/' . $button['basename'] . '/images/' . $button['icon'];
		}
		else
		{
			$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/' . $button['icon'] . '.' . $roster->config['img_suffix'];
		}
	}
	else
	{
		$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
	}

	$button['titkey'] = $button['title'];

	$button['title'] = isset($roster->locale->act[$button['title']]) ? $roster->locale->act[$button['title']] : $button['title'];
	if( strpos($button['title'],'|') )
	{
		list($button['title'],$button['tooltip']) = explode('|',$button['title'],2);
	}
	else
	{
		$button['tooltip'] = '';
	}

	$button['tooltip'] .= ( $button['tooltip'] != '' ? '<br /><br />' : '' )
		. '<span style="font-size:10px;">' . $roster->locale->act['scope'] . ': <span style="color:#FF3300;">' . $button['scope'] . '</span></span><br />'
		. '<span style="font-size:10px;">' . $roster->locale->act['basename'] . ': <span style="color:#FF3300;">' . $button['basename'] . '</span></span><br />'
		. '<span style="font-size:10px;">' . $roster->locale->act['url'] . ': <span style="color:#FF3300;">' . $button['url'] . '</span></span><br />'
		. '<span style="font-size:10px;">' . $roster->locale->act['title'] . ': <span style="color:#0099FF;">' . $button['titkey'] . '</span></span>';

	if( $button['active'] == '0' )
	{
		$buttonclass = 'menu_config_div_disabled';
		$button['tooltip'] .= '<br /><span style="font-size:10px;">' . $roster->locale->act['info'] . ': <span style="color:#FF3300;">' . $roster->locale->act['menuconf_addon_inactive'] . '</span></span>';
	}
	else
	{
		$buttonclass = 'menu_config_div';
	}

	$button['tooltip'] = ' ' . makeOverlib($button['tooltip'],$button['title'],'',2,'');

	$roster->tpl->assign_block_vars('button_grid',array(
		'ID'      => $button['button_id'],
		'CLASS'   => $buttonclass,
		'ICON'    => $button['icon'],
		'TOOLTIP' => $button['tooltip'],
		)
	);

	// Restore our locale array
	$roster->locale->wordings = $localetemp;
	unset($localetemp);
}

// --[ Button palet ]--
foreach( $palet as $id => $button )
{
	// Save current locale array
	// Since we add all locales for button name localization, we save the current locale array
	// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
	$localetemp = $roster->locale->wordings;

	if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
	{
		// Include addon's locale files if they exist
		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS . $button['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
		}
	}

	if( !empty($button['icon']) )
	{
		if( strpos($button['icon'],'.') !== false )
		{
			$button['icon'] = ROSTER_PATH . 'addons/' . $button['basename'] . '/images/' . $button['icon'];
		}
		else
		{
			$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/' . $button['icon'] . '.' . $roster->config['img_suffix'];
		}
	}
	else
	{
		$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
	}

	$button['titkey'] = $button['title'];

	$button['title'] = isset($roster->locale->act[$button['title']]) ? $roster->locale->act[$button['title']] : $button['title'];
	if( strpos($button['title'],'|') )
	{
		list($button['title'],$button['tooltip']) = explode('|',$button['title'],2);
	}
	else
	{
		$button['tooltip'] = '';
	}

	$button['tooltip'] .= ( $button['tooltip'] != '' ? '<br /><br />' : '' )
		. '<span style="font-size:10px;">' . $roster->locale->act['scope'] . ': <span style="color:#FF3300;">' . $button['scope'] . '</span></span><br />'
		. '<span style="font-size:10px;">' . $roster->locale->act['basename'] . ': <span style="color:#FF3300;">' . $button['basename'] . '</span></span><br />'
		. '<span style="font-size:10px;">' . $roster->locale->act['url'] . ': <span style="color:#FF3300;">' . $button['url'] . '</span></span><br />'
		. '<span style="font-size:10px;">' . $roster->locale->act['title'] . ': <span style="color:#0099FF;">' . $button['titkey'] . '</span></span>';

	if( $button['active'] == '0' )
	{
		$buttonclass = 'menu_config_div_disabled';
		$button['tooltip'] .= '<br /><span style="font-size:10px;">' . $roster->locale->act['info'] . ': <span style="color:#FF3300;">' . $roster->locale->act['menuconf_addon_inactive'] . '</span></span>';
	}
	else
	{
		$buttonclass = 'menu_config_div';
	}

	$button['tooltip'] = ' ' . makeOverlib($button['tooltip'],$button['title'],'',2,'');

	$roster->tpl->assign_block_vars('pallet_grid',array(
		'ID'      => $button['button_id'],
		'CLASS'   => $buttonclass,
		'ICON'    => $button['icon'],
		'TOOLTIP' => $button['tooltip'],
		)
	);
	// Restore our locale array
	$roster->locale->wordings = $localetemp;
	unset($localetemp);
}

// --[ Javascript defines and variable passing ]--
$footer .=
'<script type="text/javascript">
<!--

SET_DHTML(CURSOR_MOVE, TRANSPARENT, SCROLL' . $dhtml_reg . ', "palet"+NO_DRAG, "array"+NO_DRAG, "rec_bin"+NO_DRAG);

var roster_url	= \'' . ROSTER_URL . '\';

var dy		= 40;
var margTop	= 5;
var dx		= 40;
var margLef	= 5;

var arrayWidth = ' . $arrayWidth . ';
var arrayHeight = ' . $arrayHeight . ';
var paletHeight = ' . $paletHeight . ';

// Array intended to reflect the order of the draggable items
var aElts = Array();
var palet = Array();' . "\n";

$i = 0;

foreach( $palet as $id => $button )
{
	$roster->tpl->assign_block_vars('script_pallet',array(
		'ID'  => $id,
		'IDX' => $i++,
		)
	);
}

foreach( $arrayButtons as $pos => $button )
{
	$roster->tpl->assign_block_vars('script_aelts',array(
		'ID'  => $button['button_id'],
		'POS' => $pos,
		)
	);
}


$roster->tpl->set_filenames(array(
	'menu' => 'admin/menu_conf_menu.html',
	'body' => 'admin/menu_conf.html',
	'foot' => 'admin/menu_conf_footer.html',
	)
);
$menu = $roster->tpl->fetch('menu');
$body = $roster->tpl->fetch('body');
$footer = $roster->tpl->fetch('foot');
