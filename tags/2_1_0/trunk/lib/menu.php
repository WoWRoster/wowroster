<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Menu class
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
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

		$roster->tpl->assign_vars(array(
			'S_MENU_BOTTOM' => false
			)
		);

		$this->makeButtonList($sections);
	}

	function displayMenu( )
	{
		global $roster;

		if( defined('ROSTER_MENU_INC') )
		{
			// Create the mini update pop-up
			// Include update lib
			require_once (ROSTER_LIB . 'update.lib.php');
			$mini_update = new update();

			// Fetch addon data
			$mini_update->fetchAddonData();

			// Create the file fields
			$mini_update->makeFileFields('mini_file_fields');

			unset($mini_update);

			$roster->tpl->set_handle('roster_menu', 'menu.html');
			$roster->tpl->display('roster_menu');
		}
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
				}
			}
		}

		foreach( $arrayButtons as $id => $page )
		{
			switch( $id )
			{
				case 'char':
					$panel_label = $roster->data['name'] . ' @ ' . $roster->data['region'] . '-' . $roster->data['server'];
					break;

				default:
					$panel_label = (isset($roster->locale->act['menupanel_' . $id]) ? sprintf($roster->locale->act['menu_header_scope_panel'], $roster->locale->act['menupanel_' . $id]) : '');
					break;
			}

			$roster->tpl->assign_block_vars('menu_button_section', array(
				'ID' => $id,
				'OPEN' => !$sections[$id],
				'LABEL' => $panel_label
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
					'TOOLTIP'  => $button['tooltip'],
					'ICON'     => $button['icon'],
					'NAME'     => $button['title'],
					'SCOPE'    => $button['scope'],
					'BASENAME' => $button['basename'],
					'U_LINK'   => $button['url']
					)
				);
			}
		}

		// Restore our locale array
		$roster->locale->wordings = $localetemp;
		unset($localetemp);
	}
}
