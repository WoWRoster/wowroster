<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Installer
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
 * @package    WoWRoster
 * @subpackage Upgrader
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

if( version_compare($roster->config['version'], ROSTER_VERSION,'>=') )
{
	roster_die($roster->locale->act['no_upgrade'],$roster->locale->act['upgrade_wowroster']);
}


/**
 * WoWRoster Upgrader
 *
 * @package    WoWRoster
 * @subpackage Upgrader
 */
class Upgrade
{
	var $versions = array('1.9.9','2.0.0','2.0.9');
	var $index = null;

	function Upgrade()
	{
		global $roster;

		//$roster->db->error_die(false);

		if( isset($_POST['upgrade']) )
		{
			// Find out what version we're upgrading from
			$version_from = $_POST['version'];
			foreach( $this->versions as $index => $version )
			{
				$this->index = $index;
				if( str_replace('.', '', $version) == $version_from )
				{
					$method = 'upgrade_' . $version_from;
					$this->$method();
				}
			}
		}
		else
		{
			$this->display_form();
		}
	}

	function finalize()
	{
		global $roster;

		$this->index++;

		if( isset($this->versions[$this->index]) )
		{
			$method = 'upgrade_' . str_replace('.', '', $this->versions[$this->index]);
			$this->$method();
		}
		else
		{
			roster_die($roster->locale->act['upgrade_complete'],$roster->locale->act['upgrade_wowroster'],'sgreen');
		}
	}

	//--------------------------------------------------------------
	// Upgrade methods
	//--------------------------------------------------------------

	/**
	 * Upgrades the 2.0.9.x beta versions into the 2.1.0 release
	 */
	function upgrade_209()
	{
		global $roster;

		if( version_compare($roster->config['version'],'2.0.9.1879','<') )
		{
			// Quest Data
			$roster->db->query("DROP TABLE IF EXISTS `" . $roster->db->table('quest_data') . "`;");
			$roster->db->query("CREATE TABLE `" . $roster->db->table('quest_data') . "` (
				`quest_id` int(11) NOT NULL default '0',
				`quest_name` varchar(64) NOT NULL default '',
				`quest_level` int(11) unsigned NOT NULL default '0',
				`quest_tag` varchar(32) NOT NULL default '',
				`group` int(1) NOT NULL default '0',
				`daily` int(1) NOT NULL default '0',
				`reward_money` int(11) NOT NULL default '0',
				`zone` varchar(32) NOT NULL default '',
				`description` text NOT NULL,
				`objective` text NOT NULL,
				`locale` varchar(4) NOT NULL default '',
				PRIMARY KEY  (`quest_id`,`locale`),
				FULLTEXT KEY `quest_name` (`quest_name`),
				FULLTEXT KEY `zone` (`zone`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			// Member Quests
			$roster->db->query("DROP TABLE IF EXISTS `" . $roster->db->table('quests') . "`;");
			$roster->db->query("CREATE TABLE `" . $roster->db->table('quests') . "` (
				`member_id` int(11) unsigned NOT NULL default '0',
				`quest_id` int(11) NOT NULL default '0',
				`quest_index` int(11) NOT NULL default '0',
				`difficulty` int(1) NOT NULL default '0',
				`is_complete` int(1) NOT NULL default '0',
				PRIMARY KEY  (`member_id`,`quest_id`),
				KEY `quest_index` (`quest_id`,`quest_index`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}

		if( version_compare($roster->config['version'],'2.0.9.1882','<') )
		{
			// Fix commit 1879 renprefix_ error...oops
			$roster->db->query("DROP TABLE IF EXISTS `renprefix_pet_spellbook`;");
			$roster->db->query("DROP TABLE IF EXISTS `renprefix_pet_talents`;");
			$roster->db->query("DROP TABLE IF EXISTS `renprefix_pet_talenttree`;");

			// And we have to re-add the tables, uhg

			// Rename spellbook_pet to pet_spellbook
			$roster->db->query("DROP TABLE IF EXISTS `" . $roster->db->table('pet_spellbook') . "`;");
			$roster->db->query("CREATE TABLE `" . $roster->db->table('pet_spellbook') . "` (
				`member_id` int( 11 ) unsigned NOT NULL default '0',
				`pet_id` int( 11 ) unsigned NOT NULL default '0',
				`spell_name` varchar( 64 ) NOT NULL default '',
				`spell_texture` varchar( 64 ) NOT NULL default '',
				`spell_rank` varchar( 64 ) NOT NULL default '',
				`spell_tooltip` mediumtext NOT NULL ,
				PRIMARY KEY ( `member_id` , `pet_id` , `spell_name` , `spell_rank` )
				) ENGINE = MYISAM DEFAULT CHARSET = utf8;");

			$roster->db->query("INSERT INTO `" . $roster->db->table('pet_spellbook') . "`
				SELECT *
				FROM `" . $roster->db->table('spellbook_pet') . "`;");

			$roster->db->query("DROP TABLE `" . $roster->db->table('spellbook_pet') . "` ;");

			// Pet Talents
			$roster->db->query("DROP TABLE IF EXISTS `" . $roster->db->table('pet_talents') . "`;");
			$roster->db->query("CREATE TABLE `" . $roster->db->table('pet_talents') . "` (
				`member_id` int(11) NOT NULL default '0',
				`pet_id` int(11) unsigned NOT NULL default '0',
				`name` varchar(64) NOT NULL default '',
				`tree` varchar(64) NOT NULL default '',
				`row` tinyint(4) NOT NULL default '0',
				`column` tinyint(4) NOT NULL default '0',
				`rank` tinyint(4) NOT NULL default '0',
				`maxrank` tinyint(4) NOT NULL default '0',
				`tooltip` mediumtext NOT NULL,
				`icon` varchar(64) NOT NULL default '',
				PRIMARY KEY  (`member_id`,`pet_id`,`tree`,`row`,`column`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$roster->db->query("DROP TABLE IF EXISTS `" . $roster->db->table('pet_talenttree') . "`;");
			$roster->db->query("CREATE TABLE `" . $roster->db->table('pet_talenttree') . "` (
				`member_id` int(11) NOT NULL default '0',
				`pet_id` int(11) unsigned NOT NULL default '0',
				`tree` varchar(64) NOT NULL default '',
				`background` varchar(64) NOT NULL default '',
				`pointsspent` tinyint(4) NOT NULL default '0',
				PRIMARY KEY  (`member_id`,`pet_id`,`tree`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$roster->db->query("ALTER TABLE `" . $roster->db->table('pets') . "` DROP `usedtp`, DROP `loyalty`;");
		}

		$this->beta_upgrade();

		$this->finalize();
	}

	/**
	 * Upgrades 2.0.0 to 2.1.0
	 */
	function upgrade_200()
	{
		global $roster;

		// This will be active when the release is done
		//$this->standard_upgrader();

		$this->finalize();
	}

	/**
	 * Upgrades the 1.9.9.x beta versions into the 2.0.0 release
	 */
	function upgrade_199()
	{
		global $roster;

		if( version_compare($roster->config['version'],'1.9.9.1407','<') )
		{
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (6, 'versioncache', '', 'hidden', 'master');");
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '168', `form_type` = 'select{Do Not check^0|Once a Day^24|Once a Week^168|Once a Month^720' WHERE `id` = 1150 LIMIT 1;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('addon') . "` ADD `wrnet_id` int(4) NOT NULL DEFAULT '0';");
		}

		if( version_compare($roster->config['version'],'1.9.9.1417','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('menu') . "` CHANGE `section` `section` varchar(64) NULL DEFAULT NULL;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('menu') . "` ADD UNIQUE `section` ( `section` ) ");
		}

		if( version_compare($roster->config['version'],'1.9.9.1432','<') )
		{
			$roster->db->query("UPDATE `" . $roster->db->table('addon') . "` SET `version` = '1.9.9.1431' WHERE `basename` IN('guildbank','guildinfo','info','keys','memberslist','news','professions','pvplog','questlist');");
		}

		if( version_compare($roster->config['version'],'1.9.9.1438','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('mailbox') . "` DROP `item_icon`, DROP `item_name`, DROP `item_quantity`, DROP `item_tooltip`, DROP `item_color`;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1439','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('mailbox') . "` ADD `mailbox_icon` varchar(64) NOT NULL DEFAULT '';");
		}

		if( version_compare($roster->config['version'],'1.9.9.1443','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('players') . "` ADD `melee_expertise` int(11) NOT NULL default '0' AFTER `melee_haste_d`,"
				. " ADD `melee_expertise_c` int(11) NOT NULL default '0' AFTER `melee_expertise`,"
				. " ADD `melee_expertise_b` int(11) NOT NULL default '0' AFTER `melee_expertise_c`,"
				. " ADD `melee_expertise_d` int(11) NOT NULL default '0' AFTER `melee_expertise_b`;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1458','<') )
		{
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (1180, 'use_temp_tables', '1', 'radio{on^1|off^0', 'main_conf');");
		}

		if( version_compare($roster->config['version'],'1.9.9.1488','<') )
		{
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (1055, 'external_auth', 'roster', 'function{externalAuth', 'main_conf');");
		}

		if( version_compare($roster->config['version'],'1.9.9.1524','<') )
		{
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (1190, 'enforce_rules', '1', 'radio{on^1|off^0', 'main_conf');");
		}

		if( version_compare($roster->config['version'],'1.9.9.1541','<') )
		{
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `form_type` = 'select{Never^0|All LUA Updates^1|CP Updates^2|Guild Updates^3' WHERE `id` = 1190 LIMIT 1;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('addon') . "` ADD `versioncache` tinytext;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1556','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('memberlog') . "` CHANGE `name` `name` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL default '';");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('members') . "` CHANGE `name` `name` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL default '';");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('players') . "` CHANGE `name` `name` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL default '';");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('pets') . "` CHANGE `name` `name` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL default '';");
		}

		if( version_compare($roster->config['version'],'1.9.9.1567','<') )
		{
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (8470, 'rs_color_full', '#990000', 'color', 'rs_right');");
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (8480, 'rs_color_recommended', '#0033FF', 'color', 'rs_right');");
		}

		if( version_compare($roster->config['version'],'1.9.9.1585','<') )
		{
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `form_type` = 'radio{extended^2|on^1|off^0' WHERE `id` = 1002;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1637','<') )
		{
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `form_type` = 'radio{extended^2|on^1|off^0' WHERE `id` = 1001;");
		}

		/* All that remains of a bad idea... */
		if( version_compare($roster->config['version'],'1.9.9.1708','<') )
		{
			$roster->db->query("DROP TABLE IF EXISTS `" . $roster->db->table('blinds') . "`;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1715','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('recipes') . "` DROP INDEX `categoriesI`;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('recipes') . "` DROP `categories`;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('recipes') . "` ADD `recipe_id` VARCHAR(32) NULL AFTER `member_id`;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('recipes') . "` ADD `item_id` VARCHAR(64) NULL AFTER `recipe_id`;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1717','<') )
		{
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '#646464' WHERE `id` = 8460;");
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (8465, 'rs_color_offline', '#646464', 'color', 'rs_right');");
		}

		if( version_compare($roster->config['version'],'1.9.9.1754','<') )
		{
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '2.4.0' WHERE `id` = 1010;");
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '2.4.0' WHERE `id` = 1020;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1758','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('addon') . "` ADD `access` INT(1) NOT NULL DEFAULT '0' AFTER `active`;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('memberlog') . "` ADD `classid` INT(11) NOT NULL DEFAULT '0' AFTER `class`;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('members') . "` ADD `classid` INT(11) NOT NULL DEFAULT '0' AFTER `class`;");
		}

		$this->beta_upgrade();

		$this->finalize();
	}

	function beta_upgrade()
	{
		global $roster;

		$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . ROSTER_VERSION . "' WHERE `id` = '4' LIMIT 1;");
	}

	/**
	 * The standard upgrader
	 * This parses the requested sql file for database upgrade
	 * Most upgrades will use this function
	 */
	function standard_upgrader()
	{
		global $roster;

		$ver = str_replace('.','',$this->versions[$this->index]);

		$db_structure_file = ROSTER_LIB . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'upgrade_' . $ver . '.sql';

		if( file_exists($db_structure_file) )
		{
			// Parse structure file and create database tables
			$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
			$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $roster->db->prefix . '\\1\\2', $sql);

			$sql = remove_remarks($sql);
			$sql = parse_sql($sql, ';');

			$sql_count = count($sql);
			for( $i = 0; $i < $sql_count; $i++ )
			{
				$roster->db->query($sql[$i]);
			}
			unset($sql);
		}
		else
		{
			roster_die('Could not obtain SQL structure/data',$roster->locale->act['upgrade_wowroster']);
		}

		$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . ROSTER_VERSION . "' WHERE `id` = '4' LIMIT 1;");
		$roster->db->query("ALTER TABLE `" . $roster->db->table('config') . "` ORDER BY `id`;");

		return;
	}

	function display_form()
	{
		global $roster;

		$this->versions = array_reverse($this->versions);

		foreach ( $this->versions as $version )
		{
			$selected = ( $version == $roster->config['version'] ) ? ' selected="selected"' : '';

			$roster->tpl->assign_block_vars('version_row', array(
				'VALUE'    => str_replace('.', '', $version),
				'SELECTED' => $selected,
				'OPTION'   => 'WoWRoster ' . $version
				)
			);
		}

		$roster->tpl->assign_vars(array(
			'L_UPGRADE'        => $roster->locale->act['upgrade_wowroster'],
			'L_SELECT_VERSION' => $roster->locale->act['select_version'],
			'L_UPGRADE'        => $roster->locale->act['upgrade'],

			'U_UPGRADE'        => makelink('upgrade'),
			)
		);

		$roster->output['title'] = $roster->locale->act['upgrade_wowroster'];

		include(ROSTER_BASE . 'header.php');

		$roster->tpl->set_filenames(array('body' => 'upgrade.html'));
		$roster->tpl->display('body');

		include(ROSTER_BASE . 'footer.php');
	}
}



/**
 * Removes comments from a SQL data file
 *
 * @param    string  $sql    SQL file contents
 * @return   string
 */
function remove_remarks($sql)
{
	global $roster;

	if ( $sql == '' )
	{
		roster_die('Could not obtain SQL structure/data',$roster->locale->act['upgrade_wowroster']);
	}

	$retval = '';
	$lines  = explode("\n", $sql);
	unset($sql);

	foreach ( $lines as $line )
	{
		// Only parse this line if there's something on it, and we're not on the last line
		if ( strlen($line) > 0 )
		{
			// If '#' is the first character, strip the line
			$retval .= ( substr($line, 0, 1) != '#' ) ? $line . "\n" : "\n";
		}
	}
	unset($lines, $line);

	return $retval;
}

/**
 * Parse multi-line SQL statements into a single line
 *
 * @param    string  $sql    SQL file contents
 * @param    char    $delim  End-of-statement SQL delimiter
 * @return   array
 */
function parse_sql($sql, $delim)
{
	global $roster;

	if ( $sql == '' )
	{
		roster_die('Could not obtain SQL structure/data',$roster->locale->act['upgrade_wowroster']);
	}

	$retval     = array();
	$statements = explode($delim, $sql);
	unset($sql);

	$linecount = count($statements);
	for ( $i = 0; $i < $linecount; $i++ )
	{
		if ( ($i != $linecount - 1) || (strlen($statements[$i]) > 0) )
		{
			$statements[$i] = trim($statements[$i]);
			$statements[$i] = str_replace("\r\n", '', $statements[$i]) . "\n";

			// Remove 2 or more spaces
			$statements[$i] = preg_replace('#\s{2,}#', ' ', $statements[$i]);

			$retval[] = trim($statements[$i]);
		}
	}
	unset($statements);

	return $retval;
}

$upgrade = new Upgrade();

// And the upgrade-o-matic 5000 takes care of the rest.
