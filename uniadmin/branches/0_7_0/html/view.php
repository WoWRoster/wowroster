<?php

$interface = true;

include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');

main();

$db->close_db();

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
				<th class="tableHeader" colspan="10">'.$user->lang['view_addons'].'</th>
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

	$AddonPanel .= "</table>\n<br />\n";

	$db->free_result($result);




	/**
	 * Grab UniUploader settings as well
	 **/



	// logos
	$logo = '';
	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `active` = '1';";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$logo = '
<table class="uuTABLE" align="center">
	<tr>
		<th colspan="4" class="tableHeader">'.$user->lang['title_logo'].'</th>
	</tr>
	<tr>';

		while ($row = $db->fetch_record($result))
		{
			$logo .= '
		<td class="dataHeader">'.sprintf($user->lang['logo_table'],$row['logo_num']).'</td>
		<td class="data2"><img src="'.$row['download_url'].'" alt="'.sprintf($user->lang['logo_table'],$row['logo_num']).'" /></td>
';
		}
		$logo .= "\t</tr>\n</table>\n<br />\n";
	}
	$db->free_result($result);



	// sv list
	$svlist = '';
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."`;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$svlist = '
<table class="uuTABLE" align="center">
	<tr>
		<th colspan="2" class="tableHeader">'.$user->lang['svfiles'].'</th>
	</tr>
	<tr>
		<td class="dataHeader">'.$user->lang['files'].'</td>
		<td class="data2">';
		while ($row = $db->fetch_record($result))
		{
			$svlist .= $row['sv_name'].'<br />';
		}
		$svlist .= "</td>\n\t</tr>\n</table>\n<br />\n";
	}
	$db->free_result($result);



	// settings
	$settings = '';
	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` WHERE `enabled` = '1';";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$settings = '
<table class="uuTABLE" align="center">
	<tr>
		<th colspan="2" class="tableHeader">'.$user->lang['uniuploader_sync_settings'].'</th>
	</tr>';

		$settings .= '
	<tr>
		<td class="dataHeader">'.$user->lang['setting_name'].'</td>
		<td class="dataHeader">'.$user->lang['value'].'</td>
	</tr>';

		while ($row = $db->fetch_record($result))
		{
			$tdClass = 'data'.$uniadmin->switch_row_class(true);

			$settings .= '
	<tr>
		<td class="'.$tdClass.'" onmouseover="return overlib(\''.$user->lang[$row['set_name']].'<hr /><img src=&quot;'.$uniadmin->url_path.'images/'.$row['set_name'].'.jpg&quot; alt=&quot;['.$user->lang['image_missing'].']&quot; />\',CAPTION,\''.$row['set_name'].'\',VAUTO);" onmouseout="return nd();">
			<img src="'.$uniadmin->url_path.'images/blue-question-mark.gif" alt="[?]" /> '.$row['set_name'].'</td>
		<td class="'.$tdClass.'">';


		// Figure out input type
		$input_field = '';
		$input_type = explode('{',$row['form_type']);

		switch ($input_type[0])
		{
			case 'radio':
				$options = explode('|',$input_type[1]);
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					$input_field .= ( $row['set_value'] == $vals[1] ? $vals[0] : '' )."\n";
				}
				break;

			case 'select':
				$options = explode('|',$input_type[1]);
				$select_one = 1;
				foreach( $options as $value )
				{
					$input_field .= ( $row['set_value'] == $vals[1] ? $vals[0] : '' )."\n";
					break;
				}
				break;

			default:
				$input_field = $row['set_value'];
				break;
		}

		$settings .= $input_field.'</td>
	</tr>
';
		}
		$settings .= "</table>\n<br />\n";
	}
	$db->free_result($result);


	EchoPage($AddonPanel.$logo.$svlist.$settings,$user->lang['view_addons']);
}

?>