<?php

//Instructions how to upload, as seen on the mainpage
$update_link['enUS']='Click here for Updating Instructions';

$index_text_uniloader = "(You can download the program from the WoW Profilers website, look for the UniUploader Installer for the latest version.)";

$lualocation['enUS']='Click browse and upload your *.lua files<br>
(These files are located in *WoW Directory*\WTF\Account\*Accountname*\SavedVariables\CharacterProfiler.lua)<br>';

$noGuild['enUS']='Could not find guild in database. Please update members first.';
$return['enUS']='Return to Roster';
$updMember['enUS']='Update Member (update.php)';
$updCharInfo['enUS']='Update Character Info';
$guild_nameNotFound['enUS']='Could not find guild_name. Maybe its not set in configuration?';
$guild_addonNotFound['enUS']='Could not find Guild. GuildProfiler Addon not installed correctly?';
$updGuildMembers['enUS']='Update Guild Members';
$nofileUploaded['enUS']='Your UniUploader did not upload any file(s), or uploaded the wrong file(s).';

// Updating Instructions
$update_instruct['enUS']='
Recommended automatic updaters:<br>
Use <a href="'.$uploadapp.'">UniUploader</a>&nbsp; '.$index_text_uniloader.'<br>
<br>
Updating instructions:<br>
Step 1: Download <a href="'.$profiler.'">Character Profiler</a><br>
Step 2: Extract zip into its own dir in C:\Program Files\World of Warcraft\Interface\Addons\ (CharacterProfiler\) directory<br>
Step 3: Start WoW<br>
Step 4: Open your bank, quests, and the profession windows which contain recipes<br>
Step 5: Log out/Exit WoW (See above if you want to use the UniUploader to upload 
the data automatically for you.)<br>
Step 6: Go to <a href="'.$roster_dir.'/admin/update.php">the update page</a><br>
Step 7: '.$lualocation['enUS'].'
<br>';

$update_instructpvp['enUS']='
Optional PvP Stats:<br>
Step 1: Download the <a href="'.$pvplogger.'">PVPLogger</a><br>
Step 2: Extract the PvPLog dir into your Addon dir.<br>
Step 3: Duel or PvP<br>
Step 4: Upload PvPLog.lua<br>
';

$credits['enUS']='Props to <a href="http://www.poseidonguild.com/char.php?name=Celandro&server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&server=Bleeding%20Hollow">Paleblackness</a>, Pytte, and <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&server=Deathwing">Rubricsinger</a> for the original code used for this site<br>
Special thanks to <a href="mailto:calvin@rpgoutfitter.com">calvin</a> from <a href="http://www.rpgoutfitter.com">rpgoutfitter</a> for sharing his <a href="http://www.rpgoutfitter.com/downloads/wowinterface.cfm">icons</a>.<br>
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br>';

$questsearchtext['enUS']='From the list below choose a zone to see who is working it. Note that if the quest level is not the same for all listed guildmenbers, they may be on another part of a multi-chain quest.';

//Charset
$charset['enUS']="charset=utf-8";

//$timeformat['enUS']="%b %d %l%p"; // Time format example - Jul 23 2PM
$timeformat['enUS']='%b %d %l:%i %p'; // Time format example - Jul 23 2:19 PM
$phptimeformat['enUS']='D jS M, g:ia'; // Time format example - Mon Jul, 23rd 2:19pm. This is PHP syntax for date() function

/*
Instance Keys
=============
A part that is marked with 'MS' (milestone) will be designated as an overall status. So if
you have this one part it will mark all other parts lower than this one as complete.
*/
$inst_keys['enUS']['A'] = array(
		'SG' => array('Quests','SG' => 'Key to Searing Gorge|4826','The Horn of the Beast|','Proof of Deed|','At Last!|'),
		'Gnome' => array('Key-Only','Gnome' => 'Workshop Key|2288'),
		'SM' => array('Key-Only','SM' => 'The Scarlet Key|4445'),
		'ZF' => array('Parts','ZF' => 'Mallet of Zul\\\'Farrak|5695','Sacred Mallet|8250'),
		'Mauro' => array('Parts', 'Mauro' => 'Scepter of Celebras|19710','Celebrian Rod|19549','Celebrian Diamond|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Prison Cell Key|15545'),
		'BRDs' => array('Parts','BRDs' => 'Shadowforge Key|2966','Ironfel|9673'),
		'DM' => array('Key-Only','DM' => 'Crescent Key|35607'),
		'Scholo' => array('Quests','Scholo' => 'Skeleton Key|16854','Scholomance|','Skeletal Fragments|','Mold Rhymes With...|','Fire Plume Forged|','Araj\\\'s Scarab|','The Key to Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Key to the City|13146'),
		'UBRS' => array('Parts','UBRS' => 'Seal of Ascension|17057','Unadorned Seal of Ascension|5370','Gemstone of Spirestone|5379','Gemstone of Smolderthorn|16095','Gemstone of Bloodaxe|21777','Unforged Seal of Ascension|24554||MS','Forged Seal of Ascension|19463||MS'),
		'Onyxia' => array('Quests','Onyxia' => 'Drakefire Amulet|4829','Dragonkin Menace|','The True Masters|','Marshal Windsor|','Abandoned Hope|','A Crumpled Up Note|','A Shred of Hope|','Jail Break!|','Stormwind Rendezvous|','The Great Masquerade|','The Dragon\\\'s Eye|','Drakefire Amulet|')
	);

$inst_keys['enUS']['H'] = array(
	    'SG' => array('Key-Only','SG' => 'Key to Searing Gorge|4826'),
		'Gnome' => array('Key-Only','Gnome' => 'Workshop Key|2288'),
		'SM' => array('Key-Only','SM' => 'The Scarlet Key|4445'),
		'ZF' => array('Parts', 'ZF' => 'Mallet of Zul\\\'Farrak|5695','Sacred Mallet|8250'),
		'Mauro' => array('Parts', 'Mauro' => 'Scepter of Celebras|19710','Celebrian Rod|19549','Celebrian Diamond|19545'),
		'BRDp' => array('Key-Only','BRDp' => 'Prison Cell Key|15545'),
		'BRDs' => array('Parts', 'BRDs' => 'Shadowforge Key|2966','Ironfel|9673'),
		'DM' => array('Key-Only','DM' => 'Crescent Key|35607'),
		'Scholo' => array('Quests', 'Scholo' => 'Skeleton Key|16854','Scholomance|','Skeletal Fragments|','Mold Rhymes With...|','Fire Plume Forged|','Araj\\\'s Scarab|','The Key to Scholomance|'),
		'Strath' => array('Key-Only','Strath' => 'Key to the City|13146'),
		'UBRS' => array('Parts', 'UBRS' => 'Seal of Ascension|17057','Unadorned Seal of Ascension|5370','Gemstone of Spirestone|5379','Gemstone of Smolderthorn|16095','Gemstone of Bloodaxe|21777', 'Unforged Seal of Ascension|24554||MS', 'Forged Seal of Ascension|19463||MS'),
		'Onyxia' => array('Quests', 'Onyxia' => 'Drakefire Amulet|4829','Warlord\\\'s Command|','Eitrigg\\\'s Wisdom|','For The Horde!|','What the Wind Carries|','The Champion of the Horde|','The Testament of Rexxar|','Oculus Illusions|','Emberstrife|','The Test of Skulls, Scryer|','The Test of Skulls, Somnus|','The Test of Skulls, Chronalis|','The Test of Skulls, Axtroz|','Ascension...|','Blood of the Black Dragon Champion|')
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
$wordings['enUS']['lastupdate']='Last updated';
$wordings['enUS']['currenthonor']='Current Honor Rank';
$wordings['enUS']['sortby']='Sort by %';
$wordings['enUS']['total']='Total';
$wordings['enUS']['hearthed']='Hearthed';
$wordings['enUS']['recipes']='Recipes';
$wordings['enUS']['bags']='Bags';
$wordings['enUS']['character']='Character';
$wordings['enUS']['pvplog']='PvP Log';
$wordings['enUS']['duellog']='Duel Log';
$wordings['enUS']['bank']='Bank';
$wordings['enUS']['guildbank']='GuildBank';
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
$wordings['enUS']['completedsteps'] = 'Completed Steps';
$wordings['enUS']['currentstep'] = 'Current Step';
$wordings['enUS']['uncompletedsteps'] = 'Uncompleted Steps';
$wordings['enUS']['key'] = 'Key';

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
$wordings['enUS']['backpack']='Backpack';

//Tradeskill-Array
$tsArray['enUS'] = array ($wordings['enUS']['Alchemy'],$wordings['enUS']['Herbalism'],$wordings['enUS']['Blacksmithing'],$wordings['enUS']['Mining'],$wordings['enUS']['Leatherworking'],$wordings['enUS']['Skinning'],$wordings['enUS']['Tailoring'],$wordings['enUS']['Enchanting'],$wordings['enUS']['Engineering'],$wordings['enUS']['Cooking'],$wordings['enUS']['Fishing'],$wordings['enUS']['First Aid']);

//skills
$skilltypes['enUS'] = array( 1 => 'Class Skills',
         2 => 'Professions',
         3 => 'Secondary Skills',
         4 => 'Weapon Skills',
         5 => 'Armor Proficiencies',
         6 => 'Languages' );
         
//tabs
$wordings['enUS']['tab1']='Stats';
$wordings['enUS']['tab2']='Pet';
$wordings['enUS']['tab3']='Rep';
$wordings['enUS']['tab4']='Skills';
$wordings['enUS']['tab5']='Talents';
$wordings['enUS']['tab6']='Honor';

$wordings['enUS']['strength']='Strength';
$wordings['enUS']['strength_tooltip']='Increases your attack power with melee Weapons.<br>Increases the amount of damage you can block with a shield.';
$wordings['enUS']['agility']='Agility';
$wordings['enUS']['agility_tooltip']= 'Increases your attack power with ranged weapons.<br>Improves your chance to score a critcal hit with all weapons.<br>Increases your armor and your chance to dodge attacks.';
$wordings['enUS']['stamina']='Stamina';
$wordings['enUS']['stamina_tooltip']= 'Increases your health points.';
$wordings['enUS']['intellect']='Intellect';
$wordings['enUS']['intellect_tooltip']= 'Increases your mana points and your chance to score a critical hit with spells.<br>Increases the rate at which you improve weapon skills.';
$wordings['enUS']['spirit']='Spirit';
$wordings['enUS']['spirit_tooltip']= 'Increases your health and mana regeneration rates.';  
$wordings['enUS']['armor_tooltip']= 'Decreases the amount of damage you take from physical attacks.<br>The amount of reduction is influenced by the level of your attacker.';

$wordings['enUS']['melee_att']='Melee Attack';
$wordings['enUS']['melee_att_power']='Melee Attack Power';
$wordings['enUS']['range_att']='Ranged Attack';
$wordings['enUS']['range_att_power']='Ranged Attack Power';
$wordings['enUS']['power']='Power';
$wordings['enUS']['damage']='Damage';

$wordings['enUS']['melee_rating']='Melee Attack Rating';
$wordings['enUS']['melee_rating_tooltip']='Your attack rating affects your chance to hit a target and is based on the weapon skill of the weapon you are currently holding.';
$wordings['enUS']['range_rating']='Ranged Attack Rating';
$wordings['enUS']['range_rating_tooltip']='Your attack rating affects your chance to hit a target and is based on the weapon skill of the weapon you are currently weilding.';

$wordings['enUS']['res_fire']='Fire Resistance';
$wordings['enUS']['res_fire_tooltip']='Increases your resistance to Fire damage.<br>Higher the number the better the resistance.';
$wordings['enUS']['res_nature']='Nature Resistance';
$wordings['enUS']['res_nature_tooltip']='Increases your resistance to Nature damage.<br>Higher the number the better the resistance.';
$wordings['enUS']['res_arcane']='Arcane Resistance';
$wordings['enUS']['res_arcane_tooltip']='Increases your resistance to Arcane damage.<br>Higher the number the better the resistance.';
$wordings['enUS']['res_frost']='Frost Resistance';
$wordings['enUS']['res_frost_tooltip']='Increases your resistance to Frost damage.<br>Higher the number the better the resistance.';
$wordings['enUS']['res_shadow']='Shadow Resistance';
$wordings['enUS']['res_shadow_tooltip']='Increases your resistance to Shadow damage.<br>Higher the number the better the resistance.';

$wordings['enUS']['pointsspent']='Points Spent:';
$wordings['enUS']['none']='None';

$wordings['enUS']['pvplist']=' PvP Stats';
$wordings['enUS']['pvplist1']='Guild that has suffered most at our hands';
$wordings['enUS']['pvplist2']='Guild that has killed us the most';
$wordings['enUS']['pvplist3']='Player that has suffered most at our hands';
$wordings['enUS']['pvplist4']='Player that has killed us the most';
$wordings['enUS']['pvplist5']='Guild member with most kills';
$wordings['enUS']['pvplist6']='Guild member who has died the most';
$wordings['enUS']['pvplist7']='Guild member with best kill average';
$wordings['enUS']['pvplist8']='Guild member with best loss average';

$wordings['enUS']['hslist']=' Honour System Stats';
$wordings['enUS']['hslist1']='Highest Ranking Guild member This Week';
$wordings['enUS']['hslist2']='Guild member with the Best Weekly Standing';
$wordings['enUS']['hslist3']='Guild member who Scored The Most HKs Last Week';
$wordings['enUS']['hslist4']='Guild member who Scored The Most DKs Last Week';
$wordings['enUS']['hslist5']='Guild member who Scored The Most CPs Last Week';
$wordings['enUS']['hslist6']='Guild member with the Highest Lifetime Rank';
$wordings['enUS']['hslist7']='Guild member with the Highest Lifetime HKs';
$wordings['enUS']['hslist8']='Guild member with the Highest Lifetime DKs';
$wordings['enUS']['hslist9']='Guild member with the Best Weekly HK to CP Average';

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
$wordings['enUS']['alltime']='Lifetime';
$wordings['enUS']['honorkills']='Honorable Kills';
$wordings['enUS']['dishonorkills']='Dishonorable Kills';
$wordings['enUS']['honor']='Honor';
$wordings['enUS']['standing']='Standing';
$wordings['enUS']['highestrank']='Highest Rank';

$wordings['enUS']['totalwins']='Total Wins:';
$wordings['enUS']['totallosses']='Total Losses:';
$wordings['enUS']['totaloverall']='Total Overall:';
$wordings['enUS']['win_average']='Average Level Diff (Wins):';
$wordings['enUS']['loss_average']='Average Level Diff (Losses):';

$wordings['enUS']['when']='When';
$wordings['enUS']['guild']='Guild';
$wordings['enUS']['theirlevel']='Their Level';
$wordings['enUS']['yourlevel']='Your Level';
$wordings['enUS']['diff']='Diff';
$wordings['enUS']['result']='Result';
$wordings['enUS']['zone2']='Zone';
$wordings['enUS']['subzone']='Subzone';
$wordings['enUS']['group']='Group';

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

// Language definitions for Race selection in sig.php
// Format: $wordings['language']['Localized Name']='Race in Filename';
	// Example: $wordings['deDE']['Nachtelf']='nightelf';
	// Example: $wordings['enUS']['Night Elf']='nightelf';
// 'Localized Name' MUST be the same as what is stored in the database
// 'Race in Filename' MUST be the same as the race part in the image filenames

$wordings['enUS']['Night Elf']='nightelf';
$wordings['enUS']['Dwarf']='dwarf';
$wordings['enUS']['Gnome']='gnome';
$wordings['enUS']['Human']='human';
$wordings['enUS']['Orc']='orc';
$wordings['enUS']['Undead']='undead';
$wordings['enUS']['Troll']='troll';
$wordings['enUS']['Tauren']='tauren';
$wordings['enUS']['Male']='male';
$wordings['enUS']['Female']='female';
// END Language definitions for Race selection in sig.php

// language definitions for the rogue instance keys 'fix'
$wordings['enUS']['thievestools']='Thieves\\\' Tools';
$wordings['enUS']['lockpicking']='Lockpicking';
// END

	// Quests page external links (on character quests page)
		// questlink_n_name=?		This is the name displayed on the quests page
		// questlink_n_url=?		This is the URL used for the quest lookup

		$questlink_1_name['enUS']='Thottbot';
		$questlink_1_url1['enUS']='http://www.thottbot.com/?f=q&title=';
		$questlink_1_url2['enUS']='&obj=&desc=&minl=';
		$questlink_1_url3['enUS']='&maxl=';

		$questlink_2_name['enUS']='Allakhazam';
		$questlink_2_url1['enUS']='http://wow.allakhazam.com/db/qlookup.html?name=';
		$questlink_2_url2['enUS']='&obj=&desc=&minl=';
		$questlink_2_url3['enUS']='&maxl=';

		$questlink_3_name['enUS']='WWN Data';
		$questlink_3_url1['enUS']='http://wwndata.worldofwar.net/questsearch.php?questname=';
		//$questlink_3_url2['enUS']='';
		//$questlink_3_url3['enUS']='';

// definitions for the questsearchpage
	$wordings['enUS']['search1']='From the list below choose a zone or a quest name to see who is working it. Note that if the quest level is not the same for all listed guildmenbers, they may be on another part of a multi-chain quest.';
	$wordings['enUS']['search2']='Search by Zone';
	$wordings['enUS']['search3']='or Search by Quest Name';
?>