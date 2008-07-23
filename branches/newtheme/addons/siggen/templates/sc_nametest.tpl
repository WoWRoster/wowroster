<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /templates/sc_nametest.tpl
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
 * @version $Id: sc_nametest.tpl 322 2007-12-05 22:12:39Z Zanix $
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( $name_test != '' )
{
	$preview_image = '
	<tr>
		<td class="sc_row_right2" colspan="2"><img src="'.makelink('util-'.$addon['basename'].'-'.$config_name.'&amp;member='.$name_test.'&amp;saveonly=0&amp;etag=0').'" alt="'.$name_test.' '.$config_name.' image" /></td>
	</tr>';

	/* Not needed
	$functions->setMessage('Link to preview image:<br />
	[ '.makelink('util-'.$addon['basename'].'-'.$config_name.'&amp;member='.$name_test,false,true).' ]');
	*/
}
?>

<!-- Begin Image Preview Box -->
<form action="<?php print makelink(); ?>" method="post" enctype="multipart/form-data" name="preview_select" onsubmit="submitonce(this)">
<?php print border('sblue','start','Test SigGen'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row1" align="left">Select a name:
        <?php print $functions->createMemberList($member_list,$name_test,'name_test',1 ); ?></td>
      <td class="sc_row_right1" align="right"><input type="submit" value="Preview" /></td>
    </tr><?php print ( $name_test == '' ? '' : $preview_image ); ?>
  </table>
<?php print border('sblue','end'); ?>
</form>
<!-- End Image Preview Box -->
