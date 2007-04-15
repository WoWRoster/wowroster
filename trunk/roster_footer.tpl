<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Overall footer for Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.6.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

define('ROSTER_FOOTER_INC',true);

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
<small><?php echo sprintf($act_words['roster_credits'], makelink('credits')); ?></small>
<br /><br />
<a href="http://validator.w3.org/check?uri=referer" target="_blank">
    <img src="<?php print $roster_conf['img_url']; ?>validxhtml.gif" alt="Valid XHTML 1.0 Transitional" width="80" height="15" /></a>
  <br /><br />

<?php
if( $roster_conf['processtime'] )
	print '  <small>' . $totaltime . ' | ' . count($wowdb->sqlstrings) . "</small>\n\n";

if( $roster_conf['sql_window'] )
	echo "<br /><br />\n".messagebox('<div style="text-align:left;font-size:10px;">' . nl2br(htmlentities($wowdb->getSQLStrings())) . '</div>',$act_words['sql_queries'],'sgreen');


print getAllTooltips();
?>

</div>
</body>
</html>