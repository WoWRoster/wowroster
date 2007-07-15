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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

require_once( ROSTER_LIB . 'item.php');

class bag extends item
{
	var $contents;

	function bag( $data )
	{
		parent::item( $data );
		$this->contents = item_get_many( $this->data['member_id'], $this->data['item_slot'] );
	}

	function out( )
	{
		global $roster, $addon, $tooltips;

		$lang = $this->data['clientLocale'];

		if( $this->data['item_slot'] == 'Bank Bag0' )
		{

			$returnstring = '
<div class="bankbag" style="background-image:url(' . $addon['image_path'] . 'bags/bank_frame.png);">
	<div class="bankcont_name">' . $this->data['item_name'] . '</div>
	<div class="holder">';
		}
		else
		{
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
			$num_of_tips = (count($tooltips)+1);
			$linktip = '';
			foreach( $roster->locale->wordings[$lang]['itemlinks'] as $key => $ilink )
			{
				$linktip .= '<a href="' . $ilink . urlencode(utf8_decode($this->data['item_name'])) . '" target="_blank">' . $key . '</a><br />';
			}
			setTooltip($num_of_tips,$linktip);
			setTooltip('itemlink',$roster->locale->wordings[$lang]['itemlink']);

			$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

			$returnstring = '
<div class="bag" style="height:' . $offset . 'px;background-image:url(' . $addon['image_path'] . 'bags/' . $bag_type . '_top_' . $bag_style . '.png);">
	<div class="' . $bag_type . '_name">' . $this->data['item_name'] . '</div>
	<img src="' . $roster->config['interface_url'] . 'Interface/Icons/' . $this->data['item_texture'] . '.' . $roster->config['img_suffix'] . '" class="bagicon" alt="" />
	<img src="' . $addon['image_path'] . 'bags/' . $bag_type . '_mask.png" class="bagmask" alt="" ' . makeOverlib($this->data['item_tooltip'],'',$this->data['item_color'],0,$lang) . ' ' . $linktip . ' />
	<div class="bottom" style="margin-top:' . $offset . 'px;background-image:url(' . $addon['image_path'] . 'bags/' . $bag_type . '_bot.png);"></div>
	<div class="holder' . $bag_style . '">
			<div class="bagspacer' . $bag_style . '">&nbsp;</div>
			<div class="bagspacer' . $bag_style . '">&nbsp;</div>
';
		}

		// Select all item for this bag
		for( $slot = 0; $slot < $this->data['item_quantity'] ; $slot++ )
		{
			if( $slot < 0 )
			{
				$returnstring .=  '			<div class="bagitem"><img src="' . $roster->config['img_url'] . 'pixel.gif" class="noicon" alt="" /></div>' . "\n";
			}
			else
			{
				$returnstring .=  '			<div class="bagitem">' . "\n";
				if( isset($this->contents[$slot+1]) )
				{
					$item = $this->contents[$slot+1];
					$returnstring .= $item->out();
				}
				$returnstring .=  "</div>\n";
			}
		}

		$returnstring .= '
	</div>
</div>
';

		return $returnstring;
	}
}

function bag_get( $member_id , $slot )
{
	$item = item_get_one( $member_id, $slot );
	if( $item )
	{
		return new bag( $item->data );
	}
	else
	{
		return null;
	}
}
