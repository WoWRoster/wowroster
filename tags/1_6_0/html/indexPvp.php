<?php
$versions['versionDate']['indexPvp'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['indexPvp'] = '$Revision: 1.8 $'; 
$versions['versionAuthor']['indexPvp'] = '$Author: mordon $';

require_once 'conf.php';

$header_title = $wordings[$roster_lang]['pvplist'];
include 'roster_header.tpl';

require 'guild-pvp.php';

include 'roster_footer.tpl';
?>