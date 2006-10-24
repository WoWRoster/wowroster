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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

?>
<!-- BEGIN THOT SEARCH BOX -->
<?php print border('sorange','start'); ?>
<table cellspacing="0" cellpadding="4" border="0" class="wowroster">
  <tr>
    <td valign="middle" class="membersRowRight1">
      <p align="center">
        <img src="http://wow.allakhazam.com/images/wowex.png" alt="Allakhazam" width="158" height="51" /><br />
        <br />
      </p>
      <form method="get" action="http://wow.allakhazam.com/search.html">
        <p align="center">
          <?php print $wordings[$roster_conf['roster_lang']]['search'] ?>:
          <input type="text" name="q" />&nbsp;&nbsp;
          <input type="submit" value="Go" onclick="win=window.open('','myWin',''); this.form.target='myWin'"></span>
        </p>
      </form></td>
  </tr>
</table>
<?php print border('sorange','end'); ?>
<!-- END THOT SEARCH BOX -->