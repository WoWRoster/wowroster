<?php

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
		processUpdate();
		break;

	case UA_URI_ADD:
		addSv();
		break;

	case UA_URI_DELETE:
		removeSv($id);
		break;

	case UA_URI_UPINI:
		processIni();
		break;

	case UA_URI_GETINI:
		//getIni();
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
function main( )
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

	$section = '';

	while( $row = $db->fetch_record($result) )
	{
		if( $row['section'] != $section )
		{
			$section = $row['section'];
			$form .= sprintf($sectionheader,( $section == '' ? 'unknown' : $section ));
		}

		$setname = $row['set_name'];
		$setvalue = $row['set_value'];

		$tdClass = 'data'.$uniadmin->switch_row_class();

		$form .= '
	<tr>
		<td class="'.$tdClass.'" onmouseover="return overlib(\''.$user->lang[$setname].'<hr /><img src=&quot;'.$uniadmin->url_path.'images/'.$setname.'.jpg&quot; alt=&quot;['.$user->lang['image_missing'].']&quot; />\',CAPTION,\''.$setname.'\',VAUTO);" onmouseout="return nd();">
			<img src="'.$uniadmin->url_path.'images/blue-question-mark.gif" alt="[?]" /> '.$setname.'</td>
		<td class="'.$tdClass.'">';


		// Figure out input type
		$input_field = '';
		$input_type = explode('{',$row['form_type']);

		switch( $input_type[0] )
		{
			case 'text':
				$length = explode('|',$input_type[1]);
				$input_field = '<input class="input" name="'.$row['set_name'].'" type="text" value="'.$setvalue.'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
				break;

			case 'radio':
				$options = explode('|',$input_type[1]);
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					$input_field .= '<label><input type="radio" name="'.$row['set_name'].'" value="'.$vals[1].'" '.( $setvalue == $vals[1] ? 'checked="checked"' : '' ).' />'.$user->lang[$vals[0]]."</label>\n";
				}
				break;

			case 'select':
				$options = explode('|',$input_type[1]);
				$input_field .= '<select class="input" name="'.$row['set_name'].'">'."\n";
				$select_one = 1;
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					if( $setvalue == $vals[1] && $select_one )
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
				$input_field = $setvalue;
				break;

			default:
				$input_field = $setvalue;
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

	// Build the SV list table
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

	<br />';

	if( $user->data['level'] >= UA_ID_POWER )
	{
		$svTable .= '
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
	}

	if( $user->data['level'] == UA_ID_ADMIN )
	{
		$svTable .= '
	<br />

	<table class="uuTABLE" align="center">
		<tr>
			<th colspan="2" class="tableHeader">'.$user->lang['settings_file'].'</th>
		</tr>
		<tr>
			<td class="data1">'.$user->lang['import_file'].':</td>
			<td class="data1"><form name="ua_uploadini" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input class="file" type="file" name="file" />
				<input type="hidden" value="'.UA_URI_UPINI.'" name="'.UA_URI_OP.'" />
				<input class="submit" type="submit" value="'.$user->lang['import'].'" />
				</form></td>
		</tr>
		<tr>
			<td class="data2">'.$user->lang['export_file'].':</td>
			<td class="data2"><form name="ua_getini" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input type="hidden" value="'.UA_URI_GETINI.'" name="'.UA_URI_OP.'" />
				<input class="submit" type="submit" value="'.$user->lang['export'].'" />
				</td>
		</tr>
	</table>
	</form>
';
	}

	echoPage($svTable.'<br />'.$form,$user->lang['title_settings']);
}

/**
 * Process Settings Update
 */
function processUpdate( )
{
	global $db, $user;

	foreach( $_POST as $settingName => $settingValue )
	{
		if( !( substr_count($settingName,'_en') >= 1 ) || $settingName != UA_URI_OP )
		{
			if( isset($_POST[$settingName.'_en']) && $_POST[$settingName.'_en'] == '1')
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
function addSv( )
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
 *
 * @param int $id
 */
function removeSv( $id )
{
	global $db, $user;

	$sql = "DELETE FROM `".UA_TABLE_SVLIST."` WHERE `id` = ".$db->escape($id)." LIMIT 1;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
		debug(sprintf($user->lang['sql_error_settings_sv_remove'],$id));
	}
}

function processIni( )
{
	global $db, $uniadmin, $user;

	$tempFilename = $_FILES['file']['tmp_name'];

	if( !empty($tempFilename) )
	{
		if( $_FILES['file']['name'] != 'settings.ini' )
		{
			message($user->lang['error_ini_file']);
			return;
		}

		$url = $uniadmin->url_path;
		$fileName = str_replace(' ','_',$_FILES['file']['name']);

		$iniFolder = UA_BASEDIR.$uniadmin->config['addon_folder'];

		// Set Download URL
		$downloadLocation = $url.$uniadmin->config['addon_folder'].'/'.$fileName;

		// Name and location of the ini file
		$inifile = $iniFolder.DIR_SEP.$fileName;

		// Delete ini if it exists
		@unlink($inifile);


		// Try to move to the addon_temp directory
		$try_move = move_uploaded_file($tempFilename,$inifile);
		if( !$try_move )
		{
			debug(sprintf($user->lang['error_move_uploaded_file'],$tempFilename,$inifile));
			return;
		}

		$iniData = readINIfile($inifile);

		ob_start();
		print_r($iniData);
		$printthis = ob_get_clean();

		message('<pre>'.$printthis.'</pre>');

		// Delete ini if it exists
		@unlink($inifile);
	}
	else // Nothing was uploaded
	{
		message($user->lang['error_no_ini_uploaded']);
	}
	return;
}

function getIni( )
{
	global $db, $uniadmin, $user;

	return;
}

/**
 * Read a settings.ini uploaded from UniUploader
 *
 * @param string $filename
 * @param string $commentchar
 * @return mixed
 */
function readINIfile( $filename , $commentchar='#' )
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
 * Write a settings.ini file for UniUploader
 *
 * @param string $filename
 * @param array $array
 * @param string $commentchar
 * @param string $commenttext
 * @return bool
 */
function writeINIfile( $filename , $array , $commentchar , $commenttext )
{
	$handle = fopen( $filename, 'wb' );
	if( $handle )
	{
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
			fwrite( $handle, $comtext."\r\n\r\n" );
		}
		foreach( $array as $sections => $items )
		{
			//Write the section
			if( isset( $section ) )
			{
				fwrite( $handle, "\r\n" );
			}
			$section = preg_replace( '/[\0-\37]|\177/', '-', $sections );
			fwrite( $handle, '['.$section."]\r\n" );
			foreach( $items as $keys => $values )
			{
				//Write the key/value pairs
				$key = preg_replace( '/[\0-\37]|=|\177/', '-', $keys );
				if ( substr( $key, 0, 1 ) == $commentchar )
				{
					$key = '-'.substr( $key, 1 );
				}
				$value = addcslashes( $values,'' );
				fwrite( $handle, $key.'='.$value."\r\n" );
			}
		}
		fclose( $handle );
		return true;
	}
	else
	{
		return false;
	}
}

?>