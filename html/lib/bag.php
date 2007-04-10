<?php
require_once 'item.php';
require_once 'char.php';

class bag extends item {
	var $contents;
	var $char;

	function bag( $char, $data ){
		parent::item( $data );
		$this->char = $char;
		$this->contents = item_get_many( $this->data['member_id'], $this->data['item_slot'] );
	}

	function out() {
		extract($GLOBALS);
		echo '
<div class="bag">
  <div class="bagTop">
    <div class="bagIcon">';
		parent::out();
		echo '</div>
    <div class="bagName">'. substr($this->data['item_name'], 0, 25).'</div>
  </div>
';
		$offset = -1 * ($this->data['item_quantity'] % 4);
		for( $slot = $offset, $idx = $this->data['item_quantity'] - $offset;
		$slot < $this->data['item_quantity'] ;
		$slot++, $idx-- )
		{
      if( $idx % 4 == 0 ) {
				if( $idx == 4 ) {
					echo '<div class="bagBottomLine">';
				} else {
					echo '<div class="bagLine">';
				}
			}
			if( $slot < 0 ) {
				echo '<div class="bagNoSlot"></div>';
			} else {
				echo '<div class="bagSlot">';
				if (isset($this->contents[$slot+1])) {
					$item = $this->contents[$slot+1];
					$item->out();
				}
				echo '</div>';
			}
      if( $idx % 4 == 1 ) {
				echo "</div>\n";
			}
		}
		if($show_money) {
			if( ($this->data['item_name'] == $wordings[$this->char->get('clientLocale')]['backpack']) ) {
				echo '<div class="bagMoneyBottom">';
				echo '<div class="money">';
				echo $this->char->get('money_g').
					' <img src="img/bagCoinGold.gif" alt="g"/> '.
					$this->char->get('money_s').
					' <img src="img/bagCoinSilver.gif" alt="s"/> '.
					$this->char->get('money_c').
					' <img src="img/bagCoinBronze.gif" alt="c"/> ';
				echo '</div>';
			} else {
				echo '<div class="bagBottom"><div></div>';
			}
			echo '
	  </div>
	</div>
	';
	
		} else {
			echo '<div class="bagBottom"><div></div>
	  </div>
	</div>
	';
		}
	}
}

function bag_get( $char, $slot ) {
	$item = item_get_one( $char->get('member_id'), $slot );
	if( $item ) {
		return new bag( $char, $item->data );
	} else {
		return Null;
	}
}
?>