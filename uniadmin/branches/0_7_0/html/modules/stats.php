<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

main();

$db->close_db();






/**
 * Stats Page Functions
 */


/**
 * Main Display
 */
function main()
{
	global $user, $uniadmin;

	EchoPage(
	buildMainTable().
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.buildPieHosts('host_name').'" alt="host_name" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.buildPieHosts('ip_addr').'" alt="ip_addr" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.buildPieHosts('user_agent').'" alt="user_agent" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.buildPieHosts('action').'" alt="action" />'."\n".
	'<img src="'.$uniadmin->url_path.'images/piechart.php?'.buildPieHosts('time').'" alt="time" />'."\n",$user->lang['title_stats']);
}

/**
 * Build a string for pieChart.php image
 *
 * @param string $fieldName
 * @return string
 */
function buildPieHosts($fieldName)
{
	global $db, $uniadmin, $user;

	$sql = "SELECT `$fieldName` FROM `".UA_TABLE_STATS."`;";
	$result = $db->query($sql);

	$i=0;
	$array = '';
	while ($row = $db->fetch_record($result))
	{
		$array[$i] = $row[$fieldName];
		$i++;
	}
	$db->free_result($result);

	if( is_array($array) )
	{
		$array = array_unique($array);

		foreach ($array as $HostName)
		{
			$sql = "SELECT `id` FROM `".UA_TABLE_STATS."` WHERE `$fieldName` = '".addslashes($HostName)."'";
			$result = $db->query($sql);

			if ($fieldName != "time")
			{
				$finalArray[$HostName] = $db->num_rows($result);
			}
			else
			{
				$finalArray[date($user->lang['time_format'],$HostName)] = $db->num_rows($result);
			}
		}

		$db->free_result($result);

		asort($finalArray,SORT_NUMERIC);
		reset($finalArray);

		foreach ($finalArray as $HostName => $count)
		{
			if (count($finalArray) > 5 )
				unset($finalArray[$HostName]);
			$i++;
		}

		$finalArray = array_reverse($finalArray);

		$fieldName = urlencode($fieldName);

		$pie = "title=$fieldName&amp;";
		$i = 0;
		foreach ($finalArray as $HostName => $numHits)
		{
			$HostName = urlencode($HostName);
			$numHits = urlencode($numHits);

			$pie .= "slice[$i]=$numHits&amp;itemName[$i]=$HostName&amp;";
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
function buildMainTable()
{
	global $db, $uniadmin, $user;

	if (isset($_REQUEST['orderby']) || isset($_REQUEST['direction']) ||isset($_REQUEST['limit']) || isset($_REQUEST['start']))
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

	$totalRows = $db->num_rows($result);


	$sql = "SELECT * FROM `".UA_TABLE_STATS."` ORDER BY `$orderby` $direction LIMIT $start , $limit;";
	$result = $db->query($sql);


	if ($direction == 'ASC')
	{
		$direction = 'DESC';
	}
	else
	{
		$direction = 'ASC';
	}

	$table = '<table class="uuTABLE stats" id="table_results" cellspacing="1" width="90%" align="center">
	<tr>
		<th class="tableHeader" colspan="6">'.$user->lang['title_stats'].'</th>
	</tr>
	<tr>
		<td class="dataHeader"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=id&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['row'].'</a></td>
		<td class="dataHeader"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=action&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['action'].'</a></td>
		<td class="dataHeader"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=ip_addr&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['ip_address'].'</a></td>
		<td class="dataHeader"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=time&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['date_time'].'</a></td>
		<td class="dataHeader"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=user_agent&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['user_agent'].'</a></td>
		<td class="dataHeader"><a href="'.UA_FORMACTION.'&amp;start='.$start.'&amp;orderby=host_name&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['host_name'].'</a></td>
	</tr>';

	while ($row = $db->fetch_record($result))
	{
		$time = date($user->lang['time_format'],$row['time']);

		$tdClass = $uniadmin->switch_row_class();

		$userAgent = stringChop($row['user_agent'],45,'...');
		$hostName = stringChop($row['host_name'],25,'...');

		$table .= '	<tr>
		<td class="'.$tdClass.'" align="right">'.$row['id'].'</td>
		<td class="'.$tdClass.'">'.$row['action'].'</td>
		<td class="'.$tdClass.'">'.$row['ip_addr'].'</td>
		<td class="'.$tdClass.'">'.$time.'</td>
		<td class="'.$tdClass.'" title="'.$row['user_agent'].'">'.$userAgent.'</td>
		<td class="'.$tdClass.'" title="'.$row['host_name'].'">'.$hostName.'</td>
	</tr>';
	}

	if ($direction == 'ASC')
	{
		$direction = 'DESC';
	}
	else
	{
		$direction = 'ASC';
	}

	$PrevStart = $start - $limit;
	if ($PrevStart > -1)
	{
		$PrevLink = '<a href="'.UA_FORMACTION.'&amp;start='.$PrevStart.'&amp;orderby='.$orderby.'&amp;limit='.$limit.'&amp;direction='.$direction.'">&lt;&lt; '.$user->lang['previous_page'].'</a>';
	}
	else
	{
		$PrevLink = '';
	}
	$NextStart = $start + $limit;
	if ($NextStart < $totalRows)
	{
		$NextLink = '<a href="'.UA_FORMACTION.'&amp;start='.$NextStart.'&amp;orderby='.$orderby.'&amp;limit='.$limit.'&amp;direction='.$direction.'">'.$user->lang['next_page'].' &gt;&gt;</a>';
	}
	else
	{
		$NextLink = '';
	}

	if (!empty($PrevLink) && !empty($NextLink))
		$sep = ' | ';
	else
		$sep = '';


	$totalPages = floor($totalRows / $limit) + 1;
	$pageNum =  floor($start / $limit) + 1;

	$table .= '	<tr>
		<td class="statsFooter" colspan="4">'.$PrevLink.$sep.$NextLink.' &nbsp;&nbsp;&nbsp; ['.$pageNum.' / '.$totalPages.']</td>
		<td class="statsFooter" colspan="2">

		<form name="ua_changeparams" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
			<input class="submit" type="submit" value="'.$user->lang['show'].'" />
			<input class="input" type="textbox" name="limit" value="'.$limit.'" size="5" maxlength="5" /> '.$user->lang['stats_limit'].' <input class="input" type="textbox" name="start" value="'.$start.'" size="5" maxlength="5" />
			<input type="hidden" value="'.$orderby.'" name="orderby" />
			<input type="hidden" value="'.$direction.'" name="direction" />
		</form>

	</td>
	</tr>
</table>';

	return $table;
}


?>