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

$script_filename = getlink($module_name.'&amp;file=update');

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


// Files that we accept for upload. Up to the first dot, lowercase.
$filefields[] = 'characterprofiler';
$filefields[] = 'pvplog';
$filefields[] = 'ct_raidtracker';
$filefields[] = 'bookworm';
$filefields[] = 'guildeventmanager2';
$filefields[] = 'groupcalendar';

// Initialize some vars
$uploadFound = false;
$uploadData = null;


if( is_array($_FILES) && !empty($_FILES) )
{
	$uploadFound = true;

	$parseMessages = 'Parsing files'."<br />\n".'<ul>';

	// Loop through each posted file
	foreach( $_FILES as $filefield => $file )
	{
		if( !empty($file['name']) )
		{
			$file_name=$file_ext=$file_type='';

			list( $file_name, $file_ext, $file_type ) = explode( '.',$file['name'] );

			if( in_array(strtolower($file_name),$filefields) )
			{
				// Filefield is 1 of the kind we accept.
				if( $roster_conf['authenticated_user'] )
				{
					// Get start of parse time
					$parse_starttime = explode(' ', microtime() );
					$parse_starttime = $parse_starttime[1] + $parse_starttime[0];

					// Parse the lua file into a php array that we can use
					$data = ParseLuaFile( $file['tmp_name'],$file_type );

					// Calculate parse time
					$parse_endtime = explode(' ', microtime() );
					$parse_endtime = $parse_endtime[1] + $parse_endtime[0];
					$parse_totaltime = round(($parse_endtime - $parse_starttime), 2);

					if( $data )
					{
						$parseMessages .= '<li>Parsed '.$file_name.' in '.$parse_totaltime.' seconds</li>'."\n";
					}
					else
					{
						$parseMessages .= '<li>Error while parsing '.$file_name.' after '.$parse_totaltime.' seconds</li>'."\n";
					}

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
			else
			{
				$parseMessages .= '<li>Did not accept '.$file['name'].'</li>'."\n";
			}
		}
		@unlink($filename);	// Done with the file, we don't need it anymore
	}

	$parseMessages .= '</ul>'."<br />\n";
}

// If the roster update password is sent and matches, and $uploadData['myProfile'] is there, update the roster
if( is_admin() && is_array($uploadData['myProfile']) )
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
		if( $roster_conf['server_name'] == $realm_name )
		{
			$guildInfo = $wowdb->get_guild_info($realm_name,$roster_conf['guild_name']);

			if( $guildInfo && is_array($myProfile[$realm_name]['Character']) )
			{
				$characters = $myProfile[$realm_name]['Character'];

				// Start update triggers
				if( $roster_conf['use_update_triggers'] )
				{
					$output .= start_update_hook('char_pre', $characters);
				}


				foreach( array_keys( $characters ) as $char_name )
				{
					$char = $characters[$char_name];

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
							$output .= start_update_trigger($char_name,'char', $char );
						}
					}
					else // CP Version not new enough
					{
						$output .= "<span class=\"red\">NOT Updating character [$char_name]</span><br />\n";
						$output .= "Data is from CharacterProfiler v".$char['DBversion']."<br />\n";
						$output .= $wordings[$roster_conf['roster_lang']]['CPver_err']."\n";
					}
					$output .= "<br />\n";
				}

				// Start update triggers
				if( $roster_conf['use_update_triggers'] )
				{
					$output .= start_update_hook('char_post', $characters);
				}
			}
			else
			{
				$output .= $wordings[$roster_conf['roster_lang']]['noGuild'];
			}
		}
		else
		{
			$output .= 'Realm: '.$realm_name.' '.$wordings[$roster_conf['roster_lang']]['ignored']."<br />\n";
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

				if( isset($realm['Guild']) && is_array($realm['Guild']) )
				{
					foreach( array_keys($realm['Guild']) as $guild_name )
					{
						// Only allow the guild specified in config
						if( $roster_conf['guild_name'] == $guild_name )
						{
							$guild = $realm['Guild'][$guild_name];

							// GP Version Detection, don't allow lower than minVer
							if( $guild['DBversion'] >= $roster_conf['minGPver'] )
							{
								// take the current time and get the offset. Upload must occur same day that roster was obtained
								$currentTimestamp = $guild['timestamp']['init']['TimeStamp'];
								$currentTime = getDate($currentTimestamp);

								// Update the guild
								$guildId = $wowdb->update_guild($realm_name, $guild_name, $currentTime, $guild);
								$guildMembers = $guild['Members'];

								// update the list of guild members
								$guild_output = "<li><strong>Updating Members</strong>\n<ul>\n";

								// Start update triggers
								if( $roster_conf['use_update_triggers'] )
								{
									$guild_output .= start_update_hook('guild_pre', $guild);
								}

								foreach(array_keys($guildMembers) as $char_name)
								{
									$char = $guildMembers[$char_name];
									$wowdb->update_guild_member($guildId, $char_name, $char, $currentTimestamp, $guild['Ranks']);
									$guild_output .= $wowdb->getMessages();
									$wowdb->resetMessages();

									// Start update triggers
									if( $roster_conf['use_update_triggers'] )
									{
										$guild_output .= start_update_trigger($char_name, 'guild', $guild);
									}
								}
								// Remove the members who were not in this list
								$wowdb->remove_guild_members($guildId, $currentTime);
								$wowdb->remove_guild_members_id($guildId);

								$guild_output .= $wowdb->getMessages()."</ul></li>\n";
								$wowdb->resetMessages();

								// Start update triggers
								if( $roster_conf['use_update_triggers'] )
								{
									$guild_output .= start_update_hook('guild_post', $guild);
								}

								$guild_output .= "</ul>\n";
								$output .= "<strong>Updating Guild [<span class=\"orange\">$guild_name</span>]</strong>\n<ul>\n";
								$output .= "<li><strong>Member Log</strong>\n<ul>\n".
									"<li>Updated: ".$wowdb->membersupdated."</li>\n".
									"<li>Added: ".$wowdb->membersadded."</li>\n".
									"<li>Removed: ".$wowdb->membersremoved."</li>\n".
									"</ul>\n<br />\n";
								$output .= $guild_output;
							}
							else
							// GP Version not new enough
							{
								$output .= "<span class=\"red\">NOT Updating Guild list for $guild_name</span><br />\n";
								$output .= "Data is from GuildProfiler v".$guild['DBversion']."<br />\n";
								$output .= $wordings[$roster_conf['roster_lang']]['GPver_err']."<br />\n";
							}
						}
						else
						{
							$output .= 'Guild: '.$guild_name.' @ Server: '.$realm_name.' '.$wordings[$roster_conf['roster_lang']]['ignored']."<br />\n";
						}
					}
					if( !isset($guild) )
					{
						$output .= sprintf($wordings[$roster_conf['roster_lang']]['guild_nameNotFound'],$guild_name)."<br />\n";
					}

				}
				else
				{
					$output .= '<span class="red">'.$wordings[$roster_conf['roster_lang']]['guild_addonNotFound'].'</span>'."<br />\n";
				}
			}
			else
			{
				$output .= 'Server: '.$realm_name.' '.$wordings[$roster_conf['roster_lang']]['ignored']."<br />\n";
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
                      <td class=\"membersHeaderRight\" colspan=\"2\"><div align=\"center\">".$wordings[$roster_conf['roster_lang']]['lualocation']."</div></td>
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

/* NOT NEEDED FOR ROSTERDF
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
".border('sgray','end')."<br />\n";
*/
	$inputForm .= "<br />\n<input type=\"submit\" value=\"".$wordings[$roster_conf['roster_lang']]['upload']."\">";


	$inputForm .= "\n                </form>";


	include_once(ROSTER_BASE.'roster_header.tpl');
	include_once(ROSTER_LIB.'menu.php');

	if( !$roster_conf['authenticated_user'] )
	{
		print messagebox($wordings[$roster_conf['roster_lang']]['update_disabled'],$wordings[$roster_conf['roster_lang']]['update_page'],'sred');
	}
	else
	{
		print '<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['update_page']."</span><br />\n";

		if( is_admin() )
		{
			print "Logged in Admin<br /><br />\n";
		}

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
					$parseMessages.
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
		}
		else
		{
			print $inputForm;
		}
	}

	include_once(ROSTER_BASE.'roster_footer.tpl');
}
else	// Dont need the header and footer when responding to UU
{
	if( !$roster_conf['authenticated_user'] )
	{
		print $wordings[$roster_conf['roster_lang']]['update_disabled'];
	}
	else
	{
		if( $uploadFound )
		{
			// Strip all html tags out, then print
			print stripAllHtml(
					$parseMessages.
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
}

?>