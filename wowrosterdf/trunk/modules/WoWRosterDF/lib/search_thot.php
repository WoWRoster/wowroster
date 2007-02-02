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

//DF security
if (!defined('CPG_NUKE')) { exit; }
//Roster security
/*
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}*/

?>
<!-- BEGIN THOT SEARCH BOX -->
<?php print border('sblue','start'); ?>
<table cellspacing="0" cellpadding="4" border="0" class="bodyline">
  <tr>
    <td valign="middle" class="membersRowRight1">
      <div align="center">
        <img src="<?php print $roster_conf['img_url']; ?>thottbot.gif" alt="Thottbot" width="158" height="51" /><br />
        <br />
      <form method="post" action="http://www.thottbot.com/">
          <?php print $wordings[$roster_conf['roster_lang']]['search'] ?>:
          <input type="text" name="s" class="wowinput" />&nbsp;&nbsp;
          <input type="submit" value="Go" onclick="win=window.open('','myWin',''); this.form.target='myWin'">
      </form>
      </div></td>
  </tr>
</table>
<?php print border('sblue','end'); ?>
<!-- END THOT SEARCH BOX -->