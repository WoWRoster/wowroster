<?php

class guild_repInstall
{

	var $members_list_select;
	var $members_list_table;
	var $members_list_where = array();
	var $members_list_fields = array();

	/*
	*	These Vars are used with the new Plugin installer
	*	@var name - unique name for the plugin
	*	@var parent - the intended addon to use this plugin
	*
	*/
	var $active = true;
	var $name = 'guild_rep';
	var $filename = 'memberslist-guild-guild_rep.php';
	var $parent = 'memberslist';
	var $scope = 'guild';
	var $icon = 'inv_misc_film_01';
	var $version = '1.0';
	var $oldversion = '';
	var $wrnet_id = '';

	var $fullname = 'MembersList Guild Rep';
	var $description = 'displays guildrep on members list.';
	var $credits = array(
		array(	"name"=>	"Ulminia <Ulminia@gmail.com>",
				"info"=>	"Guild Rep (Alpha Release)"),
	);


		/**
	 * Install Function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;
		return true;
	}

	/**
	 * Upgrade Function
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
	     global $installer,$addon,$roster;
		// Nothing to upgrade from yet
		return true;

	}

	/**
	 * Un-Install Function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer, $addon;
		return true;
	}


}

?>