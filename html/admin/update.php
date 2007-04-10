<?php
$subdir='../';
include $subdir.'conf.php';
include $subdir.'lib/luaparser.php';
include $subdir.'lib/wowdb.php';

// Set $htmlout to 1 to assume request is from a browser
$htmlout = 1;
// See if UU is requesting this page
if( substr( $_SERVER["HTTP_USER_AGENT"], 0, 11 ) == 'UniUploader' ) {
	$htmlout = 0;
}

$header = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>['.$guild_name.' Roster] '.$updCharInfo[$roster_lang].'</title>
		<link rel="stylesheet" type="text/css" href="'.$subdir.$stylesheet2.'">
		<link rel="stylesheet" type="text/css" href="'.$subdir.$stylesheet1.'">
		<style type="text/css"> 
			/* This is the border line & background colour round the entire page */
			.bodyline { background-color: #000000; border: 1px #212121 solid; }
		</style>
	</head>
	<body>
		<table border=0 cellpadding=0 cellspacing=0 width="100%">
			<tr>
				<td align="center">
					<table border=0 cellpadding=8 cellspacing=0 width="100%">
						<tr>
							<td width="100%" class="bodyline">
								<table border=0 cellpadding=0 cellspacing=0 width="100%">
									<tr>
										<td align="center" width="100%" class="bodyline">
											<a href="'.$website_address.'"><img src="'.$subdir.$logo.'" alt="" border="0"></a>
											<br>
										</td>
									<tr>
										<td align="center">
											<br>
											<font color="#FFFFFF">'.$updMember[$roster_lang].'</font>
											<br>
											<br>';

if (!$authenticated_user) {
	$wow_group = $upload_group;
	include $subdir.'lib/phpbb.php';
}


$filefields[0] = 'SavedVariables'; //remove this to remove support for the main SavedVariables.lua input
$filefields[1] = 'CharacterProfiler';
$filefields[2] = 'PvPLog';

foreach ($_FILES as $filefield => $file)
{
	if (substr_count($file['name'],'.gz') > 0) //if the file is gzipped
	{
		$filename = $file['tmp_name'];
		$tempHandle = gzopen($filename, 'r');      //uncompress
		$contents = fread($tempHandle,5000000);   //read up to 5 megs (no way to see total uncompressed length :( )
		fclose($tempHandle);
		$tmpfname = tempnam('','luatemp');         //create temp file for uncompressed data
		$handle = fopen($tmpfname, 'w');
		fwrite($handle, $contents);                     //write the uncompressed temp file
		fclose($handle);
		$filename = $tmpfname;                     //return the filename (with full path)
	}
	else //the file is not gzipped
	{
		$filename = $file['tmp_name'];
	}
	
	for ($i = 0; $i < count($filefields); $i++) //itterate through all the possible filefields
	{
		if ($filefields[$i] == $filefield)
		{
			//filefield is 1 of the 3 we accept.
			$uploadFound = true;
			if ($authenticated_user)
			{
				$data = lua_parse( $filename );

				//if pvp data is there, assign it to $pvpdata
				if (isset($data['PurgeLogData']))
				{
					$pvpdata = $data['PurgeLogData'];
				}
				//if CP data is there, assign it to $myProfile
				if (isset($data['myProfile']))
				{
					$myProfile = $data['myProfile'];
				}
			}
		}
	}
	@unlink($filename); //done with the file, dont need it anymore
}

$output .= ""; //this will hold the output of this complicated mess


$wowdb->connect( $db_host, $db_user, $db_passwd, $db_name );
if( isset( $myProfile ) ) {
	foreach( array_keys( $myProfile ) as $realm_name ) {
		$realm = $myProfile[$realm_name];
		foreach( array_keys( $realm ) as $char_name ) {
			if ($char_name != 'Guild') {
				if ($server_name == $realm_name) {
					$guildInfo = $wowdb->get_guild_info($realm_name,$guild_name);
					if ($guildInfo)
					{
						$char = $realm[$char_name];
						$output .= "Updating character [$char_name]<br>\n";
						$output .= $wowdb->update_char( $guildInfo['guild_id'], $char_name, $char );
						if( isset( $pvpdata['PurgeLogData'] ) ) {
							$myPvPData = $pvpdata['PurgeLogData'];
							$realm2 = $myPvPData[$realm_name];
							$char2 = $realm2[$char_name];
							$battles = $char2['battles'];
							$output .= "Updating PVP Data.";
							$output .= $wowdb->update_pvp2($guildInfo['guild_id'], $char_name, $battles);
						}
					}
					else
					{
						$output .=  $noGuild[$roster_lang];
					}
				}
			}
		}
	}
}


if (!$authenticated_user)
$authFields = "
													<tr>
														<td>Username</td><td><input type=text name=username></td>
													</tr>
													<tr>
														<td>Password</td><td><input type=password name=password></td>
													</tr>";
if ($show_pvplist)
$pvplogInputField = "
													<tr>
														<td>PvPLog.lua (".$wordings[$roster_lang]['optional'].") </td>
														<td><input type=\"file\" accept=\"PvPLog.lua\" name=\"PvPLog\"></td>
													</tr>";
$inputForm = "
											<form ENCTYPE=\"multipart/form-data\" method=\"POST\">
												<table>
													$authFields
													<tr>
														<td>CharacterProfiler.lua (".$wordings[$roster_lang]['required'].") </td>
														<td><input type=\"file\" accept=\"CharacterProfiler.lua\" name=\"CharacterProfiler\"></td>
													</tr>
													$pvplogInputField
													<tr>
														<td colspan=\"2\"><center><input type=\"submit\" value=\"".$wordings[$roster_lang]['upload']."\"></center></td>
													</tr>
												</table>
											</form>";
$footer = "
										</td>
									</tr>
									<tr>
										<td><br><br><br><br><br></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<hr>".$lualocation[$roster_lang]."
					<p align=\"center\">
						<a href=\"".$subdir."index.php\">".$return[$roster_lang]."</a>
					</p>
				</td>
			</tr>
		</table>
	</body>
</html>";


// construct our page
if ($htmlout)
{
	print $header;
	if ($uploadFound)
	{
		print $output;
	}
	else
	{
		print $inputForm;
	}
	print $footer;
}
else //dont need the header and footer when responding to UU
{
	if ($uploadFound)
	{
		print $output;
	}
	else
	{
		//weren't any files in the upload that correspond to anything in the array of filefields that we take.
		print $nofileUploaded[$roster_lang];
	}
}

?>