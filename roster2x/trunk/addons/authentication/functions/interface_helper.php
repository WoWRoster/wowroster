<?php 
// All the parts where logic cant be separated from presentation are here, to keep the frontend as clear from code as possible
class Interface_Helper extends Authentication
{

#-----------| GUI |-----------#
	function gui($class=NULL, $function=NULL, $args=NULL)
	{
		require_once strtolower($class).'.php';
		$GUI_Class = new $class;
		$GUI_Class->$function($args);
	}

		
#-----------| General Functions |-----------#	

	function treat_get_post($request=array())
	{
		global $LU;
		// Make sure the user is logged in and that he has at least GM status
		if($LU->isLoggedIn())
		{
			if($LU->getProperty('perm_type') >= LIVEUSER_SUPERADMIN_TYPE_ID)
			{
				if(!empty($request['object']))
				{
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
								{	
									// check again, if its a changed one, this should only pass if its a totally new name, not a change of name
									$filters = array('filters' => array('application_id' => $request['guild_dropdown'], 'area_define_name' => base64_encode($request['area_input'])));
									$existing_area = $this->get_area($filters);
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
									// Do that by drilling down:
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
		
									$filter = array('filters' => array('application_id' => $guild[0]['application_id']));
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
								// If the right type has changed, save it
								if(!empty($request['right_level']) && $request['orig_right_level'] != $request['right_level'])
								{
									$filter = array('container' => 'perm',
													'filters' => array('auth_user_id' => $request['auth_user_id']));
									$user = $this->get_user($filter); 
									$new_data = array('perm_type' => $request['right_level']);
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
								if(!@empty($request['char_name']))
								{
									$data = array('auth_user_id' => $request['auth_user_id'], 'char_name' => utf8_encode(ucwords($request['char_name'])));
									$error = $this->add_user_char_link($data);
									if(@$error == 'DB Error: already exists') $_SESSION['error'] = ucwords($request['char_name']).' is already grouped up with an account';
									if(@$error == 'DB Error: syntax error') $_SESSION['error'] = ucwords($request['char_name']).' could not be found, please check the spelling of the name';
								}
							}
							elseif($request['action'] == 'remove_from_group')
							{
								// Remove a user from a group
								$this->remove_user_from_group($request['id'], $request['group_id']);
								header("Location: ".$_SERVER['PHP_SELF'].'?'.stripslashes(base64_decode($request['url'])));
								exit;
							}
							elseif($request['action'] == 'add_user_to_group')
							{
								// Add a user to a group
								$this->add_user_to_group($request['user_id'], $request['group_id']);
							}
							break;
						} // End of - case 'users'
						#-----| Rights Management |-----#
						case 'rights':
						{
							if($request['action'] == 'group_rights')
							{
								if(@$request['area_id'] != 'Select' && @$request['save'] == 'Set')
								{
									$ticked = array();
									$unticked = array();
									if(@$request['view']) $ticked[] = 'View'; else $unticked[] = 'View';
									if(@$request['use']) $ticked[] = 'Use'; else $unticked[] = 'Use';
									if(@$request['edit']) $ticked[] = 'Edit'; else $unticked[] = 'Edit';
									
									foreach($ticked as $selected){
										// Check if the right for this area exists
										$filter = array('filters' => array('area_id' => $request['area_id'], 'right_define_name' => $selected));
										$right = $this->get_right($filter);
										if(empty($right)) {// If the right isnt associated with this area yet, create it
											$this->add_right($request['area_id'], $selected, 0);
										} 
										// Now that we're sure that the right exists, get the details
										$filter = array('filters' => array('area_id' => $request['area_id'], 'right_define_name' => $selected));
										$right = $this->get_right($filter);
										if(!empty($right)){ // Check that its not been granted to the group yet (avoid constraint error)
											$filter = array('filters' => array('group_id' => $request['group_id'], 'right_id' => $right[0]['right_id']));
											$group_right = $this->get_group($filter);
											if(empty($group_right)){ // All checks passed, grant the right for this area to the group
												$data = array('right_id' => $right[0]['right_id'], 'group_id' => $request['group_id']);
												$this->grant_group_right($data);
											}
										}
									}
									foreach($unticked as $deselected){ 
										// A right was not selected, remove the groupright
										$filter = array('filters' => array('area_id' => $request['area_id'], 'right_define_name' => $deselected));
										$right = $this->get_right($filter);
										if(!empty($right)){ 
											$filter = array('group_id' => $request['group_id'], 'right_id' => $right[0]['right_id']);
											$this->delete_group_right($filter);
										}
									}
								} // End of - if($request['area_id'] != 'Select')
							} // End of - if($request['action'] == 'group_rights')
							elseif($request['action'] == 'personal_rights')
							{
								
							}
							break;
						} // End of - case 'rights'
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