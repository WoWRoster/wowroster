{literal}
<script type="text/javascript">
<!--
	//Set tab to intially be selected when page loads:
	//[which tab (1=first tab), ID of tab content to display]:
	var initialtab=[1, 'tab1']
	var previoustab=''

	window.onload=charpage_onload
//-->
</script>
{/literal}

<div class="char_panel">
	<img src="{$roster_conf.imagepath}/char_page/char_portrait/{$char_image|lower|strip:''}.gif" class="char_tab_image" alt=""/>
	<div class="char_name">{if $data.honor_current_rank eq 0}{else}{$data.honor_current_name} {/if}{$data.name}</div>
	<div class="char_infoline_1">{$roster_wordings[$data.locale].charpage_level} {$data.level} {$data.race} {$data.class}</div>
	{if $data.guild_name neq ''}<div class="char_infoline_2">{$data.guild_title} of {$data.guild_name}</div>{/if}
<!-- Begin Character Page -->
	<div id="tab1" class="char_tab" style="display:none;">
		<div class="char_page_background">&nbsp;</div>

		<!-- Begin Equipment Items Loop -->
{* This section loops through the "equip" array
	available variables
		$equip[equip_id].slot_id = Name of equip slot
		$equip[equip_id].image   = "Interface/Icons/ImageName"
*}
{section loop=$equip name=equip_id}
		<img onmouseover="overlib(equip_{$equip[equip_id].slot_id});" onclick="overlib(equip_{$equip[equip_id].slot_id}_link,STICKY,MOUSEOFF);" onmouseout="return nd();" src="{$roster_conf.iconpath}/{$equip[equip_id].image}" class="equip_{$equip[equip_id].slot_id}" alt="" />
{/section}
		<!-- End Equipment Items Loop -->

		<div class="char_resist_fire" onmouseover="overlib(resist_fire);" onmouseout="return nd();">{$data.resist_fire_current}</div>
		<div class="char_resist_nature" onmouseover="overlib(resist_nature);" onmouseout="return nd();">{$data.resist_nature_current}</div>
		<div class="char_resist_arcane" onmouseover="overlib(resist_arcane);" onmouseout="return nd();">{$data.resist_arcane_current}</div>
		<div class="char_resist_frost" onmouseover="overlib(resist_frost);" onmouseout="return nd();">{$data.resist_frost_current}</div>
		<div class="char_resist_shadow" onmouseover="overlib(resist_shadow);" onmouseout="return nd();">{$data.resist_shadow_current}</div>
		<!-- Begin Advanced Stats -->
		<img src="{$roster_conf.imagepath}/char_page/percentframe.gif" class="char_percent_frame" alt="Character Advanced Info frame" />
		<div class="char_info_values">{$data.health}<br />
			{$data.mana}<br />
{if $data.dodge neq '0'}
			{$data.dodge} %<br />
{/if}
{if $data.parry neq '0'}
			{$data.parry} %<br />
{/if}
{if $data.block neq '0'}
			{$data.block} %<br />
{/if}
{if $data.mitigation neq '0'}
			{$data.mitigation} %<br />
{/if}
{if $data.crit neq '0'}
			{$data.crit} %<br />
{/if}</div>
		<div class="char_info_desc">{$roster_wordings[$data.locale].charpage_health}:<br />
			{$roster_wordings[$data.locale].charpage_mana}:<br />
{if $data.dodge neq '0'}
			{$roster_wordings[$data.locale].charpage_dodge}:<br />
{/if}
{if $data.parry neq '0'}
			{$roster_wordings[$data.locale].charpage_parry}:<br />
{/if}
{if $data.block neq '0'}
			{$roster_wordings[$data.locale].charpage_block}:<br />
{/if}
{if $data.mitigation neq '0'}
			{$roster_wordings[$data.locale].charpage_damage_mitigation}:<br />
{/if}
{if $data.crit neq '0'}
			{$roster_wordings[$data.locale].charpage_crit_rate}:<br />
{/if}</div>
		<!-- Money Display -->
		<div class="char_money_disp">{$data.money_gold}<img src="{$roster_conf.imagepath}/coin_gold.gif" class="coin" alt="" /> {$data.money_silver}<img src="{$roster_conf.imagepath}/coin_silver.gif" class="coin" alt="" /> {$data.money_copper}<img src="{$roster_conf.imagepath}/coin_copper.gif" class="coin" alt="" />&nbsp;</div>
		<!-- Begin EXP Bar -->
		<img src="{$roster_conf.imagepath}/char_page/expbar_empty.gif" class="char_xpbar_empty" alt="" />
{if $data.level eq 60}
		<div class="char_xpbar"><img src="{$roster_conf.imagepath}/char_page/expbar_full.gif" alt="" /></div>
		<div class="char_xpbar_text">{$roster_wordings[$data.locale].charpage_max_xp}</div>
{else}
		<div class="char_xpbar" style="clip:rect(0px {$expbarwidth}px 12px 0px);"><img src="{$roster_conf.imagepath}/char_page/expbar_full{if $data.xp_rested gt 0}_rested{/if}.gif" alt="" /></div>
		<div class="char_xpbar_text">XP {$data.xp_current} / {$data.xp_total}{if $data.xp_rested gt 0} : {$data.xp_rested}{/if} ( {$exppercent}% )</div>
{/if}
		<!-- End EXP Bar -->
		<div class="char_stats_desc">{$roster_wordings[$data.locale].charpage_strength}:<br />
			{$roster_wordings[$data.locale].charpage_agility}:<br />
			{$roster_wordings[$data.locale].charpage_stamina}:<br />
			{$roster_wordings[$data.locale].charpage_intellect}:<br />
			{$roster_wordings[$data.locale].charpage_spirit}:<br />
			{$roster_wordings[$data.locale].charpage_armor}:<br />
			{$roster_wordings[$data.locale].charpage_defense}:</div>
		<div class="char_stats_values">{if $data.stats_strength_debuff lt 0}<span class="myRed_nobold">{elseif $data.stats_strength_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$data.stats_strength_current}</span><br />
			{if $data.stats_agility_debuff lt 0}<span class="myRed_nobold">{elseif $data.stats_agility_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$data.stats_agility_current}</span><br />
			{if $data.stats_stamina_debuff lt 0}<span class="myRed_nobold">{elseif $data.stats_stamina_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$data.stats_stamina_current}</span><br />
			{if $data.stats_intellect_debuff lt 0}<span class="myRed_nobold">{elseif $data.stats_intellect_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$data.stats_intellect_current}</span><br />
			{if $data.stats_spirit_debuff lt 0}<span class="myRed_nobold">{elseif $data.stats_spirit_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$data.stats_spirit_current}</span><br />
			{if $data.stats_armor_debuff lt 0}<span class="myRed_nobold">{elseif $data.stats_armor_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$data.stats_armor_current}</span><br />
			{if $data.stats_defense_debuff lt 0}<span class="myRed_nobold">{elseif $data.stats_defense_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$data.stats_defense_current}</span></div>

		<div class="CharMeleeAttack">{$roster_wordings[$data.locale].charpage_melee_att}:</div>
		<div class="CharMeleePower">{$roster_wordings[$data.locale].charpage_power}:</div>
		<div class="CharMeleeDamage">{$roster_wordings[$data.locale].charpage_damage}:</div>

		<div class="CharMeleeAttackRating">{if $data.melee_rating eq 0}N/A{else}{$data.melee_rating}{/if}</div>
		<div class="CharMeleePowerRating">{if $data.melee_power eq 0}N/A{else}{$data.melee_power}{/if}</div>
		<div class="CharMeleeDamageRating">{if $data.melee_damage_max eq 0}N/A{else}{$data.melee_damage_min} - {$data.melee_damage_max}{/if}</div>

		<div class="CharRangedAttack">{$roster_wordings[$data.locale].charpage_range_att}:</div>
		<div class="CharRangedPower">{$roster_wordings[$data.locale].charpage_power}:</div>
		<div class="CharRangedDamage">{$roster_wordings[$data.locale].charpage_damage}:</div>

		<div class="CharRangedAttackRating">{if $data.ranged_rating eq 0}N/A{else}{$data.ranged_rating}{/if}</div>
		<div class="CharRangedPowerRating">{if $data.ranged_power eq 0}N/A{else}{$data.ranged_power}{/if}</div>
		<div class="CharRangedDamageRating">{if $data.ranged_damage_max eq 0}N/A{else}{$data.ranged_damage_min} - {$data.ranged_damage_max}{/if}</div>

		<div class="CharStatsTooltip" onmouseover="overlib(strength_tooltip);" onmouseout="return nd();" style="margin-top:260px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharStatsTooltip" onmouseover="overlib(agility_tooltip);" onmouseout="return nd();" style="margin-top:275px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharStatsTooltip" onmouseover="overlib(stamina_tooltip);" onmouseout="return nd();" style="margin-top:290px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharStatsTooltip" onmouseover="overlib(intellect_tooltip);" onmouseout="return nd();" style="margin-top:305px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharStatsTooltip" onmouseover="overlib(spirit_tooltip);" onmouseout="return nd();" style="margin-top:320px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharStatsTooltip" onmouseover="overlib(armor_tooltip);" onmouseout="return nd();" style="margin-top:335px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharStatsTooltip" onmouseover="overlib(defense_tooltip);" onmouseout="return nd();" style="margin-top:350px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>

		<div class="CharAttackTooltip" onmouseover="overlib(melee_rating_tooltip);" onmouseout="return nd();" style="margin-top:261px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharAttackTooltip" onmouseover="overlib(melee_power_tooltip);" onmouseout="return nd();" style="margin-top:276px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharAttackTooltip" onmouseover="overlib(melee_damage_range_tooltip);" onmouseout="return nd();" style="margin-top:291px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharAttackTooltip" onmouseover="overlib(ranged_rating_tooltip);" onmouseout="return nd();" style="margin-top:318px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharAttackTooltip" onmouseover="overlib(ranged_power_tooltip);" onmouseout="return nd();" style="margin-top:333px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>
		<div class="CharAttackTooltip" onmouseover="overlib(ranged_damage_range_tooltip);" onmouseout="return nd();" style="margin-top:348px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:100px;" alt="" /></div>

	</div>

{if $show_pets}
<!-- Begin Pet Tab -->
	<div id="tab2" class="char_tab" style="display:none;">
		<div class="char_pet_background">&nbsp;</div>
{section loop=$pets name=pet_id}
{if $smarty.section.pet_id.first}
		<div id="pet_{$smarty.section.pet_id.index}">
{else}
		<div id="pet_{$smarty.section.pet_id.index}" style="display:none;">
{/if}

			<div class="char_pet_name">{$pets[pet_id].name}</div>
			<div class="char_pet_info">{$roster_wordings[$data.locale].charpage_level} {$pets[pet_id].level} {$pets[pet_id].type}</div>
{if $pets[pet_id].loyalty neq ''}
			<div class="char_pet_loyalty">{$pets[pet_id].loyalty}</div>
{/if}
			<img class="char_pet_icon" src="{$roster_conf.iconpath}/{$pets[pet_id].icon}" alt="" />

			<div class="char_pet_health"><span class="myYellow_nobold">{$roster_wordings[$data.locale].charpage_health}:</span> {$pets[pet_id].health}</div>
			<div class="char_pet_mana"><span class="myYellow_nobold">{$roster_wordings[$data.locale].charpage_mana}:</span> {$pets[pet_id].mana}</div>

			<div class="char_pet_resist_fire" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_resist_fire);" onmouseout="return nd();">{$pets[pet_id].resist_fire_current}</div>
			<div class="char_pet_resist_nature" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_resist_nature);" onmouseout="return nd();">{$pets[pet_id].resist_nature_current}</div>
			<div class="char_pet_resist_arcane" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_resist_arcane);" onmouseout="return nd();">{$pets[pet_id].resist_arcane_current}</div>
			<div class="char_pet_resist_frost" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_resist_frost);" onmouseout="return nd();">{$pets[pet_id].resist_frost_current}</div>
			<div class="char_pet_resist_shadow" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_resist_shadow);" onmouseout="return nd();">{$pets[pet_id].resist_shadow_current}</div>


			<div class="CharPetStatsDescription_left">{$roster_wordings[$data.locale].charpage_strength}:<br />
				{$roster_wordings[$data.locale].charpage_agility}:<br />
				{$roster_wordings[$data.locale].charpage_stamina}:<br />
				{$roster_wordings[$data.locale].charpage_intellect}:<br />
				{$roster_wordings[$data.locale].charpage_spirit}:</div>
			<div class="CharPetStats_left">{if $pets[pet_id].stats_strength_debuff lt 0}<span class="myRed_nobold">{elseif $pets[pet_id].stats_strength_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$pets[pet_id].stats_strength_current}</span><br />
				{if $pets[pet_id].stats_agility_debuff lt 0}<span class="myRed_nobold">{elseif $pets[pet_id].stats_agility_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$pets[pet_id].stats_agility_current}</span><br />
				{if $pets[pet_id].stats_stamina_debuff lt 0}<span class="myRed_nobold">{elseif $pets[pet_id].stats_stamina_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$pets[pet_id].stats_stamina_current}</span><br />
				{if $pets[pet_id].stats_intellect_debuff lt 0}<span class="myRed_nobold">{elseif $pets[pet_id].stats_intellect_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$pets[pet_id].stats_intellect_current}</span><br />
				{if $pets[pet_id].stats_spirit_debuff lt 0}<span class="myRed_nobold">{elseif $pets[pet_id].stats_spirit_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$pets[pet_id].stats_spirit_current}</span></div>


			<div class="CharPetStatsDescription_right">{$roster_wordings[$data.locale].charpage_attack}:<br />
				{$roster_wordings[$data.locale].charpage_power}:<br />
				{$roster_wordings[$data.locale].charpage_damage}:<br />
				{$roster_wordings[$data.locale].charpage_defense}:<br />
				{$roster_wordings[$data.locale].charpage_armor}:</div>
			<div class="CharPetStats_right">{$pets[pet_id].melee_rating}<br />
				{$pets[pet_id].melee_power}<br />
				{$pets[pet_id].melee_damage_min} - {$pets[pet_id].melee_damage_max}<br />
				{if $pets[pet_id].stats_defense_bonus lt 0}<span class="myRed_nobold">{elseif $pets[pet_id].stats_defense_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$pets[pet_id].stats_defense_current}</span><br />
				{if $pets[pet_id].stats_armor_bonus lt 0}<span class="myRed_nobold">{elseif $pets[pet_id].stats_armor_bonus gt 0}<span class="myGreen_nobold">{else}<span class="myWhite_nobold">{/if}{$pets[pet_id].stats_armor_current}</span></div>

			<div class="CharPetStats1Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_strength_tooltip);" onmouseout="return nd();" style="margin-top:238px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats1Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_agility_tooltip);" onmouseout="return nd();" style="margin-top:255px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats1Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_stamina_tooltip);" onmouseout="return nd();" style="margin-top:272px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats1Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_intellect_tooltip);" onmouseout="return nd();" style="margin-top:289px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats1Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_spirit_tooltip);" onmouseout="return nd();" style="margin-top:306px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>

			<div class="CharPetStats2Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_melee_rating_tooltip);" onmouseout="return nd();" style="margin-top:238px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats2Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_melee_power_tooltip);" onmouseout="return nd();" style="margin-top:255px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats2Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_melee_damage_range_tooltip);" onmouseout="return nd();" style="margin-top:272px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats2Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_melee_defense_tooltip);" onmouseout="return nd();" style="margin-top:289px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>
			<div class="CharPetStats2Tooltip" onmouseover="overlib(pet_{$smarty.section.pet_id.index}_armor_tooltip);" onmouseout="return nd();" style="margin-top:306px;"><img src="{$roster_conf.imagepath}/spacer.gif" style="height:14px;width:110px;" alt="" /></div>


{if $pets[pet_id].xp_total neq 0}
{if $pets[pet_id].level neq 60}

			<!-- Begin Pet EXP Bar -->
			<img src="{$roster_conf.imagepath}/char_page/expbar_empty.gif" class="CharPetExpBarEmpty" alt="" />
			<div class="CharPetExpBar" style="clip:rect(0px {$pets[pet_id].exp_barwidth}px 12px 0px);"><img src="{$roster_conf.imagepath}/char_page/expbar_full.gif" alt="" /></div>
			<div class="CharPetExpText">XP {$pets[pet_id].xp_current} / {$pets[pet_id].xp_total}  ( {$pets[pet_id].exp_percent}% )</div>
			<!-- End Pet EXP Bar -->
{/if}
{/if}
{if $pets[pet_id].total_training_points neq 0}
			<div class="CharPetTrainingPts">{$roster_wordings[$data.locale].charpage_training_points}: {$pets[pet_id].unused_tp} / {$pets[pet_id].total_training_points}</div>
{/if}
		</div>

{/section}
		<div class="CharPetIcons">
{section loop=$pets name=pet_id_icon}
			<img class="CharPetIconClick" src="{$roster_conf.iconpath}/{$pets[pet_id_icon].icon}" onclick="showPet('pet_{$smarty.section.pet_id_icon.index}')" onmouseover="overlib('{$pets[pet_id_icon].name|escape:"quotes"}&lt;br /&gt;{$pets[pet_id_icon].type}',WRAP);" onmouseout="return nd();" alt="" />
{/section}
		</div>


	</div>
{/if}

<!-- Begin Reputation Tab -->
	<div id="tab3" class="char_tab" style="display:none;">
		<img class="char_rep_menu" src="{$roster_conf.imagepath}/char_page/repmenu.gif" alt="" />
		<div class="char_rep_faction">{$roster_wordings[$data.locale].charpage_faction}</div>
		<div class="char_rep_standing">{$roster_wordings[$data.locale].charpage_standing}</div>
		<div class="char_rep_atwar">{$roster_wordings[$data.locale].charpage_at_war}</div>

		<div class="char_rep_container">

{section loop=$repinfo name=repid}
				<div id="rep{$repinfo[repid].id}c" style="display:none;">
					<div class="char_rep_header"><img src="{$roster_conf.imagepath}/char_page/plus.gif" class="char_rep_minus_plus" alt="" onclick="expandRep({$repinfo[repid].id});" />
						{$repinfo[repid].name}</div>
				</div>

				<div id="rep{$repinfo[repid].id}e">
					<div class="char_rep_header"><img src="{$roster_conf.imagepath}/char_page/minus.gif" class="char_rep_minus_plus" alt="" onclick="collapseRep({$repinfo[repid].id});" />
						{$repinfo[repid].name}</div>

{section loop=$repbarinfo[repid] name=repbarid}
					<div class="char_rep_bar">
						<div class="char_rep_title">{$repbarinfo[repid][repbarid].name}</div>
						<div class="char_rep_bar_field" style="clip:rect(0px {$repbarinfo[repid][repbarid].barwidth}px 13px 0px);"><img class="char_rep_bar_image" src="{$repbarinfo[repid][repbarid].image}" alt="" /></div>
						<div id="rb{$repbarinfo[repid][repbarid].barid}" class="char_rep_bar_text">{$repbarinfo[repid][repbarid].standing}</div>
						<div id="rbn{$repbarinfo[repid][repbarid].barid}" class="char_rep_bar_text" style="display:none">{$repbarinfo[repid][repbarid].value} / {$repbarinfo[repid][repbarid].maxvalue}</div>
						<div class="char_rep_bar_field"><img class="char_rep_bar_image" src="{$roster_conf.imagepath}/spacer.gif" onmouseout="showLayer('rb{$repbarinfo[repid][repbarid].barid}');hideLayer('rbn{$repbarinfo[repid][repbarid].barid}');" onmouseover="hideLayer('rb{$repbarinfo[repid][repbarid].barid}');showLayer('rbn{$repbarinfo[repid][repbarid].barid}');" alt="" /></div>
{if $repbarinfo[repid][repbarid].atwar eq '1'}
						<img src="{$roster_conf.imagepath}/char_page/reputationbox_atwar.gif" style="float:right;" alt="" />
{/if}
					</div>
{/section}

				</div>
{/section}
		</div>

	</div>

<!-- Begin Skills Tab -->
	<div id="tab4" class="char_tab" style="display:none;">
		<img class="char_skill_alltab" src="{$roster_conf.imagepath}/char_page/all_tab.gif" alt="" />
		<img id="cAll" class="char_skill_minus_plus_all" src="{$roster_conf.imagepath}/char_page/minus.gif" onclick="hideAllSkills()" alt="" />
		<img id="eAll" class="char_skill_minus_plus_all" src="{$roster_conf.imagepath}/char_page/plus.gif" style="display:none;" onclick="showAllSkills()" alt="" />

		<div class="char_skill_container">

{section loop=$skillinfo name=skillid}
				<div id="cs{$skillinfo[skillid].id}c" style="display:none;">
					<div class="char_skill_header"><img class="char_skill_minus_plus" src="{$roster_conf.imagepath}/char_page/plus.gif" alt="" onclick="expandSkills({$skillinfo[skillid].id});" />
							{$skillinfo[skillid].name}</div>
				</div>
				<div id="cs{$skillinfo[skillid].id}e">
						<div class="char_skill_header"><img class="char_skill_minus_plus" src="{$roster_conf.imagepath}/char_page/minus.gif" alt="" onclick="collapseSkills({$skillinfo[skillid].id});" />
							{$skillinfo[skillid].name}</div>

{section loop=$skillbarinfo[skillid] name=skillbarid}

						<div class="char_skill_bar">
{if $skillbarinfo[skillid][skillbarid].max eq 1}
							<div style="position:absolute;"><img src="{$roster_conf.imagepath}/char_page/skillbar_grey.gif" alt="" /></div>
							<div class="char_skill_text">{$skillbarinfo[skillid][skillbarid].name}</div>
{else}
							<div style="position:absolute;clip:rect(0px {$skillbarinfo[skillid][skillbarid].image_width}px 15px 0px);"><img src="{$roster_conf.imagepath}/char_page/skillbar.gif" alt="" /></div>
							<div class="char_skill_text">{$skillbarinfo[skillid][skillbarid].name}<span class="char_skill_text_num">{$skillbarinfo[skillid][skillbarid].min}/{$skillbarinfo[skillid][skillbarid].max}</span></div>
{/if}
						</div>
{/section}

				</div>
{/section}

		</div>

	</div>

<!-- Begin Honor Tab -->
	<div id="tab5" class="char_tab" style="display:none;">
		<div class="CharHonorFrame">&nbsp;</div>
{if $data.honor_current_progress neq 0}
		<div class="CharHonorbar" style="clip:rect(0px {$honor_imagewidth}px 26px 0px);"><img src="{$roster_conf.imagepath}/char_page/honor_percentbar_{$faction_en|lower}.gif" style="width:308px;height:26px" alt="" /></div>
{/if}
{if $data.honor_current_rank neq 0}
		<div class="CharHonor_rank"><span style="font-size:12px;">{$data.honor_current_progress}%</span> <img src="{$honor_icon}.png" width="16" height="16" alt="" /> {$data.honor_current_name} <span style="font-size:12px;">({$roster_wordings[$data.locale].charpage_rank} {$data.honor_current_rank})</span></div>
{/if}
		<div class="CharH_group1_desc">{$roster_wordings[$data.locale].charpage_honor_today}</div>
		<div class="CharH_group1_line1_desc">{$roster_wordings[$data.locale].charpage_honor_honor_kills}</div>
		<div class="CharH_group1_line2_desc">{$roster_wordings[$data.locale].charpage_honor_dishonor_kills}</div>
		<div class="CharH_group1_line1">{$data.honor_session_hk}</div>
		<div class="CharH_group1_line2">{$data.honor_session_dk}</div>

		<div class="CharH_group2_desc">{$roster_wordings[$data.locale].charpage_honor_yesterday}</div>
		<div class="CharH_group2_line1_desc">{$roster_wordings[$data.locale].charpage_honor_honor_kills}</div>
		<div class="CharH_group2_line2_desc">{$roster_wordings[$data.locale].charpage_honor_dishonor_kills}</div>
		<div class="CharH_group2_line3_desc">{$roster_wordings[$data.locale].charpage_honor_honor}</div>
		<div class="CharH_group2_line1">{$data.honor_yesterday_hk}</div>
		<div class="CharH_group2_line2">{$data.honor_yesterday_dk}</div>
		<div class="CharH_group2_line3">{$data.honor_yesterday_contib}</div>

		<div class="CharH_group3_desc">{$roster_wordings[$data.locale].charpage_honor_this_week}</div>
		<div class="CharH_group3_line1_desc">{$roster_wordings[$data.locale].charpage_honor_honor_kills}</div>
		<div class="CharH_group3_line2_desc">{$roster_wordings[$data.locale].charpage_honor_honor}</div>
		<div class="CharH_group3_line1">{$data.honor_thisweek_hk}</div>
		<div class="CharH_group3_line2">{$data.honor_thisweek_contrib}</div>

		<div class="CharH_group4_desc">{$roster_wordings[$data.locale].charpage_honor_last_week}</div>
		<div class="CharH_group4_line1_desc">{$roster_wordings[$data.locale].charpage_honor_honor_kills}</div>
		<div class="CharH_group4_line2_desc">{$roster_wordings[$data.locale].charpage_honor_dishonor_kills}</div>
		<div class="CharH_group4_line3_desc">{$roster_wordings[$data.locale].charpage_honor_honor}</div>
		<div class="CharH_group4_line4_desc">{$roster_wordings[$data.locale].charpage_honor_standing}</div>
		<div class="CharH_group4_line1">{$data.honor_lastweek_hk}</div>
		<div class="CharH_group4_line2">{$data.honor_lastweek_dk}</div>
		<div class="CharH_group4_line3">{$data.honor_lastweek_contrib}</div>
		<div class="CharH_group4_line4">({$roster_wordings[$data.locale].charpage_rank} {$data.honor_lastweek_rank})</div>

		<div class="CharH_group5_desc">{$roster_wordings[$data.locale].charpage_honor_lifetime}</div>
		<div class="CharH_group5_line1_desc">{$roster_wordings[$data.locale].charpage_honor_honor_kills}</div>
		<div class="CharH_group5_line2_desc">{$roster_wordings[$data.locale].charpage_honor_dishonor_kills}</div>
		<div class="CharH_group5_line3_desc">{$roster_wordings[$data.locale].charpage_honor_highest_rank}</div>
		<div class="CharH_group5_line1">{$data.honor_lifetime_hk}</div>
		<div class="CharH_group5_line2">{$data.honor_lifetime_dk}</div>
		<div class="CharH_group5_line3">{$data.honor_lifetime_name} ({$roster_wordings[$data.locale].charpage_rank} {$data.honor_lifetime_rank})</div>

	</div>


	<div id="char_navagation">
		<ul>
			<li onclick="return displaypage('tab1',this);"><div class="text">{$roster_wordings[$data.locale].charpage_tab1}</div></li>
{if $show_pets}
			<li onclick="return displaypage('tab2',this);"><div class="text">{$roster_wordings[$data.locale].charpage_tab2}</div></li>
{/if}
			<li onclick="return displaypage('tab3',this);"><div class="text">{$roster_wordings[$data.locale].charpage_tab3}</div></li>
			<li onclick="return displaypage('tab4',this);"><div class="text">{$roster_wordings[$data.locale].charpage_tab4}</div></li>
			<li onclick="return displaypage('tab5',this);"><div class="text">{$roster_wordings[$data.locale].charpage_tab5}</div></li>
		</ul>
	</div>
</div>
