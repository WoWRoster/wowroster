<?php
######################################################
# File to install Roster module
# This file is called automatically by Admin->Modules
######################################################

if (!defined('ADMIN_MOD_INSTALL')) {  exit; } //Important Safeguard.

$roster_base = basename(dirname(__FILE__));
if( !defined('ROSTER_DF_INSTALLER') )
{
require_once(BASEDIR.'modules/'.$roster_base.'/install.php');
}
eval("class {$roster_base}
{
	var \$description;
	var \$radmin;
	var \$modname;
	var \$version;
	var \$author;
	var \$website;
	var \$dbtables;
	var \$prefix;

	// class constructor
	function {$roster_base}()
	{
		\$this->radmin = true;
		\$this->version = '1.7.0';
		\$this->modname = 'WoWRosterDF';
		\$this->base = '{$roster_base}';
		\$this->description = 'World of Warcraft Guild Roster Ported to Dragonfly™';
		\$this->author = 'WoWRoster Dev Team (Ported to Dragonfly™ by Anaxent)';
		\$this->website = 'www.wowroster.net';
		\$this->prefix = strtolower('{$roster_base}').'_';
		\$this->dbtables = array(
			\$this->prefix.'account',
			\$this->prefix.'config',
			\$this->prefix.'guild',
			\$this->prefix.'items',
			\$this->prefix.'mailbox',
			\$this->prefix.'members',
			\$this->prefix.'pets',
			\$this->prefix.'players',
			\$this->prefix.'pvp2',
			\$this->prefix.'quests',
			\$this->prefix.'realmstatus',
			\$this->prefix.'recipes',
			\$this->prefix.'reputation',
			\$this->prefix.'skills',
			\$this->prefix.'spellbook',
			\$this->prefix.'spellbooktree',
			\$this->prefix.'talents',
			\$this->prefix.'talenttree',
			\$this->prefix.'addon_siggen',
			\$this->prefix.'addon_altmonitor',
			\$this->prefix.'addon_altmonitor_config',
		);
	}

	// Module Installer
	function install()
	{
		return RosterDF_install(\$this->prefix, \$this->base);
	}

	// module uninstaller
	function uninstall()
	{
		return RosterDF_uninstall(\$this->dbtables);
	}

	# module upgrader
	function upgrade(\$prev_version)
	{
		return RosterDF_upgrade(\$prev_version, \$this->prefix, \$this->base);
	}
}");