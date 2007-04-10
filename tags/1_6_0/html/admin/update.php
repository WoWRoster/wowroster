<?php
$versions['versionDate']['update'] = '$Date: 2006/02/03 14:16:39 $'; 
$versions['versionRev']['update'] = '$Revision: 1.23 $'; 
$versions['versionAuthor']['update'] = '$Author: anthonyb $';

$subdir = '../';
include $subdir.'conf.php';
include $subdir.'lib/luaparser.php';
include $subdir.'lib/wowdb.php';


// Update Triggers
if( $use_update_triggers )
	include( $subdir.'lib/update_trigger_lib.php' );


if (!$authenticated_user)
{
	$wow_group = $upload_group;
	include $subdir.'lib/phpbb.php';
}

// Set $htmlout to 1 to assume request is from a browser
	$htmlout = 1;
// See if UU is requesting this page
if( substr( $_SERVER["HTTP_USER_AGENT"], 0, 11 ) == 'UniUploader' )
	$htmlout = 0;

// Connect to the db
	$wowdb->connect( $db_host, $db_user, $db_passwd, $db_name );
// Set the sql debugger (outputs html comments of sql strings in addition to normal output)
	$wowdb->setSQLDebug($sqldebug);

$header = '<font color="#FFFFFF">'.$updMember[$roster_lang].'</font><br />'."\n";


$filefields[0] = 'CharacterProfiler';
$filefields[1] = 'PvPLog';

foreach ($_FILES as $filefield => $file)
{
	if (substr_count($file['name'],'.gz') > 0)	// If the file is gzipped
	{
		$filename = $file['tmp_name'];
		$tempHandle = gzopen($filename, 'r');			// Uncompress
		$contents = fread($tempHandle,5000000);		// Read up to 5 megs (no way to see total uncompressed length :( )
		fclose($tempHandle);
		$tmpfname = tempnam('','luatemp');				// Create temp file for uncompressed data
		$handle = fopen($tmpfname, 'w');
		fwrite($handle, $contents);								// Write the uncompressed temp file
		fclose($handle);
		$filename = $tmpfname;										// Return the filename (with full path)
	}
	else		// The file is not gzipped
	{
		$filename = $file['tmp_name'];
	}

	for ($i = 0; $i < count($filefields); $i++)	// Itterate through all the possible filefields
	{
		if ($filefields[$i] == $filefield)
		{
			// Filefield is 1 of the 4 we accept.
			$uploadFound = true;
			if ($authenticated_user)
			{
				$data = lua_parse( $filename );

				// If pvp data is there, assign it to $pvpdata
				if (isset($data['PurgeLogData']))
				{
					$pvpdata = $data;
				}

				// If CP data is there, assign it to $myProfile
				if (isset($data['myProfile']))
				{
					$myProfile = $data['myProfile'];
				}
			}
		}
	}
	@unlink($filename);	// Done with the file, dont need it anymore
}

if(isset($_POST['update_trigger']) && $_POST['update_trigger'] == '1')
{
	$run_trigger = 1;
}

// If the roster update password is sent and matches, and $myprofile is there, update the roster
if(isset($_POST['roster_update_password']) && $_POST['roster_update_password'] == $roster_upd_pw && isset( $myProfile ))
{
	$rosterUpdateMessages = processGuildRoster($myProfile);
}

if( isset( $myProfile ) )
{
	$updateMessages = processMyProfile($myProfile);
}

if( isset($pvpdata))
{
	$updatePvPMessages = processPvP($pvpdata);
}

function processPvP($pvpdata)
{
	global $wowdb, $minPvPLogver, $wordings, $roster_lang;
	foreach ($pvpdata['PurgeLogData'] as $realm_name => $realm)
	{
		foreach ($realm as $char_name => $char)
		{
			$query = "SELECT `guild_id` FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '".addslashes($char_name)."' AND `server` = '".addslashes($realm_name)."'";
			$result = $wowdb->query( $query );
			if (mysql_num_rows($result) > 0)
			{
				$row = $wowdb->getrow( $result );
				$guild_id = $row['guild_id'];
				$battles = $char['battles'];
				if( $char['version'] >= $minPvPLogver )
				{
					$output .= "Updating PvP Data for [$char_name]<br />\n";
					$output .= $wowdb->update_pvp2($guild_id, $char_name, $battles);
				}
				else // PvPLog version not high enough
				{
					$output .= "<span style=\"color:#FF0000;\">NOT Updating PvP for [$char_name] - $PvPver</span><br />\n".$wordings[$roster_lang]['PvPLogver_err']."<br />\n<br />\n";
				}
			}
		}
	}
	return $output;
}

function processMyProfile($myProfile)
{
	global $wowdb, $server_name, $guild_name, $use_update_triggers, $minCPver, $wordings, $roster_lang;

	foreach( array_keys( $myProfile ) as $realm_name )
	{
		$realm = $myProfile[$realm_name];
		foreach( array_keys( $realm ) as $char_name )
		{
			if ($char_name != 'Guild')
			{
				if ($server_name == $realm_name)
				{
					$guildInfoOutput = $wowdb->get_guild_info($realm_name,$guild_name);
					$guildInfo = $guildInfoOutput[1];
					$output .= $guildInfoOutput[0];
					if ($guildInfo)
					{
						$char = $realm[$char_name];

						// CP Version Detection, don't allow lower than minVer
						if( $char['CPversion'] >= $minCPver )
						{
							$output .= "Updating character [$char_name]<br />\n";
							$output .= $wowdb->update_char( $guildInfo['guild_id'], $char_name, $char );
	
							// Start update triggers
							if( $use_update_triggers )
							{
								$output .= start_update_trigger($char_name,'char');
							}
						}
						else // CP Version not new enough
						{
							$output .= "<span style=\"color:#FF0000;\">NOT Updating character [$char_name]<br />\nData extract is from CharacterProfiler v".$char['CPversion']."</span><br />\n".$wordings[$roster_lang]['CPver_err']."<br />\n<br />\n";
						}
					}
					else
					{
						$output .= $noGuild[$roster_lang];
					}
				}
			}
		}
	}
	return $output;
}

function processGuildRoster($myProfile)
{
	global $wowdb, $server_name, $guild_name, $run_trigger, $use_update_triggers, $minGPver, $wordings, $roster_lang;

	if (!empty($myProfile))
	{
		foreach(array_keys($myProfile) as $realm_name)
		{
			$realm = $myProfile[$realm_name];
			$guild = $realm['Guild'];
			if($guild)
			{
				$guildName = $guild['Guild'];
				if($guild_name == $guildName)
				{
					// GP Version Detection, don't allow lower than minVer
					if( $guild['GPversion'] >= $minGPver )
					{
						$guildMotd = $guild['Motd'];
						$guildNumMembers = $guild['NumMembers'];
						$guildDateUpdatedUTC = $guild['DateUTC'];
						$guildFaction = $guild['Faction'];
						$GPversion = $guild['GPversion'];

						// make hour between 0 and 23 and minute between 0 and 60
						$guildHour= intval($guild['Hour']);
						$guildMinute= intval($guild['Minute']);

						// take the current time and get the offset. Upload must occur same day that roster was obtained
						$currentTimestamp = mktime($guildHour,$guildMinute,0);
						$currentTime = getDate($currentTimestamp);
						$output .= "Updating Guild [ $guildName ]<br />\n";

						// $guildId = $wowdb->update_guild($realm_name, $guildName, $guildMotd, $guildNumMembers, $currentTime, $guildDateUpdatedUTC);
						$updGuildOutput = $wowdb->update_guild($realm_name, $guildName, $guildFaction, $guildMotd, $guildNumMembers, $currentTime, $guildDateUpdatedUTC, $GPversion);
						$output .= $updGuildOutput[0];
						$guildId = $updGuildOutput[1];
						$guildMembers = $guild['Members'];

						// update the list of guild members
						foreach(array_keys($guildMembers) as $char_name)
						{
							$char = $guildMembers[$char_name];
							$output .= $wowdb->update_guild_member($guildId, $char_name, $char, $currentTimestamp);

							// Start update triggers
							if( $run_trigger && $use_update_triggers )
							{
								$output .= start_update_trigger($char_name,'guild');
							}
						}
						// Remove the members who were not in this list
						$output .= $wowdb->remove_guild_members($guildId, $currentTime);
						$output .= $wowdb->remove_guild_members_id($guildId);
					}
					else
					// GP Version not new enough
					{
						$output .= "<span style=\"color:#FF0000;\">NOT Updating Guild list for $guildName<br />\nData extract is from GuildProfiler v".$guild['GPversion']."</span><br />\n".$wordings[$roster_lang]['GPver_err']."<br />\n<br />\n";
					}
				}	
				else
				{
					$output .= $guild_nameNotFound[$roster_lang];
				}
			}
		}
	}
	return $output;
}

if ( !$authenticated_user )
$authFields = "
                    <tr class=\"membersHeader\">
                      <td colspan=\"2\">$auth_message</td>
                    </tr>
                    <tr class=\"membersRow1\">
                      <td>CMS Username</td>
                      <td><input type=\"text\" name=\"username\"></td>
                    </tr>
                    <tr class=\"membersRow2\">
                      <td>CMS Password</td>
                      <td><input type=\"password\" name=\"password\"></td>
                    </tr>";

if( $pvp_log_allow )
$pvplogInputField = "
                    <tr class=\"membersRow2\">
                      <td>PvPLog.lua (".$wordings[$roster_lang]['optional'].") </td>
                      <td><input type=\"file\" accept=\"PvPLog.lua\" name=\"PvPLog\"></td>
                    </tr>";

if( $use_update_triggers )
$updatetriggerField = "
                    <tr class=\"membersRow1\">
                      <td>Run \"Update Triggers\" on guild update?<br />
                        <small>(Warning: This may appear to \"stall\" the update)</small></td>
                      <td>
                        <input name=\"update_trigger\" type=\"checkbox\" value=\"1\">
                      </td>
                    </tr>";

$inputForm = "
                <form ENCTYPE=\"multipart/form-data\" method=\"POST\">
                  <table class=\"bodyline\" cellspacing=\"1\" cellpadding=\"2\">
$authFields
                    <tr class=\"membersRow1\">
                      <td colspan=\"2\"><small>".$lualocation[$roster_lang]."</small></td>
                    </tr>
                    <tr class=\"membersRow2\">
                      <td>CharacterProfiler.lua (".$wordings[$roster_lang]['required'].")</td>
                      <td><input type=\"file\" accept=\"CharacterProfiler.lua\" name=\"CharacterProfiler\"></td>
                    </tr>
$pvplogInputField
                    <tr class=\"membersRow2\">
                      <td>".$roster_upd_pwLabel[$roster_lang]." (".$wordings[$roster_lang]['optional'].")<br />
                        <small>".$roster_upd_pw_help[$roster_lang]."</small></td>
                      <td><input type=\"password\" name=\"roster_update_password\"></td>
                    </tr>
$updatetriggerField
                    <tr class=\"membersRow1\">
                      <td colspan=\"2\"><center><input type=\"submit\" value=\"".$wordings[$roster_lang]['upload']."\"></center></td>
                    </tr>
                  </table>
                </form>";


// Construct our page
if ($htmlout)
{
	include_once('../roster_header.tpl');
	print $header;
	include_once('updatemenu.php');
	if ($uploadFound)
	{
		print

		$updateMessages.'<br /><br />'.
		$updatePvPMessages.'<br /><br />'.
		$rosterUpdateMessages;

	}
	else
	{
		print $inputForm;
	}
	include_once('../roster_footer.tpl');
}
else	// Dont need the header and footer when responding to UU
{
	if ($uploadFound)
	{
		print

		$auth_message.'<br /><br />'.
		$updateMessages.'<br /><br />'.
		$updatePvPMessages.'<br /><br />'.
		$rosterUpdateMessages;

	}
	else
	{
		// Weren't any files in the upload that correspond to anything in the array of filefields that we take.
		print $nofileUploaded[$roster_lang];
	}
}

?>