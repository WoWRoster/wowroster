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

// Account data
// Your WoW Directory
$WoWDir               = "/home/mathos/WoW/WoW-Dir";	// You WoW Directory without trailing /
$AccountName          = "MATHOS";			// You WoW account name, usually uppercase!!
$UniAdminURL          = "http://elune.mysticwoods.nl/roster/admin/interface.php";
$CheckLUAFilesDelay   = 5;                              // How often do we check the LUA files (in seconds)
$CheckSettingsDelay   = 21600;				// How often do we check the AddOns (in seconds)
$RosterUpdateUser     = "";				// Your Roster User.
$RosterUpdatePassword = "";				// Your Roster Password.
$SendUpdatePassword   = TRUE;
$LogFile              = "";	// LogFile.
//$LogFile              = "/var/log/phpUniUploader.log";	// LogFile.
//$UploadResultLog      = "/var/log/phpUniUploader.LastUpload.log";
$UploadResultLog      = "";
$TempDir              = "/tmp";				// Temporary Directory to gzip files.


// ------------ !!! DO NOT EDIT ANYTHING BELOW THIS LINE !!! -------------- //

$luafiles = array();
$addons = array();
$post_data = array();
$phpUniSettings['SYNCHROURL'] = $UniAdminURL;

// Get the Settings from UniAdmin interface
$getsettings = GetSettings();
print_r($phpUniSettings);
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
	global $post_data, $phpUniSettings, $luafiles, $WoWDir, $AccountName, $RosterUpdatePassword;
	
	$post_data = array();
	if (isset($phpUniSettings['ADDVARNAME2']) && $phpUniSettings['ADDVARNAME2'])
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
				$filename = $WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/'.$filename;
				//$filename = gzCompressFile($WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/', $filename);
			}
			else
			{
				$filename = $WoWDir.'/WTF/Account/'.$AccountName.'/SavedVariables/'.$filename;
			}
			
			$post_data[$svfile] = "@".$filename;
			$luafiles[$svfile]['upload'] = 0;
		}
	}
	$post_result = PostData($phpUniSettings['PRIMARYURL'], 'UploadFiles');

	if ($post_result['error']) {
		WriteToLog($post_result['error']);
	}
	if (isset($UploadResultLog) && $UploadResultLog)
	{
	}
	else
	{
		print($post_result['output']."\n");
	}
	$post_data = array();
}

function GetSettings()
{
	global $post_data, $phpUniSettings, $SendUpdatePassword;
	
	$returnvalue = 0;
	
	if (isset($phpUniSettings['SYNCHROURL']) && $phpUniSettings['SYNCHROURL'])
	{
		$post_data = array();
		$post_data['OPERATION'] = 'GETSETTINGS';
		$post_result = PostData($phpUniSettings['SYNCHROURL'], 'GetSettings');

		if ($post_result['error']) {
			$returnvalue = "GetSettings Error: ".$post_result['error'];
		}
		
		
		$tempsettings = explode("|", $post_result['output']);
		foreach ($tempsettings as $setting)
		{
			$setting = explode("=", $setting);
			$settingscount = count($setting);
			if ($settingscount > 2)
			{
				for ($i=2; $i<$settingscount;$i++)
				{
					$setting[1] .= '='.$setting[$i];
				}
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
	print("Checking Addons\n");
}

function WriteToLog($string)
{
	global $LogFile;

	if (isset($LogFile) && $LogFile)
	{
		if ($logfp = fopen($LogFile, 'wb'))
        	{
			if (($bytes_written = fwrite($logfp, date("d/m/Y H:i - ").$string."\n")) === false)
			{
				print (date("d/m/Y H:i - ")."Unable to write to Log File: ".$LogFile.".\nPlease check the file permissions!\n");
        	        	print (date("d/m/Y H:i - ")."Exiting....\n");
				exit(1);
			}
		}
		else
		{
	                print ("Can not open Log File for writing: ".$LogFile."\n");
        	        print ("Exiting....\n");
                	exit(1);
        	}
	}
	else
	{
		print (date("d/m/Y H:i - ").$string."\n");
	}
}

function gzCompressFile($path, $file, $level=false)
{
	global $TempDir;
	$dest = $TempDir.'/'.$file.'.gz';
	$mode = 'wb'.$level;
	$returnvalue = 1;
	if ($fp_out = gzopen($dest, $mode))
	{
		if($fp_in=fopen($path.$file,'rb'))
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

	$loopcount = 0;
	while (TRUE)
	{
		if ($loopcount == ($CheckSettingsDelay/$CheckLUAFilesDelay) || $loopcount == 0)
		{
			CheckAddons();
			$loopcount = 1;
		}
		
		CheckFiles();
		$loopcount++;
		sleep($CheckLUAFilesDelay);
	}
}	
	
function PostData($URL, $Action = "HTTP_Post", $UserAgent = 'UniUploader')
{
	global $post_data;
	
	$returndata = array();
	$returndata['output'] = '';
	$returndata['error'] = 0;
	//function_exists(curl_init)
	if (0)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
		$returndata['output'] = curl_exec($ch);

		if (curl_errno($ch))
		{
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
		$request = '';
		$data_string = '';
		$data_stringf = '';
		srand((double)microtime()*1000000);
		$boundary = "--".substr(md5(rand(0,32000)),0,10);
		
		$data_string .= "--".$boundary;
		foreach($post_data as $index => $data)
		{
			print ("Param:".$data." - first char: ".$data{0}." - rest: ".substr($data, 1)."\n");
			if ($data{0} == '@')
			{	
				print ("Using File Method\n");
				$file = substr($data, 1);
				
				if (pathinfo($file, PATHINFO_EXTENSION) == 'gz')
				{
					$mimetype = 'application/x-gzip';
				}
				elseif (pathinfo($file, PATHINFO_EXTENSION) == 'lua')
				{
					$mimetype = 'application/x-lua';
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
				$data_stringf .= "Binary stuff\r\n";
				$data_stringf .= $content_file."\r\n";
				$data_stringf .= "--".$boundary;
			}
			else
			{
				print ("$index - $data - NOT Using File Method\n");
				$data_string .= "\r\nContent-Disposition: form-data; ";
				$data_string .= "name=\"".$index."\"\r\n\r\n";
				$data_string .= $data."\r\n";
				$data_string .= "--".$boundary;
			}
			$data_string .= $data_stringf;
			unset($data_stringf);
		}
		$data_string .= "--";
		
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
		
		$post_header  = "POST ".$URL['path']." HTTP/1.0\r\n";
		$post_header .= "Host: ".$URL['host']."\r\n"; 
		$post_header .= "User-Agent: ".$UserAgent."\r\n";
		$post_header .= "Content-Type: multipart/form-data, boundary=".$boundary."\r\n";
		$post_header .= "Content-Length: ".strlen($data_string)."\r\n\r\n";
		//print("\r\n\r\n".$post_header.$data_string."\r\n\r\n");
		// open the connection
		$fpost = fsockopen($URL['host'], $URL['port'], $error['number'], $error['string']);
		if (!$fpost)
		{
			$returndata['error'] = $error['number']." - ".$error['string'];
		}
		else
		{
			fputs($fpost, $post_header.$data_string);
			
			// get the response
			while (!feof($fpost))
			{
				$returndata['output'] .= fread($fpost,32000);
			}
			fclose($fpost);
			$returndata['error'] = 0;
		}
	}
	return $returndata;
}

?>

