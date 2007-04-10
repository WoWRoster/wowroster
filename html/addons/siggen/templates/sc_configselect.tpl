
<!-- Begin Config Select Box -->
  <form action="<?php print $script_filename; ?>" method="post" enctype="multipart/form-data">
    <table width="136" class="sc_table" cellspacing="1">
      <tr>
        <th colspan="2" class="membersHeader">Select a Config Mode</th>
      </tr>
      <tr class="sc_row1">
        <td><select class="sc_select" name="config_name">
            <option value="signature"<?php print ( $config_name == 'signature' ? ' selected="selected"' : '' ); ?>>Signature</option>
            <option value="avatar"<?php print ( $config_name == 'avatar' ? ' selected="selected"' : '' ); ?>>Avatar</option>
          </select></td>
        <td align="right"><input type="submit" value="Go" /></td>
      </tr>
      <tr class="sc_row2">
        <td colspan="2">Selected: <span class="sc_titletext"><?php print ucwords($config_name); ?></span></td>
      </tr>
    </table>
  </form>
<!-- End Config Select Box -->
