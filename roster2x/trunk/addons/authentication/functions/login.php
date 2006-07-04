<?php
require_once '../auth_conf.php';

function show_login()
{
	print '
	<form method="post">
	<table style="border:1px solid #212121; width:130px; font-weight:bold;" border="0" cellpadding="2" cellspacing="1">
		<tr>
			<td class="sc_menuTH" align="left" style="font-size:14px; font-weight:bold;" colspan="2">
				Login
			</td>
		</tr>
		<tr class="sc_menuTH">
			<td>
				Username
			</td>
			<td>
				<input name="handle" type="text">
			</td>
		</tr>
		<tr class="sc_menuTH">
			<td>
				Password
			</td>
			<td>
				<input name="passwd" type="password">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="submit" type="submit" value=" Login " class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="sc_menuTH"><input name="rememberMe" type="checkbox" value="1"> Remember me</td>
		</tr>
	</table>
	</form>
	';
}


function do_login()
{
	global $LU;
	$LU->login(@base64_encode(ucwords($_POST['handle'])), @$_POST['passwd'], @$_POST['rememberMe']);
	$remembered = $LU->readRememberCookie();
	$LU->login($remembered['handle'], $remembered['passwd'], true);
	if ($LU->isLoggedIn()) return true;
}


?>
