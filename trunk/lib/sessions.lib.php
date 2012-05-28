<?php
//You can do anything with this but you can't claim it or remove this line and the next .
//Copyright : darklight@home.ro 2004 . send feedback here .
class Session
{

	var $trackerTable="UTracker";
	var $newSession=0;
	var $trackerID="";
	var $expireTime=15;
	var $cookieName="woworster";
	var $zsause;
	var $uuid;
	var $ip;
	var $cookie_data = array();
	var $page = array();
	var $data = array();
	var $browser = '';
	var $forwarded_for = '';
	var $host = '';
	var $session_id = '';
	var $referer;
	protected $user_id;
	
	function __construct()
	{
		global $roster;
	
		$this->expireTime = $roster->config['sess_time'];
		
		$this->getuserid();
		$this->UserTracker();
		return true;

	}
	function getuserid()
	{
		global $roster;
		if (isset($_COOKIE['roster_u']) && $_COOKIE['roster_u'] != 0)
		{
			//--echo 'cookie '.$_COOKIE['roster_u'].'<br>';
			define('USER_ID',$_COOKIE['roster_u']);
			return $_COOKIE['roster_u'];
		}
		else if (isset($_COOKIE['roster_pass']) && $_COOKIE['roster_pass'] != 0)
		{
			//--echo 'auth '.$roster->auth->getUID($_COOKIE['roster_user'],$_COOKIE['roster_pass']).'<br>';
			define('USER_ID',$roster->auth->getUID($_COOKIE['roster_user'],$_COOKIE['roster_pass']));
			return $roster->auth->getUID($_COOKIE['roster_user'],$_COOKIE['roster_pass']);
		}
		else
		{
			define('USER_ID','0');
			return '0';
		}
	}
	function UserTracker ()
	{
		global $roster;
		
		//--echo ' '.(__LINE__).' your user id is '.(int) USER_ID.' '.$_COOKIE['roster_u'].' ';
		if (isset($_COOKIE['roster_user']))
		{
			$this->uuid = $roster->auth->getUUID($_COOKIE['roster_user']);
		}
		else
		{
			$this->uuid = $roster->auth->getUUID($_SERVER['HTTP_USER_AGENT']);
		}

		$this->time_now				= time();
		$this->cookie_data			= array('u' => '', 'k' => '');

		$this->browser				= (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT']) : '';
		$this->referer				= (!empty($_SERVER['HTTP_REFERER'])) ? htmlspecialchars((string) $_SERVER['HTTP_REFERER']) : '';
		$this->forwarded_for		= (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? htmlspecialchars((string) $_SERVER['HTTP_X_FORWARDED_FOR']) : '';

		$this->host					= $this->extract_current_hostname();
		$this->page					= $this->extract_current_page($roster->config['website_address']);
		$session_autologin = $set_admin = $viewonline = '';;
		$update = true;
		//remove all the expired sessions . no need to keep them . cookies are long gone anyway .
		$queryd="DELETE FROM `".$roster->db->table('sessions')."` WHERE `session_time`  <= '".(time())."'";
		////--echo $queryd.'<br>';
		$resultd = $roster->db->query($queryd);
		//$roster->db->free_result($resultd);
		$this->trackerID= (isset($this->uuid) ? $this->uuid : $roster->auth->getUUID() );
		$_COOKIE['wrsid'] = session_id();
		$aquery="SELECT `session_user_id` FROM `".$roster->db->table('sessions')."` WHERE `session_user_id`='".$roster->auth->uid."'";

		$aresult = $roster->db->query($aquery);
		$rows = $roster->db->num_rows($aresult);
		$rec = $roster->db->fetch($aresult);
		/*
		echo '<pre>';
		print_r($rec);
		echo '</pre>';
		*/
		if(isset($rec['session_user_id']))
		{

			$page = implode('-',$roster->pages);
			//make the life of the cookie longer and update time and IP .   `session_zsause` = '".USER_ID."',
			$u = (isset($_COOKIE['roster_u']) ?$_COOKIE['roster_u'] : '0');

			
			$xsql = "UPDATE `". $roster->db->table('sessions') ."` SET ".
			"`session_user_id` = '".$u."',".
			"`session_last_visit` = '".time()."', `session_browser` = '".$this->browser."', `session_ip` = '".$this->getIP()."', `session_time` = '".(time()+60*15)."',
			`session_page` = '".substr($this->page['page'], 0, 199)."[$page]'".
			//" WHERE `session_id` = '" . session_id() . "';";
			" WHERE `sess_id` = '" . $this->uuid . "';";
			
			//echo $xsql.'<br>';
			$rx = $roster->db->query($xsql);
			return true;
		}
		else if ($rows == 0)
		{

			$xsql_ary = array(
				'sess_id'				=> $this->trackerID,
				'session_id'				=> session_id(),
				'session_user_id'		=> (int) USER_ID,
				'session_start'			=> (int) time(),
				'session_last_visit'	=> (int) time(),
				'session_time'			=> (int) time()+(60*15),
				'session_page'			=> substr($this->page['page'], 0, 199),
				'session_browser'		=> (string) trim(substr($this->browser, 0, 149)),
				'session_forwarded_for'	=> (string) $this->forwarded_for,
				'session_ip'			=> (string) $this->getIP(),
				'session_autologin'		=> ($session_autologin) ? 1 : 0,
				'session_admin'			=> ($set_admin) ? 1 : 0,
				'session_viewonline'	=> ($viewonline) ? 1 : 0,
			);

			// this allways errors out because the session exists... i hate this class....
			$sql = 'REPLACE INTO `' . $roster->db->table('sessions') . '` ' . $roster->db->build_query('INSERT', $xsql_ary);
			$s = $roster->db->query($sql);
			$qry1 = "UPDATE `" . $roster->db->table('user_members') . "` SET `online` = '1' WHERE `id` = '".$xsql_ary['session_user_id']."'";
			$q = $roster->db->query($qry1);

			$this->newSession=1;
			return true;
		}
		else
		{
			//$roster->set_message( ' An erroro has occured in your session it will now be reset', 'Sessions', 'notice' );
		}
	}
	function getExpiryTime()
	{
		return $this->expireTime;
	}
	function setExpiryTime($minutes)
	{
		if($minutes<15)
			$minutes=15;
		$this->expireTime=$minutes;
		$query="UPDATE `".$roster->db->table('sessions')."` SET `Expire`='".($this->expireTime*60)."' WHERE `ID`='".$this->trackerID."'";
		$result=$roster->db->query($query);
	}
	function myID()
	{
		return $this->trackerID;
	}
	function getTableName()
	{
		return $roster->db->table('sessions');
	}
	function isNewSession()
	{
		return (bool)($this->newSession==1);
	}
	function setVar($name,$value,$uid="")
	{
		if($uid=="")
			$uid=$this->trackerID;
		$data=array();
		$query="SELECT `Data` FROM `".$roster->db->table('sessions')."` WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
		if($roster->db->num_rows($result)>0)
		{
			$rec=$roster->db->fetch($result);
			$data=unserialize($rec[0]);
			if(!is_array($data))
				$data=array();
			mysql_free_result($result);			
		}
		$data["$name"]=$value;
		$query="UPDATE `".$roster->db->table('sessions')."` SET `Data`='".serialize($data)."' WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
	}
	function delVar($name,$uid="")
	{
		if($uid=="")
			$uid=$this->trackerID;
		$data=array();
		$query="SELECT `Data` FROM `".$roster->db->table('sessions')."` WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
		if($roster->db->num_rows($result)>0)
		{
			$rec=$roster->db->fetch($result);
			$data=unserialize($rec[0]);
			if(!is_array($data))
				$data=array();
			mysql_free_result($result);			
		}
		unset($data["$name"]);
		$query="UPDATE `".$roster->db->table('sessions')."` SET `Data`='".serialize($data)."' WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
	}
	function getVar($name,$uid="")
	{
		if($uid=="")
			$uid=$this->trackerID;
		$data=array();
		$query="SELECT `Data` FROM `".$roster->db->table('sessions')."` WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
		if($roster->db->num_rows($result)>0)
		{
			$rec=$roster->db->fetch($result);
			$data=unserialize($rec[0]);
			if(!is_array($data))
				$data=array();
			mysql_free_result($result);			
		}
		return $data["$name"];
	}
	function loginTime($uid="")
	{
		if($uid=="")
			$uid=$this->trackerID;
		$data=0;
		$query="SELECT `JTime` FROM `".$roster->db->table('sessions')."` WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
		if($roster->db->num_rows($result)>0)
		{
			$rec=$roster->db->fetch($result);
			$data=$rec[0];
			mysql_free_result($result);			
		}
		return $data;
	}
	function lastVisitTime($uid="")
	{
		if($uid=="")
			$uid=$this->trackerID;
		$data=0;
		$query="SELECT `Time` FROM `".$roster->db->table('sessions')."` WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
		if($roster->db->num_rows($result)>0)
		{
			$rec=$roster->db->fetch($result);
			$data=$rec[0];
			mysql_free_result($result);			
		}
		return $data;
	}
	function getUserAgent($uid="")
	{
		if($uid=="")
			$uid=$this->trackerID;
		$data="";
		$query="SELECT `UserAgent` FROM `".$roster->db->table('sessions')."` WHERE `ID`='".$uid."'";
		$result=$roster->db->query($query);
		if($roster->db->num_rows($result)>0)
		{
			$rec=$roster->db->fetch($result);
			$data=$rec[0];
			mysql_free_result($result);			
		}
		return $data;
	}
	/*
	function getIP($uid="")
	{
		global $roster;
		if($uid=="")
			$uid=$this->trackerID;
		$data="";
		$query="SELECT `session_ip` FROM `".$roster->db->table('sessions')."` WHERE `sess_id`='".$uid."'";
		$result=$roster->db->query($query);
		if($roster->db->num_rows($result)>0)
		{
			$rec=$roster->db->fetch($result);
			$data=$rec[0];
			mysql_free_result($result);			
		}
		return $data;
	}
	*/
	function getIP()
	{
		$this->ip = (!empty($_SERVER['REMOTE_ADDR'])) ? (string) $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
		$this->ip = preg_replace('# {2,}#', ' ', str_replace(',', ' ', $this->ip));

		// split the list of IPs
		$ips = explode(' ', trim($this->ip));

		foreach ($ips as $ip)
		{
			if (preg_match($this->get_preg_expression('ipv4'), $ip))
			{
				$this->ip = $ip;
			}
			else if (preg_match($this->get_preg_expression('ipv6'), $ip))
			{
			// Quick check for IPv4-mapped address in IPv6
			if (stripos($ip, '::ffff:') === 0)
			{
				$ipv4 = substr($ip, 7);

				if (preg_match($this->get_preg_expression('ipv4'), $ipv4))
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
		return $this->ip;
	}
	function userCount($max=0)
	{
		$query="SELECT COUNT(*) FROM `".$roster->db->table('sessions')."`";
		if($maxSleep>0)
			$query.=" WHERE (".time()."-`JTime`)<".($max*60)."";
		$result=$roster->db->query($query);
		$rec=$roster->db->fetch($result);
		return (int)$rec[0];
	}
	function listUsers()
	{
		$uids=array();
		$result=$roster->db->query("SELECT `ID` FROM `".$roster->db->table('sessions')."`");
		if($roster->db->num_rows($result)>0)
		{
			while($rec=$roster->db->fetch($result))
			{
				array_push($uids,$rec[0]);			
			}
			mysql_free_result($result);
		}
		return $uids;
	}
	function endSession($uid="")
	{
		global $roster;
		if($uid=="")
			$uid=$this->getuserid();
		$query="DELETE FROM `".$roster->db->table('sessions')."` WHERE `session_user_id`='".$uid."'";
		$roster->db->query($query);
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);

		// Finally, destroy the session.
		session_destroy();

	}
	function get_preg_expression($mode)
	{
		switch ($mode)
		{
			case 'email':
				// Regex written by James Watts and Francisco Jose Martin Moreno
				// http://fightingforalostcause.net/misc/2006/compare-email-regex.php
				return '([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*(?:[\w\!\#$\%\'\*\+\-\/\=\?\^\`{\|\}\~]|&amp;)+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)';
			break;

			case 'bbcode_htm':
				return array(
					'#<!\-\- e \-\-><a href="mailto:(.*?)">.*?</a><!\-\- e \-\->#',
					'#<!\-\- l \-\-><a (?:class="[\w-]+" )?href="(.*?)(?:(&amp;|\?)sid=[0-9a-f]{32})?">.*?</a><!\-\- l \-\->#',
					'#<!\-\- ([mw]) \-\-><a (?:class="[\w-]+" )?href="(.*?)">.*?</a><!\-\- \1 \-\->#',
					'#<!\-\- s(.*?) \-\-><img src="\{SMILIES_PATH\}\/.*? \/><!\-\- s\1 \-\->#',
					'#<!\-\- .*? \-\->#s',
					'#<.*?>#s',
				);
			break;

			// Whoa these look impressive!
			// The code to generate the following two regular expressions which match valid IPv4/IPv6 addresses
			// can be found in the develop directory
			case 'ipv4':
				return '#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#';
			break;

			case 'ipv6':
				return '#^(?:(?:(?:[\dA-F]{1,4}:){6}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:::(?:[\dA-F]{1,4}:){0,5}(?:[\dA-F]{1,4}(?::[\dA-F]{1,4})?|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:):(?:[\dA-F]{1,4}:){4}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,2}:(?:[\dA-F]{1,4}:){3}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,3}:(?:[\dA-F]{1,4}:){2}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,4}:(?:[\dA-F]{1,4}:)(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,5}:(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,6}:[\dA-F]{1,4})|(?:(?:[\dA-F]{1,4}:){1,7}:)|(?:::))$#i';
			break;

			case 'url':
			case 'url_inline':
				$inline = ($mode == 'url') ? ')' : '';
				$scheme = ($mode == 'url') ? '[a-z\d+\-.]' : '[a-z\d+]'; // avoid automatic parsing of "word" in "last word.http://..."
				// generated with regex generation file in the develop folder
				return "[a-z]$scheme*:/{2}(?:(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
			break;

			case 'www_url':
			case 'www_url_inline':
				$inline = ($mode == 'www_url') ? ')' : '';
				return "www\.(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
			break;

			case 'relative_url':
			case 'relative_url_inline':
				$inline = ($mode == 'relative_url') ? ')' : '';
				return "(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
			break;
		}

		return '';
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
};
?>
