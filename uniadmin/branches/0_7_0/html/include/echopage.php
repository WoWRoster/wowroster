<?php
/******************************
 * WoWRoster.net  UniAdmin
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Output the display
 *
 * @param string $body
 * @param string $subtitle
 */
function display_page( $body , $subtitle = 'Index' )
{
	global $login_form, $ua_menu, $uniadmin, $db, $user;

	@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	@header('Last-Modified: ' . $now);
	@header('Cache-Control: no-store, no-cache, must-revalidate');
	@header('Cache-Control: post-check=0, pre-check=0', false);
	@header('Pragma: no-cache');
	@header('Content-Type: text/html; charset=iso-8859-1');

	$mc_split = split(' ', microtime());
	$uniadmin->timer_end = $mc_split[0] + $mc_split[1];
	unset($mc_split);

	if( UA_DEBUG && (isset($user->data['level']) && $user->data['level'] == UA_ID_ADMIN ) )
	{
		$mc_split = split(' ', microtime());
		$uniadmin->timer_end = $mc_split[0] + $mc_split[1];
		unset($mc_split);

		$s_show_queries = ( UA_DEBUG == 2 ) ? true : false;

		$s_show_debug = true;
		$s_rendertime = substr($uniadmin->timer_end - $uniadmin->timer_start, 0, 5);
		$s_querycount = $db->query_count;
	}
	else
	{
		$s_show_debug = false;
		$s_show_queries = false;
	}

	echo '<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>UniAdmin v'.$uniadmin->config['UAVer'].' ['.$subtitle.']</title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<link rel="stylesheet" type="text/css" media="screen" href="'.$uniadmin->url_path.'/style.css" />
	<script type="text/javascript" src="'.$uniadmin->url_path.'/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	<script type="text/javascript" src="'.$uniadmin->url_path.'/overlib/overlib_hideform.js"></script>
</head>
<body>

<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="201" valign="top"><a href="'.UA_INDEX.'"><img src="'.$uniadmin->url_path.'/images/logo.png" alt="UniAdmin" /></a></td>
    <td width="100%" valign="top">
      <span class="maintitle">UniAdmin v'.$uniadmin->config['UAVer'].'</span><br />
      '.(isset($subtitle) ? '<span class="subtitle">'.$subtitle.'</span>' : '').'<br />
      '.$login_form.'<br />';

	if( isset($ua_menu) )
	{
		echo '
	<span class="ua_menu">
'.$ua_menu.'
	</span>
	<br /><br />
	<span class="sync_url">
		'.$user->lang['syncro_url'].' ('.$user->lang['verify_syncro_url'].'):
		<a href="'.$uniadmin->config['interface_url'].'" target="_blank">'.$uniadmin->config['interface_url'].'</a>
	</span>';
	}

	echo '
    </td>
  </tr>
</table>
<br />
<br />';

	if( !empty($uniadmin->messages) && is_array($uniadmin->messages) )
	{
		echo '<table class="ua_table" width="30%" align="center">
	<tr>
		<th class="table_header">'.$user->lang['messages'].'</th>
	</tr>
';
		$i=0;
		foreach( $uniadmin->messages as $message )
		{
			echo "<tr>\n<td class=\"data".$uniadmin->switch_row_class()."\">$message</td>\n</tr>\n";

			$i++;
		}
		echo "</table>\n<br />\n";
	}

	if( !empty($uniadmin->debug) && is_array($uniadmin->debug) )
	{
		echo '<table class="ua_table" width="30%" align="center">
	<tr>
		<th class="debug_header">'.$user->lang['debug'].'</th>
	</tr>
';
		$i=0;
		foreach( $uniadmin->debug as $message )
		{
			echo "<tr>\n<td class=\"data".$uniadmin->switch_row_class()."\">$message</td>\n</tr>\n";

			$i++;
		}
		echo "</table>\n<br />\n";
	}

	echo $body;

	if( $s_show_queries )
	{
		echo '<br />
<table class="ua_table" width="80%" align="center">
  <tr>
    <th class="table_header">'.$user->lang['queries'].'</th>
  </tr>';
		foreach( $db->queries as $query )
		{
			echo '  <tr class="data'.$uniadmin->switch_row_class().'">
    <td width="100%">'.$query.'</td>
  </tr>';
		}
		echo '</table>';
	}
		echo '
<br />
<!--
    If you use this software and find it to be useful, we ask that you retain the copyright notice below.
//-->
<div class="ua_hr"><hr /></div>
<br />
<div align="center">
	<span class="copyright"><a href="http://www.wowroster.net/" target="_blank">UniAdmin</a> v'.$uniadmin->config['UAVer'].'<br />
	&copy; 2006 The WoWRoster Dev Team</span><br />';

	if( $s_show_debug )
	{
		echo '<br /><span class="copyright">'.$s_rendertime.' | '.$s_querycount.'</span><br />';
	}

	echo '<br />
	<a href="http://creativecommons.org/licenses/by-nc-sa/2.5/" target="_blank"><img src="http://creativecommons.org/images/public/somerights20.png" alt="Creative Commons License" height="31" width="88" /></a><br />
	<span class="copyright">UniAdmin is licensed under <a href="http://creativecommons.org/licenses/by-nc-sa/2.5/" target="_blank">Creative Commons Attribution-NonCommercial-ShareAlike 2.5</a></span>';


	echo '</div>
</body>
</html>';
}

?>