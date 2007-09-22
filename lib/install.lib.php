<?php
/**
 * WoWRoster.net WoWRoster
 *
 * AddOn installation library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage AddonInstallLib
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Addon Install Library
 *
 * @package    WoWRoster
 * @subpackage AddonInstallLib
 */
class Install
{
	var $sql=array();	// install sql
	var $errors=array();	// errors
	var $messages=array();	// messages
	var $tables=array();	// $table=>boolean, true to restore, false to drop on rollback.
	var $addata;

	var $addon_id;

	/**
	 * Add a query to be installed.
	 *
	 * @param string $query
	 *		The query to add
	 */
	function add_query($query)
	{
		$this->sql[] = $query;
	}

	/**
	 * Have a table backed up for rollback
	 *
	 * @param string $table
	 *		Table name
	 */
	function add_backup($table)
	{
		$this->sql[] = 'CREATE TEMPORARY TABLE `backup_' . $table . '` LIKE `' . $table . '`';
		$this->sql[] = 'INSERT INTO `backup_' . $table . '` SELECT * FROM `' . $table . '`';
		$this->tables[$table] = true; // Restore backup on rollback
	}

	/**
	 * Have a table be dropped on rollback
	 *
	 * @param string $table
	 *		Table name
	 */
	function add_drop($table)
	{
		$this->tables[$table] = false; // Remove copy on rollback
	}

	/**
	 * Drops if exists then creates a new table with the correct charset
	 *
	 * @param string $name
	 * @param string $query
	 */
	function create_table( $name , $query )
	{
		$this->sql[] = 'DROP TABLE IF EXISTS `' . $name . '`;';
		$this->sql[] = 'CREATE TABLE `' . $name . '` ' . $query . ' ENGINE=MyISAM DEFAULT CHARSET=utf8;';
	}

	/**
	 * Drops a table, if it exists
	 *
	 * @param string $name
	 */
	function drop_table($name)
	{
		$this->sql[] = 'DROP TABLE IF EXISTS `' . $name . '`;';
	}

	/**
	 * Add config sql to roster_addon_config
	 *
	 * @param string $sql
	 *		SQL string to add to the roster_addon_config table
	 */
	function add_config($sql)
	{
		global $roster;

		$this->sql[] = "INSERT INTO `" . $roster->db->table('addon_config') . "` VALUES ('" . $this->addata['addon_id'] . "',$sql);";
	}

	/**
	 * Update a config setting
	 *
	 * @param int $id
	 *		Config ID to update
	 * @param string $sql
	 *		Set string
	 */
	function update_config($id, $sql)
	{
		global $roster;

		$this->sql[] = "UPDATE `" . $roster->db->table('addon_config') . "` SET " . $sql . " WHERE `addon_id` = '" . $this->addata['addon_id'] . "' AND `id` = '" . $id . "';";
	}

	/**
	 * Delete a config setting
	 *
	 * @param int $id
	 *		Config ID to delete
	 */
	function remove_config($id)
	{
		global $roster;

		$this->sql[] = "DELETE FROM `" . $roster->db->table('addon_config') . "` WHERE `addon_id` = '" . $this->addata['addon_id'] . "' AND `id` = '" . $id . "';";
	}

	/**
	 * Removes the all the config settings for an addon
	 */
	function remove_all_config()
	{
		global $roster;

		$this->sql[] = "DELETE FROM `" . $roster->db->table('addon_config') . "` WHERE `addon_id` = '" . $this->addata['addon_id'] . "';";
	}

	/**
	 * Add a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title
	 * @param string $scope
	 *		Scope to link to
	 * @param string $url
	 *		URL parameters for the addon function
	 * @param string $icon
	 * 		Icon for display
	 */
	function add_menu_button($title, $scope='util', $url='', $icon='')
	{
		global $roster;

		if( empty($icon) )
		{
			$icon = $this->addata['icon'];
		}

		$this->sql[] = "INSERT INTO `" . $roster->db->table('menu_button') . "` VALUES (NULL,'" . $this->addata['addon_id'] . "','" . $title . "','" . $scope . "','" . $url . "','" . $icon . "');";
		$this->sql[] = "UPDATE `" . $roster->db->table('menu') . "` SET `config` = CONCAT(`config`,':','b',LAST_INSERT_ID()) WHERE `section` = '" . $scope . "' LIMIT 1;";
	}

	/**
	 * Modify a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title
	 * @param string $scope
	 *		Scope to link to
	 * @param string $url
	 *		URL parameters for the addon function
	 * @param string $icon
	 * 		Icon for display
	 */
	function update_menu_button($title, $url='', $icon='')
	{
		global $roster;

		if( empty($icon) )
		{
			$icon = $this->addata['icon'];
		}

		$this->sql[] = "UPDATE `" . $roster->db->table('menu_button') . "` SET `url`='" . $url . "', `icon`='" . $icon . "' WHERE `addon_id`='" . $this->addata['addon_id'] . "' AND `title`='" . $title . "';";
	}

	/**
	 * Remove a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title.
	 */
	function remove_menu_button($title)
	{
		global $roster;

		$this->sql[] = 'DELETE FROM `'.$roster->db->table('menu_button').'` WHERE `addon_id`="'.$this->addata['addon_id'].'" AND `title`="'.$title.'";';
	}

	/**
	 * Removes the all the menu buttons for an addon
	 */
	function remove_all_menu_button()
	{
		global $roster;

		$this->sql[] = "DELETE FROM `" . $roster->db->table('menu_button') . "` WHERE `addon_id` = '" . $this->addata['addon_id'] . "';";
	}

	/**
	 * Do the actual installation.
	 *
	 * @return int
	 *		0 on success
	 *		1 on failure but successful rollback
	 *		2 on failed rollback
	 */
	function install()
	{
		global $roster;

		$retval = 0;
		foreach ($this->sql as $id => $query)
		{
			if (!$roster->db->query($query))
			{
				$this->seterrors('Install error in query '.$id.'. MySQL said: <br/>'.$roster->db->error().'<br />The query was: <br />'.$query);
				$retval = 1;
				break;
			}
		}
		if ($retval)
		{
			foreach ($this->tables as $table => $backup)
			{
				$query = 'DROP TABLE IF EXISTS `'.$table.'`';
				if ($result = $roster->db->query($query))
				{
					$roster->db->free_result($result);
				}
				else
				{
					$this->seterrors('Rollback error while dropping '.$table.'. MySQL said: '.$roster->db->error());
					$retval = 2;
				}
				if ($backup)
				{
					$query = 'CREATE TABLE `'.$table.'` LIKE `backup_'.$table.'`';
					if ($result = $roster->db->query($query))
					{
						$roster->db->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while recreating '.$table.'. MySQL said: '.$roster->db->error());
						$retval = 2;
					}
					$query = 'INSERT INTO `'.$table.'` SELECT * FROM `backup_'.$table.'`';

					if ($result = $roster->db->query($query))
					{
						$roster->db->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while reinserting data in '.$table.'. MySQL said: '.$roster->db->error());
						$retval = 2;
					}
				}
			}
		}
		return $retval;
	}

	/**
	 * Return full table name from base table name for the current addon and config profile.
	 *
	 * @param string $table base table name
	 * @param boolean $backup true to prepend backup (for temporary tables)
	 */
	function table($table, $backup=false)
	{
		global $roster;

		return (($backup) ? 'backup_' : '').$roster->db->table($table, $this->addata['basename']);
	}

	/**
	 * Set Error Message
	 *
	 * @param string $error
	 */
	function seterrors($error)
	{
		$this->errors[] = $error;
	}

	/**
	 * Return errors
	 *
	 * @return string errors
	 */
	function geterrors()
	{
		return implode("<br />\n",$this->errors);
	}

	/**
	 * Set Message
	 *
	 * @param string $message
	 */
	function setmessages($message)
	{
		$this->messages[] = $message;
	}

	/**
	 * Return messages
	 *
	 * @return string messages
	 */
	function getmessages()
	{
		return implode("<br />\n",$this->messages);
	}

	/**
	 * Return SQL
	 *
	 * @return string SQL
	 */
	function getsql()
	{
		return implode("<br />\n",$this->sql);
	}
}

$installer = new Install;
