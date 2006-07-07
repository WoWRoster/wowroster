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


echo "<div style=\"font-size:12px;\">\n".$creditspage[$roster_conf['roster_lang']]['top']."\n</div>\n";


// format table locations
echo "<table cellspacing=\"10\"><tr><td>\n";


// Print devs
echo border('sgreen','start','Active Devs')."<table cellspacing=\"0\">\n";

$strip_count = 1;
foreach( $creditspage[$roster_conf['roster_lang']]['devs']['active'] as $dev )
{
	$stripe_class = 'membersRow'.( ( ++$strip_count % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $strip_count % 2 ) + 1 );
	print "\t<tr>\n";
	print "\t\t<td class=\"$stripe_class\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"$stripe_class_right\">".nl2br($dev['info'])."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sgreen','end');


// format table locations
echo "\n</td><td valign=\"top\">\n";


// Print inactive devs
echo border('sred','start','Inactive Devs')."<table cellspacing=\"0\">\n";

$strip_count = 1;
foreach( $creditspage[$roster_conf['roster_lang']]['devs']['inactive'] as $dev )
{
	$stripe_class = 'membersRow'.( ( ++$strip_count % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $strip_count % 2 ) + 1 );
	print "\t<tr>\n";
	print "\t\t<td class=\"$stripe_class\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"$stripe_class_right\">".nl2br($dev['info'])."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('sred','end')."<br />\n";


// Print the beta team
echo border('syellow','start','WoWRoster Beta Team')."<table cellspacing=\"0\">\n";

$strip_count = 1;
foreach( $creditspage[$roster_conf['roster_lang']]['devs']['beta'] as $dev )
{
	$stripe_class = 'membersRow'.( ( ++$strip_count % 2 ) + 1 );
	$stripe_class_right = 'membersRowRight'.( ( $strip_count % 2 ) + 1 );
	print "\t<tr>\n";
	print "\t\t<td class=\"$stripe_class\">".$dev['name']."</td>\n";
	print "\t\t<td class=\"$stripe_class_right\">".nl2br($dev['info'])."</td>\n";
	print "\t</tr>\n";
}
echo "</table>\n".border('syellow','end');


// format table locations
echo "\n</td></tr></table>\n";


echo "<div style=\"font-size:12px;\">\n".$creditspage[$roster_conf['roster_lang']]['bottom']."\n</div>\n";

include_once (ROSTER_BASE.'roster_footer.tpl');

?>