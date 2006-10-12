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

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

$id = ( isset($_POST[UA_URI_ID]) ? $_POST[UA_URI_ID] : '' );

// Decide What To Do
switch( $op )
{
	case UA_URI_PROCESS:
		process_update();
		main();
		break;

	case UA_URI_ADD:
		add_sv($_POST[UA_URI_SVNAME]);
		main();
		break;

	case UA_URI_DELETE:
		remove_sv($id);
		main();
		break;

	case UA_URI_UPINI:
		process_ini();
		break;

	case UA_URI_GETINI:
		get_ini();
		main();
		break;

	default:
		main();
		break;
}








/**
 * Settings Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $uniadmin, $user;

	$form = '
<form name="ua_mainsettings" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<table class="ua_table" align="center">
		<tr>
			<th colspan="4" class="table_header">'.$user->lang['uniuploader_sync_settings'].'</th>
		</tr>';

	$sectionheader = '
		<tr>
			<th colspan="4" class="data_header">[%s]</th>
		</tr>
		<tr>
			<td class="data_header">'.$user->lang['setting_name'].'</td>
			<td class="data_header">'.$user->lang['value'].'</td>
			<td class="data_header">'.$user->lang['enabled'].'</td>
		</tr>';

	$section = '';

	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` ORDER BY `id` ASC;";
	$result = $db->query($sql);

	while( $row = $db->fetch_record($result) )
	{
		if( $row['section'] != $section )
		{
			$section = $row['section'];
			$form .= sprintf($sectionheader,( $section == '' ? 'unknown' : $section ));
		}

		$setname = $row['set_name'];
		$setvalue = $row['set_value'];

		$td_class = 'data'.$uniadmin->switch_row_class();

		$form .= '
		<tr>
			<td class="'.$td_class.'" onmouseover="return overlib(\''.addslashes($user->lang[$setname]).'&lt;hr /&gt;&lt;img src=&quot;'.$uniadmin->url_path.'images/'.$setname.'.jpg&quot; alt=&quot;['.$user->lang['image_missing'].']&quot; /&gt;\',CAPTION,\''.$setname.'\',VAUTO);" onmouseout="return nd();">
				<img src="'.$uniadmin->url_path.'images/blue-question-mark.gif" alt="[?]" /> '.$setname.'</td>
			<td class="'.$td_class.'">';


		// Figure out input type
		$input_field = '';
		$input_type = explode('{',$row['form_type']);

		switch( $input_type[0] )
		{
			case 'text':
				$length = explode('|',$input_type[1]);
				$input_field = '<input class="input" name="'.$setname.'" type="text" value="'.$setvalue.'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
				break;

			case 'radio':
				$options = explode('|',$input_type[1]);
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					$input_field .= '<label><input type="radio" name="'.$setname.'" value="'.$vals[1].'" '.( $setvalue == $vals[1] ? 'checked="checked"' : '' ).' />'.$user->lang[$vals[0]]."</label>\n";
				}
				break;

			case 'select':
				$options = explode('|',$input_type[1]);
				$input_field .= '<select class="input" name="'.$setname.'">'."\n";
				$select_one = 1;
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					if( $setvalue == $vals[1] && $select_one )
					{
						$input_field .= '	<option value="'.$vals[1].'" selected="selected">&gt;'.$vals[0].'&lt;</option>'."\n";
						$select_one = 0;
					}
					else
					{
						$input_field .= '	<option value="'.$vals[1].'">'.$vals[0].'</option>'."\n";
					}
				}
				$input_field .= '</select>';
				break;

			case 'function':
				$input_field = $input_type[1]();
				break;

			case 'display':
				$input_field = $setvalue;
				break;

			default:
				$input_field = $setvalue;
				break;
		}

		$form .= $input_field.'</td>'."\n";

		if ($row['enabled'] == '1')
		{
			$form .= '			<td class="'.$td_class.'" align="center"><input type="checkbox" name="'.$setname.'_en" value="1" checked="checked" /></td>'."\n";
		}
		else
		{
			$form .= '			<td class="'.$td_class.'" align="center"><input type="checkbox" name="'.$setname.'_en" value="1" /></td>'."\n";
		}
		$form .= '		</tr>'."\n";
	}

	$form .= '		<tr>
			<td class="data_header" colspan="4" align="center"><input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_PROCESS.'" />
				<input class="submit" type="submit" name="settings" value="'.$user->lang['update_settings'].'" /></td>
		</tr>
	</table>
</form>';

	// Build the SV list table
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."` ORDER BY `id` DESC;";
	$result = $db->query($sql);

	$sv_table = '
<table class="ua_table" width="40%" align="center">
	<tr>
		<th colspan="2" class="table_header">'.$user->lang['manage_svfiles'].'</th>
	</tr>
	<tr>
		<td class="data_header">'.$user->lang['files'].'</td>
		<td class="data_header" width="10%">'.$user->lang['remove'].'</td>
	</tr>
';

	while( $row = $db->fetch_record($result) )
	{
		$td_class = 'data'.$uniadmin->switch_row_class(true);

		$sv_table .= '
	<tr>
		<td class="'.$td_class.'">'.$row['sv_name'].' <b>.lua</b></td>
		<td class="'.$td_class.'">
			<form name="ua_removesv_'.$row['id'].'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input class="submit" type="submit" value="'.$user->lang['remove'].'" />
				<input type="hidden" value="'.$row['id'].'" name="'.UA_URI_ID.'" />
				<input type="hidden" value="'.UA_URI_DELETE.'" name="'.UA_URI_OP.'" />
			</form>
		</td>
	</tr>
';
	}

	$td_class = 'data'.$uniadmin->switch_row_class(true);


	$sv_table .= '
</table>

<br />';

	if( $user->data['level'] >= UA_ID_POWER )
	{
		$sv_table .= '
<form name="ua_addsv" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<table class="ua_table" width="40%" align="center">
		<tr>
			<th colspan="2" class="table_header">'.$user->lang['add_svfiles'].'</th>
		</tr>
		<tr>
			<td class="data_header">'.$user->lang['filename'].'</td>
			<td class="data_header" width="10%">'.$user->lang['add'].'</td>
		</tr>
		<tr>
			<td class="'.$td_class.'"><input class="input" type="text" name="'.UA_URI_SVNAME.'" /> <b>.lua</b></td>
			<td class="'.$td_class.'"><input class="submit" type="submit" value="'.$user->lang['add'].'" /></td>
		</tr>
	</table>
	<input type="hidden" value="'.UA_URI_ADD.'" name="'.UA_URI_OP.'" />
</form>
';
	}

	if( $user->data['level'] == UA_ID_ADMIN )
	{
		$sv_table .= '
<br />

<table class="ua_table" align="center">
	<tr>
		<th colspan="2" class="table_header">'.$user->lang['settings_file'].'</th>
	</tr>
	<tr>
		<td class="data1">'.$user->lang['import_file'].':</td>
		<td class="data1">
			<form name="ua_uploadini" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input class="file" type="file" name="file" />
				<input type="hidden" value="'.UA_URI_UPINI.'" name="'.UA_URI_OP.'" />
				<input class="submit" type="submit" value="'.$user->lang['import'].'" />
			</form>
		</td>
	</tr>
	<tr>
		<td class="data2">'.$user->lang['export_file'].':</td>
		<td class="data2">
			<form name="ua_getini" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input type="hidden" value="'.UA_URI_GETINI.'" name="'.UA_URI_OP.'" />
				<input class="submit" type="submit" value="'.$user->lang['export'].'" />
			</form>
		</td>
	</tr>
</table>
';
	}

	display_page($sv_table.'<br />'.$form,$user->lang['title_settings']);
}

/**
 * Process Settings Update
 */
function process_update( )
{
	global $db, $uniadmin, $user;

	foreach( $_POST as $setting_name => $setting_value )
	{
		if( !( substr_count($setting_name,'_en') >= 1 ) ||  !( substr_count($setting_name,'_ck') >= 1 ) || $setting_name != UA_URI_OP || $setting_name != 'settings' || $setting_name != 'inifile' )
		{
			if( isset($_POST['settings']) )
			{
				if( isset($_POST[$setting_name.'_en']) && $_POST[$setting_name.'_en'] == '1')
				{
					$enabled = 1;
				}
				else
				{
					$enabled = 0;
				}

				$sql = "UPDATE `".UA_TABLE_SETTINGS."` SET `enabled` = '$enabled', `set_value` = '".$db->escape($setting_value)."' WHERE `set_name` = '".$db->escape($setting_name)."' LIMIT 1;";
				$db->query($sql);
			}
			elseif( isset($_POST['inifile']) )
			{
				if( isset($_POST[$setting_name.'_ck']) && $_POST[$setting_name.'_ck'] == '1')
				{
					$sql = "UPDATE `".UA_TABLE_SETTINGS."` SET `set_value` = '".$db->escape($setting_value)."' WHERE `set_name` = '".$db->escape($setting_name)."' LIMIT 1;";
					$db->query($sql);
				}
			}
		}
	}
	$uniadmin->message($user->lang['settings_updated']);
}

/**
 * Adds a SV filename
 *
 * @param string $svname
 */
function add_sv( $svname )
{
	global $db, $user, $uniadmin;

	if( !empty($svname) )
	{
		$sql = "INSERT INTO `".UA_TABLE_SVLIST."` ( `sv_name` ) VALUES ( '".$db->escape($svname)."' );";
		$db->query($sql);
		if( !$db->affected_rows() )
		{
			$uniadmin->debug(sprintf($user->lang['sql_error_settings_sv_insert'],$svname));
		}
	}
}

/**
 * Removes a SV filename
 *
 * @param int $id
 */
function remove_sv( $id )
{
	global $db, $user, $uniadmin;

	$sql = "DELETE FROM `".UA_TABLE_SVLIST."` WHERE `id` = ".$db->escape($id)." LIMIT 1;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
		$uniadmin->debug(sprintf($user->lang['sql_error_settings_sv_remove'],$id));
	}
}

/**
 * Process an uploaded ini file
 */
function process_ini( )
{
	global $db, $uniadmin, $user;

	$temp_file_name = $_FILES['file']['tmp_name'];

	if( !empty($temp_file_name) )
	{
		if( $_FILES['file']['name'] != 'settings.ini' )
		{
			$uniadmin->message($user->lang['error_ini_file']);
			return;
		}

		$url = $uniadmin->url_path;
		$file_name = str_replace(' ','_',$_FILES['file']['name']);

		$ini_folder = UA_BASEDIR.$uniadmin->config['addon_folder'];

		// Set Download URL
		$download_url = $url.$uniadmin->config['addon_folder'].'/'.$file_name;

		// Name and location of the ini file
		$ini_file = $ini_folder.DIR_SEP.$file_name;

		// Delete ini if it exists
		@unlink($ini_file);


		// Try to move to the addon_temp directory
		$try_move = move_uploaded_file($temp_file_name,$ini_file);
		if( !$try_move )
		{
			$uniadmin->debug(sprintf($user->lang['error_move_uploaded_file'],$temp_file_name,$ini_file));
			return;
		}

		$ini_data = read_ini_file($ini_file);

		if( is_array($ini_data) )
		{
			$form = '
<form name="ua_mainsettings" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<table class="ua_table" align="center">
		<tr>
			<th colspan="4" class="table_header">'.$user->lang['settings_file'].'</th>
		</tr>';

			$sectionheader = '
		<tr>
			<th colspan="4" class="data_header">[%s]</th>
		</tr>
		<tr>
			<td class="data_header">'.$user->lang['setting_name'].'</td>
			<td class="data_header">'.$user->lang['value'].'</td>
			<td class="data_header">'.$user->lang['import'].'</td>
		</tr>';

			$section = '';

			foreach( $ini_data as $section => $setting )
			{
				$form .= sprintf($sectionheader,( $section == '' ? 'unknown' : $section ));

				foreach( $setting as $setting_name => $setting_value )
				{
					if( $setting_value == 'True' )
					{
						$setting_value = '1';
					}
					elseif( $setting_value == 'False' )
					{
						$setting_value = '0';
					}
					if( !in_array($setting_name,$uniadmin->reject_ini) )
					{
						$td_class = 'data'.$uniadmin->switch_row_class();

						$form .= '
		<tr>
			<td class="'.$td_class.'" onmouseover="return overlib(\''.addslashes($user->lang[$setting_name]).'&lt;hr /&gt;&lt;img src=&quot;'.$uniadmin->url_path.'images/'.$setting_name.'.jpg&quot; alt=&quot;['.$user->lang['image_missing'].']&quot; /&gt;\',CAPTION,\''.$setting_name.'\',VAUTO);" onmouseout="return nd();">
				<img src="'.$uniadmin->url_path.'images/blue-question-mark.gif" alt="[?]" /> '.$setting_name.'</td>
			<td class="'.$td_class.'"><input type="hidden" name="'.$setting_name.'" value="'.$setting_value.'" />'.$setting_value.'</td>'."\n";

						$form .= '			<td class="'.$td_class.'" align="center"><input type="checkbox" name="'.$setting_name.'_ck" value="1" /></td>
		</tr>
';
					}
				}
			}

			$form .= '		<tr>
			<td class="data_header" colspan="4" align="center"><input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_PROCESS.'" />
				<input class="submit" type="submit" name="inifile" value="'.$user->lang['update_settings'].'" /></td>
		</tr>
	</table>
</form>';
		}

		// Delete ini if it exists
		@unlink($ini_file);

		display_page($form,$user->lang['title_settings']);
	}
	else // Nothing was uploaded
	{
		$uniadmin->message($user->lang['error_no_ini_uploaded']);
	}
}

function get_ini( )
{
	global $db;

	$comment = "UniUploader initialization file\nAUTO generated by UniAdmin";

	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` ORDER BY `id` ASC;";
	$result = $db->query($sql);

	$gen_ini = array();
	while( $row = $db->fetch_record($result) )
	{
		$gen_ini[$row['section']][$row['set_name']] = $row['set_value'];
	}

	$ini_file = generate_ini_file($gen_ini,$comment);

	header('Content-Type: text/x-delimtext; name="settings.ini"');
	header('Content-disposition: attachment; filename="settings.ini"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	echo stripslashes($ini_file);

	exit;
}

/**
 * Read a settings.ini uploaded from UniUploader
 *
 * @param string $filename
 * @param string $commentchar
 * @return mixed
 */
function read_ini_file( $filename , $commentchar='#' )
{
	$array = file( $filename );
	$section = '';
	if( $array )
	{
		foreach( $array as $filedata )
		{
			$dataline = trim( $filedata );
			$firstchar = substr( $dataline, 0, 1 );
			if( $firstchar != $commentchar && $dataline!='' )
			{
				//It's an entry (not a comment and not a blank line)
				if( $firstchar == '[' && substr( $dataline, -1, 1 ) == ']' )
				{
					//It's a section
					$section = substr( $dataline, 1, -1 );
				}
				else
				{
					//It's a key...
					$delimiter = strpos( $dataline, '=' );
					if( $delimiter > 0 )
					{
						//...with a value
						$key = trim( substr( $dataline, 0, $delimiter ) );
						$value = trim( substr( $dataline, $delimiter + 1 ) );
						if( substr( $value, 0, 1 ) == '"' && substr( $value, -1, 1 ) == '"' )
						{
							$value = substr( $value, 1, -1 );
						}
						$array_out[$section][$key] = $value;
					}
					else
					{
						//...without a value
						$array_out[$section][trim( $dataline )]='';
					}
				}
			}
			else
			{
				//It's a comment or blank line.  Ignore.
			}
		}
		return $array_out;
	}
	else
	{
		return false;
	}
}

/**
 * Generate a settings.ini file for UniUploader
 *
 * @param array $array
 * @param string $commentchar
 * @param string $commenttext
 * @return array
 */
function generate_ini_file( $array , $commenttext='' , $commentchar='#' )
{
	$ini_file = '';
	if( $commenttext!='' )
	{
		$comtext = $commentchar.
		str_replace( $commentchar, "\r\n".$commentchar,
		str_replace( "\r", $commentchar,
		str_replace( "\n", $commentchar,
		str_replace( "\n\r", $commentchar,
		str_replace( "\r\n", $commentchar, $commenttext )
		)
		)
		)
		)
		;
		if( substr( $comtext, -1, 1 ) == $commentchar && substr( $commenttext, -1, 1 ) != $commentchar )
		{
			$comtext = substr($comtext, 0, -1);
		}
		$ini_file .= $comtext."\r\n\r\n";
	}
	foreach( $array as $sections => $items )
	{
		//Write the section
		if( isset( $section ) )
		{
			$ini_file .= "\r\n";
		}
		$section = preg_replace( '/[\0-\37]|\177/', '-', $sections );
		$ini_file .= '['.$section."]\r\n";
		foreach( $items as $keys => $values )
		{
			//Write the key/value pairs
			$key = preg_replace( '/[\0-\37]|=|\177/', '-', $keys );
			if ( substr( $key, 0, 1 ) == $commentchar )
			{
				$key = '-'.substr( $key, 1 );
			}
			$value = addcslashes( $values,'' );
			switch( $value )
			{
				case '0':
					$value = 'False';
					break;

				case '1':
					$value = 'True';
					break;

				default:
					break;
			}
			$ini_file .= $key.'='.$value."\r\n";
		}
	}
	return $ini_file;
}

?>