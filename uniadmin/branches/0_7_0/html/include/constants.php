<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}


$debug = ( ( isset($debug) ) ? intval($debug) : 0 );


define('UA_DEBUG', $debug);

// UniAdmin Version
define('UA_VER', '0.7.0');
define('NO_CACHE', true);

// Directories
define('UA_INCLUDEDIR', UA_BASEDIR.'include'.DIR_SEP);
define('UA_LANGDIR',    UA_BASEDIR.'language'.DIR_SEP);
define('UA_THEMEDIR',   UA_BASEDIR.'themes'.DIR_SEP);
define('UA_MODULEDIR',  UA_BASEDIR.'modules'.DIR_SEP);

// User Levels
define('UA_ID_ANON',  0);
define('UA_ID_USER',  1);
define('UA_ID_POWER', 2);
define('UA_ID_ADMIN', 3);

// User Access
define('UA_A_ADDONS',       1  );
define('UA_A_ADDONS_AD',    11);
define('UA_A_ADDONS_EN',    12);
define('UA_A_ADDONS_OP',    13);
define('UA_A_LOGO',         2  );
define('UA_A_SETTINGS',     3  );
define('UA_A_SETTINGS_INI', 31);
define('UA_A_SETTINGS_SV',  32);
define('UA_A_STAT',         4  );
define('UA_A_USER',         5  );
define('UA_A_USER_PASS',    51);
define('UA_A_USER_NAME',    52);
define('UA_A_USER_PASS_A',  53);
define('UA_A_USER_ADD',     54);
define('UA_A_USER_ADD_A',   55);
define('UA_A_USER_DEL',     56);
define('UA_A_USER_DEL_S',   57);
define('UA_A_USER_DEL_A',   58);
define('UA_A_USER_LEV',     59);
define('UA_A_UA_CONF',      6 );

// URI Parameters
define('UA_URI_OP',       'op');
define('UA_URI_ID',       'id');
define('UA_URI_ADD',      'add');
define('UA_URI_DELETE',   'delete');
define('UA_URI_DISABLE',  'disable');
define('UA_URI_ENABLE',   'enable');
define('UA_URI_OPT',      'optional');
define('UA_URI_REQ',      'require');
define('UA_URI_PROCESS',  'process');
define('UA_URI_SVNAME',   'svname');
define('UA_URI_NAME',     'name');
define('UA_URI_LEVEL',    'level');
define('UA_URI_PASS',     'password');
define('UA_URI_NEW',      'new');

define('UA_INDEXPAGE',    'index.php');
define('UA_URI_PAGE',     'p');
define('UA_URI_THEME',    'theme');
define('UA_FORMACTION',    UA_INDEXPAGE.( isset($_GET[UA_URI_PAGE]) ? '?'.UA_URI_PAGE.'='.$_GET[UA_URI_PAGE] : '') );


// Database Table names
define('UA_TABLE_ADDONS',   $config['table_prefix'] . 'addons');
define('UA_TABLE_CONFIG',   $config['table_prefix'] . 'config');
define('UA_TABLE_FILES',    $config['table_prefix'] . 'files');
define('UA_TABLE_LOGOS',    $config['table_prefix'] . 'logos');
define('UA_TABLE_SETTINGS', $config['table_prefix'] . 'settings');
define('UA_TABLE_STATS',    $config['table_prefix'] . 'stats');
define('UA_TABLE_USERS',    $config['table_prefix'] . 'users');
define('UA_TABLE_SVLIST',   $config['table_prefix'] . 'svlist');


define('UA_LOGIN_FORM','<form name="ua_logoutform" style="display:inline;" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'"><input class="submit" name="ua_logout" style="color:red;" type="submit" value="Logout"></form>')

?>