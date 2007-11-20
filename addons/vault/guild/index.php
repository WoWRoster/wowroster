<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Shows the guild vault
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: index.php 1259 2007-08-21 00:46:34Z Zanix $
 * @link       http://www.wowroster.net
 * @package    Vault
 */

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once (ROSTER_LIB . 'item.php');

$roster->output['title'] = $roster->locale->act['vault'];

// Assign template vars
$roster->tpl->assign_vars(array(
	'L_VAULT' => $roster->locale->act['vault'],
	'L_LOG'     => $roster->locale->act['vault_log'],
	'L_MONEY_LOG' => $roster->locale->act['vault_money_log'],
	)
);









$roster->tpl->set_filenames(array(
	'vault' => $addon['basename'] . '/vault.html',
	'log' => $addon['basename'] . '/log.html',
	'moneylog' => $addon['basename'] . '/moneylog.html',
	)
);
//$roster->tpl->display('vault');
