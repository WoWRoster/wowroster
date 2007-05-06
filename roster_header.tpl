<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Overall header for Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.6.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

define('ROSTER_HEADER_INC',true);

/**
 * Detect and set headers
 */
if( $roster->output['http_header'] && !headers_sent() )
{
	$now = gmdate('D, d M Y H:i:s', time()) . ' GMT';

	@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	@header('Last-Modified: ' . $now);
	@header('Cache-Control: no-store, no-cache, must-revalidate');
	@header('Cache-Control: post-check=0, pre-check=0', false);
	@header('Pragma: no-cache');
	@header('Content-type: text/html; ' . $roster->locale->act['charset']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>WoWRoster<?php echo ( !empty($roster->data['guild_name']) ? ' [ ' . $roster->data['guild_name'] . ' @ ' . $roster->data['server'] . ' ] ' : ' ' ) . (isset($roster->output['title']) ? $roster->output['title'] : ''); ?></title>
<?php print ( $roster->config['seo_url'] ? '	<base href="' . ROSTER_URL . '" />' : '' ) ?>
	<link rel="stylesheet" type="text/css" href="<?php echo ROSTER_PATH ?>css/style.css" />
	<script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/mainjs.js"></script>
	<script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/scrollbar.js"></script>
	<script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/tabcontent.js">
		/**
		 * Tab Content script- Dynamic Drive DHTML code library (www.dynamicdrive.com)
		 * This notice MUST stay intact for legal use
		 * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
		 **/
	</script>
	<script type="text/javascript">
	<!--
		var ol_width='220';var ol_offestx='10';var ol_offesty='10';var ol_hauto='1';var ol_vauto='1';
		var ol_fgclass='overlib_fg';var ol_bgclass='overlib_border';var ol_textfontclass='overlib_maintext';
		var ol_captionfontclass='overlib_captiontext';var ol_closefontclass='overlib_closetext';
	//-->
	</script>
	<script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/overlib.js"></script>
	<script type="text/javascript" src="<?php echo ROSTER_PATH ?>css/js/overlib_hideform.js"></script>
<?php echo (!empty($roster->output['html_head']) ? $roster->output['html_head'] : ''); ?>
</head>
<body<?php print( !empty($roster->config['roster_bg']) ? ' style="background-image:url(' . $roster->config['roster_bg'] . ');"' : '' ); echo (!empty($roster->output['body_attr']) ? ' ' . $roster->output['body_attr'] : ''); ?>>
<div id="overDiv" style="position:absolute;visibility:hidden;z-index:1000;"></div>
<script type="text/javascript">
<!--
	setOpacity( 'overDiv',8.5 );
//-->
</script>
<div align="center">

<?php
if( !empty($roster->config['logo']) )
{
	echo '<div style="text-align:center;margin:10px;"><a href="' . $roster->config['website_address'] . '">
  <img src="' . $roster->config['logo'] . '" alt="" style="border:0;margin:10px;" /></a>
</div>';
}
?>

<!-- End Roster Header -->

