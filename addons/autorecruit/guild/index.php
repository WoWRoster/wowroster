<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays the guild information text
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: index.php 1241 2007-08-16 06:06:25Z Zanix $
 * @link       http://www.wowroster.net
 * @package    GuildInfo
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['autorecruit'];

// Get Current Guild ID
$guildset = $_GET['guild'];

// Get Guilds Recruitment Settings 

if( $guildset )
{
$sqlstring = 'SELECT * FROM ' . $roster->db->table('recruitment',$addon['basename']) . ' WHERE `guild_id` = ' . $guildset ;
$result = $roster->db->query($sqlstring);
if( $result )
{
$alldata = $roster->db->fetch_all($result);

if ($alldata[0]['min_level'])
	{
	}
else
	{ $alldata[0]['min_level'] = '70';
		roster_die($roster->locale->act['ar_page']['no_setup'],$roster->locale->act['ar_page']['title']);
	}

// Get Class Names
foreach( $roster->multilanguages as $locale )
	{
		$druids[] = $roster->locale->wordings[$locale]['Druid'];
    $hunters[] = $roster->locale->wordings[$locale]['Hunter'];
    $mages[] = $roster->locale->wordings[$locale]['Mage'];
		$paladins[] = $roster->locale->wordings[$locale]['Paladin'];
    $priests[] = $roster->locale->wordings[$locale]['Priest'];
    $rogues[] = $roster->locale->wordings[$locale]['Rogue'];
		$shamans[] = $roster->locale->wordings[$locale]['Shaman'];
    $warlocks[] = $roster->locale->wordings[$locale]['Warlock'];
    $warriors[] = $roster->locale->wordings[$locale]['Warrior'];
	}
$druids = implode("','",$druids);
$hunters = implode("','",$hunters);
$mages = implode("','",$mages);
$paladins = implode("','",$paladins);
$priests = implode("','",$priests);
$rogues = implode("','",$rogues);
$shamans = implode("','",$shamans);
$warlocks = implode("','",$warlocks);
$warriors = implode("','",$warriors);

// Count Class SQL
$druid_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $druids . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$hunter_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $hunters . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$mage_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $mages . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$paladin_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $paladins . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$priest_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $priests . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$rogue_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $rogues . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$shaman_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $shamans . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$warlock_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $warlocks . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];
$warrior_sql = "SELECT count('member_id') FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $warriors . "') AND `guild_id` =  " . $guildset . " and `level` >= " . $alldata[0]['min_level'];

// Actual Counting
$druid_count = $roster->db->query_first($druid_sql);
$hunter_count = $roster->db->query_first($hunter_sql);
$mage_count = $roster->db->query_first($mage_sql);
$paladin_count = $roster->db->query_first($paladin_sql);
$priest_count = $roster->db->query_first($priest_sql);
$rogue_count = $roster->db->query_first($rogue_sql);
$shaman_count = $roster->db->query_first($shaman_sql);
$warlock_count = $roster->db->query_first($warlock_sql);
$warrior_count = $roster->db->query_first($warrior_sql);

}
else
{
roster_die('There was an error in the query','ERROR');
}
// Setup Application Link
$apply = '<a href="' . $alldata[0]['app_link'] . '">Apply</a>';

// Set Open or Closed for Recruiting

if ($alldata[0]['max_druid'] > $druid_count)
	{$druid_status = $apply;}
	else
	{$druid_status = "closed";}
	
if ($alldata[0]['max_hunter'] > $hunter_count)
	{$hunter_status = $apply;}
	else
	{$hunter_status = "closed";}

if ($alldata[0]['max_mage'] > $mage_count)
	{$mage_status = $apply;}
	else
	{$mage_status = "closed";}
	
if ($alldata[0]['max_paladin'] > $paladin_count)
	{$paladin_status = $apply;}
	else
	{$paladin_status = "closed";}
	
if ($alldata[0]['max_priest'] > $priest_count)
	{$priest_status = $apply;}
	else
	{$priest_status = "closed";}

if ($alldata[0]['max_rogue'] > $rogue_count)
	{$rogue_status = $apply;}
	else
	{$rogue_status = "closed";}
	
if ($alldata[0]['max_shaman'] > $shaman_count)
	{$shaman_status = $apply;}
	else
	{$shaman_status = "closed";}
	
if ($alldata[0]['max_warlock'] > $warlock_count)
	{$warlock_status = $apply;}
	else
	{$warlock_status = "closed";}

if ($alldata[0]['max_warrior'] > $warrior_count)
	{$warrior_status = $apply;}
	else
	{$warrior_status = "closed";}

// Setup Class Images
$druid_icon = '<img src="' . $roster->config['img_url'] . 'class/druid_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$hunter_icon = '<img src="' . $roster->config['img_url'] . 'class/hunter_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$mage_icon = '<img src="' . $roster->config['img_url'] . 'class/mage_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$paladin_icon = '<img src="' . $roster->config['img_url'] . 'class/paladin_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$priest_icon = '<img src="' . $roster->config['img_url'] . 'class/priest_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$rogue_icon = '<img src="' . $roster->config['img_url'] . 'class/rogue_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$shaman_icon = '<img src="' . $roster->config['img_url'] . 'class/shaman_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$warlock_icon = '<img src="' . $roster->config['img_url'] . 'class/warlock_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';
$warrior_icon = '<img src="' . $roster->config['img_url'] . 'class/warrior_icon.jpg" width="20px" height="20px" align="absmiddle" /> &nbsp';


// Start Building the Page

	$body = "<br /><div id=\"char_disp\">\n" . border('sblue','start',$roster->locale->act['ar_page']['title'] ) . "\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

	$body .= '
	<tr>
		<th class="membersHeader">' . $roster->locale->act['ar_page']['class'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['ar_page']['status'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['ar_page']['apply'] . '</th>
	</tr>
	<tr>
		<td class="membersRow2">' . $druid_icon . $roster->locale->act['Druid'] . '</td>
		<td class="membersRow2">' . $druid_count . ' / ' . $alldata[0]['max_druid'] . '</td>
		<td class="membersRow2">' . $druid_status . '</td>
	</tr>
	<tr>
		<td class="membersRow1">' . $hunter_icon . $roster->locale->act['Hunter'] . '</td>
		<td class="membersRow1">' . $hunter_count . ' / ' . $alldata[0]['max_hunter'] . '</td>
		<td class="membersRow1">' . $hunter_status . '</td>
	</tr>
	<tr>	
		<td class="membersRow2">' . $mage_icon . $roster->locale->act['Mage'] . '</td>
		<td class="membersRow2">' . $mage_count . ' / ' . $alldata[0]['max_mage'] . '</td>
		<td class="membersRow2">' . $mage_status . '</td>
	</tr>
	<tr>	
		<td class="membersRow1">' . $paladin_icon . $roster->locale->act['Paladin'] . '</td>
		<td class="membersRow1">' . $paladin_count . ' / ' . $alldata[0]['max_paladin'] . '</td>
		<td class="membersRow1">' . $paladin_status . '</td>
	</tr>
	<tr>	
		<td class="membersRow2">' . $priest_icon . $roster->locale->act['Priest'] . '</td>
		<td class="membersRow2">' . $priest_count . ' / ' . $alldata[0]['max_priest'] . '</td>
		<td class="membersRow2">' . $priest_status . '</td>
	</tr>
	<tr>	
		<td class="membersRow1">' . $rogue_icon . $roster->locale->act['Rogue'] . '</td>
		<td class="membersRow1">' . $rogue_count . ' / ' . $alldata[0]['max_rogue'] . '</td>
		<td class="membersRow1">' . $rogue_status . '</td>
	</tr>
	<tr>	
		<td class="membersRow2">' . $shaman_icon . $roster->locale->act['Shaman'] . '</td>
		<td class="membersRow2">' . $shaman_count . ' / ' . $alldata[0]['max_shaman'] . '</td>
		<td class="membersRow2">' . $shaman_status . '</td>
	</tr>
	<tr>	
		<td class="membersRow1">' . $warlock_icon . $roster->locale->act['Warlock'] . '</td>
		<td class="membersRow1">' . $warlock_count . ' / ' . $alldata[0]['max_warlock'] . '</td>
		<td class="membersRow1">' . $warlock_status . '</td>
	</tr>
	<tr>	
		<td class="membersRow2">' . $warrior_icon . $roster->locale->act['Warrior'] . '</td>
		<td class="membersRow2">' . $warrior_count . ' / ' . $alldata[0]['max_warrior'] . '</td>
		<td class="membersRow2">' . $warrior_status . '</td>
	</tr>
	';
	$body .= "</table>\n" . border('sblue','end') . "\n</div>\n";
}
else
{
	$body = "<br /><div id=\"char_disp\">" . border('sred','start',$roster->locale->act['ar_page']['no_guild']) . $roster->locale->act['ar_page']['title'] . border('sred','end') . "\n</div>\n";
}



//Make Page
echo $body;