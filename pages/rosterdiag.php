<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Diagnostics and info
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterDiag
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

// Set the title for the header
$roster->output['title'] = $roster->locale->act['rosterdiag'];

// Include the library for RosterDiag
include_once(ROSTER_LIB.'rosterdiag.lib.php');

echo '<div class="roster-panel">
	<div class="container">
		<div class="tier-1-a">
			<div class="tier-1-b">
				<div class="tier-1-c">
					<div class="tier-1-title">
						<div class="icon">
							<img src="' . $roster->config['interface_url'] . 'Interface/Icons/inv_misc_gear_02.' . $roster->config['img_suffix'] . '" alt="" />
							<div class="mask"></div>
						</div>
						' . $roster->locale->act['rosterdiag'] . '
					</div>
';

// Loging in as Admin to allow up- / downgrades && Downloads

// If the entire config page is requested, display only THAT
if( isset($_GET['printconf']) && $_GET['printconf'] == 1 )
{
	echo '<div align="left">';
	aprint($roster->config);
	echo '</div></div></div></div></div></div>'; // End tier 1 element

	return;
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

		$md5remote = urlgrabber(ROSTER_SVNREMOTE.'?getfile='.$filename.'&mode=md5');
		if ($md5remote===false)
		{
			roster_die("[ERROR] Cannot Read MD5 Remote File\n");
		}

		$filesvnsource = urlgrabber(ROSTER_SVNREMOTE.'?getfile='.$filename.'&mode=diff');
		if ($filesvnsource===false)
		{
			roster_die("[ERROR] Cannot Read Remote File\n");
		}

		if (file_exists($filename) && is_file($filename) && filesize($filename))
		{
			$rhheaderlocal = fopen($filename, 'rb');
			if ($rhheaderlocal===false)
			{
				roster_die("[ERROR] Cannot Read Local File\n");
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
				$diffcheck = '<table width="100%" border="0" cellspacing="0"><tr><th class="membersHeader">Local Image</th><th class="membersHeaderRight">SVN Image</th></tr>';
				$diffcheck .= '<tr><td class="membersRow1"><img src="'.$filename.'" alt="Local Image" /></td><td class="membersRowRight1"><img src="'.$svnurl.$filename.'" alt="SVN Image" /></td></tr>';
				$diffcheck .= '</table>';
			}
			else
			{
				$diffcheck = '<table width="100%" border="0" cellspacing="0"><tr><th class="membersHeader">Type</th><th class="membersHeader">Local File</th><th class="membersHeaderRight">SVN File</th></tr>';
				$difffiles = difffile($filelocalsource, $filesvnsource);
				$row_color=2;
				foreach ($difffiles as $difference)
				{
					if($row_color==1)
					{
						$row_color=2;
					}
					else
					{
						$row_color=1;
					}

					$rowfile1 = explode(",", $difference['rownr1']);
					$rowfile2 = explode(",", $difference['rownr2']);

					$diffcheck .= "<tr valign=\"top\">";

					$diffcheck .= '<td class="membersRow'.$row_color.'">';
					$diffcheck .= '<span class="'.$difference['color'].'">'.$difference['action'].'</span>';

					$diffcheck .= '</td><td class="membersRow'.$row_color.'">';


					if (isset($difference['from']))
					{
						$diffcheck .= highlight_php(implode("\n",$difference['from']), $rowfile1[0]);
					}
					$diffcheck .= '</td><td class="membersRowRight'.$row_color.'">';
					if (isset($difference['to']))
					{
						$diffcheck .= highlight_php(implode("\n",$difference['to']), $rowfile2[0]);
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
				$svnpath = pathinfo($svnurl ['path'], PATHINFO_DIRNAME);
				$svnurl = $svnurl['scheme'] . '://' . $svnurl ['host'] . $svnpath . '/';
				$diffcheck = '<table width="100%" border="0" cellspacing="0">'
						   . '<tr><th class="membersHeaderRight">SVN Image</th></tr>'
						   . '<tr><td class="membersRowRight1"><img src="'. $svnurl . $filename . '" alt="" /></td></tr>'
						   . '<tr><td class="membersRowRight2">&nbsp;</td></tr></table>';
			}
			else
			{
				$diffcheck = '<table width="100%" border="0" cellspacing="0">'
						   . '<tr><th class="membersHeaderRight">SVN File</th></tr>'
						   . '<tr><td class="membersRowRight1">'. highlight_php(str_replace("\r\n", "\n", $filesvnsource)) . '</td></tr>'
						   . '</table>';
			}
		}

		print '<table width="100%" border="0" cellspacing="6"><tr><td valign="top" align="right">' . "\n";

		print border('syellow', 'start', 'MD5 Information for file: ' . $filename) . "\n";
		print '<table width="100%" cellspacing="0" border="0">';
		print '<tr><td class="membersRow1">Remote:</td><td class="membersRowRight1">' . $md5remote . "</td></tr>\n";
		print '<tr><td class="membersRow2">Local:</td><td class="membersRowRight2">' . $md5local . "</td></tr>\n";
		print "</table>\n";
		print border('syellow', 'end');

		print '</td><td>&nbsp;</td><td valign="top" align="left">';

		print border('sblue', 'start', 'Back Link');
		print '<table width="100%" cellspacing="0" border="0">';
		print '<tr><td class="membersRowRight2"><form method="post" action="' . makelink() . '">';
		print '<input type="hidden" name="filename" value="' . $filename . '" />';
		print '<input type="hidden" name="downloadsvn" value="savefile" />';
		print '<input type="button" value="[ RETURN TO ROSTERDIAG ]" onclick="history.go(-1);return false;" />';
		print '</form></td></tr></table>';
		print border('sblue', 'end');

		print '</td></tr></table><br />' ;

		if (isset($_POST['downmode']) && $_POST['downmode'] == 'install')
		{
			$diffwindow = 'File Contents:&nbsp;&nbsp;';
		}
		else
		{
			$diffwindow = 'File Differences for file:&nbsp;&nbsp;';
		}
		print border('sblue', 'start', $diffwindow . $filename, '90%');
		print $diffcheck;
		print border('sblue', 'end');
		print '</div></div></div></div></div>'; // End tier 1 element

	}
	else
	{
		roster_die('If you get this page, you probably are trying to exploit the system!','UNSPECIFIED ACTION');
	}

	return;
}

// Display config errors
echo ConfigErrors();

// Table display fix
echo "<table width=\"100%\" border=\"0\" cellspacing=\"6\"><tr><td valign=\"top\" style=\"width:50%;\">\n";

// Display basic server info
$rowstripe = 0;
echo '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title">Basic Server Info</div>
		<table class="border_frame" width="100%" cellspacing="0">
			<tr>
				<td class="membersRow'. ((($rowstripe = 0) % 2) + 1) . '">OS</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . php_uname('s') . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">Server Software</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '" style="white-space:normal;">' . $_SERVER ['SERVER_SOFTWARE'] . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">MySQL Version</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->db->server_info() . '</td>
			</tr>
		</table>
	</div>
</div>

<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title">PHP Settings</div>
		<table class="border_frame" width="100%" cellspacing="0">
			<tr>
				<td class="membersRow'. ((($rowstripe = 0) % 2) + 1) . '">PHP Version</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . PHP_VERSION . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">PHP API Type</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . php_sapi_name() . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">safe_mode</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . onOffRev(ini_get('safe_mode')) . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">open_basedir</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . onOffRev(ini_get('open_basedir')) . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">allow_url_fopen</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . onOff(ini_get('allow_url_fopen')) . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">file_uploads</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . onOff(ini_get('file_uploads')) . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">upload_max_filesize</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . ini_get('upload_max_filesize') . '</td>
			</tr>
		</table>
	</div>
</div>';



// Table display fix
echo "</td><td valign=\"top\" style=\"width:50%;\">\n";



// Display GD info
echo '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title">GD Support</div>
' . describeGDdyn() . '
	</div>
</div>';


// Table display fix
echo "</td></tr></table>\n";
echo "<table width=\"100%\" border=\"0\" cellspacing=\"6\"><tr><td valign=\"top\" style=\"width:50%;\">\n";

// Display conf.php info

echo '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title">Config Values</div>
		<div class="tier-3-a">
			<div class="tier-3-b">
				<div class="text">
					<em><a href="' . makelink('rosterdiag&amp;printconf=1') . '" target="_blank">Show All Config Data</a></em>
				</div>
			</div>
		</div>
		<table class="border_frame" width="100%" cellspacing="0">
			<tr>
				<td class="membersRow'. ((($rowstripe = 0) % 2) + 1) . '">version</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->config ['version'] . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">db_version</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->config ['roster_dbver'] . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">db_prefix</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->db->prefix . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">debug_mode</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . onOffRev($roster->config ['debug_mode']) . ($roster->config ['debug_mode'] == 2 ? ' (extended)' : '') . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">roster_lang</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->config ['locale'] . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">img_url</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->config ['img_url'] . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">interface_url</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->config ['interface_url'] . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">img_suffix</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . $roster->config ['img_suffix'] . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">use_update_triggers</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . onOff($roster->config ['use_update_triggers']) . '</td>
			</tr>
			<tr>
				<td class="membersRow' . (((++$rowstripe) % 2) + 1) . '">rs_mode</td>
				<td class="membersRowRight' . ((($rowstripe) % 2) + 1) . '">' . onOff($roster->config ['rs_mode']) . '</td>
			</tr>
		</table>
	</div>
</div>';


// Table display fix
echo "</td><td valign=\"top\" style=\"width:50%;\">\n";

// Display MySQL Tables
$sql_tables = '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title">MySQL Tables</div>';

$result = $roster->db->query("SHOW TABLES;");
if( !$result )
{
	$sql_tables .= '<tr><td class="membersRow1">DB Error, could not list tables<br />' . "\n";
	$sql_tables .= 'MySQL Error: ' . $roster->db->error() . '</td></tr>' . "\n";
}
else
{
	$rowstripe = 1;
	$table_list = "\n<table class=\"border_frame\" style=\"width:100%;\" cellspacing=\"0\">\n";
	while( $row = $roster->db->fetch($result) )
	{
		$table_list .= '<tr><td class="membersRowRight' . (((++$rowstripe) % 2) + 1) . '">' . $row [0] . '</td></tr>' . "\n";
	}
	$table_list .= "</table>\n";
	$roster->db->free_result($result);
}
$sql_tables .= scrollbox($table_list, '', '', '100%', '231px');
$sql_tables .= "</div>\n</div>\n";

echo $sql_tables;


// Table display fix
echo "</td></tr></table>\n";

// File Versioning Information
if( GrabRemoteVersions() !== false )
{
	//GrabRemoteVersions();
	VerifyVersions();

	$zippackage_files = '';

	// Make a post form for the download of a Zip Package
	foreach ($directories as $directory => $filecount)
	{
		if (isset($files[$directory]))
		{
			foreach ($files[$directory] as $file => $filedata)
			{
				if($filedata['update'])
				{
					if (isset($file) && $file != 'newer' && $file != 'severity' && $file != 'tooltip' && $file != 'rollup' && $file != 'rev' && $file != 'date' && $file != 'author' && $file != 'md5' && $file != 'update' && $file != 'missing')
					{
						if ($zippackage_files != '')
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
		// Display Password Box
		if( !$roster->auth->getAuthorized( ROSTERLOGIN_ADMIN ) )
		{
			echo '
<div class="tier-2-a diag-download-box">
	<div class="tier-2-b">
		<div class="tier-2-title">' . $roster->locale->act['updates_available'] . '</div>

		<div class="info-text-h">
			' . $roster->locale->act['updates_available_message'] . '
		</div>
	</div>
</div>
';
			echo $roster->auth->getLoginForm(ROSTERLOGIN_ADMIN) . '<br />';
		}
		else
		{
			echo '
<div class="tier-2-a diag-download-box">
	<div class="tier-2-b">
		<div class="tier-2-title">' . $roster->locale->act['download_update_pkg'] . '</div>

		<div class="info-text-h">
			<form method="post" action="' . ROSTER_SVNREMOTE . '">
				<input type="hidden" name="filestoget" value="' . $zippackage_files . '" />
				<input type="hidden" name="guildname" value="' . $roster->config ['default_name'] . '" />
				<input type="hidden" name="website" value="' . $roster->config ['website_address'] . '" />
				<input type="radio" name="ziptype" id="zip" value="zip" checked="checked" /><label for="zip">' . $roster->locale->act['zip_archive'] . '</label><br />
				<input type="radio" name="ziptype" id="targz" value="targz" /><label for="targz">' . $roster->locale->act['targz_archive'] . '</label><br /><br />
				<input type="submit" value="' . $roster->locale->act['download_update'] . '" />
			</form>
		</div>
	</div>
</div>
<br />
';
		}
	}

	// Open the main FileVersion table in total color
	echo '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title">Roster File Validator</div>

		<div class="tier-5-a">
			<div class="tier-5-b">
				<div class="text">
					<small style="font-weight:bold;"><i>Validator @ '. str_replace('version_match.php', '', ROSTER_SVNREMOTE) . '</i></small>
				</div>
			</div>
		</div>
';

	// Get all the gathered information and display it in a table
	foreach ($directories as $directory => $filecount)
	{
		if (isset($files[$directory]))
		{
			//echo $directory.', '.$files[$directory]['tooltip'].'<br />';
			$dirshow = substr_replace($directory, substr(ROSTER_PATH, 1, -1), 0, 1);

			$dirtooltip = str_replace("'", "\\'", $files[$directory]['tooltip']);
			$dirtooltip = makeOverlib($dirtooltip, $dirshow . '/&nbsp;&nbsp;-&nbsp;&nbsp;' . $severity[$files[$directory]['rollup']]['severityname'], '', 2);

			$directory_id = str_replace(array('.', '/', '\\'), '', $directory);

			$headertext = '<div style="cursor:pointer;width:100%;text-align:left;" onclick="showHide(\'table_' . $directory_id . '\',\'img_' . $directory_id . '\',\'' . $roster->config['theme_path'] . '/images/button_open.png\',\'' . $roster->config['theme_path'] . '/images/button_close.png\');" ' . $dirtooltip . '>'
				. '<div style="float:right;"><span style="color:' . $severity[$files[$directory]['rollup']]['color'] . ';">' . $severity[$files[$directory]['rollup']]['severityname'] . '</span> <img id="img_' . $directory_id . '" src="' . $roster->config['theme_path'] . '/images/button_close.png" alt="" /></div>' . $dirshow . '/</div>';

			echo border($severity[$files[$directory]['rollup']]['style'], 'start', $headertext, '100%');

			echo '<table id="table_' . $directory_id . '" style="display:none;width:100%;" cellpadding="0" cellspacing="0">';
			echo '<tr><th class="membersHeader">Filename</th><th class="membersHeader">Revision</th><th class="membersHeader">Date</th><th class="membersHeader">Author</th><th class="membersHeader">MD5 Match</th><th class="membersHeaderRight">SVN</th>';
			echo '</tr>';
			$row=0;
			foreach ($files[$directory] as $file => $filedata)
			{
				if ($row == 1)
				{
					$row = 2;
				}
				else
				{
					$row = 1;
				}

				if (isset($file) && $file != 'newer' && $file != 'severity' && $file != 'tooltip' && $file != 'rollup' && $file != 'rev' && $file != 'date' && $file != 'author' && $file != 'md5' && $file != 'update' && $file != 'diff' && $file != 'missing')
				{
					if (isset($filedata['tooltip']))
					{
						$filetooltip = str_replace("'", "\\'", $filedata ['tooltip']);
						$filetooltip = makeOverlib($filetooltip, $file . '/&nbsp;&nbsp;-&nbsp;&nbsp;' . $severity[$filedata['rollup']]['severityname'], '', 2);
					}
					else
					{
						$filetooltip = makeOverlib('Unknown', 'Unknown', '', 2);
					}

					echo '<tr style="cursor:help;" ' . $filetooltip . '>';
					echo '<td class="membersRow' . $row . '"><span style="color:' . $severity[$filedata['rollup']]['color'] . '">' . $file . '</span></td>';
					echo '<td class="membersRow' . $row . '">' . "\n";
					if (isset($filedata['rev']))
					{
						echo $filedata['rev'];
					}
					else
					{
						echo 'Unknown Rev';
					}
					echo "</td>\n";
					echo '<td class="membersRow' . $row . '">';
					if (isset($filedata['date']))
					{
						echo $filedata['date'];
					}
					else
					{
						echo 'Unknown Date';
					}
					echo "</td>\n";
					echo '<td class="membersRow' . $row . '">';
					if (isset($filedata['author']))
					{
						echo $filedata['author'];
					}
					else
					{
						echo 'Unknown Author';
					}
					echo "</td>\n";
					echo '<td class="membersRow' . $row . '">';
					if (isset($filedata['md5']))
					{
						echo $filedata['md5'];
					}
					else
					{
						echo 'Unknown';
					}
					echo "</td>\n";
					echo '<td class="membersRowRight' . $row . '">' . "\n";
					if($filedata['diff'] || $filedata['missing'])
					{
						echo '<form method="post" action="' . makelink() . '">' . "\n";
						echo "<input type=\"hidden\" name=\"filename\" value=\"" . $directory . '/' . $file . "\" />\n";
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

			echo border($severity[$files[$directory]['rollup']]['style'], 'end');
		}
	}
	echo '</div></div>'; // End tier 2 element box
	echo '</div></div></div></div></div>'; // End tier 1 element
}
else
{
	// FOPEN URL is Not Supported, offer the oppertunity to do this remotely
	echo '<form method="post" action="' . ROSTER_SVNREMOTE . '">';
	echo '<input type="hidden" name="remotediag" value="true" />';
	echo '<input type="hidden" name="guildname" value="' . $roster->config['default_name'] . '" />';
	echo '<input type="hidden" name="website" value="' . ROSTER_PATH . '" />';

	foreach ($files as $directory => $filedata)
	{
		foreach ($filedata as $filename => $file)
		{
			echo '<input type="hidden" name="files[' . $directory . '][' . $filename . '][versionDesc]" value="' . $file['local']['versionDesc'] . '" />';
			echo '<input type="hidden" name="files[' . $directory . '][' . $filename . '][versionRev]" value="' . $file['local']['versionRev'] . '" />';
			echo '<input type="hidden" name="files[' . $directory . '][' . $filename . '][versionDate]" value="' . $file['local']['versionDate'] . '" />';
			echo '<input type="hidden" name="files[' . $directory . '][' . $filename . '][versionAuthor]" value="' . $file['local']['versionAuthor'] . '" />';
			echo '<input type="hidden" name="files[' . $directory . '][' . $filename . '][versionMD5]" value="' . $file['local']['versionMD5'] . '" />';
		}
	}
	echo border('sblue', 'start', 'File Version Information');
	echo '<div class="membersRowRight1"><div align="center">Cannot access Roster site for file integrity checking<br />Please press the button to perform a remote File Verion Check';
	echo '<br /><br /><input type="submit" value="Check files Remotely"></div></div>';
	echo border('sblue', 'end');
	echo '</div></div></div></div></div>'; // End tier 1 element
}
