<?php 
// All the parts where logic cant be separated from presentation are here, to keep the frontend as clear from code as possible
class Interface_Helper extends Authentication
{

#-----------| Guild Management |-----------#

	function list_guilds()
	{
		print '<table border="0" cellpadding="1" cellspacing="1" width="100%">';
		print '<tr class="sc_menuTH"><td> Guild </td><td colspan="2"> Options </td></tr>';
		$guilds = $this->get_guild();
		sort($guilds);
		$i = 1;
		$line = NULL;
		for($j=0; $j<count($guilds); $j++)
		{
			if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
			else { $line_color = 'sc_row2'; $i = 1; }
			$line .= '<tr class="'.$line_color.'">';
			$line .= '<td width="60%" valign="middle">'.stripslashes(base64_decode($guilds[$j]['application_define_name'])).'</td>';
			$line .= '<a href="index.php?action=change&object=guild&id='.$guilds[$j]['application_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Change 
						</td></a>';
			$line .= '<a href="index.php?action=delete&object=guild&id='.$guilds[$j]['application_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Remove 
						</td></a>';			
			$line .= '</tr>';
		}
		print $line;
		print '</table>';
		print '<br><br>';
	}
	
	function new_edit_guild_field($get=NULL)
	{	
		if(!empty($get)&&@$get['action']=='change' && @$get['object']=='guild') 
		{
			$filter = array('filters' => array('application_id' => $get['id']));
			$guild = $this->get_guild($filter);
			$guild = $guild[0];
		}
		print '<form method="post">
					<table border="0" cellpadding="1" cellspacing="1" width="100%">
						<tr class="sc_menuTH">
							<td colspan="2">
								 New / Edit Guild Name 
							</td>
						</tr>
						<tr>
							<td width="60%">';
								if(!empty($guild))
								{ print '
									<input name="guild_input" type="text" value="'.stripslashes(base64_decode($guild['application_define_name'])).'" size="30" style="cursor:text;">
									<input name="original" value="'.stripslashes(base64_decode($guild['application_define_name'])).'" type="hidden">
								'; } 
								else 
								{ print '
									<input name="guild_input" type="text" size="30">
								'; } 
								print '
							</td>
							<td>
								<input name="object" value="guild" type="hidden">
								<input name="action" value="new_edit" type="hidden">
								<input name="guild_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
							</td>
						</tr>
					</table>
				</form>';
	}
	
#-----------| Area Management |-----------#

	function list_areas()
	{
		print '<table border="0" cellpadding="1" cellspacing="1" width="100%">';
		print '<tr class="sc_menuTH"><td width="28%"> <a class="sc_titletext" href="?sort=area" style="font-size:11px">Area</a> </td><td> <a class="sc_titletext" href="?sort=guild" style="font-size:11px">Guild</a> </td><td colspan="2"> Options </td></tr>';
		$areas = $this->get_area();
		foreach ($areas as $key => $row) {
		   $area_id[$key]  = $row['area_id'];
		   $guild_id[$key]  = $row['application_id'];
		   $area_name[$key]  = $row['area_define_name'];
		}
		if(!isset($_GET['sort'])||$_GET['sort']=='guild'){
			array_multisort($guild_id, SORT_ASC, SORT_NUMERIC, $area_name, SORT_DESC, SORT_STRING, $areas);
		}
		else
		{
			array_multisort($area_name, SORT_ASC, SORT_STRING, $guild_id, SORT_DESC, SORT_NUMERIC, $areas);
		}

		$i = 1;
		$line = NULL;
		for($j=0; $j<count($areas); $j++)
		{
			$filter = array('filters' => array('application_id' => $areas[$j]['application_id']));
			$guild = $this->get_guild($filter);
			if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
			else { $line_color = 'sc_row2'; $i = 1; }
			$line .= '<tr class="'.$line_color.'">';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($areas[$j]['area_define_name'])).'</td>';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($guild[0]['application_define_name'])).'</td>';
			$line .= '<a href="index.php?action=change&object=area&id='.$areas[$j]['area_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Change </td></a>';
			$line .= '<a href="index.php?action=delete&object=area&id='.$areas[$j]['area_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Remove 
						</td></a>';			
			$line .= '</tr>';
		}
		print $line;
		print '</table>';
		print '<br><br>';
	}
	
	function new_edit_area_field($get=NULL)
	{	
		if(!empty($get)&&@$get['action']=='change'&& @$get['object']=='area') 
		{
			$filter = array('area_id' => $get['id']);
			$area = $this->get_area($filter);
			$area = $area[0];
			$filter = array('filters' => array('application_id' => $area['application_id']));
			$guild = $this->get_guild($filter);
			$guild = $guild[0];
		}
		$all_guilds = $this->get_guild();
		print '<form method="post">
					<table border="0" cellpadding="1" cellspacing="1" width="100%">
						<tr class="sc_menuTH">
							<td>
								 New / Edit Area Name 
							</td>
							<td colspan="2">
								 Select Guild 
							</td>
						</tr>
						<tr>
							<td width="60%">';
								if(!empty($area))
								{ print '
									<input name="area_input" type="text" value="'.stripslashes(base64_decode($area['area_define_name'])).'" style="cursor:text;">
									<input name="original" value="'.$area['area_id'].'" type="hidden">
									<input name="guild_id" value="'.$area['application_id'].'" type="hidden">
								'; } 
								else 
								{ print '
									<input name="area_input" type="text" >
								'; } 
								print '
							</td>
							<td>';
								if(@$get['action']!=='change' && @$get['object']!=='area')
								{ 
									print '<select name="guild_dropdown">';
										for($i=0; $i<count($all_guilds); $i++)
										{
											print '<option value="'.$all_guilds[$i]['application_id'].'">'.stripslashes(base64_decode($all_guilds[$i]['application_define_name'])).'</option>';
										}
									print '</select>';
								}
								print '
							</td>
							<td>
								<input name="object" value="area" type="hidden">
								<input name="action" value="new_edit" type="hidden">
								<input name="area_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
							</td>
						</tr>
					</table>
				</form>';
	}
	
	
#-----------| Group Management |-----------#	
	
	function list_groups()
	{
		print '<table border="0" cellpadding="1" cellspacing="1" width="530px">';
		print '<tr class="sc_menuTH"><td><a class="sc_titletext" href="?sort=guild" style="font-size:11px"> Guild </a></td><td width="25%"><a class="sc_titletext" href="?sort=name" style="font-size:11px"> Group </a></td><td width="25%"><a class="sc_titletext" href="?sort=description" style="font-size:11px"> Description </a></td><td width="25%" colspan="2"> Options </td></tr>';
		$filter = array('fields' => 
							array('group_id', 'group_define_name', 'description'),
						'filters' => 
							array('language_id' => 'enUS'));
		$groups = $this->get_group($filter);
		foreach ($groups as $key => $row) {
			$decoded = stripslashes(base64_decode($row['group_define_name']));
			$filter = array('fields' => array('application_define_name'), 
							'filters' => array('application_id' => substr($decoded, 0, strpos($decoded, '_'))));
			$guild[$key] = $this->get_guild($filter);
			$guild[$key] = $guild[$key][0];
			$group_name[$key]  = $row['group_define_name'];
			$group_description[$key]  = $row['description'];
		}
		if(!isset($_GET['sort'])||$_GET['sort']=='name'){
			array_multisort($group_name, SORT_ASC, SORT_STRING, $guild, SORT_ASC, SORT_STRING, $group_description, SORT_DESC, SORT_STRING, $groups);
		}
		elseif(@$_GET['sort']=='guild')
		{
			array_multisort($guild, SORT_DESC, SORT_STRING, $groups);
		}
		else
		{
			array_multisort($group_description, SORT_ASC, SORT_STRING, $group_name, SORT_DESC, SORT_NUMERIC, $guild, SORT_ASC, SORT_STRING, $groups);
		}
		$i = 1;
		$line = NULL;
		for($j=0; $j<count($groups); $j++)
		{
			if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
			else { $line_color = 'sc_row2'; $i = 1; }
			$line .= '<tr class="'.$line_color.'">';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($guild[$j]['application_define_name'])).'</td>';
			$line .= '<td valign="middle">'.ltrim(stristr(stripslashes(base64_decode($groups[$j]['group_define_name'])), '_'), '_').'</td>';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($groups[$j]['description'])).'</td>';
			$line .= '<a href="index.php?action=change&object=group&id='.$groups[$j]['group_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Change </td></a>';
			$line .= '<a href="index.php?action=delete&object=group&id='.$groups[$j]['group_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Remove 
						</td></a>';			
			$line .= '</tr>';
		}
		print $line;
		print '</table>';
		print '<br><br>';
	}
	
	function new_edit_group_field($get=NULL)
	{	
		if(!empty($get)&&@$get['action']=='change'&& @$get['object']=='group') 
		{
			$filter = array('fields' => 
								array('group_id', 'group_define_name', 'description'), 
							'filters' => 
								array('group_id' => @$get['id'], 'language_id' => 'enUS'));
			$group = $this->get_group($filter);
			$group = $group[0];
		}
		$all_guilds = $this->get_guild();
		print '<form method="post">
					<table border="0" cellpadding="1" cellspacing="1" width="530px">
						<tr class="sc_menuTH">
							<td>
								 New / Edit Group Name 
							</td>
							<td>
								 Description
							</td>
							<td colspan="2">
								 Select Guild 
							</td>
						</tr>
						<tr>
							<td>';
								if(!empty($group))
								{ 
								$decoded = stripslashes(base64_decode($group['group_define_name']));
								$guild_id = substr($decoded, 0, strpos($decoded, '_'));
								print '
									<input name="group_input" type="text" value="'.ltrim(stristr($decoded, '_'), '_').'" style="cursor:text;">
									<input name="original_grp" value="'.$group['group_id'].'" type="hidden">
									<input name="original_grp_name" value="'.$group['group_define_name'].'" type="hidden">
									<input name="guild_id" value="'.$guild_id.'" type="hidden">
							</td>
							<td>
									<input name="description" type="text" value="'.stripslashes(base64_decode($group['description'])).'" style="cursor:text;">
									<input name="original_desc" value="'.$group['description'].'" type="hidden">
							</td>
								'; } 
								else 
								{ print '
									<input name="group_input" type="text" >
							</td>
							<td>
									<input name="description" type="text">
							</td>
								'; } 
								print '
							<td>';
								if(@$get['action']!=='change' && @$get['object']!=='group')
								{ 
									print '<select name="guild_dropdown">';
										for($i=0; $i<count($all_guilds); $i++)
										{
											print '<option value="'.$all_guilds[$i]['application_id'].'">'.stripslashes(base64_decode($all_guilds[$i]['application_define_name'])).'</option>';
										}
									print '</select>';
								}
								print '
							</td>
							<td>
								<input name="object" value="group" type="hidden">
								<input name="action" value="new_edit" type="hidden">
								<input name="group_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
							</td>
						</tr>
					</table>
				</form>';
	}
		

#-----------| User Management |-----------#	

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
									<option value="'.LIVEUSER_ADMIN_TYPE_ID.'">Officer</option>
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
			$filter = array('container' => 'perm',
							'filters' => array('perm_user_id' => $mode['id']));
			$user = $this->get_user($filter);
			$user = $user[0];
			$characters = $this->get_characters($user['auth_user_id']);
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
							// You cant delete yourself, you cant delete the master admin
							if($LU->getProperty('perm_user_id') != $user['perm_user_id'] && $user['perm_type'] != LIVEUSER_MASTERADMIN_TYPE_ID) {
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

	
#-----------| General Functions |-----------#	

	function treat_get_post($request=array())
	{
		global $LU;
		// Get the current users credentials, they are needed for authorisation.
		if($LU->isLoggedIn()){
			// Check if the user is a master or super admin
			if($LU->getProperty('perm_type') >= LIVEUSER_SUPERADMIN_TYPE_ID)
			{
				if(!empty($request['object'])){
					switch ($request['object']) {
						#-----| Guild Management |-----#
						case 'guild':
						{
							if($request['action'] == 'delete')
							{	
								$this->delete_guild(array('application_id' => intval($request['id'])));
								header("Location: ".$_SERVER['PHP_SELF']);
								exit;
							}
							elseif($request['action'] == 'new_edit')
							{
								$existing_guilds = $this->get_guild();
								// check for guild name, if its a changed/new one this will pass
								if(!$this->deep_in_array(base64_encode($request['guild_input']), $existing_guilds))
								{	// check again, if its a changed one, this should only pass if its a totally new name, not a change of name
									if(!@$this->deep_in_array(base64_encode($request['original']), $existing_guilds))
									{
										$this->add_guild(base64_encode($request['guild_input']));
									}
									else
									{	// It's certain that it's an existing one to be changed, so go ahead
										$data = array('application_define_name' => base64_encode($request['guild_input']));
										$filters = array('application_define_name' => base64_encode($request['original']));
										$this->change_guild($data, $filters);
									}
								}
							}
							break;
						}
						#-----| Area Management |-----#
						case 'area':
						{
							if($request['action'] == 'delete')
							{	
								$this->delete_area(array('area_id' => intval($request['id'])));
								header("Location: ".$_SERVER['PHP_SELF']);
								exit;
							}
							elseif($request['action'] == 'new_edit')
							{
								$existing_areas = $this->get_area();
								// check for area id, if its a new one this will pass
								if(!$this->deep_in_array(@$request['original'], $existing_areas))
								{	// check again, if its a changed one, this should only pass if its a totally new name, not a change of name
									$existing_area = $this->get_area(array('application_id' => $request['guild_dropdown'], 'area_define_name' => base64_encode($request['area_input'])));
									if(empty($existing_area))
									{
										$this->add_area(intval($request['guild_dropdown']), base64_encode($request['area_input']));
									}
								}
								else
								{
									// It's certain that it's an existing one to be changed, so go ahead
									$data = array('area_define_name' => base64_encode($request['area_input']),
												  'application_id' => $request['guild_id']);
									$filter = array('area_id' => $request['original']);
									$this->change_area($data, $filter);
									header("Location: ".$_SERVER['PHP_SELF']);
									exit;
								}
							}
							break;
						}
						#-----| Group Management |-----#
						case 'group':
						{
							if($request['action'] == 'delete')
							{	
								// Delete all translations for this group, then delete the group and return to the listing
								$this->delete_translation(array('section_id' => intval($request['id'])));
								$this->delete_group(array('group_id' => intval($request['id'])));
								header("Location: ".$_SERVER['PHP_SELF']);
								exit;
							}
							elseif($request['action'] == 'new_edit')
							{
								// if there isnt an existing group, then its a new one, check if all the fields have been filled in
								if(empty($request['original_grp']) && !empty($request['description']) && !empty($request['group_input']))
								{	
									// Add a new group
									$this->add_group(base64_encode($request['guild_dropdown'].'_'.$request['group_input']));
									// Get the group_id and name of the newly created group (called by name)
									$filters = array('fields' => 
														array('group_id', 'group_define_name'),
													 'filters' => 
														array('group_define_name' => base64_encode($request['guild_dropdown'].'_'.$request['group_input'])));
									$new_group_id = $this->get_group($filters);
									// Add a description, assigned to the newly created group, with the user's language code
									$data = array('section_id' => $new_group_id[0]['group_id'],
												  'section_type' => LIVEUSER_SECTION_GROUP,
												  'language_id' => 'enUS',
												  'name' => $new_group_id[0]['group_define_name'],
												  'description' => base64_encode($request['description']));
									$this->add_translation($data);
								}
								else
								{
									// It's certain that it's an existing one to be changed, so go ahead
									// if the group name was changed, save the change
									if($request['original_grp_name'] !== base64_encode($request['group_input']))
									{
										$data = array('group_define_name' => base64_encode($request['guild_id'].'_'.$request['group_input']));
										$filters = array('group_define_name' => $request['original_grp_name']);
										$this->change_group($data, $filters);
									}
									// if the description was changed, save the change
									if($request['original_desc'] !== base64_encode($request['description']))
									{
										$data = array('description' => base64_encode($request['description']));
										$filter = array('description' => $request['original_desc']);
										$this->change_translation($data, $filter);
									}
									header("Location: ".$_SERVER['PHP_SELF']);
									exit;
								}
							}
							break;
						}
						#-----| User Management |-----#
						case 'users':
						{
							// Search user box functionality
							if($request['action'] == 'search')
							{
								// Search by username
								if($request['search_object'] == 'handle')
								{
									$filter = array('container' => 'auth',
													'filters' => array('handle' => base64_encode(ucwords($request['search_for']))),
													'fields' => array('auth_user_id'));
									$users = $this->get_user($filter); 
									header('Location: '.$_SERVER['PHP_SELF'].'?action=show_profile&object=user&id='.$users[0]['perm_user_id']);
									exit;
								}
								elseif($request['search_object'] == 'character_name')
								{
									// Search a user by one of his char's name
									$user_id = $this->get_user_by_character(utf8_encode(ucwords($request['search_for'])));
									$filter = array('container' => 'auth',
													'filters' => array('auth_user_id' => $user_id['auth_user_id']),
													'fields' => array('auth_user_id'));
									$users = $this->get_user($filter); 
									header('Location: '.$_SERVER['PHP_SELF'].'?action=show_profile&object=user&id='.$users[0]['perm_user_id']);
									exit;
								}
								elseif($request['search_object'] == 'application_define_name')
								{ 
									// List all users from the entered guild name
									// Do that by:
									// drilling down:
									// $entered_value->guild_id
									//		guild id->all areas
									// 			all areas->all rights associated to that area
									//				rights->associated groups
									//					$groups->associated users
									// then passing the resulting 'associated users' sets up into the superset $users
									// then flatten that deep array by re-keying it
									// then make it global for later use (display) by assigning it to a session variable (global)
									$filter = array('filters' => array('application_define_name' => base64_encode($request['search_for'])));
									$guild = $this->get_guild($filter);
		
									$filter = array('application_id' => $guild[0]['application_id']);
									$areas = $this->get_area($filter); 
									 
									for($i=0; $i<count($areas); $i++)
									{
										$filter = array('filters' => array('area_id' => $areas[$i]['area_id']));
										$rights = $this->get_right($filter); 
										
										for($j=0; $j<count($rights); $j++)
										{
											$filter = array('filters' => array('right_id' => $rights[$j]['right_id']));
											$groups = $this->get_group($filter);
											
											for($k=0; $k<count($groups); $k++)
											{
												$filter = array('container' => 'perm',
																'filters' => array('group_id' => $groups[$k]['group_id']));
												$users[$i] = $this->get_user($filter); 
											}
										}
									}
									$_SESSION['search_users_result'] = array();
									$i=0;
									if(!@is_array($users)) break;
									foreach($users as $first_key => $second_key)
									{
										if(!@is_array($second_key)) break;
										foreach($second_key as $user_record)
										{
											$_SESSION['search_users_result'][$i] = $user_record;
											$i++;
										}
									}
								}
							}
							elseif($request['action'] == 'change_details')
							{
								// If a password is entered and it absolutely matches the confirm password field then save it.
								// LiveUser takes care of md5'ing the new password
								if(!empty($request['new_password']) && $request['new_password'] === $request['conf_new_password'])
								{
									$filter = array('container' => 'perm',
													'filters' => array('auth_user_id' => $request['auth_user_id']));
									$user = $this->get_user($filter); 
									$new_data = array('passwd' => $request['new_password']);
									$this->change_user($user[0]['perm_user_id'], $new_data);
								}
								// If the email address has been changed, save it
								if(!empty($request['email']) && $request['email'] != $request['orig_email'])
								{
									$filter = array('container' => 'perm',
													'filters' => array('auth_user_id' => $request['auth_user_id']));
									$user = $this->get_user($filter); 
									$new_data = array('email' => addslashes($request['email']));
									$this->change_user($user[0]['perm_user_id'], $new_data);
								}
							}
							elseif($request['action'] == 'change_char_status')
							{
								// Change the status field in the user char link table or delete the linking is desired
								$data = array(	'auth_user_id' => $request['auth_user_id'],
												'member_id' => $request['target_member_id'],
												'status' => $request['char_status']);
								if($data['status'] != 'delete')
								{
									$this->change_char_status($data);
								}
								else
								{
									$this->delete_user_char_link($data);
								}
							}
							elseif($request['action'] == 'delete')
							{
								// Delete the user, this removes also entries in group and rights and char link tables
								$filter = array('container' => 'perm',
											   'filters' => array('perm_user_id' => $request['id']));
								$user = $this->get_user($filter); 
								// Prevent deletion of the master admin account
								if($user[0]['perm_type'] != LIVEUSER_MASTERADMIN_TYPE_ID)
								{
									$this->delete_user_char_link(array('auth_user_id' => $user[0]['auth_user_id']));
									$this->delete_user($request['id']);
								}
								header("Location: ".$_SERVER['PHP_SELF']);
								exit;
							}
							elseif($request['action'] == 'create')
							{
								// Create a new user and evaluate the success of the operation and give an appropriate answer
								$data = array(	'handle'    => base64_encode(ucwords($request['new_username'])),
												'passwd'    => $request['password'],
												'email'     => $request['email'],
												'is_active' => '1',
												'perm_type' => $request['right_level']	);
								if(!$this->add_user($data))
								{
									$filter = array('container' => 'auth',
													'filters' => array('handle' => base64_encode(ucwords($request['handle']))));
									$users = $this->get_user($filter);
									if(!empty($users[0]['perm_user_id']))
									{
										$_SESSION['result'] = 'Error: Username already exists.';
									}
									else
									{
										$_SESSION['result'] = 'Could not create user.';
									}
								}
								else
								{
									$_SESSION['result'] = 'User '.$request['new_username'].' created.';
								}
							}
							elseif($request['action'] == 'add_char_link')
							{
								// Manually link a character to a user
								$data = array('auth_user_id' => $request['auth_user_id'], 'char_name' => utf8_encode(ucwords($request['char_name'])));
								$this->add_user_char_link($data);
							}
							break;
						} // End of - case 'users'
					} // End of - switch $request['object']
				} // End of - !emtpy($request['object'])
			} // End of - Is master or super admin
			if(!empty($request['submit']) && $request['submit'] == 'Logout')
			{
				$LU->logout(true);
			}		
		}// End of - IsLoggedIn
	} // End of - function treat_get_post()
	
	// Recursive array search, returns true/false
	function deep_in_array($value, $array, $case_insensitive = false){
	   foreach($array as $item){
		   if(is_array($item)) $ret = $this->deep_in_array($value, $item, $case_insensitive);
		   else $ret = ($case_insensitive) ? strtolower($item)==$value : $item==$value;
		   if($ret)return $ret;
	   }
	   return false;
	}
	
}


?>