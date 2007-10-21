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

require_once( 'settings.php' );
require_once( ROSTER_LIB.'luaparser.php' );

$script_filename = 'update.php';

// Update Triggers
if( $roster_conf['use_update_triggers'] )
{
	include_once( ROSTER_LIB.'update_trigger_lib.php' );
}


// Set $htmlout to 1 to assume request is from a browser
	$htmlout = 1;
// See if UU is requesting this page
if( eregi('uniuploader',$_SERVER['HTTP_USER_AGENT']) )
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

// Initialize some vars
$uploadFound = false;
$uploadData = null;

// Loop through each posted file
foreach ($_FILES as $filefield => $file)
{
	$filename = $file['tmp_name'];
	$filemode = '';

	if( substr_count($file['name'],'.gz') > 0 )	// If the file is gzipped
	{
		$filemode = 'gz';
	}

	foreach( $filefields as $acceptedfile )	// Itterate through all the possible filefields
	{
		if( strtolower($acceptedfile) == strtolower($file['name']) || strtolower($acceptedfile.'.gz') == strtolower($file['name']) )
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
if( is_array($uploadData['myProfile']) && isset($_POST['password']) )
{
	if( ( md5($_POST['password']) == $roster_conf['roster_upd_pw'] ) ||
		( $_POST['password'] == $roster_conf['roster_upd_pw'] ) ||
		( $roster_conf['phpbb_authenticated_admin'] )
	  )
	{
		$rosterUpdateMessages = processGuildRoster($uploadData['myProfile']);
	}
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
							$output .= "<li><strong>Member Log</strong></li>\n<ul>\n".
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
						$output .= sprintf($wordings[$roster_conf['roster_lang']]['guild_nameNotFound'],$guildName)."<br />\n";
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


// Get SQL messages
$sqlstringout = $wowdb->getSQLStrings();
$errorstringout = $wowdb->getErrors();


/**
 * Make the html Upload page
 */

if( $htmlout )
{
	// Create the PvPLog input field if selected in conf
	if( $roster_conf['pvp_log_allow'] )
	{
		$pvplogInputField = "
                    <tr>
                      <td class=\"membersRow1\" style=\"cursor:help;\" onmouseover=\"overlib('<b>PvPLog.lua</b> ".$wordings[$roster_conf['roster_lang']]['filelocation']."\\\\PvPLog.lua',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> PvPLog.lua</td>
                      <td class=\"membersRowRight1\"><input type=\"file\" accept=\"PvPLog.lua\" name=\"PvPLog\"></td>
                    </tr>";
	}
	else
	{
		$pvplogInputField = '';
	}

	// Create the RaidTracker input field if addon exists
	if(file_exists(ROSTER_ADDONS.'RaidTracker'))
	{
		$raidtrackerInputField = "
                    <tr>
                      <td class=\"membersRow1\" style=\"cursor:help;\" onmouseover=\"overlib('<b>CT_RaidTracker.lua</b> ".$wordings[$roster_conf['roster_lang']]['filelocation']."\\\\CT_RaidTracker.lua',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> CT_RaidTracker.lua</td>
                      <td class=\"membersRowRight1\"><input type=\"file\" accept=\"CT_RaidTracker.lua\" name=\"RaidTracker\"></td>
                    </tr>";
	}
	else
	{
		$raidtrackerInputField = '';
	}

	// Create the EventCalendar input field if addon exists
	if(file_exists(ROSTER_ADDONS.'EventCalendar'))
	{
		$eventcalendarInputField = "
                    <tr>
                      <td class=\"membersRow1\" style=\"cursor:help;\" onmouseover=\"overlib('<b>GuildEventManager2.lua or GroupCalendar.lua</b> ".$wordings[$roster_conf['roster_lang']]['filelocation']."\\\\',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> EventCalendar</td>
                      <td class=\"membersRowRight1\"><input type=\"file\" accept=\"GuildEventManager2.lua,GroupCalendar.lua\" name=\"EventCalendar\"></td>
                    </tr>";
	}
	else
	{
		$eventcalendarInputField = '';
	}

	// Create the Bookworm input field if addon exists
	if(file_exists(ROSTER_ADDONS.'bookworm'))
	{
		$bookwormInputField = "
                    <tr>
                      <td class=\"membersRow1\" style=\"cursor:help;\" onmouseover=\"overlib('<b>bookworm.lua</b> ".$wordings[$roster_conf['roster_lang']]['filelocation']."\\\\bookworm.lua',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> bookworm.lua</td>
                      <td class=\"membersRowRight1\"><input type=\"file\" accept=\"bookworm.lua\" name=\"bookworm\"></td>
                    </tr>";
	}
	else
	{
		$bookwormInputField = '';
	}


	// Construct the entire upload form
	$inputForm = "
                <form action=\"$script_filename\" enctype=\"multipart/form-data\" method=\"POST\" onsubmit=\"submitonce(this)\">
".border('syellow','start','Upload Files')."
                  <table class=\"bodyline\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                      <th class=\"membersHeaderRight\" colspan=\"2\"><div align=\"center\">".$wordings[$roster_conf['roster_lang']]['lualocation']."</div></th>
                    </tr>
                    <tr>
                      <td class=\"membersRow1\" style=\"cursor:help;\" onmouseover=\"overlib('<b>CharacterProfiler.lua</b> ".$wordings[$roster_conf['roster_lang']]['filelocation']."\\\\CharacterProfiler.lua',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> CharacterProfiler.lua</td>
                      <td class=\"membersRowRight1\"><input type=\"file\" accept=\"CharacterProfiler.lua\" name=\"CharacterProfiler\"></td>
                    </tr>
$pvplogInputField
$eventcalendarInputField
$raidtrackerInputField
$bookwormInputField
                  </table>
".border('syellow','end');


	$inputForm .= "
<br />
<br />
".border('sgray','start','GuildProfiler User Only')."
                  <table class=\"bodyline\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                      <td class=\"membersRow1\" style=\"cursor:help;\" onmouseover=\"overlib('".$wordings[$roster_conf['roster_lang']]['roster_upd_pw_help']."',CAPTION,'".$wordings[$roster_conf['roster_lang']]['roster_upd_pwLabel']."',WRAP,RIGHT);\" onmouseout=\"return nd();\"><img src=\"".$roster_conf['img_url']."blue-question-mark.gif\" alt=\"\" /> ".$wordings[$roster_conf['roster_lang']]['roster_upd_pwLabel']."</td>
                      <td class=\"membersRowRight1\"><input type=\"password\" name=\"password\"></td>
                    </tr>
                  </table>
".border('sgray','end')."<br />
                  <input type=\"submit\" value=\"".$wordings[$roster_conf['roster_lang']]['upload']."\">";


	$inputForm .= "\n                </form>";


	include_once(ROSTER_BASE.'roster_header.tpl');
	include_once(ROSTER_LIB.'menu.php');
	print '<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['update_page']."</span><br /><br />\n";

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
			print '<form method="post" action="'.$script_filename.'" name="post">'."\n";
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
		print '<form method="post" action="'.$script_filename.'" name="post">'."\n";
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
				nl2br($sqlstringout).
			'</div>
			'.border('sgreen','end').
			'</div>';


			// Print the downloadable sql separately so we can generate a download
			print "<br />\n";
			print '<form method="post" action="'.$script_filename.'" name="post">'."\n";
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
		if( !$roster_conf['authenticated_user'] )
		{
			print $wordings[$roster_conf['roster_lang']]['update_disabled'];
		}
		else
		{
			// Strip all html tags out, then print
			print stripAllHtml(
					$updateMessages.
					$updatePvPMessages.
					$rosterUpdateMessages
				);
		}

	}
	else
	{
		// Weren't any files in the upload that correspond to anything in the array of filefields that we take.
		print $wordings[$roster_conf['roster_lang']]['nofileUploaded'];
	}
}

?>