<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

// Decide What To Do
switch ($op)
{
	case UA_URI_PROCESS:
		processUpdate();
		break;

	case UA_URI_ADD:
		addSv();
		break;

	case UA_URI_DELETE:
		removeSv();
		break;

	default:
		break;
}
main();

$db->close_db();






/**
 * Settings Page Functions
 */


/**
 * Main Display
 */
function main()
{
	global $db, $uniadmin, $user;

	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` ORDER BY `id` ASC;";
	$result = $db->query($sql);

	$form = '
<form name="ua_mainsettings" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
<table class="uuTABLE" align="center">
	<tr>
		<th colspan="4" class="tableHeader">'.$user->lang['uniuploader_sync_settings'].'</th>
	</tr>';

	$sectionheader = '
	<tr>
		<th colspan="4" class="dataHeader">[%s]</th>
	</tr>
	<tr>
		<td class="dataHeader">'.$user->lang['setting_name'].'</td>
		<td class="dataHeader">'.$user->lang['value'].'</td>
		<td class="dataHeader">'.$user->lang['enabled'].'</td>
	</tr>';

	$section='';

	while( $row = $db->fetch_record($result) )
	{
		if( $row['section'] != $section )
		{
			$section = $row['section'];
			$form .= sprintf($sectionheader,( $section == '' ? 'unknown' : $section ));
		}

		$setname = $row['set_name'];

		$tdClass = 'data'.$uniadmin->switch_row_class();

		$form .= '
	<tr>
		<td class="'.$tdClass.'" onmouseover="return overlib(\''.$user->lang[$setname].'<hr /><img src=&quot;'.$uniadmin->url_path.'images/'.$setname.'.jpg&quot; alt=&quot;['.$user->lang['image_missing'].']&quot; />\',CAPTION,\''.$setname.'\',VAUTO);" onmouseout="return nd();">
			<img src="'.$uniadmin->url_path.'images/blue-question-mark.gif" alt="[?]" /> '.$setname.'</td>
		<td class="'.$tdClass.'">';


		// Figure out input type
		$input_field = '';
		$input_type = explode('{',$row['form_type']);

		switch ($input_type[0])
		{
			case 'text':
				$length = explode('|',$input_type[1]);
				$input_field = '<input class="input" name="'.$row['set_name'].'" type="text" value="'.$row['set_value'].'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
				break;

			case 'radio':
				$options = explode('|',$input_type[1]);
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					$input_field .= '<label><input type="radio" name="'.$row['set_name'].'" value="'.$vals[1].'" '.( $row['set_value'] == $vals[1] ? 'checked="checked"' : '' ).' />'.$vals[0]."</label>\n";
				}
				break;

			case 'select':
				$options = explode('|',$input_type[1]);
				$input_field .= '<select class="input" name="'.$row['set_name'].'">'."\n";
				$select_one = 1;
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					if( $row['set_value'] == $vals[1] && $select_one )
					{
						$input_field .= '  <option value="'.$vals[1].'" selected="selected">&gt;'.$vals[0].'&lt;</option>'."\n";
						$select_one = 0;
					}
					else
					{
						$input_field .= '  <option value="'.$vals[1].'">'.$vals[0].'</option>'."\n";
					}
				}
				$input_field .= '</select>';
				break;

			case 'function':
				$input_field = $input_type[1]();
				break;

			case 'display':
				$input_field = $row['set_value'];
				break;

			default:
				$input_field = $row['set_value'];
				break;
		}

		$form .= $input_field.'</td>'."\n";

		if ($row['enabled'] == '1')
		{
			$form .= '		<td class="'.$tdClass.'" align="center"><input type="checkbox" name="'.$row['set_name'].'_en" value="1" checked="checked" /></td>'."\n";
		}
		else
		{
			$form .= '		<td class="'.$tdClass.'" align="center"><input type="checkbox" name="'.$row['set_name'].'_en" value="1" /></td>'."\n";
		}
		$form .= '	</tr>'."\n";
	}

	$form .= '	<tr>
		<td class="dataHeader" colspan="4" align="center"><input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_PROCESS.'" />
			<input class="submit" type="submit" value="'.$user->lang['update_settings'].'" /></td>
	</tr>
</table>
</form>';

	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."` ORDER BY `id` DESC;";
	$result = $db->query($sql);

	$svTable = '
<table class="uuTABLE" width="40%" align="center">
	<tr>
		<th colspan="2" class="tableHeader">'.$user->lang['manage_svfiles'].'</th>
	</tr>
	<tr>
		<td class="dataHeader">'.$user->lang['files'].'</td>
		<td class="dataHeader" width="10%">'.$user->lang['remove'].'</td>
	</tr>
';

	while( $row = $db->fetch_record($result) )
	{
		$tdClass = 'data'.$uniadmin->switch_row_class(true);

		$svTable .= '
	<tr>
		<td class="'.$tdClass.'">'.$row['sv_name'].' <b>.lua</b></td>
		<td class="'.$tdClass.'">
			<form name="ua_removesv_'.$row['id'].'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input class="submit" type="submit" value="'.$user->lang['remove'].'" />
				<input type="hidden" value="'.$row['id'].'" name="'.UA_URI_ID.'" />
				<input type="hidden" value="'.UA_URI_DELETE.'" name="'.UA_URI_OP.'" />
			</form>
		</td>
	</tr>
';
	}

	$tdClass = 'data'.$uniadmin->switch_row_class(true);

	$svTable .= '
	</table>

	<br />

	<form name="ua_addsv" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<table class="uuTABLE" width="40%" align="center">
		<tr>
			<th colspan="2" class="tableHeader">'.$user->lang['add_svfiles'].'</th>
		</tr>
		<tr>
			<td class="dataHeader">'.$user->lang['filename'].'</td>
			<td class="dataHeader" width="10%">'.$user->lang['add'].'</td>
		</tr>
		<tr>
			<td class="'.$tdClass.'"><input class="input" type="text" name="'.UA_URI_SVNAME.'" /> <b>.lua</b></td>
			<td class="'.$tdClass.'"><input class="submit" type="submit" value="'.$user->lang['add'].'" /></td>
		</tr>
	</table>
	<input type="hidden" value="'.UA_URI_ADD.'" name="'.UA_URI_OP.'" />
	</form>
';

	EchoPage($svTable.'<br />'.$form,$user->lang['title_settings']);
}

/**
 * Porcess Settings Update
 */
function processUpdate()
{
	global $db, $user;

	foreach ($_POST as $settingName => $settingValue)
	{
		if( !( substr_count($settingName,'_en') >= 1 ) || $settingName != UA_URI_OP )
		{
			if ( isset($_POST[$settingName.'_en']) && $_POST[$settingName.'_en'] == '1')
			{
				$enabled = 1;
			}
			else
			{
				$enabled = 0;
			}

			$sql = "UPDATE `".UA_TABLE_SETTINGS."` SET `enabled` = '$enabled', `set_value` = '".$db->escape($settingValue)."' WHERE `set_name` = '".$db->escape($settingName)."' LIMIT 1 ;";
			$db->query($sql);
		}
	}
}

/**
 * Adds a SV filename
 */
function addSv()
{
	global $db, $user;

	$sql = "INSERT INTO `".UA_TABLE_SVLIST."` ( `sv_name` ) VALUES ( '".$db->escape($_POST[UA_URI_SVNAME])."' );";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
		debug(sprintf($user->lang['sql_error_settings_sv_insert'],$_POST[UA_URI_SVNAME]));
	}
}

/**
 * Removes a SV filename
 */
function removeSv()
{
	global $db, $user;

	$sql = "DELETE FROM `".UA_TABLE_SVLIST."` WHERE `id` = ".$db->escape($_POST[UA_URI_ID])." LIMIT 1;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
		debug(sprintf($user->lang['sql_error_settings_sv_remove'],$_POST[UA_URI_ID]));
	}
}


?>