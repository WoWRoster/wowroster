<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<link rel="stylesheet" href="modules/wowrosterdf/addons/RaidTracker/itemstats/templates/itemstats.css" type="text/css">

</body>
</html>

<?php

//include_once('addons/RaidTracker/itemstats/phpbb_itemstats.php');
include_once(BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/functions.php');

$display = $_REQUEST['display'];
$raid = $_REQUEST['raid'];
$color = $_REQUEST['color'];

// Show addon menu
if($display == 'history'){
	echo border('syellow','start',$rt_wordings[$roster_conf['roster_lang']]['LootHistory']);
}elseif($display == 'bosses'){
	echo border('syellow','start',$rt_wordings[$roster_conf['roster_lang']]['BossProgress']);
}elseif($display == 'summary'){
	echo border('syellow','start',$rt_wordings[$roster_conf['roster_lang']]['Summary']);
}elseif($display == 'attendance'){
	echo border('syellow','start',$rt_wordings[$roster_conf['roster_lang']]['Attendance']);
}else{
	if($raid != '')
	{
		$query = 'SELECT raidid, zone FROM `'.$db_prefix.'raids` WHERE raidnum = '.$raid;
		if ($roster_conf['sqldebug'])
		{
			print "<!-- $query -->\n";
		}
		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		$row = $wowdb->fetch_array($result);
		
		// Set title for selected raid
		$title = getZoneIcon($row['zone']);
		if($row['zone'] != 'RandomRaid'){
			$title .= $rt_wordings[$roster_conf['roster_lang']]['Zones'][$row['zone']].'</a></td>';
		}else{
			$title .= $rt_wordings[$roster_conf['roster_lang']][$row['zone']].'</a></td>';
		}
		$title .= ' <span style="font-size:10px;">(' . date($addon_conf['RaidTracker']['DateView'], strtotime($row['raidid'])) . ')</span>';
		echo border('syellow','start',$title);
	}
	else
	{
		echo border('syellow','start',$wordings[$roster_conf['roster_lang']]['RaidTracker']);
	}
}

echo '<table cellpadding="0" cellspacing="0" class="membersList"><tr>';
echo '<td class="membersHeader"><a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker">'.$wordings[$roster_conf['roster_lang']]['RaidTracker'].'</a></td>';
echo '<td class="membersHeader"><a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker&amp;display=history">'.$rt_wordings[$roster_conf['roster_lang']]['LootHistory'].'</a></td>';
echo '<td class="membersHeader"><a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker&amp;display=bosses">'.$rt_wordings[$roster_conf['roster_lang']]['BossProgress'].'</a></td>';
echo '<td class="membersHeader"><a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker&amp;display=attendance">'.$rt_wordings[$roster_conf['roster_lang']]['Attendance'].'</a></td>';
echo '<td class="membersHeaderRight"><a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker&amp;display=summary">'.$rt_wordings[$roster_conf['roster_lang']]['Summary'].'</a></td>';
echo '</tr></table>';
echo border('syellow','end');
echo '<br/>';

$result = $wowdb->query("SELECT * FROM ".$db_prefix."raids");
$row = $wowdb->fetch_assoc($result);
$reinstall = (!$result?array_key_exists('new_recflag',$row):0);

// Check if tables already excists
if( !$result || $reinstall){
	include_once BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/install_db.php';
}else{
	if($display == '')
	{
		if($raid != '')
		{
			require BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/raidview.php';	
		}else{
			require BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/raidlist.php';
		}
	}
	if($display == 'history')
	{
		if($color != ''){
			require BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/lootview.php';
		}else{
			require BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/loothistory.php';
		}
	}
	if($display == 'bosses')
	{
		require BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/bosses.php';
	}
	if($display == 'summary')
	{
		require BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/summary.php';
	}
	if($display == 'attendance')
	{
		require BASEDIR.'modules/'.$module_name.'/addons/RaidTracker/attendance.php';
	}
}

?>