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

$roster_menu = new RosterMenu;

echo $roster_menu->makeMenu('main');

class RosterMenu
{
	function makeMenu($sections)
	{
		global $roster_conf, $guild_info, $roster_login, $wordings, $act_words;

		define('ROSTER_MENU_INC',true);

		$cols = 1;

		if( $roster_conf['menu_left_pane'] && $guild_info !==false )
		{
			$left_pane = $this->makeLevelList('`guild_id` = '.$guild_info['guild_id']);
			$cols++;
		}
		else
		{
			$left_pane = '';
		}

		if( $roster_conf['menu_right_pane'] && $guild_info !==false )
		{
			$right_pane = $this->makeRealmStatus();
			$cols++;
		}
		else
		{
			$right_pane = '';
		}

//		if( $roster_conf['menu_top_pane'] && !empty($guild_info))
		{
			$topbar = '  <tr>'."\n".
				'    <td colspan="'.$cols.'" align="center" valign="top" class="header">'."\n".
				'      <span style="font-size:18px;"><a href="'.$roster_conf['website_address'].'">'.$roster_conf['guild_name'].'</a></span>'."\n".
				'      <span style="font-size:11px;"> @ '.$roster_conf['server_name'].' ('.$roster_conf['server_type'].')</span><br />'.
				$act_words['update'].': <span style="color:#0099FF;">'.DateDataUpdated($guild_info['guild_dateupdatedutc']).
				((!empty($roster_conf['timezone']))?' ('.$roster_conf['timezone'].')':'').
				'      </span>'."\n".
				'    </td>'."\n".
				'  </tr>'."\n".
				'  <tr>'."\n".
				'    <td colspan="'.$cols.'" class="simpleborder_b syellowborder_b"></td>'."\n".
				'  </tr>'."\n";
		}
//		else
		{
//			$topbar = '';
		}

//		if( $roster_conf['menu_button_pane'] )
		{
			$buttonlist = $this->makeButtonList($sections);
		}
//		else
		{
//			$buttonlist = $menuLogin;
//			$menuLogin = '';
		}


		return "\n".'<!-- Begin WoWRoster Menu -->'.
			border('syellow','start')."\n".
			'<table cellspacing="0" cellpadding="4" border="0" class="main_roster_menu">'."\n".
			$topbar.
			'  <tr>'."\n".
			$left_pane.
			$buttonlist.
			$right_pane.
			'  </tr>'."\n".
			'</table>'."\n".
			border('syellow','end')."\n".
			'<br />'."\n".
			'<!-- End WoWRoster Menu -->'."\n";
	}

	/**
	 * Builds the level distribution list
	 *
	 * @param $condition where condition
	 * @return formatted level distribution list
	 */
	function makeLevelList( $condition )
	{
		global $wordings, $act_words, $wowdb, $roster_conf;

		$guildstat_query="SELECT IF(`".$roster_conf['alt_location']."` LIKE '%".$roster_conf['alt_type']."%',1,0) AS 'isalt', ".
			"FLOOR(`level`/10) AS levelgroup, ".
			"COUNT(`level`) AS amount, ".
			"SUM(`level`) AS sum ".
			"FROM `".ROSTER_MEMBERSTABLE."` AS `members`".
			"WHERE ".$condition." ".
			"GROUP BY isalt, levelgroup ".
			"ORDER BY isalt ASC, levelgroup DESC";
		$result_menu = $wowdb->query($guildstat_query);

		if (!$result_menu)
		{
			die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$guildstat_query);
		}

		if ($wowdb->num_rows($result_menu) == 0)
		{
			return '';
		}

		$num_non_alts = 0;
		$num_alts = 0;

		$num_lvl_70 = 0;
		$num_lvl_60_69 = 0;
		$num_lvl_50_59 = 0;
		$num_lvl_40_49 = 0;
		$num_lvl_30_39 = 0;
		$num_lvl_1_29 = 0;

		$level_sum = 0;

		while ($row = $wowdb->fetch_assoc($result_menu))
		{
			if ($row['isalt'])
			{
				$num_alts += $row['amount'];
			}
			else
			{
				$num_non_alts += $row['amount'];
			}

			switch ($row['levelgroup'])
			{
				case 7:
					$num_lvl_70 += $row['amount'];
				case 6:
					$num_lvl_60_69 += $row['amount'];
					break;
				case 5:
					$num_lvl_50_59 += $row['amount'];
					break;
				case 4:
					$num_lvl_40_49 += $row['amount'];
					break;
				case 3:
					$num_lvl_30_39 += $row['amount'];
					break;
				case 2:
				case 1:
				case 0:
					$num_lvl_1_29 += $row['amount'];
					break;
				default:
			}
			$level_sum += $row['sum'];
		}

		$result_avg = $level_sum/($num_alts + $num_non_alts);

		return '   <td valign="top" class="row">
	      '.$act_words['members'].': '.$num_non_alts.' (+'.$num_alts.' Alts)
	      <br />
	      <ul>
		<li style="color:#999999;">Average Level: '.round($result_avg).'</li>
		<li>'.$act_words['level'].' 70: '.$num_lvl_70.'</li>
		<li>'.$act_words['level'].' 60-69: '.$num_lvl_60_69.'</li>
		<li>'.$act_words['level'].' 50-59: '.$num_lvl_50_59.'</li>
		<li>'.$act_words['level'].' 40-49: '.$num_lvl_40_49.'</li>
		<li>'.$act_words['level'].' 30-39: '.$num_lvl_30_39.'</li>
		<li>'.$act_words['level'].' 1-29: '.$num_lvl_1_29.'</li>
	      </ul>
	    </td>'."\n";
	}

	/**
	 * Builds the class distribution list
	 *
	 * @param $condition where condition
	 * @return formatted level distribution list
	 */
	function makeClassList($condition = 'true')
	{
		global $wordings, $act_words, $wowdb, $roster_conf;

		$guildstat_query="SELECT IF(`".$roster_conf['alt_location']."` LIKE '%".$roster_conf['alt_type']."%',1,0) AS 'isalt', ".
			"class, ".
			"COUNT(`class`) AS amount ".
			"FROM `".ROSTER_MEMBERSTABLE."` AS `members` ".
			"WHERE ".$condition." ".
			"GROUP BY isalt, class ".
			"ORDER BY isalt ASC, class";
		$result_menu = $wowdb->query($guildstat_query);

		if (!$result_menu)
		{
			die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$guildstat_query);
		}

		$num_non_alts = 0;
		$num_alts = 0;

		$num_druids = 0;
		$num_druids_alts = 0;
		$num_hunters = 0;
		$num_hunters_alts = 0;
		$num_mages = 0;
		$num_mages_alts = 0;
		$num_paladins = 0;
		$num_paladins_alts = 0;
		$num_priests = 0;
		$num_priests_alts = 0;
		$num_rogues = 0;
		$num_rogues_alts = 0;
		$num_shamans = 0;
		$num_shamans_alts = 0;
		$num_warlocks = 0;
		$num_warlocks_alts = 0;
		$num_warriors = 0;
		$num_warriors_alts = 0;

		while ($row = $wowdb->fetch_assoc($result_menu))
		{
			if ($row['isalt'])
			{
				switch ($row['class'])
				{
					case 'Druid':
						$num_druids_alts += $row['amount'];
						break;
					case 'Hunter':
						$num_hunters_alts += $row['amount'];
						break;
					case 'Mage':
						$num_mages_alts += $row['amount'];
						break;
					case 'Paladin':
						$num_paladins_alts += $row['amount'];
						break;
					case 'Priest':
						$num_priests_alts += $row['amount'];
						break;
					case 'Rogue':
						$num_rogues_alts += $row['amount'];
						break;
					case 'Shaman':
						$num_shamans_alts += $row['amount'];
						break;
					case 'Warlock':
						$num_warlocks_alts += $row['amount'];
						break;
					case 'Warrior':
						$num_warriors_alts += $row['amount'];
						break;
					default:
				}
				$num_alts += $row['amount'];
			}
			else
			{
				switch ($row['class'])
				{
					case 'Druid':
						$num_druids += $row['amount'];
						break;
					case 'Hunter':
						$num_hunters += $row['amount'];
						break;
					case 'Mage':
						$num_mages += $row['amount'];
						break;
					case 'Paladin':
						$num_paladins += $row['amount'];
						break;
					case 'Priest':
						$num_priests += $row['amount'];
						break;
					case 'Rogue':
						$num_rogues += $row['amount'];
						break;
					case 'Shaman':
						$num_shamans += $row['amount'];
						break;
					case 'Warlock':
						$num_warlocks += $row['amount'];
						break;
					case 'Warrior':
						$num_warriors += $row['amount'];
						break;
					default:
				}
				$num_non_alts += $row['amount'];
			}
		}

		return '   <td valign="top" class="row">
			'.$act_words['members'].': '.$num_non_alts.' (+'.$num_alts.' Alts)
			<br />
			<ul>
				<li>Druids: '.$num_druids.' (+'.$num_druids_alts.')</li>
				<li>Hunters: '.$num_hunters.' (+'.$num_hunters_alts.')</li>
				<li>Mages: '.$num_mages.' (+'.$num_mages_alts.')</li>
				<li>Paladins: '.$num_paladins.' (+'.$num_paladins_alts.')</li>
				<li>Priests: '.$num_priests.' (+'.$num_priests_alts.')</li>
				<li>Rogues: '.$num_rogues.' (+'.$num_rogues_alts.')</li>
				<li>Shamans: '.$num_shamans.' (+'.$num_shamans_alts.')</li>
				<li>Warlocks: '.$num_warlocks.' (+'.$num_warlocks_alts.')</li>
				<li>Warriors: '.$num_warriors.' (+'.$num_warriors_alts.')</li>
			</ul>
		</td>';
	}

	/**
	 * Makes the Realmstatus pane
	 *
	 * @return the formatted realmstatus pane
	 */
	function makeRealmStatus()
	{
		global $roster_conf;

		$realmStatus = '    <td valign="top" class="row">'."\n";
		if( $roster_conf['rs_mode'] )
		{
			$realmStatus .= '      <img alt="WoW Server Status" src="realmstatus.php" />'."\n";
		}
		elseif( file_exists(ROSTER_BASE.'realmstatus.php') )
		{
			ob_start();
				include_once (ROSTER_BASE.'realmstatus.php');
				$realmStatus .= ob_get_contents()."\n";
			ob_end_flush();
		}

		$realmStatus .= '    </td>'."\n";

		return $realmStatus;
	}

	/**
	 * Builds the list of menu buttons for the specified sections
	 *
	 * @param array $sections the sections to render
	 * @return the formatted button grid.
	 */
	function makeButtonList($sections)
	{
		global $wordings, $act_words, $wowdb, $roster_conf;

		if (is_array($sections))
		{
			$section = implode(',',$sections);
		}
		else
		{
			$section = $sections;
			$sections = array($section);
		}

		// --[ Fetch button list from DB ]--
		$query = "SELECT `mb`.*, `a`.`basename`
			FROM `".$wowdb->table('menu_button')."` AS mb
			LEFT JOIN `".$wowdb->table('addon')."` AS a
			ON `mb`.`addon_id` = `a`.`addon_id`;";

		$result = $wowdb->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch buttons from database. MySQL said: <br />'.$wowdb->error(),'Roster',basename(__FILE__),__LINE__,$query);
		}

		while ($row = $wowdb->fetch_assoc($result))
		{
			$palet['b'.$row['button_id']] = $row;
		}

		$wowdb->free_result($result);

		// --[ Fetch menu configuration from DB ]--
		$query = "SELECT * FROM ".$wowdb->table('menu')." WHERE `section` IN ('".$section."');";

		$result = $wowdb->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$wowdb->error(),'Roster',basename(__FILE__),__LINE__,$query);
		}

		while($row = $wowdb->fetch_assoc($result))
		{
			$data[$row['section']] = $row;
		}

		$wowdb->free_result($result);

		foreach ($sections as $id=>$value)
		{
			if( isset($data[$section]) )
			{
				$page[$id] = $data[$value];
			}
		}

		// --[ Parse DB data ]--
		if( is_array($page) )
		{
			foreach ($page as $id => $value)
			{
				foreach (explode('|',$value['config']) AS $posX=>$column)
				{
					$config[$id][$posX] = explode(':',$column);
					foreach($config[$id][$posX] as $posY=>$button)
					{
						$arrayButtons[$id][$posX][$posY] = $palet[$button];
					}
				}
			}
		}


		$html  = '    <td valign="top" class="row links">'."\n";
		$html .= '      <table cellspacing="0" cellpadding="0" border="0" width="100%">'."\n";
		foreach( $arrayButtons as $id => $page )
		{
			$html .= '        <tr><td align="center" colspan="'.count($page).'"><span style="color:#0099FF;font-weight:bold;">'.$sections[$id].'</span></td></tr>'."\n";
			$html .= '        <tr>'."\n";
			foreach( $page as $column )
			{
				$html .= '          <td valign="top">'."\n";
				$html .= '            <ul>'."\n";
				foreach( $column as $button )
				{
					if( $button['addon_id'] != '0' && !isset($act_words[$button['title']]) )
					{
						// Include addon's locale files if they exist
						foreach( $roster_conf['multilanguages'] as $lang )
						{
							if( file_exists(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php') )
							{
								add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang,$wordings);
							}
						}
					}
					$html .= '              <li><a href="'.makelink($button['url']).'">'.( isset($act_words[$button['title']]) ? $act_words[$button['title']] : $button['title'] ).'</a></li>'."\n";
				}
				$html .= '            </ul>'."\n";
				$html .= '          </td>'."\n";
			}
			$html .= '        </tr>'."\n";
		}
		$html .= '      </table>'."\n";
		$html .= '    </td>'."\n";

		return $html;
	}
}
