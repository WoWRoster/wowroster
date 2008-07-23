<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Menu class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: menu.php 1791 2008-06-15 16:59:24Z Zanix $
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage Menu
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Roster Menu Library
 *
 * @package    WoWRoster
 * @subpackage Menu
 */
class RosterMenu
{
	function makeMenu( $sections )
	{
		global $roster;

		define('ROSTER_MENU_INC',true);

		$this->makeTop();
		$roster->tpl->assign_vars(array(
			'MENU_LEFT'     => $this->makePane('menu_left'),
			'MENU_RIGHT'    => $this->makePane('menu_right'),
			'S_MENU_BOTTOM' => false
			)
		);

		if( $roster->config['menu_bottom_pane'] )
		{
			$roster->tpl->assign_var('S_MENU_BOTTOM',true);
			$this->makeBottom();
		}

		$this->makeButtonList($sections);

		$roster->tpl->set_filenames(array('roster_menu' => 'menu.html'));
		$roster->tpl->display('roster_menu');
	}

	/**
	 * Build the top pane
	 */
	function makeTop( )
	{
		global $roster;

		$roster->tpl->assign_vars(array(
			'S_MENU_TOP_LOCALE' => (bool)$roster->config['menu_top_locale'],
			'S_MENU_ICON'       => $roster->config['menu_top_faction'],

			'L_LANGUAGE'        => $roster->locale->act['language'],
			'L_MENU_LABEL'      => $roster->scope,
			'L_MENU_LABEL_NAME' => $roster->locale->act[$roster->scope],
			)
		);


		if( $roster->config['menu_top_faction'] )
		{
			$faction = ( isset($roster->data['factionEn']) ? $roster->data['factionEn'] : '' );

			switch( substr($faction,0,1) )
			{
				case 'A':
					$roster->tpl->assign_var('ROSTER_MENU_ICON','icon_alliance.png');
					break;
				case 'H':
					$roster->tpl->assign_var('ROSTER_MENU_ICON','icon_horde.png');
					break;
				default:
					$roster->tpl->assign_var('ROSTER_MENU_ICON','icon_neutral.png');
					break;
			}
		}


		// Lets make a list of our current locales
		if( $roster->config['menu_top_locale'] )
		{
			foreach( $roster->multilanguages as $language )
			{
				$roster->tpl->assign_block_vars('menu_locale_select', array(
					'LOCALE'      => $language,
					'LOCALE_NAME' => $roster->locale->wordings[$language]['langname'],
					'S_SELECTED'  => ( $language == $roster->config['locale'] ? true : false ),
					)
				);
			}
		}


		$menu_select = array();
		$roster->tpl->assign_var('S_MENU_SELECT',false);

		if( $roster->scope == 'realm' )
		{
	        // Get the scope select data
	        $query = "SELECT DISTINCT `server`, `region`"
	               . " FROM `" . $roster->db->table('guild') . "`"
	               . " UNION SELECT DISTINCT `server`, `region` FROM `" . $roster->db->table('players') . "`"
	               . " ORDER BY `server` ASC;";

	        $result = $roster->db->query($query);

	        if( !$result )
	        {
	            die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$query);
	        }

	        $realms=0;
			while( $data = $roster->db->fetch($result,SQL_NUM) )
			{
				$menu_select[$data[1]][] = $data[0];
				$realms++;
			}

	        $roster->db->free_result($result);

	        $roster->tpl->assign_var('S_MENU_SELECT',( $realms > 1 ? true : false ));

			if( $realms > 1 )
			{
				foreach( $menu_select as $region => $realmsArray )
				{
					$roster->tpl->assign_block_vars('menu_select_group', array(
						'U_VALUE'      => $region,
						)
					);

					foreach( $realmsArray as $name )
					{
						$roster->tpl->assign_block_vars('menu_select_group.menu_select_row', array(
							'TEXT'       => $name,
							'U_VALUE'    => makelink("&amp;a=r:$region-$name",true),
							'S_SELECTED' => ( $name == $roster->data['server'] ? true : false )
							)
						);
					}
				}
			}
		}
		elseif( $roster->scope == 'guild' )
		{
			// Get the scope select data
			$query = "SELECT `guild_name`, CONCAT(`region`,'-',`server`), `guild_id` FROM `" . $roster->db->table('guild') . "`"
				   . " ORDER BY `region` ASC, `server` ASC, `guild_name` ASC;";

			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$query);
			}

	        $guilds=0;
			while( $data = $roster->db->fetch($result,SQL_NUM) )
			{
				$menu_select[$data[1]][$data[2]] = $data[0];
				$guilds++;
			}

			$roster->db->free_result($result);

	        $roster->tpl->assign_var('S_MENU_SELECT',( $guilds > 1 ? true : false ));

			if( count($menu_select) > 0 )
			{
				foreach( $menu_select as $realm => $guild )
				{
					$roster->tpl->assign_block_vars('menu_select_group', array(
						'U_VALUE'      => $realm,
						)
					);

					foreach( $guild as $id => $name )
					{
						$roster->tpl->assign_block_vars('menu_select_group.menu_select_row', array(
							'TEXT'       => $name,
							'U_VALUE'    => makelink('&amp;a=g:' . $id,true),
							'S_SELECTED' => ( $id == $roster->data['guild_id'] ? true : false )
							)
						);
					}
				}
			}
		}
		elseif( $roster->scope == 'char' )
		{
			// Get the scope select data
			$query = "SELECT `name`, `member_id` FROM `" . $roster->db->table('players') . "`"
				   . " WHERE `guild_id` = '" . $roster->data['guild_id'] . "'"
				   . " ORDER BY `name` ASC;";

			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$query);
			}

			while( $data = $roster->db->fetch($result,SQL_NUM) )
			{
				$menu_select[$data[1]] = $data[0];
			}

	        $roster->tpl->assign_var('S_MENU_SELECT',( $roster->db->num_rows() > 1 ? true : false ));

			$roster->db->free_result($result);

			if( count($menu_select) > 0 )
			{
				$roster->tpl->assign_block_vars('menu_select_group', array(
					'U_VALUE'      => $roster->data['guild_name'],
					)
				);

				foreach( $menu_select as $id => $name )
				{
					$roster->tpl->assign_block_vars('menu_select_group.menu_select_row', array(
						'TEXT'       => $name,
						'U_VALUE'    => makelink('&amp;a=c:' . $id,true),
						'S_SELECTED' => ( $id == $roster->data['member_id'] ? true : false )
						)
					);
				}
			}
		}

		switch( $roster->scope )
		{
			case 'util': case 'page':
				$roster->tpl->assign_vars(array(
					'S_MENU_SUBTITLE'   => isset($roster->config['default_desc']),
					'S_MENU_3RDTITLE'   => false,
					'ROSTER_MENU_TITLE' => $roster->config['default_name'],
					'ROSTER_MENU_SUBTITLE' => isset($roster->config['default_desc']) ? '<br />' . $roster->config['default_desc'] : '',
					)
				);
				break;

			case 'realm':
				$roster->tpl->assign_vars(array(
					'S_MENU_SUBTITLE'   => isset($roster->config['default_desc']),
					'S_MENU_3RDTITLE'   => false,
					'ROSTER_MENU_TITLE' => $roster->data['region'] . '-' . $roster->data['server'],
					'ROSTER_MENU_SUBTITLE' => '',
					)
				);
				break;

			case 'guild':
				$roster->tpl->assign_vars(array(
					'S_MENU_SUBTITLE'   => isset($roster->config['default_desc']),
					'S_MENU_3RDTITLE'   => isset($roster->data['update_time']) ? true : false,
					'ROSTER_MENU_TITLE' => $roster->data['guild_name'],
					'ROSTER_MENU_SUBTITLE' => '@ ' . $roster->data['region'] . '-' . $roster->data['server'],
					'ROSTER_MENU_3RDTITLE' => ( isset($roster->data['update_time']) ? readbleDate($roster->data['update_time'])
							. ( (!empty($roster->config['timezone'])) ? ' (' . $roster->config['timezone'] . ')' : '') : '' ),

					'L_LAST_UPDATE' => $roster->locale->act['lastupdate'],
					)
				);
				break;

			case 'char':
				$roster->tpl->assign_vars(array(
					'S_MENU_SUBTITLE'   => isset($roster->config['default_desc']),
					'S_MENU_3RDTITLE'   => isset($roster->data['update_time']) ? true : false,
					'ROSTER_MENU_TITLE' => $roster->data['name'],
					'ROSTER_MENU_SUBTITLE' => '@ ' . $roster->data['region'] . '-' . $roster->data['server'],
					'ROSTER_MENU_3RDTITLE' => $roster->data['update_format'],

					'L_LAST_UPDATE' => $roster->locale->act['lastupdate'],
					)
				);
				break;
		}
	}

	/**
	 * Builds either of the side panes.
	 *
	 * @param kind of pane to build
	 */
	function makePane( $side )
	{
		global $roster;

		switch( $roster->config[$side . '_type'] )
		{
			case 'level':
			case 'class':
				$pane = $this->makeList($roster->config[$side . '_type'], $roster->config[$side . '_level'], $roster->config[$side . '_style'], $side);
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
	function makeList( $type , $level , $style , $side )
	{
		global $roster;

		// Figure out the scope and limit accordingly.
		switch( $roster->scope )
		{
			case 'guild':
				// Restrict on the selected guild
				$where = "AND `guild_id` = '" . $roster->data['guild_id'] . "' ";
				break;

			case 'char':
				// Restrict on this char's guild
				$where = "AND `guild_id` = '" . $roster->data['guild_id'] . "' ";
				break;

			default:
				// util/pages uses all entries
				$where = '';
				break;
		}

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
					$dat[$i]['name'] = ($i*10) . ' - ' . ROSTER_MAXCHARLEVEL;
				}
				else
				{
					$dat[$i]['name'] = ($i*10) . ' - ' . ($i*10+9);
				}
				$dat[$i]['alt'] = 0;
				$dat[$i]['nonalt'] = 0;
			}

			$qrypart = "FLOOR(`level`/10)";
		}
		elseif( $type == 'class' )
		{
			foreach($roster->locale->act['id_to_class'] as $class_id => $class)
			{
				$dat[$class_id]['name'] = $class;
				$dat[$class_id]['alt'] = 0;
				$dat[$class_id]['nonalt'] = 0;
			}

			$qrypart = "`classid`";
		}
		else
		{
			die_quietly('Invalid list type','Menu Sidepane error',__FILE__,__LINE__);
		}
		$num_alts = $num_non_alts = 0;

		// Build query
		$query  = "SELECT count(`member_id`) AS `amount`, ";

		if( empty( $roster->config['alt_location'] ) || empty( $roster->config['alt_type'] ) )
		{
			$query .= "0 AS isalt, ";
		}
		else
		{
			$query .= "IF(`" . $roster->db->escape($roster->config['alt_location']) . "` LIKE '%" . $roster->db->escape($roster->config['alt_type']) . "%',1,0) AS isalt, ";
		}

		$query .= $qrypart . " AS label "
			. "FROM `" . $roster->db->table('members') . "` "
			. "WHERE `level` >= $level "
			. $where
			. "GROUP BY isalt, label;";

		$result = $roster->db->query($query);

		if( !$result )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}

		// Fetch results
		while( $row = $roster->db->fetch($result) )
		{
			$label = $row['label'];

			if( $row['isalt'] )
			{
				$num_alts += $row['amount'];
				$dat[$label]['alt'] += $row['amount'];
			}
			else
			{
				$num_non_alts += $row['amount'];
				$dat[$label]['nonalt'] += $row['amount'];
			}
		}
		//aprint($dat);die();

		// No entries at all? Then there's no data uploaded, so there's no use
		// rendering the panel.
		if( $num_alts + $num_non_alts == 0 )
		{
			return '';
		}

		$text = sprintf($roster->locale->act['menu_totals'], $num_non_alts, $num_alts) . ($level>0 ? sprintf($roster->locale->act['menu_totals_level'], $level) : '');
		$output = '	<td valign="top" align="left" class="row">';

		if( $style == 'bar' )
		{
			$req = 'graphs/bargraph.php?';
			$i = 0;
			foreach( $dat as $bar )
			{
				$req .= 'barnames[' . $i . ']=' . urlencode($bar['name']) . '&amp;';
				$req .= 'barsizes[' . $i . ']=' . ($bar['alt']+$bar['nonalt']) . '&amp;';
				$req .= 'bar2sizes[' . $i . ']=' . $bar['alt'] . '&amp;';
				$i++;
			}
			$req .= 'type=' . $type . '&amp;side=' . $side;
			$req = str_replace(' ','%20',$req);

			$output .= '<img src="' . $roster->config['img_url'] . $req . '" alt="" />';
		}
		elseif( $style == 'barlog' )
		{
			$req = 'graphs/bargraph.php?';
			$i = 0;
			foreach( $dat as $bar )
			{
				$req .= 'barnames[' . $i . ']=' . urlencode($bar['name']) . '&amp;';
				$req .= 'barsizes[' . $i . ']=' . (($bar['alt']+$bar['nonalt']==0) ? -1 : log($bar['alt']+$bar['nonalt'])) . '&amp;';
				$req .= 'bar2sizes[' . $i . ']=' . (($bar['alt']==0) ? -1 : log($bar['alt'])) . '&amp;';
				$i++;
			}
			$req .= 'type=' . $type . '&amp;side=' . $side;

			$output .= '<img src="' . $roster->config['img_url'] . $req . '" alt="" />';
		}
		else
		{
			$output .= "<ul>\n";

			foreach( $dat as $line )
			{
				$output .= '<li>';
				$output .= $line['name'] . ': ' . $line['nonalt'] . ' (+' . $line['alt'] . " Alts)</li>\n";
			}
			$output .= '</ul>';
		}
		$output .= "<br />$text</td>\n";

		return $output;
	}

	/**
	 * Makes the Realmstatus pane
	 *
	 * @return the formatted realmstatus pane
	 */
	function makeRealmStatus( )
	{
		global $roster;

		$realmStatus = '    <td valign="top" class="row">' . "\n";

		if( isset($roster->data['server']) )
		{
			$realmname = $roster->data['region'] . '-' . utf8_decode($roster->data['server']);
		}
		else
		{
			// Get the default selected guild from the upload rules
			$query =  "SELECT `name`, `server`, `region`"
					. " FROM `" . $roster->db->table('upload') . "`"
					. " WHERE `default` = '1' LIMIT 1;";

			$roster->db->query($query);

			if( $roster->db->num_rows() > 0 )
			{
				$data = $roster->db->fetch();

				$realmname = $data['region'] . '-' . utf8_decode($data['server']);
			}
			else
			{
				$realmname = '';
			}
		}

		if( !empty($realmname) )
		{
			if( $roster->config['rs_mode'] )
			{
				$realmStatus .= '      <img alt="WoW Server Status" src="' . ROSTER_URL . 'realmstatus.php?r=' . urlencode($realmname) . '" />' . "\n";
			}
			elseif( file_exists(ROSTER_BASE . 'realmstatus.php') )
			{
				ob_start();
					include_once (ROSTER_BASE . 'realmstatus.php');
				$realmStatus .= ob_get_clean() . "\n";
			}
			else
			{
				$realmStatus .= '&nbsp;';
			}

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
	function makeButtonList( $sections )
	{
		global $roster;

		// Save current locale array
		// Since we add all locales for button name localization, we save the current locale array
		// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
		$localetemp = $roster->locale->wordings;

		// Add all addon locale files
		foreach( $roster->addon_data as $addondata )
		{
			foreach( $roster->multilanguages as $lang )
			{
				$roster->locale->add_locale_file(ROSTER_ADDONS . $addondata['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
			}
		}

		$section = "'" . implode("','",array_keys($sections)) . "'";

		// --[ Fetch button list from DB ]--
		$query = "SELECT `mb`.*, `a`.`basename` "
			   . "FROM `" . $roster->db->table('menu_button') . "` AS mb "
			   . "LEFT JOIN `" . $roster->db->table('addon') . "` AS a "
			   . "ON `mb`.`addon_id` = `a`.`addon_id` "
			   . "WHERE `a`.`addon_id` IS NULL "
			   . "OR `a`.`active` = 1;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch buttons from database .  MySQL said: <br />' . $roster->db->error(),'Roster',__FILE__,__LINE__,$query);
		}

		while ($row = $roster->db->fetch($result,SQL_ASSOC))
		{
			$palet['b' . $row['button_id']] = $row;
		}

		$roster->db->free_result($result);

		// --[ Fetch menu configuration from DB ]--
		$query = "SELECT * FROM `" . $roster->db->table('menu') . "` WHERE `section` IN (" . $section . ") ORDER BY `config_id`;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch menu configuration from database. MySQL said: <br />' . $roster->db->error(),'Roster',__FILE__,__LINE__,$query);
		}

		while($row = $roster->db->fetch($result,SQL_ASSOC))
		{
			$data[$row['section']] = $row;
		}

		$roster->db->free_result($result);

		$page = array();
		$scopes = array();
		$arrayButtons = array();

		foreach( $sections as $name => $visible )
		{
			if( isset($data[$name]) )
			{
				$page[$name] = $data[$name];
			}
		}

		// --[ Parse DB data ]--
		foreach( $page as $name => $value )
		{
			$config[$name] = explode(':',$value['config']);
			foreach( $config[$name] as $pos=>$button )
			{
				if( isset($palet[$button]) )
				{
					$arrayButtons[$name][$pos] = $palet[$button];
					$scopes[$name] = true;
				}
			}

			if( $name == 'util')
			{
				$arrayButtons[$name] = array_reverse($arrayButtons[$name]);
			}
		}

		$roster->tpl->assign_vars(array(
			'L_MENU_HEADER_01' => $roster->locale->act['menu_header_01'],
			'L_MENU_HEADER_02' => $roster->locale->act['menu_header_02'],
			'L_MENU_HEADER_03' => $roster->locale->act['menu_header_03'],
			'L_MENU_HEADER_04' => $roster->locale->act['menu_header_04'],

			'S_MENU_HEADER_01' => isset($scopes['guild']) ? true : false,
			'S_MENU_HEADER_02' => isset($scopes['realm']) ? true : false,
			'S_MENU_HEADER_04' => isset($scopes['util']) ? true : false,

			'U_MENU_HEADER_03' => makelink('update'),
			)
		);

		foreach( $arrayButtons as $id => $page )
		{
			$roster->tpl->assign_block_vars('menu_button_section', array(
				'CLASS' => ( $id == 'util' ? 'utility' : 'scope' ),
				'ID' => $id,
				'OPEN' => !$sections[$id],
				'ALIGN' => ( $id == 'util' ? 'right' : 'left' ),
				'LABEL' => ( isset($roster->locale->act['menupanel_' . $id]) ? sprintf($roster->locale->act['menu_header_scope_panel'], $roster->locale->act['menupanel_' . $id]) : '' )
				)
			);

			foreach( $page as $button )
			{
				if( !empty($button['icon']) )
				{
					if( strpos($button['icon'],'.') !== false )
					{
						$button['icon'] = ROSTER_PATH . 'addons/' . $button['basename'] . '/images/' . $button['icon'];
					}
					else
					{
						$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/' . $button['icon'] . '.' . $roster->config['img_suffix'];
					}
				}
				else
				{
					$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
				}

				if( !in_array($button['scope'],array('util','realm','guild','char')) || $button['addon_id'] == 0 )
				{
					$button['url'] = makelink($button['url']);
				}
				elseif( substr($button['url'],0,7) != 'http://')
				{
					$button['url'] = makelink($button['scope'] . '-' . $button['basename'] . (empty($button['url']) ? '' : '-' . $button['url']));
				}

				$button['title'] = isset($roster->locale->act[$button['title']]) ? $roster->locale->act[$button['title']] : $button['title'];
				if( strpos($button['title'],'|') )
				{
					list($button['title'],$button['tooltip']) = explode('|',$button['title'],2);
					$button['tooltip'] = ' ' . makeOverlib($button['tooltip'],$button['title'],'',1,'',',WRAP');
				}
				else
				{
					$button['tooltip'] = ' ' . makeOverlib($button['title']);
				}

				$roster->tpl->assign_block_vars('menu_button_section.menu_buttons', array(
					'TOOLTIP' => $button['tooltip'],
					'ICON'    => $button['icon'],
					'NAME'    => $button['title'],
					'SCOPE'   => $button['scope'],
					'BASENAME'   => $button['basename'],
					'U_LINK'  => $button['url']
					)
				);
			}
		}

		// Restore our locale array
		$roster->locale->wordings = $localetemp;
		unset($localetemp);
	}
	/**
	 * Builds the bottom of the menu
	 */
	function makeBottom()
	{
		global $roster;

		$roster->tpl->assign_vars(array(
			'MENU_LOGIN_FORM' => ( is_object($roster->auth) ? $roster->auth->getMenuLoginForm() : '' ),

			'L_SEARCH'        => $roster->locale->act['search'],
			'L_SEARCH_ROSTER' => $roster->locale->act['search_roster'],

			'U_SEARCH_FORM_ACTION' => makelink('search')
			)
		);
	}
}
