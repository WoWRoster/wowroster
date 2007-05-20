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

if( !defined('ROSTER_INSTALLED') )
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
					('" . $_POST['name'] . "','" . $_POST['server'] . "','" . strtolower($_POST['region']) . "','" . $type . "','" . $default . "');";

		if( !$roster->db->query($query) )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
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

$body .= messageboxtoggle($roster->locale->act['upload_rules_help'],$roster->locale->act['pagebar_uploadrules'],'sgray');

$body .= "<br />\n";

$body .= '<form action="' . makelink() . '" method="post" id="deny">';
$body .= '<input type="hidden" name="process" value="process" />';
$body .= '<input type="hidden" name="block" value="disallow" />';

$body .= ruletable_head('sred',$roster->locale->act['disallow'],'deny');

foreach( $data['deny'] as $row )
{
	$body .= ruletable_line($row,'deny');
}
$body .= ruletable_foot('sred','deny');

$body .= '</form>';


$body .= "<br />\n";


$body .= '<form action="' . makelink() . '" method="post" id="allow">';
$body .= '<input type="hidden" name="process" value="process" />';
$body .= '<input type="hidden" name="block" value="allow" />';

$body .= ruletable_head('sgreen',$roster->locale->act['allow'],'allow');

foreach( $data['allow'] as $row )
{
	$body .= ruletable_line($row,'allow');
}
$body .= ruletable_foot('sgreen','allow');

$body .= '</form>';



function ruletable_head( $style , $title , $type )
{
	global $roster, $mode;

	$output = border($style,'start',$title) . '
<table class="bodyline" cellspacing="0">
	<thead>
		<tr>
';
	if( $mode == 'guild' && $type != 'deny' )
	{
		$output .= '			<th class="membersHeader">' . $roster->locale->act['default'] . '</th>';
	}
	$output .= '
			<th class="membersHeader">' . $roster->locale->act['name'] . '</th>
			<th class="membersHeader">' . $roster->locale->act['server'] . '</th>
			<th class="membersHeader">' . $roster->locale->act['region'] . '</th>
			<th class="membersHeaderRight">&nbsp;</th>
		</tr>
	</thead>
	<tbody>' . "\n";
	return $output;
}

function ruletable_line( $row , $type )
{
	global $roster, $mode;

	$output = '
		<tr>
';
	if( $mode == 'guild' && $type != 'deny' )
	{
		if( $row['default'] == '1' )
		{
			$output .= '			<td class="membersRowCell" style="text-align:center;"><img src="' . $roster->config['img_url'] . 'check_on.png" alt="" /></td>';
		}
		else
		{
			$output .= '			<td class="membersRowCell" style="text-align:center;"><button class="button_hide" style="cursor:pointer;" name="action" value="default_' . $row['rule_id'] . '"><img src="' . $roster->config['img_url'] . 'check_off.png" alt="" /></button></td>';
		}
	}
	$output .= '
			<td class="membersRowCell">' . $row['name'] . '</td>
			<td class="membersRowCell">' . $row['server'] . '</td>
			<td class="membersRowCell">' . $row['region'] . '</td>
			<td class="membersRowRightCell"><button class="input" name="action" value="del_' . $row['rule_id'] . '">' . $roster->locale->act['delete'] . '</button></td>
		</tr>' . "\n";
	return $output;
}

function ruletable_foot( $style , $type )
{
	global $roster, $mode;

	$output = '
		<tr>
';
	if( $mode == 'guild' && $type != 'deny' )
	{
		$output .= '			<td class="membersRowCell" style="text-align:center;"><label for="defaultchk">&nbsp;</label><input type="checkbox" name="defaultchk" id="defaultchk" value="1" /></td>';
	}
	$output .= '
			<td class="membersRowCell"><input class="wowinput128" type="text" name="name" value="" /></td>
			<td class="membersRowCell"><input class="wowinput128" type="text" name="server" value="" /></td>
			<td class="membersRowCell"><input class="wowinput64" type="text" name="region" value="" /></td>
			<td class="membersRowRightCell"><button class="input" name="action" value="add">' . $roster->locale->act['add'] . '</button></td>
		</tr>
	</tbody>
</table>
' . border($style,'end');
	return $output;
}
