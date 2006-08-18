<?php
#-----------| User Management |-----------#	
class User_Management_Master extends Interface_Helper
{
	function users($mode=NULL)
	{
		global $LU;		
		if($mode == 'search_box')
		{
			print '	<table border="0" cellpadding="1" cellspacing="1">
						<tr>
							<td>
								<input name="search_for" type="text" style="width:140px;">
							</td>
						</tr>
						<tr>
							<td class="sc_titletext">
								<select name="search_object" style="width:140px;">
									<option>Select</option>
									<option value="handle">Username</option>
									<option value="character_name">Character Name</option>
									<option value="application_define_name">Guild Name</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="center">
								<input name="object" value="users" type="hidden">
								<input name="action" value="search" type="hidden">
								<input name="search_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'" style="width:100px;">
							</td>
						</tr>
					</table>';
		}
		elseif($mode == 'new_user_box')
		{
			print '	<table border="0" cellpadding="1" cellspacing="1">
						<tr>
							<td>
								<input name="new_username" type="text" style="width:140px;" value="Username">
							</td>
						</tr>
						<tr>
							<td>
								<input name="password" type="password" style="width:140px;" value="Password">
							</td>
						</tr>
						<tr>
							<td>
								<input name="email" type="text" style="width:140px;" value="Email address">
							</td>
						</tr>
						<tr>
							<td class="sc_titletext">
								<select name="right_level" style="width:140px;">
									<option>Select</option>
									<option value="'.LIVEUSER_SUPERADMIN_TYPE_ID.'">Super Admin</option>
									<option value="'.LIVEUSER_AREAADMIN_TYPE_ID.'">Guild Master</option>
									<option value="'.LIVEUSER_USER_TYPE_ID.'">Member</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="center">
								<input name="object" value="users" type="hidden">
								<input name="action" value="create" type="hidden">
								<input name="create_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'" style="width:100px;">
							</td>
						</tr>
						';
						if(!empty($_SESSION['result']))
						{
							print '<tr>
									<td class="sc_titletext">
										'.$_SESSION['result'].'
									</td>
								   </tr>';
							unset($_SESSION['result']);
						}
			print '	</table>';
		}
		elseif($mode['action'] == 'show_profile' && !empty($mode['id']))
		{
			// Display the profile of a user
			include '../user/profile.php';
			
		}
		elseif(!empty($mode['action']) && empty($mode['id']))
		{
			// this happens when a search returns nothing
			$search_message = 'No search results';
			print '<table border="0" cellpadding="1" cellspacing="1" width="500px">
					<tr class="sc_menuTH">
						<td>
							'.$search_message.'
						</td>
					</tr>
				   </table>';
			return;
		}
		elseif(empty($mode['action']))
		{ 
			// Display the list of all users or the results of a search of users by guild name
			$filter = array('container' => 'perm');
			$users = $this->get_user($filter); 
			if(!empty($_SESSION['search_users_result'])) 
			{
				$users = $_SESSION['search_users_result'];
				unset($_SESSION['search_users_result']);
			}
			elseif(isset($_SESSION['search_users_result']))
			{
				// this happens when a search by guild name returned nothing
				$search_message = 'No search results';
				unset($_SESSION['search_users_result']);
				print '<table border="0" cellpadding="1" cellspacing="1" width="500px">
						<tr class="sc_menuTH">
							<td>
								'.$search_message.'
							</td>
						</tr>
					   </table>';
				return;
			}
			foreach($users as $key => $row)
			{
				$usernames[$key] = stripslashes(base64_decode($row['handle']));
				$active_flags[$key] = $row['is_active'];
			}
			if(!isset($_GET['sort']) || $_GET['sort'] == 'username'){
				array_multisort($usernames, SORT_ASC, SORT_STRING, $users);
			}
			elseif($_GET['sort'] == 'active')
			{
				array_multisort($active_flags, SORT_DESC, SORT_NUMERIC, $users);
			}
			$line = '<table border="0" cellpadding="1" cellspacing="1" width="500px">
						<tr class="sc_menuTH">
							<td width="30%">
								<a class="sc_titletext" href="?sort=username" style="font-size:11px"> Username </a>
							</td>
							<td  width="40%">
								E-Mail Address
							</td>
							<td align="center">
								<a class="sc_titletext" href="?sort=active" style="font-size:11px"> Active </a>
							</td>
							<td colspan="2" width="25%">
								Options
							</td>
						</tr>';
			$i = 1;
			foreach($users as $user)
			{
				if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
				else { $line_color = 'sc_row2'; $i = 1; }
				$line .= '<tr class="'.$line_color.'">
							<td>
								'.stripslashes(base64_decode($user['handle'])).'
							</td>
							<td>
								'.$user['email'].'
							</td>
							<td align="center">
								'.$user['is_active'].'
							</td>
							<a href="index.php?action=show_profile&object=users&id='.$user['perm_user_id'].'">
								<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
									Profile 
								</td>
							</a>';
							// You cant delete yourself, you cant delete the master admin and you have to be at least a super admin to do it
							if($LU->getProperty('perm_user_id') != $user['perm_user_id'] && $user['perm_type'] != LIVEUSER_MASTERADMIN_TYPE_ID && $LU->getProperty('perm_type') >= LIVEUSER_SUPERADMIN_TYPE_ID) {
				$line .=	'<a href="index.php?action=delete&object=users&id='.$user['perm_user_id'].'">
								<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
									Remove 
								</td>
							</a>';
							}
				$line .= '</tr>';	
			}
			$line .= '</table>';
			print $line;
		}
	}
}
?>