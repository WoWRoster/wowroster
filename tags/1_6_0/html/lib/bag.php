<?php
$versions['versionDate']['bag'] = '$Date: 2006/01/01 13:44:34 $'; 
$versions['versionRev']['bag'] = '$Revision: 1.5 $'; 
$versions['versionAuthor']['bag'] = '$Author: zanix $';

require_once 'item.php';
require_once 'char.php';

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

	function out()
	{
		extract($GLOBALS);
		$returnstring = '
<div class="bag">
  <div class="bagTop">
    <div class="bagIcon">';
		$returnstring .= parent::out();
		$returnstring .=  '</div>
    <div class="bagName">'. substr($this->data['item_name'], 0, 25).'</div>
  </div>
';
		$offset = -1 * ($this->data['item_quantity'] % 4);
		for( $slot = $offset, $idx = $this->data['item_quantity'] - $offset;
		$slot < $this->data['item_quantity'] ;
		$slot++, $idx-- )
		{
      if( $idx % 4 == 0 )
      {
				if( $idx == 4 )
					$returnstring .=  '<div class="bagBottomLine">';
				else
					$returnstring .=  '<div class="bagLine">';
			}
			if( $slot < 0 )
				$returnstring .=  '<div class="bagNoSlot"></div>';
			else
			{
				$returnstring .=  '<div class="bagSlot">';
				if (isset($this->contents[$slot+1]))
				{
					$item = $this->contents[$slot+1];
					$returnstring .= $item->out();
				}
				$returnstring .=  '</div>';
			}
      if( $idx % 4 == 1 )
				$returnstring .= "</div>\n";
		}
		if($show_money)
		{
			if( ($this->data['item_name'] == $wordings[$this->char->get('clientLocale')]['backpack']) )
			{
				$returnstring .=  '<div class="bagMoneyBottom">';
				$returnstring .=  '<div class="money">';
				$returnstring .=  $this->char->get('money_g').
					' <img src="img/bagCoinGold.gif" alt="g"/> '.
					$this->char->get('money_s').
					' <img src="img/bagCoinSilver.gif" alt="s"/> '.
					$this->char->get('money_c').
					' <img src="img/bagCoinBronze.gif" alt="c"/> ';
				$returnstring .=  '</div>';
			} else
				$returnstring .=  '<div class="bagBottom"><div></div>';

			$returnstring .=  '
	  </div>
	</div>
	';
	
		}
		else
		{
			$returnstring .=  '<div class="bagBottom"><div></div>
	  </div>
	</div>
	';
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
		return Null;
}
?>