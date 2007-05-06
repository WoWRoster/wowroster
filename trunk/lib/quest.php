<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Quest class and functions
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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class quest
{
	var $data;

	function quest( $data )
	{
		$this->data = $data;
	}

	function get( $field )
	{
		return $this->data[$field];
	}

	function outHeader()
	{
		echo '<div class="questtype">'.$this->data['quest_zone'].' </div>';
	}

	function out2()
	{
		echo '<b><font face="Georgia" size="+1" color="#0000FF"></font></b>';
		echo '['.$this->data['quest_level'].'] '.$this->data['quest_name'];
	}

	function out()
	{
		global $roster;

		$max = ROSTER_MAXCHARLEVEL;
		$level = $this->data['quest_level'];
		if( $max == 1 )
		{
			$bgImage = $roster->config['img_url'].'bargrey.gif';
		}
		else
		{
			$bgImage = $roster->config['img_url'].'barempty.gif';
		}

		echo '
	<div class="quest">
		<div class="questbox">
			<img class="bg" alt="" src="'.$bgImage.'" />';
		if( $max > 1 )
		{
			$width = intval(($level/$max) * 354);
			echo '<img src="'.$roster->config['img_url'].'barbit.gif" alt="" class="bit" width="'.$width.'" />';
		}
		echo '
		<span class="name">'.$this->data['quest_name'].'</span>';
		if( $max > 1 )
		{
			echo '<span class="level"> ['.$level.']</span>';
		}
			echo '</div></div>';
	}
}

function quest_get_many( $member_id, $search )
{
	global $roster;

	$query= "SELECT * FROM `".$roster->db->table('quests')."` WHERE `member_id` = '$member_id' ORDER BY `zone` ASC, `quest_level` DESC";

	$result = $roster->db->query( $query );

	$quests = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$quest = new quest( $data );
		$quests[] = $quest;
	}
	return $quests;
}
