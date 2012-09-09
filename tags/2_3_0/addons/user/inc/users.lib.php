<?php 
/** 
 * Dev.PKComp.net user Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @author	   mdeshane
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    user 
 * @subpackage user Handler 
 */ 

if ( !defined('ROSTER_INSTALLED') ) 
{ 
    exit('Detected invalid access to this file!'); 
}
	include_once( $addon['inc_dir'] . 'form.lib.php');
	include_once( $addon['inc_dir'] . 'page.lib.php');
	include_once( $addon['inc_dir'] . 'user.lib.php');
	include_once( $addon['inc_dir'] . 'profile.lib.php');
	include_once( $addon['inc_dir'] . 'messaging.lib.php');
	
class user
{
	var $id;
	var $message;
	var $plugin_data;
	var $db = array(
		'usertable' => '',
		'userlink' => '',
		'uid' => '',
		'uname' => '',
		'pass' => '',
		'email' => '',
		'group_id' => '',
		'active' => '',
		'session' => '',
		'profile' => '',
		'message' => '',
		);

	/**
	 * user Page Class Object
	 *
	 * @var userPage
	 */
	var $admin;

	/**
	 * user Page Class Object
	 *
	 * @var userPage
	 */
	var $page;
	
	/**
	 * user User Class Object
	 *
	 * @var userUser
	 */
	var $user;

	/**
	 * user Plugin Class Object
	 *
	 * @var userPlugin
	 */
	var $plugin;

	/**
	 * user Profile Class Object
	 *
	 * @var userProfile
	 */
	var $profile;

	/**
	 * user Session Class Object
	 *
	 * @var userSession
	 */
	var $session;
	
	public $messaging;
	/**
	 * user Locale Object
	 */
	var $locale;
	
	function __construct()
	{
		global $roster, $addon;
		$this->form = new userForm();
		$this->page = new userPage();
		$this->user = new userUser();
		$this->profile = new usersProfile();
		$this->messaging = new userMessaging();
		
		$this->get_db();
	}

	/**
	 * Fetch all plugin data. We need to cache the active status for plugin_active()
	 * and fetching everything isn't much slower and saves extra fetches later on.
	 */
	function get_plugin_data()
	{
		global $roster, $addon;
		
		$query = "SELECT * FROM `" . $roster->db->table('plugin', $addon['basename']) . "` ORDER BY `basename`;";
		$result = $roster->db->query($query);
		$this->plugin_data = array();
		while( $row = $roster->db->fetch($result,SQL_ASSOC) )
		{
			$this->plugin_data[$row['basename']] = $row;
		}
	}

	/**
	 * Get the db data
	 */
	function get_db()
	{
		global $roster, $addon;
		
		$this->db = array(
			'usertable' => $roster->db->table('user',$addon['basename']),
			'userlink' => $roster->db->table('user_link',$addon['basename']),
			'uid' => 'uid',
			'uname' => 'uname',
			'pass' => 'pass',
			'email' => 'email',
			'group_id' => 'group_id',
			'active' => 'active',
			'session' => $roster->db->table('session',$addon['basename']),
			'profile' => $roster->db->table('profile',$addon['basename']),
			'message' => $roster->db->table('messaging',$addon['basename']),
			);
	}
	
	/**
	 * Get user locale strings
	 */
	function locale($key, $sub_key)
	{
		global $roster, $addon;
		
		$locale_string = $roster->locale->get_string(array($key => $sub_key), $addon['basename']);
		
		return $locale_string;
	}

}