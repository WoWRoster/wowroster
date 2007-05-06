<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Item class
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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class item
{
  var $data;

	function item( $data )
	{
		$this->data = $data;
	}

	function out( )
	{
		global $roster, $tooltips;

		$lang = ( isset($this->data['clientLocale']) ? $this->data['clientLocale'] : $roster->config['locale']);

		$path = $roster->config['interface_url'].'Interface/Icons/'.$this->data['item_texture'].'.'.$roster->config['img_suffix'];

		$tooltip = makeOverlib($this->data['item_tooltip'],'',$this->data['item_color'],0,$lang);

		// Item links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';
		foreach( $roster->locale->wordings[$lang]['itemlinks'] as $key => $ilink )
		{
			$linktip .= '<a href="'.$ilink.urlencode(utf8_decode($this->data['item_name'])).'" target="_blank">'.$key.'</a><br />';
		}
		setTooltip($num_of_tips,$linktip);
		setTooltip('itemlink',$roster->locale->wordings[$lang]['itemlink']);

		$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		$output = '<div class="item" '.$tooltip.$linktip.'>';

		if ($this->data['item_slot'] == 'Ammo')
			$output .= '<img src="'.$path.'" class="iconsmall"'." alt=\"\" />\n";
		else
			$output .= '<img src="'.$path.'" class="icon"'." alt=\"\" />\n";

		if( ($this->data['item_quantity'] > 1) )
		{
			$output .= '<b>'.$this->data['item_quantity'].'</b>';
			$output .= '<span>'.$this->data['item_quantity'].'</span>';
		}
		$output .= '</div>';

		return $output;
	}
}

function item_get_one( $member_id , $slot )
{
	global $roster;

	$slot = $roster->db->escape( $slot );
	$query = "SELECT `i`.*, `p`.`clientLocale` FROM `".ROSTER_ITEMSTABLE."` AS i, `".ROSTER_PLAYERSTABLE."` AS p WHERE `i`.`member_id` = '$member_id' AND `item_slot` = '$slot';";

	$result = $roster->db->query( $query );
	$data = $roster->db->fetch( $result );
	if( $data )
		return new item( $data );
	else
		return null;

}

function item_get_many( $member_id , $parent )
{
	global $roster;

	$parent = $roster->db->escape( $parent );
	$query= "SELECT `i`.*, `p`.`clientLocale` FROM `".ROSTER_ITEMSTABLE."` AS i, `".ROSTER_PLAYERSTABLE."` AS p WHERE `i`.`member_id` = '$member_id' AND `item_parent` = '$parent';";

	$result = $roster->db->query( $query );

	$items = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$item = new item( $data );
		$items[$data['item_slot']] = $item;
	}
	return $items;
}
