<?php
/**
 * WoWRoster.net WoWRoster
 *
 * PvPLog history and data
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    PvPLog
 * @subpackage Library
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Multiple edits by Gaxme, 16 May 2006
// Thanks :)

// Set up template vars used here
$roster->tpl->assign_vars(array(
	'S_SHOW_LOG' => false,
	'S_SUMMARY'  => false,
	'S_DUEL'     => false,
	'S_PVP'      => false,
	'S_BG'       => false,

	'L_LOG'          => 'Log',
	'L_DUEL_SUMMARY' => $roster->locale->act['duelsummary'],
	'L_WORLD_PVP'    => $roster->locale->act['world_pvp'],
	'L_VS_GUILDS'    => $roster->locale->act['versus_guilds'],
	'L_VS_PLAYERS'   => $roster->locale->act['versus_players'],

	'L_TOTAL_WINS'    => $roster->locale->act['totalwins'],
	'L_TOTAL_LOSSES'  => $roster->locale->act['totallosses'],
	'L_TOTAL_OVERALL' => $roster->locale->act['totaloverall'],
	'L_WIN_AVERAGE'   => $roster->locale->act['win_average'],
	'L_LOSS_AVERAGE'  => $roster->locale->act['loss_average'],

	'L_WINS'           => $roster->locale->act['wins'],
	'L_LOSSES'         => $roster->locale->act['losses'],
	'L_OVERALL'        => $roster->locale->act['overall'],
	'L_BEST_ZONE'      => $roster->locale->act['best_zone'],
	'L_WORST_ZONE'     => $roster->locale->act['worst_zone'],
	'L_BEST_SUB_ZONE'  => $roster->locale->act['bestsub'],
	'L_WORST_SUB_ZONE' => $roster->locale->act['worstsub'],
	'L_KILLS'          => $roster->locale->act['kills'],
	'L_KILLED_MOST'    => $roster->locale->act['killedmost'],
	'L_KILLED_MOST_BY' => $roster->locale->act['killedmostby'],
	'L_GUILD_KILLED_MOST'    => $roster->locale->act['gkilledmost'],
	'L_GUILD_KILLED_MOST_BY' => $roster->locale->act['gkilledmostby'],

	'L_WHEN'      => $roster->locale->act['when'],
	'L_CLASS'     => $roster->locale->act['class'],
	'L_NAME'      => $roster->locale->act['name'],
	'L_RACE'      => $roster->locale->act['race'],
	'L_RANK'      => $roster->locale->act['rank'],
	'L_GUILD'     => $roster->locale->act['guild'],
	'L_REALM'     => $roster->locale->act['realm'],
	'L_LEVELDIFF' => $roster->locale->act['leveldiff'],
	'L_WIN'       => $roster->locale->act['win'],
	'L_HONOR'     => $roster->locale->act['honor'],
	'L_ZONE'      => $roster->locale->act['zone'],
	'L_SUBZONE'   => $roster->locale->act['subzone'],
	)
);

/**
 * PvPLog Library
 *
 * @package    PvPLog
 * @subpackage PvPLog Library
 */
class pvp3
{
	var $data;

	function pvp3( $data )
	{
		$this->data = $data;
	}

	function get( $field )
	{
		return $this->data[$field];
	}
}


function show_pvp2( $type , $url , $sort , $start )
{
	global $roster;

	// Get all the available data
	$pvps = pvp_get_many3($roster->data['member_id'],$type, '', -1);

	if( is_array($pvps) )
	{
		$max = sizeof($pvps);
		$sort_part = $sort ? "&amp;s=$sort" : '';

		if( $start > 0 )
		{
			$prev = '<a href="' . makelink($url . '&amp;start=0' . $sort_part) . '">|&lt;&lt;</a>&nbsp;&nbsp;<a href="' . makelink($url.'&amp;start=' . ($start-50) . $sort_part) . '">&lt;</a> ';
		}
		else
		{
			$prev = '';
		}

		if( ($start+50) < $max )
		{
			$listing = ' <small>[' . $start . ' - ' . ($start+50) . '] of ' . $max . '</small>';
			$next = ' <a href="' . makelink($url . '&amp;start=' . ($start+50) . $sort_part) . '">&gt;</a>&nbsp;&nbsp;<a href="' . makelink($url . '&amp;start=' . ($max-50) . $sort_part) . '">&gt;&gt;|</a>';
		}
		else
		{
			$listing = ' <small>[' . $start . ' - ' . $max . '] of ' . $max . '</small>';
			$next = '';
		}

		$roster->tpl->assign_vars(array(
			'PREV' => $prev,
			'NEXT' => $next,
			'LISTING' => $listing
			)
		);

		output_pvp_summary($pvps,$type);

		if( isset( $pvps[0] ) )
		{
			switch( $type )
			{
				case 'BG':
					$roster->tpl->assign_var('S_BG', true );
					output_bglog($pvps);
					break;

				case 'Duel':
					$roster->tpl->assign_var('S_DUEL', true );
					output_duellog($roster->data['member_id']);
					break;

				case 'PvP':
					$roster->tpl->assign_var('S_PVP', true );
					output_pvplog($pvps);
					break;

				default:
					break;
			}
		}

		// Get the relevant data
		$pvps = pvp_get_many3($roster->data['member_id'],$type, $sort, $start);

		if( isset($pvps[0]) )
		{
			output_pvp2($pvps, $url . '&amp;start=' . $start,$type);
		}
	}
}


function pvp_get_many3( $member_id , $type , $sort , $start )
{
	global $roster, $addon;

	$query = "SELECT *, DATE_FORMAT(date, '" . $roster->locale->act['timeformat'] . "') AS date2 FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $member_id . "' AND ";

	if( $type == 'PvP' )
	{
		$query .= "`enemy` = '1' AND `bg` = '0'";
	}
	elseif( $type == 'BG' )
	{
		$query .= "`enemy` = '1' AND `bg` >= '1'";
	}
	else
	{
		$query .= "`enemy` = '0'";
	}

	switch( $sort )
	{
		case 'name':
			$query .= " ORDER BY `name` ASC, `level` DESC, `guild` ASC";
			break;

		case 'race':
			$query .= " ORDER BY `race` ASC, `guild` ASC, `name` ASC, `level` DESC";
			break;

		case 'class':
			$query .= " ORDER BY `class` ASC, `guild` ASC, `name` ASC, `level` DESC";
			break;

		case 'leveldiff':
			$query .= " ORDER BY `leveldiff` DESC, `guild` ASC, `name` ASC ";
			break;

		case 'win':
			$query .= " ORDER BY `win` DESC, `guild` ASC, `name` ASC ";
			break;

		case 'zone':
			$query .= " ORDER BY `zone` ASC, `guild` ASC, `name` ASC ";
			break;

		case 'subzone':
			$query .= " ORDER BY `subzone` ASC, `guild` ASC, `name` ASC ";
			break;

		case 'date':
			$query .= " ORDER BY `date` ASC, `guild` ASC, `name` ASC ";
			break;

		case 'bg':
			$query .= " ORDER BY `bg` ASC, `guild` ASC, `name` ASC ";
			break;

		case 'honor':
			$query .= " ORDER BY `honor` ASC, `guild` ASC, `name` ASC ";
			break;

		case 'rank':
			$query .= " ORDER BY `rank` ASC, `guild` ASC, `name` ASC ";
			break;

		case 'guild':
			$query .= " ORDER BY `guild` ASC, `name` ASC, `level` DESC ";
			break;

		case 'realm':
			$query .= " ORDER BY `realm` ASC, `name` ASC, `level` DESC ";
			break;

		default:
			$query .= " ORDER BY `date` DESC, `guild` ASC, `name` ASC ";
			break;
	}

	if( $start != -1 )
	{
		$query .= ' LIMIT ' . $start . ', 50';
	}

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	if( $roster->db->num_rows($result) > 0 )
	{
		$pvps = array();
		while( $data = $roster->db->fetch( $result ) )
		{
			$pvp = new pvp3( $data );
			$pvps[] = $pvp;
		}
		return $pvps;
	}
	else
	{
		return false;
	}
}


function output_pvp_summary( $pvps , $type )
{
	global $roster;

	$tot_wins = 0;
	$tot_losses = 0;
	$ave_win_level_diff = 0;
	$ave_loss_level_diff = 0;

	foreach( $pvps as $row )
	{
		if( $row->data['win'] == '1' )
		{
			$tot_wins = $tot_wins + 1;
			$ave_win_level_diff = $ave_win_level_diff + $row->data['leveldiff'];
		}
		else
		{
			$tot_losses = $tot_losses + 1;
			$ave_loss_level_diff = $ave_loss_level_diff + $row->data['leveldiff'];
		}
	}

	if( $tot_wins > 0 )
	{
		$ave_win_level_diff = $ave_win_level_diff / $tot_wins;
		$ave_win_level_diff = round($ave_win_level_diff, 2);
		if( $ave_win_level_diff > 0 )
		{
			$ave_win_level_diff = '+' . $ave_win_level_diff;
		}
	}
	else
	{
		$ave_win_level_diff = 0;
	}

	if( $tot_losses > 0 )
	{
		$ave_loss_level_diff = $ave_loss_level_diff / $tot_losses;
		$ave_loss_level_diff = round($ave_loss_level_diff, 2);
		if ($ave_loss_level_diff > 0)
		{
			$ave_loss_level_diff = '+' . $ave_loss_level_diff;
		}
	}
	else
	{
		$ave_loss_level_diff = 0;
	}

	$total = $tot_wins - $tot_losses;
	if( $total > 0 )
	{
		$total = '+'.$total;
	}

	$winpercent = round( ($tot_wins / ($tot_wins + $tot_losses)), 2 ) * 100;

	switch( $type )
	{
		case 'BG':
			$header_text = $roster->locale->act['bglog'];
			break;

		case 'PvP':
			$header_text = $roster->locale->act['pvplog'];
			break;

		case 'Duel':
			$header_text = $roster->locale->act['duellog'];
			break;

		default:
			break;
	}

	$roster->tpl->assign_vars(array(
		'S_SUMMARY'         => true,

		'LOG_TITLE'         => $header_text,

		'SUM_TOTAL_WINS'    => $tot_wins,
		'SUM_TOTAL_LOSSES'  => $tot_losses,
		'SUM_TOTAL_OVERALL' => $total,
		'SUM_TOTAL_OVERALL_PERC' => $winpercent,
		'SUM_WIN_AVERAGE'   => $ave_win_level_diff,
		'SUM_LOSS_AVERAGE'  => $ave_loss_level_diff
		)
	);
}


/**
 * Let's limit how many DB calls we do
 * Callback functions for:
 *   o Comparing BG Win/Loss Ratios
 *   o Calculating most killed/most killed by
 *
 * @param array $a
 * @param array $b
 * @return int
 */
function calc_winloss( $a , $b )
{
	if( $a['WinLoss'] == $b['WinLoss'] )
	{
		return 0;
	}
	else
	{
		return ($a['WinLoss'] < $b['WinLoss']);
	}
}


function calc_gwinloss( $a , $b )
{
	if( $a['killed'] == $b['killed'] )
	{
		return 0;
	}
	else
	{
		return ($a['killed'] < $b['killed']);
	}
}


function calc_pwinloss( $a , $b )
{
	if( $a['killed'] == $b['killed'] )
	{
		return 0;
	}
	else
	{
		return ($a['killed'] < $b['killed']);
	}
}


function output_bglog( $pvps )
{
	global $roster, $addon;

	$bg_array = array(
		'eye_of_the_storm'=>'spurple',
		'alterac_valley'=>'sblue',
		'arathi_basin'=>'sgreen',
		'warsong_gulch'=>'sorange',
	);

	foreach( $bg_array as $bgname => $bgcolor )
	{
		$wins=0;
		$loss=0;
		$pwin = $gwin = $ploss = $gloss = $subs = array();
		foreach( $pvps as $row )
		{
			if( $row->data['zone'] == $roster->locale->act[$bgname] )
			{
				// Set some defaults
				$eguild = $row->data['guild'];
				$esub = $row->data['subzone'];
				$ename = $row->data['name'];
				$eclass = $row->data['class'];

				if( empty($eguild) || !isset($eguild) || $eguild == '' )
				{
					$eguild = '--';
				}
				if( empty($esub) || !isset($esub) || $esub == '' )
				{
					$esub = 'None';
				}

				if( !isset($subs[$esub]) )
				{
					$subs[$esub] = array('Wins' => 0, 'WinLoss' => 0, 'Zone' => 0, 'Loss' => 0);
				}

				if( !isset($gwin[$eguild]) )
				{
					$gwin[$eguild] = array('killed' => 0, 'name');
				}

				if( !isset($pwin[$ename]) )
				{
					$pwin[$ename] = array('killed' => 0, 'name', 'class', 'class_icon');
				}

				if( !isset($gloss[$eguild]) )
				{
					$gloss[$eguild] = array('killed' => 0, 'name');
				}

				if( !isset($ploss[$ename]) )
				{
					$ploss[$ename] = array('killed' => 0, 'name', 'class', 'class_icon');
				}

				// Get Class Icon
				foreach( $roster->multilanguages as $language )
				{
					$icon_name = ( isset($roster->locale->wordings[$language]['class_iconArray'][$eclass]) ? $roster->locale->wordings[$language]['class_iconArray'][$eclass] : '' );
					if( strlen($icon_name) > 0 )
					{
						break;
					}
				}

				if( !empty($icon_name) )
				{
					$class_icon = '<img style="cursor:help;" onmouseover="return overlib(\'' . $eclass . '\',WRAP);" onmouseout="return nd();" class="membersRowimg" width="16" height="16" src="' . $roster->config['img_url'] . 'class/' . $icon_name . '.jpg" alt="" />&nbsp;';
				}
				else
				{
					$class_icon = '';
				}

				$win_level_diff = $loss_level_diff = 0;

				if( $row->data['win'] == '1' )
				{
					$wins++;
					$win_level_diff += $row->data['leveldiff'];
					$subs[$esub]['Wins'] += 1;
					$subs[$esub]['WinLoss'] += 1;
					$subs[$esub]['Zone'] = $esub;
					$gwin[$eguild]['killed'] += 1;
					$gwin[$eguild]['name'] = $eguild;
					$pwin[$ename]['killed'] += 1;
					$pwin[$ename]['name'] = $ename;
					$pwin[$ename]['class'] = $eclass;
					$pwin[$ename]['class_icon'] = $class_icon;
				}
				else
				{
					$loss++;
					$loss_level_diff += $row->data['leveldiff'];
					$subs[$esub]['Loss'] += 1;
					$subs[$esub]['WinLoss'] = $subs[$esub]['WinLoss'] - 1;
					$subs[$esub]['Zone'] = $esub;
					$gloss[$eguild]['killed'] += 1;
					$gloss[$eguild]['name'] = $ename;
					$ploss[$ename]['killed'] += 1;
					$ploss[$ename]['name'] = $ename;
					$ploss[$ename]['class'] = $eclass;
					$ploss[$ename]['class_icon'] = $class_icon;
				}
			}
		}

		if( $wins > 0 )
		{
			$win_level_diff = $win_level_diff / $wins;
			$win_level_diff = round($win_level_diff, 2);
			if( $win_level_diff > 0 )
			{
				$win_level_diff = '+' . $win_level_diff;
			}
		}
		else
		{
			$win_level_diff = 0;
		}

		if( $loss > 0 )
		{
			$loss_level_diff = $loss_level_diff / $loss;
			$loss_level_diff = round($loss_level_diff, 2);
			if( $loss_level_diff > 0 )
			{
				$loss_level_diff = '+' . $loss_level_diff;
			}
		}
		else
		{
			$loss_level_diff = 0;
		}

		$total = $wins - $loss;
		if ($total > 0)
		{
			$total = '+' . $total;
		}

		if( ($wins + $loss) != 0 )
		{
			$winpercent = round( ($wins / ($wins + $loss)), 2 ) * 100;
		}
		else
		{
			$winpercent = 0;
		}

		usort($subs, 'calc_winloss');
		usort($gwin, 'calc_gwinloss');
		usort($pwin, 'calc_pwinloss');
		usort($gloss, 'calc_gwinloss');
		usort($ploss, 'calc_pwinloss');

		$best = ( isset($subs[0]['Zone']) ? $subs[0]['Zone'] : '' );
		$worst = ( isset($subs[sizeof($subs)-1]['Zone']) ? $subs[sizeof($subs)-1]['Zone'] : '' );
		$bestNum = ( isset($subs[0]['WinLoss']) ? $subs[0]['WinLoss'] : '' );
		$worstNum = ( isset($subs[sizeof($subs)-1]['WinLoss']) ? $subs[sizeof($subs)-1]['WinLoss'] : '' );

		$kills = ( isset($pwin[0]['killed']) ? $pwin[0]['killed'] : '' );
		$killed = ( isset($pwin[0]['name']) ? $pwin[0]['name'] : '' );
		$killedclass = ( isset($pwin[0]['class']) ? $pwin[0]['class'] : '' );
		$killedclassicon = ( isset($pwin[0]['class_icon']) ? $pwin[0]['class_icon'] : '' );

		$deaths = ( isset($ploss[0]['killed']) ? $ploss[0]['killed'] : '' );
		$killedBy = ( isset($ploss[0]['name']) ? $ploss[0]['name'] : '' );
		$killedByclass = ( isset($ploss[0]['class']) ? $ploss[0]['class'] : '' );
		$killedByclassicon = ( isset($ploss[0]['class_icon']) ? $ploss[0]['class_icon'] : '' );

		$gkills = ( isset($gwin[0]['killed']) ? $gwin[0]['killed'] : '' );
		$gkilled = ( isset($gwin[0]['name']) ? $gwin[0]['name'] : '' );
		$gdeaths = ( isset($gloss[0]['killed']) ? $gloss[0]['killed'] : '' );
		$gkilledBy = ( isset($gloss[0]['name']) ? $gloss[0]['name'] : '' );

		$roster->tpl->assign_block_vars('bg_log_summary',array(
			'S_HIDE' => true,

			'L_HEADER' => $roster->locale->act[$bgname],

			'ID' => $bgname,

			'COLOR' => $bgcolor,
			'WIDTH' => '400px',
			'WINS' => $wins,
			'LOSSES' => $loss,
			'OVERALL' => $total,
			'OVERALL_PERC' => $winpercent,
			'WIN_AVERAGE' => $win_level_diff,
			'LOSS_AVERAGE' => $loss_level_diff,
			'BEST_SUB_ZONE' => $best,
			'BEST_SUB_ZONE_NUM' => $bestNum,
			'WORST_SUB_ZONE' => $worst,
			'WORST_SUB_ZONE_NUM' => $worstNum,
			'KILLED_MOST' => $killed,
			'KILLED_MOST_CLASS' => $killedclass,
			'KILLED_MOST_CLASS_ICON' => $killedclassicon,
			'KILLED_MOST_KILLS' => $kills,
			'KILLED_MOST_BY' => $killedBy,
			'KILLED_MOST_BY_CLASS' => $killedByclass,
			'KILLED_MOST_BY_CLASS_ICON' => $killedByclassicon,
			'KILLED_MOST_BY_KILLS' => $deaths,
			'GUILD_KILLED_MOST' => $gkilled,
			'GUILD_KILLED_MOST_KILLS' => $gkills,
			'GUILD_KILLED_MOST_BY' => $gkilledBy,
			'GUILD_KILLED_MOST_BY_KILLS' => $gdeaths
			)
		);
	}
}


function output_duellog( $member_id )
{
	global $roster, $addon;

	$data = array();

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $member_id . "' AND `enemy` = '0' AND `bg` = '0' AND `win` = '0' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	$data['loss'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $member_id . "' AND `enemy` = '0' AND `bg` = '0' AND `win` = '1' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	$data['win'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	foreach( $data as $datakey => $dataset )
	{
		// Get Class Icon
		foreach( $roster->multilanguages as $language )
		{
			$dataset['icon_name'] = ( isset($roster->locale->wordings[$language]['class_iconArray'][$dataset['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$dataset['class']] : '' );
			if( strlen($dataset['icon_name']) > 0 ) break;
		}

		if( !empty($dataset['icon_name']) )
		{
			$data[$datakey]['class_icon'] = '<img style="cursor:help;" onmouseover="return overlib(\'' . $dataset['class'] . '\',WRAP);" onmouseout="return nd();" class="membersRowimg" width="16" height="16" src="' . $roster->config['img_url'] . 'class/' . $dataset['icon_name'] . '.jpg" alt="" />&nbsp;';
		}
		else
		{
			$data[$datakey]['class_icon'] = '';
		}

		// Fix table rows if they are empty
		$check_array = array('name', 'guild', 'race', 'class', 'leveldiff', 'countn');

		foreach( $check_array as $check_value )
		{
			if( $dataset[$check_value] == '' )
			{
				$data[$datakey][$check_value] = '&nbsp;';
			}
		}
	}

	$roster->tpl->assign_vars(array(
		'DUEL_K_NAME'  => $data['win']['name'],
		'DUEL_KB_NAME' => $data['loss']['name'],
		'DUEL_K_CLASS'  => $data['win']['class'],
		'DUEL_KB_CLASS' => $data['loss']['class'],
		'DUEL_K_CLASS_ICON'  => $data['win']['class_icon'],
		'DUEL_KB_CLASS_ICON' => $data['loss']['class_icon'],
		'DUEL_K_RACE' => $data['win']['race'],
		'DUEL_KB_RACE' => $data['loss']['race'],
		'DUEL_K_COUNT' => $data['win']['countn'],
		'DUEL_KB_COUNT' => $data['loss']['countn'],
		'DUEL_K_GUILD' => $data['win']['guild'],
		'DUEL_KB_GUILD' => $data['loss']['guild'],
		'DUEL_K_DIFF' => $data['win']['leveldiff'],
		'DUEL_KB_DIFF' => $data['loss']['leveldiff'],
		)
	);
}


function output_pvplog( $pvps )
{
	global $roster, $addon;

	$worldPvPWin = 0;
	$worldPvPLoss = 0;
	$worldPvPPerc = 0;

	foreach( $pvps as $row )
	{
		if( $row->data['win'] == '1' )
		{
			$worldPvPWin = $worldPvPWin + 1;
		}
		else
		{
			$worldPvPLoss = $worldPvPLoss + 1;
		}
	}
	if( $worldPvPWin > 0 and $worldPvPLoss > 0 )
	{
		$worldPvPPerc = round((100*$worldPvPWin)/($worldPvPWin + $worldPvPLoss));
	}

	// Get the world best zone
	$query = "SELECT `zone`, COUNT(`zone`) AS countz FROM " . $roster->db->table('pvp2',$addon['basename']) . " WHERE `member_id` = '" . $roster->data['member_id'] . "' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY `zone` ORDER BY countz DESC LIMIT 0,1";
	$wbzone = $roster->db->query_first($query);

	// Get the world worst zone
	$query = "SELECT `zone`, COUNT(`zone`) AS countz FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $roster->data['member_id'] . "' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY `zone` ORDER BY countz DESC LIMIT 0,1";
	$wwzone = $roster->db->query_first($query);

	// Get vs guild best zone
	$query = "SELECT guild, COUNT(guild) AS countg FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $roster->data['member_id'] . "' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY guild ORDER BY countg DESC LIMIT 0,1";
	$gbzone = $roster->db->query_first($query);

	// Get vs guild worst zone
	$query = "SELECT guild, COUNT(guild) AS countg FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $roster->data['member_id'] . "' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY guild ORDER BY countg DESC LIMIT 0,1";
	$gwzone = $roster->db->query_first($query);

	// Get vs player loss stats
	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $roster->data['member_id'] . "' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	$data['loss'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	// Get vs player win stats
	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` WHERE `member_id` = '" . $roster->data['member_id'] . "' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	$data['win'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	foreach( $data as $datakey => $dataset )
	{
		// Get Class Icon
		foreach( $roster->multilanguages as $language )
		{
			$dataset['icon_name'] = ( isset($roster->locale->wordings[$language]['class_iconArray'][$dataset['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$dataset['class']] : '' );
			if( strlen($dataset['icon_name']) > 0 ) break;
		}

		if( !empty($dataset['icon_name']) )
		{
			$data[$datakey]['class_icon'] = '<img style="cursor:help;" onmouseover="return overlib(\'' . $dataset['class'] . '\',WRAP);" onmouseout="return nd();" class="membersRowimg" width="16" height="16" src="' . $roster->config['img_url'] . 'class/' . $dataset['icon_name'] . '.jpg" alt="" />&nbsp;';
		}
		else
		{
			$data[$datakey]['class_icon'] = '';
		}

		// Fix table rows if they are empty
		$check_array = array('name', 'guild', 'race', 'class', 'leveldiff', 'countn');

		foreach( $check_array as $check_value )
		{
			if( $dataset[$check_value] == '' )
			{
				$data[$datakey][$check_value] = '&nbsp;';
			}
		}
	}
	$roster->tpl->assign_vars(array(
		'PVP_WORLD_PVP_PERC' => $worldPvPPerc,
		'PVP_WORLD_BEST_ZONE' => ( $wbzone ? $wbzone : 'N/A' ),
		'PVP_WORLD_WORST_ZONE' => ( $wwzone ? $wwzone : 'N/A' ),

		'PVP_GUILD_BEST_ZONE' => ( $gbzone ? $gbzone : 'N/A' ),
		'PVP_GUILD_WORST_ZONE' => ( $gwzone ? $gwzone : 'N/A' ),

		'PVP_K_NAME'  => $data['win']['name'],
		'PVP_KB_NAME' => $data['loss']['name'],
		'PVP_K_CLASS'  => $data['win']['class'],
		'PVP_KB_CLASS' => $data['loss']['class'],
		'PVP_K_CLASS_ICON'  => $data['win']['class_icon'],
		'PVP_KB_CLASS_ICON' => $data['loss']['class_icon'],
		'PVP_K_RACE' => $data['win']['race'],
		'PVP_KB_RACE' => $data['loss']['race'],
		'PVP_K_COUNT' => $data['win']['countn'],
		'PVP_KB_COUNT' => $data['loss']['countn'],
		'PVP_K_GUILD' => $data['win']['guild'],
		'PVP_KB_GUILD' => $data['loss']['guild'],
		'PVP_K_DIFF' => $data['win']['leveldiff'],
		'PVP_KB_DIFF' => $data['loss']['leveldiff'],
		)
	);
}


function output_pvp2( $pvps , $url , $type )
{
	global $roster;

	$roster->tpl->assign_vars(array(
		'S_SHOW_LOG' => true,

		'U_WHEN'      => makelink($url . '&amp;s=date'),
		'U_CLASS'     => makelink($url . '&amp;s=class'),
		'U_NAME'      => makelink($url . '&amp;s=name'),
		'U_RACE'      => makelink($url . '&amp;s=race'),
		'U_RANK'      => makelink($url . '&amp;s=rank'),
		'U_GUILD'     => makelink($url . '&amp;s=guild'),
		'U_REALM'     => makelink($url . '&amp;s=realm'),
		'U_LEVELDIFF' => makelink($url . '&amp;s=leveldiff'),
		'U_WIN'       => makelink($url . '&amp;s=win'),
		'U_HONOR'     => makelink($url . '&amp;s=honor'),
		'U_ZONE'      => makelink($url . '&amp;s=zone'),
		'U_SUBZONE'   => makelink($url . '&amp;s=subzone'),

		)
	);

	foreach( $pvps as $row )
	{
		$diff = $row->data['leveldiff'];
		if( $diff < -10 )
		{
			$diffcolor = 'grey';
		}
		elseif( $diff < -4 )
		{
			$diffcolor = 'green';
		}
		elseif( $diff > 4 )
		{
			$diffcolor = 'red';
		}
		else
		{
			$diffcolor = 'yellow';
		}

		// Get Class Icon
		foreach( $roster->multilanguages as $language )
		{
			$icon_name = ( isset($roster->locale->wordings[$language]['class_iconArray'][$row->data['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$row->data['class']] : '' );
			if( strlen($icon_name) > 0 )
			{
				break;
			}
		}

		if( !empty($icon_name) )
		{
			$class_icon = '<img style="cursor:help;" onmouseover="return overlib(\'' . $row->data['class'] . '\',WRAP);" onmouseout="return nd();" class="membersRowimg" width="16" height="16" src="' . $roster->config['img_url'] . 'class/' . $icon_name . '.jpg" alt="" />&nbsp;';
		}
		else
		{
			$class_icon = '';
		}

		$roster->tpl->assign_block_vars('log_row', array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'DATE' => $row->data['date2'],
			'NAME' => $row->data['name'],
			'CLASS' => $row->data['class'],
			'CLASS_ICON' => $class_icon,
			'RACE' => $row->data['race'],
			'RANK' => $row->data['rank'],
			'GUILD' => $row->data['guild'],
			'REALM' => $row->data['realm'],
			'DIFF_COLOR' => $diffcolor,
			'DIFF' => ( $diff > 0 ? '+' : '' ) . $diff,
			'WIN' => (bool)$row->data['win'],
			'HONOR' => $row->data['honor'],
			'ZONE' => $row->data['zone'],
			'SUBZONE' => $row->data['subzone'],
			)
		);
	}
}
