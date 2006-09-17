<?php
class Authentication
{

#-----------| User Management |-----------#
	
	/*	$user_type may be any of the following constants:
		LIVEUSER_ANONYMOUS_TYPE_ID : lowest user level: anonymous 
		LIVEUSER_USER_TYPE_ID : highest user level 
		LIVEUSER_ADMIN_TYPE_ID : lowest admin level 
		LIVEUSER_AREAADMIN_TYPE_ID : area admin level (lookup area admin) 
		LIVEUSER_SUPERADMIN_TYPE_ID : all rights granted 
		LIVEUSER_MASTERADMIN_TYPE_ID : highest admin level 
	*/
		
	/*
		$data = array(	'handle'    => $username,
						'passwd'    => $password,
						'email'     => $email,
						'is_active' => $is_active,
						'perm_type' => $user_type	);
		add_user($data);
	*/
	function add_user($data)
	{
		global $LUA;
		$userId = $LUA->addUser($data);
		$error_params = func_get_args();
		return $this->status($userId, 'add_user()', __LINE__, $error_params);
	}
	
	/*
		$new_data = array(	'handle' 	=> $username,
							'passwd' 	=> $password,
							'email' 	=> $email,
							'is_active'	=> $is_active,
							'perm_type'	=> $user_type	);
		change_user('1', $data); // Change something in user 1's record
	*/
	function change_user($user_id, $new_data)
	{
		global $LUA;
		$updatedUser = $LUA->updateUser($new_data, $user_id);
		$error_params = func_get_args();
		return $this->status($updatedUser, 'change_user()', __LINE__, $error_params);
	}
	
	/*
		delete_user('1'); // Delete user with the ID of 1
	*/
	function delete_user($user_id)
	{
		global $LUA;
		$removedUser = $LUA->removeUser($user_id);
		$error_params = func_get_args();
		return $this->status($removedUser, 'delete_user()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('container' => $name_or_rights, // Either 'auth' for user details or 'perm' for his rights
						'filters' => array(	'perm_user_id' => $user_id,  
											'handle' 	=> $username, 	
											'passwd' 	=> $password,
											'email'		=> $email,
											'is_active'	=> $is_active,
											'perm_type'	=> $user_type	);
		get_user($filter);					
	*/
	function get_user($filter=array())
	{
		global $LUA;
		if(empty($filter)) $users = $LUA->getUsers();
		else $users = $LUA->getUsers($filter);
		$error_params = func_get_args();
		return $this->status($users, 'get_user()', __LINE__, $error_params);
	}
	
	/*
		add_area_admin('1', '2'); // Add a new area admin
	*/
	function add_area_admin($area_id, $user_id)
	{
		global $LUA;
		$data = array('area_id' => $area_id,
					  'perm_user_id' => $user_id);
		$areaAdminId = $LUA->perm->addAreaAdmin($data);
		$error_params = func_get_args();
		return $this->status($areaAdminId, 'add_area_admin()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('area_id' => $area_id,
						'area_define_name' => $area_name);
		delete_area_admin($filter); // Delete specified area admin, a filter has to be passed
	*/
	function delete_area_admin($filter)
	{
		global $LUA;
		$removeAreaAdmin = $LUA->perm->removeAreaAdmin($filter);
		$error_params = func_get_args();
		return $this->status($removeAreaAdmin, 'delete_area_admin()', __LINE__, $error_params);
	}
	
	/*
		add_user_to_group('1', '1'); 
	*/
	function add_user_to_group($user_id, $group_id)
	{
		global $LUA;
		$data = array('perm_user_id' => $user_id,
					  'group_id' => $group_id);
		$added = $LUA->perm->addUserToGroup($data);
		$error_params = func_get_args();
		return $this->status($added, 'add_user_to_group()', __LINE__, $error_params);
	}
	
	/*
		remove_user_from_group('1', '1');
	*/
	function remove_user_from_group($user_id, $group_id)
	{
		global $LUA;
		$data = array('perm_user_id' => $user_id,
					  'group_id' => $group_id);
		$removed = $LUA->perm->removeUserFromGroup($data);
		$error_params = func_get_args();
		return $this->status($removed, 'remove_user_from_group()', __LINE__, $error_params);
	}
	
	/*
		$char_name = utf8_encode($char_name);
	*/
	function get_user_by_character($char_name)
	{
		global $LUA, $db;
		$query_string = "SELECT link.auth_user_id FROM (roster2_addon_auth_user_char_linktable AS link, roster2_members) WHERE (link.member_id = roster2_members.member_id) AND (roster2_members.name = '".$char_name."')";
		$res = &$db->query($query_string);
		$res->fetchInto($user_id);
		if (PEAR::isError($res)) {
    		die($res->getMessage());
		}
		$error_params = func_get_args();
		return $this->status($user_id, 'get_user_by_character()', __LINE__, $error_params);
	}
	

#-----------| Guild Management |-----------#
	
	/*
		add_guild('Claiomh an Highlanders');
	*/
	function add_guild($guild_name)
	{
		global $LUA;
		$data = array('application_define_name' => $guild_name);
		$appId = $LUA->perm->addApplication($data);
		$error_params = func_get_args();
		return $this->status($appId, 'add_guild()', __LINE__, $error_params);
	}
	
	/*
		$data = array('application_define_name' => $new_application_name);
		$filters = array('application_id' => $application_id
						 'application_define_name' => $application_name);
	*/
	function change_guild($data, $filters)
	{
		global $LUA;
		$updateApp = $LUA->perm->updateApplication($data, $filters);
		$error_params = func_get_args();
		return $this->status($updateApp, 'change_guild()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('application_id' => $guild_id,
						'application_define_name => $guild_name);
		delete_guild($filter) // Delete specified guild, a filter has to be passed
	*/
	function delete_guild($filter)
	{
		global $LUA;
		$removeApp = $LUA->perm->removeApplication($filter);
		$error_params = func_get_args();
		return $this->status($removeApp, 'delete_guild()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('filters' => array('application_id' => $application_id));
	*/
	function get_guild($filter = array())
	{
		global $LUA;
		if(empty($filter)) $applications = $LUA->perm->getApplications();
		else $applications = $LUA->perm->getApplications($filter);
		$error_params = func_get_args();
		return $this->status($applications , 'get_guild()', __LINE__, $error_params);
	}
	
	
#-----------| Area Management |-----------#
	
	/*
		add_area('1', 'Roster'); 
	*/
	function add_area($guild_id, $area_name)
	{
		global $LUA;
		$data = array('application_id' => $guild_id,
					  'area_define_name' => $area_name);
		$areaId = $LUA->perm->addArea($data);
		$error_params = func_get_args();
		return $this->status($areaId, 'add_area()', __LINE__, $error_params);
	}
	
	/*
		$data = array('area_define_name' => $new_area_name);
		$filter = array('area_id' => $area_id);
		change_area($data, $filter); 
	*/
	function change_area($data, $filter)
	{
		global $LUA;
		$updateArea = $LUA->perm->updateArea($data, $filter);
		$error_params = func_get_args();
		return $this->status($updateArea, 'change_area()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('area_id' => $area_id,
						'guild_id' => $guild_id,
						'area_define_name => $area_name);
		delete_area($filter); // Deletes an area, a filter has to be passed
	*/
	function delete_area($filter)
	{
		global $LUA;
		$removeArea = $LUA->perm->removeArea($filter);
		$error_params = func_get_args();
		return $this->status($removeArea, 'delete_area()', __LINE__, $error_params);
	}
	
	/*
		$filters = array('filters' => array('area_id' => $area_id,
											'application_id' => $guild_id,
											'area_define_name' => $area_name));
		get_area($filter);  // Get specific area(s)
		get_area();			// Get all areas
	*/
	function get_area($filters=array())
	{
		global $LUA;
    	if(!empty($filters)) {
    		$areas = $LUA->perm->getAreas($filters);
		}
		else
		{
			$areas = $LUA->perm->getAreas();
		}
		$error_params = func_get_args();
		return $this->status($areas, 'get_area()', __LINE__, $error_params);
	}


#-----------| Group Management |-----------#

	/*
		add_group('CaH Members');
	*/
	function add_group($group_name)
	{
		global $LUA;
		$data = array('group_define_name' => $group_name);
		$groupId = $LUA->perm->addGroup($data);
		$error_params = func_get_args();
		return $this->status($groupId, 'add_group()', __LINE__, $error_params);
	}
	
	/*
		$data = array('group_define_name' => $new_group_name);
		$filters = array('group_id' => $group_id
						 'group_define_name' => $group_name);
		change_group($data, $filters);
	*/
	function change_group($data, $filters)
	{
		global $LUA;
		$updateGroup = $LUA->perm->updateGroup($data, $filters);
		$error_params = func_get_args();
		return $this->status($updateGroup ,'change_group()', __LINE__, $error_params);
		
	}
	
	/*
		$filter = array('group_id' => $group_id,
						'group_define_name' => $group_name);
		delete_group($filter);
	
	*/
	function delete_group($filter)
	{
		global $LUA;
		$removed = $LUA->perm->removeGroup($filter);
		$error_params = func_get_args();
		return $this->status($removed, 'delete_group()', __LINE__, $error_params);
	}
	
	/*
		$filters = array('fields' => 
							array('group_id', 'group_define_name', 'description'),
						'filters' => array('language_id' => 'en')
						);
		get_group($filters); // Specific result
		get_group();		// All groups
	*/
	function get_group($filters=array())
	{
		global $LUA;
		if(empty($filters)) $groups = $LUA->perm->getGroups();
		else $groups = $LUA->perm->getGroups($filters);
		$error_params = func_get_args();
		return $this->status($groups, 'get_group()', __LINE__, $error_params);
	}
	
	/*
		$data = array('right_id' => $right_id,
					  'group_id' => $group_id);
		grant_group_right($data);
	*/
	function grant_group_right($data)
	{
		global $LUA;
		$added = $LUA->perm->grantGroupRight($data);
		$error_params = func_get_args();
		return $this->status($added, 'grant_group_right()', __File__, $error_params);
	}
	
	/*
		$data = array('right_level' => $new_right_level);
		$filters = array('right_id' => $right_id,
					 	 'group_id' => $group_id);
		change_group_right($data, $filters);
	*/
	function change_group_right($data, $filters)
	{
		global $LUA;
		$updated = $LUA->perm->updateGroupRight($data, $filters);
		$error_params = func_get_args();
		return $this->status($updated, 'change_group_right()', __File__, $error_params);
	}
	
	/*
		$filter = array('group_id' => $group_id, 'right_id' => $right_id);
		delete_group_right($filter);
	*/
	function delete_group_right($filter)
	{
		global $LUA;
		$removed = $LUA->perm->revokeGroupRight($filter);
		$error_params = func_get_args();
		return $this->status($removed, 'delete_group_right()', __File__, $error_params);
	}

	
#-----------| Rights Management |-----------#

	/*
		add_right('1', 'View', '0'); // Area 1, View, Not implying a right (0 / 1)
	*/
	function add_right($area_id, $right_name, $has_implied)
	{
		global $LUA;
		$data = array('area_id' => $area_id,
					  'right_define_name' => $right_name,
					  'has_implied' => $has_implied);
		$rightId = $LUA->perm->addRight($data);
		$error_params = func_get_args();
		return $this->status($rightId, 'add_right()', __LINE__, $error_params);
	}
	
	/*
		change_right('1', 'View Roster'); // View, View Roster
	*/
	function change_right($right_id, $new_right_name)
	{
		global $LUA;
		$data = array('right_define_name' => $new_right_name);
		$filter = array('right_id' => $right_id);
		$updateRight = $LUA->perm->updateRight($data, $filter);
		$error_params = func_get_args();
		return $this->status($updateRight, 'change_right()', __LINE__, $error_params);
	}
	
	/*
		delete_right('1'); // View Roster
	*/
	function delete_right($right_id)
	{
		global $LUA;
		$filter = array('right_id' => $right_id);
		$removeRight = $LUA->perm->removeRight($filter);
		$error_params = func_get_args();
		return $this->status($removeRight, 'delete_right()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('filters' => array('right_id' => $right_id,
										  'area_id' => $area_id,
										  'right_define_name' => $right_name,
										  'has_implied' => $has_implied));
		get_right($filters); // Get specific right(s)
		get_right();		// Gets all existing rights
	*/
	function get_right($filters=array())
	{
		global $LUA;
		$rights = $LUA->perm->getRights($filters);
		$error_params = func_get_args();
		return $this->status($rights, 'get_right()', __LINE__, $error_params);
	}
	
	/*
		$data = array('right_id' => $right_id,
				  	  'implied_right_id' => $implied_right_id);
		add_implied_right($data); // Adds a record that one right implies the other one to be granted
	*/
	function add_implied_right($data)
	{
		global $LUA;
		$impliedright = $LUA->perm->implyRight($data);
		$error_params = func_get_args();
		return $this->status($impliedright, 'add_implied_right()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('right_id' => $right_id,
						'implied_right_id' => $implied_right_id);
		$update_imply_status = true; // Dont change this setting
		delete_implied_right($filter, $update_imply_status);
	*/
	function delete_implied_right($filter, $update_imply_status)
	{
		global $LUA;
		$deleteImpliedRight = $LUA->perm->unimplyRight($filter, $update_imply_status);
		$error_params = func_get_args();
		return $this->status($deleteImpliedRight, 'delete_implied_right()', __LINE__, $error_params);
	}		


#-----------| Translation Management |-----------#

	/*
		$data = array('section_id' => $section_id,
					  'section_type' => $section_type,
					  'language_id' => $language_id,
					  'name' => $name,
					  'description' => $description);
					  
		$section_type may contain one of the following constants:
			LIVEUSER_SECTION_APPLICATION
			LIVEUSER_SECTION_AREA
			LIVEUSER_SECTION_GROUP
			LIVEUSER_SECTION_RIGHT
		
		$section_id is the ID of the record to be manipulated,
		 in the table corresponding to the $section_type setting.
		 
		$language_id is a 2 character language code.
		
		$name is the _define_name of the record in question.
		
		add_translation($data);
	*/
	function add_translation($data)
	{
		global $LUA;
		$added = $LUA->perm->addTranslation($data);
		$error_params = func_get_args();
		return $this->status($added, 'add_translation()', __LINE__, $error_params);
	}
	
	/*
		$data = array('name' => $changed_name,
					  'description' => $changed_translation);
		$filter = array('translation_id' => $translation_id,
						'name' => $name,
						'description' => $description);
		change_translation($data, $filter);
	*/
	function change_translation($data, $filter)
	{
		global $LUA;
		$updatetranslation = $LUA->perm->updateTranslation($data, $filter);
		$error_params = func_get_args();
		return $this->status($updatetranslation, 'change_translation()', __LINE__, $error_params);
	}
	
	/*
		$filter = array('translation_id' => $translation_id,
						'section_id' => $group/guild/area/right_id
						'name' => $name,
						'description' => $description);
	*/
	function delete_translation($filter)
	{
		global $LUA;
		$removed = $LUA->perm->removeTranslation($filter);
		$error_params = func_get_args();
		return $this->status($removed, 'delete_translation()', __LINE__, $error_params);
		
	}
	
	/*
		$filter = array('section_id' => $section_id,
						'section_type' => $section_type,
						'language_id' => $language_id,
						'name' => $name,
						'description' => $description);
		get_translation($filter);
	*/
	function get_translation($filter)
	{
		global $LUA;
		$translation = $LUA->perm->getTranslations($filter);
		$error_params = func_get_args();
		return $this->status($translation, 'get_translation()', __LINE__, $error_params);
		
	}

#-----------| Character Management |-----------#

	/*
		$user_id = $auth_user_id
	*/
	function get_characters($user_id)
	{
		global $LUA, $db;
		$query_string = "SELECT members.member_id, members.name, members.guild_title, members.guild_id, guilds.guild_name, link.status FROM (roster2_addon_auth_user_char_linktable AS link, roster2_members AS members, roster2_guilds AS guilds) WHERE (link.auth_user_id = ".$user_id.") AND (link.member_id = members.member_id) AND (guilds.guild_id = members.guild_id) ORDER BY guilds.guild_name ASC, members.guild_title ASC";
		$res = &$db->query($query_string);
		if (PEAR::isError($res)) {
    		die($res->getMessage());
		}
		$i=0;
		while($res->fetchInto($characters['data'][$i])){ $i++; }
		$characters['num_rows'] = $res->numRows();
		$error_params = func_get_args();
		return $this->status($characters, 'get_characters()', __LINE__, $error_params);
	}
	
	/*
		$data = array('auth_user_id' => $id, 'member_id' => $member_id, 'status' => $status);
	*/
	function change_char_status($data)
	{
		global $LUA, $db;
		$query_string = "UPDATE roster2_addon_auth_user_char_linktable SET status = '".$data['status']."' WHERE auth_user_id = ".$data['auth_user_id']." AND member_id = ".$data['member_id']."";
		$res = &$db->query($query_string);
		if (PEAR::isError($res)) {
    		die($res->getMessage());
		}
	}
	
	/*
		$data = array('auth_user_id' => $id, 'member_id' => $member_id, 'status' => $status);
	*/
	function delete_user_char_link($data)
	{
		global $LUA, $db;
		if(!empty($data['member_id'])){
			$query_string = "DELETE FROM roster2_addon_auth_user_char_linktable WHERE auth_user_id = ".$data['auth_user_id']." AND member_id = ".$data['member_id']."";
		}else{
			$query_string = "DELETE FROM roster2_addon_auth_user_char_linktable WHERE auth_user_id = ".$data['auth_user_id']."";
		}
		$res = &$db->query($query_string);
		if(PEAR::isError($res)) {
			die($res->getMessage());
		} 
	}
	
	/*
		$data = array('auth_user_id' => $auth_user_id, 'member_id' => $char_id, 'guild_name' => $guild_name);
	*/
	function add_user_char_link($data)
	{
		global $LUA, $db;
		if(!empty($data['guild_name']))	{
			$query_string = "SELECT member_id FROM (roster2_members AS members) INNER JOIN (roster2_guilds AS guilds) ON (members.guild_id = guilds.guild_id AND guilds.guild_name = '".$data['guild_name']."') WHERE name = '".$data['char_name']."'";
		}else{
			$query_string = "SELECT member_id FROM roster2_members WHERE name = '".$data['char_name']."'";
		}
		$res = &$db->query($query_string);
		$res->fetchInto($member_id);
		if(PEAR::isError($res)) {
			return $res->getMessage();
		} 
		$query_string = "INSERT INTO roster2_addon_auth_user_char_linktable (auth_user_id, member_id) VALUES (".$data['auth_user_id'].", ".$member_id['member_id'].")";
		$res = &$db->query($query_string);
		if(PEAR::isError($res)) {
			return $res->getMessage();
		} 
	}
	

#-----------| Error Management |-----------#

	/*
		print_array($array, 0);
	*/
	function print_array($arr, $level) 
	{
		$indent=str_repeat("&nbsp;", $level * 4);
		print "<div align='left'>";
		foreach ($arr as $key => $item) {
			if (is_array($item)) {
				print $indent . "[".$key."] Item is an array...<br><br>";
				$this->print_array($item, $level + 1);
			} else
				print $indent . $key .": ". $item . "<br><br>";
		}
		print "</div>";
	}
	
	/*
		// The result of a function to be tested, the result is passed back if its OK,
		// or the additional variables are used for the error message.
		$error_params = func_get_args();
		status($object, 'sending_function()', __LINE__, $error_params); 
	*/
	function status($object, $sender, $line, $args=array())
	{
		global $addon_conf, $LUA;
		if ($object == false)
		{
			if($addon_conf['authentication']['debug'] == true)
			{
				$native_errors = $LUA->getErrors();
				if(!empty($native_errors))
				{
					print 'Error in '.$sender.'<br />on line: '.$line.'<br />';
					if(!empty($args))
					{ 
						print 'with the arguments of: ';
						print_r($args);
					}
					print '<br /><hr>';
					if(is_array($native_errors)) $this->print_array($native_errors, 0);
				}
			}
		}
		else
		{
			return $object;
		}
	}
	

}

?>