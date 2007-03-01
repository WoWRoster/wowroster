<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// Explicitly close the db
$wowdb->closeDb();

$endtime = explode(' ', microtime() );
$endtime = $endtime[1] + $endtime[0];
$totaltime = round($endtime - ROSTER_STARTTIME, 2);

?>

<!-- Begin Roster Footer -->
<br />
<hr />
<small>WoWRoster v<?php print $roster_conf['version'] ?></small>
<br /><br />
<small><?php echo $wordings[$roster_conf['roster_lang']]['roster_credits']; ?></small>
<br /><br />
<a href="http://validator.w3.org/check?uri=referer" target="_blank">
    <img src="<?php print $roster_conf['roster_dir']; ?>/img/valid-html40.gif" alt="Valid HTML 4.0 Transitional" height="15" width="119"></a>
  <br /><br />

<?php
if( $roster_conf['processtime'] )
	print '  <small>This page was created in '.$totaltime.' seconds with '.count($wowdb->sqlstrings)." queries executed</small>\n\n";

if( $roster_conf['sql_window'] )
	echo "<br /><br />\n".messagebox('<div style="text-align:left;font-size:10px;">'.nl2br(htmlentities($wowdb->getSQLStrings())).'</div>','SQL Queries','sgreen');


print getAllTooltips();
?>

</div>
</body>
</html>