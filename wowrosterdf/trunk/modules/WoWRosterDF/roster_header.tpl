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

define('HEADER_INC',true);

$pagetitle .= $module_title.' '._BC_DELIM.' '.$header_title;

include (BASEDIR.'header.php');
opentable();
?>
  <link rel="stylesheet" type="text/css" href="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['stylesheet'] ?>">
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['roster_js']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib_hide']; ?>"></script>
  <?php echo (isset($more_css) ? $more_css : ''); ?>
<div class="wowroster">

<?php
if( !isset($roster_conf['char_header_logo']) || $roster_conf['char_header_logo'] )
{
	echo '
<div style="text-align:center;margin:10px;" class="bodyline"><a href="'.$roster_conf['website_address'].'">
  <img src="'.$roster_conf['logo'].'" alt="" style="border:0;margin:10px;" /></a>
</div>';
}
?>

<div align="center" style="margin:10px;">
<!-- End Roster Header -->
