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
if( isset($_POST['process']) && $_POST['process'] != '' )
{
	processData($uid);
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

	$query = 'SELECT `id`,`usr`,`pass` FROM `'.$roster->db->table('user_members').'` WHERE `id` = "' . $uid . '" ;';

	$result = $roster->db->query($query);

	$data = $roster->db->fetch($result);
	$roster->tpl->assign_block_vars('profile', array(
			'CNAME'            => '<a href="' . makelink('user-user-profile-' . $data['usr']) . '" target="_blank">' . $data['usr'] . '</a>',
			'OLD_PASS'         => $roster->locale->act['user_password']['old_pass'],
			'OLD_PASS_FIELD'   => '<input class="box" type="password" name="oldpass"/>',
			'NEW_PASS_1'       => $roster->locale->act['user_password']['new_pass_1'],
			'NEW_PASS_1_FIELD' => '<input class="box" type="password" name="newpass1" />',
			'NEW_PASS_2'       => $roster->locale->act['user_password']['new_pass_2'],
			'NEW_PASS_2_FIELD' => '<input class="box" type="password" name="newpass2" />'
			)
		);

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
	<li><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab3[1],$tab3[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-edit') . '">' . $tab3[0] . '</a></li>
	<li class="selected"><span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . makeOverlib($tab4[1],$tab4[0],'',2,'',',WRAP') . '></span><a href="' . makelink('user-user-settings-pass') . '">' . $tab4[0] . '</a></li>
</ul>';

$roster->tpl->set_filenames(array(
	'ucp4' => $addon['basename'] . '/ucp-pass.html'
	)
);

$roster->tpl->assign_vars(array(
	'ROSTERCP_TITLE' => (!empty($rostercp_title) ? $rostercp_title : $roster->locale->act['roster_cp_ab']),
	'MENU'           => $menu,
	'BODY'           => $roster->tpl->fetch('ucp4'),
	'PAGE_INFO'      => $roster->locale->act['user_cp'],
	)
);
$roster->tpl->set_filenames(array(
	'ucp' => $addon['basename'] . '/ucp.html'
	)
);
$roster->tpl->display('ucp');


function processData($uid)
{
	global $roster, $user, $addon;

	$oldpass = $_POST['oldpass'];
	$newpass1 = $_POST['newpass1'];
	$newpass2 =  $_POST['newpass2'];

	if ($oldpass != $newpass1)
	{
		if ($newpass1 == $newpass2)
		{
			$update_sql = "UPDATE `" . $roster->db->table('user_memebrs') . "`"
							  . " SET `pass` = '" . md5($newpass1) . "'"
							  . " WHERE `id` = '$uid';";
			$result = $roster->db->query($update_sql);
		}

	}
}















