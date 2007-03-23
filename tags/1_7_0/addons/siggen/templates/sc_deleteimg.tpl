<?php
/*******************************
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
 *******************************/
?>

<?php
	// Get regular image files
	$userFilesArr = $functions->listFiles( $sigconfig_dir.$configData['image_dir'].$configData['user_dir'],'png' );

?>
<!-- Begin Image Delete Box -->
<?php
if( $allow_upload )
{
?>
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data" onsubmit="submitonce(this)">
<?php print border('sgray','start',$functions->createTip( 'Images are currently located in:<br />\n&quot;'.str_replace('\\','/',$sigconfig_dir.$configData['image_dir'].$configData['user_dir']).'&quot;','Delete User Images' )); ?>
    <table width="198" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center">Character Image:
          <?php print $functions->createOptionList( $userFilesArr,$name_test.'.png','image_name' ); ?></td>
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
<!-- End Image Delete Box -->
