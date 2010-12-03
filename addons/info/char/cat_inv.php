<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays character information
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 */

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include( $addon['inc_dir'] . 'header.php' );

$items = array();

/* Get items */
if( $roster->auth->getAuthorized($addon['config']['show_bags']) )
{
	$query  = " SELECT *"
			. " FROM `" . $roster->db->table('items') . "`"
			. " WHERE `member_id` = '" . $char->get('member_id') . "'"
			. " AND (`item_parent` LIKE 'Bag%' OR `item_parent` LIKE 'Bank Bag%' OR `item_parent` LIKE 'Mail%')"
			. " AND `item_parent` != 'bags'"
			. " ORDER BY `item_type` ASC, `item_subtype` ASC, `item_rarity` DESC, `item_level` DESC";

	$result = $roster->db->query( $query );

	while( $data = $roster->db->fetch( $result ) )
	{
		$item = new item( $data, 'simple' );

		$type    = $item->data['item_type'];
		$subtype = $item->data['item_subtype'];

		if( !array_key_exists($type, $items) )
		{
			$items[$type] = array();
		}

		if( !array_key_exists($subtype, $items[$type]) )
		{
			$items[$type][$subtype] = array();
		}

		$items[$type][$subtype][] = $item;
	}
}

/* Output data */
foreach( $items as $type => $category )
{
	$roster->tpl->assign_block_vars( 'type', array(
		'NAME' => $type,
		'DIRECTS' => array_key_exists($type, $category),
		)
       	);

	/* If $type == $subtype, there actually isn't a subcategory */
	if( array_key_exists($type, $category) )
	{
		foreach( $category[$type] as $item )
		{
			$roster->tpl->assign_block_vars('type.item',array(
				'ICON'     => $item->tpl_get_icon(),
				'TOOLTIP'  => $item->tpl_get_tooltip(),
				'ITEMLINK' => $item->tpl_get_itemlink(),
				'QUALITY'  => $item->quality,
				'QTY'      => $item->quantity
				)
			);
		}
	}

	/* Subcategories */
	foreach( $category as $subtype => $subcategory )
	{
		if( $type == $subtype )
		{
			continue;
		}

		$roster->tpl->assign_block_vars( 'type.subtype', array("NAME" => $subtype) );

		foreach( $subcategory as $item )
		{
			$roster->tpl->assign_block_vars('type.subtype.item',array(
				'ICON'     => $item->tpl_get_icon(),
				'TOOLTIP'  => $item->tpl_get_tooltip(),
				'ITEMLINK' => $item->tpl_get_itemlink(),
				'QUALITY'  => $item->quality,
				'QTY'      => $item->quantity
				)
			);
		}
	}
}

$roster->tpl->set_filenames(array('cat_inv' => $addon['basename'] . '/cat_inv.html'));

$roster->tpl->display('cat_inv');
