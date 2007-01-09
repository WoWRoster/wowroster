<?php
/******************************
 * $Id$
 ******************************/



/*
	Start the following scripts when "update.php" is called

	Available variables
		- $wowdb       = roster's db layer
		- $member_id   = character id from the database ( ex. 24 )
		- $member_name = character's name ( ex. 'Jonny Grey' )
		- $roster_conf = The entire roster config array
		- $mode        = when you want to run the trigger
			= 'char'  - during a character update
			= 'guild' - during a guild update

	You may need to do some fancy coding if you need more variables

	You can just print any needed output

*/
//----------[ INSERT UPDATE TRIGGER BELOW ]-----------------------

require_once('maxres.php');
require_once(ROSTER_BASE.'/lib/item.php');

global $wordings;
include('localization.php');

if( $mode == 'char' )
{
	$client_lang_query = ("SELECT clientLocale FROM ".ROSTER_PLAYERSTABLE." WHERE member_id = ". $member_id);
	$cl_result = $wowdb->query($client_lang_query) or die($wowdb->error());
	$row = $wowdb->fetch_row($cl_result);
	$client_lang = $row[0];
	$success = 0;
	if(strlen($client_lang) != 0)
	{

		$res_all	= getres($member_id, $client_lang, $wordings[$client_lang]['res_all']);
		$res_fire	= getres($member_id, $client_lang, $wordings[$client_lang]['Fire Resistance']);
		$res_nat	= getres($member_id, $client_lang, $wordings[$client_lang]['Nature Resistance']);
		$res_arc	= getres($member_id, $client_lang, $wordings[$client_lang]['Arcane Resistance']);
		$res_fro	= getres($member_id, $client_lang, $wordings[$client_lang]['Frost Resistance']);
		$res_shad	= getres($member_id, $client_lang, $wordings[$client_lang]['Shadow Resistance']);
				
		$max_res = ("UPDATE ".ROSTER_PLAYERSTABLE." SET `max_res_all`='$res_all', `max_res_fire`='$res_fire', `max_res_nat`='$res_nat', `max_res_arc`='$res_arc', `max_res_fro`='$res_fro', `max_res_shad`='$res_shad' WHERE ".ROSTER_PLAYERSTABLE.".member_id='".$member_id."'");
		$max_res_result = $wowdb->query($max_res) or die($wowdb->error());
		$success = 1;
	}

	if($success)
	{
		print '<span style="color:blue;font-size:10px;">Updated Resistances for ['.$member_name."]</span><br />\n";
	}
	else
	{
		print '<span style="color:red;font-size:10px;">Resistance Update for ['.$member_name."] failed</span><br />\n";
	}

}

?>

