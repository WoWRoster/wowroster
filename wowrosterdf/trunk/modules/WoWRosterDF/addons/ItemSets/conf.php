<?php
$versions['versionDate']['itemsetslang'] = '$Date: 2006/06/18 17:00:00 $'; 
$versions['versionRev']['itemsetslang'] = '$Revision: 1.7.0 $'; 
$versions['versionAuthor']['itemsetslang'] = '$Author: Gorgar, PoloDude, Zeryl $';

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
    die("You can't access this file directly!");
}

//Multilanguage support
$addon_conf['itemsets']['multilanguage'] = 0;

?>