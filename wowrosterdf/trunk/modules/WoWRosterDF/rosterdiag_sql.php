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
 * This file contains and applies all SQL fixes from BETA till SQL BUILD[2]
 ******************************/

// This number must increment
$SQLBuild = 2;
$config_array_fixes = array();



// ALWAYS KEEP THIS TO UPDATE THE DB VERSION
$config_array_fixes['roster_dbver'] = "UPDATE `".ROSTER_CONFIGTABLE."` SET
`config_value` = '$SQLBuild'
WHERE `config_name` = 'roster_dbver' LIMIT 1 ;";


$errors=0;
if( $roster_conf['roster_dbver'] < $SQLBuild )
{
	$errors = apply_sql_fixes();
}

if ( $errors )
{
	// Something went wrong, otherwise we would have had no output
	echo "<br />".border('sred','start','SQL UPDATE ERROR Build['.$SQLBuild.']').'
	<table width="300" class="wowroster" cellspacing="0">'.
	$errors.
	'</table>'.
	border('sred','end')."<br />";
}


function apply_sql_fixes()
{
	global $roster_conf, $config_array_fixes, $wowdb;
	$returnvalue = 0;

	foreach ($config_array_fixes as $config_array_fix => $config_array_query)
	{
		if (!$config_fix_result = $wowdb->query($config_array_query))
		{
			$returnvalue = '<tr>
												<td class="membersRow'.((($rowstripe=0)%2)+1).'">$roster_conf[\''.$config_array_fix.'\']</td>
												<td class="membersRowRight'.((($rowstripe)%2)+1).'">'.$wowdb->error().'</td>
											</tr>';
		}
	}
	return $returnvalue;
}

?>
