<?php
// Call this class the same name as the adapter directory name
class e107_7 extends CMS_Sync
{
	
	var $cms_users_table    = 'e107_user';
	var $cms_username_field = 'user_loginname';
	var $cms_password_field = 'user_password';
	var $cms_email_field    = 'user_email';
	// %1$s = roster username, %2$s = roster password, %3$s = roster email address. They can be used multiple times but have to be escaped.
	var $cms_new_user_query = 'INSERT INTO e107_user (user_name, user_loginname, user_password, user_email) VALUES (\'%1$s\', \'%1$s\', \'%2$s\', \'%3$s\')';
	
	
	function treat_get_post($request=array())
	{
		if(!@empty($_SESSION['cms_db'])) $this->cms_db = $_SESSION['cms_db'];
		if(!@empty($request['cms_db_username']) && @isset($request['cms_db_password']) && !@empty($request['cms_db_name']) && !@empty($request['cms_db_host']))
		{
			$cms_dsn = 'mysql://'.$request['cms_db_username'].':'.$request['cms_db_password'].'@'.$request['cms_db_host'].'/'.$request['cms_db_name'];
			$this->cms_db =& DB::connect($cms_dsn);
			// Check for connection error or else move onto the next page
			if(@isset($this->cms_db->message))
			{
				$this->message_stack = array($this->cms_db->message, $this->cms_db->userinfo);
				$this->display = $this->display_dsn_form();
			}
			else
			{
				$this->display = $this->display_transfer_options();
			}
		}
		elseif(empty($this->display))
		{
			$this->display = $this->display_dsn_form();
		}
		
		if(@isset($request['copy_all_to_roster']))
		{
			$this->message_stack = $this->transfer_accounts_from_cms();
			$this->display = $this->display_transfer_options();
		}
		elseif(@isset($request['copy_all_to_cms'])) 
		{
			$this->message_stack = $this->transfer_accounts_to_cms();
			$this->display = $this->display_transfer_options();
		}
		elseif(@isset($request['copy_selected_to_roster']))
		{
			if(@empty($request['list_cms_users'])) $request['list_cms_users'] = 'none';
			$this->message_stack = $this->transfer_accounts_from_cms($request['list_cms_users']);
			$this->display = $this->display_transfer_options();
		}
		elseif(@isset($request['copy_selected_to_cms']))
		{
			if(@empty($request['list_roster_users'])) $request['list_roster_users'] = 'none';
			$this->message_stack = $this->transfer_accounts_to_cms($request['list_roster_users']);
			$this->display = $this->display_transfer_options();
		}
		if(!empty($this->cms_db)) $_SESSION['cms_db'] = $this->cms_db;
	}

	function transfer_accounts_from_cms($accounts=array())
	{
		global $db, $IH;
		if(!empty($accounts))
		{
			if($accounts == 'none') return;
			if(!is_array($accounts)) $accounts = array($accounts);
			$where_clause = ' WHERE ';
			$i = 0;
			foreach($accounts as $key => $name)
			{
				if($i==0) $where_clause .= $this->cms_username_field." = '".$name."' ";
				else $where_clause .= "AND ".$this->cms_username_field." = '".$name."' ";
				$i++;
			}
		}
		else
		{
			$where_clause = NULL;
		}
		// Get the username (name used for login), password and email address from the CMS user table
		// Keep the format of: cms username AS username, cms password AS password, cms email AS email
		$query_string = "SELECT ".$this->cms_username_field." AS username, ".$this->cms_password_field." AS password, ".$this->cms_email_field." AS email FROM ".$this->cms_users_table.$where_clause;
		// ------------
		$res = &$this->cms_db->query($query_string);
		while($user_info = $res->fetchRow(DB_FETCHMODE_OBJECT))
		{
			$filter = array('container' => 'auth',
							'filters' => array('handle' => base64_encode(ucwords($user_info->username))));
			if(!$IH->get_user($filter))
			{
				$data = array(	'handle'    => $user_info->username,
								'passwd'    => $user_info->password,
								'email'     => $user_info->email,
								'is_active' => 1,
								'perm_type' => 1 );
				$IH->ih_create_user('', $data);
				$query_string = "UPDATE roster2_addon_auth_users SET username = '".base64_encode(ucwords($user_info->username))."', 
																	 password = '".$user_info->password."' 
								 WHERE username = '".$user_info->username."'";
				$db->query($query_string);
			}
			$message_stack[] = 'User: '.$user_info->username.' copied to the Roster.';
		}
		$message_stack[] = 'Account copying sequence complete.';
		return $message_stack;
	}
	

	function transfer_accounts_to_cms($accounts=array())
	{
		global $IH, $CMS_Sync;
		if(empty($accounts))
		{
			$accounts = $IH->get_user();
		}
		else
		{
			if($accounts == 'none') return;
			foreach($accounts as $key => $name)
			{
				$filter = array('container' => 'auth',
								'filters' => array('handle' => base64_encode(ucwords($name))));
				$temp_account = $IH->get_user($filter);
				$temp_accounts[] = $temp_account[0];
			}
			$accounts = $temp_accounts;
		}
		foreach($accounts as $key => $user_info)
		{
			$user_info['username'] = base64_decode($user_info['handle']);
			/* 	
				Check if the name already exists in the users table of the CMS, if not,
				insert a new record into the user table of the CMS.
				$user_info holds the roster user account information:
					username: 	$user_info['username']
					password: 	$user_info['passwd']
					email: 		$user_info['email']
			*/
			// --------------------
			$query_string = "SELECT ".$this->cms_username_field." FROM ".$this->cms_users_table." WHERE ".$this->cms_username_field." = '".$user_info['username']."'";
			// --------------------
			$res = &$this->cms_db->query($query_string);
			$result = $res->fetchRow(DB_FETCHMODE_OBJECT);
			if(@empty($result->user_loginname))
			{
				$query_string = sprintf($this->cms_new_user_query, $user_info['username'], $user_info['passwd'], $user_info['email']);
				$this->cms_db->query($query_string);
				$message_stack[] = 'User: '.$user_info['username'].' copied to e107.';
			}
		}
		$message_stack[] = 'Account copying sequence complete.';
		return $message_stack;
	}
	
	
	function get_cms_users()
	{
		global $IH;
		// Get the cms username or customized name (must be unique)
		// Keep the format of: cms username AS username
		$query_string = "SELECT ".$this->cms_username_field." AS username FROM ".$this->cms_users_table;
		// ------------
		$res = &$this->cms_db->query($query_string);
		while($user_info = $res->fetchRow(DB_FETCHMODE_OBJECT))
		{
			$user_list[] = $user_info;
		}
		return $user_list;
	}
	
	
	function display_dsn_form()
	{	
		global $IH;
		ob_start();
		?>
			<table border="0" cellpadding="1" cellspacing="1" width="530px">
				<form action="#" method="post">
				<tr align="center" class="sc_row2" style="font-size:14px; font-weight:bold; vertical-align:middle;">
					<td colspan="2" style="padding-top:5px; padding-bottom:5px;">
						Please enter the connection information to your CMS's Database.
					</td>
				</tr>
				<tr align="center" class="sc_row1" style="font-size:14px; font-weight:bold; vertical-align:middle;">
					<td>
						Username
					</td>
					<td style="padding-top:5px; padding-bottom:5px;">
						<input name="cms_db_username" type="text">
					</td>
				</tr>
				<tr align="center" class="sc_row2" style="font-size:14px; font-weight:bold; vertical-align:middle;">
					<td>
						Password
					</td>
					<td style="padding-top:5px; padding-bottom:5px;">
						<input name="cms_db_password" type="password">
					</td>
				</tr>
				<tr align="center" class="sc_row1" style="font-size:14px; font-weight:bold; vertical-align:middle;">
					<td>
						Database
					</td>
					<td style="padding-top:5px; padding-bottom:5px;">
						<input name="cms_db_name" type="text">
					</td>
				</tr>
				<tr align="center" class="sc_row2" style="font-size:14px; font-weight:bold; vertical-align:middle;">
					<td>
						DB Host
					</td>
					<td style="padding-top:5px; padding-bottom:5px;">
						<input name="cms_db_host" type="text">
					</td>
				</tr>
				<tr align="center" class="sc_row1" style="font-size:14px; font-weight:bold; vertical-align:middle;">
					<td colspan="2" style="padding-top:5px; padding-bottom:5px;">
						<input name="object" value="cms_sync" type="hidden">
						<input name="cms_dsn_submit" type="submit" value="Continue"  class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'">
					</td>
				</tr>
				<?php if(!empty($this->message_stack)) { ?>
				<tr class="sc_row2">
					<td colspan="3" style="color:#FF0000;">
						<?php 
							foreach($this->message_stack as $key => $message) {
								print $message.'<br />';
							}
							unset($this->message_stack);
						?>
					</td>
				</tr>
				<?php } ?>
				</form>
			</table>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function display_transfer_options($message_stack=array())
	{
		ob_start(); 
		?>
			<table border="0" cellpadding="1" cellspacing="1" width="530px">
				<form action="#" method="post">
				<tr align="center" class="sc_row1" style="font-size:14px; font-weight:bold; vertical-align:middle;">
					<td>
						<input name="copy_all_to_roster" type="submit" value="All <?php print $_GET['name']; ?> users >> Roster" class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'">
					</td>
					<td>&nbsp;</td>
					<td style="padding-top:5px; padding-bottom:5px;">
						<input name="copy_all_to_cms" type="submit" value="All Roster users >> <?php print $_GET['name']; ?>" class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'">
					</td>
				</tr>
				<tr class="sc_row2">
					<td colspan="3">
						<hr />
					</td>
				</tr>
				<tr class="sc_row1" style="font-size:14px; font-weight:bold; vertical-align:middle;" align="center">
					<td style="padding-top:5px; padding-bottom:5px;">
						<?php print $_GET['name']; ?>
						<br />
						<select name="list_cms_users[]" multiple size="20" style="width:150px; overflow:scroll;">
							<?php 
								$user_list = $this->get_cms_users();
								for($i=0; $i<count($user_list); $i++)
								{
									print '<option value='.$user_list[$i]->username.'>'.$user_list[$i]->username.'</option>';
								}
							?>
						</select>
					</td>
					<td>
						<input name="copy_selected_to_roster" type="submit" value=">" class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'" style="width:30px;">
						<br />
						<br />
						Copy
						<br />
						<input name="copy_selected_to_cms" type="submit" value="<" class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'" style="width:30px;">
					</td>
					<td style="padding-top:5px; padding-bottom:5px;">
						Roster
						<br />
						<select name="list_roster_users[]" multiple size="20" style="width:150px; overflow:scroll;">
							<?php
								global $IH;
								$user_list = $IH->get_user();
								for($i=0; $i<count($user_list); $i++)
								{
									print '<option value='.base64_decode($user_list[$i]['handle']).'>'.base64_decode($user_list[$i]['handle']).'</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<?php if(@$this->message_stack) { ?>
				<tr class="sc_row2">
					<td colspan="3">
						<?php 
							foreach($this->message_stack as $key => $message) {
								print $message.'<br />';
							}
							unset($this->message_stack);
						?>
					</td>
				</tr>
				<?php } ?>
				<tr class="sc_row2" align="center">
					<td colspan="3">
						Hold CTRL to select multiple names or Shift to select a range of names. <br /> Or simply hold the left mouse button and drag up/down to select a range.
					</td>
				</tr>
				</form>
			</table>	
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
}
?>
