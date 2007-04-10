<?php
$versions['versionDate']['indexStat'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['indexStat'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['indexStat'] = '$Author: mordon $';

require_once 'conf.php';

$header_title = $wordings[$roster_lang]['menustats'];
include 'roster_header.tpl';

require 'membersStat.php';

include 'roster_footer.tpl';
?>