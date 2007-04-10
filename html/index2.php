<?php include "conf.php"; ?>
<html>
<head>
<link href="<?php print $stylesheet1 ?>" rel="stylesheet" type="text/css" />
<title><?php print $guild_desc; ?> WoW Roster</title>
</head>
<body>
<p>Group by: <a href="index2.php?groupby=class<?php if(strlen($_GET['sortby'])) print "&sortby=" . $_GET['sortby']; ?>">Class</a></p>
<p>Group by: <a href="index2.php?groupby=guild_title<?php if(strlen($_GET['sortby'])) print "&sortby=" . $_GET['sortby']; ?>">Guild Title</a></p>
<?php

function has_uploaded_data()
{
	$query = "SELECT `name`, `server` FROM `".ROSTER_PLAYERSTABLE."` WHERE name = '" . func_get_arg(0) . "' and server = '" . addslashes(func_get_arg(1)). "'";
	$result = mysql_query($query) or die(mysql_error());

	$row = mysql_fetch_row($result);

	if($row)
		print"<a href=\"char.php?name=" . $row[0] . "&server=" . $row[1] . "\">" . $row[0] . "</a>";
	else
		print func_get_arg(0);

	return;
}


$link = mysql_connect($db_host, $db_user, $db_passwd) or die("Could not connect");
mysql_select_db($db_name) or die("Could not select DB");

$default_groupby = "";
$default_sortby = "name";

// Determine grouping, if any
// I'd like to be able to use 'Race' ... maybe tables and Addon should be modified ... maybe too intensive

if ($_GET['groupby'] == "class")
{
	print "Mode: Sorting by Class<br><br>";

	// Not all classes are going to be in use, we should find out what classes we have!

	$query = "SELECT `class` FROM `".ROSTER_MEMBERSTABLE."` ORDER BY `class` ASC";

  if ($this->sqldebug) {
  	print "<!-- $query --> \n";
  }	

	// Make a list of the various classes in `members`
	$result = mysql_query($query) or die(mysql_error());
	$found_classes[] = "";
	while($row = mysql_fetch_row($result)) {
		if(!(array_search($row[0], $found_classes)))
			$found_classes[] = $row[0];
	}
	mysql_free_result($result);

	// I can't figure out how to get the above while loop to make index 0 of $found_classes to be the first class
	// so I'm going to shift the array down, grr
	array_shift($found_classes);

  if ($this->sqldebug) {
		print_r($found_classes);	
	}
	
	if(strlen($_GET['sortby']))
		$current_sortby = $_GET['sortby'];
	else
		$current_sortby = $default_sortby;

	foreach($found_classes as $found_class) {

		$query = "SELECT `name`, `class`, `level`, `note`, `guild_title`, DATE_FORMAT(last_online, '%b %d at %l%p') FROM `".ROSTER_MEMBERSTABLE."` WHERE class = '" . $found_class . "' ORDER BY `" . $current_sortby . "` ASC";

    if ($this->sqldebug) {
      print "<!-- $query --> \n";
    }		
		$result = mysql_query($query) or die(mysql_error());

		// Print headers
		print "<h4>" . $found_class . "</h4>";
		
		print "<table id=\"memberlist\">
		<tr>
			<th><a href=\"?groupby=class&sortby=name\">Name</a></th>
			<th><a href=\"?groupby=class&sortby=class\">Class</a></th>
			<th><a href=\"?groupby=class&sortby=level\">Level</a></th>
			<th><a href=\"?groupby=class&sortby=note\">Note</a></th>
			<th><a href=\"?groupby=class&sortby=guild_rank\">Rank</a></th>
			<th><a href=\"?groupby=class&sortby=last_online\">Last Online</a></th>
		</tr>";
		
		
		
		
		//<table id=\"memberlist\"><tr><th><a href=\"?sortby=name\">Name</a></th><th><a href=\"?sortby=class\">Class</a></th><th><a href=\"?sortby=level\">Level</a></th><th><a href=\"?sortby=note\">Note</a></th><th><a href=\"?sortby=rank\">Rank</a></th><th><a href=\"?sortby=last_online\">Last Online</a></th></tr>";
		
		// Print rows w/ data

		while ( $row = mysql_fetch_row($result) )
		{
			if(strncmp($row[5], date("M d", time()), 3) == 0)
			$row[5] = str_replace(date("M d", time()), "Today", $row[5]);

			?>
			<?php if($i % 2) print "<tr class=\"odd\">"; else print "<tr>"; ?>
				<td><?php has_uploaded_data($row[0], addslashes($server_name)); ?></td>
				<td><?php print $row[1]; ?></td>
				<td><?php print $row[2]; ?></td>
				<td><?php print $row[3]; ?></td>
				<td><?php print $row[4]; ?></td>
				<td align="right"><?php print $row[5]; ?></td>
			</tr>
			<?php

			// Increment our even/odd counter
			$i = $i +1;
		}
		print "</table>";

		mysql_free_result($result);
	}
}


if ($_GET['groupby'] == "guild_title")
{
	print "Mode: Sorting by Guild Title<br><br>";

	// Ranks are custom so we must teach ourselves!

	$query = "SELECT `guild_title` FROM `".ROSTER_MEMBERSTABLE."` ORDER BY `guild_rank` ASC";
	print $query . "<br><br>";

	// Make a list of the various guild_titles in `members`
	$result = mysql_query($query) or die(mysql_error());
	$found_guild_titles[] = "";
	while($row = mysql_fetch_row($result)) {
		if(!(array_search($row[0], $found_guild_titles)))
			$found_guild_titles[] = $row[0];
	}
	mysql_free_result($result);

	// I can't figure out how to get the above while loop to make index 0 of $found_classes to be the first class
	// so I'm going to shift the array down, grr
	array_shift($found_guild_titles);

	print_r($found_guild_titles);	

	foreach($found_guild_titles as $found_guild_title) {

		$query = "SELECT `name`, `class`, `level`, `note`, `guild_title`, DATE_FORMAT(last_online, '%b %d at %l%p') FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_title = '" . $found_guild_title . "' ORDER BY `" . $default_sortby . "` 	ASC";
		$result = mysql_query($query) or die(mysql_error());

		// Print headers
		print "<h4>" . $found_guild_title . "</h4>";
		
		print "<table id=\"memberlist\">
		<tr>
			<th><a href=\"?groupby=guild_title&sortby=name\">Name</a></th>
			<th><a href=\"?groupby=guild_title&sortby=class\">Class</a></th>
			<th><a href=\"?groupby=guild_title&sortby=level\">Level</a></th>
			<th><a href=\"?groupby=guild_title&sortby=note\">Note</a></th>
			<th><a href=\"?groupby=guild_title&sortby=guild_rank\">Rank</a></th>
			<th><a href=\"?groupby=guild_title&sortby=last_online\">Last Online</a></th>
		</tr>";
		
		// Print rows w/ data

		while ( $row = mysql_fetch_row($result) )
		{
			if(strncmp($row[5], date("M d", time()), 3) == 0)
			$row[5] = str_replace(date("M d", time()), "Today", $row[5]);

			?>
			<?php if($i % 2) print "<tr class=\"odd\">"; else print "<tr>"; ?>
				<td><?php has_uploaded_data($row[0], addslashes($server_name)); ?></td>
				<td><?php print $row[1]; ?></td>
				<td><?php print $row[2]; ?></td>
				<td><?php print $row[3]; ?></td>
				<td><?php print $row[4]; ?></td>
				<td align="right"><?php print $row[5]; ?></td>
			</tr>
			<?php

			// Increment our even/odd counter
			$i = $i +1;
		}
		print "</table>";

		mysql_free_result($result);

	}
}



// Determine sorting, if any







?>


</body>
</html>