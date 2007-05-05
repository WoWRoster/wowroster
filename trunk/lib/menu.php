<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Menu class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class RosterMenu
{
	function makeMenu($sections)
	{
		global $roster, $roster_login;

		define('ROSTER_MENU_INC',true);

		if( $roster->data == false )
		{
			$topbar = $left_pane = $right_pane = '';
		}
		else
		{
			if( $roster->config['menu_top_pane'] )
			{
				$topbar = "  <tr>\n"
						. '    <td colspan="3" align="center" valign="top" class="header">' . "\n"
						. '      <span style="font-size:18px;"><a href="' . $roster->config['website_address'] . '">' . $roster->config['guild_name'] . '</a></span>'."\n"
						. '      <span style="font-size:11px;"> @ ' . $roster->config['server_name'] . ' (' . $roster->config['server_type'] . ')</span><br />'
						. $roster->locale->act['lastupdate'].': <span style="color:#0099FF;">'.DateDataUpdated($roster->data['guild_dateupdatedutc'])
						. ((!empty($roster->config['timezone']))?' (' . $roster->config['timezone'] . ')':'')
						. "      </span>\n"
						. "    </td>\n"
						. "  </tr>\n"
						. "  <tr>\n"
						. '    <td colspan="3" class="simpleborder_b syellowborder_b"></td>' . "\n"
						. "  </tr>\n";
			}
			else
			{
				$topbar = '';
			}

			$left_pane = $this->makePane('menu_left');
			$right_pane = $this->makePane('menu_right');
		}

		$buttonlist = $this->makeButtonList($sections);

		return "\n".'<!-- Begin WoWRoster Menu -->'.
			border('syellow','start')."\n".
			'<table cellspacing="0" cellpadding="4" border="0" class="main_roster_menu">'."\n".
			$topbar.
			'  <tr>'."\n".
			$left_pane.
			$buttonlist.
			$right_pane.
			'  </tr>'."\n".
			'</table>'."\n".
			border('syellow','end')."\n".
			'<br />'."\n".
			'<!-- End WoWRoster Menu -->'."\n";
	}

	/**
	 * Builds either of the side panes.
	 *
	 * @param kind of pane to build
	 */
	function makePane( $side )
	{
		global $roster;

		switch( $roster->config[$side.'_type'] )
		{
			case 'level':
			case 'class':
				$pane = $this->makeList($roster->config[$side.'_type'], $roster->config[$side.'_level'], $roster->config[$side.'_style'], $side);
				break;
			case 'realm':
				$pane = $this->makeRealmStatus();
				break;
			default:
				$pane = '';
				break;
		}

		return $pane;
	}

	/**
	 * Make level/class distribution list
	 *
	 * @param string $type
	 *		'level' for level list
	 *		'class' for class list
	 * @param int $level
	 *		minimum level to display
	 * @param string $style
	 *		'list' for text list
	 *		'bar' for bargraph
	 *		'barlog' for logarithmic bargraph
	 * @param string $side
	 *		side this is appearing on, for the image to get the colors
	 */
	function makeList( $type, $level, $style, $side )
	{
		global $roster;

		// Initialize data array
		$dat = array();
		if( $type == 'level' )
		{
			for( $i=floor(ROSTER_MAXCHARLEVEL/10); $i>=floor($level/10); $i-- )
			{
				if( $i * 10 == ROSTER_MAXCHARLEVEL )
				{
					$dat[$i]['name'] = ROSTER_MAXCHARLEVEL;
				}
				elseif( $i * 10 + 9 >= ROSTER_MAXCHARLEVEL )
				{
					$dat[$i]['name'] = ($i*10).' - '.ROSTER_MAXCHARLEVEL;
				}
				else
				{
					$dat[$i]['name'] = ($i*10).' - '.($i*10+9);
				}
				$dat[$i]['alt'] = 0;
				$dat[$i]['nonalt'] = 0;
			}

			$qrypart = "FLOOR(`level`/10)";
		}
		elseif( $type == 'class' )
		{
			foreach($roster->locale->act['class_iconArray'] as $class => $icon)
			{
				$dat[$class]['name'] = $class;
				$dat[$class]['alt'] = 0;
				$dat[$class]['nonalt'] = 0;
			}

			$qrypart = "`class`";
		}
		else
		{
			die_quietly('Invalid list type','Menu Sidepane error',basename(__FILE__),__LINE__);
		}
		$num_alts = $num_non_alts = 0;

		// Build query
		$query  = "SELECT count(`member_id`) AS `amount`, ".
			"IF(`".$roster->config['alt_location']."` LIKE '%".$roster->config['alt_type']."%',1,0) AS 'isalt', ".
			$qrypart." AS label ".
			"FROM `".ROSTER_MEMBERSTABLE."` ".
			"WHERE `level` > ".$level." ".
			"GROUP BY isalt, label;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		}

		// Fetch results
		while( $row = $roster->db->fetch($result) )
		{
			if ($row['isalt'])
			{
				$num_alts += $row['amount'];
				$dat[$row['label']]['alt'] += $row['amount'];
			}
			else
			{
				$num_non_alts += $row['amount'];
				$dat[$row['label']]['nonalt'] += $row['amount'];
			}
		}

		$output = '	<td valign="top" class="row">
	      Total: '.$num_non_alts.' (+'.$num_alts.' Alts)'.($level>0?' Above L'.$level:'').'
			<br />';

		if( $style == 'bar' )
		{
			$req = 'graphs/bargraph.php?';
			$i = 0;
			foreach( $dat as $bar )
			{
				$req .= 'barnames['.$i.']='.$bar['name'].'&amp;';
				$req .= 'barsizes['.$i.']='.($bar['alt']+$bar['nonalt']).'&amp;';
				$req .= 'bar2sizes['.$i.']='.$bar['alt'].'&amp;';
				$i++;
			}
			$req .= 'type='.$type.'&amp;side='.$side;
			$req = str_replace(' ','%20',$req);

			$output .= '<img src="'.$roster->config['img_url'].$req.'" alt="" />';
		}
		elseif( $style == 'barlog' )
		{
			$req = 'graphs/bargraph.php?';
			$i = 0;
			foreach( $dat as $bar )
			{
				$req .= 'barnames['.$i.']='.$bar['name'].'&amp;';
				$req .= 'barsizes['.$i.']='.(($bar['alt']+$bar['nonalt']==0)?-1:log($bar['alt']+$bar['nonalt'])).'&amp;';
				$req .= 'bar2sizes['.$i.']='.(($bar['alt']==0)?-1:log($bar['alt'])).'&amp;';
				$i++;
			}
			$req .= 'type='.$type.'&amp;side='.$side;

			$output .= '<img src="'.$roster->config['img_url'].$req.'" alt="" />';
		}
		else
		{
			$output .= '<ul>'."\n";

			foreach( $dat as $line )
			{
				$output .= '<li>';
				$output .= $line['name'].': '.$line['nonalt'].' (+'.$line['alt'].' Alts)</li>'."\n";
			}
			$output .= '</ul>';
		}
		$output .= '</td>'."\n";

		return $output;
	}

	/**
	 * Makes the Realmstatus pane
	 *
	 * @return the formatted realmstatus pane
	 */
	function makeRealmStatus()
	{
		global $roster;

		$realmStatus = '    <td valign="top" class="row">' . "\n";
		if( $roster->config['rs_mode'] )
		{
			$realmStatus .= '      <img alt="WoW Server Status" src="realmstatus.php?r=' . ( !empty($roster->config['realmstatus']) ? $roster->config['realmstatus'] : $roster->data['server'] ) . '" />' . "\n";
		}
		elseif( file_exists(ROSTER_BASE . 'realmstatus.php') )
		{
			ob_start();
				include_once (ROSTER_BASE.'realmstatus.php');
			$realmStatus .= ob_get_clean() . "\n";
		}
		else
		{
			$realmStatus .= '&nbsp;';
		}

		$realmStatus .= "    </td>\n";

		return $realmStatus;
	}

	/**
	 * Builds the list of menu buttons for the specified sections
	 *
	 * @param array $sections the sections to render
	 * @return the formatted button grid.
	 */
	function makeButtonList($sections)
	{
		global $roster;

		if (is_array($sections))
		{
			$section = implode(',',$sections);
		}
		else
		{
			$section = $sections;
			$sections = array($section);
		}

		// --[ Fetch button list from DB ]--
		$query = "SELECT `mb`.*, `a`.`basename`
			FROM `".$roster->db->table('menu_button')."` AS mb
			LEFT JOIN `".$roster->db->table('addon')."` AS a
			ON `mb`.`addon_id` = `a`.`addon_id`;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch buttons from database. MySQL said: <br />'.$roster->db->error(),'Roster',basename(__FILE__),__LINE__,$query);
		}

		while ($row = $roster->db->fetch($result))
		{
			$palet['b'.$row['button_id']] = $row;
		}

		$roster->db->free_result($result);

		// --[ Fetch menu configuration from DB ]--
		$query = "SELECT * FROM `".$roster->db->table('menu')."` WHERE `section` IN ('".$section."');";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$roster->db->error(),'Roster',basename(__FILE__),__LINE__,$query);
		}

		while($row = $roster->db->fetch($result))
		{
			$data[$row['section']] = $row;
		}

		$roster->db->free_result($result);

		$page = array();

		foreach ($sections as $id=>$value)
		{
			if( isset($data[$section]) )
			{
				$page[$id] = $data[$value];
			}
		}

		// --[ Parse DB data ]--
		foreach ($page as $id => $value)
		{
			foreach (explode('|',$value['config']) AS $posX=>$column)
			{
				$config[$id][$posX] = explode(':',$column);
				foreach($config[$id][$posX] as $posY=>$button)
				{
					if( isset($palet[$button]) )
					{
						$arrayButtons[$id][$posX][$posY] = $palet[$button];
					}
				}
			}
		}


		$html  = '    <td valign="top" class="row links">'."\n";
		$html .= '      <table cellspacing="0" cellpadding="0" border="0" width="100%">'."\n";
		foreach( $arrayButtons as $id => $page )
		{
			$html .= '        <tr><td align="center" colspan="'.count($page).'"><span style="color:#0099FF;font-weight:bold;">'.$sections[$id].'</span></td></tr>'."\n";
			$html .= '        <tr>'."\n";
			foreach( $page as $column )
			{
				$html .= '          <td valign="top">'."\n";
				$html .= '            <ul>'."\n";
				foreach( $column as $button )
				{
					if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
					{
						// Include addon's locale files if they exist
						foreach( $roster->multilanguages as $lang )
						{
							if( file_exists(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php') )
							{
								add_locale_file(ROSTER_ADDONS.$button['basename'].DIR_SEP.'locale'.DIR_SEP.$lang.'.php',$lang,$roster->locale->wordings);
							}
						}
					}
					$html .= '              <li><a href="'.makelink($button['url']).'">'.( isset($roster->locale->act[$button['title']]) ? $roster->locale->act[$button['title']] : $button['title'] ).'</a></li>'."\n";
				}
				$html .= '            </ul>'."\n";
				$html .= '          </td>'."\n";
			}
			$html .= '        </tr>'."\n";
		}
		$html .= '      </table>'."\n";
		$html .= '    </td>'."\n";

		return $html;
	}
}
