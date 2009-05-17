<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character Bag class, extends item class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    CharacterInfo
 * @subpackage Bag
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once( ROSTER_LIB . 'item.php');

/**
 * Character Bag class, extends item class
 *
 * @package    CharacterInfo
 * @subpackage Item
 */
class bag extends item
{
	var $contents;

	function bag( $data )
	{
		$this->item( $data );
		$this->contents = $this->fetchManyItems($this->data['member_id'], $this->data['item_slot'], 'simple');
		if( $this->data['item_quantity'] < count($this->contents) )
		{
			$this->data['item_quantity'] = count($this->contents);
			if( $this->data['item_quantity'] % 2 )
			{
				$this->data['item_quantity']++;
			}
			if( $this->data['item_quantity'] < 4 )
			{
				$this->data['item_quantity'] = 4;
			}
		}
	}

	function out( )
	{
		global $roster, $addon, $tooltips;

		$lang = $this->data['locale'];

		$bag_type = ( strpos($this->data['item_slot'],'Bank') !== false ? 'bank' : 'bag');
		$bag_type = ( $this->data['item_slot'] == 'Bag5' ? 'key' : $bag_type);

		$bag_style = $this->data['item_quantity'] % 4;

		if( $bag_style == 0 )
		{
			$offset = (($this->data['item_quantity'] / 4) * 41);
			$offset += 42;
		}
		elseif( $bag_style == 2 )
		{
			$offset = ((($this->data['item_quantity'] - 2)+1) / 4) * 41;
			$offset += 53;
		}

		// Item links
		list($item_id) = explode(':', $this->item_id);
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';
		foreach( $roster->locale->wordings[$lang]['itemlinks'] as $key => $ilink )
		{
			$linktip .= '<a href="' . $ilink . $item_id . '" target="_blank">' . $key . '</a><br />';
		}
		setTooltip($num_of_tips,$linktip);
		setTooltip('itemlink',$roster->locale->wordings[$lang]['itemlink']);

		$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		$roster->tpl->assign_block_vars('bag',array(
			'NAME' => $this->data['item_name'],
			'SLOT' => $this->data['item_slot'],
			'TYPE' => $bag_type,
			'STYLE' => $bag_style,
			'OFFSET' => $offset,
			'ICON' => $this->data['item_texture'],
			'TOOLTIP' => makeOverlib($this->data['item_tooltip'],'',$this->data['item_color'],0,$lang),
			'LINKTIP' => $linktip,
			)
		);

		// Select all item for this bag
		for( $slot = 0; $slot < $this->data['item_quantity'] ; $slot++ )
		{
			if( isset($this->contents[$slot+1]) )
			{
				$item = $this->contents[$slot+1];

				$roster->tpl->assign_block_vars('bag.item',array(
					'ICON'     => $item->tpl_get_icon(),
					'TOOLTIP'  => $item->tpl_get_tooltip(),
					'ITEMLINK' => $item->tpl_get_itemlink(),
					'QTY'      => $item->quantity
					)
				);
			}
			else
			{
				$roster->tpl->assign_block_vars('bag.item',array(
					'ICON'     => $roster->config['img_url'] . 'pixel.gif',
					'TOOLTIP'  => '',
					'ITEMLINK' => '',
					'QTY'      => 0
					)
				);
			}
		}
	}
}

function bag_get( $member_id, $slot )
{
	$item = item::fetchOneItem( $member_id, $slot );
	if( $item )
	{
		return new bag( $item->data );
	}
	else
	{
		return null;
	}
}
