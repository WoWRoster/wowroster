<?php
$versions['versionDate']['indexquests'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['indexquests'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['indexquests'] = '$Author: mordon $'; 

require 'conf.php';

$header_title = $wordings[$roster_lang]['quests'];
include 'roster_header.tpl';

require 'questList.php';

include 'roster_footer.tpl';
?>