<?php
/* 
* $Date: 2006/01/11 19:45:26 $ 
* $Revision: 1.4 $ 
*/ 

if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die("You can't access this file directly!");
}


$header_title = $wordings[$roster_lang]['menustats'];

require 'membersRep.php';

echo $content;
?>