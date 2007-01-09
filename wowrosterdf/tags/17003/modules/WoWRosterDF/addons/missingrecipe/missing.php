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
 //include_once('./modules/Forums/itemstats/eqdkp_itemstats.php');
if(isset($_GET['skill']))
{

	$skill = $_GET['skill'];

	$skill = mysql_real_escape_string($skill);
	switch($skill)
	{
		case 'Alchemy':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=171';
			break;
		case 'Blacksmithing':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=164';
			break;
		case 'Cooking':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=185';
			break;
		case 'Enchanting':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=333';
			break;
		case 'Engineering':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=202';
			break;
		case 'Firstaid':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=129';
			$skill = 'First Aid';
			break;
		case 'Leatherworking':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=165';
			break;
		case 'Tailoring':
			$url = 'http://wow.allakhazam.com/db/skill.html?line=197';
			break;
		default:
			$url ='';
			break;
	}
}

if (function_exists('curl_init'))
{
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$contents = curl_exec($ch);

	curl_close($ch);
}
else
{
	if(ini_get('allow_url_fopen') == 1)
	{
		$contents = file_get_contents($url);
	}
	else {
		return $wordings[$roster_conf['roster_lang']]['mr_curlerror'];
	}
}

preg_match_all('/(?<=\<tr class=\"[dl]r\"\>)((.(?!\<\/tr))*)/si',$contents,$matches);
foreach ($matches[0] as $nr => $recipe)
{
	preg_match_all('/(\<td[\w \\\'\"=]*\>)(.*)(?=\<\/td)/mi',$recipe,$lines)."\n";
 	$recipes[$nr]['icon'] = $lines[2][0];
 	$recipes[$nr]['icon'] = str_replace("src=\"/images/icons", "src=\"".$roster_conf['interface_url']."Interface/Icons", $recipes[$nr]['icon']);
 	$recipes[$nr]['icon'] = str_replace(".png", ".".$roster_conf['img_suffix']."\" alt=\"" , $recipes[$nr]['icon']);
 	preg_match('/(\<a[^\>]* href=\"([^\"]*)\"\>)([^<]*)/i',$lines[2][1],$name);
 	$recipes[$nr]['name'] = $name[3];
 	preg_match('/href=\"([^\"]*)\"/i',$lines[2][5],$link);
 	//$recipes[$nr]['components'] = $lines[2][4];
	$recipes[$nr]['i_link'] = $link[1];
 	$recipes[$nr]['category'] = $lines[2][2];
 	$recipes[$nr]['skill'] = $lines[2][3];
 	preg_match('/(\<a[^\>]* href=\"([^\"]*)\"\>)([^<]*)(.*(\<a[^\>]* href=\"([^\"]*)\"\>\<span[^\>]*\>)([^<]*))?/i',$lines[2][1],$name);
	$recipes[$nr]['r_link'] = $name[2];
	if (isset($name[6]))
	{
 	$recipes[$nr]['pat_link'] = $name[6];
 	$recipes[$nr]['pat_name'] = $name[7];
	}
}

$id = nametoid($_GET[cnameadd]);

$query = "select recipe_name from ".ROSTER_RECIPESTABLE." where member_id = '$id' and skill_name = '$skill';";

$results = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

while($row = $wowdb->fetch_array($results))
{
	$known[] = $row['recipe_name'];
}

if(!is_array($known))
{
	$output = "<br />".$wordings[$roster_conf['roster_lang']]['professionunknown'];
	echo $output;
	return;
}

if(!is_array($recipes))
{
	$output = "<br />".$wordings[$roster_conf['roster_lang']]['failedretrieve'];
	echo $output;
	return;
}

foreach($recipes as $nr => $keys)
{
	$allrecipes[] = $keys['name'];
}

$diff = array_diff($allrecipes, $known);

$skillsql = "select skill_level from ".ROSTER_SKILLSTABLE." where member_id = '$id' and skill_name = '$skill';";


if($skillres = $wowdb->query($skillsql)){
	$skillfet = $wowdb->fetch_assoc($skillres);
	list($skilllvl, $skillother) = explode(":", $skillfet['skill_level'], 2);
} else {
	$skilllvl = 0;
}

$content = '<br /><br />';
$content .= border('syellow', 'start', $wordings[$roster_conf['roster_lang']]['unknownrecipes']);
$content .= '
	<table width="100%" cellspacing="0" class="wowroster">
		<tr><td class="membersHeader">&nbsp;</td><td class="membersHeader">Name</td><td class="membersHeader">Skill Level</td></tr>';

foreach($diff as $key => $value)
{

	if(strpos($recipes[$key]['icon'], "INV_Misc_QuestionMark"))
	{
		continue;
	}
	++$striping_counter;

	$link = "http://wow.allakhazam.com".$recipes[$key]['i_link'];
	if($recipes[$key]['pat_link'])
	{
		$plink = "http://wow.allakhazam.com".$recipes[$key]['pat_link'];
		$pname = $recipes[$key]['pat_name'];
		$ptotal = "<br /><a href=\"$plink\">$pname</a>";
	}

	if($recipes[$key]['skill'])
	{
		if($recipes[$key]['skill'] > $skilllvl)
		{
			$skillcolor = "hskillcolor";
		}
		else
		{
			if($recipes[$key]['skill'] < $skilllvl)
			{
				$skillcolor = "lskillcolor";
			}
			else {
				$skillcolor = "mskillcolor";
			}

		}
	}
	$content .=('
		<tr>
			<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$recipes[$key]['icon'].'</td>
			<td class="membersRow'. (($striping_counter % 2) +1) .'"><a href="'.$link.'"> '.$value.'</a>'.(isset($ptotal) ? $ptotal : "").'</td>
			<td class="membersRow'. (($striping_counter % 2) +1) .'">'.(isset($skillcolor) ? "<span class=\"$skillcolor\">" : "").$recipes[$key]['skill'].(isset($skillcolor) ? "</span>" : "").'</td>
		</tr>');

	unset($ptotal);
	unset($skillcolor);
}
$content .= '</table>'.border('syellow', 'end');


echo($content);



function nametoid($name)
{
	global $wowdb;

	if(ctype_digit($name))
	{
		return $name;
	}

	$sql = "SELECT member_id from `".ROSTER_MEMBERSTABLE."` where name = '".mysql_real_escape_string($name)."' LIMIT 1";

	$results = $wowdb->query($sql) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	if($results)
	{
		$member_id_raw = $wowdb->fetch_assoc($results);
		$member_id = $member_id_raw['member_id'];
		return $member_id;
	}
	else {
		return FALSE;
	}
}

?>