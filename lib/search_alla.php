<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

?>
<!-- BEGIN THOT SEARCH BOX -->
<?php print border('sorange','start'); ?>
<table cellspacing="0" cellpadding="4" border="0" class="bodyline">
  <tr>
    <td valign="middle" class="membersRowRight1">
      <div align="center">
        <img src="http://wow.allakhazam.com/images/wowex.png" alt="Allakhazam" width="158" height="51" /><br />
        <br />
      <form method="get" action="http://wow.allakhazam.com/search.html">
          <?php print $wordings[$roster_conf['roster_lang']]['search'] ?>:
          <input type="text" name="q" class="wowinput" />&nbsp;&nbsp;
          <input type="submit" value="Go" onclick="win=window.open('','myWin',''); this.form.target='myWin'">
      </form>
      </div></td>
  </tr>
</table>
<?php print border('sorange','end'); ?>
<!-- END THOT SEARCH BOX -->