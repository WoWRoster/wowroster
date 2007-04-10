<?php
$versions['versionDate']['indexAlt'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['indexAlt'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['indexAlt'] = '$Author: mordon $';

require_once 'conf.php';

$header_title = $wordings[$roster_lang]['alternate'];
include 'roster_header.tpl';

require 'membersList2.php';

include 'roster_footer.tpl';
?>