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
 * @version    SVN: $Id$
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


$tabs = $data = '';
$auth_req = 0;

$tab_count = 6;

for( $t=1;$t<=$tab_count;$t++ )
{
	if( $roster->auth->getAuthorized( $addon['config']['tab' . $t] ) )
	{
		$tab_data['tab' . $t] = vault_tab_get( $roster->data['guild_id'], 'Tab' . $t );
		if( !is_null( $tab_data['tab' . $t] ) )
		{
			$data .= '<div id="Tab' . $t . '" style="display:none;">
	<div class="vault_name_back">
		<div class="vault_name">' . $tab_data['tab' . $t]->data['item_name'] . '</div>
	</div>
	' . $tab_data['tab' . $t]->out() . '
	' . vault_log('Tab' . $t) . '
	<div class="tab_navagation">
		<ul id="vault_tab' . $t . '_navagation">
			<li class="selected"><a href="#" rel="Tab' . $t . 'Items" class="text">' . $tab_data['tab' . $t]->data['item_name'] . '</a></li>
			<li><a href="#" rel="Tab' . $t . 'Log" class="text">' . $roster->locale->act['vault_log'] . '</a></li>
		</ul>
		<script type="text/javascript">
			var vault_tab' . $t . '_navagation=new tabcontent(\'vault_tab' . $t . '_navagation\');
			vault_tab' . $t . '_navagation.init();
		</script>
	</div>
</div>';
			$tabs .= '			<li class="selected"><div style="background:url(' . $roster->config['interface_url'] . 'Interface/Icons/' . $tab_data['tab' . $t]->data['item_texture'] . '.' . $roster->config['img_suffix'] . ');"'. makeOverlib($tab_data['tab' . $t]->data['item_name'],'','',0,'',',RIGHT') .'><a rel="Tab' . $t . '" class="text"></a></div></li>' . "\n";
		}
	}
	else
	{
		$auth_req = 1;
	}
}

if( $roster->auth->getAuthorized( $addon['config']['money'] ) )
{
	$data .= vault_money();

	$money = vault_log('Money');
	if( $money != '' )
	{
		$data .= $money;
		$tabs .= '			<li><div style="background:url(' . $roster->config['interface_url'] . 'Interface/Icons/inv_misc_coin_01.' . $roster->config['img_suffix'] . ');"'. makeOverlib($roster->locale->act['vault_money_log'],'','',0,'',',RIGHT') .'><a rel="MoneyLog" class="text"></a></div></li>' . "\n";
	}
	else
	{
		$auth_req = 1;
	}
}

// ----[ Check log-in ]-------------------------------------
if ( $auth_req )
{
	print $roster->auth->getLoginForm();
}

if( $data != '' )
{
	print '<div class="vault" style="background-image:url(' . $addon['image_path'] . 'vaultframe_' . strtolower(substr($roster->data['factionEn'],0,1)) . '.png);">';

	if( $tabs != '' )
	{
		print '
	<!-- Begin Navagation Tabs -->
	<div class="tab_nav">
		<ul id="vault_navagation">
	' . $tabs . '
		</ul>
	</div>';
	}

	print $data . '</div>';
	if( $tabs != '' )
	{
		print '<script type="text/javascript">
		var vault_navagation=new tabcontent(\'vault_navagation\');
		vault_navagation.init();
	</script>';
	}
}


function itemidname( $item_id )
{
	global $roster, $tab_data, $tooltips;

	foreach( $tab_data as $tab )
	{
		if( is_object($tab) )
		{
			foreach( $tab->contents as $object )
			{
				$object_id = explode(':',$object->data['item_id']);
				$object_id = $object_id[0];
				$id = explode(':',$item_id);
				$id = $id[0];

				if( $object_id == $id )
				{
					$tooltip = makeOverlib($object->html_tooltip, '', '' , 2, '', ', WIDTH, 325');

					$num_of_tips = (count($tooltips)+1);
					$linktip = '';

					foreach( $roster->locale->wordings[$roster->config['locale']]['itemlinks'] as $key => $ilink )
					{
						$linktip .= '<a href="' . $ilink . $item_id . '" target="_blank">' . $key . '</a><br />';
					}
					setTooltip($num_of_tips, $linktip);
					setTooltip('itemlink', $roster->locale->wordings[$roster->config['locale']]['itemlink']);

					$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

					return '<span style="color:#' . $object->data['item_color'] . ';font-weight:bold;"' . $tooltip . $linktip . '>[' . $object->data['item_name'] . ']</span>';
				}
			}
		}
	}

	return $item_id;
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
