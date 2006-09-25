<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$interface = true;

include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');


function main()
{
	global $db, $uniadmin, $user;

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` ORDER BY `name`;";
	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		$AddonPanel = '
		<table class="uuTABLE" align="center">
			<tr>
				<th class="tableHeader" colspan="10">'.$user->lang['addon_management'].'</th>
			</tr>
			<tr>
				<td class="dataHeader">'.$user->lang['name'].'</td>
				<td class="dataHeader">'.$user->lang['toc'].'</td>
				<td class="dataHeader">'.$user->lang['required'].'</td>
				<td class="dataHeader">'.$user->lang['version'].'</td>
				<td class="dataHeader">'.$user->lang['uploaded'].'</td>
				<td class="dataHeader">'.$user->lang['enabled'].'</td>
				<td class="dataHeader">'.$user->lang['files'].'</td>
				<td class="dataHeader">'.$user->lang['url'].'</td>
			</tr>';

		while ($row = mysql_fetch_assoc($result))
		{
			$sql = "SELECT * FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '".$row['id']."';";
			$result2 = $db->query($sql);
			$numFiles = $db->num_rows($result2);
			$db->free_result($result2);

			$AddonName = $row['name'];
			$homepage = $row['homepage'];
			$version = $row['version'];
			$time = date($user->lang['time_format'],$row['time_uploaded']);
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
			{
				$required = '<span style="color:red;font-weight:bold;">yes</span>';
			}
			else
			{
				$required = '<span style="color:green;font-weight:bold;">no</span>';
			}

			$toc = $row['toc'];

			$tdClass = 'data'.$uniadmin->switch_row_class();

			$AddonPanel .= '
		<tr>
			<td class="'.$tdClass.'"><a href="'.$homepage.'" target="_blank">'.$AddonName.'</a></td>
			<td class="'.$tdClass.'">'.$toc.'</td>
			<td class="'.$tdClass.'">'.$required.'</td>
			<td class="'.$tdClass.'">'.$version.'</td>
			<td class="'.$tdClass.'">'.$time.'</td>
			<td class="'.$tdClass.'">'.$enabled.'</td>
			<td class="'.$tdClass.'">'.$numFiles.'</td>
			<td class="'.$tdClass.'"><a href="'.$url.'">Check</a></td>
		</tr>
';
		}
	}
	else
	{
		$AddonPanel = '
		<table class="uuTABLE" align="center">
			<tr>
				<th class="tableHeader">'.$user->lang['view_addons'].'</th>
			</tr>
			<tr>
				<th class="dataHeader">'.$user->lang['error_no_addon_in_db'].'</th>
			</tr>';
	}

	$AddonPanel .= '</table>';

	$db->free_result($result);

	EchoPage($AddonPanel,'Addons');
}

Main();


?>