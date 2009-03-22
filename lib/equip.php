<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Item class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage Item
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

require_once (ROSTER_LIB . 'item.php');

/**
 * Item class
 *
 * @package    WoWRoster
 * @subpackage Item
 */
class equip extends item
{
	function out( )
	{
		global $roster, $tooltips;

		$lang = ( isset($this->locale) ? $this->locale : $roster->config['locale'] );
		$path = $roster->config['interface_url'] . 'Interface/Icons/' . $this->icon . '.' . $roster->config['img_suffix'];
		$tooltip = makeOverlib($this->html_tooltip, '', '' , 2, '', ', WIDTH, 325');
		list($item_id) = explode(':', $this->item_id);
		// Item links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';

		foreach( $roster->locale->wordings[$lang]['itemlinks'] as $key => $ilink )
		{
			//$linktip .= '<a href="' . $ilink . urlencode(utf8_decode($this->data['item_name'])) . '" target="_blank">' . $key . '</a><br />';
			$linktip .= '<a href="' . $ilink . $item_id . '" target="_blank">' . $key . '</a><br />';
		}
		setTooltip($num_of_tips, $linktip);
		setTooltip('itemlink', $roster->locale->wordings[$lang]['itemlink']);

		$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		$output = '<div style="position: relative; float: left;">';

		if( $this->slot == 'Ammo' )
		{
			$output .= '<img src="' . $path . '" class="iconsmall" alt="" />' . "\n";
		}
		else
		{
			$output .= '<img src="' . $path . '" class="icon" alt="" />' . "\n";
		}

		$output .= '<div class="item" ' . $tooltip . $linktip . '>';

		if( ($this->quantity > 1) )
		{
			$output .= '<b>' . $this->quantity . '</b>';
			$output .= '<span>' . $this->quantity . '</span>';
		}
		$output .= '</div></div>';

		return $output;
	}

	/**
	 * fetch all equip items on $member_id
	 * returns object array keyed by item_slot
	 *
	 * @param int $member_id
	 * @param string $parse_mode
	 * @return object[]
	 */
	function fetchManyEquip( $member_id, $parse_mode=false )
	{
		global $roster;

		$items = array();
		$this->gemColors = array( 'red' => 0, 'yellow' => 0, 'blue' => 0 );

		$query  = " SELECT *"
				. " FROM `" . $roster->db->table('items') . "`"
				. " WHERE `member_id` = '$member_id'"
				. " AND `item_parent` = 'equip'";

		$result = $roster->db->query( $query );

		while( $data = $roster->db->fetch( $result ) )
		{
			$item = new equip( $data, $parse_mode );
			$items[$data['item_slot']] = $item;
			if( isset($item->attributes['Gems']) )
			{
				foreach( $item->attributes['Gems'] as $gem )
				{
					switch( $gem['Color'] )
					{
						case 'blue':
							$this->gemColors['blue']++;
							break;
						case 'red':
							$this->gemColors['red']++;
							break;
						case 'yellow':
							$this->gemColors['yellow']++;
							break;
						case 'orange':
							$this->gemColors['yellow']++;
							$this->gemColors['red']++;
							break;
						case 'purple':
							$this->gemColors['blue']++;
							$this->gemColors['red']++;
							break;
						case 'green':
							$this->gemColors['yellow']++;
							$this->gemColors['blue']++;
							break;
					}
				}
			}
		}
		if( isset($items['Head']) && $items['Head']->hasMetaGem )
		{
			$items['Head']->gemColors = $this->gemColors;
			$items['Head']->_makeTooltipHTML();
		}
		return $items;
	}
}
