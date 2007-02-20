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

	$lang = $roster_conf['roster_lang'];
	$neutre=0;

	$query = "SELECT distinct m.member_id, m.name member, p.server, r.standing ".
		"FROM `".ROSTER_REPUTATIONTABLE."` r, ".ROSTER_MEMBERSTABLE." m, ".ROSTER_PLAYERSTABLE." p, `".ROSTER_SKILLSTABLE."` s ".
		"WHERE r.member_id = m.member_id ".
		"AND p.member_id = m.member_id ".
		"AND s.member_id = m.member_id ".
		"AND (r.name='".addslashes($wordings[$roster_conf['roster_lang']]['Aldor'])."' OR r.name='".addslashes($wordings[$roster_conf['roster_lang']]['Scryer'])."') ";

	if( (isset($_REQUEST['Professionsfilter'])) && (($_REQUEST['Professionsfilter']) != 'All') )
		$query .= " AND s.skill_name='".$_REQUEST['Professionsfilter']."'";

	$query .= "ORDER BY m.name DESC";

	//echo $query;

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error', basename(__FILE__),__LINE__,$query);

	$ab = array();
	$striping_counter = 1;
	$rep = array();

	

	while($row = $wowdb->fetch_array($result))
	{
		$category = $row['faction'];
		$faction = $row['fct_name'];

		$query = "SELECT s.skill_name, s.skill_level ".
			"FROM `".ROSTER_SKILLSTABLE."` s ".
			"WHERE s.member_id = ".$row['member_id']."  ".
			"AND s.skill_type='".$wordings[$lang]['professions']."'";
		//echo $query."<br>";

		$result2 = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error', basename(__FILE__),__LINE__,$query);
		
		$cell_value = '';
		while($row2 = $wowdb->fetch_array($result2))
		{
			$toolTip = str_replace(':','/',$row2['skill_level']);
			$toolTiph = $row2['skill_name'];
			$skill_image = 'Interface/Icons/'.$wordings[$lang]['ts_iconArray'][$row2['skill_name']];

			$cell_value .= "<img class=\"membersRowimg\" width=\"".$roster_conf['index_iconsize']."\" height=\"".$roster_conf['index_iconsize']."\" src=\"".$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix']."\" alt=\"\" ".makeOverlib($toolTip,$toolTiph,'',2,'',',RIGHT,WRAP')." />\n";
		}

		// Increment counter so rows are colored alternately
		++$striping_counter;

		$rep[$row['standing']] .=('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");
		$rep[$row['standing']] .=('<td class="membersRow'. (($striping_counter % 2) +1) .'"><a href="'.getlink($module_name.'&amp;file=char&amp;cname='.$row['member'].'&amp;server='.$row['server']).'">'.$row['member'].'</a></td>');
		$rep[$row['standing']] .=('<td class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$cell_value.'</td>');
		$rep[$row['standing']] .=('</tr>');
		
		$neutre=1;
	}

	$wowdb->free_result($result);
	
	if($neutre==1)
	{
		$borderTop = border('syellow', 'start', $wordings[$roster_conf['roster_lang']]['neutral']);
		$tableHeader = '<table width="100%" cellspacing="0" class="bodyline">';
		$tableHeaderRow = '	<tr>
			<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['rep_name'].'</th>
			<th class="membersHeaderRight"> '.$wordings[$roster_conf['roster_lang']]['Professions'].'</th>
			</tr>';
		$borderBottom = border('syellow', 'end');
		$tableFooter = '</table>';
	
		$content .=($borderTop);
		$content .=($tableHeader);
		$content .=($tableHeaderRow);
	
	/*	$content .=($rep[$wordings[$roster_conf['roster_lang']]['exalted']]);
		$content .=($rep[$wordings[$roster_conf['roster_lang']]['revered']]);
		$content .=($rep[$wordings[$roster_conf['roster_lang']]['honored']]);
		$content .=($rep[$wordings[$roster_conf['roster_lang']]['friendly']]);*/
		$content .=($rep[$wordings[$roster_conf['roster_lang']]['neutral']]);
		$content .=($rep[$wordings[$roster_conf['roster_lang']]['unfriendly']]);
	/*	$content .=($rep[$wordings[$roster_conf['roster_lang']]['hostile']]);
		$content .=($rep[$wordings[$roster_conf['roster_lang']]['hated']]);*/
	
		$content .=($tableFooter);
		$content .= $borderBottom;
	}
