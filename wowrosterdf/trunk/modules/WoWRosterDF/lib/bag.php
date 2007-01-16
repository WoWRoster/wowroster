<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

//DF security
if (!defined('CPG_NUKE')) { exit; }
//Roster security
/*
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}*/

require_once( ROSTER_LIB.'item.php');

class bag extends item
{
	var $contents;
	var $char;

	function bag( $char, $data )
	{
		parent::item( $data );
		$this->char = $char;
		$this->contents = item_get_many( $this->data['member_id'], $this->data['item_slot'] );
	}

	function out( $bank = false )
	{
		global $wordings, $roster_conf;

		if( $bank )
		{
			$width = 7;
			$returnstring = '
<div class="bankbag">';
		}
		else
		{
			$width = 4;
			$returnstring = '
<div class="bag">';
		}

		$returnstring .= '
	<div class="bagTop">
		<div class="bagIcon">';

		$returnstring .= parent::out();
		$returnstring .=  '</div>
		<div class="bagName">'. $this->data['item_name'] .'</div>
	</div>'."\n";

		$offset = -1 * ($this->data['item_quantity'] % $width);
		for( $slot = $offset, $idx = $this->data['item_quantity'] - $offset; $slot < $this->data['item_quantity'] ; $slot++, $idx-- )
		{
			if( $idx % $width == 0 )
			{
				if( $idx == $width )
				{
					$returnstring .=  '<div class="bagBottomLine">'."\n";
				}
				else
				{
					$returnstring .=  '<div class="bagLine">'."\n";
				}
			}
			if( $slot < 0 )
			{
				$returnstring .=  '<div class="bagNoSlot"></div>'."\n";
			}
			else
			{
				$returnstring .=  '<div class="bagSlot">'."\n";
				if (isset($this->contents[$slot+1]))
				{
					$item = $this->contents[$slot+1];
					$returnstring .= $item->out();
				}
				$returnstring .=  "</div>\n";
			}
			if( $idx % $width == 1 )
			{
				$returnstring .= "</div>\n";
			}
		}
		if($roster_conf['show_money'])
		{
			if( ($this->data['item_name'] == $wordings[$this->char->get('clientLocale')]['backpack']) )
			{
				$returnstring .=  '<div class="bagMoneyBottom">';
				$returnstring .=  '<div class="money">';
				$returnstring .=  $this->char->get('money_g').
					' <img src="'.$roster_conf['img_url'].'bagcoingold.gif" alt="g"/> '.
				$this->char->get('money_s').
					' <img src="'.$roster_conf['img_url'].'bagcoinsilver.gif" alt="s"/> '.
				$this->char->get('money_c').
					' <img src="'.$roster_conf['img_url'].'bagcoinbronze.gif" alt="c"/> ';
				$returnstring .=  '</div>';
			}
			else
			{
				$returnstring .=  '<div class="bagBottom"><div></div>'."\n";
			}

			$returnstring .=  '</div></div>'."\n";

		}
		else
		{
			$returnstring .=  '<div class="bagBottom"><div></div></div></div>'."\n";
		}
		return $returnstring;
	}
}

function bag_get( $char, $slot )
{
	$item = item_get_one( $char->get('member_id'), $slot );
	if( $item )
		return new bag( $char, $item->data );
	else
		return null;
}
