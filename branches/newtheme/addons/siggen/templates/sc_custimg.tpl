<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /templates/sc_custimg.tpl
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
 * @version $Id: sc_custimg.tpl 321 2007-11-30 03:03:45Z Zanix $
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

<!-- Begin Custom Member Image Section -->
<div id="t9" style="display:none;">
<table>
	<tr>
		<td valign="top">

<?php
if( $allow_upload )
{
?>
<form id="images_upload" method="post" action="<?php print makelink(); ?>" enctype="multipart/form-data" name="images_upload" onsubmit="submitonce(this)">
<?php print border('sgray','start','<div style="width:187px;"><img src="'.$roster->config['img_url'].'blue-question-mark.gif" style="float:right;" alt="" />'.$functions->createTip( 'Images are currently located in:<br />\n&quot;'.str_replace('\\','/',SIGGEN_DIR.$configData['image_dir'].$configData['user_dir']).'&quot;','Upload User Images' ).'</div>'); ?>
	<table width="198" class="sc_table" cellspacing="0" cellpadding="2">
		<tr>
			<td class="sc_row_right1" align="center">Character Name:<br />
				<?php print $functions->createMemberList($member_list,$name_test,'image_name' ); ?></td>
		</tr>
		<tr>
			<td class="sc_row_right2" align="left">Image Upload Type:<br />
				<input type="radio" id="image_type_ch" name="image_type" value="" checked="checked" /><label for="image_type_ch">Character Image</label>
				<br />
				<input type="radio" id="image_type_bk" name="image_type" value="bk-" /><label for="image_type_bk">Background</label></td>
		</tr>
		<tr>
			<td class="sc_row_right1" align="left">Image location:<br />
				<input name="userfile" type="file" /></td>
		</tr>
		<tr>
			<td class="sc_row_right2" align="center"><input type="hidden" name="sc_op" value="upload_image" />
				<input type="submit" value="Upload Image" name="fileupload" /></td>
		</tr>
	</table>
<?php print border('sgray','end'); ?>
</form>
<?php
}
else
{
	print border('sred','start','Uploads DISABLED' );
	print border('sred','end');
}
?>

		</td>
		<td valign="top">
<?php
	// Get regular image files
	$userFilesArr = $functions->listFiles( SIGGEN_DIR.$configData['image_dir'].$configData['user_dir'],array('png','gif','jpeg','jpg') );

?>
<!-- Begin Image Delete Box -->
<?php
if( $allow_upload )
{
?>
  <form method="post" action="<?php print makelink(); ?>" enctype="multipart/form-data" name="image_delete" onsubmit="submitonce(this)">
<?php print border('sgray','start','<div style="width:187px;"><img src="'.$roster->config['img_url'].'blue-question-mark.gif" style="float:right;" alt="" />'.$functions->createTip( 'Images are currently located in:<br />\n&quot;'.str_replace('\\','/',SIGGEN_DIR.$configData['image_dir'].$configData['user_dir']).'&quot;','Delete User Images' ).'</div>'); ?>
    <table width="198" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center">Character Image:
          <?php print $functions->createOptionList( $userFilesArr,$name_test,'image_name',2 ); ?></td>
      </tr>
      <tr>
        <td class="sc_row_right2" align="center">
          <input type="hidden" name="sc_op" value="delete_image" />
          <input type="submit" value="Delete Image" name="delete_image" /></td>
      </tr>
    </table>
<?php print border('sgray','end'); ?>
  </form>
<?php
}
else
{
	print border('sred','start','Delete DISABLED' );
	print border('sred','end');
}
?>
		</td>
	</tr>
</table>
</div>
<!-- End Image Upload Box -->
