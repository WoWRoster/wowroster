<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Vault Tab class, extends item class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    Vault
 * @subpackage VaultTab
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once( ROSTER_LIB . 'item.php');

/**
 * Vault Tab class, extends item class
 *
 * @package    Vault
 * @subpackage VaultTab
 */
class VaultTab extends VaultItem
{
	var $contents;

	function VaultTab( $data )
	{
		$this->VaultItem($data);
		$this->contents = $this->fetchManyItems($this->data['guild_id'], $this->data['item_slot']);
	}

	function out( )
	{
		global $roster, $addon, $tooltips;

		$lang = $this->data['locale'];

		$roster->tpl->assign_block_vars('vault',array(
			'NAME'    => $this->data['item_name'],
			'SLOT'    => $this->data['item_slot'],
			'LINK'    => makelink('#' . $this->data['item_slot']),
			'ICON'    => $this->tpl_get_icon(),
			'TOOLTIP' => $this->tpl_get_tooltip()
			)
		);

		// Select all items for this bag
		$icon_num = $col_num = 0;
		for( $slot = 0; $slot < $this->data['item_quantity'] ; $slot++ )
		{
			if( $icon_num == 0 || $icon_num % 7 == 0 )
			{
				$roster->tpl->assign_block_vars('vault.column',array(
					'ID' => $col_num++,
					)
				);
			}

			if( isset($this->contents[$slot+1]) )
			{
				$item = $this->contents[$slot+1];

				$roster->tpl->assign_block_vars('vault.column.item',array(
					'ICON'     => $item->tpl_get_icon(),
					'TOOLTIP'  => $item->tpl_get_tooltip(),
					'ITEMLINK' => $item->tpl_get_itemlink(),
					'QUALITY'  => $item->quality,
					'QTY'      => $item->quantity
					)
				);
			}
			else
			{
				$roster->tpl->assign_block_vars('vault.column.item',array(
					'ICON'     => '',
					'TOOLTIP'  => '',
					'ITEMLINK' => '',
					'QUALITY'  => 'none',
					'QTY'      => 0
					)
				);
			}
			$icon_num++;
		}
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
