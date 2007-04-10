<?php
$preview_image = '
  <tr class="sc_row2">
    <td><img src="addons/siggen/siggen.php?name='.urlencode(utf8_decode($name_test)).'&amp;mode='.$config_name.'&amp;saveonly=0&amp;etag=0" alt="'.$name_test.' '.$config_name.' image" /></td>
  </tr>';
?>

<!-- Begin Image Preview Box -->
<form action="<?php print $script_filename; ?>" method="post" enctype="multipart/form-data">
  <table class="sc_table" cellspacing="1">
    <tr>
      <th class="membersHeader">Test SigGen</th>
    </tr>
    <tr class="sc_row1">
      <td>Select a name: 
        <?php print $functions->createOptionListValue($member_list,$name_test,'name_test' ); ?>
        <input type="submit" value="Go" /></td>
    </tr><?php print ( $name_test == '' ? '' : $preview_image ); ?>
  </table>
</form>
<!-- End Image Preview Box -->
