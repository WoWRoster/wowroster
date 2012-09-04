<?php 
/** 
 * Dev.PKComp.net user Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @author	   mdeshane
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    user 
 * @subpackage Page Handler 
 */ 

if ( !defined('ROSTER_INSTALLED') ) 
{ 
    exit('Detected invalid access to this file!'); 
}

class userPage extends user
{
	var $active = false;
	
	function userPage()
	{
		$this->active = true;
	}

	function getPage($page)
	{
		$func = array('userPage', $page . 'Page');
		call_user_func($func);
	}

	function registerPage()
	{
		global $roster, $addon, $user;

		$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

		if (isset($_POST['submit']))
		{
			$user->user->register('basic', $_POST['newUser'], $_POST['userPass'], $_POST['confirmPass'], $_POST['fname'], $_POST['lname'], $_POST['groupPass'], $_POST['email']); // the register method
		}
 
		$error = $user->user->message; // error message

		//Build the Form
		$form = 'regForm';
		$user->form->newForm('post', makelink('user-register'), $form, 'formClass');
		$user->form->addFormText('regText', $roster->locale->act['user_int']['reg_txt'], 'membersHeader', 'center', $form);
		$user->form->addTextBox('text', 'newUser', '', $roster->locale->act['user_int']['uname'], 'wowinput128', 1, $form);
		$user->form->addTextBox('password', 'userPass', '', $roster->locale->act['new_pass'], 'wowinput128', 1, $form);
		$user->form->addTextBox('password', 'passConfirm', '', $roster->locale->act['new_pass_confirm'], 'wowinput128', 1, $form);
		$user->form->addTextBox('text', 'fname', '', $roster->locale->act['user_int']['fname'], 'wowinput128', 1, $form);
		$user->form->addTextBox('text', 'lname', '', $roster->locale->act['user_int']['lname'], 'wowinput128', '', $form);
		$user->form->addTextBox('text', 'email', '', $roster->locale->act['user_int']['email'], 'wowinput128', 1, $form);
		$user->form->addTextBox('text', 'group', '', $roster->locale->act['user_int']['group'], 'wowinput128', 1, $form);
		$trayID = $user->form->addTray('buttonTray',$form);
			$user->form->addResetButton($trayID,'clear',$roster->locale->act['config_reset_button'],$form);
			$user->form->addSubmitButton($trayID,'submit',$roster->locale->act['submit'],$form);

		$roster->tpl->assign_block_vars('user_register', array(
			'BORDER_START' => border('syellow','start', $roster->locale->act['user_page']['register']),
			'FORM' => $user->form->getTableOfElements_1(1, $form,null,false),
			'PASS_LEN' => $addon['config']['acc_pass_length'],
			'BORDER_END' => border('syellow','end'),
			'MESSAGE' => (isset($error)) ? $error : "&nbsp;",
			)
		);

		$roster->tpl->set_filenames(array('user_register' => $addon['basename'] . '/register.html'));
		$roster->tpl->display('user_register');

		return;
	}

	function activatePage()
	{
    	global $roster, $addon, $user;
	
    	$roster->output['show_menu']['acc_menu'] = 1 ;  // Display the button listing

		if (isset($_GET['uid']))
		{
			$uid = $_GET['uid'];
		}
		else
		{
			$uid = $_POST['uid'];
		}
		
		if (isset($_GET['act_key']) && isset($_GET['uid']))
		{ // these two variables are required for activating/updating the account/password
		    $user->user->activateAccount($_GET['act_key'], $_GET['uid']); // the activation method 
		}
		elseif (isset($_POST['act_key']) && isset($_POST['uid']))
		{ // these two variables are required for activating/updating the account/password
		    $user->user->activateAccount($_GET['act_key'], $_GET['uid']); // the activation method 
		}

		if (isset($_GET['validate']) && isset($_GET['uid']))
		{ // these two variables are required for activating/updating the new e-mail address
		    $user->user->validateEMail($_GET['validate'], $_GET['uid']); // the validation method 
	    }

    	//if (isset($_GET['act_key']) && isset($_GET['uid']))
		//{ // these two variables are required for activating/updating the password
	    	//if ($user->user->checkActivationPass($_GET['act_key'], $_GET['uid']))
			//{ // the activation/validation method 
		    	//$_SESSION['act_key'] = $_GET['act_key']; // put the activation string into a session or into a hdden field
		    	//$uid = $_GET['uid']; // this id is the key where the record have to be updated with new pass
	    	//}
    	//}
    	if (isset($_POST['Submit']))
		{
	    	if ($user->user->activateNewPass($_POST['userPass'], $_POST['confirmPass'], $_SESSION['act_key'], $uid))
			{ // this will change the password
		    	unset($_SESSION['act_key']);
		    	unset($uid); // inserts new password only ones!
	    	}
	    	$user->user->info['uname'] = $_POST['user']; // to hold the user name in this screen (new in version > 1.77)
    	}
    	$error = $user->user->message;

    	$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

		$roster->tpl->assign_block_vars('user_activate', array(
			'ACT_KEY' => isset($_SESSION['act_key']),
			'BORDER_START' => border('sred','start', $roster->locale->act['user_page']['act_pass']),
			'ACT_PASS' => $roster->locale->act['user_int']['act_pass'],
			'UNAME' => $user->user->info['uname'],
			'FORM_LINK' => makelink('user-activate'),
			'NEW_PASS' => $roster->locale->act['new_pass'],
			'NEW_PASS_CONF' => $roster->locale->act['new_pass_confirm'],
			'SUBMIT' => $roster->locale->act['submit'],
			'user' => $roster->locale->act['user'],
			'BORDER_END' => border('sred','end'),
			'ERROR_SET' => isset($error),
			'ERROR' => $error,
			'LOGIN_LINK' => makelink('user-login'),
			'LOGIN' => $roster->locale->act['user_int']['login'],
			)
		);

		$roster->tpl->set_filenames(array('user_activate' => $addon['basename'] . '/activate.html'));
		$roster->tpl->display('user_activate');

	return;
	}

	function denyPage($groupID, $level)
	{
    	global $roster, $addon, $user;
	
		$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing
	
		$roster->tpl->assign_block_vars('user_deny', array(
			'BORDER_START' => border('sred','start', $roster->locale->act['user_page']['no_access']),
			'ACCESS_TXT' => $roster->locale->act['user_int']['no_access'],
			'INDEX_LINK' => makelink('user'),
			'INDEX_TXT' => $roster->locale->act['user_page']['main'],
			'GROUP_ID' => $user->user->info['group_id'],
			'REQ_LVL' => $user->user->info['level'],
			'BORDER_END' => border('sred','end'),
			)
		);

		$roster->tpl->set_filenames(array('user_deny' => $addon['basename'] . '/deny.html'));
		$roster->tpl->display('user_deny');

	}

	function lostPage()
	{
    	global $roster, $addon, $user;
	
		$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing
	
		$renew_password = $user->user;

    	if (isset($_POST['Submit']))
		{
	    	$renew_password->forgotPass($_POST['email']);
    	}
    	$error = $renew_password->message;
	
		$roster->tpl->assign_block_vars('user_lost', array(
			'BORDER_START' => border('sred','start', $roster->locale->act['user_int']['forgot']),
			'FORGOT_TXT' => $roster->locale->act['user_int']['forgot_txt'],
			'FORM_LINK' => makelink('user-lost'),
			'EMAIL_TXT' => $roster->locale->act['user_int']['email'],
			'SUBMIT' => $roster->locale->act['submit'],
			'BORDER_END' => border('sred','end'),
			'MESSAGE' => (isset($error)) ? $error : "&nbsp;",
			'LOGIN_LINK' => makelink('user-login'),
			'LOGIN_TXT' => $roster->locale->act['user_int']['login'],
			)
		);

		$roster->tpl->set_filenames(array('user_lost' => $addon['basename'] . '/lost.html'));
		$roster->tpl->display('user_lost');

	}

	function logoutPage()
	{
		global $roster, $addon, $user;

		$roster_login = new RosterLogin();
		$roster_login->logOut();
	}

	function loginPage()
	{
    	global $roster, $addon, $user;

    	//Get a redirect page if available
    	if(isset($roster->pages[3]))
    	{   	
    		$redirect = $roster->pages[0] . '-' . $roster->pages[1] . '-' . $roster->pages[3];
    		$locale = $roster->locale->act['user_page'][$roster->pages[3]];
    	}
    	else
    	{
    		$redirect = 'user-main';
    		$locale = $roster->locale->act['user_page']['main'];
    	}
 
		// Disallow viewing of the page
		if( !$roster->auth->getAuthorized($addon['config']['acc_min_access'], $redirect) )
		{
			print
			'<span class="title_text">' . $locale . '</span><br />'.
			$roster->auth->getMessage().
			$roster->auth->getLoginForm();
		}
	}

	function charsPage()
	{
		global $roster, $addon, $user;
 
		// Disallow viewing of the page
		if( !$roster->auth->allow_login )
		{
			print
			'<span class="title_text">' . $roster->locale->act['user_page']['chars'] . '</span><br />'.
			$roster->auth->getMessage().
			$roster->auth->getLoginForm();
		}
		else
		{
			include_once ('memberslist.php');

		$charlist = new memberslist(array('group_alts'=>-1));
	
			$uid = $_COOKIE['roster_u'];//$roster->session->session_id ;//$_COOKIE['roster_u'];

			$mainQuery =
				'SELECT '.
				'`user`.`id`, '.
				'`members`.`member_id`, '.
			'`members`.`name`, '.
			'`members`.`class`, '.
			'`members`.`classid`, '.
			'`members`.`level`, '.
			'`members`.`zone`, '.
			'`members`.`online`, '.
			'`members`.`last_online`, '.
			"UNIX_TIMESTAMP(`members`.`last_online`) AS 'last_online_stamp', ".
			"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_online_format', ".
			'`members`.`note`, '.
			'`members`.`guild_title`, '.

			//'`alts`.`main_id`, '.

			'`guild`.`update_time`, '.
			'`guild`.`factionEn`, '.

			"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
			'`members`.`officer_note`, '.
			"IF( `members`.`officer_note` IS NULL OR `members`.`officer_note` = '', 1, 0 ) AS 'onisnull', ".
			'`members`.`guild_rank`, '.

			'`players`.`server`, '.
			'`players`.`race`, '.
			'`players`.`sex`, '.
			'`players`.`exp`, '.
			'`players`.`clientLocale`, '.

			'`players`.`lifetimeRankName`, '.
			'`players`.`lifetimeHighestRank`, '.
			"IF( `players`.`lifetimeHighestRank` IS NULL OR `players`.`lifetimeHighestRank` = '0', 1, 0 ) AS 'risnull', ".
			'`players`.`hearth`, '.
			"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ".
			"UNIX_TIMESTAMP( `players`.`dateupdatedutc`) AS 'last_update_stamp', ".
			"DATE_FORMAT(  DATE_ADD(`players`.`dateupdatedutc`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_update_format', ".
			"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull', ".

			"GROUP_CONCAT( DISTINCT CONCAT( `proftable`.`skill_name` , '|', `proftable`.`skill_level` ) ORDER BY `proftable`.`skill_order`) as professions, ".
			"GROUP_CONCAT( DISTINCT CONCAT( `talenttable`.`build`, '|', `talenttable`.`tree` , '|', `talenttable`.`pointsspent` , '|', `talenttable`.`background`,'|', `talenttable`.`order` ) ORDER BY `talenttable`.`order`, `talenttable`.`build`) AS 'talents', ".
			"GROUP_CONCAT( DISTINCT CONCAT( `talenttre`.`tree` , '|', `talenttre`.`roles` , '|', `talenttre`.`icon` ) ORDER BY `talenttre`.`tree`) AS 'talents2' ".

			'FROM `'.$roster->db->table('user_members').'` AS user '.
			'LEFT JOIN `'.$roster->db->table('members').'` AS members ON `user`.`id` = `members`.`account_id`'.
			'LEFT JOIN `'.$roster->db->table('players').'` AS players ON `members`.`member_id` = `players`.`member_id` '.
			'LEFT JOIN `'.$roster->db->table('skills').'` AS proftable ON `members`.`member_id` = `proftable`.`member_id` '.
			'LEFT JOIN `'.$roster->db->table('talenttree').'` AS talenttable ON `members`.`member_id` = `talenttable`.`member_id` '.
			'LEFT JOIN `'.$roster->db->table('talenttree_data').'` AS talenttre ON `members`.`classid` = `talenttre`.`class_id` '.
			//fs'LEFT JOIN `'.$roster->db->table('alts',$addon['basename']).'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.
			'LEFT JOIN `'.$roster->db->table('guild').'` AS guild ON `members`.`guild_id` = `guild`.`guild_id`'.
			
			
			'';
			$where[] = '`user`.`id` = "'.$uid.'" ';
			$where[] = '`members`.`guild_id` = "'.$roster->data['guild_id'].'" ';

			$group[] = '`members`.`member_id`';
			$order_first[] = 'IF(`members`.`member_id` = `players`.`member_id`,1,0)';
			$order_last[] = '`members`.`level` DESC';
			$order_last[] = '`members`.`name` ASC';//, `talenttable`.`order` ASC ';

			$always_sort = '';//' `members`.`level` DESC, `members`.`name` ASC';

			$addon = getaddon('memberslist');

			$FIELD['name'] = array (
				'lang_field' => 'name',
				'order'    => array( '`members`.`name` ASC' ),
				'order_d'    => array( '`members`.`name` DESC' ),
				'value' => 'name_value',
				'js_type' => 'ts_string',
				'display' => 3,
			);

			$FIELD['class'] = array (
				'lang_field' => 'class',
				'order'    => array( '`members`.`class` ASC' ),
				'order_d'    => array( '`members`.`class` DESC' ),
				'value' => 'class_value',
				'js_type' => 'ts_string',
				'display' => $addon['config']['member_class'],
			);

			$FIELD['level'] = array (
				'lang_field' => 'level',
				'order_d'    => array( '`members`.`level` ASC' ),
				'value' => 'level_value',
				'js_type' => 'ts_number',
				'display' => $addon['config']['member_level'],
			);

			$FIELD['guild_title'] = array (
				'lang_field' => 'title',
				'order' => array( '`members`.`guild_rank` ASC' ),
				'order_d' => array( '`members`.`guild_rank` DESC' ),
				'js_type' => 'ts_number',
				'jsort' => 'guild_rank',
				'display' => $addon['config']['member_gtitle'],
			);

			$FIELD['lifetimeRankName'] = array (
				'lang_field' => 'currenthonor',
				'order' => array( 'risnull', '`players`.`lifetimeHighestRank` DESC' ),
				'order_d' => array( 'risnull', '`players`.`lifetimeHighestRank` ASC' ),
				'value' => 'honor_value',
				'js_type' => 'ts_number',
				'display' => $addon['config']['member_hrank'],
			);

			$FIELD['professions'] = array (
				'lang_field' => 'professions',
				'value' => 'tradeskill_icons',
				'js_type' => '',
				'display' => $addon['config']['member_prof'],
			);

			$FIELD['last_online'] = array (
				'lang_field' => 'lastonline',
				'order' => array( '`members`.`last_online` DESC' ),
				'order_d' => array( '`members`.`last_online` ASC' ),
				'value' => 'last_online_value',
				'js_type' => 'ts_date',
				'display' => $addon['config']['member_online'],
			);

			$FIELD['last_update_format'] = array (
				'lang_field' => 'lastupdate',
				'order' => array( 'luisnull','`players`.`dateupdatedutc` DESC' ),
				'order_d' => array( 'luisnull','`players`.`dateupdatedutc` ASC' ),
				'jsort' => 'last_update_stamp',
				'js_type' => 'ts_date',
				'display' => $addon['config']['member_update'],
			);


			$charlist->prepareData($mainQuery, $always_sort,$where, $group, $order_first, $order_last, $FIELD, 'charlist');
			//prepareData($mainQuery, $always_sort, $FIELD, 'charlist');

			$addon = getaddon('user');
			
			$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

			$roster->tpl->assign_block_vars('user_chars', array(
				'MESSAGE' => $user->message,
				'BORDER_START' => border('sblue','start', $roster->locale->act['user_page']['chars']),
				'CHARS_LIST' => $charlist->makeMembersList(),
				'BORDER_END' => border('sblue','end'),
				)
			);
	
			$roster->tpl->set_filenames(array('user_chars' => $addon['basename'] . '/chars.html'));
			$roster->tpl->display('user_chars');
		}
	}

	function guildsPage()
	{
		global $roster, $addon, $user;
 
		// Disallow viewing of the page
		if( !$roster->auth->allow_login )
		{
			print
			'<span class="title_text">' . $roster->locale->act['user_page']['guilds'] . '</span><br />'.
			$roster->auth->getMessage().
			$roster->auth->getLoginForm();
		}
		else
		{
			include_once ('memberslist.php');

			$guildlist = new memberslist(array('group_alts'=>-1));

			$uid = $_COOKIE['roster_u'];

			$mainQuery =
				'SELECT '.
			//	'`user`.`id`, '.
			//	'`user_link`.`guild_id`, '.
				'`user`.`id`, '.
				'`members`.`member_id`, '.
				'`members`.`guild_id`, '.
				'`guild`.`guild_name`, '.
				'`guild`.`guild_id`, '.
				'`guild`.`faction`, '.
				'`guild`.`factionEn`, '.
				'`guild`.`guild_num_members`, '.
			//	'`guild`.`guild_num_user`, '.
				'`guild`.`guild_motd` '.

				'FROM `'.$roster->db->table('user_members').'` AS user '.
				'LEFT JOIN `'.$roster->db->table('members').'` AS members ON `user`.`id` = `members`.`account_id`'.
				'LEFT JOIN `'.$roster->db->table('guild').'` AS guild ON `members`.`guild_id` = `guild`.`guild_id`';
				
				$where[] = '`user`.`id` = "' . $uid . '"';
				//.
				$order_first[] = 'IF(`guild`.`guild_id` = `user`.`guild_id`,1,0),';
				$order_last[] = '';
				$group[] = '`guild`.`guild_id`';

			$always_sort = 'ORDER BY `guild`.`guild_name` ASC';

			$FIELD['guild_name'] = array (
				'lang_field' => 'guild',
				'order' => array( '`guild`.`guild_name` ASC' ),
				'order_d' => array( '`guild`.`guild_name` DESC' ),
				'value' => 'guild_value',
				'js_type' => 'ts_string',
				'display' => 3,
			);

			$FIELD['faction'] = array (
				'lang_field' => 'faction',
				'order' => array( '`guild`.`faction` ASC' ),
				'order_d' => array( '`guild`.`faction` DESC' ),
				'value' => 'faction_value',
				'js_type' => 'ts_string',
				'display' => 2,
			);

			$FIELD['guild_num_members'] = array (
				'lang_field' => 'members',
				'order' => array( '`guild`.`guild_num_members` ASC' ),
				'order_d' => array( '`guild`.`guild_num_members` DESC' ),
				'js_type' => 'ts_number',
				'display' => 2,
			);

			$FIELD['guild_motd'] = array (
				'lang_field' => 'motd',
				'order' => array( '`guild`.`guild_motd` ASC' ),
				'order_d' => array( '`guild`.`guild_motd` DESC' ),
				'value' => 'note_value',
				'js_type' => 'ts_string',
				'display' => 2,
			);

			$guildlist->prepareData($mainQuery, $always_sort,$where, $group, $order_first, $order_last, $FIELD, 'guildlist');

			$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

			$roster->tpl->assign_block_vars('user_guilds', array(
				'MESSAGE' => $user->message,
				'BORDER_START' => border('sblue','start', $roster->locale->act['user_page']['guilds']),
				'GUILD_LIST' => $guildlist->makeMembersList(),
				'BORDER_END' => border('sblue','end'),
				)
			);

			$roster->tpl->set_filenames(array('user_guilds' => $addon['basename'] . '/guilds.html'));
			$roster->tpl->display('user_guilds');
		}
	}

	function realmsPage()
	{
		global $roster, $addon, $user;
 
		// Disallow viewing of the page
		if( !$roster->auth->allow_login)
		{
			print
			'<span class="title_text">' . $roster->locale->act['user_page']['realms'] . '</span><br />'.
			$roster->auth->getMessage().
			$roster->auth->getLoginForm();
		}
		else
		{
			include_once ('memberslist.php');

			$realmlist = new memberslist(array('group_alts'=>-1));

			$uid = $_COOKIE['roster_u'];

			$mainQuery =
				'SELECT '.
				'`user`.`id`, '.
				'`members`.`server` as realm, '.
				'`members`.`member_id`, '.
				'`members`.`server`, '.
				'`realm`.`server_name`, '.
				'`realm`.`server_region`, '.
				'`realm`.`servertype`, '.
				'`realm`.`serverstatus`, '.
				'`realm`.`serverpop` '.

				'FROM `'.$roster->db->table('user_members').'` AS user '.
				'LEFT JOIN `'.$roster->db->table('members').'` AS members ON `user`.`id` = `members`.`account_id` '.
				'LEFT JOIN `'.$roster->db->table('realmstatus').'` AS realm ON `members`.`server` = `realm`.`server_name` ';

			$where[] = '`user`.`id` = "' . $uid . '"';
			//.
			$order_first[] = 'IF(`realm`.`server_name` = `realm`,1,0),';
			$order_last[] = '';
			$group[] = '';
			$always_sort = ' `realm`.`server_name` ASC';

			$FIELD['realm_name'] = array (
				'lang_field' => 'realm',
				'order' => array( '`realm`.`server_name` ASC' ),
				'order_d' => array( '`realm`.`server_name` DESC' ),
				'value' => 'realm_value',
				'js_type' => 'ts_string',
				'display' => 3,
			);

			$FIELD['realm_region'] = array (
				'lang_field' => 'region',
				'order' => array( '`realm`.`server_region` ASC' ),
				'order_d' => array( '`realm`.`server_region` DESC' ),
				'value' => 'region_value',
				'js_type' => 'ts_string',
				'display' => 2,
			);

			$FIELD['servertype'] = array (
				'lang_field' => 'servertype',
				'order' => array( '`realm`.`servertype` ASC' ),
				'order_d' => array( '`realm`.`servertype` DESC' ),
				'value' => 'servertype_value',
				'js_type' => 'ts_string',
				'display' => 2,
			);

			$FIELD['serverstatus'] = array (
				'lang_field' => 'serverstatus',
				'order' => array( '`realm`.`serverstatus` ASC' ),
				'order_d' => array( '`realm`.`serverstatus` DESC' ),
				'value' => 'serverstatus_value',
				'js_type' => 'ts_string',
				'display' => 2,
			);

			$FIELD['serverpop'] = array (
				'lang_field' => 'serverpop',
				'order' => array( '`realm`.`serverpop` ASC' ),
				'order_d' => array( '`realm`.`serverpop` DESC' ),
				'value' => 'serverpop_value',
				'js_type' => 'ts_string',
				'display' => 2,
			);

			$realmlist->prepareData($mainQuery, $always_sort,$where, $group, $order_first, $order_last, $FIELD, 'realmlist');

			$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

			$roster->tpl->assign_block_vars('user_realms', array(
				'MESSAGE' => $user->message,
				'BORDER_START' => border('sblue','start', $roster->locale->act['user_page']['realms']),
				'REALMS_LIST' => $realmlist->makeMembersList(),
				'BORDER_END' => border('sblue','end'),
				)
			);

			$roster->tpl->set_filenames(array('user_realms' => $addon['basename'] . '/realms.html'));
			$roster->tpl->display('user_realms');
		}
	}

	function settingsPage()
	{
		global $roster, $addon, $user;
 
		// Disallow viewing of the page
		if( !$roster->auth->allow_login )
		{
			print
			'<span class="title_text">' . $roster->locale->act['user_page']['settings'] . '</span><br />'.
			$roster->auth->getMessage().
			$roster->auth->getLoginForm();
		}
		else
		{
			$config_page = '';
			if(in_array('profile', $roster->pages))
			{
				$config_page = $roster->pages[3];
			}
			if(in_array('edit', $roster->pages))
			{
				$config_page = $roster->pages[3];
			}
			if(in_array('pass', $roster->pages))
			{
				$config_page = $roster->pages[3];
			}
			
			if($config_page == '')
			{
				include_once($addon['admin_dir'] . 'settings.php');
			}
			else
			{
				include_once($addon['admin_dir'] . $config_page.'.php');
			}

		}
	}

	function mainPage()
	{
		global $roster, $addon, $user;

		if($user->session->getVal('isLoggedIn') == true) // This replaces login
		{
			$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

			$tab1 = explode('|',$roster->locale->act['user_main_menu']['my_prof']);
      		$tab2 = explode('|',$roster->locale->act['user_main_menu']['chars']);
      		$tab3 = explode('|',$roster->locale->act['user_main_menu']['guilds']);
      		$tab4 = explode('|',$roster->locale->act['user_main_menu']['realms']);
      		$tab5 = explode('|',$roster->locale->act['user_main_menu']['mail']);
      		$tab6 = explode('|',$roster->locale->act['user_main_menu']['char']);
      		$tab7 = explode('|',$roster->locale->act['user_main_menu']['prof']);

      		$menu = messagebox('
      		<ul class="tab_menu">
      			<li><a href="' . makelink('user-profile-' . $user->user->info['uname']) . '" style="cursor:help;"' . makeOverlib($tab1[1],$tab1[0],'',1,'',',WRAP') . '>' . $tab1[0] . '</a></li>
      			<li><a href="' . makelink('user-chars') . '" style="cursor:help;"' . makeOverlib($tab2[1],$tab2[0],'',1,'',',WRAP') . '>' . $tab2[0] . '</a></li>
      			<li><a href="' . makelink('user-guilds') . '" style="cursor:help;"' . makeOverlib($tab3[1],$tab3[0],'',1,'',',WRAP') . '>' . $tab3[0] . '</a></li>
      			<li><a href="' . makelink('user-realms') . '" style="cursor:help;"' . makeOverlib($tab4[1],$tab4[0],'',1,'',',WRAP') . '>' . $tab4[0] . '</a></li>
      			<li><a href="' . makelink('user-mail') . '" style="cursor:help;"' . makeOverlib($tab5[1],$tab5[0],'',1,'',',WRAP') . '>' . $tab5[0] . '</a></li>
                        <li><a href="' . makelink('user-settings') . '" style="cursor:help;"' . makeOverlib($tab6[1],$tab6[0],'',1,'',',WRAP') . '>' . $tab6[0] . '</a></li>
      			<li><a href="' . makelink('user-settings-profile') . '" style="cursor:help;"' . makeOverlib($tab7[1],$tab7[0],'',1,'',',WRAP') . '>' . $tab7[0] . '</a></li>
		      </ul>
      		',$roster->locale->act['user_page']['menu'],'syellow','145px');

			$roster->tpl->assign_block_vars('user_main', array(
				'MESSAGE' => $user->message,
				'MAIN_BORDER_START' => border('sblue','start', $roster->locale->act['user_page']['main']),
				'MAIN_TXT' => sprintf($roster->locale->act['user_int']['main_txt'], $user->user->info['uname']),
				'MAIN_BORDER_END' => border('sblue','end'),
				'USER_LINK' => makelink('user-profile-' . $user->user->info['uname']),
				'INFO_BORDER_START' => border('sgray','start', $roster->locale->act['user_page']['info']),
				'UNAME_TXT' => $roster->locale->act['user_int']['uname'],
				'FNAME_TXT' => $roster->locale->act['user_int']['fname'],
				'LNAME_TXT' => $roster->locale->act['user_int']['lname'],
				'GROUP_TXT' => $roster->locale->act['user_int']['ugroup'],
				'EMAIL_TXT' => $roster->locale->act['user_int']['email'],
				'USER' => $user->user->info['uname'],
				'FNAME' => $user->user->info['fname'],
				'LNAME' => $user->user->info['lname'],
				'GROUP' => $user->profile->getGroup($user->user->info['uname']),
				'EMAIL' => $user->user->info['email'],
				'INFO_BORDER_END' => border('sgray','end'),
				'MENU' => $menu,
				)
			);

			$roster->tpl->set_filenames(array('user_main' => $addon['basename'] . '/main.html'));
			$roster->tpl->display('user_main');
		}
		else
		{
			$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

			

			$roster->tpl->assign_block_vars('user_index', array(
				'MESSAGE' => $user->message,
				'BORDER_START' => border('sblue','start', $roster->locale->act['user_page']['main']),
				'WELCOME_TXT' => $addon['config']['acc_reg_text'],
				'REGISTER_LINK' => makelink('user-register'),
				'REG_CLICK' => $roster->locale->act['user_page']['registration'],
				'IF_APP' => $addon['config']['acc_use_app'],
				'APP_LINK' => makelink('user-application'),
				'APP_CLICK' => $roster->locale->act['user_page']['application'],
				'LOGIN_LINK' => makelink('user-login-main'),
				'LOGIN_CLICK' => $roster->locale->act['user_int']['login'],
				'BORDER_END' => border('sblue','end'),
				'RECRUIT_BOX' => $user->profile->recruitment(),
				)
			);

			$roster->tpl->set_filenames(array('user_index' => $addon['basename'] . '/index.html'));
			$roster->tpl->display('user_index');
		}
	}
	
	function applicationPage()
	{
		global $roster, $addon, $user;
		
		$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

		if (isset($_POST['submit']))
		{
			$age = mktime(0, 0, 0, $_POST['age_Month'], $_POST['age_Day'], $_POST['age_Year']);
                  $user->user->register('full', $_POST['uName'], $_POST['userPass'], $_POST['passConfirm'], $_POST['fName'], $_POST['lName'], $_POST['groupPass'], $_POST['eMail'], $age, $_POST['City'], $_POST['State'], $_POST['Country'], $_POST['Zone'], $_POST['otherGuilds'], $_POST['why'], $_POST['homePage'], $_POST['about'], $_POST['notes']);
		}

		$form = 'userApp';
		$user->form->newForm('post', makelink('user-application'), $form, 'formClass', 4);
		$user->form->addFormText('appText', $roster->locale->act['user_app']['app_txt'], 'membersHeader', 'center',$form);
		$user->form->addFormText('userText', $roster->locale->act['user_app']['user_hd'], 'membersHeader', 'center',$form);
		$user->form->addTextBox('text','uName','',$roster->locale->act['user_int']['uname'],'wowinput128',1,$form);
		$user->form->addTextBox('text','eMail','',$roster->locale->act['user_int']['email'],'wowinput128',1,$form);
		$user->form->addTextBox('password','userPass','',$roster->locale->act['new_pass'],'wowinput128',1,$form);
		$user->form->addTextBox('password','passConfirm','',$roster->locale->act['new_pass_confirm'],'wowinput128',1,$form);
		$user->form->addTextBox('password','groupPass','',$roster->locale->act['user_int']['group'],'wowinput128',1,$form);
		$user->form->addColumn('empty_1',2,'','',$form);
		$user->form->addFormText('infoText', $roster->locale->act['user_app']['inf_hd'], 'membersHeader', 'center',$form);
		$user->form->addTextBox('text','fName','',$roster->locale->act['user_int']['fname'],'wowinput128',1,$form);
		$user->form->addTextBox('text','lName','',$roster->locale->act['user_int']['lname'],'wowinput128','',$form);
		$user->form->addDateSelect('age','Birthdate',1,$form);
		$user->form->addColumn('empty_2',2,'','',$form);
		$user->form->addTextBox('text','City','',$roster->locale->act['user_int']['city'],'wowinput128','',$form);
		$user->form->addTextBox('text','State','',$roster->locale->act['user_int']['state'],'wowinput128','',$form);
		$user->form->addTextBox('text','Country','',$roster->locale->act['user_int']['country'],'wowinput128',1,$form);
		$user->form->addTimezone('Zone',$roster->locale->act['user_app']['zone'],'',$form);
		$user->form->addFormText('extraText',$roster->locale->act['user_app']['ext_inf'],'membersHeader','center',$form);
		$user->form->addTextBox('text','otherGuilds','',$roster->locale->act['user_app']['guilds'],'wowinput128','',$form);
		$user->form->addTextArea('why','',$roster->locale->act['user_app']['why'],1,$form);
		$user->form->addTextBox('text','homePage','',$roster->locale->act['user_app']['homepage'],'wowinput128','',$form);
		$user->form->addTextArea('about','',$roster->locale->act['user_app']['about'],'',$form);
		$user->form->addTextArea('notes','',$roster->locale->act['user_app']['notes'],'',$form);
		$user->form->addColumn('empty_3',2,'','',$form);
		$trayID = $user->form->addTray('buttonTray',$form);
			$user->form->addResetButton($trayID,'clear',$roster->locale->act['config_reset_button'],$form);
			$user->form->addSubmitButton($trayID,'submit',$roster->locale->act['submit'],$form);

		$error = $user->user->message; // error message
	
		$roster->tpl->assign_block_vars('user_application', array(
			'BORDER_START' => border('syellow','start', $roster->locale->act['user_page']['application']),
			'FORM' => $user->form->getTableOfElements_1(1,$form),
			'BORDER_END' => border('syellow','end'),
			'MESSAGE' => (isset($error)) ? $error : "&nbsp;",
			)
		);

		$roster->tpl->set_filenames(array('user_application' => $addon['basename'] . '/application.html'));
		$roster->tpl->display('user_application');

		return;
	}

	function profilePage()
	{
		global $roster, $addon, $user;
		
		$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

		$usr = $roster->pages[3];

		$uid = $user->user->getUser($usr);

		$user->user->getInfo($usr);

		$user->profile->getConfigData($uid);

		$online = '<font color="#00FF00">' . $roster->locale->act['online'] . '</font>';
		$offline = '<font color="#FF0000">' . $roster->locale->act['user_rs']['offline'] . '</font>';

		if (isset($_POST['Submit']))
		{
			$user->profile->update($_POST['uid'], $_POST['char'], $_POST['user'], $_POST['email']); // the register method
		}

		$error = $user->user->message; // error message

		$roster->tpl->assign_block_vars('user_profile', array(
			'USER_LINK' => makelink('user-profile-' . $usr),
			'MESSAGE' => $user->message,
			'BORDER_START' => border('sblue','start', sprintf($roster->locale->act['user_page']['profile'], $user->user->info['usr'])),
			'USER' => '<b>' . $roster->locale->act['user_int']['uname'] . ':</b> <a href="' . makelink('user-profile-' . $usr) . '">' . $user->user->info['usr'] . '</a>',
			'AVATAR' => $user->profile->getAvSig('av', $uid),
			'S_CITY' => $user->profile->configData['show_city'],
			'CITY' => '<b>' . $roster->locale->act['user_int']['city'] . ':</b> ' . $user->user->info['city'],
			'S_COUNTRY' => $user->profile->configData['show_country'],
			'COUNTRY' => '<b>' . $roster->locale->act['user_int']['country'] . ':</b> ' . $user->user->info['country'],
			'S_FNAME' => $user->profile->configData['show_fname'],
			'FNAME' => '<b>' . $roster->locale->act['user_int']['fname'] . ':</b> ' . $user->user->info['fname'],
			'S_LNAME' => $user->profile->configData['show_lname'],
			'LNAME' => '<b>' . $roster->locale->act['user_int']['lname'] . ':</b> ' . $user->user->info['lname'],
			'S_EMAIL' => $user->profile->configData['show_email'],
			'EMAIL' => '<b>' . $roster->locale->act['user_int']['email'] . ':</b> ' . $user->user->info['email'],
			'S_HOMEPAGE' => $user->profile->configData['show_homepage'],
			'HOMEPAGE' => '<b>' . $roster->locale->act['user_int']['homepage'] . ':</b><a href="' . $user->user->info['homepage'] . '"> ' . $user->user->info['homepage'] . '</a>',
			'S_JOINED' => $user->profile->configData['show_joined'],
			'JOINED' => '<b>' . $roster->locale->act['user_int']['date_joined'] . ':</b> ' . $user->user->info['date_joined'],
			'S_LOGIN' => $user->profile->configData['show_lastlogin'],
			'LOGIN' => '<b>' . $roster->locale->act['user_int']['last_login'] . ':</b> ' . $user->user->info['last_login'],
			'ONLINE' => $user->user->info['online'],
			'IS_ONLINE' => sprintf($roster->locale->act['user_int']['is_online'], $online),
			'IS_OFFLINE' => sprintf($roster->locale->act['user_int']['is_online'], $offline),
			'BORDER_END' => border('sblue','end'),
			)
		);

		$roster->tpl->set_filenames(array(
			'uprofile' => $addon['basename'] . '/profile.html'
			)
		);
		$roster->tpl->display('uprofile');

		return;
	}

	function mailPage()
	{
		global $roster, $addon, $user;
		
		//$roster->output['show_menu']['acc_menu'] = 1;  // Display the button listing

		$form = '';
		$menu = '';
		$mail_menu = '';
		// Disallow viewing of the page
		if( !$roster->auth->allow_login )
		{
			print
			'<span class="title_text">' . $roster->locale->act['user_page']['messaging'] . '</span><br />'.
			$roster->auth->getMessage().
			$roster->auth->getLoginForm();
		}
		else
		{
                  $tab1 = explode('|',$roster->locale->act['user_main_menu']['my_prof']);
                  $tab2 = explode('|',$roster->locale->act['user_main_menu']['chars']);
                  $tab3 = explode('|',$roster->locale->act['user_main_menu']['guilds']);
                  $tab4 = explode('|',$roster->locale->act['user_main_menu']['realms']);
                  $tab5 = explode('|',$roster->locale->act['user_main_menu']['mail']);
                  $tab6 = explode('|',$roster->locale->act['user_main_menu']['char']);
                  $tab7 = explode('|',$roster->locale->act['user_main_menu']['prof']);
/*
                  $menu = messagebox('
                  <ul class="tab_menu">
                        <li><a href="' . makelink('user-profile-' . $_COOKIE['roster_user']) . '" style="cursor:help;"' . makeOverlib($tab1[1],$tab1[0],'',1,'',',WRAP') . '>' . $tab1[0] . '</a></li>
                        <li><a href="' . makelink('user-chars') . '" style="cursor:help;"' . makeOverlib($tab2[1],$tab2[0],'',1,'',',WRAP') . '>' . $tab2[0] . '</a></li>
                        <li><a href="' . makelink('user-guilds') . '" style="cursor:help;"' . makeOverlib($tab3[1],$tab3[0],'',1,'',',WRAP') . '>' . $tab3[0] . '</a></li>
                        <li><a href="' . makelink('user-realms') . '" style="cursor:help;"' . makeOverlib($tab4[1],$tab4[0],'',1,'',',WRAP') . '>' . $tab4[0] . '</a></li>
                        <li><a href="' . makelink('user-mail') . '" style="cursor:help;"' . makeOverlib($tab5[1],$tab5[0],'',1,'',',WRAP') . '>' . $tab5[0] . '</a></li>
                        <li><a href="' . makelink('user-settings') . '" style="cursor:help;"' . makeOverlib($tab6[1],$tab6[0],'',1,'',',WRAP') . '>' . $tab6[0] . '</a></li>
                        <li><a href="' . makelink('user-settings-profile') . '" style="cursor:help;"' . makeOverlib($tab7[1],$tab7[0],'',1,'',',WRAP') . '>' . $tab7[0] . '</a></li>
                  </ul>
                  ',$roster->locale->act['user_page']['menu'],'syellow','145px');
*/
                  $mail_tab1 = explode('|',$roster->locale->act['user_mail_menu']['inbox']);
                  $mail_tab2 = explode('|',$roster->locale->act['user_mail_menu']['outbox']);
                  $mail_tab3 = explode('|',$roster->locale->act['user_mail_menu']['write']);
            
                  $mail_menu = '<ul class="tab_menu">
                        <li><a href="' . makelink('user-user-mail-inbox') . '" style="cursor:help;"' . makeOverlib($mail_tab1[1],$mail_tab1[0],'',1,'',',WRAP') . '>' . $mail_tab1[0] . '</a></li>
                        <li><a href="' . makelink('user-user-mail-outbox') . '" style="cursor:help;"' . makeOverlib($mail_tab2[1],$mail_tab2[0],'',1,'',',WRAP') . '>' . $mail_tab2[0] . '</a></li>
                        <li><a href="' . makelink('user-user-mail-write') . '" style="cursor:help;"' . makeOverlib($mail_tab3[1],$mail_tab3[0],'',1,'',',WRAP') . '>' . $mail_tab3[0] . '</a></li>
                  </ul>';
                  
                  if( isset($roster->pages[3]) && $roster->pages[3] == 'inbox' )
                  {
                        $uid = (isset($_COOKIE['roster_u']) ? $_COOKIE['roster_u'] : $roster->auth->uid);

                        $messages = $user->messaging->getAllMessages('',$uid);
                        if(is_array($messages))
                        {
                              $form = 'messsageForm';
                              $user->form->newForm('post', makelink('user-user-mail'), $form, 'formClass', 4);

                              $user->form->addColumn('read',1,$roster->locale->act['user_int']['messaging']['read'],'membersHeader',$form);
                              $user->form->addColumn('title',1,$roster->locale->act['user_int']['messaging']['title'],'membersHeader',$form);
                              $user->form->addColumn('sender',1,$roster->locale->act['user_int']['messaging']['sender'],'membersHeader',$form);
                              $user->form->addColumn('date_rec',1,$roster->locale->act['user_int']['messaging']['date_rec'],'membersHeader',$form);

                              $num = count($messages);
                              $message = '';
                              for($i = 0 ; $i < $num ; $i++ )
                              {
                                    if($i&1)
                                    {
                                          $rowColor = 'membersRow1';
                                    }
                                    else
                                    {
                                          $rowColor = 'membersRow2';
                                    }
                                    $read_val = '<button disabled="disabled"><span class="ui-icon ui-icon-radio-off"></span></button>';
                                    if($messages[$i]['read'] == '1')
                                    {
                                          $read_val = '<button disabled="disabled"><span class="ui-icon ui-icon-check"></span></button>';
                                    }

                                    $user->form->addColumn('read_' . $i,1,$read_val . "&nbsp;",$rowColor,$form);
                                    $user->form->addColumn('title_' . $i,1,'<a href="' . makelink('user-user-mail-read&amp;msgid=' . $messages[$i]['id']) . '">' . $messages[$i]['title'] . '</a>',$rowColor,$form);
                                    $user->form->addColumn('sender_' . $i,1,$user->user->getUser($messages[$i]['sender']),$rowColor,$form);
                                    $user->form->addColumn('date_' . $i,1,$messages[$i]['date'],$rowColor,$form);
                              }
                        }
						else
						{
							$form = 'messsageForm';
                            $user->form->newForm('post', makelink('user-mail'), $form, 'formClass', 4);
							//user->form->addColumn('x1x1',$span=1,'You have no mail',$class='formElement',$form);
							$user->form->addColumn('read',1,'You have no mail','membersHeader',$form);
						}
                  }
                  elseif( isset($roster->pages[3]) && $roster->pages[3] == 'outbox' )
                  {
                        $uid = $_COOKIE['roster_u'];

                        $messages = $user->messaging->getAllMessages('','',$uid);
                        if(is_array($messages))
                        {
                              $form = 'messageForm';
                              $user->form->newForm('post', makelink('user-user-mail'), $form, 'formClass', 4);
                              $user->form->addColumn('read',1,$roster->locale->act['user_int']['messaging']['read'],'membersHeader',$form);
                              $user->form->addColumn('title',1,$roster->locale->act['user_int']['messaging']['title'],'membersHeader',$form);
                              $user->form->addColumn('reciever',1,$roster->locale->act['user_int']['messaging']['reciever'],'membersHeader',$form);
                              $user->form->addColumn('date_sent',1,$roster->locale->act['user_int']['messaging']['date_sent'],'membersHeader',$form);

                              $num = count($messages);
                              $message = '';
                              for($i = 0 ; $i < $num ; $i++ )
                              {
                                    if($i&1)
                                    {
                                          $rowColor = 'membersRow1';
                                    }
                                    else
                                    {
                                          $rowColor = 'membersRow2';
                                    }
                                    $read_val = 'checkboxOff';
                                    if($messages[$i]['read'] == '1')
                                    {
                                          $read_val = 'checkboxOn';
                                    }

                                    $user->form->addColumn('read_' . $i,1,'<span class="' . $read_val . '" />&nbsp;',$rowColor,$form);
                                    $user->form->addColumn('title_' . $i,1,'<a href="' . makelink('user-user-mail-read&amp;msgid=' . $messages[$i]['id']) . '">' . $messages[$i]['title'] . '</a>',$rowColor,$form);
                                    $user->form->addColumn('reciever_' . $i,1,$user->user->getUser($messages[$i]['reciever']),$rowColor,$form);
                                    $user->form->addColumn('date_sent_' . $i,1,$messages[$i]['date'],$rowColor,$form);
                              }
                        }
						else
						{
							$form = 'messsageForm';
                            $user->form->newForm('post', makelink('user-mail'), $form, 'formClass', 4);
							//user->form->addColumn('x1x1',$span=1,'You have no mail',$class='formElement',$form);
							$user->form->addColumn('read',1,'You have sent no mail','membersHeader',$form);
						}
                  }
                  elseif( isset($roster->pages[3]) && $roster->pages[3] == 'read' )
                  {
                        $id = $_GET['msgid'];
                        $message = $user->messaging->getMessage($id);
                        $form = 'readMessageForm';

                        $user->form->newForm('post', makelink('user-user-mail-write'), $form, 'formClass', 2);
                        $user->form->addColumn('sender',1,$roster->locale->act['user_int']['messaging']['sender'] . ' :','membersRow1',$form);
                        $user->form->addColumn('sender_val',1,$user->user->getUser($message['sender']),'membersRow1',$form);
                        $user->form->addColumn('title',1,$roster->locale->act['user_int']['messaging']['title'] . ' :','membersRow2',$form);
                        $user->form->addColumn('title_val',1,$message['title'],'membersRow2',$form);
                        $user->form->addColumn('body',1,$roster->locale->act['user_int']['messaging']['body'] . ' :','membersRow1',$form);
                        $user->form->addColumn('body_val',1,$message['body'],'membersRow1',$form);
                        $trayID = $user->form->addTray('buttonTray',$form);
                        $user->form->addHiddenField($trayID,'title',$message['title'],$form);
						$user->form->addHiddenField($trayID,'reply',true,$form);
                        $user->form->addHiddenField($trayID,'body',$message['body'],$form);
                        $user->form->addHiddenField($trayID,'reciever',$message['sender'],$form);
                        $user->form->addSubmitButton($trayID,'reply',$roster->locale->act['user_int']['messaging']['reply'],$form);

                        if($message['read'] == '0' || $message['sender'] != $message['receiver'])
                        {
                              $user->messaging->markAsRead($id);
                        }
                  }
                  elseif( isset($roster->pages[3]) && $roster->pages[3] == 'write' )
                  {
                        if(isset($_POST['reply']))
                        {
                              $title_val = 'RE: ' . $_POST['title'];
                              $body_val = $_POST['body'];
                              $rec_val = $_POST['reciever'];
                        }
                        else
                        {
                              $title_val = '';
                              $body_val = '';
                              $rec_val = '';
                        }

                        if(isset($_POST['submit']))
                        {
                              $user->messaging->sendMessage($_POST['title'] ,$_POST['body'] ,$_POST['sender'] ,$_POST['reciever'] ,$_POST['senderLevel']);
                        }

                        $form = 'newMessageForm';
                        $user->form->newForm('post', makelink('user-user-mail-write'), $form, 'formClass', 1);
                        $user->form->addUserSelect('reciever',$roster->locale->act['user_int']['messaging']['reciever'] . ' :',1,$form,$rec_val);
                        $user->form->addTextBox('text','title',$title_val,$roster->locale->act['user_int']['messaging']['title'] . ' :','wowinput128',1,$form);
						if ($body_val != '')
						{
							$user->form->addTextArea('body',"\r\n\r\nQuoting \"".$body_val."\"",$roster->locale->act['user_int']['messaging']['body'] . ' :',1,$form);
						}
						else
						{
							$user->form->addTextArea('body',"",$roster->locale->act['user_int']['messaging']['body'] . ' :',1,$form);
						}						
                        $trayID = $user->form->addTray('buttonTray',$form);
                        $user->form->addHiddenField($trayID,'sender',$_COOKIE['roster_u'],$form);
						$user->form->addHiddenField($trayID,'submit','submit',$form);
                        $user->form->addHiddenField($trayID,'senderLevel','user',$form);
                        $user->form->addResetButton($trayID,'clear',$roster->locale->act['config_reset_button'],$form);
                        $user->form->addSubmitButton($trayID,'submit',$roster->locale->act['submit'],$form);
                  }
                  else
                  {
                        $form = 'mainForm';
                        $user->form->newForm('post', makelink('user-user-mail'), $form, 'formClass', 1);
                        $user->form->addColumn('mail_txt',1,$roster->locale->act['user_int']['messaging']['main'],'membersRow1',$form);
                  }
					if(isset($roster->pages[3]))
					{
						$mail_page = ' - ' . ucfirst($roster->pages[3]);
					}
					else
					{
						$mail_page = ' ';
					}
                  $roster->tpl->assign_block_vars('user_messaging', array(
                        'BORDER_START' => border('syellow','start', $roster->locale->act['user_page']['messaging'] . $mail_page),
						'PAGETITLE'		=> $roster->locale->act['user_page']['messaging'] . $mail_page,
						'ICON' => 'achievement_guildperk_gmail',
                        'MENU' => $mail_menu,
                        'FORM' => $user->form->getTableOfElements_1(1,$form),
                        'BORDER_END' => border('syellow','end'),
                        'MESSAGE' => (isset($error)) ? $error : "&nbsp;",
                        'ACC_MENU' => $menu,
                        )
                  );

                  $roster->tpl->set_filenames(array('user_messaging' => $addon['basename'] . '/messaging.html'));
                  $roster->tpl->display('user_messaging');
            }

            $error = $user->messaging->err_msg; // error message
            $mail_page = '';
            if(isset($roster->pages[3]))
            {
                  $mail_page = ' - ' . ucfirst($roster->pages[3]);
		}

		return;
	}
}
