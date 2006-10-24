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

$name = stripslashes($_GET['cnameadd']);
$skill = $_GET['skill'];

$professions = array(
"Alchemy" => $wordings[$roster_conf['roster_lang']]['Alchemy'],
"Blacksmithing" => $wordings[$roster_conf['roster_lang']]['Blacksmithing'],
"Cooking" => $wordings[$roster_conf['roster_lang']]['Cooking'],
"Enchanting" => $wordings[$roster_conf['roster_lang']]['Enchanting'],
"Engineering" => $wordings[$roster_conf['roster_lang']]['Engineering'],
"Firstaid" => $wordings[$roster_conf['roster_lang']]['First Aid'],
"Leatherworking" => $wordings[$roster_conf['roster_lang']]['Leatherworking'],
"Tailoring" => $wordings[$roster_conf['roster_lang']]['Tailoring']);

$form = "<br /><form method='get'>
<input type='hidden' name='name' value=".$module_name.">
<input type='hidden' name='file' value='addon'>
<input type='hidden' name='roster_addon_name' value='missingrecipe'>";
//Profession Drop-Down
$form .= $wordings[$roster_conf['roster_lang']]['profession'].": <select name='skill'>";
foreach($professions as $skill => $wording)
{
	if($skill == $_GET['skill'])
	{
		$form .= "<option value='$skill' SELECTED>$wording\n";
	}
	else {
		$form .= "<option value='$skill'>$wording\n";
	}
}
$form .= "</select><br /><br />";

$form .= $wordings[$roster_conf['roster_lang']]['name'].": <select name='cnameadd'>";

//Name Dropdown
$query = "select `name` from ".ROSTER_PLAYERSTABLE.";";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

while($row = $wowdb->fetch_array($result))
{
	if($row['name'] == $name)
	{
		$form .= "<option value='".$row['name']."' SELECTED>".$row['name']."\n";
	}
	else {
		$form .= "<option value='".$row['name']."'>".$row['name']."\n";
	}
}

$form .="</select><br /><br /><center><input type='submit'></center></form><br />";
echo border('sorange','start',$wordings[$roster_conf['roster_lang']]['missingrecipes_addon']);
echo($form);
echo border('sorange','end');
if(isset($_GET['skill']))
{
	include(ROSTER_BASE.'addons/missingrecipe/missing.php');
}
?>