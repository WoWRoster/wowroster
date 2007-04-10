<?php
$versions['versionDate']['updatetriglib'] = '$Date: 2006/01/15 22:48:46 $'; 
$versions['versionRev']['updatetriglib'] = '$Revision: 1.9 $'; 
$versions['versionAuthor']['updatetriglib'] = '$Author: zanix $';

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
	$query = 'SELECT `member_id` FROM '.ROSTER_MEMBERSTABLE." WHERE `name` = '$name'";
	$result = mysql_query($query)
		or die(mysql_error());
	$row = mysql_fetch_row($result);
	$member_id = $row[0];

	// Strip everything except numbers out of $member_id
	$member_id = ereg_replace ('/[^0-9]/', '', $member_id);

	$output = '';
	if ( $member_id > 0 )
	{
		global $roster_dir;

		// Get the char name with the member_id
			if( read_name($member_id) )
			{
				$member_name = read_name($member_id);
				$output = '<span style="color: #00FF00;">Running Update Triggers</span><br />'."\n";

				global $paths;

				$sep = DIRECTORY_SEPARATOR;
				$triggerPath = '../addons';

				if ( $handle = opendir( realpath($triggerPath) ) )
				{
					while (false !== ($file = readdir($handle)))
					{
						if ($file != '.' && $file != '..')
						{
							$triggers[$i] = $file;
							$i++;
						}
					}
				}

				// Start ouput buffering
				ob_start();

				foreach ($triggers as $trigger)
				{
					$triggerfile = $triggerPath.$sep.$trigger.$sep.'trigger.php';
					if ( file_exists($triggerfile) )
					{
						include( $triggerfile );
					}
				}

				// Get buffer contents
				$output .= ob_get_contents();
				ob_end_clean();

				if ( empty($output) )
				{
					return '';
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

	// Test if member_id exists
	if (!$member_id)
	{
		return 0;
	}
	else
	{
		// Make sure that there only numbers in it
		$member_id = ereg_replace ('/[^0-9]/', '', $member_id);

		// Get the name from members with the member_id
		$query_name = "SELECT `name` FROM ".ROSTER_MEMBERSTABLE." WHERE `member_id` = ".$member_id.";";
		$result_name = mysql_query($query_name) or die(mysql_error());
		if ($row_name = mysql_fetch_row($result_name))
		{
			$name = $row_name[0];
		}
		else
		{
			return 0;
		}
	}
	return $name;
}
?>