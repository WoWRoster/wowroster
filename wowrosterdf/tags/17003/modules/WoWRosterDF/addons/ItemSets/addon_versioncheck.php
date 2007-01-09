<?php
$versions['versionDate']['addon_versioncheck'] = '$Date: 2006/03/15 22:00:00  $'; 
$versions['versionRev']['addon_versioncheck'] = '$Revision: 1.00 $'; 
$versions['versionAuthor']['addon_versioncheck'] = '$Author: Mathos -alias- Beela $'; 

//!!!!!!!!!!!!!// AddOn Developer Config //!!!!!!!!!!!!!//
// As a NON-Developer of this mod, please do not modify any content of this file, or the version check might fail!!!

// CVS Remote -> Please make a page on the web where you place the most rescent version of the files, including this file.
//               The webpage must be entered below without a trailing slash 
//               (ie.:  $cvsremote = 'http://www.mydomain.com/roster_cvs/addons/guildbank'; )
$cvsremote = 'http://fantasyworld.nl/downloads/wowroster_mirror/addons/itemsets/cvs/itemsets';

//!!!!!!!!!!!!!// Do NOT edit anything below //!!!!!!!!!!!!!//

// Check if we are trying this remotely
if ($_GET['check'] == 'remote') {
	$check='remote';
} else {
	$check='local';
}

// Set some characters we will use for exploding the data streams
$explode = '*;*';
$break = '<br>';

// Make an array to store the file info in
$files = array();

// Grab all the local file info and place it into the $files array
GrabLocalVersions();

// Check if this script has been run remotely via the $check post variable
if ($check == 'remote') {
	// Set an emtpy string, and query/prepare the complete set of local versions
	// for transmission to the other side.
	$string = '';
	foreach($files as $filename => $fileinfo) {
		echo $fileinfo['local']['versionFile'].$explode;
		echo $fileinfo['local']['versionDesc'].$explode;
		echo $fileinfo['local']['versionRev'].$explode;
		echo $fileinfo['local']['versionDate'].$explode;
		echo $fileinfo['local']['versionAuthor'].$explode;
		echo $fileinfo['local']['versionMD5'];
		echo $break;
	}
}

// Check if this script has been run locally via the $check post variable
if ($check == 'local') {
	// Execute the addon_versioncheck.php script in the CVS remote site
	$handle = fopen($cvsremote.'/addon_versioncheck.php?check=remote', 'rb');
	$contents = '';
	// Read the first 80kb from the file. This should be enough for most add-ons.
	while (!feof($handle)) {
  	$contents .= fread($handle, 1024*80);
	}
	fclose($handle);
	
	// Break the header into lines
	$remoteversions = explode($break, $contents);
	foreach ($remoteversions as $remoteversion) {
		// Break the line into strings
		$remoteversion = explode($explode, $remoteversion);

		// Insert the file info into the $files array
		$filename = $remoteversion[0];
		$files[$filename]['remote']['versionFile'] = $filename;
		$files[$filename]['remote']['versionDesc'] = $remoteversion[1];
		$files[$filename]['remote']['versionRev'] = $remoteversion[2];
		$files[$filename]['remote']['versionDate'] = $remoteversion[3];
		$files[$filename]['remote']['versionAuthor'] = $remoteversion[4];
		$files[$filename]['remote']['versionMD5'] = $remoteversion[5];
	}
	echo '<table border="1"><tr><th>Filename</th><th>Local Desc.</th><th>CVS Desc.</th><th>Local Revision</th><th>Remote Revision</th><th>Local Date</th><th>Remote Date</th><th>Local Author</th><th>Remote Author</th><th>Local MD5</th><th>Remote MD5</th></tr>';
	foreach($files as $filename => $fileinfo) {
		if ($filename) {
			echo '<tr><td><b>'.$filename.'</b></td>';
			
			echo '<td>'.$fileinfo['local']['versionDesc'].'</td><td>'.$fileinfo['remote']['versionDesc'].'</td>';
			echo '<td>'.$fileinfo['local']['versionRev'].'</td><td>'.$fileinfo['remote']['versionRev'].'</td>';
			echo '<td>'.date('Y/m/d H:i:s', $fileinfo['local']['versionDate']).'</td><td>'.date('Y/m/d H:i:s', $fileinfo['remote']['versionDate']).'</td>';
			echo '<td>'.$fileinfo['local']['versionAuthor'].'</td><td>'.$fileinfo['remote']['versionAuthor'].'</td>';
			echo '<td>'.$fileinfo['local']['versionMD5'].'</td><td>'.$fileinfo['remote']['versionMD5'].'</td>';
			
			echo '</tr><tr><th><b>Result:</b></th><td colspan="2">';
			
			if (!strcasecmp($fileinfo['local']['versionDesc'], $fileinfo['remote']['versionDesc'])) {
				echo '<span style="color: #017604;font-weight: bold;">Same as CVS</span>';
			} else {
				echo '<span style="color: #D20C0C;font-weight: bold;">CVS does NOT match</span>';
			}
			
			echo '</td><td colspan="2">';
			
			$vercompare = version_compare($fileinfo['local']['versionRev'], $fileinfo['remote']['versionRev']);
			if (!$vercompare) {
								echo '<span style="color: #017604;font-weight: bold;">Same as CVS</span>';
			} elseif ($vercompare > 0) {
				echo '<span style="color: #D20C0C;font-weight: bold;">Version in CVS is OLDER</span>';
			} elseif ($vercompare < 0) {
				echo '<span style="color: #D116D8;font-weight: bold;">Version in CVS is NEWER</span>';
			}
			
			echo '</td><td colspan="2">';
			
			if ($fileinfo['local']['versionDate'] == $fileinfo['remote']['versionDate']) {
				echo '<span style="color: #017604;font-weight: bold;">Same as CVS</span>';
			} elseif ($fileinfo['local']['versionDate'] > $fileinfo['remote']['versionDate']) {
				echo '<span style="color: #D20C0C;font-weight: bold;">Version in CVS is OLDER</span>';
			} elseif ($fileinfo['local']['versionDate'] < $fileinfo['remote']['versionDate']) {
				echo '<span style="color: #D116D8;font-weight: bold;">Version in CVS is NEWER</span>';
			}
			
			echo '</td><td colspan="2">';
			
			if (!strcasecmp($fileinfo['local']['versionAuthor'], $fileinfo['remote']['versionAuthor'])) {
				echo '<span style="color: #017604;font-weight: bold;">Same as CVS</span>';
			} else {
				echo '<span style="color: #D116D8;font-weight: bold;">CVS does NOT match</span>';
			}
			
			echo '</td><td colspan="2">';

		if (!strcasecmp($fileinfo['local']['versionMD5'], $fileinfo['remote']['versionMD5'])) {
				echo '<span style="color: #017604;font-weight: bold;">Same as CVS</span>';
			} else {
				echo '<span style="color: #D116D8;font-weight: bold;">CVS does NOT match</span>';
			}
			
			echo '</td></tr><tr height="10"></tr>';

		}
	}
	echo '</table>';
}

// This function will determine all version of all files in the current directory 
// and will fill the $versions array with this data
function GrabLocalVersions() {
	global $files;
	if ($handle = opendir('.')) {
  	while ($filename = readdir($handle)) {
			if ($filename != '.' && $filename != '..') {

				// Read the first 2k of the file, which should be enough to grab the $fileheader
				$fp = fopen($filename, 'rb');
				$fileheader = fread($fp, 2048);
				fclose($fp);

				$files[$filename]['local']['versionFile'] = $filename;
				// Check if we have a version entry for the Date string and also capture the brief indication of which addon this is
				if (preg_match('~\$versions\[\'versionDate\'\]\[\'(.+?)\'\]\s\=\s\'\$Date\:\s(.+?)\s\$\'\;~', $fileheader, $local_version) == 1) {
					$files[$filename]['local']['versionDesc'] = $local_version[1];
					$files[$filename]['local']['versionDate'] = strtotime($local_version[2]);
				} else {
					$files[$filename]['local']['versionDesc'] = 0;
					$files[$filename]['local']['versionDate'] = 0;
				}
				// Check if we have a version entry for the Revision string
				if (preg_match('~\$versions\[\'versionRev\'\]\[\'(.+?)\'\]\s\=\s\'\$Revision\:\s(.+?)\s\$\'\;~', $fileheader, $local_version) == 1) {
					$files[$filename]['local']['versionRev'] = $local_version[2];
				} else {
					$files[$filename]['local']['versionRev'] = 0;
				}
				// Check if we have a version entry for the Author string
				if (preg_match('~\$versions\[\'versionAuthor\'\]\[\'(.+?)\'\]\s\=\s\'\$Author\:\s(.+?)\$\'\;~', $fileheader, $local_version) == 1) {
					$files[$filename]['local']['versionAuthor'] = $local_version[2];
				} else {
					$files[$filename]['local']['versionAuthor'] = 0;
				}
				if (!$files[$filename]['local']['versionMD5'] = md5_file($filename)) {
					$files[$filename]['local']['versionMD5'] = 0;
				}
			}
		}
	}
	closedir($handle);
}
?>