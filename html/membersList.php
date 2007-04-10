<?php
$versions['versionDate']['membersList'] = '$Date: 2006/02/05 06:01:36 $';
$versions['versionRev']['membersList'] = '$Revision: 1.36 $';
$versions['versionAuthor']['membersList'] = '$Author: zanix $';

require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect( $db_host, $db_user, $db_passwd ) or die( "Could not connect to desired database. <a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>" );
mysql_select_db( $db_name ) or die( "Could not select desired database. <a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>" );
$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."'), guild_motd FROM `".ROSTER_GUILDTABLE."` where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());

if ( $row = mysql_fetch_row( $result ) )
{
	$guildId = $row[0];
	$updateTime = $row[1];
	$guildMOTD = $row[2];
}
else
{
	die( "Could not find guild: '$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration. <a href=\"docs/index.html\" target=\"_new\">Click here for installation instructions.</a>" );
}

if ( $show_motd == 1 )
{
	echo '<p class="center"><font size="2" color="#FF0000"><strong>Guild MOTD: "'.$guildMOTD.'"</strong></font></p><br><br>';
}


include 'lib/menu.php';

echo "<table>\n  <tr>\n";

if ( $show_hslist == 1 )
{
	echo '    <td valign="top">';
	include 'hsList.php';
	echo "    </td>\n";
}

if ( $show_pvplist == 1 )
{
	echo '    <td valign="top">';
	include 'pvpList.php';
	echo "    </td>\n";
}

echo "  </tr>\n</table>\n";

/*
this section drives the entire output.
Later I'll probably write some query string handling for either/or:
1) fieldsets : given a get request, load a specific set of fields to display (note, will have to add that get string setting to all column sorting headers)
2) customisation by end user: create a list of individual fields, and load them in based off a checkbox-driven form (probably with defaults) for which columns they wish to see.
3) optomisation of query: ie, add to the fields here the section of query to add for it, so it only queries what it has to show.
- at present you need to ensure all fields we will ever display are in the query, the reason I havent done this yet is because of the joins, it makes it narky.
*/

$FIELDS0 = array (
	'name' => array( // the field name, taken from the query below. if you add options, be sure the query returns those fields or they'll be blank.
		'lang_field' => 'name', // for the column header, check the lang files for whatever they need to be called. if incorrect, table headers will be blank.
		'order'    => array( '`members`.`name`' ), // this is an array simply because some sorts benifit from multiple order fields, name the fields here as you would in the query
		'required' => true, // not currently used, but left as a reminder to me that we want to always have at least the name...
		'default'  => true, // also no tcurrently used, but if I add in 2) from above, they'd indicate defaults to show
		'value' => 'name_value', // this is in case you need a function for a field value (ie this wraps the href around names if they've been uploaded)
	),
);

$FIELDS1 = array (
	'class' => array(
		'lang_field' => 'class',
		'divider' => true, // does this devide out the table on the sections?
		'order'    => array( '`members`.`class`','`members`.`name`' ),
		'default'  => true,
	),
);

$FIELDS2 = array (
	'level' => array(
		'lang_field' => 'level',
		'divider' => true,
		'divider_prefix' => 'Level ', // do we want to prefix the divider levels?
		// default // 'order'    => array( 'level' ),
		'default'  => true,
	),
);

if ( $show_title == 1 )
{
	$FIELDS3 = array (
		'guild_title' => array (
			'lang_field' => 'title',
			'divider' => true,
			'order' => array( '`members`.`guild_rank`','`members`.`name`' ),
		),
	);
}

if ( $show_currenthonor == 1 )
{
	$FIELDS4 = array (
		'RankName' => array(
			'lang_field' => 'currenthonor',
			'divider' => true,
			'order' => array( '`players`.`RankInfo`' ),
		),
	);
}

if ( $show_note == 1 )
{
	$FIELDS5 = array (
		'note' => array(
			'lang_field' => 'note',
			'order' => array( 'nisnull', '`members`.`note`' ),
		),
	);
}

if ( $show_prof == 1 )
{
	$FIELDS6 = array (
		'professions' => array(
			'lang_field' => 'professions',
		),
	);
}

if ( $show_hearthed == 1 )
{
	$FIELDS7 = array (
		'hearth' => array(
			'lang_field' => 'hearthed',
			'divider' => true,
			'order' => array( 'tisnull', 'hearth' ),
		),
	);
}

if ( $show_zone == 1 )
{
	$FIELDS8 = array (
		'zone' => array(
			'lang_field' => 'zone',
			'divider' => true,
			'order' => array( '`members`.`zone`' ),
		),
	);
}

if ( $show_lastonline == 1 )
{
	$FIELDS9 = array (
		'last_online' => array (
			'lang_field' => 'lastonline',
			'order' => array( '`members`.`last_online`' ),
		),
	);
}

if ( $show_lastupdate == 1 )
{
	$FIELDS10 = array (
		'last_update' => array (
			'lang_field' => 'lastupdate',
			'order' => array( '`players`.`dateupdatedutc`' ),
		),
	);
}

// Merge Arrays
$FIELDS = array_merge(
	(array)$FIELDS0,
	(array)$FIELDS1,
	(array)$FIELDS2,
	(array)$FIELDS3,
	(array)$FIELDS4,
	(array)$FIELDS5,
	(array)$FIELDS6,
	(array)$FIELDS7,
	(array)$FIELDS8,
	(array)$FIELDS9,
	(array)$FIELDS10
);


#join the tables. These are small tables thankfully

// remember that any fields added above need to have a column pulled here.
$query =
	"SELECT ".
	// we could limit down what it actually pulls, but to save hassles in sorting by invisible fields, etc, we'll keep it constant. The query is a lot simpler than it looks anyway.
	"`members`.`member_id`, ".
	"`members`.`name`, ".
	"`members`.`class`, ".
	"`players`.`RankName`, ".
	"IF( `members`.`level` < `players`.`level`, `players`.`level`, `members`.`level` ) AS 'level', ".
	"`members`.`note`, ".
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	"`members`.`guild_rank`, ".
	"`members`.`guild_title`, ".
	"`members`.`zone`, ".
	"DATE_FORMAT( `members`.`last_online`, '".$timeformat[$roster_lang]."' ) AS 'last_online', ".
	"`players`.`dateupdatedutc` AS 'last_update', ".
	"`players`.`server`, ".
	"`players`.`hearth`, ".
	"`players`.`clientLocale`, ".
	"IF( `items`.`item_tooltip` IS NULL OR `items`.`item_tooltip` = '', 1, 0 ) AS 'tisnull' ".
	// this part we'll keep the same for simplicity's sake.
	"FROM `".ROSTER_MEMBERSTABLE."` as members ".
	// all those people asking about guild searching, here's a spot!  and here's the simple alteration to stop guild filtering in this particular place
	//  "LEFT JOIN `players` ON `members`.`member_id` = `players`.`member_id` ".
	"LEFT JOIN `".ROSTER_PLAYERSTABLE."` AS players ON `members`.`member_id` = `players`.`member_id` AND `members`.`guild_id` = $guildId ".
	"LEFT JOIN `".ROSTER_ITEMSTABLE."` as items ON `members`.`member_id` = `items`.`member_id` AND `items`.`item_name` = 'Hearthstone' ".
	"ORDER BY ";

// Add custom primary and secondary ORDER BY definitions
// removed all the switchstring jazz as it wasn't needed

if ( $ORDER_FIELD = $FIELDS[$_GET['s']] )
{
	$order_field = $_GET['s'];
	if ( isset( $ORDER_FIELD['order'] ) )
	{
		foreach ( $ORDER_FIELD['order'] as $order_field_sql )
		{
			// Added this to sort RankName column correctly
			if( $order_field === 'RankName' )
			{
				$query .= $order_field_sql.( $_GET['d'] ? '' : ' DESC' ).', ';
			}
			else
			{
				$query .= $order_field_sql.( $_GET['d'] ? ' DESC' : '' ).', ';
			}
		}
	}
}

$query .= 'level'.( $_GET['d'] ? ' ASC' : ' DESC' );
$query .= ', name'.( $_GET['d'] ? ' DESC' : ' ASC' );


$result = mysql_query( $query ) or die( mysql_error() );
if ( $sqldebug )
{
	echo "<!--$query-->\n";
}

// switching the quoting around as I loathe printing ugly html, and I like readable code, so I compromise.

$cols = count( $FIELDS );
$cols1 = $cols + 2;

$borderTop = "  <tr>
    <th colspan=$cols1 class='rankbordertop'><span class='rankbordertopleft'></span>
      <span class='rankbordertopright'></span></th>
    </tr>\n";

// header row
$tableHeaderRow = "  <tr>\n";
$current_col = 1;
foreach ( $FIELDS as $field => $DATA )
{
	// click a sorted field again to reverse sort it
	// Don't add it if it is detected already
	if( $_REQUEST['d'] != 'true' )
	{
		$desc = ( $order_field == $field ) ? '&amp;d=true' : '';
	}

	if ( $current_col == 1 )
	{
		$tableHeaderRow .= "    <th class='rankbordercenterleft'></th><th class='membersHeader'><a href='?s=$field$desc'>".$wordings[$roster_lang][$DATA['lang_field']]."</a></th>\n";
	}
	else if ( $current_col == $cols )
	{
		$tableHeaderRow .= "    <th class='membersHeaderRight'><a href='?s=$field$desc'>".$wordings[$roster_lang][$DATA['lang_field']]."</a></th><th class='rankbordercenterright'></th>\n";
	}
	else
	{
		$tableHeaderRow .= "    <th class='membersHeader'><a href='?s=$field$desc'>".$wordings[$roster_lang][$DATA['lang_field']]."</a></th>\n";
	}

	$current_col++;
}
$tableHeaderRow .= "  </tr>\n";
// end header row

$borderBottom = "  <tr><th colspan=$cols1 class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>\n";
$tableFooter = "</table>\n";

echo "<table cellpadding=0 cellspacing=0 class='membersList'>\n";

// Counter for row striping
$striping_counter = 0;

$last_value = 'some obscurely random string because i\'m too lazy to do this a better way.';

while ( $row = mysql_fetch_array( $result ) )
{
	// Adding grouping dividers
	if ( $ORDER_FIELD['divider'] )
	{
		if ( $last_value != $row[$order_field] )
		{
			if ( $sqldebug )
			{
				echo "<!-- $order_field :: $last_value !- $row[$order_field] -->\n";
			}
			if ( $striping_counter )
			{
				echo $borderBottom;
			}

			echo "  <tr><th colspan='$cols' class='membersGroup' id='$order_field-$row[$order_field]'>$ORDER_FIELD[divider_prefix]$row[$order_field]</th></tr>\n".
			$borderTop.
			$tableHeaderRow;

			$striping_counter = 0;
			$last_value = $row[$order_field];
		}
	}
	else if ( $striping_counter == 0 )
	{
		echo $borderTop.$tableHeaderRow;
	}

	// actual player rows
	echo "  <tr>\n";
	echo "<th class='rankbordercenterleft'></th>";


	// Increment counter so rows are colored alternately
	$stripe_counter = ( ( ++$striping_counter % 2 ) + 1 );
	$stripe_class = 'membersRow'.$stripe_counter;
	$stripe_class_right =  'membersRowRight'.$stripe_counter;
	$current_col = 1;


	// Echoing cells w/ data
	// set up field to show profession on: name this the field name of the column you want the professions to appear on.
	$profession_field = "$tradeskill_index";
	$icon_size = "$TS_iconsize";

	foreach ( $FIELDS as $field => $DATA )
	{
		if ( isset( $DATA['value'] ) )
		{
			$cell_value = $DATA['value']( $row );
		}
		else
		{
			$cell_value = empty( $row[$field] ) ? '&nbsp;' : $row[$field];
		}

		// adding image for trade skills
		if ( $tradeskill_indexon == 1 )
		{
			if ( $field == $profession_field )
			{
				$SQL_prof = mysql_query( "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` = '$row[member_id]' AND (`skill_type` = '".$wordings[$row[clientLocale]]['professions']."' OR `skill_type` = '".$wordings[$row[clientLocale]]['secondary']."')" );
				while ( $r_prof = mysql_fetch_array( $SQL_prof ) )
				{
					if( $r_prof['skill_level'] != '1:1')
					{
						$toolTip = $r_prof['skill_name']."<br />".str_replace(':','/',$r_prof['skill_level']);
						$skill_name_wow = array_search( $r_prof['skill_name'], $wordings[$row[clientLocale]] );
						$cell_value .= "&nbsp;<img class=\"membersRowimg\" width=\"$icon_size\" height=\"$icon_size\" src=\"".$img_url."trade/$skill_name_wow.jpg\" alt=\"\" onMouseover=\"return overlib('$toolTip',RIGHT,WRAP);\" onMouseout=\"return nd();\" />\n";
					}
				}

			}
		}

		if ( $honoricon_field == 1 )
		{
			if ( $field == 'RankName' ) //Honor field
			{
				$SQL_rankicon = mysql_query( "SELECT `RankIcon`, `RankName` FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` = '$row[member_id]' " );
				while ( $r_rankicon = mysql_fetch_array( $SQL_rankicon ) )
				{
					$rankicon_name_wow = preg_replace("|\\\\|",'/',$r_rankicon['RankIcon']);
					$rankname = $r_rankicon['RankName'];
					if ( strtolower($rankname) == strtolower($wordings[$row['clientLocale']]['PvPRankNone']) )
					{
						$cell_value .= '';
					}
					elseif ( $rankname == '' )
					{
						$cell_value .= '';
					}
					else
					{
						$cell_value .= "&nbsp;<img class='membersRowimg' width=$icon_size height=$icon_size src='".$img_url."$rankicon_name_wow.jpg' alt=''>\n";
					}
				}
			}
		}

		if ( $show_lastupdate == 1)
		{
			if ( $field == 'last_update')
			{
				if ( $cell_value != '&nbsp;')
				{
					$day = substr($cell_value,3,2);
					$month = substr($cell_value,0,2);
					$year = substr($cell_value,6,2);
					$hour = substr($cell_value,9,2);
					$minute = substr($cell_value,12,2);
					$second = substr($cell_value,15,2);

					$localtime = mktime($hour+$localtimeoffset ,$minute, $second, $month, $day, $year, -1);
					$cell_value = date($phptimeformat[$roster_lang], $localtime);
				}
			}
		}

		if ( $current_col == 1 ) // first col
		{
			echo "    <td class='$stripe_class'>$cell_value</td>\n";
		}
		elseif ( $current_col == 2 ) //class col
		{
			if ( $field == 'class' ) //class field
			{
				$SQL_icon = mysql_query( "SELECT class FROM `".ROSTER_MEMBERSTABLE."` WHERE `member_id` = '$row[member_id]' " );
				while ( $r_icon = mysql_fetch_array( $SQL_icon ) )
				{
					$icon_name_wow = array_search( $r_icon['class'], $wordings[$roster_lang] );
					if ( $classicon_field == 1 )
					{
						echo "<td class='$stripe_class'><img class='membersRowimg' width=$icon_size height=$icon_size src='".$img_url."class/class_$icon_name_wow.jpg' alt=''>&nbsp;$cell_value</td>\n";
					}
					elseif ( $classicon_field == 0 )
					{
						echo "    <td class='$stripe_class'>$cell_value</td>\n";
					}
				}
			}
		}
		elseif ( $current_col == $cols ) // last col
		{
			echo "    <td class='$stripe_class_right'>$cell_value</td>\n";
		}
		else
		{
			echo "    <td class='$stripe_class'>$cell_value</td>\n";
		}
		$current_col++;
	}
	echo "<th class='rankbordercenterright'></th>";
	echo "  </tr>\n";
}

echo $borderBottom.
$tableFooter;

mysql_free_result( $result );

echo "<p id='last_update' class='last_update'>".$wordings[$roster_lang]['update']." $updateTime</p>\n";

// function(s) to return a value from a row with some logic applied.
function name_value ( $row )
{
	if ( $row['server'] )
	{
		return '<a href="char.php?name='.$row['name'].'&amp;server='.$row['server'].'">'.$row['name'].'</a>';
	}
	else
	{
		return $row['name'];
	}
}
?>