<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Roster and Roster AddOn credits
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$header_title = $act_words['credit'];
include_once (ROSTER_BASE.'roster_header.tpl');

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');


echo "<div style=\"font-size:12px;\">\n".$creditspage['top']."\n</div>\n";


// format table locations
echo "<table cellspacing=\"10\"><tr><td valign=\"top\">\n";


// Print devs
echo border('sgreen','start','Active Devs')."<table cellspacing=\"0\">\n";
echo "<tr>
  <th class=\"membersHeader\">Name</th>
  <th class=\"membersHeaderRight\">Info</th>
</tr>
";

$strip_count = 1;
foreach( $creditspage['devs']['active'] as $dev )
{
	$stripe_class = 'membersRow'.( ( ++$strip_count % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $strip_count % 2 ) + 1 );
	print "\t<tr>\n";
	print "\t\t<td class=\"$stripe_class\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"$stripe_class_right\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sgreen','end')."<br />\n";

// Print third party contributions
echo border('spurple','start','3rd-Party Contributions')."<table cellspacing=\"0\">\n";
echo "<tr>
  <th class=\"membersHeader\">Name</th>
  <th class=\"membersHeaderRight\">Info</th>
</tr>
";

$strip_count = 1;
foreach( $creditspage['devs']['3rdparty'] as $dev )
{
	$stripe_class = 'membersRow'.( ( ++$strip_count % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $strip_count % 2 ) + 1 );
	print "\t<tr>\n";
	print "\t\t<td class=\"$stripe_class\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"$stripe_class_right\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('spurple','end')."<br />\n";


// format table locations
echo "\n</td><td valign=\"top\">\n";


// Print inactive devs
echo border('sred','start','Inactive Devs')."<table width=\"100%\" cellspacing=\"0\">\n";
echo "<tr>
  <th class=\"membersHeader\">Name</th>
  <th class=\"membersHeaderRight\">Info</th>
</tr>
";

$strip_count = 1;
foreach( $creditspage['devs']['inactive'] as $dev )
{
	$stripe_class = 'membersRow'.( ( ++$strip_count % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $strip_count % 2 ) + 1 );
	print "\t<tr>\n";
	print "\t\t<td class=\"$stripe_class\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"$stripe_class_right\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sred','end')."<br />\n";


// Print used libraries
echo border('sorange','start','Javascript Libraries')."<table cellspacing=\"0\">\n";
echo "<tr>
  <th class=\"membersHeader\">Name</th>
  <th class=\"membersHeaderRight\">Info</th>
</tr>
";

$strip_count = 1;
foreach( $creditspage['devs']['library'] as $dev )
{
	$stripe_class = 'membersRow'.( ( ++$strip_count % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $strip_count % 2 ) + 1 );
	print "\t<tr>\n";
	print "\t\t<td class=\"$stripe_class\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"$stripe_class_right\">".$dev['info']."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sorange','end');


// format table locations
echo "\n</td></tr></table>\n";


$AddonCredits = makeAddonCredits();
if($AddonCredits != '')
{
	// Print the Addon developer credits
	echo "<br />\n" . border('sblue','start','WoWRoster Addons') . "<table width=\"100%\" cellspacing=\"0\">\n";
	echo "<tr>
  <th class=\"membersHeader\">Addon</th>
  <th class=\"membersHeader\">Author</th>
  <th class=\"membersHeaderRight\">Info</th>
</tr>
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
	global $roster_conf, $wowdb;

	$output = '';

	$query = "SELECT * FROM `".ROSTER_ADDONTABLE."`";
	$result = $wowdb->query($query);

	if( !$result )
	{
		return "\t<tr><td colspan='3'>Error fetching addon credits.<br />MySQL said:".$wowdb->error()."</td></tr>";
	}

	$strip_count = 1;
	while( $row = $wowdb->fetch_assoc($result) )
	{
		$addonName = $row['fullname'];
		$AddOnArray = unserialize($row['credits']);
		foreach( $AddOnArray as $addonDev )
		{
			$stripe_class = 'membersRow' . ( ( ++$strip_count % 2 ) + 1 );
			$stripe_class_right = 'membersRowRight' . ( ( $strip_count % 2 ) + 1 );
			$output .= "\t<tr>\n";
			$output .= "\t\t<td class=\"$stripe_class\">" . $addonName . "</td>\n";
			$output .= "\t\t<td class=\"$stripe_class\">" . $addonDev['name']."</td>\n";
			$output .= "\t\t<td class=\"$stripe_class_right\">" . $addonDev['info'] . "</td>\n";
			$output .= "\t</tr>\n";
			$addonName = '&nbsp;';
		}
	}

	return $output;
}
