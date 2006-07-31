<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
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

// Explicitly close the db
$wowdb->closeDb();

$endtime = explode(' ', microtime() );
$endtime = $endtime[1] + $endtime[0];
$totaltime = ($endtime - $starttime);

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
{
	print '  <small>This page was created in '.$totaltime.' seconds with '.count($wowdb->sqlstrings).' queries executed</small>';
}
?>

</div>
</body>
</html>