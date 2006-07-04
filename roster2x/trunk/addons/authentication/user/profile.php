<table width="100%"  border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td>
			<form method="post">
			<table width="100%"  border="0" cellspacing="1" cellpadding="1" style="border:1px solid #212121; font-weight:bold;">
				<tr>
					<td colspan="2" class="sc_menuTH">Change Details </td>
				</tr>
				<tr>
					<td class="sc_row1">Username</td>
					<td class="sc_row1"><?php print stripslashes(base64_decode($user['handle'])); ?></td>
				</tr>
				<tr>
					<td class="sc_row2">New Password </td>
					<td class="sc_row2"><input name="new_password" type="password" id="new_password"></td>
				</tr>
				<tr>
					<td class="sc_row1">Confirm New Password </td>
					<td class="sc_row1"><input name="conf_new_password" type="password" id="conf_new_password"></td>
				</tr>
				<tr>
					<td class="sc_row2">E-Mail address</td>
					<td class="sc_row2">
						<input name="orig_email" value="<?php print $user['email']; ?>" type="hidden">
						<input name="email" type="text" id="e_mail" value="<?php print $user['email']; ?>" style="width:200px;">
					</td>
				</tr>
				<?php if($LU->getProperty('perm_type') >= LIVEUSER_AREAADMIN_TYPE_ID && $LU->getProperty('auth_user_id') != $user['auth_user_id']) { ?>
				<tr>
					<td class="sc_row1">User Rights Type</td>
					<td class="sc_row1">
						<select name="right_level" onchange="this.form.submit()">
							<option>
								<?php 
								switch ($user['perm_type']) { 
									case 1: 
										print 'Member';
										break; 
									case 2:
										print 'Officer'; 
										break; 
									case 3: 
										print 'Guild Master';
										break; 
									case 4: 
										print 'Super Admin';
										break; 
								}
								?>
							</option>
							<?php if($LU->getProperty('perm_type') >= LIVEUSER_SUPERADMIN_TYPE_ID) { ?>
							<option value="<?php print LIVEUSER_SUPERADMIN_TYPE_ID; ?>">Super Admin</option>
							<?php } ?>
							<option value="<?php print LIVEUSER_AREAADMIN_TYPE_ID; ?>">Guild Master</option>
							<option value="<?php print LIVEUSER_ADMIN_TYPE_ID; ?>">Officer</option>
							<option value="<?php print LIVEUSER_USER_TYPE_ID; ?>">Member</option>
						</select>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="2" align="center">
						<input name="object" value="users" type="hidden">
						<input name="action" value="change_details" type="hidden">
						<input name="auth_user_id" value="<?php print $user['auth_user_id']; ?>" type="hidden">
						<input name="new_password_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'" style="width:100px;">
					</td>
				</tr>
        	</table>
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%"  border="0" cellspacing="1" cellpadding="1" style="border:1px solid #212121; font-weight:bold;">
				<tr>
					<td colspan="4" class="sc_menuTH">These characters are grouped with your account </td>
					</tr>
				<tr>
					<td class="sc_menuTH">Name</td>
					<td class="sc_menuTH">Guild</td>
					<td class="sc_menuTH">Rank</td>
					<td class="sc_menuTH">Options</td>
				</tr>
				<?php 
				$i = 1;
				for($j=0; $j<$characters['num_rows']; $j++)
				{
					if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
					else { $line_color = 'sc_row2'; $i = 1; }
					$line = '
				<form method="post">
				<tr class="'.$line_color.'">
					<td width="25%"><a href="'.@ROSTER_BASE.'index.php?p=char&member='.$characters['data'][$j]['member_id'].'" class="sc_titletext" style="font-size:8pt;">'.utf8_decode($characters['data'][$j]['name']).'</a></td>
					<td width="30%"><a href="'.@ROSTER_BASE.'index.php?p=members&guild='.$characters['data'][$j]['guild_id'].'" class="sc_titletext" style="font-size:8pt;">'.$characters['data'][$j]['guild_name'].'</a></td>
					<td width="20%">'.$characters['data'][$j]['guild_title'].'</td>
					<td>
						
						<select name="char_status" onchange="this.form.submit()">
							<option>'.ucfirst($characters['data'][$j]['status']).'</option>';
							if($characters['data'][$j]['status'] == 'main'){ $line .= '
							<option value="inactive">Inactive</option>
							<option value="alt">Alt</option>'; }
							else { $line .= '
							<option value="main">Main</option>'; }
							if($LU->getProperty('perm_type') >= LIVEUSER_SUPERADMIN_TYPE_ID)	{ 
							$line .= '<option value="delete">Ungroup</option>';	}
							$line .= '
						</select>
						<input name="object" value="users" type="hidden">
						<input name="action" value="change_char_status" type="hidden">
						<input name="perm_user_id" value="'.$user['perm_user_id'].'" type="hidden">
						<input name="auth_user_id" value="'.$user['auth_user_id'].'" type="hidden">
						<input name="target_member_id" value="'.$characters['data'][$j]['member_id'].'" type="hidden">
					</td>
				</tr>
				</form>';
				print $line;
				}
				?>
        	</table>
		</td>
	</tr>
	<?php if($LU->getProperty('perm_type') >= LIVEUSER_SUPERADMIN_TYPE_ID)	{ ?>
	<tr>
		<td>
			<form method="post">
			<table width="100%"  border="0" cellspacing="0" cellpadding="1" style="border:1px solid #212121; font-weight:bold;">
				<tr>
					<td class="sc_row1">
						Add this character to the list: 
					</td>
					<td class="sc_row1">
						<input name="char_name" type="text">
					</td>
					<td class="sc_row1">
						<input name="object" value="users" type="hidden">
						<input name="action" value="add_char_link" type="hidden">
						<input name="auth_user_id" value="<?php print $user['auth_user_id']; ?>" type="hidden">
						<input name="add_char_link_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'" style="width:100px;">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
	<? } ?>
</table>
