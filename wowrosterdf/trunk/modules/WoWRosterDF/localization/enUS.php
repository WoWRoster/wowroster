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





//Instructions how to upload, as seen on the mainpage
$wordings['enUS']['update_link']='Click here for Updating Instructions';
$wordings['enUS']['update_instructions']='Updating Instructions';

$wordings['enUS']['lualocation']='Click browse and select your *.lua files to upload';

$wordings['enUS']['filelocation']='is located at<br /><i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables';

$wordings['enUS']['noGuild']='Could not find guild in database. Please update members first.';
$wordings['enUS']['nodata']="Could not find guild: <b>'".$roster_conf['guild_name']."'</b> for server <b>'".$roster_conf['server_name']."'</b><br />You need to <a href=\"".getlink($module_name.'&amp;file=update')."\">load your guild</a> first and make sure you <a href=\"".adminlink($module_name)."\">finished configuration</a>";

$wordings['enUS']['update_page']='Update Profile';
// NOT USED $wordings['enUS']['updCharInfo']='Update Character Info';
$wordings['enUS']['guild_nameNotFound']='Could not update &quot;%s&quot;. Maybe its not set in configuration?';
$wordings['enUS']['guild_addonNotFound']='Could not find Guild. GuildProfiler Addon not installed correctly?';

$wordings['enUS']['ignored']='Ignored';
$wordings['enUS']['update_disabled']='Update.php access has been disabled';

// NOT USED $wordings['enUS']['updGuildMembers']='Update Guild Members';
$wordings['enUS']['nofileUploaded']='UniUploader did not upload any file(s), or uploaded the wrong file(s).';
$wordings['enUS']['roster_upd_pwLabel']='Roster Update Password';
$wordings['enUS']['roster_upd_pw_help']='(This is required when doing a guild update)';

// Updating Instructions

$index_text_uniloader = "(You can download the program from the WoWRoster website, look for the UniUploader Installer for the latest version)";

$wordings['enUS']['update_instruct']='
<strong>Recommended automatic updaters:</strong>
<ul>
<li>Use <a href="'.$roster_conf['uploadapp'].'" target="_blank">UniUploader</a><br />
'.$index_text_uniloader.'</li>
</ul>
<strong>Updating instructions:</strong>
<ol>
<li>Download <a href="'.$roster_conf['profiler'].'" target="_blank">Character Profiler</a></li>
<li>Extract zip into its own directory in C:\Program Files\World of Warcraft\Interface\Addons\</li>
<li>Start WoW</li>
<li>Open your bank, quests, and the profession windows which contain recipes</li>
<li>Log out/Exit WoW (See above if you want to use the UniUploader to upload the data automatically for you.)</li>
<li>Go to <a href="'.getlink($module_name.'&amp;file=update').'">the update page</a></li>
<li>'.$wordings['enUS']['lualocation'].'</li>
</ol>';

$wordings['enUS']['update_instructpvp']='
<strong>Optional PvP Stats:</strong>
<ol>
<li>Download the <a href="'.$roster_conf['pvplogger'].'" target="_blank">PvPLog</a></li>
<li>Extract the PvPLog dir into your Addon dir.</li>
<li>Duel or PvP</li>
<li>Upload PvPLog.lua</li>
</ol>';

$wordings['enUS']['roster_credits']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site.<br />
WoWRoster home - <a href="http://www.wowroster.net" target="_blank">www.wowroster.net</a><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="'.getlink($module_name.'&amp;file=credits').'">Additional Credits</a>';


//Charset
$wordings['enUS']['charset']="charset=utf-8";

$timeformat['enUS'] = '%a %b %D, %l:%i %p'; // MySQL Time format      (example - '%a %b %D, %l:%i %p' => 'Mon Jul 23rd, 2:19 PM') - http://dev.mysql.com/doc/refman/4.1/en/date-and-time-functions.html
$phptimeformat['enUS'] = 'D M jS, g:ia';    // PHP date() Time format (example - 'D M jS, g:ia' => 'Mon Jul 23rd, 2:19pm') - http://www.php.net/manual/en/function.date.php


/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/

// ALLIANCE KEYS
$inst_keys['enUS']['A'] = array(
	'SG' => array( 'Quests', 'SG' =>
			'Key to Searing Gorge|4826',
			'The Horn of the Beast|',
			'Proof of Deed|',
			'At Last!|'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Workshop Key|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'The Scarlet Key|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Mallet of Zul\\\'Farrak|5695',
			'Sacred Mallet|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Scepter of Celebras|19710',
			'Celebrian Rod|19549',
			'Celebrian Diamond|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Prison Cell Key|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Shadowforge Key|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only', 'DM' =>
			'Crescent Key|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Skeleton Key|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Key to the City|13146'
		),
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Seal of Ascension|17057',
			'Unadorned Seal of Ascension|5370',
			'Gemstone of Spirestone|5379',
			'Gemstone of Smolderthorn|16095',
			'Gemstone of Bloodaxe|21777',
			'Unforged Seal of Ascension|24554||MS',
			'Forged Seal of Ascension|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Drakefire Amulet|4829',
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
	'MC' => array( 'Key-Only', 'MC' =>
			'Eternal Quintessence|22754'
		),
);


// HORDE KEYS
$inst_keys['enUS']['H'] = array(
	'SG' => array( 'Key-Only', 'SG' =>
			'Key to Searing Gorge|4826'
		),
	'Gnome' => array( 'Key-Only', 'Gnome' =>
			'Workshop Key|2288'
		),
	'SM' => array( 'Key-Only', 'SM' =>
			'The Scarlet Key|4445'
		),
	'ZF' => array( 'Parts', 'ZF' =>
			'Mallet of Zul\\\'Farrak|5695',
			'Sacred Mallet|8250'
		),
	'Mauro' => array( 'Parts', 'Mauro' =>
			'Scepter of Celebras|19710',
			'Celebrian Rod|19549',
			'Celebrian Diamond|19545'
		),
	'BRDp' => array( 'Key-Only', 'BRDp' =>
			'Prison Cell Key|15545'
		),
	'BRDs' => array( 'Parts', 'BRDs' =>
			'Shadowforge Key|2966',
			'Ironfel|9673'
		),
	'DM' => array( 'Key-Only', 'DM' =>
			'Crescent Key|35607'
		),
	'Scholo' => array( 'Quests', 'Scholo' =>
			'Skeleton Key|16854',
			'Scholomance|',
			'Skeletal Fragments|',
			'Mold Rhymes With...|',
			'Fire Plume Forged|',
			'Araj\\\'s Scarab|',
			'The Key to Scholomance|'
		),
	'Strath' => array( 'Key-Only', 'Strath' =>
			'Key to the City|13146'
		),
	'UBRS' => array( 'Parts', 'UBRS' =>
			'Seal of Ascension|17057',
			'Unadorned Seal of Ascension|5370',
			'Gemstone of Spirestone|5379',
			'Gemstone of Smolderthorn|16095',
			'Gemstone of Bloodaxe|21777',
			'Unforged Seal of Ascension|24554||MS',
			'Forged Seal of Ascension|19463||MS'
		),
	'Onyxia' => array( 'Quests', 'Onyxia' =>
			'Drakefire Amulet|4829',
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
	'MC' => array( 'Key-Only', 'MC' =>
			'Eternal Quintessence|22754'
		),
);

//single words used in menu and/or some of the functions, so if theres a wow eqivalent be correct
$wordings['enUS']['upload']='Upload';
$wordings['enUS']['required']='Required';
$wordings['enUS']['optional']='Optional';
$wordings['enUS']['attack']='Attack';
$wordings['enUS']['defense']='Defense';
$wordings['enUS']['class']='Class';
$wordings['enUS']['race']='Race';
$wordings['enUS']['level']='Level';
$wordings['enUS']['zone']='Last Zone';
$wordings['enUS']['note']='Note';
$wordings['enUS']['title']='Title';
$wordings['enUS']['name']='Name';
$wordings['enUS']['health']='Health';
$wordings['enUS']['mana']='Mana';
$wordings['enUS']['gold']='Gold';
$wordings['enUS']['armor']='Armor';
$wordings['enUS']['lastonline']='Last Online';
$wordings['enUS']['lastupdate']='Last Updated';
$wordings['enUS']['currenthonor']='Current Honor Rank';
$wordings['enUS']['rank']='Rank';
$wordings['enUS']['sortby']='Sort by %';
$wordings['enUS']['total']='Total';
$wordings['enUS']['hearthed']='Hearthed';
$wordings['enUS']['recipes']='Recipes';
$wordings['enUS']['bags']='Bags';
$wordings['enUS']['character']='Character';
$wordings['enUS']['bglog']='BG Log';
$wordings['enUS']['pvplog']='PvP Log';
$wordings['enUS']['duellog']='Duel Log';
$wordings['enUS']['duelsummary']='Duel Summary';
$wordings['enUS']['money']='Money';
$wordings['enUS']['bank']='Bank';
$wordings['enUS']['guildbank']='GuildBank';
$wordings['enUS']['guildbank_totalmoney']='Total bank holdings';
$wordings['enUS']['raid']='CT_Raid';
$wordings['enUS']['guildbankcontact']='Held By (Contact)';
$wordings['enUS']['guildbankitem']='Item Name and Description';
$wordings['enUS']['quests']='Quests';
$wordings['enUS']['roster']='Roster';
$wordings['enUS']['alternate']='Alternate';
$wordings['enUS']['byclass']='By Class';
$wordings['enUS']['menustats']='Stats';
$wordings['enUS']['menuhonor']='Honor';
$wordings['enUS']['keys']='Keys';
$wordings['enUS']['team']='Find Team';
$wordings['enUS']['search']='Search';
$wordings['enUS']['update']='Last updated';
$wordings['enUS']['credit']='Credits';
$wordings['enUS']['members']='Members';
$wordings['enUS']['items']='Items';
$wordings['enUS']['find']='Find item containing';
$wordings['enUS']['upprofile']='Update Profile';
$wordings['enUS']['backlink']='Back to the Roster';
$wordings['enUS']['gender']='Gender';
$wordings['enUS']['unusedtrainingpoints']='Unused Training Points';
$wordings['enUS']['unusedtalentpoints']='Unused Talent Points';
$wordings['enUS']['questlog']='Quest Log';
$wordings['enUS']['recipelist']='Recipe List';
$wordings['enUS']['reagents']='Reagents';
$wordings['enUS']['item']='Item';
$wordings['enUS']['type']='Type';
$wordings['enUS']['date']='Date';
$wordings['enUS']['completedsteps'] = 'Completed Steps';
$wordings['enUS']['currentstep'] = 'Current Step';
$wordings['enUS']['uncompletedsteps'] = 'Uncompleted Steps';
$wordings['enUS']['key'] = 'Key';
$wordings['enUS']['timeplayed'] = 'Time Played';
$wordings['enUS']['timelevelplayed'] = 'Time Level Played';
$wordings['enUS']['Addon'] = 'Addons';
$wordings['enUS']['advancedstats'] = 'Advanced Stats';
$wordings['enUS']['itembonuses'] = 'Bonuses For Equipped Items';
$wordings['enUS']['itembonuses2'] = 'Item Bonuses';
$wordings['enUS']['crit'] = 'Crit';
$wordings['enUS']['dodge'] = 'Dodge';
$wordings['enUS']['parry'] = 'Parry';
$wordings['enUS']['block'] = 'Block';
$wordings['enUS']['realm'] = 'Realm';

// Memberlog
$wordings['enUS']['memberlog'] = 'Member Log';
$wordings['enUS']['removed'] = 'Removed';
$wordings['enUS']['added'] = 'Added';
$wordings['enUS']['no_memberlog'] = 'No Member Log Recorded';

$wordings['enUS']['rosterdiag'] = 'Roster Diag.';
$wordings['enUS']['Guild_Info'] = 'Guild Info';
$wordings['enUS']['difficulty'] = 'Difficulty';
$wordings['enUS']['recipe_4'] = 'optimal';
$wordings['enUS']['recipe_3'] = 'medium';
$wordings['enUS']['recipe_2'] = 'easy';
$wordings['enUS']['recipe_1'] = 'trivial';
$wordings['enUS']['roster_config'] = 'Roster Config';

// Spellbook
$wordings['enUS']['spellbook'] = 'Spellbook';
$wordings['enUS']['page'] = 'Page';
$wordings['enUS']['general'] = 'General';
$wordings['enUS']['prev'] = 'Prev';
$wordings['enUS']['next'] = 'Next';

// Mailbox
$wordings['enUS']['mailbox'] = 'Mailbox';
$wordings['enUS']['maildateutc'] = 'Mail Captured';
$wordings['enUS']['mail_item'] = 'Item';
$wordings['enUS']['mail_sender'] = 'Sender';
$wordings['enUS']['mail_subject'] = 'Subject';
$wordings['enUS']['mail_expires'] = 'Mail Expires';
$wordings['enUS']['mail_money'] = 'Money Included';


//this needs to be exact as it is the wording in the db
$wordings['enUS']['professions']='Professions';
$wordings['enUS']['secondary']='Secondary Skills';
$wordings['enUS']['Blacksmithing']='Blacksmithing';
$wordings['enUS']['Mining']='Mining';
$wordings['enUS']['Herbalism']='Herbalism';
$wordings['enUS']['Alchemy']='Alchemy';
$wordings['enUS']['Leatherworking']='Leatherworking';
$wordings['enUS']['Skinning']='Skinning';
$wordings['enUS']['Tailoring']='Tailoring';
$wordings['enUS']['Enchanting']='Enchanting';
$wordings['enUS']['Engineering']='Engineering';
$wordings['enUS']['Cooking']='Cooking';
$wordings['enUS']['Fishing']='Fishing';
$wordings['enUS']['First Aid']='First Aid';
$wordings['enUS']['Poisons']='Poisons';
$wordings['enUS']['backpack']='Backpack';
$wordings['enUS']['PvPRankNone']='none';

// Uses preg_match() to find required level in recipe tooltip
$wordings['enUS']['requires_level'] = '/Requires Level ([\d]+)/';

//Tradeskill-Array
$tsArray['enUS'] = array (
	$wordings['enUS']['Alchemy'],
	$wordings['enUS']['Herbalism'],
	$wordings['enUS']['Blacksmithing'],
	$wordings['enUS']['Mining'],
	$wordings['enUS']['Leatherworking'],
	$wordings['enUS']['Skinning'],
	$wordings['enUS']['Tailoring'],
	$wordings['enUS']['Enchanting'],
	$wordings['enUS']['Engineering'],
	$wordings['enUS']['Cooking'],
	$wordings['enUS']['Fishing'],
	$wordings['enUS']['First Aid'],
	$wordings['enUS']['Poisons'],
);

//Tradeskill Icons-Array
$wordings['enUS']['ts_iconArray'] = array (
	$wordings['enUS']['Alchemy']=>'Trade_Alchemy',
	$wordings['enUS']['Herbalism']=>'Trade_Herbalism',
	$wordings['enUS']['Blacksmithing']=>'Trade_BlackSmithing',
	$wordings['enUS']['Mining']=>'Trade_Mining',
	$wordings['enUS']['Leatherworking']=>'Trade_LeatherWorking',
	$wordings['enUS']['Skinning']=>'INV_Misc_Pelt_Wolf_01',
	$wordings['enUS']['Tailoring']=>'Trade_Tailoring',
	$wordings['enUS']['Enchanting']=>'Trade_Engraving',
	$wordings['enUS']['Engineering']=>'Trade_Engineering',
	$wordings['enUS']['Cooking']=>'INV_Misc_Food_15',
	$wordings['enUS']['Fishing']=>'Trade_Fishing',
	$wordings['enUS']['First Aid']=>'Spell_Holy_SealOfSacrifice',
	$wordings['enUS']['Poisons']=>'Ability_Poisons',
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
$wordings['enUS']['riding'] = 'Riding';
$wordings['enUS']['ts_ridingIcon'] = array(
	'Night Elf'=>'Ability_Mount_WhiteTiger',
	'Human'=>'Ability_Mount_RidingHorse',
	'Dwarf'=>'Ability_Mount_MountainRam',
	'Gnome'=>'Ability_Mount_MechaStrider',
	'Undead'=>'Ability_Mount_Undeadhorse',
	'Troll'=>'Ability_Mount_Raptor',
	'Tauren'=>'Ability_Mount_Kodo_03',
	'Orc'=>'Ability_Mount_BlackDireWolf',
	'Paladin'=>'Ability_Mount_Dreadsteed',
	'Warlock'=>'Ability_Mount_NightmareHorse'
);

// Class Icons-Array
$wordings['enUS']['class_iconArray'] = array (
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
$skilltypes['enUS'] = array(
	1 => 'Class Skills',
	2 => 'Professions',
	3 => 'Secondary Skills',
	4 => 'Weapon Skills',
	5 => 'Armor Proficiencies',
	6 => 'Languages'
);

//tabs
$wordings['enUS']['tab1']='Char';
$wordings['enUS']['tab2']='Pet';
$wordings['enUS']['tab3']='Rep';
$wordings['enUS']['tab4']='Skills';
$wordings['enUS']['tab5']='Talents';
$wordings['enUS']['tab6']='PvP';

$wordings['enUS']['strength']='Strength';
$wordings['enUS']['strength_tooltip']='Increases your attack power with melee Weapons.<br />Increases the amount of damage you can block with a shield.';
$wordings['enUS']['agility']='Agility';
$wordings['enUS']['agility_tooltip']= 'Increases your attack power with ranged weapons.<br />Improves your chance to score a critcal hit with all weapons.<br />Increases your armor and your chance to dodge attacks.';
$wordings['enUS']['stamina']='Stamina';
$wordings['enUS']['stamina_tooltip']= 'Increases your health points.';
$wordings['enUS']['intellect']='Intellect';
$wordings['enUS']['intellect_tooltip']= 'Increases your mana points and your chance to score a critical hit with spells.<br />Increases the rate at which you improve weapon skills.';
$wordings['enUS']['spirit']='Spirit';
$wordings['enUS']['spirit_tooltip']= 'Increases your health and mana regeneration rates.';
$wordings['enUS']['armor_tooltip']= 'Decreases the amount of damage you take from physical attacks.<br />The amount of reduction is influenced by the level of your attacker.';

$wordings['enUS']['melee_att']='Melee Attack';
$wordings['enUS']['melee_att_power']='Melee Attack Power';
$wordings['enUS']['range_att']='Ranged Attack';
$wordings['enUS']['range_att_power']='Ranged Attack Power';
$wordings['enUS']['power']='Power';
$wordings['enUS']['damage']='Damage';
$wordings['enUS']['energy']='Energy';
$wordings['enUS']['rage']='Rage';

$wordings['enUS']['melee_rating']='Melee Attack Rating';
$wordings['enUS']['melee_rating_tooltip']='Your attack rating affects your chance to hit a target<br />And is based on the weapon skill of the weapon you are currently holding.';
$wordings['enUS']['range_rating']='Ranged Attack Rating';
$wordings['enUS']['range_rating_tooltip']='Your attack rating affects your chance to hit a target<br />And is based on the weapon skill of the weapon you are currently weilding.';

$wordings['enUS']['res_fire']='Fire Resistance';
$wordings['enUS']['res_fire_tooltip']='Increases your resistance to Fire damage.<br />Higher the number the better the resistance.';
$wordings['enUS']['res_nature']='Nature Resistance';
$wordings['enUS']['res_nature_tooltip']='Increases your resistance to Nature damage.<br />Higher the number the better the resistance.';
$wordings['enUS']['res_arcane']='Arcane Resistance';
$wordings['enUS']['res_arcane_tooltip']='Increases your resistance to Arcane damage.<br />Higher the number the better the resistance.';
$wordings['enUS']['res_frost']='Frost Resistance';
$wordings['enUS']['res_frost_tooltip']='Increases your resistance to Frost damage.<br />Higher the number the better the resistance.';
$wordings['enUS']['res_shadow']='Shadow Resistance';
$wordings['enUS']['res_shadow_tooltip']='Increases your resistance to Shadow damage.<br />Higher the number the better the resistance.';

$wordings['enUS']['empty_equip']='No item equipped';
$wordings['enUS']['pointsspent']='Points Spent:';
$wordings['enUS']['none']='None';

$wordings['enUS']['pvplist']=' PvP Stats';
$wordings['enUS']['pvplist1']='Guild that suffered most at our hands';
$wordings['enUS']['pvplist2']='Guild that killed us the most';
$wordings['enUS']['pvplist3']='Player who we killed the most';
$wordings['enUS']['pvplist4']='Player who killed us the most';
$wordings['enUS']['pvplist5']='Member with the most kills';
$wordings['enUS']['pvplist6']='Member who has died the most';
$wordings['enUS']['pvplist7']='Member with best kill average';
$wordings['enUS']['pvplist8']='Member with best loss average';

$wordings['enUS']['hslist']=' Honor System Stats';
$wordings['enUS']['hslist1']='Highest Ranking member';
$wordings['enUS']['hslist2']='Highest Lifetime Rank';
$wordings['enUS']['hslist3']='Highest Lifetime HKs';
$wordings['enUS']['hslist4']='Most Honor Points';
$wordings['enUS']['hslist5']='Most Arena Points';

$wordings['enUS']['Druid']='Druid';
$wordings['enUS']['Hunter']='Hunter';
$wordings['enUS']['Mage']='Mage';
$wordings['enUS']['Paladin']='Paladin';
$wordings['enUS']['Priest']='Priest';
$wordings['enUS']['Rogue']='Rogue';
$wordings['enUS']['Shaman']='Shaman';
$wordings['enUS']['Warlock']='Warlock';
$wordings['enUS']['Warrior']='Warrior';

$wordings['enUS']['today']='Today';
$wordings['enUS']['yesterday']='Yesterday';
$wordings['enUS']['thisweek']='This Week';
$wordings['enUS']['lastweek']='Last Week';
$wordings['enUS']['lifetime']='Lifetime';
$wordings['enUS']['honorkills']='Honorable Kills';
$wordings['enUS']['dishonorkills']='Dishonorable Kills';
$wordings['enUS']['honor']='Honor';
$wordings['enUS']['standing']='Standing';
$wordings['enUS']['highestrank']='Highest Rank';
$wordings['enUS']['arena']='Arena';

$wordings['enUS']['totalwins']='Total Wins';
$wordings['enUS']['totallosses']='Total Losses';
$wordings['enUS']['totaloverall']='Total Overall';
$wordings['enUS']['win_average']='Average Level Diff (Wins)';
$wordings['enUS']['loss_average']='Average Level Diff (Losses)';

// These need to be EXACTLY what PvPLog stores them as
$wordings['enUS']['alterac_valley']='Alterac Valley';
$wordings['enUS']['arathi_basin']='Arathi Basin';
$wordings['enUS']['warsong_gulch']='Warsong Gulch';

$wordings['enUS']['world_pvp']='World PvP';
$wordings['enUS']['versus_guilds']='Versus Guilds';
$wordings['enUS']['versus_players']='Versus Players';
$wordings['enUS']['bestsub']='Best Subzone';
$wordings['enUS']['worstsub']='Worst Subzone';
$wordings['enUS']['killedmost']='Killed Most';
$wordings['enUS']['killedmostby']='Killed Most By';
$wordings['enUS']['gkilledmost']='Guild Killed Most';
$wordings['enUS']['gkilledmostby']='Guild Killed Most By';

$wordings['enUS']['wins']='Wins';
$wordings['enUS']['losses']='Losses';
$wordings['enUS']['overall']='Overall';
$wordings['enUS']['best_zone']='Best Zone';
$wordings['enUS']['worst_zone']='Worst Zone';
$wordings['enUS']['most_killed']='Most Killed';
$wordings['enUS']['most_killed_by']='Most Killed By';

$wordings['enUS']['when']='When';
$wordings['enUS']['guild']='Guild';
$wordings['enUS']['leveldiff']='LevelDiff';
$wordings['enUS']['result']='Result';
$wordings['enUS']['zone2']='Zone';
$wordings['enUS']['subzone']='Subzone';
$wordings['enUS']['bg']='Battleground';
$wordings['enUS']['yes']='Yes';
$wordings['enUS']['no']='No';
$wordings['enUS']['win']='Win';
$wordings['enUS']['loss']='Loss';
$wordings['enUS']['kills']='Kills';
$wordings['enUS']['unknown']='Unknown';

//strings for Rep-tab
$wordings['enUS']['exalted']='Exalted';
$wordings['enUS']['revered']='Revered';
$wordings['enUS']['honored']='Honored';
$wordings['enUS']['friendly']='Friendly';
$wordings['enUS']['neutral']='Neutral';
$wordings['enUS']['unfriendly']='Unfriendly';
$wordings['enUS']['hostile']='Hostile';
$wordings['enUS']['hated']='Hated';
$wordings['enUS']['atwar']='At War';
$wordings['enUS']['notatwar']='Not at War';

// language definitions for the rogue instance keys 'fix'
$wordings['enUS']['thievestools']='Thieves\\\' Tools';
$wordings['enUS']['lockpicking']='Lockpicking';
// END

	// Quests page external links (on character quests page)
		// questlinks[#]['lang']['name']  This is the name displayed on the quests page
		// questlinks[#]['lang']['url#']   This is the URL used for the quest lookup

		$questlinks[0]['enUS']['name']='Thottbot';
		$questlinks[0]['enUS']['url1']='http://www.thottbot.com/?f=q&amp;title=';
		$questlinks[0]['enUS']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[0]['enUS']['url3']='&amp;maxl=';

		$questlinks[1]['enUS']['name']='Allakhazam';
		$questlinks[1]['enUS']['url1']='http://wow.allakhazam.com/db/qlookup.html?name=';
		$questlinks[1]['enUS']['url2']='&amp;obj=&amp;desc=&amp;minl=';
		$questlinks[1]['enUS']['url3']='&amp;maxl=';

		$questlinks[2]['enUS']['name']='WWN Data';
		$questlinks[2]['enUS']['url1']='http://wwndata.worldofwar.net/search.php?q=on&amp;search=';
		//$questlinks[2]['enUS']['url2']='&amp;levelmin=';
		//$questlinks[2]['enUS']['url3']='&amp;levelmax=';

// Items external link
	$itemlink['enUS']='http://www.thottbot.com/index.cgi?i=';
	//$itemlink['enUS']='http://wow.allakhazam.com/search.html?q=';

// definitions for the questsearchpage
	$wordings['enUS']['search1']="From the list below choose a zone or a quest name to see who is working it.<br />\n<small>Note that if the quest level is not the same for all listed guildmembers, they may be on another part of a multi-chain quest.</small>";
	$wordings['enUS']['search2']='Search by Zone';
	$wordings['enUS']['search3']='Search by Quest Name';

// Definition for item tooltip coloring
	$wordings['enUS']['tooltip_use']='Use:';
	$wordings['enUS']['tooltip_requires']='Requires';
	$wordings['enUS']['tooltip_reinforced']='Reinforced';
	$wordings['enUS']['tooltip_soulbound']='Soulbound';
	$wordings['enUS']['tooltip_boe']='Binds when equipped';
	$wordings['enUS']['tooltip_equip']='Equip:';
	$wordings['enUS']['tooltip_equip_restores']='Equip: Restores';
	$wordings['enUS']['tooltip_equip_when']='Equip: When';
	$wordings['enUS']['tooltip_chance']='Chance';
	$wordings['enUS']['tooltip_enchant']='Enchant';
	$wordings['enUS']['tooltip_set']='Set';
	$wordings['enUS']['tooltip_rank']='Rank';
	$wordings['enUS']['tooltip_next_rank']='Next rank';
	$wordings['enUS']['tooltip_spell_damage']='Spell Damage';
	$wordings['enUS']['tooltip_school_damage']='\\+.*Spell Damage';
	$wordings['enUS']['tooltip_healing_power']='Healing Power';
	$wordings['enUS']['tooltip_chance_hit']='Chance to hit:';
	$wordings['enUS']['tooltip_reinforced_armor']='Reinforced Armor';
	$wordings['enUS']['tooltip_damage_reduction']='Damage Reduction';

// Warlock pet names for icon displaying
	$wordings['enUS']['Imp']='Imp';
	$wordings['enUS']['Voidwalker']='Voidwalker';
	$wordings['enUS']['Succubus']='Succubus';
	$wordings['enUS']['Felhunter']='Felhunter';
	$wordings['enUS']['Infernal']='Infernal';
	$wordings['enUS']['Felguard']='Felguard';

// Max experiance for exp bar on char page
	$wordings['enUS']['max_exp']='Max XP';

// Error messages
	$wordings['enUS']['CPver_err']="The version of CharacterProfiler used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minCPver']." and have logged onto this character and saved data using this version.";
	$wordings['enUS']['PvPLogver_err']="The version of PvPLog used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minPvPLogver'].", and if you have just updated your PvPLog, ensure you deleted your old PvPLog.lua Saved Variables file prior to updating.";
	$wordings['enUS']['GPver_err']="The version of GuildProfiler used to capture data for this guild is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v".$roster_conf['minGPver'];






/******************************
 * Roster Admin Strings
 ******************************/

// Submit/Reset confirm questions
$wordings['enUS']['confirm_config_submit'] = 'This will save the changes to the database. Are you sure?';
$wordings['enUS']['confirm_config_reset'] = 'This will reset the form to how it was when you loaded it. Are you sure?';

// Main Menu words
$wordings['enUS']['admin']['main_conf'] = 'Main Settings';
$wordings['enUS']['admin']['guild_conf'] = 'Guild Config';
$wordings['enUS']['admin']['index_conf'] = 'Index Page';
$wordings['enUS']['admin']['menu_conf'] = 'Menu';
$wordings['enUS']['admin']['display_conf'] = 'Display Config';
$wordings['enUS']['admin']['char_conf'] = 'Character Page';
$wordings['enUS']['admin']['realmstatus_conf'] = 'Realmstatus';
$wordings['enUS']['admin']['guildbank_conf'] = 'Guildbank';
$wordings['enUS']['admin']['data_links'] = 'Item/Quest Data Links';
$wordings['enUS']['admin']['update_access'] = 'Update Access';


// All strings here
// Each variable must be the same name as the config variable name
// Example:
//   Assign description text and tooltip for $roster_conf['sqldebug']
//   $wordings['locale']['admin']['sqldebug'] = "Desc|Tooltip";

// Each string is separated by a pipe ( | )
// The first part is the short description, the next part is the tooltip
// Use <br /> to make new lines!
// Example:
//   "Controls Flux-Capacitor|Turning this on may cause serious temporal distortions<br />Use with care"


// main_conf
$wordings['enUS']['admin']['roster_upd_pw'] = "Roster Update Password|This is a password to allow guild updates on the update page<br />Some addons may also use this password";
$wordings['enUS']['admin']['roster_dbver'] = "Roster Database Version|The version of the database";
$wordings['enUS']['admin']['version'] = "Roster Version|Current version of Roster";
$wordings['enUS']['admin']['sqldebug'] = "SQL Debug Output|Print MySQL Debug Statements in html comments";
$wordings['enUS']['admin']['debug_mode'] = "Debug Mode|Full debug trace in error messages";
$wordings['enUS']['admin']['sql_window'] = "SQL Window|Displays SQL Queries in a window in the footer";
$wordings['enUS']['admin']['minCPver'] = "Min CP Version|Minimum CharacterProfiler version allowed to upload";
$wordings['enUS']['admin']['minGPver'] = "Min GP version|Minimum GuildProfiler version allowed to upload";
$wordings['enUS']['admin']['minPvPLogver'] = "Min PvPLog version|Minimum PvPLog version allowed to upload";
$wordings['enUS']['admin']['roster_lang'] = "Roster Main Language|The main language roster will be displayed in";
$wordings['enUS']['admin']['website_address'] = "Website Address|Used for url link for logo, and guildname link in the main menu<br />Some roster addons may also use this";
$wordings['enUS']['admin']['roster_dir'] = "Roster URL|The URL path to the Roster directory<br />It is critical that this is correct or errors may occur<br />(EX: http://www.site.com/roster )<br /><br />A full url is not required but a foreward slash before the directory is<br />(EX: /roster )";
$wordings['enUS']['admin']['server_name_comp'] = "char.php Compatibility Mode|If your character page does not work, try changing this";
$wordings['enUS']['admin']['interface_url'] = "Interface Directory URL|Directory that the Interface images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$wordings['enUS']['admin']['img_suffix'] = "Interface Image Extension|The image type of the Interface images";
$wordings['enUS']['admin']['alt_img_suffix'] = "Alt Interface Image Extension|The alternate possible image type of the Interface images";
$wordings['enUS']['admin']['img_url'] = "Roster Images Directory URL|Directory that Roster's images are located<br />Default is &quot;img/&quot;<br /><br />You can use a relative path or a full URL";
$wordings['enUS']['admin']['timezone'] = "Timezone|Displayed after timestamps so people know what timezone the time references are in";
$wordings['enUS']['admin']['localtimeoffset'] = "Time Offest|The timezone offset from UTC/GMT<br />Times on roster will be displayed as a calculated value using this offset";
$wordings['enUS']['admin']['pvp_log_allow'] = "Allow upload of PvPLog Data|Changing this to &quot;no&quot; will disable the PvPLog upload field in &quot;update&quot;";
$wordings['enUS']['admin']['use_update_triggers'] = "Addon Update Triggers|Addon Update Triggers are for addons that need to run during a character or guild update<br />Some addons my require that this is turned on for them to function properly";

// guild_conf
$wordings['enUS']['admin']['guild_name'] = "Guild Name|This must be spelled exactly as it is in the game<br />or you <u>WILL</u> <u>NOT</u> be able to upload profiles";
$wordings['enUS']['admin']['server_name'] = "Server Name|This must be spelled exactly as it is in the game<br />or you <u>WILL</u> <u>NOT</u> be able to upload profiles";
$wordings['enUS']['admin']['guild_desc'] = "Guild Description|Enter a short Guild Description";
$wordings['enUS']['admin']['server_type'] = "Server Type|This for your type of server in WoW";
$wordings['enUS']['admin']['alt_type'] = "Alt-Text Search|Text used to designate alts for display count in the main menu";
$wordings['enUS']['admin']['alt_location'] = "Alt-Search Field|Search location, what field to search for Alt-Text";

// index_conf
$wordings['enUS']['admin']['index_pvplist'] = "PvP-Logger Stats|PvP-Logger stats on the index page<br />If you have disabled PvPlog uploading, there is no need to have this on";
$wordings['enUS']['admin']['index_hslist'] = "Honor System Stats|Honor System stats on the index page";
$wordings['enUS']['admin']['hspvp_list_disp'] = "PvP/Honor List Display|Controls how the PvP and Honor Lists display on page load<br />The lists can be collapsed and opened by clicking on the header<br /><br />&quot;show&quot; will fully display the lists when the page loads<br />&quot;hide&quot; will show the lists collapsed";
$wordings['enUS']['admin']['index_member_tooltip'] = "Member Info Tooltip|Displays some info about a character in a tooltip";
$wordings['enUS']['admin']['index_update_inst'] = "Update Instructions|Controls the display of the Update Instructions on the page";
$wordings['enUS']['admin']['index_sort'] = "Member List Sort|Controls the default sorting";
$wordings['enUS']['admin']['index_motd'] = "Guild MOTD|Show Guild Message of the Day on the top of the page<br /><br />This also controls the display on the &quot;Guild Info&quot; page as well";
$wordings['enUS']['admin']['index_level_bar'] = "Level Bar|Toggles the display of a visual level percentage bar on the main page";
$wordings['enUS']['admin']['index_iconsize'] = "Icon Size|Select the size of the icons on the main pages (PvP, tradeskills, class, etc..)";
$wordings['enUS']['admin']['index_tradeskill_icon'] = "Tradeskill Icons|Enables tradeskill icons on the main pages";
$wordings['enUS']['admin']['index_tradeskill_loc'] = "Tradeskill Column Display|Select what column to place tradeskill icons";
$wordings['enUS']['admin']['index_class_color'] = "Class Colorizing|Colorize the class names";
$wordings['enUS']['admin']['index_classicon'] = "Class Icons|Displays an icon for each class, for each character";
$wordings['enUS']['admin']['index_honoricon'] = "PvP Honor Icons|Displays a PvP rank icon next to the rank name";
$wordings['enUS']['admin']['index_prof'] = "Professions Column|This is a specific coulmn for the tradeskill icons<br />If you move them to another column, you might want to turn this off";
$wordings['enUS']['admin']['index_currenthonor'] = "Honor Column|Toggles the display of the honor column";
$wordings['enUS']['admin']['index_note'] = "Note Column|Toggles the display of the public note column";
$wordings['enUS']['admin']['index_title'] = "Guild Title Column|Toggles the display of the guild title column";
$wordings['enUS']['admin']['index_hearthed'] = "Hearthstone Loc. Column|Toggles the display of the hearthstone location column";
$wordings['enUS']['admin']['index_zone'] = "Last Zone Loc. Column|Toggles the display of the last zone column";
$wordings['enUS']['admin']['index_lastonline'] = "Last Seen Online Column|Toggles the display of the last seen online column";
$wordings['enUS']['admin']['index_lastupdate'] = "Last Updated Column|Display when the character last updated their info";

// menu_conf
$wordings['enUS']['admin']['menu_left_pane'] = "Left Pane (Member Quick List)|Controls display of the left pane of the main roster menu<br />This area holds the member quick list";
$wordings['enUS']['admin']['menu_right_pane'] = "Right Pane (Realmstatus)|Controls display of the right pane of the main roster menu<br />This area holds the realmstatus image";
$wordings['enUS']['admin']['menu_memberlog'] = "Member Log Link|Controls display of the Member Log Link";
$wordings['enUS']['admin']['menu_guild_info'] = "Guild-Info Link|Controls display of the Guild-Info Link";
$wordings['enUS']['admin']['menu_stats_page'] = "Basic Stats Link|Controls display of the Basic Stats Link";
$wordings['enUS']['admin']['menu_pvp_page'] = "PvPLog Stats Link|Controls display of the PvPLog Stats Link";
$wordings['enUS']['admin']['menu_honor_page'] = "Honor Page Link|Controls display of the Honor Page Link";
$wordings['enUS']['admin']['menu_guildbank'] = "Guildbank Link|Controls display of the Guildbank Link";
$wordings['enUS']['admin']['menu_keys_page'] = "Instance Keys Link|Controls display of the Instance Keys Link";
$wordings['enUS']['admin']['menu_tradeskills_page'] = "Professions Link|Controls display of the Professions Link";
$wordings['enUS']['admin']['menu_update_page'] = "Profile Update Link|Controls display of the Profile Update Link";
$wordings['enUS']['admin']['menu_quests_page'] = "Find Team/Quests Link|Controls display of the Find Team/Quests Link";
$wordings['enUS']['admin']['menu_search_page'] = "Search Page Link|Controls display of the Search Page Link";

// display_conf
$wordings['enUS']['admin']['stylesheet'] = "CSS Stylesheet|CSS stylesheet for roster";
$wordings['enUS']['admin']['roster_js'] = "Roster JS File|Main Roster JavaScript file location";
$wordings['enUS']['admin']['tabcontent'] = "Dynamic Tab JS File|JavaScript file location for dynamic tab menus";
$wordings['enUS']['admin']['overlib'] = "Tooltip JS File|Tooltip JavaScript file location";
$wordings['enUS']['admin']['overlib_hide'] = "Overlib JS Fix|JavaScript file location of fix for Overlib in Internet Explorer";
$wordings['enUS']['admin']['logo'] = "URL for header logo|The full URL to the image<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$wordings['enUS']['admin']['roster_bg'] = "URL for background image|The full URL to the image used for the main background<br />Or by apending &quot;img/&quot; to the name, it will look in the roster's img/ directory";
$wordings['enUS']['admin']['motd_display_mode'] = "MOTD Display Mode|How the MOTD will be displayed<br /><br />&quot;Text&quot; - Shows MOTD in red text<br />&quot;Image&quot; - Shows MOTD as an image (REQUIRES GD!)";
$wordings['enUS']['admin']['compress_note'] = "Note Display Mode|How the Player Notes will be displayed<br /><br />&quot;Text&quot; - Shows the note text<br />&quot;Icon&quot; - Shows an note icon with the text in a tooltip";
$wordings['enUS']['admin']['signaturebackground'] = "img.php Background|Support for legacy signature-creator";
$wordings['enUS']['admin']['processtime'] = "Process time|Displays &quot;<i>This page was created in XXX seconds with XX queries executed</i>&quot; in the footer of roster";
$wordings['enUS']['admin']['item_stats'] = "Item Stats Mod|If you have item_stats installed, turn this on";

// data_links
$wordings['enUS']['admin']['questlink_1'] = "Quest Link #1|Item external links<br />Look in your localization-file(s) for link configuration";
$wordings['enUS']['admin']['questlink_2'] = "Quest Link #2|Item external links<br />Look in your localization-file(s) for link configuration";
$wordings['enUS']['admin']['questlink_3'] = "Quest Link #3|Item external links<br />Look in your localization-file(s) for link configuration";
$wordings['enUS']['admin']['profiler'] = "CharacterProfiler download link|URL to download CharacterProfiler";
$wordings['enUS']['admin']['pvplogger'] = "PvPLog download link|URL to download PvPLog";
$wordings['enUS']['admin']['uploadapp'] = "UniUploader download link|URL to download UniUploader";

// char_conf
$wordings['enUS']['admin']['char_bodyalign'] = "Character Page Alignment|Alignment of the data on the character page";
$wordings['enUS']['admin']['show_talents'] = "Talents|Controls the display of Talents<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_spellbook'] = "Spellbook|Controls the display of the Spellbook<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_mail'] = "Mail|Controls the display of Mail<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_inventory'] = "Bags|Controls the display of Bags<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_money'] = "Money|Controls the display of Money<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_bank'] = "Bank|Controls the display of Bank contents<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_recipes'] = "Recipes|Controls the display of Recipes<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_quests'] = "Quests|Controls the display of Quests<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_bg'] = "Battleground PvPLog Data|Controls the display of Battleground PvPLog data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_pvp'] = "PvPLog Data|Controls the display of PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_duels'] = "Duel PvPLog Data|Controls the display of Duel PvPLog Data<br />Requires upload of PvPLog addon data<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_item_bonuses'] = "Item Bonuses|Controls the display of Item Bonuses<br /><br />Setting is global and overrides per-user setting";
$wordings['enUS']['admin']['show_signature'] = "Display Signature|Controls the display of a Signature image<br /><span class=\"red\">Requires SigGen Roster Addon</span><br /><br />Setting is global";
$wordings['enUS']['admin']['show_avatar'] = "Display Avatar|Controls the display of an Avatar image<br /><span class=\"red\">Requires SigGen Roster Addon</span><br /><br />Setting is global";

// realmstatus_conf
$wordings['enUS']['admin']['realmstatus_url'] = "Realmstatus URL|URL to Blizzard's Realmstatus page";
$wordings['enUS']['admin']['rs_display'] = "Info Mode|&quot;full&quot; will show status and server name, population, and type<br />&quot;half&quot; will display just the status";
$wordings['enUS']['admin']['rs_mode'] = "Display Mode|How Realmstatus will be displayed<br /><br />&quot;DIV Container&quot; - Shows Realmstatus in a DIV container with text and standard images<br />&quot;Image&quot; - Shows Realmstatus as an image (REQUIRES GD!)";
$wordings['enUS']['admin']['realmstatus'] = "Alternate Servername|Some server names may cause realmstatus to not work correctly, even if uploading profiles works<br />The actual server name from the game may not match what is used on the server status data page<br />You can set this so serverstatus can use another servername<br /><br />Leave blank to use the name set in Guild Config";

// guildbank_conf
$wordings['enUS']['admin']['guildbank_ver'] = "Guildbank Display Type|Guild bank display type<br /><br />&quot;Table&quot; is a basic view showing all items available from every bank character in one list<br />&quot;Inventory&quot; shows a table of items for each bank character";
$wordings['enUS']['admin']['bank_money'] = "Money Display|Controls Money display in guildbanks";
$wordings['enUS']['admin']['banker_rankname'] = "Banker Search Text|Text used to designate banker characters";
$wordings['enUS']['admin']['banker_fieldname'] = "Banker Search Field|Banker Search location, what field to search for Banker Text";

// update_access
$wordings['enUS']['admin']['authenticated_user'] = "Access to Update.php|Controls access to update.php<br /><br />Turning this off disables access for EVERYONE";

// Character Display Settings
$wordings['enUS']['admin']['per_character_display'] = 'Per-Character Display';



// Credits page
// Only defined here because we don't need to translate this for EVERY locale

$creditspage['top']='Props to <a href="http://www.poseidonguild.com" target="_blank">Celandro</a>, <a href="http://www.movieobsession.com" target="_blank">Paleblackness</a>, Pytte, <a href="http://www.witchhunters.net" target="_blank">Rubricsinger</a>, and <a href="http://sourceforge.net/users/konkers/" target="_blank">Konkers</a> for the original code used for this site
<br />
Special thanks to calvin from <a href="http://www.rpgoutfitter.com" target="_blank">rpgoutfitter.com</a> for his wonderfull addons CharacterProfiler and GuildProfiler
<br /><br />
To the DEVs of Roster, for helping to build and maintain the package. You Rock!
<br /><br />
Thanks to all the coders who have contributed code, bug fixes, time, and testing of WoWRoster
<br /><br />';

// This is an array of the dev team
$creditspage['devs'] = array(
		'active'=>array(
			array(	"name"=>	"zanix",
					"info"=>	"Site Admin, WoWRoster Coordinator<br />Author of SigGen"),
			array(	"name"=>	"Anaxent",
					"info"=>	"WoWRoster Dev<br />WoWRosterDF Author (DragonflyCMS Port)"),
			array(	"name"=>	"mathos",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"PleegWat",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"seleleth",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Sphinx",
					"info"=>	"WoWRoster Dev<br />German Translator"),
			array(	"name"=>	"silencer-ch-au",
					"info"=>	"WoWRoster Dev"),
			array(	"name"=>	"Zeryl",
					"info"=>	"WoWRoster Dev<br />Missing Recipes Roster-Addon Author"),
			array(	"name"=>	"Matt Miller",
					"info"=>	"Gimpy DEV<br />Author of UniAdmin and UniUploader"),
			array(	"name"=>	"calvin",
					"info"=>	"Gimpy DEV<br />Author of CharacterProfiler and GuildProfiler"),
			array(	"name"=>	"bsmorgan",
					"info"=>	"Gimpy DEV<br />Author of PvPLog"),
		),

		'library'=>array(
			array(	"name"=>	"DynamicDrive",
					"info"=>	"<a href='http://www.dynamicdrive.com/dynamicindex17/tabcontent.htm'>Tab content script</a><br /><a href='http://www.dynamicdrive.com/notice.htm'>DynamicDrive license</a>"),
			array(	"name"=>	"Erik Bosrup",
					"info"=>	"<a href='http://www.bosrup.com/web/overlib/'>OverLib tooltip library</a><br /><a href='http://www.bosrup.com/web/overlib/?License'>License</a>"),
		),

		'3rdparty'=>array(
			array(	"name"=>	"<a href='http://53x11.com' target='_blank'>Nick Schaffner</a>",
					"info"=>	"Original WoW server status"),
			array(	"name"=>	"Averen",
					"info"=>	"Original MemberLog author"),
			array(	"name"=>	"Cybrey",
					"info"=>	"Advanced stats &amp; bonuses block on the character page"),
			array(	"name"=>	"vgjunkie",
					"info"=>	"Recoded professions page for v1.7.1<br />New show/hide javascript code for collapsable tables"),
			array(	"name"=>	"dehoskins",
					"info"=>	"Improvements to the stats &amp; bonuses block"),
			array(	"name"=>	"<a href='http://www.eqdkp.com' target='_blank'>EQdkp team</a>",
					"info"=>	"Original version of the installer/upgrader code<br />and their templating engine"),
		),

		'inactive'=>array(
			array(	"name"=>	"AnthonyB",
					"info"=>	"Retired DEV<br />Site Admin and Coordinator<br />v1.04 to v1.7.0"),
			array(	"name"=>	"Airor/Chris",
					"info"=>	"Inactive Dev<br />Coded new lua parser for v1.7.0"),
			array(	"name"=>	"dsrbo",
					"info"=>	"Retired DEV<br />Retired PvPLog Author"),
			array(	"name"=>	"Guppy",
					"info"=>	"Retired DEV"),
			array(	"name"=>	"Mordon",
					"info"=>	"Retired Dev<br />Head Dev v1.03 and lower"),
			array(	"name"=>	"mrmuskrat",
					"info"=>	"Retired DEV<br />Retired PvPLog Author"),
			array(	"name"=>	"Nemm",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"nerk01",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"Nostrademous",
					"info"=>	"Retired Dev<br />Retired PvPLog Author"),
			array(	"name"=>	"peperone",
					"info"=>	"Inactive Dev<br />German Translator"),
			array(	"name"=>	"RossiRat",
					"info"=>	"Inactive Dev<br />German Translator"),
			array(	"name"=>	"Swipe",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"vaccafoeda",
					"info"=>	"Inactive Dev"),
			array(	"name"=>	"Vich",
					"info"=>	"Inactive Dev"),
		),
	);

$creditspage['bottom']='WoW Roster is licensed under a Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" license.
<br />
Serveral javascript files are libraries that are under their own licenses.
<br />
The installer was derived from the EQdkp installer and is licensed under the GNU General Public License
<br /><br />
See the <a href="'.getlink($module_name.'&amp;file=license').'">license</a> for details';
