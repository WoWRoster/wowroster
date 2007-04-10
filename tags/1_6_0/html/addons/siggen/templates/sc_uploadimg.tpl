
<!-- Begin Image Upload Box -->
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data">
    <table width="198" class="sc_table" cellspacing="1">
      <tr>
        <th class="membersHeader"><?php print $functions->createTip( 'Images are currently located in:<br />\n&quot;'.$sigconfig_dir.$configData['image_dir'].$configData['user_dir'].'&quot;','Upload User Images' ); ?></th>
      </tr>
      <tr class="sc_row1">
        <td align="center">Character Name:<br />
          <?php print $functions->createOptionListValue($member_list,$name_test,'image_name' ); ?></td>
      </tr>
      <tr class="sc_row2">
        <td>Image Upload Type:<br /><label>
        <input type="radio" name="image_type" value="" checked="checked" />
        Character Image</label><label>
        <br />
        <input type="radio" name="image_type" value="bk-" />
        Background</label></td>
      </tr>
      <tr class="sc_row1">
        <td>Image location:<br />
          <input class="inputbox" name="userfile" type="file" /></td>
      </tr>
      <tr class="sc_row2">
        <td align="center"><input type="hidden" name="op" value="upload_image" />
          <input type="submit" value="Upload Image" name="fileupload" /></td>
      </tr>
    </table>
  </form>
<!-- End Image Upload Box -->
