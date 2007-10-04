<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
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

require_once (ROSTER_BASE.'lib'.DIR_SEP.'item.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'bag.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'skill.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'reputation.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'quest.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'recipes.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'pvp3.php');

$myBonus = array();
$myTooltip = array();

class char
{
	var $data;

	function char( $data )
	{
		$this->data = $data;
	}


	function printXP()
	{
		list($current, $max) =
		explode( ':', $this->data['exp'] );
		if ($current > 0)
		{
			$perc = round(($current / $max)* 248, 1);
			return $perc;
		}
	}


	function show_pvp2($type, $url, $sort, $start)
	{
		global $roster_conf;

		$pvps = pvp_get_many3( $this->data['member_id'],$type, $sort, -1);
		$returnstring .= '<div align="center">';

		if( is_array($pvps) )
		{
			$returnstring .= output_pvp_summary($pvps,$type);

			if( isset( $pvps[0] ) )
			{
			    if ($type == 'PvP')
			    {
					$returnstring .= output_pvplog($this->data['member_id']);
			    }
			    elseif ($type == 'BG')
			    {
					$returnstring .= output_bglog($this->data['member_id']);
			    }
			    else
			    {
					$returnstring .= output_duellog($this->data['member_id']);
			    }
			}

			$returnstring .= '<br />';
			$returnstring .= '<br />';

			$max = sizeof($pvps);
			$sort_part = $sort ? "&amp;s=$sort" : '';

			if ($start > 0)
				$prev = $url.'&amp;start='.($start-50).$sort_part.'">&lt;</a> ';

			if (($start+50) < $max)
			{
				$listing = '<small>['.$start.' - '.($start+50).']</small>';
				$next = ' '.$url.'&amp;start='.($start+50).$sort_part.'">&gt;</a>';
			}
			else
				$listing = '<small>['.$start.' - '.($max).']</small>';


			$pvps = pvp_get_many3( $this->data['member_id'],$type, $sort, $start);

			if( isset( $pvps[0] ) )
			{
				$returnstring .= border('sgray','start',$prev.'Log '.$listing.$next);
				$returnstring .= output_pvp2($pvps, $url."&amp;start=".$start,$type);
				$returnstring .= border('sgray','end');
			}

			$returnstring .= '<br />';

			if ($start > 0)
				$returnstring .= $url.'&amp;start='.($start-50).$sort_part.'">&lt;</a> ';

			if (($start+50) < $max)
			{
				$returnstring .= $start.' - '.($start+50);
				$returnstring .= ' '.$url.'&amp;start='.($start+50).$sort_part.'">&gt;</a>';
			}
			else
				$returnstring .= $start.' - '.($max);

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
			$returnstring .= border('sgray','start','<h3>'.$wordings[$lang]['questlog'].'&nbsp; ('.count($quests).'/20)</h3>').
				'<table class="bodyline" cellspacing="0" cellpadding="0">';

			foreach ($quests as $quest)
			{
				if ($zone != $quest->data['zone'])
				{
					$zone = $quest->data['zone'];
					$returnstring .= '<tr><th colspan="10" class="membersHeaderRight"><h2>'.$zone.'</h2></th></tr>';
				}
				$quest_level = $quest->data['quest_level'];
				$char_level = $this->data['level'];
				$font = 'grey';

				if ($quest_level + 7 < $char_level)
					$font = 'grey';
				else if ($quest_level + 2 < $char_level)
					$font = 'green';
				else if ($quest_level < $char_level+2)
					$font = 'yellow';
				else
					$font = 'red';

				$returnstring .= '        <tr>
          <td class="membersRow1">';
				$returnstring .= '&nbsp;&nbsp;&nbsp;&nbsp;<span class="'.$font.'">['.$quest_level.'] </span>';

				$name = $quest->data['quest_name'];
				if ($name{0} == '[')
					$name = trim(strstr($name, ' '));

				$returnstring .= $name;

				if ($quest->data['quest_tag'])
					$returnstring .= ' ('.$quest->data['quest_tag'].')';

				if ($quest->data['is_complete'])
					$returnstring .= ' (Complete)';

				$returnstring .= "</td>\n";

				$returnstring .= '<td class="membersRowRight1 quest_link">';

				$q = 1;
				foreach( $questlinks as $link )
				{
					if( $roster_conf['questlink_'.$q] )
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
			$returnstring = '<h1>'.$wordings[$lang]['recipelist'].'</h1>'."\n";

			// Get char professions for quick links
			$query = "SELECT `skill_name` FROM `".ROSTER_RECIPESTABLE."` WHERE `member_id` = '" . $this->data['member_id'] . "' GROUP BY `skill_name` ORDER BY `skill_name`";
			$result = $wowdb->query( $query );

			// Set a ank for link to top of page
			$returnstring .= "<a name=\"top\">&nbsp;</a>\n";
			$returnstring .= '<h2 align="center">';
			$skill_name_divider = '';
			while( $data = $wowdb->fetch_assoc( $result ) )
			{
				$skill_name_header = $data['skill_name'];
				$returnstring .= $skill_name_divider .'<a href="#' . strtolower(str_replace(' ','',$skill_name_header)) . '">' . $skill_name_header . '</a>';
				$skill_name_divider = '&nbsp;-&nbsp;';
			}
			$returnstring .= "</h2>\n<br />\n";

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

					$header = $skill_image.'<a name="'.strtolower(str_replace(' ','',$skill_name)).'"></a><a href="#top">'.$skill_name."</a>\n";

					$returnstring .= border('sgray','start',$header)."\n<table width=\"600\" class=\"bodyline\" cellspacing=\"0\">\n";

$returnstring .= '  <tr>
    <th class="membersHeader">'.$url.'&amp;action=recipes&amp;s=item">'.$wordings[$lang]['item'].'</a></th>
    <th class="membersHeader">'.$url.'&amp;action=recipes&amp;s=name">'.$wordings[$lang]['name'].'</a></th>
    <th class="membersHeader">'.$url.'&amp;action=recipes&amp;s=difficulty">'.$wordings[$lang]['difficulty'].'</a></th>
    <th class="membersHeader">'.$url.'&amp;action=recipes&amp;s=type">'.$wordings[$lang]['type'].'</a></th>
    <th class="membersHeaderRight">'.$url.'&amp;action=recipes&amp;s=reagents">'.$wordings[$lang]['reagents'].'</a></th>
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
		global $wowdb, $wordings, $roster_conf, $phptimeformat, $itemlink;

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
					$money_included = $mail_money['c'].'<img src="'.$roster_conf['img_url'].'bagcoinbronze.gif" alt="c"/>';

					if( !empty($db_money) )
					{
						$mail_money['s'] = substr($db_money,-2,2);
						$db_money = substr($db_money,0,-2);
						$money_included = $mail_money['s'].'<img src="'.$roster_conf['img_url'].'bagcoinsilver.gif" alt="s"/> '.$money_included;
					}
					if( !empty($db_money) )
					{
						$mail_money['g'] = $db_money;
						$money_included = $mail_money['g'].'<img src="'.$roster_conf['img_url'].'bagcoingold.gif" alt="g"/> '.$money_included;
					}
				}

				// Fix icon texture
				if( !empty($row['item_icon']) )
				{
					$item_icon = $roster_conf['interface_url'].preg_replace('|\\\\|','/', $row['item_icon']).'.'.$roster_conf['img_suffix'];
				}
				elseif( !empty($money_included) )
				{
					$item_icon = $roster_conf['interface_url'].preg_replace('|\\\\|','/', $row['mailbox_coin_icon']).'.'.$roster_conf['img_suffix'];
				}
				else
				{
					$item_icon = $roster_conf['interface_url'].'Interface/Icons/INV_Misc_Note_02.'.$roster_conf['img_suffix'];
				}


				// Start the tooltips
				$tooltip_h = addslashes($row['mailbox_subject']);

				// first line is sender
				$tooltip = $wordings[$this->data['clientLocale']]['mail_sender'].
					': '.$row['mailbox_sender'].'<br />';

				$expires_line = date($phptimeformat[$this->data['clientLocale']],((($row['mailbox_days']*24 + $roster_conf['localtimeoffset'])*3600)+$maildateutc)).' '.$roster_conf['timezone'];
				if( (($row['mailbox_days']*24*3600)+$maildateutc) - time() < (3*24*3600) )
					$color = 'FF0000;';
				else
					$color = 'FFFFFF;';

				$tooltip .= $wordings[$this->data['clientLocale']]['mail_expires'].": <span style=\"color:#$color\">$expires_line</span><br />";

				// Join money with main tooltip
				if( !empty($money_included) )
				{
					$tooltip .= $wordings[$this->data['clientLocale']]['mail_money'].': '.$money_included;
				}


				// Get item tooltip
				$first_line = True;
				$item_tooltip = '';
				foreach (explode("\n",$row['item_tooltip']) as $line)
				{
					if( $first_line )
					{
						$color = 'FFFFFF; font-size: 12px; font-weight: bold';
						$first_line = False;
					}
					else
					{
						if( substr( $line, 0, 2 ) == '|c' )
						{
							$color = substr( $line, 4, 6 ).';';
							$line = substr( $line, 10, -2 );
						}
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_use'] ) === 0 )
							$color = '00ff00;';
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_requires'] ) === 0 )
							$color = 'ff0000;';
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_reinforced'] ) === 0 )
							$color = '00ff00;';
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_equip'] ) === 0 )
							$color = '00ff00;';
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_chance'] ) === 0 )
							$color = '00ff00;';
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_enchant'] ) === 0 )
							$color = '00ff00;';
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_soulbound'] ) === 0 )
							$color = '00bbff;';
						else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_set'] ) === 0 )
							$color = '00ff00;';
						elseif ( strpos( $line, '"' ) === 0 )
							$color = 'ffd517;';
						else
							$color='ffffff;';
					}
					$line = preg_replace('|\\>|','&#8250;', $line );
					$line = preg_replace('|\\<|','&#8249;', $line );

					if( strpos($line,"\t") )
					{
						$line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
						$line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>';
						$item_tooltip .= $line;
					}
					elseif( $line != '')
						$item_tooltip .= "<span style=\"color:#$color\">$line</span><br />";
				}

				// If the tip has no info, at least get the item name in there
				if( $item_tooltip != '' )
				{
					$item_tooltip = '<hr />'.$item_tooltip;
				}

				// Join item tooltip with main tooltip
				$tooltip .= $item_tooltip;

				$tooltip = str_replace("'", "\'", $tooltip);
				$tooltip = str_replace('"','&quot;', $tooltip);

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
				$content .= '<div class="item" onmouseover="overlib(\''.$tooltip.'\',CAPTION,\''.$tooltip_h.'\');" onmouseout="return nd();">';

				$content .= '<a href="'.$itemlink[$this->data['clientLocale']].urlencode(utf8_decode($row['item_name'])).'" target="_itemlink">'."\n".
					'<img src="'.$item_icon.'"'." alt=\"\" /></a>\n";

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

		$sqlquery = "SELECT `spelltree`.*, `talenttree`.`order` ".
			"FROM `".ROSTER_SPELLTREETABLE."` AS spelltree ".
			"LEFT JOIN `".ROSTER_TALENTTREETABLE."` AS talenttree ON `spelltree`.`member_id` = `talenttree`.`member_id` AND `spelltree`.`spell_type` = `talenttree`.`tree` ".
			"WHERE `spelltree` . `member_id` = ".$this->data['member_id']." ".
			"ORDER BY `talenttree` . `order` ASC";

		$result = $wowdb->query($sqlquery);

		if( !$result )
		{
			return "No ".$wordings[$roster_conf['roster_lang']]['spellbook']." for ".$this->data['name'];
		}

		$num_trees = $wowdb->num_rows($result);

		if( $num_trees > 0 )
		{
			for( $t=0; $t < $num_trees; $t++)
			{
				$treedata = $wowdb->fetch_assoc($result);

				$tree[$t]['name'] = $treedata['spell_type'];
				$tree[$t]['icon'] = preg_replace('|\\\\|','/', $treedata['spell_texture']);
				$tree[$t]['id'] = $t;


				// Get Icons
				$sqlquery = "SELECT * FROM `".ROSTER_SPELLTABLE."` WHERE `member_id` = '".$this->data['member_id']."' AND `spell_type` = '".$tree[$t]['name']."' ORDER BY `spell_name`;";

				$icons_result = $wowdb->query($sqlquery);

				if( $wowdb->num_rows($icons_result) > 0 )
				{
					$i=0;
					$p=0;
					for( $r=0; $r < $wowdb->num_rows($icons_result); $r++ )
					{
						$icons_data = $wowdb->fetch_assoc($icons_result);

						if( $i >= 14 )
						{
							$i=0;
							$p++;
						}
						$spells[$p][$i]['name'] = $icons_data['spell_name'];
						$spells[$p][$i]['type'] = $icons_data['spell_type'];
						$spells[$p][$i]['icon'] = preg_replace('|\\\\|','/', $icons_data['spell_texture']);
						$spells[$p][$i]['rank'] = $icons_data['spell_rank'];

						// Parse the tooltip
						$first_line = true;
						$tooltip = '';
						foreach (explode("\n", $icons_data['spell_tooltip']) as $line )
						{
							if( $first_line )
							{
								$color = 'FFFFFF; font-size: 12px; font-weight: bold';
								$first_line = False;
							}
							else
							{
								if( substr( $line, 0, 2 ) == '|c' )
								{
									$color = substr( $line, 4, 6 ).';';
									$line = substr( $line, 10, -2 );
								}
								else if ( strpos( $line, $wordings[$row['clientLocale']]['tooltip_use'] ) === 0 )
									$color = '00ff00;';
								else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_requires'] ) === 0 )
									$color = 'ff0000;';
								else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_reinforced'] ) === 0 )
									$color = '00ff00;';
								else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_equip'] ) === 0 )
									$color = '00ff00;';
								else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_chance'] ) === 0 )
									$color = '00ff00;';
								else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_enchant'] ) === 0 )
									$color = '00ff00;';
								else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_soulbound'] ) === 0 )
									$color = '00bbff;';
								else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_set'] ) === 0 )
									$color = '00ff00;';
								elseif ( strpos( $line, '"' ) === 0 )
									$color = 'ffd517;';
								else
									$color='ffffff;';
							}
							$line = preg_replace('|\\>|','&#8250;', $line );
							$line = preg_replace('|\\<|','&#8249;', $line );

							if( strpos($line,"\t") )
							{
								$line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
								$line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>';
								$tooltip .= $line;
							}
							elseif( $line != '')
								$tooltip .= "<span style=\"color:#$color\">$line</span><br />";
						}
						$tooltip = str_replace("'", "\'", $tooltip);
						$spells[$p][$i]['tooltip'] = str_replace('"', '&quot;', $tooltip);

						$i++;
					}
					// Assign spells to tree
					$tree[$t]['spells'] = $spells;
					unset($spells);
				}
			}
			$spelltree = $tree;
		}
		else
		{
			return "No ".$wordings[$roster_conf['roster_lang']]['spellbook']." for ".$this->data['name'];
		}

		$return_string .= '
<div class="spell_panel">
	<div class="spell_panel_name">'.$wordings[$roster_conf['roster_lang']]['spellbook'].'</div>

	<!-- Skill Type Icons Menu -->
	<div class="spell_skill_tab_bar">
';

		foreach( $spelltree as $tree )
		{
			$return_string .= '
		<div class="spell_skill_tab">
			<img class="spell_skill_tab_icon" src="'.$roster_conf['interface_url'].$tree['icon'].'.'.$roster_conf['img_suffix'].'" onmouseover="overlib(\''.$tree['name'].'\',WRAP,RIGHT);" onmouseout="return nd();" alt="" onclick="showSpellTree(\'spelltree_'.$tree['id'].'\');" />
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
					<img src="'.$roster_conf['interface_url'].$spellicons['icon'].'.'.$roster_conf['img_suffix'].'" class="icon" onmouseover="overlib(\''.$spellicons['tooltip'].'\',RIGHT);" onmouseout="return nd();" alt="" />
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


	function getNumPets($name, $server)
	{
		global $wowdb; 				//the object derived from class wowdb used to do queries

		$server = $wowdb->escape( $server );
		/******************************************************************************************************************
		returns the number of pets the character has in the database
		******************************************************************************************************************/
		$query = "SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '$name' AND `server` = '$server'";
		$result = $wowdb->query( $query );
		$row = $wowdb->fetch_assoc( $result );
		$member_id = $row['member_id'];
		$query = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$member_id' order by `level` DESC";
		$result = $wowdb->query( $query );
		return $wowdb->num_rows($result);
	}


	function printPet($name, $server)
	{
		global $wowdb, $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		$server = $wowdb->escape( $server );
		/******************************************************************************************************************
		Gets all the pets from the database associated with that character from that server.
		******************************************************************************************************************/

		$query = "SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '$name' AND `server` = '$server'";
		$result = $wowdb->query( $query );
		$row = $wowdb->fetch_assoc( $result );
		$member_id = $row['member_id'];
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

			if ($row['level'] == 60)
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

			switch ($petNum)
			{
				case 1:
					$left = 35;
					$top = 285;
					break;
				case 2:
					$left = 85;
					$top = 285;
					break;
				case 3:
					$left = 135;
					$top = 285;
					break;
				default:
					$left = 185;
					$top = 285;
					break;
			}

			// Start Warlock Pet Icon Mod
			$imp = 'Interface\\Icons\\Spell_Shadow_SummonImp';
			$void = 'Interface\\Icons\\Spell_Shadow_SummonVoidWalker';
			$suc = 'Interface\\Icons\\Spell_Shadow_SummonSuccubus';
			$fel = 'Interface\\Icons\\Spell_Shadow_SummonFelHunter';
			$inferno = 'Interface\\Icons\\Spell_Shadow_SummonInfernal';

			$iconStyle='cursor: pointer; position: absolute; left: '.$left.'px; top: '.$top.'px;';

			if ($row['type'] == $wordings[$lang]['Imp'])
				$row['icon'] = $imp;

			if ($row['type'] == $wordings[$lang]['Voidwalker'])
				$row['icon'] = $void;

			if ($row['type'] == $wordings[$lang]['Succubus'])
				$row['icon'] = $suc;

			if ($row['type'] == $wordings[$lang]['Felhunter'])
				$row['icon'] = $fel;

			if ($row['type'] == $wordings[$lang]['Infernal'])
				$row['icon'] = $inferno;
			// End Warlock Pet Icon Mod

			if ($row['icon'] == "" || !isset($row['icon']))
				$row['icon'] = "unknownIcon.gif";
			else
				$row['icon'] .= '.'.$roster_conf['img_suffix'];

			$icons			.= '<img src="'.$roster_conf['interface_url'].preg_replace('|\\\\|','/', $row['icon']).'" onclick="showPet(\''.$petNum.'\')" style="'.$iconStyle.'" alt="" onmouseover="overlib(\''.addslashes($row['type']).'\',CAPTION,\''.addslashes($row['name']).'\',WRAP);" onmouseout="return nd();" />';
			$petName		.= '<span class="petName" style="top: 10px; left: 95px; display: none;" id="pet_name'.$petNum.'">' . stripslashes($row['name']).'</span>';
			$petTitle		.= '<span class="petName" style="top: 30px; left: 95px; display: none;" id="pet_title'.$petNum.'">'.$wordings[$lang]['level'].' '.$row['level'].' ' . stripslashes($row['type']).'</span>';
			$loyalty		.= '<span class="petName" style="top: 50px; left: 95px; display: none;" id="pet_loyalty'.$petNum.'">'.$row['loyalty'].'</span>';
			$petIcon		.= '<img id="pet_top_icon'.$petNum.'" style="position: absolute; left: 35px; top: 10px; width: 55px; height: 60px; display: none;" src="'.$roster_conf['interface_url'].preg_replace('|\\\\|','/', $row['icon']).'" alt="" />';
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
		.$PetComboBox
		.$icons
		;
	}


	function printStat( $statname )
	{
		global $wordings;

		$lang = $this->data['clientLocale'];

		$base = $this->data[$statname];
		$current = $this->data[$statname.'_c'];
		$buff = $this->data[$statname.'_b'];
		$debuff = $this->data[$statname.'_d'];

		$id = $statname.':'.$base.':'.$current.':'.$buff.':'.$debuff;

		if( $buff == 0 )
		{
			$color = 'white';
			$mod_symbol = '';
		}
		else if( $buff < 0 )
		{
			$color = 'purple';
			$mod_symbol = '-';
		}
		else
		{
			$color = 'green';
			$mod_symbol = '+';
		}

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
		}

		if( $mod_symbol == '' )
		{
			$tooltipheader = $name.' '.$current;
		}
		else
		{
			$tooltipheader = "$name $current ($base <span class=\"$color\">$mod_symbol $buff</span>)";
		}

		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';
		$clean_line = str_replace("'", "\'", $line);
		$clean_line = str_replace('"','&quot;', $clean_line);
		$clean_line = str_replace('(',"\(", $clean_line);
		$clean_line = str_replace(')',"\)", $clean_line);
		$clean_line = str_replace('<','&lt;', $clean_line);
		$clean_line = str_replace('>','&gt;', $clean_line);

		$output  = '<span onmouseover="return overlib(\''.$clean_line.'\');" onmouseout="return nd();">';
		$output .= '<strong class="'.$color.'">'.$current.'</strong>';
		$output .= '</span>';

		return $output;
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
		$line = '<span style="color: '.$color.'; font-size: 12px; font-weight: bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color: #DFB801; text-align: left;">'.$tooltip.'</span>';
		$line = str_replace("'", "\'", $line);
		$line = str_replace('"','&quot;', $line);
		$line = str_replace('<','&lt;', $line);
		$line = str_replace('>','&gt;', $line);

		$output .= '<li class="'.substr($resname,4).'" onmouseover="overlib(\''.$line.'\',WRAP);" onmouseout="return nd();">';

		$output .= $this->data[$resname.'_c'];
		$output .= "</li>\n";

		return $output;
	}


	function printEquip( $slot )
	{
		global $roster_conf;

		$item = item_get_one( $this->data['member_id'], $slot );

		if( isset($item) )
		{
			$output = $item->out();
		}
		else
		{
			$output = '<div class="item" onmouseover="overlib(\'No item equipped\',CAPTION,\''.$slot.'\',WRAP);" onmouseout="return nd();">'."\n";
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

		$atk = $this->data[$type.'_'.$stat];
		$atktooltip = $this->data[$type.'_' .$stat.'_tooltip'];

		if (ereg(':', $atk))
			$atk = ereg_replace(':', ' - ', $atk);

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



		$line = "<span style=\"color: #FFFFFF; font-size: 12px;font-weight: bold;\">$tooltipheader</span><br />";
		$line .= "<span style=\"color: #DFB801;\">$tooltip</span>";
		$line = str_replace("'", "\'", $line);
		$line = str_replace('"','&quot;', $line);
		$line = str_replace("\n", '', $line);
		$line = str_replace('<','&lt;', $line);
		$line = str_replace('>','&gt;', $line);

		if($atk == '')
			$atk = 'N/A';

		$output = '<span>';
		$output .= '<strong onmouseover="overlib(\''.$line.'\');" onmouseout="return nd();" style="color:#ffffff">'.$atk.'</strong>';
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

			while( $tree = $wowdb->fetch_assoc( $trees ) )
			{
				$g++;
				$c = 0;
				$outtalent .='<div class="tablabel" id="tlab'.$g.'" onclick="setActiveTalentWrapper(\''.$g.'\',\''.$roster_conf['img_url'].'\');">'.$tree["tree"].'</div>';

				$output .= '<div id="talentwrapper'.$tree['order'].'">';
				$output .= '<div id="talentpage'.$tree['order'].'" style="background: url('.$roster_conf['interface_url'].preg_replace("|\\\\|","/", $tree['background']).'.'.$roster_conf['img_suffix'].') no-repeat">';
				$output .= $wordings[$lang]['pointsspent'].' ' . $tree['pointsspent'].'
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

					while( $r < 7 )
					{
						$r++;

						$output .= '        <tr>
	          <td height="45">';

						$query4 = "SELECT * FROM `".ROSTER_TALENTSTABLE."` where `member_id` = '$member_id' and `tree` = '".$tree['tree']."' and `column` = '" . $c . "' and `row` ='" . $r . "'";
						$talents4 = $wowdb->query( $query4 );

						if ($wowdb->num_rows($talents4) == 0)
						{
							$output .= '<div class="item"><img src="'.$roster_conf['img_url'].'pixel.gif" class="icon" alt="" /></div>';
						}
						else
						{
							$talent4 = $wowdb->fetch_assoc( $talents4 );
							$path4 = $roster_conf['interface_url'] . preg_replace("|\\\\|","/", $talent4['texture']) . "." . $roster_conf['img_suffix'];

							$first_line = True;
							$talent_tooltip = '';

							// Compatibility with < 1.7
							$talent4['tooltip'] = str_replace('<br>',"\n",$talent4['tooltip']);
							foreach (explode("\n", $talent4['tooltip']) as $line)
							{
								if( $first_line )
								{
									$color = 'ffffff; font-size: 12px; font-weight: bold;';
									$first_line = False;
								}
								else
								{
									if( substr( $line, 0, 2 ) == '|c' )
									{
										$color = substr( $line, 4, 6 ).';';
										$line = substr( $line, 10, -2 );
									}
									else if ( strpos( $line, $wordings[$lang]['tooltip_rank'] ) === 0 )
										$color = '00ff00; font-size: 11px;';
									else if ( strpos( $line, $wordings[$lang]['tooltip_next_rank'] ) === 0 )
										$color = 'ffffff; font-size: 11px;';
									else if ( strpos( $line, $wordings[$lang]['tooltip_requires'] ) === 0 )
										$color = 'ff0000;';
									else
										$color = 'dfb801;';
								}
								if( $line != '' )
								{
									$talent_tooltip .= '<span style="color:#'.$color.'">'.$line.'</span><br />';
								}
							}

							$talent_tooltip = str_replace("'", "\'", $talent_tooltip);
							$talent_tooltip = str_replace('"', '&quot;', $talent_tooltip);
							$talent_tooltip = str_replace('<','&lt;', $talent_tooltip);
							$talent_tooltip = str_replace('>','&gt;', $talent_tooltip);

							if ($talent4['rank'] == 0)
								$class = 'talenticonGreyed';
							else
								$class = 'talenticon';

							$output .= '<div class="item" onmouseover="return overlib(\''.$talent_tooltip.'\');" onmouseout="return nd();"><img src="'.$path4.'" class="'.$class.'" width="40" height="40" alt="" />';

							if( $talent4['rank'] == $talent4['maxrank'] )
							{
								$output .= '<span class="talvalue green">'.$talent4['rank'].'</span>';
							}
							elseif( $talent4['rank'] < $talent4['maxrank'] && $talent4['rank'] > 0 )
							{
								$output .= '<span class="talvalue yellow">'.$talent4['rank'].'</span>';
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

		list( $major, $minor ) = explode( '.', $this->data['version'] );

		for( $i=1 ; $i<7 ;  $i++ )
		{
			if( ($major == 0) && ($minor < 96) )
			{
				$skills = skill_get_many_by_type( $this->data['member_id'], $skilltypes[$this->data['clientLocale']][$i] );
			}
			else
			{
				$skills = skill_get_many_by_order( $this->data['member_id'], $i );
			}

			if( isset( $skills[0] ) )
			{
				$returnstring .= $skills[0]->outHeader();
				foreach ($skills as $skill)
				{
					$returnstring .= $skill->out();
				}
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
		global $roster_conf, $wowdb, $wordings;

		$lang = $this->data['clientLocale'];

		$showhonorxpBar = true;

		if ( strlen($this->data['Rankexp']) < 1 )
			$honorshowxpBar = false;

		$honorxp_percent = $this->data['Rankexp'];;

		$honorbarpixelwidth = floor(381 * ( $honorxp_percent / 100) );
		$honorxp_percent_word = $honorxp_percent.'%';

		if($this->data['RankInfo'])
		{
			$RankInfo = $this->data['RankInfo'];
			$RankIcon = $this->data['RankIcon'];
			if (empty($RankIcon))
			{
				$RankIcon = 'pixel';
				$roster_conf['img_suffix'] = 'gif';
			}
			$Badge = '<img src="'.$roster_conf['interface_url'].preg_replace("|\\\\|",'/',$RankIcon).'.'.$roster_conf['img_suffix'].'" alt="" />';
		}
		else
		{
			$RankInfo = '&nbsp;';
			$RankIcon = 'pixel.gif';
			$Badge = '<img src="'.$roster_conf['img_url'].$RankIcon.'" width="16" hieght="16" alt="" />';
		}

		$RankIcondata = $this->data['RankIcon'];

		if ($RankIcondata == NULL)
		{
			$output = '<div class="honortitleoff"></div>';
		}
		else
		{
			$RankName = $this->data['RankName'];
			$output = '<div class="honortitle"><img src="'.$roster_conf['img_url'].'expbar-var2.gif" alt="" class="honor_bit" width="'.$honorbarpixelwidth.'" />'.$honorxp_percent_word.' '.$Badge.' '.$RankName.' ('.$wordings[$lang]['rank'].' '.$RankInfo.')</div>'."\n";
		}

		// Today
		$output .= '<div class="today">'.$wordings[$lang]['today'].'</div>'."\n";
		$output .= '<div class="honortext0_">'.$wordings[$lang]['honorkills'].'</div>'."\n";
		$output .= '<div class="honortext0">'.$this->data['sessionHK'].'</div>'."\n";
		$output .= '<div class="honortext1_">'.$wordings[$lang]['dishonorkills'].'</div>'."\n";
		$output .= '<div class="honortext1">'.$this->data['sessionDK'].'</div>'."\n";

		// Yesterday
		$output .= '<div class="yesterday">'.$wordings[$lang]['yesterday'].'</div>'."\n";
		$output .= '<div class="honortext2_">'.$wordings[$lang]['honorkills'].'</div>'."\n";
		$output .= '<div class="honortext2">'.$this->data['yesterdayHK'].'</div>'."\n";
		$output .= '<div class="honortext3_">'.$wordings[$lang]['honor'].'</div>'."\n";
		$output .= '<div class="honortext3">'.$this->data['yesterdayContribution'].'</div>'."\n";

		// This week
		$output .= '<div class="thisweek">'.$wordings[$lang]['thisweek'].'</div>'."\n";
		$output .= '<div class="honortext4_">'.$wordings[$lang]['honorkills'].'</div>'."\n";
		$output .= '<div class="honortext4">'.$this->data['TWHK'].'</div>'."\n";
		$output .= '<div class="honortext5_">'.$wordings[$lang]['honor'].'</div>'."\n";
		$output .= '<div class="honortext5">'.$this->data['TWContribution'].'</div>'."\n";

		// Last Week
		$output .= '<div class="lastweek">'.$wordings[$lang]['lastweek'].'</div>'."\n";
		$output .= '<div class="honortext6_">'.$wordings[$lang]['honorkills'].'</div>'."\n";
		$output .= '<div class="honortext6">'.$this->data['lastweekHK'].'</div>'."\n";
		$output .= '<div class="honortext7_">'.$wordings[$lang]['honor'].'</div>'."\n";
		$output .= '<div class="honortext7">'.$this->data['lastweekContribution'].'</div>'."\n";
		$output .= '<div class="honortext8_">'.$wordings[$lang]['standing'].'</div>'."\n";
		$output .= '<div class="honortext8">'.$this->data['lastweekRank'].'</div>'."\n";

		// Lifetime
		$output .= '<div class="alltime">'.$wordings[$lang]['alltime'].'</div>'."\n";
		$output .= '<div class="honortext9_">'.$wordings[$lang]['honorkills'].'</div>'."\n";
		$output .= '<div class="honortext9">'.$this->data['lifetimeHK'].'</div>'."\n";
		$output .= '<div class="honortext10_">'.$wordings[$lang]['dishonorkills'].'</div>'."\n";
		$output .= '<div class="honortext10">'.$this->data['lifetimeDK'].'</div>'."\n";
		$output .= '<div class="honortext11_">'.$wordings[$lang]['highestrank'].'</div>'."\n";
		$output .= '<div class="honortext11">'.$this->data['lifetimeRankName'].'</div>'."\n";

		return $output;
	}


	function out()
	{
		global $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		if ($this->data['name'] != '')
		{

?>
<script type="text/javascript">
<!--
  addTab('page1')
<?php
if ( $this->getNumPets($this->data['name'], $this->data['server']))
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
<?php

if( $this->data['RankName'] == $wordings[$lang]['PvPRankNone'] )
	$RankName = '';
else
	$RankName = $this->data['RankName'].' ';

?>
      <h1><?php print ($RankName.$this->data['name']); ?></h1>
      <h2>Level <?php print ($this->data['level'].' | '.$this->data['sex'].' '.$this->data['race'].' '.$this->data['class']); ?></h2>
<?php

if( isset( $this->data['guild_name'] ) )
	echo '      <h2>'.$this->data['guild_title'].' of '.$this->data['guild_name']."</h2>\n";

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
          <div class="health_mana"><!-- begin char-main-page1-middle-portrait-health_mana -->
            <div class="health"><?php print $wordings[$lang]['health'].': <span class="white">'.$this->data['health']; ?></span></div>
            <?php

if( $this->data['class'] == $wordings[$lang]['Warrior'] )
	print '<div class="mana">'.$wordings[$lang]['rage'].': <span class="white">'.$this->data['mana'].'</span></div>';
elseif( $this->data['class'] == $wordings[$lang]['Rogue'] )
	print '<div class="mana">'.$wordings[$lang]['energy'].': <span class="white">'.$this->data['mana'].'</span></div>';
else
	print '<div class="mana">'.$wordings[$lang]['mana'].': <span class="white">'.$this->data['mana'].'</span></div>';

?>

          </div><!-- end char-main-page1-middle-portrait-health_mana -->
          <div class="talentPoints"><!-- begin char-main-page1-middle-portrait-talentPoints -->
<?php

if( $this->data['crit'] != '0' ) print	$wordings[$lang]['crit'].': <span class="white">'.$this->data['crit'].'%</span><br />'."\n";
if( $this->data['dodge'] != '0' ) print	$wordings[$lang]['dodge'].': <span class="white">'.$this->data['dodge'].'%</span><br />'."\n";
if( $this->data['parry'] != '0' ) print	$wordings[$lang]['parry'].': <span class="white">'.$this->data['parry'].'%</span><br />'."\n";
if( $this->data['block'] != '0' ) print	$wordings[$lang]['block'].': <span class="white">'.$this->data['block'].'%</span><br />'."\n";

if($this->data['talent_points'])
	print '            '.$wordings[$lang]['unusedtalentpoints'].': <span class="white">'.$this->data['talent_points'].'</span><br />';

$TimeLevelPlayedConverted = seconds_to_time($this->data['timelevelplayed']);
$TimePlayedConverted = seconds_to_time($this->data['timeplayed']);

print "<br />\n";
print '            '.$wordings[$lang]['timeplayed'].': <span class="white">'.$TimePlayedConverted[days].$TimePlayedConverted[hours].$TimePlayedConverted[minutes].$TimePlayedConverted[seconds].'</span><br />'."\n";
print '            '.$wordings[$lang]['timelevelplayed'].': <span class="white">'.$TimeLevelPlayedConverted[days].$TimeLevelPlayedConverted[hours].$TimeLevelPlayedConverted[minutes].$TimeLevelPlayedConverted[seconds].'</span>'."\n";
?>
          </div><!-- end char-main-page1-middle-portrait-talentPoints -->
          <div class="xp" style="padding-left:12px;"><!-- begin char-main-page1-middle-portrait-xp -->
            <div class="xpbox">
<?php

// Code to write a "Max Exp bar" just like in SigGen
if( $this->data['level'] == '60' )
{
	$expbar_width = '248';
	$expbar_text = $wordings[$lang]['max_exp'];
}
else
{
	$expbar_width = $this->printXP();
	list($xp, $xplevel, $xprest) = explode(':',$this->data['exp']);
	if ($xplevel != '0' || $xplevel != '')
	{
		if( $xprest > 0 )
		{
			$expbar_text = $xp.'/'.$xplevel.' : '.$xprest.' ('.round($xp/$xplevel*100).'%)';
		}
		else
		{
			$expbar_text = $xp.'/'.$xplevel.' ('.round($xp/$xplevel*100).'%)';
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
        <div class="bottom"><!-- begin char-main-page1-middle-bottom -->
          <div class="padding">
            <ul class="stats">
              <li><?php print $wordings[$lang]['strength'] .': '.$this->printStat('stat_str'); ?></li>
              <li><?php print $wordings[$lang]['agility']  .': '.$this->printStat('stat_agl'); ?></li>
              <li><?php print $wordings[$lang]['stamina']  .': '.$this->printStat('stat_sta'); ?></li>
              <li><?php print $wordings[$lang]['intellect'].': '.$this->printStat('stat_int'); ?></li>
              <li><?php print $wordings[$lang]['spirit']   .': '.$this->printStat('stat_spr'); ?></li>
              <li><?php print $wordings[$lang]['armor']    .': '.$this->printStat('stat_armor'); ?></li>
            </ul>
            <ul class="stats">
              <li><?php print $wordings[$lang]['melee_att'] .' '.$this->printAtk('melee','rating')."\n"; ?>
                <ul>
                  <li><?php print $wordings[$lang]['power'] .': '. $this->printAtk('melee','power'); ?></li>
                  <li><?php print $wordings[$lang]['damage'].': '. $this->printAtk('melee','range'); ?></li>
                </ul></li>
              <li><?php print $wordings[$lang]['range_att'] .' '. $this->printAtk('ranged','rating')."\n"; ?>
                <ul>
                  <li><?php print $wordings[$lang]['power'] .': '. $this->printAtk('ranged','power'); ?></li>
                  <li><?php print $wordings[$lang]['damage'].': '. $this->printAtk('ranged','range'); ?></li>
                </ul></li>
            </ul>
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

if ($this->getNumPets($this->data['name'], $this->data['server']) > 0)
{
	$petTab = $this->printPet($this->data['name'], $this->data['server']);
	$tab = '
    <div class="page2" id="page2"><!-- begin char-main-page2 -->
      <div class="left"></div>
      <div class="pet"><!-- begin char-main-page2-pet -->
		    '.$petTab.'
      </div>
      <div class="right"></div>
		</div><!-- end char-main-page2 -->';
	print $tab;
}

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
if ($this->data['class'] == $wordings[$lang]['Hunter'] || $this->data['class'] == $wordings[$lang]['Warlock'])
{
	if ($this->getNumPets($this->data['name'],$this->data['server']) > 0)
	{
		print $this->printTab( $wordings[$lang]['tab2'], 'page2' );
		echo "\n";
	}
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
			die_quietly('Sorry no data in database for '.$_GET['name'].' of '.$_GET['server'],'Character Not Found');
		}
	}
}


function char_get_one_by_id( $member_id )
{
	global $wowdb;

	$name = $wowdb->escape( $name );
	$server = $wowdb->escape( $server );
	$query = "SELECT a.*, b.*, c.guild_name FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
	"WHERE a.member_id = b.member_id AND a.member_id = '$member_id' AND a.guild_id = c.guild_id";
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
	global $wowdb;

	$name = $wowdb->escape( $name );
	$server = $wowdb->escape( $server );
	$query = "SELECT a.*, b.*, c.guild_name FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
	"WHERE a.member_id = b.member_id AND a.name = '$name' AND a.server = '$server' AND a.guild_id = c.guild_id";
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


function DateCharDataUpdated($name)
{
	global $wowdb, $roster_conf, $phptimeformat;

	$query1 = "SELECT `dateupdatedutc` FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '$name'";
	$result1 = $wowdb->query($query1);
	$data1 = $wowdb->fetch_assoc($result1);
	$dateupdatedutc = $data1['dateupdatedutc'];
	$day = substr($dateupdatedutc,3,2);
	$month = substr($dateupdatedutc,0,2);
	$year = substr($dateupdatedutc,6,2);
	$hour = substr($dateupdatedutc,9,2);
	$minute = substr($dateupdatedutc,12,2);
	$second = substr($dateupdatedutc,15,2);

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

	$full = '&lt;span style=&quot;color:#'.$item_color.';&quot;&gt;'.str_replace('\'', '\\\'', $item_name) . '&lt;/span&gt; : ' . $modifier;

	if (array_key_exists($string, $myBonus)) {
		$myBonus[$string] = $myBonus[$string] + $modifier;
		$myTooltip[$string] = $myTooltip[$string] . '&lt;br /&gt;' . $full;
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


function dumpBonuses($char, $server)
{
	global $myBonus, $myTooltip, $wordings, $roster_conf, $wowdb;

	$server = $wowdb->escape( $server );
	$qry  = "SELECT i.item_tooltip, i.item_name, i.item_color, p.parry, p.dodge, p.block, p.crit, p.mitigation, p.clientLocale
			FROM `".ROSTER_ITEMSTABLE."` i, `".ROSTER_PLAYERSTABLE."` p
			WHERE i.item_parent = 'equip'
				and i.member_id = p.member_id
				and p.name = '".$char."'
				and p.server = '".$server."'";

	$result = $wowdb->query($qry) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry);
	while($row = $wowdb->fetch_array($result))
	{
		sortOutTooltip($row['item_tooltip'], $row['item_name'], substr($row['item_color'], 2, 6),$row['clientLocale'] );
	}

	$wowdb->data_seek($result, 0);
	$row = $wowdb->fetch_array($result);

	$bt .= border('sgray','start',$wordings[$roster_conf['roster_lang']]['itembonuses']).
		'<table style="width:400px;" class="bodyline" cellspacing="0" cellpadding="0" border="0">'."\n";

	$row = 0;
	foreach ($myBonus as $key => $value)
	{
		$bt .= '	<tr>
		<td class="membersRowRight'.(($row%2)+1).'" style="white-space:normal;" onmouseover="return overlib(\''.
		$myTooltip[$key].
		'\',CAPTION,\''.
		addslashes(str_replace('XX', $value, $key)).
		'\');" onmouseout="return nd();">'.
		str_replace('XX', $value, $key).'</td>
	</tr>';

		$row++;
	}
	$bt .= '</table>'.border('sgray','end');

	if( !empty($myBonus) )
		return $bt;
}

?>