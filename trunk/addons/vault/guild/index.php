<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Shows the guild vault
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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
//require_once (ROSTER_LIB . 'armory.class.php');

//$armory = new RosterArmory($roster->data['region']);

$roster->output['title'] = $roster->locale->act['vault'];


$roster->tpl->assign_vars(array(
	'S_MONEY' => false,
	'U_IMAGE_PATH' => $addon['tpl_image_path'],
	'U_FRAME_IMAGE' => strtolower(substr($roster->data['factionEn'],0,1)),

	'L_LOG' => $roster->locale->act['vault_log'],
	'L_MONEY_AVAIL' => $roster->locale->act['available_amount'],
	)
);


$tab_count = 6;

for( $t=1;$t<=$tab_count;$t++ )
{
	if( $roster->auth->getAuthorized($addon['config']['tab' . $t]) )
	{
		$tab_data['tab' . $t] = vault_tab_get($roster->data['guild_id'], 'Tab' . $t);
		if( !is_null( $tab_data['tab' . $t] ) )
		{
			$tab_data['tab' . $t]->out();

			vault_log('Tab' . $t);
		}
		
	}
}

if( $roster->auth->getAuthorized($addon['config']['money']) )
{
	vault_money();

	$roster->tpl->assign_block_vars('vault',array(
		'NAME'    => $roster->locale->act['vault_money_log'],
		'SLOT'    => 'MoneyLog',
		'LINK'    => makelink('#MoneyLog'),
		'ICON'    => $roster->config['interface_url'] . 'Interface/Icons/inv_misc_coin_01.' . $roster->config['img_suffix'],
		'TOOLTIP' => makeOverlib($roster->locale->act['vault_money_log'], '', '', 0,'',',RIGHT')
		)
	);

	vault_log('Money');
}

$roster->tpl->set_handle('body', $addon['basename'] . '/vault.html');
$roster->tpl->display('body');



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

					return '<span style="color:#' . $object->data['item_color'] . ';font-weight:bold;" ' . $tooltip . $linktip . '>[' . $object->data['item_name'] . ']</span>';
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

		$roster->tpl->assign_vars(array(
			'S_MONEY' => true,
			'MONEY_G' => ( !empty($data['money_g']) ? $data['money_g'] : 0 ),
			'MONEY_S' => ( !empty($data['money_s']) ? $data['money_s'] : 0 ),
			'MONEY_C' => ( !empty($data['money_c']) ? $data['money_c'] : 0 ),
			)
		);
	}
}

function vault_log( $parent )
{
	global $roster, $addon, $armory;

	$sqlstring = "SELECT * FROM `" . $roster->db->table('log',$addon['basename']) . "` "
		. "WHERE `parent` = '" . $parent . "' "
			. "AND `guild_id` = '" . $roster->data['guild_id'] . "' "
		. "ORDER BY `log_id` DESC;";

	$result = $roster->db->query($sqlstring);

	while( $row = $roster->db->fetch($result,SQL_ASSOC) )
	{
		if( $row['amount'] > 0 )
		{
			$db_money = $row['amount'];

			$money_item = '';

			$log_money['c'] = $db_money % 100;
			$db_money = floor( $db_money / 100 );
			if( $log_money['c'] > 0 )
			{
				$money_item = $log_money['c'] . '<img src="' . $roster->config['img_url'] . 'coin_copper.gif" alt="c" />';
			}

			if( !empty($db_money) )
			{
				$log_money['s'] = $db_money % 100;
				$db_money = floor( $db_money / 100 );
				if( $log_money['s'] > 0 )
				{
					$money_item = $log_money['s'] . '<img src="' . $roster->config['img_url'] . 'coin_silver.gif" alt="s" /> ' . $money_item;
				}
			}
			if( !empty($db_money) )
			{
				$log_money['g'] = $db_money;
				if( $log_money['g'] > 0 )
				{
					$money_item = $log_money['g'] . '<img src="' . $roster->config['img_url'] . 'coin_gold.gif" alt="g" /> ' . $money_item;
				}
			}
		}
		elseif( $row['item_id'] != '' )
		{
			$money_item = itemidname($row['item_id']);
			//aprint($armory->fetchItemInfo($row['item_id'],$roster->config['locale']));
		}

		$roster->tpl->assign_block_vars('vault.log',array(
			'TYPE' => sprintf($roster->locale->act['vault_log_' . $row['type']],$row['member'],$money_item),
			'TIME' => readbleDate($row['time'])
			)
		);
	}
}
