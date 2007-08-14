<?php
/**
 * WoWRoster.net WoWRoster
 *
 * PvPLog history and data
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    PvPLog
 * @subpackage PvPLog Library
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Multiple edits by Gaxme, 16 May 2006
// Thanks :)

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


	function outHeader()
	{
		return '<div class="pvptype">'.$this->data['guild'].' </div>';
	}


	function out2()
	{
		$returnstring = '<b><font face="Georgia" size="+1" color="#0000FF"></font></b>';
		$returnstring .= '['.$this->data['pvp_level'].'] '.$this->data['pvp_name'];
		return $returnstring;
	}


	function out()
	{
		global $roster;

		$max = ROSTER_MAXCHARLEVEL;
		$level = $this->data['pvp_level'];
		if( $max == 1 )
		{
			$bgImage = $roster->config['img_url'].'bargrey.gif';
		}
		else
		{
			$bgImage = $roster->config['img_url'].'barempty.gif';
		}

		$returnstring = '
<div class="pvp">
	<div class="pvpbox">
		<img class="bg" alt="" src="'.$bgImage.'" />';
		if( $max > 1 )
		{
			$width = intval(($level/$max) * 354);
			$returnstring .= '<img src="'.$roster->config['img_url'].'barbit.gif" alt="" class="bit" width="'.$width.'" />';
		}
		$returnstring .= '<span class="name">'.$this->data['pvp_name'].'</span>';

		if( $max > 1 )
		{
			$returnstring .= '<span class="level"> ['.$level.']</span>';
		}
		$returnstring .= '
	</div>
</div>';

		return $returnstring;
	}
}


function show_pvp2( $type , $url , $sort , $start )
{
	global $roster;

	$pvps = pvp_get_many3( $roster->data['member_id'],$type, $sort, -1);
	$returnstring = '<div align="center">';

	if( is_array($pvps) )
	{
		$returnstring .= output_pvp_summary($pvps,$type);

		if( isset( $pvps[0] ) )
		{
			switch ($type)
			{
				case 'BG':
					$returnstring .= output_bglog($roster->data['member_id']);
					break;

				case 'PvP':
					$returnstring .= output_pvplog($roster->data['member_id']);
					break;

				case 'Duel':
					$returnstring .= output_duellog($roster->data['member_id']);
					break;

				default:
					break;
			}
		}

		$returnstring .= '<br />';
		$returnstring .= '<br />';

		$max = sizeof($pvps);
		$sort_part = $sort ? "&amp;s=$sort" : '';

		if ($start > 0)
		{
			$prev = '<a href="'.makelink($url.'&amp;start=0'.$sort_part).'">|&lt;&lt;</a>&nbsp;&nbsp;'.'<a href="'.makelink($url.'&amp;start='.($start-50).$sort_part).'">&lt;</a> ';
		}
		else
		{
			$prev = '';
		}

		if (($start+50) < $max)
		{
			$listing = '<small>['.$start.' - '.($start+50).'] of '.$max.'</small>';
			$next = ' <a href="'.makelink($url.'&amp;start='.($start+50).$sort_part).'">&gt;</a>&nbsp;&nbsp;'.'<a href="'.makelink($url.'&amp;start='.($max-50).$sort_part).'">&gt;&gt;|</a>';
		}
		else
		{
			$listing = '<small>['.$start.' - '.($max).'] of '.$max.'</small>';
			$next = '';
		}

		$pvps = pvp_get_many3( $roster->data['member_id'],$type, $sort, $start);

		if( isset( $pvps[0] ) )
		{
			$returnstring .= border('sgray','start',$prev.'Log '.$listing.$next);
			$returnstring .= output_pvp2($pvps, $url."&amp;start=".$start,$type);
			$returnstring .= border('sgray','end');
		}

		$returnstring .= '<br />';

		if ($start > 0)
		{
			$returnstring .= $prev;
		}

		if (($start+50) < $max)
		{
			$returnstring .= '['.$start.' - '.($start+50).'] of '.$max;
			$returnstring .= $next;
		}
		else
		{
			$returnstring .= '['.$start.' - '.($max).'] of '.$max;
		}

		$returnstring .= '</div><br />';

		return $returnstring;
	}
	else
	{
		return '';
	}
}


function pvp_get_many3($member_id, $type, $sort, $start)
{
	global $roster;

	$query= "SELECT *, DATE_FORMAT(date, '".$roster->locale->act['timeformat']."') AS date2 FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND ";

	if ($type == 'PvP')
	{
		$query .= "`enemy` = '1' AND `bg` = '0'";
	}
	else if ($type == 'BG')
	{
		$query .= "`enemy` = '1' AND `bg` >= '1'";
	}
	else
	{
		$query .= "`enemy` = '0'";
	}

	if ($sort == 'name')
	{
		$query .= " ORDER BY 'name', 'level' DESC, 'guild'";
	}
	else if ($sort == 'race')
	{
		$query .= " ORDER BY 'race', 'guild', 'name', 'level' DESC";
	}
	else if ($sort == 'class')
	{
		$query .= " ORDER BY 'class', 'guild', 'name', 'level' DESC";
	}
	else if ($sort == 'leveldiff')
	{
		$query .= " ORDER BY 'leveldiff' DESC, 'guild', 'name' ";
	}
	else if ($sort == 'win')
	{
		$query .= " ORDER BY 'win' DESC, 'guild', 'name' ";
	}
	else if ($sort == 'zone')
	{
		$query .= " ORDER BY 'zone', 'guild', 'name' ";
	}
	else if ($sort == 'subzone')
	{
		$query .= " ORDER BY 'subzone', 'guild', 'name' ";
	}
	else if ($sort == 'date')
	{
		$query .= " ORDER BY 'date', 'guild', 'name' ";
	}
	else if ($sort == 'bg')
	{
		$query .= " ORDER BY 'bg', 'guild', 'name' ";
	}
	else if ($sort == 'honor')
	{
		$query .= " ORDER BY 'honor', 'guild', 'name' ";
	}
	else if ($sort == 'rank')
	{
		$query .= " ORDER BY 'rank', 'guild', 'name' ";
	}
	else if ($sort == 'guild')
	{
		$query .= " ORDER BY 'guild', 'name', 'level' DESC ";
	}
	else if ($sort == 'realm')
	{
		$query .= " ORDER BY 'realm', 'name', 'level' DESC ";
	}
	else
	{
		$query .= " ORDER BY 'date' DESC, 'guild', 'name' ";
	}

	if ($start != -1)
	{
		$query = $query.' LIMIT '.$start.', 50';
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


function output_pvp_summary($pvps,$type)
{
	global $roster;

	$tot_wins = 0;
	$tot_losses = 0;
	$ave_win_level_diff = 0;
	$ave_loss_level_diff = 0;

	foreach ($pvps as $row)
	{
		if ($row->data['win'] == '1')
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

	if ($tot_wins > 0)
	{
		$ave_win_level_diff = $ave_win_level_diff / $tot_wins;
		$ave_win_level_diff = round($ave_win_level_diff, 2);
		if ($ave_win_level_diff > 0)
		{
			$ave_win_level_diff = '+'.$ave_win_level_diff;
		}
	}
	else
	{
		$ave_win_level_diff = 0;
	}

	if ($tot_losses > 0)
	{
		$ave_loss_level_diff = $ave_loss_level_diff / $tot_losses;
		$ave_loss_level_diff = round($ave_loss_level_diff, 2);
		if ($ave_loss_level_diff > 0)
		{
			$ave_loss_level_diff = '+'.$ave_loss_level_diff;
		}
	}
	else
	{
		$ave_loss_level_diff = 0;
	}

	$total = $tot_wins - $tot_losses;
	if ($total > 0)
	{
		$total = '+'.$total;
	}

	$winpercent = round( ($tot_wins / ($tot_wins + $tot_losses)), 2 ) * 100;

	switch ($type)
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
	$returnstring = '
'.border('sgray','start',$header_text).'
<table class="bodyline" width="280" cellspacing="0">
	<tr>
		<td class="membersRow2" width="200">'.$roster->locale->act['totalwins'].'</td>
		<td class="membersRowRight2" width="80">'.$tot_wins.'</td>
	</tr>
	<tr>
		<td class="membersRow1" width="200">'.$roster->locale->act['totallosses'].'</td>
		<td class="membersRowRight1" width="80">'.$tot_losses.'</td>
	</tr>
	<tr>
		<td class="membersRow2" width="200">'.$roster->locale->act['totaloverall'].'</td>
		<td class="membersRowRight2" width="80">'.$total.' ('.$winpercent.'%)</td>
	</tr>
	<tr>
		<td class="membersRow1" width="200">'.$roster->locale->act['win_average'].'</td>
		<td class="membersRowRight1" width="80">'.$ave_win_level_diff.'</td>
	</tr>
	<tr>
		<td class="membersRow2" width="200">'.$roster->locale->act['loss_average'].'</td>
		<td class="membersRowRight2" width="80">'.$ave_loss_level_diff.'</td>
	</tr>
</table>
'.border('sgray','end');

	return $returnstring;
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
function calc_winloss($a, $b)
{
	if ($a['WinLoss'] == $b['WinLoss'])
		return 0;
	else
		return ($a['WinLoss'] < $b['WinLoss']);
}


function calc_gwinloss($a, $b)
{
	if ($a['killed'] == $b['killed'])
		return 0;
	else
		return ($a['killed'] < $b['killed']);
}


function calc_pwinloss($a, $b)
{
	if ($a['killed'] == $b['killed'])
		return 0;
	else
		return ($a['killed'] < $b['killed']);
}


function output_bglog($member_id)
{
	global $roster;

	$bg_array = array(
		'eye_of_the_storm'=>'spurple',
		'alterac_valley'=>'sblue',
		'arathi_basin'=>'sgreen',
		'warsong_gulch'=>'sorange',
	);

	$query= "SELECT *, DATE_FORMAT(date, '".$roster->locale->act['timeformat']."') AS date2 FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` >= '1'";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$pvps = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$pvp = new pvp3( $data );
		$pvps[] = $pvp;
	}


	$returnstring = "<br /><br />\n";


	foreach( $bg_array as $bgname => $bgcolor )
	{
		$wins=0;
		$loss=0;
		$pwin = $gwin = $ploss = $gloss = $subs = array();
		foreach ($pvps as $row)
		{
			if( $row->data['zone'] == $roster->locale->act[$bgname] )
			{
				// Set some defaults
				$eguild = $row->data['guild'];
				$esub = $row->data['subzone'];
				$ename = $row->data['name'];
				$eclass = $row->data['class'];

				if (empty($eguild) || !isset($eguild) || $eguild == '')
				{
					$eguild = '--';
				}
				if (empty($esub) || !isset($esub) || $esub == '')
				{
					$esub = 'None';
				}

				if(!isset($subs[$esub]))
				{
					$subs[$esub] = array('Wins' => 0, 'WinLoss' => 0, 'Zone' => 0, 'Loss' => 0);
				}

				if(!isset($gwin[$eguild]))
				{
					$gwin[$eguild] = array('killed' => 0, 'name');
				}

				if(!isset($pwin[$ename]))
				{
					$pwin[$ename] = array('killed' => 0, 'name', 'class', 'class_icon');
				}

				if(!isset($gloss[$eguild]))
				{
					$gloss[$eguild] = array('killed' => 0, 'name');
				}

				if(!isset($ploss[$ename]))
				{
					$ploss[$ename] = array('killed' => 0, 'name', 'class', 'class_icon');
				}


				// Get Class Icon
				foreach ($roster->multilanguages as $language)
				{
					$icon_name = isset($roster->locale->wordings[$language]['class_iconArray'][$eclass]) ? $roster->locale->wordings[$language]['class_iconArray'][$eclass] : '';
					if( strlen($icon_name) > 0 ) break;
				}

				if( !empty($icon_name) )
				{
					$icon_name = 'Interface/Icons/'.$icon_name;
					$class_icon = '<img style="cursor:help;" '.makeOverlib($eclass,'','',2,'',',WRAP').' class="membersRowimg" width="16" height="16" src="'.$roster->config['interface_url'].$icon_name.'.'.$roster->config['img_suffix'].'" alt="" />&nbsp;';
				}
				else
				{
					$class_icon = '';
				}

				$win_level_diff = $loss_level_diff = 0;

				if ($row->data['win'] == '1')
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

		if ($wins > 0)
		{
			$win_level_diff = $win_level_diff / $wins;
			$win_level_diff = round($win_level_diff, 2);
			if ($win_level_diff > 0)
			{
				$win_level_diff = '+'.$win_level_diff;
			}
		}
		else
		{
			$win_level_diff = 0;
		}

		if ($loss > 0)
		{
			$loss_level_diff = $loss_level_diff / $loss;
			$loss_level_diff = round($loss_level_diff, 2);
			if ($loss_level_diff > 0)
			{
				$loss_level_diff = '+'.$loss_level_diff;
			}
		}
		else
		{
			$loss_level_diff = 0;
		}

		$total = $wins - $loss;
		if ($total > 0)
		{
			$total = '+'.$total;
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
		$killedclass = ( isset($pwin[0]['class_icon']) ? $pwin[0]['class_icon'] : '' );

		$deaths = ( isset($ploss[0]['killed']) ? $ploss[0]['killed'] : '' );
		$killedBy = ( isset($ploss[0]['name']) ? $ploss[0]['name'] : '' );
		$killedByclass = ( isset($ploss[0]['class_icon']) ? $ploss[0]['class_icon'] : '' );

		$gkills = ( isset($gwin[0]['killed']) ? $gwin[0]['killed'] : '' );
		$gkilled = ( isset($gwin[0]['name']) ? $gwin[0]['name'] : '' );
		$gdeaths = ( isset($gloss[0]['killed']) ? $gloss[0]['killed'] : '' );
		$gkilledBy = ( isset($gloss[0]['name']) ? $gloss[0]['name'] : '' );

		$bgtable = '			<table width="100%" cellpadding="0" cellspacing="0" class="bodyline">
				<tr>
					<td class="membersRow2">' . $roster->locale->act['wins'] . '</td>
					<td class="membersRowRight2" style="white-space:normal;">' . $wins . '</td>
				</tr>
				<tr>
					<td class="membersRow1">' . $roster->locale->act['losses'] . '</td>
					<td class="membersRowRight1" style="white-space:normal;">' . $loss . '</td>
				</tr>
				<tr>
					<td class="membersRow2">' . $roster->locale->act['overall'] . '</td>
					<td class="membersRowRight2" style="white-space:normal;">' . $total . ' (' . $winpercent . '%)</td>
				</tr>
				<tr>
					<td class="membersRow1">' . $roster->locale->act['win_average'] . '</td>
					<td class="membersRowRight1" style="white-space:normal;">' . $win_level_diff . '</td>
				</tr>
				<tr>
					<td class="membersRow2">' . $roster->locale->act['loss_average'] . '</td>
					<td class="membersRowRight2" style="white-space:normal;">' . $loss_level_diff . '</td>
				</tr>
				<tr>
					<td class="membersRow1">' . $roster->locale->act['bestsub'] . '</td>
					<td class="membersRowRight1" style="white-space:normal;">' . $best . ' (' . $bestNum . ')</td>
				</tr>
				<tr>
					<td class="membersRow2">' . $roster->locale->act['worstsub'] . '</td>
					<td class="membersRowRight2" style="white-space:normal;">' . $worst . ' (' . $worstNum . ')</td>
				</tr>
				<tr>
					<td class="membersRow1">' . $roster->locale->act['killedmost'] . '</td>
					<td class="membersRowRight1" style="white-space:normal;">' . $killedclass . $killed . ' (' . $kills . ')</td>
				</tr>
				<tr>
					<td class="membersRow2">' . $roster->locale->act['killedmostby'] . '</td>
					<td class="membersRowRight2" style="white-space:normal;">' . $killedByclass . $killedBy . ' (' . $deaths . ')</td>
				</tr>
				<tr>
					<td class="membersRow1">' . $roster->locale->act['gkilledmost'] . '</td>
					<td class="membersRowRight1" style="white-space:normal;">' . $gkilled . ' (' . $gkills . ')</td>
				</tr>
				<tr>
					<td class="membersRow2">' . $roster->locale->act['gkilledmostby'] . '</td>
					<td class="membersRowRight2" style="white-space:normal;">' . $gkilledBy . ' (' . $gdeaths . ')</td>
				</tr>
			</table>';

		$returnstring .= messageboxtoggle($bgtable,$roster->locale->act[$bgname],$bgcolor,false,'400px') . "<br />\n";
	}

	return $returnstring;
}


function output_duellog($member_id)
{
	global $roster;

	$data = array();

	$returnstring = '<br />'.border('sblue','start',$roster->locale->act['duelsummary']);

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '0' AND `bg` = '0' AND `win` = '0' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$data['loss'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '0' AND `bg` = '0' AND `win` = '1' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$data['win'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	foreach( $data as $datakey => $dataset )
	{
		// Get Class Icon
		foreach ($roster->multilanguages as $language)
		{
			$dataset['icon_name'] = ( isset($roster->locale->wordings[$language]['class_iconArray'][$dataset['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$dataset['class']] : '' );
			if( strlen($dataset['icon_name']) > 0 ) break;
		}

		if( !empty($dataset['icon_name']) )
		{
			$dataset['icon_name'] = 'class'.$dataset['icon_name'];
			$data[$datakey]['class_icon'] = '<img style="cursor:help;" '.makeOverlib($dataset['class'],'','',2,'',',WRAP').' class="membersRowimg" width="16" height="16" src="'.$roster->config['interface_url'].$dataset['icon_name'].'.'.$roster->config['img_suffix'].'" alt="" />&nbsp;';
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


	$returnstring .= "
<table width='400' cellpadding='0' cellspacing='0'>
	<tr>
		<th width='20%' class='membersHeader'>&nbsp;</th>
		<th width='40%' class='membersHeader'>".$roster->locale->act['most_killed']."</th>
		<th width='40%' class='membersHeaderRight'>".$roster->locale->act['most_killed_by']."</th>
	</tr>
	<tr>
		<td class='membersRow1'>".$roster->locale->act['name']."</td>
		<td class='membersRow1'>".$data['win']['class_icon'].$data['win']['name']."</td>
		<td class='membersRowRight1'>".$data['loss']['class_icon'].$data['loss']['name']."</td>
	</tr>
	<tr>
		<td class='membersRow2'>".$roster->locale->act['race']."</td>
		<td class='membersRow2'>".$data['win']['race']."</td>
		<td class='membersRowRight2'>".$data['loss']['race']."</td>
	</tr>
	<tr>
		<td class='membersRow1'>".$roster->locale->act['kills']."</td>
		<td class='membersRow1'>".$data['win']['countn']."</td>
		<td class='membersRowRight1'>".$data['loss']['countn']."</td>
	</tr>
	<tr>
		<td class='membersRow2'>".$roster->locale->act['guild']."</td>
		<td class='membersRow2'>".$data['win']['guild']."</td>
		<td class='membersRowRight2'>".$data['loss']['guild']."</td>
	</tr>
	<tr>
		<td class='membersRow1'>".$roster->locale->act['leveldiff']."</td>
		<td class='membersRow1'>".$data['win']['leveldiff']."</td>
		<td class='membersRowRight1'>".$data['loss']['leveldiff']."</td>
	</tr>
</table>
".border('sblue','end');

	return $returnstring;
}


function output_pvplog($member_id)
{
	global $roster;

	$query= "SELECT *, DATE_FORMAT(date, '".$roster->locale->act['timeformat']."') AS date2 FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0'";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$pvps = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$pvp = new pvp3( $data );
		$pvps[] = $pvp;
	}

	$worldPvPWin = 0;
	$worldPvPLoss = 0;
	$worldPvPPerc = 0;

	foreach ($pvps as $row)
	{
		if ($row->data['win'] == '1')
		{
			$worldPvPWin = $worldPvPWin + 1;
		}
		else
		{
			$worldPvPLoss = $worldPvPLoss + 1;
		}
	}
	if ($worldPvPWin > 0 and $worldPvPLoss > 0)
	{
		$worldPvPPerc = round((100*$worldPvPWin)/($worldPvPWin + $worldPvPLoss));
	}


	$returnstring = "
<br />
".border('sgreen','start',$roster->locale->act['world_pvp'])."
<table width='400' cellpadding='0' cellspacing='0'>
	<tr>
		<th width='10%' class='membersHeader'><div align='center'>".$roster->locale->act['win']." %</div></th>
		<th width='45%' class='membersHeader'><div align='center'>".$roster->locale->act['best_zone']."</div></th>
		<th width='45%' class='membersHeaderRight'><div align='center'>".$roster->locale->act['worst_zone']."</div></th>
	</tr>
	<tr>
		<td class='membersRow1'><div align='center'>".$worldPvPPerc." %</div></td>
		<td class='membersRow1'><div align='center'>";

	$query = "SELECT `zone`, COUNT(`zone`) as countz FROM ".$roster->db->table('pvp2')." WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY `zone` ORDER BY countz DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$rzone = $roster->db->fetch($result);
	if ($rzone)
	{
		$returnstring .= $rzone['zone'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$roster->db->free_result($result);

	$returnstring .= "</div></td>
		<td class='membersRowRight1'><div align='center'>";

	$query = "SELECT `zone`, COUNT(`zone`) AS countz FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY `zone` ORDER BY countz DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$rzone = $roster->db->fetch($result);
	if ($rzone)
	{
		$returnstring .= $rzone['zone'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$roster->db->free_result($result);

	$returnstring .= "</div></td>
	</tr>
</table>
".border('sgreen','end')."

<br />

".border('syellow','start',$roster->locale->act['versus_guilds'])."
<table width='400' cellpadding='0' cellspacing='0'>
	<tr>
		<th width='50%' class='membersHeader'><div align='center'>".$roster->locale->act['most_killed']."</div></th>
		<th width='50%' class='membersHeaderRight'><div align='center'>".$roster->locale->act['most_killed_by']."</div></th>
	</tr>
	<tr>
		<td class='membersRow1'><div align='center'>";

	$query = "SELECT guild, COUNT(guild) AS countg FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY guild ORDER BY countg DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$rguild = $roster->db->fetch($result);
	if ($rguild)
	{
		$returnstring .= $rguild['guild'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$roster->db->free_result($result);

	$returnstring .= "</div></td>
		<td class='membersRowRight1'><div align='center'>";

	$query = "SELECT guild, COUNT(guild) AS countg FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY guild ORDER BY countg DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$rguild = $roster->db->fetch($result);
	if ($rguild)
	{
		$returnstring .= $rguild['guild'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$roster->db->free_result($result);

	$returnstring .= "</div></td>
	</tr>
</table>
".border('syellow','end')."

<br />

".border('sblue','start',$roster->locale->act['versus_players']);

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$data['loss'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `".$roster->db->table('pvp2')."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$data['win'] = $roster->db->fetch($result);
	$roster->db->free_result($result);

	foreach( $data as $datakey => $dataset )
	{
		// Get Class Icon
		foreach ($roster->multilanguages as $language)
		{
			if( isset($dataset['class']) )
			{
				$dataset['icon_name'] = ( isset($roster->locale->wordings[$language]['class_iconArray'][$dataset['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$dataset['class']] : '' );
				if( strlen($dataset['icon_name']) > 0 ) break;
			}
			else
			{
				break;
			}
		}

		if( !empty($dataset['icon_name']) )
		{
			$dataset['icon_name'] = 'Interface/Icons/'.$dataset['icon_name'];
			$data[$datakey]['class_icon'] = '<img style="cursor:help;" '.makeOverlib($dataset['class'],'','',2,'',',WRAP').' class="membersRowimg" width="16" height="16" src="'.$roster->config['interface_url'].$dataset['icon_name'].'.'.$roster->config['img_suffix'].'" alt="" />&nbsp;';
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


	$returnstring .= "
<table width='400' cellpadding='0' cellspacing='0'>
	<tr>
		<th width='20%' class='membersHeader'>&nbsp;</th>
		<th width='40%' class='membersHeader'>".$roster->locale->act['most_killed']."</th>
		<th width='40%' class='membersHeaderRight'>".$roster->locale->act['most_killed_by']."</th>
	</tr>
	<tr>
		<td class='membersRow1'>".$roster->locale->act['name']."</td>
		<td class='membersRow1'>".$data['win']['class_icon'].$data['win']['name']."</td>
		<td class='membersRowRight1'>".$data['loss']['class_icon'].$data['loss']['name']."</td>
	</tr>
	<tr>
		<td class='membersRow2'>".$roster->locale->act['race']."</td>
		<td class='membersRow2'>".$data['win']['race']."</td>
		<td class='membersRowRight2'>".$data['loss']['race']."</td>
	</tr>
	<tr>
		<td class='membersRow1'>".$roster->locale->act['kills']."</td>
		<td class='membersRow1'>".$data['win']['countn']."</td>
		<td class='membersRowRight1'>".$data['loss']['countn']."</td>
	</tr>
	<tr>
		<td class='membersRow2'>".$roster->locale->act['guild']."</td>
		<td class='membersRow2'>".$data['win']['guild']."</td>
		<td class='membersRowRight2'>".$data['loss']['guild']."</td>
	</tr>
	<tr>
		<td class='membersRow1'>".$roster->locale->act['leveldiff']."</td>
		<td class='membersRow1'>".$data['win']['leveldiff']."</td>
		<td class='membersRowRight1'>".$data['loss']['leveldiff']."</td>
	</tr>
</table>
".border('sblue','end');

	return $returnstring;
}


function output_pvp2($pvps,$url,$type)
{
	global $roster;

	$returnstring = '
<table class="bodyline" cellspacing="0">
	<tr>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=date').'">'.$roster->locale->act['when'].'</a></th>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=class').'">'.$roster->locale->act['class'].'</a> /
			<a href="'.makelink($url.'&amp;s=name').'">'.$roster->locale->act['name'].'</a></th>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=race').'">'.$roster->locale->act['race'].'</a></th>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=rank').'">'.$roster->locale->act['rank'].'</a></th>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=guild').'">'.$roster->locale->act['guild'].'</a></th>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=realm').'">'.$roster->locale->act['realm'].'</a></th>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=leveldiff').'">'.$roster->locale->act['leveldiff'].'</a></th>
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=win').'">'.$roster->locale->act['win'].'</a></th>';
	if( $type != 'Duel' )
	{
		$returnstring .= '
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=honor').'">'.$roster->locale->act['honor'].'</a></th>';
	}
	$returnstring .= '
		<th class="membersHeader"><a href="'.makelink($url.'&amp;s=zone').'">'.$roster->locale->act['zone'].'</a></th>
		<th class="membersHeaderRight"><a href="'.makelink($url.'&amp;s=subzone').'">'.$roster->locale->act['subzone'].'</a></th>
	</tr>';

	$rc = 0;
	foreach ($pvps as $row)
	{
		$diff = $row->data['leveldiff'];
		if ($diff < -10)
		{
			$diffcolor = 'grey';
		}
		elseif ($diff < -4)
		{
			$diffcolor = 'green';
		}
		elseif ($diff > 4)
		{
			$diffcolor = 'red';
		}
		else
		{
			$diffcolor = 'yellow';
		}

		if ($row->data['win'] == '1')
		{
			$result = '<img class="membersRowimg" src="img/pvp-win.gif" alt="'.$roster->locale->act['win'].'" />';
		}
		elseif($row->data['win'] == '0')
		{
			$result = '<img class="membersRowimg" src="img/pvp-loss.gif" alt="'.$roster->locale->act['loss'].'" />';
		}

		// Get Class Icon
		foreach ($roster->multilanguages as $language)
		{
			$icon_name = ( isset($roster->locale->wordings[$language]['class_iconArray'][$row->data['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$row->data['class']] : '' );
			if( strlen($icon_name) > 0 ) break;
		}
		$icon_name = 'Interface/Icons/'.$icon_name;
		$class_icon = '<img style="cursor:help;" '.makeOverlib($row->data['class'],'','',2,'',',WRAP').' class="membersRowimg" width="16" height="16" src="'.$roster->config['interface_url'].$icon_name.'.'.$roster->config['img_suffix'].'" alt="" />&nbsp;';

		$row_st = (($rc%2)+1);
		$returnstring .= '
	<tr>
		<td class="membersRow'.$row_st.'">'.$row->data['date2'].'</td>
		<td class="membersRow'.$row_st.'">'.$class_icon.$row->data['name'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['race'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['rank'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['guild'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['realm'].'</td>
		<td class="membersRow'.$row_st.'"><span class="'.$diffcolor.'">';
		if ($diff > 0)
		{
			$returnstring .= '+';
		}
		$returnstring .= $diff.'</span></td>
		<td class="membersRow'.$row_st.'">'.$result.'</td>
';

		if ($type != 'Duel')
		{
			$returnstring .= '		<td class="membersRow'.$row_st.'">'.$row->data['honor'].'</td>';
		}

		$returnstring .= '
		<td class="membersRow'.$row_st.'">';

		if ($row->data['zone'] != '')
			$returnstring .= $row->data['zone'].'</td>';
		else
			$returnstring .= '&nbsp;</td>';

		$returnstring .= '
		<td class="membersRowRight'.$row_st.'">';

		if ($row->data['subzone'] != '')
			$returnstring .= $row->data['subzone'].'</td>';
		else
			$returnstring .= '&nbsp;</td>';

		$returnstring .= "\n\t</tr>";
		$rc++;
	}
	$returnstring .= "\n</table>\n";
	return $returnstring;
}
