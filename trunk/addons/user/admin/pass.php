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
			'CNAME'  			=> '<a href="' . makelink('user-user-profile-' . $data['usr']) . '" target="_blank">' . $data['usr'] . '</a>',
			'OLD_PASS' 			=> $roster->locale->act['user_password']['old_pass'],
			'OLD_PASS_FIELD'	=> '<input class="box" type="password" name="oldpass"/>',
			'NEW_PASS_1'		=> $roster->locale->act['user_password']['new_pass_1'],
			'NEW_PASS_1_FIELD'	=> '<input class="box" type="password" name="newpass1" />',
			'NEW_PASS_2'		=> $roster->locale->act['user_password']['new_pass_2'],
			'NEW_PASS_2_FIELD'	=> '<input class="box" type="password" name="newpass2" />'
			)
		);

}
else
{
	$formbody = 'No Data';
}

$roster->output['body_onload'] .= 'initARC(\'config\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';
$tab1 = explode('|',$roster->locale->act['user_settings']['set']);
$tab2 = explode('|',$roster->locale->act['user_settings']['prof']);
$tab3 = explode('|',$roster->locale->act['user_main_menu']['my_prof']);
$tab4 = explode('|',$roster->locale->act['user_password']['settings_password']);
$menu = messagebox('
<ul class="tab_menu">
	<li><a href="' . makelink('user-user-settings') . '" style="cursor:help;"' . makeOverlib($tab1[1],$tab1[0],'',1,'',',WRAP') . '>' . $tab1[0] . '</a></li>
	<li><a href="' . makelink('user-user-settings-profile') . '" style="cursor:help;"' . makeOverlib($tab2[1],$tab2[0],'',1,'',',WRAP') . '>' . $tab2[0] . '</a></li>
	<li><a href="' . makelink('user-user-settings-edit') . '" style="cursor:help;"' . makeOverlib($tab3[1],$tab3[0],'',1,'',',WRAP') . '>' . $tab3[0] . '</a></li>
	<li class="selected"><a href="' . makelink('user-user-settings-pass') . '" style="cursor:help;"' . makeOverlib($tab4[1],$tab4[0],'',1,'',',WRAP') . '>' . $tab4[0] . '</a></li>
</ul>
',$roster->locale->act['user_page']['settings'],'sgray','145px');

$roster->tpl->set_filenames(array(
	'ucp4' => $addon['basename'] . '/ucp-pass.html'
	)
);

$roster->tpl->assign_vars(array(
	'ROSTERCP_TITLE'  => (!empty($rostercp_title) ? $rostercp_title : $roster->locale->act['roster_cp_ab']),
	'MENU' => $menu,
	'BODY' => $roster->tpl->fetch('ucp4'),
	'PAGE_INFO' => 'User Controle Pannel',
	)
);
$roster->tpl->set_filenames(array(
	'ucp' => $addon['basename'] . '/ucp.html'
	)
);
$roster->tpl->display('ucp');


processData($uid)
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















