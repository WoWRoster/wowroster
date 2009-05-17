<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Skill class and functions
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage Skill
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Skill class and functions
 *
 * @package    WoWRoster
 * @subpackage Skill
 */
class skill
{
	var $data;

	function skill( $data )
	{
		$this->data = $data;
	}

	function get( $field )
	{
		return $this->data[$field];
	}
}

function skill_get_many_by_type( $member_id , $type )
{
	global $roster;

	$type = $roster->db->escape($type);

	return skill_get_many($member_id, "`skill_type` = '$type'");
}

function skill_get_many_by_order( $member_id , $order )
{
	global $roster;

	$order = $roster->db->escape($order);

	return skill_get_many($member_id, "`skill_order` = '$order'");
}

function skill_get_many( $member_id )
{
	global $roster;

	if( isset($char) )
	{
		$char = $roster->db->escape($char);
	}
	if( isset($server) )
	{
		$server = $roster->db->escape($server);
	}
	$query = "SELECT * FROM `" . $roster->db->table('skills') . "` WHERE `member_id` = '$member_id';";

	$result = $roster->db->query($query);

	$skills = array();
	while( $data = $roster->db->fetch($result) )
	{
		$skill = new skill($data);
		$skills[$skill->data['skill_order']][] = $skill;
	}
	return $skills;
}
