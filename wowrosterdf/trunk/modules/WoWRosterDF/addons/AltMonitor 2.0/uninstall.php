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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

require($addonDir.'/inc/sql.php');

$errors = array();

// Uninstalling is easier than installing in that we don't check anything but just run all queries.
foreach ($uninstall_sql as $version => $sql)
{
	$install_queries = explode(';',$sql);

	foreach ($install_queries as $query)
	{
		if ( trim($query) != '' )
		{
			if ( $roster_conf['sqldebug'] ) echo "<!--$query-->\n";

			$result = $wowdb->query( $query ) or $wowdb->setError('MySQL said: '.$wowdb->error(),$query);
		}
	}
}

$errorstringout = $wowdb->getErrors();

// print the error messages
if( !empty($errorstringout) )
{
	print
	'<div id="errorCol" style="display:inline;">
		'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").'
		'.border('sred','end').'
	</div>
	<div id="error" style="display:none">
	'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" /><span class=\"red\">Update Errors</span></div>").
	$errorstringout.
	border('sred','end').
	'</div>';

	// Print the downloadable errors separately so we can generate a download
	print "<br />\n";
	print '<form method="post" action="update.php" name="post">'."\n";
	print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($errorstringout)).'" />'."\n";
	print '<input type="hidden" name="send_file" value="error" />'."\n";
	print '<input type="submit" name="download" value="Save Error Log" />'."\n";
	print '</form>';
	print "<br />\n";
}

echo border('sgreen','start',$wordings[$roster_conf['roster_lang']]['AltMonitor_install_page']).
	$wordings[$roster_conf['roster_lang']]['AltMonitor_uninstalled'].'<br />'.
	"<a href='".ROSTER_URL."'>".$wordings[$roster_conf['roster_lang']]['backlink']."</a>".
	border('sgreen','end');


?>
