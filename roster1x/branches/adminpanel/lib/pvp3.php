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

// Multiple edits by Gaxme, 16 May 2006
// Thanks :)

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
		global $roster_conf;

		$max = 60;
		$level = $this->data['pvp_level'];
		if( $max == 1 )
		{
			$bgImage = $roster_conf['img_url'].'bargrey.gif';
		}
		else
		{
			$bgImage = $roster_conf['img_url'].'barempty.gif';
		}

		$returnstring = '
<div class="pvp">
	<div class="pvpbox">
		<img class="bg" alt="" src="'.$bgImage.'" />';
		if( $max > 1 )
		{
			$width = intval(($level/$max) * 354);
			$returnstring .= '<img src="'.$roster_conf['img_url'].'barbit.gif" alt="" class="bit" width="'.$width.'" />';
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

function pvp_get_many3($member_id, $type, $sort, $start)
{
	global $wowdb, $roster_conf, $timeformat;

	$query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_conf['roster_lang']]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND ";

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
		$query .= " ORDER BY 'name', 'level' DESC, 'guild'";
	else if ($sort == 'race')
		$query .= " ORDER BY 'race', 'guild', 'name', 'level' DESC";
	else if ($sort == 'class')
		$query .= " ORDER BY 'class', 'guild', 'name', 'level' DESC";
	else if ($sort == 'leveldiff')
		$query .= " ORDER BY 'leveldiff' DESC, 'guild', 'name' ";
	else if ($sort == 'win')
		$query .= " ORDER BY 'win' DESC, 'guild', 'name' ";
	else if ($sort == 'zone')
		$query .= " ORDER BY 'zone', 'guild', 'name' ";
	else if ($sort == 'subzone')
		$query .= " ORDER BY 'subzone', 'guild', 'name' ";
	else if ($sort == 'date')
		$query .= " ORDER BY 'date', 'guild', 'name' ";
	else if ($sort == 'bg')
		$query .= " ORDER BY 'bg', 'guild', 'name' ";
	else if ($sort == 'honor')
		$query .= " ORDER BY 'honor', 'guild', 'name' ";
	else if ($sort == 'rank')
		$query .= " ORDER BY 'rank', 'guild', 'name' ";
	else if ($sort == 'guild')
		$query .= " ORDER BY 'guild', 'name', 'level' DESC ";
	else
		$query .= " ORDER BY 'date' DESC, 'guild', 'name' ";

	if ($start != -1)
		$query = $query.' LIMIT '.$start.', 50';

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	if( $wowdb->num_rows($result) > 0 )
	{
		$pvps = array();
		while( $data = $wowdb->fetch_assoc( $result ) )
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
	global $roster_conf, $wordings;

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

	$returnstring = '
'.border('sgray','start',$type.' Log').'
<table class="bodyline" width="280" cellspacing="0">
	<tr>
		<td class="membersRow2" width="200">'.$wordings[$roster_conf['roster_lang']]['totalwins'].'</td>
		<td class="membersRowRight2" width="80">'.$tot_wins.'</td>
	</tr>
	<tr>
		<td class="membersRow1" width="200">'.$wordings[$roster_conf['roster_lang']]['totallosses'].'</td>
		<td class="membersRowRight1" width="80">'.$tot_losses.'</td>
	</tr>
	<tr>
		<td class="membersRow2" width="200">'.$wordings[$roster_conf['roster_lang']]['totaloverall'].'</td>
		<td class="membersRowRight2" width="80">'.$total.'</td>
	</tr>
	<tr>
		<td class="membersRow1" width="200">'.$wordings[$roster_conf['roster_lang']]['win_average'].'</td>
		<td class="membersRowRight1" width="80">'.$ave_win_level_diff.'</td>
	</tr>
	<tr>
		<td class="membersRow2" width="200">'.$wordings[$roster_conf['roster_lang']]['loss_average'].'</td>
		<td class="membersRowRight2" width="80">'.$ave_loss_level_diff.'</td>
	</tr>
</table>
'.border('sgray','end');

	return $returnstring;
}

// Let's limit how many DB calls we do
// Callback functions for:
//   o Comparing BG Win/Loss Ratios
//   o Calculating most killed/most killed by
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
	global $wowdb, $roster_conf, $timeformat, $wordings, $char;

	$bg_array = array(
					'alterac_valley',
					'arathi_basin',
					'warsong_gulch',
				);

	$query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_conf['roster_lang']]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` >= '1'";

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$pvps = array();
	while( $data = $wowdb->fetch_assoc( $result ) )
	{
		$pvp = new pvp3( $data );
		$pvps[] = $pvp;
	}


	$returnstring = "<br /><br />\n";


	foreach ($bg_array as $bgname)
	{
		$wins=0;
		$loss=0;
		$pwin = $gwin = $ploss = $gloss = $subs = array();
		foreach ($pvps as $row)
		{
			if( $row->data['zone'] == $wordings[$roster_conf['roster_lang']][$bgname] )
			{
				// Set some defaults
				$eguild = $row->data['guild'];
				$esub = $row->data['subzone'];
				$ename = $row->data['name'];

				if (empty($eguild) || !isset($eguild) || $eguild == '')
					$eguild = 'Unguilded';
				if (empty($esub) || !isset($esub) || $esub == '')
					$esub = 'None';

				if ($row->data['win'] == '1')
				{
					$wins++;
					$win_level_diff = $win_level_diff + $row->data['leveldiff'];
					$subs[$esub]['Wins'] += 1;
					$subs[$esub]['WinLoss'] = $subs[$esub]['WinLoss'] + 1;
					$subs[$esub]['Zone'] = $esub;
					$gwin[$eguild]['killed'] += 1;
					$gwin[$eguild]['name'] = $eguild;
					$pwin[$ename]['killed'] += 1;
					$pwin[$ename]['name'] = $ename;
				}
				else
				{
					$loss++;
					$loss_level_diff = $loss_level_diff + $row->data['leveldiff'];
					$subs[$esub]['Loss'] += 1;
					$subs[$esub]['WinLoss'] = $subs[$esub]['WinLoss'] - 1;
					$subs[$esub]['Zone'] = $esub;
					$gloss[$eguild]['killed'] += 1;
					$gloss[$eguild]['name'] = $ename;
					$ploss[$ename]['killed'] += 1;
					$ploss[$ename]['name'] = $ename;
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
			$winpercent = round( ($wins / ($wins + $loss)), 2 ) * 100;
		else
			$winpercent = 0;

		usort($subs, 'calc_winloss');
		usort($gwin, 'calc_gwinloss');
		usort($pwin, 'calc_pwinloss');
		usort($gloss, 'calc_gwinloss');
		usort($ploss, 'calc_pwinloss');
		$best = $subs[0]['Zone'];
		$worst = $subs[sizeof($subs)-1]['Zone'];
		$bestNum = $subs[0]['WinLoss'];
		$worstNum = $subs[sizeof($subs)-1]['WinLoss'];

		$kills = $pwin[0]['killed'];
		$killed = $pwin[0]['name'];
		$deaths = $ploss[0]['killed'];
		$killedBy = $ploss[0]['name'];

		$gkills = $gwin[0]['killed'];
		$gkilled = $gwin[0]['name'];
		$gdeaths = $gloss[0]['killed'];
		$gkilledBy = $gloss[0]['name'];

		$returnstring .= '
<div id="'.$bgname.'Col" style="display:inline">
'.border('sorange','start','<div style="cursor:pointer;width:400px;" onclick="swapShow(\''.$bgname.'Col\',\''.$bgname.'\')"><img src="'.$roster_conf['img_url'].'plus.gif" style="float:right;" />'.$wordings[$roster_conf['roster_lang']][$bgname].'</div>').
border('sorange','end').
'</div>
<div id="'.$bgname.'" style="display:none">
'.border('sorange','start','<div style="cursor:pointer;width:400px;" onclick="swapShow(\''.$bgname.'Col\',\''.$bgname.'\')"><img src="'.$roster_conf['img_url'].'minus.gif" style="float:right;" />'.$wordings[$roster_conf['roster_lang']][$bgname].'</div>').
"			<table width='100%' cellpadding='0' cellspacing='0' class='bodyline'>
				<tr>
					<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['wins']."</td>
					<td class='membersRowRight2' style='white-space:normal;'>".$wins."</td>
				</tr>
				<tr>
					<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['losses']."</td>
					<td class='membersRowRight1' style='white-space:normal;'>".$loss."</td>
				</tr>
				<tr>
					<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['overall']."</td>
					<td class='membersRowRight2' style='white-space:normal;'>".$total." (".$winpercent."%)</td>
				</tr>
				<tr>
					<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['win_average']."</td>
					<td class='membersRowRight1' style='white-space:normal;'>".$win_level_diff."</td>
				</tr>
				<tr>
					<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['loss_average']."</td>
					<td class='membersRowRight2' style='white-space:normal;'>".$loss_level_diff."</td>
				</tr>
				<tr>
					<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['bestsub']."</td>
					<td class='membersRowRight1' style='white-space:normal;'>".$best." (".$bestNum.")</td>
				</tr>
				<tr>
					<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['worstsub']."</td>
					<td class='membersRowRight2' style='white-space:normal;'>".$worst." (".$worstNum.")</td>
				</tr>
				<tr>
					<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['killedmost']."</td>
					<td class='membersRowRight1' style='white-space:normal;'>".$killed." (".$kills.")</td>
				</tr>
				<tr>
					<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['killedmostby']."</td>
					<td class='membersRowRight2' style='white-space:normal;'>".$killedBy." (".$deaths.")</td>
				</tr>
				<tr>
					<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['gkilledmost']."</td>
					<td class='membersRowRight1' style='white-space:normal;'>".$gkilled." (".$gkills.")</td>
				</tr>
				<tr>
					<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['gkilledmostby']."</td>
					<td class='membersRowRight2' style='white-space:normal;'>".$gkilledBy." (".$gdeaths.")</td>
				</tr>
			</table>
".border('sorange','end')."\n</div><br />\n";
	}

	return $returnstring;
}

function output_duellog($member_id)
{
	global $wowdb, $roster_conf, $timeformat;

	$query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_conf['roster_lang']]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '0'";

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$pvps = array();
	while( $data = $wowdb->fetch_assoc( $result ) )
	{
		$pvp = new pvp3( $data );
		$pvps[] = $pvp;
	}

	// Modified by Gaxme 28 May, 2006
	$returnstring = "
<table border='0' cellspacing='10' cellpadding='0' align='center'>
	<tr>
		<td>";

	$wins = $loss = 0;
	foreach ($pvps as $row)
	{
		$ename = $row->data['name'];
		$eguild = $row->data['guild'];
		$ezone = $row->data['zone'];
		$esub = $row->data['subzone'];

		if($row->data['win'] == 1)
		{
			$wins++;
			$win_level_diff = $win_level_diff + $row->data['leveldiff'];

		}
		else
		{
			$loss++;
			$loss_level_diff = $loss_level_diff + $row->data['leveldiff'];
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

	$winpercent = round( ($wins / ($wins + $loss)), 2 ) * 100;

	$returnstring .= border('sorange','start',$wordings[$roster_conf['roster_lang']]['duellog'])."
		<table width='175' cellpadding='0' cellspacing='0' align='center'>
			<tr>
				<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['wins']."</td>
				<td class='membersRowRight2'>".$wins."</td>
			</tr>
			<tr>
				<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['leveldiff']."</td>
				<td class='membersRowRight1'>".$win_level_diff."</td>
			</tr>
			<tr>
				<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['losses']."</td>
				<td class='membersRowRight2'>".$loss."</td>
			</tr>
			<tr>
				<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['leveldiff']."</td>
				<td class='membersRowRight1'>".$loss_level_diff."</td>
			</tr>
			<tr>
				<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['overall']."</td>
				<td class='membersRowRight2'>".$total." (".$winpercent."%)</td>
			</tr>
		</table>".border('sorange','end')."</td>\n";

	$returnstring .= "
	</tr>
</table>";

	return $returnstring;
}

function output_pvplog($member_id)
{
	global $wowdb, $roster_conf, $timeformat, $wordings;

	$query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_conf['roster_lang']]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0'";

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$pvps = array();
	while( $data = $wowdb->fetch_assoc( $result ) )
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
".border('sgreen','start',$wordings[$roster_conf['roster_lang']]['world_pvp'])."
<table width='400' cellpadding='0' cellspacing='0'>
	<tr>
		<th width='10%' class='membersHeader'><div align='center'>".$wordings[$roster_conf['roster_lang']]['win']." %</div></th>
		<th width='45%' class='membersHeader'><div align='center'>".$wordings[$roster_conf['roster_lang']]['best_zone']."</div></th>
		<th width='45%' class='membersHeaderRight'><div align='center'>".$wordings[$roster_conf['roster_lang']]['worst_zone']."</div></th>
	</tr>
	<tr>
		<td class='membersRow1'><div align='center'>".$worldPvPPerc." %</div></td>
		<td class='membersRow1'><div align='center'>";

	$query = "SELECT `zone`, COUNT(`zone`) as countz FROM ".ROSTER_PVP2TABLE." WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY `zone` ORDER BY countz DESC LIMIT 0,1";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$rzone = $wowdb->fetch_array($result);
	if ($rzone)
	{
		$returnstring .= $rzone['zone'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$wowdb->free_result($result);

	$returnstring .= "</div></td>
		<td class='membersRowRight1'><div align='center'>";

	$query = "SELECT `zone`, COUNT(`zone`) AS countz FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY `zone` ORDER BY countz DESC LIMIT 0,1";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$rzone = $wowdb->fetch_array($result);
	if ($rzone)
	{
		$returnstring .= $rzone['zone'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$wowdb->free_result($result);

	$returnstring .= "</div></td>
	</tr>
</table>
".border('sgreen','end')."

<br />

".border('syellow','start',$wordings[$roster_conf['roster_lang']]['versus_guilds'])."
<table width='400' cellpadding='0' cellspacing='0'>
	<tr>
		<th width='50%' class='membersHeader'><div align='center'>".$wordings[$roster_conf['roster_lang']]['most_killed']."</div></th>
		<th width='50%' class='membersHeaderRight'><div align='center'>".$wordings[$roster_conf['roster_lang']]['most_killed_by']."</div></th>
	</tr>
	<tr>
		<td class='membersRow1'><div align='center'>";

	$query = "SELECT guild, COUNT(guild) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY guild ORDER BY countg DESC LIMIT 0,1";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$rguild = $wowdb->fetch_array($result);
	if ($rguild)
	{
		$returnstring .= $rguild['guild'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$wowdb->free_result($result);

	$returnstring .= "</div></td>
		<td class='membersRowRight1'><div align='center'>";

	$query = "SELECT guild, COUNT(guild) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY guild ORDER BY countg DESC LIMIT 0,1";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$rguild = $wowdb->fetch_array($result);
	if ($rguild)
	{
		$returnstring .= $rguild['guild'];
	}
	else
	{
		$returnstring .= "N/A";
	}
	$wowdb->free_result($result);

	$returnstring .= "</div></td>
	</tr>
</table>
".border('syellow','end')."

<br />

".border('sblue','start',$wordings[$roster_conf['roster_lang']]['versus_players']);

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$rloss = $wowdb->fetch_array($result);
	$wowdb->free_result($result);

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countn FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY name ORDER BY countn DESC LIMIT 0,1";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$rwin = $wowdb->fetch_array($result);
	$wowdb->free_result($result);

	// Get Class Icon
	foreach ($roster_conf['multilanguages'] as $language)
	{
		$rwin['icon_name'] = $wordings[$language]['class_iconArray'][$rwin['class']];
		if( strlen($icon_name) > 0 ) break;
	}
	$rwin['icon_name'] = 'Interface/Icons/'.$rwin['icon_name'];
	$rwin['class_icon'] = '<img style="cursor:help;" onmouseover="overlib(\''.$rwin['class'].'\',WRAP);" onmouseout="return nd();" class="membersRowimg" width="16" height="16" src="'.$roster_conf['interface_url'].$rwin['icon_name'].'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';

	// Get Class Icon
	foreach ($roster_conf['multilanguages'] as $language)
	{
		$rloss['icon_name'] = $wordings[$language]['class_iconArray'][$rloss['class']];
		if( strlen($icon_name) > 0 ) break;
	}
	$rloss['icon_name'] = 'Interface/Icons/'.$rloss['icon_name'];
	$rloss['class_icon'] = '<img style="cursor:help;" onmouseover="overlib(\''.$rloss['class'].'\',WRAP);" onmouseout="return nd();" class="membersRowimg" width="16" height="16" src="'.$roster_conf['interface_url'].$rloss['icon_name'].'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';


	$returnstring .= "
<table width='400' cellpadding='0' cellspacing='0'>
	<tr>
		<th width='20%' class='membersHeader'>&nbsp;</th>
		<th width='40%' class='membersHeader'>".$wordings[$roster_conf['roster_lang']]['most_killed']."</th>
		<th width='40%' class='membersHeaderRight'>".$wordings[$roster_conf['roster_lang']]['most_killed_by']."</th>
	</tr>
	<tr>
		<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['name']."</td>
		<td class='membersRow1'>".$rwin['class_icon'].$rwin['name']."</td>
		<td class='membersRowRight1'>".$rloss['class_icon'].$rloss['name']."</td>
	</tr>
	<tr>
		<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['race']."</td>
		<td class='membersRow2'>".$rwin['race']."</td>
		<td class='membersRowRight2'>".$rloss['race']."</td>
	</tr>
	<tr>
		<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['kills']."</td>
		<td class='membersRow1'>".$rwin['countn']."</td>
		<td class='membersRowRight1'>".$rloss['countn']."</td>
	</tr>
	<tr>
		<td class='membersRow2'>".$wordings[$roster_conf['roster_lang']]['guild']."</td>
		<td class='membersRow2'>".$rwin['guild']."</td>
		<td class='membersRowRight2'>".$rloss['guild']."</td>
	</tr>
	<tr>
		<td class='membersRow1'>".$wordings[$roster_conf['roster_lang']]['leveldiff']."</td>
		<td class='membersRow1'>".$rwin['leveldiff']."</td>
		<td class='membersRowRight1'>".$rloss['leveldiff']."</td>
	</tr>
</table>
".border('sblue','end');
	return $returnstring;
}

function output_pvp2($pvps,$url,$type)
{
	global $wordings, $roster_conf;

	$returnstring = '
<table class="bodyline" cellspacing="0">
	<tr>
		<th class="membersHeader">'.$url.'&amp;s=date">'.$wordings[$roster_conf['roster_lang']]['when'].'</a></th>
		<th class="membersHeader">'.$url.'&amp;s=class">'.$wordings[$roster_conf['roster_lang']]['class'].'</a> /
			'.$url.'&amp;s=name">'.$wordings[$roster_conf['roster_lang']]['name'].'</a></th>
		<th class="membersHeader">'.$url.'&amp;s=race">'.$wordings[$roster_conf['roster_lang']]['race'].'</a></th>
		<th class="membersHeader">'.$url.'&amp;s=rank">'.$wordings[$roster_conf['roster_lang']]['rank'].'</a></th>
		<th class="membersHeader">'.$url.'&amp;s=guild">'.$wordings[$roster_conf['roster_lang']]['guild'].'</a></th>
		<th class="membersHeader">'.$url.'&amp;s=leveldiff">'.$wordings[$roster_conf['roster_lang']]['leveldiff'].'</a></th>
		<th class="membersHeader">'.$url.'&amp;s=win">'.$wordings[$roster_conf['roster_lang']]['win'].'</a></th>';
	if( $type != 'Duel' )
	{
		$returnstring .= '
		<th class="membersHeader">'.$url.'&amp;s=honor">'.$wordings[$roster_conf['roster_lang']]['honor'].'</a></th>';
	}
	$returnstring .= '
		<th class="membersHeaderRight">'.$url.'&amp;s=zone">'.$wordings[$roster_conf['roster_lang']]['zone2'].'</a></th>
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
			$result = '<img class="membersRowimg" src="img/pvp-win.gif" alt="'.$wordings[$roster_conf['roster_lang']]['win'].'" />';
		}
		elseif($row->data['win'] == '0')
		{
			$result = '<img class="membersRowimg" src="img/pvp-loss.gif" alt="'.$wordings[$roster_conf['roster_lang']]['loss'].'" />';
		}

		if ($row->data['bg'] > 0)
		{
			$bg = $wordings[$roster_conf['roster_lang']]['yes'];
		}
		else
		{
			$bg = $wordings[$roster_conf['roster_lang']]['no'];
		}

		// Get Class Icon
		foreach ($roster_conf['multilanguages'] as $language)
		{
			$icon_name = $wordings[$language]['class_iconArray'][$row->data['class']];
			if( strlen($icon_name) > 0 ) break;
		}
		$icon_name = 'Interface/Icons/'.$icon_name;
		$class_icon = '<img style="cursor:help;" onmouseover="overlib(\''.$row->data['class'].'\',WRAP);" onmouseout="return nd();" class="membersRowimg" width="16" height="16" src="'.$roster_conf['interface_url'].$icon_name.'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';



		$row_st = (($rc%2)+1);
		$returnstring .= '
	<tr>
		<td class="membersRow'.$row_st.'">'.$row->data['date2'].'</td>
		<td class="membersRow'.$row_st.'">'.$class_icon.$row->data['name'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['race'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['rank'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['guild'].'</td>
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
		<td class="membersRowRight'.$row_st.'">';

		if ($row->data['subzone'] != '')
		{
			$returnstring .= '<div class="yellow" style="cursor:help;" onmouseover="overlib(\''.addslashes($row->data['subzone']).'\',CAPTION,\''.$wordings[$roster_conf['roster_lang']]['subzone'].'\',WRAP);" onmouseout="return nd();">'.$row->data['zone'].'</div></td>';
		}
		else
		{
			$returnstring .= $row->data['zone'].'</td>';
		}

		$returnstring .= "\n\t</tr>";
		$rc++;
	}
	$returnstring .= "\n</table>\n";
	return $returnstring;
}
?>