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

		if ( !isset($roster_conf['user_upgrade_triggers']) )
		{
			return '';
		}

		$query = 'SELECT `addon`.`basename`,`addon`.`fullname`,`trigger`.`file` '.
			'FROM `'.$wowdb->table('addon_trigger').'` AS `trigger` '.
			'LEFT JOIN `'.$wowdb->table('addon').'` AS `addon` '.
				'ON `trigger`.`addon_id` = `addon`.`addon_id` '.
			'WHERE `trigger`.`active` = 1 AND `addon`.`active` = 1';
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
				$this->addons[$row['basename']][] = strtolower($row['file']);
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
		global $act_words;
		if (!is_array($_FILES))
		{
			return '<span class="red">Upload failed: No files present</span>'."<br />\n";
		}

		require_once(ROSTER_LIB.'luaparser.php');

		$output = $act_words['parsing_files']."<br />\n".'<ul>';
		foreach ($_FILES as $file)
		{
			if( !empty($file['name']) )
			{
				$filename = explode('.',$file['name']);
				$filebase = strtolower($filename[0]);

				if( in_array($filebase,$this->files) )
				{
					// Get start of parse time
					$parse_starttime = explode(' ', microtime() );
					$parse_starttime = $parse_starttime[1] + $parse_starttime[0];

					$luahandler = new lua();
					$data = $luahandler->luatophp( $file['tmp_name'] );

					// Calculate parse time
					$parse_endtime = explode(' ', microtime() );
					$parse_endtime = $parse_endtime[1] + $parse_endtime[0];
					$parse_totaltime = round(($parse_endtime - $parse_starttime), 2);

					if( $data )
					{
						$output .= '<li>'.sprintf($act_words['parsed_time'],$filename[0],$parse_totaltime).'</li>'."\n";
						$this->uploadData[$filebase] = $data;
					}
					else
					{
						$output .= '<li>'.sprintf($act_words['error_parsed_time'],$filename[0],$parse_totaltime).'</li>'."\n";
						$output .= ($luahandler->error !='' ? '<li>'.$luahandler->error().'</li>'."\n" : '');
					}
					unset($luahandler);
				}
				else
				{
					$output .= '<li>'.sprintf($act_words['upload_not_accept'],$file['name']).'</li>'."\n";
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
		global $roster_conf;

		if (!is_array($this->uploadData))
		{
			return '';
		}
		$output = 'Processing files'."<br />\n";
		$gotfiles = array_keys($this->uploadData);
		if (in_array('characterprofiler',$gotfiles))
		{
			if( ( md5($_POST['password']) == $roster_conf['roster_upd_pw'] ) ||
				( $_POST['password'] == $roster_conf['roster_upd_pw'] ) ||
				( isset($roster_conf['phpbb_authenticated_admin']) )
			  )
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
			foreach ($this->addons as $basename => $files)
			{
				if (count(array_intersect($gotfiles, $files))>0)
				{
					ob_start();
						$trigger = $addon['basename'];
						$triggerfile = $triggerPath.DIR_SEP.$trigger.DIR_SEP.'trigger.php';
						$triggerconf = $triggerPath.DIR_SEP.$trigger.DIR_SEP.'conf.php';
						$addonDir = $triggerPath.DIR_SEP.$trigger.DIR_SEP;

						if ( file_exists($triggerfile) )
						{
							if ( file_exists($triggerconf) )
								include_once( $triggerconf );

							include( $triggerfile );
						}
						$output .= ob_get_contents();
					ob_end_clean();
				}
			}
		}
		return $output;

	}

	/**
	 * Process PvPLog data
	 */
	function processPvP()
	{
		global $wowdb, $roster_conf, $act_words;

		$pvpdata = $this->uploadData['pvplog'];
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
						$output .= '<strong>'.sprintf($act_words['upload_data'],'PvPLog',$char_name)."</strong>\n";

						$wowdb->update_pvp2($guild_id, $char_name, $battles);
						$output .= "<ul>\n".$wowdb->getMessages()."</ul>\n";
						$wowdb->resetMessages();
					}
					else // PvPLog version not high enough
					{
						$output .= '<span class="red">'.sprintf($act_words['not_updating'],'PvPLog',$char_name,$char['version'])."</span><br />\n";
						$output .= $act_words['PvPLogver_err']."\n";
					}
				}
			}
		}
		return $output;
	}

	/**
	 * Process character data
	 */
	function processMyProfile()
	{
		global $wowdb, $roster_conf, $act_words;

		$output = '';
		$myProfile = $this->uploadData['characterprofiler']['myProfile'];

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
							$output .= '<strong>'.sprintf($act_words['upload_data'],'Character',$char_name)."</strong>\n";

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
							$output .= '<span class="red">'.sprintf($act_words['not_updating'],'CharacterProfiler',$char_name,$char['DBversion'])."</span><br />\n";
							$output .= $act_words['CPver_err']."\n";
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
					$output .= $act_words['noGuild'];
				}
			}
			else
			{
				$output .= sprintf($act_words['realm_ignored'],$realm_name)."<br />\n";
			}
		}
		return $output;
	}

	/**
	 * Process guild data
	 */
	function processGuildRoster()
	{
		global $wowdb, $roster_conf, $act_words, $guild_info;

		$myProfile = $this->uploadData['characterprofiler']['myProfile'];

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
									if( count($guild['Members']) > 0 )
									{
										// take the current time and get the offset. Upload must occur same day that roster was obtained
										$currentTimestamp = $guild['timestamp']['init']['TimeStamp'];
										$currentTime = getDate($currentTimestamp);

										if( $guild_info && ( ( strtotime($guild_info['guild_dateupdatedutc']) - strtotime($guild['timestamp']['init']['DateUTC']) ) <= 0 ) )
										{
											return sprintf($act_words['not_update_guild'],$guild_name)."<br />\n";
										}

										// Update the guild
										$guildId = $wowdb->update_guild($realm_name, $guild_name, $currentTimestamp, $guild);
										$guildMembers = $guild['Members'];

										// update the list of guild members
										$guild_output = "<ul><li><strong>".$act_words['update_members']."</strong>\n<ul>\n";

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
												$guild_output .= start_update_trigger($char_name, 'guild', $char);
											}
										}
										// Remove the members who were not in this list
										$wowdb->remove_guild_members($guildId, $currentTimestamp);
										$wowdb->remove_guild_members_id($guildId, $currentTimestamp);

										$guild_output .= $wowdb->getMessages()."</ul></li>\n";
										$wowdb->resetMessages();

										// Start update triggers
										if( $roster_conf['use_update_triggers'] )
										{
											$guild_output .= start_update_hook('guild_post', $guild);
										}

										$guild_output .= "</ul>\n";
										$output .= '<strong>'.sprintf($act_words['upload_data'],'Guild',$guild_name)."</strong>\n<ul>\n";
										$output .= '<li><strong>'.$act_words['memberlog']."</strong>\n<ul>\n".
											'<li>'.$act_words['updated'].': '.$wowdb->membersupdated."</li>\n".
											'<li>'.$act_words['added'].': '.$wowdb->membersadded."</li>\n".
											'<li>'.$act_words['removed'].': '.$wowdb->membersremoved."</li>\n".
											"</ul></li></ul>\n";
										$output .= $guild_output;
									}
									else
									{
										$output .= '<span class="red">'.sprintf($act_words['not_update_guild'],$guild_name)."</span><br />\n";
										$output .= $act_words['no_members']."<br />\n";
									}
								}
								else
								// GP Version not new enough
								{
									$output .= '<span class="red">'.sprintf($act_words['not_updating'],'GuildProfiler',$char_name,$guild['DBversion'])."</span><br />\n";
									$output .= $act_words['GPver_err']."<br />\n";
								}
							}
							else
							{
								$output .= sprintf($act_words['guild_realm_ignored'],$guild_name,$realm_name)."<br />\n";
							}
						}
						if( !isset($guild) )
						{
							$output .= sprintf($act_words['guild_nameNotFound'],$guild_name)."<br />\n";
						}

					}
					else
					{
						$output .= '<span class="red">'.$act_words['guild_addonNotFound'].'</span>'."<br />\n";
					}
				}
				else
				{
					$output .= sprintf($act_words['realm_ignored'],$realm_name)."<br />\n";
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
