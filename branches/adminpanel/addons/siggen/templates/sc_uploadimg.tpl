<?php
/*******************************
 * $Id$
 *******************************/
?>

<!-- Begin Image Upload Box -->
<?php
if( $allow_upload )
{
?>
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data" onsubmit="submitonce(this)">
<?php print border('sgray','start','<div style="width:187px;"><img src="'.$roster_conf['img_url'].'blue-question-mark.gif" style="float:right;" alt="" />'.$functions->createTip( 'Images are currently located in:<br />\n&quot;'.str_replace('\\','/',$sigconfig_dir.$configData['image_dir'].$configData['user_dir']).'&quot;','Upload User Images' ).'</div>'); ?>
    <table width="198" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center">Character Name:<br />
          <?php print $functions->createOptionListValue($member_list,$name_test,'image_name' ); ?></td>
      </tr>
      <tr>
        <td class="sc_row_right2" align="left">Image Upload Type:<br /><label>
        <input type="radio" class="checkBox" name="image_type" value="" checked="checked" />
        Character Image</label><label>
        <br />
        <input type="radio" class="checkBox" name="image_type" value="bk-" />
        Background</label></td>
      </tr>
      <tr>
        <td class="sc_row_right1" align="left">Image location:<br />
          <input class="inputbox" name="userfile" type="file" /></td>
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
<!-- End Image Upload Box -->
