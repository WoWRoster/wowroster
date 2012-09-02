<?php

if( !isset($user) )
{
include_once ($addon['inc_dir'] . 'conf.php');
}

if(isset($_POST['op']) && $_POST['op']=='register')
{
	// If the Register form has been submitted
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>64)
	{
		$err[]='Your username must be between 3 and 64 characters!';
	}

	if (isset($_POST['password1']) && isset($_POST['password2']) && $_POST['password1'] == $_POST['password2'])
	{
		$pass = md5($_POST['password1']);
	}
	
	//"SELECT COUNT(*) AS `check` FROM %s WHERE `email` = '%s' AND `active` = '1'";
	//
	$email = mysql_real_escape_string($_POST['email']);
	if ( $user->user->checkEMail($email) )
	{
		// ok the email passed the form check now see if its used anywhere else...
		$em = "SELECT COUNT(`email`) AS `check` FROM `".$roster->db->table('user_members')."` WHERE `email` = '".$email."'";
		$ema = $roster->db->query($em);
		$rowem = $roster->db->fetch($ema);
		if ($rowem['check'] == '0')
		{
		
			if(!count($err))
			{
			
				$_POST['username'] = mysql_real_escape_string($_POST['username']);
				// Escape the input data
				if (!empty($_POST['rank']))
				{
					$rank = $_POST['rank'];
				}
				else
				{
					$querya = "SELECT `name`,`guild_rank` FROM `".$roster->db->table('members')."` WHERE `name` = '".$_POST['username']."';";
					$resulta = $roster->db->query($querya);
					if( $resulta )
					{
						$row = $roster->db->fetch($resulta);
						$rank = $row['guild_rank'];
					}
					else
					{
						$rank = '';
					}
				}

				$age = mktime(0, 0, 0, $_POST['age_Month'], $_POST['age_Day'], $_POST['age_Year']);
				$data = array(
					'usr'		=> $_POST['username'],
					'pass'		=> $pass,
					'email'		=> $email,
					'regIP'		=> $_SERVER['REMOTE_ADDR'],
					'dt'		=> $roster->db->escape(gmdate('Y-m-d H:i:s')),
					'access'	=> '0:'.$rank,
					'fname'		=> $_POST['fname'],
					'lname'		=> $_POST['lname'],
					'age'		=> $age,
					'city'		=> $_POST['City'],
					'state'		=> $_POST['State'],
					'country'	=> $_POST['Country'],
					'zone'		=> $_POST['Zone'],
					'active'	=> $_POST['active']
				);
				$query = 'INSERT INTO `' . $roster->db->table('user_members') . '` ' . $roster->db->build_query('INSERT', $data);

				// user link table i was hoping to NOT use this....
				
				if( $roster->db->query($query) )
				{
					$uuid = $roster->db->insert_id();
					$roster->set_message('You are registered and can now login','User Register:','notice');
					
					$querya = "SELECT `name`,`guild_id`,`server`,`region`,`member_id` FROM `".$roster->db->table('members')."` WHERE `name` = '".$_POST['username']."';";
					$resulta = $roster->db->query($querya);
					$a = "INSERT INTO `".$roster->db->table('profile','user')."` (`uid`, `signature`, `avatar`, `avsig_src`, `show_fname`, `show_lname`, `show_email`, `show_city`, `show_country`, `show_homepage`, `show_notes`, `show_joined`, `show_lastlogin`, `show_chars`, `show_guilds`, `show_realms`) VALUES ('$uuid', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');";
					$aa = $roster->db->query($a);

					if( !$resulta )
					{
						die_quietly($roster->db->error, 'user Profile', __FILE__,__LINE__,$querya);
					}
					else
					{
						$row = $roster->db->fetch($resulta);
						
						$data2 = array(
							'uid' => $uuid,
							'member_id' => $row['member_id'],
							'guild_id' => $row['guild_id'],
							'group_id' => (isset($roster->data['guild_id']) ? $roster->data['guild_id'] : '0'),
							'is_main' => '1',
							'realm' => $row['server'],
							'region' => $row['region']
						);
						$query2 = 'INSERT INTO `' . $roster->db->table('user_link', 'user') . '` ' . $roster->db->build_query('INSERT', $data2);
						$result2 = $roster->db->query($query2);
						
						$update_sql = "UPDATE `" . $roster->db->table('members') . "`"
									  . " SET `account_id` = '" . $uuid . "'"
									  . " WHERE `name` = '".$_POST['username']."';";
						$accid = $roster->db->query($update_sql);
					}

					echo $roster->auth->getLoginForm();
					return;

				}
				else
				{
					$roster->set_message('There was a DB error while creating your user.', '', 'error');
					$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
				}
			}
		}
		else
		{
			$roster->set_message($roster->locale->act['user_user']['msg31'],$roster->locale->act['user_page']['register'],'error');
		}

	}
	else
	{
		//echo $roster->locale->act['user_user']['msg16']
		$roster->set_message($roster->locale->act['user_user']['msg16'],$roster->locale->act['user_page']['register'],'warning');
	}
	if(count($err))
	{
		$e = implode('<br />',$err);
	}

}
	$num1=$num2=$num3=null;
	function generateUniqueRandoms($min, $max, $count)  
	{
		if($count > $max)  
		{  // this prevents an infinite loop
			echo "ERROR: The array count is greater than the random number maximum.<br>\n";
			echo "Therefore, it is impossible to build an array of unique random numbers.<br>\n";
			break;
		}    
		$numArray = array('0'=>'','1'=>'','2'=>'');
		for($i = 0; $i < $count; $i++)
		{        
			$numArray[$i] = mt_rand($min,$max);         // set random number
			
			for($j = 0; $j < $count; $j++)                 // for each number, check for duplicates
			  if($j != $i)                                 // except for the one you are checking of course
				if($numArray[$i] == $numArray[$j])
				{
					$numArray[$i] = mt_rand(1,10);         // if duplicate, generate new random
					$j = 0;                                // go back through and check new number
				} 
		}
		return $numArray;
	}

	if ($addon['config']['char_auth'])
	{
		$x = generateUniqueRandoms(0, 18, 3);
		$r = implode(':',$x);
		list($num1,$num2,$num3) = explode(':',$r);

		$s = "
		$(document).ready(function () {  
			//$('input#xsubmit').attr('disabled', 'true');
			$('input#submit_btn').click(function(){
			//$('#pUsername').keyup(function () {
				var t = this;
				var char = $('input#pUsername').val();
				var validateUsername = $('#validateUsername');
				var pUsername = $('#pUsername');
				var pclass = $('input#class');
				var plevel = $('input#level');
				var prank = $('#rank');
				var pmember_id = $('#member_id');
				var ptitle = $('#title');
				var photo = $('#photo');
				var EQ1 = $('div#EQ1');
				var EQ2 = $('div#EQ2');
				var EQ3 = $('div#EQ3');
			

					if (this.timer) clearTimeout(this.timer);
					validateUsername.removeClass('error').html('<img src=\"".$roster->config['img_url']."canvas-loader.gif\" height=\"18\" width=\"18\" /> checking availability...');
					this.timer = setTimeout(function () {
						$.ajax({
							url: 'index.php?p=user-user-ajaxcvar',
							//url: 'index.php?p=ajaxcvar',
							data: 'action=check_username&server=".$roster->data['server']."&slot=".$num1.'-'.$num2.'-'.$num3."&character=' + char,
							dataType: 'json',
							type: 'post',
							success: function (j) {
								if (j.ok)
								{
									validateUsername.html(j.msg);
									pUsername.val(char);
									$('div#EQ1').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ1);
									$('div#EQ2').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ2);
									$('div#EQ3').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ3);
									
									pclass.val(j.cla55);
									plevel.val(j.level);
									prank.val(j.rank);
									pmember_id.val(j.member_id);
									ptitle.html(j.title);
									
									photo.html('<img src=\"http://us.battle.net/static-render/us/'+j.thumb+'\" height=\"75\" width=\"75\" /></a>');
									
									$('#xsubmit').removeAttr('disabled');
									$('input#submit_btn').attr('disabled', 'true');
								}
								else
								{
									photo.html('<img src=\"http://us.battle.net/static-render/us/'+j.thumb+'\" height=\"75\" width=\"75\" /></a>');
									validateUsername.html(j.msg);
									$('div#EQ1').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ1);
									$('div#EQ2').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ2);
									$('div#EQ3').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ3);
									pclass.val(j.cla55);
									plevel.val(j.level);
									prank.val(j.rank);
									ptitle.html(j.title);
									
								}
								
							}

						});
					}, 200);

			});
		});";

		roster_add_js($s,'inline');
	}
	
	function _getItemSlot($value) {
		$ret = '';
		switch ($value) 
		{
			case 0: $ret = "Head";break;		case 1: $ret = "Neck";break;			case 2: $ret = "Shoulder";break;
			case 3: $ret = "Shirt";break;		case 4: $ret = "Chest";break;			case 5: $ret = "Waist";break;
			case 6: $ret = "Legs";break;		case 7: $ret = "Feet";break;			case 8: $ret = "Wrist";break;
			case 9: $ret = "Hands";break;		case 10: $ret = "Finger0";break;		case 11: $ret = "Finger1";break;
			case 12: $ret = "Trinket0";break;	case 13: $ret = "Trinket1";break;		case 14: $ret = "Back";break;
			case 15: $ret = "MainHand";break;	case 16: $ret = "SecondaryHand";break;	case 17: $ret = "Ranged";break;
			case 18: $ret = "Tabard";break;		
		}
		return $ret;
	}
	function _getItemSlotn($value) {
		$ret = '';
		switch ($value) 
		{
			case 0: $ret = "Head";break;		case 1: $ret = "Neck";break;			case 2: $ret = "Shoulder";break;
			case 3: $ret = "Shirt";break;		case 4: $ret = "Chest";break;			case 5: $ret = "Waist";break;
			case 6: $ret = "Legs";break;		case 7: $ret = "Feet";break;			case 8: $ret = "Wrist";break;
			case 9: $ret = "Hands";break;		case 10: $ret = "Finger 0";break;		case 11: $ret = "Finger 1";break;
			case 12: $ret = "Trinket 0";break;	case 13: $ret = "Trinket 1";break;		case 14: $ret = "Back";break;
			case 15: $ret = "MainHand";break;	case 16: $ret = "SecondaryHand";break;	case 17: $ret = "Ranged";break;
			case 18: $ret = "Tabard";break;		
		}
		return $ret;
	}

$form = 'userApp';
//$user->form->newForm('post', makelink('util-accounts-application'), $form, 'formClass', 4);

$user->form->newForm('post', makelink('user-user-register'), $form, 'formClass', 4);

if ($addon['config']['fname_auth'] == '1')
{
	$user->form->addTextBox('text', 'fname', '', $roster->locale->act['user_int']['fname'], 'wowinput128', 1, $form);
}
if ($addon['config']['lname_auth'] == '1')
{
	$user->form->addTextBox('text', 'lname', '', $roster->locale->act['user_int']['lname'], 'wowinput128', '', $form);
}
if ($addon['config']['age_auth'] == '1')
{
	$user->form->addDateSelect('age','Birthdate',1,$form);
}
if ($addon['config']['city_auth'] == '1')
{
	$user->form->addTextBox('text','City','',$roster->locale->act['user_int']['city'],'wowinput128','',$form);
}
if ($addon['config']['state_auth'] == '1')
{
	$user->form->addTextBox('text','State','',$roster->locale->act['user_int']['state'],'wowinput128','',$form);
}
if ($addon['config']['country_auth'] == '1')
{
	$user->form->addTextBox('text','Country','',$roster->locale->act['user_int']['country'],'wowinput128',1,$form);
}
if ($addon['config']['zone_auth'] == '1')
{
	$user->form->addTimezone('Zone',$roster->locale->act['user_app']['zone'],'',$form);
}

if ($addon['config']['char_auth'] == '1')
{
	$roster->tpl->assign_vars(array(
		'R_EQUIP_1'     => _getItemSlot($num1),
		'R_EQUIP_2'     => _getItemSlot($num2),
		'R_EQUIP_3'     => _getItemSlot($num3),
		'N_EQUIP_1'     => _getItemSlotn($num1),
		'N_EQUIP_2'     => _getItemSlotn($num2),
		'N_EQUIP_3'     => _getItemSlotn($num3),
		'R_IMAGE_PTH'	=> $roster->config['img_url'].'Interface/EmptyEquip/',
		//'R_Q_CHAR'     => @$_REQUEST['character'],
		//'R_Q_RANK'     => @$_REQUEST['rank'],
		'R_MSG_ERROR'	=> (!empty($e) ? $e : ''),
		'CHAR_AUTH'		=> $addon['config']['char_auth'],
		'CNAMETT' 		=> makeOverlib($roster->locale->act['cname_tt'],$roster->locale->act['cname'],'',1,'',',WRAP'),
		'CNAME' 		=> $roster->locale->act['cname'],
		'CLASSTT' 		=> makeOverlib($roster->locale->act['cclass_tt'],$roster->locale->act['cclass'],'',1,'',',WRAP'),
		'CLASS'			=> $roster->locale->act['cclass'],
		'LEVELTT' 		=> makeOverlib($roster->locale->act['clevel_tt'],$roster->locale->act['clevel'],'',1,'',',WRAP'),
		'LEVEL' 		=> $roster->locale->act['clevel'],
		'RANKTT' 		=> makeOverlib($roster->locale->act['cgrank_tt'],$roster->locale->act['cgrank'],'',1,'',',WRAP'),
		'RANK' 			=> $roster->locale->act['cgrank'],
		'EMAILTT' 		=> makeOverlib($roster->locale->act['cemail_tt'],$roster->locale->act['cemail'],'',1,'',',WRAP'),
		'EMAIL' 		=> $roster->locale->act['cemail'],
		'AGE'			=> 'Birthday',
		'AGEF'			=> '',
		'XXX'			=> $user->form->getTableOfElements_3(1,$form),
		)
	);

}
else
{
	$roster->tpl->assign_vars(array(
		'R_EQUIP_1'     => '',//_getItemSlot($num1),
		'R_EQUIP_2'     => '',//_getItemSlot($num2),
		'R_EQUIP_3'     => '',//_getItemSlot($num3),
		'N_EQUIP_1'     => '',//_getItemSlotn($num1),
		'N_EQUIP_2'     => '',//_getItemSlotn($num2),
		'N_EQUIP_3'     => '',//_getItemSlotn($num3),
		'R_IMAGE_PTH'	=> $roster->config['img_url'].'Interface/EmptyEquip/',
		//'R_Q_CHAR'     => @$_REQUEST['character'],
		//'R_Q_RANK'     => @$_REQUEST['rank'],
		'R_MSG_ERROR'	=> (!empty($e) ? $e : ''),
		'CHAR_AUTH'		=> $addon['config']['char_auth'],
		'CNAMETT' 		=> makeOverlib($roster->locale->act['cname_tt'],$roster->locale->act['cname'],'',1,'',',WRAP'),
		'CNAME' 		=> $roster->locale->act['cname'],
		'CLASSTT' 		=> makeOverlib($roster->locale->act['cclass_tt'],$roster->locale->act['cclass'],'',1,'',',WRAP'),
		'CLASS'			=> $roster->locale->act['cclass'],
		'LEVELTT' 		=> makeOverlib($roster->locale->act['clevel_tt'],$roster->locale->act['clevel'],'',1,'',',WRAP'),
		'LEVEL' 		=> $roster->locale->act['clevel'],
		'RANKTT' 		=> makeOverlib($roster->locale->act['cgrank_tt'],$roster->locale->act['cgrank'],'',1,'',',WRAP'),
		'RANK' 			=> $roster->locale->act['cgrank'],
		'EMAILTT' 		=> makeOverlib($roster->locale->act['cemail_tt'],$roster->locale->act['cemail'],'',1,'',',WRAP'),
		'EMAIL' 		=> $roster->locale->act['cemail'],
		'AGE'			=> 'Birthday',
		'AGEF'			=> '',
		'XXX'			=> $user->form->getTableOfElements_3(1,$form),
		)
	);
}

$roster->tpl->set_filenames(array(
	'register' => 'register.html'
	)
);
$roster->tpl->display('register');	

?>