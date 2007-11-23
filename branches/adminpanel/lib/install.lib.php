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

class Install
{
	var $sql=array();	// install sql
	var $errors=array();	// errors
	var $messages=array();	// messages
	var $tables=array();	// $table=>boolean, true to restore, false to drop on rollback.
	var $profile='default';	// Profile to add tables/data to
	var $addata;

	var $addon_id;

	/**
	 * Add a query to be installed.
	 *
	 * @param string $query_type
	 *      One of BACKUP, CREATE, DROP, REN, ADD, DEL, CHANGE, INSERT, DELETE, UPDATE
	 * @param string $table
	 *      Base table name
	 * @param string $data
	 *      Contents of the SQL query. Look down for details.
	 */
	function add_query($query_type, $table, $data='')
	{
		global $wowdb;

		switch ($query_type)
		{
		// Backup a table to be restored in case of rollback
		case 'BACKUP':		// $data is ignored
			$this->sql[] = 'CREATE TEMPORARY TABLE `'.$this->table($table, $this->profile, true).'` LIKE `'.$this->table($table, $this->profile).'`';
			$this->sql[] = 'INSERT INTO `'.$this->table($table, $this->profile, true).'` SELECT * FROM `'.$this->table($table, $this->profile).'`';
			$this->tables[$this->profile.'_'.$table] = true; // Restore backup on rollback
			break;

		// Rename a table from the old table scheme to update addons from pre-1.8
		case 'LEGACY':		// $data holds a full table name
			$this->sql[] = 'DROP TABLE IF EXISTS `'.$this->table($table, $this->profile).'`';
			$this->sql[] = 'CREATE TABLE `'.$this->table($table, $this->profile).'` LIKE `'.$data.'`';
			$this->sql[] = 'INSERT INTO `'.$this->table($table, $this->profile).'` SELECT * FROM `'.$this->table($table, $this->profile).'`';
			$this->tables[$this->profile.'_'.$table] = false; // Remove copy on rollback
			break;

		// Table queries
		case 'CREATE':		// $data holds column and key definitions
			$this->sql[] = 'DROP TABLE IF EXISTS `'.$this->table($table, $this->profile).'`';
			$this->sql[] = 'CREATE TABLE `'.$this->table($table, $this->profile).'` ('.$data.') TYPE=MyISAM';
			if (!array_key_exists($this->profile.'_'.$table,$this->tables))
				$this->tables[$this->profile.'_'.$table] = false; // Drop on rollback if there's no backup set
			break;

		case 'DROP':		// $data is ignored
			$this->sql[] = 'DROP TABLE IF EXISTS `'.$this->table($table, $this->profile).'`';
			break;

		case 'REN':		// $data hods a table identifier
			$this->sql[] = 'DROP TABLE IF EXISTS `'.$this->table($table, $this->profile).'`';
			$this->sql[] = 'RENAME TABLE `'.$this->table($table, $this->profile).'` TO `'.$this->table($data, $this->profile).'`';
			$this->tables[$this->profile.'_'.$data] = false; // Drop the renamed table on rollback
			break;

		// Column queries
		case 'ADD':		// $data hold one column, key, index, or constraint definition
			$this->sql[] = 'ALTER TABLE `'.$this->table($table, $this->profile).'` ADD '.$data;
			break;

		case 'DEL':		// $data holds one column name, KEY keyname, INDEX indexname, or PRIMARY KEY
			$this->sql[] = 'ALTER TABLE `'.$this->table($table, $this->profile).'` DROP '.$data;
			break;

		case 'CHANGE':		// $data holds one column name followed by one column definition
			$this->sql[] = 'ALTER TABLE `'.$this->table($table, $this->profile).'` CHANGE '.$data;

		// Data queries
		case 'INSERT':		// $data holds comma-seperated data for each column.
			$this->sql[] = 'INSERT INTO `'.$this->table($table, $this->profile).'` VALUES ('.$data.')';
			break;

		case 'DELETE':		// $data holds comma-seperated column=condition pairs.
			$this->sql[] = 'DELETE FROM `'.$this->table($table, $this->profile).'` WHERE '.$data;
			break;

		case 'UPDATE':		// $data holds column=data WHERE column=condition
			$this->sql[] = 'UPDATE `'.$this->table($table, $this->profile).'` SET '.$data;
			break;

		default:
			$this->seterrors('Invalid query type '.$query_type.'. Table is '.$table.', active profile is '.$this->profile.', and data is '.$data);
		}
	}

	/**
	 * Add a front page menu button
	 *
	 * @param string $title
	 *	Localization key for the button title
	 * @param string $url
	 *	URL parameters for the addon function
	 * @param boolean $active
	 *	If this button should be active initially. If you specify your
	 *      addon not to be active on install, this parameter means if this
	 *      button is active after the addon is enabled.
	 */
	function add_menu_button($title, $url, $creds)
	{
		$this->sql[] = 'INSERT INTO `'.ROSTER_MENUBUTTONTABLE.'` VALUES (0,"'.$this->addata['addon_id'].'","'.$title.'","'.ROSTER_URL.'/addon.php?dbname='.$this->addata['dbname'].$url.'","'.$creds.'")';
	}

	/**
	 * Modify a front page menu button
	 *
	 * @param string $title
	 *	Localization key for the button title
	 * @param string $url
	 *	URL parameters for the addon function
	 * @param boolean $active
	 *	If this button should be active initially. If you specify your
	 *      addon not to be active on install, this parameter means if this
	 *	button is active after the addon is enabled.
	 */
	function update_menu_button($title, $url, $creds)
	{
		$this->sql[] = 'UPDATE `'.ROSTER_MENUBUTTONTABLE.'` SET `url`="'.ROSTER_URL.'addon.php?dbname='.$this->addata['dbname'].$url.'", `creds`="'.$creds.'" WHERE `addon_id`="'.$this->addata['addon_id'].'" AND `title`="'.$title.'"';
	}

	/**
	 * Remove a front page menu button
	 *
	 * @param string $title
	 *	Localization key for the button title.
	 */
	function remove_menu_button($title)
	{
		$this->sql[] = 'DELETE FROM `'.ROSTER_MENUBUTTONTABLE.'` WHERE `addon_id`="'.$this->addata['addon_id'].'" AND `title`="'.$title.'"';
	}

	/**
	 * Register a file for updates
	 *
	 * @param string $file
	 *	Name of the WoW addon
	 * @param boolean $active
	 *	If this trigger should be active initially. If you specify your
	 *	addon not to be active on install, this parameter means if this
	 *	trigger is active after the addon in enabled.
	 */
	function add_trigger($file, $active)
	{
		global $wowdb;

		$this->sql[] = 'INSERT INTO `'.$wowdb->table('addon_trigger').'` VALUES (0,"'.$this->addata['addon_id'].'","'.$file.'","'.$active.'")';
	}

	/**
	 * Unregister a file for updates
	 *
	 * @param string $file
	 *	Name of the WoW addon
	 */
	 function remove_trigger($file)
	 {
	 	global $wowdb;

	 	$this->sql[] = 'DELETE FROM `'.$wowdb->table('addon_trigger').'` WHERE `addon_id`="'.$this->addata['addon_id'].'" AND `file`="'.$file.'"';
	 }

	/**
	 * Do the actual installation.
	 *
	 * @return int
	 *	0 on success
	 *	1 on failure but successful rollback
	 *	2 on failed rollback
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
				$table = explode('_',$table);
				$query = 'DROP TABLE IF EXISTS `'.$this->table($table[1],$table[0]).'`';
				if ($result = $wowdb->query($query))
				{
					$wowdb->free_result($result);
				}
				else
				{
					$this->seterrors('Rollback error while dropping '.$table[1].' for profile '.$table[0].'. MySQL said: '.$wowdb->error());
					$retval = 2;
				}
				if ($backup)
				{
					$query = 'CREATE TABLE `'.$this->table($table[1],$table[0]).'` LIKE `'.$this->table($table[1],$table[0],true).'`';
					if ($result = $wowdb->query($query))
					{
						$wowdb->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while recreating '.$table[1].' for profile '.$table[0].'. MySQL said: '.$wowdb->error());
						$retval = 2;
					}
					$query = 'INSERT INTO `'.$this->table($table[1],$table[0]).'` SELECT * FROM `'.$this->table($table[1],$table[0],true).'`';

					if ($result = $wowdb->query($query))
					{
						$wowdb->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while reinserting data in '.$table[1].' for profile '.$table[0].'. MySQL said: '.$wowdb->error());
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
	function table($table, $profile, $backup=false)
	{
		global $wowdb;

		return (($backup)?'backup_':'').$wowdb->table($table, $this->addata['dbname'], $profile);
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

?>