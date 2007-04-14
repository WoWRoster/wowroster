<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// --[ Translate GET data ]--
$section = (isset($_GET['section']) ? $_GET['section'] : 'main' );

// --[ Write submitted menu configuration to DB if applicable ]--
if (isset($_POST['process']) && $_POST['process'] == 'process')
{
	$query = "UPDATE `".$wowdb->table('menu')."` SET `config` = '".$_POST['arrayput']."' WHERE `section` = '".$section."'";

	$result = $wowdb->query($query);

	if (!$result)
	{
		$body .= messagebox('Failed to update '.$section.' menu configuration due to a database error. MySQL said: <br />'.$wowdb->error(),'WoW Roster','sred');
	}

	if ($wowdb->affected_rows()>0) // the config row was actually changed
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
	FROM `".$wowdb->table('menu_button')."` AS mb
	LEFT JOIN `".$wowdb->table('addon')."` AS a
	ON `mb`.`addon_id` = `a`.`addon_id`;";

$result = $wowdb->query($query);

if (!$result)
{
	die_quietly('Could not fetch buttons from database. MySQL said: <br />'.$wowdb->error(),'Roster');
}

$palet = array();
$dhtml_reg = '';
while ($row = $wowdb->fetch_assoc($result))
{
	$palet['b'.$row['button_id']] = $row;
	$dhtml_reg .= ', "b'.$row['button_id'].'"';
}

$wowdb->free_result($result);

// --[ Fetch menu configuration from DB ]--
$query = "SELECT * FROM ".$wowdb->table('menu')." WHERE `section` = '".$section."'";

$result = $wowdb->query($query);

if (!$result)
{
	die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$wowdb->error(),'Roster');
}

if ($wowdb->num_rows($result))
{
	$row = $wowdb->fetch_assoc($result);

	$wowdb->free_result($result);
}
else
{
	$row = array('config'=>'');
}

// --[ Distribute buttons between the button list and the unused buttons panel ]--
$arrayHeight = 0;
$arrayButtons = array();
foreach(explode('|',$row['config']) AS $posX=>$column)
{
	$arrayButtons[$posX] = array();
	foreach(explode(':',$column) as $posY=>$button)
	{
		if( isset($palet[$button]) )
		{
			$config[$posX][] = $button;
			$arrayButtons[$posX][] = $palet[$button];
			unset($palet[$button]);
		}
	}
	$arrayHeight = max($arrayHeight, count($arrayButtons[$posX]));
}
$arrayWidth = count($arrayButtons);

$paletHeight = count($palet);
$paletWidth = 1;

// --[ Render configuration screen. ]--
if (!isset($html_head))
{
	$html_head = '';
}
$html_head .= '  <script type="text/javascript" src="'.ROSTER_PATH.'css/js/wz_dragdrop.js"></script>'."\n";
$html_head .= '  <script type="text/javascript" src="'.ROSTER_PATH.'css/js/menuconf.js"></script>'."\n";

// --[ Section select. ]--
$menu .= border('sorange','start',$act_words['menuconf_sectionselect'])."\n";
$menu .= '<form action="'.makelink().'" method="post">'."\n";
$menu .= '<select name="section">'."\n";

$query = "SELECT `section` FROM ".$wowdb->table('menu').";";
$result = $wowdb->query($query);

if (!$result)
{
	die_quietly('Could not fetch section list from database for the selection dialog. MySQL said: <br />'.$wowdb->error(),'WoW Roster',basename(__FILE__),__LINE__,$query);
}

while ($row = $wowdb->fetch_assoc($result))
{
	if ($row['section'] == $section)
	{
		$menu .= '<option value="'.$row['section'].'">-'.$row['section'].'-</option>'."\n";
	}
	else
	{
		$menu .= '<option value="'.$row['section'].'">'.$row['section'].'</option>'."\n";
	}
}

$wowdb->free_result($result);

$menu .= '</select>&nbsp;'."\n";


$menu .= '<input type="submit" value="Go" />'."\n";
$menu .= '</form>'."\n";
$menu .= border('sorange','end')."\n";

$menu .= '<br />'."\n";

// --[ Button palet ]--
$menu .= messagebox('<div id="palet" style="width:'.(105*$paletWidth+5).'px;height:'.(30*$paletHeight+5).'px;"></div>','Unused buttons','sblue');
foreach($palet as $id=>$button)
{
	if( $button['addon_id'] != '0' && !isset($act_words[$button['title']]) )
	{
		// Include addon's locale files if they exist
		foreach( $roster_conf['multilanguages'] as $lang )
		{
			if( file_exists(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php') )
			{
				add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang,$wordings);
			}
		}
	}
	$menu .= '<div id="'.$id.'" class="menu_config_div">'.( isset($act_words[$button['title']]) ? $act_words[$button['title']] : $button['title'] ).(!empty($button['basename']) ? ' (addon)' : '').' ['.$button['title'].']</div>'."\n";
}
$menu .= "<br />\n";

// --[ Add button ]--
$menu .= border('syellow','start','Add button')."\n";
$menu .= '<table cellspacing="0" cellpadding="0" border="0">';
$menu .= '<tr><td>title:<td><input id="title" type="text" size="16" maxlength="32" />'."\n";
$menu .= '<tr><td>url:  <td><input id="url"   type="text" size="16" maxlength="128" />'."\n";
//$menu .= '<tr><td>show: <td>'.$roster_login->accessConfig(array('name'=>'access','value'=>$roster_login->everyone()))."\n";
$menu .= '<tr><td colspan="2" align="right"><button onclick="sendAddElement()">Go</button>'."\n";
$menu .= '</table>';
$menu .= border('syellow','end')."\n";

// --[ Main grid design ]--
$body .= isset($save_status)?$save_status:'';
$body .= '<form action="'.makelink().'" method="post" onsubmit="return confirm(\''.$act_words['confirm_config_submit'].'\') && writeValue() && submitonce(this);">'."\n";
$body .= '<input type="hidden" name="arrayput" id="arrayput" /><input type="hidden" name="section" value="'.$section.'" /><input type="hidden" name="process" value="process" />';
$body .= '<input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['config_submit_button'].'" />'."\n";
$body .= '</form><br />'."\n";

$body .= border('sgreen','start',$section);
$body .= '<div id="array" style="width:'.(105*$arrayWidth+10).'px;height:'.(30*$arrayHeight+5).'px;"></div>'."\n";
$body .= border('sgreen','end',$section);

foreach($arrayButtons as $posX=>$column)
{
	foreach($column as $posY=>$button)
	{
		if( $button['addon_id'] != '0' && !isset($act_words[$button['title']]) )
		{
			// Include addon's locale files if they exist
			foreach( $roster_conf['multilanguages'] as $lang )
			{
				if( file_exists(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php') )
				{
					add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang,$wordings);
				}
			}
		}
		$body .= '<div id="b'.$button['button_id'].'" class="menu_config_div">'.( isset($act_words[$button['title']]) ? $act_words[$button['title']] : $button['title'] ).(!empty($button['basename']) ? ' (addon)' : '').' ['.$button['title'].']</div>'."\n";
	}
}

// --[ Delete box ]--
$body .= "<br/>\n";
$body .= border('sred','start','Drag here to delete');
$body .= '<div id="rec_bin" style="width:215px;height:65px;background-color:black;"></div>'."\n";
$body .= border('sred','end');

// --[ Javascript defines and variable passing ]--
$footer .=
'<script type="text/javascript">
<!--

SET_DHTML(CURSOR_MOVE'.$dhtml_reg.', "palet"+NO_DRAG, "array"+NO_DRAG, "rec_bin"+NO_DRAG);

var roster_url	= \''.ROSTER_URL.'\';

var dy		= 30;
var margTop	= 5;
var dx		= 105;
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

foreach ($arrayButtons as $posX => $column)
{
	$footer .= 'aElts['.$posX.'] = Array();'."\n";
	foreach ($column as $posY => $button)
	{
		$footer .= 'aElts['.$posX.']['.$posY.'] = dd.elements.b'.$button['button_id'].';'."\n";
	}
}

$footer .= '
updatePositions();
//-->
</script>';
