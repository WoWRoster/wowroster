<?php
/******************************
 * WoWRoster.net  UniAdmin
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

if( $user->data['level'] == UA_ID_ANON )
{
	$uniadmin->message($user->lang['access_denied']);
	$uniadmin->set_vars(array(
	    'template_file' => 'index.html',
	    'display'       => true)
	);
	die();
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

	$pie_chart_array = array('host_name','ip_addr','user_agent','action','time');

	foreach( $pie_chart_array as $name )
	{
		$tpl->assign_block_vars('pie_charts', array(
			'LINK' => build_pie_hosts($name),
			'ALT'  => $name,
			)
		);
	}

	if( isset($_REQUEST['orderby']) || isset($_REQUEST['direction']) || isset($_REQUEST['limit']) || isset($_REQUEST['start']) )
	{
		$orderby = $_REQUEST['orderby'];
		$direction = $_REQUEST['direction'];
		$limit = $_REQUEST['limit'];
		$start = $_REQUEST['start'];
	}
	else
	{
		$orderby = 'time';
		$direction = 'DESC';
		$limit = '10';
		$start = '0';
	}

	$sql = "SELECT * FROM `".UA_TABLE_STATS."`;";
	$result = $db->query($sql);

	$total_rows = $db->num_rows($result);


	$sql = "SELECT * FROM `".UA_TABLE_STATS."` ORDER BY `$orderby` $direction LIMIT $start , $limit;";
	$result = $db->query($sql);


	if( $direction == 'ASC' )
	{
		$direction = 'DESC';
	}
	else
	{
		$direction = 'ASC';
	}

	while( $row = $db->fetch_record($result) )
	{
		$time = date($user->lang['time_format'],$row['time']);

		$td_class = $uniadmin->switch_row_class();

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

	$prev_start = $start - $limit;
	if( $prev_start > -1 )
	{
		$prev_link = '<a href="'.UA_FORMACTION.'&amp;start='.$prev_start.'&amp;orderby='.$orderby.'&amp;limit='.$limit.'&amp;direction='.$direction.'">&lt;&lt; '.$user->lang['previous_page'].'</a>';
	}
	else
	{
		$prev_link = '';
	}
	$next_start = $start + $limit;
	if( $next_start < $total_rows )
	{
		$next_link = '<a href="'.UA_FORMACTION.'&amp;start='.$next_start.'&amp;orderby='.$orderby.'&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['next_page'].' &gt;&gt;</a>';
	}
	else
	{
		$next_link = '';
	}

	if( !empty($prev_link) && !empty($next_link) )
	{
		$sep = ' | ';
	}
	else
	{
		$sep = '';
	}


	$total_pages = floor($total_rows / $limit) + 1;
	$page_num =  floor($start / $limit) + 1;

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

		'U_START'     => $start,
		'U_LIMIT'     => $limit,
		'U_DIRECTION' => $direction,
		'U_ORDERBY'   => $orderby,

		'U_PREV_LINK'    => $prev_link,
		'U_SEP'    => $sep,
		'U_NEXT_LINK'    => $next_link,
		'U_PAGE_NUM'    => $page_num,
		'U_TOTAL_PAGES'    => $total_pages,
		)
	);

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
function build_pie_hosts( $field_name )
{
	global $db, $uniadmin, $user;

	$sql = "SELECT `$field_name` FROM `".UA_TABLE_STATS."`;";
	$result = $db->query($sql);

	$i=0;
	$array = '';
	while( $row = $db->fetch_record($result) )
	{
		$array[$i] = $row[$field_name];
		$i++;
	}
	$db->free_result($result);

	if( is_array($array) )
	{
		$array = array_unique($array);

		foreach( $array as $host_name )
		{
			$sql = "SELECT `id` FROM `".UA_TABLE_STATS."` WHERE `$field_name` = '".$db->escape($host_name)."'";
			$result = $db->query($sql);

			if( $field_name != 'time' )
			{
				$final_array[$host_name] = $db->num_rows($result);
			}
			else
			{
				$final_array[date($user->lang['time_format'],$host_name)] = $db->num_rows($result);
			}
		}

		$db->free_result($result);

		asort($final_array,SORT_NUMERIC);
		reset($final_array);

		foreach( $final_array as $host_name => $count )
		{
			if (count($final_array) > 5 )
			{
				unset($final_array[$host_name]);
			}
			$i++;
		}

		$final_array = array_reverse($final_array);

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
}

?>