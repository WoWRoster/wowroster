<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Diagnostics and info
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// Set the title for the header
$header_title = $act_words['rosterdiag'];

// Include the library for RosterDiag
include_once(ROSTER_LIB.'rosterdiag.lib.php');


// Loging in as Admin to allow up- / downgrades && Downloads
// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin();


include_once (ROSTER_BASE.'roster_header.tpl');

// If the entire config page is requested, display only THAT
if( isset($_GET['printconf']) && $_GET['printconf'] == 1 )
{
	print '<div align="left"><pre>';
	print_r($roster_conf);
	print '</pre></div>';

	include_once(ROSTER_BASE.'roster_footer.tpl');
	exit();
}

// If a FileDiff is requested, display the header of the file and display Warning / Confirmation
if(isset($_POST['filename']) && isset($_POST['downloadsvn']))
{
	if ($_POST['downloadsvn'] == 'confirmation')
	{
		//Do confirmation stuff
		$filename = $_POST['filename'];
		if (is_file($filename))
		{
			$md5local = md5_file($filename);
		}
		else
		{
			$md5local = "Local File does not exist yet";
		}

		$rhmd5 = fopen(ROSTER_SVNREMOTE.'?getfile='.$filename.'&mode=md5', 'rb');
		if ($rhmd5===false)
		{
			print("[ERROR] Cannot Read MD5 Remote File\n");
			exit();
		}
		else
		{
			$md5remote = '';
			while (!feof($rhmd5))
			{
				$md5remote .= fread($rhmd5, 1024);
			}
		}
		fclose($rhmd5);

		$rhheadersvn = fopen(ROSTER_SVNREMOTE.'?getfile='.$filename.'&mode=diff', 'rb');
		if ($rhheadersvn===false)
		{
			print("[ERROR] Cannot Read Remote File\n");
			exit();
		}
		else
		{
			$filesvnsource = '';
			while (!feof($rhheadersvn))
			{
				$filesvnsource .= fread($rhheadersvn, 2048+10);
			}
		}
		fclose($rhheadersvn);

		if (file_exists($filename) && is_file($filename) && filesize($filename))
		{
			$rhheaderlocal = fopen($filename, 'rb');
			if ($rhheaderlocal===false)
			{
				print("[ERROR] Cannot Read Local File\n");
				exit();
			}
			else
			{
				$filelocalsource = '';
				while (!feof($rhheaderlocal))
				{
					$filelocalsource .= fread($rhheaderlocal, filesize($filename));
				}
			}
			fclose($rhheaderlocal);



			// Perform a DIFF check on the local and remote file
			if (check_if_image($filename))
			{
				$svnurl = parse_url(ROSTER_SVNREMOTE);
				$svnpath = pathinfo($svnurl['path'], PATHINFO_DIRNAME);
				$svnurl = $svnurl['scheme'].'://'.$svnurl['host'].$svnpath.'/';
				$diffcheck = '<table width="100%" border="0" cellspacing="0" class="bodyline"><tr><th class="membersHeader">Local Image</th><th class="membersHeaderRight">SVN Image</th></tr>';
				$diffcheck .= '<tr><td class="membersRow1"><img src="'.$filename.'" alt="Local Image" /></td><td class="membersRowRight1"><img src="'.$svnurl.$filename.'" alt="SVN Image" /></td></tr>';
				$diffcheck .= '</table>';
			}
			else
			{
				$diffcheck = '<table width="100%" border="0" cellspacing="0" class="bodyline"><tr><th class="membersHeader">Type</th><th class="membersHeader">Local File</th><th class="membersHeaderRight">SVN File</th></tr>';
				$difffiles = difffile($filelocalsource, $filesvnsource);
				$row_color=2;
				foreach ($difffiles as $difference)
				{
					if($row_color==1)
						$row_color=2;
					else
						$row_color=1;

					$rowfile1 = explode(",", $difference['rownr1']);
					$rowfile2 = explode(",", $difference['rownr2']);
					$diffcheck .= "<tr valign=\"top\">";

					$diffcheck .= '<td class="membersRow'.$row_color.'">';
					$diffcheck .= '<span class="'.$difference['color'].'">'.$difference['action'].'</span>';

					$diffcheck .= '</td><td class="membersRow'.$row_color.'">';


					if (isset($difference['from']))
					{
						foreach ($difference['from'] as $key => $value)
						{
							$from = $value."\n";
						}
						$diffcheck .= highlight_php($from, $rowfile1[0]);
					}
					$diffcheck .= '</td><td class="membersRowRight'.$row_color.'">';
					if (isset($difference['to']))
					{
						foreach ($difference['to'] as $key => $value)
						{
							$to = $value."\n";
						}
						$diffcheck .= highlight_php($to, $rowfile2[0]);
					}
					$diffcheck .= '</td>';
					$diffcheck .= '</tr>';
				}
				$diffcheck .= '</table>';
			}
		}
		else
		{
			if (check_if_image($filename))
			{
				$svnurl = parse_url(ROSTER_SVNREMOTE);
				$svnpath = pathinfo($svnurl['path'], PATHINFO_DIRNAME);
				$svnurl = $svnurl['scheme'].'://'.$svnurl['host'].$svnpath.'/';
				$diffcheck = '<table><tr><th colspan="3" class="membersHeaderRight">SVN Image</th></tr><tr><td>&nbsp;</td><td><img src="'.$svnurl.$filename.'" alt="" /></td><td>&nbsp;</td></tr><tr><td colspan="3">&nbsp;</td></tr></table>';
			}
			else
			{
				$diffcheck = '<table width="100%" border="0" cellspacing="0"><tr><th class="membersHeader">SVN File</th></tr>';
				$diffcheck .= '<tr><td>'.highlight_php($filesvnsource).'</td></tr>';
				$diffcheck .= '</table>';
			}
		}

		print('<table width="100%" border="0"><tr valign="top"><td align="center">'."\n");
		print(border('syellow','start','MD5 Information for file: '.$filename)."\n");
		print('<table width="100%" cellspacing="0" border="0" class="bodyline"><tr><td class="membersRow1">Remote:</td><td class="membersRowRight1">'.$md5remote."</td>\n");
		print("</tr><tr>\n");
		print('<td class="membersRow2">Local:</td><td class="membersRowRight2">'.$md5local."</td>\n");
		print("</tr></table>\n");
		print(border('syellow','end').'<br />');

		print('<td>&nbsp;</td><td align="center">');

		print(border('sblue','start','Back Link'));
		print('<table width="100%" cellspacing="0" border="0" class="bodyline">');
		print('<tr><td class="membersRowRight2"><form method="post" action="'.makelink().'">');
		print ('<input type="hidden" name="filename" value="'.$filename.'" />');
		print ('<input type="hidden" name="downloadsvn" value="savefile" />');
		print('<input type="button" value="[ RETURN TO ROSTERDIAG ]" onclick="history.go(-1);return false;" />');
		print('</form></td></tr></table>');
		print(border('sblue','end'));

		print('</td></tr><tr><td colspan="3">');
		if (isset($_POST['downmode']) && $_POST['downmode'] == 'install')
		{
			$diffwindow = 'File Contents:&nbsp;&nbsp;';
		}
		else
		{
			$diffwindow = 'File Differences for file:&nbsp;&nbsp;';
		}
		print('<div align="center" style="width:100%;">'.border('sblue','start',$diffwindow.$filename));
		print ($diffcheck);
		print(border('sblue','end').'</div>');
		print('</td></tr></table>');

	}
	else
	{
		print(border('sred','start','ERROR').'<div class="membersRow1">UNSPECIFIED ACTION<br />If you get this page, you probably are trying to exploit the system!</div>'.border('sred','end'));
	}

	include_once(ROSTER_BASE.'roster_footer.tpl');
	exit();
}

// Include the menu-box
$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');

// Diplay Password Box
if ( $roster_login->getAuthorized() )
{
	print('<span class="title_text">Roster Diag</span><br />'.$roster_login->getMessage());
}
else
{
	print('<span class="title_text">Roster Diag</span><br />'.$roster_login->getMessage().$roster_login->getLoginForm());
}

echo "<br />\n";

// Display config errors
echo ConfigErrors();

echo "<br />\n";

// Table display fix
echo "<table cellspacing=\"6\"><tr><td valign=\"top\">\n";

// Display basic server info
$rowstripe = 0;
echo border('syellow','start','Basic Server Info').'
<table width="300" class="bodyline" cellspacing="0">
	<tr>
		<td class="membersRow'.((($rowstripe=0)%2)+1).'">OS</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.php_uname('s').'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">Server Software</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'" style="white-space:normal;">'.$_SERVER['SERVER_SOFTWARE'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">MySQL Version</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.mysql_get_server_info().'</td>
	</tr>
</table>'.
border('syellow','end').'
<br />
'.
border('syellow','start','PHP Settings').'
<table width="300" class="bodyline" cellspacing="0">
	<tr>
		<td class="membersRow'.((($rowstripe=0)%2)+1).'">PHP Version</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.PHP_VERSION.'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">PHP API Type</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.php_sapi_name().'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">safe_mode</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.onOff(ini_get('safe_mode')).'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">open_basedir</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.onOff(ini_get('open_basedir')).'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">allow_url_fopen</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.onOff(ini_get('allow_url_fopen')).'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">file_uploads</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.onOff(ini_get('file_uploads')).'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">upload_max_filesize</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.ini_get('upload_max_filesize').'</td>
	</tr>
</table>
'.border('syellow','end');



// Table display fix
echo "</td><td valign=\"top\">\n";



// Display GD info
echo describeGDdyn();


// Table display fix
echo "</td></tr></table>\n";
echo "<table cellspacing=\"6\"><tr><td valign=\"top\">\n";

// Display conf.php info

echo border('sblue','start','Config Values&nbsp;&nbsp;&nbsp;<i><small><a href="'.makelink('rosterdiag&amp;printconf=1').'" target="_blank">Show Entire $roster_conf array</a></small></i>').
'<table width="100%" class="bodyline" cellspacing="0">
	<tr>
		<td class="membersRow'.((($rowstripe=0)%2)+1).'">version</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['version'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">db_version</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['roster_dbver'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">db_name</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$db_name.'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">db_host</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$db_host.'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">db_prefix</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$db_prefix.'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">sqldebug</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.onOff($roster_conf['sqldebug']).'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">roster_lang</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['roster_lang'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">img_url</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['img_url'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">interface_url</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['interface_url'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">img_suffix</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['img_suffix'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">guild_name</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['guild_name'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">server_name</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['server_name'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">use_update_triggers</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.onOff($roster_conf['use_update_triggers']).'</td></tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">realmstatus_url</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$roster_conf['realmstatus_url'].'</td>
	</tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">realmstatus</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.($roster_conf['realmstatus'] != '' ? $roster_conf['realmstatus'] : 'Uses server_name').'</td></tr>
	<tr>
		<td class="membersRow'.(((++$rowstripe)%2)+1).'">rs_mode</td>
		<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.onOff($roster_conf['rs_mode']).'</td>
	</tr>
</table>
'.border('sblue','end')."
<br />\n";


// Table display fix
echo "</td><td valign=\"top\">\n";


// Display MySQL Tables
echo border('sgray','start','List of tables in ['.$db_name.']').
'<table width="100%" class="bodyline" cellspacing="0">'."\n";

$result = $wowdb->query("SHOW TABLES FROM `$db_name`;");
if( !$result )
{
	echo '<tr><td class="membersRow1">DB Error, could not list tables<br />'."\n";
	echo 'MySQL Error: '.$wowdb->error().'</td></tr>'."\n";
}
else
{
	$rowstripe = 1;
	while( $row = $wowdb->fetch_array($result) )
	{
		echo '<tr><td class="membersRowRight'.(((++$rowstripe)%2)+1).'">'.$row[0].'</td></tr>'."\n";
	}
}
echo "</table>\n".border('sgray','end');
$wowdb->free_result($result);


// Table display fix
echo "</td></tr></table>\n<br />\n";

// File Versioning Information
if (ini_get('allow_url_fopen') && GrabRemoteVersions() !== false )
{
	//GrabRemoteVersions();
	VerifyVersions();

	// Make a post form for the download of a Zip Package
	if ( $roster_login->getAuthorized() )
	{
		$zippackage_files = '';
		foreach ($directories as $directory => $filecount)
		{
			if (isset($files[$directory]))
			{
				foreach ($files[$directory] as $file => $filedata)
				{
					if($filedata['update'])
					{
						if (isset($file) && $file != 'severity' && $file != 'tooltip' && $file != 'rollup' && $file != 'rev' && $file != 'date' && $file != 'author' && $file != 'md5' && $file != 'update' && $file != 'missing')
						{
							if ($zippackage_files != '')
							{
								$zippackage_files .= ';';
							}
							$zippackage_files .= $directory.'/'.$file;
						}
					}
				}
			}
		}

		if ($zippackage_files != '')
		{
			echo border('spurple', 'start', '<span class="blue">Download Update Package</span>');
			echo '<div align="center" style="background-color:#1F1E1D;"><form method="post" action="'.ROSTER_SVNREMOTE.'">';
			echo '<input type="hidden" name="filestoget" value="'.$zippackage_files.'" />';
			echo '<input type="hidden" name="guildname" value="'.$roster_conf['guild_name'].'" />';
			echo '<input type="hidden" name="website" value="'.$roster_conf['website_address'].'" />';
			echo '<input type="radio" name="ziptype" id="zip" value="zip" checked="checked" /><label for="zip">.zip Archive</label><br />';
			echo '<input type="radio" name="ziptype" id="targz" value="targz" /><label for="targz">.tar.gz Archive</label><br /><br />';
			echo '<input style="decoration:bold;" type="submit" value="[GET UPDATE PACKAGE]" /><br />';
			echo '</form></div>';
			echo border('spurple', 'end').'<br />';
		}
	}

	// Open the main FileVersion table in total color
	echo border('sgray', 'start', '<span class="blue">File Versions:</span> <small style="color:#6ABED7;font-weight:bold;"><i>Roster File Validator @ '.str_replace('version_match.php', '', ROSTER_SVNREMOTE).'</i></small>');

	// Get all the gathered information and display it in a table
	foreach ($directories as $directory => $filecount)
	{
		if (isset($files[$directory]))
		{
			//echo $directory.', '.$files[$directory]['tooltip'].'<br>';
			$dirtooltip = str_replace("'", "\'", $files[$directory]['tooltip']);
			$dirtooltip = str_replace('"','&quot;', $dirtooltip);
			$directory_id = str_replace(array('.','/','\\'),'', $directory);

			$dirshow = substr_replace($directory, substr(ROSTER_PATH,1,-1), 0, 1);


			$headertext_max = '<div style="cursor:pointer;width:800px;text-align:left;" onclick="swapShow(\''.$directory_id.'TableShow\',\''.$directory_id.'TableHide\')" '.
			'onmouseover="overlib(\''.$dirtooltip.'\',CAPTION,\''.$dirshow.'/&nbsp;&nbsp;-&nbsp;&nbsp;'.$severity[$files[$directory]['rollup']]['severityname'].'\',WRAP);" onmouseout="return nd();">'.
			'<div style="float:right;"><span style="color:'.$severity[$files[$directory]['rollup']]['color'].';">'.$severity[$files[$directory]['rollup']]['severityname'].'</span> <img class="membersRowimg" src="'.$roster_conf['img_url'].'plus.gif" alt="" /></div>'.$dirshow.'/</div>';

			$headertext_min = '<div style="cursor:pointer;width:800px;text-align:left;" onclick="swapShow(\''.$directory_id.'TableShow\',\''.$directory_id.'TableHide\')" '.
			'onmouseover="overlib(\''.$dirtooltip.'\',CAPTION,\''.$dirshow.'/&nbsp;&nbsp;-&nbsp;&nbsp;'.$severity[$files[$directory]['rollup']]['severityname'].'\',WRAP);" onmouseout="return nd();">'.
			'<div style="float:right;"><span style="color:'.$severity[$files[$directory]['rollup']]['color'].';">'.$severity[$files[$directory]['rollup']]['severityname'].'</span> <img class="membersRowimg" src="'.$roster_conf['img_url'].'minus.gif" alt="" /></div>'.$dirshow.'/</div>';


			echo '<div style="display:none;" id="'.$directory_id.'TableShow">';
			echo border($severity[$files[$directory]['rollup']]['style'],'start',$headertext_min);


			echo '<table width="100%" cellpadding="0" cellspacing="0" class="bodyline">';
			echo '<tr><th class="membersHeader">Filename</th><th class="membersHeader">Revision</th><th class="membersHeader">Date</th><th class="membersHeader">Author</th><th class="membersHeader">MD5 Match</th><th class="membersHeaderRight">SVN</th>';
			echo '</tr>';
			$row=0;
			foreach ($files[$directory] as $file => $filedata)
			{
				if ($row==1)
					$row=2;
				else
					$row=1;

				if (isset($filedata['tooltip']))
				{
					$filetooltip = str_replace("'", "\'", $filedata['tooltip']);
					$filetooltip = str_replace('"','&quot;', $filetooltip);
				}
				else
				{
					$filetooltip = 'Unknown';
				}
				if (isset($file) && $file != 'severity' && $file != 'tooltip' && $file != 'rollup' && $file != 'rev' && $file != 'date' && $file != 'author' && $file != 'md5' && $file != 'update' && $file != 'diff' && $file != 'missing')
				{
					echo '<tr style="cursor:help;" onmouseover="overlib(\'<span style=&quot;color:blue;&quot;>'.$filetooltip.'</span>\',CAPTION,\''.$file.'/&nbsp;&nbsp;-&nbsp;&nbsp;'.$severity[$filedata['rollup']]['severityname'].'\',WRAP);" onmouseout="return nd();">';
					echo '<td class="membersRow'.$row.'"><span style="color:'.$severity[$filedata['rollup']]['color'].'">'.$file.'</span></td>';
					echo '<td class="membersRow'.$row.'">'."\n";
					if (isset($filedata['rev']))
					{
						echo $filedata['rev'];
					}
					else
					{
						echo 'Unknown Rev';
					}
					echo "</td>\n";
					echo '<td class="membersRow'.$row.'">';
					if (isset($filedata['date']))
					{
						echo $filedata['date'];
					}
					else
					{
						echo 'Unknown Date';
					}
					echo "</td>\n";
					echo '<td class="membersRow'.$row.'">';
					if (isset($filedata['author']))
					{
						echo $filedata['author'];
					}
					else
					{
						echo 'Unknown Author';
					}
					echo "</td>\n";
					echo '<td class="membersRow'.$row.'">';
					if (isset($filedata['md5']))
					{
						echo $filedata['md5'];
					}
					else
					{
						echo 'Unknown';
					}
					echo "</td>\n";
					echo '<td class="membersRowRight'.$row.'">'."\n";
					if($filedata['diff'] || $filedata['missing'])
					{
						echo '<form method="post" action="'.makelink().'">'."\n";
						echo "<input type=\"hidden\" name=\"filename\" value=\"".$directory.'/'.$file."\" />\n";
						echo "<input type=\"hidden\" name=\"downloadsvn\" value=\"confirmation\" />\n";
						if (isset($filedata['diff']) && $filedata['diff'])
						{
							echo "<input type=\"hidden\" name=\"downmode\" value=\"update\" />\n";
							echo "<input type=\"submit\" value=\"Diff Check\" />\n";
						}
						elseif (isset($filedata['missing']) && $filedata['missing'])
						{
							echo "<input type=\"hidden\" name=\"downmode\" value=\"install\" />\n";
							echo "<input type=\"submit\" value=\"Show File\" />\n";
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

			echo border($severity[$files[$directory]['rollup']]['style'],'end').'</div>';
			echo '<div id="'.$directory_id.'TableHide">';
			echo border($severity[$files[$directory]['rollup']]['style'],'start',$headertext_max);
			echo border($severity[$files[$directory]['rollup']]['style'],'end').'</div>';
		}
	}
	echo border('sgray', 'end');
}
else
{
	// FOPEN URL is Not Supported, offer the oppertunity to do this remotely
	echo '<form method="post" action="'.ROSTER_SVNREMOTE.'">';
	echo '<input type="hidden" name="remotediag" value="true" />';
	echo '<input type="hidden" name="guildname" value="'.$roster_conf['guild_name'].'" />';
	echo '<input type="hidden" name="website" value="'.	ROSTER_PATH .'" />';

	foreach ($files as $directory => $filedata)
	{
		foreach ($filedata as $filename => $file)
		{
			echo '<input type="hidden" name="files['.$directory.']['.$filename.'][versionDesc]" value="'.$file['local']['versionDesc'].'" />';
			echo '<input type="hidden" name="files['.$directory.']['.$filename.'][versionRev]" value="'.$file['local']['versionRev'].'" />';
			echo '<input type="hidden" name="files['.$directory.']['.$filename.'][versionDate]" value="'.$file['local']['versionDate'].'" />';
			echo '<input type="hidden" name="files['.$directory.']['.$filename.'][versionAuthor]" value="'.$file['local']['versionAuthor'].'" />';
			echo '<input type="hidden" name="files['.$directory.']['.$filename.'][versionMD5]" value="'.$file['local']['versionMD5'].'" />';
		}
	}
	echo border('sblue','start','File Version Information');
	echo '<div class="membersRowRight1"><div align="center"><b>fopen_url</b> is <span style="color:red;">NOT Supported</span> by your Web Server.<br />Please press the button to process the File Verion Check remotely';
	echo '<br /><br /><input type="submit" value="Check files Remotely"></div></div>';
	echo border('sblue','end');
}



include_once(ROSTER_BASE.'roster_footer.tpl');
