<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    InstanceKeys
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['keys'];

require_once (ROSTER_LIB . 'item.php');


$striping_counter = 1;
$tableHeader = '<table cellpadding="0" cellspacing="0">' . "\n";
$tableFooter = "</table>\n";

function borderTop()
{
	print border('sgray','start');
}

function tableHeaderRow($th)
{
	global $roster, $items, $tooltips;

	$acount = 0;
	print "  <tr>\n";
	foreach ($th as $header)
	{
		++$acount;
		if(isset($items[$header]) && $items[$header])
		{
			list($iname, $thottnum) = explode('|', $items[$header][$header]);
			// Item links
			$num_of_tips = (count($tooltips)+1);
			$linktip = '';
			foreach( $roster->locale->act['itemlinks'] as $ikey => $ilink )
			{
				$linktip .= '<a href="' . $ilink . urlencode(utf8_decode(stripslashes($iname))) . '" target="_blank">' . $ikey . '</a><br />';
			}
			setTooltip($num_of_tips,$linktip);
			setTooltip('itemlink',$roster->locale->act['itemlink']);

			$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);" onmouseout="return nd();"';

			$header = '<a href="#"' . $linktip . '>' . $header . '</a>';
		}
		if ($acount == 1)
		{
			print '    <th class="membersHeader">' . $header . "</th>\n";
		}
		elseif ($acount == count($th))
		{
			print '    <th class="membersHeaderRight">' . $header . "</th>\n";
		}
		else
		{
			print '    <th class="membersHeader" align="center">' . $header . "</th>\n";
		}
	}
	print "  </tr>\n";
}

function borderBottom()
{
	print border('sgray','end');
}

function rankLeft($sc)
{
	print '    <td class="membersKeyRowLeft' . $sc . '">';
}

function rankMid($sc)
{
	print '    <td class="membersKeyRow' . $sc . '">';
}

function rankRight($sc)
{
	print '    <td class="membersKeyRowRight' . $sc . '">';
}

function buildSQL($item,$key,$type)
{
	global $selectp, $wherep, $pcount, $selectq, $whereq, $qcount;

	$alist = explode('|', $item);

	$iname = isset($alist[0]) ? $alist[0] : '';

	if ($type == 'quest')
	{
		++$pcount;
		$selectq .= ", SUM(IF(`quests`.`quest_name` = '$iname', 1, 0)) AS $key";
		if ($pcount == 1)
		{
			$whereq .= " `quests`.`quest_name` = '$iname'";
		}
		else
		{
			$whereq .= " OR `quests`.`quest_name` = '$iname'";
		}
	}
	else
	{
		++$qcount;
		$selectp .= ", SUM(IF(items.item_name = '$iname', 1, 0)) AS $key";
		if ($qcount == 1)
		{
			$wherep .= " items.item_name = '$iname'";
		}
		else
		{
			$wherep .= " OR items.item_name = '$iname'";
		}
	}
}

//Minimum lockpicking skill to get it, 1000 indicates that the lock can't be picked
$min_skill_for_lock = array(
	'SG' => 225,
	'Gnome' => 150,
	'SM' => 175,
	'ZF' => 1000,
	'Mauro' => 1000,
	'BRDp' => 250,
	'BRDs' => 1000,
	'DM' => 295,
	'Scholo' => 280,
	'Strath' => 295,
	'UBRS' => 1000,
	'Onyxia' => 1000,
	'MC' => 1000,
);

$items = $roster->locale->act['inst_keys'][ substr($roster->data['factionEn'],0,1) ];
$keys = array('Name');
foreach ($items as $key => $data)
{
	array_push($keys,$key);
}

borderTop();
print($tableHeader);
tableHeaderRow($keys);

$query = "SELECT `name`, `level`, `member_id`, `class`, `clientLocale` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' GROUP BY `name` ORDER BY `name` ASC;";
$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

while ($row = $roster->db->fetch($result))
{
	if ($row['clientLocale'] == '')
	{
		$row['clientLocale'] = $roster->config['locale'];
	}
	$items = $roster->locale->act['inst_keys'][ substr($roster->data['factionEn'],0,1) ];
	// build SQL search string for the instance keys only
	$selectk = ''; $wherek = ''; $countk = 0;
	foreach ($items as $key => $item)
	{
		foreach ($items[$key] as $subkey => $subitem)
		{
			$onechar = substr($subkey, 0, 1);
			if (!is_numeric($onechar))
			{
				++$countk;
				list($iname, $thottnum) = explode('|', $subitem);
				$selectk .= ", SUM(if(`items`.`item_name` = '$iname', -1, 0)) AS $key";
				if ($countk == 1)
				{
					$wherek .= " `items`.`item_name` = '$iname'";
				}
				else
				{
					$wherek .= " OR `items`.`item_name` = '$iname'";
				}
			}
		}
	}
	// instance key search
	$kquery = "SELECT `members`.`name`$selectk FROM `" . $roster->db->table('items') . "` AS items LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `items`.`member_id` WHERE `items`.`member_id` = '" . $row['member_id'] . "' AND ($wherek) GROUP BY `members`.`name`;";
	$kresult = $roster->db->query($kquery) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$kquery);
	$krow = $roster->db->fetch($kresult);
	$kcount = 0; // counts how many keys this player has. if 0 at the end don't display
	$selectp = ''; $wherep = ''; $pcount = 0;
	$selectq = ''; $whereq = ''; $qcount = 0;
	// ==============================
	// VALUE:MEANING for $krow[$key]:
	// ==============================
	// -1: player has the key
	// -2: player (rogue) can pick the lock but doesn't have the key
	//  0: no access
	// 1+: current quest step
	// 0|1|2|...: completed steps
	// ==============================
	foreach ($items as $key => $item)
	{
		if ( isset($krow[$key]) && $krow[$key] == '-1')
		{
			++$kcount;
		}
		else
		{
			if (($row['class'] == $roster->locale->wordings[$row['clientLocale']]['Rogue']) && ($row['level'] >= 16))
			{
				$squery = "SELECT `skill_level` FROM `" . $roster->db->table('skills') . "` WHERE `member_id` = " . $row['member_id'] . " AND `skill_name` = '" . $roster->locale->wordings[$row['clientLocale']]['lockpicking'] . "';";
				$sresult = $roster->db->query($squery) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$squery);
				$srow = $roster->db->fetch($sresult);
				list($current_skill,$max_skill) = explode(':',$srow['skill_level']);
				$roster->db->free_result($sresult);

				// This is for armory uploads where rouges have the skill, but not the item
				$iname = $roster->locale->wordings[$row['clientLocale']]['thievestools'];
				$iquery = "SELECT `item_name` FROM `" . $roster->db->table('items') . "` WHERE `item_name` = '$iname' AND `member_id` = '" . $row['member_id'] . "';";
				$iresult = $roster->db->query($iquery);

				if ( $roster->db->num_rows() > 0 && $current_skill >= $min_skill_for_lock[$key])
				{
					$krow[$key] = '-2';
					++$kcount;
					continue;
				}
			}
			if ($items[$key][0] == 'Quests')
			{
				$type = 'quest';
			}
			else if ($items[$key][0] == 'Parts')
			{
				$type = 'item';
			}
			else
			{
				continue;
			}
			for ($acount=1;$acount<count($items[$key])-1;$acount++)
			{
				buildSQL($items[$key][$acount], "${key}$acount", $type);
			}
		}
	}

	if ($selectp != '')
	{
		// parts search (only the remaining ones!)
		$queryp = "SELECT `members`.`name`$selectp FROM `" . $roster->db->table('items') . "` AS items LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `items`.`member_id` WHERE `items`.`member_id` = '" . $row['member_id'] . "' AND ($wherep) GROUP BY `members`.`name` ORDER BY `members`.`name` ASC;";
		$presult = $roster->db->query($queryp) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$queryp);
		$prow = $roster->db->fetch($presult);
		if (is_array($prow))
		{
			foreach ($prow as $pkey => $pvalue)
			{
				if ( $pvalue == 1 && !is_numeric($pkey))
				{
					++$kcount;
					$key = preg_replace('/[0-9]/', '', $pkey);
					$step = preg_replace('/[A-Za-z]/', '', $pkey);
					if( !isset($items[$key]) )
					{
						continue;
					}
					list($junk,$milestone) = explode('|',$items[$key][$step]);
					if ($milestone == 'MS')
					{
						$krow[$key] = '0';
						for ($i=1;$i<=$step;$i++)
						{
							$krow[$key] .= "|$i";
						}
					}
					else
					{
						$krow[$key] .= "|$step";
					}
				}
			}
		}
		$roster->db->free_result($presult);
	}
	if ($selectq != '')
	{
		// quests search (only the remaining ones!)
		$queryq = "SELECT `members`.`name`$selectq FROM `" . $roster->db->table('quests') . "` AS quests LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `quests`.`member_id` WHERE `quests`.`member_id` = '" . $row['member_id'] . "' AND ($whereq) GROUP BY `members`.`name` ORDER BY `members`.`name` ASC;";
		$qresult = $roster->db->query($queryq) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$queryq);
		$qrow = $roster->db->fetch($qresult);
		if (is_array($qrow))
		{
			foreach ($qrow as $qkey => $qvalue)
			{
				if ($qvalue == 1 && !is_numeric($qkey))
				{
					++$kcount;
					$key = preg_replace('/[0-9]/', '', $qkey);
					$step = preg_replace('/[A-Za-z]/', '', $qkey);
					$krow[$key] = $step;
				}
			}
		}
		$roster->db->free_result($qresult);
	}
	if ($kcount == 0)
	{
		continue; // nothing to display -> next player
	}

	// ========================================================================
	// ----------------------------> DISPLAY CODE <----------------------------
	// ========================================================================
	++$striping_counter;
	print "<tr>\n";
	$acount = 0;
	rankLeft((($striping_counter % 2) +1));
	print ( active_addon('info') ? '<a href="' . makelink('char-info&amp;a=c:' . $row['member_id']) . '">' . $row['name'] . '</a>' : $row['name'] ) . '<br />' . $row['class'] . ' (' . $row['level'] . ')</td>' . "\n";
	foreach ($items as $key => $data)
	{
		++$acount;
		if($acount == count($items))
		{
			rankRight((($striping_counter % 2) +1));
		}
		else
		{
			rankMid((($striping_counter % 2) +1));
		}
		if (isset($krow[$key]) && $krow[$key] == '-2')
		{
			$iname = $roster->locale->wordings[$row['clientLocale']]['thievestools'];
			$iquery = "SELECT * FROM `" . $roster->db->table('items') . "` WHERE `item_name` = '$iname' AND `member_id` = '" . $row['member_id'] . "';";
			$iresult = $roster->db->query($iquery);
			$idata = $roster->db->fetch($iresult);
			$item = new item($idata, 'simple');
			print $item->out($key);
		}
		elseif (isset($krow[$key]) && $krow[$key] == '-1')
		{
			list($iname, $thottnum) = explode('|', $data[$key]);
			if(isset($$key))
			{
				print($$key);
			}
			else
			{
				$iquery = "SELECT * FROM `" . $roster->db->table('items') . "` WHERE `item_name` = '$iname' AND `member_id` = '" . $row['member_id'] . "';";
				$iresult = $roster->db->query($iquery);
				$idata = $roster->db->fetch($iresult);
				$item = new item($idata, 'simple');
				$$key = $item->out();
				print $$key;
			}
		}
		elseif (isset($krow[$key]) && $krow[$key] == '0')
		{
			print '&nbsp;';
		}
		elseif (!isset($krow[$key]) || $krow[$key] == '')
		{
			print '&nbsp;';
		}
		else
		{
			list($iname, $thottnum) = explode('|', $items[$key][$key]);
			$qcount = count($items[$key])-2;    //number of parts/quests
			if ($items[$key][0] == 'Quests')    //-> $krow[$key] = "5" (e.g.)
			{
				$bcount = $krow[$key];
			}
			else
			{                             //-> $krow[$key] = "0|1|2|3" (e.g.)
				$parray = explode('|',$krow[$key]); //array for completed parts
				$bcount = count($parray)-1;
			}

			$tooltip_h = sprintf($roster->locale->act['key_status'],$key,$roster->locale->act['key']);

			$tooltip = '<span style="color:' . $addon['config']['colorcmp'] . ';">' . $roster->locale->act['completedsteps'] . '</span><br />';
			if ($items[$key][0] == 'Quests')
			{
				$tooltip .= '<span style="color:' . $addon['config']['colorcur'] . ';">' . $roster->locale->act['currentstep'] . '</span><br />';
			}
			$tooltip .= '<span style="color:' . $addon['config']['colorno'] . ';">' . $roster->locale->act['uncompletedsteps'] . '</span><br /><br />';
			if ($items[$key][0] == 'Quests')
			{
				for ($i=1;$i<count($items[$key])-1;$i++)
				{
					if ($krow[$key]>$i)
					{
						$color = $addon['config']['colorcmp'];
					}
					else if ($krow[$key]==$i)
					{
						$color = $addon['config']['colorcur'];
					}
					else
					{
						$color = $addon['config']['colorno'];
					}
					list($qname,$junk) = explode('|',$items[$key][$i]);
					$qname = preg_replace('/\\\/', '', $qname);
					$tooltip .= '<span style="color:' . $color . ';">' . $i . ': ' . $qname . '</span><br />';
				}
			}
			else
			{
				$j=1;
				for ($i=1;$i<count($items[$key])-1;$i++)
				{
					if ($j < count($parray) && $parray[$j] == $i)
					{
						$color = $addon['config']['colorcmp'];
						$j++;
					}
					else
					{
						$color = $addon['config']['colorno'];
					}
					list($pname,$junk) = explode('|',$items[$key][$i]);
					$pname = preg_replace('/\\\/', '', $pname);
					$tooltip .= '<span style="color:' . $color . ';">' . $i . ': ' . $pname . '</span><br />';
				}
			}

			$pcent = round(($bcount/$qcount) * 100);

			// Item links
			$num_of_tips = (count($tooltips)+1);
			$linktip = '';
			foreach( $roster->locale->act['itemlinks'] as $ikey => $ilink )
			{
				$linktip .= '<a href="' . $ilink . urlencode(utf8_decode(stripslashes($iname))) . '" target="_blank">' . $ikey . '</a><br />';
			}
			setTooltip($num_of_tips,$linktip);
			setTooltip('itemlink',$roster->locale->act['itemlink']);

			$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

			echo '<div style="cursor:pointer;" ' . makeOverlib($tooltip,$tooltip_h,'',2) . $linktip . ">\n";
			print '<span class="name">' . $roster->locale->act[$items[$key][0]] . "</span>\n";

			print '<div class="levelbarParent" style="width:40px;"><div class="levelbarChild">' . $bcount . '/' . $qcount . '</div></div>' . "\n";
			print '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="40">' . "\n";
			print "<tr>\n";
			print '<td style="background-image: url(\'' . $roster->config['img_url'] . 'expbar-var2.gif\');" width="' . $pcent . '%"><img src="' . $roster->config['img_url'] . 'pixel.gif" height="14" width="1" alt="" /></td>' . "\n";
			print '<td width="' . (100 - $pcent) . '%"></td>' . "\n";
			print "</tr>\n</table>\n</div>\n";
		}

		print "</td>\n";
	}
	print("  </tr>\n");
	$roster->db->free_result($kresult);
}
$roster->db->free_result($result);

print($tableFooter);
borderBottom();
