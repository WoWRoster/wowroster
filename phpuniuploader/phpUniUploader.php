<?php
/******************************
 * WoWRoster.net  Roster
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

// ------------ !!! DO NOT EDIT ANYTHING IN THIS FILE !!! -------------- //

require_once 'settings.ini.php';
require_once 'pclzip.lib.php';
$luafiles = array();
$addons = array();
$post_data = array();
$phpUniSettings['SYNCHROURL'] = $UniAdminURL;

// Get the Settings from UniAdmin interface
$getsettings = GetSettings();

if ($getsettings)
{
	WriteToLog($getsettings);
	WriteToLog("Exiting....");
	exit(1);
}

// Check the WoW directory and account data
if (isset($WoWDir) && $WoWDir && is_dir($WoWDir))
{
	if (isset($AccountName) && $AccountName && is_dir($WoWDir.'/WTF/Account/'.$AccountName))
	{
		$phpUniSettings['SAVEDVARIABLES'] = $WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/';
	}
	else
	{
		WriteToLog("Can not find directory ".$WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/');
		WriteToLog("Please check if the WoW-Directory and Account Name variables are correct!!");
		WriteToLog("Exiting....");
		exit(1);
	}
}
else
{
	WriteToLog("Can not find directory ".$WoWDir);
	WriteToLog("Please check if the WoW-Directory variable is correct!!");
	WriteToLog("Exiting....");
	exit(1);
}

LoopHole();
 
function CheckFiles()
{
	global $phpUniSettings, $luafiles, $WoWDir, $AccountName;

	$filesupload = 0;
	if (isset($phpUniSettings['SVLIST']) && $phpUniSettings['SVLIST'])
	{
		$SV_Files = explode(":", $phpUniSettings['SVLIST']);
	}
	if (isset($SV_Files) && $SV_Files)
	{
		foreach ($SV_Files as $SV_File)
		{	
			if (is_file($WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/'.$SV_File.'.lua'))
			{
				if (!isset($luafiles[$SV_File]))
				{
					$luafiles[$SV_File]['md5'] = 0;
				}
				if ($luafiles[$SV_File]['md5'] != md5_file($WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/'.$SV_File.'.lua') || !$luafiles[$SV_File]['md5'])
				{
					
					$luafiles[$SV_File]['md5'] = md5_file($WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/'.$SV_File.'.lua');
					$luafiles[$SV_File]['upload'] = 1;
					$filesupload++;
				}
				else
				{
					$luafiles[$SV_File]['upload'] = 0;
				}
			}
		}
	}
	else
	{
		WriteToLog("No Saved Variables are defined at the UniAdmin site! Check with the WebAdmin.");
	}
	
	if ($filesupload)
	{
		WriteToLog("SV Files have changed. Uploading Files.");
		UploadFiles();
	}
}
	

function UploadFiles()
{
	global $post_data, $phpUniSettings, $luafiles, $WoWDir, $UploadResultLog, $AccountName, $RosterUpdatePassword, $UserAgentVersion;
	
	$post_data = array();
	if (isset($phpUniSettings['ADDVARNAME2']) && $phpUniSettings['ADDVARNAME2'] && isset($phpUniSettings['ADDVARVAL2']) && $phpUniSettings['ADDVARVAL2'])
	{
		if (isset($phpUniSettings['ADDVARVAL2']) && $phpUniSettings['ADDVARVAL2'])
		{
			$post_data[$phpUniSettings['ADDVARNAME2']] = $phpUniSettings['ADDVARVAL2'];
		}
	}
	elseif (isset($RosterUpdatePassword) && $RosterUpdatePassword)
	{
		if (isset($phpUniSettings['SENDPWSECURE']) && $phpUniSettings['SENDPWSECURE'])
		{
			$password = md5($RosterUpdatePassword);
		}
		else
		{
			$password = $RosterUpdatePassword;
		}

		$post_data['password'] = $password;
	}
	
	foreach ($luafiles as $svfile => $luafile)
	{
		if (isset($luafile['upload']) && $luafile['upload'])
		{
			$filename = $svfile.'.lua';
			if (isset($phpUniSettings['GZIP']) && $phpUniSettings['GZIP'])
			{
				$filename = gzCompressFile($WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/', $filename);
			}
			else
			{
				$filename = $WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/'.$filename;
			}
			
			$post_data[$svfile] = "@".$filename;
			$luafiles[$svfile]['upload'] = 0;
		}
	}
	$post_result = PostData($phpUniSettings['PRIMARYURL'], 'UploadFiles', $UserAgentVersion);

	if ($post_result['error']) {
		WriteToLog($post_result['error']);
	}
	if (isset($UploadResultLog) && $UploadResultLog)
	{
		if ($logfp = fopen($UploadResultLog, 'wb'))
        	{
			if (($bytes_written = fwrite($logfp, date("d/m/Y H:i \r\n").$post_result['output']."\r\n")) === false)
			{
				WriteToLog("Unable to write to Output File: ".$UploadResultLog.". Please check the file permissions!");
			}
			
			fclose($logfp);
		}
		else
		{
	                print ("Can not open Log File for writing: ".$LogFile."\r\n");
        	        print ("Exiting....\r\n");
                	exit(1);
        	}
	}
	else
	{
		print($post_result['output']."\r\n");
	}

	foreach ($post_data as $svfile => $filename)
	{
		$filename = substr($filename, 1);
		if (file_exists($filename) && pathinfo($filename, PATHINFO_EXTENSION) == 'gz')
		{
			unlink($filename);
		}
	}
			
	$post_data = array();

}

function GetSettings()
{
	global $post_data, $phpUniSettings, $SendUpdatePassword, $UserAgentVersion;
	
	$returnvalue = 0;
	
	if (isset($phpUniSettings['SYNCHROURL']) && $phpUniSettings['SYNCHROURL'])
	{
		$post_data = array();
		$post_data['OPERATION'] = 'GETSETTINGS';
		$post_result = PostData($phpUniSettings['SYNCHROURL'], 'GetSettings', $UserAgentVersion);

		if ($post_result['error']) {
			$returnvalue = "GetSettings Error: ".$post_result['error'];
		}
		
		
		$tempsettings = explode("[|]", $post_result['output']);
		foreach ($tempsettings as $setting)
		{
			$setting = explode("[=]", $setting);
			$settingscount = count($setting);
			if ($settingscount > 2)
			{
				for ($i=2; $i<$settingscount;$i++)
				{
					$setting[1] .= '='.$setting[$i];
				}
			}
			
			if (substr($setting[0], 0, 4) == 'HTTP' || substr($setting[0], 0, 4) == 'http')
			{	
				$setting[0] = substr($setting[0], strpos($setting[0], "\r\n\r\n") + 4);
			}
				
			$phpUniSettings[$setting[0]] = $setting[1];
		}
		if (!isset($phpUniSettings['PRIMARYURL']))
		{
			$returnvalue = "GetSettings Error: Settings could not be read!! Check UniAdminURL";
		}
		if (isset($phpUniSettings['ADDVARVAL2']) && $phpUniSettings['ADDVARVAL2'] && isset($phpUniSettings['SENDPWSECURE']) && $phpUniSettings['SENDPWSECURE'])
		{
			$phpUniSettings['ADDVARVAL2'] = md5($phpUniSettings['ADDVARVAL2']);
		}
		if (!$SendUpdatePassword)
		{
			$phpUniSettings['ADDVARVAL2'] = '';
			$phpUniSettings['ADDVARNAME2'] = '';
		}
	}
	else
	{
		$returnvalue = 'UniAdmin URL is not configured!';
	}
	$post_data = array();
	return $returnvalue;
}

function CheckAddons()
{
	global $post_data, $phpUniSettings, $SendUpdatePassword, $TempDir, $WoWDir, $UserAgentVersion, $GetNonRequired;
	
	$returnvalue = 0;
	
	WriteToLog("Checking AddOns");
	// Declare an array to store the data
	$addon_data = array();
	
	if (isset($phpUniSettings['SYNCHROURL']) && $phpUniSettings['SYNCHROURL'])
	{
		$post_data = array();
		$post_data['OPERATION'] = 'GETADDONLIST';
		$post_result = PostData($phpUniSettings['SYNCHROURL'], 'GetAddOnList', $UserAgentVersion);

		if ($post_result['error']) 
		{
			$returnvalue = "GetSettings Error: ".$post_result['error'];
		}
		
		preg_match("'<addons.*?>(.*?)</addons>'si", $post_result['output'], $rawxml);
		preg_match_all("'<addon.*?>(.*?)</addon>'si", $rawxml[0], $rawxml);

		$rawxml = $rawxml[0];
		foreach ($rawxml as $index => $data)
		{
			preg_match("'<addon\sname=\"(.*?)\"\sversion=\"(.*?)\"\srequired=\"(.*?)\"\shomepage=\"(.*?)\"\sfilename=\"(.*?)\"\stoc=\"(.*?)\sfull_path=\"(.*?)\">'", $data, $addon_info);
			preg_match_all("'<file\sname=\"(.*?)\"\smd5sum=\"(.*?)\"\s\/>'", $data, $addon_files);

			$addon_data[$index]['name'] = $addon_info[1];
			$addon_data[$index]['version'] = $addon_info[2];
			$addon_data[$index]['required'] = $addon_info[3];
			$addon_data[$index]['homepage'] = $addon_info[4];
			$addon_data[$index]['filename'] = $addon_info[5];
			$addon_data[$index]['toc'] = $addon_info[6];
			$addon_data[$index]['fullpath'] = $addon_info[7];
			
			foreach ($addon_files[0] as $fileidx => $filedata)
			{
				$addon_data[$index]['files'][$fileidx]['file'] = str_replace("\\\\", "/", $addon_files[1][$fileidx]);
				$addon_data[$index]['files'][$fileidx]['md5'] = $addon_files[2][$fileidx];
			}
		}
		
		foreach ($addon_data as $addonindex => $addondata)
		{
			WriteToLog("Checking AddOn: ".$addondata['name'].", version ".$addondata['version']);
			$addon_data[$addonindex]['different'] = 0;
			foreach ($addondata['files'] as $fileidx => $filedata)
			{
				if (file_exists($WoWDir."/".$filedata['file']))
				{
					if ($filedata['md5'] == md5_file($WoWDir."/".$filedata['file']))
					{
						WriteToLog("File ".$filedata['file']." is the same");
					}
					else
					{
						WriteToLog("File ".$filedata['file']." is different");
						$addon_data[$addonindex]['different'] = 1;
					}
				}
				else
				{
					WriteToLog("File ".$filedata['file']." does not exist locally");
					$addon_data[$addonindex]['different'] = 1;
				}
			}
			
			// Download the file if there are differences and update the addon
			if ($addon_data[$addonindex]['different'] && ($addon_data[$addonindex]['required'] || $GetNonRequired))
			{
				// Get the AddOn URL
				$post_data = array();
				$post_data['OPERATION'] = 'GETADDON';
				$post_data['ADDON'] = $addon_data[$addonindex]['name'];
				$post_result = PostData($phpUniSettings['SYNCHROURL'], 'GetAddOnUrl', $UserAgentVersion);
				
				// Get the AddOn and store it in the tmp folder
				if ($post_result['error']) 
				{
					WriteToLog("GetAddonUrl Error: ".$post_result['error']);
				}
				else
				{
					preg_match('/Content-Length: ([0-9]+)/', $post_result['output'], $parts);
					$URL = substr($post_result['output'], - $parts[1]);

					if (isset($URL) && $URL != '')
					{
						$downloaded_addon = download_binary($URL, $TempDir."/");
						if ($downloaded_addon['error'])
						{
							WriteToLog("AddOn Download Error: ".$downloaded_addon['error']);
						}
						else
						{
							// Check if the AddOn was uploaded with Full-Path
							if ($addon_data[$addonindex]['fullpath'])
							{
								$extract_path = $WoWDir;
							}
							else
							{
								$extract_path = $WoWDir."/Interface/AddOns";
							}
							WriteToLog("Unzipping AddOn ".$addon_data[$addonindex]['name']." to ".$extract_path);
							$archive = new PclZip($downloaded_addon['filename']);
							if ($archive->extract(PCLZIP_OPT_REPLACE_NEWER, PCLZIP_OPT_PATH, $extract_path) == 0)
							{
								WriteToLog("Error Unzipping: ".$archive->errorInfo(true));
							}
							var_dump($archive);

							// Remove the Downloaded AddOn once we are done with it
							if (file_exists($downloaded_addon['filename']) && is_file($downloaded_addon['filename']))
							{
								unlink($downloaded_addon['filename']);
							}
						}
					}
					else
					{
						WriteToLog("AddOn URL Invalid: ".$URL);
					}
				}
			}
		}
	}
	else
	{
		$returnvalue = 'UniAdmin URL is not configured!';
	}
	return $returnvalue;
}

// Little function to easily write stuff to the logfile
function WriteToLog($string)
{
	global $LogFile;

	if (isset($LogFile) && $LogFile)
	{
		if ($logfp = fopen($LogFile, 'ab'))
        	{
			if (($bytes_written = fwrite($logfp, date("d/m/Y H:i - ").$string."\n")) === false)
			{
				print (date("d/m/Y H:i - ")."Unable to write to Log File: ".$LogFile.".\nPlease check the file permissions!\r\n");
        	        	print (date("d/m/Y H:i - ")."Exiting....\r\n");
				exit(1);
			}
			
			fclose($logfp);
		}
		else
		{
	                print ("Can not open Log File for writing: ".$LogFile."\r\n");
        	        print ("Exiting....\r\n");
                	exit(1);
        	}
	}
	else
	{
		print (date("d/m/Y H:i - ").$string."\r\n");
	}
}

// Compress function to make gzip's out of lua's
function gzCompressFile($path, $file, $level=false)
{
	global $WoWDir;
	$dest = $WoWDir.'/'.$file.'.gz';
	$mode = 'wb'.$level;
	$returnvalue = 1;
	if ($fp_out = gzopen($dest, $mode))
	{
		if($fp_in = fopen($path.$file, 'rb'))
		{
			while(!feof($fp_in))
			{
				gzwrite($fp_out, fread($fp_in, filesize($path.$file)));
			}
			fclose($fp_in);
		}
		else
		{
			WriteToLog("Unable to open file ".$path.$file." for compression!");
			$returnvalue = 0;
		}
	}
	else
	{
		WriteToLog("Unable to compress file using gzip: ".$path.$file." -> ".$dest);
		$returnvalue = 0;
	}
	
	gzclose($fp_out);
	
	if ($returnvalue)
	{
		$returnvalue = $dest;
	}
	
	return $returnvalue;
}

function LoopHole()
{
	global $phpUniSettings, $CheckLUAFilesDelay, $CheckSettingsDelay;
	
	// Create a loopcount
	$loopcount = 0;
	
	// Start looping
	while (TRUE)
	{
		// If the loopcount reaches a value that it should check the addons, make it so, and reset the loopcount to 1
		if ($loopcount == ($CheckSettingsDelay/$CheckLUAFilesDelay) || $loopcount == 0)
		{
			// Check and verify the addons
			CheckAddons();
			$loopcount = 1;
		}
		
		// Check if there were LUA file changes
		CheckFiles();
		$loopcount++;
		sleep($CheckLUAFilesDelay);
	}
}	
	
function PostData($URL, $Action = "HTTP_Post", $UserAgent = 'UniUploader 2.0')
{
	global $post_data;
	
	// Declare the return array
	$returndata = array();
	$returndata['output'] = '';
	$returndata['error'] = 0;
	
	// If we have cURL, use those libraries to post, if not, use fsockopen().
	if (function_exists(curl_init))
	{
		// Post the data using the cURL libraries
		
		// Initialize and fill the cURL data
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
        	
        	// Put the results in the return array
		$returndata['output'] = curl_exec($ch);
		
		// Check if there were cUrl errors
		if (curl_errno($ch))
		{
			// If there were errors, put them in the return array
        		$returndata['error'] = $Action." Error: ".curl_error($ch);
        	}
		else
		{
			$returndata['error'] = 0;
		}
	
		curl_close($ch);
	}
	else
	{	
		// Post the data via fsockopen()
		
		// Declare the data holders
		$request = '';
		$data_string = '';
		$data_stringf = '';
		
		// Make a random boundary to seperate the data components
		srand((double)microtime()*1000000);
		$boundary = "--".substr(md5(rand(0,32000)),0,10);
		
		// Start Building the data string
		$data_string .= "--".$boundary;
		foreach($post_data as $index => $data)
		{
			// Check if the data is a file
			if ($data{0} == '@')
			{	
				
				$file = substr($data, 1);
				
				if (pathinfo($file, PATHINFO_EXTENSION) == 'gz' && pathinfo($file, PATHINFO_EXTENSION) == 'lua')
				{
					$mimetype = 'application/octet-stream';
				}
				else
				{
					$mimetype = 'text/plain';
				}
				$filename = pathinfo($file);
				$filename = $filename['basename'];
				$content_file = join("", file($file));
				$data_stringf .= "\r\nContent-Disposition: form-data; ";
				$data_stringf .= "name=\"".$index."\"; ";
				$data_stringf .= "filename=\"".$filename."\"\r\n";
				$data_stringf .= "Content-Type: ".$mimetype."\r\n";
				$data_stringf .= "Content-Transfer-Encoding: binary\r\n\r\n";
				$data_stringf .= $content_file."\r\n";
				$data_stringf .= "--".$boundary;
			}
			else
			{
				$data_string .= "\r\nContent-Disposition: form-data; ";
				$data_string .= "name=\"".$index."\"\r\n\r\n";
				$data_string .= $data."\r\n";
				$data_string .= "--".$boundary;
			}
			$data_string .= $data_stringf;
			unset($data_stringf);
		}
		$data_string .= "--";
		
		// Parse the URL to get the individual parts
		$URL = parse_url($URL);
		if (!isset($URL['port']) || $URL['port'] == '')
		{
			switch ($URL['scheme']) {
				case "http":
	   				$URL['port'] = 80;
					break;
				case "https":
	                        	$URL['port'] = 443;
	                        	break;
	                        default:
					$URL['port'] = 80;
			}
		}
		
		// Build the post header
		$post_header  = "POST ".$URL['path']." HTTP/1.0\r\n";
		$post_header .= "Host: ".$URL['host']."\r\n"; 
		$post_header .= "User-Agent: ".$UserAgent."\r\n";
		$post_header .= "Content-Type: multipart/form-data, boundary=".$boundary."\r\n";
		$post_header .= "Content-Length: ".strlen($data_string)."\r\n\r\n";

		// Open the connection
		$fpost = fsockopen($URL['host'], $URL['port'], $error['number'], $error['string']);
		if (!$fpost)
		{
			$returndata['error'] = $error['number']." - ".$error['string'];
		}
		else
		{
			
			// Post the http
			fputs($fpost, $post_header.$data_string);
			
			// get the response
			while (!feof($fpost))
			{
				$returndata['output'] .= fread($fpost,3200000);
			}
			fclose($fpost);
			$returndata['error'] = 0;
		}
	}
	// Return the results of the post
	return $returndata;
}

// Download a file binary save
function download_binary($URL, $save_path = '')
{
	$URL = parse_url($URL);
	
	$filename = $save_path.pathinfo($URL['path'], PATHINFO_BASENAME);
	
	$returndata['filename'] = $filename;
	$returndata['error'] = 0;
	
	
	if (!isset($URL['port']) || $URL['port'] == '')
	{
		switch ($URL['scheme']) {
			case "http":
	  				$URL['port'] = 80;
				break;
			case "https":
	                       	$URL['port'] = 443;
	                       	break;
	                       default:
				$URL['port'] = 80;
		}
	}
	// If we have cURL, use those libraries to download the file, if not, use fsockopen().
	if (function_exists(curl_init))
	{
		// Download the file using the cURL libraries
		$curl_url = $URL['scheme'].'://'.$URL['host'].$URL['path'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$buffer = curl_exec ($ch);
		curl_close ($ch);
	}
	else
	{
		// Download the file using fsockopen
		$fp = fsockopen($URL['host'], $URL['port']);

		$query  = 'GET ' . $URL['path'] . " HTTP/1.0\n";
		$query .= 'Host: ' . $URL['host'];
		$query .= "\r\n\r\n";

		fwrite($fp, $query);
		while ($tmp = fread($fp, 819200))
		{
			$buffer .= $tmp;
		}
	
		preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts);
	}
	
	// Let's make sure the file exists and is writable first.
	if (!file_exists($filename) || is_writable($filename))
	{

		// In our example we're opening $filename in append mode.
		// The file pointer is at the bottom of the file hence
		// that's where $somecontent will go when we fwrite() it.
		if (!$handle = fopen($filename, 'wb')) {
			$returndata['error'] = "Cannot open file ($filename)";
		}

		// Write $somecontent to our opened file.
		if (fwrite($handle, substr($buffer, - $parts[1])) === FALSE) {
			$returndata['error'] = "Cannot write to file ($filename)";
		}
	  
		fclose($handle);
	} 
	else
	{
		$returndata['error'] = "The file $filename is not writable";
	}
	return $returndata;
}

?>

