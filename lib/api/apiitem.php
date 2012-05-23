<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.2.0
 * @package    WoWRoster
 */
 
 
/*
this file will process the json data for items and make a tooltip for us ..

		EXPERAMENTAL!!! use at own risk!!

*/

class ApiItem {

var $itemclass = array(
'0' => 'Consumable','1' => 'Container','2' => 'Weapon','3' => 'Gem','4' => 'Armor','5' => 'Reagent','6' => 'Projectile',
'7' => 'Trade Goods','8' => 'Generic','9' => 'Book','10' => 'Money','11' => 'Quiver','12' => 'Quest','13' => 'Key','14' => 'Permanent',
'15' => 'Junk','16' => 'Glyph');

//itemSubClass

//'classid' => 'subclassid' => 'subclassname' => 'subclassfullname',
var $itemSubClass = array(
'0' => array('0' => 'Consumable','5' => 'Food & Drink','1' => 'Potion','2' => 'Elixir','3' => 'Flask','7' => 'Bandage','6' => 'Item Enhancement',
'4' => 'Scroll','8' => 'Other'),

'1' => array('0' => 'Bag','1' => 'Soul Bag','2' => 'Herb Bag','3' => 'Enchanting Bag','4' => 'Engineering Bag','5' => 'Gem Bag','6' => 'Mining Bag',
'7' => 'Leatherworking Bag','8' => 'Inscription Bag','9' => 'Tackle Box'),

'2' => array('0' => 'Axe','1' => 'Axe','2' => 'Bow','3' => 'Gun','4' => 'One-Handed Mace','5' => 'Two-Handed Mace','6' => 'Polearm','7' => 'One-Handed Sword',
'8' => 'Two-Handed Sword','9' => 'Obsolete','10' => 'Stave','11' => 'One-Handed Exotic','12' => 'Two-Handed Exotic','13' => 'Fist Weapon','14' => 'Miscellaneous',
'15' => 'Dagger','16' => 'Thrown','17' => 'Spear','18' => 'Crossbow','19' => 'Wand','20' => 'Fishing Pole'),
'3' => array('0' => 'Red','1' => 'Blue','2' => 'Yellow','3' => 'Purple','4' => 'Green','5' => 'Orange','6' => 'Meta','7' => 'Simple','8' => 'Prismatic',
'9' => 'Hydraulic','10' => 'Cogwheel'),
'4' => array('0' => 'Miscellaneous','1' => 'Cloth','2' => 'Leather','3' => 'Mail','4' => 'Plate','5' => 'Bucklers','6' => 'Shield','7' => 'Libram',
'8' => 'Idol','9' => 'Totem','10' => 'Sigil','11' => 'Relic'),
'5' => array('0' => 'Reagent'),
'6' => array('0' => 'Wand(OBSOLETE)','1' => 'Bolt(OBSOLETE)','2' => 'Arrow','3' => 'Bullet','4' => 'Thrown(OBSOLETE)'),
'7' => array('0' => 'Trade Goods','10' => 'Elemental','15' => 'Weapon Enchantment - Obsolete','5' => 'Cloth','6' => 'Leather','7' => 'Metal & Stone','8' => 'Meat',
'9' => 'Herb','12' => 'Enchanting','4' => 'Jewelcrafting','1' => 'Parts','3' => 'Devices','2' => 'Explosives','13' => 'Materials','11' => 'Other','14' => 'Item Enchantment'),
//'0' => 'Generic(OBSOLETE)',
'9' => array('0' => 'Book','1' => 'Leatherworking','2' => 'Tailoring','3' => 'Engineering','4' => 'Blacksmithing','5' => 'Cooking','6' => 'Alchemy','7' => 'First Aid',
'8' => 'Enchanting','9' => 'Fishing','10' => 'Jewelcrafting','11' => 'Inscription'),
//'0' => 'Money(OBSOLETE)',
'11' => array('0' => 'Quiver(OBSOLETE)','1' => 'Quiver(OBSOLETE)','2' => 'Quiver','3' => 'Ammo Pouch'),
'12' => array('0' => 'Quest'),
'13' => array('0' => 'Key','1' => 'Lockpick'),
'14' => array('0' => 'Permanent'),
'15' => array('0' => 'Junk','1' => 'Reagent','2' => 'Pet','3' => 'Holiday','4' => 'Other','5' => 'Mount'),
'16' => array('1' => 'Warrior','2' => 'Paladin','3' => 'Hunter','4' => 'Rogue','5' => 'Priest','6' => 'Death Knight','7' => 'Shaman','8' => 'Mage',
'9' => 'Warlock','11' => 'Druid'));

var $slotType = array('0' => 'None','1' => 'Head','2' => 'Neck','3' => 'Shoulder','4' => 'Shirt','5' => 'Chest','6' => 'Waist','7' => 'Legs','8' => 'Feet',
'9' => 'Wrist','10' => 'Hands','11' => 'Finger','12' => 'Trinket','13' => 'One-Hand','14' => 'Shield','15' => 'Ranged','16' => 'Cloak','17' => 'Two-Hand',
'18' => 'Bag','19' => 'Tabard','20' => 'Robe','21' => 'Main Hand','22' => 'Off Hand','23' => 'Held In Off-hand','24' => 'Ammo','25' => 'Thrown','26' => 'Ranged Right',
'28' => 'Relic');


/*
itemQuality
'qualityid' => 'qualityname' => 'qualitycolor',
'0' => 'Poor' => '9D9D9D',
'1' => 'Common' => 'FFFFFF',
'2' => 'Uncommon' => '1EFF00',
'3' => 'Rare' => '0070DD',
'4' => 'Epic' => 'A335EE',
'5' => 'Legendary' => 'FF8000',
'6' => 'Artifact' => 'E5CC80',
'7' => 'Heirloom' => 'E5CC80',
'8' => 'Quality 8' => 'FFFF98',
'9' => 'Quality 9' => '71D5FF"
*
stat types
*/
var $statlocal = array(
'1' => 'Health',
'2' => 'Mana',
'3' => 'Agility',
'4' => 'Strength',
'5' => 'Intellect',
'6' => 'Spirit',
'7' => 'Stamina',
);
var $itemstat = array(
'1' => '+%s Health',
'2' => '+%s Mana',
'3' => '+%s Agility',
'4' => '+%s Strength',
'5' => '+%s Intellect',
'6' => '+%s Spirit',
'7' => '+%s Stamina',
'46' => 'Equip: Restores %s health per 5 sec.',
'44' => 'Equip: Increases your armor penetration rating by %s.',
'38' => 'Equip: Increases attack power by %s.',
'15' => 'Equip: Increases your shield block rating by %s.',
'48' => 'Equip: Increases the block value of your shield by %s.',
'19' => 'Equip: Improves melee critical strike rating by %s.',
'20' => 'Equip: Improves ranged critical strike rating by %s.',
'32' => 'Equip: Increases your critical strike rating by %s.',
'21' => 'Equip: Improves spell critical strike rating by %s.',
'25' => 'Equip: Improves melee critical avoidance rating by %s.',
'26' => 'Equip: Improves ranged critical avoidance rating by %s.',
'34' => 'Equip: Improves critical avoidance rating by %s.',
'27' => 'Equip: Improves spell critical avoidance rating by %s.',
//ITEM_MOD_DAMAGE_PER_SECOND_SHORT => 'Damage Per Second',
'12' => 'Equip: Increases defense rating by %s.',
'13' => 'Equip: Increases your dodge rating by %s.',
'37' => 'Equip: Increases your expertise rating by %s.',
'40' => 'Equip: Increases attack power by %s in Cat, Bear, Dire Bear, and Moonkin forms only.',
'28' => 'Equip: Improves melee haste rating by %s.',
'29' => 'Equip: Improves ranged haste rating by %s.',
'36' => 'Equip: Increases your haste rating by %s.',
'30' => 'Equip: Improves spell haste rating by %s.',
'16' => 'Equip: Improves melee hit rating by %s.',
'17' => 'Equip: Improves ranged hit rating by %s.',
'31' => 'Equip: Increases your hit rating by %s.',
'18' => 'Equip: Improves spell hit rating by %s.',
'22' => 'Equip: Improves melee hit avoidance rating by %s.',
'23' => 'Equip: Improves ranged hit avoidance rating by %s.',
'33' => 'Equip: Improves hit avoidance rating by %s.',
'24' => 'Equip: Improves spell hit avoidance rating by %s.',
'43' => 'Equip: Restores %s mana per 5 sec.',
'49' => 'Equip: Increases your mastery rating by %s.',
'14' => 'Equip: Increases your parry rating by %s.',
'39' => 'Equip: Increases ranged attack power by %s.',
'35' => 'Equip: Increases your resilience rating by %s.',
'41' => 'Equip: Increases damage done by magical spells and effects by up to %s.',
'42' => 'Equip: Increases healing done by magical spells and effects by up to %s.',
'47' => 'Equip: Increases spell penetration by %s.',
'45' => 'Equip: Increases spell power by %s.');

var $bind = array(
'0' => '','8' => "Account Bound",'2' => "Binds when equipped",'1' => "Binds when picked up",'3' => "Binds when used",'4' => "Quest Item",
'5' => "Binds to account",'6' => "Binds to Battle.net account",'7' => "Battle.net Account Bound");
var $skills = array(
"185" => "Cooking","773" => "Inscription","755" => "Jewelcrafting","393" => "Skinning","333" => "Enchanting","202" => "Engineering",
"197" => "Tailoring","186" => "Mining","182" => "Herbalism","171" => "Alchemy","165" => "Leatherworking","164" => "Blacksmithing");

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
	var $user = array();
	var $dapi = array();
	var $dgems = array();
	
	
	
	function item ($data,$userData,$gems=false)
	{
		global $api, $roster;
		$this->user = $userData;
		$this->dapi = $data;
		$this->dgems = $gems;
		
		if (isset($data['tooltip_html']))
		{
			$a = str_replace("<br /><br />", '<br />', $data['tooltip_html']);//$data['tooltip_html'];
		}
		else
		{
			$this->build_Attributes($data);
			$a = $this->_makeTooltip();
		}
		return $a;
	}
	
	function build_Attributes($data)
	{
		global $api, $roster;
		//duhharharhar copy and paste....
		$tt=array();
			//$tt['Attributes']['TempEnchantment'][] = $matches[1];
			if (isset($data['socketInfo']['socketBonus']))
			{
				$tt['Attributes']['SocketBonus'] = $data['socketInfo']['socketBonus'];
			}
//			$tt['Attributes']['Enchantment'] = $matches[1];
			$tt['General']['Name'] = $data['name'];
			$tt['General']['ItemId'] = $data['id'];
			$tt['General']['ItemColor'] = $this->_getItemColor($data['quality']);
			$tt['General']['Icon'] = $data['icon'];
			$tt['Attributes']['Icon'] = $data['icon'];
			$tt['General']['Slot'] = $data['inventoryType'];
			$tt['General']['Parent'] = 'Equip';
			$tt['General']['Tooltip'] = (isset($data['tooltip_html']) ? $data['tooltip_html'] : '');// i wish...str_replace("<br />", '<br />', $data['tooltip']);
			$tt['General']['Locale']=$roster->config['locale'];

			
			foreach( $data['bonusStats'] as $id => $stat )
			{
				if ($stat['stat'] <= '7')
				{
					$tt['Attributes']['BaseStats'][$this->statlocal[$stat['stat']]] = sprintf( $this->itemstat[$stat['stat']], $stat['amount']);
				}
			}
			foreach( $data['bonusStats'] as $id => $stat )
			{
				if ($stat['stat'] >= '8')
				{
					$tt['Effects']['Equip'][] = sprintf( $this->itemstat[$stat['stat']], $stat['amount']);
				}
			}
			if (isset($data['itemSpells']))
			{
				foreach($data['itemSpells'] as $id => $spell)
				{
					if ($spell['spell']['description'] != '')
					{
						if ($spell['trigger'] == 'ON_EQUIP' )
						{
							$tt['Effects']['Equip'][] = 'Equip: '.$spell['spell']['description'];
						}
						else
						{
							$tt['Effects']['Use'][] = 'Use: '.$spell['spell']['description'];
						}
					}
				}
			}
			$tt['Attributes']['ItemLevel'] = $data['itemLevel'];
			if ($data['requiredLevel'] > 0)
			{
				$tt['Attributes']['Requires'] = $data['requiredLevel'];
			}
			//$tt['Effects']['ChanceToProc'][] = $line;
			$tt['Attributes']['BindType'] = $this->bind[$data['itemBind']];

			if (isset($this->user['tooltipParams']['set']))
			{
				$this->isSetPiece = true;
				foreach ($data['itemSet']['setBonuses'] as $r => $e)
				{
					$tt['Attributes']['Set']['SetBonus'][] = "(".$e['threshold'].") Set: ".$e['description']."";
				}
				$tt['Attributes']['Set']['InactiveSet'][] = '';//$line;
				$tt['Attributes']['Set']['ArmorSet']['Name'] = $data['itemSet']['name'];
				$this->setItemEquiped = count($this->user['tooltipParams']['set']);
				//$this->isSetPiece = true;
				$setpiece = 1;
				$tt['Attributes']['Set']['ArmorSet']['Piece'][$setpiece]['Name'] = $data['name'];//trim($line);
			}
			
			
			if ($data['maxDurability'] > 0)
			{
				$tt['Attributes']['Durability']['Line']= 'Durability';
				$tt['Attributes']['Durability']['Current'] = ( isset($data['curDurability']) ? $data['curDurability'] : $data['maxDurability']);
				$tt['Attributes']['Durability']['Max'] = $data['maxDurability'];
			}
			if (isset($data['allowableClasses']))
			{
				$tt['Attributes']['Class'] = 'Classes:';
				foreach($data['allowableClasses'] as $id => $classes)
				{
					$tt['Attributes']['ClassText'][] = $roster->locale->wordings['enUS']['id_to_class'][$classes];
				}
			}
			if (isset($data['allowableRaces']))
			{
				$tt['Attributes']['Race'] = 'Races:';
				foreach($data['allowableRaces'] as $id => $classes)
				{
					$tt['Attributes']['RaceText'][] = 		$roster->locale->wordings['enUS']['id_to_race'][$classes];
				}
			}
			//socketInfo][sockets
			if (isset($data['socketInfo']['sockets']))
			{
				foreach($data['socketInfo']['sockets'] as $id => $sc)
				{
					$sk =  mb_strtolower($sc['type'], 'UTF-8');
					$tt['Attributes']['Sockets'][$id]['color'] = ucfirst($sk);
					$tt['Attributes']['Sockets'][$id]['line'] = ''.ucfirst($sk).' Socket';
				}
			}
			if(isset($data['gemInfo']))
			{
				$tt['Attributes']['GemBonus'] = $data['gemInfo']['bonus']['name'];
				if (isset($data['gemInfo']['bonus']['requiredSkillId']) && $data['gemInfo']['bonus']['requiredSkillId'] > 0)
				{
					$tt['Attributes']['SkillRequired'] = $this->skills[$data['gemInfo']['bonus']['requiredSkillId']].' ('.$data['gemInfo']['bonus']['requiredSkillRank'].')';
				}
			}
			$this->isSocketable = $data['hasSockets'];
			
			$tt['Attributes']['ItemNote'] = $data['description'];
			//$tt['Attributes']['Unique'] = $line;
			if ($data['itemClass'] == '4')
			{
				if ($data['baseArmor'] > 0)
				{
					$tt['Attributes']['ArmorClass']['Line'] = $data['baseArmor'] .' Armor';
					$tt['Attributes']['ArmorClass']['Rating'] = $data['baseArmor'];
				}
				$tt['Attributes']['ArmorType'] = $this->itemSubClass[$data['itemClass']][$data['itemSubClass']];
				$tt['Attributes']['ArmorSlot'] = ''.$this->slotType[$data['inventoryType']].'';
				$this->isArmor = true;
			}
			if ($data['itemClass'] == '2' )
			{
				$tt['Attributes']['WeaponType'] = $this->itemSubClass[$data['itemClass']][$data['itemSubClass']];
				$tt['Attributes']['WeaponSlot'] = ''.$this->slotType[$data['inventoryType']].'';
				$tt['Attributes']['WeaponSpeed'] = $data['weaponInfo']['weaponSpeed'];
				$tt['Attributes']['WeaponDamage'] = $data['weaponInfo']['damage']['min'].' - '.$data['weaponInfo']['damage']['max'];
				$tt['Attributes']['WeaponDPS'] = number_format($data['weaponInfo']['dps'], 1, '.', '');//$data['weaponInfo']['dps'];
				$this->isWeapon = true;
				
			}
			if ($data['itemClass'] == '1' )
			{
				$tt['Attributes']['BagSomething'] = $this->itemSubClass[$data['itemClass']][$data['itemSubClass']];
				$tt['Attributes']['BagType'] = ''.$this->slotType[$data['inventoryType']].'';
				$tt['Attributes']['BagSize'] = $data['containerSlots'];
				$tt['Attributes']['BagDesc'] = $data['containerSlots'].' Slot '.$this->itemSubClass[$data['itemClass']][$data['itemSubClass']].'';
				$this->isBag = true;
			}
			//$this->isWeapon = true;
			//$tt['Attributes']['MadeBy']['Name'] = $matches[1];
			//$tt['Attributes']['MadeBy']['Line'] = $matches[0];

			//$tt['Attributes']['Charges'] = $line;
			//$tt['Poison']['Effect'][] = $line;
			//$this->isPoison = true;
			//$tt['Attributes']['Conjured'][] = $line;
			//$this->parsed_item = $tt;
			//$this->attributes = ( isset($tt['Attributes']) ? $tt['Attributes'] : null );
			//$this->effects = ( isset($tt['Effects']) ? $tt['Effects'] : null );
			//echo '<pre>';
			//print_r($tt);
		$this->parsed_item = $tt;
		$this->attributes = ( isset($tt['Attributes']) ? $tt['Attributes'] : null );
		$this->effects = ( isset($tt['Effects']) ? $tt['Effects'] : null );
		
	}
	
	function _makeTooltip()
	{
		$html_tt = $this->_getCaption();
		if( isset($this->attributes['Conjured']) )
		{
			$html_tt .= $this->_getConjures();
		}
		if( isset($this->attributes['Heroic']) )
		{
			$html_tt .= $this->_getHeroic();
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
		if( $this->isWeapon )
		{
			$html_tt .= $this->_getWeapon();
		}
		if( $this->isBag )
		{
			$html_tt .= $this->_getBag();
		}

		if( isset($this->attributes['ArmorClass']) )
		{
			$html_tt .= $this->_getArmorClass();
		}
		if( isset($this->attributes['GemBonus']) )
		{
			$html_tt .= $this->_getGemBonus();
		}
		if( isset($this->attributes['SkillRequired']) )
		{
			$html_tt .= $this->_getSkillRequired();
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
		if( isset($this->attributes['ItemLevel']) )
		{
			$html_tt .= $this->_getItemLevel();
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

		$html_tt = str_replace("<br />", '<br />', $html_tt);
		
		if( ($this->DEBUG && $this->isParseError) || $this->DEBUG == 2 )
		{
			trigger_error('<table class="border_frame" cellpadding="0" cellspacing="1" width="350"><tr><td>'
			. $html_tt
			. '<hr width="80%" /> ' . str_replace("<br />", '<br />', $this->tooltip)
			. '</td></tr></table><br />'
			. aprint($this->parsed_item,'',true));
		}
		//echo  $html_tt . ( $this->DEBUG ? '<br />Parsed Full' : '' );
		$tooltip = ''.$html_tt.'';
		return $tooltip;
	}
	
function _getCaption()
	{
		$html = '' . $this->parsed_item['General']['Name'] . "<br />";
		return $html;
	}

	// heroic shit wow i missed this 
	function _getHeroic()
	{
		global $roster;

		$heroic = $this->attributes['Heroic'];

		if( preg_match( $roster->locale->wordings[$this->locale]['tooltip_preg_heroic'], $heroic) )
		{
			$color = '66DD33';
		}
		
		else
		{
			$color = 'ffffff';
		}

		$html = '' . $heroic . "<br />";

		return $html;
	}
	
	function _getBindType()
	{
		global $roster;

		$html = $this->attributes['BindType']. "<br />";

		return $html;
	}

	function _getConjures()
	{
		$html = '';
		foreach( $this->attributes['Conjured'] as $conjured )
		{
			$html .= $conjured . "<br />";
		}
		return $html;
	}

	function _getUnique()
	{
		$html = $this->attributes['Unique'] . "<br />";
		return $html;
	}

	function _getArmor()
	{
		if( isset($this->attributes['ArmorType']) && isset($this->attributes['ArmorSlot']) )
		{
			//$html = '<div style="width:100%;"><span style="float:right;">' . $this->attributes['ArmorType'] . '</span>' . $this->attributes['ArmorSlot'] . '</div>';
			$html = '' . $this->attributes['ArmorSlot'] . '	' . $this->attributes['ArmorType'] . "<br />";
		}
		elseif( isset($this->attributes['ArmorSlot'] ) )
		{
			$html = $this->attributes['ArmorSlot'] . "<br />";
		}
		elseif( isset($this->attributes['ArmorType']) )
		{
			$html = $this->attributes['ArmorType'] . "<br />";
		}
		else
		{
			return null;
		}
		return $html;
	}

	function _getWeapon()
	{
		$html='';
		if( isset($this->attributes['WeaponType']) && isset($this->attributes['WeaponSlot']) )
		{
			/*$html = '<div style="width:100%;"><span style="float:right;">'
				  . $this->attributes['WeaponType'] . '</span>'
				  . $this->attributes['WeaponSlot'] . '</div>';
				  */
			$html .= '' . $this->attributes['WeaponSlot'] . '	' . $this->attributes['WeaponType'] . "<br />";
		}
		elseif( isset($this->attributes['WeaponType']) )
		{
			$html .= $this->attributes['WeaponType'] . "<br />";
		}
		elseif( isset($this->attributes['WeaponSlot']) )
		{
			$html .= $this->attributes['WeaponSlot'] . "<br />";
		}

		if( isset($this->attributes['WeaponDamage']) )
		{
			$html .='' . $this->attributes['WeaponDamage'] . ' Damage	Speed ' . $this->attributes['WeaponSpeed'] . "<br />";
		}
		if( isset($this->attributes['WeaponDPS']) )
		{
			$html .= '('.$this->attributes['WeaponDPS'] . " damage per second)<br />";
		}

		return $html;
	}

	function _getBag()
	{
		if (isset( $this->attributes['BagDesc']))
		{
		//$html = $this->attributes['BagDesc'] . "<br />";
		$html = $this->attributes['BagDesc'] . "<br />";
		return $html;
		}
		else
		{
		return;
		}
		
	}
	function _getGemBonus()
	{
		$html = $this->attributes['GemBonus'] . "<br />";
		return $html;
	}

	function _getSkillRequired()
	{
		$html = 'Requires ' . $this->attributes['SkillRequired'] . "<br />";
		return $html;
	}
	function _getArmorClass()
	{
		$html = $this->attributes['ArmorClass']['Line'] . "<br />";
		return $html;
	}

	function _getBaseStats()
	{
		$html = '';
		$stats = array();
		$stats = $this->attributes['BaseStats'];

		foreach( $stats as $stat )
		{
			$html .= '' . $stat . "<br />";
		}
		return $html;
	}

	function _getEnchantment()
	{
		$html = '<span style="color:#00ff00;">' . $this->attributes['Enchantment'] . '</span><br />';
		return $html;
	}

	function _getTempEnchantment()
	{
		$html = '';

		foreach( $this->attributes['TempEnchantment'] as $bonus )
		{
			$html .= '' . $bonus . "<br />";
		}
		return $html;
	}

	function _getSockets()
	{
		global $roster;

		$html = '';

		$numSockets = count($this->dapi['socketInfo']['sockets']);
		$gem0 =  $gem1 =  $gem2 = null;
		$i =0;
		
		if (isset($this->user['tooltipParams']['gem0']))
		{
			$html .= $this->dgems['gem0']['gemInfo']['bonus']['name']."<br />";
			$i++;
		}
		if (isset($this->user['tooltipParams']['gem1']))
		{
			$html .= $this->dgems['gem1']['gemInfo']['bonus']['name']."<br />";
			$i++;
		}
		if (isset($this->user['tooltipParams']['gem2']))
		{
			$html .= $this->dgems['gem2']['gemInfo']['bonus']['name']."<br />";
			$i++;
		}

		for( $i; $i < $numSockets; $i++ )
		{
			$sk =  mb_strtolower($this->dapi['socketInfo']['sockets'][$i]['type'], 'UTF-8');
			$html .= '' .  ucfirst ($sk) . " Socket<br />";
		}
		//now lets do sockets with gems
		
		return $html;
	}

	function _getSocketBonus()
	{
		if( isset($this->attributes['SocketBonus']) )
		{
			if( isset($this->isSocketBonus) == true )
			{
				$html = 'Socket Bonus: ' . $this->attributes['SocketBonus'] . "<br />";
			}
			else
			{
				$html = 'Socket Bonus: ' . $this->attributes['SocketBonus'] . "<br />";
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
		$html = $this->attributes['Durability']['Line'] . ' ';

		$html .= $current . ' / ' . $max . "<br />";

		return $html;
	}

	function _getRequiredClasses()
	{
		global $roster;

		$html = $this->attributes['Class'] . ' ';
		$count = count($this->attributes['ClassText']);

		$i = 0;
		foreach( $this->attributes['ClassText'] as $class => $x)
		{
			$i++;
			$html .= $x;
			if( $count > $i )
			{
				$html .= ', ';
			}
		}
		$html .= "<br />";
		return $html;
	}

	function _getRequiredRaces()
	{
		$html = $this->attributes['Race'] . ' ';
		$count = count($this->attributes['RaceText']);

		$i = 0;
		foreach( $this->attributes['RaceText'] as $class => $x)
		{
			$i++;
			$html .= $x;
			if( $count > $i )
			{
				$html .= ', ';
			}
		}
		$html .= "<br />";
		return $html;
	}

	function _getRequired()
	{
		global $roster;

		$requires = array();
		$requires = $this->attributes['Requires'];
		$html = '';
		$html .= 'Requires Level '.$this->attributes['Requires']."<br />";

		return $html;
	}

	
	function _getILevel()
	{
		global $roster;

		$this->attributes['ItemLevel'];
		$html = '';
		$html .= 'Item Level '.$this->attributes['ItemLevel']."<br />";

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
				$html .= $effect . "<br />";
			}
		}
		return $html;
	}

	function _getItemCharges()
	{
		$html = $this->attributes['Charges'] . "<br />";
		return $html;
	}

	function _getSetPiece()
	{
		$html = '';
		if (!empty($this->attributes['Set']['ArmorSet']['Name']))
		{
			$html .= "<br /><br />" . $this->attributes['Set']['ArmorSet']['Name'] ." (".$this->setItemEquiped."/5)<br />";
		}
		$pices = $this->attributes['Set']['ArmorSet']['Piece'];
		foreach ($pices as $num => $p)
		{
			$html .= "" . $this->attributes['Set']['ArmorSet']['Piece'][$num]['Name'] ."<br />";
		}
		return $html;
	}

	function _getSetBonus()
	{
		if( isset($this->attributes['Set']['SetBonus']) )
		{
			$html = '';
			foreach( $this->attributes['Set']['SetBonus'] as $bonus )
			{
				$html .= $bonus . "<br />";
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
			$html .= $piece . "<br />";
		}
		return $html;
	}

	function _getCrafter()
	{
		$html = htmlentities($this->attributes['MadeBy']['Line']) . "<br />";
		return $html;
	}

	function _getRestrictions()
	{
		$html = '';

		foreach( $this->attributes['Restrictions'] as $val )
		{
			$html .= $val . "<br />";
		}
		return $html;
	}

	function _getItemNote()
	{
		$html = $this->attributes['ItemNote'] . "<br />";
		return $html;
	}

	function _getItemLevel()
	{
		$html = 'Item Level ' . $this->attributes['ItemLevel']. "<br />";
		return $html;
	}

	function _getBoss()
	{
		
		$html = '';
		return $html;
	}

	function _getSource()
	{

		$html = '';
		return $html;
	}

	function _getDropRate()
	{

		$html = '';
		return $html;
	}
	
		function _getItemColor($value) 
		{
		$ret = '';
		switch ($value) {
			case 5: $ret = "ff8000"; //Orange
				break;
			case 4: $ret = "a335ee"; //Purple
				break;
			case 3: $ret = "0070dd"; //Blue
				break;
			case 2: $ret = "1eff00"; //Green
				break;
			case 1: $ret = "ffffff"; //White
				break;
			default: $ret = "9d9d9d"; //Grey
				break;
		}
		
		return $ret;
	}

	
}

?>