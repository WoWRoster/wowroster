<?php
$versions['versionDate']['enUS'] = '$Date: 2006/02/05 03:43:06 $'; 
$versions['versionRev']['enUS'] = '$Revision: 1.41 $'; 
$versions['versionAuthor']['enUS'] = '$Author: anthonyb $';


//Instructions how to upload, as seen on the mainpage
$update_link['enUS']='Click here for Updating Instructions';

$index_text_uniloader = "(You can download the program from the WoW Profilers website, look for the UniUploader Installer for the latest version.)";

$lualocation['enUS']='Click browse and upload your *.lua files<br />
(These files are located in *WoW Directory*\WTF\Account\*Accountname*\SavedVariables\*.lua)<br />';

$noGuild['enUS']='Could not find guild in database. Please update members first.';
$nodata['enUS']="<br /><br />Could not find guild: <b>'$guild_name'</b> for server <b>'$server_name'</b>. You need to load your guild first and make sure you finished configuration.<br /><a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>";
$return['enUS']='Return to Roster';
$updMember['enUS']='Update (update.php)';
$updCharInfo['enUS']='Update Character Info';
$guild_nameNotFound['enUS']='Could not find guild_name. Maybe its not set in configuration?';
$guild_addonNotFound['enUS']='Could not find Guild. GuildProfiler Addon not installed correctly?';
$updGuildMembers['enUS']='Update Guild Members';
$nofileUploaded['enUS']='Your UniUploader did not upload any file(s), or uploaded the wrong file(s).';
$roster_upd_pwLabel['enUS']='Roster Update Password';
$roster_upd_pw_help['enUS']='(This is required when doing a guild update)';

// Updating Instructions
$update_instruct['enUS']='
Recommended automatic updaters:<br />
Use <a href="'.$uploadapp.'">UniUploader</a>&nbsp; '.$index_text_uniloader.'<br />
<br />
Updating instructions:<br />
Step 1: Download <a href="'.$profiler.'">Character Profiler</a><br />
Step 2: Extract zip into its own dir in C:\Program Files\World of Warcraft\Interface\Addons\ (CharacterProfiler\) directory<br />
Step 3: Start WoW<br />
Step 4: Open your bank, quests, and the profession windows which contain recipes<br />
Step 5: Log out/Exit WoW (See above if you want to use the UniUploader to upload
the data automatically for you.)<br />
Step 6: Go to <a href="'.$roster_dir.'/admin/update.php">the update page</a><br />
Step 7: '.$lualocation['enUS'].'
<br />';

$update_instructpvp['enUS']='
Optional PvP Stats:<br />
Step 1: Download the <a href="'.$pvplogger.'">PvPLog</a><br />
Step 2: Extract the PvPLog dir into your Addon dir.<br />
Step 3: Duel or PvP<br />
Step 4: Upload PvPLog.lua<br />
';

$credits['enUS']='Props to <a href="http://www.poseidonguild.com/char.php?name=Celandro&amp;server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&amp;server=Bleeding Hollow">Paleblackness</a>, Pytte, and <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&amp;server=Deathwing">Rubricsinger</a> for the original code used for this site.<br />
WoW Roster home - <a href="http://wowroster.net">http://wowroster.net</a>.<br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />
<a href="credits.php">Additional Credits</a>';

$questsearchtext['enUS']='From the list below choose a zone to see who is working it. Note that if the quest level is not the same for all listed guildmenbers, they may be on another part of a multi-chain quest.';

//Charset
$charset['enUS']="charset=utf-8";

//$timeformat['enUS']="%b %d %l%p"; // Time format example - Jul 23 2PM
$timeformat['enUS']='%b %d %l:%i %p'; // Time format example - Jul 23 2:19 PM
$phptimeformat['enUS']='D jS M, g:ia'; // Time format example - Mon 23rd Jul, 2:19pm. This is PHP syntax for date() function

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
$wordings['enUS']['completedsteps'] = 'Completed Steps';
$wordings['enUS']['currentstep'] = 'Current Step';
$wordings['enUS']['uncompletedsteps'] = 'Uncompleted Steps';
$wordings['enUS']['key'] = 'Key';
$wordings['enUS']['timeplayed'] = 'Time Played';
$wordings['enUS']['timelevelplayed'] = 'Time Level Played';
$wordings['enUS']['Addon'] = 'Addons:';
$wordings['enUS']['advancedstats'] = 'Advanced Stats';
$wordings['enUS']['itembonuses'] = 'Bonuses For Equipped Items';
$wordings['enUS']['crit'] = 'Crit';
$wordings['enUS']['dodge'] = 'Dodge';
$wordings['enUS']['parry'] = 'Parry';
$wordings['enUS']['block'] = 'Block';

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
$wordings['enUS']['poisons']='Poisons';
$wordings['enUS']['backpack']='Backpack';
$wordings['enUS']['PvPRankNone']='none';

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
$wordings['enUS']['rank']='Rank';
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

// Items external link
	$itemlink['enUS']='http://www.thottbot.com/index.cgi?i=';
	//$itemlink['enUS']='http://wow.allakhazam.com/search.html?q=';

// definitions for the questsearchpage
	$wordings['enUS']['search1']="From the list below choose a zone or a quest name to see who is working it.<br />\n<small>Note that if the quest level is not the same for all listed guildmembers, they may be on another part of a multi-chain quest.</small>";
	$wordings['enUS']['search2']='Search by Zone';
	$wordings['enUS']['search3']='Search by Quest Name';

// serverstatus strings
	$servertypes['enUS']= array( 'PvP', 'Normal', 'RP', 'RPPVP' );
	$serverpops['enUS']= array( 'Medium', 'Low', 'High', 'Max' );

// Definition for item tooltip coloring
	$wordings['enUS']['tooltip_use']='Use';
	$wordings['enUS']['tooltip_requires']='Requires';
	$wordings['enUS']['tooltip_reinforced']='Reinforced';
	$wordings['enUS']['tooltip_soulbound']='Soulbound';
	$wordings['enUS']['tooltip_equip']='Equip';
	$wordings['enUS']['tooltip_equip_restores']='Equip: Restores';
	$wordings['enUS']['tooltip_equip_when']='Equip: When';
	$wordings['enUS']['tooltip_chance']='Chance';
	$wordings['enUS']['tooltip_enchant']='Enchant';
	$wordings['enUS']['tooltip_set']='Set';
	$wordings['enUS']['tooltip_rank']='Rank';
	$wordings['enUS']['tooltip_next_rank']='Next rank';
	$wordings['enUS']['tooltip_spell_damage']='Spell Damage';
	$wordings['enUS']['tooltip_healing_power']='Healing Power';
	$wordings['enUS']['tooltip_chance_hit']='Chance to hit:';
	$wordings['enUS']['tooltip_reinforced_armor']='Reinforced Armor';

// Warlock pet names for icon displaying
	$wordings['enUS']['Imp']='Imp';
	$wordings['enUS']['Voidwalker']='Voidwalker';
	$wordings['enUS']['Succubus']='Succubus';
	$wordings['enUS']['Felhunter']='Felhunter';
	$wordings['enUS']['Infernal']='Infernal';

// Max experiance for exp bar on char page
	$wordings['enUS']['max_exp']='Max Experience';

// Error messages
	$wordings['enUS']['CPver_err']="The version of CharacterProfiler used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v$minCPver and have logged onto this character and saved data using this version.";
	$wordings['enUS']['PvPLogver_err']="The version of PvPLog used to capture data for this character is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v$minPvPLogver, and if you have just updated your PvPLog, ensure you deleted your old PvPLog.lua Saved Variables file prior to updating.";
	$wordings['enUS']['GPver_err']="The version of GuildProfiler used to capture data for this guild is older than the minimum version allowed for upload.<br />\nPlease ensure you are running at least v$minGPver.";

// Credit page
$creditspage['enUS']='Props to <a href="http://www.poseidonguild.com/char.php?name=Celandro&amp;server=Cenarius">Celandro</a>, <a href="http://www.movieobsession.com/wow/parser/char.php?name=Grieve&amp;server=Bleeding Hollow">Paleblackness</a>, Pytte, and <a href="http://www.witchhunters.net/wowinfonew/char.php?name=Rubsi&amp;server=Deathwing">Rubricsinger</a> for the original code used for this site.
<br /><br />Special Thanks to <a href="mailto:calvin@rpgoutfitter.com">calvin</a> from <a href="http://www.rpgoutfitter.com">rpgoutfitter</a> for sharing his <a href="http://www.rpgoutfitter.com/downloads/wowinterface.cfm">icons</a>.<br />
<br />
Special Thanks to the DEVs of Roster for helping to build and maintain the package, current DEVs are:<br /><br />
<TABLE BORDER CELLPADDING=5>
<TABLE style="border: none;">
<COL>
<COL ALIGN=LEFT>
<TR> <TD>AnthonyB:</TD> <TD>Site Admin, DEV Coordinator</TD></TR>
<TR> <TD>Matt Miller:</TD> <TD>Roster DEV, UniAdmin and UniUploader Author, Site Hoster</TD></TR>
<TR> <TD>Calvin:</TD> <TD>Roster DEV, CharacterProfiler and GuildProfiler Author</TD></TR>
<TR> <TD>Mordon:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Zanix:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Sphinx:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Swipe:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Nerk01:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>RossiRat:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Vaccafoeda:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Nostrademous:</TD> <TD>Roster DEV, PvPLog 0.4.8 Author</TD></TR>
<TR> <TD>Ulminia:</TD> <TD>Roster DEV</TD></TR>
<TR> <TD>Guppy:</TD> <TD>Roster DEV (currently retired)</TD></TR>
</TABLE>
<br /><br />
Thanks to Cybrey for the Orginal "Made By" addon and Thorus for his mod of this script.
<br /><br />
Thanks to Cybrey for the Reputation addon.
<br /><br />
Advanced Stats & Bonuses, Thanks to cybrey (original author) and dehoskins (for additional output formatting).
<br /><br />
Thanks to the Roster 1.6 Beta Test Team - Kieeps, silencer-ch-au, Thorus, and Zeryl.
<br /><br />
Thanks to all the coders who have contributed there codes in bug fixes and testing of the roster.
<br /><br />
WoW Roster home - <a href="http://wowroster.net">http://wowroster.net</a>.<br /><br />
World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries. All other trademarks are the property of their respective owners.<br />';
?>
