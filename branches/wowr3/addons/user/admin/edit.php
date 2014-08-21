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
	processData();
}

global $roster, $user, $addon;

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$listing = $next = $prev = '';

if($roster->auth->uid > 0)
{
	$uid = $roster->auth->uid;
}
else
{
	$uid = '';
}

/**
 * Actual list
 */
$query = "SELECT "
	. " COUNT( `id` )"
	. " FROM `" . $roster->db->table('user_members') . "`"
	. " WHERE `id` = " . $uid . ";";

$num_members = $roster->db->query_first($query);

if( $num_members > 0 )
{
	$i=1;

	$query = 'SELECT `id`,`usr`,`fname`,`lname`,`age`,`city`,`state`,`country`,`homepage`,`other_guilds`,`why`,`about`,	`notes` FROM `'.$roster->db->table('user_members').'` WHERE `id` = "' . $uid . '" ;';

	$result = $roster->db->query($query);

	$data = $roster->db->fetch($result);
	$roster->tpl->assign_block_vars('profile', array(
			'CNAME'  => '<a href="' . makelink('user-user-profile-' . $data['usr']) . '" target="_blank">' . $data['usr'] . '</a>',
			'CUSR' => $data['usr'],
			'ID' => $i,
			)
		);

	foreach( $data as $val_name => $value )
	{
		if (! is_int ($val_name) && $val_name != 'usr' && $val_name != 'id' && $val_name != 'age')
		{
			$field = '<input class="box" type="text" name="disp_' . $data['id'] . ':' . $val_name . '" value="'.$value.'"/>';

			$roster->tpl->assign_block_vars('profile.cfg',array(
				'NAME'  => $roster->locale->act['user_profile_edit'][$val_name],
				'FIELD' => $field,
				)
			);
		}
	}

}
else
{
	$formbody = 'No Data';
}

$tab1 = explode('|',$roster->locale->act['user_settings']['set']);
$tab2 = explode('|',$roster->locale->act['user_settings']['prof']);
$tab3 = explode('|',$roster->locale->act['user_main_menu']['my_prof']);
$tab4 = explode('|',$roster->locale->act['user_password']['settings_password']);

$menu = '
<ul class="tab_menu">
	<li><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab1[1],$tab1[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings') . '">' . $tab1[0] . '</a></li>
	<li><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab2[1],$tab2[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-profile') . '">' . $tab2[0] . '</a></li>
	<li class="selected"><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab3[1],$tab3[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-edit') . '">' . $tab3[0] . '</a></li>
	<li><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab4[1],$tab4[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-pass') . '">' . $tab4[0] . '</a></li>
</ul>';

$roster->tpl->set_filenames(array(
	'ucp3' => $addon['basename'] . '/ucp-edit.html'
	)
);

$roster->tpl->assign_vars(array(
	'ROSTERCP_TITLE' => (!empty($rostercp_title) ? $rostercp_title : $roster->locale->act['roster_cp_ab']),
	'MENU'           => $menu,
	'BODY'           => $roster->tpl->fetch('ucp3'),
	'PAGE_INFO'      => $roster->locale->act['user_cp'],
	)
);
$roster->tpl->set_filenames(array(
	'ucp' => $addon['basename'] . '/ucp.html'
	)
);
$roster->tpl->display('ucp');



/**
 * Make select box of characters for main selection
 */
function selectMain($uid)
{
	global $roster, $addon, $user;
include_once( $addon['inc_dir'] . 'users.lib.php' );
	$user = new user();
	$query = "SELECT `users`.`id`, `member`.`name`,`member`.`member_id` FROM `".$roster->db->table('user_members')."` AS users LEFT JOIN `".$roster->db->table('members')."` AS member ON `users`.`id` = `member`.`account_id` WHERE `users`.`id` = ".$uid.";";
	$result = $roster->db->query($query);

	if( !$result )
	{
		die_quietly($roster->db->error, 'users Profile', __FILE__,__LINE__,$query);
	}

      $chars = '';
	while( $row = $roster->db->fetch($result) )
	{
		$chars[$row['member_id']] = $row['name'];
	}

	$input_field = '<select name="select_'.$uid.':is_main">' . "\n";
	$select_one = 1;
	if(is_array($chars) && count($chars) > 0)
	{
   foreach( $chars as $member => $name )
   {
   	     if( $member == getMain($uid) && $select_one )
    {
	   $input_field .= '  <option value="' . $member . '" selected="selected">' . $name . '</option>' . "\n";
	   $select_one = 0;
    }
    else
    {
	   $input_field .= '  <option value="' . $member . '">' . $name . '</option>' . "\n";
    }
   }
	}
      else
      {
            $input_field .= '  <option value="none" selected="selected">None</option>' . "\n";
            $select_one = 0;
      }
	$input_field .= '</select>';

	     return $input_field;
}


function getMain($uid)
{
	global $roster, $addon;

	$sql = 'SELECT * FROM `' . $roster->db->table('user_link', 'user') . '` WHERE `uid` = ' . $uid . ' AND `is_main` = 1';
	$query = $roster->db->query($sql);
	while($row = $roster->db->fetch($query))
	{
		if(!$query || $roster->db->num_rows($query) == 0)
		{
			return false;
		}
		$mid = $row['member_id'];
	}

	return $mid;
}


/**
 * Make select box of characters for main selection
 */
function selectGen($uid)
{
	global $roster, $addon, $user;

	$query = "SELECT `avsig_src` FROM `".$roster->db->table('profile','user')."` WHERE `uid` = ".$uid.";";
	$result = $roster->db->query($query);

	if( !$result )
	{
		die_quietly($roster->db->error, 'user Profile', __FILE__,__LINE__,$query);
	}

      $src = '';
	while( $row = $roster->db->fetch($result) )
	{
		$src = $row['avsig_src'];
	}

	$input_field = '<select name="select_'.$uid.':avsig_src">' . "\n";

	$sigGen = active_addon('siggen');

	if( $sigGen == 1 && $src == 'SigGen')
	{
		$input_field .= '  <option value="SigGen" selected="selected">SigGen</option>' . "\n";
		$input_field .= '  <option value="default">Default</option>' . "\n";
	}
	elseif( $sigGen == 1 && $src == 'default' || $src == '')
	{
		$input_field .= '  <option value="default" selected="selected">Default</option>' . "\n";
		$input_field .= '  <option value="SigGen">SigGen</option>' . "\n";
	}
	else
	{
		$input_field .= '  <option value="default" selected="selected">Default</option>' . "\n";
	}

	$input_field .= '</select>';

	return $input_field;
}

/**
 * Process Data for entry to the database
 *
 * @return string Settings changed or not changed
 */
function processData()
{
	global $roster, $addon, $user;

	$update_sql = array();
	$mid = 0;
	$src = '';
	// Update only the changed fields
	foreach( $_POST as $settingName => $settingValue )
	{
		if( substr($settingName,0,7) == 'select_' )
		{
			$settingName = str_replace('select_','',$settingName);

			list($uid,$settingName) = explode(':',$settingName);

			if( $settingName == 'is_main' && $settingValue != 'none' )
			{
				$mid = $roster->db->escape( $settingValue );//$roster->db->escape( $settingValue );
			}
			if( $config[$settingName] != $settingValue && $settingName == 'is_main' )
			{
			      $user->profile->setMain($uid, $mid);
			}
			elseif( $config[$settingName] != $settingValue && $settingName == 'avsig_src' )
			{
				$update_sql[] = "UPDATE `" . $roster->db->table('user_members') . "`"
							  . " SET `$settingName` = '" . $roster->db->escape( $settingValue ) . "'"
							  . " WHERE `uid` = '$uid';";
							if( $settingName == 'avsig_src' )

				$result = $roster->db->query($get_val) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$get_val);

				$config = $roster->db->fetch($result);

				$src = $roster->db->escape( $settingValue );
				//echo 'getavsig<br>';
				$user->profile->setAvSig($uid, $mid, $src);

			}
		}
		elseif( substr($settingName,0,5) == 'disp_' )
		{
			$settingName = str_replace('disp_','',$settingName);

			list($uid,$settingName) = explode(':',$settingName);

			$get_val = "SELECT `$settingName`"
					 . " FROM `" . $roster->db->table('user_members') . "`"
					 . " WHERE `id` = '$uid';";

			$result = $roster->db->query($get_val) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$get_val);

			$config = $roster->db->fetch($result);

			if( $config[$settingName] != $settingValue && $settingName != 'process' )
			{
				$update_sql[] = "UPDATE `" . $roster->db->table('user_members') . "`"
							  . " SET `$settingName` = '" . $roster->db->escape( $settingValue ) . "'"
							  . " WHERE `id` = '$uid';";
			}
		}
		else
		{
			//echo $settingName.'<br>';

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
	return true;
}
