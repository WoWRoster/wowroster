<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Professions
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Installer for Professions Addon
 *
 * @package    Professions
 * @subpackage Locale
 */
class professionsInstall
{
	var $active = true;
	var $icon = 'trade_blacksmithing';

	var $version = '1.9.9.1758';
	var $wrnet_id = '0';

	var $fullname = 'professions';
	var $description = 'professions_desc';
	var $credits = array(
		array(	"name"=>	"vgjunkie",
				"info"=>	"Original Author")
	);


	/**
	 * Install Function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		// Master and menu entries
		$installer->add_config("'1','startpage','tradeskill_conf','display','master'");
		$installer->add_config("'110','professions_conf',NULL,'blockframe','menu'");

		$installer->add_config("'1010','show_new_skills','0','radio{No^0|Yes^1','professions_conf'");
		$installer->add_config("'1020','collapse_list','0','radio{Show^0|Hide^1','professions_conf'");

		$installer->add_menu_button('professions_menu','guild','','trade_blacksmithing');
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
		global $installer;

		if( version_compare( $oldversion, '1.9.9.1562', '<' ) )
		{
			$installer->add_config("'1030','professions_access','0','access','professions_conf'");
		}

		if( version_compare( $oldversion, '1.9.9.1758', '<' ) )
		{
			$installer->remove_config('1030');
		}

		return true;
	}

	/**
	 * Un-Install Function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;

		$installer->remove_all_config();

		$installer->remove_all_menu_button();
		return true;
	}
}
