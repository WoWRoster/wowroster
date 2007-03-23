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

if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}


$query = "SELECT `c`.`name` member, `c`.`member_id`,`r`.`faction`, `r`.`name` fct_name, `r`.`value`, ".
	"( substring( `r`.`value`, 1, locate('/', `r`.`value`)-1) + 0 ) AS curr_rep, ".
	"( substring( `r`.`value`, locate('/', `r`.`value`)+1, length(`r`.`value`)-locate('/', `r`.`value`)) + 0 ) AS max_rep, ".
	"`r`.`standing` ".
	"FROM `".ROSTER_REPUTATIONTABLE."` r ".
	"INNER JOIN ".ROSTER_CHARACTERSTABLE." c ON `r`.`member_id` = `c`.`member_id`";

if( (isset($_REQUEST['factionfilter'])) && (($_REQUEST['factionfilter']) != 'All') )
	$query .= " AND `r`.`name` = '".$wowdb->escape($_REQUEST['factionfilter'])."'";


$query .=  " ORDER BY max_rep DESC, `r`.`standing` DESC, curr_rep DESC;";


$qry_fct = "SELECT DISTINCT(`name`) fct_name FROM ".ROSTER_REPUTATIONTABLE." ORDER BY `name`;";


if (isset($_REQUEST['factionfilter']))
{
	if ($roster_conf['sqldebug'])
		$content .= ("<!--$query-->\n");

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

}
if ($roster_conf['sqldebug'])
	$content .= ("<!--$query-->\n");

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

if( isset($_REQUEST['factionfilter']) )
{
	$ab = array();
	while($row = $wowdb->fetch_array($result))
	{
		$ab[] = array($row['member'], $row['faction'], $row['fct_name'], $row['standing'], $row['curr_rep'], $row['max_rep'], $row['standing'], $row['member_id']);
	}

	$borderTop = border('syellow', 'start', $ab['0']['1'].' - '.$ab['0']['2']);
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
	$striping_counter = 0;

	for($i = 0; $i <= count($ab); $i++)
	{
		$exalted .= replevel($wordings[$roster_conf['roster_lang']]['exalted']);
		$revered .= replevel($wordings[$roster_conf['roster_lang']]['revered']);
		$honored .= replevel($wordings[$roster_conf['roster_lang']]['honored']);
		$friendly .= replevel($wordings[$roster_conf['roster_lang']]['friendly']);
		$neutral .= replevel($wordings[$roster_conf['roster_lang']]['neutral']);
		$unfriendly .= replevel($wordings[$roster_conf['roster_lang']]['unfriendly']);
		$hostile .= replevel($wordings[$roster_conf['roster_lang']]['hostile']);
		$hated .= replevel($wordings[$roster_conf['roster_lang']]['hated']);
	}

	$content .=($exalted);
	$content .=($revered);
	$content .=($honored);
	$content .=($friendly);
	$content .=($neutral);
	$content .=($unfriendly);
	$content .=($hostile);
	$content .=($hated);

	$content .=($tableFooter);
	$content .= $borderBottom;
}



$wowdb->closeQuery($result);

function replevel($replevel)
{
	global $ab;
	global $i;
	global $striping_counter;

	if($ab[$i]['6'] == $replevel)
	{
		$rep .=('<tr class="membersRowColor'. (($striping_counter % 2) +1) ."\">\n");

		// Increment counter so rows are colored alternately
		++$striping_counter;

		$rep .=('<td class="membersRowCell"><a href="char.php?member='.$ab[$i]['7'].'">'.$ab[$i]['0'].'</a></td>');
		$rep .=('<td class="membersRowCell">'.$ab[$i]['3'].'</td>');
		$rep .=('<td class="membersRowRightCell">'.$ab[$i]['4'].' / '.$ab[$i]['5'].'</td>');
		$rep .=('</tr>');
	}
	return($rep);
}

$wowdb->free_result($result);
?>
