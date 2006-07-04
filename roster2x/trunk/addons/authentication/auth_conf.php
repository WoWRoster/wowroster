<?php 
$path_to_liveuser_dir = realpath(dirname(__FILE__).'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR).PATH_SEPARATOR;
ini_set('include_path', $path_to_liveuser_dir . ini_get('include_path'));

require_once 'DB.php';
require_once 'LiveUser.php';
require_once 'LiveUser/Admin.php';
//require_once 'functions/db_layer.php';



//$dsn = '{dbtype}://{user}:{passwd}@{dbhost}/{dbname}';
//$dsn = $roster_conf['dbtype'].'://'.$roster_conf['db_user'].':'.$roster_conf['db_pass'].'@'.$roster_conf['db_host'].'/'.$roster_conf['db_name'];
$dsn = 'mysql://root:mistikack@localhost/roster2';

$db =& DB::connect($dsn);
global $db;

if (PEAR::isError($db)) {
    echo $db->getMessage() . ' ' . $db->getUserInfo();
}

$db->setFetchMode(DB_FETCHMODE_ASSOC);


$addon_conf['authentication'] =
    array(
		'autoInit' => true, 
        'debug' => false,
        'session'  => array(
            'name'     => 'PHPSESSION',
            'varname'  => 'authdata'
        ),
        'login' => array(
            'force'    => true,
        ),
        'logout' => array(
            'destroy'  => true,
        ),
		'cookie' => array(
            'name' => 'roster_auth_cookie',
            'path' => null,
            'domain' => null,
            'lifetime' => 30,
            'savedir' => '../cookies',
            'secure' => false,
			'secret' => 'mysecretkey',
        ),
        'authContainers' => array(
            'DB' => array(
                'type'          => 'DB',
                'expireTime'    => 3600,
                'idleTime'      => 1800,
				'allowDuplicateHandles' => 0,
                'allowEmptyPasswords'   => 0,
				'passwordEncryptionMode' => 'MD5',
                'storage' => array(
                    'dsn' => $dsn,
					'prefix' => 'roster2_addon_auth_',
                    'alias' => array(
						'handle' => 'username',
						'passwd' => 'password',
                        'last_login' => 'last_login',
						'email' => 'email',
						'is_active' => 'is_active',
						'member_id' => 'member_id',
						'status' => 'status',
                    ),
                    'fields' => array(
                        'last_login' => 'timestamp',
                        'is_active' => 'boolean',
						'email' => 'text',
						'member_id' => 'integer',
						'status' => 'text',
                    ),
                    'tables' => array(
                        'users' => array(
                            'fields' => array(
                                'last_login' => false,
                                'is_active' => false,
								'email' => false,
                            ),
                        ),
						'user_char_linktable' => array(
							'fields' => array(
								'member_id' => false,
								'auth_user_id' => false,
								'status' => false,
							),
						),
                    ),
                )
            )
        ),
    'permContainer' => array(
        'type' => 'Complex',
        'storage' => array(
			'DB' => array(
				'dsn' => $dsn, 
				#'prefix' => $roster_conf['db_prefix'].'addon_auth_'
				'prefix' => 'roster2_addon_auth_',
				'tables' => array(        	// contains additional tables         
					'users' => array(
						'fields' => array(
							'last_login' => false,
							'is_active' => false,
							'owner_user_id' => false,
							'owner_group_id' => false,
							'email' => false,
                            ),
                        ),
						'user_char_linktable' => array(
							'fields' => array(
								'member_id' => false,
								'auth_user_id' => false,
								'status' => false,
							),
						),
                    ),
                    'fields' => array(
                        'last_login' => 'timestamp',
                        'is_active' => 'boolean',
						'owner_user_id'  => 'integer',
                        'owner_group_id' => 'integer',
						'email' => 'text',
						'member_id' => 'integer',
						'status' => 'text',
                    ),
                    'alias' => array(
						'handle' => 'username',
						'passwd' => 'password',
                        'last_login' => 'last_login',
						'is_active' => 'is_active',
						'owner_user_id' => 'owner_user_id',
						'owner_group_id' => 'owner_group_id',
						'email' => 'email',
						'member_id' => 'member_id',
						'status' => 'status',
                    ),
                )
            )
        )
    );

PEAR::setErrorHandling(PEAR_ERROR_RETURN);

$LU = &LiveUser::singleton(&$addon_conf['authentication']);
global $LU;

if (!$LU->init()) {
    var_dump($LU->getErrors());
    die();
}
$handle = (array_key_exists('handle', $_REQUEST)) ? $_REQUEST['handle'] : null;
$passwd = (array_key_exists('passwd', $_REQUEST)) ? $_REQUEST['passwd'] : null;
$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;
$remember = (array_key_exists('rememberMe', $_REQUEST)) ? $_REQUEST['rememberMe'] : false;
if ($logout)
{
	$LU->logout(true);
}
elseif(!$LU->isLoggedIn() || ($handle && $LU->getProperty('handle') != $handle))
{
	if (!$handle)
	{
		$LU->login(NULL, NULL, true);
	}
	else
	{
		$LU->login($handle, $passwd, $remember);
		//var_dump($LU->getErrors());
	}
}

$LUA =& LiveUser_Admin::factory(&$addon_conf['authentication']);
$LUA->init();


?>