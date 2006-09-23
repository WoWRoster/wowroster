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

define('ROSTER_MENU_INC',true);

$cols = 1;

if( $roster_conf['menu_left_pane'] && !empty($guild_info) )
{
	$levellist = makeLevelList('`guild_id` = '.$guild_info['guild_id']);
	$cols++;
}
else
{
	$levellist = '';
}

if( $roster_conf['menu_right_pane'] && !empty($guild_info) )
{
	$realmstatus = makeRealmStatus();
	$cols++;
}
else
{
	$realmstatus = '';
}

$buttonlist = makeButtonList('main');

$menuLogin = '  <tr>'."\n".
	'    <td align="center" class="row">'."\n".
	$roster_login->getMenuLogin().
	'    </td>'."\n".
	'  </tr>'."\n";

print "\n".'<!-- Begin WoWRoster Menu -->'.
	border('syellow','start')."\n".
	'<table cellspacing="0" cellpadding="4" border="0" class="main_roster_menu">'."\n".
	'  <tr>'."\n".
	'    <td colspan="'.$cols.'" align="center" valign="top" class="header">'."\n".
	'      <span style="font-size:18px;"><a href="'.$roster_conf['website_address'].'">'.$roster_conf['guild_name'].'</a></span>'."\n".
	'      <span style="font-size:11px;"> @ '.$roster_conf['server_name'].' ('.$roster_conf['server_type'].')</span><br />'.
	$wordings[$roster_conf['roster_lang']]['update'].': <span style="color:#0099FF;">'.$guild_info['date_format'].
	((!empty($roster_conf['timezone']))?' ('.$roster_conf['timezone'].')':'').
	'      </span>'."\n".
	'    </td>'."\n".
	'  </tr>'."\n".
	'  <tr>'."\n".
	'    <td colspan="'.$cols.'" class="simpleborderbot syellowborderbot"></td>'."\n".
	'  </tr>'."\n".
	'  <tr>'."\n".
	$levellist.
	$buttonlist.
	$realmstatus.
	'  </tr>'."\n".
	$menuLogin.
	'</table>'."\n".
	border('syellow','end')."\n".
	'<br />'."\n".
	'<!-- End WoWRoster Menu -->'."\n";

/**
 * Builds the level distribution list
 *
 * @param $condition where condition
 * @return formatted level distribution list
 */
function makeLevelList($condition)
{
	global $wordings, $wowdb, $roster_conf;

	$guildstat_query="SELECT IF(`".$roster_conf['alt_location']."` LIKE '%".$roster_conf['alt_type']."%',1,0) AS 'isalt', ".
		"`level` DIV 10 AS levelgroup, ".
		"COUNT(`level`) AS amount, ".
		"SUM(`level`) AS sum ".
		"FROM `".ROSTER_MEMBERSTABLE."` ".
		"WHERE ".$condition." ".
		"GROUP BY isalt, levelgroup ".
		"ORDER BY isalt ASC, levelgroup DESC";
	$result_menu = $wowdb->query($guildstat_query);

	if (!$result_menu) {
		die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$guildstat_query);
	}

	$num_non_alts = 0;
	$num_alts = 0;

	$num_lvl_60 = 0;
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
			case 6:
				$num_lvl_60 += $row['amount'];
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

	return '   <td rowspan="2" valign="top" class="row">
      '.$wordings[$roster_conf['roster_lang']]['members'].': '.$num_non_alts.' (+'.$num_alts.' Alts)
      <br />
      <ul>
        <li style="color:#999999;">Average Level: '.round($result_avg).'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 60: '.$num_lvl_60.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 50-59: '.$num_lvl_50_59.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 40-49: '.$num_lvl_40_49.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 30-39: '.$num_lvl_30_39.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 1-29: '.$num_lvl_1_29.'</li>
      </ul>
    </td>'."\n";
}

/**
 * Makes the Realmstatus pane
 *
 * @return the formatted realmstatus pane
 */
function makeRealmStatus()
{
	global $roster_conf;

	$realmStatus = '    <td rowspan="2" valign="top" class="row">'."\n";
	if( $roster_conf['rs_mode'] )
	{
		$realmStatus .= '      <img alt="WoW Server Status" src="'.$roster_conf['roster_dir'].'/realmstatus.php" />'."\n";
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
	global $wordings, $wowdb, $roster_conf, $roster_login;

	if (is_array($sections))
	{
		$section = implode(',',$sections);
	}
	else
	{
		$section = $sections;
		$sections = array($section);
	}

	$account = $roster_login->getAccount();
	if ($account<0)
	{
		$account = 0;
	}


	// --[ Fetch button list from DB ]--
	$query = "SELECT * FROM ".$wowdb->table('menu_button');

	$result = $wowdb->query($query);

	if (!$result)
	{
		die_quietly('Could not fetch buttons from database. MySQL said: <br />'.$wowdb->error(),'Roster');
	}

	while ($row = $wowdb->fetch_assoc($result))
	{
		$palet['b'.$row['button_id']] = $row;
	}

	$wowdb->free_result($result);

	// --[ Fetch menu configuration from DB ]--
	$query = "SELECT * FROM ".$wowdb->table('menu')." WHERE `account_id` = '".$account."' AND `section` IN ('".$section."')";

	$result = $wowdb->query($query);

	if (!$result)
	{
		die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$wowdb->error(),'Roster');
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
			$refetch[$id] = $value;
		}
	}

	if( is_array($refetch) )
	{
		$refetch = implode(',',$refetch);

		$query = "SELECT * FROM ".$wowdb->table('menu')." WHERE `account_id` = '0' AND `section` IN ('".$refetch."')";

		$result = $wowdb->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$wowdb->error(),'Roster');
		}

		while($row = $wowdb->fetch_assoc($result))
		{
			$data[$row[$section]] = $row;
		}

		$wowdb->free_result($result);

		foreach ($sections as $id=>$value)
		{
			if( isset($data[$section]) )
			{
				$page[$id] = $data[$value];
			}
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
					if ($roster_login->getAuthorized($palet[$button]['need_creds']))
					{
						$arrayButtons[$id][$posX][$posY] = $palet[$button];
					}
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
			$html .= '          <td>'."\n";
			$html .= '            <ul>'."\n";
			foreach( $column as $button )
			{
				$html .= '              <li><a href='.$button['url'].'>'.$button['title'].'</a></li>'."\n";
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
?>
