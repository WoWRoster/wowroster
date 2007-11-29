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

require_once ($addon['inc_dir'] . 'vault_item.php');
require_once ($addon['inc_dir'] . 'vault_tab.php');
require_once (ROSTER_LIB . 'armory.class.php');

$armory = new RosterArmory($roster->data['region']);

$roster->output['title'] = $roster->locale->act['vault'];


// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin();

// Diplay Password Box
if ( $roster_login->getAuthorized() < 1 )
{
	print($roster_login->getMessage() . $roster_login->getLoginForm(1));
}
else
{
	print($roster_login->getMessage());
}


$tabs = $data = '';

if( $roster_login->getAuthorized() >= $addon['config']['tab1'] )
{
	$tab1 = vault_tab_get( $roster->data['guild_id'], 'Tab1' );
	if( !is_null( $tab1 ) )
	{
		$data .= $tab1->out();
		$tabs .= '			<li class="selected"><a rel="Tab1" class="text">' . $tab1->data['item_name'] . "</a></li>\n";
		$tabs .= '			<li><a rel="Tab1Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
		$data .= vault_log('Tab1');
	}
}

if( $roster_login->getAuthorized() >= $addon['config']['tab2'] )
{
	$tab2 = vault_tab_get( $roster->data['guild_id'], 'Tab2' );
	if( !is_null( $tab2 ) )
	{
		$data .= $tab2->out();
		$tabs .= '			<li><a rel="Tab2" class="text">' . $tab2->data['item_name'] . "</a></li>\n";
		$tabs .= '			<li><a rel="Tab2Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
		$data .= vault_log('Tab2');
	}
}

if( $roster_login->getAuthorized() >= $addon['config']['tab3'] )
{
	$tab3 = vault_tab_get( $roster->data['guild_id'], 'Tab3' );
	if( !is_null( $tab3 ) )
	{
		$data .= $tab3->out();
		$tabs .= '			<li><a rel="Tab3" class="text">' . $tab3->data['item_name'] . "</a></li>\n";
		$tabs .= '			<li><a rel="Tab3Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
		$data .= vault_log('Tab3');
	}
}

if( $roster_login->getAuthorized() >= $addon['config']['tab4'] )
{
	$tab4 = vault_tab_get( $roster->data['guild_id'], 'Tab4' );
	if( !is_null( $tab4 ) )
	{
		$data .= $tab4->out();
		$tabs .= '			<li><a rel="Tab4" class="text">' . $tab4->data['item_name'] . "</a></li>\n";
		$tabs .= '			<li><a rel="Tab4Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
		$data .= vault_log('Tab4');
	}
}

if( $roster_login->getAuthorized() >= $addon['config']['tab5'] )
{
	$tab5 = vault_tab_get( $roster->data['guild_id'], 'Tab5' );
	if( !is_null( $tab5 ) )
	{
		$data .= $tab5->out();
		$tabs .= '			<li><a rel="Tab5" class="text">' . $tab5->data['item_name'] . "</a></li>\n";
		$tabs .= '			<li><a rel="Tab5Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
		$data .= vault_log('Tab5');
	}
}

if( $roster_login->getAuthorized() >= $addon['config']['money'] )
{
	$data .= vault_money();

	$money = vault_log('Money');
	if( $money != '' )
	{
		$data .= $money;
		$tabs .= '			<li><a rel="MoneyLog" class="text">' . $roster->locale->act['vault_money_log'] . "</a></li>\n";
	}
}


if( $data != '' )
{
	print '<div class="vault" style="background-image:url(' . $addon['image_path'] . 'vaultframe_' . strtolower(substr($roster->data['factionEn'],0,1)) . '.png);">'
		. $data;

	if( $tabs != '' )
	{
		print '
	<!-- Begin Navagation Tabs -->
	<div class="tab_navagation" style="margin:476px 0 0 17px;">
		<ul id="vault_navagation">
	' . $tabs . '
		</ul>
	</div>
	<script type="text/javascript">
		initializetabcontent(\'vault_navagation\');
	</script>';
	}
	print '</div>';
}

function itemidname( $item_id )
	{
		global $roster, $addon;
		
		$sql = "SELECT * FROM `" . $roster->db->table('items',$addon['basename']) . "`"	. " WHERE `item_id` = '$item_id'"	. " LIMIT 1";
		$result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		$row = $roster->db->fetch($result);
		return $row['item_name'];
	}


function vault_money()
{
	global $roster, $addon;

	$sqlstring = "SELECT * FROM `" . $roster->db->table('money',$addon['basename']) . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "';";

	$result = $roster->db->query($sqlstring);

	if( $roster->db->num_rows($result) > 0 )
	{
		$data = $roster->db->fetch($result, SQL_ASSOC);

		$g = ( !empty($data['money_g']) ? $data['money_g'] : 0 );
		$s = ( !empty($data['money_s']) ? $data['money_s'] : 0 );
		$c = ( !empty($data['money_c']) ? $data['money_c'] : 0 );

		return '<div class="vault_money"><span class="yellowB">' . $roster->locale->act['available_amount'] . ':</span> <span class="money">'
			. ( $g != 0 ? $g . ' <img src="' . $roster->config['img_url'] . 'coin_gold.gif" alt="g" /> ' : '' )
			. ( $s != 0 ? $s . ' <img src="' . $roster->config['img_url'] . 'coin_silver.gif" alt="s" /> ' : '' )
			. $c . ' <img src="' . $roster->config['img_url'] . 'coin_copper.gif" alt="c" /></span></div>';
	}
	else
	{
		return '';
	}
}

function vault_log( $parent )
{
	global $roster, $addon, $armory;

	$sqlstring = "SELECT * FROM `" . $roster->db->table('log',$addon['basename']) . "` WHERE `parent` = '" . $parent . "' AND `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `log_id` DESC;";

	$result = $roster->db->query($sqlstring);

	$return = '';
	while( $row = $roster->db->fetch($result,SQL_ASSOC) )
	{
		if( $row['amount'] > 0 )
		{
			$db_money = $row['amount'];

			$mail_money['c'] = $db_money % 100;
			$db_money = floor( $db_money / 100 );
			$money_item = $mail_money['c'] . '<img src="' . $roster->config['img_url'] . 'coin_copper.gif" alt="c" />';

			if( !empty($db_money) )
			{
				$mail_money['s'] = $db_money % 100;
				$db_money = floor( $db_money / 100 );
				$money_item = $mail_money['s'] . '<img src="' . $roster->config['img_url'] . 'coin_silver.gif" alt="s" /> ' . $money_item;
			}
			if( !empty($db_money) )
			{
				$mail_money['g'] = $db_money;
				$money_item = $mail_money['g'] . '<img src="' . $roster->config['img_url'] . 'coin_gold.gif" alt="g" /> ' . $money_item;
			}
		}
		elseif( $row['item_id'] != '' )
		{
			$money_item = itemidname($row['item_id']);
			//aprint($armory->fetchItemInfo($row['item_id'],$roster->config['locale']));
		}

		$return .= '<tr><td>' . sprintf($roster->locale->act['vault_log_' . $row['type']],$row['member'],$money_item) . ' <span class="blueB">(' . readbleDate($row['time']) . ')</span></td></tr>';
	}

	if( $return != '' )
	{
		$return = '<div id="' . $parent . 'Log" class="log" style="display:none;">
	<table style="width:100%">' . $return . '</table>
</div>';
	}

	return $return;
}
