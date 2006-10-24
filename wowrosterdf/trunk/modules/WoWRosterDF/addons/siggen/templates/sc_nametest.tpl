<?php
/*******************************
 * $Id$
 *******************************/
?>

<?php
$preview_image = '
  <tr>
    <td class="sc_row_right2" colspan="2"><img src="'.getlink('&amp;file=addon&amp;roster_addon_name=siggen&amp;mode='.$config_name.'&amp;member='.urlencode(utf8_decode($name_test)).'&amp;saveonly=0&amp;etag=0').'" alt="'.$name_test.' '.$config_name.' image" /></td>
  </tr>';
?>

<!-- Begin Image Preview Box -->
<form action="<?php print $script_filename; ?>" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
<?php print border('sblue','start','Test SigGen'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row1" align="left">Select a name:
        <?php print $functions->createOptionListValue($member_list,$name_test,'name_test' ); ?></td>
      <td class="sc_row_right1" align="right"><input type="submit" value="Preview" /></td>
    </tr><?php print ( $name_test == '' ? '' : $preview_image ); ?>
  </table>
<?php print border('sblue','end'); ?>
</form>
<!-- End Image Preview Box -->
