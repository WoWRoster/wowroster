
<!-- Begin Settings Reset Box -->
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data">
    <table width="198" class="sc_table" cellspacing="1">
      <tr>
        <th class="membersHeader">Reset to Defaults</th>
      </tr>
      <tr class="sc_row1">
        <td align="center">Check to confirm reset<br />
          <input type="checkbox" name="confirm_reset" value="1" /></td>
      </tr>
      <tr class="sc_row2">
        <td align="center">
          <input type="hidden" name="op" value="reset_defaults" />
          <input class="button" type="submit" value="Default Settings" name="resetDefault" />
        </td>
      </tr>
    </table>
  </form>
<!-- End Settings Reset Box -->
