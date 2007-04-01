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
	@header('Content-type: text/html; '.$act_words['charset']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>[<?php echo $roster_conf['guild_name']; ?> Roster] <?php echo (isset($header_title) ? $header_title : ''); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo ROSTER_PATH ?>css/styles.css" />
<?php echo (isset($more_css) ? $more_css : ''); ?>

  <script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/mainjs.js"></script>
  <script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/scrollbar.js"></script>
  <script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/tabcontent.js">
    /***********************************************
    * Tab Content script- Dynamic Drive DHTML code library (www.dynamicdrive.com)
    * This notice MUST stay intact for legal use
    * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
    ***********************************************/
  </script>
  <script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/overlib.js"></script>
  <script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/overlib_hideform.js"></script>
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
