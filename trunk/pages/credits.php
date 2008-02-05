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
 * @package    WoWRoster
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['credit'];

echo "<div style=\"font-size:12px;\">\n".$roster->locale->creditspage['top']."\n</div>\n";

// format table locations
echo "<table cellspacing=\"10\"><tr><td valign=\"top\" width=\"50%\">\n";


// Print devs
echo border('sgreen','start','Active Developers')."<table cellspacing=\"0\">\n";
echo "<tr>
  <th class=\"membersHeader\">Name</th>
  <th class=\"membersHeaderRight\">Info</th>
</tr>
";

$strip_count = 1;
foreach( $roster->locale->creditspage['devs']['active'] as $dev )
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
foreach( $roster->locale->creditspage['devs']['3rdparty'] as $dev )
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
echo "\n</td><td valign=\"top\" width=\"50%\">\n";


// Print inactive devs
echo border('sred','start','Retired/Inactive Developers')."<table width=\"100%\" cellspacing=\"0\">\n";
echo "<tr>
  <th class=\"membersHeader\">Name</th>
  <th class=\"membersHeaderRight\">Info</th>
</tr>
";

$strip_count = 1;
foreach( $roster->locale->creditspage['devs']['inactive'] as $dev )
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
foreach( $roster->locale->creditspage['devs']['library'] as $dev )
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

echo "<div style=\"font-size:12px;\">\n".$roster->locale->creditspage['bottom']."\n</div>\n";


/**
 * Gets the list of currently installed roster addons
 *
 * @return string formatted list of addons
 */
function makeAddonCredits()
{
	global $roster;

	$output = '';

	$query = "SELECT * FROM `".$roster->db->table('addon')."`";
	$result = $roster->db->query($query);

	if( !$result )
	{
		return "\t<tr><td colspan='3'>Error fetching addon credits.<br />MySQL said:".$roster->db->error()."</td></tr>";
	}

	$strip_count = 1;
	while( $row = $roster->db->fetch($result) )
	{
		// Save current locale array
		// Since we add all locales for localization, we save the current locale array
		// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
		$localetemp = $roster->locale->wordings;

		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS . $row['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
		}

		$addonName = ( isset($roster->locale->act[$row['fullname']]) ? $roster->locale->act[$row['fullname']] : $row['fullname'] ) . ' v' . $row['version'];

		// Restore our locale array
		$roster->locale->wordings = $localetemp;
		unset($localetemp);

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
