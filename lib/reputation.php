<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Reputation class and functions
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage Reputation
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Reputation class and functions
 *
 * @package    WoWRoster
 * @subpackage Reputation
 */
class reputation
{
	var $data;
	var $lastfaction;

	function reputation( $data )
	{
		$this->data = $data;
	}

	function get( $field )
	{
		return $this->data[$field];
	}
}

function get_reputation( $member_id )
{
	global $roster;

	$query= "SELECT * FROM `" . $roster->db->table('reputation') . "` WHERE `member_id` = '$member_id' ORDER BY `faction` ASC";
	$result = $roster->db->query( $query );
	$reputations = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$reputation = new reputation( $data );
		$reputations[] = $reputation;
	}
	return $reputations;
}
