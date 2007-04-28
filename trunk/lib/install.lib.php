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
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

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
		global $wowdb;

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
		$this->sql[] = 'CREATE TEMPORARY TABLE `backup_'.$table.'` LIKE `'.$table.'`';
		$this->sql[] = 'INSERT INTO `backup_'.$table.'` SELECT * FROM `'.$table.'`';
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
	 * Add config sql to roster_addon_config
	 *
	 * @param string $sql
	 *		SQL string to add to the roster_addon_config table
	 */
	function add_config($sql)
	{
		$this->sql[] = "INSERT INTO `".ROSTER_ADDONCONFTABLE."` VALUES ('".$this->addata['addon_id']."',$sql);";
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
		$this->sql[] = "UPDATE `".ROSTER_ADDONCONFTABLE."` SET ".$sql." WHERE `addon_id` = '".$this->addata['addon_id']."' AND `id` = '".$id."';";
	}

	/**
	 * Delete a config setting
	 *
	 * @param int $id
	 *		Config ID to delete
	 */
	function remove_config($id)
	{
		$this->sql[] = "DELETE FROM `".ROSTER_ADDONCONFTABLE."` WHERE `addon_id` = '".$this->addata['addon_id']."' AND `id` = '".$id."';";
	}

	/**
	 * Removes the all the config settings for an addon
	 */
	function remove_all_config()
	{
		$this->sql[] = 'DELETE FROM `'.ROSTER_ADDONCONFTABLE.'` WHERE `addon_id` = "'.$this->addata['addon_id'].'";';
	}

	/**
	 * Add a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title
	 * @param string $url
	 *		URL parameters for the addon function
	 * @param boolean $active
	 *		If this button should be active initially. If you specify your
	 *		addon not to be active on install, this parameter means if this
	 *		button is active after the addon is enabled.
	 */
	function add_menu_button($title, $url='')
	{
		$this->sql[] = 'INSERT INTO `'.ROSTER_MENUBUTTONTABLE.'` VALUES (NULL,"'.$this->addata['addon_id'].'","'.$title.'","'.$this->addata['basename'].$url.'");';
	}

	/**
	 * Modify a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title
	 * @param string $url
	 *		URL parameters for the addon function
	 * @param boolean $active
	 *		If this button should be active initially. If you specify your
	 *		addon not to be active on install, this parameter means if this
	 *		button is active after the addon is enabled.
	 */
	function update_menu_button($title, $url)
	{
		$this->sql[] = 'UPDATE `'.ROSTER_MENUBUTTONTABLE.'` SET `url`="'.$url.'" WHERE `addon_id`="'.$this->addata['addon_id'].'" AND `title`="'.$title.'";';
	}

	/**
	 * Remove a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title.
	 */
	function remove_menu_button($title)
	{
		$this->sql[] = 'DELETE FROM `'.ROSTER_MENUBUTTONTABLE.'` WHERE `addon_id`="'.$this->addata['addon_id'].'" AND `title`="'.$title.'";';
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
		global $wowdb;

		$retval = 0;
		foreach ($this->sql as $id => $query)
		{
			if (!$wowdb->query($query))
			{
				$this->seterrors('Install error in query '.$id.'. MySQL said: <br/>'.$wowdb->error().'<br />The query was: <br />'.$query);
				$retval = 1;
				break;
			}
		}
		if ($retval)
		{
			foreach ($this->tables as $table => $backup)
			{
				$query = 'DROP TABLE IF EXISTS `'.$table.'`';
				if ($result = $wowdb->query($query))
				{
					$wowdb->free_result($result);
				}
				else
				{
					$this->seterrors('Rollback error while dropping '.$table.'. MySQL said: '.$wowdb->error());
					$retval = 2;
				}
				if ($backup)
				{
					$query = 'CREATE TABLE `'.$table.'` LIKE `backup_'.$table.'`';
					if ($result = $wowdb->query($query))
					{
						$wowdb->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while recreating '.$table.'. MySQL said: '.$wowdb->error());
						$retval = 2;
					}
					$query = 'INSERT INTO `'.$table.'` SELECT * FROM `backup_'.$table.'`';

					if ($result = $wowdb->query($query))
					{
						$wowdb->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while reinserting data in '.$table.'. MySQL said: '.$wowdb->error());
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
		global $wowdb;

		return (($backup) ? 'backup_' : '').$wowdb->table($table, $this->addata['basename']);
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
