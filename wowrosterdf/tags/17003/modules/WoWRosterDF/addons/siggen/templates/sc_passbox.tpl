<?php
/******************************
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
 ******************************/
?>

<!-- Begin Password Input Box -->
<form action="<?php print $script_filename; ?>" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
<?php print border('sred','start','Authorization Required'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row_right1" colspan="2" align="left">Password:<br />
        <input name="pass_word" type="password" size="32" maxlength="32" /></td>
    </tr>
    <tr>
      <td class="sc_row_right2" align="center">
        :Select Config Mode:<br />
        <select class="sc_select" name="config_name">
          <option value="signature">Signature</option>
          <option value="avatar">Avatar</option>
        </select></td>
    </tr>
    <tr>
      <td class="sc_row_right1" align="center">
        <input type="submit" value="Go" /></td>
    </tr>
  </table>
<?php print border('sred','end'); ?>
</form>
<!-- End Password Input Box -->
