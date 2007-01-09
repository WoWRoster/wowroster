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

<!-- Begin Roster Footer -->
</div><!-- End roster main area -->

</div><!-- End main border -->

<div align="center">
  <small>WoW Roster v<?php print $roster_conf['version'] ?></small>
  <br /><br />
  <small><?php echo $wordings[$roster_conf['roster_lang']]['roster_credits']; ?></small>
</div>

<?php
closetable();
include(BASEDIR.'footer.php');
?>