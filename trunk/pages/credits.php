<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Roster and Roster AddOn credits
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['credit'];

$roster->tpl->assign_vars(array(
	'S_ADDON_CREDITS' => false,

	'L_TOP_CREDITS' => $roster->locale->creditspage['top'],
	'L_BOTTOM_CREDITS' => $roster->locale->creditspage['bottom'],
	'L_ACTIVE_DEV' => 'Active Developers',
	'L_3RD_PARTY' => '3rd-Party Contributions',
	'L_INACTIVE_DEV' => 'Retired/Inactive Developers',
	'L_JS_LIB' => 'Javascript Libraries',
	'L_ROSTER_ADDONS' => 'WoWRoster Addons',
	'L_VERSION' => 'Version'
));

foreach( $roster->locale->creditspage['devs']['active'] as $dev )
{
	$roster->tpl->assign_block_vars('active_dev', array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'NAME' => $dev['name'],
		'INFO' => $dev['info']
	));
}

foreach( $roster->locale->creditspage['devs']['3rdparty'] as $dev )
{
	$roster->tpl->assign_block_vars('3rd_party', array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'NAME' => $dev['name'],
		'INFO' => $dev['info']
	));
}

foreach( $roster->locale->creditspage['devs']['inactive'] as $dev )
{
	$roster->tpl->assign_block_vars('inactive_dev', array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'NAME' => $dev['name'],
		'INFO' => $dev['info']
	));
}

foreach( $roster->locale->creditspage['devs']['library'] as $dev )
{
	$roster->tpl->assign_block_vars('js_lib', array(
		'ROW_CLASS' => $roster->switch_row_class(),
		'NAME' => $dev['name'],
		'INFO' => $dev['info']
	));
}

makeAddonCredits();

$roster->tpl->set_handle('body', 'credits.html');
$roster->tpl->display('body');

/**
 * Gets the list of currently installed roster addons
 *
 * @return string formatted list of addons
 */
function makeAddonCredits( )
{
	global $roster;

	if( count($roster->addon_data) == 0 )
	{
		return;
	}

	$roster->tpl->assign_var('S_ADDON_CREDITS', true);

	foreach( $roster->addon_data as $addon )
	{
		// Save current locale array
		// Since we add all locales for localization, we save the current locale array
		// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
		$localetemp = $roster->locale->wordings;

		foreach( $roster->multilanguages as $lang )
		{
			$roster->locale->add_locale_file(ROSTER_ADDONS . $addon['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php', $lang);
		}

		$addon_name = (isset($roster->locale->act[$addon['fullname']]) ? $roster->locale->act[$addon['fullname']] : $addon['fullname']);

		// Restore our locale array
		$roster->locale->wordings = $localetemp;
		unset($localetemp);

		$roster->tpl->assign_block_vars('addon_row', array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'NAME' => $addon_name,
			'VERSION' => $addon['version']
		));

		$addon_array = unserialize($addon['credits']);

		foreach( $addon_array as $addon_dev )
		{
			$roster->tpl->assign_block_vars('addon_row.addon_credit_row', array(
				'NAME' => $addon_dev['name'],
				'INFO' => $addon_dev['info']
			));
		}
	}
}
