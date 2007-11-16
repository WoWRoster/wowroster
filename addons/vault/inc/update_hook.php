<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Vault
 * @subpackage UpdateHook
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Addon Update class
 * This MUST be the same name as the addon basename
 *
 * @package    Vault
 * @subpackage UpdateHook
 */
class vaultUpdate
{
	var $messages = '';		// Update messages
	var $data = array();	// Addon config data automatically pulled from the addon_config table
	var $files = array();
	var $timestamp = '';

	function vaultUpdate($data)
	{
		$this->data = $data;
	}

	/**
	 * Guild post trigger: process vault items
	 *
	 * @param array $guild
	 *		CP.lua guild data
	 */
	function guild_post( $guild )
	{
		global $roster, $update;

		$this->timestamp = strtotime($guild['timestamp']['init']['DateUTC']);

		$this->reset_messages();

		$this->messages .= '<strong>Vault:</strong><ul>';

		$guildid = $update->get_guild_info($update->current_realm,$update->current_guild,$update->current_region);
		$guildid = $guildid['guild_id'];

		// Update Vault Inventory
		if( isset($guild['Vault']['Tabs']) )
		{
			$vault = $guild['Vault']['Tabs'];
		}

		if( !empty($vault) && is_array($vault) )
		{
			$this->messages .= '<li>Items';

			// Clearing out old items
			$querystr = "DELETE FROM `" . $roster->db->table('items',$this->data['basename']) . "` WHERE `guild_id` = '$guildid';";
			if( !$roster->db->query($querystr) )
			{
				$update->setError('Vault could not be cleared',$roster->db->error());
				return false;
			}

			foreach( array_keys( $vault ) as $tab_name )
			{
				$this->messages .= " : $tab_name";

				$tab = $vault[$tab_name];
				if( is_null($tab) || !is_array($tab) || empty($tab) )
				{
					continue;
				}

				$item = $this->make_item( $tab, $guildid, 'vault', $tab_name );

				// quantity for a bag means number of slots it has
				$item['item_quantity'] = '98';
				$this->insert_item( $item,$guild['Locale'] );

				if (isset($tab['Contents']) && is_array($tab['Contents']))
				{
					foreach( array_keys( $tab['Contents'] ) as $slot_name )
					{
						$slot = $tab['Contents'][$slot_name];
						if( is_null($slot) || !is_array($slot) || empty($slot) )
						{
							continue;
						}
						$item = $this->make_item( $slot, $guildid, $tab_name, $slot_name );
						$this->insert_item( $item,$guild['Locale'] );
					}
				}
			}
			$this->messages .= '</li>';
		}
		else
		{
			$this->messages .= '<li>No Items</li>';
		}


		// Update Vault Log
		if( isset($guild['Vault']['Log']) )
		{
			$log = $guild['Vault']['Log'];
		}

		if( !empty($log) && is_array($log) )
		{
			$this->messages .= '<li>Log';

			// Clearing out old log
			$querystr = "DELETE FROM `" . $roster->db->table('log',$this->data['basename']) . "` WHERE `guild_id` = '$guildid';";
			if( !$roster->db->query($querystr) )
			{
				$update->setError('Vault Log could not be cleared',$roster->db->error());
				return false;
			}

			foreach( array_keys( $log ) as $tab_name )
			{
				$this->messages .= " : $tab_name";

				$tab = $log[$tab_name];
				if( is_null($tab) || !is_array($tab) || empty($tab) )
				{
					continue;
				}

				foreach( array_keys( $tab ) as $tab_number )
				{
					$slot = $tab[$tab_number];
					if( is_null($slot) || !is_array($slot) || empty($slot) )
					{
						continue;
					}
					$this->insert_log($slot,$guildid,$tab_name,$tab_number);
				}
			}
			$this->messages .= '</li>';
		}
		else
		{
			$this->messages .= '<li>No Log</li>';
		}


		// Update Vault Funds
		if( isset($guild['Vault']['Money']) )
		{
			$money = $guild['Vault']['Money'];
		}

		if( !empty($money) && is_array($money) )
		{
			$this->messages .= '<li>Funds</li>';

			// Clearing out old funds
			$querystr = "DELETE FROM `" . $roster->db->table('money',$this->data['basename']) . "` WHERE `guild_id` = '$guildid';";
			if( !$roster->db->query($querystr) )
			{
				$update->setError('Vault Funds could not be cleared',$roster->db->error());
				return false;
			}

			$update->reset_values();
			$update->add_value( 'guild_id', $guildid );
			$update->add_ifvalue($money, 'Copper', 'money_c', '0' );
			$update->add_ifvalue($money, 'Silver', 'money_s', '0' );
			$update->add_ifvalue($money, 'Gold', 'money_g', '0' );

			$querystr = "INSERT INTO `" . $roster->db->table('money',$this->data['basename']) . "` SET " . $update->assignstr;
			$result = $roster->db->query($querystr);
			if( !$result )
			{
				$update->setError('Vault: Funds could not be inserted',$roster->db->error());
			}
		}
		else
		{
			$this->messages .= '<li>No Vault Funds</li>';
		}
		return true;
	}

	/**
	 * Update trigger: remove deleted members from the pvp2 table
	 *
	 * @param array $guild
	 *		CP.lua guild data
	 */
	function update( $guild )
	{
		global $roster;

		$query = "DELETE `" . $roster->db->table('item',$this->data['basename']) . "`"
			   . " FROM `" . $roster->db->table('item',$this->data['basename']) . "`"
			   . " LEFT JOIN `" . $roster->db->table('guild') . "` USING (`guild_id`)"
			   . " WHERE `" . $roster->db->table('guild') . "`.`guild_id` IS NULL;";

		if( $roster->db->query($query) )
		{
			$this->messages .= 'Vault: ' . $roster->db->affected_rows() . ' records without matching guild records deleted';
		}
		else
		{
			$this->messages .= 'Vault: <span style="color:red;">Old records not deleted. MySQL said: ' . $roster->db->error() . "</span><br />\n";
			return false;
		}

		$query = "DELETE `" . $roster->db->table('log',$this->data['basename']) . "`"
			   . " FROM `" . $roster->db->table('log',$this->data['basename']) . "`"
			   . " LEFT JOIN `" . $roster->db->table('guild') . "` USING (`guild_id`)"
			   . " WHERE `" . $roster->db->table('guild') . "`.`guild_id` IS NULL;";

		if( !$roster->db->query($query) )
		{
			$this->messages .= 'Vault: <span style="color:red;">Old log records not deleted. MySQL said: ' . $roster->db->error() . "</span><br />\n";
			return false;
		}

		$query = "DELETE `" . $roster->db->table('money',$this->data['basename']) . "`"
			   . " FROM `" . $roster->db->table('money',$this->data['basename']) . "`"
			   . " LEFT JOIN `" . $roster->db->table('guild') . "` USING (`guild_id`)"
			   . " WHERE `" . $roster->db->table('guild') . "`.`guild_id` IS NULL;";

		if( !$roster->db->query($query) )
		{
			$this->messages .= 'Vault: <span style="color:red;">Old money records not deleted. MySQL said: ' . $roster->db->error() . "</span><br />\n";
			return false;
		}

		return true;
	}


	/**
	 * Formats item data to be inserted into the db
	 *
	 * @param array $item_data
	 * @param int $guildid
	 * @param string $parent
	 * @param string $slot_name
	 * @return array
	 */
	function make_item( $item_data, $guildid, $parent, $slot_name )
	{
		global $update;

		$item = array();
		$item['guild_id'] = $guildid;
		$item['item_name'] = $item_data['Name'];
		$item['item_parent'] = $parent;
		$item['item_slot'] = $slot_name;
		$item['item_color'] = ( isset($item_data['Color']) ? $item_data['Color'] : 'ffffff' );
		$item['item_id'] = ( isset($item_data['Item']) ? $item_data['Item'] : '0:0:0:0:0:0:0:0' );
		$item['item_texture'] = ( isset($item_data['Icon']) ? $update->fix_icon($item_data['Icon']) : 'inv_misc_questionmark');

		if( isset( $item_data['Quantity'] ) )
		{
			$item['item_quantity'] = $item_data['Quantity'];
		}
		else
		{
			$item['item_quantity'] = 1;
		}

		if( !empty($item_data['Tooltip']) )
		{
			$item['item_tooltip'] = $update->tooltip( $item_data['Tooltip'] );
		}
		else
		{
			$item['item_tooltip'] = $item_data['Name'];
		}

		if( !empty($item_data['Gem']))
		{
			$update->do_gems($item_data['Gem'], $item_data['Item']);
		}

		return $item;
	}


	/**
	 * Inserts an item into the database
	 *
	 * @param string $item
	 * @return bool
	 */
	function insert_item( $item , $locale )
	{
		global $roster, $update;

		$update->reset_values();
		$update->add_ifvalue( $item, 'guild_id' );
		$update->add_ifvalue( $item, 'item_name' );
		$update->add_ifvalue( $item, 'item_parent' );
		$update->add_ifvalue( $item, 'item_slot' );
		$update->add_ifvalue( $item, 'item_color' );
		$update->add_ifvalue( $item, 'item_id' );
		$update->add_ifvalue( $item, 'item_texture' );
		$update->add_ifvalue( $item, 'item_tooltip' );
		$update->add_value( 'locale', $locale );

		$level = array();
		if( preg_match($roster->locale->wordings[$locale]['requires_level'],$item['item_tooltip'],$level))
		{
			$update->add_value('level',$level[1]);
		}

		$update->add_ifvalue( $item, 'item_quantity' );

		$querystr = "INSERT INTO `" . $roster->db->table('items',$this->data['basename']) . "` SET " . $update->assignstr;
		$result = $roster->db->query($querystr);
		if( !$result )
		{
			$update->setError('Vault: Item [' . $item['item_name'] . '] could not be inserted',$roster->db->error());
		}
	}


	/**
	 * Formats item data to be inserted into the db
	 *
	 * @param array $data
	 * @param int $guildid
	 * @param string $tab_name
	 * @param string $tab_number
	 * @return array
	 */
	function insert_log( $data, $guildid, $tab_name, $tab_number )
	{
		global $roster, $update;

		$update->reset_values();
		$update->add_value( 'log_id', $tab_number );
		$update->add_value( 'guild_id', $guildid );
		$update->add_ifvalue($data,'Name','member');
		$update->add_ifvalue($data,'Type','type');
		$update->add_ifvalue($data,'Count','count');
		$update->add_ifvalue($data,'Item','item_id');
		$update->add_ifvalue($data,'Amount','amount');
		$update->add_value( 'parent', $tab_name );
		$this->make_time($data['Time']);

		$querystr = "INSERT INTO `" . $roster->db->table('log',$this->data['basename']) . "` SET " . $update->assignstr;
		$result = $roster->db->query($querystr);
		if( !$result )
		{
			$update->setError('Vault: log data could not be inserted',$roster->db->error());
		}
	}

	function make_time( $time )
	{
		global $update;

		list($lastOnlineYears,$lastOnlineMonths,$lastOnlineDays,$lastOnlineHours) = explode(':',$time);

		$timeString = '-';
		if ($lastOnlineYears > 0)
		{
			$timeString .= $lastOnlineYears . ' Years ';
		}
		if ($lastOnlineMonths > 0)
		{
			$timeString .= $lastOnlineMonths . ' Months ';
		}
		if ($lastOnlineDays > 0)
		{
			$timeString .= $lastOnlineDays . ' Days ';
		}
		$timeString .= max($lastOnlineHours,1) . ' Hours';

		$lastOnlineTime = strtotime($timeString,$this->timestamp);
		$update->add_time( 'time', getDate($lastOnlineTime) );
	}

	/**
	 * Resets addon messages
	 */
	function reset_messages()
	{
		$this->messages = '';
	}
}
