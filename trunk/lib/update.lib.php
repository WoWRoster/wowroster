<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LUA updating library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class update
{
	var $textmode = false;
	var $uploadData;
	var $addons = array();
	var $files = array();

	/**
	 * Collect info on what files are used
	 */
	function fetchAddonData()
	{
		global $wowdb, $roster_conf;

		// Add roster-used tables
		$this->files[] = 'characterprofiler';

		if( $roster_conf['pvp_log_allow'] )
		{
			$this->files[] = 'pvplog';
		}

		if( !$roster_conf['use_update_triggers'] )
		{
			return '';
		}

		$query = 'SELECT `addon`.`basename`,`addon`.`fullname` '.
			'FROM `'.$wowdb->table('addon').'` AS `addon` '.
			'WHERE `addon`.`active` = 1';
		$result = $wowdb->query($query);

		if( !$result )
		{
			$wowdb->setError('Failed to load addon triggers',$wowdb->error());
		}
		else
		{
			while( $row = $wowdb->fetch_assoc($result) )
			{
				if( !isset($this->addons[$row['basename']]) )
				{
					$hookfile = ROSTER_ADDONS.$row['basename'].DIR_SEP.'update_hook.php';

					if( file_exists($hookfile) )
					{
						$addon = getaddon($row['basename']);

						include_once($hookfile);

						if( class_exists($row['basename']) )
						{
							$this->addons[$row['basename']] = new $row['basename']($addon);
							$this->files += $this->addons[$row['basename']]->files;
						}
						else
						{
							$wowdb->setError('Failed to load addon '.$row['basename'].': Update class did not exist','');
						}
					}
				}
			}
		}

		$this->files = array_unique($this->files);

		return '';
	}

	/**
	 * Parses the files and put it in $uploadData
	 *
	 * @return string $output | Output messages
	 */
	function parseFiles()
	{
		global $act_words;
		if( !is_array($_FILES) )
		{
			return '<span class="red">Upload failed: No files present</span>'."<br />\n";
		}

		require_once(ROSTER_LIB.'luaparser.php');

		$output = $act_words['parsing_files']."<br />\n".'<ul>';
		foreach( $_FILES as $file )
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
						$output .= ($luahandler->error() !='' ? '<li>'.$luahandler->error().'</li>'."\n" : '');
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
		global $roster_conf, $wowdb, $wordings, $act_words;

		if( !is_array($this->uploadData) )
		{
			return '';
		}
		$output = 'Processing files'."<br />\n";
		$gotfiles = array_keys($this->uploadData);
		if( in_array('characterprofiler',$gotfiles) )
		{
			$roster_login = new RosterLogin();

			if( $roster_login->getAuthorized(2) )
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

		if( is_array($this->addons) && count($this->addons)>0 )
		{
			foreach( $this->addons as $basename => $addon )
			{
				if( count(array_intersect($gotfiles, $addon->files))>0 )
				{
					if( file_exists($addon->data['trigger_file']) )
					{
						$addon->reset_messages();
						if( method_exists($addon, 'update') )
						{
							$result = $addon->update();

							if( $result )
							{
								$output .= $addon->messages;
							}
							else
							{
								$output .= 'There was an error in addon '.$addon->data['fullname']." in method update<br />\n".
									"Addon messages:<br />\n".$addon->messages;
							}
						}
					}
				}
			}
		}
		return $output;
	}

	/**
	 * Run trigger for character
	 */
	function addon_hook( $mode , $data , $memberid = '0' )
	{
		$output = '';
		foreach( $this->addons as $basename => $addon )
		{
			if( file_exists($addon->data['trigger_file']) )
			{
				$addon->reset_messages();
				if( method_exists($addon, $mode) )
				{
					$result = $addon->{$mode}($data , $memberid);

					if( $result )
					{
						if( $mode == 'guild' )
						{
							$output .= '<li>'.$addon->messages.'</li>'."\n";
						}
						else
						{
							$output .= $addon->messages.'<br/>'."\n";
						}
					}
					else
					{
						if( $mode == 'guild' )
						{
							$output .= '<li>There was an error in addon '.$addon->data['fullname']." in method $mode<br />\n".
								"Addon messages:<br />\n".$addon->messages.'</li>'."\n";
						}
						else
						{
							$output .= 'There was an error in addon '.$addon->data['fullname']." in method $mode<br />\n".
								"Addon messages:<br />\n".$addon->messages.'<br />'."\n";
						}
					}
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
						$output .= sprintf($act_words['PvPLogver_err'], $roster_conf['minPvPLogver'])."\n";
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
						$output .= $this->addon_hook('char_pre', $characters);
					}

					foreach( array_keys( $characters ) as $char_name )
					{
						$char = $characters[$char_name];

						// CP Version Detection, don't allow lower than minVer
						if( $char['DBversion'] >= $roster_conf['minCPver'] )
						{
							$output .= '<strong>'.sprintf($act_words['upload_data'],'Character',$char_name)."</strong>\n";

							$memberid = $wowdb->update_char( $guildInfo['guild_id'], $char_name, $char );
							$output .= "<ul>\n".$wowdb->getMessages()."</ul>\n";
							$wowdb->resetMessages();

							// Start update triggers
							if( $memberid !== false && $roster_conf['use_update_triggers'] )
							{
								$output .= $this->addon_hook('char', $char, $memberid);
							}
						}
						else // CP Version not new enough
						{
							$output .= '<span class="red">'.sprintf($act_words['not_updating'],'CharacterProfiler',$char_name,$char['DBversion'])."</span><br />\n";
							$output .= sprintf($act_words['CPver_err'], $roster_conf['minCPver'])."\n";
						}
						$output .= "<br />\n";
					}

					// Start update triggers
					if( $roster_conf['use_update_triggers'] )
					{
						$output .= $this->addon_hook('char_post', $characters);
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

		$output = '';
		$wowdb->resetMessages();

		if( is_array($myProfile) )
		{
			foreach( $myProfile as $realm_name => $realm )
			{
				// Only allow realms specified in config
				if( $realm_name == $roster_conf['server_name'])
				{
					if( isset($realm['Guild']) && is_array($realm['Guild']) )
					{
						foreach( $realm['Guild'] as $guild_name => $guild )
						{
							// Only allow the guild specified in config
							if( $roster_conf['guild_name'] == $guild_name )
							{
								// GP Version Detection, don't allow lower than minVer
								if( $guild['DBversion'] >= $roster_conf['minGPver'] )
								{
									if( count($guild['Members']) > 0 )
									{
										// take the current time and get the offset. Upload must occur same day that roster was obtained
										$currentTimestamp = $guild['timestamp']['init']['TimeStamp'];
										$currentTime = getDate($currentTimestamp);

										if( $guild_info && ( ( strtotime($guild_info['guild_dateupdatedutc']) - strtotime($guild['timestamp']['init']['DateUTC']) ) > 0 ) )
										{
											return sprintf($act_words['not_update_guild_time'],$guild_name)."<br />\n";
										}

										// Update the guild
										$guildId = $wowdb->update_guild($realm_name, $guild_name, $currentTimestamp, $guild);
										$guildMembers = $guild['Members'];

										$guild_output = '';
										
										// Start update triggers
										if( $roster_conf['use_update_triggers'] )
										{
											$guild_output .= $this->addon_hook('guild_pre', $guild);
										}

										// update the list of guild members
										$guild_output .= "<ul><li><strong>".$act_words['update_members']."</strong>\n<ul>\n";

										foreach(array_keys($guildMembers) as $char_name)
										{
											$char = $guildMembers[$char_name];
											$memberid = $wowdb->update_guild_member($guildId, $char_name, $char, $currentTimestamp, $guild['Ranks']);
											$guild_output .= $wowdb->getMessages();
											$wowdb->resetMessages();

											// Start update triggers
											if( $memberid !== false && $roster_conf['use_update_triggers'] )
											{
												$guild_output .= $this->addon_hook('guild', $char, $memberid);
											}
										}
										// Remove the members who were not in this list
										$wowdb->remove_guild_members($guildId, $currentTimestamp);
										$wowdb->remove_guild_members_id($guildId, $currentTimestamp);

										$guild_output .= $wowdb->getMessages()."</ul></li>\n";
										$wowdb->resetMessages();

										$guild_output .= "</ul>\n";

										// Start update triggers
										if( $roster_conf['use_update_triggers'] )
										{
											$guild_output .= $this->addon_hook('guild_post', $guild);
										}

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
									$output .= sprintf($act_words['GPver_err'], $roster_conf['minGPver'])."<br />\n";
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
		$filefields = '';
		if (!is_array($this->files) || (count($this->files) == 0)) // Just in case
		{
			return "No files accepted!";
		}
		foreach ($this->files as $file)
		{
			$filefields .=
			'<tr>'."\n".
				"\t".'<td class="membersRow1" '.makeOverlib('<i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables\\\\'.$file.'.lua',$file.'.lua Location','',2).'><img src="'.$roster_conf['img_url'].'blue-question-mark.gif" alt="?" />'.$file.'.lua</td>'."\n".
				"\t".'<td class="membersRowRight1"><input type="file" accept="'.$file.'.lua" name="'.$file.'" /></td>'."\n".
			'</tr>'."\n";
		}
		return $filefields;
	}
}
