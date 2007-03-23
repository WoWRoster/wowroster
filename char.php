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

require_once( 'settings.php' );

define('HEADER_INC',true);

$name = (isset($_GET['name']) ? $_GET['name'] : '');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>[<?php echo $roster_conf['guild_name']; ?> Roster] Character Stats for: <?php echo $name ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['stylesheet'] ?>" />
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['roster_js']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib'] ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib_hide']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['profile'] ?>"></script>
</head>
<body>
<div class="bodyline">
<?php

if($roster_conf['char_header_logo'])
{
	echo '
<div style="text-align:center;margin:10px;" class="bodyline"><a href="'.$roster_conf['website_address'].'">
  <img src="'.$roster_conf['roster_dir'].'/'.$roster_conf['logo'].'" alt="" style="border:0;margin:10px;" /></a>
</div>';
}


include_once (ROSTER_BASE.'memberdetails.php');

// Explicitly close the db
$wowdb->closeDb();

?>

</div><!-- End main border -->

<div align="center">
  <small>WoW Roster v<?php echo $roster_conf['version'] ?></small>
  <br /><br />
  <small><?php echo $wordings[$roster_conf['roster_lang']]['roster_credits']; ?></small>
  <br /><br />
  <a href="http://validator.w3.org/check?uri=referer" target="_new">
      <img src="<?php echo $roster_conf['roster_dir']; ?>/img/validxhtml.gif" alt="Valid XHTML 1.0 Transitional" height="15" width="80" /></a>
</div>
</body>
</html>