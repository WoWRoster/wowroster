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

$tabs = '';

print '<div class="vault" style="background-image:url(' . $addon['image_path'] . 'vaultframe_' . strtolower(substr($roster->data['factionEn'],0,1)) . '.png);">';

$tab1 = vault_tab_get( $roster->data['guild_id'], 'Tab1' );
if( !is_null( $tab1 ) )
{
	print $tab1->out();
	$tabs .= '			<li class="selected"><a rel="Tab1" class="text">' . $tab1->data['item_name'] . "</a></li>\n";
	$tabs .= '			<li class="selected"><a rel="Tab1Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
	print '<div id="Tab1Log" class="log" style="display:none;">' . vault_log('Tab1') . '</div>';
}

$tab2 = vault_tab_get( $roster->data['guild_id'], 'Tab2' );
if( !is_null( $tab2 ) )
{
	print $tab2->out();
	$tabs .= '			<li><a rel="Tab2" class="text">' . $tab2->data['item_name'] . "</a></li>\n";
	$tabs .= '			<li class="selected"><a rel="Tab2Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
	print '<div id="Tab2Log" class="log" style="display:none;">' . vault_log('Tab2') . '</div>';
}

$tab3 = vault_tab_get( $roster->data['guild_id'], 'Tab3' );
if( !is_null( $tab3 ) )
{
	print $tab3->out();
	$tabs .= '			<li><a rel="Tab3" class="text">' . $tab3->data['item_name'] . "</a></li>\n";
	$tabs .= '			<li class="selected"><a rel="Tab3Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
	print '<div id="Tab3Log" class="log" style="display:none;">' . vault_log('Tab3') . '</div>';
}

$tab4 = vault_tab_get( $roster->data['guild_id'], 'Tab4' );
if( !is_null( $tab4 ) )
{
	print $tab4->out();
	$tabs .= '			<li><a rel="Tab4" class="text">' . $tab4->data['item_name'] . "</a></li>\n";
	$tabs .= '			<li class="selected"><a rel="Tab4Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
	print '<div id="Tab4Log" class="log" style="display:none;">' . vault_log('Tab4') . '</div>';
}

$tab5 = vault_tab_get( $roster->data['guild_id'], 'Tab5' );
if( !is_null( $tab5 ) )
{
	print $tab5->out();
	$tabs .= '			<li><a rel="Tab5" class="text">' . $tab5->data['item_name'] . "</a></li>\n";
	$tabs .= '			<li class="selected"><a rel="Tab5Log" class="text">' . $roster->locale->act['vault_log'] . "</a></li>\n";
	print '<div id="Tab5Log" class="log" style="display:none;">' . vault_log('Tab5') . '</div>';
}


print '<div id="MoneyLog" class="log" style="display:none;">' . vault_log('Money') . '</div>';


print vault_money();

if( $tabs != '' )
{
	print '
<!-- Begin Navagation Tabs -->
<div class="tab_navagation" style="margin:476px 0 0 17px;">
	<ul id="vault_navagation">
' . $tabs . '
		<li><a rel="MoneyLog" class="text">' . $roster->locale->act['vault_money_log'] . '</a></li>
	</ul>
</div>
<script type="text/javascript">
	initializetabcontent(\'vault_navagation\');
</script>';
}

print '</div>';


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
			$money_item = $row['item_id'];
		}

		$return .= '<tr><td>' . sprintf($roster->locale->act['vault_log_' . $row['type']],$row['member'],$money_item) . ' <span class="blueB">(' . readbleDate($row['time']) . ')</span></td></tr>';
	}

	if( $return != '' )
	{
		$return = '<table style="width:100%">' . $return . '</table>';
	}

	return $return;
}
