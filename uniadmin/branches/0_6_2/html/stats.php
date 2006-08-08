<?php

include("config.php");
if (!isset ($_POST['op'])) {
	$op = $op;
} else {
	$op = $_POST['op'];
}

if (!isset ($_POST['id'])) {
	$id = $id;
} else {
	$id = $_POST['id'];
}

if (!isset ($_POST)) {
	$post = $post;
} else {
	$post = $_POST;
}

if (!isset ($_REQUEST)) {
	$request = $request;
} else {
	$request = $_REQUEST;
}


function Main(){
	EchoPage("<br><br>".
	BuildMainTable().
	"<center><img src='pieChart.php?".BuildPieHosts("host_name")."'>".
	"<img src='pieChart.php?".BuildPieHosts("ip_addr")."'>".
	"<img src='pieChart.php?".BuildPieHosts("user_agent")."'>".
	"<img src='pieChart.php?".BuildPieHosts("action")."'>".
	"<img src='pieChart.php?".BuildPieHosts("time")."'></center>","Statistics");
}

function BuildPieHosts($fieldName){
	global $dblink, $config;
	//title=Kingdom&slice[0]=$miscStats[8]&itemName[0]=Neutral&slice[1]=$miscStats[9]&itemName[1]=Dominion&slice[2]=$miscStats[10]&itemName[2]=Shadow&slice[3]=$miscStats[11]&itemName[3]=Order&action=drawChart

	$sql = "SELECT `$fieldName` FROM `".$config['db_tables_stats']."`";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

	while ($row = mysql_fetch_assoc($result)) {
		$array[$i] = $row[$fieldName];
		$i++;
	}
	mysql_free_result($result);
	$array = array_unique($array);

	foreach ($array as $HostName){
		$sql = "SELECT `id` FROM `".$config['db_tables_stats']."` WHERE `$fieldName` = '".addslashes($HostName)."'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		if ($fieldName != "time"){
			$finalArray[$HostName] = mysql_num_rows($result);
		}else{
			$finalArray[date("M jS y H:i:s",$HostName)] = mysql_num_rows($result);
		}
	}
	
	//print_r($finalArray);
	mysql_free_result($result);
	asort($finalArray,SORT_NUMERIC);
	reset($finalArray);
	//$finalArray = array_pad($finalArray,5,"blah");

	foreach ($finalArray as $HostName => $count){
		if (count($finalArray) > 5 )unset($finalArray[$HostName]);
		$i++;
	}
	$finalArray = array_reverse($finalArray);

	$pie = "title=$fieldName&";
	$i = 0;
	foreach ($finalArray as $HostName => $numHits){
		$pie .= "slice[$i]=$numHits&itemName[$i]=$HostName&";
		$i++;
	}
	$pie .= "action=drawChart";
	return $pie;

}


function BuildMainTable(){
	global $dblink, $config, $request;


	if (isset($request['orderby']) || isset($request['direction']) ||isset($request['limit']) ||isset($request['start'])){
		$orderby = $request['orderby'];
		$direction = $request['direction'];
		$limit = $request['limit'];
		$start = $request['start'];
	}else{
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




	if ($direction == "ASC"){
		$direction = "DESC";
	}else {
		$direction = "ASC";
	}

	$table = "<table class='uuTABLE' class=\"stats\" id=\"table_results\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">
		<th><div class=\"nowrap\"><a href='stats.php?start=$start&orderby=id&limit=$limit&direction=$direction'>Row</a></div></th>
		<th><div class=\"nowrap\"><a href='stats.php?start=$start&orderby=action&limit=$limit&direction=$direction'>Action</a></div></th>
		<th><div class=\"nowrap\"><a href='stats.php?start=$start&orderby=ip_addr&limit=$limit&direction=$direction'>IP Address</a></div></th>
		<th><div class=\"nowrap\"><a href='stats.php?start=$start&orderby=time&limit=$limit&direction=$direction'>Date/Time</a></div></th>
		<th><div class=\"nowrap\"><a href='stats.php?start=$start&orderby=user_agent&limit=$limit&direction=$direction'>User Agent</a></div></th>
		<th><div class=\"nowrap\"><a href='stats.php?start=$start&orderby=host_name&limit=$limit&direction=$direction'>Host Name</a></div></th>
	</tr>";

	while ($row = mysql_fetch_assoc($result)) {
		$time = date("M jS y H:i",$row['time']);
		if($i % 2){
			$tdClass = 'data2';
		}else{
			$tdClass = 'data1';
		}

		$userAgent = stringChop($row['user_agent'],25,"...");
		$hostName = stringChop($row['host_name'],35,"...");

		$table .= "<tr>
			<td class=\"$tdClass\" align=\"right\" valign=\"top\"><center>".$row['id']."</center></td>
			<td class=\"$tdClass\"  valign=\"top\">".$row['action']."</td>
			<td class=\"$tdClass\"  valign=\"top\"><center>".$row['ip_addr']."</center></td>
			<td class=\"$tdClass\"  valign=\"top\">$time</td>
			<td class=\"$tdClass\"  valign=\"top\" title='".$row['user_agent']."'>$userAgent</td>
			<td class=\"$tdClass\"  valign=\"top\" title='".$row['host_name']."'>$hostName</td>
			</tr>";
		$i++;

	}

	if ($direction == "ASC"){
		$direction = "DESC";
	}else {
		$direction = "ASC";
	}

	$PrevStart = $start - $limit;
	if ($PrevStart > -1){
		$PrevLink = "<a href='stats.php?start=$PrevStart&orderby=$orderby&limit=$limit&direction=$direction'><< Previous Page</a>";
	}
	$NextStart = $start + $limit;
	if ($NextStart < $totalRows){
		$NextLink = "<a href='stats.php?start=$NextStart&orderby=$orderby&limit=$limit&direction=$direction'>Next Page >></a>";
	}

	if (isset($PrevLink) && isset($NextLink))$sep = " | ";
	
	
	$totalPages = floor($totalRows / $limit) + 1;
	$pageNum =  floor($start / $limit) + 1;

	$table .= "<tr><td class='statsFooter' colspan=4>$PrevLink$sep$NextLink &nbsp&nbsp&nbsp Page $pageNum of $totalPages</td>
				<td class='statsFooter' colspan=2>
	
	<form name='changeparams' method='post' ENCTYPE='multipart/form-data' action='stats.php'>
	
	<input type='submit' value='Show'><input type=textbox name='limit' value='$limit' size='5' maxlength='5'> row(s) starting from record # <input type=textbox name='start' value='$start' size='5' maxlength='5'>
	<input type='hidden' value='$orderby' name='orderby'>
	<input type='hidden' value='$direction' name='direction'>
	
	</form>
	
	
	</td></tr>";
	$table .= "</table>";

	return "<center>".$table."</center>";
}

function stringChop($string, $desiredLength, $suffix){
	if (strlen($string) > $desiredLength){
		$string = substr($string,0,$desiredLength).$suffix;
		return $string;
	}
	return $string;
}

switch ($op){

	default:
	Main();
	break;
}


?>