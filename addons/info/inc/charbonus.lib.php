<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character item bonus class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}



/**
 * Code originally from cybrey's 'Bonuses/Advanced Stats' addon
 * output formatting originally by dehoskins
 *
 * Modified by the roster dev team
 */
class CharBonus
{
	var $my_bonus = array();
	var $my_tooltip = array();
	var $lang;
	var $equip;


	function CharBonus( $char )
	{
		$char->fetchEquip();

		$this->lang = $char->data['clientLocale'];
		$this->equip = $char->equip;
	}

	function dumpBonus( )
	{
		global $roster;

		foreach( $this->equip as $item )
		{
			$this->sortOutTooltip($item->data['item_tooltip'], $item->data['item_name'], $item->data['item_color'] );
		}

		$bt = '<div class="char_panel" style="margin-left:20px;">
	<img src="' . $roster->config['img_url'] . 'char/menubar/icon_bonuses.gif" class="panel_icon" alt="" />
	<div class="panel_title">' . $roster->locale->wordings[$this->lang]['item_bonuses_full'] . '</div>
	<div class="tab3">
		<div class="container">
';
		$row = 0;
		foreach( $this->my_bonus as $key => $value )
		{
			$bt .= '		<div class="membersRowRight'.(($row%2)+1).'" style="white-space:normal;" '.makeOverlib($this->my_tooltip[$key],str_replace('XX', $value, $key),'',2).'>'.
		str_replace('XX', $value, $key).'</div>
';
		$row++;
		}
		$bt .= '
		</div>
	</div>
</div>';

		if( !empty($this->my_bonus) )
		{
			return $bt;
		}
		else
		{
			return;
		}
	}

	function dbl( $frontString, $value )
	{
		echo $frontString . ' : ' . $value . '<br />';
	}

	function getStartofModifier( $aString )
	{
		$startpos =  strlen($aString);

		//Count back till we get to the first number
		while( isset($aString[$startpos]) && (is_numeric($aString[$startpos])==FALSE) && ($startpos <> 0) )
		{
			$startpos--;
		}

		//Get start position of the number
		while( isset($aString[$startpos]) && is_numeric($aString[$startpos]) )
		{
			$startpos = $startpos-1;
		}
		return $startpos + 1;
	}

	function getLengthofModifier( $aString )
	{
		$startpos = $this->getStartofModifier($aString);
		$length = 0;
		while( isset($aString[$startpos+$length]) && is_numeric($aString[$startpos+$length]) )
		{
			//$startpos ++;
			$length ++;
		}
		return $length;
	}

	function getModifier( $aString )
	{
		$startpos = $this->getStartofModifier($aString);
		$modifier = '';

		// Extract the number
		while( isset($aString[$startpos]) && is_numeric($aString[$startpos]) )
		{
			$modifier .= $aString[$startpos];
			$startpos++;
		}
		return $modifier;
	}

	function getString( $aString )
	{
		return substr_replace($aString, 'XX',$this->getStartofModifier($aString), $this->getLengthofModifier($aString));
	}

	function getStartofModifierMana( $aString )
	{
		$startpos = 0;
		while( (is_numeric($aString[$startpos])==FALSE) and ($startpos <> strlen($aString)) )
		{
			$startpos ++;
		}
		return $startpos;
	}

	function getLengthofModifierMana( $aString )
	{
		$startpos = $this->getStartofModifierMana( $aString );
		$length = 0;
		while( is_numeric($aString[$startpos+$length]) )
		{
			$length ++;
		}
		return $length;
	}

	function getModifierString( $aString )
	{
		return substr_replace($aString, 'XX',$this->getStartofModifierMana($aString), $this->getLengthofModifierMana($aString));
	}

	function getModifierMana( $aString )
	{
		return subStr($aString, $this->getStartofModifierMana($aString), $this->getLengthofModifierMana($aString));
	}

	function setBonus( $modifier, $string, $item_name, $item_color )
	{
		$full = '<span style="color:#'.$item_color.';">' . $item_name . '</span> : ' . $modifier;

		if (array_key_exists($string, $this->my_bonus))
		{
			$this->my_bonus[$string] = $this->my_bonus[$string] + $modifier;
			$this->my_tooltip[$string] = $this->my_tooltip[$string] . '<br />' . $full;
		}
		else
		{
			$this->my_bonus[$string] = $modifier;
			$this->my_tooltip[$string] = $full;
		}
	}

	function hasNumeric( $aString )
	{
		$pos = 0;

		while( ($pos <= strlen($aString)) and (is_numeric($aString[$pos])==FALSE) )
		{
			$pos++;
		}

		if( $pos < strlen($aString) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function sortOutTooltip( $tooltip, $item_name, $item_color )
	{
		global $roster;

		$lines = explode(chr(10), $tooltip);

		foreach( $lines as $line )
		{
			if( (ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip'], $line)) && ( $this->hasNumeric($line)==FALSE) )
			{
				$this->setBonus( '', $line, $item_name,$item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip_restores'], $line) )
			{
				$this->setBonus( $this->getModifierMana($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip_when'], $line) )
			{
				$this->setBonus( $this->getModifierMana($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_set'], $line) )
			{
				$this->setBonus( '', $line, $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_spell_damage'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $line, $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_healing_power'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $line, $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_chance_hit'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_reinforced_armor'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_school_damage'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getString( $line), $item_name, $item_color);
			}
		}
	}

	function dumpString( $aString )
	{
		//$aString = str_replace( chr(10), 'TWAT', $aString);
		$this->dbl('STRING', $aString);
		for ($i = 0; $i < strlen($aString); $i++)
		{
			$this->dbl( $aString[$i], ord($aString[$i]));
		}
	}
}