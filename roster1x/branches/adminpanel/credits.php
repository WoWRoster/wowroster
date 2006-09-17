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

require_once( 'settings.php' );


$header_title = $wordings[$roster_conf['roster_lang']]['credit'];
include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_LIB.'menu.php');


echo "<div style=\"font-size:12px;\">\n".$creditspage['top']."\n</div>\n";


// format table locations
echo "<table cellspacing=\"10\"><tr><td valign=\"top\">\n";


// Print devs
echo border('sgreen','start','Active Devs')."<table cellspacing=\"0\">\n";
echo "<tr>
<th class=\"membersHeader\">Name</th>
<th class=\"membersHeaderRight\">Info</th>
";

$strip_count = 1;
foreach( $creditspage['devs']['active'] as $dev )
{
	$stripe_class = 'membersRowColor'.( ( ++$strip_count % 2 ) + 1 );
	print "\t<tr class=\"$stripe_class\">\n";
	print "\t\t<td class=\"membersRowCell\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"membersRowRightCell\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sgreen','end')."<br />\n";

// Print third party contributions
echo border('spurple','start','3rd party contributions')."<table cellspacing=\"0\">\n";
echo "<tr>
<th class=\"membersHeader\">Name</th>
<th class=\"membersHeaderRight\">Info</th>
";

$strip_count = 1;
foreach( $creditspage['devs']['3rdparty'] as $dev )
{
	$stripe_class = 'membersRowColor'.( ( ++$strip_count % 2 ) + 1 );
	print "\t<tr class=\"$stripe_class\">\n";
	print "\t\t<td class=\"membersRowCell\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"membersRowRightCell\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('spurple','end')."<br />\n";

// Print used libraries
echo border('sorange','start','Javascript libraries')."<table cellspacing=\"0\">\n";
echo "<tr>
<th class=\"membersHeader\">Name</th>
<th class=\"membersHeaderRight\">Info</th>
";

$strip_count = 1;
foreach( $creditspage['devs']['library'] as $dev )
{
	$stripe_class = 'membersRowColor'.( ( ++$strip_count % 2 ) + 1 );
	print "\t<tr class=\"$stripe_class\">\n";
	print "\t\t<td class=\"membersRowCell\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"membersRowRightCell\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sorange','end');


// format table locations
echo "\n</td><td valign=\"top\">\n";


// Print inactive devs
echo border('sred','start','Inactive Devs')."<table width=\"100%\" cellspacing=\"0\">\n";
echo "<tr>
<th class=\"membersHeader\">Name</th>
<th class=\"membersHeaderRight\">Info</th>
";

$strip_count = 1;
foreach( $creditspage['devs']['inactive'] as $dev )
{
	$stripe_class = 'membersRowColor'.( ( ++$strip_count % 2 ) + 1 );
	print "\t<tr class=\"$stripe_class\">\n";
	print "\t\t<td class=\"membersRowCell\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"membersRowRightCell\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sred','end')."<br />\n";


// Print the beta team
echo border('syellow','start','WoWRoster Beta Team')."<table width=\"100%\" cellspacing=\"0\">\n";
echo "<tr>
<th class=\"membersHeader\">Name</th>
<th class=\"membersHeaderRight\">Info</th>
";

$strip_count = 1;
foreach( $creditspage['devs']['beta'] as $dev )
{
	$stripe_class = 'membersRowColor'.( ( ++$strip_count % 2 ) + 1 );
	print "\t<tr class=\"$stripe_class\">\n";
	print "\t\t<td class=\"membersRowCell\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"membersRowRightCell\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('syellow','end');

// format table locations
echo "\n</td></tr></table>\n";


$AddonCredits = makeAddonCredits();
if($AddonCredits != '') {
	// Print the Addon developer credits
	echo "<br />\n" . border('sblue','start','WoWRoster Addons') . "<table width=\"100%\" cellspacing=\"0\">\n";
	echo "<tr>
<th class=\"membersHeader\">Addon</th>
<th class=\"membersHeaderRight\">Info</th>
";
	echo $AddonCredits;
	echo "</table>\n".border('sblue','end')."<br />\n";
}

echo "<div style=\"font-size:12px;\">\n".$creditspage['bottom']."\n</div>\n";

include_once (ROSTER_BASE.'roster_footer.tpl');

/**
 * Gets the list of currently installed roster addons
 *
 * @return string formatted list of addons
 */
function makeAddonCredits()
{
	global $wowdb, $roster_conf, $wordings;

	$query = "SELECT `fullname`, `version`, `description`, `credits` FROM `".$wowdb->table('addon')."` WHERE `active` = '1';";

	$result = $wowdb->query($query);

	if (!$result)
	{
		return;
	}

	$output = '';
	while ($row = $wowdb->fetch_assoc($result))
	{
		$row['credits'] = unserialize($row['credits']);

		$output .= "\t<tr class=\"membersRowColor2\">\n";
		$output .= "\t\t<td class=\"membersRowCell\">" . $row['fullname'] . " <span class=\"blue\">v" . $row['version'] . "</span></td>\n";
		$output .= "\t\t<td class=\"membersRowRightCell\"><span class=\"yellow\">" . $row['description'] . "</span></td>\n";
		$output .= "\t</tr>\n";

		if( is_array($row['credits']) )
		{
			foreach( $row['credits'] as $dev )
			{
				$output .= "\t<tr class=\"membersRowColor1\">\n";
				$output .= "\t\t<td class=\"membersRowCell\"><div align=\"right\">" . $dev['name']."</div></td>\n";
				$output .= "\t\t<td class=\"membersRowRightCell\">" . $dev['info'] . "</td>\n";
				$output .= "\t</tr>\n";
			}
		}

		$lCount += 1;
	}

	if ($lCount < 1)
	{
		return '';
	}

	return $output;
}
?>