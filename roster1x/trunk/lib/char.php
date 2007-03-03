<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

require_once (ROSTER_LIB.'item.php');
require_once (ROSTER_LIB.'bag.php');
require_once (ROSTER_LIB.'skill.php');
require_once (ROSTER_LIB.'reputation.php');
require_once (ROSTER_LIB.'quest.php');
require_once (ROSTER_LIB.'recipes.php');
require_once (ROSTER_LIB.'pvp3.php');

$myBonus = array();
$myTooltip = array();

class char
{
	var $data;
	var $equip;

	function char( $data )
	{
		$this->data = $data;
	}


	function printXP()
	{
		list($current, $max) = explode( ':', $this->data['exp'] );

		$perc = 0;
		if( $max > 0 )
		{
			$perc = round(($current / $max)* 248, 0);
		}
		return $perc;
	}

	function show_pvp2($type, $url, $sort, $start)
	{
		$pvps = pvp_get_many3( $this->data['member_id'],$type, $sort, -1);
		$returnstring .= '<div align="center">';

		if( is_array($pvps) )
		{
			$returnstring .= output_pvp_summary($pvps,$type);

			if( isset( $pvps[0] ) )
			{
				switch ($type)
				{
					case 'BG':
						$returnstring .= output_bglog($this->data['member_id']);
						break;

					case 'PvP':
						$returnstring .= output_pvplog($this->data['member_id']);
						break;

					case 'Duel':
						$returnstring .= output_duellog($this->data['member_id']);
						break;

					default:
						break;
				}
			}

			$returnstring .= '<br />';
			$returnstring .= '<br />';

			$max = sizeof($pvps);
			$sort_part = $sort ? "&amp;s=$sort" : '';

			if ($start > 0)
				$prev = '<a href="'.makelink($url.'&amp;start=0'.$sort_part).'">&lt;&lt;</a>&nbsp;&nbsp;'.'<a href="'.makelink($url.'&amp;start='.($start-50).$sort_part).'">&lt;</a> ';

			if (($start+50) < $max)
			{
				$listing = '<small>['.$start.' - '.($start+50).'] of '.$max.'</small>';
				$next = ' <a href="'.makelink($url.'&amp;start='.($start+50).$sort_part).'">&gt;</a>&nbsp;&nbsp;'.'<a href="'.makelink($url.'&amp;start='.($max-50).$sort_part).'">&gt;&gt;</a>';
			}
			else
				$listing = '<small>['.$start.' - '.($max).'] of '.$max.'</small>';

			$pvps = pvp_get_many3( $this->data['member_id'],$type, $sort, $start);

			if( isset( $pvps[0] ) )
			{
				$returnstring .= border('sgray','start',$prev.'Log '.$listing.$next);
				$returnstring .= output_pvp2($pvps, $url."&amp;start=".$start,$type);
				$returnstring .= border('sgray','end');
			}

			$returnstring .= '<br />';

			if ($start > 0)
				$returnstring .= $prev;

			if (($start+50) < $max)
			{
				$returnstring .= '['.$start.' - '.($start+50).'] of '.$max;
				$returnstring .= $next;
			}
			else
				$returnstring .= '['.$start.' - '.($max).'] of '.$max;

			$returnstring .= '</div><br />';

			return $returnstring;
		}
		else
		{
			return '';
		}
	}


	function show_quests()
	{
		global $wordings, $roster_conf, $questlinks;

		$lang = $this->data['clientLocale'];

		$quests = quest_get_many( $this->data['member_id'],'');
		if( isset( $quests[0] ) )
		{
			$zone = '';
			$returnstring .= border('sgray','start',$wordings[$lang]['questlog'].' ('.count($quests).'/25)').
				'<table class="bodyline" cellspacing="0" cellpadding="0">';

			foreach ($quests as $quest)
			{
				if ($zone != $quest->data['zone'])
				{
					$zone = $quest->data['zone'];
					$returnstring .= '<tr><th colspan="10" class="membersHeaderRight">'.$zone.'</th></tr>';
				}
				$quest_level = $quest->data['quest_level'];
				$char_level = $this->data['level'];
				$font = 'grey';

				if ($quest_level + 9 < $char_level)
				{
					$font = 'grey';
				}
				else if ($quest_level + 2 < $char_level)
				{
					$font = 'green';
				}
				else if ( $quest_level < $char_level+3 )
				{
					$font = 'yellow';
				}
				else
				{
					$font = 'red';
				}

				$name = $quest->data['quest_name'];
				if ($name{0} == '[')
					$name = trim(strstr($name, ' '));

				$returnstring .= '        <tr>
          <td class="membersRow1">';

				$returnstring .= '<span class="'.$font.'">['.$quest_level.'] '.$name.'</span>';

				$quest_tags = '';

				if ($quest->data['quest_tag'])
					$quest_tags[] = $quest->data['quest_tag'];

				if( $quest->data['is_complete'] == 1 )
					$quest_tags[] = 'Complete';
				elseif( $quest->data['is_complete'] == -1 )
					$quest_tags[] = 'Failed';

				if( is_array($quest_tags) )
				{
					foreach( $quest_tags as $quest_tag )
					{
						$returnstring .= ' ('.$quest_tag.')';
					}
				}

				$returnstring .= "</td>\n";

				$returnstring .= '<td class="membersRowRight1 quest_link">';

				$q = 1;
				foreach( $questlinks as $link )
				{
					//if( $roster_conf['questlink_'.$q] )
						$returnstring .= '<a href="'.$link[$lang]['url1'].urlencode(utf8_decode($name)).($link[$lang]['url2'] ? $link[$lang]['url2'].$quest_level : '').($link[$lang]['url3'] ? $link[$lang]['url3'].$quest_level : '').'" target="_blank">'.$link[$lang]['name']."</a>\n";
					$q++;
				}

				$returnstring .= '</td></tr>';
			}
			$returnstring .= '      </table>'.border('sgray','end');
		}
		return $returnstring;
	}


	function show_recipes()
	{
		global $roster_conf, $url, $sort, $wordings, $wowdb;

		$lang = $this->data['clientLocale'];

		$recipes = recipe_get_many( $this->data['member_id'],'', $sort );
		if( isset( $recipes[0] ) )
		{
			$skill_name = '';
			$returnstring = '<span class="headline_1">'.$wordings[$lang]['recipelist'].'</span>'."\n";

			// Get char professions for quick links
			$query = "SELECT `skill_name` FROM `".ROSTER_RECIPESTABLE."` WHERE `member_id` = '" . $this->data['member_id'] . "' GROUP BY `skill_name` ORDER BY `skill_name`";
			$result = $wowdb->query( $query );

			// Set a ank for link to top of page
			$returnstring .= "<a name=\"top\">&nbsp;</a>\n";
			$returnstring .= '<div align="center">';
			$skill_name_divider = '';
			while( $data = $wowdb->fetch_assoc( $result ) )
			{
				$skill_name_header = $data['skill_name'];
				$returnstring .= $skill_name_divider .'<a href="#' . strtolower(str_replace(' ','',$skill_name_header)) . '">' . $skill_name_header . '</a>';
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
						$returnstring .= '</table>'.border('sgray','end')."<br />\n";
					$first_run = 0;

					// Set an link to the top behind the profession image
					$skill_image = 'Interface/Icons/'.$wordings[$this->data['clientLocale']]['ts_iconArray'][$skill_name];
					$skill_image = "<div style=\"display:inline;float:left;\"><img width=\"17\" height=\"17\" src=\"".$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix']."\" alt=\"\" /></div>\n";

					$header = '<div style="cursor:pointer;width:600px;" onclick="showHide(\'table_'.$rc.'\',\'img_'.$rc.'\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');">
	'.$skill_image.'
	<div style="display:inline;float:right;"><img id="img_'.$rc.'" src="'.$roster_conf['img_url'].'plus.gif" alt="" /></div>
<a name="'.strtolower(str_replace(' ','',$skill_name)).'"></a>'.$skill_name.'</div>';


					$returnstring .= border('sgray','start',$header)."\n<table width=\"100%\" style=\"display:none;\" class=\"bodyline\" cellspacing=\"0\" id=\"table_$rc\">\n";

$returnstring .= '  <tr>
    <th class="membersHeader"><a href="'.makelink($url.'&amp;action=recipes&amp;s=item').'">'.$wordings[$lang]['item'].'</a></th>
    <th class="membersHeader"><a href="'.makelink($url.'&amp;action=recipes&amp;s=name').'">'.$wordings[$lang]['name'].'</a></th>
    <th class="membersHeader"><a href="'.makelink($url.'&amp;action=recipes&amp;s=difficulty').'">'.$wordings[$lang]['difficulty'].'</a></th>
    <th class="membersHeader"><a href="'.makelink($url.'&amp;action=recipes&amp;s=type').'">'.$wordings[$lang]['type'].'</a></th>
    <th class="membersHeader"><a href="'.makelink($url.'&amp;action=recipes&amp;s=level').'">'.$wordings[$lang]['level'].'</a></th>
    <th class="membersHeaderRight"><a href="'.makelink($url.'&amp;action=recipes&amp;s=reagents').'">'.$wordings[$lang]['reagents'].'</a></th>
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
    <td class="membersRow'.$stripe.'">';

				$returnstring .= $recipe->out();
				$returnstring .= '</td>
    <td class="membersRow'.$stripe.'"><span style="color:#'.substr( $recipe->data['item_color'], 2, 6 ).'">&nbsp;'.$recipe->data['recipe_name'].'</span></td>
    <td class="membersRow'.$stripe.'"><span style="color:#'.$difficultycolor.'">&nbsp;'.$wordings[$lang]['recipe_'.$recipe->data['difficulty']].'</span></td>
    <td class="membersRow'.$stripe.'">&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>
    <td class="membersRow'.$stripe.'">&nbsp;'.$recipe->data['level'].'&nbsp;</td>
    <td class="membersRowRight'.$stripe.'">&nbsp;'.str_replace('<br>','&nbsp;<br />&nbsp;',$recipe->data['reagents']).'</td>
  </tr>
';
			$rc++;
			}
			$returnstring .= "</table>".border('sgray','end');
		}
		return $returnstring;
	}


	function show_mailbox()
	{
		global $wowdb, $wordings, $roster_conf, $phptimeformat, $itemlink, $tooltips;

		$sqlquery = "SELECT * FROM `".ROSTER_MAILBOXTABLE."` ".
			"WHERE `member_id` = '".$this->data['member_id']."' ".
			"ORDER BY `mailbox_days`;";

		if ($wowdb->sqldebug)
		{
			$content .= "<!-- $sqlquery -->\n";
		}
		$result = $wowdb->query($sqlquery);

		if( !$result )
		{
			return "No ".$wordings[$roster_conf['roster_lang']]['mailbox']." for ".$this->data['name'];
		}

		if( $wowdb->num_rows($result) > 0 )
		{
			//begin generation of mailbox's output
			$content .= border('sgray','start',$wordings[$roster_conf['roster_lang']]['mailbox']).
				'<table cellpadding="0" cellspacing="0" class="bodyline">'."\n";
			$content .= "<tr>\n";
			$content .= '<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['mail_item'].'</th>'."\n";
			$content .= '<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['mail_sender'].'</th>'."\n";
			$content .= '<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['mail_subject'].'</th>'."\n";
			$content .= '<th class="membersHeaderRight">'.$wordings[$roster_conf['roster_lang']]['mail_expires'].'</th>'."\n";
			$content .= "</tr>\n";
			$content .= "<tr>\n";

			$cur_row = 1;
			while( $row = $wowdb->fetch_assoc($result) )
			{
				$maildateutc = strtotime($this->data['maildateutc']);

				$content .= '<td class="membersRow'.$cur_row.'">'."\n";

				$cur_row = (($cur_row + 1) % 1) + 1;

				// Get money in mail
				$money_included = '';
				if( $row['mailbox_coin'] > 0 && $roster_conf['show_money'] )
				{
					$db_money = $row['mailbox_coin'];

					$mail_money['c'] = substr($db_money,-2,2);
					$db_money = substr($db_money,0,-2);
					$money_included = $mail_money['c'].'<img src="'.$roster_conf['img_url'].'bagcoinbronze.gif" alt="c" />';

					if( !empty($db_money) )
					{
						$mail_money['s'] = substr($db_money,-2,2);
						$db_money = substr($db_money,0,-2);
						$money_included = $mail_money['s'].'<img src="'.$roster_conf['img_url'].'bagcoinsilver.gif" alt="s" /> '.$money_included;
					}
					if( !empty($db_money) )
					{
						$mail_money['g'] = $db_money;
						$money_included = $mail_money['g'].'<img src="'.$roster_conf['img_url'].'bagcoingold.gif" alt="g" /> '.$money_included;
					}
				}

				// Fix icon texture
				if( !empty($row['item_icon']) )
				{
					$item_icon = $roster_conf['interface_url'].$row['item_icon'].'.'.$roster_conf['img_suffix'];
				}
				elseif( !empty($money_included) )
				{
					$item_icon = $roster_conf['interface_url'].$row['mailbox_coin_icon'].'.'.$roster_conf['img_suffix'];
				}
				else
				{
					$item_icon = $roster_conf['interface_url'].'Interface/Icons/INV_Misc_Note_02.'.$roster_conf['img_suffix'];
				}


				// Start the tooltips
				$tooltip_h = $row['mailbox_subject'];

				// first line is sender
				$tooltip = $wordings[$this->data['clientLocale']]['mail_sender'].
					': '.$row['mailbox_sender'].'<br />';

				$expires_line = date($phptimeformat[$this->data['clientLocale']],((($row['mailbox_days']*24 + $roster_conf['localtimeoffset'])*3600)+$maildateutc)).' '.$roster_conf['timezone'];
				if( (($row['mailbox_days']*24*3600)+$maildateutc) - time() < (3*24*3600) )
					$color = 'ff0000;';
				else
					$color = 'ffffff;';

				$tooltip .= $wordings[$this->data['clientLocale']]['mail_expires'].": <span style=\"color:#$color\">$expires_line</span><br />";

				// Join money with main tooltip
				if( !empty($money_included) )
				{
					$tooltip .= $wordings[$this->data['clientLocale']]['mail_money'].': '.$money_included;
				}


				// Get item tooltip
				$item_tooltip = colorTooltip($row['item_tooltip'],$row['item_color'],$this->data['clientLocale']);


				// If the tip has no info, at least get the item name in there
				if( $item_tooltip != '<br />' )
					$item_tooltip = '<hr />'.$item_tooltip;


				// Join item tooltip with main tooltip
				$tooltip .= $item_tooltip;

				if ($tooltip == '')
				{
					if ($row['item_name'] != '')
					{
						$tooltip = $row['item_name'];
					}
					else
					{
						$tooltip = "No information";
					}
				}

				$tooltip = makeOverlib($tooltip,$tooltip_h,'',2,$this->data['clientLocale']);

				// Item links
				$num_of_tips = (count($tooltips)+1);
				$linktip = '';
				foreach( $itemlink[$this->data['clientLocale']] as $ikey => $ilink )
				{
					$linktip .= '<a href="'.$ilink.urlencode(utf8_decode($row['item_name'])).'" target="_blank">'.$ikey.'</a><br />';
				}
				setTooltip($num_of_tips,$linktip);
				setTooltip('itemlink',$wordings[$this->data['clientLocale']]['itemlink']);

				$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';


				$content .= '<div class="item" style="cursor:pointer;" '.$tooltip.$linktip.'>';

				$content .= '<img src="'.$item_icon.'"'." alt=\"\" />\n";

				if( ($row['item_quantity'] > 1) )
					$content .= '<span class="quant">'.$row['item_quantity'].'</span>';
				$content .= "</div>\n</td>\n";

				$content .= '<td class="membersRow'.$cur_row.'">'.$row['mailbox_sender'].'</td>'."\n";
				$content .= '<td class="membersRow'.$cur_row.'">'.$row['mailbox_subject'].'</td>'."\n";
				$content .= '<td class="membersRowRight'.$cur_row.'">'.$expires_line.'</td>'."\n";

				$content .= "</tr>\n";
			}

			$content .= "</tr>\n</table>\n".border('sgray','end');

			return $content;
		}
		else
		{
			return "No ".$wordings[$roster_conf['roster_lang']]['mailbox']." for ".$this->data['name'];
		}
	}



	function show_spellbook()
	{
		global $wowdb, $wordings, $roster_conf;

		$query = "SELECT `spelltree`.*, `talenttree`.`order` ".
			"FROM `".ROSTER_SPELLTREETABLE."` AS spelltree ".
			"LEFT JOIN `".ROSTER_TALENTTREETABLE."` AS talenttree ON `spelltree`.`member_id` = `talenttree`.`member_id` AND `spelltree`.`spell_type` = `talenttree`.`tree` ".
			"WHERE `spelltree` . `member_id` = ".$this->data['member_id']." ".
			"ORDER BY `talenttree` . `order` ASC";

		$result = $wowdb->query($query);

		if( !$result )
		{
			return "No ".$wordings[$roster_conf['roster_lang']]['spellbook']." for ".$this->data['name'];
		}

		$num_trees = $wowdb->num_rows($result);

		if( $num_trees == 0 )
		{
			return "No ".$wordings[$roster_conf['roster_lang']]['spellbook']." for ".$this->data['name'];
		}

		for( $t=0; $t < $num_trees; $t++)
		{
			$treedata = $wowdb->fetch_assoc($result);

			$spelltree[$t]['name'] = $treedata['spell_type'];
			$spelltree[$t]['icon'] = $treedata['spell_texture'];
			$spelltree[$t]['id'] = $t;

			$name_id[$treedata['spell_type']] = $t;
		}

		$wowdb->free_result($result);

		// Get the spell data
		$query = "SELECT * FROM `".ROSTER_SPELLTABLE."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `spell_name`";

		$result = $wowdb->query($query);

		while ($row = $wowdb->fetch_assoc($result))
		{
			$spelltree[$name_id[$row['spell_type']]]['rawspells'][] = $row;
		}

		foreach ($spelltree as $t => $tree)
		{
			$i=0;
			$p=0;
			foreach ($spelltree[$t]['rawspells'] as $r => $spell)
			{
				if( $i >= 14 )
				{
					$i=0;
					$p++;
				}
				$spelltree[$t]['spells'][$p][$i]['name'] = $spell['spell_name'];
				$spelltree[$t]['spells'][$p][$i]['type'] = $spell['spell_type'];
				$spelltree[$t]['spells'][$p][$i]['icon'] = $spell['spell_texture'];
				$spelltree[$t]['spells'][$p][$i]['rank'] = $spell['spell_rank'];

				// Parse the tooltip
				$spelltree[$t]['spells'][$p][$i]['tooltip'] = makeOverlib($spell['spell_tooltip'],'','',0,$this->data['clientLocale'],',RIGHT');

				$i++;
			}
		}

		$return_string .= '
<div class="spell_panel">
	<div class="spell_panel_name">'.$wordings[$roster_conf['roster_lang']]['spellbook'].'</div>

	<!-- Skill Type Icons Menu -->
	<div class="spell_skill_tab_bar">
';

		foreach( $spelltree as $tree )
		{
			$treetip = makeOverlib($tree['name'],'','',2,'',',WRAP,RIGHT');
			$return_string .= '
		<div class="spell_skill_tab">
			<img class="spell_skill_tab_icon" src="'.$roster_conf['interface_url'].$tree['icon'].'.'.$roster_conf['img_suffix'].'" '.$treetip.' alt="" onclick="showSpellTree(\'spelltree_'.$tree['id'].'\');" />
		</div>'."\n";
		}
		$return_string .= "	</div>\n";


		foreach( $spelltree as $tree )
		{
			if( $tree['id'] == 0 )
			{
				$return_string .= '	<div id="spelltree_'.$tree['id'].'">'."\n";
			}
			else
			{
				$return_string .= '	<div id="spelltree_'.$tree['id'].'" style="display:none;">'."\n";
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
						$return_string .= '		<div id="page_'.$page.'_'.$tree['id'].'">'."\n";
						$first_page = false;
					}
					else
					{
						$return_string .= '		<div id="page_'.$page.'_'.$tree['id'].'">'."\n";
						$return_string .= '			<div class="spell_page_forward" onclick="show(\'page_'.($page+1).'_'.$tree['id'].'\');hide(\'page_'.$page.'_'.$tree['id'].'\');">'.$wordings[$roster_conf['roster_lang']]['next'].' <img src="'.$roster_conf['img_url'].'spellbook/pageforward.gif" class="navicon" alt="" /></div>'."\n";
						$first_page = false;
					}
				}
				elseif( ($num_pages-1) == $page )
				{
					$return_string .= '		<div id="page_'.$page.'_'.$tree['id'].'" style="display:none;">'."\n";
					$return_string .= '			<div class="spell_page_back" onclick="show(\'page_'.($page-1).'_'.$tree['id'].'\');hide(\'page_'.$page.'_'.$tree['id'].'\');"><img src="'.$roster_conf['img_url'].'spellbook/pageback.gif" class="navicon" alt="" /> '.$wordings[$roster_conf['roster_lang']]['prev'].'</div>'."\n";
				}
				else
				{
					$return_string .= '		<div id="page_'.$page.'_'.$tree['id'].'" style="display:none;">'."\n";
					$return_string .= '			<div class="spell_page_back" onclick="show(\'page_'.($page-1).'_'.$tree['id'].'\');hide(\'page_'.$page.'_'.$tree['id'].'\');"><img src="'.$roster_conf['img_url'].'spellbook/pageback.gif" class="navicon" alt="" /> '.$wordings[$roster_conf['roster_lang']]['prev'].'</div>'."\n";
					$return_string .= '			<div class="spell_page_forward" onclick="show(\'page_'.($page+1).'_'.$tree['id'].'\');hide(\'page_'.$page.'_'.$tree['id'].'\');">'.$wordings[$roster_conf['roster_lang']]['next'].' <img src="'.$roster_conf['img_url'].'spellbook/pageforward.gif" class="navicon" alt="" /></div>'."\n";
				}
				$return_string .= '			<div class="spell_pagenumber">Page '.($page+1).'</div>'."\n";


				$icon_num = 0;
				foreach( $spellpage as $spellicons )
				{
					if( $icon_num == 0 )
					{
						$return_string .= '			<div class="spell_container_1">'."\n";
					}
					elseif( $icon_num == 7 )
					{
						$return_string .= "			</div>\n			<div class=\"spell_container_2\">\n";
					}
					$return_string .= '
				<div class="spell_info_container">
					<img src="'.$roster_conf['interface_url'].$spellicons['icon'].'.'.$roster_conf['img_suffix'].'" class="icon" '.$spellicons['tooltip'].' onmouseout="return nd();" alt="" />
					<span class="text"><span class="spellYellow">'.$spellicons['name'].'</span>';
					if( $spellicons['rank'] != '' )
					{
						$return_string .= '<br /><span class="spellBrown">'.$spellicons['rank'].'</span>';
					}
					$return_string .= "</span>\n				</div>\n";
					$icon_num++;
				}
				$return_string .= "			</div>\n		</div>\n";

				$page++;
			}
			$return_string .= "	</div>\n";
		}
		$return_string .= "</div>\n";

		return $return_string;
	}


	function get( $field )
	{
		return $this->data[$field];
	}


	function getNumPets()
	{
		global $wowdb; 				//the object derived from class wowdb used to do queries

		$query = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '".$this->data['member_id']."' order by `level` DESC";
		$result = $wowdb->query( $query );
		$retval = $wowdb->num_rows($result);
		$wowdb->free_result($result);

		return $retval;

	}


	function printPet()
	{
		global $wowdb, $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		$member_id = $this->data['member_id'];
		$query = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$member_id' ORDER BY `level` DESC";
		$result = $wowdb->query( $query );

		$petNum = 1;
		while ($row = $wowdb->fetch_assoc($result))
		{
			$showxpBar = true;
			if ( strlen($row['xp']) < 1 )
				$showxpBar = false;

			list($xpearned, $totalxp) = split(":",$row['xp']);
			if ($totalxp == 0)
				$xp_percent = .00;
			else
				$xp_percent = $xpearned / $totalxp;

			$barpixelwidth = floor(381 * $xp_percent);
			$xp_percent_word = floor($xp_percent * 100).'%';
			$unusedtp = $row['totaltp'] - $row['usedtp'];

			if( $row['level'] == ROSTER_MAXCHARLEVEL )
				$showxpBar = false;

			$tmp = split(':',$row['stat_str']);
			$str = $tmp[0];
			$tmp = split(':',$row['stat_agl']);
			$agi = $tmp[0];
			$tmp = split(':',$row['stat_sta']);
			$sta = $tmp[0];
			$tmp = split(':',$row['stat_int']);
			$int = $tmp[0];
			$tmp = split(':',$row['stat_spr']);
			$spr = $tmp[0];
			$tmp = split(':',$row['armor']);
			$basearmor = $tmp[0];

			$left = 35+(($petNum-1)*50);
			$top = 285;

			// Start Warlock Pet Icon Mod
			$imp = 'Interface/Icons/Spell_Shadow_SummonImp';
			$void = 'Interface/Icons/Spell_Shadow_SummonVoidWalker';
			$suc = 'Interface/Icons/Spell_Shadow_SummonSuccubus';
			$fel = 'Interface/Icons/Spell_Shadow_SummonFelHunter';
			$inferno = 'Interface/Icons/Spell_Shadow_SummonInfernal';
			$felguard = 'Interface/Icons/Spell_Shadow_SummonFelGuard';

			$iconStyle='cursor:pointer;position:absolute;left:'.$left.'px;top:'.$top.'px;height:40px;width:40px;';

			if ($row['type'] == $wordings[$lang]['Imp'])
				$row['icon'] = $imp;

			if ($row['type'] == $wordings[$lang]['Voidwalker'])
				$row['icon'] = $void;

			if ($row['type'] == $wordings[$lang]['Succubus'])
				$row['icon'] = $suc;

			if ($row['type'] == $wordings[$lang]['Felhunter'])
				$row['icon'] = $fel;

			if ($row['type'] == $wordings[$lang]['Felguard'])
				$row['icon'] = $felguard;

			if ($row['type'] == $wordings[$lang]['Infernal'])
				$row['icon'] = $inferno;
			// End Warlock Pet Icon Mod

			if ($row['icon'] == '' || !isset($row['icon']))
			{
				$row['icon'] = 'Interface/Icons/INV_Misc_QuestionMark';
			}

			$icons			.= '<img src="'.$roster_conf['interface_url'].$row['icon'].'.'.$roster_conf['img_suffix'].'" onclick="showPet(\''.$petNum.'\')" style="'.$iconStyle.'" alt="" '.makeOverlib($row['name'],$row['type'],'',2,'',',WRAP').' />';
			$petName		.= '<span class="petName" style="top: 10px; left: 95px; display: none;" id="pet_name'.$petNum.'">' . stripslashes($row['name']).'</span>';
			$petTitle		.= '<span class="petName" style="top: 30px; left: 95px; display: none;" id="pet_title'.$petNum.'">'.$wordings[$lang]['level'].' '.$row['level'].' ' . stripslashes($row['type']).'</span>';
			$loyalty		.= '<span class="petName" style="top: 50px; left: 95px; display: none;" id="pet_loyalty'.$petNum.'">'.$row['loyalty'].'</span>';
			$petIcon		.= '<img id="pet_top_icon'.$petNum.'" style="position: absolute; left: 30px; top: 8px; width: 64px; height: 64px; display: none;" src="'.$roster_conf['interface_url'].$row['icon'].'.'.$roster_conf['img_suffix'].'" alt="" />';
			$resistances	.= '<div  class="pet_resistance" id="pet_resistances'.$petNum.'">
				<ul>
					<li class="pet_fire"><span class="white">'.$row['res_fire'].'</span></li>
					<li class="pet_nature"><span class="white">'.$row['res_nature'].'</span></li>
					<li class="pet_arcane"><span class="white">'.$row['res_arcane'].'</span></li>
					<li class="pet_frost"><span class="white">'.$row['res_frost'].'</span></li>
					<li class="pet_shadow"><span class="white">'.$row['res_shadow'].'</span></li>
				</ul>
			</div>';
			$stats			.= '
			<div class="petStatsBg" id="pet_stats_table'.$petNum.'" >
					<table style="text-align: left; position: absolute; top: 5px; left: 5px;" border="0" cellpadding="2" cellspacing="0" width="130">
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['strength'].':</td>
							<td class="petStatsTableStatValue">'.$str.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['agility'].':</td>
							<td class="petStatsTableStatValue">'.$agi.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['stamina'].':</td>
							<td class="petStatsTableStatValue">'.$sta.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['intellect'].':</td>
							<td class="petStatsTableStatValue">'.$int.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['spirit'].':</td>
							<td class="petStatsTableStatValue">'.$spr.'</td>
						</tr>
					</table>

					<table style="text-align: left;	position: absolute;	top: 5px; left: 146px;" border="0" cellpadding="2" cellspacing="0" width="130">
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['attack'].':</td>
							<td class="petStatsTableStatValue">'.$row['melee_rating'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['power'].':</td>
							<td class="petStatsTableStatValue">'.$row['melee_power'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['damage'].':</td>
							<td class="petStatsTableStatValue">'.str_replace(':',' - ',$row['melee_range']).'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['defense'].':</td>
							<td class="petStatsTableStatValue">'.$row['defense'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['armor'].':</td>
							<td class="petStatsTableStatValue">'.$basearmor.'</td>
						</tr>
					</table>
			</div>';

			if ($showxpBar)
				$xpBar			.= '
				<div class="pet_xp" id="pet_xp_bar'.$petNum.'">
		            <div class="pet_xpbox">
		                <img class="xp_bg" width="100%" height="15" src="'.$roster_conf['img_url'].'barxpempty.gif" alt="" />
		                <img src="'.$roster_conf['img_url'].'expbar-var2.gif" alt="" class="pet_bit" width="'.$barpixelwidth.'" />
		                <span class="pet_xp_level">'.$xpearned.'/'.$totalxp.' ( '.$xp_percent_word.' )</span>
		            </div>
		        </div>';


			if( $row['totaltp'] != '' && $row['totaltp'] != '0' )
			{
				$trainingPoints .= '
			<span class="petTrainingPts" style="position: absolute; top: 412px; left: 100px;" id="pet_training_nm'.$petNum.'">'.$wordings[$lang]['unusedtrainingpoints'].': </span>
			<span class="petTrainingPts" style="color: #FFFFFF; position: absolute; top: 413px; left: 305px;" id="pet_training_pts'.$petNum.'" >'.$unusedtp.' / '.$row['totaltp'].'</span>';
			}

			$hpMana	.= '
			<div id="pet_hpmana'.$petNum.'" class="health_mana" style="position: absolute;	left: 35px; top: 65px; display: none;">
				<div class="health" style="text-align: left;">
					'.$wordings[$lang]['health'].': <span class="white">'.$row['health'].'</span>
				</div>
		        <div class="mana" style="text-align: left;">
		        	'.$wordings[$lang]['mana'].': <span class="white">'.$row['mana'].'</span>
		        </div>
			</div>';

			$petNum++;
		}


		//return all the objects
		return
		$petName
		.$petTitle
		.$loyalty
		.$petIcon
		.$resistances
		.$stats
		.$xpBar
		.$trainingPoints
		.$hpMana
		.$icons
		;
	}

	function printStatLine( $label, $value, $tooltip)
	{
		$output  = '  <tr '.makeOverlib($tooltip,'','',2).'>'."\n";
		$output .= '    <td class="label">'.$label.':</td>'."\n";
		$output .= '    <td class="value">'.$value.'</td>'."\n";
		$output .- '  </tr>'."\n";

		return $output;
	}
	
	function printRatingShort( $statname )
	{
		$base = $this->data[$statname];
		$current = $this->data[$statname.'_c'];
		$buff = $this->data[$statname.'_b'];
		$debuff = -$this->data[$statname.'_d'];

		if( $buff>0 && $debuff>0 )
		{
			$color = "purple";
		}
		elseif( $buff>0 )
		{
			$color = "green";
		}
		elseif( $debuff>0 )
		{
			$color = "red";
		}
		else
		{
			$color = "white";
		}
		
		return '<strong class="'.$color.'">'.$current.'</strong>';
	}
		
	function printRatingLong( $statname )
	{
		$base = $this->data[$statname];
		$current = $this->data[$statname.'_c'];
		$buff = $this->data[$statname.'_b'];
		$debuff = -$this->data[$statname.'_d'];

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
	
	function printBox( $cat, $side, $visible)
	{
		print '<table class="stats" id="'.$cat.$side.'" style="display:'.($visible?'block':'none').'">'."\n";
		switch($cat)
		{
			case 'stats':
				print $this->printStat('stat_str');
				print $this->printStat('stat_agl');
				print $this->printStat('stat_sta');
				print $this->printStat('stat_int');
				print $this->printStat('stat_spr');
				print $this->printStat('stat_armor');
				break;
			case 'melee':
				print $this->printWSkill('melee');
				print $this->printWDamage('melee');
				print $this->printWSpeed('melee');
				print $this->printStat('melee_power');
				print $this->printStat('melee_hit');
				print $this->printStat('melee_crit');
				break;
			case 'ranged':
				print $this->printWSkill('ranged');
				print $this->printWDamage('ranged');
				print $this->printWSpeed('ranged');
				print $this->printStat('ranged_power');
				print $this->printStat('ranged_hit');
				print $this->printStat('ranged_crit');
				break;
			case 'spell':
				print $this->printSpellDamage();
				print $this->printValue('spell_healing');
				print $this->printStat('spell_hit');
				print $this->printSpellCrit();
				print $this->printValue('spell_penetration');
				print $this->printValue('mana_regen_value');
				break;
			case 'defense':
				print $this->printStat('stat_armor');
				print $this->printDefense();
				print $this->printDef('dodge');
				print $this->printDef('parry');
				print $this->printDef('block');
				print $this->printResilience();
				break;
		}
		print '</table>'."\n";
	}
	
	function printStat( $statname )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$base = $this->data[$statname];
		$current = $this->data[$statname.'_c'];
		$buff = $this->data[$statname.'_b'];
		$debuff = -$this->data[$statname.'_d'];

		switch( $statname )
		{
			case 'stat_str':
				$name = $wordings[$lang]['strength'];
				$tooltip = $wordings[$lang]['strength_tooltip'];
				break;
			case 'stat_int':
				$name = $wordings[$lang]['intellect'];
				$tooltip = $wordings[$lang]['intellect_tooltip'];
				break;
			case 'stat_sta':
				$name = $wordings[$lang]['stamina'];
				$tooltip = $wordings[$lang]['stamina_tooltip'];
				break;
			case 'stat_spr':
				$name = $wordings[$lang]['spirit'];
				$tooltip = $wordings[$lang]['spirit_tooltip'];
				break;
			case 'stat_agl':
				$name = $wordings[$lang]['agility'];
				$tooltip = $wordings[$lang]['agility_tooltip'];
				break;
			case 'stat_armor':
				$name = $wordings[$lang]['armor'];
				$tooltip = $wordings[$lang]['armor_tooltip'];
				if( !empty($this->data['mitigation']) )
					$tooltip .= '<br /><span class="red">'.$wordings[$lang]['tooltip_damage_reduction'].': '.$this->data['mitigation'].'%</span>';
				break;
			case 'melee_power':
				$lname = $wordings[$lang]['melee_att_power'];
				$name = $wordings[$lang]['power'];
				$tooltip = sprintf($wordings[$lang]['melee_att_power_tooltip'], $data['melee_power_dps']);
				break;
			case 'melee_hit':
				$name = $wordings[$lang]['weapon_hit_rating'];
				$tooltip = $wordings[$lang]['weapon_hit_rating_tooltip'];
				break;
			case 'melee_crit':
				$name = $wordings[$lang]['weapon_crit_rating'];
				$tooltip = sprintf($wordings[$lang]['weapon_crit_rating_tooltip'], $data['melee_crit_chance']);
				break;
			case 'ranged_power':
				$lname = $wordings[$lang]['ranged_att_power'];
				$name = $wordings[$lang]['power'];
				$tooltip = sprintf($wordings[$lang]['ranged_att_power_tooltip'], $data['ranged_power_dps']);
				break;
			case 'ranged_hit':
				$name = $wordings[$lang]['weapon_hit_rating'];
				$tooltip = $wordings[$lang]['weapon_hit_rating_tooltip'];
				break;
			case 'ranged_crit':
				$name = $wordings[$lang]['weapon_crit_rating'];
				$tooltip = sprintf($wordings[$lang]['weapon_crit_rating_tooltip'], $data['ranged_crit_chance']);
				break;
			case 'spell_hit':
				$name = $wordings[$lang]['spell_hit_rating'];
				$tooltip = $wordings[$lang]['spell_hit_rating_tooltip'];
				break;
		}
		
		if( isset($lname) )
			$tooltipheader = $lname.' '.$this->printRatingLong($statname);
		else
			$tooltipheader = $name.' '.$this->printRatingLong($statname);
		
		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $this->printRatingShort($statname), $line);
	}
	
	function printValue( $statname )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];
		$value = $this->data[$statname];
		switch( $statname )
		{
			case 'spell_penetration':
				$name = $wordings[$lang]['spell_penetration'];
				$tooltip = $wordings[$lang]['spell_penetration_tooltip'];
				break;
			case 'mana_regen_value':
				$name = $wordings[$lang]['mana_regen'];
				$tooltip = sprintf($wordings[$lang]['mana_regen_tooltip'],$this->data['mana_regen_value'],$this->data['mana_regen_time']);
				break;
			case 'spell_healing':
				$name = $wordings[$lang]['spell_healing'];
				$tooltip = sprintf($wordings[$lang]['spell_healing_tooltip'],$this->data['spell_healing']);
				break;
		}
		
		if( isset($lname) )
			$tooltipheader = $lname;
		else
			$tooltipheader = $name;
		
		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'</strong>', $line);
	}

	function printWSkill ( $location )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		if( $location == 'ranged' )
		{
			$value = '<strong class="white">'.$this->data['ranged_skill'].'</strong>';
			$name = $wordings[$lang]['weapon_skill'];
			$tooltipheader = $wordings[$lang]['ranged'];
			$tooltip = sprintf($wordings[$lang]['weapon_skill_tooltip'], $this->data['ranged_skill'], $this->data['ranged_rating']);

			$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line = '<span style="color:#DFB801;">'.$tooltip.'</span>';
		}
		else
		{
			$value = '<strong class="white">'.$this->data['melee_mhand_skill'].'</strong>';
			$name = $wordings[$lang]['weapon_skill'];
			$tooltipheader = $wordings[$lang]['mainhand'];
			$tooltip = sprintf($wordings[$lang]['weapon_skill_tooltip'], $this->data['melee_mhand_skill'], $this->data['melee_mhand_rating']);

			$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
			
			if( $this->data['melee_ohand_dps'] > 0 )
			{
				$value .= '/'.'<strong class="white">'.$this->data['melee_ohand_skill'].'</strong>';
				$tooltipheader = $wordings[$lang]['offhand'];
				$tooltip = sprintf($wordings[$lang]['weapon_skill_tooltip'], $this->data['melee_ohand_skill'], $this->data['melee_ohand_rating']);

				$line .= '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
				$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
			}
		}

		return $this->printStatLine($name, $value, $line);
	}
	
	function printWDamage ( $location )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		if( $location == 'ranged' )
		{
			$value = '<strong class="white">'.$this->data['ranged_mindam'].'</strong>'.'-'.'<strong class="white">'.$this->data['ranged_maxdam'].'</strong>';
			$name = $wordings[$lang]['damage'];
			$tooltipheader = $wordings[$lang]['ranged'];
			$tooltip = sprintf($wordings[$lang]['damage_tooltip'], $this->data['ranged_speed'], $this->data['ranged_mindam'], $this->data['ranged_maxdam'], $this->data['ranged_dps']);

			$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line = '<span style="color:#DFB801;">'.$tooltip.'</span>';
		}
		else
		{
			$value = '<strong class="white">'.$this->data['melee_mhand_mindam'].'</strong>'.'-'.'<strong class="white">'.$this->data['melee_mhand_maxdam'].'</strong>';
			$name = $wordings[$lang]['damage'];
			$tooltipheader = $wordings[$lang]['mainhand'];
			$tooltip = sprintf($wordings[$lang]['damage_tooltip'], $this->data['melee_mhand_speed'], $this->data['melee_mhand_mindam'], $this->data['melee_mhand_maxdam'], $this->data['melee_mhand_dps']);

			$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
			
			if( $this->data['melee_ohand_dps'] > 0 )
			{
				$value .= '/'.'<strong class="white">'.$this->data['melee_ohand_mindam'].'</strong>'.'-'.'<strong class="white">'.$this->data['melee_ohand_maxdam'].'</strong>';
				$tooltipheader = $wordings[$lang]['offhand'];
				$tooltip = sprintf($wordings[$lang]['damage_tooltip'], $this->data['melee_ohand_speed'], $this->data['melee_ohand_mindam'], $this->data['melee_ohand_maxdam'], $this->data['melee_ohand_dps']);

				$line .= '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
				$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
			}
		}
		

		return $this->printStatLine($name, $value, $line);
	}

	function printWSpeed ( $location )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		if( $location == 'ranged' )
		{
			$value = '<strong class="white">'.$this->data['ranged_speed'].'</strong>';
			$name = $wordings[$lang]['speed'];
			$tooltipheader = $wordings[$lang]['atk_speed'].' '.$value;
			$tooltip = $wordings[$lang]['haste_tooltip'].$this->printRatingLong('ranged_haste');

			$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
		}
		else
		{
			$value = '<strong class="white">'.$this->data['melee_mhand_speed'].'</strong>';
			$name = $wordings[$lang]['speed'];
			
			if( $this->data['melee_ohand_dps'] > 0 )
			{
				$value .= '/'.'<strong class="white">'.$this->data['melee_ohand_speed'].'</strong>';
			}
			
			$tooltipheader = $wordings[$lang]['atk_speed'].' '.$value;
			$tooltip = $wordings[$lang]['haste_tooltip'].$this->printRatingLong('melee_haste');

			$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
		}

		return $this->printStatLine($name, $value, $line);
	}

	function printSpellDamage()
	{
		global $wordings, $roster_conf;
		$lang = $this->data['clientLocale'];

		$name = $wordings[$lang]['spell_damage'];
		$value = '<strong class="white">'.$this->data['spell_damage'].'</strong>';
		
		$tooltipheader = $name.' '.$value;
		$tooltip = '<table>';
//		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-holy.gif" alt=""><td>'.$wordings[$lang]['holy'].'<td>';
		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-fire.gif" alt=""><td>'.$wordings[$lang]['fire'].'<td>'.$this->data['spell_damage_fire'];
		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-nature.gif" alt=""><td>'.$wordings[$lang]['nature'].'<td>'.$this->data['spell_damage_nature'];
		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-frost.gif" alt=""><td>'.$wordings[$lang]['frost'].'<td>'.$this->data['spell_damage_frost'];
		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-shadow.gif" alt=""><td>'.$wordings[$lang]['shadow'].'<td>'.$this->data['spell_damage_shadow'];
		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-arcane.gif" alt=""><td>'.$wordings[$lang]['arcane'].'<td>'.$this->data['spell_damage_arcane'];
		$tooltip .= '</table>';

		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $value, $line);
	}

	function printSpellCrit()
	{
		global $wordings, $roster_conf;
		$lang = $this->data['clientLocale'];

		$name = $wordings[$lang]['spell_crit_rating'];
		$value = $this->printRatingShort('spell_crit');
		
		$tooltipheader = $name.' '.$this->printRatingLong('spell_crit');
		$tooltip = $wordings[$lang]['spell_crit_chance'].' '.$this->data['spell_crit_chance'];
//		$tooltip = '<table>';
//		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-holy.gif" alt=""><td>'.$wordings[$lang]['holy'].'<td>';
//		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-fire.gif" alt=""><td>'.$wordings[$lang]['fire'].'<td>'.$this->data['spell_damage_fire'];
//		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-nature.gif" alt=""><td>'.$wordings[$lang]['nature'].'<td>'.$this->data['spell_damage_nature'];
//		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-frost.gif" alt=""><td>'.$wordings[$lang]['frost'].'<td>'.$this->data['spell_damage_frost'];
//		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-shadow.gif" alt=""><td>'.$wordings[$lang]['shadow'].'<td>'.$this->data['spell_damage_shadow'];
//		$tooltip .= '<tr><td><img src="'.$roster_conf['img_url'].'icon-arcane.gif" alt=""><td>'.$wordings[$lang]['arcane'].'<td>'.$this->data['spell_damage_arcane'];
//		$tooltip .= '</table>';

		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $value, $line);
	}
	
	function printDefense()
	{
		global $wordings, $roster_conf, $wowdb;
		$lang = $this->data['clientLocale'];
	
		$qry = "SELECT `skill_level` FROM `roster_skills` WHERE `member_id` = ".$this->data['member_id']." AND `skill_name` = '".$wordings[$lang]['defense']."'";
		$result = $wowdb->query($qry);
		if( !$result )
		{
			$value = 'N/A';
		}
		else
		{
			$row = $wowdb->fetch_row($result);
			$value = explode(':',$row[0]);
			$value = $value[0];
			$wowdb->free_result($result);
			unset($row);
		}
		
		$name = $wordings[$lang]['defense'];
		$tooltipheader = $name.' '.$value;
		
		$tooltip = $wordings[$lang]['defense_rating'].$this->printRatingLong('stat_defr');
		
		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'</strong>', $line);
	}
	
	function printDef( $statname )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$base = $this->data['stat_'.$statname];
		$current = $this->data['stat_'.$statname.'_c'];
		$buff = $this->data['stat_'.$statname.'_b'];
		$debuff = -$this->data['stat_'.$statname.'_d'];
		
		$name = $wordings[$lang][$statname];
		$value = $this->data[$statname];

		$tooltipheader = $name.' '.$this->printRatingLong('stat_'.$statname);
		$tooltip = sprintf($wordings[$lang]['def_tooltip'],$name);
		
		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'%</strong>', $line);
	}
	
	function printResilience()
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$name = $wordings[$lang]['resilience'];
		$value = min($this->data['stat_res_melee'],$this->data['stat_res_ranged'],$this->data['stat_res_spell']);
		
		$tooltipheader = $name.' '.$this->printRatingLong('stat_'.$statname);
		$tooltip = '<table>';
		$tooltip .= '<tr><td>'.$wordings[$lang]['melee'].'<td>'.$this->data['stat_res_melee'];
		$tooltip .= '<tr><td>'.$wordings[$lang]['ranged'].'<td>'.$this->data['stat_res_ranged'];
		$tooltip .= '<tr><td>'.$wordings[$lang]['spell'].'<td>'.$this->data['stat_res_spell'];
		$tooltip .= '</table>';
		
		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'%</strong>', $line);
	}
	
	function printRes ( $resname )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		switch($resname)
		{
		case 'res_fire':
			$name = $wordings[$lang]['res_fire'];
			$tooltip = $wordings[$lang]['res_fire_tooltip'];
			$color = 'red';
			break;
		case 'res_nature':
			$name = $wordings[$lang]['res_nature'];
			$tooltip = $wordings[$lang]['res_nature_tooltip'];
			$color = 'green';
			break;
		case 'res_arcane':
			$name = $wordings[$lang]['res_arcane'];
			$tooltip = $wordings[$lang]['res_arcane_tooltip'];
			$color = 'yellow';
			break;
		case 'res_frost':
			$name = $wordings[$lang]['res_frost'];
			$tooltip = $wordings[$lang]['res_frost_tooltip'];
			$color = 'blue';
			break;
		case 'res_shadow':
			$name = $wordings[$lang]['res_shadow'];
			$tooltip = $wordings[$lang]['res_shadow_tooltip'];
			$color = 'purple';
			break;
		}

		$tooltipheader = $name;
		$line = '<span style="color:'.$color.';font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;text-align:left;">'.$tooltip.'</span>';

		$output .= '<li class="'.substr($resname,4).'" '.makeOverlib($line,'','',2,'',',WRAP').'>';

		$output .= $this->data[$resname.'_c'];
		$output .= "</li>\n";

		return $output;
	}


	function fetchEquip()
	{
		if (!is_array($this->equip))
		{
			$this->equip = item_get_many($this->data['member_id'], 'equip');
		}
	}


	function printEquip( $slot )
	{
		global $roster_conf, $wordings;

		$item = $this->equip[$slot];

		if( isset($item) )
		{
			$output = $item->out();
		}
		else
		{
			$output = '<div class="item" '.makeOverlib($wordings[$roster_conf['roster_lang']]['empty_equip'],$slot,'',2,'',',WRAP').'>'."\n";
			if ($slot == 'Ammo')
				$output .= '<img src="'.$roster_conf['interface_url'].'Interface/EmptyEquip/'.$slot.'.gif" class="iconsmall" alt="" />'."\n";
			else
				$output .= '<img src="'.$roster_conf['interface_url'].'Interface/EmptyEquip/'.$slot.'.gif" class="icon" alt="" />'."\n";

			$output .= "</div>\n";
		}
		return $output;
	}


	function printAtk( $type, $stat )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		switch($stat)
		{
			case 'rating':
				if( $type == 'ranged' )
					$atk = $this->data['ranged_skill'];
				elseif( (int)$this->data['melee_ohand_skill'] != 0 )
					$atk = $this->data['melee_mhand_skill'].'/'.$this->data['melee_ohand_skill'];
				else
					$atk = $this->data['melee_mhand_skill'];
				break;
			case 'power':
				$atk = $this->data[$type.'_power_c'];
				break;
			case 'range':
				if( $type == 'ranged' )
					$atk = $this->data['ranged_mindam'].'-'.$this->data['ranged_maxdam'];
				elseif( (int)$this->data['melee_ohand_skill'] != 0 )
					$atk = $this->data['melee_mhand_mindam'].'-'.$this->data['melee_mhand_maxdam'].'/'.$this->data['melee_ohand_mindam'].'-'.$this->data['melee_ohand_maxdam'];
				else
					$atk = $this->data['melee_mhand_mindam'].'-'.$this->data['melee_mhand_maxdam'];
				break;
		}
		$atktooltip = $this->data[$type.'_' .$stat.'_tooltip'];

		if (ereg(':', $atk))
			$atk = ereg_replace(':', ' - ', $atk);

		$matches='';
		preg_match('|\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r|',$atk,$matches);
		$atkcolor = (isset($matches[1]))?$matches[1]:'ffffff';
		$atk = preg_replace('|\|c[a-f0-9]{8}(.+?)\|r|','$1',$atk);

		switch($stat)
		{
			case 'rating':
				if($type =='melee')
				{
					$tooltipheader = $wordings[$lang]['melee_rating'];
					$tooltip = $wordings[$lang]['melee_rating_tooltip'];
				}
				else
				{
					$tooltipheader = $wordings[$lang]['range_rating'];
					$tooltip = $wordings[$lang]['range_rating_tooltip'];
				}
				break;

			case 'power':
				if($type =='melee')
				{
					$tooltipheader = $wordings[$lang]['melee_att_power'].' '.$atk;
				}
				else
				{
					$tooltipheader = $wordings[$lang]['range_att_power'].' '.$atk;
				}
				$tooltip = nl2br($atktooltip);
				break;

			case 'range':
				$tooltipheader = $wordings[$lang]['damage'].' '.$atk;
				if( !empty($atktooltip) )
				{
					$tooltip = str_replace("\n",'</td></tr><tr><td style="font-size:10px;">',$atktooltip);
					$tooltip = str_replace(":\t",':</td><td align="right" style="color:white;font-size:10px;">',$tooltip);
					$tooltip = '<table style="width:220px;" cellspacing="0" cellpadding="0"><tr><td colspan="2">'.$tooltip.'</table>';
				}
				else
				{
					$tooltip = 'N/A';
				}
				break;
		}



		$line = "<span style=\"color:#FFFFFF;font-size:12px;font-weight:bold;\">$tooltipheader</span><br />";
		$line .= "<span style=\"color:#DFB801;\">$tooltip</span>";

		if($atk == '')
			$atk = 'N/A';

		$output = '<span>';
		$output .= '<strong '.makeOverlib($line,'','',2).' style="color:#'.$atkcolor.'">'.$atk.'</strong>';
		$output .= '</span>';

		return $output;
	}


	function printTab( $name, $div, $enabled = false )
	{
		/**********************************************************
		 * onlclick event has additional Javascript that will
		 * select the first pet and show the elements for it
		**********************************************************/
		if ($div == 'page2')
			$action = 'onclick="showPet(1); doTab( \''.$div.'\' )"';
		else
			$action = 'onclick="doTab( \''.$div.'\' )"';


		if( $enabled )
			$output = '<div class="tab" '.$action.'><span id="tabfont'.$div.'" class="white">'.$name.'</span></div>';
		else
			$output = '<div class="tab" '.$action.'><span id="tabfont'.$div.'" class="yellow">'.$name.'</span></div>';

		return $output;
	}


	function printTalents($member_id)
	{
		global $roster_conf, $wowdb, $wordings;

		$lang = $this->data['clientLocale'];

		$query = "SELECT * FROM `".ROSTER_TALENTTREETABLE."` WHERE `member_id` = '$member_id' ORDER BY `order`;";
		$trees = $wowdb->query( $query );
		if( $wowdb->num_rows($trees) > 0 )
		{
			$g = 0;

			$outtalent = '<div><img class="tbar" height="5" src="'.$roster_conf['img_url'].'tbar.png" width="300" alt="" />';
			$outtalent .= '<img id="tlabbg1" height="32" src="'.$roster_conf['img_url'].'atab.gif" width="105" alt="" />';
			$outtalent .= '<img id="tlabbg2" height="32" src="'.$roster_conf['img_url'].'itab.gif" width="105" alt="" />';
			$outtalent .= '<img id="tlabbg3" height="32" src="'.$roster_conf['img_url'].'itab.gif" width="105" alt="" /></div>';

			$query = "SELECT * FROM `".ROSTER_TALENTSTABLE."` WHERE `member_id` = '".$member_id."'";
			$result = $wowdb->query( $query );

			if (!$result)
			{
				die_quietly('Failed to fetch talents','Character Stats',__FILE__,__LINE__,$query);
			}
			while ( $talent = $wowdb->fetch_assoc( $result ) )
			{
				$talents[$talent['tree']][$talent['column']][$talent['row']] = $talent;
			}
			$wowdb->free_result($result);

			while( $tree = $wowdb->fetch_assoc( $trees ) )
			{
				$g++;
				$c = 0;
				if( $g==1 )
					$outtalent .='<div class="tablabelactive" id="tlab'.$g.'" onclick="setActiveTalentWrapper(\''.$g.'\',\''.$roster_conf['img_url'].'\');">'.$tree["tree"].'</div>';
				else
					$outtalent .='<div class="tablabel" id="tlab'.$g.'" onclick="setActiveTalentWrapper(\''.$g.'\',\''.$roster_conf['img_url'].'\');">'.$tree["tree"].'</div>';

				$output .= '<div id="talentwrapper'.$tree['order'].'">';
				$output .= '<div id="talentpage'.$tree['order'].'" style="background:url(\''.$roster_conf['interface_url'].$tree['background'].'.'.$roster_conf['img_suffix'].'\') no-repeat;">';
				$output .= '<span class="talspent">'.$wordings[$lang]['pointsspent'].' ' . $tree['pointsspent'].'</span>
	<table align="center" width="100%">
	  <tr>';
				while( $c < 4)
				{
					$c++;
					$r = 0;

					$output .= '
	    <td width="20"></td>
	    <td>
	      <table>';

					while( $r < 9 )
					{
						$r++;

						$output .= '        <tr>
	          <td height="45">';

						if (!isset($talents[$tree['tree']][$c][$r]))
						{
							$output .= '<div class="item"><img src="'.$roster_conf['img_url'].'pixel.gif" class="icon" alt="" /></div>';
						}
						else
						{
							$talent4 = $talents[$tree['tree']][$c][$r];
							$path4 = $roster_conf['interface_url'] . $talent4['texture'] . "." . $roster_conf['img_suffix'];

							$first_line = True;
							$talent_tooltip = '';

							// Compatibility with < 1.7
							$talent4['tooltip'] = str_replace('<br>',"\n",$talent4['tooltip']);
							foreach (explode("\n", $talent4['tooltip']) as $line)
							{
								if( $first_line )
								{
									$color = 'ffffff;font-size:12px;font-weight: bold;';
									$first_line = False;
								}
								else
								{
									if( substr( $line, 0, 2 ) == '|c' )
									{
										$color = substr( $line, 4, 6 );
										$line = substr( $line, 10, -2 );
									}
									else if ( strpos( $line, $wordings[$lang]['tooltip_rank'] ) === 0 )
									{
										$color = '00ff00;font-size:11px';
									}
									else if ( strpos( $line, $wordings[$lang]['tooltip_next_rank'] ) === 0 )
									{
										$color = 'ffffff;font-size:11px';
									}
									else if ( strpos( $line, $wordings[$lang]['tooltip_requires'] ) === 0 )
									{
										$color = 'ff0000';
									}
									else
									{
										$color = 'dfb801';
									}
								}
								if( $line != '' )
								{
									$talent_tooltip .= '<span style="color:#'.$color.';">'.$line.'</span><br />';
								}
							}

							if ($talent4['rank'] == 0)
								$class = 'talenticonGreyed';
							else
								$class = 'talenticon';

							$output .= '<div class="item" '.makeOverlib($talent_tooltip,'','',2).'><img src="'.$path4.'" class="'.$class.'" width="40" height="40" alt="" />';

							if( $talent4['rank'] == $talent4['maxrank'] )
							{
								$output .= '<span class="talvalue yellow">'.$talent4['rank'].'</span>';
							}
							elseif( $talent4['rank'] < $talent4['maxrank'] && $talent4['rank'] > 0 )
							{
								$output .= '<span class="talvalue green">'.$talent4['rank'].'</span>';
							}

							$output .= '</div>';
						}
						$output .= "</td>\n        </tr>\n";
					}
					$output .= "      </table></td>\n";
				}
				$output .= "  </tr>\n</table></div></div>\n";
			}
			return $output.$outtalent;
		}
		else
		{
			return 'No Talents for '.$this->data['name'];
		}
	}


	function printSkills()
	{
		global $skilltypes;

		$allskills = skill_get_many( $this->data['member_id']);

		foreach ($allskills as $cat => $skills)
		{
			$returnstring .= $skills[0]->outHeader();
			foreach ($skills as $skill)
			{
				$returnstring .= $skill->out();
			}
		}
		return $returnstring;
	}


	function printReputation()
	{
		$reputation = get_reputation( $this->data['member_id']);
		$temp = '';
		for( $i=0 ; $i<sizeof($reputation) ;  $i++ )
		{
			$temp = $reputation[$i]->outHeader($temp);
			echo $reputation[$i]->out();
		}
	}


	function printHonor()
	{
		global $roster_conf, $wowdb, $wordings, $guild_info;

		$lang = $this->data['clientLocale'];

		$icon = '';
		switch( substr($guild_info['faction'],0,1) )
		{
			case 'A':
				$icon = '<img src="'.$roster_conf['img_url'].'battleground-alliance.png" alt="" />';
				break;
			case 'H':
				$icon = '<img src="'.$roster_conf['img_url'].'battleground-horde.png" alt="" />';
				break;
		}

		$output = '<div class="honortext">'.$wordings[$lang]['honor'].':<span>'.$this->data['honorpoints'].'</span>'.$icon.'</div>'."\n";

		$output .= '<div class="today">'.$wordings[$lang]['today'].'</div>'."\n";
		$output .= '<div class="yesterday">'.$wordings[$lang]['yesterday'].'</div>'."\n";
		$output .= '<div class="lifetime">'.$wordings[$lang]['lifetime'].'</div>'."\n";

		$output .= '<div class="divider"></div>'."\n";

		$output .= '<div class="killsline">'.$wordings[$lang]['kills'].'</div>'."\n";
		$output .= '<div class="killsline1">'.$this->data['sessionHK'].'</div>'."\n";
		$output .= '<div class="killsline2">'.$this->data['yesterdayHK'].'</div>'."\n";
		$output .= '<div class="killsline3">'.$this->data['lifetimeHK'].'</div>'."\n";

		$output .= '<div class="honorline">'.$wordings[$lang]['honor'].'</div>'."\n";
		$output .= '<div class="honorline1">~'.$this->data['sessionCP'].'</div>'."\n";
		$output .= '<div class="honorline2">'.$this->data['yesterdayContribution'].'</div>'."\n";
		$output .= '<div class="honorline3">-</div>'."\n";

		$output .= '<div class="arenatext">'.$wordings[$lang]['arena'].':<span>'.$this->data['arenapoints'].'</span><img src="'.$roster_conf['img_url'].'arenapointsicon.png" alt="" /></div>'."\n";

		return $output;
	}

	function out()
	{
		global $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		if ($this->data['name'] != '')
		{
			$this->fetchEquip();
			$petTab = $this->printPet();

?>
<script type="text/javascript">
<!--
  addTab('page1')
<?php
if( $petTab != '' )
	print '  addTab(\'page2\')'."\n";
?>
  addTab('page3')
  addTab('page4')
<?php
if( $roster_conf['show_talents'] )
	print '  addTab(\'page5\')'."\n";
?>
  addTab('page6')
//-->
</script>


<div class="char" id="char"><!-- Begin char -->
  <div class="main"><!-- Begin char-main -->
    <div class="top" id="top"><!-- Begin char-main-top -->
      <div class="headline_1"><?php print $this->data['name']; ?></div>
      <div class="headline_2">Level <?php print ($this->data['level'].' - '.$this->data['sex'].' '.$this->data['race'].' '.$this->data['class']); ?></div>
<?php

if( isset( $this->data['guild_name'] ) )
	echo '      <div class="headline_2">'.$this->data['guild_title'].' of '.$this->data['guild_name']."</div>\n";

?>
    </div><!-- End char-main-top -->
    <div class="page1" id="page1"><!-- begin char-main-page1 -->
      <div class="left"><!-- begin char-main-page1-left -->
        <div class="equip"><?php print $this->printEquip('Head'); ?></div>
        <div class="equip"><?php print $this->printEquip('Neck'); ?></div>
        <div class="equip"><?php print $this->printEquip('Shoulder'); ?></div>
        <div class="equip"><?php print $this->printEquip('Back'); ?></div>
        <div class="equip"><?php print $this->printEquip('Chest'); ?></div>
        <div class="equip"><?php print $this->printEquip('Shirt'); ?></div>
        <div class="equip"><?php print $this->printEquip('Tabard'); ?></div>
        <div class="equip"><?php print $this->printEquip('Wrist'); ?></div>
      </div><!-- end char-main-page1-left -->
      <div class="middle"><!-- begin char-main-page1-middle -->
        <div class="portrait"><!-- begin char-main-page1-middle-portrait -->
          <div class="resistance"><!-- begin char-main-page1-middle-portrait-resistance -->
            <ul>
                <?php print $this->printRes('res_fire'); ?>
            	<?php print $this->printRes('res_nature'); ?>
            	<?php print $this->printRes('res_arcane'); ?>
            	<?php print $this->printRes('res_frost'); ?>
            	<?php print $this->printRes('res_shadow'); ?>
            </ul>
          </div><!-- end char-main-page1-middle-portrait-resistance -->
          <div class="info"><!-- begin char-main-page1-middle-portrait-info -->
            <div class="health"><?php print $wordings[$lang]['health'].': <span class="white">'.$this->data['health']; ?></span></div>
            <?php

if( $this->data['class'] == $wordings[$lang]['Warrior'] )
	print '<div class="mana">'.$wordings[$lang]['rage'].': <span class="white">'.$this->data['mana'].'</span></div>';
elseif( $this->data['class'] == $wordings[$lang]['Rogue'] )
	print '<div class="mana">'.$wordings[$lang]['energy'].': <span class="white">'.$this->data['mana'].'</span></div>';
else
	print '<div class="mana">'.$wordings[$lang]['mana'].': <span class="white">'.$this->data['mana'].'</span></div>';


if($this->data['talent_points'])
	print '            '.$wordings[$lang]['unusedtalentpoints'].': <span class="white">'.$this->data['talent_points'].'</span><br />';

$TimeLevelPlayedConverted = seconds_to_time($this->data['timelevelplayed']);
$TimePlayedConverted = seconds_to_time($this->data['timeplayed']);

print "<br />\n";
print '            '.$wordings[$lang]['timeplayed'].': <span class="white">'.$TimePlayedConverted[days].$TimePlayedConverted[hours].$TimePlayedConverted[minutes].$TimePlayedConverted[seconds].'</span><br />'."\n";
print '            '.$wordings[$lang]['timelevelplayed'].': <span class="white">'.$TimeLevelPlayedConverted[days].$TimeLevelPlayedConverted[hours].$TimeLevelPlayedConverted[minutes].$TimeLevelPlayedConverted[seconds].'</span><br />'."\n";
print '            <div class="money">';
print '<span class="white">'.$this->data['money_g'].'</span><img src="'.$roster_conf['img_url'].'coin_gold.gif" alt="g" /> ';
print '<span class="white">'.$this->data['money_s'].'</span><img src="'.$roster_conf['img_url'].'coin_silver.gif" alt="s" /> ';
print '<span class="white">'.$this->data['money_c'].'</span><img src="'.$roster_conf['img_url'].'coin_copper.gif" alt="c" /> ';
print '</div>';

?>
          </div><!-- end char-main-page1-middle-portrait-info -->
          <div class="xp" style="padding-left:12px;"><!-- begin char-main-page1-middle-portrait-xp -->
            <div class="xpbox">
<?php

// Code to write a "Max Exp bar" just like in SigGen
if( $this->data['level'] == ROSTER_MAXCHARLEVEL )
{
	$expbar_width = '248';
	$expbar_text = $wordings[$lang]['max_exp'];
}
else
{
	$expbar_width = $this->printXP();
	list($xp, $xplevel, $xprest) = explode(':',$this->data['exp']);
	if ($xplevel != '0' && $xplevel != '')
	{
		$exp_percent = ( $xplevel > 0 ? round(($xp/$xplevel)*100) : 0);
		if( $xprest > 0 )
		{
			$expbar_text = $xp.'/'.$xplevel.' : '.$xprest.' ('.$exp_percent.'%)';
		}
		else
		{
			$expbar_text = $xp.'/'.$xplevel.' ('.$exp_percent.'%)';
		}
	}
}

?>
              <img class="bg" alt="" src="<?php echo $roster_conf['img_url']?>barxpempty.gif"/>
              <img src="<?php echo $roster_conf['img_url']?>expbar-var2.gif" alt="" class="bit" width="<?php print $expbar_width; ?>"/>
              <span class="level"><?php print $expbar_text;?></span>
            </div>
          </div><!-- end char-main-page1-middle-portrait-xp -->
        </div><!-- end char-main-page1-middle-portrait -->
<?php
	switch( $this->data['class'] )
	{
		case $wordings[$this->data['clientLocale']]['Warrior']:
			$rightbox = 'melee';
			break;
		case $wordings[$this->data['clientLocale']]['Paladin']:
			$rightbox = 'melee';
			break;
		case $wordings[$this->data['clientLocale']]['Shaman']:
			$rightbox = 'spell';
			break;
		case $wordings[$this->data['clientLocale']]['Hunter']:
			$rightbox = 'ranged';
			break;
		case $wordings[$this->data['clientLocale']]['Druid']:
			$rightbox = 'spell';
			break;
		case $wordings[$this->data['clientLocale']]['Rogue']:
			$rightbox = 'melee';
			break;
		case $wordings[$this->data['clientLocale']]['Mage']:
			$rightbox = 'spell';
			break;
		case $wordings[$this->data['clientLocale']]['Priest']:
			$rightbox = 'spell';
			break;
		case $wordings[$this->data['clientLocale']]['Warlock']:
			$rightbox = 'spell';
			break;
	}
?>
<script type="text/javascript">
<!--
  addLpage('statsleft');
  addLpage('meleeleft');
  addLpage('rangedleft');
  addLpage('spellleft');
  addLpage('defenseleft');
  addRpage('statsright');
  addRpage('meleeright');
  addRpage('rangedright');
  addRpage('spellright');
  addRpage('defenseright');
//-->
</script>
        <div class="bottom"><!-- begin char-main-page1-middle-bottom -->
		  <form>
		    <select class="statselect" name="statbox_left" onchange="doLpage(this.value);">
			  <option value="statsleft" selected="selected">Stats</option>
			  <option value="meleeleft">Melee</option>
			  <option value="rangedleft">Ranged</option>
			  <option value="spellleft">Spell</option>
			  <option value="defenseleft">Defense</option>
		    </select>
		    <select class="statselect" name="statbox_right" onchange="doRpage(this.value);">
			  <option value="statsright">Stats</option>
			  <option value="meleeright"<?php echo ($rightbox == 'melee'?' selected="selected"':'');?>>Melee</option>
			  <option value="rangedright"<?php echo ($rightbox == 'ranged'?' selected="selected"':'');?>>Ranged</option>
			  <option value="spellright"<?php echo ($rightbox == 'spell'?' selected="selected"':'');?>>Spell</option>
			  <option value="defenseright">Defense</option>
		    </select>
		  </form>
          <div class="padding">
		    <?php print $this->printBox('stats','left',true); ?>
		    <?php print $this->printBox('melee','left',false); ?>
		    <?php print $this->printBox('ranged','left',false); ?>
		    <?php print $this->printBox('spell','left',false); ?>
		    <?php print $this->printBox('defense','left',false); ?>
		    <?php print $this->printBox('stats','right',false); ?>
		    <?php print $this->printBox('melee','right',$rightbox=='melee'); ?>
		    <?php print $this->printBox('ranged','right',$rightbox=='ranged'); ?>
		    <?php print $this->printBox('spell','right',$rightbox=='spell'); ?>
		    <?php print $this->printBox('defense','right',false); ?>
          </div><!-- end char-main-page1-middle-bottom-padding -->
          <div class="hands">
            <div class="weapon0"><?php print $this->printEquip('MainHand'); ?></div>
            <div class="weapon1"><?php print $this->printEquip('SecondaryHand'); ?></div>
            <div class="weapon2"><?php print $this->printEquip('Ranged'); ?></div>
            <div class="ammo"><?php print $this->printEquip('Ammo'); ?></div>
          </div><!-- end char-main-page1-middle-bottom-hands -->
        </div><!-- end char-main-page1-middle-bottom -->
      </div><!-- end char-main-page1-middle -->
      <div class="right"> <!-- begin char-main-page1-right -->
        <div class="equip"><?php print $this->printEquip('Hands'); ?></div>
        <div class="equip"><?php print $this->printEquip('Waist'); ?></div>
        <div class="equip"><?php print $this->printEquip('Legs'); ?></div>
        <div class="equip"><?php print $this->printEquip('Feet'); ?></div>
        <div class="equip"><?php print $this->printEquip('Finger0'); ?></div>
        <div class="equip"><?php print $this->printEquip('Finger1'); ?></div>
        <div class="equip"><?php print $this->printEquip('Trinket0'); ?></div>
        <div class="equip"><?php print $this->printEquip('Trinket1'); ?></div>
      </div><!-- end char-main-page1-right -->
    </div><!-- end char-main-page1 -->
<?php

	$tab = '
    <div class="page2" id="page2"><!-- begin char-main-page2 -->
      <div class="left"></div>
      <div class="pet"><!-- begin char-main-page2-pet -->
		    '.$petTab.'
      </div>
      <div class="right"></div>
		</div><!-- end char-main-page2 -->';
	print $tab;

?>

    <div class="page3" id="page3"><!-- begin char-main-page3 -->
      <div class="left"></div>
      <div class="reputation"><!-- begin char-main-page3-reputation -->
        <?php print $this->printReputation(); ?>
      </div>
      <div class="right"></div>
    </div><!-- end char-main-page3 -->
    <div class="page4" id="page4"><!-- begin char-main-page4 -->
      <div class="left"></div>
      <div class="skills"><!-- begin char-main-page4-skills -->
        <?php print $this->printSkills(); ?>
      </div>
      <div class="right"></div>
    </div><!-- end char-main-page4 -->
<?php

if( $roster_conf['show_talents'] )
{
	$talent_tab = '
    <div class="page5" id="page5"><!-- begin char-main-page5 -->
      <div class="left"></div>
      <div class="talents"><!-- begin char-main-page5-talents -->
		    '.$this->printTalents( $this->data['member_id']).'
      </div>
      <div class="right"></div>
    </div><!-- end char-main-page5 -->';
	print $talent_tab;
}

?>

    <div class="page6" id="page6"><!-- begin char-main-page6 -->
      <div class="left"></div>
      <div class="honor"><!-- begin char-main-page6-honor -->
        <?php print $this->printHonor(); ?>
      </div>
      <div class="right"></div>
    </div><!-- end char-main-page6 -->
  </div><!-- end char-main -->
  <div class="bottomBorder"></div>
  <div class="tabs"><!-- begin char-tabs -->
<?php

print $this->printTab( $wordings[$lang]['tab1'], 'page1', True );
echo "\n";
if ($petTab != '')
{
	print $this->printTab( $wordings[$lang]['tab2'], 'page2' );
	echo "\n";
}
print $this->printTab( $wordings[$lang]['tab3'], 'page3' )."\n";
print $this->printTab( $wordings[$lang]['tab4'], 'page4' )."\n";
if( $roster_conf['show_talents'] )
{
	print $this->printTab( $wordings[$lang]['tab5'], 'page5' );
	echo "\n";
}
print $this->printTab( $wordings[$lang]['tab6'], 'page6' )."\n";

echo '  </div><!-- end char-tabs -->
</div><!-- end char -->
';
		}
		else
		{
			roster_die('Sorry no data in database for '.$_GET['name'].' of '.$_GET['server'],'Character Not Found');
		}
	}
}


function char_get_one_by_id( $member_id )
{
	global $wowdb, $timeformat, $roster_conf;

	$query = "SELECT a.*, b.*, `c`.`guild_name`, DATE_FORMAT(  DATE_ADD(`a`.`dateupdatedutc`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'update_format' ".
		"FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
		"WHERE `a`.`member_id` = `b`.`member_id` AND `a`.`member_id` = '$member_id' AND `a`.`guild_id` = `c`.`guild_id`;";
	$result = $wowdb->query( $query );
	if( $wowdb->num_rows($result) > 0 )
	{
		$data = $wowdb->fetch_assoc( $result );
		return new char( $data );
	}
	else
	{
		return false;
	}
}


function char_get_one( $name, $server )
{
	global $wowdb, $timeformat, $roster_conf;

	$name = $wowdb->escape( $name );
	$server = $wowdb->escape( $server );
	$query = "SELECT `a`.*, `b`.*, `c`.`guild_name`, DATE_FORMAT(  DATE_ADD(`a`.`dateupdatedutc`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'update_format' ".
		"FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
		"WHERE `a`.`member_id` = `b`.`member_id` AND `a`.`name` = '$name' AND `a`.`server` = '$server' AND `a`.`guild_id` = `c`.`guild_id`;";
	$result = $wowdb->query( $query );
	if( $wowdb->num_rows($result) > 0 )
	{
		$data = $wowdb->fetch_assoc( $result );
		return new char( $data );
	}
	else
	{
		return false;
	}
}


function DateCharDataUpdated($id)
{
	global $wowdb, $roster_conf, $phptimeformat;

	$query1 = "SELECT `dateupdatedutc` FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` = '$id'";
	$result1 = $wowdb->query($query1);
	$data1 = $wowdb->fetch_assoc($result1);
	$dateupdatedutc = $data1['dateupdatedutc'];
	$wowdb->free_result($result1);

	list($year,$month,$day,$hour,$minute,$second) = sscanf($dateupdatedutc,"%d-%d-%d %d:%d:%d");
	$localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);
	return date($phptimeformat[$roster_conf['roster_lang']], $localtime);
}


function seconds_to_time($seconds)
{
	while ($seconds >= 60)
	{
		if ($seconds >= 86400)
		{
		// there is more than 1 day
			$days = floor($seconds / 86400);
			$seconds = $seconds - ($days * 86400);
		}
		elseif ($seconds >= 3600)
		{
			$hours = floor($seconds / 3600);
			$seconds = $seconds - ($hours * 3600);
		}
		elseif ($seconds >= 60)
		{
			$minutes = floor($seconds / 60);
			$seconds = $seconds - ($minutes * 60);
		}
	}

	// convert variables into sentence structure components
	if (!$days)
		$days = '';
	else
	{
		if ($days == 1)
			$days = $days.'d, ';
		else
			$days = $days.'d, ';
	}
	if (!$hours)
		$hours = '';
	else $hours = $hours.'h, ';
	if (!$minutes)
		$minutes = '';
	else $minutes = $minutes.'m, ';
	if (!$seconds)
		$seconds = '';
	else
		$seconds = $seconds.'s';

	return array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds);
}


/*****************************************************************************************************
 Code originally from cybrey's 'Bonuses/Advanced Stats' addon with output formatting by dehoskins
 http://www.wowroster.net/viewtopic.php?t=1506

 Modified by the roster dev team
*****************************************************************************************************/

function dbl( $frontString, $value)
{
	echo $frontString . ' : ' . $value . '<br />';
}

function getStartofModifier( $aString )
{
	$startpos =  strlen( $aString);

	//Count back till we get to the first number
	while ( (is_numeric($aString[$startpos])==FALSE) and ($startpos <> 0) )
	{
		$startpos--;
	}

	//Get start position of the number
	while ( is_numeric($aString[$startpos]))
	{
		$startpos = $startpos-1;
	}
	return $startpos + 1;
}

function getLengthofModifier( $aString)
{
	$startpos = getStartofModifier( $aString);
	$length = 0;
	while (is_numeric($aString[$startpos+$length])){
		//$startpos ++;
		$length ++;
	}
	return $length;
}

function getModifier( $aString)
{
	$startpos = getStartofModifier($aString);
	$modifier = '';

	// Extract the number
	while ( is_numeric( $aString[$startpos] ))
	{
		$modifier .= $aString[$startpos];
		$startpos++;
	}
	return $modifier;
}

function getString( $aString)
{
	return substr_replace( $aString, 'XX',getStartofModifier($aString), getLengthofModifier($aString));
}

function getStartofModifierMana( $aString)
{
	$startpos = 0;
	while ( (is_numeric($aString[$startpos])==FALSE) and ($startpos <> strlen($aString)) ){
		$startpos ++;
	}
	return $startpos;
}

function getLengthofModifierMana( $aString)
{
	$startpos = getStartofModifierMana( $aString);
	$length = 0;
	while (is_numeric($aString[$startpos+$length])){
		$length ++;
	}
	return $length;
}

function getModifierString( $aString)
{
	return substr_replace( $aString, 'XX',getStartofModifierMana($aString), getLengthofModifierMana($aString));
}

function getModifierMana( $aString)
{
	return subStr($aString, getStartofModifierMana($aString), getLengthofModifierMana($aString));
}

function setBonus( $modifier, $string, $item_name, $item_color)
{
	global $myBonus, $myTooltip;

	$full = '<span style="color:#'.$item_color.';">' . $item_name . '</span> : ' . $modifier;

	if (array_key_exists($string, $myBonus)) {
		$myBonus[$string] = $myBonus[$string] + $modifier;
		$myTooltip[$string] = $myTooltip[$string] . '<br />' . $full;
	} else {
		$myBonus[$string] = $modifier;
		$myTooltip[$string] = $full;
	}
}

function hasNumeric( $aString)
{
	$pos = 0;

	while (($pos <= strlen($aString)) and (is_numeric($aString[$pos])==FALSE))
	{
		$pos++;
	}

	if ($pos < strlen($aString) )
		return TRUE;
	else
		return FALSE;
}

function sortOutTooltip( $tooltip, $item_name, $item_color,$client_lang )
{
	global $wordings;

	$lines = explode(chr(10), $tooltip);

	foreach ($lines as $line)
	{
		if ((ereg('^'.$wordings[$client_lang]['tooltip_equip'], $line)) and ( hasNumeric($line)==FALSE)){
			setBonus( '', $line, $item_name,$item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_equip_restores'], $line)){
			setBonus( getModifierMana( $line), getModifierString( $line), $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_equip_when'], $line)){
			setBonus( getModifierMana( $line), getModifierString( $line), $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_equip'], $line)){
			setBonus( getModifier( $line), getString( $line), $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_set'], $line)){
			setBonus( '', $line, $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_spell_damage'], $line)){
			setBonus( getModifier( $line), $line, $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_healing_power'], $line)){
			setBonus( getModifier( $line), $line, $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_chance_hit'], $line)){
			setBonus( getModifier( $line), getModifierString( $line), $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_reinforced_armor'], $line)){
			setBonus( getModifier( $line), getModifierString( $line), $item_name, $item_color);
		}
		else if (ereg('^'.$wordings[$client_lang]['tooltip_school_damage'], $line)){
			setBonus( getModifier( $line), getString( $line), $item_name, $item_color);
		}
	}
}

function dumpString( $aString)
{
	//$aString = str_replace( chr(10), 'TWAT', $aString);
	dbl( 'STRING', $aString);
	for ($i = 0; $i < strlen($aString); $i++)
	{
		dbl( $aString[$i], ord($aString[$i]));
	}
}


function dumpBonuses($char)
{
	global $myBonus, $myTooltip, $wordings, $roster_conf, $wowdb;

	$char->fetchEquip();

	foreach($char->equip as $slot=>$item)
	{
		sortOutTooltip($item->data['item_tooltip'], $item->data['item_name'], $item->data['item_color'],$char->data['clientLocale'] );
	}

	$bt .= border('sgray','start',$wordings[$roster_conf['roster_lang']]['itembonuses']).
		'<table style="width:400px;" class="bodyline" cellspacing="0" cellpadding="0" border="0">'."\n";

	$row = 0;
	foreach ($myBonus as $key => $value)
	{
		$bt .= '	<tr>
		<td class="membersRowRight'.(($row%2)+1).'" style="white-space:normal;" '.makeOverlib($myTooltip[$key],str_replace('XX', $value, $key),'',2).'>'.
		str_replace('XX', $value, $key).'</td>
	</tr>';

		$row++;
	}
	$bt .= '</table>'.border('sgray','end');

	if( !empty($myBonus) )
		return $bt;
}
