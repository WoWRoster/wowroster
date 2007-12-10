<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage CharacterLib
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once (ROSTER_LIB . 'item.php');
require_once ($addon['inc_dir'] . 'bag.php');
require_once (ROSTER_LIB . 'skill.php');
require_once (ROSTER_LIB . 'quest.php');
require_once (ROSTER_LIB . 'recipes.php');

/**
 * Character Information Class
 * @package    CharacterInfo
 * @subpackage CharacterLib
 *
 */
class char
{
	var $data;
	var $equip;
	var $talent_build;
	var $locale;
	var $alt_hover;

	/**
	 * Constructor
	 *
	 * @param array $data
	 * @return char
	 */
	function char( $data )
	{
		global $roster;

		$this->data = $data;
		$this->locale = $roster->locale->wordings[$this->data['clientLocale']];
	}


	/**
	 * Gets a value from the character data
	 *
	 * @param string $field
	 * @return mixed
	 */
	function get( $field )
	{
		return $this->data[$field];
	}


	/**
	 * Gathers all of the characters equiped items into an array
	 * Array $this->equip
	 *
	 */
	function fetchEquip( )
	{
		if( !is_array($this->equip) )
		{
			$this->equip = item::fetchManyItems($this->data['member_id'], 'equip', 'full');
		}
	}


	function show_buffs( )
	{
		global $roster;

		// Get char professions for quick links
		$query = "SELECT * FROM `" . $roster->db->table('buffs') . "` WHERE `member_id` = '" . $this->data['member_id'] . "';";
		$result = $roster->db->query($query);

		$return_string = '';
		if( $roster->db->num_rows($result) > 0 )
		{
			$return_string .= "<div class=\"buff_icons\">\n\t\t\t\n";
			while( $row = $roster->db->fetch($result) )
			{
				$tooltip = makeOverlib($row['tooltip'],'','ffdd00',1,'',',RIGHT');
				$return_string .= '				<div class="buff"><img class="icon" src="' . $roster->config['interface_url'] . 'Interface/Icons/' . $row['icon'] . '.' . $roster->config['img_suffix'] . '" ' . $tooltip . ' alt="" />';
				if( ($row['count'] > 1) )
				{
					$return_string .= '<b>' . $row['count'] . '</b>';
					$return_string .= '<span>' . $row['count'] . '</span>';
				}
				$return_string .= "</div>\n";
			}
			$return_string .= "			\n		</div>\n";
		}
		return $return_string;
	}


	/**
	 * Build quests
	 *
	 * @return string
	 */
	function show_quests( )
	{
		global $roster;

		$quests = quest_get_many($this->data['member_id'],'');

		$returnstring = '';
		if( isset($quests[0]) )
		{
			$zone = '';
			$returnstring = border('sgray','start',$this->locale['questlog'] . ' (' . count($quests) . '/' . ROSTER_MAXQUESTS . ')').
				'<table class="bodyline" cellspacing="0" cellpadding="0">';

			foreach( $quests as $quest )
			{
				if( $zone != $quest->data['zone'] )
				{
					$zone = $quest->data['zone'];
					$returnstring .= '<tr><th colspan="10" class="membersHeaderRight">' . $zone . '</th></tr>';
				}
				$quest_level = $quest->data['quest_level'];
				$char_level = $this->data['level'];

				if( $quest_level + 9 < $char_level )
				{
					$font = 'grey';
				}
				elseif( $quest_level + 2 < $char_level )
				{
					$font = 'green';
				}
				elseif( $quest_level < $char_level+3 )
				{
					$font = 'yellow';
				}
				else
				{
					$font = 'red';
				}

				$name = $quest->data['quest_name'];
				if( $name{0} == '[' )
				{
					$name = trim(strstr($name, ' '));
				}

				$returnstring .= '<tr><td class="membersRow1">';

				$returnstring .= '<span class="' . $font . '">[' . $quest_level . '] ' . $name . '</span>';

				$quest_tags = '';

				if( $quest->data['quest_tag'] )
				{
					$quest_tags[] = $quest->data['quest_tag'];
				}

				if( $quest->data['is_complete'] == 1 )
				{
					$quest_tags[] = $this->locale['complete'];
				}
				elseif( $quest->data['is_complete'] == -1 )
				{
					$quest_tags[] = $this->locale['failed'];
				}

				if( is_array($quest_tags) )
				{
						$returnstring .= ' (' . implode(', ',$quest_tags) . ')';
				}

				$returnstring .= "</td>\n";

				$returnstring .= '<td class="membersRowRight1 quest_link">';

				foreach( $this->locale['questlinks'] as $link )
				{
					$returnstring .= '<a href="' . $link['url1'] . urlencode(utf8_decode($name)) . (isset($link['url2']) ? $link['url2'] . $quest_level : '') . (isset($link['url3']) ? $link['url3'] . $quest_level : '') . '" target="_blank">' . $link['name'] . "</a>\n";
				}

				$returnstring .= '</td></tr>';
			}
			$returnstring .= '</table>' . border('sgray','end');
		}
		return $returnstring;
	}


	/**
	 * Build Recipes
	 *
	 * @return string
	 */
	function show_recipes( )
	{
		global $roster, $sort, $addon;

		$returnstring = '';

		$recipes = recipe_get_many( $this->data['member_id'],'', $sort );
		if( isset( $recipes[0] ) )
		{
			$skill_name = '';
			$returnstring = '';

			// Get char professions for quick links
			$query = "SELECT `skill_name` FROM `" . $roster->db->table('recipes') . "` WHERE `member_id` = '" . $this->data['member_id'] . "' GROUP BY `skill_name` ORDER BY `skill_name`";
			$result = $roster->db->query( $query );

			// Set a ank for link to top of page
			$returnstring .= "<a name=\"top\">&nbsp;</a>\n";
			$returnstring .= '<div align="center">';
			$skill_name_divider = '';
			while( $data = $roster->db->fetch( $result ) )
			{
				$skill_name_header = $data['skill_name'];
				$returnstring .= $skill_name_divider .'<a href="' . makelink('#' . strtolower(str_replace(' ','',$skill_name_header))) . '">' . $skill_name_header . '</a>';
				$skill_name_divider = '&nbsp;-&nbsp;';
			}
			$returnstring .= "</div>\n<br />\n";

			$rc = 0;
			$first_run = 1;

			foreach ($recipes as $recipe)
			{
				if ($skill_name != $recipe->data['skill_name'])
				{
					$skill_name = $recipe->data['skill_name'];
					if ( !$first_run )
						$returnstring .= '</table>' . border('sgray','end') . "<br />\n";
					$first_run = 0;

					// Set an link to the top behind the profession image
					$skill_image = 'Interface/Icons/' . $roster->locale->wordings[$this->data['clientLocale']]['ts_iconArray'][$skill_name];
					$skill_image = "<img style=\"float:left;\" width=\"17\" height=\"17\" src=\"" . $roster->config['interface_url'] . $skill_image . '.' . $roster->config['img_suffix'] . "\" alt=\"\" />\n";

					$header = '<div style="cursor:pointer;width:600px;" onclick="showHide(\'table_' . $rc . '\',\'img_' . $rc . '\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
	' . $skill_image . '
	<div style="display:inline;float:right;"><img id="img_' . $rc . '" src="' . $roster->config['img_url'] . 'plus.gif" alt="" /></div>
<a name="' . strtolower(str_replace(' ','',$skill_name)) . '"></a>' . $skill_name . '</div>';


					$returnstring .= border('sgray','start',$header) . "\n<table width=\"100%\" " . ($addon['config']['recipe_disp'] == '0' ? 'style="display:none;"' : '') . ";\" class=\"bodyline\" cellspacing=\"0\" id=\"table_$rc\">\n";

$returnstring .= '  <tr>
    <th class="membersHeader"><a href="' . makelink('char-info-recipes&amp;s=item') . '">' . $this->locale['item'] . '</a></th>
    <th class="membersHeader"><a href="' . makelink('char-info-recipes&amp;s=name') . '">' . $this->locale['name'] . '</a></th>
    <th class="membersHeader"><a href="' . makelink('char-info-recipes&amp;s=difficulty') . '">' . $this->locale['difficulty'] . '</a></th>
    <th class="membersHeader"><a href="' . makelink('char-info-recipes&amp;s=type') . '">' . $this->locale['type'] . '</a></th>
    <th class="membersHeader"><a href="' . makelink('char-info-recipes&amp;s=level') . '">' . $this->locale['level'] . '</a></th>
    <th class="membersHeaderRight"><a href="' . makelink('char-info-recipes&amp;s=reagents') . '">' . $this->locale['reagents'] . '</a></th>
  </tr>
';
				}

				if( $recipe->data['difficulty'] == '4' )
					$difficultycolor = 'FF9900';
				elseif( $recipe->data['difficulty'] == '3' )
					$difficultycolor = 'FFFF66';
				elseif( $recipe->data['difficulty'] == '2' )
					$difficultycolor = '339900';
				elseif( $recipe->data['difficulty'] == '1' )
					$difficultycolor = 'CCCCCC';
				else
					$difficultycolor = 'FFFF80';

				// Dont' set an CSS class for the image cell - center it
				$stripe = (($rc%2)+1);
				$returnstring .= '  <tr>
    <td class="membersRow' . $stripe . ' equip">';

				$returnstring .= $recipe->out();
				$returnstring .= '</td>
    <td class="membersRow' . $stripe . '"><span style="color:#' . substr( $recipe->data['item_color'], 2, 6 ) . '">&nbsp;' . $recipe->data['recipe_name'] . '</span></td>
    <td class="membersRow' . $stripe . '"><span style="color:#' . $difficultycolor . '">&nbsp;' . $this->locale['recipe_' . $recipe->data['difficulty']] . '</span></td>
    <td class="membersRow' . $stripe . '">&nbsp;' . $recipe->data['recipe_type'] . '&nbsp;</td>
    <td class="membersRow' . $stripe . '">&nbsp;' . $recipe->data['level'] . '&nbsp;</td>
    <td class="membersRowRight' . $stripe . '">&nbsp;' . str_replace('<br>','&nbsp;<br />&nbsp;',$recipe->data['reagents']) . '</td>
  </tr>
';
			$rc++;
			}
			$returnstring .= "</table>" . border('sgray','end');
		}
		return $returnstring;
	}


	/**
	 * Build Mail
	 *
	 * @return string
	 */
	function show_mailbox( )
	{
		global $roster, $tooltips, $addon;

		$sqlquery = "SELECT * FROM `" . $roster->db->table('mailbox') . "` "
				  . "WHERE `member_id` = '" . $this->data['member_id'] . "' "
				  . "ORDER BY `mailbox_days`;";

		$result = $roster->db->query($sqlquery);

		if( !$result )
		{
			return '<span class="headline_1">' . sprintf($this->locale['no_mail'],$this->data['name']) . '</span>';
		}

		$content = '';
		$boxes = '';

		if( $roster->db->num_rows($result) > 0 )
		{
			//begin generation of mailbox's output
			$content .= border('sgray','start',$this->locale['mailbox'])
					  . '<table cellpadding="0" cellspacing="0" class="bodyline">' . "\n";
			$content .= "<tr>\n";
			$content .= '<th class="membersHeader">' . $this->locale['mail_item'] . "</th>\n";
			$content .= '<th class="membersHeader">' . $this->locale['mail_sender'] . "</th>\n";
			$content .= '<th class="membersHeader">' . $this->locale['mail_subject'] . "</th>\n";
			$content .= '<th class="membersHeaderRight">' . $this->locale['mail_expires'] . "</th>\n";
			$content .= "</tr>\n";

			$cur_row = 1;
			while( $row = $roster->db->fetch($result) )
			{
				$maildateutc = strtotime($this->data['maildateutc']);

				$content .= "<tr>\n";
				$content .= '<td class="membersRow' . $cur_row . '">' . "\n";

				// Get money in mail
				$money_included = '';
				if( $row['mailbox_coin'] > 0 && $addon['config']['show_money'] )
				{
					$db_money = $row['mailbox_coin'];

					$mail_money['c'] = $db_money % 100;
					$db_money = floor( $db_money / 100 );
					$money_included = $mail_money['c'] . '<img src="' . $roster->config['img_url'] . 'coin_copper.gif" alt="c" />';

					if( !empty($db_money) )
					{
						$mail_money['s'] = $db_money % 100;
						$db_money = floor( $db_money / 100 );
						$money_included = $mail_money['s'] . '<img src="' . $roster->config['img_url'] . 'coin_silver.gif" alt="s" /> ' . $money_included;
					}
					if( !empty($db_money) )
					{
						$mail_money['g'] = $db_money;
						$money_included = $mail_money['g'] . '<img src="' . $roster->config['img_url'] . 'coin_gold.gif" alt="g" /> ' . $money_included;
					}
				}

				// Fix icon texture
					$item_icon = $roster->config['interface_url'] . 'Interface/Icons/' . $row['mailbox_icon'] . '.' . $roster->config['img_suffix'];

				// Start the tooltips
				$tooltip_h = $row['mailbox_subject'];

				// first line is sender
				$tooltip = $roster->locale->wordings[$this->data['clientLocale']]['mail_sender'] . ': ' . $row['mailbox_sender'] . '<br />';

				$expires_line = date($roster->locale->wordings[$this->data['clientLocale']]['phptimeformat'],($row['mailbox_days']*24*3600)+$maildateutc) . ' ' . $roster->config['timezone'];

				if( (($row['mailbox_days']*24*3600)+$maildateutc) - time() < (3*24*3600) )
				{
					$color = 'ff0000';
				}
				else
				{
					$color = 'ffffff';
				}

				$tooltip .= $roster->locale->wordings[$this->data['clientLocale']]['mail_expires'] . ": <span style=\"color:#$color;\">$expires_line</span><br />";

				// Join money with main tooltip
				if( !empty($money_included) )
				{
					$tooltip .= $roster->locale->wordings[$this->data['clientLocale']]['mail_money'] . ': ' . $money_included;
				}


				$tooltipcode = makeOverlib($tooltip,$tooltip_h,'',2,$this->data['clientLocale']);

				$content .= '<div ' . $tooltipcode . '>';

				$content .= '<img src="' . $item_icon . '"' . " alt=\"\" />\n";

				$content .= "</div>\n</td>\n";

				$content .= '<td class="membersRow' . $cur_row . '">' . $row['mailbox_sender'] . "</td>\n";
				$content .= '<td class="membersRow' . $cur_row . '">' . $row['mailbox_subject'] . "</td>\n";
				$content .= '<td class="membersRowRight' . $cur_row . '">' . $expires_line . "</td>\n";

				$content .= "</tr>\n";

				$cur_row = (($cur_row%2)+1);

				if( $addon['config']['mail_disp'] > 0 )
				{
					// Set up box display
					$row['item_slot'] = 'Mail ' . $row['mailbox_slot'];
					$row['item_id'] = '0:0:0:0:0';
					$row['item_name'] = $row['mailbox_subject'];
					$row['item_level'] = 0;
					$row['item_texture'] = $row['mailbox_icon'];
					$row['item_parent'] = 'Mail';
					$row['item_tooltip'] = $tooltip;
					$row['item_color'] = '';
					$row['item_quantity'] = 0;
					$row['locale'] = $this->data['clientLocale'];

					$attach = new bag($row);
					$boxes .= $attach->out();
				}
			}

			$content .= "</table>\n" . border('sgray','end');

			return $boxes . ( $addon['config']['mail_disp'] == '0' || $addon['config']['mail_disp'] == '2' ? '<div style="clear: left;">' . $content . '</div>' : '' );
		}
		else
		{
			return '<span class="headline_1">' . sprintf($this->locale['no_mail'],$this->data['name']) . '</span>';
		}
	}


	/**
	 * Build Spellbook
	 *
	 * @return string
	 */
	function show_spellbook( )
	{
		global $roster, $addon;

		$query = "SELECT `spelltree`.*, `talenttree`.`order`
			FROM `" . $roster->db->table('spellbooktree') . "` AS spelltree
			LEFT JOIN `" . $roster->db->table('talenttree') . "` AS talenttree
				ON `spelltree`.`member_id` = `talenttree`.`member_id`
				AND `spelltree`.`spell_type` = `talenttree`.`tree`
			WHERE `spelltree`.`member_id` = " . $this->data['member_id'] . "
			ORDER BY `talenttree`.`order` ASC;";

		$result = $roster->db->query($query);

		if( !$result )
		{
			return sprintf($this->locale['no_spellbook'],$this->data['name']);
		}

		$num_trees = $roster->db->num_rows($result);

		if( $num_trees == 0 )
		{
			return sprintf($this->locale['no_spellbook'],$this->data['name']);
		}

		for( $t=0; $t < $num_trees; $t++)
		{
			$treedata = $roster->db->fetch($result);

			$spelltree[$t]['name'] = $treedata['spell_type'];
			$spelltree[$t]['icon'] = 'Interface/Icons/' . $treedata['spell_texture'];
			$spelltree[$t]['id'] = $t;

			$name_id[$treedata['spell_type']] = $t;
		}

		$roster->db->free_result($result);

		// Get the spell data
		$query = "SELECT * FROM `" . $roster->db->table('spellbook') . "` WHERE `member_id` = '" . $this->data['member_id'] . "' ORDER BY `spell_name`;";

		$result = $roster->db->query($query);

		while ($row = $roster->db->fetch($result))
		{
			$spelltree[$name_id[$row['spell_type']]]['rawspells'][] = $row;
		}

		foreach ($spelltree as $t => $tree)
		{
			$i=0;
			$p=0;
			foreach ($spelltree[$t]['rawspells'] as $spell)
			{
				if( $i >= 14 )
				{
					$i=0;
					$p++;
				}
				$spelltree[$t]['spells'][$p][$i]['name'] = $spell['spell_name'];
				$spelltree[$t]['spells'][$p][$i]['type'] = $spell['spell_type'];
				$spelltree[$t]['spells'][$p][$i]['icon'] = 'Interface/Icons/' . $spell['spell_texture'];
				$spelltree[$t]['spells'][$p][$i]['rank'] = $spell['spell_rank'];

				// Parse the tooltip
				$spelltree[$t]['spells'][$p][$i]['tooltip'] = makeOverlib($spell['spell_tooltip'],'','',0,$this->data['clientLocale'],',RIGHT');

				$i++;
			}
		}
		$roster->db->free_result($result);


		// Get the PET spell data
		$query = "SELECT `spell`.*, `pet`.`name`
			FROM `" . $roster->db->table('spellbook_pet') . "` as spell
			LEFT JOIN `" . $roster->db->table('pets') . "` AS pet
			ON `spell`.`pet_id` = `pet`.`pet_id`
			WHERE `spell`.`member_id` = '" . $this->data['member_id'] . "' ORDER BY `spell`.`spell_name`;";

		$result = $roster->db->query($query);

		$petspells = array();
		while( $row = $roster->db->fetch($result) )
		{
			$petid = $row['pet_id'];
			$petspells[$petid]['name'] = $row['name'];
			$petspells[$petid][$i]['name'] = $row['spell_name'];
			$petspells[$petid][$i]['icon'] = 'Interface/Icons/' . $row['spell_texture'];
			$petspells[$petid][$i]['rank'] = $row['spell_rank'];

			// Parse the tooltip
			$petspells[$petid][$i]['tooltip'] = makeOverlib($row['spell_tooltip'],'','',0,$this->data['clientLocale'],',RIGHT');
			$i++;
		}
		$roster->db->free_result($result);



		$return_string = '
<div class="char_panel spell_panel">
	<img class="panel_icon" src="' . $addon['image_path'] . 'icon_spellbook.gif" alt=""/>
	<div class="panel_title">' . $this->locale['spellbook'] . '</div>
	<div class="background">&nbsp;</div>

	<div id="main_spells">
		<div class="skill_types">
			<ul>
';

		foreach( $spelltree as $tree )
		{
			$treetip = makeOverlib($tree['name'],'','',2,'',',WRAP,RIGHT');
			$return_string .= '				<li onclick="return showSpell(\'' . $tree['id'] . '\');"><img class="icon" src="' . $roster->config['interface_url'] . $tree['icon'] . '.' . $roster->config['img_suffix'] . '" ' . $treetip . ' alt="" /></li>' . "\n";
		}
		$return_string .= "			</ul>\n		</div>\n";


		foreach( $spelltree as $tree )
		{
			if( $tree['id'] == 0 )
			{
				$return_string .= '		<div id="spelltree_' . $tree['id'] . '">' . "\n";
			}
			else
			{
				$return_string .= '		<div id="spelltree_' . $tree['id'] . '" style="display:none;">' . "\n";
			}

			$num_pages = count($tree['spells']);
			$first_page = true;
			$page = 0;
			foreach( $tree['spells'] as $spellpage )
			{
				if( $first_page )
				{
					if( ($num_pages-1) == $page )
					{
						$return_string .= '			<div id="page_' . $page . '_' . $tree['id'] . '">' . "\n";
						$return_string .= '				<div class="page_back_off"><img src="' . $addon['image_path'] . 'spellbook/pageback_off.gif" class="navicon" alt="" /> ' . $this->locale['prev'] . "</div>\n";
						$return_string .= '				<div class="page_forward_off">' . $this->locale['next'] . ' <img src="' . $addon['image_path'] . 'spellbook/pageforward_off.gif" class="navicon" alt="" /></div>' . "\n";
						$first_page = false;
					}
					else
					{
						$return_string .= '			<div id="page_' . $page . '_' . $tree['id'] . '">' . "\n";
						$return_string .= '				<div class="page_back_off"><img src="' . $addon['image_path'] . 'spellbook/pageback_off.gif" class="navicon" alt="" /> ' . $this->locale['prev'] . "</div>\n";
						$return_string .= '				<div class="page_forward" onclick="swapShow(\'page_' . ($page+1) . '_' . $tree['id'] . '\',\'page_' . $page . '_' . $tree['id'] . '\');">' . $this->locale['next'] . ' <img src="' . $addon['image_path'] . 'spellbook/pageforward.gif" class="navicon" alt="" /></div>' . "\n";
						$first_page = false;
					}
				}
				elseif( ($num_pages-1) == $page )
				{
					$return_string .= '			<div id="page_' . $page . '_' . $tree['id'] . '" style="display:none;">' . "\n";
					$return_string .= '				<div class="page_back" onclick="swapShow(\'page_' . ($page-1) . '_' . $tree['id'] . '\',\'page_' . $page . '_' . $tree['id'] . '\');"><img src="' . $addon['image_path'] . 'spellbook/pageback.gif" class="navicon" alt="" /> ' . $this->locale['prev'] . "</div>\n";
					$return_string .= '				<div class="page_forward_off">' . $this->locale['next'] . ' <img src="' . $addon['image_path'] . 'spellbook/pageforward_off.gif" class="navicon" alt="" /></div>' . "\n";
				}
				else
				{
					$return_string .= '			<div id="page_' . $page . '_' . $tree['id'] . '" style="display:none;">' . "\n";
					$return_string .= '				<div class="page_back" onclick="swapShow(\'page_' . ($page-1) . '_' . $tree['id'] . '\',\'page_' . $page . '_' . $tree['id'] . '\');"><img src="' . $addon['image_path'] . 'spellbook/pageback.gif" class="navicon" alt="" /> ' . $this->locale['prev'] . "</div>\n";
					$return_string .= '				<div class="page_forward" onclick="swapShow(\'page_' . ($page+1) . '_' . $tree['id'] . '\',\'page_' . $page . '_' . $tree['id'] . '\');">' . $this->locale['next'] . ' <img src="' . $addon['image_path'] . 'spellbook/pageforward.gif" class="navicon" alt="" /></div>' . "\n";
				}
				$return_string .= '				<div class="pagenumber">' . $this->locale['page'] . ' ' . ($page+1) . "</div>\n";


				$icon_num = 0;
				foreach( $spellpage as $spellicons )
				{
					if( $icon_num == 0 )
					{
						$return_string .= '				<div class="container_1">' . "\n";
					}
					elseif( $icon_num == 7 )
					{
						$return_string .= "				</div>\n				<div class=\"container_2\">\n";
					}
					$return_string .= '
				<div class="info_container">
					<img src="' . $roster->config['interface_url'] . $spellicons['icon'] . '.' . $roster->config['img_suffix'] . '" class="icon" ' . $spellicons['tooltip'] . ' alt="" />
					<span class="text"><span class="yellowB">' . $spellicons['name'] . '</span>';
					if( $spellicons['rank'] != '' )
					{
						$return_string .= '<br /><span class="brownB">' . $spellicons['rank'] . '</span>';
					}
					$return_string .= "</span>\n					</div>\n";
					$icon_num++;
				}
				$return_string .= "				</div>\n			</div>\n";

				$page++;
			}
			$return_string .= "		</div>\n";
		}
		$return_string .= "	</div>\n";

		// PET SPELLS
		$pet_tabs = '';
		foreach( $petspells as $petid => $pet )
		{
			$pet_tabs .= '			<li><a rel="petspell_' . $petid . '" class="text">' . $pet['name'] . "</a></li>\n";

			$return_string .= '		<div id="petspell_' . $petid . '" style="display:none;">' . "\n";

			$icon_num = 0;
			foreach( $pet as $arrayname => $spellicons )
			{
				if( $arrayname != 'name' )
				{
					if( $icon_num == 0 )
					{
						$return_string .= '			<div class="container_1">' . "\n";
					}
					elseif( $icon_num == 7 )
					{
						$return_string .= "			</div>\n			<div class=\"container_2\">\n";
					}
					$return_string .= '
			<div class="info_container">
				<img src="' . $roster->config['interface_url'] . $spellicons['icon'] . '.' . $roster->config['img_suffix'] . '" class="icon" ' . $spellicons['tooltip'] . ' alt="" />
				<span class="text"><span class="yellowB">' . $spellicons['name'] . '</span>';
					if( $spellicons['rank'] != '' )
					{
						$return_string .= '<br /><span class="brownB">' . $spellicons['rank'] . '</span>';
					}
					$return_string .= "</span>\n				</div>\n";
					$icon_num++;
				}
			}
			$return_string .= "			</div>\n		</div>\n";
		}


		$return_string .= '
<!-- Begin Navagation Tabs -->
	<div class="tab_navagation" style="margin:428px 0 0 17px;">
		<ul id="spell_set">
			<li class="selected"><a rel="main_spells" class="text">' . $this->data['name'] . '</a></li>
' . $pet_tabs . '
		</ul>
	</div>
</div>

<script type="text/javascript">
	initializetabcontent(\'spell_set\');
</script>' . "\n";

		return $return_string;
	}


	/**
	 * Build Pet
	 *
	 * @return string
	 */
	function printPet( )
	{
		global $roster, $addon;

		$query = "SELECT * FROM `" . $roster->db->table('pets') . "` WHERE `member_id` = '" . $this->data['member_id'] . "' ORDER BY `level` DESC;";
		$result = $roster->db->query( $query );

		$output = $icons = '';

		$petNum = 0;
		if( $roster->db->num_rows($result) > 0 )
		{
			while ($row = $roster->db->fetch($result))
			{
				$xpbarshow = true;

				if( $row['level'] == ROSTER_MAXCHARLEVEL )
				{
					$expbar_width = '216';
					$expbar_text = $this->locale['max_exp'];
				}
				else
				{
					list($xp, $xplevel) = explode(':',$row['xp']);
					if ($xplevel != '0' && $xplevel != '')
					{
						$expbar_width = ( $xplevel > 0 ? floor($xp / $xplevel * 216) : 0);

						$exp_percent = ( $xplevel > 0 ? floor($xp / $xplevel * 100) : 0);

						$expbar_text = $xp . '/' . $xplevel . ' (' . $exp_percent . '%)';
					}
					else
					{
						$xpbarshow = false;
					}
				}

				$unusedtp = $row['totaltp'] - $row['usedtp'];

				// Start Warlock Pet Icon Fix
				if( $row['type'] == $this->locale['Imp'] )
				{
					$row['icon'] = 'spell_shadow_summonimp';
				}
				elseif( $row['type'] == $this->locale['Voidwalker'] )
				{
					$row['icon'] = 'spell_shadow_summonvoidwalker';
				}
				elseif( $row['type'] == $this->locale['Succubus'] )
				{
					$row['icon'] = 'spell_shadow_summonsuccubus';
				}
				elseif( $row['type'] == $this->locale['Felhunter'] )
				{
					$row['icon'] = 'spell_shadow_summonfelhunter';
				}
				elseif( $row['type'] == $this->locale['Felguard'] )
				{
					$row['icon'] = 'spell_shadow_summonfelguard';
				}
				elseif( $row['type'] == $this->locale['Infernal'] )
				{
					$row['icon'] = 'spell_shadow_summoninfernal';
				}
				// End Warlock Pet Icon Fix

				if( $row['icon'] == '' || !isset($row['icon']) )
				{
					$row['icon'] = 'inv_misc_questionmark';
				}

				$icons .= '			<li ' . makeOverlib($row['name'],$row['type'],'',2,'',',WRAP') . ' style="background-image:url(\'' . $roster->config['interface_url'] . 'Interface/Icons/' . $row['icon'] . '.' . $roster->config['img_suffix'] . '\');">
				<a rel="pet_' . $petNum . '" class="text"></a></li>
';

				$output .= '
		<div id="pet_' . $petNum . '"' . ($petNum == 0 ? '' : ' style="display:none;"') . '>
			<div class="name">' . stripslashes($row['name']) . '</div>
			<div class="info">' . $this->locale['level'] . ' ' . $row['level'] . ' ' . stripslashes($row['type']) . '</div>

			<div class="loyalty">' . $row['loyalty'] . '</div>

			<img class="icon" src="' . $roster->config['interface_url'] . 'Interface/Icons/' . $row['icon'] . '.' . $roster->config['img_suffix'] . '" alt="" />

			<div class="health"><span class="yellowB">' . $this->locale['health'] . ':</span> ' . (isset($row['health']) ? $row['health'] : '0') . '</div>
			<div class="mana"><span class="yellowB">' . $row['power'] . ':</span> ' . (isset($row['mana']) ? $row['mana'] : '0') . '</div>

			<div class="resist">
				' . $this->printPetResist('arcane',$row) . '
				' . $this->printPetResist('fire',$row) . '
				' . $this->printPetResist('nature',$row) . '
				' . $this->printPetResist('frost',$row) . '
				' . $this->printPetResist('shadow',$row) . '
			</div>
';
				if( $xpbarshow )
				{
					$output .= '
			<img src="' . $addon['image_path'] . 'expbar_empty.gif" class="xpbar_empty" alt="" />
			<div class="xpbar" style="clip:rect(0px ' . $expbar_width . 'px 12px 0px);"><img src="' . $addon['image_path'] . 'expbar_full.gif" alt="" /></div>
			<div class="xpbar_text">' . $expbar_text . '</div>';
				}

				$output .= '
			<div class="padding">
				<div class="stats">
					' . $this->printPetStat('stat_str',$row) . '
					' . $this->printPetStat('stat_agl',$row) . '
					' . $this->printPetStat('stat_sta',$row) . '
					' . $this->printPetStat('stat_int',$row) . '
					' . $this->printPetStat('stat_spr',$row) . '
					' . $this->printPetStat('stat_armor',$row) . '
				</div>
				<div class="stats">
					' . $this->printPetWSkill($row) . '
					' . $this->printPetWDamage($row) . '
					' . $this->printPetStat('melee_power',$row) . '
					' . $this->printPetStat('melee_hit',$row) . '
					' . $this->printPetStat('melee_crit',$row) . '
					' . $this->printPetResilience($row) . '
				</div>
			</div>
';
				if( $row['totaltp'] != 0 )
				{
					$output .= '
			<div class="trainingpts">' . $this->locale['unusedtrainingpoints'] . ': ' . $unusedtp . ' / ' . $row['totaltp'] . '</div>';
				}
				$output .= '
		</div>
';

				$petNum++;
			}
			$output .= '
<!-- Begin Navagation Tabs -->
	<div class="pet_tabs">
		<ul id="pet_tabs">
' . $icons . '
		</ul>
	</div>
<script type="text/javascript">
	initializetabcontent(\'pet_tabs\');
</script>
';
		}

		return $output;
	}



	/**
	 * Build Pet stats
	 *
	 * @param string $statname
	 * @param array $data
	 * @return string
	 */
	function printPetStat( $statname , $data )
	{
		global $roster;

		switch( $statname )
		{
			case 'stat_str':
				$name = $this->locale['strength'];
				$tooltip = $this->locale['strength_tooltip'];
				break;
			case 'stat_int':
				$name = $this->locale['intellect'];
				$tooltip = $this->locale['intellect_tooltip'];
				break;
			case 'stat_sta':
				$name = $this->locale['stamina'];
				$tooltip = $this->locale['stamina_tooltip'];
				break;
			case 'stat_spr':
				$name = $this->locale['spirit'];
				$tooltip = $this->locale['spirit_tooltip'];
				break;
			case 'stat_agl':
				$name = $this->locale['agility'];
				$tooltip = $this->locale['agility_tooltip'];
				break;
			case 'stat_armor':
				$name = $this->locale['armor'];
				$tooltip = sprintf($this->locale['armor_tooltip'],$this->data['mitigation']);
				break;
			case 'melee_power':
				$lname = $this->locale['melee_att_power'];
				$name = $this->locale['power'];
				$tooltip = sprintf($this->locale['melee_att_power_tooltip'], $data['melee_power_dps']);
				break;
			case 'melee_hit':
				$name = $this->locale['weapon_hit_rating'];
				$tooltip = $this->locale['weapon_hit_rating_tooltip'];
				break;
			case 'melee_crit':
				$name = $this->locale['weapon_crit_rating'];
				$tooltip = sprintf($this->locale['weapon_crit_rating_tooltip'], $data['melee_crit_chance']);
				break;
		}

		if( isset($lname) )
			$tooltipheader = $lname . ' ' . $this->printRatingLong($statname,$data);
		else
			$tooltipheader = $name . ' ' . $this->printRatingLong($statname,$data);

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
		$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

		return $this->printStatLine($name, $this->printRatingShort($statname,$data), $line);
	}


	/**
	 * Build Pet weapon skill
	 *
	 * @param array $data
	 * @return string
	 */
	function printPetWSkill ( $data )
	{
		global $roster;

		$value = '<strong class="white">' . $data['melee_mhand_skill'] . '</strong>';
		$name = $this->locale['weapon_skill'];
		$tooltipheader = $this->locale['mainhand'];
		$tooltip = sprintf($this->locale['weapon_skill_tooltip'], $data['melee_mhand_skill'], $data['melee_mhand_rating']);

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
		$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

		return $this->printStatLine($name, $value, $line);
	}


	/**
	 * Build Pet weapon damage
	 *
	 * @param array $data
	 * @return string
	 */
	function printPetWDamage ( $data )
	{
		global $roster;

		$value = '<strong class="white">' . $data['melee_mhand_mindam'] . '</strong>' . '-' . '<strong class="white">' . $data['melee_mhand_maxdam'] . '</strong>';
		$name = $this->locale['damage'];
		$tooltipheader = $this->locale['mainhand'];
		$tooltip = sprintf($this->locale['damage_tooltip'], $data['melee_mhand_speed'], $data['melee_mhand_mindam'], $data['melee_mhand_maxdam'], $data['melee_mhand_dps']);

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
		$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

		return $this->printStatLine($name, $value, $line);
	}


	/**
	 * Build Pet resists
	 *
	 * @param string $resname
	 * @param array $data
	 * @return string
	 */
	function printPetResist( $resname , $data )
	{
		global $roster;

		switch($resname)
		{
		case 'fire':
			$name = $this->locale['res_fire'];
			$tooltip = $this->locale['res_fire_tooltip'];
			$color = 'red';
			break;
		case 'nature':
			$name = $this->locale['res_nature'];
			$tooltip = $this->locale['res_nature_tooltip'];
			$color = 'green';
			break;
		case 'arcane':
			$name = $this->locale['res_arcane'];
			$tooltip = $this->locale['res_arcane_tooltip'];
			$color = 'yellow';
			break;
		case 'frost':
			$name = $this->locale['res_frost'];
			$tooltip = $this->locale['res_frost_tooltip'];
			$color = 'blue';
			break;
		case 'shadow':
			$name = $this->locale['res_shadow'];
			$tooltip = $this->locale['res_shadow_tooltip'];
			$color = 'purple';
			break;
		}

		$line = '<span style="color:' . $color . ';font-size:11px;font-weight:bold;">' . $name . '</span> ' . $this->printRatingLong('res_' . $resname,$data) . '<br />';
		$line .= '<span style="color:#DFB801;text-align:left;">' . $tooltip . '</span>';

		$output = '<div class="' . $resname . '" ' . makeOverlib($line,'','',2,'','') . '><b>' . $data['res_'.$resname.'_c'] . '</b><span>' . $data['res_' . $resname . '_c'] . "</span></div>\n";

		return $output;
	}


	/**
	 * Build Pet resilience
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	function printPetResilience( $data )
	{
		global $roster;

		$name = $this->locale['resilience'];
		$value = min($data['stat_res_melee'],$data['stat_res_ranged'],$data['stat_res_spell']);

		$tooltipheader = $name;
		$tooltip  = '<div><span style="float:right;">' . $data['stat_res_melee'] . '</span>' . $this->locale['melee'] . '</div>';
		$tooltip .= '<div><span style="float:right;">' . $data['stat_res_ranged'] . '</span>' . $this->locale['ranged'] . '</div>';
		$tooltip .= '<div><span style="float:right;">' . $data['stat_res_spell'] . '</span>' . $this->locale['spell'] . '</div>';


		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
		$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

		return $this->printStatLine($name, '<strong class="white">' . $value . '%</strong>', $line);
	}


	/**
	 * Build stat line
	 *
	 * @param string $label
	 * @param string $value
	 * @param string $tooltip
	 * @return string
	 */
	function printStatLine( $label , $value , $tooltip )
	{
		$output  = '  <div class="statline" ' . makeOverlib($tooltip,'','',2,'','') . ">\n";
		$output .= '    <span class="value">' . $value . "</span>\n";
		$output .= '    <span class="label">' . $label . ":</span>\n";
		$output .= "  </div>\n";

		return $output;
	}


	/**
	 * Build short rating value
	 *
	 * @param string $statname
	 * @param array $data_or Alternative data to use
	 * @return string
	 */
	function printRatingShort( $statname , $data_or=false )
	{
		if( $data_or == false )
		{
			$data = $this->data;
		}
		else
		{
			$data = $data_or;
		}

		$base = $data[$statname];
		$current = $data[$statname . '_c'];
		$buff = $data[$statname . '_b'];
		$debuff = -$data[$statname . '_d'];

		if( $buff>0 && $debuff>0 )
		{
			$color = 'purple';
		}
		elseif( $buff>0 )
		{
			$color = 'green';
		}
		elseif( $debuff>0 )
		{
			$color = 'red';
		}
		else
		{
			$color = 'white';
		}

		return '<strong class="' . $color . '">' . $current . '</strong>';
	}


	/**
	 * Build long rating value
	 *
	 * @param string $statname
	 * @param array $data_or Alternative data to use
	 * @return string
	 */
	function printRatingLong( $statname , $data_or=false )
	{
		if( $data_or == false )
		{
			$data = $this->data;
		}
		else
		{
			$data = $data_or;
		}

		$base = $data[$statname];
		$current = $data[$statname . '_c'];
		$buff = $data[$statname . '_b'];
		$debuff = -$data[$statname . '_d'];

		$tooltipheader = $current;

		if( $base != $current)
		{
			$tooltipheader .= " ($base";
			if( $buff > 0 )
			{
				$tooltipheader .= " <span class=\"green\">+ $buff</span>";
			}
			if( $debuff > 0 )
			{
				$tooltipheader .= " <span class=\"red\">- $debuff</span>";
			}
			$tooltipheader .= ")";
		}

		return $tooltipheader;
	}


	/**
	 * Build a status box
	 *
	 * @param string $cat
	 * @param string $side
	 * @param bool $visible
	 */
	function printBox( $cat , $side , $visible )
	{
		$return = '<div class="stats" id="' . $cat . $side . '" style="display:' . ($visible?'block':'none') . '">' . "\n";
		switch($cat)
		{
			case 'stats':
				$return .= $this->printStat('stat_str');
				$return .= $this->printStat('stat_agl');
				$return .= $this->printStat('stat_sta');
				$return .= $this->printStat('stat_int');
				$return .= $this->printStat('stat_spr');
				$return .= $this->printStat('stat_armor');
				break;
			case 'melee':
				$return .= $this->printWDamage('melee');
				$return .= $this->printWSpeed('melee');
				$return .= $this->printStat('melee_power');
				$return .= $this->printStat('melee_hit');
				$return .= $this->printStat('melee_crit');
				$return .= $this->printStat('melee_expertise');
				break;
			case 'ranged':
				$return .= $this->printWSkill('ranged');
				$return .= $this->printWDamage('ranged');
				$return .= $this->printWSpeed('ranged');
				$return .= $this->printStat('ranged_power');
				$return .= $this->printStat('ranged_hit');
				$return .= $this->printStat('ranged_crit');
				break;
			case 'spell':
				$return .= $this->printSpellDamage();
				$return .= $this->printValue('spell_healing');
				$return .= $this->printStat('spell_hit');
				$return .= $this->printSpellCrit();
				$return .= $this->printValue('spell_penetration');
				$return .= $this->printValue('mana_regen');
				break;
			case 'defense':
				$return .= $this->printStat('stat_armor');
				$return .= $this->printDefense();
				$return .= $this->printDef('dodge');
				$return .= $this->printDef('parry');
				$return .= $this->printDef('block');
				$return .= $this->printResilience();
				break;
		}
		$return .= "</div>\n";

		return $return;
	}


	/**
	 * Build a status line
	 *
	 * @param string $statname
	 * @return string
	 */
	function printStat( $statname )
	{
		global $roster;

		switch( $statname )
		{
			case 'stat_str':
				$name = $this->locale['strength'];
				$tooltip = $this->locale['strength_tooltip'];
				break;
			case 'stat_int':
				$name = $this->locale['intellect'];
				$tooltip = $this->locale['intellect_tooltip'];
				break;
			case 'stat_sta':
				$name = $this->locale['stamina'];
				$tooltip = $this->locale['stamina_tooltip'];
				break;
			case 'stat_spr':
				$name = $this->locale['spirit'];
				$tooltip = $this->locale['spirit_tooltip'];
				break;
			case 'stat_agl':
				$name = $this->locale['agility'];
				$tooltip = $this->locale['agility_tooltip'];
				break;
			case 'stat_armor':
				$name = $this->locale['armor'];
				$tooltip = sprintf($this->locale['armor_tooltip'],$this->data['mitigation']);
				break;
			case 'melee_power':
				$lname = $this->locale['melee_att_power'];
				$name = $this->locale['power'];
				$tooltip = sprintf($this->locale['melee_att_power_tooltip'], $this->data['melee_power_dps']);
				break;
			case 'melee_hit':
				$name = $this->locale['weapon_hit_rating'];
				$tooltip = $this->locale['weapon_hit_rating_tooltip'];
				break;
			case 'melee_expertise':
				$name = $this->locale['weapon_expertise'];
				$tooltip = $this->locale['weapon_expertise_tooltip'];
				break;
			case 'melee_crit':
				$name = $this->locale['weapon_crit_rating'];
				$tooltip = sprintf($this->locale['weapon_crit_rating_tooltip'], $this->data['melee_crit_chance']);
				break;
			case 'ranged_power':
				$lname = $this->locale['ranged_att_power'];
				$name = $this->locale['power'];
				$tooltip = sprintf($this->locale['ranged_att_power_tooltip'], $this->data['ranged_power_dps']);
				break;
			case 'ranged_hit':
				$name = $this->locale['weapon_hit_rating'];
				$tooltip = $this->locale['weapon_hit_rating_tooltip'];
				break;
			case 'ranged_crit':
				$name = $this->locale['weapon_crit_rating'];
				$tooltip = sprintf($this->locale['weapon_crit_rating_tooltip'], $this->data['ranged_crit_chance']);
				break;
			case 'spell_hit':
				$name = $this->locale['spell_hit_rating'];
				$tooltip = $this->locale['spell_hit_rating_tooltip'];
				break;
		}

		if( isset($lname) )
		{
			$tooltipheader = $lname . ' ' . $this->printRatingLong($statname);
		}
		else
		{
			$tooltipheader = $name . ' ' . $this->printRatingLong($statname);
		}

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
		$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

		return $this->printStatLine($name, $this->printRatingShort($statname), $line);
	}


	/**
	 * Build a special status line
	 *
	 * @param string $statname
	 * @return unknown
	 */
	function printValue( $statname )
	{
		global $roster;

		$value = $this->data[$statname];
		switch( $statname )
		{
			case 'spell_penetration':
				$name = $this->locale['spell_penetration'];
				$tooltip = $this->locale['spell_penetration_tooltip'];
				break;

			case 'mana_regen':
				$name = $this->locale['mana_regen'];
				$tooltip = sprintf($this->locale['mana_regen_tooltip'],$this->data['mana_regen'],$this->data['mana_regen_cast']);
				break;

			case 'spell_healing':
				$name = $this->locale['spell_healing'];
				$tooltip = sprintf($this->locale['spell_healing_tooltip'],$this->data['spell_healing']);
				break;
		}

		$tooltipheader = (isset($name) ? $name : '');

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
		$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

		return $this->printStatLine($name, '<strong class="white">' . $value . '</strong>', $line);
	}


	/**
	 * Build weapon skill
	 *
	 * @param string $location
	 * @return string
	 */
	function printWSkill ( $location )
	{
		global $roster;

		if( $location == 'ranged' )
		{
			$value = '<strong class="white">' . $this->data['ranged_skill'] . '</strong>';
			$name = $this->locale['weapon_skill'];
			$tooltipheader = $this->locale['ranged'];
			$tooltip = sprintf($this->locale['weapon_skill_tooltip'], $this->data['ranged_skill'], $this->data['ranged_rating']);

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
			$line .= '<div style="color:#DFB801;">' . $tooltip . '</div>';
		}
		else
		{
			$value = '<strong class="white">' . $this->data['melee_mhand_skill'] . '</strong>';
			$name = $this->locale['weapon_skill'];
			$tooltipheader = $this->locale['mainhand'];
			$tooltip = sprintf($this->locale['weapon_skill_tooltip'], $this->data['melee_mhand_skill'], $this->data['melee_mhand_rating']);

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
			$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

			if( $this->data['melee_ohand_dps'] > 0 )
			{
				$value .= '/<strong class="white">' . $this->data['melee_ohand_skill'] . '</strong>';
				$tooltipheader = $this->locale['offhand'];
				$tooltip = sprintf($this->locale['weapon_skill_tooltip'], $this->data['melee_ohand_skill'], $this->data['melee_ohand_rating']);

				$line .= '<br /><span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
				$line .= '<div style="color:#DFB801;">' . $tooltip . '</div>';
			}
		}

		return $this->printStatLine($name, $value, $line);
	}


	/**
	 * Build weapon damage
	 *
	 * @param string $location
	 * @return string
	 */
	function printWDamage ( $location )
	{
		global $roster;

		if( $location == 'ranged' )
		{
			$value = '<strong class="white">' . $this->data['ranged_mindam'] . '</strong>' . '-' . '<strong class="white">' . $this->data['ranged_maxdam'] . '</strong>';
			$name = $this->locale['damage'];
			$tooltipheader = $this->locale['ranged'];
			$tooltip = sprintf($this->locale['damage_tooltip'], $this->data['ranged_speed'], $this->data['ranged_mindam'], $this->data['ranged_maxdam'], $this->data['ranged_dps']);

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
			$line .= '<div style="color:#DFB801;">' . $tooltip . '</div>';
		}
		else
		{
			$value = '<strong class="white">' . $this->data['melee_mhand_mindam'] . '</strong>-<strong class="white">' . $this->data['melee_mhand_maxdam'] . '</strong>';
			$name = $this->locale['damage'];
			$tooltipheader = $this->locale['mainhand'];
			$tooltip = sprintf($this->locale['damage_tooltip'], $this->data['melee_mhand_speed'], $this->data['melee_mhand_mindam'], $this->data['melee_mhand_maxdam'], $this->data['melee_mhand_dps']);

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
			$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';

			if( $this->data['melee_ohand_dps'] > 0 )
			{
				// This will only print then there is no main hand data because printing both stats is too long for the box
				if( empty($this->data['melee_mhand_mindam']) )
				{
					$value .= '<strong class="white">' . $this->data['melee_ohand_mindam'] . '</strong>-<strong class="white">' . $this->data['melee_ohand_maxdam'] . '</strong>';
				}
				$tooltipheader = $this->locale['offhand'];
				$tooltip = sprintf($this->locale['damage_tooltip'], $this->data['melee_ohand_speed'], $this->data['melee_ohand_mindam'], $this->data['melee_ohand_maxdam'], $this->data['melee_ohand_dps']);

				$line .= '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
				$line .= '<div style="color:#DFB801;">' . $tooltip . '</div>';
			}
		}


		return $this->printStatLine($name, $value, $line);
	}


	/**
	 * Build weapon speed
	 *
	 * @param string $location
	 * @return string
	 */
	function printWSpeed ( $location )
	{
		global $roster;

		if( $location == 'ranged' )
		{
			$value = '<strong class="white">' . $this->data['ranged_speed'] . '</strong>';
			$name = $this->locale['speed'];
			$tooltipheader = $this->locale['atk_speed'] . ' ' . $value;
			$tooltip = $this->locale['haste_tooltip'] . $this->printRatingLong('ranged_haste');

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
			$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';
		}
		else
		{
			$value = '<strong class="white">' . $this->data['melee_mhand_speed'] . '</strong>';
			$name = $this->locale['speed'];

			if( $this->data['melee_ohand_dps'] > 0 )
			{
				$value .= '/<strong class="white">' . $this->data['melee_ohand_speed'] . '</strong>';
			}

			$tooltipheader = $this->locale['atk_speed'] . ' ' . $value;
			$tooltip = $this->locale['haste_tooltip'] . $this->printRatingLong('melee_haste');

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">' . $tooltipheader . '</span><br />';
			$line .= '<span style="color:#DFB801;">' . $tooltip . '</span>';
		}

		return $this->printStatLine($name, $value, $line);
	}


	/**
	 * Build spell damage
	 *
	 * @return string
	 */
	function printSpellDamage( )
	{
		global $roster, $addon;

		$name = $this->locale['spell_damage'];
		$value = '<strong class="white">'.$this->data['spell_damage'].'</strong>';

		$tooltipheader = $name.' '.$value;

		$tooltip  = '<div><span style="float:right;">'.$this->data['spell_damage_holy'].'</span><img src="' . $addon['image_path'] . 'resist/icon-holy.gif" alt="" />'.$this->locale['holy'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_fire'].'</span><img src="' . $addon['image_path'] . 'resist/icon-fire.gif" alt="" />'.$this->locale['fire'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_nature'].'</span><img src="' . $addon['image_path'] . 'resist/icon-nature.gif" alt="" />'.$this->locale['nature'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_frost'].'</span><img src="' . $addon['image_path'] . 'resist/icon-frost.gif" alt="" />'.$this->locale['frost'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_shadow'].'</span><img src="' . $addon['image_path'] . 'resist/icon-shadow.gif" alt="" />'.$this->locale['shadow'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_arcane'].'</span><img src="' . $addon['image_path'] . 'resist/icon-arcane.gif" alt="" />'.$this->locale['arcane'].'</div>';

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $value, $line);
	}


	/**
	 * Build spell crit chance
	 *
	 * @return string
	 */
	function printSpellCrit()
	{
		global $roster, $addon;

		$name = $this->locale['spell_crit_chance'];
		$value = '<strong class="white">' . $this->data['spell_crit_chance'] . '</strong>';

		$tooltipheader = $this->locale['spell_crit_rating'].' '.$this->printRatingLong('spell_crit');

		$tooltip = '<div><span style="float:right;">'.sprintf('%.2f%%',$this->data['spell_crit_chance_holy']).'</span><img src="' . $addon['image_path'] . 'resist/icon-holy.gif" alt="" />'.$this->locale['holy'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.sprintf('%.2f%%',$this->data['spell_crit_chance_fire']).'</span><img src="' . $addon['image_path'] . 'resist/icon-fire.gif" alt="" />'.$this->locale['fire'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.sprintf('%.2f%%',$this->data['spell_crit_chance_nature']).'</span><img src="' . $addon['image_path'] . 'resist/icon-nature.gif" alt="" />'.$this->locale['nature'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.sprintf('%.2f%%',$this->data['spell_crit_chance_frost']).'</span><img src="' . $addon['image_path'] . 'resist/icon-frost.gif" alt="" />'.$this->locale['frost'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.sprintf('%.2f%%',$this->data['spell_crit_chance_shadow']).'</span><img src="' . $addon['image_path'] . 'resist/icon-shadow.gif" alt="" />'.$this->locale['shadow'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.sprintf('%.2f%%',$this->data['spell_crit_chance_arcane']).'</span><img src="' . $addon['image_path'] . 'resist/icon-arcane.gif" alt="" />'.$this->locale['arcane'].'</div>';

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $value, $line);
	}


	/**
	 * Build defense rating value
	 *
	 * @return string
	 */
	function printDefense( )
	{
		global $roster;

		$qry = "SELECT `skill_level` FROM `" . $roster->db->table('skills') . "` WHERE `member_id` = ".$this->data['member_id']." AND `skill_name` = '".$this->locale['defense']."'";
		$result = $roster->db->query($qry);
		if( !$result )
		{
			$value = 'N/A';
		}
		else
		{
			$row = $roster->db->fetch($result);
			$value = explode(':',$row[0]);
			$value = $value[0];
			$roster->db->free_result($result);
			unset($row);
		}

		$name = $this->locale['defense'];
		$tooltipheader = $name.' '.$value;

		$tooltip = $this->locale['defense_rating'].$this->printRatingLong('stat_defr');

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'</strong>', $line);
	}


	/**
	 * Build a defense value
	 *
	 * @param string $statname
	 *
	 * @return string
	 */
	function printDef( $statname )
	{
		global $roster;

		$name = $this->locale[$statname];
		$value = $this->data[$statname];

		$tooltipheader = $name.' '.$this->printRatingLong('stat_'.$statname);
		$tooltip = sprintf($this->locale['def_tooltip'],$name);

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'%</strong>', $line);
	}


	/**
	 * Build resiliance value
	 *
	 * @return string
	 */
	function printResilience( )
	{
		global $roster;

		$name = $this->locale['resilience'];
		$value = min($this->data['stat_res_melee'],$this->data['stat_res_ranged'],$this->data['stat_res_spell']);

		$tooltipheader = $name;
		$tooltip  = '<div><span style="float:right;">'.$this->data['stat_res_melee'].'</span>'.$this->locale['melee'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['stat_res_ranged'].'</span>'.$this->locale['ranged'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['stat_res_spell'].'</span>'.$this->locale['spell'].'</div>';


		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'</strong>', $line);
	}


	/**
	 * Build a resistance value
	 *
	 * @param string $resname
	 * @return string
	 */
	function printResist( $resname )
	{
		global $roster;

		switch($resname)
		{
		case 'fire':
			$name = $this->locale['res_fire'];
			$tooltip = $this->locale['res_fire_tooltip'];
			$color = 'red';
			break;
		case 'nature':
			$name = $this->locale['res_nature'];
			$tooltip = $this->locale['res_nature_tooltip'];
			$color = 'green';
			break;
		case 'arcane':
			$name = $this->locale['res_arcane'];
			$tooltip = $this->locale['res_arcane_tooltip'];
			$color = 'yellow';
			break;
		case 'frost':
			$name = $this->locale['res_frost'];
			$tooltip = $this->locale['res_frost_tooltip'];
			$color = 'blue';
			break;
		case 'shadow':
			$name = $this->locale['res_shadow'];
			$tooltip = $this->locale['res_shadow_tooltip'];
			$color = 'purple';
			break;
		}

		$line = '<span style="color:'.$color.';font-size:11px;font-weight:bold;">'.$name.'</span> '.$this->printRatingLong('res_'.$resname).'<br />';
		$line .= '<span style="color:#DFB801;text-align:left;">'.$tooltip.'</span>';

		$output = '<div class="'.$resname.'" '.makeOverlib($line,'','',2,'','').'><b>'. $this->data['res_'.$resname.'_c'] .'</b><span>'. $this->data['res_'.$resname.'_c'] ."</span></div>\n";

		return $output;
	}


	/**
	 * Build a equiped item slot
	 *
	 * @param string $slot
	 * @return string
	 */
	function printEquip( $slot )
	{
		global $roster;

		if( isset($this->equip[$slot]) )
		{
			$item = $this->equip[$slot];
			$output = $item->out();
		}
		else
		{
			$output = '<div class="item" '.makeOverlib($this->locale['empty_equip'],$this->locale[$slot],'',2,'',',WRAP').">\n";
			if ($slot == 'Ammo')
			{
				$output .= '<img src="'.$roster->config['img_url'].'pixel.gif" class="iconsmall" alt="" />'."\n";
			}
			else
			{
				$output .= '<img src="'.$roster->config['img_url'].'pixel.gif" class="icon" alt="" />'."\n";
			}
			$output .= "</div>\n";
		}
		return '<div class="equip_'.$slot.'">'.$output.'</div>';
	}


	/**
	 * Build Talents
	 *
	 * @return string
	 */
	function printTalents( )
	{
		global $roster, $addon;

		$sqlquery = "SELECT * FROM `".$roster->db->table('talenttree')."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `order`;";
		$trees = $roster->db->query( $sqlquery );

		if( $roster->db->num_rows($trees) > 0 )
		{
			for( $j=0; $j < $roster->db->num_rows($trees); $j++)
			{
				$treedata = $roster->db->fetch($trees);

				$treelayer[$j]['name'] = $treedata['tree'];
				$treelayer[$j]['image'] = $treedata['background'].'.'.$roster->config['img_suffix'];
				$treelayer[$j]['points'] = $treedata['pointsspent'];
				$treelayer[$j]['talents'] = $this->talentLayer($treedata['tree']);
			}

			$returndata = '
<div class="char_panel talent_panel">

	<img class="panel_icon" src="' . $addon['image_path'] . 'icon_talents.gif" alt="" />
	<div class="panel_title">'.$this->locale['talents'].'</div>
	<img class="top_bar" src="' . $addon['image_path'] . 'talent/bar_top.gif" alt="" />
	<img class="bot_bar" src="' . $addon['image_path'] . 'talent/bar_bottom.gif" alt="" />

	<div class="link"><a href="';

			switch($this->data['clientLocale'])
			{
				case 'enUS':
					$returndata .= 'http://www.worldofwarcraft.com/info/classes/';
					break;

				case 'frFR':
					$returndata .= 'http://www.wow-europe.com/fr/info/basics/talents/';
					break;

				case 'deDE':
					$returndata .= 'http://www.wow-europe.com/de/info/basics/talents/';
					break;

				case 'esES':
					$returndata .= 'http://www.wow-europe.com/es/info/basics/talents/';
					break;

				default:
					$returndata .= 'http://www.worldofwarcraft.com/info/classes/';
					break;
			}

			$returndata .= strtolower($this->data['classEn']).'/talents.html?'.$this->talent_build.'" target="_blank">'.$roster->locale->wordings[$this->data['clientLocale']]['talentexport'].'</a></div>
	<div class="points_unused"><span class="label">'.$roster->locale->wordings[$this->data['clientLocale']]['unusedtalentpoints'].':</span> '.$this->data['talent_points'].'</div>'."\n";

			$treeshow = 0;
			$treeshowp = 0;
			foreach( $treelayer as $treeindex => $tree )
			{
				$treeshow = ( $tree['points'] > $treeshowp ? $tree['points'] : $treeshowp);

				$returndata .= '	<div id="treetab'.$treeindex.'" style="display:none;" >

		<div class="points"><span style="color:#ffdd00">'.sprintf($roster->locale->wordings[$this->data['clientLocale']]['pointsspent'],$tree['name']).':</span> '.$tree['points'].'</div>
		<img class="background" src="'.$roster->config['interface_url'].'Interface/TalentFrame/'.$tree['image'].'" alt="" />

		<div class="container">'."\n";

				foreach( $tree['talents'] as $row )
				{
					$returndata .= '			<div class="row">'."\n";
					foreach( $row as $cell )
					{
						if( $cell['name'] != '' )
						{
							if( $cell['rank'] != 0 )
							{
								$returndata .= '				<div class="cell" '.$cell['tooltipid'].'>
					<img class="rank_icon" src="' . $addon['image_path'] . 'talent/rank.gif" alt="" />
					<div class="rank_text" style="font-weight:bold;color:#'.$cell['numcolor'].';">'.$cell['rank'].'</div>
					<img src="'.$roster->config['interface_url'].'Interface/Icons/'.$cell['image'].'" alt="" /></div>'."\n";
							}
							else
							{
								$returndata .= '				<div class="cell" '.$cell['tooltipid'].'>
					<img class="icon_grey" src="'.$roster->config['interface_url'].'Interface/Icons/'.$cell['image'].'" alt="" /></div>'."\n";
							}
						}
						else
						{
							$returndata .= '				<div class="cell">&nbsp;</div>'."\n";
						}
					}
					$returndata .= '			</div>'."\n";
				}

				$returndata .= "		</div>\n	</div>\n";
			}
			$returndata .= '
	<div class="tab_navagation" style="margin:428px 0 0 17px;">
		<ul id="talent_navagation">
			<li' . ($treeshow==0?' class="selected"':'') . '><a rel="treetab0" class="text">'.$treelayer[0]['name'].'</a></li>
			<li' . ($treeshow==0?' class="selected"':'') . '><a rel="treetab1" class="text">'.$treelayer[1]['name'].'</a></li>
			<li' . ($treeshow==0?' class="selected"':'') . '><a rel="treetab2" class="text">'.$treelayer[2]['name'].'</a></li>
		</ul>
	</div>

</div>

<script type="text/javascript">
	initializetabcontent(\'talent_navagation\')
</script>';
			return $returndata;
		}
		else
		{
			return '<span class="headline_1">No Talents for '.$this->data['name'].'</span>';
		}
	}


	/**
	 * Build a talent tree
	 *
	 * @param string $treename
	 * @return array
	 */
	function talentLayer( $treename )
	{
		global $roster;

		$sqlquery = "SELECT * FROM `".$roster->db->table('talents')."` WHERE `member_id` = '".$this->data['member_id']."' AND `tree` = '".$treename."' ORDER BY `row` ASC , `column` ASC";

		$result = $roster->db->query($sqlquery);

		$returndata = array();
		if( $roster->db->num_rows($result) > 0 )
		{
			// initialize the rows and cells
			for($r=1; $r < 10; $r++)
			{
				for($c=1; $c < 5; $c++)
				{
					$returndata[$r][$c]['name'] = '';
				}
			}

			while( $talentdata = $roster->db->fetch( $result ) )
			{
				$r = $talentdata['row'];
				$c = $talentdata['column'];

				$this->talent_build .= $talentdata['rank'];

				$returndata[$r][$c]['name'] = $talentdata['name'];
				$returndata[$r][$c]['rank'] = $talentdata['rank'];
				$returndata[$r][$c]['maxrank'] = $talentdata['maxrank'];
				$returndata[$r][$c]['row'] = $r;
				$returndata[$r][$c]['column'] = $c;
				$returndata[$r][$c]['image'] = $talentdata['texture'].'.'.$roster->config['img_suffix'];
				$returndata[$r][$c]['tooltipid'] = makeOverlib($talentdata['tooltip'],'','',0,$this->data['clientLocale']);

				if( $talentdata['rank'] == $talentdata['maxrank'] )
				{
					$returndata[$r][$c]['numcolor'] = 'ffdd00';
				}
				else
				{
					$returndata[$r][$c]['numcolor'] = '00dd00';
				}
			}
		}
		return $returndata;
	}


	/**
	 * Build character skills
	 *
	 * @return string
	 */
	function printSkills( )
	{
		global $roster, $addon;

		$skillData = $this->getSkillTabValues();

		$output = '';
		foreach( $skillData as $sindex => $skill )
		{
			$output .= '
		<div class="header"><img src="'.$roster->config['img_url'].'minus.gif" id="skill'.$sindex.'_img" class="minus_plus" alt="" onclick="showHide(\'skill'.$sindex.'\',\'skill'.$sindex.'_img\',\''.$roster->config['img_url'].'minus.gif\',\''.$roster->config['img_url'].'plus.gif\');" />'.$skill['name'].'</div>
		<div id="skill'.$sindex.'">
';
			foreach( $skill['bars'] as $skillbar )
			{
				$output .= '
			<div class="skill_bar">';
				if( $skillbar['maxvalue'] == '1' )
				{
					$output .= '
				<div style="position:absolute;"><img src="' . $addon['image_path'] . 'skill/bar_grey.gif" alt="" /></div>
				<div class="text">'.$skillbar['name'].'</div>';
				}
				else
				{
					$output .= '
				<div style="position:absolute;clip:rect(0px '.$skillbar['barwidth'].'px 15px 0px);"><img src="' . $addon['image_path'] . 'skill/bar.gif" alt="" /></div>
				<div class="text">'.$skillbar['name'].'<span class="text_num">'.$skillbar['value'].' / '.$skillbar['maxvalue'].'</span></div>';
				}
				$output .= "\n			</div>\n";
			}
			$output .= "		</div>\n";
		}

		return $output;
	}


	/**
	 * Build a skill bars data
	 *
	 * @param array $skilldata
	 * @return array
	 */
	function getSkillBarValues( $skilldata )
	{
		list($level, $max) = explode( ':', $skilldata['skill_level'] );

		$returnData['maxvalue'] = $max;
		$returnData['value'] = $level;
		$returnData['name'] = $skilldata['skill_name'];
		$returnData['barwidth'] = ceil($level/$max*273);

		return $returnData;
	}


	/**
	 * Build skill values
	 *
	 * @return array
	 */
	function getSkillTabValues( )
	{
		global $roster;

		$query = "SELECT * FROM `".$roster->db->table('skills')."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `skill_order` ASC, `skill_name` ASC;";
		$result = $roster->db->query( $query );

		$skill_rows = $roster->db->num_rows($result);

		$i=0;
		$j=0;
		if ( $skill_rows > 0 )
		{
			$data = $roster->db->fetch( $result );
			$skillInfo[$i]['name'] = $data['skill_type'];

			for( $r=0; $r < $skill_rows; $r++ )
			{
				if( $skillInfo[$i]['name'] != $data['skill_type'] )
				{
					$i++;
					$j=0;
					$skillInfo[$i]['name'] = $data['skill_type'];
				}
				$skillInfo[$i]['bars'][$j] = $this->getSkillBarValues($data);
				$j++;
				$data = $roster->db->fetch( $result );
			}
			return $skillInfo;
		}
	}


	/**
	 * Build character reputation
	 *
	 * @return string
	 */
	function printReputation( )
	{
		global $roster, $addon;

		$repData = $this->getRepTabValues();

		$output = '';
		foreach( $repData as $findex => $faction )
		{
			$output .= '
		<div class="header" onclick="showHide(\'rep' . $findex . '\',\'rep' . $findex . '_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');"><img src="' . $roster->config['img_url'] . '' . ( $faction['name'] == $roster->locale->act['inactive'] ? 'plus' : 'minus' ) . '.gif" id="rep' . $findex . '_img" class="minus_plus" alt="" />' . $faction['name'] . '</div>
		<div id="rep' . $findex . '"' . ( $faction['name'] == $roster->locale->act['inactive'] ? ' style="display:none;"' : '' ) . ">\n";

			foreach( $faction['bars'] as $repbar )
			{
				$output .= '
			<div class="rep_bar">
				<div class="rep_title">' . $repbar['name'] . '</div>
				<div class="rep_bar_field" style="clip:rect(0px ' . $repbar['barwidth'] . 'px 13px 0px);"><img class="rep_bar_image" src="' . $repbar['image'] . '" alt="" /></div>
				<div class="rep_bar_field"><img class="rep_bar_image" src="' . $roster->config['img_url'] . 'pixel.gif" alt="" /></div>
				<div class="rep_bar_text">' . $repbar['standing'] . ' - ' . $repbar['value'] . ' / ' . $repbar['maxvalue'] . '</div>' . "\n";
				if( $repbar['atwar'] == 1 )
				{
					$output .= '				<img src="' . $addon['image_path'] . '/rep/atwar.gif" style="float:right;" alt="" />' . "\n";
				}
				$output .= "			</div>\n";
			}
			$output .= "		</div>\n";
		}

		return $output;
	}


	/**
	 * Build a reputation bars data
	 *
	 * @return array
	 */
	function getRepTabValues( )
	{
		global $roster;

		$query= "SELECT * FROM `".$roster->db->table('reputation')."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `faction` ASC, `name` ASC;";
		$result = $roster->db->query( $query );

		$rep_rows = $roster->db->num_rows($result);

		$i=0;
		$j=0;
		if ( $rep_rows > 0 )
		{
			$data = $roster->db->fetch( $result );
			$repInfo[$i]['name'] = $data['faction'];

			for( $r=0; $r < $rep_rows; $r++ )
			{
				if( $repInfo[$i]['name'] != $data['faction'] )
				{
					$i++;
					$j=0;
					$repInfo[$i]['name'] = $data['faction'];
				}
				$repInfo[$i]['bars'][$j] = $this->getRepBarValues($data);
				$j++;
				$data = $roster->db->fetch( $result );
			}
			return $repInfo;
		}
	}


	/**
	 * Build reputation values
	 *
	 * @param array $repdata
	 * @return array
	 */
	function getRepBarValues( $repdata )
	{
		static $repnum = 0;

		global $roster, $addon;

		$level = $repdata['curr_rep'];
		$max = $repdata['max_rep'];

		$img = array(
			$this->locale['exalted'] => $addon['image_path'] . 'rep/green.gif',
			$this->locale['revered'] => $addon['image_path'] . 'rep/green.gif',
			$this->locale['honored'] => $addon['image_path'] . 'rep/green.gif',
			$this->locale['friendly'] => $addon['image_path'] . 'rep/green.gif',
			$this->locale['neutral'] => $addon['image_path'] . 'rep/yellow.gif',
			$this->locale['unfriendly'] => $addon['image_path'] . 'rep/orange.gif',
			$this->locale['hostile'] => $addon['image_path'] . 'rep/red.gif',
			$this->locale['hated'] => $addon['image_path'] . 'rep/red.gif'
		);

		$returnData['name'] = $repdata['name'];
		$returnData['barwidth'] = ceil($level / $max * 139);
		$returnData['image'] = $img[$repdata['Standing']];
		$returnData['barid'] = $repnum;
		$returnData['standing'] = $repdata['Standing'];
		$returnData['value'] = $level;
		$returnData['maxvalue'] = $max;
		$returnData['atwar'] = $repdata['AtWar'];

		$repnum++;

		return $returnData;
	}


	/**
	 * Build pvp stats
	 *
	 * @return string
	 */
	function printHonor()
	{
		global $roster;

		$icon = '';
		switch( substr($roster->data['factionEn'],0,1) )
		{
			case 'A':
				$icon = '<img src="'.$roster->config['img_url'].'icon_alliance.png" style="width:20px;height:20px;" alt="" />';
				break;
			case 'H':
				$icon = '<img src="'.$roster->config['img_url'].'icon_horde.png" style="width:20px;height:20px;" alt="" />';
				break;
		}

		$output = '
		<div class="honortext">'.$this->locale['honor'].':<span>'.$this->data['honorpoints'].'</span>'.$icon.'</div>

		<div class="today">'.$this->locale['today'].'</div>
		<div class="yesterday">'.$this->locale['yesterday'].'</div>
		<div class="lifetime">'.$this->locale['lifetime'].'</div>

		<div class="divider"></div>

		<div class="killsline">'.$this->locale['kills'].'</div>
		<div class="killsline1">'.$this->data['sessionHK'].'</div>
		<div class="killsline2">'.$this->data['yesterdayHK'].'</div>
		<div class="killsline3">'.$this->data['lifetimeHK'].'</div>

		<div class="honorline">'.$this->locale['honor'].'</div>
		<div class="honorline1">~'.$this->data['sessionCP'].'</div>
		<div class="honorline2">'.$this->data['yesterdayContribution'].'</div>
		<div class="honorline3">-</div>

		<div class="arenatext">'.$this->locale['arena'].':<span>'.$this->data['arenapoints'].'</span><img src="'.$roster->config['img_url'].'arenapointsicon.png" alt="" /></div>'."\n";

		return $output;
	}

	function _altNameHover()
	{
		global $roster;

		if( active_addon('memberslist') )
		{
			$sql = "SELECT `main_id` FROM `"
				 . $roster->db->table('alts', 'memberslist')
				 . "` WHERE `member_id` = " . $this->data['member_id'] . ";";

			$main_id = $roster->db->query_first($sql);
			if( $main_id != 0 )
			{
				// we know the main, get alt info
				$sql = "SELECT `m`.`name`, `m`.`level`, `m`.`class`, `a`.* FROM `"
					 . $roster->db->table('alts', 'memberslist') . "` AS a, `"
					 . $roster->db->table('players') . "` AS m "
					 . " WHERE `a`.`member_id` = `m`.`member_id` "
					 . " AND `a`.`main_id` = $main_id;";

				$qry = $roster->db->query($sql);
				$alts = $roster->db->fetch_all($qry, SQL_ASSOC);

				if( isset($alts[1]) )
				{
					$html = $caption = '';

					foreach( $alts as $alt )
					{
						if( $alt['main_id'] == $alt['member_id'] )
						{
							$caption = '<a href="' . makelink('char-info&amp;a=c:' . $alt['member_id']) . '">'
								     . $alt['name'] . ' (' . $roster->locale->act['level']
								     . ' ' . $alt['level'] . ' ' . $alt['class'] . ')</a>';
						}
						else
						{
							$html .= '<a href="' . makelink('char-info&amp;a=c:' . $alt['member_id']) . '">'
								   . $alt['name'] . ' (' . $roster->locale->act['level']
								   . ' ' . $alt['level'] . ' ' . $alt['class'] . ')</a><br />';
						}
					}
					setTooltip('alt_html', $html);
					setTooltip('alt_cap', $caption);
					$this->alt_hover = 'style="cursor:pointer;" onmouseover="return overlib(overlib_alt_html,CAPTION,overlib_alt_cap);" '
									 . 'onclick="return overlib(overlib_alt_html,CAPTION,overlib_alt_cap,STICKY,OFFSETX,-5,OFFSETY,-5,NOCLOSE);" '
									 . 'onmouseout="return nd();"';
					return;
				}
			}
		}
		$this->alt_hover = '';
		return;
	}

	/**
	 * Main output function
	 */
	function out( )
	{
		global $roster, $addon;

		if ($this->data['name'] != '')
		{
			$this->fetchEquip();
			$petTab = $this->printPet();
			$this->_altNameHover();

			$output = '
<div class="char_panel">
	<img src="' . $this->data['char_icon'] . '.gif" class="panel_icon" alt="" '. $this->alt_hover . '/>
	<div class="panel_title" ' . $this->alt_hover . '>' . $this->data['name'] . '</div>
	<div class="infoline_1" ' . $this->alt_hover . '>' . sprintf($this->locale['char_level_race_class'],$this->data['level'],$this->data['race'],$this->data['class']) . '</div>
';

			if( isset($this->data['guild_title']) && isset($this->data['guild_name']) )
			{
				$output .= '	<div class="infoline_2"' . $this->alt_hover . '>'.sprintf($this->locale['char_guildline'],$this->data['guild_title'],$this->data['guild_name'])."</div>\n";
			}

			$output .= '

<!-- Begin tab1 -->
	<div id="tab1" class="tab1" style="display:none;">
		<div class="background">&nbsp;</div>

	<!-- Begin Equipment Items -->
		<div class="equip">
			' . $this->printEquip('Head') . '
			' . $this->printEquip('Neck') . '
			' . $this->printEquip('Shoulder') . '
			' . $this->printEquip('Back') . '
			' . $this->printEquip('Chest') . '
			' . $this->printEquip('Shirt') . '
			' . $this->printEquip('Tabard') . '
			' . $this->printEquip('Wrist') . '

			' . $this->printEquip('MainHand') . '
			' . $this->printEquip('SecondaryHand') . '
			' . $this->printEquip('Ranged') . '
			' . $this->printEquip('Ammo') . '

			' . $this->printEquip('Hands') . '
			' . $this->printEquip('Waist') . '
			' . $this->printEquip('Legs') . '
			' . $this->printEquip('Feet') . '
			' . $this->printEquip('Finger0') . '
			' . $this->printEquip('Finger1') . '
			' . $this->printEquip('Trinket0') . '
			' . $this->printEquip('Trinket1') . '
		</div>
	<!-- End Equipment Items -->

	<!-- Begin Resists -->
		<div class="resist">
			' . $this->printResist('arcane') . '
			' . $this->printResist('fire') . '
			' . $this->printResist('nature') . '
			' . $this->printResist('frost') . '
			' . $this->printResist('shadow') . '
		</div>
	<!-- End Resists -->

	<!-- Begin Advanced Stats -->
		<img src="' . $addon['image_path'] . 'percentframe.gif" class="percent_frame" alt="" />

		<div class="health"><span class="yellowB">' . $this->locale['health'] . ':</span> ' . $this->data['health'] . '</div>
		<div class="mana"><span class="yellowB">' . $this->data['power'] . ':</span> ' . $this->data['mana'] . '</div>

		<div class="info_desc">
';

			if($this->data['talent_points'])
			{
				$output .= '			'.$this->locale['unusedtalentpoints']."<br />\n";
			}

			if( $addon['config']['show_played'] )
			{
				$output .= '			'.$this->locale['timeplayed']."<br />\n";
				$output .= '			'.$this->locale['timelevelplayed']."<br />\n";
			}

			$output .= "\n		</div>\n		<div class=\"info_values\">\n";

			if($this->data['talent_points'])
			{
				$output .= '			'.$this->data['talent_points']."<br />\n";
			}

			if( $addon['config']['show_played'] )
			{
				$TimeLevelPlayedConverted = seconds_to_time($this->data['timelevelplayed']);
				$TimePlayedConverted = seconds_to_time($this->data['timeplayed']);
				$output .= '			'.$TimePlayedConverted['days'].$TimePlayedConverted['hours'].$TimePlayedConverted['minutes'].$TimePlayedConverted['seconds']."<br />\n";
				$output .= '			'.$TimeLevelPlayedConverted['days'].$TimeLevelPlayedConverted['hours'].$TimeLevelPlayedConverted['minutes'].$TimeLevelPlayedConverted['seconds']."<br />\n";
			}

			$output .= "\n		</div>\n";

			if( $addon['config']['show_money'] )
			{
				$output .= "\n		<!-- Money Display -->\n		<div class=\"money_disp\">\n";

				if( $this->data['money_g'] != '0' )
				{
					$output .= '			'.$this->data['money_g'].'<img src="'.$roster->config['img_url'].'coin_gold.gif" class="coin" alt="g" />'."\n";
				}
				if( $this->data['money_s'] != '0' )
				{
					$output .= '			'.$this->data['money_s'].'<img src="'.$roster->config['img_url'].'coin_silver.gif" class="coin" alt="s" />'."\n";
				}
				if( $this->data['money_c'] != '0' )
				{
					$output .= '			'.$this->data['money_c'].'<img src="'.$roster->config['img_url'].'coin_copper.gif" class="coin" alt="c" />'."\n";
				}
				$output .= "\n		</div>\n";
			}

			// Code to write a "Max Exp bar" just like in SigGen
			if( $this->data['level'] == ROSTER_MAXCHARLEVEL )
			{
				$expbar_width = '216';
				$expbar_text = $this->locale['max_exp'];
				$expbar_type = 'expbar_full';
			}
			elseif( $this->data['exp'] == '0' )
			{
				$expbar_width = 0;
				$expbar_type = 'expbar_full';
				$expbar_text = '';
			}
			else
			{
				list($xp, $xplevel, $xprest) = explode(':',$this->data['exp']);
				if ($xplevel != '0' && $xplevel != '')
				{
					$expbar_width = ( $xplevel > 0 ? floor($xp / $xplevel * 216) : 0);

					$exp_percent = ( $xplevel > 0 ? floor($xp / $xplevel * 100) : 0);

					if( $xprest > 0 )
					{
						$expbar_text = $xp.'/'.$xplevel.' : '.$xprest.' ('.$exp_percent.'%)';
						$expbar_type = 'expbar_full_rested';
					}
					else
					{
						$expbar_text = $xp.'/'.$xplevel.' ('.$exp_percent.'%)';
						$expbar_type = 'expbar_full';
					}
				}
			}

			$output .= '
	<!-- Begin EXP Bar -->
		<img src="' . $addon['image_path'] . 'expbar_empty.gif" class="xpbar_empty" alt="" />
		<div class="xpbar" style="clip:rect(0px ' . $expbar_width . 'px 12px 0px);"><img src="' . $addon['image_path'] . '/' . $expbar_type . '.gif" alt="" /></div>
		<div class="xpbar_text">' . $expbar_text . '</div>
	<!-- End EXP Bar -->
';

			switch( $this->data['class'] )
			{
				case $roster->locale->wordings[$this->data['clientLocale']]['Warrior']:
				case $roster->locale->wordings[$this->data['clientLocale']]['Paladin']:
				case $roster->locale->wordings[$this->data['clientLocale']]['Rogue']:
					$rightbox = 'melee';
					break;

				case $roster->locale->wordings[$this->data['clientLocale']]['Hunter']:
					$rightbox = 'ranged';
					break;

				case $roster->locale->wordings[$this->data['clientLocale']]['Shaman']:
				case $roster->locale->wordings[$this->data['clientLocale']]['Druid']:
				case $roster->locale->wordings[$this->data['clientLocale']]['Mage']:
				case $roster->locale->wordings[$this->data['clientLocale']]['Warlock']:
				case $roster->locale->wordings[$this->data['clientLocale']]['Priest']:
					$rightbox = 'spell';
					break;
			}

			$output .= '
<script type="text/javascript">
<!--
	addLpage(\'statsleft\');
	addLpage(\'meleeleft\');
	addLpage(\'rangedleft\');
	addLpage(\'spellleft\');
	addLpage(\'defenseleft\');
	addRpage(\'statsright\');
	addRpage(\'meleeright\');
	addRpage(\'rangedright\');
	addRpage(\'spellright\');
	addRpage(\'defenseright\');
//-->
</script>
		<form action="' . makelink() . '">
			<select class="statselect_l" name="statbox_left" onchange="doLpage(this.value);">
				<option value="statsleft" selected="selected">' . $this->locale['menustats'] . '</option>
				<option value="meleeleft">' . $this->locale['melee'] . '</option>
				<option value="rangedleft">' . $this->locale['ranged'] . '</option>
				<option value="spellleft">' . $this->locale['spell'] . '</option>
				<option value="defenseleft">' . $this->locale['defense'] . '</option>
			</select>
			<select class="statselect_r" name="statbox_right" onchange="doRpage(this.value);">
				<option value="statsright">' . $this->locale['menustats'] . '</option>
				<option value="meleeright"' . ($rightbox == 'melee'?' selected="selected"':'') . '>' . $this->locale['melee'] . '</option>
				<option value="rangedright"' . ($rightbox == 'ranged'?' selected="selected"':'') . '>' . $this->locale['ranged'] . '</option>
				<option value="spellright"' . ($rightbox == 'spell'?' selected="selected"':'') . '>' . $this->locale['spell'] . '</option>
				<option value="defenseright">' . $this->locale['defense'] . '</option>
			</select>
		</form>
		<div class="padding">
			' . $this->printBox('stats','left',true) . '
			' . $this->printBox('melee','left',false) . '
			' . $this->printBox('ranged','left',false) . '
			' . $this->printBox('spell','left',false) . '
			' . $this->printBox('defense','left',false) . '
			' . $this->printBox('stats','right',false) . '
			' . $this->printBox('melee','right',$rightbox=='melee') . '
			' . $this->printBox('ranged','right',$rightbox=='ranged') . '
			' . $this->printBox('spell','right',$rightbox=='spell') . '
			' . $this->printBox('defense','right',false) . '
		</div>
	</div>
';

			if( $addon['config']['show_tab2'] )
			{
				$output .= '
<!-- Begin tab2 -->
	<div id="tab2" class="tab2" style="display:none;">
		<div class="background">&nbsp;</div>
' . $petTab . "\n	</div>\n";
			}

			if( $addon['config']['show_tab3'] )
			{
				$output .= '
<!-- Begin tab3 -->
	<div id="tab3" class="tab3" style="display:none;">
		<div class="faction">' . $this->locale['faction'] . '</div>
		<div class="standing">' . $this->locale['standing'] . '</div>
		<div class="atwar">' . $this->locale['atwar'] . '</div>

		<div class="container">
' . $this->printReputation() . "\n		</div>\n	</div>\n";
			}

			if( $addon['config']['show_tab4'] )
			{
				$output .= '
<!-- Begin tab4 -->
	<div id="tab4" class="tab4" style="display:none;">
		<div class="container">
' . $this->printSkills() . "\n		</div>\n	</div>\n";
			}

			if( $addon['config']['show_tab5'] )
			{
				$output .= '
<!-- Begin tab5 -->
	<div id="tab5" class="tab5" style="display:none;">
		<div class="background">&nbsp;</div>
' . $this->printHonor() . "\n	</div>\n";
			}

			$output .= '
<!-- Begin Navagation Tabs -->
	<div class="tab_navagation" style="margin:428px 0 0 17px;">
		<ul id="char_navagation">
			<li class="selected"><a rel="tab1" class="text">' . $this->locale['tab1'] . '</a></li>
';
			if( $addon['config']['show_tab2'] && $petTab != '' )
			{
				$output .= '			<li><a rel="tab2" class="text">' . $this->locale['tab2']."</a></li>\n";
			}
			if( $addon['config']['show_tab3'] )
			{
				$output .= '			<li><a rel="tab3" class="text">'.$this->locale['tab3']."</a></li>\n";
			}
			if( $addon['config']['show_tab4'] )
			{
				$output .= '			<li><a rel="tab4" class="text">'.$this->locale['tab4']."</a></li>\n";
			}
			if( $addon['config']['show_tab5'] )
			{
				$output .= '			<li><a rel="tab5" class="text">'.$this->locale['tab5']."</a></li>\n";
			}
			$output .= '
		</ul>
	</div>

<!-- Begin Buff Icons -->
' . $this->show_buffs() . '

</div>

<script type="text/javascript">
	initializetabcontent(\'char_navagation\');
</script>
';
			return $output;

		}
		else
		{
			roster_die('Sorry no data in database for '.$_GET['name'].' of '.$_GET['server'],'Character Not Found');
		}
	}
}


/**
 * Gets one characters data using a member id
 *
 * @param int $member_id
 * @return mixed False on failure
 */
function char_get_one_by_id( $member_id )
{
	global $roster;

	$query = "SELECT a.*, b.*, `c`.`guild_name`, DATE_FORMAT(  DATE_ADD(`a`.`dateupdatedutc`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'update_format' ".
		"FROM `".$roster->db->table('players')."` a, `".$roster->db->table('members')."` b, `".$roster->db->table('guild')."` c " .
		"WHERE `a`.`member_id` = `b`.`member_id` AND `a`.`member_id` = '$member_id' AND `a`.`guild_id` = `c`.`guild_id`;";
	$result = $roster->db->query( $query );
	if( $roster->db->num_rows($result) > 0 )
	{
		$data = $roster->db->fetch( $result );
		return new char( $data );
	}
	else
	{
		return false;
	}
}


/**
 * Gets one characters data using name, server
 *
 * @param string $name
 * @param string $server
 * @return mixed False on failure
 */
function char_get_one( $name, $server )
{
	global $roster;

	$name = $roster->db->escape( $name );
	$server = $roster->db->escape( $server );
	$query = "SELECT `a`.*, `b`.*, `c`.`guild_name`, DATE_FORMAT(  DATE_ADD(`a`.`dateupdatedutc`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'update_format' ".
		"FROM `".$roster->db->table('players')."` a, `".$roster->db->table('members')."` b, `".$roster->db->table('guild')."` c " .
		"WHERE `a`.`member_id` = `b`.`member_id` AND `a`.`name` = '$name' AND `a`.`server` = '$server' AND `a`.`guild_id` = `c`.`guild_id`;";
	$result = $roster->db->query( $query );
	if( $roster->db->num_rows($result) > 0 )
	{
		$data = $roster->db->fetch( $result );
		return new char( $data );
	}
	else
	{
		return false;
	}
}
