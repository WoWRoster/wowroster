<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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
 * Completely rewritten by vgjunkie 2006-09-14
 *
 ******************************/

 require(BASEDIR . 'modules/' . $module_name . '/settings.php');


//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	message_die( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

$header_title = $wordings[$roster_conf['roster_lang']]['professions'];
include_once(ROSTER_BASE.'roster_header.tpl');


// Get guild_id from guild info check above
$guildId = $guild_info['guild_id'];

include_once(ROSTER_BASE.'lib'.DIR_SEP.'menu.php');

// Build a list of "Skills" to look for
$inClause = "'";
foreach( $roster_conf['multilanguages'] as $lang )
{
	$inClause .= implode("', '",$tsArray[$lang]);
	$inClause .= "', '";
}
$inClause .= "'";

// If we don't want to show skills with a "1" value, uncomment this line (make option in config?)
$showNewSkill = " AND SUBSTRING_INDEX( s.skill_level, ':', 1 ) > 1 ";

// Gather a list of players that have the skills we are looking for
$query = "SELECT `s`.*, `p`.`name`, `p`.`clientLocale` FROM `".ROSTER_SKILLSTABLE."` s, `".ROSTER_PLAYERSTABLE."` p
	WHERE p.member_id = s.member_id
	AND p.guild_id = '".$guildId."'
	$showNewSkill
	AND skill_name IN ($inClause)
	ORDER BY s.skill_type,s.skill_name,(mid(skill_level FROM 1 FOR (locate(':', skill_level)-1)) + 0) DESC, p.name;";

//print $query;

$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);


############################### START OUTPUT ##############################

// Counter for row striping
$striping_counter = 0;
$last_value = 'some obscurely random string to keep me lazy.';


if( $wowdb->num_rows($result) )
{
	$id = 0;
	while( $row = $wowdb->fetch_assoc($result) )
	{
		$skill_name = $row['skill_name'];
		$skill_image = 'Interface/Icons/'.$wordings[$row['clientLocale']]['ts_iconArray'][$skill_name];
		$skill_image = '<div style="display:inline;float:left;"><img width="17" height="17" src="'.$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix'].'" alt="" /></div>';
		$skill_output = '<div style="cursor:pointer;width:370px;" onclick="showHide(\'table_'.$id.'\',\'img_'.$id.'\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');">
	'.$skill_image.'
	<div style="display:inline;float:right;"><img id="img_'.$id.'" src="'.$roster_conf['img_url'].'minus.gif" alt="" /></div>
'.$skill_name.'</div>';

		if( $last_value != $skill_name )
		{
			if( $striping_counter )
			{
				print '</table>';
				print border('sgray','end');
				print '<br />';
			}

			print border('sgray','start',$skill_output);
			print ('
<table border="0" cellpadding="0" cellspacing="0" class="bodyline" id="table_'.$id.'">
	<tr>
		<td class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['level'].'</td>
		<td class="membersHeaderRight" width="150">'.$wordings[$roster_conf['roster_lang']]['name'].'</td>
	</tr>
');

			$striping_counter = 0;
			$last_value = $skill_name;
		}

		// Increment counter so rows are colored alternately
		$stripe_counter = ( ( $striping_counter++ % 2 ) + 1 );
		$stripe_class = 'membersRow'.$stripe_counter;
		$stripe_class_right =  'membersRowRight'.$stripe_counter;

		// Setup some user row data
		$level_array = explode (':',$row['skill_level']);
		$levelpct = $level_array[0] / $level_array[1] * 100 ;
		settype( $levelpct, 'integer' );

		if ( !$levelpct )
		{
			$levelpct = 1;
		}

		print  ('
	<tr>
	<td class="'.$stripe_class.'">
		<div class="levelbarParent" style="width:200px;">
			<div class="levelbarChild">'.$level_array[0].'/'.$level_array[1].'</div>
		</div>
		<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$levelpct.'%">
					<img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt="">
				</td>
				<td width="'.(100-$levelpct).'%"></td>
			</tr>
		</table>
	</td>
	<td class="'.$stripe_class_right.'">
		<a href="char.php?name='.$row['name'].'&amp;server='.$roster_conf['server_name'].'&amp;action=recipes">'.$row['name'].'</a>
	</td>
	</tr>
');
		$id++;
	}

	print '</table>';
	print border('sgray','end');
	print '<br />';
}

include_once(ROSTER_BASE.'roster_footer.tpl');

?>