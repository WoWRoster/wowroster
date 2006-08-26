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

session_start();

if ($_POST['process'] == 'process')
{
	// Get parsed data from $_SESSION if available, else parse uploaded files.
	if (isset($_SESSION['uploadData'])) {
		$update->uploadData =& $_SESSION['uploadData'];
	}
	else
	{
		$messages .= $update->parseFiles();
		$_SESSION['uploadData'] =& $update->uploadData;
	}

	echo $roster_login->getUserName();

	if ($roster_login->getUserName() == '')
	{
		if (isset($_POST['user']) && $roster_login->createAccount($_POST['user'],$_POST['pass1'],$_POST['pass2']))
		{
			$body .= messagebox($roster_login->getMessage(),'','sgreen');
			$messages .= $update->processFiles();
		}
		else
		{
			if (isset($_POST['user']))
			{
				$body .= messagebox($roster_login->getMessage(),'','sred');
			}

			$chars = array_keys($update->uploadData['CharacterProfiler']['myProfile'][$roster_conf['server_name']]);
			$useroptions = '';
			foreach ($chars as $char)
			{
				if ($char != 'guild')
				{
					$useroptions .= '<option value="'.$char.'">'.$char.'</option>'.'\n';
				}
			}
			$body .= '<form action="'.$script_filename.'?page=update&amp;'.SID.'" enctype="multipart/form-data" method="POST" onsubmit="submitonce(this);">'."\n".
				border('sblue','start','Select a user name and password')."\n".
				'<table class="bodyline" cellspacing="0" cellpadding="0">'."\n".
					'<tr>'."\n".
						"\t".'<td class="membersRow1">User Name</td>'."\n".
						"\t".'<td class="membersRowRight1"><select name="user">'.$useroptions.'</select></td>'."\n".
					'</tr>'."\n".
					'<tr>'."\n".
						"\t".'<td class="membersRow2">Password</td>'."\n".
						"\t".'<td class="membersRowRight2"><input name="pass1" type="password"></td>'."\n".
					'</tr>'."\n".
					'<tr>'."\n".
						"\t".'<td class="membersRow1">Password (confirm)</td>'."\n".
						"\t".'<td class="membersRowRight1"><input name="pass2" type="password"></td>'."\n".
					'</tr>'."\n".
					'<tr>'."\n".
						"\t".'<td class="membersRowRight2" colspan="2" style="text-align: right;"><input type="submit" value="Go"></td>'."\n".
					'</tr>'."\n".
				'</table>'."\n".
				'<input type="hidden" name="process" value="process">'."\n".
				border('sblue','end');
		}
	}
	else
	{
		$messages .= $update->processFiles();
	}

	// Produce result page
	$errors = $wowdb->getErrors();
	$queries = $wowdb->getSQLStrings();

	if (!empty($errors))
	{
		$body .= scrollbox($errors,'Errors','sred');
		$body .= "<br />\n";
	}

	$body .= scrollbox($messages,'Update Log','syellow');

	if ($roster_conf['sqldebug'])
	{
		$body .= "<br />\n";
		$body .= scrollbox(nl2br(sql_highlight($queries)),'SQL Queries','sgreen');
	}
}
else
{
	// Remove uploaded data from session if relevant
	unset($_SESSION['uploadData']);
	
	$body .= '<form action="'.$script_filename.'?page=update" enctype="multipart/form-data" method="POST" onsubmit="submitonce(this);">'."\n";

	$body .= border('sblue','start','Select files to upload')."\n";
	$body .= '<table class="bodyline" cellspacing="0" cellpadding="0">'."\n";
	$body .= $update->makeFileFields();
	$body .= '</table>'."\n";
	$body .= border('sblue','end')."\n";

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