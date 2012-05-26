<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster menu configuration
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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
$section_name = (isset($roster->locale->act['menupanel_' . $section]) ? $roster->locale->act['menupanel_' . $section] : $section);

// --[ Write submitted menu configuration to DB if applicable ]--
if( isset($_POST['process']) && $_POST['process'] == 'process' )
{
	$query = "UPDATE `" . $roster->db->table('menu') . "` SET `config` = '" . $_POST['arrayput'] . "' WHERE `section` = '" . $section . "';";

	$result = $roster->db->query($query);

	if( !$result )
	{
		$roster->set_message('Failed to update ' . $section_name . ' menu configuration due to a database error.', '' ,'error');
		$roster->set_message('<pre>' . $roster->db->error() , '</pre>', 'MySQL Said', 'error');
	}

	if( $roster->db->affected_rows() > 0 ) // the config row was actually changed
	{
		$roster->set_message(sprintf($roster->locale->act['menuconf_changes_saved'], $section_name));
	}
	else
	{
		$roster->set_message($roster->locale->act['menuconf_no_changes_saved']);
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
roster_add_js('js/wz_dragdrop.js');
roster_add_js('js/menuconf.js');

$roster->tpl->assign_vars(array(
	'U_FORM_ACTION' => makelink('&amp;section=' . $section),

	'DHTML_REG'     => $dhtml_reg,

	'SECTION'       => $section,
	'SECTION_NAME'  => $section_name,

	'ARRAY_WIDTH'   => $arrayWidth,
	'ARRAY_HEIGHT'  => $arrayHeight,
	'PALLET_WIDTH'  => $paletWidth,
	'PALLET_HEIGHT' => $paletHeight,

	'BUTTON_GRID_WIDTH'     => (40*$arrayWidth+8),
	'BUTTON_GRID_HEIGHT'    => (40*$arrayHeight+8),

	'UN_BUTTON_GRID_WIDTH'  => (40*$paletWidth+8),
	'UN_BUTTON_GRID_HEIGHT' => (40*$paletHeight+8),

	'ADD_TIP' => makeOverlib($roster->locale->act['menu_config_help_text'], $roster->locale->act['menu_config_help'], '', 2, '', ',WRAP')
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
	$panel_label = (isset($roster->locale->act['menupanel_' . $row['section']]) ? $roster->locale->act['menupanel_' . $row['section']] : $row['section']);

	$roster->tpl->assign_block_vars('section_select',array(
		'SELECTED' => ( $row['section'] == $section ? true : false ),
		'VALUE' => $row['section'],
		'NAME' => $panel_label,
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
		$buttonclass = 'legendary';
		$button['tooltip'] .= '<br /><span style="font-size:10px;">' . $roster->locale->act['info'] . ': <span style="color:#FF3300;">' . $roster->locale->act['menuconf_addon_inactive'] . '</span></span>';
	}
	else
	{
		$buttonclass = 'common';
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
		$buttonclass = 'legendary';
		$button['tooltip'] .= '<br /><span style="font-size:10px;">' . $roster->locale->act['info'] . ': <span style="color:#FF3300;">' . $roster->locale->act['menuconf_addon_inactive'] . '</span></span>';
	}
	else
	{
		$buttonclass = 'common';
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

$i = 0;


$jscript = '
SET_DHTML(CURSOR_MOVE, TRANSPARENT, SCROLL'. $dhtml_reg .', "palet" + NO_DRAG, "array" + NO_DRAG, "rec_bin" + NO_DRAG);

var roster_url = "'. ROSTER_URL .'";

var dy      = 40;
var margTop = 5;
var dx      = 40;
var margLef = 5;

var arrayWidth = '. $arrayWidth .';
var arrayHeight = '. $arrayHeight .';
var paletHeight = '. $paletHeight .';

// Array intended to reflect the order of the draggable items
var aElts = Array();
var palet = Array();
';

foreach( $palet as $id => $button )
{
	$jscript .= 'palet['. $i++ .'] = dd.elements.'. $id .';'."\n";
}
foreach( $arrayButtons as $pos => $button )
{
	$jscript .= 'aElts['. $pos .'] = dd.elements.b'. $button['button_id'] .';'."\n";
}
$jscript .= 'updatePositions();'."\n";

roster_add_js($jscript, 'inline', 'footer', FALSE, FALSE);


$roster->tpl->set_filenames(array(
	'menu' => 'admin/menu_conf_menu.html',
	'body' => 'admin/menu_conf.html',
	)
);

$menu = $roster->tpl->fetch('menu');
$body = $roster->tpl->fetch('body');
