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
		if( $user->data['level'] >= UA_ID_USER )
			process_update();
		main();
		break;

	case UA_URI_ADD:
		if( $user->data['level'] >= UA_ID_POWER )
			add_sv($_POST[UA_URI_SVNAME]);
		main();
		break;

	case UA_URI_DELETE:
		if( $user->data['level'] >= UA_ID_POWER )
			remove_sv($id);
		main();
		break;

	case UA_URI_UPINI:
		if( $user->data['level'] >= UA_ID_ADMIN )
			process_ini();
		break;

	case UA_URI_GETINI:
		if( $user->data['level'] >= UA_ID_ADMIN )
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
	global $db, $uniadmin, $user, $tpl;

	$tpl->assign_vars(array(
		'L_SYNC_SETTINGS'  => $user->lang['uniuploader_sync_settings'],
		'L_SETTING_NAME'   => $user->lang['setting_name'],
		'L_VALUE'          => $user->lang['value'],
		'L_ENABLED'        => $user->lang['enabled'],
		'L_IMG_MISSING'    => $user->lang['image_missing'],
		'L_UPDATE_SET'     => $user->lang['update_settings'],
		'L_REMOVE'         => $user->lang['remove'],
		'L_FILES'          => $user->lang['files'],
		'L_MANAGE_SV'      => $user->lang['manage_svfiles'],
		'L_ADD_SV'         => $user->lang['add_svfiles'],
		'L_FILENAME'       => $user->lang['filename'],
		'L_ADD'            => $user->lang['add'],
		'L_SETTINGS_FILE'  => $user->lang['settings_file'],
		'L_IMPORT_FILE'    => $user->lang['import_file'],
		'L_IMPORT'         => $user->lang['import'],
		'L_EXPORT_FILE'    => $user->lang['export_file'],
		'L_EXPORT'         => $user->lang['export'],

		'S_MANAGE'         => false,
		'S_INI'            => false,
		)
	);

	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."`";

	if( $user->data['level'] == UA_ID_ADMIN )
	{
		$tpl->assign_var('S_MANAGE',true);
		$tpl->assign_var('S_INI',true);
		$sql .= " ORDER BY `id` ASC;";
	}
	elseif( $user->data['level'] >= UA_ID_POWER )
	{
		$tpl->assign_var('S_MANAGE',true);
		$sql .= " ORDER BY `id` ASC;";
	}
	elseif( $user->data['level'] == UA_ID_ANON )
	{
		$tpl->assign_var('L_MANAGE_SV',$user->lang['svfiles']);
		$sql .= " WHERE `enabled` = '1' ORDER BY `id` ASC;";
	}

	$section = '';
	$result = $db->query($sql);

	while( $row = $db->fetch_record($result) )
	{
		if( $row['section'] != $section )
		{
			$section = $row['section'];

			$tpl->assign_block_vars('section', array(
				'NAME'  => $section,
				)
			);
		}

		$setname = $row['set_name'];
		$setvalue = $row['set_value'];

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

		if ($row['enabled'] == '1')
		{
			$checked = ' checked="checked"';
			$enabled = true;
		}
		else
		{
			$checked = '';
			$enabled = false;
		}

		$tpl->assign_block_vars('section.settings_row', array(
			'ROW_CLASS'   => $uniadmin->switch_row_class(),
			'SETNAME'     => $setname,
			'SETVALUE'    => $setvalue,
			'TOOLTIP'     => addslashes($user->lang[$setname]),
			'INPUT_FIELD' => $input_field,
			'CHECKED'     => $checked,
			'ENABLED'     => $enabled,
			)
		);
	}

	// Build the SV list table
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."` ORDER BY `id` DESC;";
	$result = $db->query($sql);

	while( $row = $db->fetch_record($result) )
	{
		$td_class = 'data'.$uniadmin->switch_row_class(true);

		$tpl->assign_block_vars('sv_list', array(
			'ROW_CLASS' => $uniadmin->switch_row_class(),
			'NAME'      => $row['sv_name'],
			'ID'        => $row['id'],
			)
		);
	}

	$uniadmin->set_vars(array(
		'page_title'    => $user->lang['title_settings'],
		'template_file' => 'settings.html',
		'display'       => true
		)
	);
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
			$uniadmin->message(sprintf($user->lang['sql_error_settings_sv_insert'],$svname));
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
		$uniadmin->message(sprintf($user->lang['sql_error_settings_sv_remove'],$id));
	}
}

/**
 * Process an uploaded ini file
 */
function process_ini( )
{
	global $db, $uniadmin, $user, $tpl;

	if( $user->data['level'] != UA_ID_ADMIN )
	{
		$uniadmin->set_vars(array(
		    'template_file' => 'index.html',
		    'display'       => true)
		);
		die();
	}

	$tpl->assign_vars(array(
		'L_SETTINGS_FILE'  => $user->lang['settings_file'],
		'L_SETTING_NAME'   => $user->lang['setting_name'],
		'L_VALUE'          => $user->lang['value'],
		'L_IMPORT'         => $user->lang['import'],
		'L_UPDATE_SET'     => $user->lang['update_settings'],
		'L_IMG_MISSING'    => $user->lang['image_missing'],
		)
	);

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

		$ini_folder = UA_CACHEDIR;

		// Name and location of the ini file
		$ini_file = $ini_folder.$file_name;

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
			$section = '';

			foreach( $ini_data as $section => $setting )
			{
				$tpl->assign_block_vars('section', array(
					'NAME'  => $section,
					)
				);

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
						$tpl->assign_block_vars('section.settings_row', array(
							'ROW_CLASS'   => $uniadmin->switch_row_class(),
							'SETNAME'     => $setting_name,
							'SETVALUE'    => $setting_value,
							'TOOLTIP'     => addslashes($user->lang[$setting_name]),
							)
						);
					}
				}
			}
		}

		// Delete ini if it exists
		@unlink($ini_file);


		$uniadmin->set_vars(array(
			'page_title'    => $user->lang['title_settings'],
			'template_file' => 'ini_import.html',
			'display'       => true
			)
		);
	}
	else // Nothing was uploaded
	{
		$uniadmin->message($user->lang['error_no_ini_uploaded']);
		main();
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