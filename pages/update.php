<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LUA update interface
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage LuaUpdate
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Include update lib
require_once(ROSTER_LIB.'update.lib.php');
$update = new update;

// login class for guild update pass check
$roster_login = new RosterLogin();

// See if UU is requesting this page
if( eregi('uniuploader',$_SERVER['HTTP_USER_AGENT']) )
{
	$update->textmode = true;
}

// Fetch addon data
$messages = $update->fetchAddonData();

// Has data been uploaded?
if( (isset($_POST['process']) && $_POST['process'] == 'process') || $update->textmode )
{
	$messages .= $update->parseFiles();
	$messages .= $update->processFiles();

	$errors = $update->getErrors();

	// Normal upload results
	if( !$update->textmode )
	{
		include_once(ROSTER_BASE.'roster_header.tpl');
		$roster_menu = new RosterMenu;
		print $roster_menu->makeMenu($roster->output['show_menu']);

		// print the error messages
		if( !empty($errors) )
		{
			print scrollboxtoggle($errors,'<span class="red">'.$roster->locale->act['update_errors'].'</span>','sred',false);

			// Print the downloadable errors separately so we can generate a download
			print "<br />\n";
			print '<form method="post" action="'.makelink().'" name="post">'."\n";
			print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($errors)).'" />'."\n";
			print '<input type="hidden" name="send_file" value="error_log" />'."\n";
			print '<input type="submit" name="download" value="'.$roster->locale->act['save_error_log'].'" />'."\n";
			print '</form>';
			print "<br />\n";
		}

		// Print the update messages
		print scrollbox('<div style="text-align:left;font-size:10px;">'.$messages.'</div>',$roster->locale->act['update_log'],'syellow');

		// Print the downloadable messages separately so we can generate a download
		print "<br />\n";
		print '<form method="post" action="'.makelink().'" name="post">'."\n";
		print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($messages)).'" />'."\n";
		print '<input type="hidden" name="send_file" value="update_log" />'."\n";
		print '<input type="submit" name="download" value="'.$roster->locale->act['save_update_log'].'" />'."\n";
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
	$roster_menu = new RosterMenu;
	print $roster_menu->makeMenu($roster->output['show_menu']);

	print '<form action="'.makelink().'" enctype="multipart/form-data" method="post" onsubmit="submitonce(this);">'."\n";

	print messagebox('<table class="bodyline" cellspacing="0" cellpadding="0">'.$update->makeFileFields().'</table>',$roster->locale->act['update_page'],'sblue');

	print "<br />\n";

	print border('sgray','start',$roster->locale->act['gp_user_only']);
	print '
                  <table class="bodyline" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="membersRow1" style="cursor:help;" onmouseover="overlib(\''.$roster->locale->act['roster_upd_pw_help'].'\',CAPTION,\''.$roster->locale->act['roster_upd_pwLabel'].'\',WRAP,RIGHT);" onmouseout="return nd();"><img src="'.$roster->config['img_url'].'blue-question-mark.gif" alt="?" /> '.$roster->locale->act['roster_upd_pwLabel'].'</td>
                      <td class="membersRowRight1"><input class="wowinput128" type="password" name="password" /></td>
                    </tr>
                  </table>'."\n";
	print border('sgray','end');

	print "<br />\n";

	print '<input type="hidden" name="process" value="process" />'."\n";
	print '<input type="submit" value="'.$roster->locale->act['upload'].'" />'."\n";
	print '</form>'."\n";

	if (!empty($messages))
	{
		print "<br />\n";
		print scrollbox($messages,'Messages','syellow');
	}

	include_once(ROSTER_BASE.'roster_footer.tpl');
}
