<?php
$subdir='../';
include $subdir.'conf.php';
include $subdir.'lib/luaparser.php';
include $subdir.'lib/wowdb.php';

if( !$authenticated_user ) {
	$wow_group = $upload_group;
	include $subdir.'lib/phpbb.php';
}

$filefield = 'CharacterProfiler';

if(isset($_FILES[$filefield]) && $authenticated_user) {
	if(substr_count($_FILES[$filefield]['name'],'.gz') > 0){
		$filename = $_FILES[$filefield]['tmp_name'];
		$tempHandle = gzopen($filename, 'r');		// uncompress
		$contents = fread($tempHandle,5000000);	// read up to 5 megs (no way to see total uncompressed length :( )
		fclose($tempHandle);
		$tmpfname = tempnam('','luatemp');			// create temp file for uncompressed data
		$handle = fopen($tmpfname, 'w');
		fwrite($handle, $contents);							// write the uncompressed temp file
		fclose($handle);
		$filename = $tmpfname;									// return the filename (with full path)
	} else { $filename = $_FILES[$filefield]['tmp_name']; }

	$data = lua_parse($filename);
	unlink($filename);
	$wowdb = new wowdb;
	$wowdb->connect($db_host, $db_user, $db_passwd, $db_name);

	if(isset($data['myProfile'])) {
		$myProfile = $data['myProfile'];
		foreach(array_keys($myProfile) as $realm_name) {
			$realm = $myProfile[$realm_name];
			$guild = $realm['Guild'];
				if($guild) {
					$guildName = $guild['Guild'];
					if($guild_name == $guildName) {
						$guildMotd = $guild['Motd'];
						$guildNumMembers = $guild['NumMembers'];
						$guildDateUpdatedUTC = $guild['DateUTC'];
					// make hour between 0 and 23 and minute between 0 and 60
						$guildHour= intval($guild['Hour']);
						$guildMinute= intval($guild['Minute']);
					// take the current time and get the offset. Upload must occur same day that roster was obtained
						$currentTimestamp = mktime($guildHour,$guildMinute,0);
						$currentTime = getDate($currentTimestamp);
						print "Updating Guild [ $guildName ]<br>\n";
						$guildId = $wowdb->update_guild($realm_name, $guildName, $guildMotd, $guildNumMembers, $currentTime, $guildDateUpdatedUTC);
						$guildMembers = $guild['Members'];
					// update the list of guild members
						foreach(array_keys($guildMembers) as $char_name) {
							$char = $guildMembers[$char_name];
							print " Updating member - $char_name<br>\n";
							$wowdb->update_guild_member($guildId, $char_name, $char, $currentTimestamp);
						}
					// remove the members who were not in this list
						$wowdb->remove_guild_members($guildId, $currentTime);
					// update your character(s)
						foreach(array_keys($realm) as $char_name) {
							if($char_name != 'Guild') {
								$char = $realm[$char_name];
								print " - Updating your character [ $char_name ]<br>\n";
								$wowdb->update_char($guildId, $char_name, $char);
							}
						}
					} else { print $guild_nameNotFound[$roster_lang]; }
				}
			}
	} else { print $guild_addonNotFound[$roster_lang]; }
} else {
?>

<html>
<head>
<title>[<? print $guild_name ?> Roster] <? print $updGuildMembers[$roster_lang]; ?></title>
</head>
<body>
	<form ENCTYPE="multipart/form-data" method="POST">

<?
	if (!$authenticated_user) {
?>

	Username: <input type=text name=username><br>
	Password: <input type=password name=password><br>

<? } ?>

	CharacterProfiler.lua: <input type="file" accept="CharacterProfiler.lua" name="CharacterProfiler"><br>
	<input type="submit" value="<? print $wordings[$roster_lang]['upload']; ?>">

<? } ?>

</form>
</body>
</html>