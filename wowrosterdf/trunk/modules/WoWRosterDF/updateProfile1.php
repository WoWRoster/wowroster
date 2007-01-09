<?php
/******************************
 * WoWRoster.net  Roster
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


//---[ Update File Downloader ]-----------------------------
if( isset($_POST['send_file']) && !empty($_POST['send_file']) && !empty($_POST['data']) )
{
	$file = $_POST['data'];

	header('Content-Type: text/x-delimtext; name="'.$_POST['send_file'].'_log.txt"');
	header('Content-disposition: attachment; filename="'.$_POST['send_file'].'_log.txt"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	echo stripslashes($file);

	exit;
}


require_once( BASEDIR.'modules/'.$module_name.'/settings.php' );
require_once( ROSTER_LIB.'luaparser.php' );


// Update Triggers
if( $roster_conf['use_update_triggers'] )
	include_once( ROSTER_LIB.'update_trigger_lib.php' );


if (!$roster_conf['authenticated_user'])
{
	$wow_group = $roster_conf['upload_group'];
	include_once( ROSTER_LIB.'phpbb.php' );
}

// Set $htmlout to 1 to assume request is from a browser
	$htmlout = 1;
// See if UU is requesting this page
if( substr( $_SERVER['HTTP_USER_AGENT'], 0, 11 ) == 'UniUploader' )
{
	$htmlout = 0;
}
else
{
	// Set the sql debugger (outputs html comments of sql strings in addition to normal output)
	$wowdb->setSQLDebug($roster_conf['sqldebug']);
}


// Files that we accept for upload
$filefields[] = 'CharacterProfiler.lua';
$filefields[] = 'PvPLog.lua';
$filefields[] = 'CT_RaidTracker.lua';
$filefields[] = 'Bookworm.lua';
$filefields[] = 'GuildEventManager2.lua';
$filefields[] = 'GroupCalendar.lua';

// Loop through each posted file
foreach ($_FILES as $filefield => $file)
{
	$filename = $file['tmp_name'];
	$filemode = '';

	if( substr_count($file['name'],'.gz') > 0 )	// If the file is gzipped
		$filemode = 'gz';

	foreach( $filefields as $acceptedfile )	// Itterate through all the possible filefields
	{
		if( $acceptedfile == $file['name'] || $acceptedfile.'.gz' == $file['name'] )
		{
			// Filefield is 1 of the kind we accept.
			if( $roster_conf['authenticated_user'] )
			{
				$uploadFound = true;

				// Parse the lua file into a php array that we can use
				$data = ParseLuaFile( $filename , $filemode );

				// If pvp data is there, assign it to $uploadData['PvpLogData']
				if( isset($data['PurgeLogData']) )
				{
					$uploadData['PvpLogData'] = $data;
				}

				// If CP data is there, assign it to $uploadData['myProfile']
				if( isset($data['myProfile']) )
				{
					$uploadData['myProfile'] = $data['myProfile'];
				}
				// If CT_RaidTracker data is there, assign it to $uploadData['RaidTrackerData']
				if( isset($data['CT_RaidTracker_Options']) )
				{
					$uploadData['RaidTrackerData'] = $data['CT_RaidTracker_RaidLog'];
				}
				
				// If Bookworm data is there, assign it to $uploadData['Bookworm']
				if( isset($data['BookwormBooks']) )
				{
					$uploadData['BookwormBooks'] = $data['BookwormBooks'];
				}
				// If GuildEventManager2 data is there, assign it to $uploadData['GuildEventManagerData']
				if( isset($data['GEM_Events']) )
				{
					$uploadData['GuildEventManagerData'] = $data['GEM_Events'];
				}
				// If GroupCalendar data is there, assign it to $uploadData['GroupCalenderData']
				if( isset($data['gGroupCalendar_Database']) )
				{
					$uploadData['GroupCalendarData'] = $data['gGroupCalendar_Database'];
				}
				// Clear the $data variable
				unset($data);
			}
		}
	}
	@unlink($filename);	// Done with the file, we don't need it anymore
}


// If the roster update password is sent and matches, and $uploadData['myProfile'] is there, update the roster
if( is_array($uploadData['myProfile']) && is_admin() )
{
	$rosterUpdateMessages = processGuildRoster($uploadData['myProfile']);
}

if( is_array($uploadData['myProfile']) )
{
	$updateMessages = processMyProfile($uploadData['myProfile']);
}

if( is_array($uploadData['PvpLogData']) )
{
	$updatePvPMessages = processPvP($uploadData['PvpLogData']);
}

function processPvP($pvpdata)
{
	global $wowdb, $roster_conf, $wordings;

	$wowdb->resetMessages();

	foreach ($pvpdata['PurgeLogData'] as $realm_name => $realm)
	{
		foreach ($realm as $char_name => $char)
		{
			$query = "SELECT `guild_id` FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '".addslashes($char_name)."' AND `server` = '".addslashes($realm_name)."'";
			$result = $wowdb->query( $query );
			if ($wowdb->num_rows($result) > 0)
			{
				$row = $wowdb->fetch_assoc( $result );
				$guild_id = $row['guild_id'];
				$battles = $char['battles'];
				if( $char['version'] >= $roster_conf['minPvPLogver'] )
				{
					$output .= "<strong>Updating PvP Data for [<span class=\"orange\">$char_name</span>]</strong>\n";

					$wowdb->update_pvp2($guild_id, $char_name, $battles);
					$output .= "<ul>\n".$wowdb->getMessages()."</ul>\n";
					$wowdb->resetMessages();
				}
				else // PvPLog version not high enough
				{
					$output .= "<span class=\"red\">NOT Updating PvP for [$char_name] - ".$char['version']."</span><br />\n";
					$output .= $wordings[$roster_conf['roster_lang']]['PvPLogver_err']."\n";
				}
			}
		}
	}
	return $output;
}

function processMyProfile($myProfile)
{
	global $wowdb, $roster_conf, $wordings;

	$wowdb->resetMessages();

	foreach( array_keys( $myProfile ) as $realm_name )
	{
		$realm = $myProfile[$realm_name];
		foreach( array_keys( $realm ) as $char_name )
		{
			if ($char_name != 'Guild')
			{
				if ($roster_conf['server_name'] == $realm_name)
				{
					$guildInfo = $wowdb->get_guild_info($realm_name,$roster_conf['guild_name']);

					if ($guildInfo)
					{
						$char = $realm[$char_name];

						// CP Version Detection, don't allow lower than minVer
						if( $char['DBversion'] >= $roster_conf['minCPver'] )
						{
							$output .= "<strong>Updating Character [<span class=\"orange\">$char_name</span>]</strong>\n";

							$wowdb->update_char( $guildInfo['guild_id'], $char_name, $char );
							$output .= "<ul>\n".$wowdb->getMessages()."</ul>\n";
							$wowdb->resetMessages();

							// Start update triggers
							if( $roster_conf['use_update_triggers'] )
							{
								$output .= start_update_trigger($char_name,'char');
							}
						}
						else // CP Version not new enough
						{
							$output .= "<span class=\"red\">NOT Updating character [$char_name]</span><br />\n";
							$output .= "Data is from CharacterProfiler v".$char['DBversion']."<br />\n";
							$output .= $wordings[$roster_conf['roster_lang']]['CPver_err']."\n";
						}
					}
					else
					{
						$output .= $wordings[$roster_conf['roster_lang']]['noGuild'];
					}
				}
				else
				{
					$output .= $char_name.' @ '.$realm_name.' '.$wordings[$roster_conf['roster_lang']]['ignored']."<br />\n";
				}
			}
			$output .= "<br />\n";
		}
	}
	return $output;
}

function processGuildRoster($myProfile)
{
	global $wowdb, $roster_conf, $wordings;

	$wowdb->resetMessages();

	if( is_array($myProfile) )
	{
		foreach( array_keys($myProfile) as $realm_name )
		{
			// Only allow realms specified in config
			if( $realm_name == $roster_conf['server_name'])
			{
				$realm = $myProfile[$realm_name];
				$guild = $realm['Guild'];
				if( is_array($guild) )
				{
					$guildName = $guild['Guild'];
					// Only allow the guild specified in config
					if( $roster_conf['guild_name'] == $guildName )
					{
						// GP Version Detection, don't allow lower than minVer
						if( $guild['DBversion'] >= $roster_conf['minGPver'] )
						{
							// make hour between 0 and 23 and minute between 0 and 60
							$guildHour= intval($guild['Hour']);
							$guildMinute= intval($guild['Minute']);

							// take the current time and get the offset. Upload must occur same day that roster was obtained
							$currentTimestamp = mktime($guildHour,$guildMinute,0);
							$currentTime = getDate($currentTimestamp);

							// Update the guild
							$guildId = $wowdb->update_guild($realm_name, $guildName, $currentTime, $guild);
							$guildMembers = $guild['Members'];

							// update the list of guild members
							$guild_output = '';
							foreach(array_keys($guildMembers) as $char_name)
							{
								$char = $guildMembers[$char_name];
								$wowdb->update_guild_member($guildId, $char_name, $char, $currentTimestamp);
								$guild_output .= $wowdb->getMessages();
								$wowdb->resetMessages();

								// Start update triggers
								if( $roster_conf['use_update_triggers'] )
								{
									$guild_output .= start_update_trigger($char_name,'guild');
								}
							}
							// Remove the members who were not in this list
							$wowdb->remove_guild_members($guildId, $currentTime);
							$wowdb->remove_guild_members_id($guildId);

							$guild_output .= $wowdb->getMessages();
							$wowdb->resetMessages();
							$guild_output .= "</ul>\n";
							$output .= "<strong>Updating Guild [<span class=\"orange\">$guildName</span>]</strong>\n<ul>\n";
							$output .= "<li><strong>Members Update Summary</strong></li>\n<ul>\n".
								"<li>Updated: ".$wowdb->membersupdated."</li>\n".
								"<li>Added: ".$wowdb->membersadded."</li>\n".
								"<li>Removed: ".$wowdb->membersremoved."</li>\n".
								"</ul>\n<br />\n";
							$output .= $guild_output;
						}
						else
						// GP Version not new enough
						{
							$output .= "<span class=\"red\">NOT Updating Guild list for $guildName</span><br />\n";
							$output .= "Data is from GuildProfiler v".$guild['DBversion']."<br />\n";
							$output .= $wordings[$roster_conf['roster_lang']]['GPver_err']."<br />\n";
						}
					}
					else
					{
						$output .= str_replace('*GUILDNAME*',$guildName,$wordings[$roster_conf['roster_lang']]['guild_nameNotFound'])."<br />\n";
					}
				}
				else
				{
					$output .= '<span class="red">'.$wordings[$roster_conf['roster_lang']]['guild_addonNotFound'].'</span>'."<br />\n";
				}
			}
			else
			{
				$output .= $myProfile[$realm_name]['Guild']['Guild'].' @ '.$realm_name.' '.$wordings[$roster_conf['roster_lang']]['ignored']."<br />\n";
			}
		}
	}
	return $output;
}

// Create the auth fields if needed
if( !$roster_conf['authenticated_user'] )
{
	if( $auth_message != '' )
	{
		$auth_message = "<tr>\n<td class=\"membersRowRight1\" colspan=\"2\">$auth_message</td>\n</tr>\n";
	}

	$authFields = border('syellow','start','Upload Authorization')."
                  <table class=\"wowroster\" cellspacing=\"0\" cellpadding=\"0\">
$auth_message
                    <tr>
                      <td class=\"membersRow2\">Username</td>
                      <td class=\"membersRowRight2\"><input type=\"text\" name=\"username\"></td>
                    </tr>
                    <tr>
                      <td class=\"membersRow2\">Password</td>
                      <td class=\"membersRowRight2\"><input type=\"password\" name=\"password\"></td>
                    </tr>
                  </table>".border('syellow','end')."<br />\n";
}

// Create the PvPLog input field if selected in conf
if( $roster_conf['pvp_log_allow'] )
{
	$pvplogInputField = "
                    <tr>
                      <td class=\"membersRow2\" style=\"cursor:help;\" onmouseover=\"overlib('<b>PvPLog.lua</b> ".$wordings[$roster_conf['roster_lang']]['filelocation']."\\\\PvPLog.lua',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> PvPLog.lua</td>
                      <td class=\"membersRowRight2\"><input type=\"file\" accept=\"PvPLog.lua\" name=\"PvPLog\"></td>
                    </tr>";
}


// Construct the entire upload form
$inputForm = "
                <form action=\"".getlink('&amp;file=update')."\" enctype=\"multipart/form-data\" method=\"POST\" onsubmit=\"submitonce(this)\">
$authFields
".border('syellow','start','Upload Files')."
                  <table class=\"wowroster\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                      <td class=\"membersRowRight1\" colspan=\"2\" align=\"center\"><div align=\"center\"><small>".$wordings[$roster_conf['roster_lang']]['lualocation']."</small></div></td>
                    </tr>
                    <tr>
                      <td class=\"membersRow2\" style=\"cursor:help;\" onmouseover=\"overlib('<b>CharacterProfiler.lua</b> ".$wordings[$roster_conf['roster_lang']]['filelocation']."\\\\CharacterProfiler.lua',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> CharacterProfiler.lua</td>
                      <td class=\"membersRowRight2\"><input type=\"file\" accept=\"CharacterProfiler.lua\" name=\"CharacterProfiler\"></td>
                    </tr>
$pvplogInputField
                    <tr>
                      <td class=\"membersRowRight1\" colspan=\"2\" align=\"center\"><div align=\"center\"><input type=\"submit\" value=\"".$wordings[$roster_conf['roster_lang']]['upload']."\"></div></td>
                    </tr>
                  </table>
".border('syellow','end')."
                </form>";


// Construct our page

// Get SQL messages
$sqlstringout = $wowdb->getSQLStrings();
$errorstringout = $wowdb->getErrors();
if ($userinfo['username'] == 'Anonymous') {
    include_once(ROSTER_BASE.'roster_header.tpl');
	include_once(ROSTER_LIB.'menu.php');
	print '<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['upprofile']."</span><br /><br />\n";
	 print
			'<div >
				'.border('sred','start').'
				<center>Must Be Logged in to use this service!</center>
				'.border('sred','end').'
			</div>';
    include_once(ROSTER_BASE.'roster_footer.tpl');
	}

elseif( $htmlout )
{
	$header_title = $wordings[$roster_conf['roster_lang']]['upprofile'];
	include_once(ROSTER_BASE.'roster_header.tpl');
	include_once(ROSTER_LIB.'menu.php');
	print '<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['upprofile']."</span><br /><br />\n";

	if( $uploadFound )
	{
		// print the error messages
		if( !empty($errorstringout) )
		{
			print
			'<div id="errorCol" style="display:inline;">
				'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").'
				'.border('sred','end').'
			</div>
			<div id="error" style="display:none">
			'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").
			$errorstringout.
			border('sred','end').
			'</div>';

			// Print the downloadable errors separately so we can generate a download
			print "<br />\n";
			print '<form method="post" action="update.php" name="post">'."\n";
			print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($errorstringout)).'" />'."\n";
			print '<input type="hidden" name="send_file" value="error" />'."\n";
			print '<input type="submit" name="download" value="Save Error Log" />'."\n";
			print '</form>';
			print "<br />\n";
		}

		// Print the update messages
		print
			border('syellow','start','Update Log').
			'<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:300px;width:550px;overflow:auto;">'.
				$updateMessages.
				$updatePvPMessages.
				$rosterUpdateMessages.
			'</div>'.
			border('syellow','end');

		// Print the downloadable messages separately so we can generate a download
		print "<br />\n";
		print '<form method="post" action="update.php" name="post">'."\n";
		print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($updateMessages.$updatePvPMessages.$rosterUpdateMessages)).'" />'."\n";
		print '<input type="hidden" name="send_file" value="update" />'."\n";
		print '<input type="submit" name="download" value="Save Update Log" />'."\n";
		print '</form>';
		print "<br />\n";


		// Print the SQL Messages
		if( $roster_conf['sqldebug'] )
		{
			print
			'<div id="sqlDebugCol" style="display:inline;">
				'.border('sgray','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('sqlDebugCol','sqlDebug')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />SQL Queries</div>").'
				'.border('sgray','end').'
			</div>
			<div id="sqlDebug" style="display:none">
			'.border('sgreen','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('sqlDebugCol','sqlDebug')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />SQL Queries</div>").'
			<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:300px;width:560px;overflow:auto;">'.
				nl2br(sql_highlight($sqlstringout)).
			'</div>
			'.border('sgreen','end').
			'</div>';


			// Print the downloadable sql separately so we can generate a download
			print "<br />\n";
			print '<form method="post" action="update.php" name="post">'."\n";
			print '<input type="hidden" name="data" value="'.htmlspecialchars($sqlstringout).'" />'."\n";
			print '<input type="hidden" name="send_file" value="sql" />'."\n";
			print '<input type="submit" name="download" value="Save SQL Log" />'."\n";
			print '</form>';
		}
	}
	else
	{
		print $inputForm;
	}

	include_once(ROSTER_BASE.'roster_footer.tpl');
}
else	// Dont need the header and footer when responding to UU
{
	if( $uploadFound )
	{
		// Strip all html tags out, then print
		print stripAllHtml(
				$auth_message.
				$updateMessages.
				$updatePvPMessages.
				$rosterUpdateMessages
			);

	}
	else
	{
		// Weren't any files in the upload that correspond to anything in the array of filefields that we take.
		print $wordings[$roster_conf['roster_lang']]['nofileUploaded'];
	}
}

?>