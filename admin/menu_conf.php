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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_menuconf'];

// --[ Translate GET data ]--
$section = (isset($_GET['section']) ? $_GET['section'] : 'main' );

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
$roster->output['html_head'] .= '  <script type="text/javascript" src="'.ROSTER_PATH.'css/js/wz_dragdrop.js"></script>'."\n";
$roster->output['html_head'] .= '  <script type="text/javascript" src="'.ROSTER_PATH.'css/js/menuconf.js"></script>'."\n";

// --[ Section select. ]--
$menu .= border('sorange','start',$roster->locale->act['menuconf_sectionselect'])."\n";
$menu .= '<form action="'.makelink().'" method="post">'."\n";
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
		$menu .= '<option value="'.$row['section'].'">-'.$row['section'].'-</option>'."\n";
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

$menu .= '<br />'."\n";

// --[ Button palet ]--
$menu .= messagebox('<div id="palet" style="width:'.(125*$paletWidth+5).'px;height:'.(40*$paletHeight+5).'px;"></div>','Unused buttons','sblue');
foreach($palet as $id=>$button)
{
	if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
	{
		// Include addon's locale files if they exist
		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang);
		}
	}

	$button['icon'] = '<img src="'.$roster->config['interface_url'].'Interface/Icons/'.(empty($button['icon'])?'inv_misc_questionmark':$button['icon']).'.'.$roster->config['img_suffix'].'" alt=""/>';
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
	
	$button['tooltip'] .= '<br /><br /><span style="color:#FFFFFF;font-size:10px;">db name: <span style="color:#0099FF;">'.$button['titkey'].'</span></span>';

	$button['tooltip'] = ' '.makeOverlib($button['tooltip'],$button['title'],'',2,'',',WRAP');
	
	$menu .= '<div id="b' . $button['button_id'] . '" class="menu_config_div"'.$button['tooltip'].'>' . $button['icon'] . $button['title'] . '</div>' . "\n";
}
$menu .= "<br />\n";

// --[ Add button ]--
$menu .= border('syellow','start','Add button')."\n";
$menu .= '<table cellspacing="0" cellpadding="0" border="0">';
$menu .= '<tr><td>title:</td><td><input id="title" type="text" size="16" maxlength="32" /></td></tr>'."\n";
$menu .= '<tr><td>url:  </td><td><input id="url"   type="text" size="16" maxlength="128"/></td></tr>'."\n";
$menu .= '<tr><td>icon: </td><td><input id="icon"  type="text" size="16" maxlength="64" /></td></tr>'."\n";
$menu .= '<tr><td colspan="2" align="right"><button onclick="sendAddElement()">Go</button></td></tr>'."\n";
$menu .= '</table>';
$menu .= border('syellow','end')."\n";

// --[ Main grid design ]--
$body .= isset($save_status)?$save_status:'';
$body .= '<form action="'.makelink().'" method="post" onsubmit="return confirm(\''.$roster->locale->act['confirm_config_submit'].'\') &amp;&amp; writeValue() &amp;&amp; submitonce(this);">'."\n";
$body .= '<input type="hidden" name="arrayput" id="arrayput" /><input type="hidden" name="section" value="'.$section.'" /><input type="hidden" name="process" value="process" />';
$body .= '<input type="submit" value="'.$roster->locale->act['config_submit_button'].'" />'."\n";
$body .= '</form><br />'."\n";

$body .= border('sgreen','start',$section);
$body .= '<div id="array" style="width:'.(125*$arrayWidth+10).'px;height:'.(40*$arrayHeight+5).'px;"></div>'."\n";
$body .= border('sgreen','end',$section);

foreach($arrayButtons as $posX=>$column)
{
	foreach($column as $posY=>$button)
	{
		if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
		{
			// Include addon's locale files if they exist
			foreach( $roster->multilanguages as $lang )
			{
				$roster->locale->add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang);
			}
		}

		$button['icon'] = '<img src="'.$roster->config['interface_url'].'Interface/Icons/'.(empty($button['icon'])?'inv_misc_questionmark':$button['icon']).'.'.$roster->config['img_suffix'].'" alt=""/>';
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
		
		$button['tooltip'] .= '<br /><br /><span style="color:#FFFFFF;font-size:10px;">db name: <span style="color:#0099FF;">'.$button['titkey'].'</span></span>';

		$button['tooltip'] = ' '.makeOverlib($button['tooltip'],$button['title'],'',2,'',',WRAP');
		
		$body .= '<div id="b' . $button['button_id'] . '" class="menu_config_div"'.$button['tooltip'].'>' . $button['icon'] . $button['title'] . '</div>' . "\n";
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

var dy		= 40;
var margTop	= 5;
var dx		= 125;
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
