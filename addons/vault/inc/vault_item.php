<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Vault Item class, extends item class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Vault
 * @subpackage VaultItem
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

require_once(ROSTER_LIB . 'item.php');

/**
 * Vault Item class, extends item class
 *
 * @package    Vault
 * @subpackage VaultItem
 */
class VaultItem extends item
{
	/**
	 * Constructor
	 *
	 * @param array $data
	 * @param string $parse_mode | accepts 'full' full item parsing, 'simple' for simple item coloring only.  Defaults to auto detect
	 *
	 */
	function VaultItem( $data, $parse_mode=false )
	{
		global $roster;

		$this->isParseMode = $parse_mode;
		$this->data = $data;
		$this->member_id = $data['guild_id'];
		$this->item_id = $data['item_id'];
		$this->name = $data['item_name'];
		$this->level = $data['item_level'];
		$this->icon = $data['item_texture'];
		$this->slot = $data['item_slot'];
		$this->parent = $data['item_parent'];
		$this->tooltip = $data['item_tooltip'];
		$this->color = $data['item_color'];
		$this->locale = ( isset($data['locale']) ? $data['locale'] : $roster->config['locale'] );
		$this->quantity = $data['item_quantity'];
		$this->_setQuality($this->color);
		$this->_doParseTooltip();
		$this->_makeTooltipHTML();
	}

	function _fetchArmorSet( $pieces=array(), $guild_id='' )
	{
		global $roster, $addon;

		$count = count($pieces);
		$guild_id = ( is_numeric($guild_id) ? $guild_id : $this->member_id );

		if( $count && is_array($pieces) )
		{
			global $roster;

			$i = 1; // loop count
			$sql_in = "('";
			foreach( $pieces as $item )
			{
				$sql_in .= $roster->db->escape( $item['Name'] );
				if( $i < $count )
				{
					$sql_in .= "', '";
				}
				$i++;
			}
			$sql_in .= "')";

			if( $roster->cache->mcheck($sql_in) )
			{
				return $roster->cache->mget($sql_in);
			}

			$sql = "SELECT `item_name`, `item_parent` FROM"
				 . " `" . $roster->db->table('items',$addon['basename']) . "`"
				 . " WHERE `guild_id` = '$guild_id'"
				 . " AND `item_name` IN $sql_in ";
			$result = $roster->db->query($sql);

			while( $data = $roster->db->fetch( $result ) )
			{
				if( $data['item_parent'] == 'equip')
				{
					$armor_set['equip'][] = $data['item_name'];
				}
				else
				{
					$armor_set['owned'][] = $data['item_name'];
				}
			}
			$roster->cache->mput($armor_set, $sql_in);
			return $armor_set;
		}
		return false;
	}

	/**
	 * Fetches passed named item from the database. First Match is used.
	 *
	 * @param unknown_type $item_name
	 * @param unknown_type $parse_mode
	 * @return unknown
	 */
	function fetchNamedItem( $name, $parse_mode=false )
	{
		global $roster, $addon;

		$name = $roster->db->escape( $name );
		$sql = " SELECT *"
			 . " FROM `" . $roster->db->table('items',$addon['basename']) . "`"
			 . " WHERE `item_name` LIKE '%$name%'"
			 . " LIMIT 1";
		$result = $roster->db->query( $sql );
		$data = $roster->db->fetch( $result );
		if( $data )
		{
			return new VaultItem( $data, $parse_mode );
		}
		else
		{
			return false;
		}
	}

	function fetchOneItem( $guild_id, $slot, $parse_mode=false )
	{
		global $roster, $addon;

		$slot = $roster->db->escape( $slot );
		$query 	= " SELECT *"
				. " FROM `" . $roster->db->table('items',$addon['basename']) . "`"
				. " WHERE `guild_id` = '$guild_id'"
				. " AND `item_slot` = '$slot'";

		$result = $roster->db->query( $query );
		$data = $roster->db->fetch( $result );
		if( $data )
		{
			return new VaultItem( $data, $parse_mode );
		}
		else
		{
			return null;
		}
	}

	/**
	 * fetch all $parent items on $guild_id
	 * returns object array keyed by item_slot
	 *
	 * @param int $guild_id
	 * @param string $parent
	 * @param string $parse_mode
	 * @return object[]
	 */
	function fetchManyItems( $guild_id, $parent, $parse_mode=false )
	{
		global $roster, $addon;

		$parent = $roster->db->escape( $parent );
		$items = array();

		$query  = " SELECT *"
				. " FROM `" . $roster->db->table('items',$addon['basename']) . "`"
				. " WHERE `guild_id` = '$guild_id'"
				. " AND `item_parent` = '$parent'";

		$result = $roster->db->query( $query );

		while( $data = $roster->db->fetch( $result ) )
		{
			$item = new VaultItem( $data, $parse_mode );
			$items[$data['item_slot']] = $item;
		}
		return $items;
	}
} //end class item
