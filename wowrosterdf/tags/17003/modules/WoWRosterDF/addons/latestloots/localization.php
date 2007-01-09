<?php
 
if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
    die("You can't access this file directly!");
}
$wordings['deDE']['LatestLoot'] = 'Neueste Beute';
$wordings['enUS']['LatestLoot'] = 'Latest Loot';

$wordings['deDE']['by'] = 'durch';
$wordings['enUS']['by'] = 'by';

$wordings['enUS']['TotalDrops'] = 'Total Drops';
$wordings['deDE']['TotalDrops'] = 'Gesamtbeute';
?>