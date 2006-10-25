<?php
/******************************
 * WoWRoster.net  UniAdmin
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

if( $user->data['level'] < UA_ID_USER )
{
	$uniadmin->debug($user->lang['access_denied']);
	$uniadmin->set_vars(array(
	    'template_file' => 'index.html',
	    'display'       => true)
	);
	die();
}

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

// Decide What To Do
switch( $op )
{
	case 'edit':
		modify_user();
		break;

	case 'finalize':
		finalize_user();
		main();
		break;

	case UA_URI_NEW:
		new_user();
		main();
		break;

	case 'delete':
		delete_user();
		main();
		break;

	default:
		main();
		break;
}







/**
 * Users Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $uniadmin, $user, $tpl;

	$tpl->assign_vars(array(
		'L_CURRENT_USERS' => $user->lang['current_users'],
		'L_USERNAME'      => $user->lang['username'],
		'L_PASSWORD'      => $user->lang['password'],
		'L_USERLEVEL'     => $user->lang['userlevel'],
		'L_LANGUAGE'      => $user->lang['language'],
		'L_MODIFY'        => $user->lang['modify'],
		'L_DELETE'        => $user->lang['delete'],
		'L_ADD_USER'      => $user->lang['add_user'],
		'L_USER_STYLE'    => $user->lang['user_style'],

		'S_ADD_USERS'     => ( $user->data['level'] >= UA_ID_POWER ) ? true : false,
		'S_ADMIN'         => ( $user->data['level'] == UA_ID_ADMIN ) ? true : false,

		'U_LEVEL_SELECTBOX' => level_select(),
		'U_LANG_SELECTBOX'  => lang_select(),
		'U_STYLE_SELECTBOX' => style_select(),
		'U_USER_ID'       => UA_ID_USER,
		)
	);

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` ORDER BY `level` DESC, `name` ASC;";
	$result = $db->query($sql);


	while ($row = $db->fetch_record($result))
	{
		$userN = $row['name'];
		$userL = $row['level'];
		$userI = $row['id'];
		$userW = $row['language'];
		$userS = $row['user_style'];

		if( strtoupper($userN) == strtoupper($user->data['name']) || $user->data['level'] >= UA_ID_POWER )
		{
			if( strtoupper($userN) == strtoupper($user->data['name']) || $user->data['level'] == UA_ID_ADMIN || ($user->data['level'] == UA_ID_POWER && $userL == UA_ID_USER) )
			{
				$tpl->assign_block_vars('user_row', array(
					'ROW_CLASS'  => $uniadmin->switch_row_class(),
					'USER_ID'    => $userI,
					'NAME'       => $userN,
					'LEVEL'      => $userL,
					'LANG'       => $userW,
					'STYLE'      => $userS,
					'S_EDIT'     => true,
					'S_DELETE'   => true,
					)
				);
			}
			else
			{
				$tpl->assign_block_vars('user_row', array(
					'ROW_CLASS'  => $uniadmin->switch_row_class(),
					'USER_ID'    => $userI,
					'NAME'       => $userN,
					'LEVEL'      => $userL,
					'LANG'       => $userW,
					'STYLE'      => $userS,
					'S_EDIT'     => false,
					'S_DELETE'   => false,
					)
				);
			}
		}
		else
		{
			$tpl->assign_block_vars('user_row', array(
				'ROW_CLASS'  => $uniadmin->switch_row_class(),
				'USER_ID'    => $userI,
				'NAME'       => $userN,
				'LEVEL'      => $userL,
				'LANG'       => $userW,
				'STYLE'      => $userS,
				'S_EDIT'     => false,
				'S_DELETE'   => false,
				)
			);
		}
	}

	$db->free_result($result);

	$uniadmin->set_vars(array(
		'page_title'    => $user->lang['title_users'],
		'template_file' => 'user.html',
		'display'       => true
		)
	);
}

/**
 * Builds the edit user table
 */
function modify_user()
{
	global $db, $uniadmin, $user, $tpl;

	$uid = $_POST[UA_URI_ID];

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` WHERE `id` = '$uid';";
	$result = $db->query($sql);

	$row = $db->fetch_record($result);
	$userN = $row['name'];
	$userL = $row['level'];
	$userS = $row['user_style'];
	$userW = $row['language'];

	$tpl->assign_vars(array(
		'L_MODIFY_USER' => $user->lang['modify_user'],
		'L_CHANGE_USERNAME'      => $user->lang['change_username'],
		'L_CHANGE_PASSWORD'      => $user->lang['change_password'],
		'L_CHANGE_USERLEVEL'     => $user->lang['change_userlevel'],
		'L_CHANGE_LANGUAGE'      => $user->lang['change_language'],
		'L_CHANGE_STYLE'         => $user->lang['change_style'],

		'L_USERNAME'      => $user->lang['username'],
		'L_USERLEVEL'     => $user->lang['userlevel'],

		'S_POWER'         => ( $user->data['level'] >= UA_ID_POWER ) ? true : false,
		'S_ADMIN'         => ( $user->data['level'] == UA_ID_ADMIN ) ? true : false,

		'U_USER_ID'         => $uid,
		'U_USERNAME'        => $userN,
		'U_USERLEVEL'       => $userL,
		'U_LANG_SELECTBOX'  => lang_select($userW),
		'U_LEVEL_SELECTBOX' => level_select($userL),
		'U_STYLE_SELECTBOX' => style_select($userS),
		)
	);

	$db->free_result($result);

	$uniadmin->set_vars(array(
		'page_title'    => $user->lang['modify_user'],
		'template_file' => 'user_edit.html',
		'display'       => true
		)
	);
}

/**
 * Finalizes editing of a user
 */
function finalize_user()
{
	global $db, $uniadmin, $user;

	$userN = $_POST['name'];
	$userI = $_POST[UA_URI_ID];
	$userP = $_POST['password'];
	$userL = $_POST['level'];
	$userS = $_POST['style'];
	$userW = $_POST['language'];

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` WHERE `name` = '$userN';";
	$result = $db->query($sql);

	$row = $db->fetch_record($result);
	$old_pass_hash = $row['password'];

	$userP = (empty($userP) ? $old_pass_hash : md5($userP) );

	if ($user->data['level'] > UA_ID_USER)
	{
		if ($user->data['id'] != $userI)
		{
			if ($user->data['level'] < UA_ID_ADMIN)
			{
				$userL = UA_ID_USER;
			}
			$sql = "UPDATE `".UA_TABLE_USERS."` SET `name` = '".$db->escape($userN)."', `level` = '$userL', `password` = '$userP', `language` = '".$db->escape($userW)."', `user_style` = '".$db->escape($userS)."' WHERE `id` = '$userI' LIMIT 1 ;";
		}
		else
		{
			$sql = "UPDATE `".UA_TABLE_USERS."` SET `name` = '".$db->escape($userN)."', `password` = '$userP', `language` = '".$db->escape($userW)."', `user_style` = '".$db->escape($userS)."' WHERE `id` = '$userI' LIMIT 1 ;";

		}
		$result = $db->query($sql);
	}
	else
	{
		// user is level 1 and changing own password
		if ($user->data['id'] != $userI)
		{
			$uniadmin->debug($user->lang['access_denied']);
			$uniadmin->set_vars(array(
			    'template_file' => 'index.html',
			    'display'       => true)
			);
			die();
		}

		$sql = "UPDATE `".UA_TABLE_USERS."` SET `password` = '$userP' WHERE `id` = '$userI' LIMIT 1 ;";
		$result = $db->query($sql);
		if( !$db->affected_rows() )
		{
			$uniadmin->debug(sprintf($user->lang['sql_error_user_modify'],$userN));
		}

		$userN = $user->data['name'];
	}
	$uniadmin->message(sprintf($user->lang['user_modified'],$userN));
}

/**
 * Finalizes creation of a new user
 */
function new_user()
{
	global $db, $uniadmin, $user;

	$userN = $_POST['name'];
	$userP = $_POST['password'];
	$userL = $_POST['level'];
	$userW = $_POST['language'];
	$userS = $_POST['style'];

	if ($user->data['level'] > UA_ID_USER)
	{
		if ($user->data['level'] > UA_ID_POWER)
		{
			$sql = "INSERT INTO `".UA_TABLE_USERS."` ( `name` , `password` , `level` , `language` , `user_style` ) VALUES ( '".$db->escape($userN)."' , '".md5($userP)."' , '$userL' , '".$db->escape($userW)."' , '".$db->escape($userS)."' );";
			$db->query($sql);
			if( !$db->affected_rows() )
			{
				$uniadmin->message(sprintf($user->lang['sql_error_user_add'],$userN));
				return;
			}

			$uniadmin->message(sprintf($user->lang['user_added'],$userN));
		}
		else
		{
			$sql = "INSERT INTO `".UA_TABLE_USERS."` ( `name` , `password` , `level` , `language` , `user_style` ) VALUES ( '".$db->escape($userN)."' , '".md5($userP)."' , '1' , '".$db->escape($userW)."' , '".$db->escape($userS)."' );";
			$db->query($sql);
			if( !$db->affected_rows() )
			{
				$uniadmin->message(sprintf($user->lang['sql_error_user_add'],$userN));
				return;
			}

			$uniadmin->message(sprintf($user->lang['user_added'],$userN));
		}
	}
	else
	{
		$uniadmin->debug($user->lang['access_denied']);
		$uniadmin->set_vars(array(
		    'template_file' => 'index.html',
		    'display'       => true)
		);
		die();
	}
}

/**
 * Deletes a user
 */
function delete_user()
{
	global $db, $uniadmin, $user;

	$userI = $_POST[UA_URI_ID];

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` WHERE `id` = '$userI';";
	$result = $db->query($sql);

	$row = $db->fetch_record($result);
	$userN = $row['name'];

	if ($user->data['level'] == UA_ID_ADMIN || $user->data['id'] == $userI)
	{
		$sql = "DELETE FROM `".UA_TABLE_USERS."` WHERE `id` = '$userI' LIMIT 1";
		$result = $db->query($sql);
		if( !$db->affected_rows() )
		{
			$uniadmin->message(sprintf($user->lang['sql_error_user_delete'],$userN));
			return;
		}

		$uniadmin->message(sprintf($user->lang['user_deleted'],$userN));
	}
	elseif ($user->data['level'] == UA_ID_POWER && $row['level'] == UA_ID_USER )
	{
		$sql = "DELETE FROM `".UA_TABLE_USERS."` WHERE `id` = '$userI' LIMIT 1";
		$result = $db->query($sql);
		if( !$db->affected_rows() )
		{
			$uniadmin->message(sprintf($user->lang['sql_error_user_delete'],$userN));
			return;
		}

		$uniadmin->message(sprintf($user->lang['user_deleted'],$userN));
	}
	else
	{
		$uniadmin->debug($user->lang['access_denied']);
		$uniadmin->set_vars(array(
		    'template_file' => 'index.html',
		    'display'       => true)
		);
		die();
	}
}


?>