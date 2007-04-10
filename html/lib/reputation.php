<?php
require_once 'wowdb.php';

class reputation {
	var $data;
	var $lastfaction;

	function reputation( $data ) {
		$this->data = $data;
	}

	function get( $field ) {
		return $this->data[$field];
	}

	function outHeader($fac) {
		extract($GLOBALS);
		if ($this->data['faction'] != $fac) {
			print '<div class="skilltype">'.$this->data['faction'].'</div>';
		}
		$this->lastfaction = $this->data['faction'];
		return $this->data['faction'];
	}

	function out() {
		extract($GLOBALS);
		list($level, $max) = explode( '/', $this->data['Value'] );
		if( $max == 1 ) {
			$bgImage = 'img/barGrey.gif';
		} else {
			$bgImage = 'img/barEmpty.gif';
		}
		switch ($this->data['Standing']) {
		case ($wordings[$roster_lang]['hated']):
			$RepBarImg = 'img/barBit_R.gif';
			$width = intval((($level+26000)/23000) * 354);
			break;
		case ($wordings[$roster_lang]['hostile']):
			$RepBarImg = 'img/barBit_R.gif';
			$width = intval((($level+6000)/3000) * 354);
			break;			
		case ($wordings[$roster_lang]['neutral']):
			$RepBarImg = 'img/barBit_Y.gif';
			break;
		case ($wordings[$roster_lang]['unfriendly']):
			$RepBarImg = 'img/barBit_O.gif';
			$width = intval(($level/-3000) * 354);
			break;			
		case ($wordings[$roster_lang]['honored']):
			$RepBarImg = 'img/barBit_G.gif';
			break;
		case ($wordings[$roster_lang]['friendly']):
			$RepBarImg = 'img/barBit_G.gif';
			break;
		case ($wordings[$roster_lang]['exalted']):
			$RepBarImg = 'img/barBit_G.gif';
			break;			
		case ($wordings[$roster_lang]['revered']):
			$RepBarImg = 'img/barBit_G.gif';
			break;		
		}
		echo '

		<div class="rep">
		<div class="repbox">
		<img class="repbg" alt="" src="'.$bgImage.'" />';
		if( $max < 1 ) {
			echo '<img src="'.$RepBarImg.'" alt="" class="repbit" width="'.$width.'" />';
		}
		if( $max > 1 ) {
			$width = intval(($level/$max) * 354);
			echo '<img src="'.$RepBarImg.'" alt="" class="repbit" width="'.$width.'" />';
		}
		echo '
		<span class="faction">'.$this->data['name'].'</span>';
		//if( $max > 1 ) 
			if( $show_rep_values == 1) {
				echo '<span onMouseover="return overlib(\''.$this->data['Standing'].'\',WIDTH,10); "onMouseout="return nd();">'."\n";
				echo '<span class="value">'.$level.'/'.$max.'</span>';
				echo '</span>';
			} else {
				echo '<span onMouseover="return overlib(\''.$level.'/'.$max.'\',WRAP); "onMouseout="return nd();">'."\n";
				echo '<span class="value">'.$this->data['Standing'].'</span>';
				echo '</span>';
			}
			if ($this->data['AtWar'] == 1 ) {
				echo '<span class="war">'.$wordings[$roster_lang]['atwar'].'</span>';
			} else {
				echo '<span class="nowar">'.$wordings[$roster_lang]['notatwar'].'</span>';
			}
		echo '</div></div>';
	}
}

function get_reputation( $member_id) {
	global $wowdb;

	if (isset($char)) { $char = $wowdb->escape( $char ); }
	if (isset($server)) { $server = $wowdb->escape( $server ); }
	$query= "SELECT * FROM `".ROSTER_REPUTATIONTABLE."` WHERE `member_id` = '$member_id' ORDER BY `faction` ASC";
	$result = $wowdb->query( $query );
	$reputations = array();
	while( $data = $wowdb->getrow( $result ) ) {
		$reputation = new reputation( $data );
		$reputations[] = $reputation;
	}
	return $reputations;
}
?>