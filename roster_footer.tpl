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

$error_report = $roster->error->stop();

print '
<!-- Begin Roster Footer -->
<br />
<hr />
<small>WoWRoster v' . $roster->config['version'] . '</small>
<br /><br />
<small>' . sprintf($roster->locale->act['roster_credits'], makelink('credits')) . '</small>
<br /><br />
<a href="http://validator.w3.org/check?uri=referer" target="_blank">
    <img src="' . $roster->config['img_url'] . 'validxhtml.gif" alt="Valid XHTML 1.0 Transitional" width="80" height="15" /></a>
  <br /><br />
';

if( $roster->config['processtime'] )
{
	print '  <small>' . $totaltime . ' | ' . $roster->db->query_count . "</small>\n\n";
}

if( $roster->config['debug_mode'] )
{
	if( is_array($error_report) )
	{
		$debug_php = "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		foreach( $error_report as $file => $errors )
		{
			$debug_php .= '<tr><th class="membersHeaderRight">' . substr($file, strlen(ROSTER_BASE)) . "</th>\n</tr>\n";
			foreach( $errors as $error )
			{
				$debug_php .= '<tr><td class="membersRowRight1" style="font-size:10px;">&nbsp;&nbsp;' . $error . "</td></tr>\n";
			}
		}
		$debug_php .= "</table>\n";

		print "<br /><br />\n".messagebox($debug_php,'PHP Errors','sred');
	}
}

if( $roster->config['sql_window'] )
{
	echo "<br /><br />\n".messagebox($roster->db->getQueries(),$roster->locale->act['sql_queries'],'sgreen');
}


print getAllTooltips();
?>

</div>
</body>
</html>
