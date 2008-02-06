<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Skill class and functions
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

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

	function outHeader()
	{
		return '<div class="skilltype">' . $this->data['skill_type'] . ' </div>';
	}
	function out()
	{
		global $roster;

		list($level, $max) = explode( ':', $this->data['skill_level'] );
		if( $max == 1 )
		{
			$bgImage = $roster->config['img_url'] . 'bargrey.gif';
		}
		else
		{
			$bgImage = $roster->config['img_url'] . 'barempty.gif';
		}

		$returnstring = '
<div class="skill">
  <div class="skillbox">
    <img class="bg" alt="" src="' . $bgImage . '" />';
		if( $max > 1 )
		{
			$width = intval(($level/$max) * 354);
			$returnstring .= '<img src="' . $roster->config['img_url'] . 'barbit.gif" alt="" class="bit" width="' . $width . '" />';
		}

		$returnstring .= '
    <span class="name">' . $this->data['skill_name'] . '</span>';

		if( $max > 1 )
		{
			$returnstring .= '<span class="level">' . $level . '/' . $max . '</span>';
		}
		$returnstring .= '
  </div>
</div>
';

		return $returnstring;
	}
}

function skill_get_many_by_type( $member_id, $type )
{
	global $roster;

	$type = $roster->db->escape( $type );

	return skill_get_many( $member_id, "`skill_type` = '$type'" );
}

function skill_get_many_by_order( $member_id, $order )
{
	global $roster;

	$order = $roster->db->escape( $order );

	return skill_get_many( $member_id, "`skill_order` = '$order'" );
}

function skill_get_many( $member_id )
{
	global $roster;

	if (isset($char))
	{
		$char = $roster->db->escape( $char );
	}
	if (isset($server))
	{
		$server = $roster->db->escape( $server );
	}
	$query= "SELECT * FROM `" . $roster->db->table('skills') . "` WHERE `member_id` = '$member_id';";

	$result = $roster->db->query( $query );

	$skills = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$skill = new skill( $data );
		$skills[$skill->data['skill_order']][] = $skill;
	}
	return $skills;
}
