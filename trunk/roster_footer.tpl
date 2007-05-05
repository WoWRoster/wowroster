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

$endtime = explode(' ', microtime() );
$endtime = $endtime[1] + $endtime[0];
$totaltime = round($endtime - ROSTER_STARTTIME, 2);

?>

<!-- Begin Roster Footer -->
<br />
<hr />
<small>WoWRoster v<?php print $roster->config['version'] ?></small>
<br /><br />
<small><?php echo sprintf($roster->locale->act['roster_credits'], makelink('credits')); ?></small>
<br /><br />
<a href="http://validator.w3.org/check?uri=referer" target="_blank">
    <img src="<?php print $roster->config['img_url']; ?>validxhtml.gif" alt="Valid XHTML 1.0 Transitional" width="80" height="15" /></a>
  <br /><br />

<?php
if( $roster->config['processtime'] )
	print '  <small>' . $totaltime . ' | ' . $roster->db->query_count . "</small>\n\n";

if( $roster->config['sql_window'] )
	echo "<br /><br />\n".messagebox('<div style="text-align:left;font-size:10px;">' . $roster->db->getQueries() . '</div>',$roster->locale->act['sql_queries'],'sgreen');


print getAllTooltips();
?>

</div>
</body>
</html>
