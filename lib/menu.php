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
 * @package    WoWRoster
 * @subpackage Menu
*/

if( !defined('ROSTER_INSTALLED') )
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

		$topbar = $this->makeTop();
		$left_pane = $this->makePane('menu_left');
		$right_pane = $this->makePane('menu_right');

		if( $roster->config['menu_bottom_pane'] )
		{
			$bottombar = $this->makeBottom();
		}
		else
		{
			$bottombar = '';
		}


		$buttonlist = $this->makeButtonList($sections);

		return "\n<!-- Begin WoWRoster Menu -->"
			. border('syellow','start') . "\n"
			. '
<table cellspacing="0" cellpadding="4" border="0" class="main_roster_menu">' . "\n"
			. $topbar
			. "  <tr>\n"
			. $left_pane
			. $buttonlist
			. $right_pane
			. "  </tr>\n"
			. $bottombar
			. '</table>'."\n"
			. border('syellow','end') . "\n"
			. "<br />\n"
			. "<!-- End WoWRoster Menu -->\n";
	}

	/**
	 * Build the top pane
	 */
	function makeTop( )
	{
		global $roster;

		if( $roster->config['menu_top_faction'] )
		{
			$faction = ( isset($roster->data['factionEn']) ? $roster->data['factionEn'] : '' );

			switch( substr($faction,0,1) )
			{
				case 'A':
					$icon = '<img src="' . $roster->config['img_url'] . 'icon_alliance.png" style="float:left;" alt="" />';
					break;
				case 'H':
					$icon = '<img src="' . $roster->config['img_url'] . 'icon_horde.png" style="float:left;" alt="" />';
					break;
				default:
					$icon = '<img src="' . $roster->config['img_url'] . 'icon_neutral.png" style="float:left;" alt="" />';
					break;
			}
		}

		// Time to generate some select lists
		$choiceForm = '';

		// Lets make a list of our current locales
		if( $roster->config['menu_top_locale'] )
		{
			$choiceForm .= '	<form action="' . makelink() . '" name="locale_select" method="post" style="margin:0;">
		' . $roster->locale->act['language'] . ':
		<select name="locale" onchange="document.locale_select.submit();">' . "\n";
			foreach( $roster->multilanguages as $language )
			{
				$choiceForm .= '			<option value="' . $language . '"' . ( $language == $roster->config['locale'] ? ' selected="selected"' : '' ) . '>' . $roster->locale->wordings[$language]['langname'] . "</option>\n";
			}
			$choiceForm .= "\t\t</select>\n\t</form>\n";
		}



		$menu_select = array();
		$choices = '';
		if( $roster->scope == 'realm' )
		{
			$label = 'realm';

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

	        $realms = 0;
			while( $data = $roster->db->fetch($result,SQL_NUM) )
			{
				$menu_select[$data[1]][] = $data[0];
				$realms++;
			}

	        $roster->db->free_result($result);

			if( $realms > 1 )
			{
				foreach( $menu_select as $region => $realmsArray )
				{
					$choices .= ( count($menu_select) > 1 ? '			<optgroup label="' . $region . '">' . "\n" : '' );

					foreach( $realmsArray as $name )
					{
						$choices .= '				<option value="' . makelink("&amp;realm=$region-$name") . '"' . ( $name == $roster->data['server'] ? ' selected="selected"' : '' ) . '>' . $name . "</option>\n";
					}

					$choices .= ( count($menu_select) > 1 ? "\t\t\t</optgroup>\n" : '' );
				}
			}
		}
		elseif( $roster->scope != 'util' )
		{
			$label = 'guild';

			// Get the scope select data
			$query = "SELECT `guild_name`, CONCAT(`region`,'-',`server`), `guild_id` FROM `" . $roster->db->table('guild') . "`"
				   . " ORDER BY `region` ASC, `server` ASC, `guild_name` ASC;";

			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$query);
			}

			while( $data = $roster->db->fetch($result,SQL_NUM) )
			{
				$menu_select[$data[1]][$data[2]] = $data[0];
			}

			$roster->db->free_result($result);

			if( count($menu_select) > 0 )
			{
				foreach( $menu_select as $realm => $guild )
				{
					$choices .= ( count($menu_select) > 1 ? '		<optgroup label="' . $realm . '">' . "\n" : '' );

					foreach( $guild as $id => $name )
					{
						$choices .= '			<option value="' . makelink('&amp;guild=' . $id) . '"' . ( $id == $roster->data['guild_id'] ? ' selected="selected"' : '' ) . '>' . $name . "</option>\n";

					}

					$choices .= ( count($menu_select) > 1 ? "\t\t</optgroup>\n" : '' );
				}
			}
		}

		if( !empty($choices) )
		{
			$choiceForm .= '	<form action="' . makelink() . '" name="list_select" method="post" style="margin:0;">' . "\n";
			$choiceForm .= "\t\t" . $roster->locale->act[$label] . ":\n"
						 . '		<select name="' . $label . '" onchange="window.location.href=this.options[this.selectedIndex].value;">' . "\n"
						 . $choices . "\t\t</select>\n\t</form>\n";
		}

		if( !empty($choiceForm) )
		{
			$choiceForm = '<div align="right" style="float:right;">' . "\n" . $choiceForm . "</div>\n";
		}

		switch( $roster->scope )
		{
			case 'util':
				$menu_text = '	<span style="font-size:18px;">' . $roster->config['default_name'] . '</span><br />' . "\n"
						   . ( isset($roster->config['default_desc']) ? '      <span style="font-size:11px;">' . $roster->config['default_desc'] . "</span>\n" : '' );
				break;

			case 'realm':
				$menu_text = '	<span style="font-size:18px;">' . $roster->data['region'] . '-' . $roster->data['server'] . '</span>' . "<br />\n";
				break;

			case 'guild':
				$menu_text = '	<span style="font-size:18px;">' . $roster->data['guild_name'] . '</span>' . "\n"
						   . '	<span style="font-size:11px;"> @ ' . $roster->data['region'] . '-' . $roster->data['server'] . "</span><br />\n"
						   . ( isset($roster->data['guild_dateupdatedutc']) ? $roster->locale->act['lastupdate'] . ': <span style="color:#0099FF;">' . readbleDate($roster->data['guild_dateupdatedutc'])
						   . ( (!empty($roster->config['timezone'])) ? ' (' . $roster->config['timezone'] . ')</span>' : '</span>') : '' ) . "\n";
				break;

			case 'char':
				$menu_text = '	<span style="font-size:18px;">' . $roster->data['name'] . '</span>' . "\n"
						   . '	<span style="font-size:11px;"> @ ' . $roster->data['region'] . '-' . $roster->data['server'] . "</span><br />\n"
						   . $roster->locale->act['lastupdate'] . ': <span style="color:#0099FF;">' . $roster->data['update_format'] . "</span>\n";
				break;

		}

		return "	<tr>\n"
				. '		<td colspan="3" align="center" valign="top" class="header">' . "\n"
				. $choiceForm
				. $icon
				. '<div style="white-space:nowrap;">' . "\n"
				. $menu_text
				. "</div></td>\n"
				. "</tr>\n"
				. "<tr>\n"
				. '	<td colspan="3" class="divider_gold"><img src="' . $roster->config['img_url'] . 'pixel.gif" width="1" height="1" alt="" /></td>' . "\n"
				. "</tr>\n";
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
			die_quietly('Invalid list type','Menu Sidepane error',__FILE__,__LINE__);
		}
		$num_alts = $num_non_alts = 0;

		// Build query
		$query  = "SELECT count(`member_id`) AS `amount`, "
				. "IF(`" . $roster->config['alt_location'] . "` LIKE '%" . $roster->config['alt_type'] . "%',1,0) AS 'isalt', "
				. $qrypart . " AS label "
				. "FROM `" . $roster->db->table('members') . "` "
				. "WHERE `level` > $level "
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
			if( $row['isalt'] )
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

		// No entries at all? Then there's no data uploaded, so there's no use
		// rendering the panel.
		if( $num_alts + $num_non_alts == 0 )
		{
			return '';
		}

		$text = 'Total: ' . $num_non_alts . ' (+' . $num_alts . ' Alts)' . ($level>0 ? ' Above L' . $level : '');
		$output = '	<td valign="top" align="left" class="row">';

		if( $style == 'bar' )
		{
			$req = 'graphs/bargraph.php?';
			$i = 0;
			foreach( $dat as $bar )
			{
				$req .= 'barnames[' . $i . ']=' . $bar['name'] . '&amp;';
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
				$req .= 'barnames[' . $i . ']=' . $bar['name'] . '&amp;';
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
		$localestore = $roster->locale->wordings;

		if (is_array($sections))
		{
			$section = "'" . implode("','",$sections) . "'";
		}
		else
		{
			$section = "'" . $sections . "'";
			$sections = array($sections);
		}

		// --[ Fetch button list from DB ]--
		$query = "SELECT `mb`.*, `a`.`basename` "
			   . "FROM `" . $roster->db->table('menu_button') . "` AS mb "
			   . "LEFT JOIN `" . $roster->db->table('addon') . "` AS a "
			   . "ON `mb`.`addon_id` = `a`.`addon_id`;";

		$result = $roster->db->query($query);

		if (!$result)
		{
			die_quietly('Could not fetch buttons from database .  MySQL said: <br />' . $roster->db->error(),'Roster',__FILE__,__LINE__,$query);
		}

		while ($row = $roster->db->fetch($result))
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

		while($row = $roster->db->fetch($result))
		{
			$data[$row['section']] = $row;
		}

		$roster->db->free_result($result);

		$page = array();
		$scopes = array();
		$arrayButtons = array();

		foreach( $sections as $id=>$value )
		{
			if( isset($data[$value]) )
			{
				$page[$id] = $data[$value];
			}
		}

		// --[ Parse DB data ]--
		foreach( $page as $id => $value )
		{
			$config[$id] = explode(':',$value['config']);
			foreach( $config[$id] as $pos=>$button )
			{
				if( isset($palet[$button]) )
				{
					$arrayButtons[$id][$pos] = $palet[$button];
					$scopes[$sections[$id]] = true;
				}
			}

			if( $sections[$id] == 'util')
			{
				$arrayButtons[$id] = array_reverse($arrayButtons[$id]);
			}
		}

		$html = '    <td valign="top" class="row">' . "\n"
			. '      <div class="menu_container">' . "\n"
			. '        <div class="menu_header">' . "\n"
			. '          <ul>' . "\n"
			. '            <li><a href="#" class="menu_bg_01" onclick="' . (isset($scopes['guild']) ? 'showHide(\'menu_guild\');' : '') . 'return false;">' . $roster->locale->act['menu_header_01'] . '</a></li>' . "\n"
			. '            <li><a href="#" class="menu_bg_02" onclick="' . (isset($scopes['realm']) ? 'showHide(\'menu_realm\');' : '') . 'return false;">' . $roster->locale->act['menu_header_02'] . '</a></li>' . "\n"
			. '            <li><a href="' . makelink('update') . '" class="menu_bg_03">' . $roster->locale->act['menu_header_03'] . '</a></li>' . "\n"
			. '            <li><a href="#" class="menu_bg_04" onclick="' . (isset($scopes['util']) ? 'showHide(\'menu_util\');' : '') . 'return false;">' . $roster->locale->act['menu_header_04'] . '</a></li>' . "\n"
			. '          </ul>' . "\n"
			. '        </div>' . "\n";

		foreach( $arrayButtons as $id => $page )
		{
			if( ($sections[$id] == $roster->scope) || ( $id == count($sections) - 1 ) )
			{
				$open = true;
			}
			else
			{
				$open = false;
			}

			$html .= '        <div class="menu_' . ( $sections[$id] == 'util' ? 'utility' : 'scope' ) . '" id="menu_'.$sections[$id].'"' . (($open)?'':' style="display:none"') . '>'."\n"
				. '          <div align="'. ( $sections[$id] == 'util' ? 'right' : 'left' ) . '">' . sprintf($roster->locale->act['menu_header_scope_panel'], $roster->locale->act[$sections[$id]]) . '</div>' . "\n"
				. '          <ul>'."\n";
			foreach( $page as  $button )
			{
				if( $button['addon_id'] != '0' && !isset($roster->locale->act[$button['title']]) )
				{
					// Include addon's locale files if they exist
					foreach( $roster->multilanguages as $lang )
					{
						$roster->locale->add_locale_file(ROSTER_ADDONS . $button['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
					}
				}

				$button['icon'] = $roster->config['interface_url'] . 'Interface/Icons/' . (empty($button['icon'])?'inv_misc_questionmark':$button['icon']) . '.' . $roster->config['img_suffix'];

				if( $button['addon_id'] == 0 )
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

				$html .= '            <li' . $button['tooltip'] . ' style="background-image:url(' . $button['icon'] . '); background-position:center; background-repeat:no-repeat;"><a href="' . $button['url'] . '">' . "</a></li>\n";
			}
			$html .= '          </ul>' . "\n"
				. '        </div>' . "\n";
		}
		$html .= '      </div>' . "\n"
			. '    </td>' . "\n";

		// Restore our locale array
		$roster->locale->wordings = $localestore;
		unset($localestore);

		return $html;
	}
	/**
	 * Builds The search feilds
	 *
	 * 
	 * 
	 */
	function makeBottom()
	{
		global $roster, $open;

		$addonlist = array();
		foreach( $roster->addon_data as $name => $data )
		{
			$addon_search_file = ROSTER_ADDONS . $data['basename'] . DIR_SEP . 'inc' . DIR_SEP . 'search.inc';
			if( file_exists($addon_search_file) )
			{
				include_once($addon_search_file);
				$sclass = $data['basename'] . '_search';
				$basename = $data['basename'];
				if( class_exists($sclass) )
				{

					$addonlist[$basename]['search_class'] = $sclass;
					$addonlist[$basename]['addon'] = $data['basename'];
					$addonlist[$basename]['basename'] = ($data['fullname'] != '') ? $data['fullname'] : $data['basename'];
				}
			}
		}
		asort($addonlist);







		$output = '
	<tr>
		<td colspan="3" class="divider_gold"><img src="' . $roster->config['img_url'] . 'pixel.gif" width="1" height="1" alt="" /></td>
	</tr>
	<tr>
		<td colspan="3" align="center" valign="top" class="header" style="padding:0px;">

			<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\'data_search\',\'data_search_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
			<img src="'.$roster->config['img_url'] . (($open)?'minus':'plus') . '.gif" style="float:right;" alt="" id="data_search_img"/>Search the Roster
			</div>

		<div id="data_search" style="display:none;">';
		$output .=  '<br /><form id="s_addon" action="' . makelink('search') . '" method="post" enctype="multipart/form-data" >'
		.'<input size="25" type="text" name="search" value="" class="wowinput192" />'
		.'   '.'<input type="submit" value="' . $roster->locale->act['search'] . '" /><br />'
		.'<br />';


		$output .=  '<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\'sonly\',\'data_search_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
			<img src="'.$roster->config['img_url'] . (($open)?'minus':'plus') . '.gif" style="float:right;" alt="" id="sonly_img"/>' . $roster->locale->act['search_onlyin'] . '
			</div>';
		$output .= '<div id="sonly" >';
		$output .=  '<table border="0" ><tr>';



		$i = 0;
		//this is set to show a checkbox for all installed and active addons with search.inc files
		//it is set to only show 4 addon check boxes per row and allows for the search only in feature
		foreach ($addonlist as $s_addon) {
			if ($i && ($i % 4 == 0)) {
				$output .= '</tr><tr>';
				$output .= "\n";
			}

			$output .=  '<td><input type="checkbox"  id="menu_'. $s_addon['addon'] .'" name="$s_addon[]" value="'. $s_addon['addon'] .'" /></td>'.
			'<td><label for="menu_' . $s_addon['addon'] . '">' . $s_addon['basename'] . '</label></td>';


			$i++;
		}

		$output .=  '</tr></table>';
		$output .=  '</div>';
		//include advanced search options
		//the advanced options are defined in the addon search class using $search->options = then build your form/s
		foreach ($addonlist as $s_addon) {
			if (class_exists($s_addon['search_class'])) {
				$search = new $s_addon['search_class'];
				if ($search->options) {

					$output .= '<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\'' . $s_addon['basename'] . '\',\'data_search_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
			<img src="'.$roster->config['img_url'] . (($open)?'minus':'plus') . '.gif" style="float:right;" alt="" id="data_search_img"/>' . $roster->locale->act['search_advancedoptionsfor'] . ' ' . $s_addon['basename'] . ':
			</div>'; 
					$output .= '<div id="' . $s_addon['basename'] . '" style="display:none;">';
					$output .=  '<table width="100%" ><tr><td><br />' . $search->options . '<br /></td></tr></table>';
					$output .= '</div>';
				}
			}
		}
		$output .= '
				</form>
			</div>

			<script type="text/javascript">initARC(\'s_addon\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');initARC(\'searchformdata\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');</script></td>
	</tr>';

		return $output;
	}
}