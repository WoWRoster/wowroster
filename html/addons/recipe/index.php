<?php
/* 
* $Date: 2006/01/11 19:45:26 $ 
* $Revision: 1.4 $ 
*/ 


if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die("You can't access this file directly!");
}

$header_title = $wordings[$roster_lang]['MadeBy'];

$link = mysql_connect($db_host, $db_user, $db_passwd) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not connect");
mysql_select_db($db_name) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not select DB");

require 'RecipeList.php'; 

echo $content;
?>