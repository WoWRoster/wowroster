<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
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

/**
 * Addon Parsing Functions
 */

/**
 * Processess an uploaded addon for insertion into the database
 *
 * @param array $fileArray	Array of info about a file
 * Standard array that the global $_FILES would contain
 *
 * 		[name] => The original name of the file
 * 		[type] => The mime type of the file
 * 		[tmp_name] => Full Server Path to real file location
 * 		[error] => The error code associated with this file
 * 		[size] => The size, in bytes, of the file
 */
function process_addon( $fileArray )
{
	global $db, $user, $uniadmin;

	//$uniadmin->error('<pre>'.print_r($fileArray,true).'</pre>');

	$temp_file_name = ( isset($fileArray['tmp_name']) ? $fileArray['tmp_name'] : '' );

	if( !empty($temp_file_name) )
	{
		$addon_file_name = str_replace(' ','_',$fileArray['name']);

		if( $uniadmin->get_file_ext($addon_file_name) != 'zip' )
		{
			$uniadmin->message($user->lang['error_zip_file']);
			return;
		}

		$addon_zip_folder = UA_BASEDIR.$uniadmin->config['addon_folder'];
		$temp_folder = UA_BASEDIR.$uniadmin->config['temp_analyze_folder'];

		// Check if this addon is required
		$required = ( isset($_POST['required']) ? 1 : 0 );

		// See if we are auto detecting the path or are we overriding it
		$full_path = false;
		$auto_path = true;
		if( isset($_POST['fullpath_addon']) && $_POST['fullpath_addon'] != '' )
		{
			switch($_POST['fullpath_addon'])
			{
				case '0': // Force false
					$full_path = false;
					$auto_path = false;
					break;

				case '1': // Force true
					$full_path = true;
					$auto_path = false;
					break;

				case '2': // Auto-detect mode
					$full_path = false;
					$auto_path = true;
					break;

				default: // Default is false and auto-detect
					$full_path = false;
					$auto_path = true;
					break;
			}
		}

		// Name and location of the zip file
		$zip_file = $addon_zip_folder.DIR_SEP.$addon_file_name;

		// Do the following actions only if we are not processing an existing addon
		if( is_uploaded_file($temp_file_name) )
		{
			// Delete Addon if it exists
			@unlink($zip_file);

			// Try to move to the addon_temp directory
			$try_move = move_uploaded_file($temp_file_name,$zip_file);
			if( !$try_move )
			{
				$uniadmin->error(sprintf($user->lang['error_move_uploaded_file'],$temp_file_name,$zip_file));
				return;
			}
			@unlink($temp_file_name);
		}

		// Try to set write access on the uploaded file
		if( !is_writeable($zip_file) )
		{
			$try_chmod = @chmod($zip_file,0777);
			if( !$try_chmod )
			{
				$uniadmin->error(sprintf($user->lang['error_chmod'],$zip_file));
				return;
			}
		}

		// Get the file size
		$file_size = @filesize($zip_file);

		// Unzip the file
		$files = $uniadmin->unzip($zip_file,$temp_folder.DIR_SEP);

		//$files = $uniadmin->ls($temp_folder);

		// Get the TOC of the addon
		$toc_file_name = '';
		$toc_files = array();
		$revision_files = array();

		if( is_array($files) )
		{
			foreach( $files as $index => $file )
			{
				$file = $file['filename'];
				if( $uniadmin->get_file_ext($file) == 'toc' )
				{
					$toc_files[] = $file;
					continue;
				}
				elseif( strpos($file, 'changelog-r') !== false && $uniadmin->get_file_ext($file) == 'txt' )
				{
					$revision_files[] = $file;
					continue;
				}

				if( $auto_path )
				{
					// Check if the file has 'Interface/AddOns/', if so set full_path to true
					if( stristr($file, 'Interface/AddOns') || stristr($file, 'Interface\\AddOns') )
					{
						$full_path = true;
					}
				}
			}

			if( count($toc_files) > 0 )
			{
				foreach( $toc_files as $file )
				{
					$toc_number = get_toc_val($file, 'Interface', '00000');

					$k = explode(DIR_SEP,$file);
					$toc_file_name = $k[count($k) - 1];
					$toc_file_name = substr($toc_file_name,0,count($toc_file_name) -5);

					$real_addon_name = get_toc_val($file, 'Title', $toc_file_name);

					if( strpos($real_addon_name, 'Ace') && strpos($real_addon_name, '|r'))
					{
						$real_addon_name = trim(substr($real_addon_name, 0, strpos(substr($real_addon_name, 0, strpos($real_addon_name, '|r')), '|')));
					}

					// Get version
					$revision = '';
					$version = get_toc_val($file, 'Version', '');
					$rev_matches = null;

					if( count($revision_files) > 0 )
					{
						asort($revision_files);

						foreach( $revision_files as $revision_file_name )
						{
							preg_match('|changelog\-r(.+?).txt|',$revision_file_name,$rev_matches);

							$revision = $rev_matches[1];
						}

						if( !empty($version) && !empty($revision) && ($version != $revision) )
						{
							$version .= " | r$revision";
						}
						elseif( empty($version) && !empty($revision) )
						{
							$version = "r$revision";
						}
					}

					// Set notes and homepage
					$homepage = get_toc_val($file, 'X-Website', get_toc_val($file, 'URL', ''));
					$notes = get_toc_val($file, 'Notes', '');


					$addon_file_check = strtolower(str_replace('.zip','',$addon_file_name));
					$toc_file_check = strtolower( str_replace( array(' ','.toc'), array('_',''), basename($toc_file_name) ) );

					if( strpos($addon_file_check, $toc_file_check) !== false )
					{
						break;
					}
					else
					{
						unset($k, $toc_file_name);
					}
				}
			}
			else
			{
				$try_unlink = @unlink($zip_file);
				if( !$try_unlink )
				{
					$uniadmin->error(sprintf($user->lang['error_unlink'],$zip_file));
				}
				$uniadmin->cleardir($temp_folder);
				$uniadmin->error($user->lang['error_no_toc_file']);
				return;
			}
		}
		else
		{
			$try_unlink = @unlink($zip_file);
			if( !$try_unlink )
			{
				$uniadmin->error(sprintf($user->lang['error_unlink'],$zip_file));
			}
			$uniadmin->cleardir($temp_folder);
			$uniadmin->message($user->lang['error_no_files_addon']);
			return;
		}

		// See if AddOn exists in the database and do stuff to it
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `name` = '".$db->escape($real_addon_name)."';";
		$result = $db->query($sql);


		if( $db->num_rows($result) > 0 )
		{
			$row = $db->fetch_record($result);

			$db->free_result($result);

			$addon_id = $row['id'];

			if( $homepage == '' )
			{
				$homepage = $row['homepage'];
			}
			if( $notes == '' )
			{
				$notes = $row['notes'];
			}
			if( $version == '' )
			{
				$version = $row['version'];
			}

			$enabled = $row['enabled'];

			// Remove files from database since we'll be updating them all
			$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '".$addon_id."';";
			$db->query($sql);

			// Update Main Addon data
			$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `time_uploaded` = '".time()."', `version` = '".$db->escape($version)."', `enabled` = '$enabled', `name` = '".$db->escape($real_addon_name)."', `file_name` = '".$db->escape($addon_file_name)."', `homepage` = '".$db->escape($homepage)."', `notes` = '".$db->escape($notes)."', `toc` = '$toc_number', `required` = '$required', `filesize` = '$file_size', `full_path` = '".intval($full_path)."'
				WHERE `id` = '".$addon_id."';";
			$db->query($sql);
		}
		else
		{
			// Insert Main Addon data
			$sql = "INSERT INTO `".UA_TABLE_ADDONS."` ( `time_uploaded` , `version` , `enabled` , `name`, `file_name`, `homepage`, `notes`, `toc`, `required`, `filesize`, `full_path` )
				VALUES ( '".time()."', '".$db->escape($version)."', '1', '".$db->escape($real_addon_name)."', '".$db->escape($addon_file_name)."', '".$db->escape($homepage)."', '".$db->escape($notes)."', '$toc_number', '$required', '$file_size', '".intval($full_path)."' );";
			$db->query($sql);

			// Get the insert id of the addon just inserted
			$addon_id = $db->insert_id();
		}

		if( !$db->affected_rows() )
		{
			// Clear up the addons table
			$sql = "DELETE FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$addon_id'";
			$db->query($sql);
			if( !$db->affected_rows() )
			{
			    $uniadmin->error(sprintf($user->lang['sql_error_addons_delete'],$addon_id));
			}

			$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '$addon_id';";
			$db->query($sql);
			if( !$db->affected_rows() )
			{
			    $uniadmin->error(sprintf($user->lang['sql_error_addons_delete'],$addon_id));
			}

		    $uniadmin->error($user->lang['sql_error_addons_insert']);
		    $uniadmin->cleardir($temp_folder);
		    return;
		}

		// Insert Addon Files' Data
		foreach( $files as $file )
		{
			$file = $file['filename'];
			if( file_exists($file) )
			{
				$md5 = md5_file($file);
				$k = explode(DIR_SEP,$file);
				$pos_t = strpos($file,'addon_temp');
				$file_name = str_replace('/','\\',substr($file,$pos_t + 10));

				if( $file_name != 'index.htm' && $file_name != 'index.html' && $file_name != '.svn' )
				{
					if( $full_path == false )
					{
						$file_name = '\Interface\AddOns'.$file_name;
					}

					$sql = "INSERT INTO `".UA_TABLE_FILES."` ( `addon_id` , `filename` , `md5sum` )
						VALUES ( '".$addon_id."', '".$db->escape($file_name)."', '".$db->escape($md5)."' );";
					$db->query($sql);
					if( !$db->affected_rows() )
					{
						// Clear up the addons table
						$sql = "DELETE FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$addon_id'";
						$db->query($sql);
						if( !$db->affected_rows() )
						{
						    $uniadmin->error(sprintf($user->lang['sql_error_addons_delete'],$addon_id));
						}

						$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '$addon_id';";
						$db->query($sql);
						if( !$db->affected_rows() )
						{
						    $uniadmin->error(sprintf($user->lang['sql_error_addons_delete'],$addon_id));
						}

					    $uniadmin->error($user->lang['sql_error_addons_files_insert']);
					    $uniadmin->cleardir($temp_folder);
					    return;
					}
				}

				// We have obtained the md5 and inserted the row into the database, now delete the temp file
				$try_unlink = @unlink($file);
				if( !$try_unlink )
				{
					$uniadmin->error(sprintf($user->lang['error_unlink'],$file));
				}
			}
		}

		// Now clear the temp folder
		$uniadmin->cleardir($temp_folder);

		$uniadmin->message(sprintf($user->lang['addon_uploaded'],$real_addon_name));
	}
	else // Nothing was uploaded
	{
		$uniadmin->message($user->lang['error_no_addon_uploaded']);
	}
}

/**
 * Gets the value from the .toc file
 *
 * @param string $file		File to parse
 * @param string $var		Variable to search for
 * @param string $def_val	Default Value
 * @return string
 */
function get_toc_val( $file, $var, $def_val )
{
	$lines = file($file);

	$matches = $str_matches = null;

	$val = $def_val;
	foreach( $lines as $line )
	{
		$found = preg_match('/## \\b'.$var.'\\b: (.+)/',$line,$matches);

		if( $found )
		{
			$val = $matches[1];

			switch ( $var )
			{
				case 'Version':
					$str_found = preg_match('/\$Revision:(.+?)\$/',$val,$str_matches);
					if( $str_found )
					{
						$val = $str_matches[1];
					}
					break;
			}

			$val = preg_replace('/(.+?)\\|c[a-f0-9]{8}(.+?)\\|r/i','\\1\\2',$val);
			$val = preg_replace('/\\|c[a-f0-9]{8}/i','',$val);
			$val = str_replace('|r','',$val);
		}
	}
	return trim($val);
}


/**
 * Convert a PHP array to a HTML list
 *
 * @param array $array		Array to convert
 * @param string $baseName	Top
 * @param string $string
 * @param bool $call
 */
function arrayToLi( $array, &$string, $baseName='', $call=false )
{
	// Write out the initial definition
	if( $call )
	{
		$open = array('interface','addons');
		if( in_array(strtolower($baseName),$open) )
		{
			$string .= ("<li>$baseName\n<ul rel=\"open\">\n");
		}
		else
		{
			$string .= ("<li>$baseName\n<ul>\n");
		}
	}

	//Reset the array loop pointer
	reset ($array);

	//Use list() and each() to loop over each key/value
	//pair of the array
	while (list($key, $value) = each($array))
	{
		if (is_array($value))
		{
			//The value is another array, so simply call
			//another instance of this function to handle it
			arrayToLi($value, $string, $key, true);
			if( $call )
			{
				$string .= "</ul></li>\n";
			}
		}
		else
		{
			//Output the value directly otherwise
			$string .= ("<li onmouseover=\"overlib('$value',LEFT,CAPTION,'MD5 - [$key]');\" onmouseout=\"return nd();\">$key</li>\n");
		}
	}
	if( !$call )
	{
		$string .= ("</ul>\n");
	}
}

/**
 * Adds a string to a directory tree array
 *
 * @param string $str	String to parse
 * @param string $md5	MD5 hash (optional)
 * @param array $array	Array to add elements to
 */
function addToList( $str , $md5 , &$array )
{
	$things = explode('\\', $str);

	if($things['0'] == '')
	{
		array_shift($things);
	}
	addToArray($things, $md5, $array);
}

/**
 * Part two of addToList()
 *
 * @param array $things		Array to convert
 * @param string $md5		MD5 hash
 * @param array $array		Array to add elements to
 */
function addToArray( $things , $md5 ,  &$array )
{
	$count = count($things);

	switch ($count)
	{
		case 1:
			$array[$things['0']] = $md5;
			break;

		case 2:
			$array[$things['0']][$things['1']] = $md5;
			break;

		case 3:
			$array[$things['0']][$things['1']][$things['2']] = $md5;
			break;

		case 4:
			$array[$things['0']][$things['1']][$things['2']][$things['3']] = $md5;
			break;

		case 5:
			$array[$things['0']][$things['1']][$things['2']][$things['3']][$things['4']] = $md5;
			break;

		case 6:
			$array[$things['0']][$things['1']][$things['2']][$things['3']][$things['4']][$things['5']] = $md5;
			break;

		case 7:
			$array[$things['0']][$things['1']][$things['2']][$things['3']][$things['4']][$things['5']][$things['6']] = $md5;
			break;

		case 8:
			$array[$things['0']][$things['1']][$things['2']][$things['3']][$things['4']][$things['5']][$things['6']][$things['7']] = $md5;
			break;

		case 9:
			$array[$things['0']][$things['1']][$things['2']][$things['3']][$things['4']][$things['5']][$things['6']][$things['7']][$things['8']] = $md5;
			break;

		case 10:
			$array[$things['0']][$things['1']][$things['2']][$things['3']][$things['4']][$things['5']][$things['6']][$things['7']][$things['8']][$things['9']] = $md5;
			break;

		case 11:
			$array[$things['0']][$things['1']][$things['2']][$things['3']][$things['4']][$things['5']][$things['6']][$things['7']][$things['8']][$things['9']][$things['10']] = $md5;
			break;

		default:
			break;
	}
}
