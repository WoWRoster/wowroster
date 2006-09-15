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

// Include update lib
require_once(ROSTER_LIB.'update.lib.php');
$update = new update;

// Fetch addon data.
$messages = $update->fetchAddonData();

if ($_POST['process'] == 'process')
{
	$messages .= $update->parseFiles();
	$messages .= $update->processFiles();

	// Produce result page
	$errors = $wowdb->getErrors();
	$queries = $wowdb->getSQLStrings();

	if (!empty($errors))
	{
		$body .= scrollboxtoggle($errors,'Errors','sred');
		$body .= "<br />\n";
	}

	$body .= scrollbox($messages,'Update Log','syellow');

	if ($roster_conf['sqldebug'])
	{
		$body .= "<br />\n";
		$body .= scrollboxtoggle(nl2br($queries),'SQL Queries','sgreen');
	}
}
else
{
	$body .= '<form action="'.$script_filename.'?page=update" enctype="multipart/form-data" method="POST" onsubmit="submitonce(this);">'."\n";

	$body .= messagebox('<table class="bodyline" cellspacing="0" cellpadding="0">'.$update->makeFileFields().'</table>','Select files to upload','sblue');

	$body .= "<br />\n";

	$body .= '<input type="hidden" name="process" value="process">'."\n";
	$body .= '<input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['upload'].'">'."\n";
	$body .= '</form>'."\n";

	if (!empty($messages))
	{
		$body .= "<br />\n";
		$body .= scrollbox($messages,'Messages','syellow');
	}

}


?>