<?php
/*******************************
 * $Id$
 *******************************/
?>

<!-- Begin Settings Reset Box -->
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data" onsubmit="submitonce(this)">
  <div id="resetdbCol">
<?php print border('sgray','start','<div style="cursor:pointer;width:187px;" onclick="swapShow(\'resetdbCol\',\'resetdb\')"><img src="'.$roster_conf['img_url'].'plus.gif" style="float:right;" alt="+" />Reset to Defaults</div>'); ?>
<?php print border('sgray','end'); ?>
  </div>
  <div id="resetdb" style="display:none;">
<?php print border('sgray','start','<div style="cursor:pointer;width:187px;" onclick="swapShow(\'resetdbCol\',\'resetdb\')"><img src="'.$roster_conf['img_url'].'minus.gif" style="float:right;" alt="-" />Reset to Defaults</div>'); ?>
    <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center">Check to confirm reset<br />
          <input type="checkbox" class="checkBox" name="confirm_reset" value="1" /></td>
      </tr>
      <tr>
        <td class="sc_row_right2" align="center">
          <input type="hidden" name="sc_op" value="reset_defaults" />
          <input class="button" type="submit" value="Default Settings" name="resetDefault" /></td>
      </tr>
    </table>
<?php print border('sgray','end'); ?>
  </div>
  </form>
<!-- End Settings Reset Box -->
