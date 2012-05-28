<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * News Addon Installer
 * @package News
 * @subpackage Installer
 */
class mainInstall
{
	var $active = true;
	var $icon = 'ability_warrior_rallyingcry';

	var $version = '0.2.3';
	var $wrnet_id = '0';

	var $fullname = 'CMS News Page';
	var $description = 'A \'front page\' for WoWRoster. Display user controls, post news and slideshow images.';
	var $credits = array(
		array("name"=>	"Ulminia",
				"info"=>	"Original author")
	);


	/**
	 * Install function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		$installer->add_config("'1','startpage','cmsmain_conf','display','master'");
		$installer->add_config("'100','cmsmain_conf',NULL,'blockframe','menu'");
		$installer->add_config("'200','cmsmain_slider',NULL,'blockframe','menu'");
		$installer->add_config("'300','cmsmain_banner','rostercp-addon-main-banners','makelink','menu'");
		$installer->add_config("'400','cmsmain_banneradd','rostercp-addon-main-banneradd','makelink','menu'");

		$installer->add_config("'1000','news_add','2','access','cmsmain_conf'");
		$installer->add_config("'1010','news_edit','2','access','cmsmain_conf'");
		$installer->add_config("'1020','comm_add','0','access','cmsmain_conf'");
		$installer->add_config("'1030','comm_edit','2','access','cmsmain_conf'");
		$installer->add_config("'1040','news_html','1','radio{enabled^1|disabled^0|forbidden^-1','cmsmain_conf'");
		$installer->add_config("'1050','comm_html','-1','radio{enabled^1|disabled^0|forbidden^-1','cmsmain_conf'");
		$installer->add_config("'1060','news_nicedit','1','radio{enabled^1|disabled^0', 'cmsmain_conf'");

		$installer->add_config("'2010','autoAdvance','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2020','mobileAutoAdvance','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2030','barDirection','leftToRight','select{leftToRight^leftToRight|rightToLeft^rightToLeft|topToBottom^topToBottom|bottomToTop^bottomToTop','cmsmain_slider'");
		$installer->add_config("'2040','barPosition','bottom','select{left^left|right^right|top^top|bottom^bottom','cmsmain_slider'");
		$installer->add_config("'2050','easing','easeInOutExpo','function{sliderEasing','cmsmain_slider'");
		$installer->add_config("'2060','mobileEasing','','function{sliderEasing','cmsmain_slider'");
		$installer->add_config("'2070','fx','random','function{sliderFx','cmsmain_slider'");
		$installer->add_config("'2080','mobileFx','','function{sliderFx','cmsmain_slider'");
		$installer->add_config("'2090','gridDifference','250','text{10|10','cmsmain_slider'");
		$installer->add_config("'2100','height','50%','text{10|10','cmsmain_slider'");
		$installer->add_config("'2110','hover','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2120','loader','pie','select{pie^pie|bar^bar|none^none','cmsmain_slider'");
		$installer->add_config("'2130','loaderColor','#EEEEEE','color','cmsmain_slider'");
		$installer->add_config("'2140','loaderBgColor','#222222','color','cmsmain_slider'");
		$installer->add_config("'2150','loaderOpacity','0.8','select{0^0|0.1^0.1|0.2^0.2|0.3^0.3|0.4^0.4|0.5^0.5|0.6^0.6|0.7^0.7|0.8^0.8|0.9^0.9|1.0^1.0','cmsmain_slider'");
		$installer->add_config("'2160','loaderPadding','2','text{10|10','cmsmain_slider'");
		$installer->add_config("'2170','loaderStroke','7','text{10|10','cmsmain_slider'");
		$installer->add_config("'2180','minHeight','200px','text{10|10','cmsmain_slider'");
		$installer->add_config("'2190','navigationHover','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2200','mobileNavHover','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2210','opacityOnGrid','0','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2220','overlayer','0','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2230','pagination','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2240','playPause','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2250','pauseOnClick','1','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2260','pieDiameter','38','text{10|10','cmsmain_slider'");
		$installer->add_config("'2270','piePosition','rightTop','select{rightTop^rightTop|leftTop^leftTop|leftBottom^leftBottom|rightBottom^rightBottom','cmsmain_slider'");
		$installer->add_config("'2280','portrait','0','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2290','cols','6','text{10|10','cmsmain_slider'");
		$installer->add_config("'2300','rows','4','text{10|10','cmsmain_slider'");
		$installer->add_config("'2310','slicedCols','12','text{10|10','cmsmain_slider'");
		$installer->add_config("'2320','slicedRows','8','text{10|10','cmsmain_slider'");
		$installer->add_config("'2330','slideOn','random','select{next^next|prev^prev|random^random','cmsmain_slider'");
		$installer->add_config("'2340','thumbnails','0','radio{yes^1|no^0','cmsmain_slider'");
		$installer->add_config("'2350','time','7000','text{10|10','cmsmain_slider'");
		$installer->add_config("'2360','transPeriod','1500','text{10|10','cmsmain_slider'");


		$installer->create_table($installer->table('config'),"
      `guild_id` int(11) unsigned NOT NULL DEFAULT '0',
      `config_name` varchar(64) NOT NULL DEFAULT '',
      `config_value` varchar(225) NOT NULL DEFAULT '',
      PRIMARY KEY (`guild_id`,`config_name`)");

		$installer->create_table($installer->table('blocks'),"
			`guild_id` int(11) unsigned NOT NULL DEFAULT '0',
			`block_name` varchar(64) NOT NULL DEFAULT '',
			`block_location` varchar(10) NOT NULL DEFAULT '',
			PRIMARY KEY (`guild_id`,`block_name`)");

		$installer->create_table($installer->table('banners'),"
			`id` int(5) NOT NULL AUTO_INCREMENT,
			`b_id` varchar(10) DEFAULT NULL,
			`b_image` varchar(255) DEFAULT NULL,
			`b_desc` varchar(150) DEFAULT NULL,
			`b_url` varchar(255) NOT NULL DEFAULT '#',
			`b_title` varchar(255) DEFAULT NULL,
			`b_active` int(10) DEFAULT NULL,
			PRIMARY KEY (`id`)");

		$installer->create_table($installer->table('news'),"
			`news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(255) DEFAULT NULL,
			`title` varchar(200) DEFAULT NULL,
			`text` longtext,
			`news_type` varchar(25) DEFAULT NULL,
			`comm_count` int(11) unsigned NOT NULL,
			`poster` varchar(100) DEFAULT NULL,
			`date` datetime DEFAULT NULL,
			`html` tinyint(1),
			PRIMARY KEY (`news_id`)");

		$installer->create_table($installer->table('comments'),"
			`comment_id` int(11) unsigned AUTO_INCREMENT,
			`news_id` int(11) unsigned NOT NULL,
			`author` varchar(16) NOT NULL DEFAULT '',
			`date` datetime,
			`content` longtext,
			`html` tinyint(1),
			PRIMARY KEY (`comment_id`)");

		$installer->add_menu_button('cms_button','guild');

		return true;
	}

	/**
	 * Upgrade functoin
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		global $installer;

    /**
     * Update uninstalls old version and sets new install
     */
		if( version_compare('0.2.3', $oldversion, '>') == true )
		{
			$installer->drop_table($installer->table('config'));
			$installer->drop_table($installer->table('blocks'));
			$installer->drop_table($installer->table('banners'));
			$installer->drop_table($installer->table('news'));
      $installer->remove_all_config();

      $installer->remove_all_menu_button();
      $this->install();
    }

		return true;
	}

	/**
	 * Un-Install function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;
		$installer->drop_table($installer->table('config'));
		$installer->drop_table($installer->table('blocks'));
		$installer->drop_table($installer->table('banners'));
		$installer->drop_table($installer->table('news'));
		$installer->remove_all_config();

		$installer->remove_all_menu_button();
		return true;
	}
}
