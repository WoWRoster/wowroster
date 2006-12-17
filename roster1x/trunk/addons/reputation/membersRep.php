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

// --[ Build faction select box ]--
$qry_fct = "SELECT distinct(name) fct_name FROM ".ROSTER_REPUTATIONTABLE." ORDER BY name";

$result_fct = $wowdb->query($qry_fct) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_fct);

$choiceForm = '<form action="'.$script_filename.'" method="POST">
	'.$wordings[$roster_conf['roster_lang']]['faction_filter'].'
	<select name="factionfilter" onchange="top.location.href=this.options[this.selectedIndex].value">
		<option value="">Select Faction....</option>'."\n";

while($row_fct = $wowdb->fetch_array($result_fct))
{
	if( $_REQUEST['factionfilter'] == $row_fct['fct_name'] )
		$choiceForm .= '		<option value="'.$script_filename.'&amp;factionfilter='.$row_fct['fct_name'].'" selected="selected">'.$row_fct['fct_name']."</option>\n";
	else
		$choiceForm .= '		<option value="'.$script_filename.'&amp;factionfilter='.$row_fct['fct_name'].'">'.$row_fct['fct_name']."</option>\n";
}

$wowdb->free_result($result_fct);

$choiceForm .= '	</select>
</form><br />';

$content .= ($choiceForm);

// --[ If a faction is selected, build the box with rep levels ]--
if( isset($_REQUEST['factionfilter']) )
{
	$query = "SELECT m.name member, r.faction, r.name fct_name, r.value, ".
		"( substring( r.value, 1, locate('/', r.value)-1) + 0 ) AS curr_rep, ".
		"( substring( r.value, locate('/', r.value)+1, length(r.value)-locate('/', r.value)) + 0 ) AS max_rep, ".
		"r.standing ".
		"FROM `".ROSTER_REPUTATIONTABLE."` r, ".ROSTER_MEMBERSTABLE." m ".
		"WHERE r.member_id = m.member_id";

	if( (isset($_REQUEST['factionfilter'])) && (($_REQUEST['factionfilter']) != 'All') )
		$query .= " AND r.name='".$_REQUEST['factionfilter']."'";

	$query .=  " ORDER BY max_rep desc, r.standing DESC, curr_rep DESC";


	if (isset($_REQUEST['factionfilter']))
	{
		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	}

	$ab = array();
	$striping_counter = 1;
	$rep = array();

	while($row = $wowdb->fetch_array($result))
	{
		$category = $row['faction'];
		$faction = $row['fct_name'];

		// Increment counter so rows are colored alternately
		++$striping_counter;

		$rep[$row['standing']] .=('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");
		$rep[$row['standing']] .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['member'].'</td>');
		$rep[$row['standing']] .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['standing'].'</td>');
		$rep[$row['standing']] .=('<td class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$row['curr_rep'].' / '.$row['max_rep'].'</td>');
		$rep[$row['standing']] .=('</tr>');
	}

	$wowdb->free_result($result);

	$borderTop = border('syellow', 'start', $category.' - '.$faction);
	$tableHeader = '<table width="100%" cellspacing="0" class="bodyline">';
	$tableHeaderRow = '	<tr>
		<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['rep_name'].'</th>
		<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['rep_status'].'</th>
		<th class="membersHeaderRight">'.$wordings[$roster_conf['roster_lang']]['rep_value'].' / '.$wordings[$roster_conf['roster_lang']]['rep_max'].'</th>
		</tr>';
	$borderBottom = border('syellow', 'end');
	$tableFooter = '</table>';

	$content .=($borderTop);
	$content .=($tableHeader);
	$content .=($tableHeaderRow);

	$content .=($rep[$wordings[$roster_conf['roster_lang']]['exalted']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['revered']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['honored']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['friendly']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['neutral']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['unfriendly']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['hostile']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['hated']]);

	$content .=($tableFooter);
	$content .= $borderBottom;
}

?>
