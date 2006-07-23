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

// ----[ Prevent Direct Access to this file ]-------------------
if( !defined('ROSTER_INCLUDED') )
{
	exit("You can't access this file directly!");
}


function getRepBarValues( $repdata )
{
	global $data;

	static $repnum = 0;

	// Check locale
	if( empty($data['locale']) )
	{
		$locale = GetConfigValue('lang');
	}
	else
	{
		$locale = $data['locale'];
	}

	$img = array(getLocaleValue('charpage_exalted',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_green.gif',
				 getLocaleValue('charpage_revered',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_green.gif',
				 getLocaleValue('charpage_honored',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_green.gif',
				 getLocaleValue('charpage_friendly',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_green.gif',
				 getLocaleValue('charpage_neutral',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_yellow.gif',
				 getLocaleValue('charpage_unfriendly',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_orange.gif',
				 getLocaleValue('charpage_hostile',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_red.gif',
				 getLocaleValue('charpage_hated',$locale) =>
					GetConfigValue('imagepath').'/char_page/bar_red.gif');

    $min = $repdata['reputation_current'];
    $max = $repdata['reputation_maximum'];

	$returnData['name'] = $repdata['reputation_subgroup'];
	$returnData['barwidth'] = ceil($min / $max * 133);
	$returnData['image'] = $img[$repdata['reputation_standing']];
	$returnData['barid'] = $repnum;
	$returnData['standing'] = $repdata['reputation_standing'];
	$returnData['value'] = $min;
	$returnData['maxvalue'] = $max;
	$returnData['atwar'] = $repdata['reputation_war_status'];

	$repnum++;

	return $returnData;
}


function getRepTabValues( $member_id )
{
	global $tpl;

	$sqlquery = "SELECT * FROM `".ROSTER_REPUTATIONTABLE."` AS main LEFT JOIN `".ROSTER_LUT_REPUTATION."` AS lut ON main.reputation_id = lut.reputation_id WHERE `member_id` = '$member_id' ORDER BY `reputation_group` ASC, `reputation_subgroup` ASC";
	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	$i=0;
	$j=0;
	if ( $result->numRows() > 0 )
	{
		$result->fetchInto($data2);
		$repInfo[$i]['name'] = $data2['reputation_group'];
		$repInfo[$i]['id'] = $i;

		for( $r=0; $r < $result->numRows(); $r++ )
		{
			if( $repInfo[$i]['name'] != $data2['reputation_group'] )
			{
				$i++;
				$j=0;
				$repInfo[$i]['name'] = $data2['reputation_group'];
				$repInfo[$i]['id'] = $i;
			}
			$barData[$i][$j] = getRepBarValues($data2);
			$j++;
			$result->fetchInto($data2);
		}
		$tpl->assign('repinfo',$repInfo);
		$tpl->assign('repbarinfo',$barData);
	}
}


function getSkillBarValues( &$skilldata )
{
	$skilldata['Max'] = $skilldata['skill_level_maximum'];
	$skilldata['Min'] = $skilldata['skill_level_current'];

	$returnData['max'] = $skilldata['Max'];
	$returnData['min'] = $skilldata['Min'];
	$returnData['name'] = $skilldata['skill_name'];
	$returnData['image_width'] = ceil($skilldata['Min']/$skilldata['Max']*273);

	return $returnData;
}


function getSkillTabValues( $member_id )
{
	global $tpl;

	$sqlquery = "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` = '$member_id' ORDER BY `skill_group_order` ASC";
	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	$i=0;
	$j=0;
	if ( $result->numRows() > 0 )
	{
		$result->fetchInto($data2);
		$skillInfo[$i]['name'] = $data2['skill_group_name'];
		$skillInfo[$i]['id'] = $i;

		for( $r=0; $r < $result->numRows(); $r++ )
		{
			if( $skillInfo[$i]['name'] != $data2['skill_group_name'] )
			{
				$i++;
				$j=0;
				$skillInfo[$i]['name'] = $data2['skill_group_name'];
				$skillInfo[$i]['id'] = $i;
			}
			$barData[$i][$j] = getSkillBarValues($data2);
			$j++;
			$result->fetchInto($data2);
		}
		$tpl->assign('skillinfo',$skillInfo);
		$tpl->assign('skillbarinfo',$barData);
	}
}


function getPetTabValues( $data )
{
	global $tpl;

	$sqlquery = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '".$data['member_id']."' ORDER BY `level` DESC";
	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	if( $result->numRows() == 0 )
	{
		return false;
	}

	$id=0;
	while( $result->fetchInto($row) )
	{
		// Get exp values
		if( $row['xp_total'] == 0 )
		{
			$xp_percent = .00;
		}
		else
		{
			$xp_percent = $row['xp_current'] / $row['xp_total'];
		}

		$row['exp_barwidth'] = floor(216 * $xp_percent);
		$row['exp_percent'] = floor($xp_percent * 100);

		$row['unused_tp'] = $row['total_training_points'] - $row['used_training_points'];


		// Fix Resist tooltips
		if( $row['resist_arcane_current'] == $row['resist_arcane_base'] )
		{
			$row['resist_arcane_tooltip'] = $row['resist_arcane_current'];
		}
		else
		{
			$row['resist_arcane_tooltip'] = $row['resist_arcane_current'].' ('.$row['resist_arcane_base'];
			if( $row['resist_arcane_bonus'] > 0 )
			{
				$row['resist_arcane_tooltip'] .= '<span class="myGreen">+'.$row['resist_arcane_bonus'].'</span>';
			}
			if( $row['resist_arcane_debuff'] < 0 )
			{
				$row['resist_arcane_tooltip'] .= '<span class="myRed">'.$row['resist_arcane_debuff'].'</span>';
			}
			$row['resist_arcane_tooltip'] .= ')';
		}

		if( $row['resist_fire_current'] == $row['resist_fire_base'] )
		{
			$row['resist_fire_tooltip'] = $row['resist_fire_current'];
		}
		else
		{
			$row['resist_fire_tooltip'] = $row['resist_fire_current'].' ('.$row['resist_fire_base'];
			if( $row['resist_fire_bonus'] > 0 )
			{
				$row['resist_fire_tooltip'] .= '<span class="myGreen">+'.$row['resist_fire_bonus'].'</span>';
			}
			if( $row['resist_fire_debuff'] < 0 )
			{
				$row['resist_fire_tooltip'] .= '<span class="myRed">'.$row['resist_fire_debuff'].'</span>';
			}
			$row['resist_fire_tooltip'] .= ')';
		}

		if( $row['resist_frost_current'] == $row['resist_frost_base'] )
		{
			$row['resist_frost_tooltip'] = $row['resist_frost_current'];
		}
		else
		{
			$row['resist_frost_tooltip'] = $row['resist_frost_current'].' ('.$row['resist_frost_base'];
			if( $row['resist_frost_bonus'] > 0 )
			{
				$row['resist_frost_tooltip'] .= '<span class="myGreen">+'.$row['resist_frost_bonus'].'</span>';
			}
			if( $row['resist_frost_debuff'] < 0 )
			{
				$row['resist_frost_tooltip'] .= '<span class="myRed">'.$row['resist_frost_debuff'].'</span>';
			}
			$row['resist_frost_tooltip'] .= ')';
		}

		if( $row['resist_holy_current'] == $row['resist_holy_base'] )
		{
			$row['resist_holy_tooltip'] = $row['resist_holy_current'];
		}
		else
		{
			$row['resist_holy_tooltip'] = $row['resist_holy_current'].' ('.$row['resist_holy_base'];
			if( $row['resist_holy_bonus'] > 0 )
			{
				$row['resist_holy_tooltip'] .= '<span class="myGreen">+'.$row['resist_holy_bonus'].'</span>';
			}
			if( $row['resist_holy_debuff'] < 0 )
			{
				$row['resist_holy_tooltip'] .= '<span class="myRed">'.$row['resist_holy_debuff'].'</span>';
			}
			$row['resist_holy_tooltip'] .= ')';
		}

		if( $row['resist_nature_current'] == $row['resist_nature_base'] )
		{
			$row['resist_nature_tooltip'] = $row['resist_nature_current'];
		}
		else
		{
			$row['resist_nature_tooltip'] = $row['resist_nature_current'].' ('.$row['resist_nature_base'];
			if( $row['resist_nature_bonus'] > 0 )
			{
				$row['resist_nature_tooltip'] .= '<span class="myGreen">+'.$row['resist_nature_bonus'].'</span>';
			}
			if( $row['resist_nature_debuff'] < 0 )
			{
				$row['resist_nature_tooltip'] .= '<span class="myRed">'.$row['resist_nature_debuff'].'</span>';
			}
			$row['resist_nature_tooltip'] .= ')';
		}

		if( $row['resist_shadow_current'] == $row['resist_shadow_base'] )
		{
			$row['resist_shadow_tooltip'] = $row['resist_shadow_current'];
		}
		else
		{
			$row['resist_shadow_tooltip'] = $row['resist_shadow_current'].' ('.$row['resist_shadow_base'];
			if( $row['resist_shadow_bonus'] > 0 )
			{
				$row['resist_shadow_tooltip'] .= '<span class="myGreen">+'.$row['resist_shadow_bonus'].'</span>';
			}
			if( $row['resist_shadow_debuff'] < 0 )
			{
				$row['resist_shadow_tooltip'] .= '<span class="myRed">'.$row['resist_shadow_debuff'].'</span>';
			}
			$row['resist_shadow_tooltip'] .= ')';
		}


		// Fix Stats tooltips
		if( $row['stats_strength_current'] == $row['stats_strength_base'] )
		{
			$row['stats_strength_tooltip'] = $row['stats_strength_current'];
		}
		else
		{
			$row['stats_strength_tooltip'] = $row['stats_strength_current'].' ('.$row['stats_strength_base'];
			if( $row['stats_strength_bonus'] > 0 )
			{
				$row['stats_strength_tooltip'] .= '<span class="myGreen">+'.$row['stats_strength_bonus'].'</span>';
			}
			if( $row['stats_strength_debuff'] < 0 )
			{
				$row['stats_strength_tooltip'] .= '<span class="myRed">'.$row['stats_strength_debuff'].'</span>';
			}
			$row['stats_strength_tooltip'] .= ')';
		}

		if( $row['stats_agility_current'] == $row['stats_agility_base'] )
		{
			$row['stats_agility_tooltip'] = $row['stats_agility_current'];
		}
		else
		{
			$row['stats_agility_tooltip'] = $row['stats_agility_current'].' ('.$row['stats_agility_base'];
			if( $row['stats_agility_bonus'] > 0 )
			{
				$row['stats_agility_tooltip'] .= '<span class="myGreen">+'.$row['stats_agility_bonus'].'</span>';
			}
			if( $row['stats_agility_debuff'] < 0 )
			{
				$row['stats_agility_tooltip'] .= '<span class="myRed">'.$row['stats_agility_debuff'].'</span>';
			}
			$row['stats_agility_tooltip'] .= ')';
		}

		if( $row['stats_stamina_current'] == $row['stats_stamina_base'] )
		{
			$row['stats_stamina_tooltip'] = $row['stats_stamina_current'];
		}
		else
		{
			$row['stats_stamina_tooltip'] = $row['stats_stamina_current'].' ('.$row['stats_stamina_base'];
			if( $row['stats_stamina_bonus'] > 0 )
			{
				$row['stats_stamina_tooltip'] .= '<span class="myGreen">+'.$row['stats_stamina_bonus'].'</span>';
			}
			if( $row['stats_stamina_debuff'] < 0 )
			{
				$row['stats_stamina_tooltip'] .= '<span class="myRed">'.$row['stats_stamina_debuff'].'</span>';
			}
			$row['stats_stamina_tooltip'] .= ')';
		}

		if( $row['stats_intellect_current'] == $row['stats_intellect_base'] )
		{
			$row['stats_intellect_tooltip'] = $row['stats_intellect_current'];
		}
		else
		{
			$row['stats_intellect_tooltip'] = $row['stats_intellect_current'].' ('.$row['stats_intellect_base'];
			if( $row['stats_intellect_bonus'] > 0 )
			{
				$row['stats_intellect_tooltip'] .= '<span class="myGreen">+'.$row['stats_intellect_bonus'].'</span>';
			}
			if( $row['stats_intellect_debuff'] < 0 )
			{
				$row['stats_intellect_tooltip'] .= '<span class="myRed">'.$row['stats_intellect_debuff'].'</span>';
			}
			$row['stats_intellect_tooltip'] .= ')';
		}

		if( $row['stats_spirit_current'] == $row['stats_spirit_base'] )
		{
			$row['stats_spirit_tooltip'] = $row['stats_spirit_current'];
		}
		else
		{
			$row['stats_spirit_tooltip'] = $row['stats_spirit_current'].' ('.$row['stats_spirit_base'];
			if( $row['stats_spirit_bonus'] > 0 )
			{
				$row['stats_spirit_tooltip'] .= '<span class="myGreen">+'.$row['stats_spirit_bonus'].'</span>';
			}
			if( $row['stats_spirit_debuff'] < 0 )
			{
				$row['stats_spirit_tooltip'] .= '<span class="myRed">'.$row['stats_spirit_debuff'].'</span>';
			}
			$row['stats_spirit_tooltip'] .= ')';
		}

		if( $row['stats_armor_current'] == $row['stats_armor_base'] )
		{
			$row['stats_armor_tooltip'] = $row['stats_armor_current'];
		}
		else
		{
			$row['stats_armor_tooltip'] = $row['stats_armor_current'].' ('.$row['stats_armor_base'];
			if( $row['stats_armor_bonus'] > 0 )
			{
				$row['stats_armor_tooltip'] .= '<span class="myGreen">+'.$row['stats_armor_bonus'].'</span>';
			}
			if( $row['stats_armor_debuff'] < 0 )
			{
				$row['stats_armor_tooltip'] .= '<span class="myRed">'.$row['stats_armor_debuff'].'</span>';
			}
			$row['stats_armor_tooltip'] .= ')';
		}

		if( $row['stats_defense_current'] == $row['stats_defense_base'] )
		{
			$row['stats_defense_tooltip'] = $row['stats_defense_current'];
		}
		else
		{
			$row['stats_defense_tooltip'] = $row['stats_defense_current'].' ('.$row['stats_defense_base'];
			if( $row['stats_defense_bonus'] > 0 )
			{
				$row['stats_defense_tooltip'] .= '<span class="myGreen">+'.$row['stats_defense_bonus'].'</span>';
			}
			if( $row['stats_defense_debuff'] < 0 )
			{
				$row['stats_defense_tooltip'] .= '<span class="myRed">'.$row['stats_defense_debuff'].'</span>';
			}
			$row['stats_defense_tooltip'] .= ')';
		}


		$row['melee_power_tooltip'] = str_replace("\n",'<br />',$row['melee_power_tooltip']);

		if( !empty($row['melee_damage_range_tooltip']) )
		{
			$row['melee_damage_range_tooltip'] = str_replace("\n",'</td></tr><tr><td class="myYellow_nobold" style="font-size:10px;">',$row['melee_damage_range_tooltip']);
			$row['melee_damage_range_tooltip'] = str_replace(":\t",':</td><td align="right" style="color:#FFFFFF;font-size:10px;">',$row['melee_damage_range_tooltip']);
			$row['melee_damage_range_tooltip'] = '<table style="width:200px;" cellspacing="0" cellpadding="0"><tr><td colspan="2" style="color:#FFFFFF;font-size:10px;font-weight:bold;">'.$row['melee_damage_range_tooltip'].'</table>';
		}
		else
		{
			$row['melee_damage_range_tooltip'] = '<span class="myWhite">'.getLocaleValue('charpage_melee_att',$data['locale']).' '.getLocaleValue('charpage_damage',$data['locale']).': N/A</span>';
		}

		// Stat tooltips
		setTooltip('pet_'.$id.'_strength_tooltip','<span class="myWhite">'.getLocaleValue('charpage_strength',$data['locale']).': '.$row['stats_strength_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_strength_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_agility_tooltip','<span class="myWhite">'.getLocaleValue('charpage_agility',$data['locale']).': '.$row['stats_agility_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_agility_tooltip',$data['locale']).'</span>');

		setTooltip('pet_'.$id.'_stamina_tooltip','<span class="myWhite">'.getLocaleValue('charpage_stamina',$data['locale']).': '.$row['stats_stamina_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_stamina_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_intellect_tooltip','<span class="myWhite">'.getLocaleValue('charpage_intellect',$data['locale']).': '.$row['stats_intellect_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_intellect_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_spirit_tooltip','<span class="myWhite">'.getLocaleValue('charpage_spirit',$data['locale']).': '.$row['stats_spirit_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_spirit_tooltip',$data['locale']).'</span>');


		// Attack tooltips
		setTooltip('pet_'.$id.'_melee_rating_tooltip','<span class="myWhite">'.getLocaleValue('charpage_melee_rating',$data['locale']).': '.$row['melee_rating'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_melee_rating_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_melee_power_tooltip','<span class="myWhite">'.getLocaleValue('charpage_melee_att_power',$data['locale']).': '.$row['melee_power'].'</span><br /><span class="myYellow_nobold">'.$row['melee_power_tooltip'].'</span>');
		setTooltip('pet_'.$id.'_melee_damage_range_tooltip',$row['melee_damage_range_tooltip']);
		setTooltip('pet_'.$id.'_melee_defense_tooltip','<span class="myWhite">'.getLocaleValue('charpage_defense',$data['locale']).': '.$row['stats_defense_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_defense_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_armor_tooltip','<span class="myWhite">'.getLocaleValue('charpage_armor',$data['locale']).': '.$row['stats_armor_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_armor_tooltip_less',$data['locale']).'</span>');


		// Resistance tooltips
		setTooltip('pet_'.$id.'_resist_fire','<span class="myRed">'.getLocaleValue('charpage_res_fire',$data['locale']).'</span> <span class="myWhite">'.$row['resist_fire_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_fire_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_resist_nature','<span class="myGreen">'.getLocaleValue('charpage_res_nature',$data['locale']).'</span> <span class="myWhite">'.$row['resist_nature_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_nature_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_resist_arcane','<span class="myYellow">'.getLocaleValue('charpage_res_arcane',$data['locale']).'</span> <span class="myWhite">'.$row['resist_arcane_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_arcane_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_resist_frost','<span class="myBlue">'.getLocaleValue('charpage_res_frost',$data['locale']).'</span> <span class="myWhite">'.$row['resist_frost_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_frost_tooltip',$data['locale']).'</span>');
		setTooltip('pet_'.$id.'_resist_shadow','<span class="myPurple">'.getLocaleValue('charpage_res_shadow',$data['locale']).'</span> <span class="myWhite">'.$row['resist_shadow_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_shadow_tooltip',$data['locale']).'</span>');



		// Start Warlock Pet Icon Fix

		if( $row['type'] == getLocaleValue('Imp',$data['locale']) )
		{
			$row['icon'] = 'Interface/Icons/Spell_Shadow_SummonImp';
		}
		if( $row['type'] == getLocaleValue('Voidwalker',$data['locale']) )
		{
			$row['icon'] = 'Interface/Icons/Spell_Shadow_SummonVoidWalker';
		}
		if( $row['type'] == getLocaleValue('Succubus',$data['locale']) )
		{
			$row['icon'] = 'Interface/Icons/Spell_Shadow_SummonSuccubus';
		}
		if( $row['type'] == getLocaleValue('Felhunter',$data['locale']) )
		{
			$row['icon'] = 'Interface/Icons/Spell_Shadow_SummonFelHunter';
		}
		if( $row['type'] == getLocaleValue('Infernal',$data['locale']) )
		{
			$row['icon'] = 'Interface/Icons/Spell_Shadow_SummonInfernal';
		}
		// End Warlock Pet Icon Fix


		if( $row['icon'] == '' || !isset($row['icon']) )
		{
			$row['icon'] = 'unknownIcon.gif';
		}
		else
		{
			$row['icon'] = str_replace('\\','/',$row['icon']).'.'.GetConfigValue('icon_ext');
		}
		$petsArray[] = $row;
		$id++;
	}
	if( is_array($petsArray) )
	{
		$tpl->assign( 'pets',$petsArray );
		return true;
	}
	else
	{
		return false;
	}
}


function getCharTabValues( &$data )
{
	global $tpl;

	// Get equipment
	$sqlquery = "SELECT * FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` = '".$data['member_id']."' AND `item_parent` = 'equip'";
	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	if( $result->numRows() > 0 )
	{
		for ($j=0; $j < $result->numRows(); $j++)
		{
			$result->fetchInto($data2);

			formatTooltip( $data2,'equip_'.$data2['item_slot'],$data['locale'] );

			$equip[$j]['slot_id'] = $data2['item_slot'];
			$equip[$j]['image'] = str_replace('\\\\','/',$data2['item_texture']).'.'.GetConfigValue('icon_ext');
		}
	}
	// End Get equipment


	// Fix Resist tooltips
	if( $data['resist_arcane_current'] == $data['resist_arcane_base'] )
	{
		$data['resist_arcane_tooltip'] = $data['resist_arcane_current'];
	}
	else
	{
		$data['resist_arcane_tooltip'] = $data['resist_arcane_current'].' ('.$data['resist_arcane_base'];
		if( $data['resist_arcane_bonus'] > 0 )
		{
			$data['resist_arcane_tooltip'] .= '<span class="myGreen">+'.$data['resist_arcane_bonus'].'</span>';
		}
		if( $data['resist_arcane_debuff'] < 0 )
		{
			$data['resist_arcane_tooltip'] .= '<span class="myRed">'.$data['resist_arcane_debuff'].'</span>';
		}
		$data['resist_arcane_tooltip'] .= ')';
	}

	if( $data['resist_fire_current'] == $data['resist_fire_base'] )
	{
		$data['resist_fire_tooltip'] = $data['resist_fire_current'];
	}
	else
	{
		$data['resist_fire_tooltip'] = $data['resist_fire_current'].' ('.$data['resist_fire_base'];
		if( $data['resist_fire_bonus'] > 0 )
		{
			$data['resist_fire_tooltip'] .= '<span class="myGreen">+'.$data['resist_fire_bonus'].'</span>';
		}
		if( $data['resist_fire_debuff'] < 0 )
		{
			$data['resist_fire_tooltip'] .= '<span class="myRed">'.$data['resist_fire_debuff'].'</span>';
		}
		$data['resist_fire_tooltip'] .= ')';
	}

	if( $data['resist_frost_current'] == $data['resist_frost_base'] )
	{
		$data['resist_frost_tooltip'] = $data['resist_frost_current'];
	}
	else
	{
		$data['resist_frost_tooltip'] = $data['resist_frost_current'].' ('.$data['resist_frost_base'];
		if( $data['resist_frost_bonus'] > 0 )
		{
			$data['resist_frost_tooltip'] .= '<span class="myGreen">+'.$data['resist_frost_bonus'].'</span>';
		}
		if( $data['resist_frost_debuff'] < 0 )
		{
			$data['resist_frost_tooltip'] .= '<span class="myRed">'.$data['resist_frost_debuff'].'</span>';
		}
		$data['resist_frost_tooltip'] .= ')';
	}

	if( $data['resist_holy_current'] == $data['resist_holy_base'] )
	{
		$data['resist_holy_tooltip'] = $data['resist_holy_current'];
	}
	else
	{
		$data['resist_holy_tooltip'] = $data['resist_holy_current'].' ('.$data['resist_holy_base'];
		if( $data['resist_holy_bonus'] > 0 )
		{
			$data['resist_holy_tooltip'] .= '<span class="myGreen">+'.$data['resist_holy_bonus'].'</span>';
		}
		if( $data['resist_holy_debuff'] < 0 )
		{
			$data['resist_holy_tooltip'] .= '<span class="myRed">'.$data['resist_holy_debuff'].'</span>';
		}
		$data['resist_holy_tooltip'] .= ')';
	}

	if( $data['resist_nature_current'] == $data['resist_nature_base'] )
	{
		$data['resist_nature_tooltip'] = $data['resist_nature_current'];
	}
	else
	{
		$data['resist_nature_tooltip'] = $data['resist_nature_current'].' ('.$data['resist_nature_base'];
		if( $data['resist_nature_bonus'] > 0 )
		{
			$data['resist_nature_tooltip'] .= '<span class="myGreen">+'.$data['resist_nature_bonus'].'</span>';
		}
		if( $data['resist_nature_debuff'] < 0 )
		{
			$data['resist_nature_tooltip'] .= '<span class="myRed">'.$data['resist_nature_debuff'].'</span>';
		}
		$data['resist_nature_tooltip'] .= ')';
	}

	if( $data['resist_shadow_current'] == $data['resist_shadow_base'] )
	{
		$data['resist_shadow_tooltip'] = $data['resist_shadow_current'];
	}
	else
	{
		$data['resist_shadow_tooltip'] = $data['resist_shadow_current'].' ('.$data['resist_shadow_base'];
		if( $data['resist_shadow_bonus'] > 0 )
		{
			$data['resist_shadow_tooltip'] .= '<span class="myGreen">+'.$data['resist_shadow_bonus'].'</span>';
		}
		if( $data['resist_shadow_debuff'] < 0 )
		{
			$data['resist_shadow_tooltip'] .= '<span class="myRed">'.$data['resist_shadow_debuff'].'</span>';
		}
		$data['resist_shadow_tooltip'] .= ')';
	}


	// Fix Stats tooltips
	if( $data['stats_strength_current'] == $data['stats_strength_base'] )
	{
		$data['stats_strength_tooltip'] = $data['stats_strength_current'];
	}
	else
	{
		$data['stats_strength_tooltip'] = $data['stats_strength_current'].' ('.$data['stats_strength_base'];
		if( $data['stats_strength_bonus'] > 0 )
		{
			$data['stats_strength_tooltip'] .= '<span class="myGreen">+'.$data['stats_strength_bonus'].'</span>';
		}
		if( $data['stats_strength_debuff'] < 0 )
		{
			$data['stats_strength_tooltip'] .= '<span class="myRed">'.$data['stats_strength_debuff'].'</span>';
		}
		$data['stats_strength_tooltip'] .= ')';
	}

	if( $data['stats_agility_current'] == $data['stats_agility_base'] )
	{
		$data['stats_agility_tooltip'] = $data['stats_agility_current'];
	}
	else
	{
		$data['stats_agility_tooltip'] = $data['stats_agility_current'].' ('.$data['stats_agility_base'];
		if( $data['stats_agility_bonus'] > 0 )
		{
			$data['stats_agility_tooltip'] .= '<span class="myGreen">+'.$data['stats_agility_bonus'].'</span>';
		}
		if( $data['stats_agility_debuff'] < 0 )
		{
			$data['stats_agility_tooltip'] .= '<span class="myRed">'.$data['stats_agility_debuff'].'</span>';
		}
		$data['stats_agility_tooltip'] .= ')';
	}

	if( $data['stats_stamina_current'] == $data['stats_stamina_base'] )
	{
		$data['stats_stamina_tooltip'] = $data['stats_stamina_current'];
	}
	else
	{
		$data['stats_stamina_tooltip'] = $data['stats_stamina_current'].' ('.$data['stats_stamina_base'];
		if( $data['stats_stamina_bonus'] > 0 )
		{
			$data['stats_stamina_tooltip'] .= '<span class="myGreen">+'.$data['stats_stamina_bonus'].'</span>';
		}
		if( $data['stats_stamina_debuff'] < 0 )
		{
			$data['stats_stamina_tooltip'] .= '<span class="myRed">'.$data['stats_stamina_debuff'].'</span>';
		}
		$data['stats_stamina_tooltip'] .= ')';
	}

	if( $data['stats_intellect_current'] == $data['stats_intellect_base'] )
	{
		$data['stats_intellect_tooltip'] = $data['stats_intellect_current'];
	}
	else
	{
		$data['stats_intellect_tooltip'] = $data['stats_intellect_current'].' ('.$data['stats_intellect_base'];
		if( $data['stats_intellect_bonus'] > 0 )
		{
			$data['stats_intellect_tooltip'] .= '<span class="myGreen">+'.$data['stats_intellect_bonus'].'</span>';
		}
		if( $data['stats_intellect_debuff'] < 0 )
		{
			$data['stats_intellect_tooltip'] .= '<span class="myRed">'.$data['stats_intellect_debuff'].'</span>';
		}
		$data['stats_intellect_tooltip'] .= ')';
	}

	if( $data['stats_spirit_current'] == $data['stats_spirit_base'] )
	{
		$data['stats_spirit_tooltip'] = $data['stats_spirit_current'];
	}
	else
	{
		$data['stats_spirit_tooltip'] = $data['stats_spirit_current'].' ('.$data['stats_spirit_base'];
		if( $data['stats_spirit_bonus'] > 0 )
		{
			$data['stats_spirit_tooltip'] .= '<span class="myGreen">+'.$data['stats_spirit_bonus'].'</span>';
		}
		if( $data['stats_spirit_debuff'] < 0 )
		{
			$data['stats_spirit_tooltip'] .= '<span class="myRed">'.$data['stats_spirit_debuff'].'</span>';
		}
		$data['stats_spirit_tooltip'] .= ')';
	}

	if( $data['stats_armor_current'] == $data['stats_armor_base'] )
	{
		$data['stats_armor_tooltip'] = $data['stats_armor_current'];
	}
	else
	{
		$data['stats_armor_tooltip'] = $data['stats_armor_current'].' ('.$data['stats_armor_base'];
		if( $data['stats_armor_bonus'] > 0 )
		{
			$data['stats_armor_tooltip'] .= '<span class="myGreen">+'.$data['stats_armor_bonus'].'</span>';
		}
		if( $data['stats_armor_debuff'] < 0 )
		{
			$data['stats_armor_tooltip'] .= '<span class="myRed">'.$data['stats_armor_debuff'].'</span>';
		}
		$data['stats_armor_tooltip'] .= ')';
	}

	if( $data['stats_defense_current'] == $data['stats_defense_base'] )
	{
		$data['stats_defense_tooltip'] = $data['stats_defense_current'];
	}
	else
	{
		$data['stats_defense_tooltip'] = $data['stats_defense_current'].' ('.$data['stats_defense_base'];
		if( $data['stats_defense_bonus'] > 0 )
		{
			$data['stats_defense_tooltip'] .= '<span class="myGreen">+'.$data['stats_defense_bonus'].'</span>';
		}
		if( $data['stats_defense_debuff'] < 0 )
		{
			$data['stats_defense_tooltip'] .= '<span class="myRed">'.$data['stats_defense_debuff'].'</span>';
		}
		$data['stats_defense_tooltip'] .= ')';
	}

	$data['melee_power_tooltip'] = str_replace("\n",'<br />',$data['melee_power_tooltip']);
	$data['ranged_power_tooltip'] = str_replace("\n",'<br />',$data['ranged_power_tooltip']);

	if( !empty($data['melee_damage_range_tooltip']) )
	{
		$data['melee_damage_range_tooltip'] = str_replace("\n",'</td></tr><tr><td class="myYellow_nobold" style="font-size:10px;">',$data['melee_damage_range_tooltip']);
		$data['melee_damage_range_tooltip'] = str_replace(":\t",':</td><td align="right" style="color:#FFFFFF;font-size:10px;">',$data['melee_damage_range_tooltip']);
		$data['melee_damage_range_tooltip'] = '<table style="width:200px;" cellspacing="0" cellpadding="0"><tr><td colspan="2" style="color:#FFFFFF;font-size:10px;font-weight:bold;">'.$data['melee_damage_range_tooltip'].'</table>';
	}
	else
	{
		$data['melee_damage_range_tooltip'] = '<span class="myWhite">'.getLocaleValue('charpage_melee_att',$data['locale']).' '.getLocaleValue('charpage_damage',$data['locale']).': N/A</span>';
	}

	if( !empty($data['ranged_damage_range_tooltip']) )
	{
		$data['ranged_damage_range_tooltip'] = str_replace("\n",'</td></tr><tr><td class="myYellow_nobold" style="font-size:10px;">',$data['ranged_damage_range_tooltip']);
		$data['ranged_damage_range_tooltip'] = str_replace(":\t",':</td><td align="right" style="color:#FFFFFF;font-size:10px;">',$data['ranged_damage_range_tooltip']);
		$data['ranged_damage_range_tooltip'] = '<table style="width:200px;" cellspacing="0" cellpadding="0"><tr><td colspan="2" style="color:#FFFFFF;font-size:10px;font-weight:bold;">'.$data['ranged_damage_range_tooltip'].'</table>';
	}
	else
	{
		$data['ranged_damage_range_tooltip'] = '<span class="myWhite">'.getLocaleValue('charpage_range_att',$data['locale']).' '.getLocaleValue('charpage_damage',$data['locale']).': N/A</span>';
	}

	// Resistance tooltips
	setTooltip('resist_fire','<span class="myRed">'.getLocaleValue('charpage_res_fire',$data['locale']).'</span> <span class="myWhite">'.$data['resist_fire_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_fire_tooltip',$data['locale']).'</span>');
	setTooltip('resist_nature','<span class="myGreen">'.getLocaleValue('charpage_res_nature',$data['locale']).'</span> <span class="myWhite">'.$data['resist_nature_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_nature_tooltip',$data['locale']).'</span>');
	setTooltip('resist_arcane','<span class="myYellow">'.getLocaleValue('charpage_res_arcane',$data['locale']).'</span> <span class="myWhite">'.$data['resist_arcane_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_arcane_tooltip',$data['locale']).'</span>');
	setTooltip('resist_frost','<span class="myBlue">'.getLocaleValue('charpage_res_frost',$data['locale']).'</span> <span class="myWhite">'.$data['resist_frost_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_frost_tooltip',$data['locale']).'</span>');
	setTooltip('resist_shadow','<span class="myPurple">'.getLocaleValue('charpage_res_shadow',$data['locale']).'</span> <span class="myWhite">'.$data['resist_shadow_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_res_shadow_tooltip',$data['locale']).'</span>');

	// Stat tooltips
	setTooltip('strength_tooltip','<span class="myWhite">'.getLocaleValue('charpage_strength',$data['locale']).': '.$data['stats_strength_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_strength_tooltip',$data['locale']).'</span>');
	setTooltip('agility_tooltip','<span class="myWhite">'.getLocaleValue('charpage_agility',$data['locale']).': '.$data['stats_agility_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_agility_tooltip',$data['locale']).'</span>');
	setTooltip('stamina_tooltip','<span class="myWhite">'.getLocaleValue('charpage_stamina',$data['locale']).': '.$data['stats_stamina_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_stamina_tooltip',$data['locale']).'</span>');
	setTooltip('intellect_tooltip','<span class="myWhite">'.getLocaleValue('charpage_intellect',$data['locale']).': '.$data['stats_intellect_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_intellect_tooltip',$data['locale']).'</span>');
	setTooltip('spirit_tooltip','<span class="myWhite">'.getLocaleValue('charpage_spirit',$data['locale']).': '.$data['stats_spirit_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_spirit_tooltip',$data['locale']).'</span>');
	setTooltip('armor_tooltip','<span class="myWhite">'.getLocaleValue('charpage_armor',$data['locale']).': '.$data['stats_armor_tooltip'].'</span><br /><span class="myYellow_nobold">'.str_replace('*MITIGATION*',$data['mitigation'],str_replace('*LEVEL*',$data['level'],getLocaleValue('charpage_armor_tooltip',$data['locale']))).'</span>');
	setTooltip('defense_tooltip','<span class="myWhite">'.getLocaleValue('charpage_defense',$data['locale']).': '.$data['stats_defense_tooltip'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_defense_tooltip',$data['locale']).'</span>');

	// Attack tooltips
	setTooltip('melee_rating_tooltip','<span class="myWhite">'.getLocaleValue('charpage_melee_rating',$data['locale']).': '.$data['melee_rating'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_melee_rating_tooltip',$data['locale']).'</span>');
	setTooltip('melee_power_tooltip','<span class="myWhite">'.getLocaleValue('charpage_melee_att_power',$data['locale']).': '.$data['melee_power'].'</span><br /><span class="myYellow_nobold">'.$data['melee_power_tooltip'].'</span>');
	setTooltip('melee_damage_range_tooltip',$data['melee_damage_range_tooltip']);

	setTooltip('ranged_rating_tooltip','<span class="myWhite">'.getLocaleValue('charpage_range_rating',$data['locale']).': '.$data['ranged_rating'].'</span><br /><span class="myYellow_nobold">'.getLocaleValue('charpage_range_rating_tooltip',$data['locale']).'</span>');
	setTooltip('ranged_power_tooltip','<span class="myWhite">'.getLocaleValue('charpage_range_att_power',$data['locale']).': '.$data['ranged_power'].'</span><br /><span class="myYellow_nobold">'.$data['ranged_power_tooltip'].'</span>');
	setTooltip('ranged_damage_range_tooltip',$data['ranged_damage_range_tooltip']);


	//assign the required template variables
	if( isset($equip) )
	{
		$tpl->assign( 'equip', $equip );
	}
	$tpl->assign( 'expbarwidth',floor($data['xp_current'] / $data['xp_total'] * 216) );
	$tpl->assign( 'exppercent',floor($data['xp_current'] / $data['xp_total'] * 100) );
}


function writeCharacter( &$data )
{
	global $tpl;

	getCharTabValues($data);
	$showPets = getPetTabValues($data);
	getRepTabValues($data['member_id']);
	getSkillTabValues($data['member_id']);

	// Assign template variables
	$tpl->assign( 'honor_imagewidth', ceil( (308 / 100) * $data['honor_current_progress'] ) );
	$tpl->assign( 'honor_icon', GetConfigValue('iconpath').'/'.str_replace('\\\\','/',$data['honor_current_icon']) );
	$tpl->assign( 'faction_en', getEnglishValue($data['faction']) );
	$tpl->assign( 'show_pets',$showPets );

	$tpl->assign( 'data', $data );
}


function writeBagData( $bagdata , $result )
{
	global $data;

	$bagtype = 'bag_'.$bagdata['item_quantity'].'_slot';

	$bagname = 'bagname';
	$bagmask = 'bm';

	if( $bagdata['item_slot'] == 'Bag5' )
	{
		$bagtype .= '_key';
		$bagmask = 'kbm';
	}

	if( $bagdata['item_slot'] == 'Bank Contents' )
	{
		$bagname .= '24';
		$bagtype = 'mainbank_'.$bagtype;
		$bagmask = 'bbm';
	}
	elseif( substr($bagdata['item_slot'],0,4) == 'Bank' )
	{
		$bagtype = 'bank_'.$bagtype;
		$bagmask = 'bbm';
	}

	//Create the bag array
	$returnBag['type'] = $bagtype;
	$returnBag['name'] = $bagdata['item_name'];
	$returnBag['slot'] = $bagdata['item_slot'];
	$returnBag['id'] = str_replace(' ','',$bagdata['item_slot']);
	$returnBag['icon'] = str_replace('\\\\','/',$bagdata['item_texture']);
	$returnBag['text_css'] = $bagname;
	$returnBag['icon_mask'] = $bagmask;


	//Create the items array
	$itemnum = 0;
	if( $result->numRows() > 0 )
	{
		for( $j=0; $j < $result->numRows(); $j++)
		{
			$result->fetchInto($itemdata);

			while( $itemdata['pos'] > $itemnum )
			{
				$returnItem[$itemnum]['image'] = '';
				$returnItem[$itemnum]['quantity'] = '';
				$returnItem[$itemnum]['id'] = $itemnum;
				$itemnum++;
			}

			$item_texture = str_replace('\\\\','/',$itemdata['item_texture']).'.'.GetConfigValue('icon_ext');
			formatTooltip($itemdata,str_replace(' ','',$bagdata['item_slot']).'_'.$itemnum,$data['locale']);

			$returnItem[$itemnum]['image'] = $item_texture;
			$returnItem[$itemnum]['quantity'] = $itemdata['item_quantity'];
			$returnItem[$itemnum]['id'] = $itemnum;
			$itemnum++;
		}
		//Insert items into the bags array
		$returnBag['items'] = $returnItem;
	}
	return $returnBag;
}


function bagInit( $data )
{
	global $tpl;

	$sqlquery = "SELECT *, CASE `item_slot` ".
		"WHEN 'Bag5' THEN 0 ".
		"WHEN 'Bag4' THEN 1 ".
		"WHEN 'Bag3' THEN 2 ".
		"WHEN 'Bag2' THEN 3 ".
		"WHEN 'Bag1' THEN 4 ".
		"WHEN 'Bag0' THEN 5 ".
		"ELSE 6 END AS bagpos ".
		"FROM `".ROSTER_ITEMSTABLE."` ".
		"WHERE `item_parent` = 'bags' ".
		"AND (`item_slot` = 'Bag0' OR `item_slot` = 'Bag1' OR `item_slot` = 'Bag2' OR `item_slot` = 'Bag3' OR `item_slot` = 'Bag4' OR `item_slot` = 'Bag5') ".
		"AND `member_id` = '".$data['member_id']."' ".
		"ORDER BY bagpos ASC";

	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	if( $result->numRows() > 0 )
	{
		for( $j=0; $j < $result->numRows(); $j++)
		{
			$result->fetchInto($bagsdata);
			$bagsdata['item_texture'] = str_replace('\\\\','/',$bagsdata['item_texture']).'.'.GetConfigValue('icon_ext');

			formatTooltip($bagsdata,str_replace(' ','',$bagsdata['item_slot']),$data['locale']);

			$sqlquery = "SELECT *,item_tooltip,(0+item_slot) AS pos FROM `".ROSTER_ITEMSTABLE."` WHERE `item_parent` = '".$bagsdata['item_slot']."' AND `member_id` = '".$bagsdata['member_id']."' ORDER BY pos";
			setSqlQuery($sqlquery);

			$bagresult = db_query($sqlquery);
			if( PEAR::isError($bagresult) )
			{
			    die_quietly($bagresult->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
			}
			$bagArray[] = writeBagData($bagsdata,$bagresult);
		}
		$tpl->assign( 'loop',-1 );
		$tpl->assign( 'bagarray',$bagArray );
	}
}


function bankInit( $data )
{
	global $tpl;

	$sqlquery = "SELECT *, CASE `item_slot` ".
		"WHEN 'Bank Contents' THEN 0 ".
		"WHEN 'Bank Bag1' THEN 1 ".
		"WHEN 'Bank Bag2' THEN 2 ".
		"WHEN 'Bank Bag3' THEN 3 ".
		"WHEN 'Bank Bag4' THEN 4 ".
		"WHEN 'Bank Bag5' THEN 5 ".
		"WHEN 'Bank Bag6' THEN 6 ".
		"ELSE 7 END AS bagpos ".
		"FROM `".ROSTER_ITEMSTABLE."` ".
		"WHERE `item_parent` = 'bags' ".
		"AND (`item_slot` = 'Bank Bag1' OR `item_slot` = 'Bank Bag2' OR `item_slot` = 'Bank Bag3' OR `item_slot` = 'Bank Bag4' OR `item_slot` = 'Bank Bag5' OR `item_slot` = 'Bank Bag6' OR `item_slot` = 'Bank Contents') ".
		"AND `member_id` = '".$data['member_id']."' ".
		"ORDER BY bagpos ASC";

	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	if( $result->numRows() > 0 )
	{
		for( $j=0; $j < $result->numRows(); $j++)
		{
			$result->fetchInto($bagsdata);
			$bagsdata['item_texture'] = str_replace('\\\\','/',$bagsdata['item_texture']).'.'.GetConfigValue('icon_ext');

			if( $bagsdata['item_slot'] == 'Bank Contents' )
			{
				$bagsdata['item_texture'] = 'Interface/Icons/INV_Misc_Bag_15.'.GetConfigValue('icon_ext');
				$bagsdata['item_tooltip'] = "Bank Contents\n24 Slot Container";
			}

			formatTooltip($bagsdata,str_replace(' ','',$bagsdata['item_slot']),$data['locale']);

			$sqlquery = "SELECT *,item_tooltip,(0+item_slot) AS pos FROM `".ROSTER_ITEMSTABLE."` WHERE `item_parent` = '".$bagsdata['item_slot']."' AND `member_id` = '".$bagsdata['member_id']."' ORDER BY pos";
			setSqlQuery($sqlquery);

			$bagresult = db_query($sqlquery);
			if( PEAR::isError($bagresult) )
			{
			    die_quietly($bagresult->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
			}
			$bagArray[] = writeBagData($bagsdata,$bagresult);
		}
		$tpl->assign( 'loop',1 );
		$tpl->assign( 'bagarray',$bagArray );
	}
}


function writeTalentLayer( $member_id , $treename )
{
	global $data;

	$sqlquery = "SELECT * FROM `".ROSTER_TALENTSTABLE."` AS main LEFT JOIN `".ROSTER_LUT_TALENTS."` AS lut ON main.talent_id=lut.talent_id WHERE `member_id` = '$member_id' AND `talent_tree` = '".$treename."' ORDER BY `talent_row` ASC , `talent_column` ASC";
	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	while( $result->fetchInto($talentdata) )
	{
		$r = $talentdata['talent_row'];
		$c = $talentdata['talent_column'];

		$returndata[$r][$c]['name'] = $talentdata['talent_name'];
		$returndata[$r][$c]['rank'] = $talentdata['talent_rank'];
		$returndata[$r][$c]['maxrank'] = $talentdata['talent_maximum_rank'];
		$returndata[$r][$c]['row'] = $r;
		$returndata[$r][$c]['column'] = $c;

		if( $talentdata['talent_rank'] == $talentdata['talent_maximum_rank'] )
		{
			$returndata[$r][$c]['numcolor'] = '00dd00';
		}
		else
		{
			$returndata[$r][$c]['numcolor'] = 'ffdd00';
		}

		$returndata[$r][$c]['image'] = str_replace('\\\\','/',$talentdata['talent_texture']).'.'.GetConfigValue('icon_ext');
		$returndata[$r][$c]['tooltipid'] = formatTalentTooltip($talentdata['talent_tooltip'],$talentdata['talent_name'],$data['locale']);
	}
	return $returndata;
}


function writeTalents( $member_id )
{
	global $tpl;

	$sqlquery = "SELECT * FROM `".ROSTER_TALENTTREETABLE."` WHERE `member_id` = '$member_id' ORDER BY `order` ASC";
	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	if( $result->numRows() > 0 )
	{
		for( $j=0; $j < $result->numRows(); $j++)
		{
			$result->fetchInto($treedata);

			$treelayer[$j]['name'] = $treedata['tree'];
			$treelayer[$j]['image'] = str_replace('\\','/',$treedata['background']).'.'.GetConfigValue('icon_ext');
			$treelayer[$j]['points'] = $treedata['pointsspent'];
			$talents[$j] = writeTalentLayer($member_id,$treedata['tree']);
		}
		$tpl->assign( 'treelayer',$treelayer );
		$tpl->assign( 'talents',$talents );
	}

}


function writeSpellbook( $member_id )
{
	global $tpl;

	$sqlquery = "SELECT * FROM `".ROSTER_SPELLTREETABLE."` AS main LEFT JOIN ".ROSTER_LUT_SPELLINFO." AS sec ON main.spellinfo_id = sec.spellinfo_id WHERE `member_id` = '$member_id';";
	setSqlQuery($sqlquery);

	$result = db_query($sqlquery);
	if( PEAR::isError($result) )
	{
	    die_quietly($result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
	}

	if( $result->numRows() > 0 )
	{
		for( $t=0; $t < $result->numRows(); $t++)
		{
			$result->fetchInto($treedata);

			$tree[$t]['name'] = $treedata['spellinfo_type'];
			$tree[$t]['icon'] = str_replace('\\','/',$treedata['spellinfo_icon']);


			// Get Icons
			$sqlquery = "SELECT * FROM `".ROSTER_SPELLTABLE."` AS main LEFT JOIN ".ROSTER_LUT_SPELLINFO." AS sec ON main.spellinfo_id = sec.spellinfo_id WHERE `member_id` = '$member_id' AND `spellinfo_type` = '".$tree[$t]['name']."' ORDER BY `spellinfo_name`;";
			setSqlQuery($sqlquery);

			$icons_result = db_query($sqlquery);
			if( PEAR::isError($icons_result) )
			{
			    die_quietly($icons_result->getMessage(),'',(__FILE__),(__LINE__),$sqlquery);
			}

			if( $icons_result->numRows() > 0 )
			{
				$i=0;
				$p=0;
				for( $r=0; $r < $icons_result->numRows(); $r++ )
				{
					$icons_result->fetchInto($icons_data);

					if( $i == 14 )
					{
						$i=0;
						$p++;
					}

					$spells[$p][$i]['name'] = $icons_data['spellinfo_name'];
					$spells[$p][$i]['type'] = $icons_data['spellinfo_type'];
					$spells[$p][$i]['icon'] = str_replace('\\','/',$icons_data['spellinfo_icon']);
					$spells[$p][$i]['rank'] = $icons_data['spellinfo_rank'];
					setTooltip("spelltip_".$t."_".$p."_".$i,"<span class=\"myYellow\">".addslashes($icons_data['spellinfo_name'])."</span><br />".addslashes($icons_data['spellinfo_rank']));

					$i++;
				}
				// Assign spells to tree
				$tree[$t]['spells'] = $spells;
				unset($spells);
			}
		}
		$tpl->assign( 'spelltree',$tree );
	}
}

?>