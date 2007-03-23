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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>[<?php echo $roster_conf['guild_name']; ?> Roster] <?php echo (isset($header_title) ? $header_title : ''); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['stylesheet'] ?>">
<?php echo (isset($more_css) ? $more_css : ''); ?>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['roster_js']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib_hide']; ?>"></script>
<?php echo (isset($html_head) ? $html_head : ''); ?>
</head>
<body style="background-image:url(<?php echo $roster_conf['roster_bg']; ?>);" <?php echo (isset($body_action) ? $body_action : ''); ?>>
<div align="center">

<?php
if( !isset($roster_conf['char_header_logo']) || $roster_conf['char_header_logo'] )
{
	echo '<div style="text-align:center;margin:10px;"><a href="'.$roster_conf['website_address'].'">
  <img src="'.$roster_conf['logo'].'" alt="" style="border:0;margin:10px;" /></a>
</div>';
}
?>

<!-- End Roster Header -->
