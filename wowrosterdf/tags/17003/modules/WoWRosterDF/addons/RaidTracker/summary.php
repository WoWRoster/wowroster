<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }

// Display loot summary
echo '<table><tr><td valign="top">';
getRaidCount();
echo '</td><td width="10px"></td><td valign="top">';
getLootCount();
echo '</td></tr></table>';

?>