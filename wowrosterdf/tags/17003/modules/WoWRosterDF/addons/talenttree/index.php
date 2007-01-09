<?php
/*
* $Date: 2006/01/11 19:45:26 $
* $Revision: 1.9 $
*/

if ( !defined( 'CPG_NUKE' ) )
{
	exit;
}

$header_title .= $wordings[$roster_conf['roster_lang']]['talenttree'];


$output = "<script type=\"text/javascript\" language=\"JavaScript\">
<!--
	var mt_width = 600;
	var mt_css   = '".$roster_conf['website_address'].'/'.$roster_conf['roster_dir'].$css."';
//-->
</script>
<script type=\"text/javascript\" src=\"http://www.wowhead.com/talent/include.js\"></script>";

	echo $output;

?>