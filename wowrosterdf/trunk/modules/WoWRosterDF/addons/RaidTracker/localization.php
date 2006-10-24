<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

$wordings['addoncredits']['RaidTracker'] = array(
			array(	"name"=>	"PoloDude",
				"info"=>	"Original Addon Developer"),
);

$rt_directory = dirname(__FILE__).DIR_SEP;
$lang = $roster_conf['roster_lang'];

switch ($lang){
case 'deDE':
	include $rt_directory.'deDE.php';
	break;
case 'enUS':
	include $rt_directory.'enUS.php';
	break;
case 'frFR':
	include $rt_directory.'frFR.php';
	break;
}

?>