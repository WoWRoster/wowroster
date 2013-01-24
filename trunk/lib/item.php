<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Item class
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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

/**
 * Item class
 *
 * @package    WoWRoster
 * @subpackage Item
 */
class item
{
	var $data = array(); // raw data from database deprecated -- use class properties

	var $member_id, $item_id, $name, $level, $icon, $color;
	var $slot, $parent, $tooltip, $quantity, $locale;

	// 0=none, 1=poor, 2=common, 3=uncommon, 4=rare, 5=epic, 6=legendary, 7=heirloom
	var $quality_id; //holds numerical value of item quality
	var $quality; // holds string value of item quality

	// parsing flags
	var $isBag = false, $isSetPiece = false, $isSocketable = false, $isEnchant = false, $isArmor = false;
	var $isWeapon = false, $isParseError = false, $isParseMode = false, $isSocketBonus = false;

	// parsing counters
	var $setItemEquiped = 0;
	var $setItemOwned = 0;
	var $setItemTotal = 0;

	// Upgrade counting
	var $upgradeMin = 0;
	var $upgradeMax = 0;
	
	/**
	 * Armory Lookup Object
	 *
	 * @var RosterArmory
	 */
	var $armory_db;

	// parsed arrays/strings
	var $parsed_item = array();  // fully parsed item array
	var $attributes = array(); // holds all parsed item attributes
	var $effects = array(); // holds passive bonus effects of the item
	var $enchantment;
	var $sockets = array('red' => 0, 'yellow' => 0, 'blue' => 0, 'meta' => 0); //socket colors
	var $hasMetaGem = false;
	var $gemColors = array( 'red' => 0, 'yellow' => 0, 'blue' => 0 );
	var $html_tooltip;

	// item debugging. debug level 0, 1, 2
	var $DEBUG = 1; // 0 (never show debug), 1 (show debug on parse error), 2 (always show debug)
	var $DEBUG_junk = '';

	/**
	 * Constructor
	 *
	 * @param array $data
	 * @param string $parse_mode | accepts 'full' full item parsing, 'simple' for simple item coloring only.  Defaults to auto detect
	 *
	 */
	function item( $data, $parse_mode=false )
	{
		global $roster;

		$this->DEBUG = $roster->config['debug_mode'];
		// Lets hard code this to 1 for BETA ONLY
		//$this->DEBUG = 2;

		$this->isParseMode = $parse_mode;
		$this->data = $data;
		$this->member_id = $data['member_id'];
		$this->item_id = $data['item_id'];
		$this->name = $data['item_name'];
		$this->level = $data['item_level'];
		$this->requires_level = $data['level'];
		$this->icon = $data['item_texture'];
		$this->slot = $data['item_slot'];
		$this->parent = $data['item_parent'];
		$this->tooltip = $data['item_tooltip'];
		$this->color = $data['item_color'];
		$this->locale = ( isset($data['locale']) ? $data['locale'] : $roster->config['locale'] );
		$this->quantity = $data['item_quantity'];
		$this->_setQuality($this->color);
		$this->_doParseTooltip();
		$this->_makeTooltipHTML();
	}

	// TPL data the easy way
	function tpl_get_icon()
	{
		global $roster;

		return $roster->config['interface_url'] . 'Interface/Icons/' . $this->icon . '.' . $roster->config['img_suffix'];
	}

	// TPL data the easy way
	function tpl_get_tooltip()
	{
		return makeOverlib($this->html_tooltip, '', '' , 2, '', ', WIDTH, 325');
	}

	// TPL data the easy way
	function tpl_get_itemlink()
	{
		global $roster, $tooltips;

		$lang = ( isset($this->locale) ? $this->locale : $roster->config['locale'] );
		list($item_id) = explode(':', $this->item_id);

		// Item links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';

		foreach( $roster->locale->wordings[$lang]['itemlinks'] as $key => $ilink )
		{
			$linktip .= '<a href="' . $ilink . $item_id . '" target="_blank">' . $key . '</a><br />';
		}
		setTooltip($num_of_tips, $linktip);
		setTooltip('itemlink', $roster->locale->wordings[$lang]['itemlink']);

		$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		return $linktip;
	}

	function out( $small = false )
	{
		$output = '<div class="item' . ($small ? '-sm' : '') . '" ' . $this->tpl_get_tooltip() . '' . $this->tpl_get_itemlink() . '>
	<img src="' . $this->tpl_get_icon() . '" alt="" />
	<div class="mask ' . $this->quality . '"></div>';
		if( $this->quantity > 1 )
		{
			$output .= "\n	<b>" . $this->quantity . '</b><span>' . $this->quantity . '</span>';
		}
		$output .= "\n</div>\n";

		return $output;
	}

	function setDebug( $mode )
	{
		$this->DEBUG = $mode;
	}

// -- makeTooltipHTML() tooltip methods start here //

	/**
	 * Private function to return item caption in formated HTML
	 *
	 * @return string $html
	 */
	function _getCaption()
	{
		$html = '<span style="color:#' . $this->color . ';font-size:12px;font-weight:bold;">' . $this->name . '</span><br />';
		return $html;
	}

	// heroic shit wow i missed this 
	function _getHeroic()
	{
		global $roster;

		$heroic = $this->attributes['Heroic'];

		if( preg_match( $roster->locale->act['tooltip_preg_heroic'], $heroic) )
		{
			$color = '66DD33';
		}
		
		else
		{
			$color = 'ffffff';
		}

		$html = '<span style="color:#' . $color . ';"><i>' . $heroic . '</i></span><br />';

		return $html;
	}
	
	// upgrade item strings
	function _getUpgrade()
	{
		global $roster;

		//$heroic = $this->attributes['Upgrade'];
		preg_match( $roster->locale->act['tooltip_preg_upgrade'], $this->attributes['Upgrade'], $matches);
		//print_r($matches);echo '<br>';
		$this->upgradeMin = $matches[1];
		$this->upgradeMax = $matches[2];
		$upgrade = sprintf($roster->locale->act['tooltip_upgrade'],$this->upgradeMin,$this->upgradeMax);
		
		$color = '66DD33';

		$html = '<span style="color:#' . $color . ';"><i>' . $upgrade . '</i></span><br />';

		return $html;
	}
	
	function _getTrans()
	{
		global $roster;

		$heroic = $this->attributes['Trans'];

		if( preg_match( $roster->locale->act['tooltip_transmogc'], $heroic) )
		{
			$color = 'F58CBA';
		}
		
		else
		{
			$color = 'ffffff';
		}

		$html = '<span style="color:#' . $color . ';"><i>' . $heroic . '</i></span><br />';

		return $html;
	}
	
	function _getBindType()
	{
		global $roster;

		$bindtype = $this->attributes['BindType'];

		if( preg_match( $roster->locale->act['tooltip_preg_soulbound'], $bindtype) )
		{
			$color = '00bbff';
		}
		elseif( preg_match( "/\b" . $roster->locale->act['tooltip_accountbound']."/", $bindtype) )
		{
			$color = 'e6cc80';
		}
		else
		{
			$color = 'ffffff';
		}

		$html = '<span style="color:#' . $color . ';">' . $bindtype . '</span><br />';

		return $html;
	}

	function _getConjures()
	{
		$html = '';
		foreach( $this->attributes['Conjured'] as $conjured )
		{
			$html .= $conjured . '<br />';
		}
		return $html;
	}

	function _getUnique()
	{
		$html = $this->attributes['Unique'] . '<br />';
		return $html;
	}

	function _getArmor()
	{
		if( isset($this->attributes['ArmorType']) && isset($this->attributes['ArmorSlot']) )
		{
			$html = '<div style="width:100%;"><span style="float:right;">'
				  . $this->attributes['ArmorType'] . '</span>'
				  . $this->attributes['ArmorSlot'] . '</div>';
		}
		elseif( isset($this->attributes['ArmorSlot'] ) )
		{
			$html = $this->attributes['ArmorSlot'] . '<br />';
		}
		elseif( isset($this->attributes['ArmorType']) )
		{
			$html = $this->attributes['ArmorType'] . '<br />';
		}
		else
		{
			return null;
		}
		return $html;
	}

	function _getWeapon()
	{
		if( isset($this->attributes['WeaponType']) && isset($this->attributes['WeaponSlot']) )
		{
			$html = '<div style="width:100%;"><span style="float:right;">'
				  . $this->attributes['WeaponType'] . '</span>'
				  . $this->attributes['WeaponSlot'] . '</div>';
		}
		elseif( isset($this->attributes['WeaponType']) )
		{
			$html = $this->attributes['WeaponType'] . '<br />';
		}
		elseif( isset($this->attributes['WeaponSlot']) )
		{
			$html = $this->attributes['WeaponSlot'] . '<br />';
		}

		if( isset($this->attributes['WeaponDamage']) )
		{
			$html  .= '<div style="width:100%;"><span style="float:right;">'
					. $this->attributes['WeaponSpeed'] . '</span>'
					. $this->attributes['WeaponDamage'] . '</div>';
		}
		if( isset($this->attributes['WeaponDPS']) )
		{
			$html .= $this->attributes['WeaponDPS'] . '<br />';
		}

		return $html;
	}

	function _getBag()
	{
		$html = $this->attributes['BagDesc'] . '<br />';
		return $html;
	}

	function _getArmorClass()
	{
		$html = $this->attributes['ArmorClass']['Line'] . '<br />';
		return $html;
	}

	function _getBaseStats()
	{
		$html = '';
		$stats = array();
		$stats = $this->attributes['BaseStats'];

		foreach( $stats as $stat )
		{
			$html .= '<span style="color:#00ff00;">' . $stat . '</span><br />';
		}
		return $html;
	}

	function _getEnchantment()
	{
		global $roster;
		$html = '<br /><span style="color:#00ff00;">' . sprintf($roster->locale->act['tooltip_ienchant'],$this->attributes['Enchantment']) . '</span><br />';
		return $html;
	}

	function _getTempEnchantment()
	{
		$html = '';

		foreach( $this->attributes['TempEnchantment'] as $bonus )
		{
			$html .= '<span style="color:#ff4242;">' . $bonus . '</span><br />';
		}
		return $html;
	}

	function _getSockets()
	{
		global $roster;

		$html = '';

		//first lets do empty sockets
		if( isset($this->attributes['Sockets']) )
		{
			$emptysockets = $this->attributes['Sockets'];
			foreach( $emptysockets as $socket_color => $socket_line )
			{
				$html .= '<img width="10px" height="10px" src="' . $roster->config['interface_url'] . 'Interface/ItemSocketingFrame/ui-emptysocket-'
					   . $roster->locale->act['socket_colors_to_en'][strtolower($socket_color)] . '.' . $roster->config['img_suffix'] . '" />&nbsp;&nbsp;'
					   . $socket_line . '<br />';
			}
		}
		//now lets do sockets with gems
		if( isset($this->attributes['Gems']) )
		{
			$gems = $this->attributes['Gems'];
			foreach( $gems as $gem )
			{
				$html .= '<img width="10px" height="10px" src="' . $roster->config['interface_url'] . 'Interface/Icons/'
					   . $gem['Icon'] . '.' . $roster->config['img_suffix'] . '" />'
					   . '<span style="color:#ffffff;">&nbsp;&nbsp;' . $gem['Bonus'] . '</span><br />';
				if( $this->hasMetaGem && preg_match('/inv_misc_gem_diamond/', $gem['Icon']) )
				{
					foreach ( $this->attributes['MetaRequires'] as $requirement )
					{
						if ( preg_match( $roster->locale->act['tooltip_preg_meta_requires_min'], $requirement, $matches) )
						{
							$tmp = $roster->locale->act['gem_colors_to_en'];
							if ( $this->gemColors[$tmp[strtolower($matches[2])]] >= $matches[1] )
							{
								$html .= '<span style="color:#ffffff;">&nbsp;&nbsp;' . $requirement . '</span><br />';
							}
							else
							{
								$html .= '<span style="color:#787880;">&nbsp;&nbsp;' . $requirement . '</span><br />';
							}
						}
						elseif ( preg_match( $roster->locale->act['tooltip_preg_meta_requires_more'], $requirement, $matches) )
						{
							$tmp = $roster->locale->act['gem_colors_to_en'];
							if ( $this->gemColors[$tmp[strtolower($matches[1])]] > $this->gemColors[$tmp[strtolower($matches[2])]] )
							{
								$html .= '<span style="color:#ffffff;">&nbsp;&nbsp;' . $requirement . '</span><br />';
							}
							else
							{
								$html .= '<span style="color:#787880;">&nbsp;&nbsp;' . $requirement . '</span><br />';
							}
						}
					}
				}
			}
		}
		return $html;
	}

	function _getSocketBonus()
	{
		if( isset($this->attributes['SocketBonus']) )
		{
			if( isset($this->isSocketBonus) == true )
			{
				$html = '<span style="color:#00ff00;">' . $this->attributes['SocketBonus'] . '</span><br />';
			}
			else
			{
				$html = '<span style="color:#9d9d9d;">' . $this->attributes['SocketBonus'] . '</span><br />';
			}

			return $html;
		}
		return null;
	}

	function _getDurability()
	{
		global $roster;

		$current = $this->attributes['Durability']['Current'];;
		$max = $this->attributes['Durability']['Max'];
		$percent = (($current / $max) * 100);
		$html = '<br />'.$roster->locale->act['tooltip_durability'] . '&nbsp;';

		if( $percent == 0 )
		{
			$html .= '<span style="color:#ff0000;">' . $current . '</span>';
		}
		elseif( $percent <= 25)
		{
			$html .= '<span style="color:gold;">' . $current . '</span>';
		}
		else
		{
			$html .= '<span style="color:#ffffff;">' . $current . '</span>';
		}
		$html .= '<span style="color:#ffffff;"> / ' . $max . '</span><br />';

		return $html;
	}

	function _getRequiredClasses()
	{
		global $roster;

		$html = $this->attributes['ClassText'] . '&nbsp;';
		$count = count($this->attributes['Class']);

		$i = 0;
		foreach( $this->attributes['Class'] as $class )
		{
			$i++;
			$html .= '<span style="color:#'. $roster->locale->act['class_colorArray'][$class] . ';">' . $class . '</span>';
			if( $count > $i )
			{
				$html .= ', ';
			}
		}
		$html .= '<br />';
		return $html;
	}

	function _getRequiredRaces()
	{
		$html = $this->attributes['RaceText'] . '&nbsp;';
		$count = count($this->attributes['Race']);

		$i = 0;
		foreach( $this->attributes['Race'] as $race )
		{
			$i++;
			$html .= $race;
			if( $count > $i )
			{
				$html .= ', ';
			}
		}
		$html .= '<br />';
		return $html;
	}

	function _getRequired()
	{
		global $roster;

		$requires = array();
		$requires = $this->attributes['Requires'];
		$html = '';

		// -- TODO --
		// this needs to make a check for Crafting Requires and move
		// the required line to the item set area of the tooltip
		foreach( $requires as $val )
		{
			if( preg_match($roster->locale->act['requires_level'], $val) )
			{
				$html .= '<span style="color:#ff0000;">' . $val . '</span><br />';
			}
			else
			{
				$html .= '<span style="color:#ff0000;">' . $val . '</span><br />';
			}
		}
		return $html;
	}

	function _getPassiveBonus()
	{
		$html = '';
		$effects = array();
		$effects = $this->effects;

		foreach( $effects as $type )
		{
			foreach( $type as $effect)
			{
				$html .= '<span style="color:#00ff00;">' . $effect . '</span><br />';
			}
		}
		return $html;
	}

	function _getItemCharges()
	{
		$html = '<span style="color:#ffffff;">' . $this->attributes['Charges'] . '</span><br />';
		return $html;
	}

	function _getSetPiece()
	{
		$html = '<br /><span style="color:#ffd517;font-size:11px;font-weight:bold">' . $this->attributes['Set']['ArmorSet']['Name']
			  . ' ( ' . $this->setItemEquiped . ' / ' . $this->setItemTotal . ' )'
			  . '</span><br />';

		foreach( $this->attributes['Set']['ArmorSet']['Piece'] as $piece )
		{
			if( isset($piece['Equip']) )
			{
				$html .= '<span style="color:#f8ffa8;">&nbsp;&nbsp;' . $piece['Name'] . '</span><br />';
			}
			elseif( isset($piece['Owned']) )
			{
				$html .= '<span style="color:#787880;font-style:italic;">&nbsp;&nbsp;' . $piece['Name'] . '</span><br />';
			}
			else
			{
				$html .= '<span style="color:#787880;">&nbsp;&nbsp;' . $piece['Name'] . '</span><br />';
			}
		}
		$html .= '<br />';
		return $html;
	}

	function _getSetBonus()
	{
		if( isset($this->attributes['Set']['SetBonus']) )
		{
			$html = '';
			foreach( $this->attributes['Set']['SetBonus'] as $bonus )
			{
				$html .= '<span style="color:#00ff00;">' . $bonus . '</span><br />';
			}
		return $html;
		}
	return null;
	}

	function _getInactiveSetBonus()
	{
		if( !isset($this->attributes['Set']['InactiveSet']) )
		{
			return false;
		}
		$html = '';

		foreach( $this->attributes['Set']['InactiveSet'] as $piece )
		{
			$html .= '<span style="color:#9d9d9d;">' . $piece . '</span><br />';
		}
		return $html;
	}

	function _getCrafter()
	{
		$html = '<span style="color:#00ff00;font-weight:bold;">' . htmlentities($this->attributes['MadeBy']['Line']) . '</span><br />';
		return $html;
	}

	function _getRestrictions()
	{
		$html = '';

		foreach( $this->attributes['Restrictions'] as $val )
		{
			$html .= '<span style="color:#ffffff;">' . $val . '</span><br />';
		}
		return $html;
	}

	function _getItemNote()
	{
		$html = '<span style="color:#ffd517;">' . $this->attributes['ItemNote'] . '</span><br />';
		return $html;
	}

	function _getItemLevel()
	{
		$html = '<span style="color:#ffd800;">' . $this->attributes['ItemLevel']['Line'] . '</span><br />';
		return $html;
	}

	function _getBoss()
	{
		$tmp = explode ( ':', $this->attributes['Boss'] );
		$html = '<span style="color:#ffd800;">' . $tmp[0] . ':</span><span style="color:#ffffff;">' . $tmp[1] . '</span><br />';
		return $html;
	}

	function _getSource()
	{
		$tmp = explode ( ':', $this->attributes['Source'] );
		$html = '<br /><span style="color:#ffd800;">' . $tmp[0] . ':</span><span style="color:#ffffff;">' . $tmp[1] . '</span><br />';
		return $html;
	}

	function _getDropRate()
	{
		$tmp = explode ( ':', $this->attributes['DropRate'] );
		$html = '<span style="color:#ffd800;">' . $tmp[0] . ':</span><span style="color:#ffffff;">' . $tmp[1] . '</span><br />';
		return $html;
	}

	/**
	 * Reconstructs item's tooltip from parsed information.
	 * All HTML Styling is done in the private _getXX() methods
	 *
	 */
	function _makeTooltipHTML()
	{
		//
		// if Parse Error fall back to colorToolTip() for tooltip parsing.
		if( $this->isParseError || $this->isParseMode == 'simple' )
		{
			if( ($this->DEBUG && $this->isParseError) || $this->DEBUG == 2 )
			{
				trigger_error('Item parser data'
					 . '<table class="border_frame" cellpadding="0" cellspacing="1" width="350"><tr><td>'
					 . ( !empty($this->DEBUG_junk) ? implode('<br>', $this->DEBUG_junk) : '' )
					 . '<hr width="80%" /> ' . str_replace("\n", '<br />', $this->tooltip)
					 . '<hr width="80%" /> '
					 . '</td></tr></table><br />'
					 . aprint($this->parsed_item,'',true));
			}
			$this->html_tooltip = colorTooltip($this->tooltip . ( $this->DEBUG ? '<br />Parsed Simple' : '' ), $this->color, $this->locale);
		}
		else
		{
			$html_tt = $this->_getCaption();
			if( isset($this->attributes['Conjured']) )
			{
				$html_tt .= $this->_getConjures();
			}
			if( isset($this->attributes['ItemLevel']) )
			{
				$html_tt .= $this->_getItemLevel();
			}
			if( isset($this->attributes['Upgrade']) )
			{
				$html_tt .= $this->_getUpgrade();
			}
			
			if( isset($this->attributes['Heroic']) )
			{
				$html_tt .= $this->_getHeroic();
			}
			if( isset($this->attributes['Trans']) )
			{
				$html_tt .= $this->_getTrans();
			}
			if( isset($this->attributes['BindType']) )
			{
				$html_tt .= $this->_getBindType();
			}
			if( isset($this->attributes['Unique']) )
			{
				$html_tt .= $this->_getUnique();
			}

			if( $this->isArmor )
			{
				$html_tt .= $this->_getArmor();
			}
			elseif( $this->isWeapon )
			{
				$html_tt .= $this->_getWeapon();
			}
			elseif( $this->isBag )
			{
				$html_tt .= $this->_getBag();
			}

			if( isset($this->attributes['ArmorClass']) )
			{
				$html_tt .= $this->_getArmorClass();
			}
			if( isset($this->attributes['BaseStats']) )
			{
				$html_tt .= $this->_getBaseStats();
			}
			if( isset($this->attributes['Enchantment']) )
			{
				$html_tt .= $this->_getEnchantment();
			}
			if( isset($this->attributes['TempEnchantment']) )
			{
				$html_tt .= $this->_getTempEnchantment();
			}
			if( $this->isSocketable )
			{
				$html_tt .= $this->_getSockets();
				$html_tt .= $this->_getSocketBonus();
			}
			if( isset($this->attributes['Class']) )
			{
				$html_tt .= $this->_getRequiredClasses();
			}
			if( isset($this->attributes['Race']) )
			{
				$html_tt .= $this->_getRequiredRaces();
			}
			if( isset($this->attributes['Durability']) )
			{
				$html_tt .= $this->_getDurability();
			}
			if( isset($this->attributes['Requires']) )
			{
				$html_tt .= $this->_getRequired();
			}
			if( isset($this->effects) )
			{
				$html_tt .= $this->_getPassiveBonus();
			}
			if( isset($this->attributes['Charges']) )
			{
				$html_tt .= $this->_getItemCharges();
			}
			if( $this->isSetPiece )
			{
				$html_tt .= $this->_getSetPiece();
				$html_tt .= $this->_getSetBonus();
				$html_tt .= $this->_getInactiveSetBonus();
			}
			if( isset($this->attributes['MadeBy']['Line']) )
			{
				$html_tt .= $this->_getCrafter();
			}
			if( isset($this->attributes['Restrictions']) )
			{
				$html_tt .= $this->_getRestrictions();
			}
			if( isset($this->attributes['ItemNote']) )
			{
				$html_tt .= $this->_getItemNote();
			}
			if( isset($this->attributes['Source']) )
			{
				$html_tt .= $this->_getSource();
			}
			if( isset($this->attributes['Boss']) )
			{
				$html_tt .= $this->_getBoss();
			}
			if( isset($this->attributes['DropRate']) )
			{
				$html_tt .= $this->_getDropRate();
			}

			if( ($this->DEBUG && $this->isParseError) || $this->DEBUG == 2 )
			{
				trigger_error('<table class="border_frame" cellpadding="0" cellspacing="1" width="350"><tr><td>'
				. $html_tt
				. '<hr width="80%" /> ' . str_replace("\n", '<br />', $this->tooltip)
				. '</td></tr></table><br />'
				. aprint($this->parsed_item,'',true));
			}
			$this->html_tooltip = $html_tt . ( $this->DEBUG ? '<br />Parsed Full' : '' );
		}
	}

	/**
	 * Sets the $quality and $quality_id property
	 * Takes the color of the item
	 *
	 * @param string $color | color of item
	 */
	function _setQuality( $color )
	{
		switch( strtolower( $color ) )
		{
			case 'e6cc80':
				$this->quality_id = '7';
				$this->quality = 'heirloom';
				break;
			case 'ff8800':
				$this->quality_id = '6';
				$this->quality = 'legendary';
				break;
			case 'a335ee':
				$this->quality_id = '5';
				$this->quality = 'epic';
				break;
			case '0070dd':
				$this->quality_id = '4';
				$this->quality = 'rare';
				break;
			case '1eff00':
				$this->quality_id = '3';
				$this->quality = 'uncommon';
				break;
			case 'ffffff':
				$this->quality_id = '2';
				$this->quality = 'common';
				break;
			case '9d9d9d':
				$this->quality_id = '1';
				$this->quality = 'poor';
				break;
			default:
				$this->quality_id = '0';
				$this->quality = 'none';
				break;
		}
	}

	/**
	 * Decides the best way to parse the item
	 *
	 * @return call
	 */
	function _doParseTooltip()
	{
		// look at check $parseMode variable and call parse method "simple" or "full"
		// // TODO // "webdb" tie into failed parsing too?
		// if no $parseMode is defined then try and figure the best method to parse.
		// -- if item has gems or enchants send to full parsing
		// otherwise do simple parsing
		list($itemid, $enchant, $gem1, $gem2, $gem3) = explode(':', $this->item_id);

		// Mop broke enchants unless i find a better way to do this we are gona diable them...

		if( $this->isParseMode == 'full' && !strstr($this->name, ':') || $enchant || $gem1 || $gem2 || $gem3
			&& !$this->isParseMode == 'simple'  )
		{
			return $this->_parseTooltipFull($itemid, $enchant, $gem1, $gem2, $gem3);
		}
		elseif( preg_match('/\(\d+\/\d+\)/', $this->tooltip) )//&& !strstr($this->name, ':') )
		{
			return $this->_parseTooltipFull($itemid);
		}
		return $this->_parseTooltipSimple();
	}

	function _parseTooltipSimple()
	{
		$this->isParseMode = 'simple';
	}

	function _parseTooltipFull( $itemid, $enchant=false, $gem1=false, $gem2=false, $gem3=false)
	{
		global $roster;

		$locale = $this->locale;
		$tooltip = $this->tooltip;

		$tt = array();
		$setpiece = null; //flag for parsing

		//cleanup tooltip for parsing
		$tooltip = str_replace("\n\n", "\n", $tooltip);
		$tooltip = str_replace('<br>', "\n",$tooltip);
		$tooltip = str_replace('<br />', "\n",$tooltip);

		// Another filter for non breaking spaces
		// this might be removed once all character data is updated
		$tooltip = str_replace(chr(194).chr(160), ' ', $tooltip);

		// As enchants can't be in colored lines we make a copy of tooltip without colored lines
		$tooltipWithoutColoredLines = $tooltip;
		$tooltip = preg_replace( '/\|c[a-f0-9]{6,8}(.+?)\|r/', '$1', $tooltip );
		$tooltipWithoutColoredLines = preg_replace( '/\s\s\|c[a-f0-9]{6,8}.+?\|r\n/', '',$tooltipWithoutColoredLines );
		$tooltipWithoutColoredLines = preg_replace( '/\|c[a-f0-9]{6,8}.+?\|r\n/', '', $tooltipWithoutColoredLines );


		
		//echo'<br><hr><br><pre>';echo $tooltip;echo'</pre>';
		//echo'<br><pre>';echo $tooltipWithoutColoredLines;echo'</pre>';
		// if any gems get the data for them, remove lines from stack.
		if( $gem1 || $gem2 || $gem3 )
		{
			$gems = array($gem1,$gem2,$gem3);
			$i = 1;
			foreach( $gems as $gem )
			{
				if( $gem != 0 )
				{
					$tt['Attributes']['Gems'][$i] = $this->fetchGem($gem);
					if( isset($tt['Attributes']['Gems'][$i]['Bonus']) )
					{
						$tooltip = str_replace( $tt['Attributes']['Gems'][$i]['Bonus'] . "\n", '', $tooltip);
						$tooltip = str_replace('<span style="color:#ffffff;">' . $tt['Attributes']['Gems'][$i]['Bonus'] . "</span>\n", '', $tooltip);
						//echo 'Removed'.$tt['Attributes']['Gems'][$i]['Bonus'].'<br>';
						//<span style="color:#ffffff;">" . $tt['Attributes']['Gems'][$i]['Bonus'] . "</span>
						$tooltipWithoutColoredLines = str_replace( $tt['Attributes']['Gems'][$i]['Bonus'] . "\n", '', $tooltipWithoutColoredLines);
						$tooltipWithoutColoredLines = str_replace('<span style="color:#ffffff;">' . $tt['Attributes']['Gems'][$i]['Bonus'] . "</span>\n", '',$tooltipWithoutColoredLines);
					}
					else
					{
						trigger_error('Unable to find gem_socketid: ' . $gem . ' locale: ' . $this->locale . ' in Gems table! [' . $this->item_id . ']' );
						$this->isParseError = true;
					}
				}
				$i++;
			}
			$this->isSocketable = true;
		}
		//echo'<br><pre>';echo $tooltip;echo'</pre><br><hr><br>';
		
		// tries to capture temp enchants based on pattern
		if( preg_match($roster->locale->act['tooltip_preg_tempenchants'], $tooltip, $matches) )
		{
			$tooltip = str_replace( $matches[0], '', $tooltip );
			$tooltipWithoutColoredLines = str_replace( $matches[0], '', $tooltipWithoutColoredLines );
			$tt['Attributes']['TempEnchantment'][] = $matches[1];
		}
		//
		// need a way to find out if this bonus is in effect or not
		// perhaps pull base stat info from a db.. check into itemstats mod?
		if( preg_match($roster->locale->act['tooltip_preg_socketbonus'], $tooltip, $matches) )
		{
			$tooltip = str_replace( $matches[0] . "\n", '', $tooltip );
			$tooltipWithoutColoredLines = str_replace( $matches[0] . "\n", '', $tooltipWithoutColoredLines );
			$tt['Attributes']['SocketBonus'] = $matches[0];
		}
		// time to remove sell back garbage
		if (preg_match($roster->locale->act['tooltip_garbage8'], $tooltip, $matches))
		{
			$tooltip = str_replace( $matches[0], '', $tooltip );
			$tooltipWithoutColoredLines = str_replace( $matches[0], '', $tooltipWithoutColoredLines );
		}


		

		//
		// if itemid shows an enchant on the item parse for it.
		// First check for special enchants that do not follow the general rules.
		// If none then find 'durability' and the line above that is the enchantment.
		// if the tooltip does not have 'durability' then the item might be a cloak, and cloaks do not have 'durability'
		//   so look for 'Requires Level' and the line above should be the enchant.
		// if the item does not have 'durability' and a 'required level' then it is a quest cloak
		// // TODO // then check for any cloak enchants that do not start with a plus sign
		//                "Increased Stealth" and "Subtlety" are known. need enchant IDs to make it efficent localize txts? or static?
		// find the last line that starts with a + (plus) sign and assume that is the enchantment
		// in all cases Remove the line from the stack
		//echo '<br><hr><br>'.$tooltip.'<br>';
		//echo $tooltipWithoutColoredLines.'<br><hr><br>';
		if( $enchant )
		{
			$this->isEnchant = true;
/*
			//
			// Check for 'Reinforced (+32 Armor)' enchant types first.
			if( $enchant < 2000 && preg_match($roster->locale->act['tooltip_preg_reinforcedarmor'], $tooltipWithoutColoredLines, $matches) )
			{
				$tooltip = str_replace( $matches[1], '', $tooltip );
				$tt['Attributes']['Enchantment'] = $matches[1];
			}
			elseif( preg_match('/\n(.+?)\n(?:' . $roster->locale->act['tooltip_durability'] . '|\s\s' . $roster->locale->act['tooltip_reg_requires'] . '|' . substr($roster->locale->act['tooltip_preg_emptysocket'], 1, strlen($roster->locale->act['tooltip_preg_emptysocket']) - 3 ) . ')/i', $tooltipWithoutColoredLines, $matches) )
			{
				$tooltip = str_replace( $matches[1], '', $tooltip );
				$tt['Attributes']['Enchantment'] = $matches[1];
			}
			elseif( preg_match( '/\n(.+)\n' . $roster->locale->act['tooltip_reg_requires'] . '/i', $tooltipWithoutColoredLines, $matches) )
			{
				$tooltip = str_replace( $matches[1], '', $tooltip );
				$tt['Attributes']['Enchantment'] = $matches[1];
			}
			//
			// add check for "Increased Stealth" and "Subtlety" cloak enchants otherwise could fail
			//
			elseif( preg_match_all('/\+\d+.+/', $tooltipWithoutColoredLines, $matches) )
			{
				//grab the last + stat in the tooltip and call that the enchantment.
				$tooltip = str_replace($matches[0][count($matches[0])-1], '', $tooltip);
				$tt['Attributes']['Enchantment'] = $matches[0][count($matches[0])-1];
			}
			else
			*/
			if( preg_match($roster->locale->act['tooltip_preg_enchant'], $tooltipWithoutColoredLines, $matches) )
			{
				//echo '<pre>';print_r($matches);echo '</pre>';
				$tt['Attributes']['Enchantment'] = $matches[0];
			}
			else
			{
				//still failed to find the enchantment.  Report an error.
				trigger_error("Unable to parse the Enchantment! ( $this->name ) [ $this->item_id ]");
				$this->isEnchant = false;
				/*  we wont pull an errror any more because enchant errors this is no longer an issue .. which is sweet
				*$this->isParseError = true;
				*/
			}
			if( $this->isEnchant )
			{
				$this->enchantment = $tt['Attributes']['Enchantment'];
			}
		}

		$tooltip = str_replace($roster->locale->act['tooltip_transmoga'], $roster->locale->act['tooltip_transmogb'], $tooltip);
		$setpiece=0;
		// pissed off item sets are nto working... work arround
		$d = str_replace("\n", '<br>', $this->tooltip);
		$tvt = explode("<br>", $d);
		foreach( $tvt as $linet )
		{
			//echo $linet.'---<br>';
			if( preg_match('/^  (.*)$/', $linet) )
			{
				if( strlen($linet) > 4)
				{
					$tt['Attributes']['Set']['ArmorSet']['Piece'][$setpiece]['Name'] = trim($linet);
					$setpiece++;
					$tooltip = str_replace($linet, '', $tooltip);
				}
				else
				{
					$setpiece=false;
				}
			}
		}
		// end work arround
		

		$tooltip = explode("\n", $tooltip);
		
		$tt['General']['Name'] = array_shift($tooltip);
		$tt['General']['ItemId'] = $this->item_id;
		$tt['General']['ItemColor'] = $this->color;
		$tt['General']['Icon'] = $this->icon;
		$tt['General']['Slot'] = $this->slot;
		$tt['General']['Parent'] = $this->parent;
		$tt['General']['Tooltip'] = str_replace("\n", '<br>', $this->tooltip);
		$tt['General']['Locale']=$this->locale;
		$tt['Attributes']['Quality']['Id'] = $this->quality_id;
		$tt['Attributes']['Quality']['Name'] = $this->quality;
		$tt['Attributes']['Quantity'] = $this->quantity;

		foreach( $tooltip as $line )
		{
			//
			// at this point any line prefixed with a + must be a White Stat (or base stat).
			//([0-9, ]+)
			if( preg_match('/^([+,0-9]+)\s(.+)$/', $line, $matches) )
			{
				if ( !isset($tt['Attributes']['BaseStats'][$matches[2]]) && $matches[2] != $roster->locale->act['armor'])
				{
					$tt['Attributes']['BaseStats'][$matches[2]] = $matches[0];
				}
				elseif ($matches[2] == $roster->locale->act['armor'])
				{
					$tt['Attributes']['ArmorClass']['Line'] = $matches[0];
					$tt['Attributes']['ArmorClass']['Rating'] = $matches[1];
				}
			}
			elseif( preg_match( $roster->locale->act['tooltip_preg_use'], $line) )
			{
				//Use:
				$tt['Effects']['Use'][] = $line;
			}
			elseif( preg_match('/^([,0-9]+)\s'.$roster->locale->act['armor'].'$/', $line, $matches) )
			{
				//echo '<pre>';print_r($matches);echo '</pre>';
				$tt['Attributes']['ArmorClass']['Line'] = $matches[0];
				$tt['Attributes']['ArmorClass']['Rating'] = $matches[1];
				// dont flag as armor here because weapons can have Armor too
			}
			elseif( preg_match( "/" . $roster->locale->act['tooltip_reg_requires'] . "/i", $line) )
			{
				//Requires
				$tt['Attributes']['Requires'][] = $line;
			}

			//$lang['tooltip_reforged']
			elseif( preg_match( "/" . $roster->locale->act['tooltip_reforged'] . "/i", $line) )
			{
				//Reforged
				$tt['Attributes']['Requires'][] = $line;
			}


			elseif( preg_match($roster->locale->act['tooltip_preg_item_level'], $line, $matches) )
			{
				//Item Level
				$tt['Attributes']['ItemLevel']['Line'] = $matches[0];
				$tt['Attributes']['ItemLevel']['Level'] = $matches[1];
			}
			elseif( preg_match($roster->locale->act['tooltip_preg_item_equip'], $line) )
			{
				if( preg_match($roster->locale->act['tooltip_preg_chance'], $line) )
				{
					//Chance
					$tt['Effects']['ChanceToProc'][] = $line;
				}
				else
				{
					//Equip:
					$tt['Effects']['Equip'][] = $line;
				}
			}
			elseif( preg_match( $roster->locale->act['tooltip_preg_chance_hit'], $line) )
			{
				$tt['Effects']['ChanceToProc'][] = $line;
			}
			elseif( preg_match( "/\b" . $roster->locale->act['tooltip_feral_ap'] . "\b/i", $line) )
			{
				$tt['Effects']['Equip'][] = $line;
			}
			elseif( preg_match( $roster->locale->act['tooltip_preg_heroic'], $line) )
			{
				//heroic
				$tt['Attributes']['Heroic'] = $line;
			}
			elseif( preg_match( $roster->locale->act['tooltip_preg_upgrade'], $line) )
			{
				//upgrade
				$tt['Attributes']['Upgrade'] = $line;
			}
			elseif( preg_match( $roster->locale->act['tooltip_preg_lfr'], $line) )
			{
				//heroic
				$tt['Attributes']['Heroic'] = $line;
			}
			elseif( preg_match( $roster->locale->act['tooltip_preg_item_season'], $line) )
			{
				//heroic
				$tt['Attributes']['Heroic'] = $line;
			}
			elseif( preg_match( $roster->locale->act['tooltip_transmogc'], $line, $matches) )
			{
				$tt['Attributes']['Trans'] = $matches[0];
			}
			elseif( preg_match( "/\b" . $roster->locale->act['tooltip_bind_types'] . "\b/i", $line) )
			{
				//soulbound, bop, quest item etc
				$tt['Attributes']['BindType'] = $line;
			}
			
			elseif( preg_match($roster->locale->act['tooltip_preg_item_set'], $line) )
			{
				//set piece bonus
				$tt['Attributes']['Set']['SetBonus'][] = $line;
			}
			elseif( preg_match($roster->locale->act['tooltip_preg_durability'], $line, $matches) )
			{
				$tt['Attributes']['Durability']['Line']= $matches[0];
				$tt['Attributes']['Durability']['Current'] = $matches[2];
				$tt['Attributes']['Durability']['Max'] = $matches[3];
			}
			elseif( preg_match($roster->locale->act['tooltip_preg_classes'], $line, $matches) )
			{
				$tt['Attributes']['Class'] = explode(', ', $matches[2]);
				$tt['Attributes']['ClassText'] = $matches[1];
			}
			elseif( preg_match($roster->locale->act['tooltip_preg_races'], $line, $matches) )
			{
				$tt['Attributes']['Race'] = explode(', ', $matches[2]);
				$tt['Attributes']['RaceText'] = $matches[1];
			}
			elseif( preg_match($roster->locale->act['tooltip_preg_emptysocket'], $line, $matches) )
			{
				// match[1] = color, match[0] full line  Red Socket
				$tt['Attributes']['Sockets'][$matches[1]] = $matches[0];
				$this->isSocketable = true;
			}
			elseif( preg_match('/\([0-9]\).' . $roster->locale->act['tooltip_set'] . '/', $line) )
			{
				$tt['Attributes']['Set']['InactiveSet'][] = $line;
			}
			elseif( preg_match('/"/',$line) )
			{
				$tt['Attributes']['ItemNote'] = '';//$line;
			}
			elseif( preg_match( "/\b" . $roster->locale->act['tooltip_unique'] . "\b/i", $line ) )
			{
				$tt['Attributes']['Unique'] = $line;
			}
			
			elseif( strpos($line,"\t") )
			{
				$line = explode("\t",$line);

				if(preg_match("/" . $roster->locale->act['tooltip_armor_types'] . "/", $line[1] ) )
				{
					$tt['Attributes']['ArmorType'] = $line[1];
					$tt['Attributes']['ArmorSlot'] = $line[0];
					$this->isArmor = true;
				}
				elseif( preg_match($roster->locale->act['tooltip_preg_weapon_types'], $line[1] ) )
				{
					$tt['Attributes']['WeaponType'] = $line[1];
					$tt['Attributes']['WeaponSlot'] = $line[0];
					$this->isWeapon = true;
				}
				elseif( preg_match($roster->locale->act['tooltip_preg_speed'], $line[1]) )
				{
					$tt['Attributes']['WeaponSpeed'] = $line[1];
					$tt['Attributes']['WeaponDamage'] = $line[0];
					$this->isWeapon = true;
				}
			}

			elseif( $this->isWeapon && preg_match("/" . $roster->locale->act['tooltip_reg_weaponorbulletdps'] . "/", $line) )
			{
				$tt['Attributes']['WeaponDPS'] = $line;
				$this->isWeapon = true;
			}
			elseif( preg_match( $roster->locale->act['tooltip_preg_madeby'], $line, $matches ) )
			{
				$tt['Attributes']['MadeBy']['Name'] = $matches[1];
				$tt['Attributes']['MadeBy']['Line'] = $matches[0];
			}
			elseif( preg_match('/^(.*) \(\d+\/\d+\)$/', $line, $matches ) )
			{
				$tt['Attributes']['Set']['ArmorSet']['Name'] = $matches[1];
				$this->isSetPiece = true;
				$setpiece=1;
			}
			elseif( preg_match( "/\b" . $roster->locale->act['tooltip_source'] . "\b/i", $line ) )
			{
				$tt['Attributes']['Source'] = $line;
			}
			elseif( preg_match( "/\b" . $roster->locale->act['tooltip_boss'] . "\b/i", $line ) )
			{
				$tt['Attributes']['Boss'] = $line;
			}
			elseif( preg_match( "/\b" . $roster->locale->act['tooltip_droprate'] . "\b/i", $line ) )
			{
				$tt['Attributes']['DropRate'] = $line;
			}
			/*
			elseif( $setpiece )
			//elseif( preg_match('/  (.*)/', $line, $matches ) )
			{
				//echo '--'.$line.'<br>';
				if( strlen($line) > 4)
				{
					$tt['Attributes']['Set']['ArmorSet']['Piece'][$setpiece]['Name'] = trim($line);
					$setpiece++;
				}
				else
				{
					$setpiece=false;
				}
			}*/
			elseif( preg_match($roster->locale->act['tooltip_preg_meta_requires'], $line ) )
			{
				$tt['Attributes']['MetaRequires'][] = $line;
				$this->hasMetaGem = true;
			}
			else
			{
				// pass 2
				// check for less common strings here. this will only get called if pass1 has failed to match anything on the current line
				if( $line !== '' && $line !== ' '
					&& !preg_match($roster->locale->act['tooltip_garbage1'], $line)
					&& !preg_match($roster->locale->act['tooltip_garbage2'], $line)
					&& !preg_match($roster->locale->act['tooltip_garbage3'], $line)
					&& !preg_match($roster->locale->act['tooltip_garbage4'], $line)
					&& !preg_match($roster->locale->act['tooltip_garbage5'], $line)
					&& !preg_match($roster->locale->act['tooltip_garbage6'], $line)
					&& !preg_match($roster->locale->act['tooltip_garbage7'], $line)
					&& !preg_match($roster->locale->act['tooltip_preg_dps'], $line)
				)
				{
					// check to match more simpler items
					// also could have fell through the line split in pass1
					if( preg_match( $roster->locale->act['tooltip_preg_weapon_types'], $line ) )
					{
						$tt['Attributes']['WeaponSlot'] = $line;
						$this->isWeapon = true;
					}
					elseif( preg_match( "/\b" . $roster->locale->act['tooltip_misc_types'] . "\b/i", $line) )
					{
						$tt['Attributes']['ArmorSlot'] = $line;
						$this->isArmor = true;
					}
					//
					//check if its a bag
					elseif( preg_match( $roster->locale->act['tooltip_preg_bags'], $line, $matches ) )
					{
						$tt['Attributes']['BagSize'] = $matches[1];
						$tt['Attributes']['BagDesc'] = $line;
						$this->isBag = true;
					}
					//
					//check if its a shield
					elseif( preg_match( $roster->locale->act['tooltip_preg_block'], $line, $matches ) )
					{
						// sometimes these are reversed so check for it
						if( is_numeric($matches[1]) )
						{
							$tt['Attributes']['BaseStats'][$matches[2]] = $matches[0];
						}
						else
						{
							$tt['Attributes']['BaseStats'][$matches[1]] = $matches[0];
						}
						$this->isArmor = true;
					}
					elseif( preg_match($roster->locale->act['tooltip_preg_armor'], $line, $matches) )
					{
						echo '<pre>';print_r($matches);echo '</pre>';
						$tt['Attributes']['ArmorClass']['Line'] = $matches[0];
						$tt['Attributes']['ArmorClass']['Rating'] = $matches[1];
						// dont flag as armor here because weapons can have Armor too
					}
					//
					//check if item has charges
					elseif( preg_match( $roster->locale->act['tooltip_preg_charges'], $line ) )
					{
						$tt['Attributes']['Charges'] = $line;
					}
					//
					//check if item is a poison
					elseif( preg_match( '/'.$roster->locale->act['tooltip_poisoneffect'] . "/", $line ) )
					{
						$tt['Poison']['Effect'][] = $line;
						$this->isPoison = true;
					}
					//
					//is this needed?
					elseif( preg_match( "/\b" . $roster->locale->act['tooltip_armor_types'] . "\b/i", $line) )
					{
						$tt['Attributes']['ArmorSlot'] = $line;
						$this->isArmor = true;
					}
					elseif( preg_match( "/\b" . $roster->locale->act['tooltip_reg_onlyworksinside'] . "\b/i", $line) )
					{
						$tt['Attributes']['Restrictions'][] = $line;
					}
					elseif( preg_match( "/\b" . $roster->locale->act['tooltip_reg_conjureditems']."/", $line) )
					{
						$tt['Attributes']['Conjured'][] = $line;
					}
					else
					{
						//
						//if all else fails its an unexpected/unparsed line perhaps "extra" ?
						//$tt['Attributes']['ItemText'] = $line;
						$unparsed[]=$line;
					}
				} // end pass2 if
			} // end pass1
		} // end foreach
		//echo '<pre>';print_r($tt);echo '</pre>';
		if (isset($_GET['debug']))
		{
			echo '<pre>';print_r($tt);echo '</pre>';
		}
		if (isset( $unparsed ) && count($unparsed) == 1)
		{
			if( preg_match('/([+,0-9]+)\s(.+)/', $unparsed[0], $matches) )
			{
				//echo '<pre>';print_r($matches);echo '</pre>';
				$tt['Attributes']['Enchantment'] = $matches[0];
				$tooltip = str_replace( $matches[0] . "\n", '', $tooltip);
				$unparsed = null;
			}
		}
		if( isset( $unparsed ) )
		{
			$source = implode( '\n', $unparsed);
			$test = "|". str_replace( "\n", "|__BR__|", $source). "|";
			$test = str_replace( "\t", "|__BR__|", $test);
			$test1 = htmlentities($test);
			$test2 = utf8_decode($test);
			$test3 = utf8_encode($test);
			$test4 = '<table border="1" cellspacing="0" cellpadding="0"><tr>';
			for ( $i = 0; $i < strlen($source); $i++ ) {
				$char = substr( $source, $i, 1);
				$test4 .= '<td align="center">'. $char. "<br />". ord($char). "</td>";
			}
			$test4 .= '</tr></table>';
			$cmp = "Result=>__BR__". $source. "__BR____BR__htmlentities=>__BR__". $test1. "__BR____BR__utf8_decode=>__BR__". $test2. "__BR____BR__utf8_encode=>__BR__". $test3. "__BR____BR__ord=>__BR__";

			trigger_error( "Failed to Parse \"$this->name\": [$this->item_id] ($this->locale) colorToolTip() used<br />" . implode('<br />', $unparsed) . '<br />' . str_replace( '__BR__', '<br />', htmlspecialchars( $cmp) . $test4 ) );
			$this->isParseError = true;
			$this->DEBUG_junk = $unparsed;
		}

		$this->parsed_item = $tt;
		$this->attributes = ( isset($tt['Attributes']) ? $tt['Attributes'] : null );
		$this->effects = ( isset($tt['Effects']) ? $tt['Effects'] : null );
		if( $this->isSetPiece )
		{
			$this->_setArmorSets();
		}
	}

	/**
	 * Fetches Gem information from the gems table
	 *
	 * @param String $gem_id
	 * @param String $locale
	 * @return array
	 */
	function fetchGem($gem_id, $locale=false)
	{
		if( !$gem_id )
		{
			return false;
		}

		if( !$locale )
		{
			$locale = $this->locale;
		}

		global $roster;

		if( $roster->cache->mcheck($gem_id . $locale) )
		{
			return $roster->cache->mget($gem_id . $locale);
		}

		$sql = "SELECT `gem_id` AS GemId, `gem_name` AS Name, `gem_color` AS Color, `gem_tooltip` AS Tooltip, "
			 . "`gem_texture` AS Icon, `gem_bonus` AS Bonus, `gem_socketid` AS SocketId, `locale` FROM `"
			 . $roster->db->table('gems') . "` WHERE `gem_id` = '" . $gem_id . "' AND `locale` = '" . $locale . "';";
		$result = $roster->db->query($sql);
		$gem = $roster->db->fetch($result, SQL_ASSOC);

		if( !$gem )
		{
			$this->isParseError = true;
			trigger_error("Failed to find gemid: $gem_id in gems table!");
			return false;
		}

		$gem['Tooltip'] = str_replace("\n", '<br>', $gem['Tooltip']);
		$roster->cache->mput( $gem, $gem_id . $locale );

		return $gem;
	}

	function _setArmorSets()
	{
		if( !$this->isSetPiece )
		{
			return false;
		}
		//
		// sanity check for odd issue with esES locale, seems not to save set information in tooltip check CP.lua file
		$armor_pieces = ( isset($this->attributes['Set']['ArmorSet']['Piece']) ? $this->attributes['Set']['ArmorSet']['Piece'] : null );

		if( $armor_pieces == null)
		{
			$this->isParseError = true;
			trigger_error("Unable to retieve Set Information, bad upload? Falling back to colorTooltip() [$this->item_id]");
			return false;
		}

		$data = $this->_fetchArmorSet( $armor_pieces );

		$equip = ( isset($data['equip']) ? $data['equip'] : null );
		$owned = ( isset($data['owned']) ? $data['owned'] : null );
		$this->setItemTotal = count($armor_pieces);

		foreach( $armor_pieces as $key => $val )
		{
			if( isset($equip) && in_array($val['Name'], $equip) )
			{
				$this->attributes['Set']['ArmorSet']['Piece'][$key]['Equip'] = true;
				$this->parsed_item['Attributes']['Set']['ArmorSet']['Piece'][$key]['Equip'] = true;
				$this->setItemEquiped++;
			}
			if( isset($owned) && in_array($val['Name'], $owned) )
			{
				$this->attributes['Set']['ArmorSet']['Piece'][$key]['Owned'] = true;
				$this->parsed_item['Attributes']['Set']['ArmorSet']['Piece'][$key]['Owned'] = true;
				$this->setItemOwned++;
			}
		}
	}

	function _fetchArmorSet( $pieces=array(), $member_id='' )
	{
		global $roster;

                $armor_set = array();
		$count = count($pieces);
		$member_id = ( is_numeric($member_id) ? $member_id : $this->member_id );

		if( $count && is_array($pieces) )
		{
			global $roster;

			$i = 1; // loop count
			$sql_in = "('";
			foreach( $pieces as $item )
			{
				$sql_in .= $roster->db->escape( $item['Name'] );
				if( $i < $count )
				{
					$sql_in .= "', '";
				}
				$i++;
			}
			$sql_in .= "')";

			if( $roster->cache->mcheck($sql_in) )
			{
				return $roster->cache->mget($sql_in);
			}

			$sql = "SELECT `item_name`, `item_parent` FROM"
				 . " `" . $roster->db->table('items') . "`"
				 . " WHERE `member_id` = '$member_id'"
				 . " AND `item_name` IN $sql_in ";
			$result = $roster->db->query($sql);

			while( $data = $roster->db->fetch( $result ) )
			{
				if( $data['item_parent'] == 'equip')
				{
					$armor_set['equip'][] = $data['item_name'];
				}
				else
				{
					$armor_set['owned'][] = $data['item_name'];
				}
			}
			$roster->cache->mput($armor_set, $sql_in);
			return $armor_set;
		}
		return false;
	}

	function showBaseStats()
	{
		return $this->attributes['BaseStats'];
	}

	/**
	 * Fetches passed named item from the database. First Match is used.
	 *
	 * @param unknown_type $item_name
	 * @param unknown_type $parse_mode
	 * @return unknown
	 */
	function fetchNamedItem( $name, $parse_mode=false )
	{
		global $roster;

		$name = $roster->db->escape( $name );
		$sql = " SELECT *"
			 . " FROM `" . $roster->db->table('items') . "`"
			 . " WHERE `item_name` LIKE '%$name%'"
			 . " LIMIT 1";
		$result = $roster->db->query( $sql );
		$data = $roster->db->fetch( $result );
		if( $data )
		{
			return new item( $data, $parse_mode );
		}
		else
		{
			return false;
		}
	}

	function fetchOneItem( $member_id, $slot, $parse_mode=false )
	{
		global $roster;

		$slot = $roster->db->escape( $slot );
		$query 	= " SELECT *"
				. " FROM `" . $roster->db->table('items') . "`"
				. " WHERE `member_id` = '$member_id'"
				. " AND `item_slot` = '$slot'";

		$result = $roster->db->query( $query );
		$data = $roster->db->fetch( $result );
		if( $data )
		{
			return new item( $data, $parse_mode );
		}
		else
		{
			return null;
		}
	}

	/**
	 * fetch all $parent items on $member_id
	 * returns object array keyed by item_slot
	 *
	 * @param int $member_id
	 * @param string $parent
	 * @param string $parse_mode
	 * @return object[]
	 */
	function fetchManyItems( $member_id, $parent, $parse_mode=false )
	{
		global $roster;

		$parent = $roster->db->escape($parent);
		$items = array();
		$this->gemColors = array( 'red' => 0, 'yellow' => 0, 'blue' => 0 );

		$query  = " SELECT *"
				. " FROM `" . $roster->db->table('items') . "`"
				. " WHERE `member_id` = '$member_id'"
				. " AND `item_parent` = '$parent'";

		$result = $roster->db->query( $query );

		while( $data = $roster->db->fetch( $result ) )
		{
			$item = new item( $data, $parse_mode );
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

	/**
	 * Fetches passed itemID from the database. First Match is used.
	 *
	 * @param unknown_type $item_id
	 * @param unknown_type $parse_mode
	 * @return unknown
	 */
	function fetchItemID( $item_id, $parse_mode=false )
	{
		global $roster;

		$item_id = $roster->db->escape($item_id);
		$sql = " SELECT *"
			 . " FROM `" . $roster->db->table('items') . "`"
			 . " WHERE `item_id` = '$item_id'"
			 . " LIMIT 1;";
		$result = $roster->db->query($sql);
		$data = $roster->db->fetch($result);
		if( $data )
		{
			return new item( $data, $parse_mode );
		}
		else
		{
			return false;
		}
	}

	/**
	 * Fetches passed item itemID with associated member_id from the database.
	 *
	 * @param unknown_type $member_id
	 * @param unknown_type $item_id
	 * @param unknown_type $parse_mode
	 * @return unknown
	 */
	function fetchOneMemberItemID( $member_id, $item_id, $parse_mode=false )
	{
		global $roster;

		$item_id = $roster->db->escape($item_id);
		$member_id = $roster->db->escape($member_id);
		$query  = " SELECT *"
			. " FROM `" . $roster->db->table('items') . "`"
			. " WHERE `member_id` = '$member_id'"
			. " AND `item_id` = '$item_id';";

		$result = $roster->db->query($query);
		$data = $roster->db->fetch($result);
		if( $data )
		{
			return new item( $data, $parse_mode );
		}
		else
		{
			return null;
		}
	}

	function debugPrintAttributes()
	{
		return aprint($this->parsed_item['Attributes']);
	}

	function debugPrintAll()
	{
		return aprint($this->parsed_item);
	}

	/**
	 * Returns an Icon with Mouse over Tooltip text of the passed item if found
	 * otherwise return null
	 *
	 * @param string $name
	 * @param string $parse_mode  | accepts 'full' 'simple' by default will do best guess parsing
	 * @return string | html also sets makeOverlib()'s tooltip of successful otherwise returns null
	 */
	function printTooltipIcon( $name, $parse_mode=false )
	{
		if( !is_numeric($name) )
		{
			$item = $this->fetchNamedItem( $name );
			return $item->out();
		}
		return null;
	}

	function _addSocketColor( $socket_color, $amount=1 )
	{
		$this->sockets[$socket_color] = $this->sockets[$socket_color] + $amount;
	}

	function _initArmoryDb()
	{
		require_once(ROSTER_LIB . 'armory.class.php');
		$this->armory_db = new RosterArmory();
	}
} //end class item
