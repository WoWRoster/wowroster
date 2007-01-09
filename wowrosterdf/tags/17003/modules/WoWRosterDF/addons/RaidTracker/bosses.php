<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }

// Make boxes for each instance
$count = 0;
echo '<table><tr><td valign="top">';
foreach($rt_wordings[$roster_conf['roster_lang']]['Zones'] as $zonename => $zone){

$count = $count + 1;
	bossProgress($zonename);
	if($count == 3)
	{
		$count = 0;
		echo '</td><td width="10px"></td><td valign="top">';
	}
}
echo '</td></tr></table>';

$wowdb->free_result($result);
?>