<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
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
	global $user, $uniadmin;

	display_page(
	build_main_table().
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.build_pie_hosts('host_name').'" alt="host_name" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.build_pie_hosts('ip_addr').'" alt="ip_addr" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.build_pie_hosts('user_agent').'" alt="user_agent" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.build_pie_hosts('action').'" alt="action" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.build_pie_hosts('time').'" alt="time" />'."\n".
	'<br />'."\n"
	,$user->lang['title_stats']);
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

/**
 * Builds main display stats table
 *
 * @return string
 */
function build_main_table( )
{
	global $db, $uniadmin, $user;

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

	$table = '<table class="ua_table stats" id="table_results" cellspacing="1" width="90%" align="center">
	<tr>
		<th class="table_header" colspan="6">'.$user->lang['title_stats'].'</th>
	</tr>
	<tr>
		<td class="data_header"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=id&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['row'].'</a></td>
		<td class="data_header"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=action&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['action'].'</a></td>
		<td class="data_header"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=ip_addr&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['ip_address'].'</a></td>
		<td class="data_header"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=time&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['date_time'].'</a></td>
		<td class="data_header"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=user_agent&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['user_agent'].'</a></td>
		<td class="data_header"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=host_name&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['host_name'].'</a></td>
	</tr>';

	while( $row = $db->fetch_record($result) )
	{
		$time = date($user->lang['time_format'],$row['time']);

		$td_class = $uniadmin->switch_row_class();

		$user_agent = $uniadmin->string_chop($row['user_agent'],45,'...');
		$host_name = $uniadmin->string_chop($row['host_name'],25,'...');

		$table .= '	<tr>
		<td class="'.$td_class.'" align="right">'.$row['id'].'</td>
		<td class="'.$td_class.'">'.$row['action'].'</td>
		<td class="'.$td_class.'">'.$row['ip_addr'].'</td>
		<td class="'.$td_class.'">'.$time.'</td>
		<td class="'.$td_class.'" title="'.$row['user_agent'].'">'.$user_agent.'</td>
		<td class="'.$td_class.'" title="'.$row['host_name'].'">'.$host_name.'</td>
	</tr>';
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


	$totalPages = floor($total_rows / $limit) + 1;
	$pageNum =  floor($start / $limit) + 1;

	$table .= '	<tr>
		<td class="stats_footer" colspan="4">'.$prev_link.$sep.$next_link.' &nbsp;&nbsp;&nbsp; ['.$pageNum.' / '.$totalPages.']</td>
		<td class="stats_footer" colspan="2">

		<form name="ua_changeparams" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
			<input class="submit" type="submit" value="'.$user->lang['show'].'" />
			<input class="input" type="text" name="limit" value="'.$limit.'" size="5" maxlength="5" /> '.$user->lang['stats_limit'].' <input class="input" type="text" name="start" value="'.$start.'" size="5" maxlength="5" />
			<input type="hidden" value="'.$orderby.'" name="orderby" />
			<input type="hidden" value="'.$direction.'" name="direction" />
		</form>

	</td>
	</tr>
</table>';

	return $table;
}

?>