<?php
/*
* $Date: 2006/01/30 07:20:34 $
* $Revision: 1.10 $
* $Author: zanix $
*/


/*
Common SigConfig Functions Class

*/
class SigConfigClass
{
	var $message;
	var $sql_debug;


/* ----[ Get the realpath for a directory ]-----------------
return
	string ( realpath of argument )
arguments
	$dir = directory to get realpath
*/
	function getRealPath( $dir )
	{
		// Get system slash
		$slash = DIRECTORY_SEPARATOR;

		// Replace slashes in directory with system slashes
		$dir = str_replace( '/',$slash,$dir );

		// Get realpath
		return realpath( $dir );
	}


/* ----[ Check for a directory ]----------------------------
return
	bool ( TRUE{dir exists} | FALSE{dir does not exist} )
uses
	$this->getRealPath()
arguments
	$dir   = name of the directory to check for
	$check = check to see if it is writable ( default 0{false} )
	$chmod = make the directory writable    ( default 0{false} )
*/
	function checkDir( $dir , $check=0 , $chmod=0 )
	{
		$dir = $this->getRealPath( $dir );

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


/* ----[ Create a directory ]-------------------------------
return
	bool ( TRUE{created} | FALSE{not created} )
uses
	$this->getRealPath()
arguments
	$dir      = name of the directory to create
	$realpath = use the realpath for $dir
*/
	function makeDir( $dir,$realpath=0 )
	{
		if( $realpath )
			$dir = $this->getRealPath( $dir );

		if( mkdir($dir) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


/* ----[ Set the Messages ]---------------------------------
return
	none
arguments
	$message = String to add to $this->message
*/
	function setMessage( $message )
	{
		$this->message .= "$message\n";
	}


/* ----[ Get the Messages ]---------------------------------
return
	string ( Full HTML table with messages )
*/
	function getMessage()
	{
		$message = $this->message;

		if( !empty($message) )
		{
			// Replace newline feeds with <br />, then newline
			$message = nl2br( $message );

			$message = '
<!-- Begin Message Box -->
<table class="sc_table" cellspacing="1" cellpadding="2">
  <tr>
    <th class="membersHeaderRight">Messages</th>
  </tr>
  <tr class="sc_row1">
    <td>'.$message.'</td>
  </tr>
</table>
<!-- End Message Box -->
';

			return $message;
		}
		else
		{
			return '';
		}
	}


/* ----[ Set the SQL Message ]------------------------------
return
	none
arguments
	$message = String to add to $this->sql_debug
*/
	function setSqlDebug( $message )
	{
		$this->sql_debug .= "$message\n";
	}


/* ----[ Get the SQL Message ]------------------------------
return
	string ( Full HTML table with SQL statements )
*/
	function getSqlDebug()
	{
		global $db_prefix;
		$message = $this->sql_debug;

		if( !empty($message) )
		{
			// Make table names bold
			$message = preg_replace('/' . (ROSTER_SIGCONFIGTABLE) .'/', '<b>' . (ROSTER_SIGCONFIGTABLE) . '</b>', $message);

			// Non-passive keywords
			$red_keywords = array('/(INSERT INTO)/','/(UPDATE\s+)/','/(DELETE FROM\s+)/','/(CREATE TABLE)/','/(IF (NOT)? EXISTS)/', 
														'/(ALTER TABLE)/','/(CHANGE)/','/(SET)/','/(REPLACE INTO)/');
			$red_replace = array_fill(0, sizeof($red_keywords), '<span class="sc_errorText">\\1</span>');
			$message = preg_replace($red_keywords, $red_replace, $message);

			// Passive keywords
			$green_keywords = array('/(SELECT)/','/(FROM)/','/(WHERE)/','/(LIMIT)/','/(ORDER BY)/','/(GROUP BY)/',
															'/(\s+AND\s+)/','/(\s+OR\s+)/','/(BETWEEN)/','/(DESC)/','/(LEFT JOIN)/','/(SHOW TABLES)/',
															'/(LIKE)/','/(PRIMARY KEY)/','/(VALUES)/','/(TYPE)/','/(ENGINE)/','/(MyISAM)/','/(SHOW COLUMNS)/');
            
			$green_replace = array_fill(0, sizeof($green_keywords), '<span class="sc_sqlText">\\1</span>');
			$message = preg_replace($green_keywords, $green_replace, $message);


			// Replace '<!-- ' with color tag
			$message = nl2br($message);

			$message = '
<!-- Begin SQL Debug Box -->
<div id="sqlDebugCol" style="display:inline">
  <table width="400" class="sc_table" cellspacing="1" cellpadding="2">
    <tr>
      <th class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'" onclick="swapShow(\'sqlDebugCol\',\'sqlDebug\')">SQL Debug Window</th>
    </tr>
  </table>
</div>
<div id="sqlDebug" style="display:none">
  <table class="sc_table" cellspacing="1" cellpadding="2">
    <tr>
      <th class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'" onclick="swapShow(\'sqlDebugCol\',\'sqlDebug\')">SQL Debug Window</th>
    </tr>
    <tr class="sc_row1">
      <td>'.$message.'</td>
    </tr>
  </table>
</div>
<!-- End SQL Debug Box -->
';

			return $message;
		}
		else
		{
			return '';
		}
	}


/* ----[ Create an HTML option list ]-----------------------
return
	string ( an html option list )
arguments
	$values = array with $values
	$selected = what will be selected
	$select_field = what to match selected with
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


/* ----[ Create an HTML option list ]-----------------------
return
	string ( an html option list )
arguments
	$values = array with $values
	$selected = what will be selected
	$select_field = what to match selected with
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


/* ----[ Create an HTML option list ]-----------------------
return
	string ( an html option list )
arguments
	$values = array with $values
	$selected = what will be selected
	$select_field = what to match selected with
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


/* ----[ Create an HTML option list ]-----------------------
return
	string ( an html option list )
arguments
	$values = array with $values
	$selected = what will be selected
	$select_field = what to match selected with
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


/* ----[ Get a list of files from a directory ]-------------
return
	array ( $file => $name )
uses
	$this->getRealPath()
arguments
	$dir = directory to get list of files
	$ext = get files with this extention
*/
	function listFiles( $dir , $ext )
	{
		$arrFiles = array();

		$dir = $this->getRealPath( $dir );

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


/* ----[ Check if a table exists ]--------------------------
return
	array ( $name => $value )
uses
	$this->setSqlDebug()
arguments
	$table = table to get data from
*/
function checkDb( $table )
{
	global $wowdb;

	$sql_str = "SHOW TABLES LIKE '$table';";

	$this->setSqlDebug("checkDb: $sql_str");

	$result = $wowdb->query($sql_str);

	$r = mysql_fetch_assoc($result);

	if( empty($r) )
	{
		return FALSE;
	}
	else
	{
		return TRUE;
	}
}


/* ----[ Get Data from the database ]-----------------------
return
	array ( $name => $value )
uses
	$this->setSqlDebug()
arguments
	$table = table to get data from
	$field = field to get data from ( default *{all} )
	$where = where statement        ( default ''{blank} )
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
				$data = mysql_fetch_assoc($result);
				return $data;
			}
		}
	}


/* ----[ Get Data from the database ]-----------------------
return
	array ( $name => $value )
uses
	$this->setSqlDebug()
arguments
	$table = table to get data from
	$field = field to get data from ( default *{all} )
	$where = where statement        ( default ''{blank} )
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
	
			while( $r = mysql_fetch_assoc($result) )
			{
				extract($r);
				$list[] = $name;
			}
			return $list;
		}
	}
/* ----[ Get Data from the database ]-----------------------
return
	array ( $name => $value )
uses
	$this->setSqlDebug()
arguments
	$table = table to get data from
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
	
			while( $row = mysql_fetch_array($result) )
			{
				$col_names[] = $row[0];
			}
			return $col_names;
		}
	}


/* ----[ Upload Images to a folder ]------------------------
return
	bool
uses
	$this->setMessage()
	$this->getRealPath()
arguments
	$dir      = directory to upload image to
	$filename = filename to upload
*/
function uploadImage( $dir , $filename )
{
	$dir = $this->getRealPath($dir).(DIRECTORY_SEPARATOR);

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
			$this->setMessage('Please select a character before uploading an image');
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
				$this->setMessage("Upload of &quot;$userfile_name&quot; failed - You might not have write access to - &quot;".str_replace( '\\','/',$dir ).'&quot;');
				return FALSE;
			}
			else
			{
				$this->setMessage("Upload of &quot;$userfile_name&quot; to &quot;".str_replace( '\\','/',$dir ).'&quot; successful');
				return TRUE;
			}
		}
	}
	else
	{
		return FALSE;
	}
}


/* ----[ Delete Images in a folder ]------------------------
return
	bool
uses
	$this->setMessage()
	$this->getRealPath()
arguments
	$dir      = directory to upload image to
	$filename = filename to upload
*/
function deleteImage( $dir , $filename )
{
	$dir = $this->getRealPath($dir).(DIRECTORY_SEPARATOR);

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
				$this->setMessage( str_replace( '\\','/',$dir ).$filename.': Deleted' );
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


/* ----[ Process Data for entry to the database ]-----------
return
	none
uses
	$this->setMessage()
	$this->setSqlDebug()
arguments
	$post        = data from $_POST
	$config_name = db id to upload
*/
	function processData( $post , $config_name )
	{
		global $wowdb;
		$configData = $this->getDbData( (ROSTER_SIGCONFIGTABLE),'',"`config_id` = '$config_name'");;
	
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
	
			if( $settingValue != $configData[$settingName] && $settingName != 'op' )
				$wowdb->add_value($settingName, $settingValue );
		}
	
		// Update DataBase
		if( !empty($wowdb->assignstr) )
		{
			$update_sql = 'UPDATE `'.ROSTER_SIGCONFIGTABLE."` SET ".$wowdb->assignstr." WHERE `config_id` = '".$config_name."'";
			if ( $wowdb->sqldebug )
				$this->setSqlDebug("processData: $update_sql");
	
			$wowdb->query($update_sql)
				or exit( 'processData: '.mysql_error() );
	
			$this->setMessage( 'Settings have been changed' );
		}
	}


/* ----[ Resets the database config to defaults ]-----------
return
	none
uses
	$this->setMessage()
	$this->setSqlDebug()
arguments
	$confirm = Confirmation to reset
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
					or exit( 'resetDefaults: '.mysql_error() );

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


/* ----[ Creates the sig_config table ]---------------------
return
	string
uses
	$this->setMessage()
	$this->setSqlDebug()
arguments
	$mode = install mode
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
					$engine_type = $_REQUEST['engine'];

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
					or exit( 'installDb: '.mysql_error() );
			}
			return ucwords($mode)." was successfull...\nClick <a href=\"addon.php?roster_addon_name=siggen\">HERE</a> to continue\n";
		}
		else
		{
			return "Cannot find sql file [$sql_file]";
		}
	}



/* ----[ Create a tooltip ]---------------------------------
return
	string ( Overlib styled tooltip )
arguments
	$caption = Text in the caption
	$content = Content in tooltip
*/
	function createTip( $content , $caption )
	{
		$tipsettings = ",FGCOLOR,'#000000',BGCOLOR,'#696969',CAPCOLOR,'#FFFFFF',WRAP";

		if( !empty($caption) )
			$caption2 = ",CAPTION,'$caption'";

		$tip = "<div style=\"cursor:help;\" onMouseover=\"return overlib('$content'$caption2$tipsettings);\" onMouseout=\"return nd();\">$caption</div>";

		return $tip;
	}


/* ----[ Create a tooltip for colors ]----------------------
return
	string ( Overlib styled tooltip )
arguments
	$selected   = currently selected color
	$configData = Data from the database
*/
	function createColorTip( $selected,$configData )
	{
		
		$tipsettings = ",FGCOLOR,'#000000',BGCOLOR,'#696969',CAPCOLOR,'#FFFFFF',RIGHT,WRAP";

		
		for($n=1;$n<=10;$n++)
		{
			if( $selected == ('color'.$n) )
				$current = '<span class=&quot;sc_errortext&quot;>&nbsp;&nbsp;&lt;&lt;&lt;&lt;&lt;</span>';
			else
				$current = '';

			$content .= '<tr><td>[<span style=&quot;font-size:10px; color:#FFFFFF; background-color:'.$configData['color'.$n].';&quot;>&nbsp;'.$n.'&nbsp;</span>]</td><td>'.$configData['color'.$n].'</td><td>'.$current.'</td></tr>';
		}

		$content = "<table class=&quot;sc_table&quot; cellspacing=&quot;0&quot;><tr><td colspan=&quot;2&quot;><strong>Available</strong></td><td><strong>Selected</strong></td></tr>$content";

		$tip = "[<span style=\"cursor:help; font-size:10px; color:#FFFFFF; background-color:".$configData[$selected].";\" onMouseover=\"return overlib('$content',CAPTION,'Color Menu'$tipsettings);\" onMouseout=\"return nd();\">colors</span>]";

		return $tip;
	}



}

$functions = new SigConfigClass;
?>