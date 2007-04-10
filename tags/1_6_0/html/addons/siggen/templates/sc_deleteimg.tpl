<?php
	// Get regular image files
	$userFilesArr = $functions->listFiles( $sigconfig_dir.$configData['image_dir'].$configData['user_dir'],'png' );

?>

<!-- Begin Image Delete Box -->
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data">
    <table width="198" class="sc_table" cellspacing="1">
      <tr>
        <th class="membersHeader"><?php print $functions->createTip( 'Images are currently located in:<br />\n&quot;'.$sigconfig_dir.$configData['image_dir'].$configData['user_dir'].'&quot;','Delete User Images' ); ?></th>
      </tr>
      <tr class="sc_row1">
        <td align="center">Character Image: 
          <?php print $functions->createOptionList( $userFilesArr,$name_test.'.png','image_name' ); ?>
        </td>
      </tr>
      <tr class="sc_row2">
        <td align="center">
          <input type="hidden" name="op" value="delete_image" />
          <input type="submit" value="Delete Image" name="delete_image" />
        </td>
      </tr>
    </table>
  </form>
<!-- End Image Delete Box -->
