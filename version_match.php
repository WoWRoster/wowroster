<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Diag File Checker Interface
 * DO NOT SHIP WITH THE RELEASE ROSTER!!!!
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
 * @package    WoWRoster
 * @subpackage RosterDiag
*/

error_reporting(E_ALL);
//error_reporting(E_ALL ^ E_NOTICE);

// Needed so files think we are in Roster =P
define('IN_ROSTER',true);

/**
 * OS specific Directory Seperator
 */
define('DIR_SEP',DIRECTORY_SEPARATOR);


/**
 * Base, absolute roster directory
 */
define('ROSTER_BASE',dirname(__FILE__) . DIR_SEP);
define('ROSTER_LIB',ROSTER_BASE . 'lib' . DIR_SEP);

// This file is for on the SVN only, so this should NOT be shipped to the clients!!!
require_once(ROSTER_BASE . 'lib/constants.php');
require_once(ROSTER_LIB . 'functions.lib.php');
require_once(ROSTER_LIB . 'roster.php');
$roster = new roster;
define('ROSTER_PAGE_NAME', '');

$roster->config['seo_url'] = false;

require_once(ROSTER_LIB . 'cmslink.lib.php');

$roster->config['img_url'] = ROSTER_PATH . 'img/';

require_once(ROSTER_LIB . 'rosterdiag.lib.php');

if( isset($_GET['getfile']) && $_GET['getfile'] != '' )
{
	$getfile = $_GET['getfile'];
	$pathparts_getfile = pathinfo($getfile);
	$realpath_getfile = realpath($pathparts_getfile['dirname']);
	$realpathparts_getfile = pathinfo($realpath_getfile);

	$thisfile = $_SERVER['SCRIPT_FILENAME'];
	$realpath_thisfile = realpath($thisfile);
	$pathparts_thisfile = pathinfo($thisfile);
	$realpathparts_thisfile = pathinfo($realpath_thisfile);


	if( substr($_GET['getfile'], 0, 1) == '.' )
	{
		$subpath_getfile = substr($pathparts_getfile['dirname'], 2);
	}
	else
	{
		$subpath_getfile = $pathparts_getfile['dirname'];
	}

	if( !checkfile($pathparts_getfile, $realpath_getfile, $realpathparts_thisfile['dirname'].'/'.$subpath_getfile) )
	{
		print("<pre>[ERROR] INVALID FILE: " . $_GET['getfile'] . ", Operation NOT Allowed!!!</pre>\n");
	}
	else
	{
		// Safety Checks have been completed, the file requested in this part or deeper, and is not conf.php or version_match.php
		$filename = $getfile;
		if( is_file($filename) && is_readable($filename) )
		{
			// File is OK and readable, so lets get it, either header only or the full file
			if( isset($_GET['mode']) && $_GET['mode'] == 'diff' )
			{
				$handle = fopen($filename, 'rb');
				$contents = fread($handle, filesize($filename));
				fclose($handle);
				print($contents);
			}
			elseif( isset($_GET['mode']) && $_GET['mode'] == 'md5' )
			{
				print(md5_file($filename));
			}
			else
			{
				print("<pre>[ERROR] FILE MODE ERROR: No Get-Mode specified</pre>\n");
			}
		}
		else
		{
			print("<pre>[ERROR] FILE NOT READABLE: " . $filename . " is not readable!</pre>\n");
		}

	}
	exit();
}
elseif( isset($_POST['remotediag']) && $_POST['remotediag'] == 'true' )
{
	$roster->config['theme'] = 'default';
	$roster->config['default_name'] = $_POST['guildname'];
	$roster->output['title'] = 'Remote Diagnostics';
	$roster->config['website_address'] = $_SERVER['HTTP_REFERER'];
	$roster->config['logo'] = $roster->config['img_url'] . 'wowroster_logo.jpg';
	$roster->config['roster_bg'] = $roster->config['img_url'] . 'wowroster_bg.jpg';

	require_once(ROSTER_LIB . 'template.php');
	$roster->tpl = new RosterTemplate;

	/**
	 * Assign initial template vars
	 */
	$roster->tpl->assign_vars(array(
		'S_SEO_URL'          => false,
		'S_HEADER_LOGO'      => ( !empty($roster->config['logo']) ? true : false ),

		'U_MAKELINK'      => makelink(),
		'U_LINKFORM'      => linkform(),
		'ROSTER_URL'      => ROSTER_URL,
		'ROSTER_PATH'     => ROSTER_PATH,
		'WEBSITE_ADDRESS' => $roster->config['website_address'],
		'HEADER_LOGO'     => $roster->config['logo'],
		'IMG_URL'         => $roster->config['img_url'],
		'INTERFACE_URL'   => '',
		'ROSTER_VERSION'  => ROSTER_VERSION,
		'ROSTER_CREDITS'  => '',
		'XML_LANG'        => 'en',

		'T_BORDER_WHITE'  => border('swhite','start'),
		'T_BORDER_GRAY'   => border('sgray','start'),
		'T_BORDER_GOLD'   => border('sgold','start'),
		'T_BORDER_RED'    => border('sred','start'),
		'T_BORDER_ORANGE' => border('sorange','start'),
		'T_BORDER_YELLOW' => border('syellow','start'),
		'T_BORDER_GREEN'  => border('sgreen','start'),
		'T_BORDER_PURPLE' => border('spurple','start'),
		'T_BORDER_BLUE'   => border('sblue','start'),
		'T_BORDER_END'    => border('sgray','end'),

		'PAGE_TITLE'         => '',
		'ROSTER_HEAD'        => '',
		'ROSTER_BODY'        => '',
		'ROSTER_MENU_BEFORE' => '',
		)
	);

	include_once(ROSTER_BASE . 'header.php');
	$temp_array = split('&', $_SERVER['QUERY_STRING']);
	foreach( $temp_array as $key=>$value )
	{
		if( substr($value, 0, 15) == 'files' )
		{
  			$_POST['files'][] = substr($value, 15, strlen($value));
		}
	}
	foreach( $files as $directory => $filedata )
	{
		foreach( $filedata as $filename => $file )
		{
			$files[$directory][$filename]['remote']['versionFile'] = $filename;
			unset($files[$directory][$filename]['local']['versionFile']);
			$files[$directory][$filename]['remote']['versionDesc'] = $file['local']['versionDesc'];
			unset($files[$directory][$filename]['local']['versionDesc']);
			$files[$directory][$filename]['remote']['versionRev'] = $file['local']['versionRev'];
			unset($files[$directory][$filename]['local']['versionRev']);
			$files[$directory][$filename]['remote']['versionDate'] = $file['local']['versionDate'];
			unset($files[$directory][$filename]['local']['versionDate']);
			$files[$directory][$filename]['remote']['versionAuthor'] = $file['local']['versionAuthor'];
			unset($files[$directory][$filename]['local']['versionAuthor']);
			$files[$directory][$filename]['remote']['versionMD5'] = $file['local']['versionMD5'];
			unset($files[$directory][$filename]['local']['versionMD5']);
			unset($files[$directory][$filename]['local']);
		}
	}

	foreach( $_POST['files'] as $directory => $filedata )
	{
		foreach( $filedata as $file => $fdata )
		{
			$files[$directory][$file]['local']['versionFile'] = $file;
			$files[$directory][$file]['local']['versionDesc'] = $fdata['versionDesc'];
			$files[$directory][$file]['local']['versionRev'] = $fdata['versionRev'];
			$files[$directory][$file]['local']['versionDate'] = $fdata['versionDate'];
			$files[$directory][$file]['local']['versionAuthor'] = $fdata['versionAuthor'];
			$files[$directory][$file]['local']['versionMD5'] = $fdata['versionMD5'];
		}
	}

	VerifyVersions();

	// Make a post form for the download of a Zip Package
	$zippackage_files = '';
	foreach( $directories as $directory => $filecount )
	{
		if( isset($files[$directory]) )
		{
			foreach( $files[$directory] as $file => $filedata )
			{
				if( $filedata['update'] )
				{
					if( isset($file) && $file != 'severity' && $file != 'tooltip' && $file != 'rollup' && $file != 'rev' && $file != 'date' && $file != 'author' && $file != 'md5' && $file != 'update' && $file != 'missing' )
					{
						if( $zippackage_files != '' )
						{
							$zippackage_files .= ';';
						}
						$zippackage_files .= $directory . '/' . $file;
					}
				}
			}
		}
	}

	if( $zippackage_files != '' )
	{
		echo border('spurple', 'start', '<span class="blue">Download Update Package From:</span> <small style="color:#6ABED7;font-weight:bold;"><i>SVN @ ' . str_replace('version_match.php', '', ROSTER_SVNREMOTE) . '</i></small>');
		echo '<div align="center"><form method="post" action="' . ROSTER_SVNREMOTE . '">';
		echo '<input type="hidden" name="filestoget" value="' . $zippackage_files . '">';
		echo '<input type="hidden" name="guildname" value="' . $roster->config['default_name'] . '">';
		echo '<input type="hidden" name="website" value="' . $roster->config['website_address'] . '">';
		echo '<input type="radio" name="ziptype" value="zip" checked="checked">.zip Archive<br />';
		echo '<input type="radio" name="ziptype" value="targz">.tar.gz Archive<br /><br />';
		echo '<input style="decoration:bold;" type="submit" value="[GET UPDATE PACKAGE]">';
		echo '</form></div>';
		echo border('spurple', 'end') . '<br />';
	}

	// Open the main FileVersion table in total color
	echo border('sgray', 'start', '<span class="blue">File Versions:</span> <small style="color:#6ABED7;font-weight:bold;"><i>SVN @ ' . str_replace('version_match.php', '', ROSTER_SVNREMOTE) . '</i></small>');

	// Get all the gathered information and display it in a table

	foreach( $directories as $directory => $filecount )
	{
		if( isset($files[$directory]) )
		{
			//echo $directory . ', '.$files[$directory]['tooltip'] . '<br />';
			$dirtooltip = str_replace("'", "\\'", $files[$directory]['tooltip']);
			$dirtooltip = str_replace('"','&quot;', $dirtooltip);
			$directory_id = str_replace(array('.','/','\\'),'', $directory);

			$dirshow = substr_replace($directory, ROSTER_PATH, 0, 1);


			$headertext_max = '<div style="cursor:pointer;width:800px;text-align:left;" onclick="swapShow(\'' . $directory_id . 'TableShow\',\'' . $directory_id . 'TableHide\')" '
							. 'onmouseover="overlib(\'' . $dirtooltip . '\',CAPTION,\'' . $directory . '/&nbsp;&nbsp;-&nbsp;&nbsp;' . $severity[$files[$directory]['rollup']]['severityname'] . '\',WRAP);" onmouseout="return nd();">'
							. '<div style="float:right;"><span style="color:' . $severity[$files[$directory]['rollup']]['color'] . ';">' . $severity[$files[$directory]['rollup']]['severityname'] . '</span> <img class="membersRowimg" src="' . $roster->config['img_url'] . 'plus.gif" alt="+" /></div>' . $dirshow . '/</div>';

			$headertext_min = '<div style="cursor:pointer;width:800px;text-align:left;" onclick="swapShow(\'' . $directory_id . 'TableShow\',\'' . $directory_id . 'TableHide\')" '
							. 'onmouseover="overlib(\'' . $dirtooltip . '\',CAPTION,\'' . $directory . '/&nbsp;&nbsp;-&nbsp;&nbsp;' . $severity[$files[$directory]['rollup']]['severityname'] . '\',WRAP);" onmouseout="return nd();">'
							. '<div style="float:right;"><span style="color:' . $severity[$files[$directory]['rollup']]['color'] . ';">' . $severity[$files[$directory]['rollup']]['severityname'] . '</span> <img class="membersRowimg" src="' . $roster->config['img_url'] . 'minus.gif" alt="-" /></div>' . $dirshow . '/</div>';


			echo '<div style="display:none;" id="' . $directory_id . 'TableShow">';
			echo border($severity[$files[$directory]['rollup']]['style'],'start',$headertext_min);


			echo '<table width="100%" cellpadding="0" cellspacing="0" class="bodyline">';
			echo '<tr><th class="membersHeader">Filename</th><th class="membersHeader">Revision</th><th class="membersHeader">Date</th><th class="membersHeader">Author</th><th class="membersHeader">MD5 Match</th><th class="membersHeaderRight">SVN</th>';
			echo '</tr>';
			$row=0;
			foreach( $files[$directory] as $file => $filedata )
			{
				if( $row==1 )
				{
					$row=2;
				}
				else
				{
					$row=1;
				}

				if( isset($filedata['tooltip']) )
				{
					$filetooltip = str_replace("'", "\\'", $filedata['tooltip']);
					$filetooltip = str_replace('"','&quot;', $filetooltip);
				}
				else
				{
					$filetooltip = 'Unknown';
				}
				if( isset($file) && $file != 'severity' && $file != 'tooltip' && $file != 'rollup' && $file != 'rev' && $file != 'date' && $file != 'author' && $file != 'md5' && $file != 'update' && $file != 'diff' && $file != 'missing' )
				{
					echo '<tr style="cursor:help;" onmouseover="overlib(\'<span style=&quot;color:blue;&quot;>' . $filetooltip . '</span>\',CAPTION,\'' . $file . '/&nbsp;&nbsp;-&nbsp;&nbsp;' . $severity[$filedata['rollup']]['severityname'] . '\',WRAP);" onmouseout="return nd();">';
					echo '<td class="membersRow' . $row . '"><span style="color:' . $severity[$filedata['rollup']]['color'] . '">' . $file . '</span></td>';
					echo '<td class="membersRow' . $row . '">' . "\n";
					if( isset($filedata['rev']) )
					{
						echo $filedata['rev'];
					}
					else
					{
						echo 'Unknown Rev';
					}
					echo "</td>\n";
					echo '<td class="membersRow' . $row . '">';
					if( isset($filedata['date']) )
					{
						echo $filedata['date'];
					}
					else
					{
						echo 'Unknown Date';
					}
					echo "</td>\n";
					echo '<td class="membersRow' . $row . '">';
					if( isset($filedata['author']) )
					{
						echo $filedata['author'];
					}
					else
					{
						echo 'Unknown Author';
					}
					echo "</td>\n";
					echo '<td class="membersRow' . $row . '">';
					if( isset($filedata['md5']) )
					{
						echo $filedata['md5'];
					}
					else
					{
						echo 'Unknown';
					}
					echo "</td>\n";
					echo '<td class="membersRowRight' . $row . '">' . "\n";
					if( $filedata['diff'] || $filedata['missing'] )
					{
						echo '<form method="post" action="' . makelink('rosterdiag') . '">' . "\n";
						echo '<input type="hidden" name="filename" value="' . $directory . '/' . $file . "\">\n";
						echo '<input type="hidden" name="downloadsvn" value="confirmation">' . "\n";
						if( isset($filedata['diff']) && $filedata['diff'] )
						{
							echo '<input type="hidden" name="downmode" value="update">' . "\n";
							echo '<input type="submit" value="Diff Check">' . "\n";
						}
						elseif( isset($filedata['missing']) && $filedata['missing'] )
						{
							echo '<input type="hidden" name="downmode" value="install">' . "\n";
							echo '<input type="submit" value="Show File">' . "\n";
						}
						echo '</form>';
					}
					else
					{
						echo '&nbsp;';
					}
					echo "</td>\n";
					echo "</tr>\n";
				}
			}

			echo '</table>';

			echo border($severity[$files[$directory]['rollup']]['style'],'end') . '</div>';
			echo '<div id="' . $directory_id . 'TableHide">';
			echo border($severity[$files[$directory]['rollup']]['style'],'start',$headertext_max);
			echo border($severity[$files[$directory]['rollup']]['style'],'end') . '</div>';
		}
	}
	echo border('sgray', 'end');

	echo '<!-- Begin Roster Footer -->';
	echo '</div>';
	echo '</body>';
	echo '</html>';

	exit();
}
elseif( isset($_POST['filestoget']) && isset($_POST['ziptype']) )
{
	$filesarray = explode(';', $_POST['filestoget']);
	$ziptype = $_POST['ziptype']; // targz  or  zip
	$errors = '';

	if( $ziptype == 'targz' )
	{
		$downloadpackage = new gzip_file('WoWRoster_UpdatePackage_' . date('Ymd_Hi') . '.tar.gz');
	}
	else
	{
		$downloadpackage = new zip_file('WoWRoster_UpdatePackage_' . date('Ymd_Hi') . '.zip');
	}

	$downloadpackage->set_options(array('inmemory' => 1, 'recurse' => 0, 'storepaths' => 1));

	foreach( $filesarray as $file )
	{
		$getfile = $file;

		$pathparts_getfile = pathinfo($getfile);
		$realpath_getfile = realpath($pathparts_getfile['dirname']);
		$realpathparts_getfile = pathinfo($realpath_getfile);

		$thisfile = $_SERVER['SCRIPT_FILENAME'];
		$realpath_thisfile = realpath($thisfile);
		$pathparts_thisfile = pathinfo($thisfile);
		$realpathparts_thisfile = pathinfo($realpath_thisfile);

		//echo $file . ', ' . $thisfile . ', ' . $realpath_thisfile . ', ' . $realpathparts_getfile['dirname'] . ', ' . $realpathparts_thisfile . '<br />';


		if( substr($getfile, 0, 1) == '.' )
		{
			$subpath_getfile = substr($pathparts_getfile['dirname'], 2);
		}
		else
		{
			$subpath_getfile = $pathparts_getfile['dirname'];
		}

		if( !checkfile($pathparts_getfile, $realpath_getfile, $realpathparts_thisfile['dirname'] . '/' . $subpath_getfile) )
		{
			$errors .= '[ERROR] INVALID FILE: ' . $getfile . ', Operation NOT Allowed!!!<br />';
		}
		else
		{
			// Add file to Archive
//			echo $getfile . '<br />';
			$downloadpackage->add_files($getfile);
		}
	}
	if( $errors == '' )
	{
		$downloadpackage->create_archive();
		// Send archive to user for download

		//
		if( count($downloadpackage->error) == 0 )
		{
			$downloadpackage->download_file();
		}
		else
		{
			foreach( $downloadpackage->error as $error )
			{
				print $error . '<br />';
			}
		}

		/*foreach( $filesarray as $file )
		{
			echo $file . '<br />';
		}
		echo 'DOWNLOAD STARTING of the following files';*/
	}
	else
	{
		echo $errors;
	}
}
else
{
	foreach( $files as $directory => $filedata )
	{
		foreach( $filedata as $filename => $file )
		{
			print($directory . $explode . $filename . $explode . $file['local']['versionDesc'] . $explode . $file['local']['versionRev'] . $explode . $file['local']['versionDate'] . $explode . $file['local']['versionAuthor'] . $explode . $file['local']['versionMD5'] . $break);
		}
	}
}


// Check the file requested function
function checkfile()
{
	global $extensions, $pathparts_getfile, $realpath_getfile, $realpath_thisfile, $subpath_getfile;

	$returnvalue = 0;
	$unwanted = 0;

	if( !strcmp($realpath_getfile, $realpath_thisfile . '/' . $subpath_getfile) )
	{
		$unwanted = 1;
	}
	if( !strcmp('version_match.php', $pathparts_getfile['basename']) )
	{
		$unwanted = 1;
	}
	if( !strcmp('conf.php', $pathparts_getfile['basename']) )
	{
		$unwanted = 1;
	}

	if( $unwanted )
	{
		$returnvalue = 0;
	}
	else
	{
		foreach( $extensions as $wantedextension )
		{
			if( !strcmp($wantedextension, $pathparts_getfile['extension']) )
			{
				$returnvalue = 1;
			}
		}
	}
	return $returnvalue;
}



/*
	class: archive:

 Examples of Compression:

The following example creates a gzipped tar file:
// Assume the following script is executing in /var/www/htdocs/test
// Create a new gzip file test.tgz in htdocs/test
	$test = new gzip_file('htdocs/test/test.tgz');
// Set basedir to '../..', which translates to /var/www
// Overwrite /var/www/htdocs/test/test.tgz if it already exists
// Set compression level to 1 (lowest)
	$test->set_options(array('basedir' => '../..', 'overwrite' => 1, 'level' => 1));
// Add entire htdocs directory and all subdirectories
// Add all php files in htsdocs and its subdirectories
	$test->add_files(array('htdocs', 'htsdocs/*.php'));
// Exclude all jpg files in htdocs and its subdirectories
	$test->exclude_files('htdocs/*.jpg');
// Create /var/www/htdocs/test/test.tgz
	$test->create_archive();
// Check for errors (you can check for errors at any point)
	if (count($test->errors) > 0)
		print ('Errors occurred.'); // Process errors here

The following example creates a zip file:
// Create new zip file in the directory below the current one
	$test = new zip_file('../example.zip');
// All files added will be relative to the directory in which the script is
//    executing since no basedir is set.
// Create archive in memory
// Do not recurse through subdirectories
// Do not store file paths in archive
	$test->set_options(array('inmemory' => 1, 'recurse' => 0, 'storepaths' => 0));
// Add lib/archive.php to archive
	$test->add_files('src/archive.php');
// Add all jpegs and gifs in the images directory to archive
	$test->add_files(array('images/*.jp*g', 'images/*.gif'));
// Store all exe files in bin without compression
	$test->store_files('bin/*.exe');
// Create archive in memory
	$test->create_archive();
// Send archive to user for download
	$test->download_file();

Examples of Decompression:

The following example extracts a bzipped tar file:
// Open test.tbz2
	$test = new bzip_file('test.tbz2');
// Overwrite existing files
	$test->set_options(array('overwrite' => 1));
// Extract contents of archive to disk
	$test->extract_files();

The following example extracts a tar file:
// Open archives/test.tar
	$test = new tar_file('archives/test.tar');
// Extract in memory
	$test->set_options(array('inmemory' => 0));
// Extract archive to memory
	$test->extract_files();
// Write out the name and size of each file extracted
	foreach ($test->files as $file)
		print ('File ' + $file['name'] + ' is ' + $file['stat'][7] + " bytes\n");

*/

/*--------------------------------------------------
 | TAR/GZIP/BZIP2/ZIP ARCHIVE CLASSES 2.1
 | By Devin Doucette
 | Copyright (c) 2005 Devin Doucette
 | Email: darksnoopy@shaw.ca
 +--------------------------------------------------
 | Email bugs/suggestions to darksnoopy@shaw.ca
 +--------------------------------------------------
 | This script has been created and released under
 | the GNU GPL and is free to use and redistribute
 | only if this copyright statement is not removed
 +--------------------------------------------------*/

class archive
{
	var $options;
	var $files;
	var $exclude;
	var $storeonly;
	var $error;

	function archive($name)
	{
		$this->options = array (
			'basedir' => '.',
			'name' => $name,
			'prepend' => '',
			'inmemory' => 0,
			'overwrite' => 0,
			'recurse' => 1,
			'storepaths' => 1,
			'followlinks' => 0,
			'level' => 3,
			'method' => 1,
			'sfx' => '',
			'type' => '',
			'comment' => ''
		);
		$this->files = array ();
		$this->exclude = array ();
		$this->storeonly = array ();
		$this->error = array ();
	}

	function set_options($options)
	{
		foreach ($options as $key => $value)
		{
			$this->options[$key] = $value;
		}
		if (!empty($this->options['basedir']))
		{
			$this->options['basedir'] = str_replace("\\", "/", $this->options['basedir']);
			$this->options['basedir'] = preg_replace("/\\/+/", "/", $this->options['basedir']);
			$this->options['basedir'] = preg_replace("/\\/$/", "", $this->options['basedir']);
		}
		if (!empty($this->options['name']))
		{
			$this->options['name'] = str_replace("\\", "/", $this->options['name']);
			$this->options['name'] = preg_replace("/\\/+/", "/", $this->options['name']);
		}
		if (!empty($this->options['prepend']))
		{
			$this->options['prepend'] = str_replace("\\", "/", $this->options['prepend']);
			$this->options['prepend'] = preg_replace("/^(\\.*\\/+)+/", "", $this->options['prepend']);
			$this->options['prepend'] = preg_replace("/\\/+/", "/", $this->options['prepend']);
			$this->options['prepend'] = preg_replace("/\\/$/", "", $this->options['prepend']) . "/";
		}
	}

	function create_archive()
	{
		$this->make_list();

		if ($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if ($this->options['overwrite'] == 0 && file_exists($this->options['name'] . ($this->options['type'] == 'gzip' || $this->options['type'] == 'bzip' ? '.tmp' : '')))
			{
				$this->error[] = "File {$this->options['name']} already exists.";
				chdir($pwd);
				return 0;
			}
			elseif ($this->archive = @fopen($this->options['name'] . ($this->options['type'] == 'gzip' || $this->options['type'] == 'bzip' ? '.tmp' : ''), 'wb+'))
			{
				chdir($pwd);
			}
			else
			{
				$this->error[] = "Could not open {$this->options['name']} for writing.";
				chdir($pwd);
				return 0;
			}
		}
		else
		{
			$this->archive = '';
		}

		switch ($this->options['type'])
		{
			case 'zip':
				if (!$this->create_zip())
				{
					$this->error[] = 'Could not create zip file.';
					return 0;
				}
				break;
			case 'bzip':
				if (!$this->create_tar())
				{
					$this->error[] = 'Could not create tar file.';
					return 0;
				}
				if (!$this->create_bzip())
				{
					$this->error[] = 'Could not create bzip2 file.';
					return 0;
				}
				break;
			case 'gzip':
				if (!$this->create_tar())
				{
					$this->error[] = 'Could not create tar file.';
					return 0;
				}
				if (!$this->create_gzip())
				{
					$this->error[] = 'Could not create gzip file.';
					return 0;
				}
				break;
			case 'tar':
				if (!$this->create_tar())
				{
					$this->error[] = 'Could not create tar file.';
					return 0;
				}
		}

		if ($this->options['inmemory'] == 0)
		{
			fclose($this->archive);
			if ($this->options['type'] == 'gzip' || $this->options['type'] == 'bzip')
			{
				unlink($this->options['basedir'] . '/' . $this->options['name'] . '.tmp');
			}
		}
	}

	function add_data($data)
	{
		if ($this->options['inmemory'] == 0)
		{
			fwrite($this->archive, $data);
		}
		else
		{
			$this->archive .= $data;
		}
	}

	function make_list()
	{
		if (!empty ($this->exclude))
		{
			foreach ($this->files as $key => $value)
			{
				foreach ($this->exclude as $current)
				{
					if ($value['name'] == $current['name'])
					{
						unset ($this->files[$key]);
					}
				}
			}
		}
		if (!empty ($this->storeonly))
		{
			foreach ($this->files as $key => $value)
			{
				foreach ($this->storeonly as $current)
				{
					if ($value['name'] == $current['name'])
					{
						$this->files[$key]['method'] = 0;
					}
				}
			}
		}
		unset ($this->exclude, $this->storeonly);
	}

	function add_files($list)
	{
		$temp = $this->list_files($list);
		foreach ($temp as $current)
		{
			$this->files[] = $current;
		}
	}

	function exclude_files($list)
	{
		$temp = $this->list_files($list);
		foreach ($temp as $current)
		{
			$this->exclude[] = $current;
		}
	}

	function store_files($list)
	{
		$temp = $this->list_files($list);
		foreach ($temp as $current)
		{
			$this->storeonly[] = $current;
		}
	}

	function list_files($list)
	{
		if (!is_array ($list))
		{
			$temp = $list;
			$list = array ($temp);
			unset ($temp);
		}

		$files = array ();

		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($list as $current)
		{
			$current = str_replace("\\", "/", $current);
			$current = preg_replace("/\\/+/", "/", $current);
			$current = preg_replace("/\\/$/", "", $current);
			if (strstr($current, '*'))
			{
				$regex = preg_replace("/([\\\^\$\.\[\]\|\(\)\?\+\{\}\/])/", "\\\\\\1", $current);
				$regex = str_replace('*', '.*', $regex);
				$dir = strstr($current, '/') ? substr($current, 0, strrpos($current, '/')) : '.';
				$temp = $this->parse_dir($dir);
				foreach ($temp as $current2)
				{
					if (preg_match("/^{$regex}$/i", $current2['name']))
					{
						$files[] = $current2;
					}
				}
				unset ($regex, $dir, $temp, $current);
			}
			else if (@is_dir($current))
			{
				$temp = $this->parse_dir($current);
				foreach ($temp as $file)
				{
					$files[] = $file;
				}
				unset ($temp, $file);
			}
			else if (@file_exists($current))
			{
				$files[] = array ('name' => $current, 'name2' => $this->options['prepend'] .
					preg_replace("/(\\.+\\/+)+/", '', ($this->options['storepaths'] == 0 && strstr($current, '/')) ?
					substr($current, strrpos($current, '/') + 1) : $current),
					'type' => @is_link($current) && $this->options['followlinks'] == 0 ? 2 : 0,
					'ext' => substr($current, strrpos($current, '.')), 'stat' => stat($current));
			}
		}

		chdir($pwd);

		unset ($current, $pwd);

		usort($files, array ('archive', 'sort_files'));

		return $files;
	}

	function parse_dir($dirname)
	{
		if ($this->options['storepaths'] == 1 && !preg_match("/^(\\.+\\/*)+$/", $dirname))
		{
			$files = array (array ('name' => $dirname, 'name2' => $this->options['prepend'] .
				preg_replace("/(\\.+\\/+)+/", '', ($this->options['storepaths'] == 0 && strstr($dirname, '/')) ?
				substr($dirname, strrpos($dirname, '/') + 1) : $dirname), 'type' => 5, 'stat' => stat($dirname)));
		}
		else
		{
			$files = array ();
		}
		$dir = @opendir($dirname);

		while ($file = @readdir($dir))
		{
			$fullname = $dirname . '/' . $file;
			if ($file == '.' || $file == '..')
			{
				continue;
			}
			else if (@is_dir($fullname))
			{
				if (empty ($this->options['recurse']))
				{
					continue;
				}
				$temp = $this->parse_dir($fullname);
				foreach ($temp as $file2)
				{
					$files[] = $file2;
				}
			}
			else if (@file_exists($fullname))
			{
				$files[] = array ('name' => $fullname, 'name2' => $this->options['prepend'] .
					preg_replace("/(\\.+\\/+)+/", '', ($this->options['storepaths'] == 0 && strstr($fullname, '/')) ?
					substr($fullname, strrpos($fullname, '/') + 1) : $fullname),
					'type' => @is_link($fullname) && $this->options['followlinks'] == 0 ? 2 : 0,
					'ext' => substr($file, strrpos($file, '.')), 'stat' => stat($fullname));
			}
		}

		@closedir($dir);

		return $files;
	}

	function sort_files($a, $b)
	{
		if ($a['type'] != $b['type'])
		{
			if ($a['type'] == 5 || $b['type'] == 2)
			{
				return -1;
			}
			else if ($a['type'] == 2 || $b['type'] == 5)
			{
				return 1;
			}
		}
		else if ($a['type'] == 5)
		{
			return strcmp(strtolower($a['name']), strtolower($b['name']));
		}
		else if ($a['ext'] != $b['ext'])
		{
			return strcmp($a['ext'], $b['ext']);
		}
		else if ($a['stat'][7] != $b['stat'][7])
		{
			return $a['stat'][7] > $b['stat'][7] ? -1 : 1;
		}
		else
		{
			return strcmp(strtolower($a['name']), strtolower($b['name']));
		}
		return 0;
	}

	function download_file()
	{
		if ($this->options['inmemory'] == 0)
		{
			$this->error[] = 'Can only use download_file() if archive is in memory. Redirect to file otherwise, it is faster.';
			return;
		}
		switch ($this->options['type'])
		{
			case 'zip':
				header('Content-Type: application/zip');
				break;
			case 'bzip':
				header('Content-Type: application/x-bzip2');
				break;
			case 'gzip':
				header('Content-Type: application/x-gzip');
				break;
			case 'tar':
				header('Content-Type: application/x-tar');
		}
		$header = 'Content-Disposition: attachment; filename="';
		$header .= strstr($this->options['name'], '/') ? substr($this->options['name'], strrpos($this->options['name'], '/') + 1) : $this->options['name'];
		$header .= '"';
		header($header);
		header('Content-Length: ' . strlen($this->archive));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: no-cache, must-revalidate, max-age=60');
		header('Expires: Sat, 01 Jan 2000 12:00:00 GMT');
		print($this->archive);
	}
}

class tar_file extends archive
{
	function tar_file($name)
	{
		$this->archive($name);
		$this->options['type'] = 'tar';
	}

	function create_tar()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($this->files as $current)
		{
			if ($current['name'] == $this->options['name'])
			{
				continue;
			}
			if (strlen($current['name2']) > 99)
			{
				$path = substr($current['name2'], 0, strpos($current['name2'], '/', strlen($current['name2']) - 100) + 1);
				$current['name2'] = substr($current['name2'], strlen($path));
				if (strlen($path) > 154 || strlen($current['name2']) > 99)
				{
					$this->error[] = "Could not add {$path}{$current['name2']} to archive because the filename is too long.";
					continue;
				}
			}
			$block = pack('a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12', $current['name2'], sprintf('%07o',
				$current['stat'][2]), sprintf('%07o', $current['stat'][4]), sprintf('%07o', $current['stat'][5]),
				sprintf('%011o', $current['type'] == 2 ? 0 : $current['stat'][7]), sprintf('%011o', $current['stat'][9]),
				'        ', $current['type'], $current['type'] == 2 ? @readlink($current['name']) : '', 'ustar ', ' ',
				'Unknown', 'Unknown', '', '', !empty ($path) ? $path : '', '');

			$checksum = 0;
			for ($i = 0; $i < 512; $i++)
			{
				$checksum += ord(substr($block, $i, 1));
			}
			$checksum = pack('a8', sprintf('%07o', $checksum));
			$block = substr_replace($block, $checksum, 148, 8);

			if ($current['type'] == 2 || $current['stat'][7] == 0)
			{
				$this->add_data($block);
			}
			else if ($fp = @fopen($current['name'], 'rb'))
			{
				$this->add_data($block);
				while ($temp = fread($fp, 1048576))
				{
					$this->add_data($temp);
				}
				if ($current['stat'][7] % 512 > 0)
				{
					$temp = '';
					for ($i = 0; $i < 512 - $current['stat'][7] % 512; $i++)
					{
						$temp .= '\0';
					}
					$this->add_data($temp);
				}
				fclose($fp);
			}
			else
			{
				$this->error[] = "Could not open file {$current['name']} for reading. It was not added.";
			}
		}

		$this->add_data(pack('a1024', ''));

		chdir($pwd);

		return 1;
	}

	function extract_files()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		if ($fp = $this->open_archive())
		{
			if ($this->options['inmemory'] == 1)
			{
				$this->files = array ();
			}

			while ($block = fread($fp, 512))
			{
				$temp = unpack('a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100symlink/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp', $block);
				$file = array (
					'name' => $temp['prefix'] . $temp['name'],
					'stat' => array (
						2 => $temp['mode'],
						4 => octdec($temp['uid']),
						5 => octdec($temp['gid']),
						7 => octdec($temp['size']),
						9 => octdec($temp['mtime']),
					),
					'checksum' => octdec($temp['checksum']),
					'type' => $temp['type'],
					'magic' => $temp['magic'],
				);
				if ($file['checksum'] == 0x00000000)
				{
					break;
				}
				else if (substr($file['magic'], 0, 5) != 'ustar')
				{
					$this->error[] = 'This script does not support extracting this type of tar file.';
					break;
				}
				$block = substr_replace($block, '        ', 148, 8);
				$checksum = 0;
				for ($i = 0; $i < 512; $i++)
				{
					$checksum += ord(substr($block, $i, 1));
				}
				if ($file['checksum'] != $checksum)
				{
					$this->error[] = "Could not extract from {$this->options['name']}, it is corrupt.";
				}

				if ($this->options['inmemory'] == 1)
				{
					$file['data'] = fread($fp, $file['stat'][7]);
					fread($fp, (512 - $file['stat'][7] % 512) == 512 ? 0 : (512 - $file['stat'][7] % 512));
					unset ($file['checksum'], $file['magic']);
					$this->files[] = $file;
				}
				else if ($file['type'] == 5)
				{
					if (!is_dir($file['name']))
					{
						mkdir($file['name'], $file['stat'][2]);
					}
				}
				else if ($this->options['overwrite'] == 0 && file_exists($file['name']))
				{
					$this->error[] = "{$file['name']} already exists.";
					continue;
				}
				else if ($file['type'] == 2)
				{
					symlink($temp['symlink'], $file['name']);
					chmod($file['name'], $file['stat'][2]);
				}
				else if ($new = @fopen($file['name'], 'wb'))
				{
					fwrite($new, fread($fp, $file['stat'][7]));
					fread($fp, (512 - $file['stat'][7] % 512) == 512 ? 0 : (512 - $file['stat'][7] % 512));
					fclose($new);
					chmod($file['name'], $file['stat'][2]);
				}
				else
				{
					$this->error[] = "Could not open {$file['name']} for writing.";
					continue;
				}
				chown($file['name'], $file['stat'][4]);
				chgrp($file['name'], $file['stat'][5]);
				touch($file['name'], $file['stat'][9]);
				unset ($file);
			}
		}
		else
		{
			$this->error[] = "Could not open file {$this->options['name']}";
		}

		chdir($pwd);
	}

	function open_archive()
	{
		return @fopen($this->options['name'], 'rb');
	}
}

class gzip_file extends tar_file
{
	function gzip_file($name)
	{
		$this->tar_file($name);
		$this->options['type'] = 'gzip';
	}

	function create_gzip()
	{
		if ($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if ($fp = gzopen($this->options['name'], "wb{$this->options['level']}"))
			{
				fseek($this->archive, 0);
				while ($temp = fread($this->archive, 1048576))
				{
					gzwrite($fp, $temp);
				}
				gzclose($fp);
				chdir($pwd);
			}
			else
			{
				$this->error[] = "Could not open {$this->options['name']} for writing.";
				chdir($pwd);
				return 0;
			}
		}
		else
		{
			$this->archive = gzencode($this->archive, $this->options['level']);
		}

		return 1;
	}

	function open_archive()
	{
		return @gzopen($this->options['name'], 'rb');
	}
}

class bzip_file extends tar_file
{
	function bzip_file($name)
	{
		$this->tar_file($name);
		$this->options['type'] = 'bzip';
	}

	function create_bzip()
	{
		if ($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if ($fp = bzopen($this->options['name'], 'wb'))
			{
				fseek($this->archive, 0);
				while ($temp = fread($this->archive, 1048576))
				{
					bzwrite($fp, $temp);
				}
				bzclose($fp);
				chdir($pwd);
			}
			else
			{
				$this->error[] = "Could not open {$this->options['name']} for writing.";
				chdir($pwd);
				return 0;
			}
		}
		else
		{
			$this->archive = bzcompress($this->archive, $this->options['level']);
		}

		return 1;
	}

	function open_archive()
	{
		return @bzopen($this->options['name'], 'rb');
	}
}

class zip_file extends archive
{
	function zip_file($name)
	{
		$this->archive($name);
		$this->options['type'] = 'zip';
	}

	function create_zip()
	{
		$files = 0;
		$offset = 0;
		$central = '';

		if (!empty ($this->options['sfx']))
		{
			if ($fp = @fopen($this->options['sfx'], 'rb'))
			{
				$temp = fread($fp, filesize($this->options['sfx']));
				fclose($fp);
				$this->add_data($temp);
				$offset += strlen($temp);
				unset ($temp);
			}
			else
			{
				$this->error[] = "Could not open sfx module from {$this->options['sfx']}.";
			}
		}

		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($this->files as $current)
		{
			if ($current['name'] == $this->options['name'])
			{
				continue;
			}

			$timedate = explode(' ', date('Y n j G i s', $current['stat'][9]));
			$timedate = ($timedate[0] - 1980 << 25) | ($timedate[1] << 21) | ($timedate[2] << 16) |
				($timedate[3] << 11) | ($timedate[4] << 5) | ($timedate[5]);

			$block = pack('VvvvV', 0x04034b50, 0x000A, 0x0000, (isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate);

			if ($current['stat'][7] == 0 && $current['type'] == 5)
			{
				$block .= pack('VVVvv', 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']) + 1, 0x0000);
				$block .= $current['name2'] . '/';
				$this->add_data($block);
				$central .= pack('VvvvvVVVVvvvvvVV', 0x02014b50, 0x0014, $this->options['method'] == 0 ? 0x0000 : 0x000A, 0x0000,
					(isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
					0x00000000, 0x00000000, 0x00000000, strlen($current['name2']) + 1, 0x0000, 0x0000, 0x0000, 0x0000, $current['type'] == 5 ? 0x00000010 : 0x00000000, $offset);
				$central .= $current['name2'] . '/';
				$files++;
				$offset += (31 + strlen($current['name2']));
			}
			else if ($current['stat'][7] == 0)
			{
				$block .= pack('VVVvv', 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']), 0x0000);
				$block .= $current['name2'];
				$this->add_data($block);
				$central .= pack('VvvvvVVVVvvvvvVV', 0x02014b50, 0x0014, $this->options['method'] == 0 ? 0x0000 : 0x000A, 0x0000,
					(isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
					0x00000000, 0x00000000, 0x00000000, strlen($current['name2']), 0x0000, 0x0000, 0x0000, 0x0000, $current['type'] == 5 ? 0x00000010 : 0x00000000, $offset);
				$central .= $current['name2'];
				$files++;
				$offset += (30 + strlen($current['name2']));
			}
			else if ($fp = @fopen($current['name'], 'rb'))
			{
				$temp = fread($fp, $current['stat'][7]);
				fclose($fp);
				$crc32 = crc32($temp);
				if (!isset($current['method']) && $this->options['method'] == 1)
				{
					$temp = gzcompress($temp, $this->options['level']);
					$size = strlen($temp) - 6;
					$temp = substr($temp, 2, $size);
				}
				else
				{
					$size = strlen($temp);
				}
				$block .= pack('VVVvv', $crc32, $size, $current['stat'][7], strlen($current['name2']), 0x0000);
				$block .= $current['name2'];
				$this->add_data($block);
				$this->add_data($temp);
				unset ($temp);
				$central .= pack('VvvvvVVVVvvvvvVV', 0x02014b50, 0x0014, $this->options['method'] == 0 ? 0x0000 : 0x000A, 0x0000,
					(isset($current['method']) || $this->options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
					$crc32, $size, $current['stat'][7], strlen($current['name2']), 0x0000, 0x0000, 0x0000, 0x0000, 0x00000000, $offset);
				$central .= $current['name2'];
				$files++;
				$offset += (30 + strlen($current['name2']) + $size);
			}
			else
			{
				$this->error[] = "Could not open file {$current['name']} for reading. It was not added.";
			}
		}

		$this->add_data($central);

		$this->add_data(pack('VvvvvVVv', 0x06054b50, 0x0000, 0x0000, $files, $files, strlen($central), $offset,
			!empty ($this->options['comment']) ? strlen($this->options['comment']) : 0x0000));

		if (!empty ($this->options['comment']))
		{
			$this->add_data($this->options['comment']);
		}

		chdir($pwd);

		return 1;
	}
}
