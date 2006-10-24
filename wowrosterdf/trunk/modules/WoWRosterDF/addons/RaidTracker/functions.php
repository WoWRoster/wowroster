<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

include_once( 'modules/Forums/itemstats/config.php');
include_once('modules/Forums/itemstats/includes/itemcache.php');
include_once('modules/Forums/itemstats/includes/allakhazam.php');



class ItemStats
{
	var $item_cache;
	var $info_site;
	
	// Constructor
	function ItemStats()
	{
		$this->item_cache = new ItemCache();
		$this->info_site = new InfoSite();

		// Setup a ghetto destructor.
		register_shutdown_function(array(&$this, '_ItemStats'));
	}
	
	// Ghetto Destructor
	function _ItemStats()
	{
		$this->item_cache->close();
		$this->info_site->close();
	}
	
	// Returns the properly capitalized name for the specified item.  If the update flag is set and the item is
	// not in the cache, item data item will be fetched from an info site
	function getItemName($name, $update = false)
	{
		$proper_name = $this->item_cache->getItemName($name);

		// If this item was not found and the update flag is set, try to fetch the item data from an info site.
		if (empty($proper_name) && $update)
		{
			$this->updateItem($name);
			$proper_name = $this->item_cache->getItemName($name);
		}
		
		return empty($proper_name) ? $name : $proper_name;
	}
	
	// Returns the link to the info site for the specified item.  If the update flag is set and the item is not in
	// the cache, item data will be fetched from an info site
	function getItemLink($name, $update = false)
	{
		$link = $this->item_cache->getItemLink($name);

		// If this item was not found and the update flag is set, try to fetch the item data from an info site.
		if (empty($link) && $update)
		{
			$this->updateItem($name);
			$link = $this->item_cache->getItemLink($name);
		}

		return $link;
	}
	
	// Returns the color class for the specified item.  If the update flag is set and the item is not in the cache, item
	// data will be fetched from an info site
	function getItemColor($name, $update = false)
	{
		$color = $this->item_cache->getItemColor($name);

		// If this item was not found and the update flag is set, try to fetch the item data from an info site.
		if (empty($color) && $update)
		{
			$this->updateItem($name);
			$color = $this->item_cache->getItemColor($name);
		}

		return $color;
	}
	
	// Returns the icon link for the specified item.  If the update flag is set and the item is not in the cache, item
	// data will be fetched from an info site
	function getItemIconLink($name, $update = false)
	{
		$icon = $this->item_cache->getItemIcon($name);

		// If this item was not found and the update flag is set, try to fetch the item data from an info site.
		if (empty($icon) && $update)
		{
			$this->updateItem($name);
			$icon = $this->item_cache->getItemIcon($name);
		}
		
		// If the icon was found, create a link by merging it with the icon path and extension.
		if (!empty($icon))
		{
			$icon_link = ICON_STORE_LOCATION . $icon . ICON_EXTENSION;
		}

		return $icon_link;
	}
	
	// Returns the html for the specified item.  If the update flag is set and the item is not in the cache, the
	// item will be fetched from an info site
	function getItemHtml($name, $update = false)
	{
		$html = $this->item_cache->getItemHtml($name);

		// If this item was not found and the update flag is set, try to fetch the item data from an info site.
		if (empty($html) && $update)
		{
			$this->updateItem($name);
			$html = $this->item_cache->getItemHtml($name);
		}
		
		// If the item was found, update the icon path in the HTML.
		if (!empty($html))
		{
			$html = str_replace(ICON_LINK_PLACEHOLDER, $this->getItemIconLink($name), $html);
		}

		return $html;
	}
	
	// Returns the overlib tooltip html for the specified item.  If the update flag is set and the item is not in
	// the cache, the item will be fetched from an info site
	function getItemTooltipHtml($name, $update = false)
	{
		// Retrieve the item data from the cache.
		$html = $this->getItemHtml($name, $update);
		if (empty($html))
		{
			return null;
		}

		// Warp the data around the HTML data that invokes the tooltip.
		if (!empty($html))
		{
			// Format the HTML to be compatible with Overlib.
			$html = str_replace(array("\n", "\r"), '', $html);
			$html = addslashes($html);

			$html = 'onmouseover="return overlib(' . "'" . $html . "'" . ',VAUTO,HAUTO,FULLHTML);" onmouseout="return nd();"';
		}

		return $html;
	}

	// Retrieves the data for the specified item from an info site and caches it.
	function updateItem($name)
	{
		// Retrives the data from an information site.
		$item = $this->info_site->getItem($name);

		// If the item wasn't found, and we have something cached already, don't overwrite with lesser data.
		$cached_link = $this->getItemLink($name);
		if (!empty($item['link']) || empty($cached_link))
		{
			// If the data was loaded succesfully, save it to the cache.
			$result = $this->item_cache->saveItem($item);
		}

		return $result;
	}
}

function getZoneIcon($zone){
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
		
	$icon_name = $rt_wordings['RaidTracker']['ZoneIcons'][$zone];
	if($roster_conf['roster_lang'] != "enUS"){
		$url = 'http://www.wow-europe.com/'.substr($roster_conf['roster_lang'],0,2).'/info/basics/raidarea.html#'.$icon_name;
	}else{
		$url = 'http://www.worldofwarcraft.com/info/basics/raidarea.html#'.$icon_name;
	}
	
	$icon_value = '<a href="'.$url.'" target="_blank"><img class="membersRowimg" width="'.$roster_conf['index_iconsize'].'" height="'.$roster_conf['index_iconsize'].'" src="'.$roster_conf['roster_dir'].'/addons/RaidTracker/img/'.$icon_name.'.gif" alt="'.$icon_name.'" /></a> ';
	if($icon_name !='')
	return $icon_value;
}
include_once ('/modules/Forums/itemstats/itemstats.php');
function itemstats_parse($message)

{

	$item_stats = new ItemStats();



	// Search for [item] tags, and replace them with HTML for the specified item.

	while (preg_match('#\[item\](.+?)\[/item\]#s', $message, $match))

	{

		// Grab the item name.

		$item_name = $match[1];



		// Get the proper name of this item.

		$item_name = $item_stats->getItemName($item_name, true);

		

		// Initialize the html.

		$item_html = '[' . $item_name . ']';



		// Get the color of this item and apply it to the html.

		$item_color = $item_stats->getItemColor($item_name);

		if (!empty($item_color))

		{

			$item_html = "<span class='" . $item_color . "'>" . $item_html . "</span>";

		}



		// Get the tooltip html for this item and apply it to the html.

		$item_tooltip_html = $item_stats->getItemTooltipHtml($item_name);

		if (!empty($item_tooltip_html))

		{

			$item_html = "<span " . $item_tooltip_html . ">" . $item_html . "</span>";

		}



		// If this item has a link to the info site, add this link to the HTML.  If it doesn't have a link, it

		// means the item hasn't been found yet, so put up a link to the update page instead.

		$item_link = $item_stats->getItemLink($item_name);

		if (!empty($item_link))

		{

			$item_html = "<a class='forumitemlink' target='_blank' href='" . $item_link . "'>" . $item_html . "</a>";

		}

		else

		{

			$item_link = 'itemstats/updateitem.php?item=' . urlencode(urlencode($item_name));

			$item_html = "<a class='forumitemlink' href='$item_link'>" . $item_html . "</a>";

		}



		// Finally, replace the bbcode with the html.

		$message = str_replace($match[0], $item_html, $message);

	}



	return $message;

}


function getNoteIcon($note){
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
	
	$icon_value = '';
	if($note != ''){
		$icon_value = '<img class="membersRowimg" src="'.$roster_conf['roster_dir'].'/addons/RaidTracker/img/note.gif" alt="'.$note.'" onmouseover="return overlib(\''.$note.'\');" onmouseout="return nd();" />';
	}else{
		$icon_value = '<img class="membersRowimg" src="'.$roster_conf['roster_dir'].'/addons/RaidTracker/img/no_note.gif" alt="'.$note.'" />';
	}
	return $icon_value;
}

function getClass($name){
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
	
	// Display class-icon and color name
	$cquery = 'SELECT class FROM `'.$db_prefix.'raidmembers` WHERE name = \''.$name.'\'';
	$cresult = $wowdb->query($cquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$cquery);
	$crow = $wowdb->fetch_array($cresult);
	$class = substr($crow['class'], 0, 1).strtolower(substr($crow['class'],1));
	// Class Icon
	foreach ($roster_conf['multilanguages'] as $language)
	{
	$icon_name = $wordings[$language]['class_iconArray'][$class];
	if( strlen($icon_name) > 0 ) break;
	}
	$icon_name = 'Interface/Icons/'.$icon_name;
	
	// Class coloring
	$icon_value = '<img class="membersRowimg" width="'.$roster_conf['index_iconsize'].'" height="'.$roster_conf['index_iconsize'].'" src="'.$roster_conf['roster_dir'].'/'.$roster_conf['interface_url'].$icon_name.'.jpg" alt="'.$icon_name.'" /> ';
	return $icon_value;
}

// Lootview functions

function getLoot($raidnum,$color){
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
	
	$server_name=$roster_conf['server_name'];
	//include_once('./modules/Forums/itemstats/itemstats.php');
	// Display loot by color
	echo border('syellow','start',$rt_wordings[$roster_conf['roster_lang']]['LootTypes'][$color]);
	// Make a table to hold the content
		echo '<table cellpadding="0" cellspacing="0" width="250px" class="membersList">';
		
		// Display the header of the table
		echo '<tr>';
		echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Looted'].'</th>';
		echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Name'].'</th>';
		echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Note'].'</th>';
		echo '</tr>';
	
	// Check if we have a Loot Filter
	$loot_where = '';
	if ($color != '') {
		$loot_where = ' AND color = \''.$color.'\' ';
	}
		
	// Get all loot
	$query = 'SELECT itemname, name, color, note FROM `'.$db_prefix.'raiditems` WHERE raidnum = '.$raidnum.' '.$loot_where.' GROUP BY itemname, name ORDER BY itemname ASC';
	
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}
	
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	
	$rownum=1;
	while ($row = $wowdb->fetch_array($result)) {
	// Open a new Row
			echo '<tr>';
		// Display the item in second column
		$loot_item = '[item]'.$row['itemname'].'[/item]';
		//echo '<td class="membersRow'.$rownum.'">'.$loot_item;
		echo itemstats_parse('<td class="membersRow'.$rownum.'">'.$loot_item);
	// Display the count
					$count = 0;
					$cquery = 'SELECT number FROM `'.$db_prefix.'raiditems` WHERE name = \''.$row['name'].'\' AND itemname = \''.$wowdb->escape($row['itemname']).'\' AND raidnum = '.$raidnum;
					$cresult = $wowdb->query($cquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$cquery);
					while ($crow = $wowdb->fetch_array($cresult)){
						$count += $crow['number'];
					}
					echo ' x'. $count.'</td>';
			
			//echo $row['itemname'].'</div></td>';
	
	// Display the name
	echo '<td class="membersRow'.$rownum.'">';
		// Check if char is in guild
		$gquery = 'SELECT member_id FROM '.ROSTER_MEMBERSTABLE.' WHERE name= \''.$row['name'].'\'';
		if ($roster_conf['sqldebug'])
		{
			print "<!-- $gquery -->\n";
		}
		$gid_result = $wowdb->query($gquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$gquery);
		$gid = $wowdb->fetch_array($gid_result);
		if($gid[0] != ''){
			// Check if charinfo exists
			$query = 'SELECT member_id FROM '.ROSTER_PLAYERSTABLE.' WHERE name= \''.$row['name'].'\'';
			if ($roster_conf['sqldebug'])
			{
				print "<!-- $query -->\n";
			}
			$id_result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
			$id = $wowdb->fetch_array($id_result);
			if($id[0] != ''){
				echo ' <a href="index.php?name='.$module_name.'char&amp;name='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a></td>';
			}else{
				echo ' '.$row['name'].'</td>';
			}
		}else{
			echo ' <span style="color:#999999;">'.$row['name'].'</span></td>';
		}

	// Set note
	echo '<td style="text-align:center;" class="membersRowRight'.$rownum.'">';
	echo getNoteIcon($row['note']).'</td>';
		
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
	
	// Add total loot at bottom
	$lquery = 'SELECT sum(number) FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\' AND raidnum = '.$raidnum;
	$lresult = $wowdb->query($lquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$lquery);
	$lrow = $wowdb->fetch_array($lresult);
	echo '<tr><th colspan="3" class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['TotalDrop'].': '.$lrow['0'].'</th></tr>';
	
	// Close the table
		echo '</table>';
		
	echo border('syellow','end');
	echo '<br/>';
}

function getLootByName($raidnum,$color,$name){
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
	
		// Get all loot
		$query = 'SELECT itemname, note FROM `'.$db_prefix.'raiditems` WHERE name = \''.$name.'\'  AND color = \''.$color.'\'  AND raidnum = '.$raidnum.' '.$loot_where.' GROUP BY itemname';
		
		if ($roster_conf['sqldebug'])
		{
			print "<!-- $query -->\n";
		}
		
		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		
		$rownum=1;
		while ($row = $wowdb->fetch_array($result)) {
		// Open a new Row
				echo '<tr>';
		
		// Display the loot
				$loot_item = '[item]'.$row['itemname'].'[/item]';
				echo itemstats_parse('<td class="membersRow'.$rownum.'">'.$loot_item);
			
			// Display the count
				$count = 0;
				$cquery = 'SELECT number FROM `'.$db_prefix.'raiditems` WHERE name = \''.$name.'\' AND itemname = \''.$wowdb->escape($row['itemname']).'\' AND raidnum = '.$raidnum;
				$cresult = $wowdb->query($cquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$cquery);
				while ($crow = $wowdb->fetch_array($cresult)){
					$count += $crow['number'];
				}
				echo ' x'. $count.'</td>';
		
		// Set note
		echo '<td style="text-align:center;" class="membersRowRight'.$rownum.'">';
		echo getNoteIcon($row['note']).'</td>';
		
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

// Loothistory functions

function showLoot($color)
{
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix, $zone, $boss;
	
	// Check if we have a Zone Filter
	$zone_where = '';
	if ($zone != '') {
		$zone_where = ' AND zone = \''.$zone.'\' ';
	}
	
	// Check if we have a Boss Filter
	$boss_where = '';
	if ($boss != '') {
		$boss_where = ' AND boss = \''.$boss.'\' ';
	}
	
	// Check if their is loot
	$query = 'SELECT count(itemname) FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\''.$zone_where.$boss_where.' GROUP BY itemname ORDER BY color DESC, itemname ASC'.$limit;
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}
	
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$loot_count = $wowdb->fetch_array($result);
	
	if($loot_count['0'] != 0){
	
	// Display the Top / left side of the Stylish Border
	echo border('syellow', 'start', $rt_wordings[$roster_conf['roster_lang']]['LootTypes'][$color]);

	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" class="membersList">';
	
	// Check if we have a Zone Filter
	$zone_where = '';
	if ($zone != '') {
		$zone_where = ' AND zone = \''.$zone.'\' ';
	}
	//$limit = ' limit 25';
	
	// Get all loot
	$query = 'SELECT itemname, name, number, loottime FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\''.$zone_where.$boss_where.' GROUP BY itemname ORDER BY itemname ASC'.$limit;
	
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}
	
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	
	$rownum=1;
	while ($row = $wowdb->fetch_array($result)) {
	// Open a new Row
			echo '<tr>';
	
	// Display the loot
		$loot_item = '[item]'.$row['itemname'].'[/item]';
		echo itemstats_parse('<td class="membersRowRight'.$rownum.'">'.$loot_item);
	
	// Display the count
		$count = 0;
		$cquery = 'SELECT number FROM `'.$db_prefix.'raiditems` WHERE itemname = \''.$wowdb->escape($row['itemname']).'\''.$boss_where;
		$cresult = $wowdb->query($cquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$cquery);
		while ($crow = $wowdb->fetch_array($cresult)){
			$count += $crow['number'];
		}
		echo ' x'. $count.'</td>';
	
	
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
	// Add total loot at bottom
	$lquery = 'SELECT sum(number) FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\'';
	$lresult = $wowdb->query($lquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$lquery);
	$lrow = $wowdb->fetch_array($lresult);
	$tquery = 'SELECT sum(number) FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\''.$zone_where.$boss_where;
	$tresult = $wowdb->query($tquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$tquery);
	$trow = $wowdb->fetch_array($tresult);
	echo '<tr><th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['TotalDrop'].': '.$trow['0'].' | '.$rt_wordings[$roster_conf['roster_lang']]['LootTypes'][$color].': '.$lrow['0'].'</th></tr>';
	
	// Close the table
	echo '</table>';
			
	// Display the Right side / Bottom of the Stylish Border
	echo border('syellow','end');
	}
}

// Bossprogress functions
function bossProgress($zone)
{
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
	
	// Display the Top / left side of the Stylish Border
	$title = '<div style="text-align:left;">'.getZoneIcon($zone) . $rt_wordings[$roster_conf['roster_lang']]['Zones'][$zone].'</div>';
	echo border('syellow', 'start', $title);
	
	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" width="250px" class="membersList">';
	
	// Display the header of the table
	echo '<tr>';
	echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Boss'].'</th>';
	echo '<th width="80px" class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Status'].'</th>';
	echo '</tr>';
	
	$bosscount = 0;
	$killcount = 0;
	
	$rownum=1;
	foreach($rt_wordings[$roster_conf['roster_lang']]['Bosses'][$zone] as $bossname => $bosslang){
		$bosscount = $bosscount + 1;
		echo '<tr>';
		
		$squery = 'SELECT count(*) FROM `'.$db_prefix.'raidbosskills` WHERE boss = \''.addslashes($bossname).'\'';
	
		if ($roster_conf['sqldebug'])
		{
			print "<!-- $squery -->\n";
		}
		
		$sresult = $wowdb->query($squery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$squery);
		$scount = $wowdb->fetch_array($sresult);
		
		// Display Bosses
		echo '<td class="membersRow'.$rownum.'">';
		if($scount[0] != 0){
			echo '<a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker&display=history&bossfilter='.addslashes($bossname).'">'.$bosslang.'</a></td>';
		}else{
			echo '<span style="color:#999999">'.$bosslang.'</span></td>';
		}
		
		// Display Status
		echo '<td class="membersRowRight'.$rownum.'">';
		
		if($scount[0] != 0){
			$killcount = $killcount + 1;
			echo '<span style="color:#00CC00">'.$rt_wordings[$roster_conf['roster_lang']]['Killed'].' ('.$scount[0].'x)</span></td>';
		}else{
			echo '<span style="color:#CC0000">'.$rt_wordings[$roster_conf['roster_lang']]['NotKilled'].'</span></td>';
		}
		
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
	// Add progress
	//$progress = number_format((($killcount/$bosscount)*100), 2, '.', '');
	$progress = round(($killcount/$bosscount)*100);
	if($progress != 100){
		echo '<tr><th colspan="2" class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Progress'].': '.$killcount.' / '.$bosscount.' ('.$progress.'%)'.'</th></tr>';
	}else{
		echo '<tr><th colspan="2" class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Progress'].': '.$killcount.' / '.$bosscount.' ('.$rt_wordings[$roster_conf['roster_lang']]['Completed'].')</th></tr>';	
	}
	
	// Close the table
	echo '</table>';
	
	// Display the Right side / Bottom of the Stylish Border
	echo border('syellow','end');
	
	echo '<br/>';
}

// Summary functions
function getLootCount(){
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
	$totalloot = 0;

	// Display the Top / left side of the Stylish Border
	echo border('syellow', 'start', $rt_wordings[$roster_conf['roster_lang']]['LootHistory']);
	
	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" class="membersList">';
	
	// Display the header of the table
	echo '<tr>';
	echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['LootType'].'</th>';
	echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['TotalDrop'].'</th>';
	echo '</tr>';
	
	// Get all loottypes
		$rownum=1;
		foreach($rt_wordings[$roster_conf['roster_lang']]['LootTypes'] as $color => $name) {
			// Open a new Row
			echo '<tr>';
			
			// Display the loot
			echo '<td class="membersRow'.$rownum.'"><span style="color:#'.substr($color, 2, 6).'">'.$name.'</span></td>';	
		
			// Add total loot at bottom
			$lquery = 'SELECT sum(number) FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\'';
			$lresult = $wowdb->query($lquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$lquery);
			$lrow = $wowdb->fetch_array($lresult);
			echo '<td class="membersRowRight'.$rownum.'">'.($lrow['0']>1?$lrow['0']:'0').'</td>';
		// Add total to $totalloot
		$totalloot = $totalloot + $lrow['0'];
	
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
	// Add total loot at bottom
	echo '<tr><th colspan="2" class="membersHeaderRight">';
	echo $rt_wordings[$roster_conf['roster_lang']]['TotalDrop'].': '.$totalloot;
	echo '</th></tr>';
	
	// Close the table
	echo '</table>';
	
	// Display the Right side / Bottom of the Stylish Border
	echo border('syellow','end');
}

function getRaidCount(){
	global $wowdb, $roster_conf, $wordings, $rt_wordings, $db_prefix;
	
	$totalraids = 0;
	$totalbosskills = 0;
	
	// Display the Top / left side of the Stylish Border
	echo border('syellow', 'start', $rt_wordings[$roster_conf['roster_lang']]['RaidHistory']);
	
	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" class="membersList">';
	
	// Display the header of the table
	echo '<tr>';
	echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['RaidZone'].'</th>';
	echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['RaidCount'].'</th>';
	echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['BossKill'].'</th>';
	echo '</tr>';
	
	// Get Raids
	$query = 'SELECT DISTINCT zone FROM `'.$db_prefix.'raids` ORDER BY zone ASC';
	
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}
	
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	
	$rownum=1;
	while ($row = $wowdb->fetch_array($result)) {
		// Open a new Row
		echo '<tr>';
			
		// Display zones
		echo '<td class="membersRow'.$rownum.'">'.getZoneIcon($row['zone']).' '.$rt_wordings[$roster_conf['roster_lang']]['Zones'][$row['zone']].'</td>';	
		
		// Display killcount
		$kquery = 'SELECT count(*) FROM `'.$db_prefix.'raids` WHERE zone = \''.addslashes($row['zone']).'\'';
		
		if ($roster_conf['sqldebug'])
		{
			print "<!-- $kquery -->\n";
		}
		
		$kresult = $wowdb->query($kquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$kquery);
		$krow = $wowdb->fetch_array($kresult);
		echo '<td class="membersRow'.$rownum.'">';
		echo ' '.$krow[0].'</td>';
		// Add count to $totalraids
		$totalraids = $totalraids + $krow[0];
		
		// Display bosskills for that zone
		$totalkills = 0;
			foreach($rt_wordings[$roster_conf['roster_lang']]['Bosses'][$row['zone']] as $boss => $bossloc){
				$tkquery = 'SELECT count(boss) FROM `'.$db_prefix.'raidbosskills` WHERE boss = \''.addslashes($boss).'\'';
				if ($roster_conf['sqldebug'])
				{
					print "<!-- $tkquery -->\n";
				}
		
				$tkresult = $wowdb->query($tkquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$tkquery);
				$tkrow = $wowdb->fetch_array($tkresult);
				$totalkills = $totalkills + $tkrow[0];
			}
		echo '<td class="membersRowRight'.$rownum.'">';
		echo $totalkills.'</td>';
		// Add $totalkills to $totalbosskills
		$totalbosskills = $totalbosskills + $totalkills;
		
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
	// Add totals at bottom
	echo '<tr><th colspan="3" class="membersHeaderRight">';
	echo $rt_wordings[$roster_conf['roster_lang']]['TotalRaids'].': '.$totalraids.' | ';
	echo $rt_wordings[$roster_conf['roster_lang']]['TotalKills'].': '.$totalbosskills;
	echo '</th></tr>';
	
	// Close the table
	echo '</table>';
	
	// Display the Right side / Bottom of the Stylish Border
	echo border('syellow','end');
}

// Trigger functions
function processRaids($name,$raiddata)
{
	global $wowdb, $roster_conf, $wordings;
	
	$output = "<span class=\"yellow\">Updating CTMod RaidTracker Data from Character <b>[".$name."]</b><br/>\n";
	$output .= "<ul>\n";
	
	//Put each raid in an array and then process them
	foreach( array_keys($raiddata) as $raid ) {
		$data_raids = $raiddata[$raid];
		$raid_array = array();
		
		foreach( array_keys($data_raids) as $raidId ) {
			$raid_array[$raidId] = $data_raids[$raidId];
		}
		//Process this raid
		$output .= update_raid($raid_array);
	}
	
	$output .= "</ul>\n</span>\n";
	
	return $output;
}

function update_raid($raiddata){
	global $wowdb, $roster_conf, $wordings, $db_prefix;
	
	$output = '';
	
	$raidkey = date("Y-m-d G:i:s", strtotime($raiddata['key']));
	$raidend = date("Y-m-d G:i:s", strtotime($raiddata['End']));
	$raidzone = ($raiddata['zone'] != ''?$raiddata['zone']:'RandomRaid');
	$raidnote = $raiddata['note'];
	
	$query_exist = "SELECT raidnum FROM `".$db_prefix."raids` WHERE raidid = '".$raidkey."' AND zone='".addslashes($raidzone)."' AND note = '".$raidnote."'";
	$result_exist = $wowdb->query($query_exist) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_exist);
	// If the raid exists we don't need to add or update it
	if($wowdb->num_rows($result_exist) > 0){  }
	else
	{
		// Add new raid to db
		$output .= '<li>Adding Raids : '.($raidzone !=''?'<b>['.$raidzone.']</b> - ':'') . '<i>' . $raidkey . '</i>' .($raidnote !=''?' ('. $raidnote.')':'') .'</li><ul>';
		
		// Add raidinfo to db
		$wowdb->reset_values();
		$wowdb->add_value('raidid', $raidkey);
		$wowdb->add_value('zone', $raidzone);
		$wowdb->add_value('end', $raidend);
		$wowdb->add_value('note', $raidnote);
							
		$query_raidinfo = "INSERT INTO `".$db_prefix."raids` SET " . $wowdb->assignstr;
		# echo "<!-- $query_raidinfo -->\n";
		$wowdb->query($query_raidinfo) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_raidinfo);
		
		// Get raidnum
		$query_raidnum = "SELECT raidnum FROM `".$db_prefix."raids` WHERE raidid = '".$raidkey."' AND note = '".$raidnote."'";
		$result_raidnum = $wowdb->query($query_raidnum) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_raidnum);
		$row_raidnum = $wowdb->fetch_array($result_raidnum);
		
		$raidnum = $row_raidnum[0];
		
		// Update players in db
		$output .= '<li>Updating Players</li>';
		$playerinfo = $raiddata['PlayerInfos']; // Array[player] with playerinfo (class, race, level)
		
		foreach( array_keys( $playerinfo ) as $player_id ) {
			
			$player = $playerinfo[$player_id];
			$race = $player['race'];
			$class = $player['class'];
			$level = ($player['level'] != ''?$player['level']:60);
			
			// update info if needed
			$querystr = "SELECT name FROM `".$db_prefix."raidmembers` WHERE name = '$player_id';";
			$result = $wowdb->query($querystr) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$querystr);
			$memberInfo = $wowdb->fetch_assoc( $result );
			$wowdb->closeQuery($result);
			if ($memberInfo) {
				$subquery = "SELECT name FROM `".$db_prefix."raidmembers` WHERE race = '$race' and name = '$player_id' and class = '$class' and level = '$level';";
				$subresult = $wowdb->query($subquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$subquery);
				$levelinfo = $wowdb->fetch_assoc( $subresult );
				$wowdb->closeQuery($subresult);
				if($levelinfo){}else{
					$wowdb->reset_values();
					$wowdb->add_value('level', $level);
					
					$query_updateplayer = "UPDATE `".$db_prefix."raidmembers` SET " . $wowdb->assignstr . " WHERE name = '$player_id'";
					# echo "<!-- $query_updateplayer -->\n";
					$wowdb->query($query_updateplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_updateplayer);
				}
			}
			// add new player
			else {			
				$wowdb->reset_values();
				$wowdb->add_value('name', $player_id);
				$wowdb->add_value('race', $race);
				$wowdb->add_value('class', $class);
				$wowdb->add_value('level', $level);
				
				$query_addplayer = "INSERT INTO `".$db_prefix."raidmembers` SET " . $wowdb->assignstr;
				# echo "<!-- $query_addplayer -->\n";
				$wowdb->query($query_addplayer) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addplayer);
			}
		}
		
		// Add playerinfo to db (joins)
		$output .= '<li>Adding joins and leaves</li>';
		$playerjoins = $raiddata['Join']; // Array with joininfo (player, time)
		
		foreach( array_keys( $playerjoins ) as $joins ) {
			$playerInfo = $playerjoins[$joins];
			$playerName = $playerInfo['player'];
			$playerJoinDate = date("Y-m-d G:i:s", strtotime($playerInfo['time']));
			
			# skip if entry already there
			$querystr = "SELECT raidId FROM `".$db_prefix."raidjoins` WHERE name = '$playerName' and datejoin = '$playerJoinDate';";
			$result = $wowdb->query($querystr) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$querystr);
			$memberInfo = $wowdb->fetch_assoc( $result );
			$wowdb->closeQuery($result);
			if ($memberInfo) { } else {		
				$wowdb->reset_values();
				$wowdb->add_value('raidnum', $raidnum);
				$wowdb->add_value('raidid', $raidkey);
				$wowdb->add_value('datejoin', $playerJoinDate);
				$wowdb->add_value('name', $playerName);
				
				$query_addjoins = "INSERT INTO `".$db_prefix."raidjoins` SET " . $wowdb->assignstr;
				# echo "<!-- $query_addjoins -->\n";
				$wowdb->query($query_addjoins) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addjoins);
			}
		}
		
		// Add playerinfo to db (leaves)
		$playerleaves = $raiddata['Leave']; // Array with leaveinfo (player, time)
		
		foreach( array_keys( $playerleaves ) as $leaves ) {
			$playerInfo = $playerleaves[$leaves];
			$playerName = $playerInfo['player'];
			$playerLeftDate = date("Y-m-d G:i:s", strtotime($playerInfo['time']));
			
			# skip if entry already there
			$querystr = "SELECT raidId FROM `".$db_prefix."raidleaves` WHERE name = '$playerName' and dateleft = '$playerLeftDate';";
			$result = $wowdb->query($querystr) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$querystr);
			$memberInfo = $wowdb->fetch_assoc( $result );
			$wowdb->closeQuery($result);
			if ($memberInfo) { } else {
				$wowdb->reset_values();
				$wowdb->add_value('raidnum', $raidnum);
				$wowdb->add_value('raidid', $raidkey);
				$wowdb->add_value('dateleft', $playerLeftDate);
				$wowdb->add_value('name', $playerName);
				
				$query_addleaves = "INSERT INTO `".$db_prefix."raidleaves` SET " . $wowdb->assignstr;
				# echo "<!-- $query_addleaves -->\n";
				$wowdb->query($query_addleaves) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_addleaves);
			}
		}
		
				
		// Add lootinfo to db
		$output .= '<li>Adding loot</li>';
		$lootinfo = $raiddata['Loot']; // Array with lootinfo[id] (zone, player, time, boss, [item] => name, c)
		
		foreach( array_keys($lootinfo) as $looted ) {
			foreach( array_keys($lootinfo) as $player_looted ) {
				$lootInfo = $lootinfo[$player_looted];

				$player = $lootInfo['player'];
				$zone = $lootInfo['zone'];
				$boss = $lootInfo['boss'];
				$note = $lootInfo['note'];
				$loot = $lootInfo['item'];
				$itemname = $loot['name'];
				$count = $loot['count'];
				$codecol = $loot['c'];
				$loottime = date("Y-m-d G:i:s", strtotime($lootInfo['time']));
				
				# skip if entry already there
				$querystr = "SELECT raidId FROM `".$db_prefix."raiditems` WHERE name = '$player' and loottime = '$loottime';";
				$result = $wowdb->query($querystr) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$querystr);
				$memberInfo = $wowdb->fetch_assoc($result);
				$wowdb->closeQuery($result);
				if ($memberInfo) { } else {		
					$wowdb->reset_values();
					$wowdb->add_value('raidnum', $raidnum);
					$wowdb->add_value('raidid', $raidkey);
					$wowdb->add_value('itemname', $itemname);
					$wowdb->add_value('color', $codecol);
					$wowdb->add_value('loottime', $loottime);
					$wowdb->add_value('number', $count);
					$wowdb->add_value('zone', $zone);
					$wowdb->add_value('boss', $boss);
					$wowdb->add_value('name', $player);
					$wowdb->add_value('note', $note);
	
					$query_loot = "INSERT INTO `".$db_prefix."raiditems` SET " . $wowdb->assignstr;
					# echo "<!-- $query_loot -->\n";				
					$wowdb->query($query_loot) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_loot);
				}
			}
		}
		
		// Add bosskills to db
		$output .= '<li>Adding bosskills</li></ul>';
		$bosskills = $raiddata['BossKills']; // Array with bosskills[id] (boss, time)
		
		foreach( array_keys( $bosskills ) as $bosskill ) {
			$bossInfo = $bosskills[$bosskill];
			
			$boss = $bossInfo['boss'];
			$killtime = date("Y-m-d G:i:s", strtotime($bossInfo['time']));
			
			# skip if entry already there
			$querystr = "SELECT raidId FROM `".$db_prefix."raidbosskills` WHERE boss = '".addslashes($boss)."' and raidnum = '".$raidnum."' and time = '".$killtime."';";												  
			$result = $wowdb->query($querystr) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$querystr);
			$bossExcists = $wowdb->fetch_assoc( $result );
			$wowdb->closeQuery($result);
			if ($bossExcists) { } else {
				$wowdb->reset_values();
				$wowdb->add_value('raidnum', $raidnum);
				$wowdb->add_value('raidid', $raidkey);
				$wowdb->add_value('boss', $boss);
				$wowdb->add_value('time', $killtime);
				
				$query_bosskills = "INSERT INTO `".$db_prefix."raidbosskills` SET " . $wowdb->assignstr;
				# echo "<!-- $query_bosskills -->\n";
				$wowdb->query($query_bosskills) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_bosskills);
			}
		}
	}
	
	return $output;
}

?>