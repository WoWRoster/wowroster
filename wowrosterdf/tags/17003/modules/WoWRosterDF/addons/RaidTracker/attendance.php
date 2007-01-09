<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }

echo border('syellow', 'start', $title);
		
	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" width="250px" class="membersList">';
	
	// Display the header of the table
	echo '<tr>';
	echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Name'].'</th>';
	foreach($rt_wordings['RaidTracker']['ZoneIcons'] as $zonename => $zone){
		if($zone != 'outdoor'){
			echo '<th class="membersHeader">'.$zone.'</th>';
		}
	}
	echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Total'].'</th>';
	echo '</tr>';
		
	// Get all raiders
	$query = 'SELECT name, class FROM `'.$db_prefix.'raidmembers` ORDER BY name ASC';
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	
	$rownum=1;
	while ($row = $wowdb->fetch_array($result)) {
	
		// Check if char is in guild
		$gquery = 'SELECT member_id FROM '.ROSTER_MEMBERSTABLE.' WHERE name= \''.$row['name'].'\'';
		if ($roster_conf['sqldebug'])
		{
			print "<!-- $gquery -->\n";
		}
		$gid_result = $wowdb->query($gquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$gquery);
		$gid = $wowdb->fetch_array($gid_result);
		if($gid[0] != ''){
			// Open a new Row
			echo '<tr>';
			echo '<td class="membersRow'.$rownum.'"> '.getClass($row['name']);
			// Check if charinfo exists
			$cquery = 'SELECT member_id FROM '.ROSTER_PLAYERSTABLE.' WHERE name= \''.$row['name'].'\'';
			if ($roster_conf['sqldebug'])
			{
				print "<!-- $cquery -->\n";
			}
			$cid_result = $wowdb->query($cquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$cquery);
			$cid = $wowdb->fetch_array($cid_result);
			if($id[0] != ''){
				echo ' <a href="index.php?name='.$module_name.'&amp;file=char&amp;name='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a></td>';
			}else{
				echo ' '.$row['name'].'</td>';
			}
			
			$raid_total = 0;
			
			// Loop zones and display count
			foreach($rt_wordings['RaidTracker']['ZoneIcons'] as $zonename => $zone){
				if($zone != 'outdoor'){
					$raid_count = 0;
				
					$query_raids = "SELECT raidnum FROM `".$db_prefix."raids` WHERE zone ='".addslashes($zonename)."'";
					if ($roster_conf['sqldebug'])
					{
						print "<!-- $query_raids -->\n";
					}
					$result_raids = $wowdb->query($query_raids) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_raids);
					while ($row_raids = $wowdb->fetch_array($result_raids)){
						$query_member = "SELECT name FROM `".$db_prefix."raidjoins` WHERE name = '".$row['name']."' AND raidnum = ".$row_raids['raidnum'];
						if ($roster_conf['sqldebug'])
						{
							print "<!-- $query_member -->\n";
						}
						$result_member = $wowdb->query($query_member) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_member);
						$row_member = $wowdb->fetch_array($result_member);
						if($row_member['name'] == $row['name']){
							$raid_count = $raid_count + 1;
						}
					}
					echo '<td style="text-align:center;" class="membersRow'.$rownum.'"> '.($raid_count != 0?"x".$raid_count:"/")."</td>";
				}
				$raid_total += $raid_count;
			}
			
			echo '<td style="text-align:center;" class="membersRowRight'.$rownum.'"> '.($raid_total != 0?$raid_total:"/")."</td>";
			
			// Close the Row
			echo '</tr>';
			switch ($rownum) {
			case 1:
				$rownum=2;
				break;
			default:
				$rownum=1;
			}
		}
	}
	
	// Close the table
	echo '</table>';	
	echo border('syellow','end');
	echo '<br/>';

$wowdb->free_result($result);

?>