<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /inc/functions.inc
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id:$
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Common SigConfig Functions Class
 * @package SigGen
 */
class SigConfigClass
{
	var $message;
	var $sql_debug;


	/**
	 * Check directory status
	 *
	 * @param string $dir | directory to check
	 * @param bool $check | check writable
	 * @param bool $chmod | chmod directory
	 * @return bool | ( TRUE{dir exists} | FALSE{dir does not exist} )
	 */
	function checkDir( $dir , $check=0 , $chmod=0 )
	{
		// See if it exists
		if( file_exists($dir) )
		{
			if( $check )
			{
				if( !is_writable($dir) )
				{
					if( $chmod )
					{
						$chmod_check = @chmod( $dir,0777 );
						if( $chmod_check )
						{
							return true;
						}
						else
						{
							return false;
						}
					}
					else
					{
						return false;
					}
				}
			}
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Create a directory
	 *
	 * @param string $dir | name of the directory to create
	 * @return bool | ( TRUE{created} | FALSE{not created} )
	 */
	function makeDir( $dir )
	{
		$mkdir_check = @mkdir($dir);
		if( $mkdir_check )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * Set the SigGen Config Messages
	 *
	 * @param string $message | add string to the messages
	 */
	function setMessage( $message )
	{
		$this->message .= $message.'[|]';
	}


	/**
	 * Get the Messages
	 *
	 * @return string | Full HTML table with messages
	 */
	function getMessage()
	{
		global $roster_conf, $siggen_locale;

		$message = $this->message;

		if( !empty($message) )
		{
			// Replace newline feeds with <br />
			$messageArr = explode("[|]",$message);

			$output = '<table class="sc_table" cellspacing="0" cellpadding="2" width="100%">';
			$row=0;
			foreach( $messageArr as $line )
			{
				if( $line != '' )
				{
					$output .= '
  <tr>
    <td class="sc_row_right'.((($row++)%2)+1).'">'.$line.'</td>
  </tr>';
				}
			}

$output .= "\n</table>";

			return messagebox($output,$siggen_locale[$roster_conf['roster_lang']]['sql_messages'],'syellow');
		}
		else
		{
			return '';
		}
	}


	/**
	 * Set SQL Messages
	 *
	 * @param string | add string to the sql messages
	 */
	function setSqlDebug( $message )
	{
		$this->sql_debug .= "$message\n";
	}


	/**
	 * Get the SQL Messages
	 *
	 * @return string | Full HTML table with SQL statements
	 */
	function getSqlDebug()
	{
		global $roster_conf, $siggen_locale;

		$message = $this->sql_debug;

		if( !empty($message) )
		{
			// Colorize SQL string
			$message = sql_highlight($message);


			// Replace '<!-- ' with color tag
			$message = nl2br($message);

			$message = scrollboxtoggle($message,$siggen_locale[$roster_conf['roster_lang']]['sql_queries'],'sgray',false,'450px','200px');

			return $message;
		}
		else
		{
			return '';
		}
	}


	/**
	 * Create an HTML option list
	 *
	 * @param array $values    array ($name => $value)
	 * @param string $selected what will be selected
	 * @param string $id       what to match selected with
	 * @param string $type     how to arrange the value/label
	 *                         0- name value (LIST)
	 *                         1- value name (LISTREV)
	 *                         2- name name (NAME)
	 *                         3- value value (VALUE)
	 * @param string $param    additional crap for <select> field
	 * @return string          an html option list
	 */
	function createOptionList( $values , $selected , $id , $type='' , $param='' )
	{
		if( !empty($selected) )
		{
			$select_one = true;
		}

		$option_list = "\n  <select class=\"sc_select\" name=\"{$id}\" $param>\n    <option value=\"\">--None--</option>\n";

		foreach( $values as $Aname => $Avalue )
		{
			switch ($type)
			{
				case 0: // name value
				case 'list':
					$value = $Avalue;
					$name = $Aname;
					break;

				case 1: // value name
				case 'rev':
					$value = $Aname;
					$name = $Avalue;
					break;

				case 2: // name name
				case 'name':
					$value = $Aname;
					$name = $Aname;
					break;

				case 3: // value value
				case 'value':
					$value = $Avalue;
					$name = $Avalue;
					break;

				default:
					$value = $Avalue;
					$name = $Aname;
					break;
			}

			if( $selected == $value && $select_one )
			{
				$option_list .= "    <option value=\"{$value}\" selected=\"selected\">{$name}</option>\n";
				$select_one = false;
			}
			else
			{
				$option_list .= "    <option value=\"{$value}\">{$name}</option>\n";
			}
		}
		$option_list .= "  </select>";

		return $option_list;
	}


	/**
	 * Get a list of files from a directory
	 *
	 * @param string $dir | directory to get list of files
	 * @param mixed $ext | get files with this extention can be an array
	 * @return array | $file => $name
	 */
	function listFiles( $dir , $ext )
	{
		$arrFiles = array();

		// Open the directory
		$tmp_dir = @opendir( $dir );

		if( !empty($tmp_dir) )
		{
			// Read the files
			while( $file = readdir($tmp_dir) )
			{
				$pfad_info = pathinfo($file);

				if( is_array($ext) )
				{
					if( in_array(strtolower($pfad_info['extension']),$ext))
					{
						$name = str_replace('.'.$pfad_info['extension'],'',$file);
						$arrFiles += array($file => $name);
					}
				}
				else
				{
					if( strtolower($pfad_info['extension']) == strtolower($ext) )
					{
						$name = str_replace('.'.$pfad_info['extension'],'',$file);
						$arrFiles += array($file => $name);
					}
				}
			}
			// close the directory
			closedir($tmp_dir);

			//sort the list
			asort($arrFiles);
		}
		return $arrFiles;
	}


	/**
	 * Get a list of directories from a directory
	 *
	 * @param string $dir | directory to get list of directories
	 * @return array | $file => $name
	 */
	function listDir( $dir )
	{
		$arrFiles = array();

		// Open the directory
		$tmp_dir = @opendir( $dir );

		if( !empty($tmp_dir) )
		{
			// Read the files
			while( $file = readdir($tmp_dir) )
			{
				if( is_dir($dir.$file) && $file != '.' && $file != '..' )
				{
					$arrFiles += array($file => $file);
				}
			}
			// close the directory
			closedir($tmp_dir);

			//sort the list
			asort($arrFiles);
		}
		return $arrFiles;
	}


	/**
	 * Check if a table exists
	 *
	 * @param string $table | db table to check
	 * @return array ( $name => $value )
	 */
	function checkDb( $table )
	{
		global $wowdb;

		$sql_str = "SHOW TABLES LIKE '$table';";

		$this->setSqlDebug("checkDb: $sql_str");

		$result = $wowdb->query($sql_str);

		$r = $wowdb->fetch_assoc($result);

		if( empty($r) )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}


	/**
	 * Get Data from the database
	 *
	 * @param string $table | table to get data from
	 * @param string $field | field to get data from ( default *{all} )
	 * @param string $where | where statement ( default ''{blank} )
	 * @return array ( $name => $value )
	 */
	function getDbData( $table , $field , $where )
	{
		global $wowdb;

		if( !empty($table) )
		{
			if( !empty($where) )
				$where = 'WHERE '.$where;

			if( empty($field) )
				$field = '*';

			// SQL String
			$sql_str = "SELECT $field FROM `$table` $where;";

			$this->setSqlDebug("getDbData: $sql_str");

			$result = $wowdb->query($sql_str);

			if ( $result )
			{
				$data = $wowdb->fetch_assoc($result);
				return $data;
			}
		}
	}


	/**
	 * Get list of a field from the database
	 *
	 * @param string $table | table to get data from
	 * @param string $field | field to get data from ( default *{all} )
	 * @param string $where | where statement ( default ''{blank} )
	 * @return array ( $name => $value )
	 */
	function getDbList( $table , $field , $where )
	{
		global $wowdb;

		if( !empty($table) )
		{
			if( !empty($where) )
				$where = 'WHERE '.$where;

			if( empty($field) )
				$field = '*';

			// SQL String
			$sql_str = "SELECT $field FROM `$table` $where;";

			$this->setSqlDebug("getDbList: $sql_str");

			$result = $wowdb->query($sql_str);

			while( $r = $wowdb->fetch_assoc($result) )
			{
				$list[] = $r[trim($field,'`')];
			}
			return $list;
		}
	}


	/**
	 * Get a list of tables from a database
	 *
	 * @param string $table | name of db table
	 * @return array ( $name => $value )
	 */
	function getDbColumns( $table )
	{
		global $wowdb;

		if( !empty($table) )
		{
			// SQL String
			$sql_str = "SHOW COLUMNS FROM `$table`";

			$this->setSqlDebug("getDbColumns: $sql_str");

			$result = $wowdb->query($sql_str);

			while( $row = $wowdb->fetch_array($result) )
			{
				$col_names[] = $row[0];
			}
			return $col_names;
		}
	}


	/**
	 * Upload Images to a folder
	 *
	 * @param string $dir | directory to upload image to
	 * @param string $filename | filename of final uploaded image
	 * @return bool
	 */
	function uploadImage( $dir , $filename )
	{
		global $roster_conf, $siggen_locale;

		$accepted_files = array('png','gif','jpg','jpeg');

		$uploaded_filename = $_FILES['userfile']['name'];
		$uploaded_fileext = strtolower(ltrim(strrchr($uploaded_filename,'.'),'.'));

		$userfile_name = ( isset($_FILES['userfile']['name']) ? $filename.'.'.$uploaded_fileext : '' );

		if( isset($_FILES['userfile']) )
		{
			if( empty($_FILES['userfile']['name']) )
			{
				$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['select_image_upload']);
				return FALSE;
			}
			elseif( empty( $filename ) )
			{
				$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['select_name_upload']);
				return FALSE;
			}
			else
			{
				$userfile_name = $_POST['image_type'].$userfile_name;

				if( !in_array( $uploaded_fileext,$accepted_files ) )
				{
					$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['image_mustbe_right']);
					return FALSE;
				}

				if( file_exists($dir.$userfile_name) )
				{
					if( md5_file($dir.$userfile_name) == md5_file($_FILES['userfile']['tmp_name']) )
					{
						$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['image_a_copy']);
						return FALSE;
					}
				}

				if( !move_uploaded_file( $_FILES['userfile']['tmp_name'],$dir.$userfile_name ) || !chmod( $dir.$userfile_name,0777 ) )
				{
					$this->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['image_upload_failed'],$userfile_name,str_replace( '\\','/',$dir )));
					return FALSE;
				}
				else
				{
					$this->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['image_upload_success'],$userfile_name,str_replace( '\\','/',$dir )));
					return TRUE;
				}
			}
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * Delete an image from a folder
	 *
	 * @param string $dir | directory to delete image from
	 * @param string $filename | filename of image to delete
	 * @return bool
	 */
	function deleteImage( $dir , $filename )
	{
		global $roster_conf, $siggen_locale;

		if( empty($filename) )
		{
			$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['select_image_delete']);
			return FALSE;
		}
		else
		{
			if( file_exists($dir.$filename) )
			{
				if( unlink($dir.$filename) )
				{
					$this->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['image_deleted'],str_replace( '\\','/',$dir ).$filename));
					return TRUE;
				}
				else
				{
					$this->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['image_delete_failed'],str_replace( '\\','/',$dir ).$filename));
					return FALSE;
				}
			}
		}
	}


	/**
	 * Process Data for entry to the database
	 *
	 * @param array $post | $_POST array
	 * @param string $config_name | db id to process
	 */
	function processData( $post , $config_name, $data )
	{
		global $wowdb, $roster_conf, $siggen_locale;

		$configData = $data;

		$wowdb->reset_values();

		// Array for color fixing
		$color_fix = array(
			'backg_fill_color',
			'expbar_color_border',
			'expbar_color_inside',
			'expbar_color_bar',
			'expbar_color_maxbar',
			'expbar_font_color',
			'expbar_text_shadow',
			'lvl_font_color',
			'lvl_text_shadow',
			'skills_font_color',
			'skills_shadow',
			'text_name_font_color',
			'text_name_shadow',
			'text_class_font_color',
			'text_class_shadow',
			'text_honor_font_color',
			'text_honor_shadow',
			'text_guildname_font_color',
			'text_guildname_shadow',
			'text_guildtitle_font_color',
			'text_guildtitle_shadow',
			'text_servername_font_color',
			'text_servername_shadow',
			'text_sitename_font_color',
			'text_sitename_shadow',
			'text_custom_font_color',
			'text_custom_shadow',
		);
		// Update only the changed fields
		foreach( $post as $settingName => $settingValue )
		{
			// Fix colors
			if( in_array($settingName,$color_fix) && $settingValue != '' )
			{
				if( substr($settingValue, 0, 1) != '#' )
				{
					$settingValue = '#'.strtoupper($settingValue);
				}
				else
				{
					$settingValue = strtoupper($settingValue);
				}
			}

			// Fix directories
			if( substr($settingName, -4, 4) == '_dir' )
			{
				// Replace slashes
				$settingValue = str_replace( '\\\\','/',$settingValue );
				$settingValue = str_replace( '\\','/',$settingValue );

				// Check for directories defined with no '/' at the end
				// and with a '/' at the beginning
				if( substr($settingValue, -1, 1) != '/' )
					$settingValue .= '/';
				if( substr($settingValue, 0, 1) == '/' )
					$settingValue = substr($settingValue, 1);
			}

			if( $settingValue != $configData[$settingName] && $settingName != 'sc_op' )
				$wowdb->add_value($settingName, $settingValue );
		}

		// Update DataBase
		if( !empty($wowdb->assignstr) )
		{
			$update_sql = 'UPDATE `'.ROSTER_SIGCONFIGTABLE."` SET ".$wowdb->assignstr." WHERE `config_id` = '".$config_name."'";
			$this->setSqlDebug("processData: $update_sql");

			$wowdb->query($update_sql)
				or errorMode( 'processData: '.$wowdb->error() );

			$this->setMessage( $siggen_locale[$roster_conf['roster_lang']]['settings_changed'] );
		}
	}


	/**
	 * Resets the config to defaults
	 *
	 * @param bool $confirm | Confirmation to reset
	 * @param sting $config_id | db id to process
	 */
	function resetDefaults( $confirm , $config_id )
	{
		global $wowdb, $roster_conf, $siggen_locale;

		$sql_file = SIGGEN_DIR.'inc/sql.inc';

		if( $confirm )
		{
			if( file_exists($sql_file) )
			{
				include_once( $sql_file );

				$sql = $create_sql['reset'][$config_id];

				$this->setSqlDebug("resetDefaults: $sql");

				$wowdb->query($sql)
					or errorMode( 'resetDefaults: '.$wowdb->error() );

				$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['settings_reset']);
			}
			else
			{
				$this->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_file'],$reset_file));
			}
		}
		else
		{
			$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['reset_checkbox']);
		}
	}



	/**
	 * Grabs settings from a file, exported from SigGen
	 *
	 * @param string  Config ID to import
	 * @return bool   Success or not
	 */
	function importSettings( $checkData , $config_id )
	{
		global $roster_conf, $siggen_locale, $sc_db_ver;

		$accepted_file = "siggen_$config_id.txt";

		$uploaded_filename = $_FILES['userfile']['name'];
		$temp_file = $_FILES['userfile']['tmp_name'];

		$ignore = array('config_id','db_ver');

		if( isset($_FILES['userfile']) )
		{
			if( empty($_FILES['userfile']['name']) )
			{
				$this->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['select_import_file'],$accepted_file));
				return FALSE;
			}
			else
			{
				if( $uploaded_fileext != $accepted_files )
				{
					$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['import_mustbe_right']);
					return false;
				}

				if( file_exists($temp_file) && is_readable($temp_file) )
				{
					$file_as_array = file($temp_file);

					$firstline = true;
					$settingsArray = array();
					foreach( $file_as_array as $file_line )
					{
						$file_line = trim($file_line);
						list($value,$setting) = explode('|',$file_line);

						if( $firstline )
						{
							if( $sc_db_ver != $value )
							{
								$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['import_upload_failed_ver'],$value,$sc_db_ver);
								return false;
							}
							$firstline = false;
						}
						elseif( !in_array($value,$ignore) )
						{
							$settingsArray[$value] = $setting;
						}
					}
					$this->processData($settingsArray,$config_id,$checkData);

					$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['import_upload_success']);

					return true;
				}
				else
				{
					$this->setMessage($siggen_locale[$roster_conf['roster_lang']]['import_upload_failed']);
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}



	/**
	 * Grabs settings from SigGen, exports them to a file
	 *
	 * @param array  $checkData    SigGen Data
	 * @param string $config_id    Current config
	 */
	function exportSettings( $checkData , $config_id )
	{
		global $sc_db_ver;

		$file = "$sc_db_ver|$config_id\n";

		foreach( $checkData as $name => $value )
		{
			$file .= "$name|$value\n";
		}

		header('Content-Type: text/x-delimtext; name="siggen_'.$config_id.'.txt"');
		header('Content-disposition: attachment; filename="siggen_'.$config_id.'.txt"');

		// We need to stripslashes no matter what the setting of magic_quotes_gpc is
		echo stripslashes($file);

		exit;
	}



	/**
	 * Creates or Upgrades the sig_config table
	 *
	 * @param string $mode | install/upgrade
	 * @return string
	 */
	function installDb( $mode )
	{
		global $wowdb, $sc_db_ver, $roster_conf, $siggen_locale, $script_filename;

		$sql_file = SIGGEN_DIR.'inc/sql.inc';

		if( file_exists($sql_file) )
		{
			$sql = array();
			switch( $mode )
			{
				case 'upgrade':
					include_once( $sql_file );

					$reset = $_GET['reset'];
					$ver = $_GET['ver'];

					if( is_array($create_sql['upgrade_'.$ver]) )
					{
						foreach( $create_sql['upgrade_'.$ver] as $upg_sql )
							$sql[] = $upg_sql;
					}
					else
					{
						$sql[] = $create_sql['upgrade_'.$ver];
					}

					if( $reset == '1' )
					{
						$sql[] = $create_sql['reset']['avatar'];
						$sql[] = $create_sql['reset']['signature'];
					}

					break;

				case 'install':
					include_once( $sql_file );

					foreach( $create_sql['install'] as $inst_sql )
						$sql[] = $inst_sql;

					$sql[] = $create_sql['insert']['avatar'];
					$sql[] = $create_sql['insert']['signature'];

					break;
			}

			foreach( $sql as $sql_load )
			{
				$this->setSqlDebug("installDb[$mode]: $sql_load");

				$wowdb->query($sql_load)
					or errorMode( 'installDb: '.$wowdb->error() );
			}
			return sprintf($siggen_locale[$roster_conf['roster_lang']]['install_success'],ucwords($mode),$script_filename);
		}
		else
		{
			return sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_file'],$sql_file);
		}
	}


	/**
	 * Create a tooltip
	 *
	 * @param string $content | Content in tooltip
	 * @param string $caption | Text in the caption
	 * @return string ( Overlib styled tooltip )
	 */
	function createTip( $content , $caption )
	{
		$tipsettings = ",WRAP";

		if( !empty($caption) )
			$caption2 = ",CAPTION,'$caption'";

		$tip = "<div style=\"cursor:help;\" onmouseover=\"overlib('$content'$caption2$tipsettings);\" onmouseout=\"return nd();\">$caption</div>";

		return $tip;
	}


	/**
	 * Create a tooltip for colors
	 *
	 * @param string $selected | currently selected color
	 * @param array $configData | Data from the database
	 * @return string ( Overlib styled tooltip )
	 */
	function createColorTip( $selected,$configData )
	{

		$tipsettings = ",RIGHT,WRAP";


		for($n=1;$n<=10;$n++)
		{
			if( $selected == ('color'.$n) )
				$current = '<span class=&quot;red&quot;>&nbsp;&nbsp;&lt;&lt;&lt;&lt;&lt;</span>';
			else
				$current = '';

			$content .= '<tr><td>[<span style=&quot;font-size:10px; color:#FFFFFF; background-color:'.$configData['color'.$n].';&quot;>&nbsp;'.$n.'&nbsp;</span>]</td><td>'.$configData['color'.$n].'</td><td>'.$current.'</td></tr>';
		}

		$content = "<table class=&quot;sc_table&quot; cellspacing=&quot;0&quot;><tr><td colspan=&quot;2&quot;><strong>Available</strong></td><td><strong>Selected</strong></td></tr>$content";

		$tip = "[<span style=\"cursor:help; font-size:10px; color:#FFFFFF; background-color:".$configData[$selected].";\" onMouseover=\"return overlib('$content',CAPTION,'Color Menu'$tipsettings);\" onMouseout=\"return nd();\">colors</span>]";

		return $tip;
	}


	/**
	 * Creates a table for viewing image packs
	 *
	 * @param string $dir
	 */
	function imagePreview($dir)
	{
		global $roster_config;

		$return = '<table cellpadding="0" cellspacing="0" style="font-size:10px;">';

		$dirname = '.';
		$pattern = "(\.png$)"; // valid image extensions
		$columns = 2;
		$drawIndex = 0;

		if($handle = opendir($dirname))
		{
			while(false !== ($file = readdir($handle)))
			{
				if(eregi($pattern, $file)) //if this file is a valid image
				{
					if($drawIndex == 0)
					{
						$return .= '<tr>';
					}

					$return .= '<td align="center"><img src="'.$roster_config['roster_dir'].'siggen/img/'.'/'.$file.'" style="border:0;"><br />'.$file.'</td>';
					$drawIndex++;

					if($drawIndex == $columns)
					{
						$return .= '</tr>';
						$drawIndex = 0;
					}
				}
			}

			if($drawIndex !== 0)
			{
				$finishDraw = $columns - $drawIndex;
				while($finishDraw !== 0)
				{
					$return .= '<td>&nbsp;</td>';
					$finishDraw--;
				}
				$return .= '</tr>';
			}
		}
		closedir($handle);

		$return .= '</table>';
	}

}
