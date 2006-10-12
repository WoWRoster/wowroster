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


$help = '
<table class="ua_table" cellpadding="2" align="center" width="95%">
	<tr>
		<th class="table_header">'.$user->lang['title_help'].'</th>
	</tr>';

foreach( $user->lang['help'] as $help_text )
{
	$help .= '
	<tr>
		<td class="data_header">'.$help_text['header'].'</td>
	</tr>
	<tr>
		<td class="data1">'.$help_text['text'].'</td>
	</tr>';
}

$help .= '
</table>';

display_page($help,$user->lang['title_help']);

?>