<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays character information
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require($addon['dir'] . 'inc/header.php');

if( $addon['config']['show_bags'] == 1 )
{
	$bag0 = bag_get( $char->get('member_id'), 'Bag0' );
	if( !is_null( $bag0 ) )
	{
		$char_page .= $bag0->out();
	}

	$bag1 = bag_get( $char->get('member_id'), 'Bag1' );
	if( !is_null( $bag1 ) )
	{
		$char_page .= $bag1->out();
	}

	$bag2 = bag_get( $char->get('member_id'), 'Bag2' );
	if( !is_null( $bag2 ) )
	{
		$char_page .= $bag2->out();
	}

	$bag3 = bag_get( $char->get('member_id'), 'Bag3' );
	if( !is_null( $bag3 ) )
	{
		$char_page .= $bag3->out();
	}

	$bag4 = bag_get( $char->get('member_id'), 'Bag4' );
	if( !is_null( $bag4 ) )
	{
		$char_page .= $bag4->out();
	}

	$bag5 = bag_get( $char->get('member_id'), 'Bag5' );
	if( !is_null( $bag5 ) )
	{
		$char_page .= $bag5->out();
	}
}

require($addon['dir'] . 'inc/footer.php');
