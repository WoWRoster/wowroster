<?php 
/** 
 * Dev.PKComp.net WoWRoster Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    user 
 * @subpackage Profile 
 */
if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include_once ($addon['inc_dir'] . 'users.lib.php');

class usersProfile extends user
{
	
	var $uid;
	var $city;
	var $country;
	var $homepage;
	var $notes;
	var $configData = array();

	function userProfile()
	{
		global $roster, $addon, $user;

		//$this->uid = 0;
	}

	function usersProfile($uid = '', $char = '', $uname = '', $email = '')
	{
		global $roster, $addon, $user;
		
		if (is_null($uid))
		{
			if (is_null($char))
			{
				if (is_null($uname))
				{
					if (is_null($email))
					{
						$user->message = 'No search criteria entered!';
					}
					else
					{
						$this->uid = $user->session->getVal('uid');
					}
				}
				else
				{
					$this->uid = $user->session->getVal('uid');
				}
			}
			else
			{
				$msql = 'SELECT `member_id` FROM ' . $roster->db->table['members'] . ' WHERE `name` = ' . $char . ';';
				$mquery = $roster->db->query($msql);
				while ($row = $roster->db->fetch($mquery))
				{
					$mid = $row['member_id'];
				}
				
				$usql = 'SELECT `uid` FROM ' . $roster->db->table('user_link', 'user') . ' WHERE `member_id` = ' . $mid . ';';
				$uquery = $roster->db->query($usql);
				while ($row = $roster->db->fetch($uquery))
				{
					$this->uid = $row['uid'];
				}
			}
		}
		else 
		{
			$this->uid = $uid;
		}
	}

	function recruitment()
	{
		global $roster, $addon, $user;

		if($addon['config']['acc_use_recruit'] == 1)
		{
			$roster->tpl->assign_block_vars('user_recruitment', array(
				'BORDER_START' => border('sred','start', $roster->locale->act['user_page']['recruitment']),
				'RECRUIT_TXT' => $roster->locale->act['user_int']['rec_txt'],
				'STATUS' => ucfirst($addon['config']['acc_rec_status']),
				'DRUID' => ucfirst($addon['config']['acc_rec_druid']),
				'DRUID_TXT' => $roster->locale->act['Druid'],
				'HUNTER' => ucfirst($addon['config']['acc_rec_hunter']),
				'HUNTER_TXT' => $roster->locale->act['Hunter'],
				'MAGE' => ucfirst($addon['config']['acc_rec_mage']),
				'MAGE_TXT' => $roster->locale->act['Mage'],
				'PALADIN' => ucfirst($addon['config']['acc_rec_paladin']),
				'PALADIN_TXT' => $roster->locale->act['Paladin'],
				'PRIEST' => ucfirst($addon['config']['acc_rec_priest']),
				'PRIEST_TXT' => $roster->locale->act['Priest'],
				'ROGUE' => ucfirst($addon['config']['acc_rec_rouge']),
				'ROGUE_TXT' => $roster->locale->act['Rogue'],
				'SHAMAN' => ucfirst($addon['config']['acc_rec_shaman']),
				'SHAMAN_TXT' => $roster->locale->act['Shaman'],
				'WARLOCK' => ucfirst($addon['config']['acc_rec_warlock']),
				'WARLOCK_TXT' => $roster->locale->act['Warlock'],
				'WARRIOR' => ucfirst($addon['config']['acc_rec_warrior']),
				'WARRIOR_TXT' => $roster->locale->act['Warrior'],
				'BORDER_END' => border('sred','end'),
				'MESSAGE' => (isset($error)) ? $error : "&nbsp;",
				)
			);

			$roster->tpl->set_filenames(array('user_recruitment' => $addon['basename'] . '/recruitment.html'));
			$roster->tpl->display('user_recruitment');

			return;
		}
		else
		{
			return;
		}
	}

	function createFormField($formelement, $label, $length = 25, $required = false)
	{
		global $roster, $addon, $user;

		$form_field = '<label for="' . $formelement . '">' . $label . "</label>\n";
		$form_field .= '  <input name="' . $formelement . '" type="text" size="' . $length . '" value="';
		if (isset($_REQUEST[$formelement]))
		{
			$form_field .= $_REQUEST[$formelement];
		}
		elseif (isset($this->$formelement) && !isset($_REQUEST[$formelement]))
		{
			$form_field .= $this->$formelement;
		}
		else
		{
			$form_field .= '';
		}
		$form_field .= ($required) ? "\">*<br />\n" : "\"><br />\n";
		return $form_field;		
	}
	
	function createTextArea($text_field, $label)
	{
		global $roster, $addon, $user;

		$textarea = '<label for="' . $text_field . '">' . $label . "</label>\n";
		$textarea .= '  <textarea name="' . $text_field . '">';
		if (isset($_REQUEST[$text_field]))
		{
			$textarea .= $_REQUEST[$text_field];
		}
		elseif (isset($this->$text_field))
		{
			$textarea .= $this->$text_field;
		}
		else
		{
			$textarea .= "";
		}
		$textarea .= "</textarea><br />\n";
		return $textarea;		
	}
	
	function saveProfileData($ident = '', $lname = '', $city = '', $country = '', $hp = '', $notes = '')
	{
		global $roster, $addon, $user;

		if ($_SESSION['is_rec'])
		{
			$sql = sprintf("UPDATE %s SET `lname` = %s `city` = %s, `country` = %s, `homepage` = %s, `notes` = %s, `last_change` = NOW() WHERE `uid` = %d AND `uname` = %s", $user->db['usertable'], $this->ins_string($lname), $this->ins_string($city), $this->ins_string($country), $this->ins_string($hp), $this->ins_string($notes), $this->ins_string($ident, 'int'), $_SESSION['user']);
		}
		else
		{
			$sql = sprintf("INSERT INTO %s (`uid`, `uname`, `lname`, `city`, `country`, `homepage`, `notes`, `last_change`) VALUES (NULL, %s, %s, %s, %s, %s, NOW())", $user->db['usertable'], $this->ins_string($ident, 'int'), $_SESSION['user'], $this->ins_string($lname), $this->ins_string($city), $this->ins_string($country), $this->ins_string($hp), $this->ins_string($notes));
		}
		if ($roster->db->query($sql) or die ($roster->db->errno() . ':' . $roster->db->error()))
		{
			$this->uid = (!$_SESSION['is_rec']) ? $roster->db->insert_id() : $ident;
			$this->message = $roster->locale->act['userount_profile']['msg2'];
		}
		else
		{
			$this->message = $roster->locale->act['userount_profile']['msg3'];
		}
	}
	
	function getProfileData()
	{
		global $roster, $addon, $user;

		$this->getUserInfo();
		$_SESSION['uid'] = $this->uid;
		$sql = sprintf("SELECT `uid`, `lname`, `city`, `country`, `homepage`, `notes` FROM %s WHERE `uname` = %s", $user->db['profileTable'], $this->user);
		$result = $roster->db->query($sql) or die ($roster->db->errno() . ':' . $roster->db->error());
		if ($roster->db->num_rows($result) == 0)
		{
			$_SESSION['is_rec'] = false;			
			$this->message = $roster->locale->act['userount_profile']['msg1'];
		}
		else
		{
			$_SESSION['is_rec'] = true; // this session acts like an extra control
			while ($obj = $roster->db->fetch($result))
			{
				$this->uid = $obj->uid;
				$this->userLName = $obj->lname;
				$this->city = $obj->city;
				$this->country = $obj->country;
				$this->homepage = $obj->homepage;
				$this->notes = $obj->notes;
			}
		}
	}

	function getConfigData($id)
	{
		global $roster, $addon, $user;

		$uid = $id;

		$query = 'SELECT '.
		'`user`.`id`, '.
		'`user`.`usr`, '.
		'`profile`.`uid`, '.
		'`profile`.`show_fname`, '.
		'`profile`.`show_lname`, '.
		'`profile`.`show_email`, '.
		'`profile`.`show_city`, '.
		'`profile`.`show_country`, '.
		'`profile`.`show_homepage`, '.
		'`profile`.`show_notes`, '.
		'`profile`.`show_joined`, '.
		'`profile`.`show_lastlogin`, '.
		'`profile`.`show_chars`, '.
		'`profile`.`show_guilds`, '.
		'`profile`.`show_realms`, '.
		'`profile`.`avsig_src` '.

		'FROM `'.$roster->db->table('user_members').'` AS user '.
		'LEFT JOIN `'.$roster->db->table('profile', 'user').'` AS profile ON `user`.`id` = `profile`.`uid` '.
		'WHERE `user`.`id` = "' . $uid . '" '.
		'ORDER BY `usr` ASC'.
		' LIMIT 15;';

		$result = $roster->db->query($query);

		if( !$result || $roster->db->num_rows($result) == 0 )
		{
			die_quietly("Cannot get configuration data from database<br />\nMySQL Said: " . $roster->db->error() . "<br /><br />\n");
		}

		while( $data = $roster->db->fetch($result, SQL_ASSOC) )
		{
			foreach( $data as $configData )
			{
				foreach( $data as $key => $value )
				{
					$this->configData[$key] = $value;
				}
			}
		}
		$roster->db->free_result($result);

		return;
	}

	function getMain($uid)
	{
		global $roster, $addon;

		$sql = 'SELECT `member_id` FROM `' . $roster->db->table('user_link', 'user') . '` WHERE `uid` = ' . $uid . ' AND `is_main` = 1';
		$query = $roster->db->query($sql);
		while($row = $roster->db->fetch($query))
		{
			if(!$query || $roster->db->num_rows($query) == 0)
			{
				return false;
			}
			$mid = $row['member_id'];
		}

		return $mid;
	}

	function setMain($uid, $mid)
	{
		global $roster, $addon, $user;
            //Unset previous main(s)
            $usql = 'UPDATE `' . $roster->db->table('user_link', 'user') . '` SET `is_main` = 0 WHERE `uid` = ' . $uid;
            $roster->db->query($usql);
            
            //Set New Main
		$sql = 'UPDATE `' . $roster->db->table('user_link', 'user') . '` SET `is_main` = 1 WHERE `uid` = ' . $uid . ' AND `member_id` = ' . $mid;
		$roster->db->query($sql);
		return;
	}

	function getAvSig($switch, $id = 0)
	{
		global $roster, $addon, $user;

		if($switch == 'av')
		{
			$case = 'avatar';
		}
		elseif($switch == 'sig')
		{
			$case = 'signature';
		}

		if($id > 0)
		{
			$uid = $id;
		}
		else
		{
			$uid = $user->session->getVal('uid');
		}

		switch ($case)
		{
		case 'signature':
			$sql = 'SELECT `signature` FROM `' . $roster->db->table('profile','user') . '` WHERE `uid` = ' . $uid;
			$result = $roster->db->query($sql);
			$sig = $roster->db->fetch($result);

			return urldecode($sig['signature']);
			break;
		case 'avatar':
			$sql = 'SELECT `avatar` FROM `' . $roster->db->table('profile','user') . '` WHERE `uid` = ' . $uid;
			$result = $roster->db->query($sql);
			$avatar = $roster->db->fetch($result);

			return urldecode($avatar['avatar']);
			break;
		default:
			$sql = 'SELECT `avatar` FROM `' . $roster->db->table('profile','user') . '` WHERE `uid` = ' . $uid;
			$result = $roster->db->query($sql);
			$avatar = $roster->db->fetch($result);

			return urldecode($avatar['avatar']);
			break;
		}
	}

	function setAvSig($id = '', $mid, $src = '')
	{
		global $roster, $addon, $user;

		//echo $id.' - '.$mid.'<br>';
		if(!is_null($id) || $id > 0)
		{
			$uid = $id;
		}
		else
		{
			$uid = $user->session->getVal('uid');
		}

		$user = $user->user->getUser($uid);

		if($mid > 0 && $mid != '')
		{
			$sql = 'SELECT `name` FROM `' . $roster->db->table('members') . '` WHERE `member_id` = ' . $mid;
			$query = $roster->db->query($sql);
			while($row = $roster->db->fetch($query))
			{
				$char = $row['name'];
			}
		}
		elseif($mid != '')
		{
			$char = $mid;
		} 

		$hasMain = $this->getMain($uid);

		if(is_null($src))
		{
			$this->getConfigData($uid);
			$src = $this->configData['avsig_src'];
		}

		$is_active = active_addon('siggen');

			$c = array('signature','avatar');
		foreach ($c as $case)
		{
		
			if($hasMain == false)
			{
				die_quietly("You do not have a main character set. Please set your main character and try again.");
			}
			else
			{
				if($is_active == 1 && $src == 'SigGen')
				{
					$sql = 'SELECT `region`, `realm` FROM `' . $roster->db->table('user_link', 'user') . '` WHERE `uid` = ' . $uid . ' AND `member_id` = ' . $mid;
					$query = $roster->db->query($sql);
					while($row = $roster->db->fetch($query))
					{
						$link = makelink('util-siggen-' . $case . '&amp;member=' . $char . '@' . $row['region'] . '-' . $row['realm'], 'url');
					}
				}
				elseif($is_active == 0 && $src == 'SigGen')
				{
					die_quietly("SigGen is not installed! Please install SigGen and try again.<br />");
				}
				elseif($src == 'default')
				{
					$sql = 'SELECT `race`, `sex` FROM `' . $roster->db->table('players') . '` WHERE `member_id` = ' . $mid;
					$query = $roster->db->query($sql);
					while($row = $roster->db->fetch($query))
					{
						$link = $addon['image_url'] . str_replace(' ', '', $row['race']) . '-' . $row['sex'] . '.png';
					}
				}
			}

			switch ($case)
			{
			case 'signature':
				$sql = 'UPDATE `' . $roster->db->table('profile','user') . '` SET `signature` = "' . urlencode($link) . '" WHERE `uid` = ' . $uid;
				$roster->db->query($sql);
				break;
			case 'avatar':
				$sql = 'UPDATE `' . $roster->db->table('profile','user') . '` SET `avatar` = "' . urlencode($link) . '" WHERE `uid` = ' . $uid;
				$roster->db->query($sql);
				break;
			default:
				$sql = 'UPDATE `' . $roster->db->table('profile','user') . '` SET `avatar` = "' . urlencode($link) . '" WHERE `uid` = ' . $uid;
				$roster->db->query($sql);
				break;
			}
		}
		//echo 'i worked<br>';
		return true;
	}

	function getGroup( $member )
	{
		global $roster, $addon, $user;

		if(is_int($member))
		{
			$uid = $member;
		}
		else
		{
			$uid = $user->user->getUser($member);
		}
		
		$id_sql = 'SELECT `group_id` FROM `' . $user->db['usertable'] . '` WHERE `uid` = ' . $uid;
		$id = $roster->db->query_first($id_sql);

		$name_sql = 'SELECT `name` FROM `' . $roster->db->table('account') .'` WHERE `account_id` = ' . $id;
		$group_name = $roster->db->query_first($name_sql);
		
		return $group_name;
	}

}
