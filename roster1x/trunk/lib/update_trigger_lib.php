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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

////////////////////////////////////////////////////
//
// Function area - please don't change anything
// unless you know what you're doing!
//
////////////////////////////////////////////////////
//
// Read the member name by the get var name.
// If a get var mid is set, the routine will read
// the name out of the database with member_id.
//

function start_update_trigger($name,$mode)
{
	global $wowdb, $roster_conf, $wordings;

	$query = "SELECT `member_id` FROM `".ROSTER_MEMBERSTABLE."` WHERE `name` = '$name'";
	$result = $wowdb->query($query)	or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$row = $wowdb->fetch_assoc($result);
	$member_id = $row['member_id'];

	// Strip everything except numbers out of $member_id
	$member_id = ereg_replace ('/[^0-9]/', '', $member_id);

	$output = "\n";
	if ( $member_id > 0 )
	{
		// Get the char name with the member_id
		if( read_name($member_id) )
		{
			$member_name = read_name($member_id);

			$triggerPath = ROSTER_BASE.'addons';

			if ( $handle = opendir( $triggerPath ) )
			{
				while (false !== ($file = readdir($handle)))
				{
					if($file != '.' && $file != '..' )
					{
						$triggers[$i] = $file;
						$i++;
					}
				}
			}

			if( count($triggers) > 0 )
			{
				// Start ouput buffering
				ob_start();

				foreach ($triggers as $trigger)
				{
					$triggerfile = $triggerPath.DIR_SEP.$trigger.DIR_SEP.'trigger.php';
					$triggerconf = $triggerPath.DIR_SEP.$trigger.DIR_SEP.'conf.php';
					$addonDir = $triggerPath.DIR_SEP.$trigger.DIR_SEP;

					if ( file_exists($triggerfile) )
					{
						if ( file_exists($triggerconf) )
							include( $triggerconf );

						include( $triggerfile );
					}
				}

				// Get buffer contents
				$ob_output .= ob_get_contents();
				ob_end_clean();

				if ( empty($ob_output) )
				{
					return '';
				}
				else
				{
					$output .= $ob_output;
				}
			}
			else
			{
				$output = '';
			}
		}
	}
	return $output;
}


// Search for character's name by member_id
// If there is one, get the name of the char with this member id
// This will prevent sql injection
function read_name( $member_id )
{
	global $wowdb;

	// Make sure that there only numbers in it
	$member_id = ereg_replace ('/[^0-9]/', '', $member_id);

	// Test if member_id exists
	if( !$member_id )
	{
		return 0;
	}
	else
	{
		// Get the name from members with the member_id
		$query_name = "SELECT `name` FROM `".ROSTER_MEMBERSTABLE."` WHERE `member_id` = '".$member_id."';";
		$result_name = $wowdb->query($query_name) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_name);
		if ($row_name = $wowdb->fetch_assoc($result_name))
		{
			$name = $row_name['name'];
		}
		else
		{
			return 0;
		}
	}
	return $name;
}
