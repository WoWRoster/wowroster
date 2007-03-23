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

<!-- Begin Config Select Box -->
  <form action="<?php print $script_filename; ?>" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
<?php print border('sgreen','start','Select Config Mode'); ?>
    <table width="145" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right2" colspan="2">Current Mode: <span class="sc_titletext"><?php print ucwords($config_name); ?></span></td>
      </tr>
      <tr>
        <td class="sc_row1"><select class="sc_select" name="config_name">
            <option value="signature"<?php print ( $config_name == 'signature' ? ' selected="selected"' : '' ); ?>>Signature</option>
            <option value="avatar"<?php print ( $config_name == 'avatar' ? ' selected="selected"' : '' ); ?>>Avatar</option>
          </select></td>
        <td class="sc_row_right1" align="right"><input type="submit" value="Go" /></td>
      </tr>
    </table>
<?php print border('sgreen','end'); ?>
  </form>
<!-- End Config Select Box -->
