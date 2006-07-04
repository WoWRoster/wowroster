<?php
/**
 * Character Class
 * $Id$
 * @author six
 */

class Character {

	var $name = false;
	var $profile = false;
	var $isValidCharacter = false;
	var $guildName;
	var $guildId;
	var $Id = false;
	var $level = false;

	function Character($character_name = false){
		if($character_name){
			global $roster_conf;
			$this->name = $character_name;
			$this->guildName = $roster_conf['guild_name'];
			$this->guildId = Guild::GetGuildId($this->guildName, $roster_conf['realm_name']);
			if($this->isValidCharacter()){
				$this->getAllCharacterData();
			}
		} else {
			return(false);
		}
	}

	function getAllCharacterData(){
		// TODO
	}

	function getCharacterId(){
		// If we already have the data, just return it.
		if($this->Id){
			return($this->Id);
		}
		// Since we don't, we look it up, and store it for future reference.
		$sql="SELECT member_id FROM ".ROSTER_MEMBERSTABLE." WHERE name = '".escape($this->name)."' AND guild_id = ".$this->guildId;
		$res = db_query($sql);
		if($res->numRows()==1){
			$res->fetchInto($r);
			$this->Id = $r['member_id'];
			return($this->Id);
		}
		return(false);
	}

	function isValidCharacter(){
		if($this->getCharacterId()!==false)
		return(true);
		return(false);
	}

	function PerformUpdate($profile){
		if(!$this->name){
			return("You didn't specify a character name yet.\n");
		}
		if(!$this->isValidCharacter()){
			return("Couldn't find ".$this->name." in the roster. A guild update is probably needed.\n");
		} else {
		    $retval="Processing ".$this->name."...\n";
		}
		$this->profile = $profile;
		// Update Members Table
		$this->UpdateMembersTable();

		// Update Players Table
		$this->UpdatePlayer();
		unset($this->profile['Ranged Attack']);
		unset($this->profile['Melee Attack']);

		// Update Talents
		$this->UpdateTalents();
		unset($this->profile['Talents']);

		$this->UpdateReputation();
		unset($this->profile['Reputation']);

		$this->UpdateSkills();
		unset($this->profile['Skills']);

		$this->UpdateEquipment();
		unset($this->profile['Equipment']);

		$this->UpdateBank();
		unset($this->profile['Bank']);

		$this->UpdateInventory();
		unset($this->profile['Inventory']);

		$this->UpdateRecipes();
		unset($this->profile['Professions']);

	    $this->UpdateSpells();
		unset($this->profile['SpellBook']);

	    $this->UpdatePets();
		unset($this->profile['Pets']);

	    unset($this->profile['Buffs']);
		unset($this->profile['Debuffs']);
		unset($this->profile['Quests']);
		unset($this->profile['Stats']);
		unset($this->profile['Honor']);
		unset($this->profile['MailBox']);
		//	pre_r($this->profile);


		return($retval."Processed $this->name\n");
	}

	function RetrieveProfile(){
		if($this->profile == false){
			// TODO: Fetch the profile from the db.
		}
		return($this->profile);
	}

	function GetLevel(){
		return($this->Level);
	}

	function SetLevel($level){
		$this->Level = $level;
		$sql = "UPDATE ".ROSTER_MEMBERSTABLE." SET `level` = ".intval($level)." WHERE `member_id` = ".$this->Id;
		db_query($sql);
	}

    /* private */ function UpdateBank(){
		// Exit if no bank data
		if(!isset($this->profile['Bank'])){
			return(false);
		}

		$e = $this->profile['Bank'];
		db_query("DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = ".$this->Id." AND (item_slot LIKE 'bank%' OR item_parent LIKE 'bank%')");
		// Isnert the magic bank item.
		$data = array("member_id"=>$this->Id, "item_name"=>"Bank Contents", "item_parent"=>"bags", "item_slot"=>"Bank Contents", "item_color"=>"ffffffff",
		"item_id"=>"", "item_texture"=>'Interface\Icons\INV_Misc_Bag_07', "item_quantity"=>24);
		db_query_insert_array(ROSTER_ITEMSTABLE, array_keys($data), $data);

		// Populate the bank item
		foreach($e['Contents'] as $slot_name=>$slot){
			if(isset($slot['Quantity'])){
				$quantity = $slot['Quantity'];
			} else {
				$quantity = 0;
			}
			$data = array("member_id"=>$this->Id, "item_name"=>$slot['Name'], "item_parent"=>"Bank Contents", "item_slot"=>$slot_name,
			"item_color"=>$slot['Color'], "item_id"=>$slot['Item'], "item_texture"=>$slot['Texture'], "item_quantity"=>$quantity, "item_tooltip"=>prep_tooltip_for_db($slot['Tooltip']));
			db_query_insert_array(ROSTER_ITEMSTABLE, array_keys($data), $data);
		}
		unset($e['Contents']);

		// Do the other bags.
		foreach($e as $slot_name=>$slot){
			$bagname = "Bank ".$slot_name;
			$data = array("member_id"=>$this->Id, "item_name"=>$slot['Name'], "item_parent"=>"bags", "item_slot"=>$bagname,
			"item_color"=>$slot['Color'], "item_id"=>$slot['Item'], "item_texture"=>$slot['Texture'], "item_quantity"=>$slot['Slots'], "item_tooltip"=>prep_tooltip_for_db($slot['Tooltip']));
			db_query_insert_array(ROSTER_ITEMSTABLE, array_keys($data), $data);
			foreach($slot['Contents'] as $slot_name=>$slot){
				if(isset($slot['Quantity'])){
					$quantity = $slot['Quantity'];
				} else {
					$quantity = 0;
				}
				$data = array("member_id"=>$this->Id, "item_name"=>$slot['Name'], "item_parent"=>$bagname, "item_slot"=>$slot_name,
				"item_color"=>$slot['Color'], "item_id"=>$slot['Item'], "item_texture"=>$slot['Texture'], "item_quantity"=>$quantity, "item_tooltip"=>prep_tooltip_for_db($slot['Tooltip']));
				db_query_insert_array(ROSTER_ITEMSTABLE, array_keys($data), $data);
			}
		}

	}

	/* private */ function UpdateEquipment(){
		$e = $this->profile['Equipment'];
		db_query("DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = ".$this->Id." AND item_parent = 'equip'");
		foreach($e as $slot_name=>$slot){
			if(isset($slot['Quantity'])){
				$quantity = $slot['Quantity'];
			} else {
				$quantity = 0;
			}
			$data = array("member_id"=>$this->Id, "item_name"=>$slot['Name'], "item_parent"=>"equip", "item_slot"=>$slot_name,
			"item_color"=>$slot['Color'], "item_id"=>$slot['Item'], "item_texture"=>$slot['Texture'], "item_quantity"=>$quantity, "item_tooltip"=>prep_tooltip_for_db($slot['Tooltip']));
			db_query_insert_array(ROSTER_ITEMSTABLE, array_keys($data), $data);
		}
	}

	/* private */ function UpdateInventory(){
		// Exit if no inventory data
		if(!isset($this->profile['Inventory'])){
			return(false);
		}

		$e = $this->profile['Inventory'];
		db_query("DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = ".$this->Id." AND item_parent LIKE 'bag%' AND item_parent != 'bags'");
		db_query("DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = ".$this->Id." AND item_parent = 'bags' AND item_slot LIKE 'bag%'");
		foreach($e as $bag_name=>$bag){
			$data = array("member_id"=>$this->Id, "item_name"=>$bag['Name'], "item_parent"=>"bags", "item_slot"=>$bag_name,
			"item_color"=>$bag['Color'], "item_id"=>$bag['Item'], "item_texture"=>$bag['Texture'], "item_quantity"=>$bag['Slots'], "item_tooltip"=>prep_tooltip_for_db($bag['Tooltip']));
			db_query_insert_array(ROSTER_ITEMSTABLE, array_keys($data), $data);
			foreach($bag['Contents'] as $slot_name=>$slot){
				if(isset($slot['Quantity'])){
					$quantity = $slot['Quantity'];
				} else {
					$quantity = 0;
				}
				$data = array("member_id"=>$this->Id, "item_name"=>$slot['Name'], "item_parent"=>$bag_name, "item_slot"=>$slot_name,
				"item_color"=>$slot['Color'], "item_id"=>$slot['Item'], "item_texture"=>$slot['Texture'], "item_quantity"=>$quantity, "item_tooltip"=>prep_tooltip_for_db($slot['Tooltip']));
				db_query_insert_array(ROSTER_ITEMSTABLE, array_keys($data), $data);
			}
		}
	}

	/* private */ function UpdateMembersTable(){
		$this->SetLevel($this->profile['Level']);
	}

    /* private */ function UpdatePets(){
	db_query("DELETE FROM ".ROSTER_PETSTABLE." WHERE member_id = ".$this->Id);
	if(!isset($this->profile['Pets'])){
	    return;
	}
	$p =&$this->profile['Pets'];
	foreach($p as $pet_name=>$pet_data){
	    $pet['member_id'] = $this->Id;
	    $pet['name'] = $pet_name;
	    $pet['slot'] = $pet_data['Slot'];
	    $pet['level'] = $pet_data['Level'];
	    $pet['health'] = $pet_data['Health'];
	    $pet['mana'] = $pet_data['Mana'];

	    list($pet['xp_current'], $pet['xp_total']) = explode(':',$pet_data['Experience'],2);

	    $pet['used_training_points'] = $pet_data['TalentPointsUsed'];
	    $pet['total_training_points'] = $pet_data['TalentPoints'];
	    $pet['type'] = $pet_data['Type'];
	    $pet['loyalty'] = $pet_data['Loyalty'];
	    $pet['icon'] = $pet_data['Icon'];

		// Parse stats
		list($pet['stats_intellect_base'],$pet['stats_intellect_current'],
			$pet['stats_intellect_bonus'],$pet['stats_intellect_debuff']) = explode(':',$pet_data['Stats']['Intellect']);

		list($pet['stats_agility_base'],$pet['stats_agility_current'],
			$pet['stats_agility_bonus'],$pet['stats_agility_debuff']) = explode(':',$pet_data['Stats']['Agility']);

		list($pet['stats_stamina_base'],$pet['stats_stamina_current'],
			$pet['stats_stamina_bonus'],$pet['stats_stamina_debuff']) = explode(':',$pet_data['Stats']['Stamina']);

		list($pet['stats_strength_base'],$pet['stats_strength_current'],
			$pet['stats_strength_bonus'],$pet['stats_strength_debuff']) = explode(':',$pet_data['Stats']['Strength']);

		list($pet['stats_spirit_base'],$pet['stats_spirit_current'],
			$pet['stats_spirit_bonus'],$pet['stats_spirit_debuff']) = explode(':',$pet_data['Stats']['Spirit']);

		list($pet['stats_armor_base'],$pet['stats_armor_current'],
			$pet['stats_armor_bonus'],$pet['stats_armor_debuff']) = explode(':',$pet_data['Stats']['Armor']);

		list($pet['stats_defense_base'],$pet['stats_defense_current'],
			$pet['stats_defense_bonus'],$pet['stats_defense_debuff']) = explode(':',$pet_data['Stats']['Defense']);


		// Parse Resists
		list($pet['resist_frost_base'],$pet['resist_frost_current'],
			$pet['resist_frost_bonus'],$pet['resist_frost_debuff']) = explode(':',$pet_data['Resists']['Frost']);

		list($pet['resist_arcane_base'],$pet['resist_arcane_current'],
			$pet['resist_arcane_bonus'],$pet['resist_arcane_debuff']) = explode(':',$pet_data['Resists']['Arcane']);

		list($pet['resist_fire_base'],$pet['resist_fire_current'],
			$pet['resist_fire_bonus'],$pet['resist_fire_debuff']) = explode(':',$pet_data['Resists']['Fire']);

		list($pet['resist_shadow_base'],$pet['resist_shadow_current'],
			$pet['resist_shadow_bonus'],$pet['resist_shadow_debuff']) = explode(':',$pet_data['Resists']['Shadow']);

		list($pet['resist_nature_base'],$pet['resist_nature_current'],
			$pet['resist_nature_bonus'],$pet['resist_nature_debuff']) = explode(':',$pet_data['Resists']['Nature']);

		list($pet['resist_holy_base'],$pet['resist_holy_current'],
			$pet['resist_holy_bonus'],$pet['resist_holy_debuff']) = explode(':',$pet_data['Resists']['Holy']);


		// Parse Melee Attack
		if(isset($pet_data['Melee Attack'])){
			if(isset($pet_data['Melee Attack']['AttackPower'])){
				$pet['melee_power']=$pet_data['Melee Attack']['AttackPower'];
			}
			if(isset($pet_data['Melee Attack']['AttackSpeed'])){
				$pet['melee_speed']=$pet_data['Melee Attack']['AttackSpeed'];
			}
			if(isset($pet_data['Melee Attack']['AttackDPS'])){
				$pet['melee_dps']=$pet_data['Melee Attack']['AttackDPS'];
			}
			if(isset($pet_data['Melee Attack']['AttackRating'])){
				$pet['melee_rating']=$pet_data['Melee Attack']['AttackRating'];
			}
			if(isset($pet_data['Melee Attack']['DamageRange'])){
				list($pet['melee_damage_min'],$pet['melee_damage_max'])=explode(':',$pet_data['Melee Attack']['DamageRange']);
			}
			if(isset($pet_data['Melee Attack']['DamageRangeTooltip'])){
				$pet['melee_damage_range_tooltip']=prep_tooltip_for_db($pet_data['Melee Attack']['DamageRangeTooltip']);
			}
			if(isset($pet_data['Melee Attack']['AttackPowerDPS'])){
				$pet['melee_power_dps']=$pet_data['Melee Attack']['AttackPowerDPS'];
			}
			if(isset($pet_data['Melee Attack']['AttackPowerTooltip'])){
				$pet['melee_power_tooltip']=prep_tooltip_for_db($pet_data['Melee Attack']['AttackPowerTooltip']);
			}
		}

	    db_query_insert_array(ROSTER_PETSTABLE, array_keys($pet), $pet);
	}
    }

	/* private */ function UpdatePlayer(){
		$p =&$this->profile;
		$h =&$this->profile['Honor'];
		$s =&$this->profile['Stats'];
		$m =&$this->profile['Money'];

		sscanf($h['Current']['Description'], "(%s %d)", $junk, $RankInfo);

		$data = array('member_id'=>$this->Id,
		'name'=>$this->name,
		'guild_id'=>$this->guildId,
		'guild_rank'=>$p['Guild']['Rank'],
		'dodge'=>$p['DodgePercent'],
		'parry'=>$p['ParryPercent'],
		'block'=>$p['BlockPercent'],
		'mitigation'=>$p['MitigationPercent'],
		'crit'=>$p['CritPercent'],
		'faction'=>$p['Faction'],

		'honor_current_rank'=>$RankInfo,
		'honor_current_name'=>$h['Current']['Rank'],
		'honor_current_icon'=>$h['Current']['Icon'],
		'honor_current_progress'=>$h['Current']['Progress'],
		'honor_session_hk'=>$h['Session']['HK'],
		'honor_session_dk'=>$h['Session']['DK'],
		'honor_yesterday_hk'=>$h['Yesterday']['HK'],
		'honor_yesterday_dk'=>$h['Yesterday']['DK'],
		'honor_yesterday_contib'=>$h['Yesterday']['Contribution'],
		'honor_thisweek_contrib'=>$h['ThisWeek']['Contribution'],
		'honor_thisweek_hk'=>$h['ThisWeek']['HK'],
		'honor_lastweek_hk'=>$h['LastWeek']['HK'],
		'honor_lastweek_dk'=>$h['LastWeek']['DK'],
		'honor_lastweek_contrib'=>$h['LastWeek']['Contribution'],
		'honor_lastweek_rank'=>$h['LastWeek']['Rank'],
		'honor_lifetime_hk'=>$h['Lifetime']['HK'],
		'honor_lifetime_dk'=>$h['Lifetime']['DK'],
		'honor_lifetime_name'=>$h['Lifetime']['Name'],
		'honor_lifetime_rank'=>$h['Lifetime']['Rank'],

		'race'=>$p['Race'],
		'level'=>$p['Level'],
		'realm'=>$p['Server'],
		'unused_talent_points'=>$p['TalentPoints'],
		'money_copper'=>$m['Copper'],
		'money_silver'=>$m['Silver'],
		'money_gold'=>$m['Gold'],
		'class'=>$p['Class'],
		'health'=>$p['Health'],
		'mana'=>$p['Mana'],
		'sex'=>$p['Sex'],
		'hearth'=>$p['Hearth'],
		'zone'=>$p['Zone'],
		'sub_zone'=>$p['SubZone'],
		'cp_version'=>$p['CPversion'],
		'cp_provider'=>$p['CPprovider'],
		'locale'=>$p['Locale']
		);

		// Parse stats
		list($data['stats_intellect_base'],$data['stats_intellect_current'],
			$data['stats_intellect_bonus'],$data['stats_intellect_debuff']) = explode(':',$s['Intellect']);

		list($data['stats_agility_base'],$data['stats_agility_current'],
			$data['stats_agility_bonus'],$data['stats_agility_debuff']) = explode(':',$s['Agility']);

		list($data['stats_stamina_base'],$data['stats_stamina_current'],
			$data['stats_stamina_bonus'],$data['stats_stamina_debuff']) = explode(':',$s['Stamina']);

		list($data['stats_strength_base'],$data['stats_strength_current'],
			$data['stats_strength_bonus'],$data['stats_strength_debuff']) = explode(':',$s['Strength']);

		list($data['stats_spirit_base'],$data['stats_spirit_current'],
			$data['stats_spirit_bonus'],$data['stats_spirit_debuff']) = explode(':',$s['Spirit']);

		list($data['stats_armor_base'],$data['stats_armor_current'],
			$data['stats_armor_bonus'],$data['stats_armor_debuff']) = explode(':',$s['Armor']);

		list($data['stats_defense_base'],$data['stats_defense_current'],
			$data['stats_defense_bonus'],$data['stats_defense_debuff']) = explode(':',$s['Defense']);


		// Parse Resists
		list($data['resist_frost_base'],$data['resist_frost_current'],
			$data['resist_frost_bonus'],$data['resist_frost_debuff']) = explode(':',$p['Resists']['Frost']);

		list($data['resist_arcane_base'],$data['resist_arcane_current'],
			$data['resist_arcane_bonus'],$data['resist_arcane_debuff']) = explode(':',$p['Resists']['Arcane']);

		list($data['resist_fire_base'],$data['resist_fire_current'],
			$data['resist_fire_bonus'],$data['resist_fire_debuff']) = explode(':',$p['Resists']['Fire']);

		list($data['resist_shadow_base'],$data['resist_shadow_current'],
			$data['resist_shadow_bonus'],$data['resist_shadow_debuff']) = explode(':',$p['Resists']['Shadow']);

		list($data['resist_nature_base'],$data['resist_nature_current'],
			$data['resist_nature_bonus'],$data['resist_nature_debuff']) = explode(':',$p['Resists']['Nature']);

		list($data['resist_holy_base'],$data['resist_holy_current'],
			$data['resist_holy_bonus'],$data['resist_holy_debuff']) = explode(':',$p['Resists']['Holy']);


		// Parse exp
		list($data['xp_current'], $data['xp_total'], $data['xp_rested']) = explode(':',$p['XP'],3);

		// Parse date fields
		$data['date_utc'] = substr($p['DateUTC'],6,2).'/'.substr($p['DateUTC'],0,5).substr($p['DateUTC'],8);
		$data['date'] = substr($p['Date'],6,2).'/'.substr($p['Date'],0,5).substr($p['Date'],8);

		// Parse Time Played
		$data['time_played'] = $p['TimePlayed']<0?0:$p['TimePlayed'];
		$data['time_level_played'] = $p['TimeLevelPlayed']<0?0:$p['TimeLevelPlayed'];


		// Parse Ranged Attack
		if(isset($p['Ranged Attack'])){
			if(isset($p['Ranged Attack']['AttackPower'])){
				$data['ranged_power']=$p['Ranged Attack']['AttackPower'];
			}
			if(isset($p['Ranged Attack']['AttackSpeed'])){
				$data['ranged_speed']=$p['Ranged Attack']['AttackSpeed'];
			}
			if(isset($p['Ranged Attack']['AttackDPS'])){
				$data['ranged_dps']=$p['Ranged Attack']['AttackDPS'];
			}
			if(isset($p['Ranged Attack']['AttackRating'])){
				$data['ranged_rating']=$p['Ranged Attack']['AttackRating'];
			}
			if(isset($p['Ranged Attack']['DamageRange'])){
				list($data['ranged_damage_min'],$data['ranged_damage_max'])=explode(':',$p['Ranged Attack']['DamageRange']);
			}
			if(isset($p['Ranged Attack']['DamageRangeTooltip'])){
				$data['ranged_damage_range_tooltip']=prep_tooltip_for_db($p['Ranged Attack']['DamageRangeTooltip']);
			}
			if(isset($p['Ranged Attack']['AttackPowerDPS'])){
				$data['ranged_power_dps']=$p['Ranged Attack']['AttackPowerDPS'];
			}
			if(isset($p['Ranged Attack']['AttackPowerTooltip'])){
				$data['ranged_power_tooltip']=prep_tooltip_for_db($p['Ranged Attack']['AttackPowerTooltip']);
			}
		}

		// Parse Melee Attack
		if(isset($p['Melee Attack'])){
			if(isset($p['Melee Attack']['AttackPower'])){
				$data['melee_power']=$p['Melee Attack']['AttackPower'];
			}
			if(isset($p['Melee Attack']['AttackSpeed'])){
				$data['melee_speed']=$p['Melee Attack']['AttackSpeed'];
			}
			if(isset($p['Melee Attack']['AttackDPS'])){
				$data['melee_dps']=$p['Melee Attack']['AttackDPS'];
			}
			if(isset($p['Melee Attack']['AttackRating'])){
				$data['melee_rating']=$p['Melee Attack']['AttackRating'];
			}
			if(isset($p['Melee Attack']['DamageRange'])){
				list($data['melee_damage_min'],$data['melee_damage_max'])=explode(':',$p['Melee Attack']['DamageRange']);
			}
			if(isset($p['Melee Attack']['DamageRangeTooltip'])){
				$data['melee_damage_range_tooltip']=prep_tooltip_for_db($p['Melee Attack']['DamageRangeTooltip']);
			}
			if(isset($p['Melee Attack']['AttackPowerDPS'])){
				$data['melee_power_dps']=$p['Melee Attack']['AttackPowerDPS'];
			}
			if(isset($p['Melee Attack']['AttackPowerTooltip'])){
				$data['melee_power_tooltip']=prep_tooltip_for_db($p['Melee Attack']['AttackPowerTooltip']);
			}
		}

		db_query("DELETE FROM `".ROSTER_PLAYERSTABLE."` WHERE member_id = ".$this->Id);
		db_query_insert_array(ROSTER_PLAYERSTABLE, array_keys($data), $data);
	}

	/* private */ function UpdateRecipes(){
		if(!isset($this->profile['Professions']))
		return(false);

		$recipe_handler =& GetHandler("recipes");
		db_query("DELETE FROM `".ROSTER_RECIPESTABLE."` WHERE member_id = ".$this->Id);
		$recipes = $this->profile['Professions'];
		foreach($recipes as $profession_name=>$profession_detail){
			foreach($profession_detail as $group_name=>$group_detail){
				foreach($group_detail as $recipe_name=>$recipe_detail){
					$recipe_id = $recipe_handler->GetRecipeId($profession_name, $group_name, $recipe_name);
					if(!$recipe_id){
						$LevelRequired = strpos($recipe_detail['Tooltip'], 'Requires Level');
						if ($LevelRequired!=0) {
							$LevelRequired=(rtrim(substr($recipe_detail['Tooltip'], $LevelRequired + 15,2)));
						}
						$recipe_id = $recipe_handler->CreateRecipe($profession_name, $group_name, $recipe_name, $recipe_detail['Difficulty'], $recipe_detail['Reagents'], $recipe_detail['Texture'], $recipe_detail['Tooltip'], "", $LevelRequired);
					}
					$data = array('member_id'=>$this->Id, 'recipe_id'=>$recipe_id);
					db_query_insert_array(ROSTER_RECIPESTABLE, array_keys($data), $data);
				}
			}
		}
	}

	/* private */ function UpdateReputation(){
		if(!isset($this->profile['Reputation']))
		return(false);

		$rep_handler =& GetHandler("reputation");

		db_query("DELETE FROM `".ROSTER_REPUTATIONTABLE."` WHERE member_id = ".$this->Id);

		$rep = $this->profile['Reputation'];
		unset($rep['Count']);
		foreach($rep as $group_name=>$group_data){
			foreach($group_data as $subgroup_name=>$subgroup_detail){
				// Get the rep ID from the database. if we fail, let's put this rep into the database.
				$rep_id = $rep_handler->GetReputationId($group_name, $subgroup_name);
				if($rep_id===false){
					$rep_handler->CreateReputation($group_name, $subgroup_name);
					// Now, get the rep ID, since it has one.
					$rep_id = $rep_handler->GetReputationId($group_name, $subgroup_name);
				}
				list($rep_current, $rep_max) = explode("/", $subgroup_detail['Value']);
				$keys = array("member_id", "reputation_id", "reputation_current", "reputation_maximum", "reputation_war_status", "reputation_standing");
				$values = array($this->Id, $rep_id, $rep_current, $rep_max, $subgroup_detail['AtWar'], $subgroup_detail['Standing']);
				db_query_insert_array(ROSTER_REPUTATIONTABLE, $keys, $values);
			}
		}
	}

	/* private */ function UpdateSkills(){
		if(!isset($this->profile['Skills']))
		return(false);

		db_query("DELETE FROM `".ROSTER_SKILLSTABLE."` WHERE member_id = ".$this->Id);

		$skills = $this->profile['Skills'];
		foreach($skills as $skill_group_name=>$group_data){
			$skill_group_order = $group_data['Order'];
			unset($group_data['Order']);
			foreach($group_data as $skill_name=>$skill_detail){
				list($skill_current, $skill_maximum) = explode(":", $skill_detail);
				$keys = array("member_id", "skill_group_name", "skill_group_order", "skill_name", "skill_level_current", "skill_level_maximum");
				$values = array($this->Id, $skill_group_name, $skill_group_order, $skill_name, $skill_current, $skill_maximum);
				db_query_insert_array(ROSTER_SKILLSTABLE, $keys, $values);
			}
		}
	}

	/* private */ function UpdateSpells(){
		if(!isset($this->profile['SpellBook']))
		return(false);

		$spell_handler =& GetHandler("spells");
		db_query("DELETE FROM `".ROSTER_SPELLTABLE."` WHERE member_id = ".$this->Id);
		db_query("DELETE FROM `".ROSTER_SPELLTREETABLE."` WHERE member_id = ".$this->Id);

	    $spellbook = $this->profile['SpellBook'];
		foreach($spellbook as $spell_type=>$spell_type_detail){
		    $spell_id = $spell_handler->getSpellId($spell_type, "", "");
		    if(!$spell_id){
			$spell_id = $spell_handler->CreateSpell($spell_type, "", "", $spell_type_detail['Texture'], "");
		    }
		    db_query_insert_array(ROSTER_SPELLTREETABLE, array("member_id", "spellinfo_id"), array($this->Id, $spell_id));
			foreach($spell_type_detail['Spells'] as $spell_name=>$spell_detail){
//			    foreach($spell_detail as $=>$recipe_detail){
					$spell_id = $spell_handler->GetSpellId($spell_type, $spell_name, $spell_detail['Rank']);
					if(!$spell_id){
					    if(!isset($spell_detail['Tooltip'])){
						$spell_detail['Tooltip'] = "Tooltip Not Available.";
					    }
						$spell_id = $spell_handler->CreateSpell($spell_type, $spell_name, $spell_detail['Rank'], $spell_detail['Texture'], $spell_detail['Tooltip']);
					}
					$data = array('member_id'=>$this->Id, 'spellinfo_id'=>$spell_id);
					db_query_insert_array(ROSTER_SPELLTABLE, array_keys($data), $data);
//				}
			}
		}
	}

	/* private */ function UpdateTalents(){
		if(!isset($this->profile['Talents']))
		return(false);

		db_query("DELETE FROM `".ROSTER_TALENTSTABLE."` WHERE member_id = ".$this->Id);
		db_query("DELETE FROM `".ROSTER_TALENTTREETABLE."` WHERE member_id = ".$this->Id);

		$talents = $this->profile['Talents'];
		foreach($talents as $school=>$talent_data){
			foreach($talent_data as $talent_name=>$talent_detail){
				// Skip over these, as they are handled when we run across Order
				if($talent_name == "PointsSpent" || $talent_name == "Background"){
					continue;
				}
				// Process "Order" data as order information, and not a talent.
				if($talent_name == "Order"){
					$order = $talent_detail;
					$keys = array("member_id", "tree", "background", "pointsspent", "order");
					$values = array($this->Id, $school, $talent_data['Background'], $talent_data['PointsSpent'], $order);
					db_query_insert_array(ROSTER_TALENTTREETABLE, $keys, $values);
					continue;
				}
				list($rank, $maxrank) = explode(":", $talent_detail['Rank']);
				// Get the talent ID from the database. if we fail, let's put this talent into the database.
			    @$talent_handler =& GetHandler('talents');
				$talent_id = $talent_handler->GetTalentId($school, $talent_name, $rank);
				if($talent_id===false){
					list($row, $column) = explode(":", $talent_detail['Location']);
					$talent_id = $talent_handler->CreateTalent($school, $talent_name, $rank, $maxrank, $row, $column, prep_tooltip_for_db($talent_detail['Tooltip']), $talent_detail['Texture']);
				}
				$keys = array("member_id", "talent_id");
				$values = array($this->Id, $talent_id);
				db_query_insert_array(ROSTER_TALENTSTABLE, $keys, $values);
			}
		}
	}
}

class Guild {
	function GetGuildId($guild_name, $server_name){
		static $cached_guilds;
		if(isset($cached_guilds[$guild_name][$server_name])){
			return($cached_guilds[$guild_name][$server_name]);
		}

		$sql = "SELECT guild_id FROM ".ROSTER_GUILDTABLE." WHERE guild_name='".escape($guild_name)."' AND realm='".escape($server_name)."'";
		$res = db_query($sql);
		if($res->numRows()==0){
			return(false);
		}
		$res->fetchInto($r);
		$cached_guilds[$guild_name][$server_name] = $r['guild_id'];
		return($r['guild_id']);
	}
}

class RecipeHandler {
	var $recipeId;

	function RecipeHandler(){
		$this->LoadRecipeIds();
	}

	/**
     * Retrieve recipe details, based on recipe ID
     *
     * @author six

     * @param int recipeId
     * @return array recipeDetails
     */
	function GetRecipeDetails($recipeId){
		$res = db_query("SELECT * FROM `".ROSTER_LUT_RECIPES."` WHERE recipe_id = ".intval($recipeId));
		if($res->numRows()==1){
			$res->fetchInto($r);
			return($r);
		}
		return(false);
	}

	function GetRecipeId($group, $subgroup, $name){
		if(isset($this->recipeId[$group][$subgroup][$name])){
			return($this->recipeId[$group][$subgroup][$name]);
		} else {
			return(false);
		}
	}

	/* private */ function LoadRecipeIds(){
		$sql = "SELECT * FROM `".ROSTER_LUT_RECIPES."`";
		$res = db_query($sql);
		if($res->numRows()!=0){
			while($res->fetchInto($r)){
				$this->recipeId[$r['recipe_profession']][$r['recipe_group']][$r['recipe_name']]=$r['recipe_id'];
			}
		}
	}

	function CreateRecipe($group, $subgroup, $name, $difficulty, $reagents, $texture, $tooltip, $categories, $level){
		$keys = array("recipe_profession", "recipe_group", "recipe_name", "recipe_difficulty", "recipe_reagents", "recipe_texture", "recipe_tooltip", "recipe_categories", "recipe_level");
		$values = array($group, $subgroup, $name, $difficulty, prep_tooltip_for_db($reagents), $texture, prep_tooltip_for_db($tooltip), $categories, $level);
		db_query_insert_array(ROSTER_LUT_RECIPES, $keys, $values);
		$res = db_query("SELECT LAST_INSERT_ID() as id");
		$res->fetchInto($r);
		// Add it to the cache
		$this->recipeId[$group][$subgroup][$name] = $r['id'];
		return($r['id']);
	}
}

class ReputationHandler {
	var $repId;

	function ReputationHandler(){
		$this->LoadReputationIds();
	}

	function GetReputationId($group, $subgroup){
		if(isset($this->repId[$group][$subgroup])){
			return($this->repId[$group][$subgroup]);
		} else {
			return(false);
		}
	}

	/* private */ function LoadReputationIds(){
		$sql = "SELECT * FROM `".ROSTER_LUT_REPUTATION."`";
		$res = db_query($sql);
		if($res->numRows()!=0){
			while($res->fetchInto($r)){
				$this->repId[$r['reputation_group']][$r['reputation_subgroup']]=$r['reputation_id'];
			}
		}
	}

	function CreateReputation($group, $subgroup){
		$keys = array("reputation_group", "reputation_subgroup");
		$values = array($group, $subgroup);
		db_query_insert_array(ROSTER_LUT_REPUTATION, $keys, $values);
		$res = db_query("SELECT LAST_INSERT_ID() as id");
		$res->fetchInto($r);
		// Add it to the cache
		$this->repId[$group][$subgroup] = $r['id'];
		return($r['id']);
	}
}

class SpellHandler {
	var $spellId;

	function SpellHandler(){
		$this->LoadSpellIds();
	}

	/**
     * Retrieve spell details, based on spell ID
     *
     * @author six

     * @param int spellId
     * @return array spellDetails
     */
	function GetSpellDetails($spellId){
		$res = db_query("SELECT * FROM `".ROSTER_LUT_SPELLINFO."` WHERE spellinfo_id = ".intval($spellId));
		if($res->numRows()==1){
			$res->fetchInto($r);
			return($r);
		}
		return(false);
	}

	function GetSpellId($spell_type, $spell_name, $spell_rank){
		if(isset($this->spellId[$spell_type][$spell_name][$spell_rank])){
			return($this->spellId[$spell_type][$spell_name][$spell_rank]);
		} else {
			return(false);
		}
	}

	/* private */ function LoadSpellIds(){
		$sql = "SELECT * FROM `".ROSTER_LUT_SPELLINFO."`";
		$res = db_query($sql);
		if($res->numRows()!=0){
			while($res->fetchInto($r)){
				$this->spellId[$r['spellinfo_type']][$r['spellinfo_name']][$r['spellinfo_rank']]=$r['spellinfo_id'];
			}
		}
	}

	function CreateSpell($spell_type, $spell_name, $spell_rank, $spell_icon, $spell_tooltip){
		$keys = array("spellinfo_type", "spellinfo_name", "spellinfo_rank", "spellinfo_icon", "spellinfo_tooltip");
		$values = array($spell_type, $spell_name, $spell_rank, $spell_icon, prep_tooltip_for_db($spell_tooltip));
		db_query_insert_array(ROSTER_LUT_SPELLINFO, $keys, $values);
		$res = db_query("SELECT LAST_INSERT_ID() as id");
		$res->fetchInto($r);
		// Add it to the cache
		$this->spellId[$spell_type][$spell_name][$spell_rank] = $r['id'];
		return($r['id']);
	}
}

/**
 * Handle lookups and sets of talent information related to the lookup tables
 *
 */
class TalentHandler {
	var $talentId;

	function TalentHandler(){
		$this->LoadTalentIds();
	}

	function getTalentId($talent_tree, $talent_name, $talent_rank){
		if(isset($this->talentId[$talent_tree][$talent_name][$talent_rank])){
			return($this->talentId[$talent_tree][$talent_name][$talent_rank]);
		} else {
			return(false);
		}
	}

	/* private */ function LoadTalentIds(){
		$sql = "SELECT * FROM `".ROSTER_LUT_TALENTS."`";
		$res = db_query($sql);
		if($res->numRows()!=0){
			while($res->fetchInto($r)){
				$this->talentId[$r['talent_tree']][$r['talent_name']][$r['talent_rank']]=$r['talent_id'];
			}
		}
	}

	/**
	 * Create a talent in the lookup table
	 *
	 * @param string $talent_tree
	 * @param string $talent_name
	 * @param int $talent_rank
	 * @param int $talent_max_rank
	 * @param int $row
	 * @param int $column
	 * @param string $tooltip
	 * @param string $texture
	 * @return integer talent_id
	 */
	function CreateTalent($talent_tree, $talent_name, $talent_rank, $talent_max_rank, $row, $column, $tooltip, $texture){
		$keys = array("talent_name", "talent_tree", "talent_rank", "talent_maximum_rank", "talent_row", "talent_column", "talent_tooltip", "talent_texture");
		$values = array($talent_name, $talent_tree, $talent_rank, $talent_max_rank, $row, $column, prep_tooltip_for_db($tooltip), $texture);
		db_query_insert_array(ROSTER_LUT_TALENTS, $keys, $values);
		$res = db_query("SELECT LAST_INSERT_ID() as id");
		$res->fetchInto($r);
		// Add it to the cache
		$this->talentId[$talent_tree][$talent_name][$talent_rank] = $r['id'];
		return($r['id']);
	}
}
?>
