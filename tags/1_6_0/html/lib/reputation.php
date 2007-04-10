<?php
$versions['versionDate']['reputation'] = '$Date: 2006/01/01 13:44:34 $'; 
$versions['versionRev']['reputation'] = '$Revision: 1.8 $'; 
$versions['versionAuthor']['reputation'] = '$Author: zanix $';

require_once 'wowdb.php';

class reputation
{
	var $data;
	var $lastfaction;

	function reputation( $data )
	{
		$this->data = $data;
	}

	function get( $field )
	{
		return $this->data[$field];
	}

	function outHeader($fac)
	{
		extract($GLOBALS);
		if ($this->data['faction'] != $fac)
		{
			print '<div class="skilltype">'.$this->data['faction'].'</div>';
		}

		$this->lastfaction = $this->data['faction'];
		return $this->data['faction'];
	}

	function out()
	{
		extract($GLOBALS);
		list( $level, $max ) = explode( '/', $this->data['Value'] );
		if( $max == 1 )
		{
			$bgImage = 'img/barGrey.gif';
		}
		else
		{
			$bgImage = 'img/barEmpty.gif';
		}

		switch ( $this->data['Standing'] )
		{
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

/* Old code to show a tooltip when hovering over rep value
	// Set tooltip and value based on settings
		if( $show_rep_values == 1 )
		{
			$tooltip = '<span onMouseover="return overlib(\''.$this->data['Standing'].'\',WIDTH,10); "onMouseout="return nd();">';
			$value   = '<span class="value">'.$level.'/'.$max.'</span>';
			$tooltip_close = '</span>';
		}
		else
		{
			$tooltip = '<span onMouseover="return overlib(\''.$level.'/'.$max.'\',WIDTH,10); "onMouseout="return nd();">';
			$value   = '<span class="value">'.$this->data['Standing'].'</span>';
			$tooltip_close = '</span>';
		}
*/

// Tooltip changed to resemble in-game display, on mouseover of the standing it will show the value

			// Giving each rep a unique id so the onmouseover can work correctly
			$id = md5($this->data['name']);

			$hover_code = "<span style=\"cursor:pointer;\" onmouseover=\"swapShow('rep_value_$id','rep_standing_$id');\" onmouseout=\"swapShow('rep_value_$id','rep_standing_$id');\">";
			$value = $hover_code."<div class=\"value\" style=\"display:none;\" id=\"rep_value_$id\">$level/$max</div>".
							"<div class=\"value\" style=\"display:inline;\" id=\"rep_standing_$id\">".$this->data['Standing']."</div></span>";


	// Start output
		$output = '
          <div class="rep">
            <div class="repbox">
              <img class="repbg" alt="" src="'.$bgImage.'" />
              ';

		if( $max < 1 )
		{
			$output .= '<img src="'.$RepBarImg.'" alt="" class="repbit" width="'.$width.'" />'."\n";
		}
		if( $max > 1 )
		{
			$width = intval( ($level/$max) * 354 );
			$output .= '<img src="'.$RepBarImg.'" alt="" class="repbit" width="'.$width.'" />'."\n";
		}

		$output .= '              <span class="faction">'.$this->data['name'].'</span>';

		$output .= "\n              $value\n";

		if ($this->data['AtWar'] == 1 ) {
			$output .= '              <span class="war">'.$wordings[$roster_lang]['atwar'].'</span>';
		} else {
			$output .= '              <span class="nowar">'.$wordings[$roster_lang]['notatwar'].'</span>';
		}

		$output .= "\n            </div>\n          </div>";


		return $output;
	}
}

function get_reputation( $member_id)
{
	global $wowdb;

	if (isset($char))
	{
		$char = $wowdb->escape( $char );
	}
	if (isset($server))
	{
		$server = $wowdb->escape( $server );
	}
	$query= "SELECT * FROM `".ROSTER_REPUTATIONTABLE."` WHERE `member_id` = '$member_id' ORDER BY `faction` ASC";
	$result = $wowdb->query( $query );
	$reputations = array();
	while( $data = $wowdb->getrow( $result ) )
	{
		$reputation = new reputation( $data );
		$reputations[] = $reputation;
	}
	return $reputations;
}
?>