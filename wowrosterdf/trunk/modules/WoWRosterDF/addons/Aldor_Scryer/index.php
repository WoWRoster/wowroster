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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}


$header_title = $wordings[$roster_conf['roster_lang']]['menustats'];



// --[ Build professions select box ]--
$qry_fct = 	"SELECT distinct(skill_name) fct_name FROM ".ROSTER_SKILLSTABLE." ".
		"WHERE skill_type='".$wordings['frFR']['professions']."' ".
		"OR skill_type='".$wordings['enUS']['professions']."' ".
		"OR skill_type='".$wordings['deDE']['professions']."' ".
		"ORDER BY skill_name";

$result_fct = $wowdb->query($qry_fct) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_fct);


$choiceForm = '<form action="'.$script_filename.'" method="POST">
	'.$wordings[$roster_conf['roster_lang']]['Professions'].' : 
	<select name="Professionsfilter" onchange="top.location.href=this.options[this.selectedIndex].value">
		<option value="'.getlink($script_filename.'&amp;Professionsfilter=All').'">'.$wordings[$roster_conf['roster_lang']]['AS_Select_Professions'].'</option>'."\n";
while($row_fct = $wowdb->fetch_array($result_fct))
{
	if( $_REQUEST['Professionsfilter'] == $row_fct['fct_name'] )
		$choiceForm .= '		<option value="'.getlink($script_filename.'&amp;Professionsfilter='.$row_fct['fct_name']).'" selected="selected">'.$row_fct['fct_name']."</option>\n";
	else
		$choiceForm .= '		<option value="'.getlink($script_filename.'&amp;Professionsfilter='.$row_fct['fct_name']).'">'.$row_fct['fct_name']."</option>\n";
}

$wowdb->free_result($result_fct);

$choiceForm .= '	</select>
</form><br />';

$content .= ($choiceForm);

if( (isset($_REQUEST['Professionsfilter'])) && (($_REQUEST['Professionsfilter']) != 'All') )
		$content .="<h1>".$_REQUEST['Professionsfilter']."</h1>";


$content.="<table style=\"text-align: left; margin-left: auto; margin-right: auto;\" border=\"0\" cellpadding=\"10\" cellspacing=\"10\">
  <tbody>
    <tr>
      <td style=\"text-align: center; vertical-align: top;\">";
$AS_faction=$wordings[$roster_conf['roster_lang']]['Aldor'];
require($addonDir.'faction.php');
$content.="</td>
      <td style=\"text-align: center; vertical-align: top;\">";
require($addonDir.'neutre.php');
$content.="</td>
      <td style=\"text-align: center; vertical-align: top;\">";
$AS_faction=$wordings[$roster_conf['roster_lang']]['Scryer'];
require($addonDir.'faction.php');
$content.="</td>
    </tr>
  </tbody>
</table>
";

echo $content;

?>
