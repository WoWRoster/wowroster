<?php
/**
 * Dev.PKComp.net WoWRoster Addon
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2005-2007 Pretty Kitty Development
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @link       http://dev.pkcomp.net
 * @package    user
 * @subpackage Profile Admin
 */

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( isset($_POST['process']) && $_POST['process'] != '' )
{
	$roster_config_message = processData();
}

global $roster, $user, $addon;

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$listing = $next = $prev = '';

$uid = $roster->auth->uid;

$jscript = '
$(function() {
  var cds_menu = new tabcontent("cds_menu");
  cds_menu.init();
});';

roster_add_js($jscript, 'inline', 'footer');

/**
 * Actual list
 */
$query = "SELECT "
	. " COUNT( `member_id` )"
	. " FROM `" . $roster->db->table('members') . "`"
	. " WHERE `account_id` = " . $uid . ";";

$num_members = $roster->db->query_first($query);

$guildQuery = "SELECT "
	. " `guild_id` "
	. " FROM `" . $roster->db->table('members') . "`"
	. " WHERE `account_id` = " . $uid . ";";

$guild_id = $roster->db->query_first($query);

if( $num_members > 0 )
{
	$i=1;
	$display = array(
		'show_money'        => array( 'disc' => "Money", 'config' => ''),
		'show_played'       => array( 'disc' => "Time Played", 'config' => ''),
		'show_pets'         => array( 'disc' => "Pets", 'config' => ''),
		'show_reputation'   => array( 'disc' => "Reputation", 'config' => ''),
		'show_skills'       => array( 'disc' => "Skills", 'config' => ''),
		'show_honor'        => array( 'disc' => "PvP", 'config' => ''),
		'show_talents'      => array( 'disc' => "Talents", 'config' => ''),
		'show_spellbook'    => array( 'disc' => "Spellbook", 'config' => ''),
		'show_mail'         => array( 'disc' => "Mail", 'config' => ''),
		'show_bags'         => array( 'disc' => "Bags", 'config' => ''),
		'show_bank'         => array( 'disc' => "Bank", 'config' => ''),
		'show_recipes'      => array( 'disc' => "Recipes", 'config' => ''),
		'show_quests'       => array( 'disc' => "Quests", 'config' => ''),
		'show_item_bonuses' => array( 'disc' => "Item Bonuses", 'config' => '')
	);

	$query = 'SELECT '.
		'`user`.`id`, '.	'`members`.`member_id`, '.	'`members`.`account_id`, '.	'`player`.`member_id`, '.	'`members`.`name`, '.
		'`player`.`race`, '.
		'`player`.`sex`, '.
		'`player`.`level`, '.
		'`player`.`class`, '.
		'`display`.`member_id`, '.
		'`display`.`show_money`, '.
		'`display`.`show_played`, '.
		'`display`.`show_pets`, '.
		'`display`.`show_reputation`, '.
		'`display`.`show_skills`, '.
		'`display`.`show_honor`, '.
		'`display`.`show_talents`, '.
		'`display`.`show_spellbook`, '.
		'`display`.`show_mail`, '.
		'`display`.`show_bags`, '.
		'`display`.`show_bank`, '.
		'`display`.`show_quests`, '.
		'`display`.`show_recipes`, '.
		'`display`.`show_item_bonuses` '.
		'FROM `'.$roster->db->table('user_members').'` AS user '.
		'LEFT JOIN `'.$roster->db->table('members').'` AS members ON `user`.`id` = `members`.`account_id` '.
		'LEFT JOIN `'.$roster->db->table('players').'` AS player ON `members`.`member_id` = `player`.`member_id` '.
		'LEFT JOIN `'.$roster->db->table('display', 'info').'` AS display ON `members`.`member_id` = `display`.`member_id` '.
		'WHERE `user`.`id` = "' . $uid . '" '.
		'ORDER BY `members`.`name` ASC'.
		' LIMIT ' . ($start > 0 ? $start : 0) . ', 15;';

	$result = $roster->db->query($query);
	$k=0;
	while( $rw = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('characters', array(
			'CNAME'  => $rw['name'],
			'CCLASS' => $rw['class'],
			'ID' => $i,
			)
		);
		foreach($display as $dbv => $v)
		{
			$val_name = $dbv;
			if( substr( $val_name, 0, 5 ) != 'show_' )
			{
				continue;
			}
			$field = '';
			$field .= '<input type="radio" id="chard_f' . $k . '_' . $rw['member_id'] . '" name="disp_' . $rw['member_id'] . ':' . $val_name . '" value="1" ' . ( $rw[$dbv] == '1' ? 'checked="checked"' : '' ) . ' /><label for="chard_f' . $k . '_' . $rw['member_id'] . '">Off</label>';
			$field  .= '<input type="radio" id="chard_n' . $k . '_' . $rw['member_id'] . '" name="disp_' . $rw['member_id'] . ':' . $val_name . '" value="3" ' . ( $rw[$dbv] == '3' ? 'checked="checked"' : '' ) . ' /><label for="chard_n' . $k . '_' . $rw['member_id'] . '">On</label>';
			$field  .= '<input type="radio" id="chard_g' . $k . '_' . $rw['member_id'] . '" name="disp_' . $rw['member_id'] . ':' . $val_name . '" value="0" ' . ( $rw[$dbv] == '0' ? 'checked="checked"' : '' ) . ' /><label for="chard_g' . $k . '_' . $rw['member_id'] . '">Global';
			$k++;
			$roster->tpl->assign_block_vars('characters.cfg',array(
				'NAME'  => $v['disc'],
				'ID'    => $i,
				'FIELD' => infoAccess(array('name' => 'disp_' . $rw['member_id'] . ':' . $val_name . '', 'value' => $rw[$dbv])),
				// $roster->auth->rosterAccess(array('name' => 'disp_' . $rw['member_id'] . ':' . $val_name . '', 'value' => $rw[$dbv])),
				)
			);
		}
		$i++;
	}

}
else
{
	//$formbody = 'No Data';
}

$rw = $roster->db->fetch($result);
$k=0;

$tab1 = explode('|',$roster->locale->act['user_settings']['set']);
$tab2 = explode('|',$roster->locale->act['user_settings']['prof']);
$tab3 = explode('|',$roster->locale->act['user_main_menu']['my_prof']);
$tab4 = explode('|',$roster->locale->act['user_password']['settings_password']);

$menu = '
<ul class="tab_menu">
	<li class="selected"><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab1[1],$tab1[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings') . '">' . $tab1[0] . '</a></li>
	<li><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab2[1],$tab2[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-profile') . '">' . $tab2[0] . '</a></li>
	<li><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab3[1],$tab3[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-edit') . '">' . $tab3[0] . '</a></li>
	<li><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab4[1],$tab4[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-pass') . '">' . $tab4[0] . '</a></li>
</ul>';

$roster->tpl->set_filenames(array(
	'ucp2' => $addon['basename'] . '/ucp-settings.html'
	)
);


$roster->tpl->assign_vars(array(
	'ROSTERCP_TITLE' => (!empty($rostercp_title) ? $rostercp_title : $roster->locale->act['roster_cp_ab']),
	'MENU'           => $menu,
	'BODY'           => $roster->tpl->fetch('ucp2'),
	'PAGE_INFO'      => $roster->locale->act['user_cp'],
	)
);

$roster->tpl->set_filenames(array(
	'ucp' => $addon['basename'] . '/ucp.html'
	)
);
$roster->tpl->display('ucp');



function infoAccess($values)
{
	global $roster;

	if( count($roster->auth->levels) == 0 )
	{
		$roster->auth->rosterAccess(array('name'=>'','value'=>''));
		$roster->auth->levels[99] = 'None';
		ksort($roster->auth->levels);
	}

	return $roster->auth->rosterAccess($values);
}


/**
 * Process Data for entry to the database
 *
 * @return string Settings changed or not changed
 */
function processData( )
{
	global $roster;

	$update_sql = array();

	// Update only the changed fields
	foreach( $_POST as $settingName => $settingValue )
	{
		if( substr($settingName,0,12) == 'config_disp_' )
		{
			$settingName = str_replace('config_disp_','',$settingName);

			list($member_id,$settingName) = explode(':',$settingName);
			$member_id = str_replace('config_','',$member_id);
			$get_val = "SELECT `$settingName`"
					 . " FROM `" . $roster->db->table('display', 'info') . "`"
					 . " WHERE `member_id` = '$member_id';";

			$result = $roster->db->query($get_val) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$get_val);

			$config = $roster->db->fetch($result);

			if( $config[$settingName] != $settingValue && $settingName != 'process' )
			{
				$update_sql[] = "UPDATE `" . $roster->db->table('display', 'info') . "`"
							  . " SET `$settingName` = '" . implode(":",$settingValue ) . "'"
							  . " WHERE `member_id` = '$member_id';";
			}
		}
	}

	// Update DataBase
	if( !empty($update_sql) )
	{
		foreach( $update_sql as $sql )
		{
			$result = $roster->db->query($sql);
			if( !$result )
			{
				return '<span style="color:#0099FF;font-size:11px;">Error saving settings</span><br />MySQL Said:<br /><pre>' . $roster->db->error() . '</pre><br />';
			}
		}
		return '<span style="color:#0099FF;font-size:11px;">Settings have been changed</span>';
	}
	else
	{
		return '<span style="color:#0099FF;font-size:11px;">No changes have been made</span>';
	}
}
