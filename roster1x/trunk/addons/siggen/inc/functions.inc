<?php
/*******************************
 * $Id$
 *******************************/

/*
Common SigConfig Functions Class
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
						if( chmod( $dir,0777 ) )
							return TRUE;
						else
							return FALSE;
					}
					else
						return FALSE;
				}
			}
			return TRUE;
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
		if( mkdir($dir) )
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
		$this->message .= "$message\n";
	}


	/**
	 * Get the Messages
	 *
	 * @return string | Full HTML table with messages
	 */
	function getMessage()
	{
		$message = $this->message;

		if( !empty($message) )
		{
			// Replace newline feeds with <br />, then newline
			$messageArr = explode("\n",$message);

			$output = '
<!-- Begin Message Box -->
'.border('syellow','start','SigGen Messages').'
<table class="sc_table" cellspacing="0" cellpadding="2">';
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

$output .= "\n</table>\n<!-- End Message Box -->\n".border('syellow','end');

			return $output;
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
		global $db_prefix, $roster_conf;

		$message = $this->sql_debug;

		if( !empty($message) )
		{
			// Colorize SQL string
			$message = sql_highlight($message);


			// Replace '<!-- ' with color tag
			$message = nl2br($message);

			$message = '
<!-- Begin SQL Debug Box -->
<div id="sqlDebugCol" style="display:inline">
'.border('sgray','start',"<div style=\"cursor:pointer;width:400px;\" onclick=\"swapShow('sqlDebugCol','sqlDebug')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />SigGen SQL Queries</div>").'
'.border('sgray','end').'
</div>
<div id="sqlDebug" style="display:none">
'.border('sgreen','start',"<div style=\"cursor:pointer;width:100%;\" onclick=\"swapShow('sqlDebugCol','sqlDebug')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />SigGen SQL Queries</div>").'
  <table width="100%" class="sc_table" cellspacing="1" cellpadding="2">
    <tr class="sc_row1">
      <td>'.$message.'</td>
    </tr>
  </table>
'.border('sgreen','end').'
</div>
<!-- End SQL Debug Box -->';

			return $message;
		}
		else
		{
			return '';
		}
	}


	/**
	 * Create an HTML option list (value-name)
	 *
	 * @param array $values | array with $values
	 * @param string $selected | what will be selected
	 * @param string $id | what to match selected with
	 * @return string | an html option list
	 */
	function createOptionList( $values , $selected , $id )
	{
		if( !empty($selected) )
			$select_one = 1;

		$option_list = "\n  <select class=\"sc_select\" name=\"{$id}\">\n    <option value=\"\">--None--</option>\n";

		foreach( $values as $name => $value )
		{
			if( $selected == $value && $select_one )
			{
				$option_list .= "    <option value=\"{$value}\" selected=\"selected\">{$name}</option>\n";
				$select_one = 0;
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
	 * Create an HTML option list (name-value)
	 *
	 * @param array $values | array with $values
	 * @param string $selected | what will be selected
	 * @param string $id | what to match selected with
	 * @return string | an html option list
	 */
	function createOptionListRev( $values , $selected , $id )
	{
		if( !empty($selected) )
			$select_one = 1;

		$option_list = "\n  <select class=\"sc_select\" name=\"{$id}\">\n    <option value=\"\">--None--</option>\n";

		foreach( $values as $name => $value )
		{
			if( $selected == $name && $select_one )
			{
				$option_list .= "    <option value=\"{$name}\" selected=\"selected\">{$value}</option>\n";
				$select_one = 0;
			}
			else
			{
				$option_list .= "    <option value=\"{$name}\">{$value}</option>\n";
			}
		}
		$option_list .= "  </select>";

		return $option_list;
	}


	/**
	 * Create an HTML option list (name-name)
	 *
	 * @param array $values | array with $values
	 * @param string $selected | what will be selected
	 * @param string $id | what to match selected with
	 * @return string | an html option list
	 */
	function createOptionListName( $values , $selected , $id )
	{
		if( !empty($selected) )
			$select_one = 1;

		$option_list = "\n  <select class=\"sc_select\" name=\"{$id}\">\n    <option value=\"\">--None--</option>\n";

		foreach( $values as $name => $value )
		{
			if( $selected == $name && $select_one )
			{
				$option_list .= "    <option value=\"{$name}\" selected=\"selected\">{$name}</option>\n";
				$select_one = 0;
			}
			else
			{
				$option_list .= "    <option value=\"{$name}\">{$name}</option>\n";
			}
		}
		$option_list .= "  </select>";

		return $option_list;
	}


	/**
	 * Create an HTML option list (value-value)
	 *
	 * @param array $values | array with $values
	 * @param string $selected | what will be selected
	 * @param string $id | what to match selected with
	 * @return string | an html option list
	 */
	function createOptionListValue( $values , $selected , $id )
	{
		if( !empty($selected) )
			$select_one = 1;

		$option_list = "\n  <select class=\"sc_select\" name=\"{$id}\">\n    <option value=\"\">--None--</option>\n";

		foreach( $values as $name => $value )
		{
			if( $selected == $value && $select_one )
			{
				$option_list .= "    <option value=\"{$value}\" selected=\"selected\">{$value}</option>\n";
				$select_one = 0;
			}
			else
			{
				$option_list .= "    <option value=\"{$value}\">{$value}</option>\n";
			}
		}
		$option_list .= "  </select>";

		return $option_list;
	}


	/**
	 * Get a list of files from a directory
	 *
	 * @param string $dir | directory to get list of files
	 * @param string $ext | get files with this extention
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

				if( strtolower($pfad_info['extension']) == strtolower($ext) )
				{
					$name = str_replace('.'.$pfad_info['extension'],'',$file);
					$arrFiles += array($name => $file);
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
		$userfile_name = ( isset($_FILES['userfile']['name']) ? $filename.'.png' : '' );

		if( isset($_FILES['userfile']) )
		{
			if( empty($_FILES['userfile']['name']) )
			{
				$this->setMessage('Please select an image to upload');
				return FALSE;
			}
			elseif( empty( $filename ) )
			{
				$this->setMessage('Please select a name before uploading an image');
				return FALSE;
			}
			else
			{
				$userfile_name = $_POST['image_type'].$userfile_name;

				if( strcasecmp( substr($_FILES['userfile']['name'],-4),'.png' ) )
				{
					$this->setMessage('The image must be .png, 24-bit color');
					return FALSE;
				}

				if( file_exists($dir.$userfile_name) )
				{
					if( md5_file($dir.$userfile_name) == md5_file($_FILES['userfile']['tmp_name']) )
					{
						$this->setMessage('You are uploading an exact copy of an existing image');
						return FALSE;
					}
				}

				if( !move_uploaded_file( $_FILES['userfile']['tmp_name'],$dir.$userfile_name ) || !chmod( $dir.$userfile_name,0777 ) )
				{
					$this->setMessage("Upload of [<span class=\"purple\">$userfile_name</span>] failed<br />You might not have write access to [<span class=\"green\">".str_replace( '\\','/',$dir ).'</span>]');
					return FALSE;
				}
				else
				{
					$this->setMessage("Upload of [<span class=\"purple\">$userfile_name</span>] to [<span class=\"green\">".str_replace( '\\','/',$dir ).'</span>] successful');
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
		if( empty($filename) )
		{
			$this->setMessage( 'Please select an image to delete' );
			return FALSE;
		}
		else
		{
			if( file_exists($dir.$filename) )
			{
				if( unlink($dir.$filename) )
				{
					$this->setMessage( '<span class="green">'.str_replace( '\\','/',$dir ).$filename.'</span>: <span class="red">Deleted</span>' );
					return TRUE;
				}
				else
				{
					$this->setMessage( str_replace( '\\','/',$dir ).$filename.': Could not be deleted. I don&acute;t know why' );
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
		global $wowdb;

		$configData = $data;

		$wowdb->reset_values();

		// Update only the changed fields
		foreach( $post as $settingName => $settingValue )
		{
			// Fix colors
			if( substr($settingName, 0, 5) == 'color' && substr($settingValue, 0, 1) != '#' )
				$settingValue = '#'.$settingValue;

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
				or exit( 'processData: '.$wowdb->error() );

			$this->setMessage( 'Settings have been changed' );
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
		global $wowdb, $sigconfig_dir;

		$sql_file = $sigconfig_dir.'inc/sql.inc';

		if( $confirm )
		{
			if( file_exists($sql_file) )
			{
				include_once( $sql_file );

				$sql = $create_sql['reset'][$config_id];

				$this->setSqlDebug("resetDefaults: $sql");

				$wowdb->query($sql)
					or exit( 'resetDefaults: '.$wowdb->error() );

				$this->setMessage('Settings have been restored to defaults');
			}
			else
			{
				$this->setMessage("Cannot find reset file [$reset_file]");
			}
		}
		else
		{
			$this->setMessage('To reset, make sure you check the &quot;Check to confirm reset&quot; checkbox');
		}
	}



	/**
	 * Creates or Upgrades the sig_config table
	 *
	 * @param string $mode | install/upgrade
	 * @return string
	 */
	function installDb( $mode )
	{
		global $wowdb, $sigconfig_dir, $sc_db_ver;

		$sql_file = $sigconfig_dir.'inc/sql.inc';

		if( file_exists($sql_file) )
		{
			switch( $mode )
			{
				case 'upgrade':
					include_once( $sql_file );

					$reset = $_REQUEST['reset'];

					$sql[0] = $create_sql['upgrade'];

					if( $reset == 'reset' )
					{
						$sql[1] = $create_sql['reset']['avatar'];
						$sql[2] = $create_sql['reset']['signature'];
					}
					else
					{
						$sql[1] = $create_sql['upgrade_dbver'];
					}

					break;

				case 'install':
					include_once( $sql_file );

					$sql[0] = $create_sql['install'];
					$sql[1] = $create_sql['insert']['avatar'];
					$sql[2] = $create_sql['insert']['signature'];

					break;
			}

			foreach( $sql as $sql_load )
			{
				$this->setSqlDebug("installDb[$mode]: $sql_load");

				$wowdb->query($sql_load)
					or exit( 'installDb: '.$wowdb->error() );
			}
			return ucwords($mode)." was successfull...\nClick <a href=\"addon.php?roster_addon_name=siggen\">HERE</a> to continue\n";
		}
		else
		{
			return "Cannot find sql file [$sql_file]";
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


}
