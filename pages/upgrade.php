<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Installer
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
 * @package    WoWRoster
 * @subpackage Upgrader
 */

if (!defined('IN_ROSTER')) {
	exit('Detected invalid access to this file!');
}

/**
 * WoWRoster Upgrader
 *
 * @package    WoWRoster
 * @subpackage Upgrader
 */
class Upgrade {
	var $versions = array(
		'2.0.0',
		'2.0.1',
		'2.0.2',
		'2.1.0',
		'2.1.9'
	);
	var $index = null;

	function Upgrade() {
		global $roster;

		//$roster->db->error_die(false);

		$roster->tpl->assign_var('MESSAGE', false);
		$roster->cache->cleanCache();
		// First check the current version compared to upgrade version
		if (version_compare($roster->config['version'], ROSTER_VERSION, '>=')) {
			$roster->tpl->assign_var('MESSAGE', sprintf($roster->locale->act['no_upgrade'], ROSTER_PATH));
			$this->display_page();
		}

		if (isset($_POST['upgrade'])) {
			// Find out what version we're upgrading from
			$version_from = $_POST['version'];
			foreach ($this->versions as $index => $version) {
				$this->index = $index;
				if (str_replace('.', '', $version) == $version_from) {
					$method = 'upgrade_' . $version_from;
					$this->$method();
				}
			}
		}
		else {
			$this->display_form();
		}
	}

	function finalize() {
		global $roster;

		$this->index++;

		if (isset($this->versions[$this->index])) {
			$method = 'upgrade_' . str_replace('.', '', $this->versions[$this->index]);
			$this->$method();
		}
		else {
			$roster->tpl->assign_var('MESSAGE', sprintf($roster->locale->act['upgrade_complete'], ROSTER_PATH));
			$this->display_page();
		}
	}

	//--------------------------------------------------------------
	// Upgrade methods
	//--------------------------------------------------------------


	/**
	 * Upgrades the 2.1.9.x beta versions into the 2.2.0 release
	 */
	function upgrade_219() {
		global $roster, $installer;

		/* Update Examples
		if (version_compare($roster->config['version'], '2.1.9.2344', '<')) { // This MUST be equal or lower than the version set on lib/constants.php
			$roster->set_message('Message');

			$roster->db->query('DELETE FROM `' . $roster->db->table('menu_button') . '` WHERE `addon_id`= "0" AND `title` = "menu_credits";');
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (10005, 'update_inst', '1', 'radio{on^1|off^0', 'update_access');");
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = 'http://www.wowroster.net/MediaWiki' WHERE `id` = 180 LIMIT 1;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('pets') . "` DROP `usedtp`, DROP `loyalty`;");

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
		}
		*/
		if (version_compare($roster->config['version'], '2.1.9.2344', '<')) {
			$roster->set_message('Added Javascript and CSS aggregation options');

			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (99, 'css_js_query_string', 'lod68q', 'hidden', 'master');");
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (1181, 'preprocess_js', '1', 'radio{on^1|off^0', 'main_conf');");
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (1182, 'preprocess_css', '1', 'radio{on^1|off^0', 'main_conf');");
		}

		if (version_compare($roster->config['version'], '2.1.9.2350', '<')) {
			$roster->set_message('Blizzard API key settings');

			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (10001, 'api_key_private', '', 'text{64|30', 'update_access');");
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (10002, 'api_key_public', '', 'text{64|30', 'update_access');");
		}

		if (version_compare($roster->config['version'], '2.1.9.2352', '<')) {
			$roster->set_message('Adding RosterAPI usage table');

			$roster->db->query("DROP TABLE IF EXISTS `" . $roster->db->table('api_usage') . "`;");
			$roster->db->query("CREATE TABLE IF NOT EXISTS `".$roster->db->table('api_usage')."` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`type` varchar(50) DEFAULT NULL,
				`date` date DEFAULT NULL,
				`total` int(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		}

		if (version_compare($roster->config['version'], '2.1.9.2362', '<')) {
			$roster->set_message('API URL Setting');
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (10003, 'api_url_region', '', 'select{us.battle.net^us|eu.battle.net^eu|kr.battle.net^kr|tw.battle.net^tw', 'update_access');");
		}
		if (version_compare($roster->config['version'], '2.1.9.2372', '<')) {
			$roster->set_message('API URL Setting');
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (10004, 'api_url_locale', 'en_US', 'select{us.battle.net (en_US)^en_US|us.battle.net (es_MX)^es_MX|eu.battle.net (en_GB)^en_GB|eu.battle.net (es_ES)^es_ES|eu.battle.net (fr_FR)^fr_FR|eu.battle.net (ru_RU)^ru_RU|eu.battle.net (de_DE)^de_DE|kr.battle.net (ko_kr)^ko_kr|tw.battle.net (zh_TW)^zh_TW|battlenet.com.cn (zh_CN)^zh_CN', 'update_access');");

		}

		if (version_compare($roster->config['version'], '2.1.9.2378', '<')) {
			$roster->set_message('Plugin install system');
			$roster->db->query("CREATE TABLE IF NOT EXISTS `".$roster->db->table('plugin')."` (
				  `addon_id` int(11) NOT NULL auto_increment,
				  `basename` varchar(16) NOT NULL default '',
				  `version` varchar(16) NOT NULL default '0',
				  `active` int(1) NOT NULL default '1',
				  `access` int(1) NOT NULL default '0',
				  `fullname` tinytext NOT NULL,
				  `description` mediumtext NOT NULL,
				  `credits` mediumtext NOT NULL,
				  `icon` varchar(64) NOT NULL default '',
				  `wrnet_id` int(4) NOT NULL default '0',
				  `versioncache` tinytext,
				  PRIMARY KEY  (`addon_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		}
		if (version_compare($roster->config['version'], '2.1.9.2379', '<')) {
			$roster->set_message('user and access system install');
			$roster->db->query("CREATE TABLE IF NOT EXISTS `".$roster->db->table('user_members')."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `usr` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  `pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  `regIP` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  `dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `access` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `usr` (`usr`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		//5f4dcc3b5aa765d61d8327deb882cf99 password Duh!
			$roster->db->query("INSERT INTO `".$roster->db->table('user_members')."` (`id`, `usr`, `pass`, `email`, `regIP`, `dt`, `access`,`active`) VALUES (NULL, 'Admin', '5f4dcc3b5aa765d61d8327deb882cf99', '', '', '0000-00-00 00:00:00', '11:0','1');");
			$roster->set_message('Admin user created password: password <span style="color:red;">Change this asap!</span>');
			$roster->db->query("INSERT INTO `".$roster->db->table('user_members')."` (`id`, `usr`, `pass`, `email`, `regIP`, `dt`, `access`,`active`) VALUES (NULL, 'Officer', '5f4dcc3b5aa765d61d8327deb882cf99', '', '', '0000-00-00 00:00:00', '11:0','1');");
			$roster->set_message('Officer user created password: password');
			$roster->db->query("INSERT INTO `".$roster->db->table('user_members')."` (`id`, `usr`, `pass`, `email`, `regIP`, `dt`, `access`,`active`) VALUES (NULL, 'Guild', '5f4dcc3b5aa765d61d8327deb882cf99', '', '', '0000-00-00 00:00:00', '11:0','1');");
			$roster->set_message('Guild user created password: password');

			$roster->db->query("ALTER TABLE `".$roster->db->table('addon')."` CHANGE `access` `access` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'");
			$roster->set_message('Addon access field updated');
		}


		if (version_compare($roster->config['version'], '2.1.9.2386', '<')) {

			$roster->set_message('Talent updates of all things....');

			$roster->db->query("ALTER TABLE `".$roster->db->table('talenttree_data')."` ADD `roles` VARCHAR( 10 ) NULL DEFAULT NULL ,
			ADD `desc` VARCHAR( 255 ) NULL DEFAULT NULL");

			$roster->db->query("ALTER TABLE `".$roster->db->table('talents_data')."` ADD `isspell` INT( 1 ) NULL DEFAULT '0'");

			$roster->db->query("CREATE TABLE IF NOT EXISTS `".$roster->db->table('talent_mastery')."` (
			  `class_id` int(11) NOT NULL DEFAULT '0',
			  `tree` varchar(64) NOT NULL DEFAULT '',
			  `tree_num` varchar(64) NOT NULL DEFAULT '',
			  `icon` varchar(64) NOT NULL DEFAULT '',
			  `name` varchar(64) DEFAULT NULL,
			  `desc` varchar(255) DEFAULT NULL,
			  `spell_id` varchar(64) NOT NULL DEFAULT '',
			  PRIMARY KEY (`class_id`,`spell_id`,`tree`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

		}
		if (version_compare($roster->config['version'], '2.1.9.2404', '<'))
		{
			$roster->db->query("INSERT INTO `".$roster->db->table('menu_button')."` VALUES (null, 0, 'menu_register', 'util', 'register', 'inv_misc_bag_26_spellfire');");
			$t_id = $roster->db->insert_id();
			$roster->db->query("UPDATE `".$roster->db->table('menu')."` SET `config` = CONCAT(config, ':b$t_id') WHERE `section` = 'util';");
			$roster->set_message('Added Register Button');
		}
		if (version_compare($roster->config['version'], '2.1.9.2405', '<'))
		{
			$roster->db->query("ALTER TABLE `".$roster->db->table('plugin')."` ADD `parent` VARCHAR( 100 ) NULL DEFAULT NULL AFTER `basename` ,
			ADD `scope` VARCHAR( 20 ) NULL DEFAULT NULL AFTER `parent`");
		}
		///*
		//	these updates begin with 2405 and up
		if (version_compare($roster->config['version'], '2.1.9.2410', '<'))
		{
			$roster->db->query("ALTER TABLE `".$roster->db->table('user_members')."`
				ADD `user_last_visit` INT( 11 ) NOT NULL DEFAULT '0',
				ADD `age` varchar(32) NOT NULL default '',
				ADD `email` varchar(32) NOT NULL default '',
				ADD `city` varchar(32) NOT NULL default '',
				ADD `state` varchar(32) NOT NULL default '',
				ADD `country` varchar(32) NOT NULL default '',
				ADD `zone` varchar(32) NOT NULL default ''");

			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES
				(190,'acc_session','NULL','blockframe','menu'),
				(1900, 'sess_time', '15', 'text{30|4', 'acc_session'),
				(1910, 'save_login', '1', 'radio{on^1|off^0', 'acc_session');");

			$roster->db->query("CREATE TABLE IF NOT EXISTS `".$roster->db->table('sessions')."` (
			  `session_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `session_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `session_last_visit` int(11) unsigned NOT NULL DEFAULT '0',
			  `session_start` int(11) unsigned NOT NULL DEFAULT '0',
			  `session_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `session_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `session_browser` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `session_forwarded_for` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `session_page` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `session_viewonline` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `session_autologin` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `session_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`session_id`),
			  KEY `session_time` (`session_time`),
			  KEY `session_user_id` (`session_user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$roster->db->query("CREATE TABLE IF NOT EXISTS `".$roster->db->table('sessions_keys')."` (
			  `key_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
			  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`key_id`,`user_id`),
			  KEY `last_login` (`last_login`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$roster->db->query("ALTER TABLE `".$roster->db->table('members')."` CHANGE `account_id` `account_id` SMALLINT( 6 ) NULL DEFAULT NULL;");
			$roster->db->query("UPDATE `".$roster->db->table('members')."` set `account_id` = NULL WHERE `account_id` = '0';");
			$roster->db->query("INSERT INTO `".$roster->db->table('menu')."` VALUES ('', 'user', '');");
			$roster->db->query("ALTER TABLE `".$roster->db->table('user_members')."`
			ADD `fname` varchar(30) NOT NULL default '',
				ADD `lname` varchar(30) NOT NULL default '',
				ADD `age` varchar(32) NOT NULL default '',
				ADD `city` varchar(32) NOT NULL default '',
				ADD `state` varchar(32) NOT NULL default '',
				ADD `country` varchar(32) NOT NULL default '',
				ADD `zone` varchar(32) NOT NULL default '',
				ADD `homepage` varchar(64) NOT NULL default '',
				ADD `other_guilds` varchar(64) NOT NULL default '',
				ADD `why` varchar(64) NOT NULL default '',
				ADD `about` varchar(64) NOT NULL default '',
				ADD `notes` varchar(64) NOT NULL default '',
				ADD `last_login` varchar(64) NOT NULL default '',
				ADD `date_joined` varchar(64) NOT NULL default '',
				ADD `tmp_mail` varchar(32) NOT NULL default '',
				ADD `group_id` smallint(6) NOT NULL default '1',
				ADD `is_member` INT(11) NOT NULL default '0',
				ADD `active` INT(11) NOT NULL default '0',
				ADD `online` INT(11) NOT NULL default '0'");
		}
		if (version_compare($roster->config['version'], '2.1.9.2415', '<'))
		{
			$roster->db->query("DROP TABLE IF EXISTS `".$roster->db->table('sessions')."`");
			$roster->db->query("CREATE TABLE IF NOT EXISTS `".$roster->db->table('sessions')."` (
				  `sess_id` varchar(35) DEFAULT NULL,
				  `session_id` char(32) NOT NULL DEFAULT '',
				  `session_user_id` varchar(5) DEFAULT NULL,
				  `session_last_visit` int(11) NOT NULL DEFAULT '0',
				  `session_start` int(11) NOT NULL DEFAULT '0',
				  `session_time` int(11) NOT NULL DEFAULT '0',
				  `session_ip` varchar(40) NOT NULL DEFAULT '',
				  `session_browser` varchar(150) NOT NULL DEFAULT '',
				  `session_forwarded_for` varchar(255) NOT NULL DEFAULT '',
				  `session_page` varchar(255) NOT NULL DEFAULT '',
				  `session_viewonline` tinyint(1) NOT NULL DEFAULT '1',
				  `session_autologin` tinyint(1) NOT NULL DEFAULT '0',
				  `session_admin` tinyint(1) NOT NULL DEFAULT '0'
				) ENGINE=MyISAM;");


		}
		if (version_compare($roster->config['version'], '2.1.9.2467', '<'))
		{
			$roster->db->query("UPDATE `" .$roster->db->table('user_members') . "` SET `access` = '11:0',`active`='1' WHERE `usr` = 'Admin';");
		}

		if (version_compare($roster->config['version'], '2.1.9.2469', '<'))
		{
			$roster->db->query("ALTER TABLE `" .$roster->db->table('currency') . "` CHANGE `count` `count` INT( 5 ) NULL DEFAULT NULL;");
		}

		if (version_compare($roster->config['version'], '2.1.9.2473', '<'))
		{
			$roster->db->query("DROP TABLE IF EXISTS `" .$roster->db->table('api_gems') . "`;");
			$roster->db->query("CREATE TABLE IF NOT EXISTS `" .$roster->db->table('api_gems') . "` (
			  `gem_id` int(11) NOT NULL,
			  `gem_name` varchar(96) NOT NULL DEFAULT '',
			  `gem_color` varchar(16) NOT NULL DEFAULT '',
			  `gem_tooltip` mediumtext NOT NULL,
			  `gem_texture` varchar(64) NOT NULL DEFAULT '',
			  `gem_bonus` varchar(255) NOT NULL DEFAULT '',
			  `locale` varchar(16) NOT NULL DEFAULT '',
			  `timestamp` int(10) NOT NULL,
			  `json` longtext,
			  PRIMARY KEY (`gem_id`,`locale`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$roster->db->query("DROP TABLE IF EXISTS `" .$roster->db->table('api_items') . "`;");
			$roster->db->query("CREATE TABLE IF NOT EXISTS `" .$roster->db->table('api_items') . "` (
			  `item_id` int(11) NOT NULL,
			  `item_name` varchar(96) NOT NULL DEFAULT '',
			  `item_color` varchar(16) NOT NULL DEFAULT '',
			  `item_texture` varchar(64) NOT NULL DEFAULT '',
			  `item_tooltip` mediumtext NOT NULL,
			  `level` int(11) DEFAULT NULL,
			  `item_level` int(11) DEFAULT NULL,
			  `item_type` varchar(64) DEFAULT NULL,
			  `item_subtype` varchar(64) DEFAULT NULL,
			  `item_rarity` int(4) NOT NULL DEFAULT '-1',
			  `locale` varchar(16) DEFAULT NULL,
			  `timestamp` int(10) NOT NULL,
			  `json` longtext,
			  PRIMARY KEY (`item_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			/*
			##############    NOT SURE IF THIS IS REALLY NEEDED BUT IT WOULDENT BE A BAD IDEA.....

			$roster->db->query("DROP TABLE IF EXISTS `" .$roster->db->table('api_items') . "`;
			CREATE TABLE `" .$roster->db->table('api_items') . "` (
			  `achv_id` int(11) NOT NULL,
			  `achv_cat` int(11) NOT NULL,
			  `achv_cat_title` varchar(255) NOT NULL default '',
			  `achv_cat_sub` varchar(255) NOT NULL default '',
			  `achv_cat_sub2` int(10) default NULL,
			  `achv_points` int(11) NOT NULL,
			  `achv_icon` varchar(255) NOT NULL default '',
			  `achv_title` varchar(255) NOT NULL default '',
			  `achv_reward_title` varchar(255) default NULL,
			  `achv_disc` text NOT NULL,
			  `achv_date` date default NULL,
			  `achv_criteria` text NOT NULL,
			  `achv_progress` varchar(25) NOT NULL,
			  `achv_progress_width` varchar(50) NOT NULL,
			  `achv_complete` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (`achv_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
			*/
		}

		if (version_compare($roster->config['version'], '2.1.9.2496', '<'))
		{
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '11' WHERE `config_name` = 'gp_user_level' LIMIT 1;");
			$roster->set_message('Set guild data update permissions to CP Admin');
		}

		// Standard Beta Update
		$this->beta_upgrade();
		$this->finalize();
	}

	/**
	 * Upgrades 2.1.0 to 2.2
	 *
	function upgrade_220() {
		global $roster;

		// make admin password roster password
		$query = "SELECT * FROM `" . $roster->db->table('account') . "` WHERE `name` = 'Admin';";
		$result = $roster->db->query($query);
		$row = $roster->db->fetch($result);
		$roster->db->query("UPDATE `" . $roster->db->table('user_members') . "` SET `pass` = '" . $row['hash'] . "' WHERE `usr` = 'Admin' LIMIT 1;");

		$this->standard_upgrader();
		$this->finalize();
	}
	*/

	/**
	 * Upgrades 2.1.0 to 2.1.1
	 */
	function upgrade_210() {
		global $roster;

		$this->standard_upgrader();
		$this->finalize();
	}

	/**
	 * Upgrades 2.0.2 to 2.1.0
	 */
	function upgrade_202() {
		global $roster;

		$this->standard_upgrader();
		$this->finalize();
	}

	/**
	 * Upgrades 2.0.1 to 2.0.2
	 */
	function upgrade_201() {
		$this->standard_upgrader();
		$this->finalize();
	}

	/**
	 * Upgrades 2.0.0 to 2.0.1
	 */
	function upgrade_200() {
		$this->standard_upgrader();
		$this->finalize();
	}

	function beta_upgrade() {
		global $roster;
		$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . ROSTER_VERSION . "' WHERE `id` = '4' LIMIT 1;");
	}

	/**
	 * The standard upgrader
	 * This parses the requested sql file for database upgrade
	 * Most upgrades will use this function
	 */
	function standard_upgrader() {
		global $roster;

		$ver = str_replace('.', '', $this->versions[$this->index]);

		$db_structure_file = ROSTER_LIB . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'upgrade_' . $ver . '.sql';

		if (file_exists($db_structure_file)) {
			// Parse structure file and create database tables
			$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
			$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $roster->db->prefix . '\\1\\2', $sql);

			$sql = remove_remarks($sql);
			$sql = parse_sql($sql, ';');

			$sql_count = count($sql);
			for ($i = 0; $i < $sql_count; $i++) {
				$roster->db->query($sql[$i]);
			}
			unset($sql);
		}
		else {
			roster_die('Could not obtain SQL structure/data', $roster->locale->act['upgrade_wowroster']);
		}

		$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . ROSTER_VERSION . "' WHERE `id` = '4' LIMIT 1;");
		$roster->db->query("ALTER TABLE `" . $roster->db->table('config') . "` ORDER BY `id`;");

		return;
	}

	function display_form() {
		global $roster;

		$this->versions = array_reverse($this->versions);

		foreach ($this->versions as $version) {
			$selected = ($version == $roster->config['version']) ? ' selected="selected"' : '';

			$roster->tpl->assign_block_vars('version_row', array(
				'VALUE' => str_replace('.', '', $version),
				'SELECTED' => $selected,
				'OPTION' => 'WoWRoster ' . $version
			));
		}
		$this->display_page();
	}

	function display_page() {
		global $roster;

		$roster->tpl->assign_var('U_UPGRADE', makelink('upgrade'));

		$roster->output['title'] = $roster->locale->act['upgrade_wowroster'];

		include (ROSTER_BASE . 'header.php');

		$roster->tpl->set_handle('body', 'upgrade.html');
		$roster->tpl->display('body');

		include (ROSTER_BASE . 'footer.php');
		die();
	}
}

/**
 * Removes comments from a SQL data file
 *
 * @param    string  $sql    SQL file contents
 * @return   string
 */
function remove_remarks($sql) {
	global $roster;

	if($sql == '') {
		roster_die('Could not obtain SQL structure/data', $roster->locale->act['upgrade_wowroster']);
	}

	$retval = '';
	$lines = explode("\n", $sql);
	unset($sql);

	foreach ($lines as $line) {
		// Only parse this line if there's something on it, and we're not on the last line
		if (strlen($line) > 0) {
			// If '#' is the first character, strip the line
			$retval .= (substr($line, 0, 1) != '#') ? $line . "\n" : "\n";
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
function parse_sql($sql, $delim) {
	global $roster;

	if ($sql == '') {
		roster_die('Could not obtain SQL structure/data', $roster->locale->act['upgrade_wowroster']);
	}

	$retval = array();
	$statements = explode($delim, $sql);
	unset($sql);

	$linecount = count($statements);
	for ($i = 0; $i < $linecount; $i++) {
		if (($i != $linecount - 1) || (strlen($statements[$i]) > 0)) {
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
