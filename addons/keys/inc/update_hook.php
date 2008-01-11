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
 * @package    InstanceKeys
 * @subpackage UpdateHook
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * InstanceKeys Update Hook
 *
 * @package    InstanceKeys
 * @subpackage UpdateHook
 */
class keysUpdate
{
	// Update messages
	var $messages = '';

	// Addon data object, recieved in constructor
	var $data;

	// LUA upload files accepted. We don't use any.
	var $files = array();

	/**
	 * Constructor
	 *
	 * @param array $data
	 *		Addon data object
	 */
	function keysUpdate($data)
	{
		$this->data = $data;
	}

	/**
	 * Resets addon messages
	 */
	function reset_messages()
	{
		/**
		 * We display the addon name at the beginning of the output line. If
		 * the hook doesn't exist on this side, nothing is output. If we don't
		 * produce any output (update method off) we empty this before returning.
		 */

		$this->messages = 'InstanceKeys';
	}

	/**
	 * Char trigger
	 *
	 * @param array $char
	 *		CP.lua character data
	 * @param int $member_id
	 *		Member ID
	 */
	function char($char, $member_id)
	{
		global $roster;

		// Presetup structure
		$keystuff = array( 'G' => array(), 'Ii' => array(), 'In' => array(), 'Q' => array(), 'R' => array(), 'S' => array() );

		// Gold
		if( isset( $char['Money'] ) )
		{
			$keystuff['G']['money'] = 0;
			if( isset( $char['Money']['Gold'] ) )
			{
				$keystuff['G']['money'] += 10000 * $char['Money']['Gold'];
			}
			if( isset( $char['Money']['Silver'] ) )
			{
				$keystuff['G']['money'] += 100 * $char['Money']['Silver'];
			}
			if( isset( $char['Money']['Copper'] ) )
			{
				$keystuff['G']['money'] += 1 * $char['Money']['Copper'];
			}
		}
		// Items from equipment
		if( isset( $char['Equipment'] ) )
		{
			foreach( $char['Equipment'] as $item )
			{
				$quantity = isset($item['Quantity']) ? $item['Quantity'] : 1;
				if( isset( $item['Item'] ) )
				{
					list($itemID) = explode(':',$item['Item']);
					if( isset( $keystuff['Ii'][$itemID] ) )
					{
						$keystuff['Ii'][$itemID] += $quantity;
					}
					else
					{
						$keystuff['Ii'][$itemID] = $quantity;
					}
				}
				if( isset( $item['Name'] ) )
				{
					if( isset( $keystuff['In'][$item['Name']] ) )
					{
						$keystuff['In'][$item['Name']] += $quantity;
					}
					else
					{
						$keystuff['In'][$item['Name']] = $quantity;
					}
				}
			}
		}
		// Items from bags
		if( isset( $char['Inventory'] ) )
		{
			foreach( $char['Inventory'] as $bag )
			{
				if( isset( $bag['Item'] ) )
				{
					list($itemID) = explode(':',$bag['Item']);
					if( isset( $keystuff['Ii'][$itemID] ) )
					{
						$keystuff['Ii'][$itemID]++;
					}
					else
					{
						$keystuff['Ii'][$itemID] = 1;
					}
				}
				if( isset( $bag['Name'] ) )
				{
					if( isset( $keystuff['In'][$bag['Name']] ) )
					{
						$keystuff['In'][$bag['Name']]++;
					}
					else
					{
						$keystuff['In'][$bag['Name']] = 1;
					}
				}
				foreach( $bag['Contents'] as $item )
				{
					$quantity = isset($item['Quantity']) ? $item['Quantity'] : 1;
					if( isset( $item['Item'] ) )
					{
						list($itemID) = explode(':',$item['Item']);
						if( isset( $keystuff['Ii'][$itemID] ) )
						{
							$keystuff['Ii'][$itemID] += $quantity;
						}
						else
						{
							$keystuff['Ii'][$itemID] = $quantity;
						}
					}
					if( isset( $item['Name'] ) )
					{
						if( isset( $keystuff['In'][$item['Name']] ) )
						{
							$keystuff['In'][$item['Name']] += $quantity;
						}
						else
						{
							$keystuff['In'][$item['Name']] = $quantity;
						}
					}
				}
			}
		}
		// Items from bank
		if( isset( $char['Bank'] ) )
		{
			foreach( $char['Bank'] as $item )
			{
				if( isset( $bag['Item'] ) )
				{
					list($itemID) = explode(':',$bag['Item']);
					if( isset( $keystuff['Ii'][$itemID] ) )
					{
						$keystuff['Ii'][$itemID]++;
					}
					else
					{
						$keystuff['Ii'][$itemID]++;
					}
				}
				if( isset( $bag['Name'] ) )
				{
					if( isset( $keystuff['In'][$bag['Name']] ) )
					{
						$keystuff['In'][$bag['Name']]++;
					}
					else
					{
						$keystuff['In'][$bag['Name']]++;
					}
				}
				foreach( $bag['Contents'] as $item )
				{
					$quantity = isset($item['Quantity']) ? $item['Quantity'] : 1;
					if( isset( $item['Item'] ) )
					{
						list($itemID) = explode(':',$item['Item']);
						if( isset( $keystuff['Ii'][$itemID] ) )
						{
							$keystuff['Ii'][$itemID] += $quantity;
						}
						else
						{
							$keystuff['Ii'][$itemID] = $quantity;
						}
					}
					if( isset( $item['Name'] ) )
					{
						if( isset( $keystuff['In'][$item['Name']] ) )
						{
							$keystuff['In'][$item['Name']] += $quantity;
						}
						else
						{
							$keystuff['In'][$item['Name']] = $quantity;
						}
					}
				}
			}
		}
		// Quests
		if( isset( $char['Quests'] ) )
		{
			foreach( $char['Quests'] as $zone )
			{
				foreach( $zone as $quest )
				{
					if( isset( $quest['Title'] ) )
					{
						if( isset( $keystuff['Q'][$quest['Title']] ) )
						{
							$keystuff['Q'][$quest['Title']]++;
						}
						else
						{
							$keystuff['Q'][$quest['Title']] = 1;
						}
					}
				}
			}
		}
		// Reputation
		if( isset( $char['Reputation'] ) )
		{
			foreach( $char['Reputation'] as $coalition => $factions )
			{
				// There's a count field inbetween...
				if( !is_array($factions) )
				{
					continue;
				}
				foreach( $factions as $faction => $data )
				{
					if( isset( $data['Standing'] ) )
					{
						$keystuff['R'][$faction] = $roster->locale->wordings[$char['Locale']]['rep2level'][$data['Standing']];
					}
				}
			}
		}
		// Skills
		if( isset( $char['Skills'] ) )
		{
			foreach( $char['Skills'] as $category => $skills )
			{
				foreach( $skills as $skill => $data )
				{
					if( $skill == 'Order' )
					{
						continue;
					}
					list( $level, $max ) = explode( ':', $data );
					$keystuff['S'][$skill] = $level;
				}
			}
		}

		/**
		 * We now have all of the data in an easy to check structure:
		 * Quests in $keystuff['Q'][]
		 * Items by ID in $keystuff['Ii'][]
		 * Items by name in $keystuff['In'][]
		 * Reputation levels in $keystuff['R'][]
		 */
		$query = "DELETE FROM `" . $roster->db->table('keycache', $this->data['basename']) . "` WHERE `member_id` = '" . $member_id . "';";
		$roster->db->query($query);

		foreach( $roster->locale->wordings[$char['Locale']]['inst_keys'][substr($char['Faction'],0,1)] as $key => $stages )
		{
			foreach( $stages as $stage_nr => $stage )
			{
				if( isset($keystuff[$stage['type']][$stage['value']]) && ($keystuff[$stage['type']][$stage['value']] >= $stage['count'] ))
				{
					$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` "
						. "(`member_id`, `key_name`, `stage`) VALUES "
						. "(" . $member_id . ",'" . $key . "'," . $stage_nr . ");";
					$roster->db->query($query);
				}
			}
		}

		return true;
	}
}
