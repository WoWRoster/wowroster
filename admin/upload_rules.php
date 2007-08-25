<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster upload rule config
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: change_pass.php 913 2007-05-08 03:51:55Z Zanix $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_uploadrules'];

$mode = (isset($roster->pages[2]) && $roster->pages[2] == 'char')?'char':'guild';

// Process a new line
if( isset($_POST['process']) && $_POST['process'] == 'process')
{
	if( $_POST['action'] == 'add' )
	{
		$type = ($mode == 'guild'?0:2) + ($_POST['block'] == 'allow'?0:1);

		if( !empty($_POST['value']) || !empty($_POST['server']) || !empty($_POST['region']) )
		{
			$default = ( (isset($_POST['defaultchk']) && $_POST['defaultchk'] == '1') ? '1' : '0' );

			if( $default == '1' )
			{
				$query = "UPDATE `" . $roster->db->table('upload') . "` SET `default` = '0';";

				if( !$roster->db->query($query) )
				{
					die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
				}
			}

			$query = "INSERT INTO `" . $roster->db->table('upload') . "`
					(`name`,`server`,`region`,`type`,`default`) VALUES
						('" . $_POST['value'] . "','" . $_POST['server'] . "','" . strtoupper($_POST['region']) . "','" . $type . "','" . $default . "');";

			if( !$roster->db->query($query) )
			{
				die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
			}
		}
		else
		{
			$body .= messagebox($roster->locale->act['upload_rules_error'],'','sred') . "<br />\n";
		}
	}
	elseif( substr($_POST['action'],0,4) == 'del_' )
	{
		$rule_id = substr($_POST['action'],4);

		$query = "DELETE FROM `" . $roster->db->table('upload') . "` WHERE `rule_id` = '" . $rule_id . "' LIMIT 1;";

		if( !$roster->db->query($query) )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}
	}
	elseif( substr($_POST['action'],0,8) == 'default_' )
	{
		$rule_id = substr($_POST['action'],8);

		$query = "UPDATE `" . $roster->db->table('upload') . "` SET `default` = '0';";

		if( !$roster->db->query($query) )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}

		$query = "UPDATE `" . $roster->db->table('upload') . "` SET `default` = '1' WHERE `rule_id` = '" . $rule_id . "' LIMIT 1;";

		if( !$roster->db->query($query) )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}
	}
}

// Fetch data
$query = "SELECT * FROM `" . $roster->db->table('upload') . "`";

if( $mode == 'guild' )
{
	$query .= " WHERE `type` = '0' OR `type` = '1';";
}
else
{
	$query .= " WHERE `type` = '2' OR `type` = '3';";
}
$result = $roster->db->query($query);

if( !$result )
{
	die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
}

$data = array('allow'=>array(),'deny'=>array());

while( $row = $roster->db->fetch($result) )
{
	if( $row['type'] & 1 == 1 )
	{
		$data['deny'][] = $row;
	}
	else
	{
		$data['allow'][] = $row;
	}
}

// OUTPUT
$menu .= border('syellow','start','Menu') . "\n"
		. '<div style="width:145px;">' . "\n"
		. '	<ul class="tab_menu">' . "\n"
		. '		<li' . ($mode=='guild'?' class="selected"':'') . '><a href="' . makelink($roster->pages[0] . '-' . $roster->pages[1] . '-guild') . '">' . $roster->locale->act['guild'] . '</a></li>' . "\n"
		. '		<li' . ($mode=='char'?' class="selected"':'') . '><a href="' . makelink($roster->pages[0] . '-' . $roster->pages[1] . '-char') . '">' . $roster->locale->act['character'] . '</a></li>' . "\n"
		. "	</ul>\n"
		. "</div>\n"
		. border('syellow','end');

$roster->output['body_onload'] .= 'initARC(\'allow\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$body .= '<form action="' . makelink() . '" method="post" id="deny">
<input type="hidden" id="denyhide" name="action" value="" />
<input type="hidden" name="process" value="process" />
<input type="hidden" name="block" value="disallow" />';

$body .= ruletable_head('sred',$roster->locale->act['disallow'],'deny',$mode);

foreach( $data['deny'] as $row )
{
	$body .= ruletable_line($row,'deny',$mode);
}
$body .= ruletable_foot('sred','deny',$mode);

$body .= '</form>';


$body .= "<br />\n";


$body .= '<form action="' . makelink() . '" method="post" id="allow">
<input type="hidden" id="allowhide" name="action" value="" />
<input type="hidden" name="process" value="process" />
<input type="hidden" name="block" value="allow" />';

$body .= ruletable_head('sgreen',$roster->locale->act['allow'],'allow',$mode);

foreach( $data['allow'] as $row )
{
	$body .= ruletable_line($row,'allow',$mode);
}
$body .= ruletable_foot('sgreen','allow',$mode);

$body .= '</form>';

$body .= "<br />\n";
$body .= messagebox($roster->locale->act['upload_rules_help'],'<img src="' . $roster->config['img_url'] . 'blue-question-mark.gif" alt="?" style="float:right;" />' . $roster->locale->act['pagebar_uploadrules'],'sgray');


function ruletable_head( $style , $title , $type , $mode )
{
	global $roster;

	$output = border($style,'start',$title) . '
<table class="bodyline" cellspacing="0">
	<thead>
		<tr>
';
	if( $mode == 'guild' && $type != 'deny' )
	{
		$output .= '			<th class="membersHeader">' . $roster->locale->act['default'] . '</th>';
	}

	if( $mode == 'guild' )
	{
		$name = $roster->locale->act['guildname'];
	}
	else
	{
		$name = $roster->locale->act['charname'];
	}

	$output .= '
			<th class="membersHeader" ' . makeOverlib($name) . '> ' . $roster->locale->act['name'] . '</th>
			<th class="membersHeader" ' . makeOverlib($roster->locale->act['realmname']) . '> ' . $roster->locale->act['server'] . '</th>
			<th class="membersHeader" ' . makeOverlib($roster->locale->act['regionname']) . '> ' . $roster->locale->act['region'] . '</th>
			<th class="membersHeaderRight">&nbsp;</th>
		</tr>
	</thead>
	<tbody>' . "\n";
	return $output;
}

function ruletable_line( $row , $type , $mode )
{
	global $roster;

	$output = "\n\t\t<tr>\n";

	if( $mode == 'guild' && $type != 'deny' )
	{
		if( $row['default'] == '1' )
		{
			$output .= '			<td class="membersRow1" style="text-align:center;"><img src="' . $roster->config['img_url'] . 'check_on.png" alt="" /></td>';
		}
		else
		{
			$output .= '			<td class="membersRow1" style="text-align:center;"><button class="button_hide" onclick="setvalue(\'' . $type . 'hide\',\'default_' . $row['rule_id'] . '\');"><img src="' . $roster->config['img_url'] . 'check_off.png" alt="" /></button></td>';
		}
	}
	$output .= '
			<td class="membersRow1">' . $row['name'] . '</td>
			<td class="membersRow1">' . $row['server'] . '</td>
			<td class="membersRow1">' . $row['region'] . '</td>
			<td class="membersRowRight1"><button type="submit" class="input" onclick="setvalue(\'' . $type . 'hide\',\'del_' . $row['rule_id'] . '\');">' . $roster->locale->act['delete'] . '</button></td>
		</tr>' . "\n";
	return $output;
}

function ruletable_foot( $style , $type , $mode )
{
	global $roster;

	$output = "\n\t\t<tr>\n";

	if( $mode == 'guild' && $type != 'deny' )
	{
		$output .= '			<td class="membersRow2" style="text-align:center;"><label for="defaultchk">&nbsp;</label><input type="checkbox" name="defaultchk" id="defaultchk" value="1" /></td>';
	}
	$output .= '
			<td class="membersRow2"><input class="wowinput128" type="text" name="value" value="" /></td>
			<td class="membersRow2"><input class="wowinput128" type="text" name="server" value="" /></td>
			<td class="membersRow2"><input class="wowinput64" type="text" name="region" value="" /></td>
			<td class="membersRowRight2"><button type="submit" class="input" onclick="setvalue(\'' . $type . 'hide\',\'add\');">' . $roster->locale->act['add'] . '</button></td>
		</tr>
	</tbody>
</table>
' . border($style,'end');
	return $output;
}
