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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class update
{
	var $uploadData;
	var $addons;
	var $files;

	/**
	 * Collect info on what files are used
	 */
	function fetchAddonData()
	{
		global $wowdb, $roster_conf;

		// Add roster-used tables
		$this->addons = array();
		$this->files[] = 'characterprofiler';

		if ( $roster_conf['pvp_log_allow'] )
		{
			$this->files[] = 'pvplog';
		}

		if ( !$roster_conf['user_upgrade_triggers'] )
		{
			return '';
		}

		$query = 'SELECT `addon`.`dbname`,`addon`.`fullname`,`trigger`.`file` FROM `'.$wowdb->table('addon_trigger').'` AS `trigger` LEFT JOIN `'.$wowdb->table('addon').'` AS `addon` ON `trigger`.`addon_id` = `addon`.`addon_id` WHERE `trigger`.`active` = 1 AND `addon`.`active` = 1';
		$result = $wowdb->query($query);

		if (!$result)
		{
			$output = 'Could not fetch addon trigger usage. Addon triggers will not be run. MySQL said: '.$wowdb->error()."<br />\n";
		}
		else
		{
			while ($row = $wowdb->fetch_assoc($result))
			{
				$output .= 'Registering '.$row['file'].' for '.$row['fullname']."<br />\n";
				$this->addons[$row['dbname']][] = strtolower($row['file']);
				if (!in_array(strtolower($row['file']),$this->files))
				{
					$this->files[] = strtolower($row['file']);
				}
			}
		}

		return $output;

	}

	/**
	 * Parses the files and put it in $uploadData
	 *
	 * @return string $output | Output messages
	 */
	function parseFiles()
	{
		if (!is_array($_FILES))
		{
			return '<span class="red">Upload failed: No files present</span>'."<br />\n";
		}

		require_once(ROSTER_LIB.'luaparser.php');

		$output = 'Parsing files'."<br />\n".'<ul>';
		foreach ($_FILES as $file)
		{
			if( !empty($file['name']) )
			{
				$filename = explode('.',$file['name']);
				$filebase = strtolower($filename[0]);
				if (in_array($filebase,$this->files))
				{
					// Check if this file is gzipped
					$filemode = (in_array('gz',$filename))?'gz':'';

					// Get start of parse time
					$parse_starttime = explode(' ', microtime() );
					$parse_starttime = $parse_starttime[1] + $parse_starttime[0];

					$this->uploadData[$filebase] = ParseLuaFile( $file['tmp_name'], $filemode );

					// Calculate parse time
					$parse_endtime = explode(' ', microtime() );
					$parse_endtime = $parse_endtime[1] + $parse_endtime[0];
					$parse_totaltime = round(($parse_endtime - $parse_starttime), 2);

					$output .= '<li>Parsed '.$file['name'].' in '.$parse_totaltime.' seconds</li>'."\n";
				}
				else
				{
					$output .= '<li>Did not parse '.$file['name'].'</li>'."\n";
				}
			}
		}
		$output .= '</ul>'."<br />\n";
		return $output;
	}

	/**
	 * Process the files
	 *
	 * @return string $output | Output messages
	 */
	function processFiles()
	{
		global $roster_login, $roster_conf;

		if (!is_array($this->uploadData))
		{
			return '';
		}
		$output = 'Processing files'."<br />\n";
		$gotfiles = array_keys($this->uploadData);
		if (in_array('characterprofiler',$gotfiles))
		{
			if ($roster_login->getAuthorized($roster_conf['auth_updateGP']))
			{
				$output .= $this->processGuildRoster();
				$output .= "<br />\n";
			}

			$output .= $this->processMyProfile();
			$output .= "<br />\n";
		}
		if (in_array('pvplog',$gotfiles))
		{
			$output .= $this->processPvP();
			$output .= "<br />\n";
		}

		if (is_array($this->addons) && count($this->addons)>0)
		{
			foreach ($this->addons as $dbname => $files)
			{
				if (count(array_intersect($gotfiles, $files))>0)
				{
					$addon = getaddon($dbname);
					$uploadData = $this->uploadData;

					$output .= 'Running trigger for '.$addon['dbname']."<br />\n";

					ob_start();
						include(ROSTER_ADDONS.$addon['basename'].'/trigger.php');
						$output .= ob_get_contents();
					ob_end_clean();

					$output .= "<br />\n";
				}
			}
		}
		return $output;

	}

	/**
	 * Update pvplog.lua data
	 *
	 * @return string $output | Output messages
	 */
	function processPvP()
	{
		global $wowdb, $roster_conf, $roster_login, $wordings;

		$wowdb->resetMessages();

		foreach ($this->uploadData['pvplog']['PurgeLogData'] as $realm_name => $realm)
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
						if ( $roster_login->charUpdate($char_name) )
						{
							$output .= "<strong>Updating PvP Data for [<span class=\"orange\">$char_name</span>]</strong>\n";

							$wowdb->update_pvp2($guild_id, $char_name, $battles);
							$output .= "<ul>\n".$wowdb->getMessages()."</ul>\n";
							$wowdb->resetMessages();
						}
						else
						{
							$output .= "<li><strong>Not updating PvP data for [<span class=\"orange\">$char_name</span>]. The auth module said: </strong><br />".$roster_login->getMessage()."\n";
						}
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

	/**
	 * Update Character data
	 *
	 * @return string $output | Output messages
	 */
	function processMyProfile()
	{
		global $wowdb, $roster_conf, $wordings, $roster_login, $guild_info;

		$wowdb->resetMessages();

		$output = 'Updating character profiles'.'<ul>';

		foreach( $this->uploadData['characterprofiler']['myProfile'] as $realm_name => $realm)
		{
			foreach( array_keys( $realm ) as $char_name )
			{
				if ($char_name != 'Guild')
				{
					if ($roster_conf['server_name'] == $realm_name)
					{
						if ($guild_info)
						{
							$char = $realm[$char_name];

							// CP Version Detection, don't allow lower than minVer
							if( $char['DBversion'] >= $roster_conf['minCPver'] )
							{
								if ( $roster_login->charUpdate($char_name) )
								{
									$output .= "<li><strong>Updating Character [<span class=\"orange\">$char_name</span>]</strong>\n";

									$wowdb->update_char( $guild_info['guild_id'], $char_name, $char );
									$output .= "<ul>\n".$wowdb->getMessages()."</ul>\n";
									$wowdb->resetMessages();
								}
								else
								{
									$output .= "<li><strong>Not updating Character [<span class=\"orange\">$char_name</span>]. The auth module said: </strong><br />".$roster_login->getMessage()."\n";
								}
							}
							else // CP Version not new enough
							{
								$output .= "<li><span class=\"red\">NOT Updating character [</span><span class=\"orange\">$char_name</span><span class=\"red\">]</span><br />\n";
								$output .= "Data is from CharacterProfiler v".$char['DBversion']."<br />\n";
								$output .= $wordings[$roster_conf['roster_lang']]['CPver_err']."\n";
							}
						}
						else
						{
							$output .= '<li>'.$wordings[$roster_conf['roster_lang']]['noGuild'];
						}
					}
					else
					{
						$output .= '<li>'.$char_name.' @ '.$realm_name.' '.$wordings[$roster_conf['roster_lang']]['ignored']."\n";
					}
				}
				$output .= "\n";
			}
		}
		$output .= "</ul>\n";
		return $output;
	}

	/**
	 * Update Guild data
	 *
	 * @return string $output | Output messages
	 */
	function processGuildRoster()
	{
		global $wowdb, $roster_conf, $wordings, $roster_login;

		$wowdb->resetMessages();

		if( is_array($this->uploadData['characterprofiler']['myProfile']) )
		{
			foreach( array_keys($this->uploadData['characterprofiler']['myProfile']) as $realm_name )
			{
				// Only allow realms specified in config
				if( $realm_name == $roster_conf['server_name'])
				{
					$realm = $this->uploadData['characterprofiler']['myProfile'][$realm_name];
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
								// get DateExtracted and format for mysql
								list($month,$day,$year,$hour,$minute,$second) = sscanf($guild['DateUTC'],"%d/%d/%d %d:%d:%d");

								// take the current time and get the offset. Upload must occur same day that roster was obtained
								$currentTimestamp = mktime($hour, $minute, $second, $month, $day, $year);
								$currentTime = getDate($currentTimestamp);

								// Update the guild
								$guildId = $wowdb->update_guild($realm_name, $guildName, $currentTime, $guild);
								$guildMembers = $guild['Members'];

								// update the list of guild members
								$guild_output = '';
								foreach(array_keys($guildMembers) as $char_name)
								{
									$char = $guildMembers[$char_name];
									$wowdb->update_guild_member($guildId, $char_name, $char, $currentTimestamp, $realm_name);
									$guild_output .= $wowdb->getMessages();
									$wowdb->resetMessages();
								}

								// Remove the members who were not in this update
								$wowdb->remove_guild_members($guildId);

								// Update account info
								$roster_login->updateAccounts();

								$guild_output .= $wowdb->getMessages();
								$wowdb->resetMessages();
								$guild_output .= "</ul>\n";
								$output .= "<strong>Updating Guild [<span class=\"orange\">$guildName</span>]</strong>\n<ul>\n";
								$output .= "<li><strong>Members Update Summary</strong></li>\n<ul>\n".
									"<li>Updated: ".$wowdb->membersupdated."</li>\n".
									"<li>Added: ".$wowdb->membersadded."</li>\n".
									"<li>Removed: ".$wowdb->membersremoved."</li>\n".
									$roster_login->getMessage().
									"</ul>\n<br />\n";
								$output .= $guild_output;
							}
							else
							// GP Version not new enough
							{
								$output .= "<span class=\"red\">NOT Updating Guild list for [</span><span class=\"orange\">$guildName</span>]<br />\n";
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
					$output .= $this->uploadData['CharacterProfiler']['myProfile'][$realm_name]['Guild']['Guild'].' @ '.$realm_name.' '.$wordings[$roster_conf['roster_lang']]['ignored']."<br />\n";
				}
			}
		}
		return $output;
	}

	/**
	 * Returns the file input fields for all addon files we need.
	 *
	 * @return string $filefields | The HTML, without border
	 */
	function makeFileFields()
	{
		global $roster_conf;
		$filefields = "";
		if (!is_array($this->files) || (count($this->files) == 0)) // Just in case
		{
			return "No files accepted!";
		}
		foreach ($this->files as $file)
		{
			$filefields .=
			'<tr>'."\n".
				"\t".'<td class="membersRow1" '.makeOverlib('<i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables\\\\'.$file.'.lua',$file.'.lua Location','',2).'><img src="'.$roster_conf['img_url'].'blue-question-mark.gif" alt="">'.$file.'.lua</td>'."\n".
				"\t".'<td class="membersRowRight1"><input type="file" accept="'.$file.'.lua" name="'.$file.'"></td>'."\n".
			'</tr>'."\n";
		}
		return $filefields;
	}
}
?>
