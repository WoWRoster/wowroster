<?php
/*
* $Date: 2006/02/02 07:08:07 $
* $Revision: 1.9 $
* $Author: zanix $
*/

if( eregi('trigger.php',$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}

/*
	Start the following scripts when "update.php" is called

	Available variables
		- $member_id   = character id form the database ( ex. 24 )
		- $member_name = character's name ( ex. 'Jonny Grey' )
		- $roster_dir  = full url to roster directory with NO ending slash ( ex. 'http://yoursite.com/roster' )
		- $mode        = when you want to run the trigger
			= 'char'  - during a character update
			= 'guild' - during a guild update

	You may need to do some fancy coding if you need more variables

	You can just print any needed output

*/
//----------[ INSERT UPDATE TRIGGER BELOW ]-----------------------


// The following is an example "trigger.php" file form zanix's SigGen

global $wowdb, $db_prefix;

$get_trigger = TRUE;
// Include siggen conf file
include('conf.php');


// Run this on a character update
if( $mode == 'char' )
{
	// "saveonly=1" tells SigGen not to output an image

	if( $sc_trigger_sig )
	{
		if( @readfile($roster_dir.'/addons/siggen/sig.php?saveonly=1&name='.urlencode(utf8_decode($member_name)) ) )
			print '<span style="color: #0000FF;">Signature Created for ['.$member_name."]</span><br />\n";
		else
			print '<span style="color: #FF0000;">Cannot Create Signature for ['.$member_name."]</span><br />\n";
	}

	if( $sc_trigger_ava )
	{
		if( @readfile($roster_dir.'/addons/siggen/av.php?saveonly=1&name='.urlencode(utf8_decode($member_name)) ) )
			print '<span style="color: #0000FF;">Avatar Created for ['.$member_name."]</span><br />\n";
		else
			print '<span style="color: #FF0000;">Cannot Create Avatar for ['.$member_name."]</span><br />\n";
	}
}


// Run this on a guild update
if( $mode == 'guild' )
{

	// Check for safe mode
	if( !ini_get('safe_mode') )
	{
		set_time_limit(15);
	}

	// "saveonly=1" tells SigGen not to output an image

	if( $sc_guild_trigger_sig )
	{
		if( @readfile($roster_dir.'/addons/siggen/sig.php?saveonly=1&name='.urlencode(utf8_decode($member_name)) ) )
			print '<span style="color: #0000FF;">Signature Created for ['.$member_name."]</span><br />\n";
		else
			print '<span style="color: #FF0000;">Cannot Create Signature for ['.$member_name."]</span><br />\n";
	}

	if( $sc_guild_trigger_ava )
	{
		if( @readfile($roster_dir.'/addons/siggen/av.php?saveonly=1&name='.urlencode(utf8_decode($member_name)) ) )
			print '<span style="color: #0000FF;">Avatar Created for ['.$member_name."]</span><br />\n";
		else
			print '<span style="color: #FF0000;">Cannot Create Avatar for ['.$member_name."]</span><br />\n";
	}
}
?>