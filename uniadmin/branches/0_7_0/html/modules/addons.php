<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

$id = ( isset($_POST[UA_URI_ID]) ? $_POST[UA_URI_ID] : '' );

// Decide What To Do
switch($op)
{
	case UA_URI_PROCESS:
		processAddon();
		break;

	case UA_URI_DELETE:
		deleteAddon($id);
		break;

	case UA_URI_REQ:
		requireAddon($id);
		break;

	case UA_URI_OPT:
		optionalAddon($id);
		break;

	case UA_URI_DISABLE:
		disableAddon($id);
		break;

	case UA_URI_ENABLE:
		enableAddon($id);
		break;

	default:
		break;
}
main();

$db->close_db();






/**
 * Addon Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $uniadmin, $user;

	$addonInputForm = '
<form name="ua_updateaddon" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<table class="uuTABLE" align="center">
		<tr>
			<th class="tableHeader" colspan="2">'.$user->lang['add_update_addon'].'</th>
		</tr>
		<tr>
			<td class="data1">'.$user->lang['required_addon'].':</td>
			<td class="data1"><input type="checkbox" name="required" value="1" checked="checked" /></td>
		</tr>
		<tr>
			<td class="data2">'.$user->lang['select_file'].':</td>
			<td class="data2"><input class="file" type="file" name="file" /></td>
		</tr>
		<tr>
			<td class="data1">'.$user->lang['version'].':</td>
			<td class="data1"><input class="input" type="textbox" name="version" /></td>
		</tr>
		<tr>
			<td class="data2">'.$user->lang['homepage'].':</td>
			<td class="data2"><input class="input" type="textbox" name="homepage" /></td>
		</tr>
		<tr>
			<td class="dataHeader" colspan="2" align="center"><input class="submit" type="submit" value="'.$user->lang['add_update_addon'].'" /></td>
		</tr>
	</table>
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_PROCESS.'" />
</form>
';

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
				<td class="dataHeader">'.$user->lang['delete'].'</td>
			</tr>';

		while( $row = $db->fetch_record($result) )
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

			if( $row['enabled'] == '1' )
			{
				$enabled = '<form name="ua_disableaddon_'.$addonID.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DISABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addonID.'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['yes'].'" />
</form>';
			}
			else
			{
				$enabled = '<form name="ua_enableaddon_'.$addonID.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_ENABLE.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addonID.'" />
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['no'].'" />
</form>';
			}

			if( $row['homepage'] == '' )
			{
				$homepage = './';
			}

			if( $row['required'] == 1 )
			{
				$required = '<form name="ua_optionaladdon_'.$addonID.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_OPT.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addonID.'" />
	<input class="submit" style="color:red;" type="submit" value="'.$user->lang['yes'].'" />
</form>';
			}
			else
			{
				$required = '<form name="ua_requireaddon_'.$addonID.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
	<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_REQ.'" />
	<input type="hidden" name="'.UA_URI_ID.'" value="'.$addonID.'" />
	<input class="submit" style="color:green;" type="submit" value="'.$user->lang['no'].'" />
</form>';
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
			<td class="'.$tdClass.'"><form name="ua_deleteaddon_'.$addonID.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
				<input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_DELETE.'" />
				<input type="hidden" name="'.UA_URI_ID.'" value="'.$addonID.'" />
				<input class="submit" style="color:red;" type="submit" value="'.$user->lang['delete'].'" />
				</form></td>
		</tr>
';
		}
	}
	else
	{
		$AddonPanel = '
		<table class="uuTABLE" align="center">
			<tr>
				<th class="tableHeader">'.$user->lang['addon_management'].'</th>
			</tr>
			<tr>
				<th class="dataHeader">'.$user->lang['error_no_addon_in_db'].'</th>
			</tr>';
	}
	$AddonPanel .= '</table>';

	$db->free_result($result);


	echoPage(
		$AddonPanel
		.'<br />'.
		$addonInputForm,$user->lang['title_addons']);
}

/**
 * Disables an addon
 *
 * @param int $id
 */
function disableAddon( $id )
{
	global $db, $user;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `enabled` = '0' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    debug($user->lang['error_disable_addon']);
		debug(sprintf($user->lang['sql_error_addons_disable'],$id));
	}
}

/**
 * Enables an addon
 *
 * @param int $id
 */
function enableAddon( $id )
{
	global $db, $user;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `enabled` = '1' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    debug($user->lang['error_enable_addon']);
		debug(sprintf($user->lang['sql_error_addons_enable'],$id));
	}
}

/**
 * Sets an addon to required
 *
 * @param int $id
 */
function requireAddon( $id )
{
	global $db, $user;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `required` = '1' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    debug($user->lang['error_requre_addon']);
		debug(sprintf($user->lang['sql_error_addons_requre'],$id));
	}
}

/**
 * Sets an addon to optional
 *
 * @param int $id
 */
function optionalAddon( $id )
{
	global $db, $user;

	$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `required` = '0' WHERE `id` = '$id' LIMIT 1 ;";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    debug($user->lang['error_optional_addon']);
		debug(sprintf($user->lang['sql_error_addons_optional'],$id));
	}
}

/**
 * Deletes an addon from the server and the database
 *
 * @param int $id
 */
function deleteAddon( $id )
{
	global $db, $user, $uniadmin;

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$id'";
	$result = $db->query($sql);
	$row = $db->fetch_record($result);

	$id = $row['id'];
	$AddonUrl = $row['dl_url'];
	$k = explode('/',$AddonUrl);
	$fileName = $k[count($k) - 1];

	$LocalPath = UA_BASEDIR.$uniadmin->config['addon_folder'].DIR_SEP.$fileName;
	$try_unlink = @unlink($LocalPath);
	if( !$try_unlink )
	{
		debug($user->lang['error_delete_addon']);
		debug(sprintf($user->lang['error_unlink'],$LocalPath));
	}

	$sql = "DELETE FROM `".UA_TABLE_ADDONS."` WHERE `id` = '$id'";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    debug(sprintf($user->lang['sql_error_addons_delete'],$id));
	}

	$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '$id';";
	$db->query($sql);
	if( !$db->affected_rows() )
	{
	    debug(sprintf($user->lang['sql_error_addons_delete'],$id));
	}
}

/**
 * Unzips an addon zip file for analysis
 *
 * @param string $file
 * @param string $path
 * @param bool $mode
 */
function unzip( $file , $path )
{
	global $user;

	require_once(UA_INCLUDEDIR.'pclzip.lib.php');

	$archive = new PclZip($file);
	$list = $archive->extract(PCLZIP_OPT_PATH, $path); //removed PCLZIP_OPT_REMOVE_ALL_PATH to preserve file structure
	if ($list == 0)
	{
		$try_unlink = @unlink($file);
		if( !$try_unlink )
		{
			debug(sprintf($user->lang['error_unlink'],$file));
		}
		debug( sprintf( $user->lang['error_pclzip'],$archive->errorInfo(true) ) );
		die_ua();
	}
	unset($archive);
}

/**
 * Lists the contents of a directory
 *
 * @param string $dir
 * @param array $array
 * @return array
 */
function ls( $dir , $array )
{
	$handle = opendir($dir);
	for(;(false !== ($readdir = readdir($handle)));)
	{
		if( $readdir != '.' && $readdir != '..' && $readdir != 'index.htm' && $readdir != 'index.html' && $readdir != '.svn' )
		{
			$path = $dir.DIR_SEP.$readdir;
			if( is_dir($path) )
			{
				$array = ls($path, $array);
			}
			if( is_file($path) )
			{
				$array[count($array)] = $path;
			}
		}
	}
	closedir($handle);
	return $array;
}

/**
 * Processess an uploaded addon for insertion into the database
 */
function processAddon()
{
	global $db, $user, $uniadmin;

	$tempFilename = $_FILES['file']['tmp_name'];

	if( !empty($tempFilename) )
	{
		$url = $uniadmin->url_path;
		$fileName = str_replace(' ','_',$_FILES['file']['name']);

		$addonFolder = UA_BASEDIR.$uniadmin->config['addon_folder'];
		$tempFolder = UA_BASEDIR.$uniadmin->config['temp_analyze_folder'];

		$version = $_POST['version'];
		$addonName = substr($fileName,0,count($fileName) -5);
		$homepage = $_POST['homepage'];

		if( isset($_POST['required']) && $_POST['required'] == '1' )
		{
			$required = 1;
		}
		else
		{
			$required = 0;
		}

		// Set Download URL
		$downloadLocation = $url.$uniadmin->config['addon_folder'].'/'.$fileName;

		// Name and location of the zip file
		$zipfile = $addonFolder.DIR_SEP.$fileName;

		// Delete Addon if it exists
		@unlink($zipfile);

		// Try to move to the addon_temp directory
		$try_move = move_uploaded_file($tempFilename,$zipfile);
		if( !$try_move )
		{
			debug(sprintf($user->lang['error_move_uploaded_file'],$tempFilename,$zipfile));
			return;
		}

		// Try to set write access on the uploaded file
		$try_chmod = @chmod($zipfile,0777);
		if( !$try_chmod )
		{
			debug(sprintf($user->lang['error_chmod'],$zipfile));
			return;
		}

		// Unzip the file
		unzip($zipfile,$tempFolder.DIR_SEP);

		$files = ls($tempFolder,array());


		// Get the TOC of the addon
		$tocFileName = '';
		if( is_array($files) )
		{
			foreach( $files as $file )
			{
				if( getFileExtention($file) == 'toc' )
				{
					$toc = getToc($file);

					$k = explode(DIR_SEP,$file);
					$tocFileName = $k[count($k) - 1];
					$trueAddonName = substr($tocFileName,0,count($tocFileName) -5);
					break;
				}
			}
			if( empty($tocFileName) )
			{
				$try_unlink = @unlink($zipfile);
				if( !$try_unlink )
				{
					debug(sprintf($user->lang['error_unlink'],$zipfile));
				}
				cleardir($tempFolder);
				debug($user->lang['error_no_toc_file']);
			}
		}
		else
		{
			$try_unlink = @unlink($zipfile);
			if( !$try_unlink )
			{
				debug(sprintf($user->lang['error_unlink'],$zipfile));
			}
			cleardir($tempFolder);
			debug($user->lang['error_no_files_addon']);
			return;
		}

		// See if AddOn exists in the database and do stuff to it
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `name` = '".$db->escape($trueAddonName)."';";
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
			if( $version == '' )
			{
				$version = $row['version'];
			}

			$enabled = $row['enabled'];

			// Remove files from database since we'll be updating them all
			$sql = "DELETE FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '".$addon_id."';";
			$db->query($sql);

			// Update Main Addon data
			$sql = "UPDATE `".UA_TABLE_ADDONS."` SET `time_uploaded` = '".time()."', `version` = '".$db->escape($version)."', `enabled` = '$enabled', `name` = '".$db->escape($trueAddonName)."', `dl_url` = '".$db->escape($downloadLocation)."', `homepage` = '".$db->escape($homepage)."', `toc` = '$toc', `required` = '$required'
				WHERE `id` = '".$addon_id."';";
			$db->query($sql);
		}
		else
		{
			// Insert Main Addon data
			$sql = "INSERT INTO `".UA_TABLE_ADDONS."` ( `time_uploaded` , `version` , `enabled` , `name`, `dl_url`, `homepage`, `toc`, `required` )
				VALUES ( '".time()."', '".$db->escape($version)."', '1', '".$db->escape($trueAddonName)."', '".$db->escape($downloadLocation)."', '".$db->escape($homepage)."', $toc, $required);";
			$db->query($sql);

			// Get the insert id of the addon just inserted
			$addon_id = $db->insert_id();
		}

		if( !$db->affected_rows() )
		{
		    debug($user->lang['sql_error_addons_insert']);
		    cleardir($tempFolder);
		    return;
		}

		// Insert Addon Files' Data
		foreach( $files as $file )
		{
			$md5 = md5_file($file);
			$k = explode(DIR_SEP,$file);
			$pos_t = strpos($file,'addon_temp');
			$fileName = substr($file,$pos_t + 10);

			if( $fileName != 'index.htm' && $fileName != 'index.html' && $fileName != '.svn' )
			{
				$sql = "INSERT INTO `".UA_TABLE_FILES."` ( `addon_id` , `filename` , `md5sum` )
					VALUES ( '".$addon_id."', '".$db->escape($fileName)."', '".$db->escape($md5)."' );";
				$db->query($sql);
				if( !$db->affected_rows() )
				{
				    debug($user->lang['sql_error_addons_files_insert']);
				    cleardir($tempFolder);
				    return;
				}

				// We have obtained the md5 and inserted the row into the database, now delete the temp file
				$try_unlink = @unlink($file);
				if( !$try_unlink )
				{
					debug(sprintf($user->lang['error_unlink'],$file));
				}
			}
		}

		// Now clear the temp folder
		cleardir($tempFolder);
	}
	else // Nothing was uploaded
	{
		message($user->lang['error_no_addon_uploaded']);
	}
}

/**
 * Removes a file or directory
 *
 * @param string $dir
 * @return bool
 */
function rmdirr( $dir )
{
	if( is_dir($dir) && !is_link($dir) )
	{
		return ( cleardir($dir) ? rmdir($dir) : false );
	}
	return unlink($dir);
}

/**
 * Clears a directory of files
 *
 * @param string $dir
 * @return bool
 */
function cleardir( $dir )
{
	if( !($dir = dir($dir)) )
	{
		return false;
	}
	while( false !== $item = $dir->read() )
	{
		if( $item != '.' && $item != '..' && $item != '.svn' && $item != 'index.html' && $item != 'index.htm' && !rmdirr($dir->path . DIR_SEP . $item) )
		{
			$dir->close();
			return false;
		}
	}
	$dir->close();
	return true;
}

/**
 * Gets the toc version number from the .toc file
 *
 * @param string $file
 * @return unknown
 */
function getToc( $file )
{
	$lines = file($file);

	$toc = '00000';
	foreach( $lines as $line )
	{
		$IntPos = strpos(strtoupper($line),strtoupper('Interface:'));
		if( $IntPos !== false )
		{
			$toc = substr($line, $IntPos + 10);
		}
	}
	return $toc;
}

/**
 * Figures out what the file's extention is
 *
 * @param string $filename
 * @return string
 */
function getFileExtention( $filename )
{
	return strtolower(ltrim(strrchr($filename,'.'),'.'));
}


?>