<?php

$interface = true;

include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');


function Main()
{
	global $dblink, $config, $url;

	$AddonPanel = "
		<table class='uuTABLE' align='center'>
			<tr>
				<th class='tableHeader' colspan='10'>View Addons</th>
			</tr>
			<tr>
				<td class='dataHeader'>Name</td>
				<td class='dataHeader'>TOC</td>
				<td class='dataHeader'>Required</td>
				<td class='dataHeader'>Version</td>
				<td class='dataHeader'>Uploaded</td>
				<td class='dataHeader'>Enabled</td>
				<td class='dataHeader'>Files</td>
				<td class='dataHeader'>URL</td>
			</tr>";

	$sql = "SELECT * FROM `".$config['db_tables_addons']."` ORDER BY `name`";
	$result = mysql_query($sql,$dblink);

	$i=0;
	while ($row = mysql_fetch_assoc($result))
	{
		$sql = "SELECT * FROM `".$config['db_tables_files']."` WHERE `addon_name` = '".addslashes($row['name'])."'";
		$result2 = mysql_query($sql,$dblink);
		$numFiles = mysql_num_rows($result2);
		$AddonName = $row['name'];
		$homepage = $row['homepage'];
		$version = $row['version'];
		$time = date($config['date_format'],$row['time_uploaded']);
		$url = $row['dl_url'];
		$addonID = $row['id'];
		if ($row['enabled'] == '1')
		{
			$enabled = "<span style='color:green;font-weight:bold;'>yes</span>";
		}
		else
		{
			$enabled="<span style='color:red;font-weight:bold;'>no</span>";
		}
		if ($row['homepage'] == '')
		{
			$homepage = './';
		}

		if ($row['required'] == 1)
			$required = '<span style="color:red;font-weight:bold;">yes</span>';
		else
			$required = '<span style="color:green;font-weight:bold;">no</span>';
		$toc = $row['toc'];

		if($i % 2)
		{
			$tdClass = 'data2';
		}
		else
		{
			$tdClass = 'data1';
		}

		$AddonPanel .="
		<tr>
			<td class='$tdClass'><a target='_blank' href=\"$homepage\">$AddonName</a></td>
			<td class='$tdClass'>$toc</td>
			<td class='$tdClass'>$required</td>
			<td class='$tdClass'>$version</td>
			<td class='$tdClass'>$time</td>
			<td class='$tdClass'>$enabled</td>
			<td class='$tdClass'>$numFiles</td>
			<td class='$tdClass'><a href='$url'>Download</a></td>
		</tr>
		";
		$i++;
	}
	$AddonPanel .= '</table>';


	EchoPage($AddonPanel,'Addons');
}

Main();


?>