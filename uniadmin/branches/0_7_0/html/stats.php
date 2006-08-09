<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}



if( isset($_REQUEST['op']) )
{
	$op = $_REQUEST['op'];
}
elseif( isset($_POST['op']) )
{
	$op = $_POST['op'];
}
else
{
	$op = '';
}

if( isset($_REQUEST['id']) )
{
	$id = $_REQUEST['id'];
}
elseif( isset($_POST['id']) )
{
	$id = $_POST['id'];
}
else
{
	$id = '';
}



function Main()
{
	EchoPage(
	BuildMainTable().
	"<img src='pieChart.php?".BuildPieHosts("host_name")."' alt='host_name'>\n".
	"<img src='pieChart.php?".BuildPieHosts("ip_addr")."' alt='ip_addr'>\n".
	"<img src='pieChart.php?".BuildPieHosts("user_agent")."' alt='user_agent'>\n".
	"<img src='pieChart.php?".BuildPieHosts("action")."' alt='action'>\n".
	"<img src='pieChart.php?".BuildPieHosts("time")."' alt='time'>\n","Statistics");
}

function BuildPieHosts($fieldName)
{
	global $dblink, $config;

	//title=Kingdom&slice[0]=$miscStats[8]&itemName[0]=Neutral&slice[1]=$miscStats[9]&itemName[1]=Dominion&slice[2]=$miscStats[10]&itemName[2]=Shadow&slice[3]=$miscStats[11]&itemName[3]=Order&action=drawChart

	$sql = "SELECT `$fieldName` FROM `".$config['db_tables_stats']."`";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

	$i=0;
	while ($row = mysql_fetch_assoc($result))
	{
		$array[$i] = $row[$fieldName];
		$i++;
	}
	mysql_free_result($result);

	if( is_array($array) )
	{
		$array = array_unique($array);

		foreach ($array as $HostName)
		{
			$sql = "SELECT `id` FROM `".$config['db_tables_stats']."` WHERE `$fieldName` = '".addslashes($HostName)."'";
			$result = mysql_query($sql,$dblink);
			MySqlCheck($dblink,$sql);
			if ($fieldName != "time")
			{
				$finalArray[$HostName] = mysql_num_rows($result);
			}
			else
			{
				$finalArray[date("M jS y H:i:s",$HostName)] = mysql_num_rows($result);
			}
		}

		//print_r($finalArray);
		mysql_free_result($result);
		asort($finalArray,SORT_NUMERIC);
		reset($finalArray);
		//$finalArray = array_pad($finalArray,5,"blah");

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


function BuildMainTable()
{
	global $dblink, $config;

	if (isset($_REQUEST['orderby']) || isset($_REQUEST['direction']) ||isset($_REQUEST['limit']) || isset($_REQUEST['start']))
	{
		$orderby = $_REQUEST['orderby'];
		$direction = $_REQUEST['direction'];
		$limit = $_REQUEST['limit'];
		$start = $_REQUEST['start'];
	}
	else
	{
		$orderby = "time";
		$direction = "DESC";
		$limit = "10";
		$start = "0";
	}

	$sql = "SELECT * FROM `".$config['db_tables_stats']."`";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	$totalRows = mysql_num_rows($result);


	$sql = "SELECT * FROM `".$config['db_tables_stats']."` ORDER BY `$orderby` $direction LIMIT $start , $limit";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);


	if ($direction == "ASC")
	{
		$direction = "DESC";
	}
	else
	{
		$direction = "ASC";
	}

	$table = "<table class='uuTABLE stats' id='table_results' border='0' cellpadding='2' cellspacing='1'>
	<tr>
		<th class='tableHeader' colspan='6'>Statistics</th>
	</tr>
	<tr>
		<td class='dataHeader'><a href='stats.php?start=$start&amp;orderby=id&amp;limit=$limit&amp;direction=$direction'>Row</a></td>
		<td class='dataHeader'><a href='stats.php?start=$start&amp;orderby=action&amp;limit=$limit&amp;direction=$direction'>Action</a></td>
		<td class='dataHeader'><a href='stats.php?start=$start&amp;orderby=ip_addr&amp;limit=$limit&amp;direction=$direction'>IP Address</a></td>
		<td class='dataHeader'><a href='stats.php?start=$start&amp;orderby=time&amp;limit=$limit&amp;direction=$direction'>Date/Time</a></td>
		<td class='dataHeader'><a href='stats.php?start=$start&amp;orderby=user_agent&amp;limit=$limit&amp;direction=$direction'>User Agent</a></td>
		<td class='dataHeader'><a href='stats.php?start=$start&amp;orderby=host_name&amp;limit=$limit&amp;direction=$direction'>Host Name</a></td>
	</tr>";

	$i=0;
	while ($row = mysql_fetch_assoc($result))
	{
		$time = date("M jS y H:i",$row['time']);
		if($i % 2)
		{
			$tdClass = 'data2';
		}
		else
		{
			$tdClass = 'data1';
		}

		$userAgent = stringChop($row['user_agent'],45,"...");
		$hostName = stringChop($row['host_name'],25,"...");

		$table .= "	<tr>
		<td class='$tdClass' align='right'>".$row['id']."</td>
		<td class='$tdClass'>".$row['action']."</td>
		<td class='$tdClass'>".$row['ip_addr']."</td>
		<td class='$tdClass'>$time</td>
		<td class='$tdClass' title='".$row['user_agent']."'>$userAgent</td>
		<td class='$tdClass' title='".$row['host_name']."'>$hostName</td>
	</tr>";
		$i++;

	}

	if ($direction == "ASC")
	{
		$direction = "DESC";
	}
	else
	{
		$direction = "ASC";
	}

	$PrevStart = $start - $limit;
	if ($PrevStart > -1)
	{
		$PrevLink = "<a href='stats.php?start=$PrevStart&amp;orderby=$orderby&amp;limit=$limit&amp;direction=$direction'><< Previous Page</a>";
	}
	else
	{
		$PrevLink = '';
	}
	$NextStart = $start + $limit;
	if ($NextStart < $totalRows)
	{
		$NextLink = "<a href='stats.php?start=$NextStart&amp;orderby=$orderby&amp;limit=$limit&amp;direction=$direction'>Next Page >></a>";
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

	$table .= "	<tr>
		<td class='statsFooter' colspan='4'>$PrevLink$sep$NextLink &nbsp;&nbsp;&nbsp; Page $pageNum of $totalPages</td>
		<td class='statsFooter' colspan='2'>

		<form style='display:inline;' name='changeparams' method='post' enctype='multipart/form-data' action='".UA_FORMACTION."stats'>
			<input class='submit' type='submit' value='Show'>
			<input class='input' type='textbox' name='limit' value='$limit' size='5' maxlength='5'> row(s) starting from record # <input class='input' type='textbox' name='start' value='$start' size='5' maxlength='5'>
			<input type='hidden' value='$orderby' name='orderby'>
			<input type='hidden' value='$direction' name='direction'>
		</form>

	</td>
	</tr>
</table>";

	return $table;
}

function stringChop($string, $desiredLength, $suffix)
{
	if (strlen($string) > $desiredLength)
	{
		$string = substr($string,0,$desiredLength).$suffix;
		return $string;
	}
	return $string;
}

switch ($op)
{

	default:
	Main();
	break;
}


?>