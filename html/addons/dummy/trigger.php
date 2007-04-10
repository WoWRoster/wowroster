<?php
/* 
* $Date: 2006/01/15 22:48:46 $ 
* $Revision: 1.5 $ 
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

// Include dummy trigger conf file
include('conf.php');

// Run this on a character update
if( $mode == 'char' )
{
	if( $addon_conf['dummy']['trigger_1'] )
	{
		print "<span style=\"color: #0000FF;\">Dummy Addon: This is trigger 1 for a character update</span><br />\n";
	}

	if( $addon_conf['dummy']['trigger_2'] )
	{
		print "<span style=\"color: #0000FF;\">Dummy Addon: This is trigger 2 for a character update</span><br />\n";
	}
}

// Run this on a guild update
if( $mode == 'guild' )
{
	if( $addon_conf['dummy']['trigger_1'] )
	{
		print "<span style=\"color: #0000FF;\">Dummy Addon: This is trigger 1 for a guild update</span><br />\n";
	}

	if( $addon_conf['dummy']['trigger_2'] )
	{
		print "<span style=\"color: #0000FF;\">Dummy Addon: This is trigger 2 for a guild update</span><br />\n";
	}
}
?>