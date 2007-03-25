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

//---[ Update File Downloader ]-----------------------------
if( isset($_POST['send_file']) && !empty($_POST['send_file']) && !empty($_POST['data']) )
{
	$file = $_POST['data'];

	header('Content-Type: text/x-delimtext; name="'.$_POST['send_file'].'_log.txt"');
	header('Content-disposition: attachment; filename="'.$_POST['send_file'].'_log.txt"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	echo stripslashes($file);

	exit;
}


// Include update lib
require_once(ROSTER_LIB.'update.lib.php');
$update = new update;

// login class for guild update pass check
$roster_login = new RosterLogin('update');

// Update Triggers
if( $roster_conf['use_update_triggers'] )
{
	include_once( ROSTER_LIB.'update_trigger_lib.php' );
}

// Set $htmlout to 1 to assume request is from a browser
$isUU = 0;

// See if UU is requesting this page
if( eregi('uniuploader',$_SERVER['HTTP_USER_AGENT']) )
{
	$isUU = 1;
}

// Fetch addon data
$messages = $update->fetchAddonData();

// Has data been uploaded?
if (($_POST['process'] == 'process') || $isUU)
{
	$messages .= $update->parseFiles();
	$messages .= $update->processFiles();
	
	$errors = $wowdb->getErrors();

	// Normal upload results
	if( !$isUU )
	{
		include_once(ROSTER_BASE.'roster_header.tpl');
		include_once(ROSTER_LIB.'menu.php');

		// print the error messages
		if( !empty($errorstringout) )
		{
			print scrollboxtoggle($errors,'<span class="red">'.$act_words['update_errors'].'</span>','sred',false);

			// Print the downloadable errors separately so we can generate a download
			print "<br />\n";
			print '<form method="post" action="'.makelink('update').'" name="post">'."\n";
			print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($errors)).'" />'."\n";
			print '<input type="hidden" name="send_file" value="error" />'."\n";
			print '<input type="submit" name="download" value="'.$act_words['save_error_log'].'" />'."\n";
			print '</form>';
			print "<br />\n";
		}

		// Print the update messages
		print scrollbox('<div style="text-align:left;font-size:10px;">'.$messages.'</div>',$act_words['update_log'],'syellow');

		// Print the downloadable messages separately so we can generate a download
		print "<br />\n";
		print '<form method="post" action="'.makelink('update').'" name="post">'."\n";
		print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($messages)).'" />'."\n";
		print '<input type="hidden" name="send_file" value="update" />'."\n";
		print '<input type="submit" name="download" value="'.$act_words['save_update_log'].'" />'."\n";
		print '</form>';
		print "<br />\n";
		
		include_once(ROSTER_BASE.'roster_footer.tpl');
	}
	else
	{ // No-HTML result page for UU
		print stripAllHtml($messages);
	}
}
else
{
	// No data uploaded, so return upload form
	include_once(ROSTER_BASE.'roster_header.tpl');
	include_once(ROSTER_LIB.'menu.php');

	print '<form action="'.makelink('update').'" enctype="multipart/form-data" method="POST" onsubmit="submitonce(this);">'."\n";

	print messagebox('<table class="bodyline" cellspacing="0" cellpadding="0">'.$update->makeFileFields().'</table>',$act_words['update_page'],'sblue');

	print "<br />\n";

	print border('sgray','start',$act_words['gp_user_only']);
	print '
                  <table class="bodyline" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="membersRow1" style="cursor:help;" onmouseover="overlib(\''.$act_words['roster_upd_pw_help'].'\',CAPTION,\''.$act_words['roster_upd_pwLabel'].'\',WRAP,RIGHT);" onmouseout="return nd();"><img src="'.$roster_conf['img_url'].'blue-question-mark.gif" alt="" /> '.$act_words['roster_upd_pwLabel'].'</td>
                      <td class="membersRowRight1"><input class="wowinput128" type="password" name="password" /></td>
                    </tr>
                  </table>'."\n";
	print border('sgray','end');
	
	print "<br />\n";

	print '<input type="hidden" name="process" value="process">'."\n";
	print '<input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['upload'].'">'."\n";
	print '</form>'."\n";

	if (!empty($messages))
	{
		print "<br />\n";
		print scrollbox($messages,'Messages','syellow');
	}
	
	include_once(ROSTER_BASE.'roster_footer.tpl');
}