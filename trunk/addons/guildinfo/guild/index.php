<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays guild information
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['guildinfo'];

if( !$roster->auth->getAuthorized($addon['config']['guildinfo_access']) )
{
	echo $roster->auth->getLoginForm($addon['config']['guildinfo_access']);
	return; //To the addon framework
}

$guild_info_text = empty($roster->data['guild_info_text']) ? '&nbsp;' : $roster->data['guild_info_text'];

$gxp = explode(':',$roster->data['guild_xp']);
$gxpc = explode(':',$roster->data['guild_xpcap']);


$query = "SELECT * FROM `" . $roster->db->table('news',$addon['basename']) . "`"
	. " WHERE `guild_id` = '".$roster->data['guild_id']."'"
	. " ORDER BY `Date` DESC;";
$result = $roster->db->query($query);

$return_string = '';
if( $roster->db->num_rows($result) > 0 )
{
	while( $row = $roster->db->fetch($result, SQL_ASSOC) )
	{
		//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
		$roster->tpl->assign_block_vars('news', array(
			'DATE'    => $row['Display_date'],
			'TEXT'    => sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
			)
		);
	}
}



$querya = "SELECT * FROM `" . $roster->db->table('ranks',$addon['basename']) . "`"
	. " WHERE `guild_id` = '".$roster->data['guild_id']."'"
	. " ORDER BY `TotalXP` DESC LIMIT 10;";
$resulta = $roster->db->query($querya);

$return_string = '';
if( $roster->db->num_rows($resulta) > 0 )
{
	while( $rowa = $roster->db->fetch($resulta, SQL_ASSOC) )
	{
		//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
		$roster->tpl->assign_block_vars('top10', array(
			'NAME'      => $rowa['Member'],
			'TOTALXP'   => $rowa['TotalXP'],
			'TOTALRANK' => $rowa['TotalRank'],
			//'TEXT'      =>sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
			)
		);
	}
}

$queryb = "SELECT * FROM `" . $roster->db->table('ranks',$addon['basename']) . "`"
	. " WHERE `guild_id` = '".$roster->data['guild_id']."'"
	. " ORDER BY `WeeklyXP` DESC LIMIT 10;";
$resultb = $roster->db->query($queryb);

$return_string = '';
if( $roster->db->num_rows($resultb) > 0 )
{
	$wx = 1;
	while( $rowb = $roster->db->fetch($resultb, SQL_ASSOC) )
	{
		//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
		$roster->tpl->assign_block_vars('top10w', array(
			'NAME'      => $rowb['Member'],
			'TOTALXP'   => $rowb['WeeklyXP'],
			'TOTALRANK' => $wx,
			//'TEXT'      => sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
			)
		);
		$wx++;
	}
}


_renderxpBar($gxp[0], $gxp[1], 'normal');
_renderxpBar($gxpc[0], $gxpc[1], 'rested');

// Make the bar graphs
$graph = '';
if( $addon['config']['graph_level_display'] == 1 )
{
	$graph .= makeGraph('level', $addon['config']['graph_level_level'], $addon['config']['graph_level_style']);
}
if( $addon['config']['graph_class_display'] == 1 )
{
	$graph .= makeGraph('class', $addon['config']['graph_class_level'], $addon['config']['graph_class_style']);
}
if( $addon['config']['graph_rank_display'] == 1 )
{
	$graph .= makeGraph('rank', $addon['config']['graph_rank_level'], $addon['config']['graph_rank_style']);
}

$roster->tpl->assign_vars(array(
	'L_MEMBER_ACHIEVEMENTS' => $roster->locale->act['NEWS_FILTER']['2'],
	'TITLE' => '',
	'NEXT' => '',
	'LEVEL' => $roster->data['guild_level'],
	'INFO' => $guild_info_text,
	'GRAPH' => $graph
	)
);



$select_tab = (isset($_GET['t']) ? $_GET['t'] : 'profile');

$roster->tpl->assign_block_vars('gtabs', array(
	'NAME'     => $roster->locale->act['guildinfo'],
	'BLOCKID'  => 'profile',
	'SELECTED' => $select_tab == 'profile' ? true : false
	)
);

foreach ($roster->locale->act['NEWS_FILTER'] as $id => $name)
{
	//echo $id.' - '.$name.' --<br>';

	$roster->tpl->assign_block_vars('gtabs', array(
		'NAME'     => $name,
		'BLOCKID'  => 'ginfo' . $id,
		'SELECTED' => $select_tab == 'profile' ? true : false
		)
	);

	$query = "SELECT * FROM `" . $roster->db->table('news',$addon['basename']) . "`"
		. " WHERE `guild_id` = '".$roster->data['guild_id']."'"
			. " AND `Typpe` = '".($id-1)."'"
		. " ORDER BY `Date` DESC;";
	$result = $roster->db->query($query);

	$return_string = '';
	if( $roster->db->num_rows($result) > 0 )
	{
		while( $row = $roster->db->fetch($result, SQL_ASSOC) )
		{
			//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
			$roster->tpl->assign_block_vars('gtabs.news', array(
				'DATE'    => $row['Display_date'],
				'TEXT'    => sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
				)
			);
		}
	}
}


$roster->tpl->set_handle('body', $addon['basename'] . '/info.html');
$roster->tpl->display('body');




function _renderxpBar($step, $total, $txt)
{
	global $roster;

	$perc = 0;
	if ( $total == 0 )
	{
		$perc = 100;
	}
	else
	{
		$perc = round($step / $total * 100);
	}
	$per_left = 100 - $perc;

	$roster->tpl->assign_block_vars('p_bar', array(
		'TEXT'    => $txt,
		'PERCENT' => $perc,
		'STEP'    => $step,
		'TOTAL'   => $total,
		)
	);
}


/**
 * Make level/class distribution list
 *
 * @param string $type
 *		'level' for level list
 *		'class' for class list
 * @param int $level
 *		minimum level to display
 * @param string $style
 *		'list' for text list
 *		'bar' for bargraph
 *		'barlog' for logarithmic bargraph
 */
function makeGraph( $type , $level , $style )
{
	global $roster, $addon;

	// Initialize data array
	$dat = array();
	$num_alts = $num_non_alts = 0;
	$queryxxx = "IF(`" . $roster->db->escape($roster->config['alt_location']) . "` LIKE '%" . $roster->db->escape($roster->config['alt_type']) . "%',1,0) AS isalt, ";
	$wherexx = "Where `guild_id` = '" . $roster->data['guild_id'] . "' and `level` >= ".$addon['config']['graph_level_level']."";
	$queryxx = "SELECT ".$queryxxx." `level`, `classid`, `guild_title` FROM `" . $roster->db->table('members') . "`"
	. $wherexx . " Order by `level` ASC";
	$resultxx = $roster->db->query($queryxx);
		
	
	if( $type == 'level' )
	{

		
		$f = array();
		$d=0;
		$i=0;
		for( $i=0;$i<=9;$i++)
		{
			$f[$i]=array();
			$f[$i]['name'] = '';
			$f[$i]['alt'] = 0;
			$f[$i]['nonalt'] = 0;
		}
		
		while ($rowx = $roster->db->fetch($resultxx))
		{				
				if( $rowx['level'] <=9 )
				{
					$f['0']['name'] = '1 - 9';
					if ($rowx['isalt']==1){$f['0']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['0']['nonalt']++;
				}
				elseif( $rowx['level'] >=10 && $rowx['level'] <=19 )
				{
					$f['1']['name'] = '10 - 19';
					if ($rowx['isalt']==1)	{$f['1']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['1']['nonalt']++;
				}
				elseif( $rowx['level'] >=20 && $rowx['level'] <=29 )
				{
					$f['2']['name'] = '20 - 29';
					if ($rowx['isalt']==1){$f['2']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['2']['nonalt']++;
				}
				elseif( $rowx['level'] >=30 && $rowx['level'] <=39 )
				{
					$f['3']['name'] = '30 - 39';
					if ($rowx['isalt']==1){	$f['3']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['3']['nonalt']++;
				}
				elseif( $rowx['level'] >=40 && $rowx['level'] <=49 )
				{
					$f['4']['name'] = '40 - 49';
					if ($rowx['isalt']==1){$f['4']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['4']['nonalt']++;
				}
				elseif( $rowx['level'] >=50 && $rowx['level'] <=59 )
				{
					$f['5']['name'] = '50 - 59';
					if ($rowx['isalt']==1){$f['5']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['5']['nonalt']++;
				}
				elseif( $rowx['level'] >=60 && $rowx['level'] <=69 )
				{
					$f['6']['name'] = '60 - 69';
					if ($rowx['isalt']==1){$f['6']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['6']['nonalt']++;
				}
				elseif( $rowx['level'] >=70 && $rowx['level'] <=79 )
				{
					$f['7']['name'] = '70 - 79';
					if ($rowx['isalt']==1){$f['7']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['7']['nonalt']++;
				}
				elseif( $rowx['level'] >=80 && $rowx['level'] <=84 )
				{
					$f['8']['name'] = '80 - 84';
					if ($rowx['isalt']==1){	$f['8']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['8']['nonalt']++;
				}
				elseif( $rowx['level'] == ROSTER_MAXCHARLEVEL )
				{
					$f['9']['name'] = ROSTER_MAXCHARLEVEL;
					if ($rowx['isalt']==1){$f['9']['alt']++;$num_alts++;}
					else{$num_non_alts++;}
					$f['9']['nonalt']++;
				}
			}

		$dat = $f;

	}
	elseif( $type == 'class' )
	{

		$resultc = $resultxx;
		foreach($roster->locale->act['id_to_class'] as $class_id => $class)
			{
				$dat[$class_id]['name'] = $class;
				$dat[$class_id]['alt'] = 0;
				$dat[$class_id]['nonalt'] = 0;
			}
			
		while ($rowc = $roster->db->fetch($resultc))
		{
			if ($rowc['isalt']==1)
			{
				$dat[$rowc['classid']]['alt']++;
				$num_alts++;
			}
			else
			{
				$num_non_alts++;
			}
			$dat[$rowc['classid']]['nonalt']++;	
		}
	}
	elseif( $type == 'rank' )
	{

		$resultd = $resultxx;
		$resultx = $resultxx;
		$dat = array();
		/*
		foreach($roster->locale->act['id_to_class'] as $class_id => $class)
			{
				$dat[$class_id]['name'] = $class;
				$dat[$class_id]['alt'] = 0;
				$dat[$class_id]['nonalt'] = 0;
			}
		*/
		//$dat[$rowd['guild_title']]['name']=
		while ($rowd = $roster->db->fetch($resultd))
		{
			if (!isset($dat[$rowd['guild_title']]))
			{
				$dat[$rowd['guild_title']] = array();
				$dat[$rowd['guild_title']]['name']=$rowd['guild_title'];
				$dat[$rowd['guild_title']]['alt'] = 0;
				$dat[$rowd['guild_title']]['nonalt'] = 0;
			}
			//$dat[$rowd['guild_title']]['name']=$rowd['guild_title'];
			if ($rowd['isalt']==1)
			{
				$dat[$rowd['guild_title']]['alt']++;
				$num_alts++;
			}
			else
			{
				$num_non_alts++;
			}
			$dat[$rowd['guild_title']]['nonalt']++;	
		}
	}
	else
	{
		$roster->set_message('Invalid list type', 'GuildInfo graph error', 'error');
		return;
	}

	// No entries at all? Then there's no data uploaded, so there's no use
	// rendering the panel.
	if( $num_alts + $num_non_alts == 0 )
	{
		return 'bummer';
	}

	$text = sprintf($roster->locale->act['menu_totals'], $num_non_alts, $num_alts) . ($level>0 ? sprintf($roster->locale->act['menu_totals_level'], $level) : '');
	$output = '';

	if( $style == 'bar' )
	{
		$req = array(
			'type'   => $type,
			'text'   => array(
				'font'    => $addon['config']['graph_' . $type . '_font'],
				'size'    => $addon['config']['graph_' . $type . '_font_size'],
				'color'   => $addon['config']['graph_' . $type . '_font_color'],
				'outline' => $addon['config']['graph_' . $type . '_outline']
			),
			'footer' => array(
				'text'    => str_replace('+', '%2B', $text),
				'font'    => $addon['config']['graph_' . $type . '_foot_font'],
				'size'    => $addon['config']['graph_' . $type . '_foot_size'],
				'color'   => $addon['config']['graph_' . $type . '_foot_color'],
				'outline' => $addon['config']['graph_' . $type . '_foot_outline']
			),
			'bar' => array(
				'color' => $addon['config']['graph_' . $type . '_bar_color'],
				'names' => array(),
				'sizes'  => array()
			),
			'bar2' => array(
				'color' => $addon['config']['graph_' . $type . '_bar2_color'],
				'sizes'  => array()
			)
		);

		$i = 0;
		foreach( $dat as $bar )
		{
			$req['bar']['names'][$i] = urlencode($bar['name']);
			$req['bar']['sizes'][$i] = $bar['nonalt'];
			$req['bar2']['sizes'][$i] = $bar['alt'];
			$i++;
		}

		$req = 'bargraphnew.php?data=' . urlencode(json_encode($req));

		$output .= '<img class="info-graph" src="' . $addon['url_path'] . $req . '" alt="" />';
	}
	elseif( $style == 'barlog' )
	{
		$req = array(
			'type'   => $type,
			'text'   => array(
				'font'    => $addon['config']['graph_' . $type . '_font'],
				'size'    => $addon['config']['graph_' . $type . '_font_size'],
				'color'   => $addon['config']['graph_' . $type . '_font_color'],
				'outline' => $addon['config']['graph_' . $type . '_outline']
			),
			'footer' => array(
				'text'    => str_replace('+', '%2B', $text),
				'font'    => $addon['config']['graph_' . $type . '_foot_font'],
				'size'    => $addon['config']['graph_' . $type . '_foot_size'],
				'color'   => $addon['config']['graph_' . $type . '_foot_color'],
				'outline' => $addon['config']['graph_' . $type . '_foot_outline']
			),
			'bar' => array(
				'color' => $addon['config']['graph_' . $type . '_bar_color'],
				'names' => array(),
				'sizes'  => array()
			),
			'bar2' => array(
				'color' => $addon['config']['graph_' . $type . '_bar2_color'],
				'sizes'  => array()
			)
		);

		$i = 0;
		foreach( $dat as $bar )
		{
			$req['bar']['names'][$i] = urlencode($bar['name']);
			$req['bar']['sizes'][$i] = (($bar['nonalt'] == 0) ? -1 : log($bar['nonalt']));
			$req['bar2']['sizes'][$i] = (($bar['alt'] == 0) ? -1 : log($bar['alt']));
			$i++;
		}

		$req = 'bargraphnew.php?data=' . urlencode(json_encode($req));
		$output .= '<img class="info-graph" src="' . $addon['url_path'] . $req . '" alt="" />';
	}
	else
	{
		$output .= '<ul class="' . $type . '-list">' . "\n";

		foreach( $dat as $line )
		{
			$output .= '<li>';
			$output .= $line['name'] . ': ' . $line['nonalt'] . ' (+' . $line['alt'] . " Alts)</li>\n";
		}
		$output .= '</ul>';
		$output .= '<div class="' . $type . '-footer">' . $text . '</div>';
	}

	return $output;
}




