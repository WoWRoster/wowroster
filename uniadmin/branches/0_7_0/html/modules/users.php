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

if( $user->data['level'] == UA_ID_ANON )
{
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
	global $db, $uniadmin, $user;

	if( $user->data['level'] > UA_ID_USER )
	{
		$can_add_edit = true;
	}
	else
	{
		$can_add_edit = false;
	}

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` ORDER BY `level` DESC, `name` ASC;";
	$result = $db->query($sql);


	$table = '<table class="ua_table" width="50%" align="center">
	<tr>
		<th colspan="5" class="table_header">'.$user->lang['current_users'].'</th>
	</tr>
	<tr>
		<td class="data_header">'.$user->lang['username'].'</td>
		<td class="data_header">'.$user->lang['userlevel'].'</td>
		<td class="data_header">'.$user->lang['language'].'</td>
		<td class="data_header">'.$user->lang['modify'].'</td>
		<td class="data_header">'.$user->lang['delete'].'</td>
	</tr>
';


	while ($row = $db->fetch_record($result))
	{
		$td_class = 'data'.$uniadmin->switch_row_class();

		$userN = $row['name'];
		$userL = $row['level'];
		$userI = $row['id'];
		$userW = $row['language'];

		$table .= '<tr>';
		if( strtoupper($userN) == strtoupper($user->data['name']) || $can_add_edit )
		{
			$table .= '
			<td class="'.$td_class.'" valign="top">'.$userN.'</td>
			<td class="'.$td_class.'" valign="top">'.$userL.'</td>
			<td class="'.$td_class.'"  valign="top">'.$userW.'</td>
';
			if( strtoupper($userN) == strtoupper($user->data['name']) || $user->data['level'] > UA_ID_POWER )
			{
				$table .= '
			<td class="'.$td_class.'" valign="top"><form name="ua_edituser_'.$userI.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input type="hidden" name="'.UA_URI_OP.'" value="edit" />
				<input type="hidden" name="'.UA_URI_ID.'" value="'.$userI.'" />
				<input class="submit" style="color:green;" type="submit" value="'.$user->lang['modify'].'" />
				</form></td>
			<td class="'.$td_class.'" valign="top"><form name="ua_deleteuser_'.$userI.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input type="hidden" name="'.UA_URI_OP.'" value="delete" />
				<input type="hidden" name="'.UA_URI_ID.'" value="'.$userI.'" />
				<input class="submit" style="color:red;" type="submit" value="'.$user->lang['delete'].'" />
				</form></td>';
			}
			elseif( $user->data['level'] == UA_ID_POWER && $userL == UA_ID_USER )
			{
				$table .= '
			<td class="'.$td_class.'" valign="top"><form name="ua_edituser_'.$userI.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input type="hidden" name="'.UA_URI_OP.'" value="edit" />
				<input type="hidden" name="'.UA_URI_ID.'" value="'.$userI.'" />
				<input class="submit" style="color:green;" type="submit" value="'.$user->lang['modify'].'" />
				</form></td>
			<td class="'.$td_class.'" valign="top"><form name="ua_deleteuser_'.$userI.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input type="hidden" name="'.UA_URI_OP.'" value="delete" />
				<input type="hidden" name="'.UA_URI_ID.'" value="'.$userI.'" />
				<input class="submit" style="color:red;" type="submit" value="'.$user->lang['delete'].'" />
				</form></td>';
			}

		}
		else
		{
			$table .= '
			<td class="'.$td_class.'"  valign="top">'.$userN.'</td>
			<td class="'.$td_class.'"  valign="top">'.$userL.'</td>
			<td class="'.$td_class.'"  valign="top">'.$userW.'</td>
			<td class="'.$td_class.'"  valign="top"></td>
			<td class="'.$td_class.'"  valign="top"></td>';
		}
		$table .= '</tr>';
	}
	$table .= '</table>';


	if( $user->data['level'] > UA_ID_USER )
	{
		display_page("$table<br />".add_user_table(),$user->lang['title_users']);
	}
	else
	{
		display_page($table,$user->lang['title_users']);
	}
}

/**
 * Builds form to add users
 *
 * @return string
 */
function add_user_table( )
{
	global $user;

	$add_form = '
<form name="ua_adduser" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<table class="ua_table" align="center">
		<tr>
			<th colspan="2" class="table_header">'.$user->lang['add_user'].'</th>
		</tr>
		<tr>
			<td class="data1">'.$user->lang['username'].':</td>
			<td class="data1"><input class="input" type="text" name="name" value="" size="20" maxlength="30" /></td>
		</tr>
		<tr>
			<td class="data2">'.$user->lang['password'].':</td>
			<td class="data2"><input class="input" type="password" name="password" value="" size="20" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="data1">';

	if( $user->data['level'] > UA_ID_POWER )
	{
		$add_form .= $user->lang['userlevel'].':</td>
		<td class="data1">'.level_select();
	}
	else
	{
		$add_form .= $user->lang['userlevel'].':</td>
		<td class="data1">['.UA_ID_USER.']<input type="hidden" name="level" value="'.UA_ID_USER.'" />';
	}

	$add_form .= '</td>
		</tr>
		<tr>
			<td class="data1">'.$user->lang['language'].':</td>
			<td class="data2">'.lang_select().'</td>
		</tr>
		<tr>
			<td class="data2"></td>
			<td class="data2"><input class="submit" type="submit" value="'.$user->lang['add_user'].'" /></td>
		</tr>
	</table>
	<input type="hidden" value="new" name="'.UA_URI_OP.'" />
</form>';

	return $add_form;
}

/**
 * Builds the edit user table
 */
function modify_user()
{
	global $db, $uniadmin, $user;

	$uid = $_POST[UA_URI_ID];

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` WHERE `id` = '$uid';";
	$result = $db->query($sql);

	$row = $db->fetch_record($result);
	$userN = $row['name'];
	$userL = $row['level'];
	$userW = $row['language'];

	$form = '
<form name="ua_modifyuser" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" value="finalize" name="'.UA_URI_OP.'" />
	<input type="hidden" value="'.$uid.'" name="'.UA_URI_ID.'" />
	<table class="ua_table" align="center">
		<tr>
			<th colspan="2" class="table_header">'.$user->lang['modify_user'].'</th>
		</tr>
';
	if( $user->data['level'] > UA_ID_USER )
	{
		$form .= '		<tr>
			<td class="data1">'.$user->lang['change_username'].':</td>
			<td class="data1"><input class="input" type="text" name="name" value="'.$userN.'" size="20" maxlength="30" /></td>
		</tr>';
		if ($user->data['level'] > UA_ID_POWER)
		{
			$form .= '		<tr>
			<td class="data2">'.$user->lang['change_userlevel'].':</td>
			<td class="data2">'.level_select($userL).'</td>
		</tr>';
		}
		else
		{
			$form .= '		<tr>
			<td class="data1">'.$user->lang['userlevel'].':</td>
			<td class="data1">['.$userL.']</td>
		</tr>';
		}
	}
	else
	{
		$form .= '		<tr>
			<td class="data1">'.$user->lang['username'].':</td>
			<td class="data1">['.$userN.']</td>
		</tr>
		<tr>
			<td class="data2">'.$user->lang['userlevel'].':</td>
			<td class="data2">['.$userL.']</td>
		</tr>';
	}
	$form .= '
		<tr>
			<td class="data1">'.$user->lang['change_password'].':</td>
			<td class="data1"><input class="input" type="password" name="password" value="" size="20" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="data2">'.$user->lang['change_language'].':</td>
			<td class="data2">'.lang_select($userW).'</td>
		</tr>
		<tr>
			<td class="data1">&nbsp;</td>
			<td class="data1"><input class="submit" type="submit" value="'.$user->lang['modify_user'].'" /></td>
		</tr>
	</table>
</form>';

	display_page($form,$user->lang['modify_user']);
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
	$userW = $_POST['language'];

	$sql = "SELECT * FROM `".UA_TABLE_USERS."` WHERE `name` = '$userN';";
	$result = $db->query($sql);

	$row = $db->fetch_record($result);
	$old_pass_hash = $row['password'];

	if ($userP == '')
	{
		$userP = $old_pass_hash;
	}
	else
	{
		$userP = md5($userP);
	}

	if ($user->data['level'] > UA_ID_USER)
	{
		if ($user->data['id'] != $userI)
		{
			if ($user->data['level'] < UA_ID_ADMIN)
			{
				$userL = UA_ID_USER;
			}
			$sql = "UPDATE `".UA_TABLE_USERS."` SET `name` = '".$db->escape($userN)."', `level` = '$userL', `password` = '$userP', `language` = '".$db->escape($userW)."' WHERE `id` = '$userI' LIMIT 1 ;";
		}
		else
		{
			$sql = "UPDATE `".UA_TABLE_USERS."` SET `name` = '".$db->escape($userN)."', `password` = '$userP', `language` = '".$db->escape($userW)."' WHERE `id` = '$userI' LIMIT 1 ;";

		}
		$result = $db->query($sql);
	}
	else
	{
		// user is level 1 and changing own password
		if ($user->data['id'] != $userI)
		{
			$uniadmin->debug('die hacker!');
			die_ua();
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

	if ($user->data['level'] > UA_ID_USER)
	{
		if ($user->data['level'] > UA_ID_POWER)
		{
			$sql = "INSERT INTO `".UA_TABLE_USERS."` ( `name` , `password` , `level` , `language` ) VALUES ( '".$db->escape($userN)."' , '".md5($userP)."' , '$userL' , '".$db->escape($userW)."' );";
			$db->query($sql);
			if( !$db->affected_rows() )
			{
				$uniadmin->debug(sprintf($user->lang['sql_error_user_add'],$userN));
				return;
			}

			$uniadmin->message(sprintf($user->lang['user_added'],$userN));
		}
		else
		{
			$sql = "INSERT INTO `".UA_TABLE_USERS."` ( `name` , `password` , `level` , `language` ) VALUES ( '".$db->escape($userN)."' , '".md5($userP)."' , '1' , '".$db->escape($userW)."' );";
			$db->query($sql);
			if( !$db->affected_rows() )
			{
				$uniadmin->debug(sprintf($user->lang['sql_error_user_add'],$userN));
				return;
			}

			$uniadmin->message(sprintf($user->lang['user_added'],$userN));
		}
	}
	else
	{
		$uniadmin->debug('die hacker!');
		die_ua();
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
			$uniadmin->debug(sprintf($user->lang['sql_error_user_delete'],$userN));
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
			$uniadmin->debug(sprintf($user->lang['sql_error_user_delete'],$userN));
			return;
		}

		$uniadmin->message(sprintf($user->lang['user_deleted'],$userN));
	}
	else
	{
		$uniadmin->debug('die hacker!');
		die_ua();
	}
}


?>