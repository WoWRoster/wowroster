<?php
	$filter = array('container' => 'perm',
					'filters' => array('perm_user_id' => $mode['id']));
	$user = $this->get_user($filter);
	$user = $user[0];
	$characters = $this->get_characters($user['auth_user_id']);
	$filter = array('fields' => array('group_id', 'group_define_name', 'description'),
					'filters' => array('language_id' => 'enUS', 'perm_user_id' => $user['perm_user_id']),
					'orders' => array('group_define_name' => 'ASC'));
	$groups = $this->get_group($filter);
	if($LU->getProperty('perm_type') >= LIVEUSER_AREAADMIN_TYPE_ID)
	{
		$filter = array('filters' => array('perm_user_id' => $LU->getProperty('perm_user_id')),
						'fields' => array('application_define_name'));
		$guild_master = $this->get_area($filter);
		//print_r($guild_master);
	}
?>
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
				<?php // Master/Super admins can change people's right types except their own, with the limitation of only the Master Admin upgrading people to Super Admins
				if($LU->getProperty('perm_type') >= LIVEUSER_SUPERADMIN_TYPE_ID && $LU->getProperty('auth_user_id') != $user['auth_user_id']) { ?>
				<tr>
					<td class="sc_row1">User Rights Type</td>
					<td class="sc_row1">
						<input name="orig_right_level" value="<?php print $user['perm_type']; ?>" type="hidden">
						<select name="right_level" onchange="this.form.submit()">
							<option>
								<?php 
								switch ($user['perm_type']) { 
									case 1: 
										print 'Member';
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
							<?php if($LU->getProperty('perm_type') == LIVEUSER_MASTERADMIN_TYPE_ID) { ?>
							<option value="<?php print LIVEUSER_SUPERADMIN_TYPE_ID; ?>">Super Admin</option>
							<?php } ?>
							<option value="<?php print LIVEUSER_AREAADMIN_TYPE_ID; ?>">Guild Master</option>
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
			<form method="post">
			<table width="100%"  border="0" cellspacing="1" cellpadding="1" style="border:1px solid #212121; font-weight:bold;">
				<tr>
					<td colspan="4" class="sc_menuTH">You belong to the following groups:</td>
				</tr>
				<tr class="sc_menuTH">
					<td> Guild </td>
					<td width="25%"> Group </td>
					<td width="25%"> Description </td>
					<td width="15%"> Option </td>
				</tr>
				<?php 
				if(is_array($groups)) {
					foreach ($groups as $key => $row) {
						$decoded = stripslashes(base64_decode($row['group_define_name']));
						$filter = array('fields' => array('application_define_name'), 
										'filters' => array('application_id' => substr($decoded, 0, strpos($decoded, '_'))),
										'orders' => array('application_define_name' => 'ASC'));
						$guild[$key] = $this->get_guild($filter);
						$guild[$key] = $guild[$key][0];
						$group_name[$key]  = $row['group_define_name'];
						$group_description[$key]  = $row['description'];
					}
					$i = 1;
					$line = NULL;
					for($j=0; $j<count($groups); $j++)
					{
						if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
						else { $line_color = 'sc_row2'; $i = 1; }
						$line .= '<tr class="'.$line_color.'">
								 	<td valign="middle">'.stripslashes(base64_decode($guild[$j]['application_define_name'])).'</td>
									<td valign="middle">'.ltrim(stristr(stripslashes(base64_decode($groups[$j]['group_define_name'])), '_'), '_').'</td>
						 			<td valign="middle">'.stripslashes(base64_decode($groups[$j]['description'])).'</td>';
						 // Guild Masters and up can remove a user from his group
						if(!empty($guild_master) && in_array($guild[$j]['application_define_name'], $guild_master) || $LU->getProperty('perm_type') > LIVEUSER_AREAADMIN_TYPE_ID)
						{
							$line .= 	'<a href="index.php?action=remove_from_group&object=users&id='.$user['perm_user_id'].'&group_id='.$groups[$j]['group_id'].'&url='.base64_encode($_SERVER['QUERY_STRING']).'">
											<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
												Remove 
											</td>
										</a>';
						}
						$line .= '</tr>';
					}
					print $line;
				}
				if($LU->getProperty('perm_type') >= LIVEUSER_AREAADMIN_TYPE_ID)	{ ?>
				<tr>
					<td colspan="4">
						<table width="100%"  border="0" cellspacing="0" cellpadding="1" style="border:1px solid #212121; font-weight:bold;">
							<tr>
								<td class="sc_row1">
									Add <?php print stripslashes(base64_decode($user['handle'])); ?> to this group: 
								</td>
								<td class="sc_row1">
									<select name="group_id">
										<?php
										$filter = array('fields' => array('group_id', 'group_define_name', 'description'),
														'filters' => array('language_id' => 'enUS'),
														'orders' => array('group_define_name' => 'ASC'));
										$groups = $this->get_group($filter);
										if(is_array($groups)) { 
											foreach ($groups as $key => $row) {
												$decoded = stripslashes(base64_decode($row['group_define_name']));
												$filter = array('fields' => array('application_define_name'), 
																'filters' => array('application_id' => substr($decoded, 0, strpos($decoded, '_'))),
																'orders' => array('application_define_name' => 'ASC'));
												$guild[$key] = $this->get_guild($filter);
												$guild[$key] = $guild[$key][0];
											}
											$i = 1;
											$line = NULL;
											$previous_guild = NULL;
											for($j=0; $j<count($guild); $j++)
											{
												$decoded_guild = stripslashes(base64_decode($guild[$j]['application_define_name']));
												if($decoded_guild != $previous_guild || empty($line))
												{
													if(!empty($line)) 
													{ // Close the previous optgroup before opening a new one
														$line .= '</optgroup>';
													}
													else
													{ // This is the first element of the dropdown box
														$line .= '<option>Select</option>';
													}
													// Make a new optgroup for each guild and list the first group
													$line .= '<optgroup label="'.$decoded_guild.'">';
													$line .= '<option value="'.$groups[$j]['group_id'].'">'.ltrim(stristr(stripslashes(base64_decode($groups[$j]['group_define_name'])), '_'), '_').'</option>';
												}
												else
												{	// List all groups from the second one on under the current guild because none of the above conditions apply
													$line .= 	'<option value="'.$groups[$j]['group_id'].'">'.ltrim(stristr(stripslashes(base64_decode($groups[$j]['group_define_name'])), '_'), '_').'</option>';
												}
												$previous_guild = $decoded_guild;
											}
											print $line;
										}
										?>
									</select>
								</td>
								<td class="sc_row1">
									<input name="object" value="users" type="hidden">
									<input name="action" value="add_user_to_group" type="hidden">
									<input name="user_id" value="<?php print $user['perm_user_id']; ?>" type="hidden">
									<input name="add_user_to_group_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'" style="width:100px;">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<? } ?>
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
							<option value="alt">Alt</option>
							<option value="inactive">Inactive</option>'; }
							else { $line .= '
							<option value="main">Main</option>
							<option value="inactive">Inactive</option>'; }
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
				if($LU->getProperty('perm_type') >= LIVEUSER_AREAADMIN_TYPE_ID)	{  ?>
				<tr>
					<td colspan="4">
						<table width="100%"  border="0" cellspacing="0" cellpadding="1" style="border:1px solid #212121; font-weight:bold;">
							<form method="post">
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
						</form>
						</table>
					</td>
				</tr>
				<? } ?>
        	</table>
		</td>
	</tr>
</table>
