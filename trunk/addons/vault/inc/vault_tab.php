<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character Bag class, extends item class
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

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once( ROSTER_LIB . 'item.php');

class VaultTab extends VaultItem
{
	var $contents;

	function VaultTab( $data )
	{
		$this->VaultItem( $data );
		$this->contents = $this->fetchManyItems($this->data['guild_id'], $this->data['item_slot']);
	}

	function out( )
	{
		global $roster, $addon, $tooltips;

		$lang = $this->data['locale'];

		$returnstring = '<div id="' . $this->data['item_slot'] . 'Items" class="vaulttab" style="display:none;">';

		// Select all item for this bag

		$returnstring .= '<div class="itemcol1">' . "\n";

		$icon_num = 0;
		for( $slot = 0; $slot < $this->data['item_quantity'] ; $slot++ )
		{
			if( $icon_num != 0 && $icon_num%7 == 0 )
			{
				$returnstring .= "</div>\n<div class=\"itemcol" . ($icon_num%2 == 0 ? '1' : '2') . "\">\n";
			}

			if( $slot < 0 )
			{
				$returnstring .=  '			<div class="item"><img src="' . $roster->config['img_url'] . 'pixel.gif" class="noicon" alt="" /></div>' . "\n";
			}
			else
			{
				if( isset($this->contents[$slot+1]) )
				{
					$item = $this->contents[$slot+1];
					$returnstring .= $item->out();
				}
				else
				{
					$returnstring .=  '			<div class="item"><img src="' . $roster->config['img_url'] . 'pixel.gif" class="noicon" alt="" /></div>' . "\n";
				}
			}
			$icon_num++;
		}

		$returnstring .= "</div>\n</div>\n";

		return $returnstring;
	}
}

function vault_tab_get( $guild_id, $slot )
{
	$item = VaultItem::fetchOneItem( $guild_id, $slot );
	if( $item )
	{
		return new VaultTab( $item->data );
	}
	else
	{
		return null;
	}
}
