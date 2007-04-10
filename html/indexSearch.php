<?php
$versions['versionDate']['indexSearch'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['indexSearch'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['indexSearch'] = '$Author: mordon $';

require_once 'conf.php';

$header_title = $wordings[$roster_lang]['search'];
include 'roster_header.tpl';

require 'search.php';

include 'roster_footer.tpl';
?>