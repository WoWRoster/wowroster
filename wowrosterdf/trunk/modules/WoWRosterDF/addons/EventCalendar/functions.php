<?php
$versions['versionDate']['eventcalendar'] = '$Date: 2006/08/28 $'; 
$versions['versionRev']['eventcalendar'] = '$Revision: 1.0 $';
$versions['versionAuthor']['eventcalendar'] = '$Author: PoloDude $';

function getNoteIcon($note){
	global $wowdb, $roster_conf, $wordings, $db_prefix, $db;
	
	$icon_value = '';
	if($note != ''){
		$icon_value = '<img class="membersRowimg" src="./modules/wowrosterdf/addons/EventCalendar/img/note.gif" alt="'.$note.'" onmouseover="return overlib(\''.addslashes($note).'\');" onmouseout="return nd();" />';
	}else{
		$icon_value = '<img class="membersRowimg" src="./modules/wowrosterdf/addons/EventCalendar/img/no_note.gif" alt="'.$note.'" />';
	}
	return $icon_value;
}

function getClassIcon($class){
	global $wowdb, $roster_conf, $wordings, $db_prefix;
	
	// Class Icon
	foreach ($roster_conf['multilanguages'] as $language)
	{
	$icon_name = $wordings[$language]['class_iconArray'][$class];
	if( strlen($icon_name) > 0 ) break;
	}
	$icon_name = './images/wowrosterdf/Interface/Icons/'.$icon_name;
	
	// Class coloring
	$icon_value = '<img class="membersRowimg" width="'.$roster_conf['index_iconsize'].'" height="'.$roster_conf['index_iconsize'].'" src="'.$icon_name.'.jpg" alt="'.$icon_name.'" /> ';
	return $icon_value;
}

function checkReset($day,$month,$year){
	global $wowdb, $roster_conf, $wordings;
	//$start = strtotime(date("Y-m-d G:i:s"));
	$start = mktime ( 3, 0, 0, 1, 5, 2006);
	$tag = mktime ( 3, 0, 0, $month, $day, $year);
	
	$start = $start + ($wordings['EventCalendar']['ResetOffset']*60*60*24);
	unset ($daycount);
	$daycount = (($tag - $start)/(60*60*24))+1;
	
	if (($daycount%7)==0){ $reset[]="40"; }
	if ((($daycount+1)%5)==0){ $reset[]="Onx"; }
	if (($daycount%3)==0){ $reset[]="20"; }
	
	return $reset;
}

function resetIcon($type){
	global $wowdb, $roster_conf, $wordings, $rc_wordings, $db;
	
	switch ($type) {
		case 40:
			echo ('<img src="'.$roster_conf['roster_dir'].'/addons/EventCalendar/img/mc.gif" />');
			echo ('<img src="'.$roster_conf['roster_dir'].'/addons/EventCalendar/img/bwl.gif" />');
			echo ('<img src="'.$roster_conf['roster_dir'].'/addons/EventCalendar/img/aq40.gif" />');
			echo ('<img src="'.$roster_conf['roster_dir'].'/addons/EventCalendar/img/nax.gif" />');
		break;
		case 20:
			echo ('<img src="'.$roster_conf['roster_dir'].'/addons/EventCalendar/img/zg.gif" />');
			echo ('<img src="'.$roster_conf['roster_dir'].'/addons/EventCalendar/img/aq20.gif" />');
		break;
		case "Onx":
			echo ('<img src="'.$roster_conf['roster_dir'].'/addons/EventCalendar/img/onx.gif" />');
		break;
	}
}

// Eventlist Functions
function getTitularCount($eventid){
	global $wowdb, $roster_conf, $wordings, $db_prefix;
	
	$query = 'SELECT name FROM `'.$db_prefix.'event_subscribers` WHERE status = "Y" AND eventid = '.$eventid;
	if ($roster_conf['sqldebug']){ print "<!-- $query -->\n"; }
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	
	return $wowdb->num_rows($result);
}

function getSubstituteCount($eventid){
	global $wowdb, $roster_conf, $wordings, $db_prefix;
	
	$query = 'SELECT name FROM `'.$db_prefix.'event_subscribers` WHERE status != "Y" AND eventid = '.$eventid;
	if ($roster_conf['sqldebug']){ print "<!-- $query -->\n"; }
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	
	return $wowdb->num_rows($result);
}

// Eventview - attendance Functions

function checkMember($name){
	global $wowdb, $roster_conf, $wordings, $db_prefix;
	// Server (for public roster use)
	$server_name=$roster_conf['server_name'];

	// Check if charinfo exists
	$cquery = 'SELECT member_id FROM '.ROSTER_PLAYERSTABLE.' WHERE name= "'.$name.'"';
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $cquery -->\n";
	}
	$cid_result = $wowdb->query($cquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$cquery);
	$cid = $wowdb->fetch_array($cid_result);
	if($cid[0] != ''){
		return ' <a href="index.php?name='.$module_name.'&amp;file=char&amp;cname='.$name.'&server='.$server_name.'">'.$name.'</a>';
	}else{
		return ' '.$name;
	}
}

// Trigger Functions
function processGEM($name,$eventdata){
	global $wowdb, $roster_conf, $wordings;
	
	$output = "<span class=\"yellow\">Updating GuildEventManager Data from Character <b>[".$name."]</b><br/>\n";
	$output .= "<ul>\n";
	$output .= clearDB();
	//put each event in array and then process them
	$events = $eventdata['realms'][$roster_conf[server_name]]['events'];
	foreach(array_keys($events) as $event){
		$output .= updateGemEvent($events[$event]);
	}
	$output .= "</ul>\n</span>\n";
	
	return $output;
}

function updateGemEvent($eventdata){
	global $wowdb, $roster_conf, $wordings, $db_prefix;
	
	$output = '';
	$channels = $wordings['EventCalendar']['Channels'];
	$channel = $eventdata['channel'];
	
	if(in_array($channel,$channels)){
		$date = $eventdata['ev_date'];
		$type = $eventdata['ev_place'];
		$note = $eventdata['ev_comment'];
		$leader = $eventdata['leader'];
		$minLevel = $eventdata['min_lvl'];
		$maxLevel = $eventdata['max_lvl'];
		$maxCount = $eventdata['max_count'];
		
		$date = date("Y-m-d G:i:s",$date);
		
		// Add eventinfo to db
		$wowdb->reset_values();
		$wowdb->add_value('date', $date);
		$wowdb->add_value('type', $type);
		$wowdb->add_value('note', $note);
		$wowdb->add_value('leader', $leader);
		$wowdb->add_value('minLevel', $minLevel);
		$wowdb->add_value('maxLevel', $maxLevel);
		$wowdb->add_value('maxCount', $maxCount);
		
		$query_addevent = "INSERT INTO `".$db_prefix."events` SET " . $wowdb->assignstr;
		# echo "<!-- $query_addevent -->\n";
		$wowdb->query($query_addevent) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addevent);
		
		$titulars = $eventdata['titulars']; // Players comfirmed
		$substitutes = $eventdata['substitutes']; // Players standby
		$replacements = $eventdata['replacements']; // Players replacement
		$limits = $eventdata['classes']; // Limits
		
		// Get eventid
		$query_eventid = "SELECT eventid FROM `".$db_prefix."events` WHERE date = '$date' AND type = '".addslashes($type)."' AND leader = '$leader'" ;
		$result_eventid = $wowdb->query($query_eventid) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_eventid);
		$row_eventid = $wowdb->fetch_array($result_eventid);
		
		$eventid = $row_eventid[0];
		
		# add titulars
		while (list($id,$player) = each($titulars)){
			# subscriber info
			$name = $player['name'];
			$place = $player['place'];
			$status = "Y";
			$note = "";
			
			# member info
			$guild = $player['guild'];
			$class = $player['class'];
			$class = substr($class, 0, 1).strtolower(substr($class,1));
			$level = $player['level'];
			
			// Add to subsriber table
			$wowdb->reset_values();
			$wowdb->add_value('eventid', $eventid);
			$wowdb->add_value('name', $name);
			$wowdb->add_value('place', $place);
			$wowdb->add_value('status', $status);
			$wowdb->add_value('note', $note);
			
			$query_addsubscriber = "INSERT INTO `".$db_prefix."event_subscribers` SET " . $wowdb->assignstr;
			# echo "<!-- $query_addsubscriber -->\n";
			$wowdb->query($query_addsubscriber) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addsubscriber);
			
			// add players if they don't excist
			$query_player = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name'";
			$result_player = $wowdb->query($query_player) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_player);
			$memberInfo = $wowdb->fetch_assoc( $result_player );
			$wowdb->closeQuery($result_player);
			if ($memberInfo) {
				# update player if needed
				$subquery = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name' and class = '$class' and level = '$level' and guild = '".addslashes($guild)."';";
				$subresult = $wowdb->query($subquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$subquery);
				$levelinfo = $wowdb->fetch_assoc( $subresult );
				$wowdb->closeQuery($subresult);
				if($levelinfo){}else{
					$wowdb->reset_values();
					$wowdb->add_value('level', $level);
					if($guild != 'N/A'){
						$wowdb->add_value('guild', $guild);
					}
					
					$query_updateplayer = "UPDATE `".$db_prefix."event_members` SET " . $wowdb->assignstr . " WHERE name = '$name'";
					#echo "<!-- $query_updateplayer -->\n";
					$wowdb->query($query_updateplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_updateplayer);
				}
			}else{
				# add new player
				$wowdb->reset_values();
				$wowdb->add_value('name', $name);
				$wowdb->add_value('guild', $guild);
				$wowdb->add_value('class', $class);
				$wowdb->add_value('level', $level);
				
				$query_addplayer = "INSERT INTO `".$db_prefix."event_members` SET " . $wowdb->assignstr;
				# echo "<!-- $query_addplayer -->\n";
				$wowdb->query($query_addplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addplayer);
			}		
		}
		
		# add substitutes
		while (list($id,$player) = each($substitutes)){
			# substitute info
			$name = $player['name'];
			$place = $player['place'];
			$status = "S";
			$note = "";
			
			# member info
			$guild = $player['guild'];
			$class = $player['class'];
			$class = substr($class, 0, 1).strtolower(substr($class,1));
			$level = $player['level'];
			
			// Add to subsriber table
			$wowdb->reset_values();
			$wowdb->add_value('eventid', $eventid);
			$wowdb->add_value('name', $name);
			$wowdb->add_value('place', $place);
			$wowdb->add_value('status', $status);
			$wowdb->add_value('note', $note);
			
			$query_addsubscriber = "INSERT INTO `".$db_prefix."event_subscribers` SET " . $wowdb->assignstr;
			# echo "<!-- $query_addsubscriber -->\n";
			$wowdb->query($query_addsubscriber) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addsubscriber);
			
			// add players if they don't excist
			$query_player = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name'";
			$result_player = $wowdb->query($query_player) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_player);
			$memberInfo = $wowdb->fetch_assoc( $result_player );
			$wowdb->closeQuery($result_player);
			if ($memberInfo) {
				# update player if needed
				$subquery = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name' and class = '$class' and level = '$level' and guild = '".addslashes($guild)."';";
				$subresult = $wowdb->query($subquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$subquery);
				$levelinfo = $wowdb->fetch_assoc( $subresult );
				$wowdb->closeQuery($subresult);
				if($levelinfo){}else{
					$wowdb->reset_values();
					$wowdb->add_value('level', $level);
					if($guild != 'N/A'){
						$wowdb->add_value('guild', $guild);
					}
					
					$query_updateplayer = "UPDATE `".$db_prefix."event_members` SET " . $wowdb->assignstr . " WHERE name = '$name'";
					#echo "<!-- $query_updateplayer -->\n";
					$wowdb->query($query_updateplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_updateplayer);
				}
			}else{
				# add new player
				$wowdb->reset_values();
				$wowdb->add_value('name', $name);
				$wowdb->add_value('guild', $guild);
				$wowdb->add_value('class', $class);
				$wowdb->add_value('level', $level);
				
				$query_addplayer = "INSERT INTO `".$db_prefix."event_members` SET " . $wowdb->assignstr;
				# echo "<!-- $query_addplayer -->\n";
				$wowdb->query($query_addplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addplayer);
			}
		}
		
		# add replacements
		while (list($id,$player) = each($replacements)){
			# subscriber info
			$name = $player['name'];
			$place = $player['place'];
			$status = "R";
			$note = "";
			
			# member info
			$guild = $player['guild'];
			$class = $player['class'];
			$class = substr($class, 0, 1).strtolower(substr($class,1));
			$level = $player['level'];
			
			// Add to subsriber table
			$wowdb->reset_values();
			$wowdb->add_value('eventid', $eventid);
			$wowdb->add_value('name', $name);
			$wowdb->add_value('place', $place);
			$wowdb->add_value('status', $status);
			$wowdb->add_value('note', $note);
			
			$query_addsubscriber = "INSERT INTO `".$db_prefix."event_subscribers` SET " . $wowdb->assignstr;
			# echo "<!-- $query_addsubscriber -->\n";
			$wowdb->query($query_addsubscriber) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addsubscriber);
			
			// add players if they don't excist
			$query_player = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name'";
			$result_player = $wowdb->query($query_player) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_player);
			$memberInfo = $wowdb->fetch_assoc( $result_player );
			$wowdb->closeQuery($result_player);
			if ($memberInfo) {
				# update player if needed
				$subquery = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name' and class = '$class' and level = '$level' and guild = '".$guild."';";
				$subresult = $wowdb->query($subquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$subquery);
				$levelinfo = $wowdb->fetch_assoc( $subresult );
				$wowdb->closeQuery($subresult);
				if($levelinfo){}else{
					$wowdb->reset_values();
					$wowdb->add_value('level', $level);
					echo $guild;
					if($guild != 'N/A'){
						$wowdb->add_value('guild', $guild);
					}
					
					$query_updateplayer = "UPDATE `".$db_prefix."event_members` SET " . $wowdb->assignstr . " WHERE name = '$name'";
					#echo "<!-- $query_updateplayer -->\n";
					$wowdb->query($query_updateplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_updateplayer);
				}
			}else{
				# add new player
				$wowdb->reset_values();
				$wowdb->add_value('name', $name);
				$wowdb->add_value('guild', $guild);
				$wowdb->add_value('class', $class);
				$wowdb->add_value('level', $level);
				
				$query_addplayer = "INSERT INTO `".$db_prefix."event_members` SET " . $wowdb->assignstr;
				# echo "<!-- $query_addplayer -->\n";
				$wowdb->query($query_addplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addplayer);
			}
		}
		
		# insert limits
		while (list($class, $limit) = each($limits)){
			$class = substr($class, 0, 1).strtolower(substr($class,1));
			$min = ($limit['min'] == -1?0:$limit['min']);
			$max = ($limit['max'] == -1?0:$limit['max']);
			# add new limits
			$wowdb->reset_values();
			$wowdb->add_value('eventid', $eventid);
			$wowdb->add_value('class', $class);
			$wowdb->add_value('min', $min);
			$wowdb->add_value('max', $max);
			
			$query_addlimits = "INSERT INTO `".$db_prefix."event_limits` SET " . $wowdb->assignstr;
			# echo "<!-- $query_addlimits -->\n";
			if($min > 0 && $max > 0)
				$wowdb->query($query_addlimits) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addlimits);
		}
		
		$output = '<li>Inserting ' . $type .' ('. $date .')</li>' ;
		return $output;
	}
}

function processGRP($name,$eventdata){
	global $wowdb, $roster_conf, $wordings;
	
	$output = "<span class=\"yellow\">Updating GroupCalendar Data from Character <b>[".$name."]</b><br/>\n";
	$output .= "<ul>\n";
	$output .= clearDB();
	//put each event in array and then process them
	$dbs = $eventdata['Databases'];
	foreach(array_keys($dbs) as $db){
		$key = explode("_",$db);
		if($key[0] == $roster_conf[server_name]){
			foreach(array_keys($dbs[$db]['Events']) as $date){
				foreach(array_keys($dbs[$db]['Events'][$date]) as $event){
					$output .= updateGrpEvent($dbs[$db]['Events'][$date][$event],$key[1]);
				}
			}	
		}
	}
	$output .= "</ul>\n</span>\n";
	
	return $output;
}

function updateGrpEvent($eventdata,$leader){
	global $wowdb, $roster_conf, $wordings, $db_prefix;
	
	$output = '';
	
	$type = $eventdata['mType'];
	$title = $eventdata['mTitle'];
	$note = $eventdata['mDescription'];
	
	$search = array("&n;","&c;","&cn;","&sc;","&s;");
	$replace = array("<br/>",",",":",";","/");
	$note = str_replace($search,$replace,$note);
	
	//Convert GroupCal Date to Calendar date
	$startdate = mktime(12,0,0,1,1,2000);
	$mDateConv = $startdate + ($eventdata['mDate'] * 86400);
	$eventDate = getdate($mDateConv);
	//Convert GroupCal Time to Standard Time
	$eventTime = $eventdata['mTime'] / 60;
	if($decpos = strpos($eventTime,'.')){
		$eventTimeH = substr($eventTime,0,$decpos);
		$eventTimeM = substr($eventTime,$decpos);
		$eventTimeM = 60 * $eventTimeM;
		$eventTimeS = 00;
	if($eventTimeM > 0 && $eventTimeM < 10){
		$eventTimeM = 05;
	}
	}else{
		$eventTimeH = $eventTime;
		$eventTimeM = 00;
		$eventTimeS = 00;
	}
	// Make default datetime
	$date = $eventDate['year'] .'-'. $eventDate['mon'] .'-'. $eventDate['mday'] .' '. $eventTimeH .':'. $eventTimeM .':'. $eventTimeS;
	$date = strtotime($date);
	$today = date("F j, Y");

	if($date > strtotime($today)){
		$date = date("Y-m-d G:i:s",$date);
		
		// Add eventinfo to db
		$wowdb->reset_values();
		$wowdb->add_value('date', $date);
		$wowdb->add_value('title', $title);
		$wowdb->add_value('type', $type);
		$wowdb->add_value('note', $note);
		$wowdb->add_value('leader', $leader);
		
		// If type is birthday, add it
		if($type == 'Birth'){
			$query_addbirthday = "INSERT INTO `".$db_prefix."events` SET " . $wowdb->assignstr;
			# echo "<!-- $query_addbirthday -->\n";
			$wowdb->query($query_addbirthday) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addbirthday);	
		}else{
			$minLevel = $eventdata['mMinLevel'];
			$maxLevel = $eventdata['mMaxLevel'];
			$maxCount = $eventdata['mLimits']['mMaxAttendance'];
			
			$wowdb->add_value('minLevel', $minLevel);
			$wowdb->add_value('maxLevel', $maxLevel);
			$wowdb->add_value('maxCount', $maxCount);
			
			$query_addevent = "INSERT INTO `".$db_prefix."events` SET " . $wowdb->assignstr;
			# echo "<!-- $query_addevent -->\n";
			$wowdb->query($query_addevent) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addevent);
			
			$subscribers = $eventdata['mAttendance'];
			$limits = $eventdata['mLimits']['mClassLimits'];
			
			// Get eventid
			$query_eventid = "SELECT eventid FROM `".$db_prefix."events` WHERE date = '$date' AND type = '$type' AND leader = '$leader'" ;
			$result_eventid = $wowdb->query($query_eventid) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_eventid);
			$row_eventid = $wowdb->fetch_array($result_eventid);
			
			$eventid = $row_eventid[0];
			
			if($subscribers != ''){
				// Add subscribers to db
				$place = 0;
				while (list($name, $player) = each($subscribers)){
					$place += 1;
					# info for subsriber-table
					$player_info = explode(",",$player);
					$status = $player_info[2];
					$note = $player_info[4];
					$guild = $player_info[5];
					
					$note = str_replace($search,$replace,$note);
					
					# info for members-table
					$level = substr($player_info[3],2,2);
					$class = substr($player_info[3],1,1);
					$class = $wordings['EventCalendar']['Classes'][$class];
					
					// add subscribers
					$wowdb->reset_values();
					$wowdb->add_value('eventid', $eventid);
					$wowdb->add_value('name', $name);
					$wowdb->add_value('place', $place);
					$wowdb->add_value('status', $status);
					$wowdb->add_value('note', $note);
					
					if($status != 'N' && $status != '-'){
						$query_addsubscriber = "INSERT INTO `".$db_prefix."event_subscribers` SET " . $wowdb->assignstr;
						# echo "<!-- $query_addsubscriber -->\n";
						$wowdb->query($query_addsubscriber) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addsubscriber);
					}
					
					// add players if they don't excist
					$query_player = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name'";
					$result_player = $wowdb->query($query_player) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_player);
					$memberInfo = $wowdb->fetch_assoc( $result_player );
					$wowdb->closeQuery($result_player);
					if ($memberInfo) {
						# update player if needed
						$subquery = "SELECT name FROM `".$db_prefix."event_members` WHERE name = '$name' and class = '$class' and level = '$level';";
						$subresult = $wowdb->query($subquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$subquery);
						$levelinfo = $wowdb->fetch_assoc( $subresult );
						$wowdb->closeQuery($subresult);
						if($levelinfo){}else{
							$wowdb->reset_values();
							$wowdb->add_value('level', $level);
							
							$query_updateplayer = "UPDATE `".$db_prefix."event_members` SET " . $wowdb->assignstr . " WHERE name = '$name'";
							#echo "<!-- $query_updateplayer -->\n";
							$wowdb->query($query_updateplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_updateplayer);
						}
					}else{
						# add new player
						$wowdb->reset_values();
						$wowdb->add_value('name', $name);
						$wowdb->add_value('guild', $guild);
						$wowdb->add_value('class', $class);
						$wowdb->add_value('level', $level);
						
						$query_addplayer = "INSERT INTO `".$db_prefix."event_members` SET " . $wowdb->assignstr;
						# echo "<!-- $query_addplayer -->\n";
						$wowdb->query($query_addplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addplayer);
					}
				}
			}
			
			# insert limits
			if($limits != ''){
				while (list($class, $limit) = each($limits)){
					# add new player
					$wowdb->reset_values();
					$wowdb->add_value('eventid', $eventid);
					$wowdb->add_value('class', $wordings['EventCalendar']['Classes'][$class]);
					$wowdb->add_value('min', $limit['mMin']);
					$wowdb->add_value('max', $limit['mMax']);
					
					$query_addlimits = "INSERT INTO `".$db_prefix."event_limits` SET " . $wowdb->assignstr;
					# echo "<!-- $query_addlimits -->\n";
					$wowdb->query($query_addlimits) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addlimits);
				}
			}
		}
	}
	
	$output = '<li>Inserting ' . $type .' ('. $date .')</li>' ;
	
	return $output;
	
}

function ClearDB(){
	global $wowdb, $roster_conf, $wordings, $rc_wordings,$db_prefix;
	
	$truncate_events = 'TRUNCATE `'.$db_prefix.'events`';
	$truncate_event_limits = 'TRUNCATE `'.$db_prefix.'event_limits`';
	$truncate_event_subscribers = 'TRUNCATE `'.$db_prefix.'event_subscribers`';
	
	$wowdb->query($truncate_events) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$truncate_events);
	$wowdb->query($truncate_event_limits) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$truncate_event_limits);
	$wowdb->query($truncate_event_subscribers) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$truncate_event_subscribers);
	
	return "<li>Removing old data</li>";
}

?>