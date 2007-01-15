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

$pagetitle = $module_title.( !empty($header_title) ? ' '._BC_DELIM.' '.$header_title : '' );

$modheader = '
  <link rel="stylesheet" type="text/css" href="'.$roster_conf['roster_dir'].'/'.$roster_conf['stylesheet'].'" />
'.(isset($more_css) ? $more_css : '').'
  <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['roster_js'].'"></script>
  <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['tabcontent'].'">
    /***********************************************
    * Tab Content script- Dynamic Drive DHTML code library (www.dynamicdrive.com)
    * This notice MUST stay intact for legal use
    * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
    ***********************************************/
  </script>
'.( ( !isset($roster_conf['item_stats']) || $roster_conf['item_stats'] ) ?
	'<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['overlib'].'"></script>'."\n".
	'<script type="text/javascript" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['overlib_hide'].'"></script>'."\n"
	: '
  <script type="text/javascript">
	  <!--
	  var ol_fgclass="overlib_fg"
	  var ol_bgclass="overlib_border"
	  var ol_textfontclass="overlib_maintext"
	  var ol_captionfontclass="overlib_captiontext"
	  var ol_closefontclass="overlib_closetext"
	  var ol_hauto = 1;
	  var ol_vauto = 1;
	  //-->
  </script>' ).
(isset($html_head) ? $html_head : '');

include (BASEDIR.'header.php');
opentable();
?>

<div class="wowroster"<?php print( !empty($roster_conf['roster_bg']) ? ' style="background-image:url('.$roster_conf['roster_bg'].');"' : '' ); echo (isset($body_action) ? ' '.$body_action : ''); ?>>
<?php

if( !empty($roster_conf['logo']) )
{
	echo '<div style="text-align:center;margin:10px;"><a href="'.$roster_conf['website_address'].'">
  <img src="'.$roster_conf['logo'].'" alt="" style="border:0;margin:10px;" /></a>
</div>
';
}
else
{
	print '<br />';
}

?>
<div align="center" style="margin:10px;">

<!-- End Roster Header -->
