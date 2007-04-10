
<!-- Begin Password Input Box -->
<form action="<?php print $script_filename; ?>" method="post" enctype="multipart/form-data">
  <table class="sc_table" cellspacing="1">
    <tr>
      <th colspan="2" class="membersHeader">Authorization Required</th>
    </tr>
    <tr class="sc_row1">
      <td colspan="2">Password:<br />
        <input name="pass_word" type="password" size="32" maxlength="32" /></td>
    </tr>
    <tr class="sc_row2">
      <td>
        Select a Config:<br />
        <select class="sc_select" name="config_name">
          <option value="signature">Signature</option>
          <option value="avatar">Avatar</option>
        </select></td>
      <td valign="bottom" align="right">
        <input type="submit" value="Go" /></td>
    </tr>
  </table>
</form>
<!-- End Password Input Box -->
