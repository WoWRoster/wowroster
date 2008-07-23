<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /templates/sc_configselect.tpl
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id: sc_configselect.tpl 363 2008-02-07 05:16:09Z Zanix $
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}
?>

<!-- Begin Config Select Box -->
<?php print border('sgreen','start','Select Config Mode'); ?>
		<table width="150" class="sc_table" cellspacing="0" cellpadding="2">
			<tr>
				<td class="sc_row_right2">Current Mode: <span class="titletext"><?php print $config_name; ?></span></td>
			</tr>
			<tr>
				<td class="sc_row_right1"><form action="<?php print makelink(); ?>" method="post" enctype="multipart/form-data" name="config_select" onsubmit="submitonce(this)">
					<?php print $functions->createOptionList($config_list,$config_name,'config_name',3,'',false); ?>
					<input type="hidden" name="config_mode" value="switch" />
					<input type="submit" value="Go" /></form></td>
			</tr>
			<tr>
				<td class="sc_row_right2"><form action="<?php print makelink(); ?>" method="post" enctype="multipart/form-data" name="config_delete" onsubmit="submitonce(this)">
					<input type="hidden" name="config_mode" value="delete" />
					<input type="hidden" name="config_name" value="<?php print $config_name; ?>" />
					<input type="submit" value="Delete Current" /></form></td>
			</tr>
			<tr>
				<td class="sc_row_right1"><form action="<?php print makelink(); ?>" method="post" enctype="multipart/form-data" name="config_new" onsubmit="submitonce(this)">
					<input type="text" maxlength="20" size="15" value="*create new*" name="config_name" onclick="clickclear(this, '*create new*')" onblur="clickrecall(this,'*create new*')" />
					<input type="hidden" name="config_mode" value="new" />
					<input type="submit" value="New" /></form></td>
			</tr>
		</table>
<?php print border('sgreen','end'); ?>
<!-- End Config Select Box -->
