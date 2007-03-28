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
require_once (ROSTER_LIB.'quest.php');
require_once (ROSTER_LIB.'recipes.php');
require_once (ROSTER_LIB.'pvp3.php');

class char
{
	var $data;
	var $equip;
	var $talent_build;

	function char( $data )
	{
		$this->data = $data;
	}


	function show_pvp2( $type , $url , $sort , $start )
	{
		$pvps = pvp_get_many3( $this->data['member_id'],$type, $sort, -1);
		$returnstring = '<div align="center">';

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


	function show_quests( )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$quests = quest_get_many( $this->data['member_id'],'');

		$returnstring = '';
		if( isset( $quests[0] ) )
		{
			$zone = '';
			$returnstring = border('sgray','start',$wordings[$lang]['questlog'].' ('.count($quests).'/25)').
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
					$quest_tags[] = $wordings[$lang]['complete'];
				elseif( $quest->data['is_complete'] == -1 )
					$quest_tags[] = $wordings[$lang]['failed'];

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
				foreach( $wordings[$lang]['questlinks'] as $link )
				{
					$returnstring .= '<a href="'.$link['url1'].urlencode(utf8_decode($name)).(isset($link['url2']) ? $link['url2'].$quest_level : '').(isset($link['url3']) ? $link['url3'].$quest_level : '').'" target="_blank">'.$link['name']."</a>\n";
					$q++;
				}

				$returnstring .= '</td></tr>';
			}
			$returnstring .= '      </table>'.border('sgray','end');
		}
		return $returnstring;
	}

	function show_recipes( )
	{
		global $roster_conf, $url, $sort, $wordings, $wowdb;

		$lang = $this->data['clientLocale'];

		$recipes = recipe_get_many( $this->data['member_id'],'', $sort );
		if( isset( $recipes[0] ) )
		{
			$skill_name = '';
			$returnstring = '';

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
					$skill_image = "<img style=\"float:left;\" width=\"17\" height=\"17\" src=\"".$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix']."\" alt=\"\" />\n";

					$header = '<div style="cursor:pointer;width:600px;" onclick="showHide(\'table_'.$rc.'\',\'img_'.$rc.'\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');">
	'.$skill_image.'
	<div style="display:inline;float:right;"><img id="img_'.$rc.'" src="'.$roster_conf['img_url'].'plus.gif" alt="" /></div>
<a name="'.strtolower(str_replace(' ','',$skill_name)).'"></a>'.$skill_name.'</div>';


					$returnstring .= border('sgray','start',$header)."\n<table width=\"100%\" ".($roster_conf['recipe_disp'] == '0' ? 'style="display:none;"' : '').";\" class=\"bodyline\" cellspacing=\"0\" id=\"table_$rc\">\n";

$returnstring .= '  <tr>
    <th class="membersHeader"><a href="'.makelink('char-recipes&amp;s=item').'">'.$wordings[$lang]['item'].'</a></th>
    <th class="membersHeader"><a href="'.makelink('char-recipes&amp;s=name').'">'.$wordings[$lang]['name'].'</a></th>
    <th class="membersHeader"><a href="'.makelink('char-recipes&amp;s=difficulty').'">'.$wordings[$lang]['difficulty'].'</a></th>
    <th class="membersHeader"><a href="'.makelink('char-recipes&amp;s=type').'">'.$wordings[$lang]['type'].'</a></th>
    <th class="membersHeader"><a href="'.makelink('char-recipes&amp;s=level').'">'.$wordings[$lang]['level'].'</a></th>
    <th class="membersHeaderRight"><a href="'.makelink('char-recipes&amp;s=reagents').'">'.$wordings[$lang]['reagents'].'</a></th>
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

	function show_mailbox( )
	{
		global $wowdb, $wordings, $roster_conf, $tooltips;

		$lang = $this->data['clientLocale'];

		$sqlquery = "SELECT * FROM `".ROSTER_MAILBOXTABLE."` ".
			"WHERE `member_id` = '".$this->data['member_id']."' ".
			"ORDER BY `mailbox_days`;";

		$result = $wowdb->query($sqlquery);

		if( !$result )
		{
			return '<span class="headline_1">'.sprintf($wordings[$lang]['no_mail'],$this->data['name']).'</span>';
		}

		if( $wowdb->num_rows($result) > 0 )
		{
			//begin generation of mailbox's output
			$content .= border('sgray','start',$wordings[$lang]['mailbox']).
				'<table cellpadding="0" cellspacing="0" class="bodyline">'."\n";
			$content .= "<tr>\n";
			$content .= '<th class="membersHeader">'.$wordings[$lang]['mail_item'].'</th>'."\n";
			$content .= '<th class="membersHeader">'.$wordings[$lang]['mail_sender'].'</th>'."\n";
			$content .= '<th class="membersHeader">'.$wordings[$lang]['mail_subject'].'</th>'."\n";
			$content .= '<th class="membersHeaderRight">'.$wordings[$lang]['mail_expires'].'</th>'."\n";
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
					$money_included = $mail_money['c'].'<img src="'.$roster_conf['img_url'].'coin_copper.gif" alt="c" />';

					if( !empty($db_money) )
					{
						$mail_money['s'] = substr($db_money,-2,2);
						$db_money = substr($db_money,0,-2);
						$money_included = $mail_money['s'].'<img src="'.$roster_conf['img_url'].'coin_silver.gif" alt="s" /> '.$money_included;
					}
					if( !empty($db_money) )
					{
						$mail_money['g'] = $db_money;
						$money_included = $mail_money['g'].'<img src="'.$roster_conf['img_url'].'coin_gold.gif" alt="g" /> '.$money_included;
					}
				}

				// Fix icon texture
				if( !empty($row['item_icon']) )
				{
					$item_icon = $roster_conf['interface_url'].'Interface/Icons/'.$row['item_icon'].'.'.$roster_conf['img_suffix'];
				}
				elseif( !empty($money_included) )
				{
					$item_icon = $roster_conf['interface_url'].'Interface/Icons/'.$row['mailbox_coin_icon'].'.'.$roster_conf['img_suffix'];
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

				$expires_line = date($wordings[$this->data['clientLocale']]['phptimeformat'],((($row['mailbox_days']*24 + $roster_conf['localtimeoffset'])*3600)+$maildateutc)).' '.$roster_conf['timezone'];
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
						$tooltip = $wordings[$lang]['no_info'];
					}
				}

				$tooltip = makeOverlib($tooltip,$tooltip_h,'',2,$this->data['clientLocale']);

				// Item links
				$num_of_tips = (count($tooltips)+1);
				$linktip = '';
				foreach( $wordings[$this->data['clientLocale']]['itemlinks'] as $ikey => $ilink )
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
			return '<span class="headline_1">'.sprintf($wordings[$lang]['no_mail'],$this->data['name']).'</span>';
		}
	}

	function show_spellbook( )
	{
		global $wowdb, $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		$query = "SELECT `spelltree`.*, `talenttree`.`order`
			FROM `".ROSTER_SPELLTREETABLE."` AS spelltree
			LEFT JOIN `".ROSTER_TALENTTREETABLE."` AS talenttree
				ON `spelltree`.`member_id` = `talenttree`.`member_id`
				AND `spelltree`.`spell_type` = `talenttree`.`tree`
			WHERE `spelltree`.`member_id` = ".$this->data['member_id']."
			ORDER BY `talenttree`.`order` ASC";

		$result = $wowdb->query($query);

		if( !$result )
		{
			return sprintf($wordings[$lang]['no_spellbook'],$this->data['name']);
		}

		$num_trees = $wowdb->num_rows($result);

		if( $num_trees == 0 )
		{
			return sprintf($wordings[$lang]['no_spellbook'],$this->data['name']);
		}

		for( $t=0; $t < $num_trees; $t++)
		{
			$treedata = $wowdb->fetch_assoc($result);

			$spelltree[$t]['name'] = $treedata['spell_type'];
			$spelltree[$t]['icon'] = 'Interface/Icons/'.$treedata['spell_texture'];
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
				$spelltree[$t]['spells'][$p][$i]['icon'] = 'Interface/Icons/'.$spell['spell_texture'];
				$spelltree[$t]['spells'][$p][$i]['rank'] = $spell['spell_rank'];

				// Parse the tooltip
				$spelltree[$t]['spells'][$p][$i]['tooltip'] = makeOverlib($spell['spell_tooltip'],'','',0,$this->data['clientLocale'],',RIGHT');

				$i++;
			}
		}
		$wowdb->free_result($result);


		// Get the PET spell data
		$query = "SELECT `spell`.*, `pet`.`name`
			FROM `".ROSTER_PETSPELLTABLE."` as spell
			LEFT JOIN `".ROSTER_PETSTABLE."` AS pet
			ON `spell`.`pet_id` = `pet`.`pet_id`
			WHERE `spell`.`member_id` = '".$this->data['member_id']."' ORDER BY `spell`.`spell_name`;";

		$result = $wowdb->query($query);

		$petspells = array();
		while( $row = $wowdb->fetch_assoc($result) )
		{
			$petid = $row['pet_id'];
			$petspells[$petid]['name'] = $row['name'];
			$petspells[$petid][$i]['name'] = $row['spell_name'];
			$petspells[$petid][$i]['icon'] = 'Interface/Icons/'.$row['spell_texture'];
			$petspells[$petid][$i]['rank'] = $row['spell_rank'];

			// Parse the tooltip
			$petspells[$petid][$i]['tooltip'] = makeOverlib($row['spell_tooltip'],'','',0,$this->data['clientLocale'],',RIGHT');
			$i++;
		}
		$wowdb->free_result($result);



		$return_string = '
<div class="char_panel spell_panel">
	<img class="panel_icon" src="'.$roster_conf['img_url'].'char/menubar/icon_spellbook.gif" alt=""/>
	<div class="panel_title">'.$wordings[$lang]['spellbook'].'</div>
	<div class="background">&nbsp;</div>

	<div id="main_spells">
		<div class="skill_types">
			<ul>
';

		foreach( $spelltree as $tree )
		{
			$treetip = makeOverlib($tree['name'],'','',2,'',',WRAP,RIGHT');
			$return_string .= '				<li onclick="return showSpell(\''.$tree['id'].'\');"><img class="icon" src="'.$roster_conf['interface_url'].$tree['icon'].'.'.$roster_conf['img_suffix'].'" '.$treetip.' alt="" /></li>'."\n";
		}
		$return_string .= "			</ul>\n		</div>\n";


		foreach( $spelltree as $tree )
		{
			if( $tree['id'] == 0 )
			{
				$return_string .= '		<div id="spelltree_'.$tree['id'].'">'."\n";
			}
			else
			{
				$return_string .= '		<div id="spelltree_'.$tree['id'].'" style="display:none;">'."\n";
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
						$return_string .= '			<div id="page_'.$page.'_'.$tree['id'].'">'."\n";
						$return_string .= '				<div class="page_back_off"><img src="'.$roster_conf['img_url'].'char/spellbook/pageback_off.gif" class="navicon" alt="" /> '.$wordings[$lang]['prev'].'</div>'."\n";
						$return_string .= '				<div class="page_forward_off">'.$wordings[$lang]['next'].' <img src="'.$roster_conf['img_url'].'char/spellbook/pageforward_off.gif" class="navicon" alt="" /></div>'."\n";
						$first_page = false;
					}
					else
					{
						$return_string .= '			<div id="page_'.$page.'_'.$tree['id'].'">'."\n";
						$return_string .= '				<div class="page_back_off"><img src="'.$roster_conf['img_url'].'char/spellbook/pageback_off.gif" class="navicon" alt="" /> '.$wordings[$lang]['prev'].'</div>'."\n";
						$return_string .= '				<div class="page_forward" onclick="swapShow(\'page_'.($page+1).'_'.$tree['id'].'\',\'page_'.$page.'_'.$tree['id'].'\');">'.$wordings[$lang]['next'].' <img src="'.$roster_conf['img_url'].'char/spellbook/pageforward.gif" class="navicon" alt="" /></div>'."\n";
						$first_page = false;
					}
				}
				elseif( ($num_pages-1) == $page )
				{
					$return_string .= '			<div id="page_'.$page.'_'.$tree['id'].'" style="display:none;">'."\n";
					$return_string .= '				<div class="page_back" onclick="swapShow(\'page_'.($page-1).'_'.$tree['id'].'\',\'page_'.$page.'_'.$tree['id'].'\');"><img src="'.$roster_conf['img_url'].'char/spellbook/pageback.gif" class="navicon" alt="" /> '.$wordings[$lang]['prev'].'</div>'."\n";
					$return_string .= '				<div class="page_forward_off">'.$wordings[$lang]['next'].' <img src="'.$roster_conf['img_url'].'char/spellbook/pageforward_off.gif" class="navicon" alt="" /></div>'."\n";
				}
				else
				{
					$return_string .= '			<div id="page_'.$page.'_'.$tree['id'].'" style="display:none;">'."\n";
					$return_string .= '				<div class="page_back" onclick="swapShow(\'page_'.($page-1).'_'.$tree['id'].'\',\'page_'.$page.'_'.$tree['id'].'\');"><img src="'.$roster_conf['img_url'].'char/spellbook/pageback.gif" class="navicon" alt="" /> '.$wordings[$lang]['prev'].'</div>'."\n";
					$return_string .= '				<div class="page_forward" onclick="swapShow(\'page_'.($page+1).'_'.$tree['id'].'\',\'page_'.$page.'_'.$tree['id'].'\');">'.$wordings[$lang]['next'].' <img src="'.$roster_conf['img_url'].'char/spellbook/pageforward.gif" class="navicon" alt="" /></div>'."\n";
				}
				$return_string .= '				<div class="pagenumber">'.$wordings[$lang]['page'].' '.($page+1).'</div>'."\n";


				$icon_num = 0;
				foreach( $spellpage as $spellicons )
				{
					if( $icon_num == 0 )
					{
						$return_string .= '				<div class="container_1">'."\n";
					}
					elseif( $icon_num == 7 )
					{
						$return_string .= "				</div>\n				<div class=\"container_2\">\n";
					}
					$return_string .= '
				<div class="info_container">
					<img src="'.$roster_conf['interface_url'].$spellicons['icon'].'.'.$roster_conf['img_suffix'].'" class="icon" '.$spellicons['tooltip'].' alt="" />
					<span class="text"><span class="yellowB">'.$spellicons['name'].'</span>';
					if( $spellicons['rank'] != '' )
					{
						$return_string .= '<br /><span class="brownB">'.$spellicons['rank'].'</span>';
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
			$pet_tabs .= '			<li onclick="return displaypage(\'petspell_'.$petid.'\',this);"><div class="text">'.$pet['name']."</div></li>\n";

			$return_string .= '		<div id="petspell_'.$petid.'" style="display:none;">'."\n";

			$icon_num = 0;
			foreach( $pet as $arrayname => $spellicons )
			{
				if( $arrayname != 'name' )
				{
					if( $icon_num == 0 )
					{
						$return_string .= '			<div class="container_1">'."\n";
					}
					elseif( $icon_num == 7 )
					{
						$return_string .= "			</div>\n			<div class=\"container_2\">\n";
					}
					$return_string .= '
			<div class="info_container">
				<img src="'.$roster_conf['interface_url'].$spellicons['icon'].'.'.$roster_conf['img_suffix'].'" class="icon" '.$spellicons['tooltip'].' alt="" />
				<span class="text"><span class="yellowB">'.$spellicons['name'].'</span>';
					if( $spellicons['rank'] != '' )
					{
						$return_string .= '<br /><span class="brownB">'.$spellicons['rank'].'</span>';
					}
					$return_string .= "</span>\n				</div>\n";
					$icon_num++;
				}
			}
			$return_string .= "			</div>\n		</div>\n";
		}

		//$return_string .= "	</div>\n";


		$return_string .= '
<!-- Begin Navagation Tabs -->
	<div id="spell_set" class="tab_navagation">
		<ul>
			<li onclick="return displaypage(\'main_spells\',this);"><div class="text">'.$this->data['name'].'</div></li>
'.$pet_tabs.'
		</ul>
	</div>
</div>

<script type="text/javascript">
	//Set tab to intially be selected when page loads:
	//[which tab (1=first tab), ID of tab content to display]:
	window.onload=tab_nav_onload(\'spell_set\',[1, \'main_spells\'])
</script>'."\n";

		return $return_string;
	}

	function get( $field )
	{
		return $this->data[$field];
	}


	function printPet( )
	{
		global $wowdb, $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		$query = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `level` DESC";
		$result = $wowdb->query( $query );

		$output = $icons = '';

		$petNum = 0;
		if( $wowdb->num_rows($result) > 0 )
		{
			while ($row = $wowdb->fetch_assoc($result))
			{
				$xpbarshow = true;

				if( $row['level'] == ROSTER_MAXCHARLEVEL )
				{
					$expbar_width = '216';
					$expbar_text = $wordings[$lang]['max_exp'];
				}
				else
				{
					list($xp, $xplevel) = explode(':',$row['xp']);
					if ($xplevel != '0' && $xplevel != '')
					{
						$expbar_width = ( $xplevel > 0 ? floor($xp / $xplevel * 216) : 0);

						$exp_percent = ( $xplevel > 0 ? floor($xp / $xplevel * 100) : 0);

						$expbar_text = $xp.'/'.$xplevel.' ('.$exp_percent.'%)';
					}
					else
					{
						$xpbarshow = false;
					}
				}

				$unusedtp = $row['totaltp'] - $row['usedtp'];

				if( $row['level'] == ROSTER_MAXCHARLEVEL )
					$showxpBar = false;

				$left = 35+(($petNum)*50);
				$top = 285;

				// Start Warlock Pet Icon Fix
				if( $row['type'] == $wordings[$lang]['Imp'] )
				{
					$row['icon'] = 'spell_shadow_summonimp';
				}
				elseif( $row['type'] == $wordings[$lang]['Voidwalker'] )
				{
					$row['icon'] = 'spell_shadow_summonvoidwalker';
				}
				elseif( $row['type'] == $wordings[$lang]['Succubus'] )
				{
					$row['icon'] = 'spell_shadow_summonsuccubus';
				}
				elseif( $row['type'] == $wordings[$lang]['Felhunter'] )
				{
					$row['icon'] = 'spell_shadow_summonfelhunter';
				}
				elseif( $row['type'] == $wordings[$lang]['Felguard'] )
				{
					$row['icon'] = 'spell_shadow_summonfelguard';
				}
				elseif( $row['type'] == $wordings[$lang]['Infernal'] )
				{
					$row['icon'] = 'spell_shadow_summoninfernal';
				}
				// End Warlock Pet Icon Fix

				if( $row['icon'] == '' || !isset($row['icon']) )
				{
					$row['icon'] = 'inv_misc_questionmark';
				}

				$icons .= '			<li onclick="return showPet(\''. $petNum .'\');" '.makeOverlib($row['name'],$row['type'],'',2,'',',WRAP').'>
				<div class="text"><img src="'.$roster_conf['interface_url'].'Interface/Icons/'.$row['icon'].'.'.$roster_conf['img_suffix'].'" alt="" /></div></li>
';

				$output .= '
		<div id="pet_'.$petNum.'"'. ($petNum == 0 ? '' : ' style="display:none;"') .'>
			<div class="name">'. stripslashes($row['name']) .'</div>
			<div class="info">'. $wordings[$lang]['level'] .' '. $row['level'] .' '. stripslashes($row['type']) .'</div>

			<div class="loyalty">'. $row['loyalty'] .'</div>

			<img class="icon" src="'. $roster_conf['interface_url'] .'Interface/Icons/'. $row['icon'] .'.'. $roster_conf['img_suffix'] .'" alt="" />

			<div class="health"><span class="yellowB">'. $wordings[$lang]['health'] .':</span> '. (isset($row['health']) ? $row['health'] : '0') .'</div>
			<div class="mana"><span class="yellowB">'. $row['power'] .':</span> '. (isset($row['mana']) ? $row['mana'] : '0') .'</div>

			'. $this->printPetResist('arcane',$row) .'
			'. $this->printPetResist('fire',$row) .'
			'. $this->printPetResist('nature',$row) .'
			'. $this->printPetResist('frost',$row) .'
			'. $this->printPetResist('shadow',$row) .'
';
				if( $xpbarshow )
				{
					$output .= '
			<img src="'. $roster_conf['img_url'] .'char/expbar_empty.gif" class="xpbar_empty" alt="" />
			<div class="xpbar" style="clip:rect(0px '. $expbar_width .'px 12px 0px);"><img src="'. $roster_conf['img_url'].'char/expbar_full.gif' .'" alt="" /></div>
			<div class="xpbar_text">'. $expbar_text .'</div>';
				}

				$output .= '
			<div class="padding">
				<div class="stats">
					'. $this->printPetStat('stat_str',$row).'
					'. $this->printPetStat('stat_agl',$row).'
					'. $this->printPetStat('stat_sta',$row).'
					'. $this->printPetStat('stat_int',$row).'
					'. $this->printPetStat('stat_spr',$row).'
					'. $this->printPetStat('stat_armor',$row).'
				</div>
				<div class="stats">
					'. $this->printPetWSkill($row).'
					'. $this->printPetWDamage($row).'
					'. $this->printPetStat('melee_power',$row).'
					'. $this->printPetStat('melee_hit',$row).'
					'. $this->printPetStat('melee_crit',$row).'
					'. $this->printPetResilience($row).'
				</div>
			</div>
';
				if( $row['totaltp'] != 0 )
				{
					$output .= '
			<div class="trainingpts">'.$wordings[$lang]['unusedtrainingpoints'].': '. $unusedtp .' / '. $row['totaltp'] .'</div>';
				}
				$output .= '
		</div>
';

				$petNum++;
			}
			$output .= '
<!-- Begin Navagation Tabs -->
	<div class="pet_tabs">
		<ul>
'. $icons .'
		</ul>
	</div>';
		}

		return $output;
	}

	function printPetStat( $statname , $data )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$base = $data[$statname];
		$current = $data[$statname.'_c'];
		$buff = $data[$statname.'_b'];
		$debuff = -$data[$statname.'_d'];

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
				if( !empty($data['mitigation']) )
					$tooltip .= '<br /><span class="red">'.$wordings[$lang]['tooltip_damage_reduction'].': '.$data['mitigation'].'%</span>';
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
		}

		if( isset($lname) )
			$tooltipheader = $lname.' '.$this->printRatingLong($statname,$data);
		else
			$tooltipheader = $name.' '.$this->printRatingLong($statname,$data);

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $this->printRatingShort($statname,$data), $line);
	}

	function printPetWSkill ( $data )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$value = '<strong class="white">'.$data['melee_mhand_skill'].'</strong>';
		$name = $wordings[$lang]['weapon_skill'];
		$tooltipheader = $wordings[$lang]['mainhand'];
		$tooltip = sprintf($wordings[$lang]['weapon_skill_tooltip'], $data['melee_mhand_skill'], $data['melee_mhand_rating']);

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $value, $line);
	}

	function printPetWDamage ( $data )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];


		$value = '<strong class="white">'.$data['melee_mhand_mindam'].'</strong>'.'-'.'<strong class="white">'.$data['melee_mhand_maxdam'].'</strong>';
		$name = $wordings[$lang]['damage'];
		$tooltipheader = $wordings[$lang]['mainhand'];
		$tooltip = sprintf($wordings[$lang]['damage_tooltip'], $data['melee_mhand_speed'], $data['melee_mhand_mindam'], $data['melee_mhand_maxdam'], $data['melee_mhand_dps']);

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $value, $line);
	}

	function printPetResist( $resname , $data )
	{
		global $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		switch($resname)
		{
		case 'fire':
			$name = $wordings[$lang]['res_fire'];
			$tooltip = $wordings[$lang]['res_fire_tooltip'];
			$color = 'red';
			break;
		case 'nature':
			$name = $wordings[$lang]['res_nature'];
			$tooltip = $wordings[$lang]['res_nature_tooltip'];
			$color = 'green';
			break;
		case 'arcane':
			$name = $wordings[$lang]['res_arcane'];
			$tooltip = $wordings[$lang]['res_arcane_tooltip'];
			$color = 'yellow';
			break;
		case 'frost':
			$name = $wordings[$lang]['res_frost'];
			$tooltip = $wordings[$lang]['res_frost_tooltip'];
			$color = 'blue';
			break;
		case 'shadow':
			$name = $wordings[$lang]['res_shadow'];
			$tooltip = $wordings[$lang]['res_shadow_tooltip'];
			$color = 'purple';
			break;
		}

		$line = '<span style="color:'.$color.';font-size:11px;font-weight:bold;">'.$name.'</span> '.$this->printRatingLong('res_'.$resname,$data).'<br />';
		$line .= '<span style="color:#DFB801;text-align:left;">'.$tooltip.'</span>';

		$output = '<div style="background:url('.$roster_conf['img_url'].'char/resist/'.$resname.'.gif);" class="resist_'.$resname.'" '.makeOverlib($line,'','',2,'','').'>'. $data['res_'.$resname.'_c'] ."</div>\n";

		return $output;
	}

	function printPetResilience( $data )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$name = $wordings[$lang]['resilience'];
		$value = min($data['stat_res_melee'],$data['stat_res_ranged'],$data['stat_res_spell']);

		$tooltipheader = $name;
		$tooltip  = '<div><span style="float:right;">'.$data['stat_res_melee'].'</span>'.$wordings[$lang]['melee'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$data['stat_res_ranged'].'</span>'.$wordings[$lang]['ranged'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$data['stat_res_spell'].'</span>'.$wordings[$lang]['spell'].'</div>';


		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'%</strong>', $line);
	}


	function printStatLine( $label , $value , $tooltip )
	{
		$output  = '  <div class="statline" '.makeOverlib($tooltip,'','',2,'','').'>'."\n";
		$output .= '    <span class="value">'.$value.'</span>'."\n";
		$output .= '    <span class="label">'.$label.':</span>'."\n";
		$output .= '  </div>'."\n";

		return $output;
	}

	function printRatingShort( $statname , $data_or=false )
	{
		if( $data_or == false )
			$data = $this->data;
		else
			$data = $data_or;

		$base = $data[$statname];
		$current = $data[$statname.'_c'];
		$buff = $data[$statname.'_b'];
		$debuff = -$data[$statname.'_d'];

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

	function printRatingLong( $statname , $data_or=false )
	{
		if( $data_or == false )
			$data = $this->data;
		else
			$data = $data_or;

		$base = $data[$statname];
		$current = $data[$statname.'_c'];
		$buff = $data[$statname.'_b'];
		$debuff = -$data[$statname.'_d'];

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

	function printBox( $cat , $side , $visible )
	{
		print '<div class="stats" id="'.$cat.$side.'" style="display:'.($visible?'block':'none').'">'."\n";
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
		print '</div>'."\n";
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
				$tooltip = sprintf($wordings[$lang]['melee_att_power_tooltip'], $this->data['melee_power_dps']);
				break;
			case 'melee_hit':
				$name = $wordings[$lang]['weapon_hit_rating'];
				$tooltip = $wordings[$lang]['weapon_hit_rating_tooltip'];
				break;
			case 'melee_crit':
				$name = $wordings[$lang]['weapon_crit_rating'];
				$tooltip = sprintf($wordings[$lang]['weapon_crit_rating_tooltip'], $this->data['melee_crit_chance']);
				break;
			case 'ranged_power':
				$lname = $wordings[$lang]['ranged_att_power'];
				$name = $wordings[$lang]['power'];
				$tooltip = sprintf($wordings[$lang]['ranged_att_power_tooltip'], $this->data['ranged_power_dps']);
				break;
			case 'ranged_hit':
				$name = $wordings[$lang]['weapon_hit_rating'];
				$tooltip = $wordings[$lang]['weapon_hit_rating_tooltip'];
				break;
			case 'ranged_crit':
				$name = $wordings[$lang]['weapon_crit_rating'];
				$tooltip = sprintf($wordings[$lang]['weapon_crit_rating_tooltip'], $this->data['ranged_crit_chance']);
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

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
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

		$tooltipheader = (isset($name) ? $name : '');

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
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

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line = '<span style="color:#DFB801;">'.$tooltip.'</span>';
		}
		else
		{
			$value = '<strong class="white">'.$this->data['melee_mhand_skill'].'</strong>';
			$name = $wordings[$lang]['weapon_skill'];
			$tooltipheader = $wordings[$lang]['mainhand'];
			$tooltip = sprintf($wordings[$lang]['weapon_skill_tooltip'], $this->data['melee_mhand_skill'], $this->data['melee_mhand_rating']);

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

			if( $this->data['melee_ohand_dps'] > 0 )
			{
				$value .= '/'.'<strong class="white">'.$this->data['melee_ohand_skill'].'</strong>';
				$tooltipheader = $wordings[$lang]['offhand'];
				$tooltip = sprintf($wordings[$lang]['weapon_skill_tooltip'], $this->data['melee_ohand_skill'], $this->data['melee_ohand_rating']);

				$line .= '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
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

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line = '<span style="color:#DFB801;">'.$tooltip.'</span>';
		}
		else
		{
			$value = '<strong class="white">'.$this->data['melee_mhand_mindam'].'</strong>'.'-'.'<strong class="white">'.$this->data['melee_mhand_maxdam'].'</strong>';
			$name = $wordings[$lang]['damage'];
			$tooltipheader = $wordings[$lang]['mainhand'];
			$tooltip = sprintf($wordings[$lang]['damage_tooltip'], $this->data['melee_mhand_speed'], $this->data['melee_mhand_mindam'], $this->data['melee_mhand_maxdam'], $this->data['melee_mhand_dps']);

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

			if( $this->data['melee_ohand_dps'] > 0 )
			{
				$value .= '/'.'<strong class="white">'.$this->data['melee_ohand_mindam'].'</strong>'.'-'.'<strong class="white">'.$this->data['melee_ohand_maxdam'].'</strong>';
				$tooltipheader = $wordings[$lang]['offhand'];
				$tooltip = sprintf($wordings[$lang]['damage_tooltip'], $this->data['melee_ohand_speed'], $this->data['melee_ohand_mindam'], $this->data['melee_ohand_maxdam'], $this->data['melee_ohand_dps']);

				$line .= '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
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

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
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

			$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
			$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
		}

		return $this->printStatLine($name, $value, $line);
	}

	function printSpellDamage( )
	{
		global $wordings, $roster_conf;
		$lang = $this->data['clientLocale'];

		$name = $wordings[$lang]['spell_damage'];
		$value = '<strong class="white">'.$this->data['spell_damage'].'</strong>';

		$tooltipheader = $name.' '.$value;
		//$tooltip  = '<div><span style="float:right;">'.$this->data['spell_damage_holy'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-fire.gif" alt="" />'.$wordings[$lang]['holy'].'</div>';
		$tooltip  = '<div><span style="float:right;">'.$this->data['spell_damage_fire'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-fire.gif" alt="" />'.$wordings[$lang]['fire'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_nature'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-nature.gif" alt="" />'.$wordings[$lang]['nature'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_frost'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-frost.gif" alt="" />'.$wordings[$lang]['frost'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_shadow'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-shadow.gif" alt="" />'.$wordings[$lang]['shadow'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_arcane'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-arcane.gif" alt="" />'.$wordings[$lang]['arcane'].'</div>';


		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
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
/*
		//$tooltip  = '<div><span style="float:right;">'.$this->data['spell_damage_holy'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-fire.gif" alt="" />'.$wordings[$lang]['holy'].'</div>';
		$tooltip  = '<div><span style="float:right;">'.$this->data['spell_damage_fire'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-fire.gif" alt="" />'.$wordings[$lang]['fire'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_nature'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-nature.gif" alt="" />'.$wordings[$lang]['nature'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_frost'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-frost.gif" alt="" />'.$wordings[$lang]['frost'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_shadow'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-shadow.gif" alt="" />'.$wordings[$lang]['shadow'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['spell_damage_arcane'].'</span><img src="'.$roster_conf['img_url'].'char/resist/icon-arcane.gif" alt="" />'.$wordings[$lang]['arcane'].'</div>';
*/
		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, $value, $line);
	}

	function printDefense( )
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

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
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

		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'%</strong>', $line);
	}

	function printResilience( )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$name = $wordings[$lang]['resilience'];
		$value = min($this->data['stat_res_melee'],$this->data['stat_res_ranged'],$this->data['stat_res_spell']);

		$tooltipheader = $name;
		$tooltip  = '<div><span style="float:right;">'.$this->data['stat_res_melee'].'</span>'.$wordings[$lang]['melee'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['stat_res_ranged'].'</span>'.$wordings[$lang]['ranged'].'</div>';
		$tooltip .= '<div><span style="float:right;">'.$this->data['stat_res_spell'].'</span>'.$wordings[$lang]['spell'].'</div>';


		$line = '<span style="color:#ffffff;font-size:11px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		return $this->printStatLine($name, '<strong class="white">'.$value.'%</strong>', $line);
	}

	function printResist( $resname )
	{
		global $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		switch($resname)
		{
		case 'fire':
			$name = $wordings[$lang]['res_fire'];
			$tooltip = $wordings[$lang]['res_fire_tooltip'];
			$color = 'red';
			break;
		case 'nature':
			$name = $wordings[$lang]['res_nature'];
			$tooltip = $wordings[$lang]['res_nature_tooltip'];
			$color = 'green';
			break;
		case 'arcane':
			$name = $wordings[$lang]['res_arcane'];
			$tooltip = $wordings[$lang]['res_arcane_tooltip'];
			$color = 'yellow';
			break;
		case 'frost':
			$name = $wordings[$lang]['res_frost'];
			$tooltip = $wordings[$lang]['res_frost_tooltip'];
			$color = 'blue';
			break;
		case 'shadow':
			$name = $wordings[$lang]['res_shadow'];
			$tooltip = $wordings[$lang]['res_shadow_tooltip'];
			$color = 'purple';
			break;
		}

		$line = '<span style="color:'.$color.';font-size:11px;font-weight:bold;">'.$name.'</span> '.$this->printRatingLong('res_'.$resname).'<br />';
		$line .= '<span style="color:#DFB801;text-align:left;">'.$tooltip.'</span>';

		$output = '<div style="background:url('.$roster_conf['img_url'].'char/resist/'.$resname.'.gif);" class="resist_'.$resname.'" '.makeOverlib($line,'','',2,'','').'>'. $this->data['res_'.$resname.'_c'] ."</div>\n";

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

		$lang = $this->data['clientLocale'];

		if( isset($this->equip[$slot]) )
		{
			$item = $this->equip[$slot];
			$output = $item->out();
		}
		else
		{
			$output = '<div class="item" '.makeOverlib($wordings[$lang]['empty_equip'],$slot,'',2,'',',WRAP').'>'."\n";
			if ($slot == 'Ammo')
				$output .= '<img src="'.$roster_conf['img_url'].'pixel.gif" class="iconsmall"'." alt=\"\" />\n";
			else
				$output .= '<img src="'.$roster_conf['img_url'].'pixel.gif" class="icon"'." alt=\"\" />\n";
			$output .= "</div>\n";
		}
		return '<div class="equip_'.$slot.'">'.$output.'</div>';
	}


	function printTalents( )
	{
		global $roster_conf, $wowdb, $wordings;

		$lang = $this->data['clientLocale'];

		$sqlquery = "SELECT * FROM `".ROSTER_TALENTTREETABLE."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `order`;";
		$trees = $wowdb->query( $sqlquery );

		if( $wowdb->num_rows($trees) > 0 )
		{
			for( $j=0; $j < $wowdb->num_rows($trees); $j++)
			{
				$treedata = $wowdb->fetch_assoc($trees);

				$treelayer[$j]['name'] = $treedata['tree'];
				$treelayer[$j]['image'] = $treedata['background'].'.'.$roster_conf['img_suffix'];
				$treelayer[$j]['points'] = $treedata['pointsspent'];
				$treelayer[$j]['talents'] = $this->talentLayer($treedata['tree']);
			}

			$returndata = '
<div class="char_panel talent_panel">

	<img class="panel_icon" src="'.$roster_conf['img_url'].'char/menubar/icon_talents.gif" alt="" />
	<div class="panel_title">'.$wordings[$lang]['talents'].'</div>
	<img class="top_bar" src="'.$roster_conf['img_url'].'char/talent/bar_top.gif" alt="" />
	<img class="bot_bar" src="'.$roster_conf['img_url'].'char/talent/bar_bottom.gif" alt="" />

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

			$returndata .= strtolower($this->data['classEn']).'/talents.html?'.$this->talent_build.'" target="_blank">'.$wordings[$this->data['clientLocale']]['talentexport'].'</a></div>
	<div class="points_unused"><span class="label">'.$wordings[$this->data['clientLocale']]['unusedtalentpoints'].':</span> '.$this->data['talent_points'].'</div>'."\n";

			foreach( $treelayer as $treeindex => $tree )
			{
				$returndata .= '	<div id="treetab'.$treeindex.'" class="char_tab" style="display:none;" >

		<div class="points"><span style="color:#ffdd00">'.$wordings[$this->data['clientLocale']]['pointsspent'].' '.$tree['name'].' Talents:</span> '.$tree['points'].'</div>
		<img class="background" src="'.$roster_conf['interface_url'].'Interface/TalentFrame/'.$tree['image'].'" alt="" />

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
					<img class="rank_icon" src="'.$roster_conf['img_url'].'char/talent/rank.gif" alt="" />
					<div class="rank_text" style="font-weight:bold;color:#'.$cell['numcolor'].';">'.$cell['rank'].'</div>
					<img src="'.$roster_conf['interface_url'].'Interface/Icons/'.$cell['image'].'" alt="" /></div>'."\n";
							}
							else
							{
								$returndata .= '				<div class="cell" '.$cell['tooltipid'].'>
					<img class="icon_grey" src="'.$roster_conf['interface_url'].'Interface/Icons/'.$cell['image'].'" alt="" /></div>'."\n";
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
	<div id="talent_navagation" class="tab_navagation">
		<ul>
			<li onclick="return displaypage(\'treetab0\',this);"><div class="text">'.$treelayer[0]['name'].'</div></li>
			<li onclick="return displaypage(\'treetab1\',this);"><div class="text">'.$treelayer[1]['name'].'</div></li>
			<li onclick="return displaypage(\'treetab2\',this);"><div class="text">'.$treelayer[2]['name'].'</div></li>
		</ul>
	</div>

</div>

<script type="text/javascript">
	//Set tab to intially be selected when page loads:
	//[which tab (1=first tab), ID of tab content to display]:
	window.onload=tab_nav_onload(\'talent_navagation\',[1, \'treetab0\'])
</script>';
			return $returndata;
		}
		else
		{
			return '<span class="headline_1">No Talents for '.$this->data['name'].'</span>';
		}
	}

	function talentLayer( $treename )
	{
		global $wowdb, $roster_conf;

		$sqlquery = "SELECT * FROM `".ROSTER_TALENTSTABLE."` WHERE `member_id` = '".$this->data['member_id']."' AND `tree` = '".$treename."' ORDER BY `row` ASC , `column` ASC";

		$result = $wowdb->query($sqlquery);

		$returndata = array();
		if( $wowdb->num_rows($result) > 0 )
		{
			// initialize the rows and cells
			for($r=1; $r < 10; $r++)
			{
				for($c=1; $c < 5; $c++)
				{
					$returndata[$r][$c]['name'] = '';
				}
			}

			while( $talentdata = $wowdb->fetch_assoc( $result ) )
			{
				$r = $talentdata['row'];
				$c = $talentdata['column'];

				$this->talent_build .= $talentdata['rank'];

				$returndata[$r][$c]['name'] = $talentdata['name'];
				$returndata[$r][$c]['rank'] = $talentdata['rank'];
				$returndata[$r][$c]['maxrank'] = $talentdata['maxrank'];
				$returndata[$r][$c]['row'] = $r;
				$returndata[$r][$c]['column'] = $c;
				$returndata[$r][$c]['image'] = $talentdata['texture'].'.'.$roster_conf['img_suffix'];
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


	function printSkills( )
	{
		global $roster_conf;

		$skillData = $this->getSkillTabValues();

		$output = '';
		foreach( $skillData as $sindex => $skill )
		{
			$output .= '
		<div class="header"><img src="'.$roster_conf['img_url'].'minus.gif" id="skill'.$sindex.'_img" class="minus_plus" alt="" onclick="showHide(\'skill'.$sindex.'\',\'skill'.$sindex.'_img\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');" />'.$skill['name'].'</div>
		<div id="skill'.$sindex.'">
';
			foreach( $skill['bars'] as $skillbar )
			{
				$output .= '
			<div class="skill_bar">';
				if( $skillbar['maxvalue'] == '1' )
				{
					$output .= '
				<div style="position:absolute;"><img src="'.$roster_conf['img_url'].'char/skill/bar_grey.gif" alt="" /></div>
				<div class="text">'.$skillbar['name'].'</div>';
				}
				else
				{
					$output .= '
				<div style="position:absolute;clip:rect(0px '.$skillbar['barwidth'].'px 15px 0px);"><img src="'.$roster_conf['img_url'].'char/skill/bar.gif" alt="" /></div>
				<div class="text">'.$skillbar['name'].'<span class="text_num">'.$skillbar['value'].' / '.$skillbar['maxvalue'].'</span></div>';
				}
				$output .= "\n			</div>\n";
			}
			$output .= "		</div>\n";
		}

		return $output;
	}

	function getSkillBarValues( $skilldata )
	{
		list($level, $max) = explode( ':', $skilldata['skill_level'] );

		$returnData['maxvalue'] = $max;
		$returnData['value'] = $level;
		$returnData['name'] = $skilldata['skill_name'];
		$returnData['barwidth'] = ceil($level/$max*273);

		return $returnData;
	}

	function getSkillTabValues( )
	{
		global $wowdb;

		$query = "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `skill_order` ASC, `skill_name` ASC;";
		$result = $wowdb->query( $query );

		$skill_rows = $wowdb->num_rows($result);

		$i=0;
		$j=0;
		if ( $skill_rows > 0 )
		{
			$data = $wowdb->fetch_assoc( $result );
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
				$data = $wowdb->fetch_assoc( $result );
			}
			return $skillInfo;
		}
	}


	function printReputation( )
	{
		global $roster_conf;

		$repData = $this->getRepTabValues();

		$output = '';
		foreach( $repData as $findex => $faction )
		{
			$output .= '
		<div class="header"><img src="'.$roster_conf['img_url'].'minus.gif" id="rep'.$findex.'_img" class="minus_plus" alt="" onclick="showHide(\'rep'.$findex.'\',\'rep'.$findex.'_img\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\');" />'.$faction['name'].'</div>
		<div id="rep'.$findex.'">
';
			foreach( $faction['bars'] as $repbar )
			{
				$output .= '
			<div class="rep_bar">
				<div class="rep_title">'.$repbar['name'].'</div>
				<div class="rep_bar_field" style="clip:rect(0px '.$repbar['barwidth'].'px 13px 0px);"><img class="rep_bar_image" src="'.$repbar['image'].'" alt="" /></div>
				<div id="rb_'.$repbar['barid'].'" class="rep_bar_text">'.$repbar['standing'].'</div>
				<div id="rbn_'.$repbar['barid'].'" class="rep_bar_text" style="display:none">'.$repbar['value'].' / '.$repbar['maxvalue'].'</div>
				<div class="rep_bar_field"><img class="rep_bar_image" src="'.$roster_conf['img_url'].'pixel.gif" onmouseout="swapShow(\'rb_'.$repbar['barid'].'\',\'rbn_'.$repbar['barid'].'\');" onmouseover="swapShow(\'rb_'.$repbar['barid'].'\',\'rbn_'.$repbar['barid'].'\');" alt="" /></div>'."\n";
				if( $repbar['atwar'] == 1 )
				{
					$output .= '				<img src="'.$roster_conf['img_url'].'/char/rep/atwar.gif" style="float:right;" alt="" />'."\n";
				}
				$output .= "			</div>\n";
			}
			$output .= "		</div>\n";
		}

		return $output;
	}

	function getRepTabValues( )
	{
		global $wowdb;

		$query= "SELECT * FROM `".ROSTER_REPUTATIONTABLE."` WHERE `member_id` = '".$this->data['member_id']."' ORDER BY `faction` ASC, `name` ASC;";
		$result = $wowdb->query( $query );

		$rep_rows = $wowdb->num_rows($result);

		$i=0;
		$j=0;
		if ( $rep_rows > 0 )
		{
			$data = $wowdb->fetch_assoc( $result );
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
				$data = $wowdb->fetch_assoc( $result );
			}
			return $repInfo;
		}
	}

	function getRepBarValues( $repdata )
	{
		static $repnum = 0;

		global $wordings, $roster_conf, $char;

		$lang = $char->data['clientLocale'];

		$level = $repdata['curr_rep'];
		$max = $repdata['max_rep'];

		$img = array(
			$wordings[$lang]['exalted'] => $roster_conf['img_url'].'char/rep/green.gif',
			$wordings[$lang]['revered'] => $roster_conf['img_url'].'char/rep/green.gif',
			$wordings[$lang]['honored'] => $roster_conf['img_url'].'char/rep/green.gif',
			$wordings[$lang]['friendly'] => $roster_conf['img_url'].'char/rep/green.gif',
			$wordings[$lang]['neutral'] => $roster_conf['img_url'].'char/rep/yellow.gif',
			$wordings[$lang]['unfriendly'] => $roster_conf['img_url'].'char/rep/orange.gif',
			$wordings[$lang]['hostile'] => $roster_conf['img_url'].'char/rep/red.gif',
			$wordings[$lang]['hated'] => $roster_conf['img_url'].'char/rep/red.gif'
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

		$output = '
		<div class="honortext">'.$wordings[$lang]['honor'].':<span>'.$this->data['honorpoints'].'</span>'.$icon.'</div>

		<div class="today">'.$wordings[$lang]['today'].'</div>
		<div class="yesterday">'.$wordings[$lang]['yesterday'].'</div>
		<div class="lifetime">'.$wordings[$lang]['lifetime'].'</div>

		<div class="divider"></div>

		<div class="killsline">'.$wordings[$lang]['kills'].'</div>
		<div class="killsline1">'.$this->data['sessionHK'].'</div>
		<div class="killsline2">'.$this->data['yesterdayHK'].'</div>
		<div class="killsline3">'.$this->data['lifetimeHK'].'</div>

		<div class="honorline">'.$wordings[$lang]['honor'].'</div>
		<div class="honorline1">~'.$this->data['sessionCP'].'</div>
		<div class="honorline2">'.$this->data['yesterdayContribution'].'</div>
		<div class="honorline3">-</div>

		<div class="arenatext">'.$wordings[$lang]['arena'].':<span>'.$this->data['arenapoints'].'</span><img src="'.$roster_conf['img_url'].'arenapointsicon.png" alt="" /></div>'."\n";

		return $output;
	}

	function out( )
	{
		global $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		if ($this->data['name'] != '')
		{
			$this->fetchEquip();
			$petTab = $this->printPet();

?>

<div class="char_panel">
	<img src="<?php print $this->data['char_icon']; ?>.gif" class="panel_icon" alt="" />
	<div class="panel_title"><?php print $this->data['name']; ?></div>
	<div class="infoline_1"><?php print sprintf($wordings[$lang]['char_level_race_class'],$this->data['level'],$this->data['race'],$this->data['class']); ?></div>
<?php

if( isset( $this->data['guild_name'] ) )
	echo '	<div class="infoline_2">'.sprintf($wordings[$lang]['char_guildline'],$this->data['guild_title'],$this->data['guild_name'])."</div>\n";

?>

<!-- Begin tab1 -->
	<div id="tab1" class="tab1" style="display:none;">
		<div class="background">&nbsp;</div>

	<!-- Begin Equipment Items Loop -->
		<div class="equip">
			<?php print $this->printEquip('Head'); ?>
			<?php print $this->printEquip('Neck'); ?>
			<?php print $this->printEquip('Shoulder'); ?>
			<?php print $this->printEquip('Back'); ?>
			<?php print $this->printEquip('Chest'); ?>
			<?php print $this->printEquip('Shirt'); ?>
			<?php print $this->printEquip('Tabard'); ?>
			<?php print $this->printEquip('Wrist'); ?>

			<?php print $this->printEquip('MainHand'); ?>
			<?php print $this->printEquip('SecondaryHand'); ?>
			<?php print $this->printEquip('Ranged'); ?>
			<?php print $this->printEquip('Ammo'); ?>

			<?php print $this->printEquip('Hands'); ?>
			<?php print $this->printEquip('Waist'); ?>
			<?php print $this->printEquip('Legs'); ?>
			<?php print $this->printEquip('Feet'); ?>
			<?php print $this->printEquip('Finger0'); ?>
			<?php print $this->printEquip('Finger1'); ?>
			<?php print $this->printEquip('Trinket0'); ?>
			<?php print $this->printEquip('Trinket1'); ?>
		</div>
	<!-- End Equipment Items Loop -->

	<!-- Begin Resists Loop -->
		<?php print $this->printResist('arcane'); ?>
		<?php print $this->printResist('fire'); ?>
		<?php print $this->printResist('nature'); ?>
		<?php print $this->printResist('frost'); ?>
		<?php print $this->printResist('shadow'); ?>
	<!-- End Resists Loop -->

	<!-- Begin Advanced Stats -->
		<img src="<?php print $roster_conf['img_url']; ?>char/percentframe.gif" class="percent_frame" alt="" />

		<div class="health"><span class="yellowB"><?php print $wordings[$lang]['health']; ?>:</span> <?php print $this->data['health']; ?></div>
		<div class="mana"><span class="yellowB"><?php print $this->data['power']; ?>:</span> <?php print $this->data['mana']; ?></div>

		<div class="info_desc">
<?php

if($this->data['talent_points'])
	print '			'.$wordings[$lang]['unusedtalentpoints']."<br />\n";

print '			'.$wordings[$lang]['timeplayed']."<br />\n";
print '			'.$wordings[$lang]['timelevelplayed']."<br />\n";

?>
		</div>
		<div class="info_values">
<?php

if($this->data['talent_points'])
	print '			'.$this->data['talent_points']."<br />\n";

$TimeLevelPlayedConverted = seconds_to_time($this->data['timelevelplayed']);
$TimePlayedConverted = seconds_to_time($this->data['timeplayed']);
print '			'.$TimePlayedConverted['days'].$TimePlayedConverted['hours'].$TimePlayedConverted['minutes'].$TimePlayedConverted['seconds']."<br />\n";
print '			'.$TimeLevelPlayedConverted['days'].$TimeLevelPlayedConverted['hours'].$TimeLevelPlayedConverted['minutes'].$TimeLevelPlayedConverted['seconds']."<br />\n";
?>
		</div>
<?php

if( $roster_conf['show_money'] )
{
	print '
		<!-- Money Display -->
		<div class="money_disp">'."\n";
	if( $this->data['money_g'] != '0' )
		print '			'.$this->data['money_g'].'<img src="'.$roster_conf['img_url'].'coin_gold.gif" class="coin" alt="g" />'."\n";
	if( $this->data['money_s'] != '0' )
		print '			'.$this->data['money_s'].'<img src="'.$roster_conf['img_url'].'coin_silver.gif" class="coin" alt="s" />'."\n";
	if( $this->data['money_c'] != '0' )
		print '			'.$this->data['money_c'].'<img src="'.$roster_conf['img_url'].'coin_copper.gif" class="coin" alt="c" />'."\n";
print '
		</div>
';
}

// Code to write a "Max Exp bar" just like in SigGen
if( $this->data['level'] == ROSTER_MAXCHARLEVEL )
{
	$expbar_width = '216';
	$expbar_text = $wordings[$lang]['max_exp'];
	$expbar_type = 'expbar_full';
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

?>
	<!-- Begin EXP Bar -->
		<img src="<?php print $roster_conf['img_url']; ?>char/expbar_empty.gif" class="xpbar_empty" alt="" />
		<div class="xpbar" style="clip:rect(0px <?php print $expbar_width; ?>px 12px 0px);"><img src="<?php print $roster_conf['img_url'].'char/'.$expbar_type.'.gif'; ?>" alt="" /></div>
		<div class="xpbar_text"><?php print $expbar_text; ?></div>
	<!-- End EXP Bar -->


<?php
	switch( $this->data['class'] )
	{
		case $wordings[$this->data['clientLocale']]['Warrior']:
		case $wordings[$this->data['clientLocale']]['Paladin']:
		case $wordings[$this->data['clientLocale']]['Rogue']:
			$rightbox = 'melee';
			break;

		case $wordings[$this->data['clientLocale']]['Hunter']:
			$rightbox = 'ranged';
			break;

		case $wordings[$this->data['clientLocale']]['Shaman']:
		case $wordings[$this->data['clientLocale']]['Druid']:
		case $wordings[$this->data['clientLocale']]['Mage']:
		case $wordings[$this->data['clientLocale']]['Warlock']:
		case $wordings[$this->data['clientLocale']]['Priest']:
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
		<form action="">
			<select class="statselect_l" name="statbox_left" onchange="doLpage(this.value);">
				<option value="statsleft" selected="selected"><?php print $wordings[$lang]['menustats']; ?></option>
				<option value="meleeleft"><?php print $wordings[$lang]['melee']; ?></option>
				<option value="rangedleft"><?php print $wordings[$lang]['ranged']; ?></option>
				<option value="spellleft"><?php print $wordings[$lang]['spell']; ?></option>
				<option value="defenseleft"><?php print $wordings[$lang]['defense']; ?></option>
			</select>
				<select class="statselect_r" name="statbox_right" onchange="doRpage(this.value);">
				<option value="statsright"><?php print $wordings[$lang]['menustats']; ?></option>
				<option value="meleeright"<?php echo ($rightbox == 'melee'?' selected="selected"':'');?>><?php print $wordings[$lang]['melee']; ?></option>
				<option value="rangedright"<?php echo ($rightbox == 'ranged'?' selected="selected"':'');?>><?php print $wordings[$lang]['ranged']; ?></option>
				<option value="spellright"<?php echo ($rightbox == 'spell'?' selected="selected"':'');?>><?php print $wordings[$lang]['spell']; ?></option>
				<option value="defenseright"><?php print $wordings[$lang]['defense']; ?></option>
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
		</div>
	</div>
<!-- Begin tab2 -->
	<div id="tab2" class="tab2" style="display:none;">
		<div class="background">&nbsp;</div>
		<?php print $petTab; ?>
	</div>

<!-- Begin tab3 -->
	<div id="tab3" class="tab3" style="display:none;">
		<div class="faction"><?php print $wordings[$lang]['faction']; ?></div>
		<div class="standing"><?php print $wordings[$lang]['standing']; ?></div>
		<div class="atwar"><?php print $wordings[$lang]['atwar']; ?></div>

		<div class="container">
<?php print $this->printReputation(); ?>
		</div>
	</div>

<!-- Begin tab4 -->
	<div id="tab4" class="tab4" style="display:none;">
		<div class="container">
<?php print $this->printSkills(); ?>
		</div>
	</div>

<!-- Begin tab5 -->
	<div id="tab5" class="tab5" style="display:none;">
		<div class="background">&nbsp;</div>
<?php print $this->printHonor(); ?>
	</div>

<!-- Begin Navagation Tabs -->
	<div id="char_navagation" class="tab_navagation">
		<ul>
			<li onclick="return displaypage('tab1',this);"><div class="text"><?php print $wordings[$lang]['tab1']; ?></div></li>
<?php
if( $petTab != '' )
	print '			<li onclick="return displaypage(\'tab2\',this);"><div class="text">'.$wordings[$lang]['tab2'].'</div></li>'."\n";
?>
			<li onclick="return displaypage('tab3',this);"><div class="text"><?php print $wordings[$lang]['tab3']; ?></div></li>
			<li onclick="return displaypage('tab4',this);"><div class="text"><?php print $wordings[$lang]['tab4']; ?></div></li>
			<li onclick="return displaypage('tab5',this);"><div class="text"><?php print $wordings[$lang]['tab5']; ?></div></li>
		</ul>
	</div>

</div>


<script type="text/javascript">
	//Set tab to intially be selected when page loads:
	//[which tab (1=first tab), ID of tab content to display]:
	window.onload=tab_nav_onload('char_navagation',[1, 'tab1'])
</script>

<?php

		}
		else
		{
			roster_die('Sorry no data in database for '.$_GET['name'].' of '.$_GET['server'],'Character Not Found');
		}
	}
}


function char_get_one_by_id( $member_id )
{
	global $wowdb, $roster_conf, $act_words;

	$query = "SELECT a.*, b.*, `c`.`guild_name`, DATE_FORMAT(  DATE_ADD(`a`.`dateupdatedutc`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$act_words['timeformat']."' ) AS 'update_format' ".
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
	global $wowdb, $roster_conf, $act_words;

	$name = $wowdb->escape( $name );
	$server = $wowdb->escape( $server );
	$query = "SELECT `a`.*, `b`.*, `c`.`guild_name`, DATE_FORMAT(  DATE_ADD(`a`.`dateupdatedutc`, INTERVAL ".$roster_conf['localtimeoffset']." HOUR ), '".$act_words['timeformat']."' ) AS 'update_format' ".
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
	global $wowdb, $roster_conf, $wordings;

	$query = "SELECT `dateupdatedutc`, `clientLocale` FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` = '$id'";
	$result = $wowdb->query($query);
	$data = $wowdb->fetch_assoc($result);
	$wowdb->free_result($result);

	list($year,$month,$day,$hour,$minute,$second) = sscanf($data['dateupdatedutc'],"%d-%d-%d %d:%d:%d");
	$localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);
	return date($wordings[$data['clientLocale']]['phptimeformat'], $localtime);
}
