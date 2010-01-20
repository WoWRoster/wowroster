<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays character inventory
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage Inventory
 */

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include( $addon['inc_dir'] . 'header.php' );

$s_bags = false;
$s_key = false;
$s_bank = false;

$char->fetchEquip();

// Equipment
$char->equip_slot('Head');
$char->equip_slot('Neck');
$char->equip_slot('Shoulder');
$char->equip_slot('Back');
$char->equip_slot('Chest');
$char->equip_slot('Shirt');
$char->equip_slot('Tabard');
$char->equip_slot('Wrist');

$char->equip_slot('MainHand');
$char->equip_slot('SecondaryHand');
$char->equip_slot('Ranged');
$char->equip_slot('Ammo');

$char->equip_slot('Hands');
$char->equip_slot('Waist');
$char->equip_slot('Legs');
$char->equip_slot('Feet');
$char->equip_slot('Finger0');
$char->equip_slot('Finger1');
$char->equip_slot('Trinket0');
$char->equip_slot('Trinket1');

if( $roster->auth->getAuthorized($addon['config']['show_bags']) )
{
	$bag0 = bag_get( $char->get('member_id'), 'Bag0' );
	if( !is_null( $bag0 ) )
	{
		$bag0->out(true);
		$s_bags = true;
	}

	$bag1 = bag_get( $char->get('member_id'), 'Bag1' );
	if( !is_null( $bag1 ) )
	{
		$bag1->out(true);
		$s_bags = true;
	}

	$bag2 = bag_get( $char->get('member_id'), 'Bag2' );
	if( !is_null( $bag2 ) )
	{
		$bag2->out(true);
		$s_bags = true;
	}

	$bag3 = bag_get( $char->get('member_id'), 'Bag3' );
	if( !is_null( $bag3 ) )
	{
		$bag3->out(true);
		$s_bags = true;
	}

	$bag4 = bag_get( $char->get('member_id'), 'Bag4' );
	if( !is_null( $bag4 ) )
	{
		$bag4->out(true);
		$s_bags = true;
	}

	$bag5 = bag_get( $char->get('member_id'), 'Bag5' );
	if( !is_null( $bag5 ) )
	{
		$bag5->out(true);
		$s_key = true;
	}
}

if( $roster->auth->getAuthorized($addon['config']['show_bank']) )
{
	$bag0 = bag_get( $char->get('member_id'), 'Bank Bag0' );
	if( !is_null( $bag0 ) )
	{
		$bag0->out(true);
		$s_bank = true;
	}

	$bag1 = bag_get( $char->get('member_id'), 'Bank Bag1' );
	if( !is_null( $bag1 ) )
	{
		$bag1->out(true);
		$s_bank = true;
	}

	$bag2 = bag_get( $char->get('member_id'), 'Bank Bag2' );
	if( !is_null( $bag2 ) )
	{
		$bag2->out(true);
		$s_bank = true;
	}

	$bag3 = bag_get( $char->get('member_id'), 'Bank Bag3' );
	if( !is_null( $bag3 ) )
	{
		$bag3->out(true);
		$s_bank = true;
	}

	$bag4 = bag_get( $char->get('member_id'), 'Bank Bag4' );
	if( !is_null( $bag4 ) )
	{
		$bag4->out(true);
		$s_bank = true;
	}

	$bag5 = bag_get( $char->get('member_id'), 'Bank Bag5' );
	if( !is_null( $bag5 ) )
	{
		$bag5->out(true);
		$s_bank = true;
	}

	$bag6 = bag_get( $char->get('member_id'), 'Bank Bag6' );
	if( !is_null( $bag6 ) )
	{
		$bag6->out(true);
		$s_bank = true;
	}

	$bag7 = bag_get( $char->get('member_id'), 'Bank Bag7' );
	if( !is_null( $bag7 ) )
	{
		$bag7->out(true);
		$s_bank = true;
	}
}

$roster->tpl->assign_vars(array(
	'S_BAG' => $s_bag,
	'S_KEY' => $s_key,
	'S_BANK' => $s_bank
	)
);

$roster->tpl->set_filenames(array('inventory' => $addon['basename'] . '/inventory.html'));
$roster->tpl->display('inventory');
