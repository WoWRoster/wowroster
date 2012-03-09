<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.2.0
 * @package    WoWRoster
 */

 /**
 * This is the new sessions lib for roster with the new user system ... why not use sessions to show whos online lol
 *
 */
define('ANONYMOUS','0');

class Session
{
	var $cookie_data = array();
	var $page = array();
	var $data = array();
	var $browser = '';
	var $forwarded_for = '';
	var $host = '';
	var $session_id = '';
	var $ip = '';
	var $load = 0;
	var $time_now = 0;
	var $update_session_page = true;
	var $uid;
	var $auth;
	
	
	function __construct()
	{
		$this->clearSession();
		$this->session_begin();
		$this->auth = new RosterLogin();
	}
	/**
	* Start session management
	*
	* This is where all session activity begins. We gather various pieces of
	* information from the client and server. We test to see if a session already
	* exists. If it does, fine and dandy. If it doesn't we'll go on to create a
	* new one ... pretty logical heh? We also examine the system load (if we're
	* running on a system which makes such information readily available) and
	* halt if it's above an admin definable limit.
	*
	* @param bool $update_session_page if true the session page gets updated.
	*			This can be set to circumvent certain scripts to update the users last visited page.
	*/
	function session_begin($update_session_page = true)
	{
		global $phpEx, $SID, $_SID, $_EXTRA_URL, $roster, $config, $phpbb_root_path;

		// Give us some basic information
		$this->time_now				= time();
		$this->cookie_data			= array('u' => 0, 'k' => '');
		$this->update_session_page	= $update_session_page;
		$this->browser				= (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT']) : '';
		$this->referer				= (!empty($_SERVER['HTTP_REFERER'])) ? htmlspecialchars((string) $_SERVER['HTTP_REFERER']) : '';
		$this->forwarded_for		= (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? htmlspecialchars((string) $_SERVER['HTTP_X_FORWARDED_FOR']) : '';

		$this->host					= $this->extract_current_hostname();
		$this->page					= $this->extract_current_page($roster->config['website_address']);

		if (isset($_COOKIE['roster_user']) && $_COOKIE['roster_user'] != ''|| isset($_COOKIE['roster_pass']) && $_COOKIE['roster_pass'] != '')
		{
			$this->uid = $roster->auth->getUID($_COOKIE['roster_user'],$_COOKIE['roster_pass']);
			$this->cookie_data['user'] = $_COOKIE['roster_user'];
			$this->cookie_data['u'] = $this->uid;
			$this->cookie_data['k'] = request_var('roster_k', '', false, true);
			$this->session_id 		= $_COOKIE['roster_sid'];
		}
		else if (isset($_COOKIE['roster_sid']) || isset($_COOKIE['roster_u']))
		{
			$this->cookie_data['user'] = request_var('roster_user', 0, false, true);
			$this->cookie_data['u'] = $_COOKIE['roster_u'];//, 0, false, true);
			$this->cookie_data['k'] = request_var('roster_k', '', false, true);
			$this->session_id 		= request_var('roster_sid', '', false, true);

			$SID = (defined('NEED_SID')) ? '?sid=' . $this->session_id : '?sid=';
			$_SID = (defined('NEED_SID')) ? $this->session_id : '';

			if (empty($this->session_id))
			{
				$this->session_id = $_SID = request_var('sid', '');
				$SID = '?sid=' . $this->session_id;
				$this->cookie_data = array('u' => 0, 'k' => '');
			}
		}
		else
		{
			$this->session_id = $_SID = request_var('sid', '');
			$SID = '?sid=' . $this->session_id;
		}

		$_EXTRA_URL = array();

		// Why no forwarded_for et al? Well, too easily spoofed. With the results of my recent requests
		// it's pretty clear that in the majority of cases you'll at least be left with a proxy/cache ip.
		$this->ip = (!empty($_SERVER['REMOTE_ADDR'])) ? (string) $_SERVER['REMOTE_ADDR'] : '';
		$this->ip = preg_replace('# {2,}#', ' ', str_replace(',', ' ', $this->ip));

		// split the list of IPs
		$ips = explode(' ', trim($this->ip));

		// Default IP if REMOTE_ADDR is invalid
		$this->ip = '127.0.0.1';

		foreach ($ips as $ip)
		{
			if (preg_match(get_preg_expression('ipv4'), $ip))
			{
				$this->ip = $ip;
			}
			else if (preg_match(get_preg_expression('ipv6'), $ip))
			{
			// Quick check for IPv4-mapped address in IPv6
			if (stripos($ip, '::ffff:') === 0)
			{
				$ipv4 = substr($ip, 7);

				if (preg_match(get_preg_expression('ipv4'), $ipv4))
				{
					$ip = $ipv4;
				}
			}

			$this->ip = $ip;
			}
			else
			{
				// We want to use the last valid address in the chain
				// Leave foreach loop when address is invalid
				break;
			}
		}

		$this->load = false;

		// Load limit check (if applicable)
		if ($config['limit_load'] || $config['limit_search_load'])
		{
			if ((function_exists('sys_getloadavg') && $load = sys_getloadavg()) || ($load = explode(' ', @file_get_contents('/proc/loadavg'))))
			{
				$this->load = array_slice($load, 0, 1);
				$this->load = floatval($this->load[0]);
			}
			else
			{
				set_config('limit_load', '0');
				set_config('limit_search_load', '0');
			}
		}

		// Is session_id is set or session_id is set and matches the url param if required
		if (!empty($this->session_id) && (!defined('NEED_SID') || (isset($_GET['sid']) && $this->session_id === $_GET['sid'])))
		{
			$sql = 'SELECT u.*, s.*
				FROM ' . $roster->db->table('sessions') . ' s, ' . $roster->db->table('user_members') . " u
				WHERE s.session_id = '" . $roster->db->escape($this->session_id) . "'
					AND u.id = s.session_user_id";
			$result = $roster->db->query($sql);
			$this->data = $roster->db->fetch($result);
			$roster->db->free_result($result);

			// Did the session exist in the DB?
			if (isset($this->data['id']))
			{
				// Validate IP length according to admin ... enforces an IP
				// check on bots if admin requires this
//				$quadcheck = ($config['ip_check_bot'] && $this->data['user_type'] & USER_BOT) ? 4 : $config['ip_check'];

				if (strpos($this->ip, ':') !== false && strpos($this->data['session_ip'], ':') !== false)
				{
					$s_ip = short_ipv6($this->data['session_ip'], $config['ip_check']);
					$u_ip = short_ipv6($this->ip, $config['ip_check']);
				}
				else
				{
					$s_ip = implode('.', array_slice(explode('.', $this->data['session_ip']), 0, $config['ip_check']));
					$u_ip = implode('.', array_slice(explode('.', $this->ip), 0, $config['ip_check']));
				}

				$s_browser = (true) ? trim(strtolower(substr($this->data['session_browser'], 0, 149))) : '';
				$u_browser = (true) ? trim(strtolower(substr($this->browser, 0, 149))) : '';

				$s_forwarded_for = (false) ? substr($this->data['session_forwarded_for'], 0, 254) : '';
				$u_forwarded_for = (false) ? substr($this->forwarded_for, 0, 254) : '';
/*
				// referer checks
				// The @ before $config['referer_validation'] suppresses notices present while running the updater
				$check_referer_path = (@$config['referer_validation'] == REFERER_VALIDATE_PATH);
				$referer_valid = true;

				// we assume HEAD and TRACE to be foul play and thus only whitelist GET
				if (@$config['referer_validation'] && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) !== 'get')
				{
					$referer_valid = $this->validate_referer($check_referer_path);
				}
*/
				if ($u_ip === $s_ip && $s_browser === $u_browser && $s_forwarded_for === $u_forwarded_for)
				{
					$session_expired = false;

					if (!$this->validate_session($this->data))
					{
						$session_expired = true;
					}

					if (!$session_expired)
					{
						// Check the session length timeframe if autologin is not enabled.
						// Else check the autologin length... and also removing those having autologin enabled but no longer allowed board-wide.
						if (!$this->data['session_autologin'])
						{
							if ($this->data['session_time'] < $this->time_now - ($config['session_length'] + 60))
							{
								$session_expired = true;
							}
						}
						else if (!$config['allow_autologin'] || ($config['max_autologin_time'] && $this->data['session_time'] < $this->time_now - (86400 * (int) $config['max_autologin_time']) + 60))
						{
							$session_expired = true;
						}
					}

					if (!$session_expired)
					{
						// Only update session DB a minute or so after last update or if page changes
						if ($this->time_now - $this->data['session_time'] > 60 || ($this->update_session_page && $this->data['session_page'] != $this->page['page']))
						{
							$sql_ary = array('session_time' => $this->time_now);

							if ($this->update_session_page)
							{
								$sql_ary['session_page'] = substr($this->page['page'], 0, 199);
								//$sql_ary['session_forum_id'] = $this->page['forum'];
							}

							//$db->sql_return_on_error(true);

							$sql = 'UPDATE ' . $roster->db->table('sessions') . ' SET ' . $roster->db->build_query('UPDATE', $sql_ary) . "
								WHERE session_id = '" . $roster->db->escape($this->session_id) . "'";
							$result = $roster->db->query($sql);

							//$db->sql_return_on_error(false);

							// If the database is not yet updated, there will be an error due to the session_forum_id
							// @todo REMOVE for 3.0.2
							if ($result === false)
							{
								unset($sql_ary['session_forum_id']);

								$sql = 'UPDATE ' . $roster->db->table('sessions') . ' SET ' . $roster->db->build_query('UPDATE', $sql_ary) . "
									WHERE session_id = '" . $roster->db->escape($this->session_id) . "'";
								$roster->db->query($sql);
							}

							if ($this->data['id'] != ANONYMOUS && !empty($config['new_member_post_limit']) && $this->data['user_new'] && $config['new_member_post_limit'] <= $this->data['user_posts'])
							{
								$this->leave_newly_registered();
							}
						}

						$this->data['is_registered'] = ($this->data['id'] != ANONYMOUS) ? true : false;
						$this->data['is_bot'] = (!$this->data['is_registered'] && $this->data['id'] != ANONYMOUS) ? true : false;
						//$this->data['user_lang'] = basename($this->data['user_lang']);

						return true;
					}
				}
				else
				{
					// Added logging temporarly to help debug bugs...
					if (defined('DEBUG_EXTRA') && $this->data['id'] != ANONYMOUS)
					{
						if ($referer_valid)
						{
							add_log('critical', 'LOG_IP_BROWSER_FORWARDED_CHECK', $u_ip, $s_ip, $u_browser, $s_browser, htmlspecialchars($u_forwarded_for), htmlspecialchars($s_forwarded_for));
						}
						else
						{
							add_log('critical', 'LOG_REFERER_INVALID', $this->referer);
						}
					}
				}
			}
		}

		// If we reach here then no (valid) session exists. So we'll create a new one
		return $this->session_create();
	}

	/**
	* Create a new session
	*
	* If upon trying to start a session we discover there is nothing existing we
	* jump here. Additionally this method is called directly during login to regenerate
	* the session for the specific user. In this method we carry out a number of tasks;
	* garbage collection, (search)bot checking, banned user comparison. Basically
	* though this method will result in a new session for a specific user.
	*/
	function session_create($user_id = false, $set_admin = false, $persist_login = false, $viewonline = true)
	{
		global $SID, $_SID, $roster, $config, $cache;
		//echo '<font color=white>session create '.$user_id.'<br></font>';
		$this->data = array();

		// If we're presented with an autologin key we'll join against it.
		// Else if we've been passed a user_id we'll grab data based on that
		if (isset($_COOKIE['roster_k']))
		{
			$sql = 'SELECT `u`.*, `k`.* FROM `' . $roster->db->table('user_members') . '` as `u`, `' . $roster->db->table('sessions_keys') . '` as `k` WHERE `u`.`id` = ' . (int) $_COOKIE['roster_u'] . "
					AND `k`.`user_id` = `u`.`id`
					AND `k`.`key_id` = '" . md5($_COOKIE['roster_k']) . "'";
			$result = $roster->db->query($sql);
			$this->data = $roster->db->fetch($result);
			//print_r($this->data);
			$roster->db->free_result($result);
			$bot = false;
		}
		else if ($user_id !== false && !sizeof($this->data))
		{
			$this->cookie_data['k'] = '';
			$this->cookie_data['u'] = $user_id;

			$sql = 'SELECT *
				FROM ' . $roster->db->table('user_members') . '
				WHERE id = ' . (int) $this->cookie_data['u'] . '';
			$result = $roster->db->query($sql);
			$this->data = $roster->db->fetch($result);
			//print_r($this->data);
			$this->data['is_registered'] = '1';
			$roster->db->free_result($result);
			$bot = false;
		}
		else
		{
			$this->data = array(
				'is_registered'	=> false,
				'id'			=> '0',
				'user'			=> 'Guest',
				
			);
			$bot = false;
		}

		// If no data was returned one or more of the following occurred:
		// Key didn't match one in the DB
		// User does not exist
		// User is inactive
		// User is bot
		if (!is_array($this->data))
		{
			$this->cookie_data['k'] = '';
			$this->cookie_data['u'] = ($bot) ? $bot : ANONYMOUS;
			//echo '<font color=white><br>fail?</font>';

				$sql = 'SELECT *
					FROM ' . $roster->db->table('user_members') . '
					WHERE id = ' . (int) $_COOKIE['roster_u'];

			$result = $roster->db->query($sql);
			$this->data = $roster->db->fetch($result);
			$roster->db->free_result($result);
		}

		if ($this->data['id'] != ANONYMOUS)
		{
			$this->data['session_last_visit'] = (isset($this->data['session_time']) && $this->data['session_time']) ? $this->data['session_time'] : (($this->data['user_lastvisit']) ? $this->data['user_lastvisit'] : time());
		}
		else
		{
			$this->data['session_last_visit'] = $this->time_now;
		}

		//echo '<font color=white><br>usr id '.$this->data['id'].'</font>';
		// Force user id to be integer...
		$this->data['id'] = (int) $this->data['id'];
		//echo '<font color=white><br>usr id '.$this->data['id'].'</font>';

		

		$session_autologin = (($this->cookie_data['k'] || $persist_login) && $this->data['is_registered']) ? true : false;
		$set_admin = ($set_admin && $this->data['is_registered']) ? true : false;

		// Create or update the session
		$sql_ary = array(
			'session_user_id'		=> (int) $this->data['id'],
			'session_start'			=> (int) $this->time_now,
			'session_last_visit'	=> (int) $this->data['session_last_visit'],
			'session_time'			=> (int) $this->time_now,
			'session_browser'		=> (string) trim(substr($this->browser, 0, 149)),
			'session_forwarded_for'	=> (string) $this->forwarded_for,
			'session_ip'			=> (string) $this->ip,
			'session_autologin'		=> ($session_autologin) ? 1 : 0,
			'session_admin'			=> ($set_admin) ? 1 : 0,
			'session_viewonline'	=> ($viewonline) ? 1 : 0,
		);

		if ($this->update_session_page)
		{
			//$sql_ary['session_page'] = (string) substr($this->page['page'], 0, 199);
			//$sql_ary['session_forum_id'] = $this->page['forum'];
		}

		//$db->sql_return_on_error(true);

		$sql = 'DELETE
			FROM ' . $roster->db->table('sessions') . '
			WHERE session_id = \'' . $roster->db->escape($this->session_id) . '\'
				AND session_user_id = ' . ANONYMOUS;

		if (!defined('IN_ERROR_HANDLER') && (!$this->session_id || !$roster->db->query($sql) || !$roster->db->affected_rows()))
		{
			// Limit new sessions in 1 minute period (if required)
			if (empty($this->data['session_time']) && $config['active_sessions'])
			{
//				$db->sql_return_on_error(false);

				$sql = 'SELECT COUNT(session_id) AS sessions
					FROM ' . $roster->db->table('sessions') . '
					WHERE session_time >= ' . ($this->time_now - 60);
				$result = $roster->db->query($sql);
				$row = $roster->db->fetch($result);
				$roster->db->free_result($result);

				if ((int) $row['sessions'] > (int) $config['active_sessions'])
				{
					send_status_line(503, 'Service Unavailable');
					trigger_error('BOARD_UNAVAILABLE');
				}
			}
		}

		// Since we re-create the session id here, the inserted row must be unique. Therefore, we display potential errors.
		// Commented out because it will not allow forums to update correctly
//		$db->sql_return_on_error(false);

		// Something quite important: session_page always holds the *last* page visited, except for the *first* visit.
		// We are not able to simply have an empty session_page btw, therefore we need to tell phpBB how to detect this special case.
		// If the session id is empty, we have a completely new one and will set an "identifier" here. This identifier is able to be checked later.
		if (empty($this->data['session_id']))
		{
			// This is a temporary variable, only set for the very first visit
			$this->data['session_created'] = true;
		}

		$this->session_id = $this->data['session_id'] = md5(unique_id());

		$sql_ary['session_id'] = (string) $this->session_id;
		//$sql_ary['session_page'] = (string) substr($this->page['page'], 0, 199);
		//$sql_ary['session_forum_id'] = $this->page['forum'];

		$sql = 'INSERT INTO ' . $roster->db->table('sessions') . ' ' . $roster->db->build_query('INSERT', $sql_ary);
		$roster->db->query($sql);
		setcookie('roster_sid',$sql_ary['session_id'],(time()+60*60*24*30) );

		//$db->sql_return_on_error(false);

		// Regenerate autologin/persistent login key
		if ($session_autologin)
		{
			$this->set_login_key();
		}

		// refresh data
		$SID = '?sid=' . $this->session_id;
		$_SID = $this->session_id;
		$this->data = array_merge($this->data, $sql_ary);

		if ($this->data['user_lastvisit'] < 100 )
		{
			$cookie_expire = $this->time_now + (($config['max_autologin_time']) ? 86400 * (int) $config['max_autologin_time'] : 31536000);

			$this->set_cookie('u', $this->cookie_data['u'], $cookie_expire);
			$this->set_cookie('k', $this->cookie_data['k'], $cookie_expire);
			$this->set_cookie('sid', $this->session_id, $cookie_expire);

			unset($cookie_expire);

			$sql = 'SELECT COUNT(session_id) AS sessions
					FROM ' . $roster->db->table('sessions') . '
					WHERE session_user_id = ' . (int) $this->data['id'] . '
					AND session_time >= ' . (int) ($this->time_now - (max((60*60*24*30), (60*60*$roster->config['sess_time']))));
			$result = $roster->db->query($sql);
			$row = $roster->db->fetch($result);
			$roster->db->free_result($result);

		}
		else
		{
			$this->data['session_time'] = $this->data['session_last_visit'] = $this->time_now;

			// Update the last visit time
			$sql = 'UPDATE ' . $roster->db->table('user_members') . '
				SET user_lastvisit = ' . (int) $this->data['session_time'] . '
				WHERE id = ' . (int) $this->data['id'];
			$roster->db->query($sql);

			$SID = '?sid=';
			$_SID = '';
		}

		return true;
	}

	function validate_session($user)
	{
		// Check if PHP_AUTH_USER is set and handle this case
		if (isset($_SERVER['PHP_AUTH_USER']))
		{
			$php_auth_user = '';
			set_var($php_auth_user, $_SERVER['PHP_AUTH_USER'], 'string', true);

			return ($php_auth_user === $user['username']) ? true : false;
		}

		// PHP_AUTH_USER is not set. A valid session is now determined by the user type (anonymous/bot or not)
		if ($user['id'] != 0)
		{
			return true;
		}

		return false;
	}

	function extract_current_hostname()
	{
		global $config;

		// Get hostname
		$host = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME'));

		// Should be a string and lowered
		$host = (string) strtolower($host);

		// If host is equal the cookie domain or the server name (if config is set), then we assume it is valid
		if ((isset($config['cookie_domain']) && $host === $config['cookie_domain']) || (isset($config['server_name']) && $host === $config['server_name']))
		{
			return $host;
		}

		// Is the host actually a IP? If so, we use the IP... (IPv4)
		if (long2ip(ip2long($host)) === $host)
		{
			return $host;
		}

		// Now return the hostname (this also removes any port definition). The http:// is prepended to construct a valid URL, hosts never have a scheme assigned
		$host = @parse_url('http://' . $host);
		$host = (!empty($host['host'])) ? $host['host'] : '';

		// Remove any portions not removed by parse_url (#)
		$host = str_replace('#', '', $host);

		// If, by any means, the host is now empty, we will use a "best approach" way to guess one
		if (empty($host))
		{
			if (!empty($config['server_name']))
			{
				$host = $config['server_name'];
			}
			else if (!empty($config['cookie_domain']))
			{
				$host = (strpos($config['cookie_domain'], '.') === 0) ? substr($config['cookie_domain'], 1) : $config['cookie_domain'];
			}
			else
			{
				// Set to OS hostname or localhost
				$host = (function_exists('php_uname')) ? php_uname('n') : 'localhost';
			}
		}

		// It may be still no valid host, but for sure only a hostname (we may further expand on the cookie domain... if set)
		return $host;
	}
	
	function extract_current_page($root_path)
	{
		$page_array = array();

		// First of all, get the request uri...
		$script_name = (!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		$args = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));

		// If we are unable to get the script name we use REQUEST_URI as a failover and note it within the page array for easier support...
		if (!$script_name)
		{
			$script_name = (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
			$script_name = (($pos = strpos($script_name, '?')) !== false) ? substr($script_name, 0, $pos) : $script_name;
			$page_array['failover'] = 1;
		}

		// Replace backslashes and doubled slashes (could happen on some proxy setups)
		$script_name = str_replace(array('\\', '//'), '/', $script_name);

		// Now, remove the sid and let us get a clean query string...
		$use_args = array();

		// Since some browser do not encode correctly we need to do this with some "special" characters...
		// " -> %22, ' => %27, < -> %3C, > -> %3E
		$find = array('"', "'", '<', '>');
		$replace = array('%22', '%27', '%3C', '%3E');

		foreach ($args as $key => $argument)
		{
			if (strpos($argument, 'sid=') === 0)
			{
				continue;
			}

			$use_args[] = str_replace($find, $replace, $argument);
		}
		unset($args);

		// The following examples given are for an request uri of {path to the phpbb directory}/adm/index.php?i=10&b=2

		// The current query string
		$query_string = trim(implode('&', $use_args));

		// basenamed page name (for example: index.php)
		$page_name = (substr($script_name, -1, 1) == '/') ? '' : basename($script_name);
		$page_name = urlencode(htmlspecialchars($page_name));

		// current directory within the phpBB root (for example: adm)
		$root_dirs = explode('/', str_replace('\\', '/', ROSTER_BASE));
		$page_dirs = explode('/', str_replace('\\', '/', ROSTER_BASE));
		$intersection = array_intersect_assoc($root_dirs, $page_dirs);

		$root_dirs = array_diff_assoc($root_dirs, $intersection);
		$page_dirs = array_diff_assoc($page_dirs, $intersection);

		$page_dir = str_repeat('../', sizeof($root_dirs)) . implode('/', $page_dirs);

		if ($page_dir && substr($page_dir, -1, 1) == '/')
		{
			$page_dir = substr($page_dir, 0, -1);
		}

		// Current page from phpBB root (for example: adm/index.php?i=10&b=2)
		$page = (($page_dir) ? $page_dir . '/' : '') . $page_name . (($query_string) ? "?$query_string" : '');

		// The script path from the webroot to the current directory (for example: /phpBB3/adm/) : always prefixed with / and ends in /
		$script_path = trim(str_replace('\\', '/', dirname($script_name)));

		// The script path from the webroot to the phpBB root (for example: /phpBB3/)
		$script_dirs = explode('/', $script_path);
		array_splice($script_dirs, -sizeof($page_dirs));
		$root_script_path = implode('/', $script_dirs) . (sizeof($root_dirs) ? '/' . implode('/', $root_dirs) : '');

		// We are on the base level (phpBB root == webroot), lets adjust the variables a bit...
		if (!$root_script_path)
		{
			$root_script_path = ($page_dir) ? str_replace($page_dir, '', $script_path) : $script_path;
		}

		$script_path .= (substr($script_path, -1, 1) == '/') ? '' : '/';
		$root_script_path .= (substr($root_script_path, -1, 1) == '/') ? '' : '/';

		$page_array += array(
			'page_name'			=> $page_name,
			'page_dir'			=> $page_dir,

			'query_string'		=> $query_string,
			'script_path'		=> str_replace(' ', '%20', htmlspecialchars($script_path)),
			'root_script_path'	=> str_replace(' ', '%20', htmlspecialchars($root_script_path)),

			'page'				=> $page,
			'forum'				=> (isset($_REQUEST['f']) && $_REQUEST['f'] > 0) ? (int) $_REQUEST['f'] : 0,
		);

		return $page_array;
	}
	
	function set_cookie($name, $cookiedata, $cookietime)
	{
		global $roster;
		$domai = str_replace('http://', '' ,$roster->config['website_address']);
		$name_data = rawurlencode('roster_' . $name) . '=' . rawurlencode($cookiedata);
		$expire = gmdate('D, d-M-Y H:i:s \\G\\M\\T', $cookietime);
		$domain = (!$domai || $domai == 'localhost' || $domai == '127.0.0.1') ? '' : '; domain=' . $domai;

		header('Set-Cookie: ' . $name_data . (($cookietime) ? '; expires=' . $expire : '') . '; path=' . ROSTER_BASE . $domain . ((!false) ? '' : '; secure') . '; HttpOnly', false);
	}
	
	/**
	* Set/Update a persistent login key
	*
	* This method creates or updates a persistent session key. When a user makes
	* use of persistent (formerly auto-) logins a key is generated and stored in the
	* DB. When they revisit with the same key it's automatically updated in both the
	* DB and cookie. Multiple keys may exist for each user representing different
	* browsers or locations. As with _any_ non-secure-socket no passphrase login this
	* remains vulnerable to exploit.
	*/
	function set_login_key($user_id = false, $key = false, $user_ip = false)
	{
		global $config, $roster;

		$user_id = ($user_id === false) ? $this->data['id'] : $user_id;
		$user_ip = ($user_ip === false) ? $this->ip : $user_ip;
		$key = ($key === false) ? (($this->cookie_data['k']) ? $this->cookie_data['k'] : false) : $key;

		$key_id = unique_id(hexdec(substr($this->session_id, 0, 8)));

		$sql_ary = array(
			'key_id'		=> (string) md5($key_id),
			'last_ip'		=> (string) $this->ip,
			'last_login'	=> (int) time()
		);

		if (!$key)
		{
			$sql_ary += array(
				'user_id'	=> (int) $user_id
			);
		}

		if ($key)
		{
			$sql = 'UPDATE ' . $roster->db->table('sessions_keys') . '
				SET ' . $roster->db->build_query('UPDATE', $sql_ary) . '
				WHERE user_id = ' . (int) $user_id . "
					AND key_id = '" . $db->sql_escape(md5($key)) . "'";
		}
		else
		{
			$sql = 'INSERT INTO ' . $roster->db->table('sessions_keys') . ' ' . $roster->db->build_query('INSERT', $sql_ary);
		}
		$roster->db->query($sql);

		$this->cookie_data['k'] = $key_id;

		return false;
	}

	/**
	* Reset all login keys for the specified user
	*
	* This method removes all current login keys for a specified (or the current)
	* user. It will be called on password change to render old keys unusable
	*/
	function reset_login_keys($user_id = false)
	{
		global $config, $roster;

		$user_id = ($user_id === false) ? (int) $this->data['user_id'] : (int) $user_id;

		$sql = 'DELETE FROM ' . $roster->db->table('sessions_keys') . '
			WHERE user_id = ' . (int) $user_id;
		$roster->db->query($sql);

		// If the user is logged in, update last visit info first before deleting sessions
		$sql = 'SELECT session_time, session_page
			FROM ' . $roster->db->table('sessions') . '
			WHERE session_user_id = ' . (int) $user_id . '
			ORDER BY session_time DESC';
		$result = $roster->db->query($sql, 1);
		$row = $roster->db->fetch($result);
		$db->sql_freeresult($result);

		if ($row)
		{
			$sql = 'UPDATE ' . $roster->db->table('user_members') . '
				SET user_lastvisit = ' . (int) $row['session_time'] . "
				WHERE id = " . (int) $user_id;
			$roster->db->query($sql);
		}

		// Let's also clear any current sessions for the specified user_id
		// If it's the current user then we'll leave this session intact
		$sql_where = 'session_user_id = ' . (int) $user_id;
		$sql_where .= ($user_id === (int) $this->data['user_id']) ? " AND session_id <> '" . $db->sql_escape($this->session_id) . "'" : '';

		$sql = 'DELETE FROM ' . $roster->db->table('sessions') . "
			WHERE $sql_where";
		$roster->db->query($sql);

		// We're changing the password of the current user and they have a key
		// Lets regenerate it to be safe
		if ($user_id === (int) $this->data['user_id'] && $this->cookie_data['k'])
		{
			$this->set_login_key($user_id);
		}
	}
	function clearSession()
	{
		global $roster;
		$time = time()-(60*60*$roster->config['sess_time']);
		
		// select expired sessions
		$qry  = 'SELECT `session_id` FROM `' . $roster->db->table('sessions') . '` WHERE '
				. '`session_last_visit` < "' . $time . '" ;';
		
		$result = $roster->db->query($qry);
		
		if ($roster->db->num_rows($result) == 0)
		{
			return true;
		}
		
		while ($row = $roster->db->fetch($result)) 
		{			
			// delete sql record values
			$qry = 'DELETE FROM `' . $roster->db->table('sessions') . '` WHERE `session_id` = "' . $row['session_id'] . '";';
			$roster->db->query($qry);
		}
		
		// delete expired sql records
		$qry = 'DELETE FROM `' . $roster->db->table('sessions') . '` WHERE `session_last_visit` < "' . $time . '";';
		$roster->db->query($qry);
		
		return true;
	}
	
	
}
// eof sessions.lib.php

?>