<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$config['menu'] = "
<table class='uuTABLE' width='145'>
	<tr>
		<th class='tableHeader'>Menu</th>
	</tr>
	<tr>
	<td>
		<ul class='ua_menu'>
			<li><a href='".UA_FORMACTION."help'>Help</a></li>
			<li><a href='".UA_FORMACTION."addons'>Addons</a></li>
			<li><a href='".UA_FORMACTION."logo'>Logos</a></li>
			<li><a href='".UA_FORMACTION."settings'>Settings</a></li>
			<li><a href='".UA_FORMACTION."stats'>Statistics</a></li>
			<li><a href='".UA_FORMACTION."users'>Users</a></li>
		</ul></td>
	</tr>
</table>";
?>