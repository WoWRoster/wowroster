<?php
$versions['versionDate']['phpbb'] = '$Date: 2006/01/01 13:44:34 $'; 
$versions['versionRev']['phpbb'] = '$Revision: 1.4 $'; 
$versions['versionAuthor']['phpbb'] = '$Author: zanix $';

/***************************************************************************
 *                                phpbb.php
 *                            -------------------
 *   begin                : Wednesday Jan 12, 2005
 *   copyright            : John Lavoie
 *   email                : trombone8vb@galuvian.net
 *   derived  from        : login.php (C) 2001 The phpBB Group
 *
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB', true);
include_once "../conf.php"; // wowprofiler conf.php file
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//


$php_userid = "";
// Prepare POST data
  if ( isset($HTTP_POST_VARS['username']) && isset($HTTP_POST_VARS['password']))
  {
    $username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
    $password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

    // Select the data for the user trying to log in
    $sql = "SELECT user_id, username, user_password, user_active, user_level
      FROM " . USERS_TABLE . "
      WHERE username = '" . str_replace("\\'", "''", $username) . "'";


    if ( !($result = $db->sql_query($sql)) )
    {
      message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
    }

    if( $row = $db->sql_fetchrow($result) )
    {
      // Compare the provided password with the one stored in the database
      if( md5($password) == $row['user_password'] && $row['user_active'] )
      {
          // Is Valid phpBB user
          $php_userid = $row['user_id'];
      }
      else
      {
        $auth_message = "Invalid user...";
        // Login failed
        $authenticated_user = 0;
      }
    }
  }
  else
  {
    if ($userdata['session_logged_in'])
    {
      $username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';
      $sql = "SELECT user_id
        FROM " . USERS_TABLE . "
        WHERE username = '" . $username . "'";
      if ( !($result = $db->sql_query($sql)) )
      {
        message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
      }

      if( $row = $db->sql_fetchrow($result) )
      {
          // Is Valid phpBB user
          $php_userid = $row['user_id'];
      }

    }
    else
    {
    	$auth_message =  "No username passed in";
    }
  }
  if ($php_userid != "")
  {
      // Verify user belongs to correct group
      $sql = "SELECT user_id
        FROM " . USER_GROUP_TABLE . "
        WHERE user_id =  '" . $php_userid . "'
        AND group_id in ($wow_group)";

      if ( !($result = $db->sql_query($sql)) )
      {
        message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
      }

      if( $row = $db->sql_fetchrow($result) )
      {
        // User is a member of the specified group
        $auth_message =  "User verified... logging in.";
        $authenticated_user = 1;

      }
      else
      {
        $auth_message =  "Invalid user... insufficient permissions.";
        $authenticated_user = 0;
      }
  }

?>