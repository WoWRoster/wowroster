<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Reputation class and functions
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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

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
		if ($this->data['faction'] != $fac)
		{
			print '<div class="skilltype">'.$this->data['faction'].'</div>';
		}

		$this->lastfaction = $this->data['faction'];
		return $this->data['faction'];
	}

	function out()
	{
		global $roster, $char;

		$lang = $char->data['clientLocale'];

		$level = $this->data['curr_rep'];
		$max = $this->data['max_rep'];

		if( $max == 1 )
		{
			$bgImage = $roster->config['img_url'].'bargrey.gif';
		}
		else
		{
			$bgImage = $roster->config['img_url'].'barempty.gif';
		}

		switch ( $this->data['Standing'] )
		{
		case ($roster->locale->wordings[$lang]['hated']):
			$RepBarImg = $roster->config['img_url'].'barbit_r.gif';
			$width = intval((($level+26000)/23000) * 354);
			break;
		case ($roster->locale->wordings[$lang]['hostile']):
			$RepBarImg = $roster->config['img_url'].'barbit_r.gif';
			$width = intval((($level+6000)/3000) * 354);
			break;
		case ($roster->locale->wordings[$lang]['neutral']):
			$RepBarImg = $roster->config['img_url'].'barbit_y.gif';
			break;
		case ($roster->locale->wordings[$lang]['unfriendly']):
			$RepBarImg = $roster->config['img_url'].'barbit_o.gif';
			$width = intval(($level/-3000) * 354);
			break;
		case ($roster->locale->wordings[$lang]['honored']):
			$RepBarImg = $roster->config['img_url'].'barbit_g.gif';
			break;
		case ($roster->locale->wordings[$lang]['friendly']):
			$RepBarImg = $roster->config['img_url'].'barbit_g.gif';
			break;
		case ($roster->locale->wordings[$lang]['exalted']):
			$RepBarImg = $roster->config['img_url'].'barbit_g.gif';
			break;
		case ($roster->locale->wordings[$lang]['revered']):
			$RepBarImg = $roster->config['img_url'].'barbit_g.gif';
			break;
		}

		// Giving each rep a unique id so the onmouseover can work correctly
		$id = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $this->data['faction'].$this->data['name']));

		$hover_code = "<div style=\"cursor:default;\" onmouseover=\"swapShow('rep_value_$id','rep_standing_$id');\" onmouseout=\"swapShow('rep_value_$id','rep_standing_$id');\">";
		$value = $hover_code."<div class=\"value\" style=\"display:none;\" id=\"rep_value_$id\">$level/$max</div>".
						"<div class=\"value\" style=\"display:inline;\" id=\"rep_standing_$id\">".$this->data['Standing']."</div></div>";


	// Start output
		$output = '
          <div class="rep">
            <div class="repbox">
              <img class="repbg" alt="" src="'.$bgImage.'" />';

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

		if ($this->data['AtWar'] == 1 )
		{
			$output .= '              <span class="war">'.$roster->locale->wordings[$lang]['atwar'].'</span>';
		}

		$output .= "\n            </div>\n          </div>";


		return $output;
	}
}

function get_reputation( $member_id )
{
	global $roster;

	$query= "SELECT * FROM `".$roster->db->table('reputation')."` WHERE `member_id` = '$member_id' ORDER BY `faction` ASC";
	$result = $roster->db->query( $query );
	$reputations = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$reputation = new reputation( $data );
		$reputations[] = $reputation;
	}
	return $reputations;
}
