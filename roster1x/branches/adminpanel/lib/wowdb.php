<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

class wowdb
{
	var $db;			// Database resource id
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
	 * @return bool
	 */
	function connect( $host, $user, $password, $name=null )
	{
		$this->db = @mysql_connect($host, $user, $password);

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
		$result = @mysql_errno().': '.mysql_error();
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
		return mysql_affected_rows($this->db);
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
	 * @param string $profile the name of the addon's config profile.
	 * @return string tablename as fit for MySQL queries
	 */
	function table($table, $addon='', $profile='')
	{
		global $db_prefix;

		if ($addon)
			return $db_prefix.'addons_'.$addon.'_'.$profile.'_'.$table;
		else
			return $db_prefix.$table;
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

		if( !is_numeric($row_data) )
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
		if( $this->assignstr != '' )
			$this->assignstr .= ',';

		// 01/01/2000 23:00:00.000
		$row_data = "'".$date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':00'."'";
		$this->assignstr .= " `$row_name` = $row_data";
	}


	/**
	 * Add a time value to an INSERT or UPDATE SQL string for PVP table
	 *
	 * @param string $row_name
	 * @param string $date
	 */
	function add_pvp2time( $row_name, $date )
	{
		if( $this->assignstr != '' )
			$this->assignstr .= ',';

		$date_str = strtotime($date);
		$p2newdate = date('Y-m-d G:i:s',$date_str);
		$row_data = "'".$p2newdate."'";
		$this->assignstr .= " `$row_name` = $row_data";
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
			$first=true;
			foreach( $tipdata as $tip )
			{
				if( $first )
				{
					$tooltip .= "$tip";
					$first=false;
				}
				else
				{
					$tooltip .= "\n$tip";
				}
			}
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
	function insert_item( $item )
	{
		$this->reset_values();
		$this->add_value('member_id', $item['member_id'] );
		$this->add_value('item_name', $item['item_name'] );
		$this->add_value('item_parent', $item['item_parent'] );
		$this->add_value('item_slot', $item['item_slot'] );
		$this->add_value('item_color', $item['item_color'] );
		$this->add_value('item_id', $item['item_id'] );
		$this->add_value('item_texture', $item['item_texture'] );
		$this->add_value('item_tooltip', $item['item_tooltip'] );

		if( isset( $item['item_quantity'] ) )
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
	function updateMemberlog( $data , $type )
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
		$this->add_value('update_time', $data['update_time'] );
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
	function make_quest( $quest_data, $memberId, $zone)
	{
		$quest = array();
		$quest['member_id'] = $memberId;

		//Fix quest name if too many 'quest' addons cause level number to be added to title
		$quest['quest_name'] = preg_replace("/^(\[[[:digit:]]{1,2}(D|R|\+)?\] )?/",'',$quest_data['Title']);
		$quest['quest_tag'] = $quest_data['Tag'];
		$quest['quest_index'] = 0;
		$quest['quest_level'] = $quest_data['Level'];
		$quest['zone'] = $zone;

		if ($quest_data['Complete'] == 'Complete')
			$quest['is_complete'] = 1;
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
		$item = $mail_data['Item'];

		$mail = array();
		$mail['member_id'] = $memberId;
		$mail['mail_slot'] = $slot_num;
		$mail['mail_coin'] = $mail_data['Coin'];
		$mail['mail_coin_icon'] = str_replace('\\\\','/', $mail_data['CoinIcon']);
		$mail['mail_days'] = $mail_data['Days'];
		$mail['mail_sender'] = $mail_data['Sender'];
		$mail['mail_subject'] = $mail_data['Subject'];

		$mail['item_icon'] = str_replace('\\\\','/', $item['Icon']);
		$mail['item_name'] = $item['Name'];
		$mail['item_color'] = $item['Color'];

		if( isset( $item['Quantity'] ) )
			$mail['item_quantity'] = $item['Quantity'];

		if( !empty($item['Tooltip']) )
			$mail['item_tooltip'] = $this->tooltip( $item['Tooltip'] );
		else
			$mail['item_tooltip'] = $item['Name'];

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
		$item['item_color'] = $item_data['Color'];
		$item['item_id'] = $item_data['Item'];
		$item['item_texture'] = str_replace('\\\\','/', $item_data['Texture']);

		if( isset( $item_data['Quantity'] ) )
			$item['item_quantity'] = $item_data['Quantity'];

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
		$recipe['item_color'] = $recipe_data['Color'];
		$recipe['reagents'] = $recipe_data['Reagents'];
		$recipe['recipe_texture'] = str_replace('\\\\','/', $recipe_data['Texture']);

		if( !empty($recipe_data['Tooltip']) )
			$recipe['recipe_tooltip'] = $this->tooltip( $recipe_data['Tooltip'] );
		else
			$recipe['recipe_tooltip'] = $recipe_name;

		return $recipe;
	}


	/**
	 * Handles formating and insertion of quest data
	 *
	 * @param array $data
	 * @param int $memberId
	 */
	function do_quests( $data, $memberId )
	{
		$quests = $data['Quests'];

		if( !empty($quests) && is_array($quests) )
		{
			$this->setMessage('<li>Updating Quests</li>');

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
					$item = $this->make_quest( $slotInfo, $memberId, $zone);
					$this->insert_quest( $item );
					$questnum++;
				}
			}
			$this->setMessage('<ul><li>'.$questnum.' Quest'.($questnum > 1 ? 's' : '').'</li></ul>');
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
		$prof = $data['Professions'];

		if( !empty($prof) && is_array($prof) )
		{
			$this->setMessage('<li>Updating Professions</li>');

			// Delete the stale data
			$querystr = "DELETE FROM `".ROSTER_RECIPESTABLE."` WHERE `member_id` = '$memberId'";
			if( !$this->query($querystr) )
			{
				$this->setError('Professions could not be deleted',$this->error());
				return;
			}
			// Then process Professions
			$this->setMessage('<ul>');
			foreach( array_keys($prof) as $skill_name )
			{
				$this->setMessage("<li>$skill_name</li>");

				$skill = $prof[$skill_name];
				foreach( array_keys( $skill) as $recipe_type )
				{
					$item = $skill[$recipe_type];
					foreach(array_keys($item) as $recipe_name)
					{
						$recipeDetails = $item[$recipe_name];
						$recipe = $this->make_recipe( $recipeDetails, $memberId, $skill_name, $recipe_type, $recipe_name );
						$this->insert_recipe( $recipe,$data['Locale'] );
					}
				}
			}
			$this->setMessage('</ul>');
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
			$this->setMessage('<li>Updating Equipment</li>');

			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '$memberId' AND `item_parent` = 'equip'";
			if( !$this->query($querystr) )
			{
				$this->setError('Equipment could not be deleted',$this->error());
				return;
			}
			foreach( array_keys($equip) as $slot_name )
			{
				$slot = $equip[$slot_name];
				$item = $this->make_item( $slot, $memberId, 'equip', $slot_name );
				$this->insert_item( $item );
			}
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
			$this->setMessage('<li>Updating Inventory</li>');
			$this->setMessage('<ul>');

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
				$this->setMessage("<li>$bag_name</li>");

				$bag = $inv[$bag_name];
				$item = $this->make_item( $bag, $memberId, 'bags', $bag_name );
				// quantity for a bag means number of slots it has
				$item['item_quantity'] = $bag['Slots'];
				$this->insert_item( $item );
				foreach( array_keys( $bag['Contents'] ) as $slot_name )
				{
					$slot = $bag['Contents'][$slot_name];
					$item = $this->make_item( $slot, $memberId, $bag_name, $slot_name );
					$this->insert_item( $item );
				}
			}
			$this->setMessage('</ul>');
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
		$inv = $data['Bank'];
		if( !empty($inv) && is_array($inv) )
		{
			$this->setMessage('<li>Updating Bank</li>');
			$this->setMessage('<ul>');

			// Clearing out old items
			if( !empty($inv['Contents']) )
			{
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
			}
			else
			{
				$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '$memberId' AND `item_parent` = 'bags' AND `item_slot` = 'Bank Contents'";
				if( !$this->query($querystr) )
				{
					$this->setError('Bank could not be deleted',$this->error());
					return;
				}
			}

			// Make a special "Bank" container.
			$item = array();
			$item['member_id'] = $memberId;
			$item['item_name'] = 'Bank Contents';
			$item['item_parent'] = 'bags';
			$item['item_slot'] = 'Bank Contents';
			$item['item_color'] = 'ffffffff';
			$item['item_id'] = '';
			$item['item_texture'] = 'Interface/Icons/INV_Misc_Bag_07';
			$item['item_quantity'] = 24;
			$item['item_tooltip'] = "Bank Contents\n24 Slot Container";
			$this->insert_item( $item );
			$bag = $inv;

			$this->setMessage('<li>Bank Contents</li>');
			foreach( array_keys( $bag['Contents'] ) as $slot_name )
			{
				$slot = $bag['Contents'][$slot_name];
				$item = $this->make_item( $slot, $memberId, 'Bank Contents', $slot_name );
				$this->insert_item( $item );
			}
			foreach( array_keys( $inv ) as $bag_name )
			{
				if ($bag_name != 'Contents')
				{
					$this->setMessage("<li>$bag_name</li>");
					$bag = $inv[$bag_name];
					$dbname = 'Bank '.$bag_name;
					$item = $this->make_item( $bag, $memberId, 'bags', $dbname );

					// quantity for a bag means number of slots it has
					$item['item_quantity'] = $bag['Slots'];
					$this->insert_item( $item );

					foreach( array_keys( $bag['Contents'] ) as $slot_name )
					{
						$slot = $bag['Contents'][$slot_name];
						$item = $this->make_item( $slot, $memberId, $dbname, $slot_name );
						$this->insert_item( $item );
					}
				}
			}
			$this->setMessage('</ul>');
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
		$this->setMessage('<li>Updating Mailbox</li>');

		$mailbox = $data['MailBox'];
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
				$mail = $this->make_mail( $slot, $memberId, $slot_num );
				$this->insert_mail( $mail );
			}
			$this->setMessage('<ul><li>'.count($mailbox).' Message'.(count($mailbox) > 1 ? 's' : '').'</li></ul>');
		}
		else
		{
			$this->setMessage('<ul><li>No New Mail</li></ul>');
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
		$repData = $data['Reputation'];

		if( !empty($repData) && is_array($repData) )
		{
			$this->setMessage('<li>Updating Reputation</li>');

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
							$this->add_value('Value', $repData[$factions][$faction]['Value'] );
						if( !empty($repData[$factions][$faction]['AtWar']) )
							$this->add_value('AtWar', $repData[$factions][$faction]['AtWar'] );
						if( !empty($repData[$factions][$faction]['Standing']) )
							$this->add_value('Standing', $repData[$factions][$faction]['Standing']);

						$querystr = "INSERT INTO `".ROSTER_REPUTATIONTABLE."` SET ".$this->assignstr;

						$result = $this->query($querystr);
						if( !$result )
						{
							$this->setError('Reputation for '.$faction.' could not be inserted',$this->error());
						}
					}
				}
			}
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
		$skillData = $data['Skills'];

		if( !empty($skillData) && is_array($skillData) )
		{
			$this->setMessage('<li>Updating Skills</li>');

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

						$querystr = "INSERT INTO `".ROSTER_SKILLSTABLE."` SET ".$this->assignstr;

						$result = $this->query($querystr);
						if( !$result )
						{
							$this->setError('Skill ['.$skill_name.'] could not be inserted',$this->error());
						}
					}
				}
			}
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
		$spellbook = $data['SpellBook'];

		if( !empty($spellbook) && is_array($spellbook) )
		{
			$this->setMessage('<li>Updating Spellbook</li>');
			$this->setMessage('<ul>');

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
				$this->setMessage("<li>$spell_type</li>");

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
							if( !empty($data_spell_name['Texture']) )
								$this->add_value('spell_texture', str_replace('\\\\','/', $data_spell_name['Texture']) );
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
				$this->add_value('spell_texture', str_replace('\\\\','/', $data_spell_type['Texture']) );

				$querystr = "INSERT INTO `".ROSTER_SPELLTREETABLE."` SET ".$this->assignstr;
				$result = $this->query($querystr);
				if( !$result )
				{
					$this->setError('Spell Tree ['.$spell_type.'] could not be inserted',$this->error());
				}
			}
			$this->setMessage('</ul>');
		}
		else
		{
			$this->setMessage('<li>No Spellbook Data</li>');
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
		$talentData = $data['Talents'];

		if( !empty($talentData) && is_array($talentData) )
		{
			$this->setMessage('<li>Updating Talents</li>');
			$this->setMessage('<ul>');

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
				$this->setMessage("<li>$talent_tree</li>");

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
						$tree_background = str_replace('\\\\','/', $data_talent_skill);
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

						if( !empty($data_talent_skill['Texture']) )
							$this->add_value('texture', str_replace('\\\\','/', $data_talent_skill['Texture']) );

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
					$this->add_value('background', $tree_background );
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
			$this->setMessage('</ul>');
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
		$this->setMessage('<li>');

		$this->setMessage('Character Data..');
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


		$this->setMessage('Skills..');
		$querystr = "DELETE FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Skill Data could not be deleted',$this->error());
		}


		$this->setMessage('Items..');
		$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Items Data could not be deleted',$this->error());
		}


		$this->setMessage('Quests..');
		$querystr = "DELETE FROM `".ROSTER_QUESTSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Quest Data could not be deleted',$this->error());
		}


		$this->setMessage('PvPLog Data..');
		$querystr = "DELETE FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('PvPLog Data could not be deleted',$this->error());
		}


		$this->setMessage('Professions..');
		$querystr = "DELETE FROM `".ROSTER_RECIPESTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Recipe Data could not be deleted',$this->error());
		}


		$this->setMessage('Talents..');
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


		$this->setMessage('Spellbook..');
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


		$this->setMessage('Pets..');
		$querystr = "DELETE FROM `".ROSTER_PETSTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Pet Data could not be deleted',$this->error());
		}


		$this->setMessage('Reputation..');
		$querystr = "DELETE FROM `".ROSTER_REPUTATIONTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Reputation Data could not be deleted',$this->error());
		}


		$this->setMessage('Mail..');
		$querystr = "DELETE FROM `".ROSTER_MAILBOXTABLE."` WHERE `member_id` IN ($inClause)";
		if( !$this->query($querystr) )
		{
			$this->setError('Mail Data could not be deleted',$this->error());
		}

		$this->setMessage('</li>');
	}


	/**
	 * Removes guild members with update_time < guild['update_time']
	 *
	 * @param int $guild_id
	 * @param array $date
	 */
	function remove_guild_members($guild_id, $date)
	{
		$querystr = "SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = '$guild_id' AND `update_time` <> '".
			$date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':00'."'";
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
				$this->setMessage('<li><span class="red">Removing member - [</span> '.$row[1].' <span class="red">]</span></li>');
				$this->setMemberLog($row,0);

			}

			$this->setMessage('<li><span class="red">Removing '.$num.' member'.($num > 1 ? 's' : '').'</span></li>');
			$this->setMessage('<ul>');

			// now that we have our inclause, time to do some deletes
			$this->deleteMembers($inClause);

			$this->setMessage('</ul>');
		}
		$this->closeQuery($result);
	}

	/**
	 * Removes members that do not match current guild_id
	 *
	 * @param int $guild_id
	 */
	function remove_guild_members_id($guild_id)
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
				$this->setMessage('<li><span class="red">Removing guild - [</span> '.$row[1].' <span class="red">]</span></li>');
			}

			// now that we have our inclause, time to do some deletes
			$querystr = "DELETE FROM `".ROSTER_GUILDTABLE."` WHERE `guild_id` IN ($inClause)";
			if( !$this->query($querystr) )
			{
				$this->setError('Guild'.($num > 1 ? 's' : '').' with ID'.($num > 1 ? 's' : '').' '.$inClause.' could not be deleted',$this->error());
			}

			$this->setMessage('<li><span class="red">Removing '.$num.' guild'.($num > 1 ? 's' : '').' with mis-matched guild-id'.($num > 1 ? '\s' : '').'</span></li>');
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
				$this->setMessage('<li><span class="red">Removing member - [</span> '.$row[1].' <span class="red">] since their guild-id does not match</span></li>');
				$this->setMemberLog($row,0);
			}

			$this->setMessage('<li><span class="red">Removing '.$num.' member'.($num > 1 ? 's' : '').' with mis-matched guild-id'.($num > 1 ? '\'s' : '').'</span></li>');
			$this->setMessage('<ul>');

			// now that we have our inclause, time to do some deletes
			$this->deleteMembers($inClause);

			$this->setMessage('</ul>');
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
	 */
	function setMemberLog( $data , $type )
	{
		if ( is_array($data) )
		{
			switch ($type)
			{
				case 'del':
				case 'rem':
				case 0:
					$this->membersremoved++;
					$this->updateMemberlog($data,0);
					break;

				case 'add':
				case 'new':
				case 1:
					$this->membersadded++;
					$this->updateMemberlog($data,1);
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
		$this->add_value( 'guild_motd', $guild['Motd'] );

		if( !empty($guild['NumMembers']) )
			$this->add_value( 'guild_num_members', $guild['NumMembers'] );
		if( !empty($guild['NumAccounts']) )
			$this->add_value( 'guild_num_accounts', $guild['NumAccounts'] );
		if( !empty($currentTime) )
			$this->add_time( 'update_time', $currentTime );

		$this->add_value( 'guild_dateupdatedutc', $guild['DateUTC'] );
		$this->add_value( 'GPversion', $guild['DBversion'] );
		$this->add_value( 'guild_info_text', str_replace('\n',"\n",$guild['Info']) );

		if ($guildInfo)
		{
			$guildId = $guildInfo['guild_id'];
			$querystr = "UPDATE `".ROSTER_GUILDTABLE."` SET ".$this->assignstr." WHERE `guild_id` = '$guildId'";
			$output = $guildId;
		}
		else
		{
			$querystr = "INSERT INTO `".ROSTER_GUILDTABLE."` SET ".$this->assignstr;
		}

		$this->query($querystr) or die_quietly($this->error(),'WowDB Error',basename(__FILE__).'<br />Function: '.(__FUNCTION__),__LINE__,$querystr);

		if( !$guildInfo )
		{
			$guildInfo = $this->get_guild_info($realmName,$guildName);
			$output = $guildInfo['guild_id'];
		}

		return $output;
	}


	/**
	 * Updates or adds guild members
	 *
	 * @param int $guildId
	 * @param string $name
	 * @param array $char
	 * @param array $currentTimestamp
	 */
	function update_guild_member( $guildId, $name, $char, $currentTimestamp )
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
		if ($memberInfo)
			$memberId = $memberInfo['member_id'];

		$this->closeQuery($result);

		$this->reset_values();

		$this->add_value( 'name', $name_escape);
		$this->add_value( 'class', $char['Class']);

		if( !empty($char['Level']) )
			$this->add_value( 'level', $char['Level']);

		$this->add_value( 'note', $char['Note']);

		if( !empty($char['RankIndex']) )
			$this->add_value( 'guild_rank', $char['RankIndex']);

		$this->add_value( 'guild_title', $char['Rank']);
		$this->add_value( 'officer_note', $char['OfficerNote']);
		$this->add_value( 'zone', $char['Zone']);
		$this->add_value( 'status', $char['Status']);
		$this->add_time( 'update_time', getDate($currentTimestamp));

		if ($char['Online'])
		{
			$this->add_value( 'online', 1);
			$this->add_time('last_online', getDate($currentTimestamp));
		}
		else
		{
			$this->add_value( 'online', 0);
			$lastOnline = $char['LastOnline'];
			$lastOnlineYears = intval($lastOnline['Year']);
			$lastOnlineMonths = intval($lastOnline['Month']);
			$lastOnlineDays = intval($lastOnline['Day']);
			$lastOnlineHours = intval($lastOnline['Hour']);
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
			if ($lastOnlineHours > 0)
				$timeString .= max($lastOnlineHours,1).' Hours ';

			$lastOnlineTime = strtotime($timeString,$currentTimestamp);
			$this->add_time( 'last_online', getDate($lastOnlineTime));
		}

		if( $memberId )
		{
			$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId' AND `guild_id` = '$guildId'";
			$this->setMessage('<li>Updating member - [ '.$name.' ]</li>');
			$this->membersupdated++;

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError(''.$name.' could not be inserted',$this->error());
				return;
			}
		}
		else
		{
			// Add the guild Id first
			if( !empty($guildId) )
				$this->add_value( 'guild_id', $guildId);

			$querystr = "INSERT INTO `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr;
			$this->setMessage('<li><span class="green">Adding member - [</span> '.$name.' <span class="green">]</span></li>');

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError(''.$name_escape.' could not be inserted',$this->error());
				return;
			}

			$querystr = "SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = '$guildId' AND `name` = '$name_escape' AND `class` = '".$char['Class']."';";
			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Member could not be selected for MemberLog',$this->error());
			}
			else
			{
				$row = $this->fetch_array($result);
				$this->setMemberLog($row,1);
			}
		}
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

			// skip if entry already there
			$querystr = "SELECT `guild` FROM `".ROSTER_PVP2TABLE."` WHERE `index` = '$index' AND `member_id` = '$memberId' AND `name` = '$playerName' AND `date` = '$playerDate'";

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
				$name = $playerInfo['name'];
				$race = $playerInfo['race'];
				$guild = $playerInfo['guild'];
				$class = $playerInfo['class'];
				$enemy = $playerInfo['enemy'];
				$win = $playerInfo['win'];
				$zone = $playerInfo['zone'];
				$subzone = $playerInfo['subzone'];
				$datebattle = $playerInfo['date'];
				$leveldiff = $playerInfo['lvlDiff'];
				$bgflag = $playerInfo['bg'];
				$rank = $playerInfo['rank'];
				$honor = $playerInfo['honor'];

				$this->reset_values();
				if( !empty($memberId) )
					$this->add_value('member_id', $memberId);
				if( !empty($index) )
					$this->add_value('index', $index);
				if( !empty($datebattle) )
					$this->add_pvp2time('date', $datebattle);
				if( !empty($name) )
					$this->add_value('name', $name);
				if( !empty($guild) )
					$this->add_value('guild', $guild);
				if( !empty($race) )
					$this->add_value('race', $race);
				if( !empty($class) )
					$this->add_value('class', $class);
				if( !empty($zone) )
					$this->add_value('zone', $zone);
				if( !empty($subzone) )
					$this->add_value('subzone', $subzone);
				if( !empty($leveldiff) )
					$this->add_value('leveldiff', $leveldiff);
				if( !empty($enemy) )
					$this->add_value('enemy', $enemy);
				if( !empty($win) )
					$this->add_value('win', $win);
				if( !empty($bgflag) )
					$this->add_value('bg', $bgflag);
				if( !empty($rank) )
					$this->add_value('rank', $rank);
				if( !empty($honor) )
					$this->add_value('honor', $honor);

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
	 * Updates/Inserts pets into the db
	 *
	 * @param int $memberId
	 * @param array $data
	 */
	function update_pets( $memberId, $data )
	{
		$petname = $data['Name'];
		$petname_escape = $this->escape($petname);

		if (!empty($petname))
		{
			$querystr = "SELECT `member_id`, `name` FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$memberId' AND `name` LIKE  '".$petname_escape."'";

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Cannot select Pet Data',$this->error());
				return;
			}

			$update = $this->num_rows( $result ) == 1;
			$this->closeQuery($result);

			$this->reset_values();

			$this->add_value( 'member_id', $memberId );
			if( !empty($petname) )
				$this->add_value( 'name', $petname );
			if( !empty($data['Slot']) )
				$this->add_value( 'slot', $data['Slot'] );

			if( !empty($data['Stats']['Intellect']) )
				$this->add_value( 'stat_int', $data['Stats']['Intellect'] );
			if( !empty($data['Stats']['Agility']) )
				$this->add_value( 'stat_agl', $data['Stats']['Agility'] );
			if( !empty($data['Stats']['Stamina']) )
				$this->add_value( 'stat_sta', $data['Stats']['Stamina'] );
			if( !empty($data['Stats']['Strength']) )
				$this->add_value( 'stat_str', $data['Stats']['Strength'] );
			if( !empty($data['Stats']['Spirit']) )
				$this->add_value( 'stat_spr', $data['Stats']['Spirit'] );

			if( !empty($data['Resists']['Frost']) )
			{
				$resist = explode(':', $data['Resists']['Frost']);
				$this->add_value( 'res_frost', $resist[1] );
			}
			if( !empty($data['Resists']['Arcane']) )
			{
				$resist = explode(':', $data['Resists']['Arcane']);
				$this->add_value( 'res_arcane', $resist[1] );
			}
			if( !empty($data['Resists']['Fire']) )
			{
				$resist = explode(':', $data['Resists']['Fire']);
				$this->add_value( 'res_fire', $resist[1] );
			}
			if( !empty($data['Resists']['Shadow']) )
			{
				$resist = explode(':', $data['Resists']['Shadow']);
				$this->add_value( 'res_shadow', $resist[1] );
			}
			if( !empty($data['Resists']['Nature']) )
			{
				$resist = explode(':', $data['Resists']['Nature']);
				$this->add_value( 'res_nature', $resist[1] );
			}

			if( !empty($data['Level']) )
				$this->add_value( 'level', $data['Level'] );
			if( !empty($data['Health']) )
				$this->add_value( 'health', $data['Health'] );
			if( !empty($data['Mana']) )
				$this->add_value( 'mana', $data['Mana'] );
			if( !empty($data['Armor']) )
				$this->add_value( 'armor', $data['Armor'] );
			if( !empty($data['Defense']) )
				$this->add_value( 'defense', $data['Defense'] );
			if( !empty($data['Experience']) )
				$this->add_value( 'xp', $data['Experience'] );
			if( !empty($data['TalentPointsUsed']) )
				$this->add_value( 'usedtp', $data['TalentPointsUsed'] );
			if( !empty($data['TalentPoints']) )
				$this->add_value( 'totaltp', $data['TalentPoints'] );
			if( !empty($data['Type']) )
				$this->add_value( 'type', $data['Type'] );
			if( !empty($data['Loyalty']) )
				$this->add_value( 'loyalty', $data['Loyalty']);
			if( !empty($data['Icon']) )
				$this->add_value( 'icon', str_replace('\\\\','/', $data['Icon']));

			$attack = $data['Melee Attack'];
			if( !empty($attack['AttackPower']) )
				$this->add_value( 'melee_power', $attack['AttackPower'] );
			if( !empty($attack['AttackRating']) )
				$this->add_value( 'melee_rating', $attack['AttackRating'] );
			if( !empty($attack['DamageRange']) )
				$this->add_value( 'melee_range', $attack['DamageRange'] );
			if( !empty($attack['DamageRangeTooltip']) )
				$this->add_value( 'melee_rangetooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
			if( !empty($attack['AttackPowerTooltip']) )
				$this->add_value( 'melee_powertooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );

			$this->setMessage('<li>Updating pet ['.$petname_escape.']</li>');

			if( $update )
				$querystr = "UPDATE `".ROSTER_PETSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId' and name = '".$petname_escape."'";
			else
				$querystr = "INSERT INTO `".ROSTER_PETSTABLE."` SET ".$this->assignstr;

			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Cannot update Pet Data',$this->error());
				return;
			}
		}
	}


	/**
	 * Handles formatting an insertion of Character Data
	 *
	 * @param int $guildId
	 * @param string $name
	 * @param array $data
	 */
	function update_char( $guildId, $name, $data )
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
			return;
		}

		$memberInfo = $this->fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
			$memberId = $memberInfo['member_id'];
		else
		{
			$this->setMessage('<li>'.$name.' is not in the list of guild members so their data will not be inserted.</li>');
			return;
		}

		// Expose this for later functions
		$data['CharName'] = $name;

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
			return;
		}

		$update = $this->num_rows( $result ) == 1;
		$this->closeQuery($result);

		$this->reset_values();

		$this->add_value( 'name', $name );
		$this->add_value( 'guild_id', $guildId );

		// NEW VALES FOR MY WOWPROFILERS ROSTER
		$this->add_value( 'dodge', $data['DodgePercent'] );
		$this->add_value( 'parry', $data['ParryPercent'] );
		$this->add_value( 'block', $data['BlockPercent'] );
		$this->add_value( 'mitigation', $data['MitigationPercent'] );
		$this->add_value( 'crit', $data['CritPercent'] );

		// BEGIN HONOR VALUES
		$this->add_value( 'sessionHK', $data['Honor']['Session']['HK'] );
		$this->add_value( 'sessionDK', $data['Honor']['Session']['DK'] );
		$this->add_value( 'yesterdayHK', $data['Honor']['Yesterday']['HK'] );
		$this->add_value( 'yesterdayDK', $data['Honor']['Yesterday']['DK'] );
		$this->add_value( 'yesterdayContribution', $data['Honor']['Yesterday']['Contribution'] );
		$this->add_value( 'lastweekHK', $data['Honor']['LastWeek']['HK'] );
		$this->add_value( 'lastweekDK', $data['Honor']['LastWeek']['DK'] );
		$this->add_value( 'lastweekContribution', $data['Honor']['LastWeek']['Contribution'] );
		$this->add_value( 'lastweekRank', $data['Honor']['LastWeek']['Rank'] );
		$this->add_value( 'lifetimeHK', $data['Honor']['Lifetime']['HK'] );
		$this->add_value( 'lifetimeDK', $data['Honor']['Lifetime']['DK'] );
		$this->add_value( 'lifetimeRankName', $data['Honor']['Lifetime']['Name'] );
		$this->add_value( 'lifetimeHighestRank', $data['Honor']['Lifetime']['Rank'] );

		$ncc = sscanf($data['Honor']['Current']['Description'], "(%s %d)", $st, $RankInt);
			$this->add_value( 'RankInfo', (int)$RankInt );

		$this->add_value( 'RankName', $data['Honor']['Current']['Rank'] );
		$this->add_value( 'RankIcon', str_replace('\\\\','/', $data['Honor']['Current']['Icon']) );
		$this->add_value( 'Rankexp', $data['Honor']['Current']['Progress'] );
		$this->add_value( 'TWContribution', $data['Honor']['ThisWeek']['Contribution'] );
		$this->add_value( 'TWHK', $data['Honor']['ThisWeek']['HK'] );
		// END HONOR VALUES

		// BEGIN STATS
		$stats = explode(':',$data['Stats']['Intellect']);
		$this->add_value( 'stat_int', $stats[0] );
		$this->add_value( 'stat_int_c', $stats[1] );
		$this->add_value( 'stat_int_b', $stats[2] );
		$this->add_value( 'stat_int_d', $stats[3] );

		$stats = explode(':',$data['Stats']['Agility']);
		$this->add_value( 'stat_agl', $stats[0] );
		$this->add_value( 'stat_agl_c', $stats[1] );
		$this->add_value( 'stat_agl_b', $stats[2] );
		$this->add_value( 'stat_agl_d', $stats[3] );

		$stats = explode(':',$data['Stats']['Stamina']);
		$this->add_value( 'stat_sta', $stats[0] );
		$this->add_value( 'stat_sta_c', $stats[1] );
		$this->add_value( 'stat_sta_b', $stats[2] );
		$this->add_value( 'stat_sta_d', $stats[3] );

		$stats = explode(':',$data['Stats']['Strength']);
		$this->add_value( 'stat_str', $stats[0] );
		$this->add_value( 'stat_str_c', $stats[1] );
		$this->add_value( 'stat_str_b', $stats[2] );
		$this->add_value( 'stat_str_d', $stats[3] );

		$stats = explode(':',$data['Stats']['Spirit']);
		$this->add_value( 'stat_spr', $stats[0] );
		$this->add_value( 'stat_spr_c', $stats[1] );
		$this->add_value( 'stat_spr_b', $stats[2] );
		$this->add_value( 'stat_spr_d', $stats[3] );

		$stats = explode(':',$data['Stats']['Armor']);
		$this->add_value( 'stat_armor', $stats[0] );
		$this->add_value( 'stat_armor_c', $stats[1] );
		$this->add_value( 'stat_armor_b', $stats[2] );
		$this->add_value( 'stat_armor_d', $stats[3] );

		$stats = explode(':',$data['Stats']['Defense']);
		$this->add_value( 'stat_def', $stats[0] );
		$this->add_value( 'stat_def_c', $stats[1] );
		$this->add_value( 'stat_def_b', $stats[2] );
		$this->add_value( 'stat_def_d', $stats[3] );

		unset($stats);
		// END STATS

		// BEGIN RESISTS
		$resist = explode(':',$data['Resists']['Frost']);
		$this->add_value( 'res_frost', $resist[0] );
		$this->add_value( 'res_frost_c', $resist[1] );
		$this->add_value( 'res_frost_b', $resist[2] );
		$this->add_value( 'res_frost_d', $resist[3] );

		$resist = explode(':',$data['Resists']['Arcane']);
		$this->add_value( 'res_arcane', $resist[0] );
		$this->add_value( 'res_arcane_c', $resist[1] );
		$this->add_value( 'res_arcane_b', $resist[2] );
		$this->add_value( 'res_arcane_d', $resist[3] );

		$resist = explode(':',$data['Resists']['Fire']);
		$this->add_value( 'res_fire', $resist[0] );
		$this->add_value( 'res_fire_c', $resist[1] );
		$this->add_value( 'res_fire_b', $resist[2] );
		$this->add_value( 'res_fire_d', $resist[3] );

		$resist = explode(':',$data['Resists']['Shadow']);
		$this->add_value( 'res_shadow', $resist[0] );
		$this->add_value( 'res_shadow_c', $resist[1] );
		$this->add_value( 'res_shadow_b', $resist[2] );
		$this->add_value( 'res_shadow_d', $resist[3] );

		$resist = explode(':',$data['Resists']['Nature']);
		$this->add_value( 'res_nature', $resist[0] );
		$this->add_value( 'res_nature_c', $resist[1] );
		$this->add_value( 'res_nature_b', $resist[2] );
		$this->add_value( 'res_nature_d', $resist[3] );

		unset($resist);
		// END RESISTS

		$this->add_value( 'level', $data['Level'] );
		$this->add_value( 'server', $data['Server'] );
		$this->add_value( 'talent_points', $data['TalentPoints'] );

		$this->add_value( 'money_c', $data['Money']['Copper'] );
		$this->add_value( 'money_s', $data['Money']['Silver'] );
		$this->add_value( 'money_g', $data['Money']['Gold'] );

		$this->add_value( 'exp', $data['XP'] );
		$this->add_value( 'race', $data['Race'] );
		$this->add_value( 'class', $data['Class'] );
		$this->add_value( 'health', $data['Health'] );
		$this->add_value( 'mana', $data['Mana'] );
		$this->add_value( 'sex', $data['Sex'] );
		$this->add_value( 'hearth', $data['Hearth'] );
		$this->add_value( 'dateupdatedutc', $data['DateUTC'] );
		$this->add_value( 'CPversion', $data['DBversion'] );

		if ($data['TimePlayed'] < 0 )
			$this->setMessage('<li>TimePlayed Null, Not updating</li>');
		else
			$this->add_value( 'timeplayed', $data['TimePlayed'] );

		if ($data['TimeLevelPlayed'] < 0 )
			$this->setMessage('<li>TimeLevelPlayed Null, Not updating</li>');
		else
			$this->add_value( 'timelevelplayed', $data['TimeLevelPlayed'] );

		// Capture mailbox update time/date
		$this->add_value( 'maildateutc', $data['MailDateUTC'] );

		$attack = $data['Melee Attack'];
		$this->add_value( 'melee_power', $attack['AttackPower'] );
		$this->add_value( 'melee_rating', $attack['AttackRating'] );
		$this->add_value( 'melee_range', $attack['DamageRange'] );
		$this->add_value( 'melee_range_tooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
		$this->add_value( 'melee_power_tooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );

		$attack = $data['Ranged Attack'];
		$this->add_value( 'ranged_power', $attack['AttackPower'] );
		$this->add_value( 'ranged_rating', $attack['AttackRating'] );
		$this->add_value( 'ranged_range', $attack['DamageRange'] );
		$this->add_value( 'ranged_range_tooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
		$this->add_value( 'ranged_power_tooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );

		unset($attack);

		// Capture client language
		$this->add_value( 'clientLocale', $data['Locale'] );

		$this->setMessage('<li>About to update player</li>');

		if( $update )
		{
			$querystr = "UPDATE `".ROSTER_PLAYERSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId'";
		}
		else
		{
			$this->add_value( 'member_id', $memberId);
			$querystr = "INSERT INTO `".ROSTER_PLAYERSTABLE."` SET ".$this->assignstr;
		}

		$result = $this->query($querystr);
		if( !$result )
		{
			$this->setError('Cannot update Character Data',$this->error());
			return;
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

		// Adding pet info
		if( !empty( $data['Pets'] ) )
		{
			$querystr = "DELETE FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$memberId'";
			$result = $this->query($querystr);
			if( !$result )
			{
				$this->setError('Cannot delete Pet Data',$this->error());
			}

			$petsdata = $data['Pets'];
			foreach( array_keys( $petsdata ) as $pet )
			{
				$petinfo = $petsdata[$pet];
				//print_r( $petinfo );
				//print '</pre>';
				$this->update_pets( $memberId, $petinfo );
			}
		}

		// built in Auth system
		if( isset( $data['Roster'] ) )
		{
			$rosterdata = $data['Roster'];
			$this->update_account( $memberId, $name, $rosterdata);
		}
	} //-END function update_char()

} //-END CLASS

$wowdb = new wowdb;

?>