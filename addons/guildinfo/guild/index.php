<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays the guild information text
 *
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

$roster->tpl->assign_vars(array(
	'TITLE' => '',
	'NEXT' => '',
	'LEVEL' => $roster->data['guild_level'],
	'INFO' => $guild_info_text,
	'GRAPH' => makePane( 'menu_left' ),
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
	 * Builds either of the side panes.
	 *
	 * @param string kind of (a) pane to build...lol
	 */
	function makePane( $side )
	{
		global $roster;

		switch( $roster->config[$side . '_type'] )
		{
			case 'level':
			case 'class':
				$pane = makeList($roster->config[$side . '_type'], $roster->config[$side . '_level'], $roster->config[$side . '_style'], $side);
				break;

			case 'realm':
				$pane = makeRealmStatus();
				break;

			default:
				$pane = '';
				break;
		}

		return $pane;
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
	 * @param string $side
	 *		side this is appearing on, for the image to get the colors
	 */
	function makeList( $type , $level , $style , $side )
	{
		global $roster, $addon;

		// Figure out the scope and limit accordingly.
		switch( $roster->scope )
		{
			case 'guild':
				// Restrict on the selected guild
				$where = "AND `guild_id` = '" . $roster->data['guild_id'] . "' ";
				break;

			case 'char':
				// Restrict on this char's guild
				$where = "AND `guild_id` = '" . $roster->data['guild_id'] . "' ";
				break;

			default:
				// util/pages uses all entries
				$where = '';
				break;
		}

		// Initialize data array
		$dat = array();
		if( $type == 'level' )
		{
			for( $i=floor(ROSTER_MAXCHARLEVEL/10); $i>=floor($level/10); $i-- )
			{
				if( $i * 10 == ROSTER_MAXCHARLEVEL )
				{
					$dat[$i]['name'] = ROSTER_MAXCHARLEVEL;
				}
				elseif( $i * 10 + 9 >= ROSTER_MAXCHARLEVEL-1 )
				{
					$dat[$i]['name'] = ($i*10) . ' - ' . ROSTER_MAXCHARLEVEL;
				}
				else
				{
					if (($i*10)==0)
					{
						$num = 1;
					}
					else
					{
						$num = ($i*10);
					}
					$dat[$i]['name'] = $num . ' - ' . ($i*10+9);
				}
				$dat[$i]['alt'] = 0;
				$dat[$i]['nonalt'] = 0;
			}

			$qrypart = "FLOOR(`level`/10)";
		}
		elseif( $type == 'class' )
		{
			foreach($roster->locale->act['id_to_class'] as $class_id => $class)
			{
				$dat[$class_id]['name'] = $class;
				$dat[$class_id]['alt'] = 0;
				$dat[$class_id]['nonalt'] = 0;
			}

			$qrypart = "`classid`";
		}
		else
		{
			die_quietly('Invalid list type','Menu Sidepane error',__FILE__,__LINE__);
		}
		$num_alts = $num_non_alts = 0;

		// Build query
		$query  = "SELECT count(`member_id`) AS `amount`, ";

		if( empty( $roster->config['alt_location'] ) || empty( $roster->config['alt_type'] ) )
		{
			$query .= "0 AS isalt, ";
		}
		else
		{
			$query .= "IF(`" . $roster->db->escape($roster->config['alt_location']) . "` LIKE '%" . $roster->db->escape($roster->config['alt_type']) . "%',1,0) AS isalt, ";
		}

		$query .= $qrypart . " AS label "
			. "FROM `" . $roster->db->table('members') . "` "
			. "WHERE `level` >= $level "
			. $where
			. "GROUP BY isalt, label;";

		$result = $roster->db->query($query);

		if( !$result )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}

		// Fetch results
		while( $row = $roster->db->fetch($result) )
		{
			$label = $row['label'];

			if( $row['isalt'] )
			{
				$num_alts += $row['amount'];
				$dat[$label]['alt'] += $row['amount'];
			}
			else
			{
				$num_non_alts += $row['amount'];
				$dat[$label]['nonalt'] += $row['amount'];
			}
		}
		//aprint($dat);die();

		// No entries at all? Then there's no data uploaded, so there's no use
		// rendering the panel.
		if( $num_alts + $num_non_alts == 0 )
		{
			return '';
		}

		$text = sprintf($roster->locale->act['menu_totals'], $num_non_alts, $num_alts) . ($level>0 ? sprintf($roster->locale->act['menu_totals_level'], $level) : '');
		$output = '';

		if( $style == 'bar' )
		{
			$req = 'inc/bargraphnew.php?';
			$i = 0;
			foreach( $dat as $bar )
			{
				$req .= 'barnames[' . $i . ']=' . urlencode($bar['name']) . '&amp;';
				$req .= 'barsizes[' . $i . ']=' . ($bar['alt']+$bar['nonalt']) . '&amp;';
				$req .= 'bar2sizes[' . $i . ']=' . $bar['alt'] . '&amp;';
				$i++;
			}
			$req .= 'type=' . $type . '&amp;side=' . $side;
			$req = str_replace(' ','%20',$req);

			$output .= '<img src="' . $addon['url'] . $req . '" alt="" />';
		}
		elseif( $style == 'barlog' )
		{
			$req = 'inc/bargraphnew.php?';
			$i = 0;
			foreach( $dat as $bar )
			{
				$req .= 'barnames[' . $i . ']=' . urlencode($bar['name']) . '&amp;';
				$req .= 'barsizes[' . $i . ']=' . (($bar['alt']+$bar['nonalt']==0) ? -1 : log($bar['alt']+$bar['nonalt'])) . '&amp;';
				$req .= 'bar2sizes[' . $i . ']=' . (($bar['alt']==0) ? -1 : log($bar['alt'])) . '&amp;';
				$i++;
			}
			$req .= 'type=' . $type . '&amp;side=' . $side;

			$output .= '<img src="' . $addon['url'] . $req . '" alt="" />';
		}
		else
		{
			$output .= "<ul>\n";

			foreach( $dat as $line )
			{
				$output .= '<li>';
				$output .= $line['name'] . ': ' . $line['nonalt'] . ' (+' . $line['alt'] . " Alts)</li>\n";
			}
			$output .= '</ul>';
		}
		$output .= "<br />$text\n";

		return $output;
	}
	
	
	
	
