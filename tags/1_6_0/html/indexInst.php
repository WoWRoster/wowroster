<?php
$versions['versionDate']['indexInst'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['indexInst'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['indexInst'] = '$Author: mordon $';

require_once 'conf.php';

$header_title = $wordings[$roster_lang]['keys'];
include 'roster_header.tpl';

require 'membersInst.php';

include 'roster_footer.tpl';
?>