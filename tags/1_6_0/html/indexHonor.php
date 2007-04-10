<?php
$versions['versionDate']['indexHonor'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['indexHonor'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['indexHonor'] = '$Author: mordon $';

require_once 'conf.php';

$header_title = $wordings[$roster_lang]['menuhonor'];
include 'roster_header.tpl';

require 'membersHonor.php';

include 'roster_footer.tpl';
?>