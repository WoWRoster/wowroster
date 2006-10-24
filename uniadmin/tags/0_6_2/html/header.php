<?php
$config['header'] =  "
		<center>
			<table class='uuTABLE'>
				<tr>
					<th>Menu</th>
				</tr>
				<tr>
					<td><center><a href='help.php'>Help</a></center></td>
				</tr>
				<tr>
					<td><center><a href='addons.php'>Addon Management</a></center></td>
				</tr>
				<tr>
					<td><center><a href='logo.php'>Logo Management</a></center></td>
				</tr>
				<tr>
					<td><center><a href='settings.php'>Settings Management</a></center></td>
				</tr>
				<tr>
					<td><center><a href='stats.php'>Statistics</a></center></td>
				</tr>
				<tr>
					<td><center><a href='users.php'>Users</a></center></td>
				</tr>
			</table>
			<b>
				Synchronization URL (click to verify):
				<a href='".$config['IntLocation']."' target='_BLANK'>
					<br>
					<font color='red'>
						".$config['IntLocation']."
					</font>
				</a>
			</b>
		</center>";
?>