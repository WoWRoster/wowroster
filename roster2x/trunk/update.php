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
* update.php
*
* This is the primary script which handles uploading of data from users
* and places it into the database in a usable form. It can take files
* either from the web or from an automated upload utility, such as
* UniUploader.
*
* For now, we'll do HTTP client determination, but I will also support
* a POST variable, $_POST['automated_script']==1, so that anyone can
* easily write a tool to upload the data.
*
******************************/

require_once('conf.php');
require_once('set_env.php');

require_once( ROSTER_BASE.'functions'.DIR_SEP.'class.character.php');

// Move the language for our page into a more convenient array.
$lang = $roster_wordings[$roster_conf['lang']]['uploadpage'];

if(isset($_POST['automated_script']) && $_POST['automated_script']==1)
{
	$automaton = true;
} else {
	$automaton = false;
}

// Start buffering output
ob_start();

if($automaton)
{
	print($lang['automationenabled']."\n");
} else {
	// TODO: do all the fancy template stuff

	print("<form method=post ENCTYPE=\"multipart/form-data\">\n");
	print("Lua To Upload: <input type=file name=file1>\n");
	print("<input type=submit>\n");
	print("</form>\n");
}

$handled_trees = array("CharacterProfile"=>"myProfile");
$ignored_trees = array("CharacterProfiler Configuration Data"=>"rpgoCPpref");

$guild_handler = new Guild();

foreach($_FILES as $current_file){
	if ($current_file['name'] == '')continue;
	print("Processing $current_file[name]\n");
	require_once(ROSTER_BASE."functions".DIRECTORY_SEPARATOR."luaparser.php");
	$lua_data = ParseLuaFile($current_file['tmp_name']);
	foreach($lua_data as $subtree_name=>$subtree_data){
		$subtree_function_call = array_search($subtree_name, $handled_trees);
		if($subtree_function_call!==false){
			debug_print("Processing $subtree_name.\n");
			$function_name = "Process".$subtree_function_call;
			print($function_name($subtree_data));
		} else {
			$ignored_tree = array_search($subtree_name, $ignored_trees);
			if($ignored_tree !== false){
				debug_print("No need to process $ignored_tree.\n");
			} else {
				print("No handler for ".$current_file['name']."[$subtree_name], ignoring.\n");
			}
		}
	}
}

$output = ob_get_clean();


// output works stand alone or using smarty system.
if($automaton){
	print($output);
} else {
	// ----[ Assign Page Title ]------------------------------------
	$tpl->assign('page_title', $roster_wordings[$roster_conf['lang']]['pagetitle_update']);
	// ----[ Assign output Array ]----------------------------------
	$tpl->assign('update_output', str_replace("\n", "<br/>\n", $output) );
	// ----[ Fetch the page ]---------------------------------------
	$display = $tpl->fetch('update.tpl');
}

// Functions specific to the upload script

/**
 * This is the routine to process a characterprofiler.lua file.
 *
 * @param array $data
 * @return string output of the function
 */
function ProcessCharacterProfile($data){
	global $roster_conf, $guild_handler;

	// We're going to need some handlers for other stuff. Let's go ahead and get them loaded now.
	global $global_handlers;
//	$global_handlers['skills'] = new SkillHandler();
	$global_handlers['talents'] = new TalentHandler();
	$global_handlers['reputation'] = new ReputationHandler();
    $global_handlers['spells'] = new SpellHandler();

	$retval = "";
	// Make sure we're talking about the right realm, for starters.
	foreach(GetConfigValue('allowed_guilds') as $realm_name => $guild_names){
		if(!isset($data[$realm_name])){
		    $retval.=("No characters in proper realm (".$realm_name.") to process.\n");
		    continue;
		} else {
		    $retval.="Processing characters on $realm_name.\n";
		}
		// Trim down to just the realm we care about.
		$realm_data = $data[$realm_name];

		// Slice off the guild data to deal with it separately from characters.
		if(isset($realm_data['Guild'])){
			$guild_data = $realm_data['Guild'];
		}

		// Ok, let's deal with Guild Information now. We'll refactor later.
		// First, put the guild in the guilds table if it doesn't exist.
		foreach($guild_names as $guild_name)
		{
			$res = db_query("SELECT * FROM ".ROSTER_GUILDTABLE." WHERE guild_name = '".escape($guild_name)."' AND realm='".escape($realm_name)."'");
			if($res->numRows()==0){
				// We need to add the guild to the db
				$guild_update = array("guild_name"=>$guild_name, "realm"=>$realm_name, "faction"=>$guild_data['Faction'],
				"guild_motd"=>$guild_data['Motd'], "guild_num_members"=>$guild_data['NumMembers'], "guild_dateupdatedutc"=>$guild_data['DateUTC'],
				"GPversion"=>$guild_data['GPversion'], "update_time"=>date("Y-m-d h:i:s")
				);
				db_query_insert_array(ROSTER_GUILDTABLE, array_keys($guild_update), $guild_update);
			} else {
				// TODO: update the guild record!
			}
		}

		// Get the guild ID, now that we know it.
		$guild_id = $guild_handler->GetGuildId($guild_data['Guild'], $realm_name);

		// Second, put all the characters in the members table
		foreach($guild_data['Members'] as $member_name=>$md){
			$res = db_query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE name = '".escape($member_name)."' AND guild_id = $guild_id");
			if($res->numRows()==0){
				if(!isset($md['Zone'])){
					$md['Zone'] = "";
				}
				$guild_update = array("name"=>$member_name, "guild_id"=>$guild_id, "class"=>$md['Class'], "level"=>$md['Level'], "note"=>$md['Note'], "guild_rank"=>$md['RankIndex'],
				"guild_title"=>$md['Rank'], "officer_note"=>$md['OfficerNote'], "zone"=>$md['Zone'], "group"=>$md['Group']);
				db_query_insert_array(ROSTER_MEMBERSTABLE, array_keys($guild_update), $guild_update);
			} else {
				// TODO: update the member record!
			}
		}

		for($i=0; $i<=count($realm_data); $i++)
		{
			$character_data = array_shift($realm_data);
			if(in_array($character_data['Guild']['GuildName'], $guild_names))
			{
				// Process the characters that belong to the currently updating guild.
				$retval.="--------------------\n";
				$character = new Character($character_data['Name'], $character_data['Guild']['GuildName'], $realm_name);
				$retval .= ($character->PerformUpdate($character_data));
				unset($character_data);
			}
		}
	}
	return($retval);
}

?>
