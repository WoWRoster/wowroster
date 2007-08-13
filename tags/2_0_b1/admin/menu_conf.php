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

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_menuconf'];

// --[ Translate GET data ]--
$section = (isset($_GET['section']) ? $_GET['section'] : 'util' );

// --[ Write submitted menu configuration to DB if applicable ]--
if (isset($_POST['process']) && $_POST['process'] == 'process')
{
	$query = "UPDATE `".$roster->db->table('menu')."` SET `config` = '".$_POST['arrayput']."' WHERE `section` = '".$section."'";

	$result = $roster->db->query($query);

	if (!$result)
	{
		$body .= messagebox('Failed to update '.$section.' menu configuration due to a database error. MySQL said: <br />'.$roster->db->error(),'WoW Roster','sred');
	}

	if ($roster->db->affected_rows()>0) // the config row was actually changed
	{
		$save_status = '<span style="color:#0099FF;font-size:11px;">Changes to '.$section.' saved</span>';
	}
	else
	{
		$save_status = '<span style="color:#0099FF;font-size:11px;">No changes saved</span>';
	}
}

// --[ Fetch button list from DB ]--
$query = "SELECT `mb`.*, `a`.`basename`
	FROM `".$roster->db->table('menu_button')."` AS mb
	LEFT JOIN `".$roster->db->table('addon')."` AS a
	ON `mb`.`addon_id` = `a`.`addon_id`;";

$result = $roster->db->query($query);

if (!$result)
{
	die_quietly('Could not fetch buttons from database. MySQL said: <br />'.$roster->db->error(),'Roster');
}

$palet = array();
$dhtml_reg = '';
while ($row = $roster->db->fetch($result))
{
	$palet['b'.$row['button_id']] = $row;
	$dhtml_reg .= ', "b'.$row['button_id'].'"';
}

$roster->db->free_result($result);

// --[ Fetch menu configuration from DB ]--
$query = "SELECT * FROM ".$roster->db->table('menu')." WHERE `section` = '".$section."'";

$result = $roster->db->query($query);

if (!$result)
{
	die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$roster->db->error(),'Roster');
}

if ($roster->db->num_rows($result))
{
	$row = $roster->db->fetch($result);

	$roster->db->free_result($result);
}
else
{
	$row = array('config'=>'');
}

// --[ Distribute buttons between the button list and the unused buttons panel ]--
$arrayHeight = 0;
$arrayButtons = array();
foreach(explode(':',$row['config']) as $pos=>$button)
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
$roster->output['html_head'] .= '  <script type="text/javascript" src="'.ROSTER_PATH.'css/js/wz_dragdrop.js"></script>'."\n";
$roster->output['html_head'] .= '  <script type="text/javascript" src="'.ROSTER_PATH.'css/js/menuconf.js"></script>'."\n";

// --[ Section select. ]--
$menu .= border('sorange','start',$roster->locale->act['menuconf_sectionselect'])."\n";
$menu .= '<form action="'.makelink().'" method="get">'."\n";
$menu .= linkform();
$menu .= '<select name="section">'."\n";

$query = "SELECT `section` FROM ".$roster->db->table('menu').";";
$result = $roster->db->query($query);

if (!$result)
{
	die_quietly('Could not fetch section list from database for the selection dialog. MySQL said: <br />'.$roster->db->error(),'WoW Roster',__FILE__,__LINE__,$query);
}

while ($row = $roster->db->fetch($result))
{
	if ($row['section'] == $section)
	{
		$menu .= '<option value="'.$row['section'].'" selected="selected">-'.$row['section'].'-</option>'."\n";
	}
	else
	{
		$menu .= '<option value="'.$row['section'].'">'.$row['section'].'</option>'."\n";
	}
}

$roster->db->free_result($result);

$menu .= '</select>&nbsp;'."\n";


$menu .= '<input type="submit" value="Go" />'."\n";
$menu .= '</form>'."\n";
$menu .= border('sorange','end')."\n";

// --[ Add button ]--
$menu .= "<br />\n";
$menu .= border('syellow','start','Add button')."\n";
$menu .= '<table cellspacing="0" cellpadding="0" border="0">';
$menu .= '<tr><td>title:</td><td><input id="title" type="text" size="16" maxlength="32" /></td></tr>'."\n";
$menu .= '<tr><td>url:  </td><td><input id="url"   type="text" size="16" maxlength="128"/></td></tr>'."\n";
$menu .= '<tr><td>icon: </td><td><input id="icon"  type="text" size="16" maxlength="64" /></td></tr>'."\n";
$menu .= '<tr><td colspan="2" align="right"><button onclick="sendAddElement()">Go</button></td></tr>'."\n";
$menu .= '</table>';
$menu .= border('syellow','end')."\n";

// --[ Delete box ]--
$menu .= "<br/>\n";
$menu .= border('sred','start','Drag here to delete');
$menu .= '<div id="rec_bin" style="width:135px;height:65px;background-color:black;"></div>'."\n";
$menu .= border('sred','end');

// --[ Main grid design ]--
$body .= isset($save_status)?$save_status:'';
$body .= '<form action="'.makelink('&amp;section='.$section).'" method="post" onsubmit="return confirm(\''.$roster->locale->act['confirm_config_submit'].'\') &amp;&amp; writeValue() &amp;&amp; submitonce(this);">'."\n";
$body .= '<input type="hidden" name="arrayput" id="arrayput" /><input type="hidden" name="section" value="'.$section.'" /><input type="hidden" name="process" value="process" />';
$body .= '<input type="submit" value="'.$roster->locale->act['config_submit_button'].'" />'."\n";
$body .= '</form><br />'."\n";

$body .= border('sgreen','start',$section);
$body .= '<div id="array" style="width:'.(40*$arrayWidth+5).'px;height:'.(40*$arrayHeight+5).'px;"></div>'."\n";
$body .= border('sgreen','end',$section);

foreach($arrayButtons as $pos=>$button)
{
	// Save current locale array
	// Since we add all locales for button name localization, we save the current locale array
	// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
	$localestore = $roster->locale->wordings;

	if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
	{
		// Include addon's locale files if they exist
		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang);
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
			$button['icon'] = $roster->config['interface_url'].'Interface/Icons/' . $button['icon'] . '.' . $roster->config['img_suffix'];
		}
	}
	else
	{
		$button['icon'] = $roster->config['interface_url'].'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
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
		. '<span style="font-size:10px;">scope: <span style="color:#FF3300;">' . $button['scope'] . '</span></span><br />'
		. '<span style="font-size:10px;">addon basename: <span style="color:#FF3300;">' . $button['basename'] . '</span></span><br />'
		. '<span style="font-size:10px;">url: <span style="color:#FF3300;">' . $button['url'] . '</span></span><br />'
		. '<span style="font-size:10px;">title key: <span style="color:#0099FF;">' . $button['titkey'] . '</span></span>';

	$button['tooltip'] = ' '.makeOverlib($button['tooltip'],$button['title'],'',2,'');

	$body .= '<div id="b' . $button['button_id'] . '" style="background-image:url(' . $button['icon'] . '); background-position:center; background-repeat:no-repeat;" class="menu_config_div"'.$button['tooltip'].'></div>' . "\n";

	// Restore our locale array
	$roster->locale->wordings = $localestore;
	unset($localestore);
}

// --[ Button palet ]--
$body .= '<br />'."\n";
$body .= messagebox('<div id="palet" style="width:'.(40*$paletWidth+5).'px;height:'.(40*$paletHeight+5).'px;"></div>','Unused buttons','sblue');
foreach($palet as $id=>$button)
{
	// Save current locale array
	// Since we add all locales for button name localization, we save the current locale array
	// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
	$localestore = $roster->locale->wordings;

	if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
	{
		// Include addon's locale files if they exist
		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang);
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
			$button['icon'] = $roster->config['interface_url'].'Interface/Icons/' . $button['icon'] . '.' . $roster->config['img_suffix'];
		}
	}
	else
	{
		$button['icon'] = $roster->config['interface_url'].'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
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
		. '<span style="font-size:10px;">scope: <span style="color:#FF3300;">' . $button['scope'] . '</span></span><br />'
		. '<span style="font-size:10px;">addon basename: <span style="color:#FF3300;">' . $button['basename'] . '</span></span><br />'
		. '<span style="font-size:10px;">url: <span style="color:#FF3300;">' . $button['url'] . '</span></span><br />'
		. '<span style="font-size:10px;">title key: <span style="color:#0099FF;">' . $button['titkey'] . '</span></span>';

	$button['tooltip'] = ' '.makeOverlib($button['tooltip'],$button['title'],'',2,'');

	$body .= '<div id="b' . $button['button_id'] . '" style="background-image:url(' . $button['icon'] . '); background-position:center; background-repeat:no-repeat;" class="menu_config_div"'.$button['tooltip'].'></div>' . "\n";

	// Restore our locale array
	$roster->locale->wordings = $localestore;
	unset($localestore);
}

// --[ Javascript defines and variable passing ]--
$footer .=
'<script type="text/javascript">
<!--

SET_DHTML(CURSOR_MOVE, TRANSPARENT, SCROLL'.$dhtml_reg.', "palet"+NO_DRAG, "array"+NO_DRAG, "rec_bin"+NO_DRAG);

var roster_url	= \''.ROSTER_URL.'\';

var dy		= 40;
var margTop	= 5;
var dx		= 40;
var margLef	= 5;

var arrayWidth = '.$arrayWidth.';
var arrayHeight = '.$arrayHeight.';
var paletHeight = '.$paletHeight.';

// Array intended to reflect the order of the draggable items
var aElts = Array();
var palet = Array();'."\n";

$i = 0;

foreach ($palet as $id => $button)
{
	$footer .= 'palet['.$i++.'] = dd.elements.'.$id.';';
}

foreach ($arrayButtons as $pos => $button)
{
	$footer .= 'aElts['.$pos.'] = dd.elements.b'.$button['button_id'].';'."\n";
}

$footer .= '
updatePositions();
//-->
</script>';
