<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

if( $user->data['level'] == UA_ID_ANON )
{
	ua_die($user->lang['access_denied']);
}

main();








/**
 * Stats Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $user, $uniadmin, $tpl;

	if( isset($_REQUEST['limit']) || isset($_REQUEST['start']) )
	{
		$limit = $_REQUEST['limit'];
		$start = $_REQUEST['start'];
	}
	else
	{
		$limit = '10';
		$start = '0';
	}

	$sql = "SELECT count(`id`) rows FROM `".UA_TABLE_STATS."`;";
	$result = $db->query($sql);

	$total_rows = $db->fetch_record($result);
	$total_rows = $total_rows[0];

	$db->free_result($result);

	$tpl->assign_vars(array(
		'L_STATS'      => $user->lang['title_stats'],
		'L_ROW'        => $user->lang['row'],
		'L_ACTION'     => $user->lang['action'],
		'L_IP_ADDRESS' => $user->lang['ip_address'],
		'L_TIME'       => $user->lang['date_time'],
		'L_USER_AGENT' => $user->lang['user_agent'],
		'L_HOST_NAME'  => $user->lang['host_name'],
		'L_SHOW'       => $user->lang['show'],
		'L_STAT_LIMIT' => $user->lang['stats_limit'],
		'L_PREV_PAGE'  => $user->lang['previous_page'],
		'L_NEXT_PAGE'  => $user->lang['next_page'],

		'S_STATS'      => false
		)
	);

	if( $total_rows > 0 )
	{
		$tpl->assign_var('S_STATS',true);

		$pie_chart_array = array('host_name','ip_addr','user_agent','action','time');

		foreach( $pie_chart_array as $name )
		{
			$tpl->assign_block_vars('pie_charts', array(
				'LINK' => build_pie($name),
				'ALT'  => $name,
				)
			);
		}

		$sql = "SELECT * FROM `".UA_TABLE_STATS."` ORDER BY `id` DESC LIMIT $start , $limit;";
		$result = $db->query($sql);


		while( $row = $db->fetch_record($result) )
		{
			foreach( $row as $key => $value )
			{
				$row[$key] = stripslashes($value);
			}

			$time = date($user->lang['time_format'],$row['time']);

			$user_agent = $uniadmin->string_chop($row['user_agent'],45,'...');
			$host_name = $uniadmin->string_chop($row['host_name'],25,'...');

			$tpl->assign_block_vars('stats_row', array(
				'ROW_CLASS'      => $uniadmin->switch_row_class(),
				'ID'             => $row['id'],
				'ACTION'         => $row['action'],
				'IP_ADDR'        => $row['ip_addr'],
				'TIME'           => $time,
				'USER_AGENT'     => $user_agent,
				'USER_AGENT_T'   => $row['user_agent'],
				'HOST_NAME'      => $host_name,
				'HOST_NAME_T'    => $row['host_name'],
				)
			);
		}

		$db->free_result($result);

		$prev_start = $start - $limit;
		$prev_link = '';
		$s_prev_link = false;
		if( $prev_start > -1 )
		{
			$prev_link = UA_FORMACTION.'&amp;start='.$prev_start.'&amp;limit='.$limit;
			$s_prev_link = true;
		}

		$next_start = $start + $limit;
		$next_link = '';
		$s_next_link = false;
		if( $next_start < $total_rows )
		{
			$next_link = UA_FORMACTION.'&amp;start='.$next_start.'&amp;limit='.$limit;
			$s_next_link = true;
		}

		$sep = '';
		if( !empty($prev_link) && !empty($next_link) )
		{
			$sep = ' | ';
		}

		$total_pages = floor($total_rows / $limit) + 1;
		$page_num =  floor($start / $limit) + 1;

		$tpl->assign_vars(array(
			'S_PREV_LINK'  => $s_prev_link,
			'S_NEXT_LINK'  => $s_next_link,

			'U_START'      => $start,
			'U_LIMIT'      => $limit,

			'U_PREV_LINK'   => $prev_link,
			'U_SEP'         => $sep,
			'U_NEXT_LINK'   => $next_link,

			'U_PAGE_NUM'    => $page_num,
			'U_TOTAL_PAGES' => $total_pages,
			)
		);
	}

	$uniadmin->set_vars(array(
		'page_title'    => $user->lang['title_stats'],
		'template_file' => 'stats.html',
		'display'       => true
		)
	);
}

/**
 * Build a string for pieChart.php image
 *
 * @param string $fieldName
 * @return string
 */
function build_pie( $field_name )
{
	global $db, $uniadmin, $user;

	$sql = "SELECT count(`id`) count, `$field_name` field FROM `".UA_TABLE_STATS."` GROUP BY `$field_name` ORDER BY `count` DESC LIMIT 0,5";
	$result = $db->query($sql);

	while( $row = $db->fetch_record($result) )
	{
		if( $field_name != 'time' )
		{
			$final_array[$row['field']] = $row['count'];
		}
		else
		{
			$final_array[date($user->lang['time_format'],$row['field'])] = $row['count'];
		}
	}

	$db->free_result($result);

	$field_name = urlencode($field_name);

	$pie = "title=$field_name&amp;";
	$i = 0;
	foreach( $final_array as $host_name => $numHits )
	{
		$host_name = urlencode($host_name);
		$numHits = urlencode($numHits);

		$pie .= "slice[$i]=$numHits&amp;itemName[$i]=$host_name&amp;";
		$i++;
	}
	$pie .= "action=drawChart";
	return $pie;
}
