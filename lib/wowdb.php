<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Database interface, character and guild updating and information retreiveal
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
*/

class wowdb
{
	var $db;			// Database resource id
	var $db_prefix;
	var $assignstr;		// Data to be inserted/updated to the db
	var $sqldebug;		//
	var $sqlstrings;	// Array of SQL strings passed to query()
	var $messages;		// Array of stored messages
	var $errors;		// Array of stored error messages
	var $membersadded=0;
	var $membersupdated=0;
	var $membersremoved=0;


	/**
	 * Connect to the database, and select it if $name is provided
	 *
	 * @param string $host MySQL server host name
	 * @param string $user MySQL server user name
	 * @param string $password MySQL server user password
	 * @param string $name MySQL server database name to select
	 * @param string $db_prefix MySQL table prefix
	 * @return bool
	 */
	function connect( $host, $user, $password, $name=null, $db_prefix='' )
	{
		$this->db = @mysql_connect($host, $user, $password);
		$this->db_prefix = $db_prefix;

		if( $this->db )
		{
			if ( !is_null($name) )
			{
				$db_selected = @mysql_select_db( $name,$this->db );
				if( $db_selected )
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}


	/**
	 * Front-end for SQL_query
	 *
	 * @param string $querystr
	 * @return mixed $result handle
	 */
	function query( $querystr )
	{
		$this->sqlstrings[] = $querystr;

		$result = @mysql_query($querystr,$this->db);
		return $result;
	}


	/**
	 * Get last SQL error
	 *
	 * @return string last SQL error
	 */
	function error()
	{
		$result = @mysql_errno($this->db).': '.mysql_error($this->db);
		return $result;
	}

	/**
	 * Get last SQL errno
	 *
	 * @return string last SQL errno
	 */
	function errno()
	{
		$result = @mysql_errno($this->db);
		return $result;
	}

	/**
	 * Front-end for SQL_fetch_assoc
	 *
	 * @param int $result handle
	 * @return array current db row
	 */
	function getrow( $result )
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$result && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		return mysql_fetch_assoc( $result );
	}


	/**
	 * Front-end for SQL_fetch_assoc
	 *
	 * @param int $result handle
	 * @return array current db row
	 */
	function fetch_assoc( $result )
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$result && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		return mysql_fetch_assoc( $result );
	}


	/**
	 * Front-end for SQL_fetch_array
	 *
	 * @param int $result handle
	 * @return array current db row
	 */
	function fetch_array( $result )
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$result && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		return mysql_fetch_array( $result );
	}


	/**
	 * Front-end for SQL_fetch_row
	 *
	 * @param int $result handle
	 * @return array current db row
	 */
	function fetch_row( $result )
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$result && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		return mysql_fetch_row( $result );
	}


	/**
	 * Front-end for SQL_num_rows
	 *
	 * @param int $result handle
	 * @return array current db row
	 */
	function num_rows( $result )
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$result && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		return mysql_num_rows( $result );
	}


	/**
	 * Front-end to escape string for safe inserting
	 *
	 * @param string $string
	 * @return string escaped
	 */
	function escape( $string )
	{
		if( version_compare( phpversion(), '4.3.0', '>' ) )
			return mysql_real_escape_string( $string );
		else
			return mysql_escape_string( $string );
	}


	/**
	 * Closes the DB connection
	 *
	 * @return unknown
	 */
	function closeDb()
	{
		// Closing connection
		if( is_resource($this->db) )
			return @mysql_close($this->db);
	}


	/**
	 * Frees SQL result memory
	 *
	 * @param int $query_id handle
	 */
	function closeQuery($query_id)
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$query_id && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		// Free resultset
		return @mysql_free_result($query_id);
	}


	/**
	 * Frees SQL result memory
	 *
	 * @param int $query_id handle
	 */
	function free_result($query_id)
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$query_id && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		// Free resultset
		return @mysql_free_result($query_id);
	}


	/**
	 * Returns number of rows affected by an INSERT, UPDATE, or DELETE operation
	 *
	 * @param int $query_id handle
	 */
	function affected_rows()
	{
		return @mysql_affected_rows($this->db);
	}


	/**
	 * Move result pointer
	 *
	 * @param int $result handle
	 * @param int $num row number
	 * @return bool
	 */
	function data_seek($result,$num)
	{
		global $roster_conf;

		// die quietly if debugging is on and we've got an invalid result. The page may
		// render correctly with just an error printed, so if debugging is off we don't die.
		if (!$result && $roster_conf['debug_mode'])
		{
			die_quietly('Invalid query result passed','Roster DB Layer');
		}

		return @mysql_data_seek($result, $num);
	}


	/**
	 * Get the ID generated from the previous INSERT operation
	 *
	 * @return int
	 */
	function insert_id()
	{
		return @mysql_insert_id($this->db);
	}


	/**
	 * Sets the SQL Debug output flag
	 *
	 * @param bool $sqldebug
	 */
	function setSQLDebug($sqldebug)
	{
		$this->sqldebug = $sqldebug;
	}


	/**
	 * Returns all messages
	 *
	 * @return string
	 */
	function getSQLStrings()
	{
		$sqlstrings = $this->sqlstrings;
		$output = '';
		if( is_array($sqlstrings) )
		{
			foreach($sqlstrings as $sql)
			{
				$output .= "$sql\n";
			}
		}
		return $output;
	}


	/**
	 * Adds a message to the $messages array
	 *
	 * @param string $message
	 */
	function setMessage($message)
	{
		$this->messages[] = $message;
	}


	/**
	 * Returns all messages
	 *
	 * @return string
	 */
	function getMessages()
	{
		$messages = $this->messages;
		$output = '';
		if( is_array($messages) )
		{
			foreach($messages as $message)
			{
				$output .= "$message\n";
			}
		}
		return $output;
	}


	/**
	 * Resets the stored messages
	 *
	 */
	function resetMessages()
	{
		$this->messages = null;
	}


	/**
	 * Adds an error to the $errors array
	 *
	 * @param string $message
	 */
	function setError($message,$error)
	{
		$this->errors[] = array($message=>$error);
	}


	/**
	 * Gets the errors in wowdb
	 * Return is based on $mode
	 *
	 * @param string $mode
	 * @return mixed
	 */
	function getErrors( $mode='' )
	{
		if( $mode == 'a')
		{
			return $this->errors;
		}

		$output = '';

		$errors = $this->errors;
		if( is_array($errors) )
		{
			$output = '<table width="100%" class="bodyline" cellspacing="0">';
			$steps = 0;
			foreach($errors as $errorArray)
			{
				foreach ($errorArray as $message => $error)
				{
					if ( $steps == 1 )
						$steps = 2;
					else
						$steps = 1;

					$output .= "<tr><td class=\"membersRowRight$steps\">$message<br />\n".
						"$error</td></tr>\n";
				}
			}
			$output .= '</table>';
		}
		return $output;
	}


	/**
	 * Expand base table name to a full table name
	 *
	 * @param string $table the base table name
	 * @param string $addon the name of the addon, empty for a base roster table
	 * @return string tablename as fit for MySQL queries
	 */
	function table($table, $addon='')
	{
		if( $addon)
		{
			return $this->db_prefix.'addons_'.$addon.($table != '' ? '_'.$table : '');
		}
		else
		{
			return $this->db_prefix.$table;
		}
	}


/************************
 * Updating Code
************************/


	/**
	 * Resets the SQL insert/update string holder
	 *
	 */
	function reset_values()
	{
		$this->assignstr = '';
	}


	/**
	 * Add a value to an INSERT or UPDATE SQL string
	 *
	 * @param string $row_name
	 * @param string $row_data
	 */
	function add_value( $row_name, $row_data )
	{
		if( $this->assignstr != '' )
			$this->assignstr .= ',';

		$row_data = "'" . $this->escape( $row_data ) . "'";

		$this->assignstr .= " `$row_name` = $row_data";
	}


	/**
	 * Add a time value to an INSERT or UPDATE SQL string
	 *
	 * @param string $row_name
	 * @param array $date
	 */
	function add_time( $row_name, $date )
	{
		// 2000-01-01 23:00:00.000
		$row_data = $date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':'.$date['seconds'];
		$this->add_value($row_name,$row_data);
	}


	/**
	 * Add a time value to an INSERT or UPDATE SQL string
	 *
	 * @param string $row_name
	 * @param string $date | UNIX TIMESTAMP
	 */
	function add_timestamp( $row_name, $date )
	{
		$date = date('Y-m-d H:i:s',$date);
		$this->add_value($row_name,$date);
	}


	/**
	 * Add a time value to an INSERT or UPDATE SQL string for PVP table
	 *
	 * @param string $row_name
	 * @param string $date
	 */
	function add_pvp2time( $row_name, $date )
	{
		$date_str = strtotime($date);
		$p2newdate = date('Y-m-d H:i:s',$date_str);
		$this->add_value($row_name,$p2newdate);
	}

	/**
	 * Add a rating (base, buff, debuff, total)
	 *
	 * @param string $row_name will be appended _d, _b, _c for debuff, buff, total
	 * @param string $data colon-separated data
	 */
	function add_rating( $row_name, $data )
	{
		$data = explode(':',$data);
		$data[0] = ( isset($data[0]) ? $data[0] : 0 );
		$data[1] = ( isset($data[1]) ? $data[1] : 0 );
		$data[2] = ( isset($data[2]) ? $data[2] : 0 );
		$this->add_value( $row_name, $data[0] );
		$this->add_value( $row_name.'_c', $data[0]+$data[1]+$data[2] );
		$this->add_value( $row_name.'_b', $data[1] );
		$this->add_value( $row_name.'_d', $data[2] );
	}

	function fix_icon($icon_name)
	{
		return strtolower(str_replace(' ','_',$icon_name));
	}

	/**
	 * Format tooltips for insertion to the db
	 *
	 * @param mixed $tipdata
	 * @return string
	 */
	function tooltip( $tipdata )
	{
		$tooltip = '';

		if( is_array( $tipdata ) )
		{
			$tooltip = implode("\n",$tipdata);
		}
		else
		{
			$tooltip = str_replace('<br>',"\n",$tipdata);;
		}
		return $tooltip;
	}


	/**
	 * Inserts an item into the datbase
	 *
	 * @param string $item
	 * @return bool
	 */
	function insert_item( $item,$locale )
	{
		global $wordings;

		$this->reset_values();
		$this->add_value('member_id', $item['member_id'] );
		$this->add_value('item_name', $item['item_name'] );
		$this->add_value('item_parent', $item['item_parent'] );
		$this->add_value('item_slot', $item['item_slot'] );
		$this->add_value('item_color', $item['item_color'] );
		$this->add_value('item_id', $item['item_id'] );
		$this->add_value('item_texture', $item['item_texture'] );
		$this->add_value('item_tooltip', $item['item_tooltip'] );

		if( preg_match($wordings[$locale]['requires_level'],$item['item_tooltip'],$level))
			$this->add_value('level',$level[1]);

		$this->add_value('item_quantity', $item['item_quantity'] );

		$querystr = "INSERT INTO `".ROSTER_ITEMSTABLE."` SET ".$this->assignstr;
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Item ['.$item['item_name'].'] could not be inserted',$this->error());
		}
	}


	/**
	 * Inserts mail into the Database
	 *
	 * @param array $mail
	 */
	function insert_mail( $mail )
	{
		$this->reset_values();
		$this->add_value('member_id', $mail['member_id'] );
		$this->add_value('mailbox_slot', $mail['mail_slot'] );
		$this->add_value('mailbox_coin', $mail['mail_coin'] );
		$this->add_value('mailbox_coin_icon', $mail['mail_coin_icon'] );
		$this->add_value('mailbox_days', $mail['mail_days'] );
		$this->add_value('mailbox_sender', $mail['mail_sender'] );
		$this->add_value('mailbox_subject', $mail['mail_subject'] );
		$this->add_value('item_icon', $mail['item_icon'] );
		$this->add_value('item_name', $mail['item_name'] );
		$this->add_value('item_tooltip', $mail['item_tooltip'] );
		$this->add_value('item_color', $mail['item_color'] );

		if( isset( $mail['item_quantity'] ) )
			$this->add_value('item_quantity', $mail['item_quantity'] );

		$querystr = "INSERT INTO `".ROSTER_MAILBOXTABLE."` SET ".$this->assignstr;
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Mail ['.$mail['mail_subject'].'] could not be inserted',$this->error());
		}
	}


	/**
	 * Inserts a quest into the Databse
	 *
	 * @param array $quest
	 */
	function insert_quest( $quest )
	{
		$this->reset_values();
		$this->add_value('member_id', $quest['member_id'] );
		$this->add_value('quest_name', $quest['quest_name'] );
		$this->add_value('quest_index', $quest['quest_index'] );
		$this->add_value('quest_level', $quest['quest_level'] );
		$this->add_value('zone', $quest['zone'] );
		$this->add_value('quest_tag', $quest['quest_tag'] );
		$this->add_value('is_complete', $quest['is_complete'] );

		$querystr = "INSERT INTO `".ROSTER_QUESTSTABLE."` SET ".$this->assignstr;
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Quest ['.$quest['quest_name'].'] could not be inserted',$this->error());
		}
	}


	/**
	 * Inserts a recipe into the Database
	 *
	 * @param array $recipe
	 * @param string $locale
	 */
	function insert_recipe( $recipe,$locale )
	{
		global $wordings;

		$this->reset_values();
		$this->add_value('member_id', $recipe['member_id'] );
		$this->add_value('recipe_name', $recipe['recipe_name'] );
		$this->add_value('recipe_type', $recipe['recipe_type'] );
		$this->add_value('skill_name', $recipe['skill_name'] );
		$this->add_value('difficulty', $recipe['difficulty'] );
		$this->add_value('item_color', $recipe['item_color'] );
		$this->add_value('reagents', $recipe['reagents'] );
		$this->add_value('recipe_texture', $recipe['recipe_texture'] );

		$this->add_value('recipe_tooltip', $recipe['recipe_tooltip'] );

		if( preg_match($wordings[$locale]['requires_level'],$recipe['recipe_tooltip'],$level))
			$this->add_value('level',$level[1]);

		$querystr = "INSERT INTO `".ROSTER_RECIPESTABLE."` SET ".$this->assignstr;
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Recipe ['.$recipe['recipe_name'].'] could not be inserted',$this->error());
		}
	}


	/**
	 * Update Memberlog function
	 *
	 */
	function updateMemberlog( $data , $type , $timestamp )
	{
		$this->reset_values();
		$this->add_value('member_id', $data['member_id'] );
		$this->add_value('name', $data['name'] );
		$this->add_value('guild_id', $data['guild_id'] );
		$this->add_value('class', $data['class'] );
		$this->add_value('level', $data['level'] );
		$this->add_value('note', $data['note'] );
		$this->add_value('guild_rank', $data['guild_rank'] );
		$this->add_value('guild_title', $data['guild_title'] );
		$this->add_value('officer_note', $data['officer_note'] );
		$this->add_time('update_time', getDate($timestamp) );
		$this->add_value('type', $type );

		$querystr = "INSERT INTO `".ROSTER_MEMBERLOGTABLE."` SET ".$this->assignstr;
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Member Log ['.$data['name'].'] could not be inserted',$this->error());
		}
	}


	/**
	 * Formats quest data to be inserted to the db
	 *
	 * @param array $quest_data
	 * @param int $memberId
	 * @param string $zone
	 * @return array
	 */
	function make_quest( $quest_data, $memberId, $zone, $slot )
	{
		$quest = array();
		$quest['member_id'] = $memberId;
		$quest['quest_name'] = $quest_data['Title'];

		//Fix quest name if too many 'quest' addons cause level number to be added to title
		while(substr($quest['quest_name'],0,1) == '[')
		{
			$quest['quest_name'] = ltrim(substr($quest['quest_name'],strpos($quest['quest_name'],']')+1));
		}
		$quest['quest_tag'] = (isset($quest_data['Tag']) ? $quest_data['Tag'] : '');
		$quest['quest_index'] = $slot;
		$quest['quest_level'] = $quest_data['Level'];
		$quest['zone'] = $zone;

		if( isset($quest_data['Complete']) )
			$quest['is_complete'] = $quest_data['Complete'];
		else
			$quest['is_complete'] = 0;

		return $quest;
	}


	/**
	 * Formats mail data to be inserted to the db
	 *
	 * @param array $mail_data
	 * @param int $memberId
	 * @param string $slot_num
	 * @return array
	 */
	function make_mail( $mail_data, $memberId, $slot_num )
	{
		$mail = array();
		$mail['member_id'] = $memberId;
		$mail['mail_slot'] = $slot_num;
		$mail['mail_coin'] = $mail_data['Coin'];
		$mail['mail_coin_icon'] = $this->fix_icon($mail_data['CoinIcon']);
		$mail['mail_days'] = $mail_data['Days'];
		$mail['mail_sender'] = $mail_data['Sender'];
		$mail['mail_subject'] = $mail_data['Subject'];

		if( isset($mail_data['Item']) )
		{
			$item = $mail_data['Item'];

			$mail['item_icon'] = $this->fix_icon($item['Icon']);
			$mail['item_name'] = $item['Name'];
			$mail['item_color'] = $item['Color'];

			if( isset( $item['Quantity'] ) )
				$mail['item_quantity'] = $item['Quantity'];
			else
				$item['item_quantity'] = 1;

			if( !empty($item['Tooltip']) )
				$mail['item_tooltip'] = $this->tooltip( $item['Tooltip'] );
			else
				$mail['item_tooltip'] = $item['Name'];
		}
		return $mail;
	}


	/**
	 * Formats item data to be inserted into the db
	 *
	 * @param array $item_data
	 * @param int $memberId
	 * @param string $parent
	 * @param string $slot_name
	 * @return array
	 */
	function make_item( $item_data, $memberId, $parent, $slot_name )
	{
		$item = array();
		$item['member_id'] = $memberId;
		$item['item_name'] = $item_data['Name'];
		$item['item_parent'] = $parent;
		$item['item_slot'] = $slot_name;
		$item['item_color'] = ( isset($item_data['Color']) ? $item_data['Color'] : 'ffffff' );
		$item['item_id'] = ( isset($item_data['Item']) ? $item_data['Item'] : '0:0:0:0:0:0:0:0' );
		$item['item_texture'] = ( isset($item_data['Icon']) ? $this->fix_icon($item_data['Icon']) : 'inv_misc_questionmark');

		if( isset( $item_data['Quantity'] ) )
			$item['item_quantity'] = $item_data['Quantity'];
		else
			$item['item_quantity'] = 1;

		if( !empty($item_data['Tooltip']) )
			$item['item_tooltip'] = $this->tooltip( $item_data['Tooltip'] );
		else
			$item['item_tooltip'] = $item_data['Name'];

		return $item;
	}


	/**
	 * Formats recipe data to be inserted into the db
	 *
	 * @param array $recipe_data
	 * @param int $memberId
	 * @param string $parent
	 * @param string $recipe_type
	 * @param string $recipe_name
	 * @return array
	 */
	function make_recipe( $recipe_data, $memberId, $parent, $recipe_type, $recipe_name )
	{
		$recipe = array();
		$recipe['member_id'] = $memberId;
		$recipe['recipe_name'] = $recipe_name;
		$recipe['recipe_type'] = $recipe_type;
		$recipe['skill_name'] = $parent;
		$recipe['difficulty'] = $recipe_data['Difficulty'];
		$recipe['item_color'] = isset($recipe_data['Color']) ? $recipe_data['Color'] : '';

		$recipe['reagents'] = '';
		foreach( $recipe_data['Reagents'] as $reagent )
		{
			$recipe['reagents'] .= $reagent['Name'].' [x'.$reagent['Count'].']<br>';
		}
		$recipe['reagents'] = substr($recipe['reagents'],0,-4);

		$recipe['recipe_texture'] = $this->fix_icon($recipe_data['Icon']);

		if( !empty($recipe_data['Tooltip']) )
			$recipe['recipe_tooltip'] = $this->tooltip( $recipe_data['Tooltip'] );
		else
			$recipe['recipe_tooltip'] = $recipe_name;

		return $recipe;
	}


	/**
	 * Handles formating and insertion of buff data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_buffs( $data, $memberId )
	{
		if(isset($data['Attributes']['Buffs']))
		{
			$buffs = $data['Attributes']['Buffs'];
		}

		if( !empty($buffs) && is_array($buffs) )
		{
			// Delete the stale data
			$querystr = "DELETE FROM `".ROSTER_BUFFSTABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Buffs could not be deleted',$this->error());
				return;
			}
			// Then process quests
			$buffsnum = 0;
			foreach( $buffs as $buff )
			{
				if( is_null($buff) || !is_array($buff) || empty($buff) )
				{
					continue;
				}
				$this->reset_values();

				$this->add_value('member_id', $memberId );
				$this->add_value('name', $buff['Name'] );

				if( isset( $buff['Icon'] ) )
					$this->add_value('icon', $this->fix_icon($buff['Icon']) );

				if( isset( $buff['Rank'] ) )
					$this->add_value('rank', $buff['Rank'] );

				if( isset( $buff['Rank'] ) )
					$buff_rank = $buff['Rank'];

				if( isset( $buff['Count'] ) )
					$this->add_value('count', $buff['Count'] );

				if( !empty($buff['Tooltip']) )
					$this->add_value('tooltip', $this->tooltip( $buff['Tooltip'] ) );
				else
					$this->add_value('tooltip', $buff['Name'] );

				$querystr = "INSERT INTO `".ROSTER_BUFFSTABLE."` SET ".$this->assignstr;
				$result = $this->query($querystr);
				if( !$result )
				{
					$this->setError('Buff ['.$buff['Name'].'] could not be inserted',$this->error());
				}

				$buffsnum++;
			}
			$this->setMessage('<li>Updating Buffs: '.$buffsnum.'</li>');
	   	}
		else
		{
			$this->setMessage('<li>No Buffs</li>');
		}
	}


	/**
	 * Handles formating and insertion of quest data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_quests( $data, $memberId )
	{
		if(isset($data['Quests']))
		{
			$quests = $data['Quests'];
		}

		if( !empty($quests) && is_array($quests) )
		{
			// Delete the stale data
			$querystr = "DELETE FROM `".ROSTER_QUESTSTABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Quests could not be deleted',$this->error());
				return;
			}
			// Then process quests
			$questnum = 0;
			foreach( array_keys($quests) as $zone )
			{
				$zoneInfo = $quests[$zone];
				foreach( array_keys($zoneInfo) as $slot)
				{
					$slotInfo = $zoneInfo[$slot];
					if( is_null($slotInfo) || !is_array($slotInfo) || empty($slotInfo) )
					{
						continue;
					}
					$item = $this->make_quest( $slotInfo, $memberId, $zone, $slot );
					$this->insert_quest( $item );
					$questnum++;
				}
			}
			$this->setMessage('<li>Updating Quests: '.$questnum.'</li>');
	   	}
		else
		{
			$this->setMessage('<li>No Quest Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of recipe data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_recipes( $data, $memberId )
	{
		if(isset($data['Professions']))
		{
			$prof = $data['Professions'];
		}

		if( !empty($prof) && is_array($prof) )
		{
			$messages = '<li>Updating Professions';

			// Delete the stale data
			$querystr = "DELETE FROM `".ROSTER_RECIPESTABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Professions could not be deleted',$this->error());
				return;
			}
			// Then process Professions
			foreach( array_keys($prof) as $skill_name )
			{
				$messages .= " : $skill_name";

				$skill = $prof[$skill_name];
				foreach( array_keys( $skill) as $recipe_type )
				{
					$item = $skill[$recipe_type];
					foreach(array_keys($item) as $recipe_name)
					{
						$recipeDetails = $item[$recipe_name];
						if( is_null($recipeDetails) || !is_array($recipeDetails) || empty($recipeDetails) )
						{
							continue;
						}
						$recipe = $this->make_recipe( $recipeDetails, $memberId, $skill_name, $recipe_type, $recipe_name );
						$this->insert_recipe( $recipe,$data['Locale'] );
					}
				}
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Recipe Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of equipment data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_equip( $data, $memberId )
	{
		// Update Equipment Inventory
		$equip = $data['Equipment'];
		if( !empty($equip) && is_array($equip) )
		{
			$messages = '<li>Updating Equipment ';

			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '$memberId' AND `item_parent` = 'equip'";
			if( !$this->query($querystr) )
			{
				$this->setError('Equipment could not be deleted',$this->error());
				return;
			}
			foreach( array_keys($equip) as $slot_name )
			{
				$messages .= '.';

				$slot = $equip[$slot_name];
				if( is_null($slot) || !is_array($slot) || empty($slot) )
				{
					continue;
				}
				$item = $this->make_item( $slot, $memberId, 'equip', $slot_name );
				$this->insert_item( $item,$data['Locale'] );
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Equipment Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of inventory data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_inventory( $data, $memberId )
	{
		// Update Bag Inventory
		$inv = $data['Inventory'];
		if( !empty($inv) && is_array($inv) )
		{
			$messages = '<li>Updating Inventory';

			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '$memberId' AND UPPER(`item_parent`) LIKE 'BAG%' AND `item_parent` != 'bags'";
			if( !$this->query($querystr) )
			{
				$this->setError('Inventory could not be deleted',$this->error());
				return;
			}

			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '$memberId' AND `item_parent` = 'bags' AND UPPER(`item_slot`) LIKE 'BAG%'";
			if( !$this->query($querystr) )
			{
				$this->setError('Inventory could not be deleted',$this->error());
				return;
			}

			foreach( array_keys( $inv ) as $bag_name )
			{
				$messages .= " : $bag_name";

				$bag = $inv[$bag_name];
				if( is_null($bag) || !is_array($bag) || empty($bag) )
				{
					continue;
				}
				$item = $this->make_item( $bag, $memberId, 'bags', $bag_name );

				// quantity for a bag means number of slots it has
				$item['item_quantity'] = $bag['Slots'];
				$this->insert_item( $item,$data['Locale'] );
				if (isset($bag['Contents']) && is_array($bag['Contents']))
				{
					foreach( array_keys( $bag['Contents'] ) as $slot_name )
					{
						$slot = $bag['Contents'][$slot_name];
						if( is_null($slot) || !is_array($slot) || empty($slot) )
						{
							continue;
						}
						$item = $this->make_item( $slot, $memberId, $bag_name, $slot_name );
						$this->insert_item( $item,$data['Locale'] );
					}
				}
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Inventory Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of bank data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_bank( $data, $memberId )
	{
		// Update Bank Inventory
		if(isset($data['Bank']))
		{
			$inv = $data['Bank'];
		}

		if( !empty($inv) && is_array($inv) )
		{
			$messages = '<li>Updating Bank';

			// Clearing out old items
			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '$memberId' AND UPPER(`item_parent`) LIKE 'BANK%'";
			if( !$this->query($querystr) )
			{
				$this->setError('Bank could not be deleted',$this->error());
				return;
			}

			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '$memberId' AND `item_parent` = 'bags' AND UPPER(`item_slot`) LIKE 'BANK%'";
			if( !$this->query($querystr) )
			{
				$this->setError('Bank could not be deleted',$this->error());
				return;
			}

			foreach( array_keys( $inv ) as $bag_name )
			{
				$messages .= " : $bag_name";

				$bag = $inv[$bag_name];
				if( is_null($bag) || !is_array($bag) || empty($bag) )
				{
					continue;
				}

				$dbname = 'Bank '.$bag_name;
				$item = $this->make_item( $bag, $memberId, 'bags', $dbname );

				// Fix bank bag icon
				if( $bag_name == 'Bag0' )
				{
					$item['item_texture'] = 'inv_misc_bag_15';
				}

				// quantity for a bag means number of slots it has
				$item['item_quantity'] = $bag['Slots'];
				$this->insert_item( $item,$data['Locale'] );

				if (isset($bag['Contents']) && is_array($bag['Contents']))
				{
					foreach( array_keys( $bag['Contents'] ) as $slot_name )
					{
						$slot = $bag['Contents'][$slot_name];
						if( is_null($slot) || !is_array($slot) || empty($slot) )
						{
							continue;
						}
						$item = $this->make_item( $slot, $memberId, $dbname, $slot_name );
						$this->insert_item( $item,$data['Locale'] );
					}
				}
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Bank Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of mailbox data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_mailbox( $data, $memberId )
	{

		if(isset($data['MailBox']))
		{
			$mailbox = $data['MailBox'];
		}

		// If maildate is newer than the db value, wipe all mail from the db
		//if(  )
		//{
			$querystr = "DELETE FROM `".ROSTER_MAILBOXTABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Mail could not be deleted',$this->error());
				return;
			}
		//}

		if( !empty($mailbox) && is_array($mailbox) )
		{
			foreach( array_keys($mailbox) as $slot_num )
			{
				$slot = $mailbox[$slot_num];
				if( is_null($slot) || !is_array($slot) || empty($slot) )
				{
					continue;
				}
				$mail = $this->make_mail( $slot, $memberId, $slot_num );
				$this->insert_mail( $mail );
			}
			$this->setMessage('<li>Updating Mailbox: '.count($mailbox).'</li>');
		}
		else
		{
			$this->setMessage('<li>No New Mail</li>');
		}
	}


	/**
	 * Handles formating and insertion of rep data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_reputation( $data, $memberId )
	{
		if(isset($data['Reputation']))
		{
			$repData = $data['Reputation'];
		}

		if( !empty($repData) && is_array($repData) )
		{
			$messages = '<li>Updating Reputation ';

			//first delete the stale data
			$querystr = "DELETE FROM `".ROSTER_REPUTATIONTABLE."` WHERE `member_id` = '$memberId'";

			if( !$this->query($querystr) )
			{
				$this->setError('Reputation could not be deleted',$this->error());
				return;
			}

			$count = $repData['Count'];

			foreach( array_keys( $repData ) as $factions )
			{
				$faction_name = $repData[$factions];
				if ($faction_name != $count)
				{
					foreach( array_keys( $faction_name ) as $faction )
					{
						$this->reset_values();
						if( !empty($memberId) )
							$this->add_value('member_id', $memberId );
						if( !empty($factions) )
							$this->add_value('faction', $factions );
						if( !empty($faction) )
							$this->add_value('name', $faction );
						if( !empty($repData[$factions][$faction]['Value']) )
						{
							list($level, $max) = explode(':',$repData[$factions][$faction]['Value']);
							$this->add_value('curr_rep', $level );
							$this->add_value('max_rep', $max );
						}
						if( !empty($repData[$factions][$faction]['AtWar']) )
							$this->add_value('AtWar', $repData[$factions][$faction]['AtWar'] );
						if( !empty($repData[$factions][$faction]['Standing']) )
							$this->add_value('Standing', $repData[$factions][$faction]['Standing']);

						$messages .= '.';

						$querystr = "INSERT INTO `".ROSTER_REPUTATIONTABLE."` SET ".$this->assignstr;

						$result = $this->query($querystr);
						if( !$result )
						{
							$this->setError('Reputation for '.$faction.' could not be inserted',$this->error());
						}
					}
				}
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Reputation Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of skills data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_skills( $data, $memberId )
	{
		if(isset($data['Skills']))
		{
			$skillData = $data['Skills'];
		}

		if( !empty($skillData) && is_array($skillData) )
		{
			$messages = '<li>Updating Skills ';

			//first delete the stale data
			$querystr = "DELETE FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` = '$memberId'";

			if( !$this->query($querystr) )
			{
				$this->setError('Skills could not be deleted',$this->error());
				return;
			}

			foreach( array_keys( $skillData ) as $skill_type )
			{
				$sub_skill = $skillData[$skill_type];
				$order = $sub_skill['Order'];
				foreach( array_keys( $sub_skill ) as $skill_name )
				{
					if( $skill_name != 'Order' )
					{
						$this->reset_values();
						if( !empty($memberId) )
							$this->add_value('member_id', $memberId );
						if( !empty($skill_type) )
							$this->add_value('skill_type', $skill_type );
						if( !empty($skill_name) )
							$this->add_value('skill_name', $skill_name );
						if( !empty($order) )
							$this->add_value('skill_order', $order );
						if( !empty($sub_skill[$skill_name]) )
							$this->add_value('skill_level', $sub_skill[$skill_name] );

						$messages .= '.';

						$querystr = "INSERT INTO `".ROSTER_SKILLSTABLE."` SET ".$this->assignstr;

						$result = $this->query($querystr);
						if( !$result )
						{
							$this->setError('Skill ['.$skill_name.'] could not be inserted',$this->error());
						}
					}
				}
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Skill Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of spellbook data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_spellbook( $data, $memberId )
	{
		if(isset($data['SpellBook']))
		{
			$spellbook = $data['SpellBook'];
		}

		if( !empty($spellbook) && is_array($spellbook) )
		{
			$messages = '<li>Updating Spellbook';

			// first delete the stale data
			$querystr = "DELETE FROM `".ROSTER_SPELLTABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Spells could not be deleted',$this->error());
				return;
			}

			// then process Spellbook Tree
			$querystr = "DELETE FROM `".ROSTER_SPELLTREETABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Spell Trees could not be deleted',$this->error());
				return;
			}

			// then process spellbook
			foreach( array_keys( $spellbook ) as $spell_type )
			{
				$messages .= " : $spell_type";

				$data_spell_type = $spellbook[$spell_type];
				foreach( array_keys( $data_spell_type ) as $spell )
				{
					$data_spell = $data_spell_type[$spell];

					if( is_array($data_spell) )
					{
						foreach( array_keys( $data_spell ) as $spell_name )
						{
							$data_spell_name = $data_spell[$spell_name];

							$this->reset_values();
							if( !empty($memberId) )
								$this->add_value('member_id', $memberId );
							if( !empty($spell_type) )
								$this->add_value('spell_type', $spell_type );
							if( !empty($spell_name) )
								$this->add_value('spell_name', $spell_name );
							if( !empty($data_spell_name['Icon']) )
								$this->add_value('spell_texture', $this->fix_icon($data_spell_name['Icon']) );
							if( !empty($data_spell_name['Rank']) )
								$this->add_value('spell_rank', $data_spell_name['Rank'] );

							if( !empty($data_spell_name['Tooltip']) )
							{
								$this->add_value('spell_tooltip', $this->tooltip($data_spell_name['Tooltip']) );
							}
							elseif( !empty($spell_name) || !empty($data_spell_name['Rank']) )
							{
								$this->add_value('spell_tooltip', $spell_name."\n".$data_spell_name['Rank'] );
							}

							$querystr = "INSERT INTO `".ROSTER_SPELLTABLE."` SET ".$this->assignstr;
							$result = $this->query($querystr);
							if( !$result )
							{
								$this->setError('Spell ['.$spell_name.'] could not be inserted',$this->error());
							}
						}
					}
				}
				$this->reset_values();
				$this->add_value('member_id', $memberId );
				$this->add_value('spell_type', $spell_type );
				$this->add_value('spell_texture', $this->fix_icon($data_spell_type['Icon']) );

				$querystr = "INSERT INTO `".ROSTER_SPELLTREETABLE."` SET ".$this->assignstr;
				$result = $this->query($querystr);
				if( !$result )
				{
					$this->setError('Spell Tree ['.$spell_type.'] could not be inserted',$this->error());
				}
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Spellbook Data</li>');
		}
	}


	/**
	 * Handles formating and insertion of spellbook data
	 *
	 * @param array $data
	 * @param int $petID
	 */
	function do_pet_spellbook( $data , $memberId , $petID )
	{
		$spellbook = $data['SpellBook']['Spells'];

		if( !empty($spellbook) && is_array($spellbook) )
		{
			$messages = '<ul><li>Updating Spellbook';

			// first delete the stale data
			$querystr = "DELETE FROM `".ROSTER_PETSPELLTABLE."` WHERE `pet_id` = '$petID'";
			if( !$this->query($querystr) )
			{
				$this->setError('Spells could not be deleted',$this->error());
				return;
			}

			// then process spellbook

			foreach( array_keys( $spellbook ) as $spell )
			{
				$messages .= '.';
				$data_spell = $spellbook[$spell];

				if( is_array($data_spell) )
				{
					$this->reset_values();
					$this->add_value('member_id', $memberId );
					$this->add_value('pet_id', $petID );
					$this->add_value('spell_name', $spell );
					$this->add_value('spell_texture', $this->fix_icon($data_spell['Icon']) );
					$this->add_value('spell_rank', $data_spell['Rank'] );

					if( !empty($data_spell['Tooltip']) )
					{
						$this->add_value('spell_tooltip', $this->tooltip($data_spell['Tooltip']) );
					}
					elseif( !empty($spell) || !empty($data_spell['Rank']) )
					{
						$this->add_value('spell_tooltip', $spell."\n".$data_spell['Rank'] );
					}

					$querystr = "INSERT INTO `".ROSTER_PETSPELLTABLE."` SET ".$this->assignstr;
					$result = $this->query($querystr);
					if( !$result )
					{
						$this->setError('Spell ['.$spell_name.'] could not be inserted',$this->error());
					}
				}
			}

			$this->setMessage($messages.'</li></ul></li>');
		}
		else
		{
			$this->setMessage('<ul><li>No Spellbook Data</li></ul></li>');
		}
	}


	/**
	 * Handles formating and insertion of talent data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_talents( $data, $memberId )
	{
		if(isset($data['Talents']))
		{
			$talentData = $data['Talents'];
		}

		if( !empty($talentData) && is_array($talentData) )
		{
			$messages = '<li>Updating Talents';

			// first delete the stale data
			$querystr = "DELETE FROM `".ROSTER_TALENTSTABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Talents could not be deleted',$this->error());
				return;
			}

			// then process Talents
			$querystr = "DELETE FROM `".ROSTER_TALENTTREETABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Talent Trees could not be deleted',$this->error());
				return;
			}

			// Update Talents
			foreach( array_keys( $talentData ) as $talent_tree )
			{
				$messages .= " : $talent_tree";

				$data_talent_tree = $talentData[$talent_tree];
				foreach( array_keys( $data_talent_tree ) as $talent_skill )
				{
					$data_talent_skill = $data_talent_tree[$talent_skill];
					if( $talent_skill == 'Order' )
					{
						$tree_order = $data_talent_skill;
					}
					elseif ( $talent_skill == 'PointsSpent' )
					{
						$tree_pointsspent = $data_talent_skill;
					}
					elseif ( $talent_skill == 'Background')
					{
						$tree_background = $data_talent_skill;
					}
					else
					{
						$this->reset_values();
						if( !empty($memberId) )
							$this->add_value('member_id', $memberId );
						if( !empty($talent_skill) )
							$this->add_value('name', $talent_skill );
						if( !empty($talent_tree) )
							$this->add_value('tree', $talent_tree );

						if( !empty($data_talent_skill['Tooltip']) )
							$this->add_value('tooltip', $this->tooltip($data_talent_skill['Tooltip']) );
						else
							$this->add_value('tooltip', $talent_skill );

						if( !empty($data_talent_skill['Icon']) )
							$this->add_value('texture', $this->fix_icon($data_talent_skill['Icon']) );

						$this->add_value('row', substr($data_talent_skill['Location'], 0, 1) );
						$this->add_value('column', substr($data_talent_skill['Location'], 2, 1) );
						$this->add_value('rank', substr($data_talent_skill['Rank'], 0, 1) );
						$this->add_value('maxrank', substr($data_talent_skill['Rank'], 2, 1) );

						$querystr = "INSERT INTO `".ROSTER_TALENTSTABLE."` SET ".$this->assignstr;
						$result = $this->query($querystr);
						if( !$result )
						{
							$this->setError('Talent ['.$talent_skill.'] could not be inserted',$this->error());
						}
					}
				}
				$this->reset_values();
				if( !empty($memberId) )
					$this->add_value('member_id', $memberId );
				if( !empty($talent_tree) )
					$this->add_value('tree', $talent_tree );
				if( !empty($tree_background) )
					$this->add_value('background', $this->fix_icon($tree_background) );
				if( !empty($tree_pointsspent) )
					$this->add_value('pointsspent', $tree_pointsspent );
				if( !empty($tree_order) )
					$this->add_value('order', $tree_order );

				$querystr = "INSERT INTO `".ROSTER_TALENTTREETABLE."` SET ".$this->assignstr;
				$result = $this->query($querystr);
				if( !$result )
				{
					$this->setError('Talent Tree ['.$talent_tree.'] could not be inserted',$this->error());
				}
			}
			$this->setMessage($messages.'</li>');
		}
		else
		{
			$this->setMessage('<li>No Talents Data</li>');
		}
	}


	/**
	 * Delete Members in database using inClause
	 * (comma separated list of member_id's to delete)
	 *
	 * @param string $inClause
	 */
	function deleteMembers( $inClause )
	{
		$messages = '<li>';

		$messages .= 'Character Data..';
		$querystr = "DELETE FROM `".ROSTER_MEMBERSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Member Data could not be deleted',$this->error());
		}

		$querystr = "DELETE FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Player Data could not be deleted',$this->error());
		}


		$messages .= 'Skills..';
		$querystr = "DELETE FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Skill Data could not be deleted',$this->error());
		}


		$messages .= 'Items..';
		$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Items Data could not be deleted',$this->error());
		}


		$messages .= 'Quests..';
		$querystr = "DELETE FROM `".ROSTER_QUESTSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Quest Data could not be deleted',$this->error());
		}


		$messages .= 'PvPLog Data..';
		$querystr = "DELETE FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('PvPLog Data could not be deleted',$this->error());
		}


		$messages .= 'Professions..';
		$querystr = "DELETE FROM `".ROSTER_RECIPESTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Recipe Data could not be deleted',$this->error());
		}


		$messages .= 'Talents..';
		$querystr = "DELETE FROM `".ROSTER_TALENTSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Talent Data could not be deleted',$this->error());
		}

		$querystr = "DELETE FROM `".ROSTER_TALENTTREETABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Talent Tree Data could not be deleted',$this->error());
		}


		$messages .= 'Spellbook..';
		$querystr = "DELETE FROM `".ROSTER_SPELLTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Spell Data could not be deleted',$this->error());
		}

		$querystr = "DELETE FROM `".ROSTER_SPELLTREETABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Spell Tree Data could not be deleted',$this->error());
		}


		$messages .= 'Pets..';
		$querystr = "DELETE FROM `".ROSTER_PETSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Pet Data could not be deleted',$this->error());
		}


		$messages .= 'Reputation..';
		$querystr = "DELETE FROM `".ROSTER_REPUTATIONTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Reputation Data could not be deleted',$this->error());
		}


		$messages .= 'Mail..';
		$querystr = "DELETE FROM `".ROSTER_MAILBOXTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Mail Data could not be deleted',$this->error());
		}

		$this->setMessage($messages.'</li>');
	}


	/**
	 * Removes guild members with `active` = 0
	 *
	 * @param int $guild_id
	 * @param string $timestamp
	 */
	function remove_guild_members( $guild_id , $timestamp )
	{
		$querystr = "SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = '$guild_id' AND `active` = '0'";

		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Members could not be selected for deletion',$this->error());
			return;
		}

		$num = $this->num_rows($result);

		$inClause = '';
		if ($num > 0)
		{
			while ( $row = $this->fetch_array($result) )
			{
				if ($inClause != '')
					$inClause .= ',';

				$inClause .= $row[0];
				$this->setMessage('<li><span class="red">[</span> '.$row[1].' <span class="red">] - Deleted</span></li>');
				$this->setMemberLog($row,0,$timestamp);

			}

			$this->setMessage('<li><span class="red">Deleted '.$num.' member'.($num > 1 ? 's' : '').'</span>');
			$this->setMessage('<ul>');

			// now that we have our inclause, time to do some deletes
			$this->deleteMembers($inClause);

			$this->setMessage('</ul></li>');
		}
		$this->closeQuery($result);
	}

	/**
	 * Removes members that do not match current guild_id
	 *
	 * @param int $guild_id
	 * @param string $timestamp
	 */
	function remove_guild_members_id( $guild_id , $timestamp )
	{
		// Get a list of guild id's in the guild table to remove
		$querystr = "SELECT `guild_id`,`guild_name` FROM `".ROSTER_GUILDTABLE."` WHERE `guild_id` != '$guild_id'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Guild ID\'s could not be selected for deletion',$this->error());
			return;
		}

		$num = $this->num_rows($result);

		$inClause = '';
		if ($num > 0)
		{
			while ( $row = $this->fetch_array($result) )
			{
				if ($inClause != '')
					$inClause .= ',';

				$inClause .= $row[0];
				$this->setMessage('<li><span class="red">Guild [</span> '.$row[1].' <span class="red">] - Deleted</span></li>');
			}

			// now that we have our inclause, time to do some deletes
			$querystr = "DELETE FROM `".ROSTER_GUILDTABLE."` WHERE `guild_id` IN ($inClause)";
			if( !$this->query($querystr) )
			{
				$this->setError('Guild'.($num > 1 ? 's' : '').' with ID'.($num > 1 ? 's' : '').' '.$inClause.' could not be deleted',$this->error());
			}

			$this->setMessage('<li><span class="red">Deleted '.$num.' guild'.($num > 1 ? 's' : '').' with mis-matched guild-id'.($num > 1 ? '\s' : '').'</span></li>');
		}
		$this->closeQuery($result);


		// Get a list of members that don't match current guild id
		$querystr = "SELECT `member_id`,`name` FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` != '$guild_id'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Members could not be selected for deletion',$this->error());
			return;
		}

		$num = $this->num_rows($result);

		$inClause = '';
		if ($num > 0)
		{
			while ( $row = $this->fetch_array($result) )
			{
				if ($inClause != '')
					$inClause .= ',';

				$inClause .= $row[0];
				$this->setMessage('<li><span class="red">[</span> '.$row[1].' <span class="red">] Deleted since their guild-id does not match</span></li>');
				$this->setMemberLog($row,0,$timestamp);
			}

			$this->setMessage('<li><span class="red">Removing '.$num.' member'.($num > 1 ? 's' : '').' with mis-matched guild-id'.($num > 1 ? '\'s' : '').'</span>');
			$this->setMessage('<ul>');

			// now that we have our inclause, time to do some deletes
			$this->deleteMembers($inClause);

			$this->setMessage('</ul></li>');
		}
		$this->closeQuery($result);
	}

	/**
	 * Gets guild info from database
	 * Returns info as an array
	 *
	 * @param string $realmName
	 * @param string $guildName
	 * @return array
	 */
	function get_guild_info($realmName,$guildName)
	{
		$guild_name_escape = $this->escape( $guildName );
		$server_escape = $this->escape( $realmName );

		$querystr = "SELECT * FROM `".ROSTER_GUILDTABLE."` WHERE `guild_name` = '$guild_name_escape' AND `server` = '$server_escape'";
		$result = $this->query($querystr) or die_quietly($this->error(),'WowDB Error',basename(__FILE__).'<br />Function: '.(__FUNCTION__),__LINE__,$querystr);

		$retval = $this->fetch_array( $result );
		$this->closeQuery($result);

		return $retval;
	}


	/**
	 * Function to prepare the memberlog data
	 *
	 * @param array $data | Member info array
	 * @param multiple $type | Action to update ( 'rem','del,0 | 'add','new',1 )
	 * @param string $timestamp | Time
	 */
	function setMemberLog( $data , $type , $timestamp )
	{
		if ( is_array($data) )
		{
			switch ($type)
			{
				case 'del':
				case 'rem':
				case 0:
					$this->membersremoved++;
					$this->updateMemberlog($data,0,$timestamp);
					break;

				case 'add':
				case 'new':
				case 1:
					$this->membersadded++;
					$this->updateMemberlog($data,1,$timestamp);
					break;
			}
		}
	}


	/**
	 * Updates or creates an entry in the guild table in the database
	 * Then returns the guild ID
	 *
	 * @param string $realmName
	 * @param string $guildName
	 * @param array $currentTime
	 * @param array $guild
	 * @return string
	 */
	function update_guild( $realmName, $guildName, $currentTime, $guild )
	{
		$guildInfo = $this->get_guild_info($realmName,$guildName);

		$this->reset_values();

		$this->add_value( 'guild_name', $guildName );

		$this->add_value( 'server', $realmName );
		$this->add_value( 'faction', $guild['Faction'] );
		$this->add_value( 'factionEn', $guild['FactionEn'] );
		$this->add_value( 'guild_motd', $guild['Motd'] );

		$this->add_value( 'guild_num_members', $guild['NumMembers'] );
		$this->add_value( 'guild_num_accounts', $guild['NumAccounts'] );

		$this->add_timestamp( 'update_time', $currentTime );

		$this->add_value( 'guild_dateupdatedutc', $guild['timestamp']['init']['DateUTC'] );

		$this->add_value( 'GPversion', $guild['DBversion'] );
		$this->add_value( 'guild_info_text', str_replace('\n',"\n",$guild['Info']) );

		if( is_array($guildInfo) )
		{
			$querystr = "UPDATE `".ROSTER_GUILDTABLE."` SET ".$this->assignstr." WHERE `guild_id` = '".$guildInfo['guild_id']."';";
			$output = $guildInfo['guild_id'];
		}
		else
		{
			$querystr = "INSERT INTO `".ROSTER_GUILDTABLE."` SET ".$this->assignstr;
		}

		$this->query($querystr) or die_quietly($this->error(),'WowDB Error',basename(__FILE__).'<br />Function: '.(__FUNCTION__),__LINE__,$querystr);

		if( is_array($guildInfo) )
		{
			$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET `active` = '0' WHERE `guild_id` = '".$guildInfo['guild_id']."';";
			$this->query($querystr) or die_quietly($this->error(),'WowDB Error',basename(__FILE__).'<br />Function: '.(__FUNCTION__),__LINE__,$querystr);
		}

		if( !is_array($guildInfo) )
		{
			$guildInfo = $this->get_guild_info($realmName,$guildName);
			$output = $guildInfo['guild_id'];
		}

		return $output;
	}


	/**
	 * Updates or adds guild members
	 *
	 * @param int $guildId	| Character's guild id
	 * @param string $name	| Character's name
	 * @param array $char	| LUA data
	 * @param array $currentTimestamp
	 * @return mixed		| False on error, memberid on success
	 */
	function update_guild_member( $guildId, $name, $char, $currentTimestamp, $guildRanks )
	{
		$name_escape = $this->escape( $name );

		$querystr = "SELECT `member_id` FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` = '$name_escape' AND `guild_id` = '$guildId'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Member could not be selected for update',$this->error());
			return false;
		}

		$memberInfo = $this->fetch_assoc( $result );
		if( $memberInfo )
		{
			$memberId = $memberInfo['member_id'];
		}

		$this->closeQuery($result);

		$this->reset_values();

		$this->add_value( 'name', $name_escape);
		$this->add_value( 'class', $char['Class']);
		$this->add_value( 'level', $char['Level']);
		if( isset($char['Note']) )
			$this->add_value( 'note', $char['Note']);
		else
			$this->add_value( 'note', '');
		$this->add_value( 'guild_rank', $char['Rank']);
		$this->add_value( 'guild_title', $guildRanks[$char['Rank']]['Title']);
		if( isset($char['OfficerNote']) )
			$this->add_value( 'officer_note', $char['OfficerNote']);
		else
			$this->add_value( 'officer_note', '');
		$this->add_value( 'zone', $char['Zone']);
		if( isset($char['Status']) )
			$this->add_value( 'status', $char['Status']);
		else
			$this->add_value( 'status', '');

		$this->add_value( 'active', '1');

		if( isset($char['Online']) && $char['Online'] == '1' )
		{
			$this->add_value( 'online', 1 );
			$this->add_time('last_online', getDate($currentTimestamp));
		}
		else
		{
			$this->add_value( 'online', 0 );
			list($lastOnlineYears,$lastOnlineMonths,$lastOnlineDays,$lastOnlineHours) = explode(':',$char['LastOnline']);

			# use strtotime instead
			#      $lastOnlineTime = $currentTimestamp - 365 * 24* 60 * 60 * $lastOnlineYears
			#                        - 30 * 24 * 60 * 60 * $lastOnlineMonths
			#                        - 24 * 60 * 60 * $lastOnlineDays
			#                        - 60 * 60 * $lastOnlineHours;
			$timeString = '-';
			if ($lastOnlineYears > 0)
				$timeString .= $lastOnlineYears.' Years ';
			if ($lastOnlineMonths > 0)
				$timeString .= $lastOnlineMonths.' Months ';
			if ($lastOnlineDays > 0)
				$timeString .= $lastOnlineDays.' Days ';
			$timeString .= max($lastOnlineHours,1).' Hours';

			$lastOnlineTime = strtotime($timeString,$currentTimestamp);
			$this->add_time( 'last_online', getDate($lastOnlineTime) );
		}

		if( isset($memberId) )
		{
			$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId' AND `guild_id` = '$guildId'";
			$this->setMessage('<li>[ '.$name.' ]</li>');
			$this->membersupdated++;

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError($name.' could not be inserted',$this->error());
				return false;
			}
		}
		else
		{
			// Add the guild Id first
			if( !empty($guildId) )
			{
				$this->add_value( 'guild_id', $guildId);
			}

			$querystr = "INSERT INTO `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr;
			$this->setMessage('<li><span class="green">[</span> '.$name.' <span class="green">] - Added</span></li>');

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError($name_escape.' could not be inserted',$this->error());
				return false;
			}

			$memberId = $this->insert_id();

			$querystr = "SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = '$guildId' AND `name` = '$name_escape' AND `class` = '".$char['Class']."';";
			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Member could not be selected for MemberLog',$this->error());
			}
			else
			{
				$row = $this->fetch_array($result);
				$this->setMemberLog($row,1,$currentTimestamp);
			}
		}

		return $memberId;
	}


	/**
	 * Updates pvp table
	 *
	 * @param int $guildId
	 * @param string $name
	 * @param array $data
	 */
	function update_pvp2($guildId, $name, $data )
	{
		$name_escape = $this->escape( $name );

		$querystr = "SELECT `member_id` FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` = '$name_escape' AND `guild_id` = '$guildId'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Member could not be selected for update',$this->error());
			return;
		}

		$memberInfo = $this->fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
		{
			$memberId = $memberInfo['member_id'];
		}
		else
		{
			$this->setMessage('<li>'.$name.' is not in the list of guild members so PVP2 info will not be inserted.</li>');
			return;
		}
		// process pvp
		$this->setMessage('<li>Updating PvP data</li>');
		// loop through each index fought
		foreach( array_keys($data) as $index)
		{
			$playerInfo = $data[$index];
			$playerName = $playerInfo['name'];
			$playerDate = date('Y-m-d G:i:s', strtotime($playerInfo['date']));
			$playerRealm = $playerInfo['realm'];

			// skip if entry already there
			$querystr = "SELECT `guild` FROM `".ROSTER_PVP2TABLE."` WHERE `index` = '$index' AND `member_id` = '$memberId' AND `name` = '".$this->escape( $playerName )."' AND `date` = '".$this->escape( $playerDate )."'".( !empty($playerRealm) ? " AND `realm` = '".$this->escape( $playerRealm )."';" : ';' );

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('PvPLog cannot update',$this->error());
				return;
			}

			$memberInfo = $this->fetch_assoc( $result );
			$this->closeQuery($result);
			if ($memberInfo)
			{}
			else
			{
				$this->setMessage('<li>Adding pvp2 data for ['.$playerInfo['name'].']</li>');

				$this->reset_values();
				$this->add_value('member_id', $memberId);
				$this->add_value('index', $index);
				$this->add_pvp2time('date', $playerInfo['date']);
				$this->add_value('name', $playerInfo['name']);
				$this->add_value('guild', $playerInfo['guild']);
				$this->add_value('realm', $playerInfo['realm']);
				$this->add_value('race', $playerInfo['race']);
				$this->add_value('class', $playerInfo['class']);
				$this->add_value('zone', $playerInfo['zone']);
				$this->add_value('subzone', $playerInfo['subzone']);
				$this->add_value('leveldiff', $playerInfo['lvlDiff']);
				$this->add_value('enemy', $playerInfo['enemy']);
				$this->add_value('win', $playerInfo['win']);
				$this->add_value('bg', $playerInfo['bg']);
				$this->add_value('rank', $playerInfo['rank']);
				$this->add_value('honor', $playerInfo['honor']);

				$querystr = "INSERT INTO `".ROSTER_PVP2TABLE."` SET ".$this->assignstr;
				$result = $this->query($querystr);
				if( !$result )
				{
					$this->setError('PvPLog Data could not be inserted',$this->error());
				}
			}
		}

		// now calculate ratio
		$wins = 0;
		$querystr = "SELECT COUNT(`win`) AS wins FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '1' AND `member_id` = '$memberId' GROUP BY `win`";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('PvPLog cannot select wins',$this->error());
			return;
		}
		$memberInfo = $this->fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
			$wins = $memberInfo['wins'];
		$this->setMessage('<li>Wins: '.$wins.'</li>');


		$losses = 0;
		$querystr = "SELECT COUNT(`win`) AS losses FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '0' AND `member_id` = '$memberId' GROUP BY `win`";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('PvPLog cannot select losses',$this->error());
			return;
		}
		$memberInfo = $this->fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
			$losses = $memberInfo['losses'];
		$this->setMessage('<li>Losses: '.$losses.'</li>');


		if ($losses == 0 || $wins == 0)
		{
			if ($losses == 0 && $wins == 0)
				$ratio = 0;
			else
				$ratio = 99999;

		}
		else
			$ratio = $wins / $losses;

		$querystr = "UPDATE `".ROSTER_PLAYERSTABLE."` SET `pvp_ratio` = ".$ratio." WHERE `member_id` = '$memberId'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('PvPLog ratio could not be updated',$this->error());
		}

		$this->setMessage('<li>Set PvP ratio to '.$ratio.'</li>');
	}


	/**
	 * Function to handle future account data
	 *
	 * @param int $memberId
	 * @param string $name
	 * @param array $data
	 */
	function update_account( $memberId, $name, $data )
	{
		if($data['Account'] == $name)
		{
			$name_escape = $this->escape( $name );
			$querystr = "SELECT `name` FROM `".ROSTER_ACCOUNTTABLE."`";
			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Cannot update Account Data',$this->error());
				return;
			}

			$update = $this->num_rows( $result ) == 1;
			$this->closeQuery($result);

			$this->reset_values();

			$this->add_value( 'name', $data['Account'] );
			$this->add_value( 'hash', $data['Hash'] );

			if( $update )
				$querystr = "UPDATE `".ROSTER_ACCOUNTTABLE."` SET ".$this->assignstr." WHERE `name` = '$name_escape'";
			else
				$querystr = "INSERT INTO `".ROSTER_ACCOUNTTABLE."` SET ".$this->assignstr;

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Could not set Account Data',$this->error());
				return;
			}

			$this->setMessage('<li>Assigning '.$name.' as Main Character</li>');
		}
		else
		{
			$this->setMessage('<li>Assigning '.$name.' as Alt Character</li>');
		}

		$name_escape = $this->escape( $data['Account'] );
		$querystr = "SELECT `account_id` FROM `".ROSTER_ACCOUNTTABLE."` WHERE `name` = '$name_escape'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Cannot update Account Data',$this->error());
			return;
		}

		$accountInfo = $this->fetch_assoc( $result );

		if ($accountInfo)
		{
			$this->reset_values();

			$account_id = $accountInfo['account_id'];
			$this->add_value( 'account_id', $account_id );

			$display = $data['Display'];
			$this->add_value( 'inv', $display['Inventory'] );
			$this->add_value( 'talents', $display['Talents'] );
			$this->add_value( 'quests', $display['Quests'] );
			$this->add_value( 'bank', $display['Bank'] );

		}
		$this->closeQuery($result);

		$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Cannot update Account Data',$this->error());
			return;
		}
	}

	/**
	 * Updates/Inserts pets into the db
	 *
	 * @param int $memberId
	 * @param array $data
	 */
	function update_pet( $memberId, $data )
	{
		if (!empty($data['Name']))
		{
			$querystr = "SELECT `pet_id`
				FROM `".ROSTER_PETSTABLE."`
				WHERE `member_id` = '$memberId' AND `name` LIKE '".$this->escape($data['Name'])."'";

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Cannot select Pet Data',$this->error());
				return;
			}

			if( $this->num_rows( $result ) == 1 )
			{
				$update = true;
				$petID = $this->fetch_assoc($result);
				$petID = $petID['pet_id'];
			}
			else
			{
				$update = false;
			}
			$this->closeQuery($result);

			$this->reset_values();

			$this->add_value( 'member_id', $memberId );

			$this->add_value( 'name', $data['Name'] );
			$this->add_value( 'slot', (isset($data['Slot']) ? $data['Slot'] : '0') );

			// BEGIN STATS
			if( is_array($data['Attributes']['Stats']) )
			{
				$main_stats = $data['Attributes']['Stats'];

				$this->add_rating( 'stat_int', $main_stats['Intellect']);
				$this->add_rating( 'stat_agl', $main_stats['Agility']);
				$this->add_rating( 'stat_sta', $main_stats['Stamina']);
				$this->add_rating( 'stat_str', $main_stats['Strength']);
				$this->add_rating( 'stat_spr', $main_stats['Spirit']);

				unset($main_stats);
			}
			// END STATS

			// BEGIN DEFENSE
			if( is_array($data['Attributes']['Defense']) )
			{
				$main_stats = $data['Attributes']['Defense'];

				$this->add_value( 'dodge', 		$main_stats['DodgeChance']);
				$this->add_value( 'parry',		$main_stats['ParryChance']);
				$this->add_value( 'block',      $main_stats['BlockChance']);
				$this->add_value( 'mitigation', $main_stats['ArmorReduction']);

				$this->add_rating( 'stat_armor', $main_stats['Armor']);
				$this->add_rating( 'stat_def', $main_stats['Defense']);
				$this->add_rating( 'stat_block', $main_stats['BlockRating']);
				$this->add_rating( 'stat_parry', $main_stats['ParryRating']);
				$this->add_rating( 'stat_defr', $main_stats['DefenseRating']);
				$this->add_rating( 'stat_dodge', $main_stats['DodgeRating']);

				$this->add_value( 'stat_res_ranged', $main_stats['Resilience']['Ranged']);
				$this->add_value( 'stat_res_spell', $main_stats['Resilience']['Spell']);
				$this->add_value( 'stat_res_melee', $main_stats['Resilience']['Melee']);
			}
			// END DEFENSE

			// BEGIN RESISTS
			if( is_array($data['Attributes']['Resists']) )
			{
				$main_res = $data['Attributes']['Resists'];

				$this->add_rating( 'res_holy', $main_res['Holy']);
				$this->add_rating( 'res_frost', $main_res['Frost']);
				$this->add_rating( 'res_arcane', $main_res['Arcane']);
				$this->add_rating( 'res_fire', $main_res['Fire']);
				$this->add_rating( 'res_shadow', $main_res['Shadow']);
				$this->add_rating( 'res_nature', $main_res['Nature']);

				unset($main_res);
			}
			// END RESISTS

			// BEGIN MELEE
			if( is_array($data['Attributes']['Melee']) )
			{
				$attack = $data['Attributes']['Melee'];

				$this->add_rating( 'melee_power', $attack['AttackPower']);
				$this->add_rating( 'melee_hit', $attack['HitRating']);
				$this->add_rating( 'melee_crit', $attack['CritRating']);
				$this->add_rating( 'melee_haste', $attack['HasteRating']);

				$this->add_value('melee_crit_chance', $attack['CritChance']);
				$this->add_value('melee_power_dps', $attack['AttackPowerDPS']);

				if( is_array($attack['MainHand']) )
				{
					$hand = $attack['MainHand'];

					$this->add_value( 'melee_mhand_speed', $hand['AttackSpeed']);
					$this->add_value( 'melee_mhand_dps', $hand['AttackDPS']);
					$this->add_value( 'melee_mhand_skill', $hand['AttackSkill']);

					list($mindam, $maxdam) = explode(':',$hand['DamageRange']);
					$this->add_value( 'melee_mhand_mindam', $mindam);
					$this->add_value( 'melee_mhand_maxdam', $maxdam);
					unset($mindam, $maxdam);

					$this->add_rating( 'melee_mhand_rating', $hand['AttackRating']);
				}

				if( isset($attack['DamageRangeTooltip']) )
					$this->add_value( 'melee_range_tooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
				if( isset($attack['AttackPowerTooltip']) )
					$this->add_value( 'melee_power_tooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );

				unset($hand, $attack);
			}
			// END MELEE

			$this->add_value( 'level', $data['Level'] );
			$this->add_value( 'health', $data['Health'] );
			$this->add_value( 'mana', $data['Mana'] );
			$this->add_value( 'power', $data['Power'] );

			$this->add_value( 'xp', $data['Experience'] );
			$this->add_value( 'usedtp', $data['TalentPointsUsed'] );
			$this->add_value( 'totaltp', $data['TalentPoints'] );
			$this->add_value( 'type', $data['Type'] );
			$this->add_value( 'loyalty', (isset($data['Loyalty']) ? $data['Loyalty'] : '') );
			$this->add_value( 'icon', (isset($data['Icon']) ? $this->fix_icon($data['Icon']) : '') );

			if( $update )
			{
				$this->setMessage('<li>Updating pet ['.$data['Name'].']');
				$querystr = "UPDATE `".ROSTER_PETSTABLE."` SET ".$this->assignstr." WHERE `pet_id` = '$petID'";
				$result = $this->query($querystr);
			}
			else
			{
				$this->setMessage('<li>New pet ['.$data['Name'].']');
				$querystr = "INSERT INTO `".ROSTER_PETSTABLE."` SET ".$this->assignstr;
				$result = $this->query($querystr);
				$petID = $this->insert_id();
			}

			if( !$result )
			{
				$this->setError('Cannot update Pet Data',$this->error());
				return;
			}
			$this->do_pet_spellbook($data,$memberId,$petID);
		}
	}


	/**
	 * Handles formatting an insertion of Character Data
	 *
	 * @param int $guildId	| Character's guild id
	 * @param string $name	| Character's name
	 * @param array $data	| LUA data
	 * @return mixed		| False on error, memberid on success
	 */
	function update_char( $guildId , $name , $data )
	{
		//print '<pre>';
		//print_r( $data );
		//print '</pre>';

		$name_escape = $this->escape( $name );

		$querystr = "SELECT `member_id` FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` = '$name_escape' AND `guild_id` = '$guildId'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Cannot select member_id for Character Data',$this->error());
			return false;
		}

		$memberInfo = $this->fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
		{
			$memberId = $memberInfo['member_id'];
		}
		else
		{
			$this->setMessage('<li>'.$name.' is not in the list of guild members so their data will not be inserted.</li>');
			return false;
		}

		// update level in members table
		$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET `level` = '".$data['Level']."' WHERE `member_id` = $memberId LIMIT 1 ";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Cannot update Level in Members Table',$this->error());
		}


		$querystr = "SELECT `member_id` FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` = '$memberId'";
		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Cannot select member_id for Character Data',$this->error());
			return false;
		}

		$update = $this->num_rows( $result ) == 1;
		$this->closeQuery($result);

		$this->reset_values();

		$this->add_value( 'name', $name );
		$this->add_value( 'guild_id', $guildId );

		// BEGIN HONOR VALUES
		if( isset($data['Honor']) && is_array($data['Honor']) )
		{
			$honor = $data['Honor'];
			if( isset($honor['Session']['HK']) )
				$this->add_value( 'sessionHK',             $honor['Session']['HK'] );
			else
				$this->add_value( 'sessionHK',0 );

			if( isset($honor['Session']['CP']) )
				$this->add_value( 'sessionCP',             $honor['Session']['CP'] );
			else
				$this->add_value( 'sessionCP',0 );

			if( isset($honor['Yesterday']['HK']) )
				$this->add_value( 'yesterdayHK',           $honor['Yesterday']['HK'] );
			else
				$this->add_value( 'yesterdayHK',0 );

			if( isset($honor['Yesterday']['CP']) )
				$this->add_value( 'yesterdayContribution', $honor['Yesterday']['CP'] );
			else
				$this->add_value( 'yesterdayContribution',0 );

			if( isset($honor['Lifetime']['HK']) )
				$this->add_value( 'lifetimeHK',            $honor['Lifetime']['HK'] );
			else
				$this->add_value( 'lifetimeHK',0 );

			if( isset($honor['Lifetime']['Rank']) )
				$this->add_value( 'lifetimeHighestRank',   $honor['Lifetime']['Rank'] );
			else
				$this->add_value( 'lifetimeHighestRank',0 );

			if( isset($honor['Lifetime']['Name']) )
				$this->add_value( 'lifetimeRankName',      $honor['Lifetime']['Name'] );
			else
				$this->add_value( 'lifetimeRankName','' );

			if( isset($honor['Current']['HonorPoints']) )
				$this->add_value( 'honorpoints',            $honor['Current']['HonorPoints'] );
			else
				$this->add_value( 'honorpoints',0 );

			if( isset($honor['Current']['ArenaPoints']) )
				$this->add_value( 'arenapoints',            $honor['Current']['ArenaPoints'] );
			else
				$this->add_value( 'arenapoints',0 );

			unset($honor);
		}
		// END HONOR VALUES

		$this->add_value( 'crit', ( isset($data['Attributes']['Melee']['CritChance']) ? $data['Attributes']['Melee']['CritChance'] : 0 ) );

		// BEGIN STATS
		if( is_array($data['Attributes']['Stats']) )
		{
			$main_stats = $data['Attributes']['Stats'];

			$this->add_rating( 'stat_int', $main_stats['Intellect']);
			$this->add_rating( 'stat_agl', $main_stats['Agility']);
			$this->add_rating( 'stat_sta', $main_stats['Stamina']);
			$this->add_rating( 'stat_str', $main_stats['Strength']);
			$this->add_rating( 'stat_spr', $main_stats['Spirit']);

			unset($main_stats);
		}
		// END STATS

		// BEGIN DEFENSE
		if( is_array($data['Attributes']['Defense']) )
		{
			$main_stats = $data['Attributes']['Defense'];

			$this->add_value( 'dodge', 		$main_stats['DodgeChance']);
			$this->add_value( 'parry',		$main_stats['ParryChance']);
			$this->add_value( 'block',      $main_stats['BlockChance']);
			$this->add_value( 'mitigation', $main_stats['ArmorReduction']);

			$this->add_rating( 'stat_armor', $main_stats['Armor']);
			$this->add_rating( 'stat_def', $main_stats['Defense']);
			$this->add_rating( 'stat_block', $main_stats['BlockRating']);
			$this->add_rating( 'stat_parry', $main_stats['ParryRating']);
			$this->add_rating( 'stat_defr', $main_stats['DefenseRating']);
			$this->add_rating( 'stat_dodge', $main_stats['DodgeRating']);

			$this->add_value( 'stat_res_ranged', $main_stats['Resilience']['Ranged']);
			$this->add_value( 'stat_res_spell', $main_stats['Resilience']['Spell']);
			$this->add_value( 'stat_res_melee', $main_stats['Resilience']['Melee']);
		}
		// END DEFENSE

		// BEGIN RESISTS
		if( is_array($data['Attributes']['Resists']) )
		{
			$main_res = $data['Attributes']['Resists'];

			$this->add_rating( 'res_holy', $main_res['Holy']);
			$this->add_rating( 'res_frost', $main_res['Frost']);
			$this->add_rating( 'res_arcane', $main_res['Arcane']);
			$this->add_rating( 'res_fire', $main_res['Fire']);
			$this->add_rating( 'res_shadow', $main_res['Shadow']);
			$this->add_rating( 'res_nature', $main_res['Nature']);

			unset($main_res);
		}
		// END RESISTS

		// BEGIN MELEE
		if( is_array($data['Attributes']['Melee']) )
		{
			$attack = $data['Attributes']['Melee'];

			$this->add_rating( 'melee_power', $attack['AttackPower']);
			$this->add_rating( 'melee_hit', $attack['HitRating']);
			$this->add_rating( 'melee_crit', $attack['CritRating']);
			$this->add_rating( 'melee_haste', $attack['HasteRating']);

			$this->add_value('melee_crit_chance', $attack['CritChance']);
			$this->add_value('melee_power_dps', $attack['AttackPowerDPS']);

			if( is_array($attack['MainHand']) )
			{
				$hand = $attack['MainHand'];

				$this->add_value( 'melee_mhand_speed', $hand['AttackSpeed']);
				$this->add_value( 'melee_mhand_dps', $hand['AttackDPS']);
				$this->add_value( 'melee_mhand_skill', $hand['AttackSkill']);

				list($mindam, $maxdam) = explode(':',$hand['DamageRange']);
				$this->add_value( 'melee_mhand_mindam', $mindam);
				$this->add_value( 'melee_mhand_maxdam', $maxdam);
				unset($mindam, $maxdam);

				$this->add_rating( 'melee_mhand_rating', $hand['AttackRating']);
			}

			if( isset($attack['OffHand']) && is_array($attack['OffHand']) )
			{
				$hand = $attack['OffHand'];

				$this->add_value( 'melee_ohand_speed', $hand['AttackSpeed']);
				$this->add_value( 'melee_ohand_dps', $hand['AttackDPS']);
				$this->add_value( 'melee_ohand_skill', $hand['AttackSkill']);

				list($mindam, $maxdam) = explode(':',$hand['DamageRange']);
				$this->add_value( 'melee_ohand_mindam', $mindam);
				$this->add_value( 'melee_ohand_maxdam', $maxdam);
				unset($mindam, $maxdam);

				$this->add_rating( 'melee_ohand_rating', $hand['AttackRating']);
			}
			else
			{
				$this->add_value( 'melee_ohand_speed', 0);
				$this->add_value( 'melee_ohand_dps', 0);
				$this->add_value( 'melee_ohand_skill', 0);

				$this->add_value( 'melee_ohand_mindam', 0);
				$this->add_value( 'melee_ohand_maxdam', 0);

				$this->add_rating( 'melee_ohand_rating', 0);
			}

			if( isset($attack['DamageRangeTooltip']) )
				$this->add_value( 'melee_range_tooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
			if( isset($attack['AttackPowerTooltip']) )
				$this->add_value( 'melee_power_tooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );

			unset($hand, $attack);
		}
		// END MELEE

		// BEGIN RANGED
		if( isset($data['Attributes']['Ranged']) && is_array($data['Attributes']['Ranged']) )
		{
			$attack = $data['Attributes']['Ranged'];

			$this->add_rating( 'ranged_power', ( isset($attack['AttackPower']) ? $attack['AttackPower'] : '0' ));
			$this->add_rating( 'ranged_hit', $attack['HitRating']);
			$this->add_rating( 'ranged_crit', $attack['CritRating']);
			$this->add_rating( 'ranged_haste', $attack['HasteRating']);

			$this->add_value( 'ranged_crit_chance', $attack['CritChance']);
			$this->add_value( 'ranged_power_dps', ( isset($attack['AttackPowerDPS']) ? $attack['AttackPowerDPS'] : '0' ));

			$this->add_value( 'ranged_speed', $attack['AttackSpeed']);
			$this->add_value( 'ranged_dps', $attack['AttackDPS']);
			$this->add_value( 'ranged_skill', $attack['AttackSkill']);

			list($mindam, $maxdam) = explode(':',$attack['DamageRange']);
			$this->add_value( 'ranged_mindam', $mindam);
			$this->add_value( 'ranged_maxdam', $maxdam);
			unset($mindam, $maxdam);

			$this->add_rating( 'ranged_rating', $attack['AttackRating']);

			if( isset($attack['DamageRangeTooltip']) )
				$this->add_value( 'ranged_range_tooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
			if( isset($attack['AttackPowerTooltip']) )
				$this->add_value( 'ranged_power_tooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );
			unset($attack);
		}
		// END RANGED

		// BEGIN SPELL
		if( is_array($data['Attributes']['Spell']) )
		{
			$spell = $data['Attributes']['Spell'];

			$this->add_rating( 'spell_hit', $spell['HitRating']);
			$this->add_rating( 'spell_crit', $spell['CritRating']);
			$this->add_rating( 'spell_haste', $spell['HasteRating']);

			$this->add_value( 'spell_crit_chance', $spell['CritChance']);

			list($mana, $time) = explode(':',$spell['ManaRegen']);
			$this->add_value( 'mana_regen_value', $mana);
			$this->add_value( 'mana_regen_time', $time);
			unset($mana, $time);

			$this->add_value( 'spell_penetration', $spell['Penetration']);
			$this->add_value( 'spell_damage', $spell['BonusDamage']);
			$this->add_value( 'spell_healing', $spell['BonusHealing']);

			$this->add_value( 'spell_damage_frost', $spell['School']['Frost']);
			$this->add_value( 'spell_damage_arcane', $spell['School']['Arcane']);
			$this->add_value( 'spell_damage_fire', $spell['School']['Fire']);
			$this->add_value( 'spell_damage_shadow', $spell['School']['Shadow']);
			$this->add_value( 'spell_damage_nature', $spell['School']['Nature']);

			unset($spell);
		}
		// END SPELL

		$this->add_value( 'level', $data['Level'] );
		$this->add_value( 'server', $data['Server'] );

		if( isset($data['TalentPoints']) )
			$this->add_value( 'talent_points', $data['TalentPoints'] );

		$this->add_value( 'money_c', $data['Money']['Copper'] );
		$this->add_value( 'money_s', $data['Money']['Silver'] );
		$this->add_value( 'money_g', $data['Money']['Gold'] );

		$this->add_value( 'exp', $data['Experience'] );
		$this->add_value( 'race', $data['Race'] );
		$this->add_value( 'raceEn', $data['RaceEn'] );
		$this->add_value( 'class', $data['Class'] );
		$this->add_value( 'classEn', $data['ClassEn'] );
		$this->add_value( 'health', $data['Health'] );
		$this->add_value( 'mana', $data['Mana'] );
		$this->add_value( 'power', $data['Power'] );
		$this->add_value( 'sex', $data['Sex'] );
		$this->add_value( 'sexid', $data['SexId'] );
		$this->add_value( 'hearth', $data['Hearth'] );

		if( !empty($data['timestamp']['init']['DateUTC']) )
		{
			$this->add_value( 'dateupdatedutc', $data['timestamp']['init']['DateUTC'] );
		}

		$this->add_value( 'CPversion', $data['DBversion'] );

		if (isset($data['TimePlayed']) && $data['TimePlayed'] > 0 )
			$this->add_value( 'timeplayed', $data['TimePlayed'] );

		if (isset($data['TimeLevelPlayed']) && $data['TimeLevelPlayed'] > 0 )
			$this->add_value( 'timelevelplayed', $data['TimeLevelPlayed'] );


		// Capture mailbox update time/date
		if( isset($data['timestamp']['MailBox']) )
			$this->add_timestamp( 'maildateutc', $data['timestamp']['MailBox'] );

		// Capture client language
		$this->add_value( 'clientLocale', $data['Locale'] );

		$this->setMessage('<li>About to update player</li>');

		if( $update )
		{
			$querystr = "UPDATE `".ROSTER_PLAYERSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId'";
		}
		else
		{
			$this->add_value( 'member_id', $memberId );
			$querystr = "INSERT INTO `".ROSTER_PLAYERSTABLE."` SET ".$this->assignstr;
		}

		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Cannot update Character Data',$this->error());
			return false;
		}

		$this->do_equip( $data, $memberId );
		$this->do_inventory( $data, $memberId );
		$this->do_bank( $data, $memberId );
		$this->do_mailbox( $data, $memberId );
		$this->do_skills( $data, $memberId );
		$this->do_recipes( $data, $memberId );
		$this->do_spellbook( $data, $memberId );
		$this->do_talents( $data, $memberId );
		$this->do_reputation( $data, $memberId );
		$this->do_quests( $data, $memberId );
		$this->do_buffs( $data, $memberId );

		// Adding pet info
		if( !empty( $data['Pets'] ) && is_array($data['Pets']) )
		{
			$petsdata = $data['Pets'];
			foreach( $petsdata as $pet )
			{
				$this->update_pet( $memberId, $pet );
			}
		}
		else
		{
			$querystr = "DELETE FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$memberId'";
			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Cannot delete Pet Data',$this->error());
			}

			$querystr = "DELETE FROM `".ROSTER_PETSPELLTABLE."` WHERE `member_id` = '$memberId'";
			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Cannot delete Pet Spell Data',$this->error());
			}
		}

		// built in Auth system
		if( isset( $data['Roster'] ) )
		{
			$rosterdata = $data['Roster'];
			$this->update_account( $memberId, $name, $rosterdata);
		}

		return $memberId;

	} //-END function update_char()

} //-END CLASS

$wowdb = new wowdb;
