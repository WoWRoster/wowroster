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

define('ROSTER_HEADER_INC',true);

/**
 * Detect and set headers
 */
if( !isset($no_roster_headers) && !headers_sent() )
{
	$now = gmdate('D, d M Y H:i:s', time()) . ' GMT';

	@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	@header('Last-Modified: ' . $now);
	@header('Cache-Control: no-store, no-cache, must-revalidate');
	@header('Cache-Control: post-check=0, pre-check=0', false);
	@header('Pragma: no-cache');
	@header('Content-type: text/html; '.$wordings[$roster_conf['roster_lang']]['charset']);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>[<?php echo $roster_conf['guild_name']; ?> Roster] <?php echo (isset($header_title) ? $header_title : ''); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['stylesheet'] ?>">
<?php echo (isset($more_css) ? $more_css : ''); ?>

  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['roster_js']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['tabcontent']; ?>">
    /***********************************************
    * Tab Content script- Dynamic Drive DHTML code library (www.dynamicdrive.com)
    * This notice MUST stay intact for legal use
    * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
    ***********************************************/
  </script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib']; ?>"></script>
  <script type="text/javascript" src="<?php echo $roster_conf['roster_dir'] ?>/<?php echo $roster_conf['overlib_hide']; ?>"></script>
<?php echo (isset($html_head) ? $html_head : ''); ?>
</head>
<body<?php print( !empty($roster_conf['roster_bg']) ? ' style="background-image:url('.$roster_conf['roster_bg'].');"' : '' ); echo (isset($body_action) ? ' '.$body_action : ''); ?>>
<div id="overDiv" style="position:absolute;visibility:hidden;z-index:1000;"></div>
<script type="text/javascript">
<!--
	setOpacity( 'overDiv',8.5 );
//-->
</script>
<div align="center">

<?php
if( !empty($roster_conf['logo']) )
{
	echo '<div style="text-align:center;margin:10px;"><a href="'.$roster_conf['website_address'].'">
  <img src="'.$roster_conf['logo'].'" alt="" style="border:0;margin:10px;" /></a>
</div>';
}
?>

<!-- End Roster Header -->
