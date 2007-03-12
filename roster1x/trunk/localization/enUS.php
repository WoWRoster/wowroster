<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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





//Instructions how to upload, as seen on the mainpage
$lang['update_link']='Click here for Updating Instructions';
$lang['update_instructions']='Updating Instructions';

$lang['lualocation']='Click browse and select your *.lua files to upload';

$lang['filelocation']='is located at<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$lang['noGuild']='Could not find guild in database. Please update members first.';
$lang['nodata']="Could not find guild: <b>'".$roster_conf['guild_name']."'</b> for server <b>'".$roster_conf['server_name']."'</b><br />You need to <a href=\"".makelink('update')."\">load your guild</a> first and make sure you <a href=\"".makelink('rostercp')."\">finished configuration</a><br /><br /><a href=\"http://www.wowroster.net/wiki/index.php/Roster:Install\" target=\"_blank\">Click here for installation instructions</a>";
$lang['nodata_title']='No Guild Data';

$lang['update_page']='Update Profile';

$lang['guild_nameNotFound']='Could not update &quot;%s&quot;. Maybe its not set in configuration?';
$lang['guild_addonNotFound']='Could not find Guild. GuildProfiler Addon not installed correctly?';

$lang['ignored']='Ignored';
$lang['update_disabled']='Update.php access has been disabled';

$lang['nofileUploaded']='UniUploader did not upload any file(s), or uploaded the wrong file(s).';
$lang['roster_upd_pwLabel']='Roster Update Password';
$lang['roster_upd_pw_help']='(This is required when doing a guild update)';


$lang['roster_error'] = 'Roster Error';
$lang['sql_queries'] = 'SQL Queries';
$lang['invalid_char_module'] = 'Invalid characters in module name';
$lang['module_not_exist'] = 'The page [%1$s] does not exist';

$lang['addon_error'] = 'Addon Error';
$lang['specify_addon'] = 'You must specify an addon name!';
$lang['addon_not_exist'] = '<b>The addon [%1$s] does not exist!</b>';

$lang['char_error'] = 'Character Error';
$lang['specify_char'] = 'Character was not specified';
$lang['no_char_id'] = 'Sorry no character data for member_id [ %1$s ]';
$lang['no_char_name'] = 'Sorry no character data for <strong>%1$s</strong> of <strong>%2$s</strong>';
$lang['char_stats'] = 'Character Stats for: %1$s @ %2$s';
$lang['char_links'] = 'Character Links';

$lang['gbank_list'] = 'Full Listing';
$lang['gbank_inv'] = 'Inventory';
$lang['gbank_not_loaded'] = '<strong>%1$s</strong> has not uploaded an inventory yet';

$lang['roster_cp'] = 'Roster Control Panel';
$lang['roster_cp_not_exist'] = 'Page [%1$s] does not exist';
$lang['roster_cp_invalid'] = 'Invalid page specified or insufficient credentials to access this page';

$lang['parsing_files'] = 'Parsing files';
$lang['parsed_time'] = 'Parsed %1$s in %2$s seconds';
$lang['error_parsed_time'] = 'Error while parsing %1$s after %2$s seconds';
$lang['upload_not_accept'] = 'Did not accept %1$s';
$lang['not_updating'] = 'NOT Updating %1$s for [%2$s] - %3$s';
$lang['not_update_guild'] = 'NOT Updating Guild List for %1$s';
$lang['no_members'] = 'Data does not contain any guild members';
$lang['upload_data'] = 'Updating %1$s Data for [<span class="orange">%2$s</span>]';
$lang['realm_ignored'] = 'Realm: %1$s Not Scanned';
$lang['guild_realm_ignored'] = 'Guild: %1$s @ Realm: %2$s  Not Scanned';
$lang['update_members'] = 'Updating Members';
$lang['gp_user_only'] = 'GuildProfiler User Only';
$lang['update_errors'] = 'Update Errors';
$lang['update_log'] = 'Update Log';
$lang['save_error_log'] = 'Save Error Log';
$lang['save_update_log'] = 'Save Update Log';


// Updating Instructions
$lang['index_text_uniloader'] = "(You can download the program from the WoWRoster website, look for the UniUploader Installer for the latest version)";

$lang['update_instruct']='
<strong>Recommended automatic updaters:</strong>
<ul>
<li>Use <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$lang['index_text_uniloader'].'</li>
</ul>
<strong>Updating instructions:</strong>
<ol>
<li>Download <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a></li>
<li>Extract zip into its own directory in C:\Program Files\World of Warcraft\Interface\Addons\</li>
<li>Start WoW</li>
<li>Open your bank, quests, and the profession windows which contain recipes</li>
<li>Log out/Exit WoW (See above if you want to use the UniUploader to upload the data automatically for you.)</li>
<li>Go to <a href="'.makelink('update').'">the update page</a></li>
<li>'.$lang['lualocation'].'</li>
</ol>';

$lang['update_instructpvp']='
<strong>Optional PvP Stats:</strong>
<ol>
<li>Download the <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a></li>
<li>Extract the PvPLog dir into your Addon dir.</li>
<li>Duel or PvP</li>
<li>Upload PvPLog.lua</li>
</ol>';

$lang['roster_credits']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site.<br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.makelink('credits').'">Additional Credits</a>';


//Charset
$lang['charset']="charset=utf-8";

$lang['timeformat'] = '%a %b %D, %l:%i %p'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$lang['phptimeformat'] = 'D M jS, g:ia';    // PHP date() Time format (example - 'D M jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/**
 * Realmstatus Localizations
 */
$lang['rs'] = array(
	'ERROR' => 'Error',
	'NOSTATUS' => 'No Status',
	'UNKNOWN' => 'Unknown',
	'RPPVP' => 'RP-PvP',
	'PVE' => 'Normal',
	'PVP' => 'PvP',
	'RP' => 'RP',
	'OFFLINE' => 'Offline',
	'LOW' => 'Low',
	'MEDIUM' => 'Medium',
	'HIGH' => 'High',
	'MAX' => 'Max',
);


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$lang['inst_keys']['A'] = array(
	'SG' => array( 'Quests',
		'SG' =>	'Key to Searing Gorge|4826',
			'The Horn of the Beast|',
			'Proof of Deed|',
			'At Last!|'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Workshop Key|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'The Scarlet Key|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Mallet of Zul\\\'Farrak|5695',
			'Sacred Mallet|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Scepter of Celebras|19710',
			'Celebrian Rod|19549',
			'Celebrian Diamond|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Prison Cell Key|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Shadowforge Key|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Crescent Key|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skeleton Key|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Key to the City|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Seal of Ascension|17057',
			'Unadorned Seal of Ascension|5370',
			'Gemstone of Spirestone|5379',
			'Gemstone of Smolderthorn|16095',
			'Gemstone of Bloodaxe|21777',
			'Unforged Seal of Ascension|24554||MS',
			'Forged Seal of Ascension|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drakefire Amulet|4829',
			'Dragonkin Menace|',
			'The True Masters|',
			'Marshal Windsor|',
			'Abandoned Hope|',
			'A Crumpled Up Note|',
			'A Shred of Hope|',
			'Jail Break!|',
			'Stormwind Rendezvous|',
			'The Great Masquerade|',
			'The Dragon\\\'s Eye|',
			'Drakefire Amulet|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Eternal Quintessence|53490'
		),
);


// HORDE KEYS
$lang['inst_keys']['H'] = array(
	'SG' => array( 'Key-Only',
		'SG' => 'Key to Searing Gorge|4826'
		),
	'Gnome' => array( 'Key-Only',
		'Gnome' => 'Workshop Key|2288'
		),
	'SM' => array( 'Key-Only',
		'SM' => 'The Scarlet Key|4445'
		),
	'ZF' => array( 'Parts',
		'ZF' => 'Mallet of Zul\\\'Farrak|5695',
			'Sacred Mallet|8250'
		),
	'Mauro' => array( 'Parts',
		'Mauro' => 'Scepter of Celebras|19710',
			'Celebrian Rod|19549',
			'Celebrian Diamond|19545'
		),
	'BRDp' => array( 'Key-Only',
		'BRDp' => 'Prison Cell Key|15545'
		),
	'BRDs' => array( 'Parts',
		'BRDs' => 'Shadowforge Key|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only',
		'DM' => 'Crescent Key|35607'
		),
	'Scholo' => array( 'Quests',
		'Scholo' => 'Skeleton Key|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only',
		'Strath' => 'Key to the City|13146'
		),
	'UBRS' => array( 'Parts',
		'UBRS' => 'Seal of Ascension|17057',
			'Unadorned Seal of Ascension|5370',
			'Gemstone of Spirestone|5379',
			'Gemstone of Smolderthorn|16095',
			'Gemstone of Bloodaxe|21777',
			'Unforged Seal of Ascension|24554||MS',
			'Forged Seal of Ascension|19463||MS'
		),
	'Onyxia' => array( 'Quests',
		'Onyxia' => 'Drakefire Amulet|4829',
			'Warlord\\\'s Command|',
			'Eitrigg\\\'s Wisdom|',
			'For The Horde!|',
			'What the Wind Carries|',
			'The Champion of the Horde|',
			'The Testament of Rexxar|',
			'Oculus Illusions|',
			'Emberstrife|',
			'The Test of Skulls, Scryer|',
			'The Test of Skulls, Somnus|',
			'The Test of Skulls, Chronalis|',
			'The Test of Skulls, Axtroz|',
			'Ascension...|',
			'Blood of the Black Dragon Champion|'
		),
	'MC' => array( 'Key-Only',
		'MC' => 'Eternal Quintessence|22754'
		),
);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$lang['upload']='Upload';
$lang['required']='Required';
$lang['optional']='Optional';
$lang['attack']='Attack';
$lang['defense']='Defense';
$lang['class']='Class';
$lang['race']='Race';
$lang['level']='Level';
$lang['zone']='Last Zone';
$lang['note']='Note';
$lang['title']='Title';
$lang['name']='Name';
$lang['health']='Health';
$lang['mana']='Mana';
$lang['gold']='Gold';
$lang['armor']='Armor';
$lang['lastonline']='Last Online';
$lang['lastupdate']='Last Updated';
$lang['currenthonor']='Current Honor Rank';
$lang['rank']='Rank';
$lang['sortby']='Sort by %';
$lang['total']='Total';
$lang['hearthed']='Hearthed';
$lang['recipes']='Recipes';
$lang['bags']='Bags';
$lang['character']='Character';
$lang['bglog']='BG Log';
$lang['pvplog']='PvP Log';
$lang['duellog']='Duel Log';
$lang['duelsummary']='Duel Summary';
$lang['money']='Money';
$lang['bank']='Bank';
$lang['guildbank']='GuildBank';
$lang['guildbank_totalmoney']='Total bank holdings';
$lang['raid']='CT_Raid';
$lang['guildbankcontact']='Held By (Contact)';
$lang['guildbankitem']='Item Name and Description';
$lang['quests']='Quests';
$lang['roster']='Roster';
$lang['alternate']='Alternate';
$lang['byclass']='By Class';
$lang['menustats']='Stats';
$lang['menuhonor']='Honor';
$lang['keys']='Keys';
$lang['team']='Find Team';
$lang['search']='Search';
$lang['update']='Last updated';
$lang['credit']='Credits';
$lang['members']='Members';
$lang['items']='Items';
$lang['find']='Find item containing';
$lang['upprofile']='Update Profile';
$lang['backlink']='Back to the Roster';
$lang['gender']='Gender';
$lang['unusedtrainingpoints']='Unused Training Points';
$lang['unusedtalentpoints']='Unused Talent Points';
$lang['talentexport']='Export Talent Build';
$lang['questlog']='Quest Log';
$lang['recipelist']='Recipe List';
$lang['reagents']='Reagents';
$lang['item']='Item';
$lang['type']='Type';
$lang['date']='Date';
$lang['complete'] = 'Complete';
$lang['failed'] = 'Failed';
$lang['completedsteps'] = 'Completed Steps';
$lang['currentstep'] = 'Current Step';
$lang['uncompletedsteps'] = 'Uncompleted Steps';
$lang['key'] = 'Key';
$lang['timeplayed'] = 'Time Played';
$lang['timelevelplayed'] = 'Time Level Played';
$lang['Addon'] = 'Addons';
$lang['advancedstats'] = 'Advanced Stats';
$lang['itembonuses'] = 'Bonuses For Equipped Items';
$lang['itembonuses2'] = 'Item Bonuses';
$lang['crit'] = 'Crit';
$lang['dodge'] = 'Dodge';
$lang['parry'] = 'Parry';
$lang['block'] = 'Block';
$lang['realm'] = 'Realm';
$lang['talents'] = 'Talents';

// Memberlog
$lang['memberlog'] = 'Member Log';
$lang['removed'] = 'Removed';
$lang['added'] = 'Added';
$lang['updated'] = 'Updated';
$lang['no_memberlog'] = 'No Member Log Recorded';

$lang['rosterdiag'] = 'Roster Diag.';
$lang['Guild_Info'] = 'Guild Info';
$lang['difficulty'] = 'Difficulty';
$lang['recipe_4'] = 'optimal';
$lang['recipe_3'] = 'medium';
$lang['recipe_2'] = 'easy';
$lang['recipe_1'] = 'trivial';
$lang['roster_config'] = 'Roster Config';

// Character
$lang['char_level_race_class'] = 'Level %1$s %2$s %3$s';
$lang['char_guildline'] = '%1$s of %2$s';

// Spellbook
$lang['spellbook'] = 'Spellbook';
$lang['page'] = 'Page';
$lang['general'] = 'General';
$lang['prev'] = 'Prev';
$lang['next'] = 'Next';
$lang['no_spellbook'] = 'No Spellbook for %1$s';

// Mailbox
$lang['mailbox'] = 'Mailbox';
$lang['maildateutc'] = 'Mail Captured';
$lang['mail_item'] = 'Item';
$lang['mail_sender'] = 'Sender';
$lang['mail_subject'] = 'Subject';
$lang['mail_expires'] = 'Mail Expires';
$lang['mail_money'] = 'Money Included';
$lang['no_mail'] = 'No Mail for %1$s';
$lang['no_info'] = 'No Information';


//this needs to be exact as it is the wording in the db
$lang['professions']='Professions';
$lang['secondary']='Secondary Skills';
$lang['Blacksmithing']='Blacksmithing';
$lang['Mining']='Mining';
$lang['Herbalism']='Herbalism';
$lang['Alchemy']='Alchemy';
$lang['Leatherworking']='Leatherworking';
$lang['Jewelcrafting']='Jewelcrafting';
$lang['Skinning']='Skinning';
$lang['Tailoring']='Tailoring';
$lang['Enchanting']='Enchanting';
$lang['Engineering']='Engineering';
$lang['Cooking']='Cooking';
$lang['Fishing']='Fishing';
$lang['First Aid']='First Aid';
$lang['Poisons']='Poisons';
$lang['backpack']='Backpack';
$lang['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$lang['requires_level'] = '/Requires Level ([\d]+)/';

//Tradeskill-Array
$lang['tsArray'] = array (
	$lang['Alchemy'],
	$lang['Herbalism'],
	$lang['Blacksmithing'],
	$lang['Mining'],
	$lang['Leatherworking'],
	$lang['Jewelcrafting'],
	$lang['Skinning'],
	$lang['Tailoring'],
	$lang['Enchanting'],
	$lang['Engineering'],
	$lang['Cooking'],
	$lang['Fishing'],
	$lang['First Aid'],
	$lang['Poisons'],
);

//Tradeskill Icons-Array
$lang['ts_iconArray'] = array (
	$lang['Alchemy']=>'Trade_Alchemy',
	$lang['Herbalism']=>'Trade_Herbalism',
	$lang['Blacksmithing']=>'Trade_BlackSmithing',
	$lang['Mining']=>'Trade_Mining',
	$lang['Leatherworking']=>'Trade_LeatherWorking',
	$lang['Jewelcrafting']=>'INV_Misc_Gem_02',
	$lang['Skinning']=>'INV_Misc_Pelt_Wolf_01',
	$lang['Tailoring']=>'Trade_Tailoring',
	$lang['Enchanting']=>'Trade_Engraving',
	$lang['Engineering']=>'Trade_Engineering',
	$lang['Cooking']=>'INV_Misc_Food_15',
	$lang['Fishing']=>'Trade_Fishing',
	$lang['First Aid']=>'Spell_Holy_SealOfSacrifice',
	$lang['Poisons']=>'Ability_Poisons',
	'Tiger Riding'=>'Ability_Mount_WhiteTiger',
	'Horse Riding'=>'Ability_Mount_RidingHorse',
	'Ram Riding'=>'Ability_Mount_MountainRam',
	'Mechanostrider Piloting'=>'Ability_Mount_MechaStrider',
	'Undead Horsemanship'=>'Ability_Mount_Undeadhorse',
	'Raptor Riding'=>'Ability_Mount_Raptor',
	'Kodo Riding'=>'Ability_Mount_Kodo_03',
	'Wolf Riding'=>'Ability_Mount_BlackDireWolf',
);

// Riding Skill Icons-Array
$lang['riding'] = 'Riding';
$lang['ts_ridingIcon'] = array(
	'Night Elf'=>'Ability_Mount_WhiteTiger',
	'Human'=>'Ability_Mount_RidingHorse',
	'Dwarf'=>'Ability_Mount_MountainRam',
	'Gnome'=>'Ability_Mount_MechaStrider',
	'Undead'=>'Ability_Mount_Undeadhorse',
	'Troll'=>'Ability_Mount_Raptor',
	'Tauren'=>'Ability_Mount_Kodo_03',
	'Orc'=>'Ability_Mount_BlackDireWolf',
	'Blood Elf' => 'Ability_Mount_CockatriceMount',
	'Draenei' => 'Ability_Mount_RidingElekk',
	'Paladin'=>'Ability_Mount_Dreadsteed',
	'Warlock'=>'Ability_Mount_NightmareHorse'
);

// Class Icons-Array
$lang['class_iconArray'] = array (
	'Druid'=>'Ability_Druid_Maul',
	'Hunter'=>'INV_Weapon_Bow_08',
	'Mage'=>'INV_Staff_13',
	'Paladin'=>'Spell_Fire_FlameTounge',
	'Priest'=>'Spell_Holy_LayOnHands',
	'Rogue'=>'INV_ThrowingKnife_04',
	'Shaman'=>'Spell_Nature_BloodLust',
	'Warlock'=>'Spell_Shadow_Cripple',
	'Warrior'=>'INV_Sword_25',
);

//skills
$lang['skilltypes'] = array(
	1 => 'Class Skills',
	2 => 'Professions',
	3 => 'Secondary Skills',
	4 => 'Weapon Skills',
	5 => 'Armor Proficiencies',
	6 => 'Languages'
);

//tabs
$lang['tab1']='Char';
$lang['tab2']='Pet';
$lang['tab3']='Rep';
$lang['tab4']='Skills';
$lang['tab5']='PvP';

$lang['strength']='Strength';
$lang['strength_tooltip']='Increases your attack power with melee Weapons.<br />Increases the amount of damage you can block with a shield.';
$lang['agility']='Agility';
$lang['agility_tooltip']= 'Increases your attack power with ranged weapons.<br />Improves your chance to score a critcal hit with all weapons.<br />Increases your armor and your chance to dodge attacks.';
$lang['stamina']='Stamina';
$lang['stamina_tooltip']= 'Increases your health points.';
$lang['intellect']='Intellect';
$lang['intellect_tooltip']= 'Increases your mana points and your chance to score a critical hit with spells.<br />Increases the rate at which you improve weapon skills.';
$lang['spirit']='Spirit';
$lang['spirit_tooltip']= 'Increases your health and mana regeneration rates.';
$lang['armor_tooltip']= 'Decreases the amount of damage you take from physical attacks.<br />The amount of reduction is influenced by the level of your attacker.';

$lang['mainhand']='Main Hand';
$lang['offhand']='Off Hand';
$lang['ranged']='Ranged';
$lang['melee']='Melee';
$lang['spell']='Spell';

$lang['weapon_skill']='Skill';
$lang['weapon_skill_tooltip']='Weapon Skill %d<br />Weapon Skill Rating %d';
$lang['damage']='Damage';
$lang['damage_tooltip']='<table><tr><td>Attack speed (seconds):<td>%.2f<tr><td>Damage:<td>%d-%d<tr><td>Damage per second:<td>%.1f</table>';
$lang['speed']='Speed';
$lang['atk_speed']='Attack Speed';
$lang['haste_tooltip']='Haste rating ';

$lang['melee_att_power']='Melee Attack Power';
$lang['melee_att_power_tooltip']='Increases damage with melee weapons by %.1f damage per second.';
$lang['ranged_att_power']='Ranged Attack Power';
$lang['ranged_att_power_tooltip']='Increases damage with ranged weapons by %.1f damage per second.';

$lang['weapon_hit_rating']='Hit Rating';
$lang['weapon_hit_rating_tooltip']='Increases your chance to hit an enemy.';
$lang['weapon_crit_rating']='Crit rating';
$lang['weapon_crit_rating_tooltip']='Critical strike chance %.2f%%.';

$lang['damage']='Damage';
$lang['energy']='Energy';
$lang['rage']='Rage';
$lang['power']='Power';

$lang['melee_rating']='Melee Attack Rating';
$lang['melee_rating_tooltip']='Your attack rating affects your chance to hit a target<br />And is based on the weapon skill of the weapon you are currently holding.';
$lang['range_rating']='Ranged Attack Rating';
$lang['range_rating_tooltip']='Your attack rating affects your chance to hit a target<br />And is based on the weapon skill of the weapon you are currently weilding.';

$lang['spell_damage']='+Damage';
$lang['fire']='Fire';
$lang['nature']='Nature';
$lang['frost']='Frost';
$lang['shadow']='Shadow';
$lang['arcane']='Arcane';

$lang['spell_healing']='+Healing';
$lang['spell_healing_tooltip']='Increases your healing by up to %d';
$lang['spell_hit_rating']='Hit Rating';
$lang['spell_hit_rating_tooltip']='Increases your chance to hit an enemy with your spells.';
$lang['spell_crit_rating']='Crit Rating';
$lang['spell_crit_chance']='Crit Chance';
$lang['spell_penetration']='Penetration';
$lang['spell_penetration_tooltip']='Reduces the target\'s resistance to your spells';
$lang['mana_regen']='Mana Regen';
$lang['mana_regen_tooltip']='%d mana regenerated every %d seconds while not casting';

$lang['defense_rating']='Defense Rating ';
$lang['def_tooltip']='Increases your chance to %s';
$lang['resilience']='Resilience';

$lang['res_fire']='Fire Resistance';
$lang['res_fire_tooltip']='Increases your resistance to Fire damage.<br />Higher the number the better the resistance.';
$lang['res_nature']='Nature Resistance';
$lang['res_nature_tooltip']='Increases your resistance to Nature damage.<br />Higher the number the better the resistance.';
$lang['res_arcane']='Arcane Resistance';
$lang['res_arcane_tooltip']='Increases your resistance to Arcane damage.<br />Higher the number the better the resistance.';
$lang['res_frost']='Frost Resistance';
$lang['res_frost_tooltip']='Increases your resistance to Frost damage.<br />Higher the number the better the resistance.';
$lang['res_shadow']='Shadow Resistance';
$lang['res_shadow_tooltip']='Increases your resistance to Shadow damage.<br />Higher the number the better the resistance.';

$lang['empty_equip']='No item equipped';
$lang['pointsspent']='Points Spent in';
$lang['none']='None';

$lang['pvplist']=' PvP Stats';
$lang['pvplist1']='Guild that suffered most at our hands';
$lang['pvplist2']='Guild that killed us the most';
$lang['pvplist3']='Player who we killed the most';
$lang['pvplist4']='Player who killed us the most';
$lang['pvplist5']='Member with the most kills';
$lang['pvplist6']='Member who has died the most';
$lang['pvplist7']='Member with best kill average';
$lang['pvplist8']='Member with best loss average';

$lang['hslist']=' Honor System Stats';
$lang['hslist1']='Highest Lifetime Rank';
$lang['hslist2']='Highest Lifetime HKs';
$lang['hslist3']='Most Honor Points';
$lang['hslist4']='Most Arena Points';

$lang['Druid']='Druid';
$lang['Hunter']='Hunter';
$lang['Mage']='Mage';
$lang['Paladin']='Paladin';
$lang['Priest']='Priest';
$lang['Rogue']='Rogue';
$lang['Shaman']='Shaman';
$lang['Warlock']='Warlock';
$lang['Warrior']='Warrior';

$lang['today']='Today';
$lang['todayhk']='Today HK';
$lang['todaycp']='Today CP';
$lang['yesterday']='Yesterday';
$lang['yesthk']='Yest HK';
$lang['yestcp']='Yest CP';
$lang['thisweek']='This Week';
$lang['lastweek']='Last Week';
$lang['lifetime']='Lifetime';
$lang['lifehk']='Life HK';
$lang['honorkills']='Honorable Kills';
$lang['dishonorkills']='Dishonorable Kills';
$lang['honor']='Honor';
$lang['standing']='Standing';
$lang['highestrank']='Highest Rank';
$lang['arena']='Arena';

$lang['totalwins']='Total Wins';
$lang['totallosses']='Total Losses';
$lang['totaloverall']='Total Overall';
$lang['win_average']='Average Level Diff (Wins)';
$lang['loss_average']='Average Level Diff (Losses)';

// These need to be EXACTLY what PvPLog stores them as
$lang['alterac_valley']='Alterac Valley';
$lang['arathi_basin']='Arathi Basin';
$lang['warsong_gulch']='Warsong Gulch';

$lang['world_pvp']='World PvP';
$lang['versus_guilds']='Versus Guilds';
$lang['versus_players']='Versus Players';
$lang['bestsub']='Best Subzone';
$lang['worstsub']='Worst Subzone';
$lang['killedmost']='Killed Most';
$lang['killedmostby']='Killed Most By';
$lang['gkilledmost']='Guild Killed Most';
$lang['gkilledmostby']='Guild Killed Most By';

$lang['wins']='Wins';
$lang['losses']='Losses';
$lang['overall']='Overall';
$lang['best_zone']='Best Zone';
$lang['worst_zone']='Worst Zone';
$lang['most_killed']='Most Killed';
$lang['most_killed_by']='Most Killed By';

$lang['when']='When';
$lang['guild']='Guild';
$lang['leveldiff']='LevelDiff';
$lang['result']='Result';
$lang['zone2']='Zone';
$lang['subzone']='Subzone';
$lang['bg']='Battleground';
$lang['yes']='Yes';
$lang['no']='No';
$lang['win']='Win';
$lang['loss']='Loss';
$lang['kills']='Kills';
$lang['unknown']='Unknown';

// guildpvp strings
$lang['guildwins'] = 'Wins by Guild';
$lang['guildlosses'] = 'Losses by Guild';
$lang['enemywins'] = 'Wins by Enemy';
$lang['enemylosses'] = 'Losses by Enemy';
$lang['purgewins'] = 'Guild Member Kills';
$lang['purgelosses'] = 'Guild Member Deaths';
$lang['purgeavewins'] = 'Best Win/Level-Diff Average';
$lang['purgeavelosses'] = 'Best Loss/Level-Diff Average';
$lang['pvpratio'] = 'Solo Win/Loss Ratios';
$lang['playerinfo'] = 'Player Info';
$lang['guildinfo'] = 'Guild Info';
$lang['kill_lost_hist'] = 'Kill/Loss history for %1$s (%2$s %3$s) of %4$s';
$lang['kill_lost_hist_guild'] = 'Kill/Loss history for Guild &quot;%1$s&quot;';
$lang['solo_win_loss'] = 'Solo Win/Loss Ratios (Level differences -7 to +7 counted)';

//strings for Rep-tab
$lang['exalted']='Exalted';
$lang['revered']='Revered';
$lang['honored']='Honored';
$lang['friendly']='Friendly';
$lang['neutral']='Neutral';
$lang['unfriendly']='Unfriendly';
$lang['hostile']='Hostile';
$lang['hated']='Hated';
$lang['atwar']='At War';
$lang['notatwar']='Not at War';

// language definitions for the rogue instance keys 'fix'
$lang['thievestools']='Thieves\\\' Tools';
$lang['lockpicking']='Lockpicking';
// END

	// Quests page external links (on character quests page)
		// $lang['questlinks'][#]['name']  This is the name displayed on the quests page
		// $lang['questlinks'][#]['url#']  This is the URL used for the quest lookup

		$lang['questlinks'][0]['name']='Thottbot';
		$lang['questlinks'][0]['url1']='http://www.thottbot.com/?f=q&amp;title=';
		$lang['questlinks'][0]['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$lang['questlinks'][0]['url3']='&amp;maxl=';

		$lang['questlinks'][1]['name']='Allakhazam';
		$lang['questlinks'][1]['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
		$lang['questlinks'][1]['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$lang['questlinks'][1]['url3']='&amp;maxl=';

		$lang['questlinks'][2]['name']='WWN Data';
		$lang['questlinks'][2]['url1']='http://wwndata.worldofwar.net/search.php?q=on&amp;search=';
		//$lang['questlinks'][2]['url2']='&amp;levelmin=';
		//$lang['questlinks'][2]['url3']='&amp;levelmax=';

		$lang['questlinks'][3]['name']='WoWHead';
		$lang['questlinks'][3]['url1']='http://www.wowhead.com/?quests&amp;filter=ti=';
		$lang['questlinks'][3]['url2']=';minle=';
		$lang['questlinks'][3]['url3']=';maxle=';

// Items external link
// Add as many item links as you need
// Just make sure their names are unique
	$lang['itemlink'] = 'Item Links';
	$lang['itemlinks']['Thottbot'] = 'http://www.thottbot.com/index.cgi?i=';
	$lang['itemlinks']['Allakhazam'] = 'http://wow.allakhazam.com/search.html?q=';
	$lang['itemlinks']['WWN Data'] = 'http://wwndata.worldofwar.net/search.php?search=';
	$lang['itemlinks']['WoWHead'] = 'http://www.wowhead.com/?items&amp;filter=na=';


// definitions for the questsearchpage
	$lang['search1']="From the list below choose a zone or a quest name to see who is working it.<br />\n<small>Note that if the quest level is not the same for all listed guildmembers, they may be on another part of a multi-chain quest.</small>";
	$lang['search2']='Search by Zone';
	$lang['search3']='Search by Quest Name';

// Definition for item tooltip coloring
	$lang['tooltip_use']='Use:';
	$lang['tooltip_requires']='Requires';
	$lang['tooltip_reinforced']='Reinforced';
	$lang['tooltip_soulbound']='Soulbound';
	$lang['tooltip_boe']='Binds when equipped';
	$lang['tooltip_equip']='Equip:';
	$lang['tooltip_equip_restores']='Equip: Restores';
	$lang['tooltip_equip_when']='Equip: When';
	$lang['tooltip_chance']='Chance';
	$lang['tooltip_enchant']='Enchant';
	$lang['tooltip_set']='Set';
	$lang['tooltip_rank']='Rank';
	$lang['tooltip_next_rank']='Next rank';
	$lang['tooltip_spell_damage']='Spell Damage';
	$lang['tooltip_school_damage']='\\+.*Spell Damage';
	$lang['tooltip_healing_power']='Healing Power';
	$lang['tooltip_chance_hit']='Chance to hit:';
	$lang['tooltip_reinforced_armor']='Reinforced Armor';
	$lang['tooltip_damage_reduction']='Damage Reduction';

// Warlock pet names for icon displaying
	$lang['Imp']='Imp';
	$lang['Voidwalker']='Voidwalker';
	$lang['Succubus']='Succubus';
	$lang['Felhunter']='Felhunter';
	$lang['Infernal']='Infernal';
	$lang['Felguard']='Felguard';

// Max experiance for exp bar on char page
	$lang['max_exp']='Max XP';

// Error messages
	$lang['CPver_err']="The version of CharacterProfiler used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minCPver']." and have logged onto this character and saved data using this version.";
	$lang['PvPLogver_err']="The version of PvPLog used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minPvPLogver'].", and if you have just updated your PvPLog, ensure you deleted your old PvPLog.lua SavedVariable file prior to updating.";
	$lang['GPver_err']="The version of GuildProfiler used to capture data for this guild is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minGPver'];


$lang['installer_install_0']='Installation of %1$s successful';
$lang['installer_install_1']='Installation of %1$s failed, but rollback successful';
$lang['installer_install_2']='Installation of %1$s failed, and rollback also failed';
$lang['installer_uninstall_0']='Uninstallation of %1$s successful';
$lang['installer_uninstall_1']='Uninstallation of %1$s failed, but rollback successful';
$lang['installer_uninstall_2']='Uninstallation of %1$s failed, and rollback also failed';
$lang['installer_upgrade_0']='Upgrade of %1$s successful';
$lang['installer_upgrade_1']='Upgrade of %1$s failed, but rollback successful';
$lang['installer_upgrade_2']='Upgrade of %1$s failed, and rollback also failed';

$lang['installer_icon'] = 'Icon';
$lang['installer_addoninfo'] = 'Addon Info';
$lang['installer_status'] = 'Status';
$lang['installer_installation'] = 'Installation';
$lang['installer_author'] = 'Author';
$lang['installer_log'] = 'Addon Manager Log';
$lang['installer_activated'] = 'Activated';
$lang['installer_deactivated'] = 'Deactivated';
$lang['installer_installed'] = 'Installed';
$lang['installer_upgrade_avail'] = 'Upgrade Available';
$lang['installer_not_installed'] = 'Not Installed';

$lang['installer_turn_off'] = 'Click to Deactivate';
$lang['installer_turn_on'] = 'Click to Activate';
$lang['installer_click_uninstall'] = 'Click to Uninstall';
$lang['installer_click_upgrade'] = 'Click to Upgrade';
$lang['installer_click_install'] = 'Click to Install';
$lang['installer_overwrite'] = 'Old Version Overwrite';
$lang['installer_replace_files'] = 'Replace files with latest version';

$lang['installer_error'] = 'Install Errors';
$lang['installer_invalid_type'] = 'Invalid install type';
$lang['installer_no_success_sql'] = 'Queries were not successfully added to the installer';
$lang['installer_no_class'] = 'The install definition file for %1$s did not contain a correct installation class';
$lang['installer_no_installdef'] = 'install.def.php for %1$s was not found';

$lang['installer_no_empty'] = 'Cannot install with an empty addon name';
$lang['installer_fetch_failed'] = 'Failed to fetch addon data for %1$s';
$lang['installer_addon_exist'] = '%1$s already contains %2$s. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different name';
$lang['installer_no_upgrade'] = '%1$s doesn\`t contain data to upgrade from';
$lang['installer_not_upgradable'] = '%1$s cannot upgrade %2$s since its basename %3$s isn\'t in the list of upgradable addons';
$lang['installer_no_uninstall'] = '%1$s doesn\'t contain an addon to uninstall';
$lang['installer_not_uninstallable'] = '%1$s contains an addon %2$s which must be uninstalled with that addons\' uninstaller';


/******************************
 * Roster Admin Strings
 ******************************/

$lang['pagebar_function'] = 'Function';
$lang['pagebar_rosterconf'] = 'Configure Main Roster';
$lang['pagebar_charpref'] = 'Character Preferences';
$lang['pagebar_changepass'] = 'Change Password';
$lang['pagebar_addoninst'] = 'Manage Addons';
$lang['pagebar_update'] = 'Upload Profile';
$lang['pagebar_rosterdiag'] = 'Roster Diag';
$lang['pagebar_menuconf'] = 'Menu configuration';

$lang['pagebar_addonconf'] = 'Addon Config';

$lang['roster_config_menu'] = 'Config Menu';

// Submit/Reset confirm questions
$lang['config_submit_button'] = 'Save Settings';
$lang['config_reset_button'] = 'Reset';
$lang['confirm_config_submit'] = 'This will save the changes to the database. Are you sure?';
$lang['confirm_config_reset'] = 'This will reset the form to how it was when you loaded it. Are you sure?';

// All strings here
// Each variable must be the same name as the config variable name
// Example:
//   Assign description text and tooltip for $roster_conf['sqldebug']
//   $lang['admin']['sqldebug'] = "Desc|Tooltip";

// Each string is separated by a pipe ( | )
// The first part is the short description, the next part is the tooltip
// Use <br /> to make new lines!
// Example:
//   "Controls Flux-Capacitor|Turning this on may cause serious temporal distortions<br />Use with care"


// Main Menu words
$lang['admin']['main_conf'] = 'Main Settings|Roster\'s main settings<br>Including roster URL, Interface Images URL, and other core options';
$lang['admin']['guild_conf'] = 'Guild Config|Set up your guild info<ul><li>Guild name</li><li>Realm name (server)</li><li>Short guild description</li><li>Server type</li><li>etc...</li></ul>';
$lang['admin']['index_conf'] = 'Index Page|Options for what shows on the Main Page';
$lang['admin']['menu_conf'] = 'Menu|Control what is displayed in the Roster Main Menu';
$lang['admin']['display_conf'] = 'Display Config|Misc display settings<br>css, javascript, motd, etc...';
$lang['admin']['char_conf'] = 'Character Page|Control what is displayed in the Character pages';
$lang['admin']['realmstatus_conf'] = 'Realmstatus|Options for Realmstatus<br><br>To turn this off, look in the Menu section';
$lang['admin']['guildbank_conf'] = 'Guildbank|Set up your guildbank display and characters';
$lang['admin']['data_links'] = 'Item/Quest Data Links|External links for item and quest data';
$lang['admin']['update_access'] = 'Update Access|Set access levels for rostercp components';

$lang['admin']['documentation'] = 'Documentation|WoWRoster Documentation via the wowroster.net wiki';

// main_conf
$lang['admin']['roster_upd_pw'] = "Roster Update Password|This is a password to allow guild updates on the update page<br />Some addons may also use this password";
$lang['admin']['roster_dbver'] = "Roster Database Version|The version of the database";
$lang['admin']['version'] = "Roster Version|Current version of Roster";
$lang['admin']['sqldebug'] = "SQL Debug Output|Print MySQL Debug Statements in html comments";
$lang['admin']['debug_mode'] = "Debug Mode|Full debug trace in error messages";
$lang['admin']['sql_window'] = "SQL Window|Displays SQL Queries in a window in the footer";
$lang['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler version allowed to upload";
$lang['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler version allowed to upload";
$lang['admin']['minPvPLogver'] = "Min PvPLog version|Minimum PvPLog version allowed to upload";
$lang['admin']['roster_lang'] = "Roster Main Language|The main language roster will be displayed in";
$lang['admin']['default_page'] = "Default Page|Page to display if no page is specified in the url";
$lang['admin']['website_address'] = "Website Address|Used for url link for logo, and guildname link in the main menu<br />Some roster addons may also use this";
$lang['admin']['roster_dir'] = "Roster URL|The URL path to the Roster directory<br />It is critical that this is correct or errors may occur<br />(EX: http://www.site.com/roster )<br /><br />A full url is not required but a foreward slash before the directory is<br />(EX: /roster )";
$lang['admin']['interface_url'] = "Interface Directory URL|Directory that the Interface images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$lang['admin']['img_suffix'] = "Interface Image Extension|The image type of the Interface images";
$lang['admin']['alt_img_suffix'] = "Alt Interface Image Extension|The alternate possible image type of the Interface images";
$lang['admin']['img_url'] = "Roster Images Directory URL|Directory that Roster's images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$lang['admin']['timezone'] = "Timezone|Displayed after timestamps so people know what timezone the time references are in";
$lang['admin']['localtimeoffset'] = "Time Offest|The timezone offset from UTC/GMT<br />Times on roster will be displayed as a calculated value using this offset";
$lang['admin']['pvp_log_allow'] = "Allow upload of PvPLog Data|Changing this to &quot;no&quot; will disable the PvPLog upload field in &quot;update&quot;";
$lang['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers are for addons that need to run during a character or guild update<br />Some addons my require that this is turned on for them to function properly";

// guild_conf
$lang['admin']['guild_name'] = "Guild Name|This must be spelled exactly as it is in the game<br />or you <u>WILL</u> <u>NOT</u> be able to upload profiles";
$lang['admin']['server_name'] = "Server Name|This must be spelled exactly as it is in the game<br />or you <u>WILL</u> <u>NOT</u> be able to upload profiles";
$lang['admin']['guild_desc'] = "Guild Description|Enter a short Guild Description";
$lang['admin']['server_type'] = "Server Type|This for your type of server in WoW";
$lang['admin']['alt_type'] = "Alt-Text Search|Text used to designate alts for display count in the main menu";
$lang['admin']['alt_location'] = "Alt-Search Field|Search location, what field to search for Alt-Text";

// index_conf
$lang['admin']['index_pvplist'] = "PvP-Logger Stats|PvP-Logger stats on the index page<br />If you have disabled PvPlog uploading, there is no need to have this on";
$lang['admin']['index_hslist'] = "Honor System Stats|Honor System stats on the index page";
$lang['admin']['hspvp_list_disp'] = "PvP/Honor List Display|Controls how the PvP and Honor Lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$lang['admin']['index_member_tooltip'] = "Member Info Tooltip|Displays some info about a character in a tooltip";
$lang['admin']['index_update_inst'] = "Update Instructions|Controls the display of the Update Instructions on the page";
$lang['admin']['index_sort'] = "Member List Sort|Controls the default sorting";
$lang['admin']['index_motd'] = "Guild MOTD|Show Guild Message of the Day on the top of the page<br /><br />This also controls the display on the &quot;Guild Info&quot; page as well";
$lang['admin']['index_level_bar'] = "Level Bar|Toggles the display of a visual level percentage bar on the main page";
$lang['admin']['index_iconsize'] = "Icon Size|Select the size of the icons on the main pages (PvP, tradeskills, class, etc..)";
$lang['admin']['index_tradeskill_icon'] = "Tradeskill Icons|Enables tradeskill icons on the main pages";
$lang['admin']['index_tradeskill_loc'] = "Tradeskill Column Display|Select what column to place tradeskill icons";
$lang['admin']['index_class_color'] = "Class Colorizing|Colorize the class names";
$lang['admin']['index_classicon'] = "Class Icons|Displays an icon for each class, for each character";
$lang['admin']['index_honoricon'] = "PvP Honor Icons|Displays a PvP rank icon next to the rank name";
$lang['admin']['index_prof'] = "Professions Column|This is a specific coulmn for the tradeskill icons<br />If you move them to another column, you might want to turn this off";
$lang['admin']['index_currenthonor'] = "Honor Column|Toggles the display of the honor column";
$lang['admin']['index_note'] = "Note Column|Toggles the display of the public note column";
$lang['admin']['index_title'] = "Guild Title Column|Toggles the display of the guild title column";
$lang['admin']['index_hearthed'] = "Hearthstone Loc. Column|Toggles the display of the hearthstone location column";
$lang['admin']['index_zone'] = "Last Zone Loc. Column|Toggles the display of the last zone column";
$lang['admin']['index_lastonline'] = "Last Seen Online Column|Toggles the display of the last seen online column";
$lang['admin']['index_lastupdate'] = "Last Updated Column|Display when the character last updated their info";

// menu_conf
$lang['admin']['menu_left_pane'] = "Left Pane (Member Quick List)|Controls display of the left pane of the main roster menu<br />This area holds the member quick list";
$lang['admin']['menu_right_pane'] = "Right Pane (Realmstatus)|Controls display of the right pane of the main roster menu<br />This area holds the realmstatus image";
$lang['admin']['menu_memberlog'] = "Member Log Link|Controls display of the Member Log Link";
$lang['admin']['menu_member_page'] = "MemberList Link|Controls display of the MemberList Link";
$lang['admin']['menu_guild_info'] = "Guild-Info Link|Controls display of the Guild-Info Link";
$lang['admin']['menu_stats_page'] = "Basic Stats Link|Controls display of the Basic Stats Link";
$lang['admin']['menu_pvp_page'] = "PvPLog Stats Link|Controls display of the PvPLog Stats Link";
$lang['admin']['menu_honor_page'] = "Honor Page Link|Controls display of the Honor Page Link";
$lang['admin']['menu_guildbank'] = "Guildbank Link|Controls display of the Guildbank Link";
$lang['admin']['menu_keys_page'] = "Instance Keys Link|Controls display of the Instance Keys Link";
$lang['admin']['menu_tradeskills_page'] = "Professions Link|Controls display of the Professions Link";
$lang['admin']['menu_update_page'] = "Profile Update Link|Controls display of the Profile Update Link";
$lang['admin']['menu_quests_page'] = "Find Team/Quests Link|Controls display of the Find Team/Quests Link";
$lang['admin']['menu_search_page'] = "Search Page Link|Controls display of the Search Page Link";

// display_conf
$lang['admin']['stylesheet'] = "CSS Stylesheet|CSS stylesheet for roster";
$lang['admin']['roster_js'] = "Roster JS File|Main Roster JavaScript file location";
$lang['admin']['tabcontent'] = "Dynamic Tab JS File|JavaScript file location for dynamic tab menus";
$lang['admin']['overlib'] = "Tooltip JS File|Tooltip JavaScript file location";
$lang['admin']['overlib_hide'] = "Overlib JS Fix|JavaScript file location of fix for Overlib in Internet Explorer";
$lang['admin']['logo'] = "URL for header logo|The full URL to the image<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$lang['admin']['roster_bg'] = "URL for background image|The full URL to the image used for the main background<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$lang['admin']['motd_display_mode'] = "MOTD Display Mode|How the MOTD will be displayed<br /><br />&quot;Text&quot; - Shows MOTD in red text<br />&quot;Image&quot; - Shows MOTD as an image (REQUIRES GD!)";
$lang['admin']['compress_note'] = "Note Display Mode|How the Player Notes will be displayed<br /><br />&quot;Text&quot; - Shows the note text<br />&quot;Icon&quot; - Shows an note icon with the text in a tooltip";
$lang['admin']['signaturebackground'] = "img.php Background|Support for legacy signature-creator";
$lang['admin']['processtime'] = "Process time|Displays &quot;<i>This page was created in XXX seconds with XX queries executed</i>&quot; in the footer of roster";

// data_links
$lang['admin']['questlink_1'] = "Quest Link #1|Item external links<br />Look in your localization-file(s) for link configuration";
$lang['admin']['questlink_2'] = "Quest Link #2|Item external links<br />Look in your localization-file(s) for link configuration";
$lang['admin']['questlink_3'] = "Quest Link #3|Item external links<br />Look in your localization-file(s) for link configuration";
$lang['admin']['profiler'] = "CharacterProfiler download link|URL to download CharacterProfiler";
$lang['admin']['pvplogger'] = "PvPLog download link|URL to download PvPLog";
$lang['admin']['uploadapp'] = "UniUploader download link|URL to download UniUploader";

// char_conf
$lang['admin']['char_bodyalign'] = "Character Page Alignment|Alignment of the data on the character page";
$lang['admin']['recipe_disp'] = "Recipe Display|Controls how the recipe lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$lang['admin']['show_talents'] = "Talents|Controls the display of Talents<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_spellbook'] = "Spellbook|Controls the display of the Spellbook<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_mail'] = "Mail|Controls the display of Mail<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_inventory'] = "Bags|Controls the display of Bags<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_money'] = "Money|Controls the display of Money<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bank'] = "Bank|Controls the display of Bank contents<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_recipes'] = "Recipes|Controls the display of Recipes<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_quests'] = "Quests|Controls the display of Quests<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_bg'] = "Battleground PvPLog Data|Controls the display of Battleground PvPLog data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_pvp'] = "PvPLog Data|Controls the display of PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_duels'] = "Duel PvPLog Data|Controls the display of Duel PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_item_bonuses'] = "Item Bonuses|Controls the display of Item Bonuses<br /><br />Setting is global and overrides per-user setting";
$lang['admin']['show_signature'] = "Display Signature|Controls the display of a Signature image<br /><span class=\"red\">Requires SigGen Roster Addon</span><br /><br />Setting is global";
$lang['admin']['show_avatar'] = "Display Avatar|Controls the display of an Avatar image<br /><span class=\"red\">Requires SigGen Roster Addon</span><br /><br />Setting is global";

// realmstatus_conf
$lang['admin']['realmstatus_url'] = "Realmstatus URL|URL to Blizzard's Realmstatus page";
$lang['admin']['rs_display'] = "Info Mode|&quot;full&quot; will show status and server name, population, and type<br />&quot;half&quot; will display just the status";
$lang['admin']['rs_mode'] = "Display Mode|How Realmstatus will be displayed<br /><br />&quot;DIV Container&quot; - Shows Realmstatus in a DIV container with text and standard images<br />&quot;Image&quot; - Shows Realmstatus as an image (REQUIRES GD!)";
$lang['admin']['realmstatus'] = "Alternate Servername|Some server names may cause realmstatus to not work correctly, even if uploading profiles works<br />The actual server name from the game may not match what is used on the server status data page<br />You can set this so serverstatus can use another servername<br /><br />Leave blank to use the name set in Guild Config";

// guildbank_conf
$lang['admin']['guildbank_ver'] = "Guildbank Display Type|Guild bank display type<br /><br />&quot;Table&quot; is a basic view showing all items available from every bank character in one list<br />&quot;Inventory&quot; shows a table of items for each bank character";
$lang['admin']['bank_money'] = "Money Display|Controls Money display in guildbanks";
$lang['admin']['banker_rankname'] = "Banker Search Text|Text used to designate banker characters";
$lang['admin']['banker_fieldname'] = "Banker Search Field|Banker Search location, what field to search for Banker Text";

// update_access
$lang['admin']['authenticated_user'] = "Access to Update.php|Controls access to update.php<br /><br />Turning this off disables access for EVERYONE";

// Character Display Settings
$lang['admin']['per_character_display'] = 'Per-Character Display';
