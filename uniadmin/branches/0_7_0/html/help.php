<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}


$help = '
<table class="uuTABLE" cellpadding="2" align="center" width="95%">
	<tr>
		<th class="tableHeader">'.$user->lang['title_help'].'</th>
	</tr>';

foreach( $user->lang['help'] as $help_text )
{
	$help .= '
	<tr>
		<td class="dataHeader">'.$help_text['header'].'</td>
	</tr>
	<tr>
		<td class="data1">'.$help_text['text'].'</td>
	</tr>';
}

$help .= '
</table>';

EchoPage($help,$user->lang['title_help']);
?>