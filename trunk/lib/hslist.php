<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Honor ranking list
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Create Row
 *
 * @param string $sc
 * @return string
 */
function rankMid($sc)
{
	return '    <td class="membersRow'.$sc.'">';
}

/**
 * Create Right Side Row
 *
 * @param string $sc
 * @return string
 */
function rankRight($sc)
{
	return '    <td class="membersRowRight'.$sc.'">';
}

/**
 * Generate the Honor List
 *
 * @return string
 */
function generateHsList( )
{
	global $roster;

	$output = '<table width="100%" cellpadding="0" cellspacing="0" class="bodyline">' . "\n";

	$striping_counter = 0;

	//Highest Lifetime Rank
	$query = "SELECT `name`, `lifetimeRankName` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `lifetimeHighestRank`DESC, `lifetimeHK` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		// Striping rows
		$output .= "  <tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= '<a href="' . makelink('guildhonor&amp;s=lifetimeRankName') . '">' . $roster->locale->act['hslist1'] . '</a></td>' . "\n";
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= $row['name'];
		$output .= "</td>\n";
		$output .= rankRight((($striping_counter % 2) +1));
		if( $row['lifetimeRankName'] == '' )
		{
			$output .= '&nbsp;';
		}
		else
		{
			$output .= $row['lifetimeRankName'];
		}
		$output .= "</td>\n  </tr>\n";
	}

	//Highest LifeTime HKs
	$query = "SELECT `name`, `lifetimeHK` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `lifetimeHK` DESC, `lifetimeHighestRank` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		// Striping rows
		$output .= "  <tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= '<a href="' . makelink('guildhonor&amp;s=lifetimeHK') . '">' . $roster->locale->act['hslist2'] . '</a></td>' . "\n";
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= $row['name'];
		$output .= "</td>\n";
		$output .= rankRight((($striping_counter % 2) +1));
		$output .= $row['lifetimeHK'];
		$output .= "</td>\n  </tr>\n";
	}

	//Highest honorpoints
	$query = "SELECT `name`, `honorpoints` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `honorpoints` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		// Striping rows
		$output .= "  <tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= '<a href="' . makelink('guildhonor&amp;s=honorpoints') . '">' . $roster->locale->act['hslist3'] . '</a></td>' . "\n";
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= $row['name'];
		$output .= "</td>\n";
		$output .= rankRight((($striping_counter % 2) +1));
		$output .= $row['honorpoints'];
		$output .= "</td>\n  </tr>\n";
	}

	//Highest arenapoints
	$query = "SELECT `name`, `arenapoints` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `arenapoints` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		// Striping rows
		$output .= "  <tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= '<a href="' . makelink('guildhonor&amp;s=arenapoints') . '">' . $roster->locale->act['hslist4'] . '</a></td>' . "\n";
		$output .= rankMid((($striping_counter % 2) +1));
		$output .= $row['name'];
		$output .= "</td>\n";
		$output .= rankRight((($striping_counter % 2) +1));
		$output .= $row['arenapoints'];
		$output .= "</td>\n  </tr>\n";
	}

	$output .= "</table>\n";
	$roster->db->free_result($result);

	return messageboxtoggle($output, $roster->locale->act['hslist'], 'sgray', false, $width='400px');
}
