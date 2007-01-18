<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}


// UniAdmin Version
define('UA_VER', '0.7.1');
define('NO_CACHE', true);

// Directories
define('UA_INCLUDEDIR', UA_BASEDIR.'include'.DIR_SEP);
define('UA_LANGDIR',    UA_BASEDIR.'language'.DIR_SEP);
define('UA_THEMEDIR',   UA_BASEDIR.'styles'.DIR_SEP);
define('UA_MODULEDIR',  UA_BASEDIR.'modules'.DIR_SEP);
define('UA_CACHEDIR',   UA_BASEDIR.'cache'.DIR_SEP);

// User Levels
define('UA_ID_ANON',  0);
define('UA_ID_USER',  1);
define('UA_ID_POWER', 2);
define('UA_ID_ADMIN', 3);

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
define('UA_URI_UPINI',    'upini');
define('UA_URI_GETINI',   'getini');
define('UA_URI_DETAIL',   'detail');

// URL parameters
define('UA_INDEX',        'index.php');
define('UA_URI_PAGE',     'p');
define('UA_INDEXPAGE',    UA_INDEX.'?'.UA_URI_PAGE);
define('UA_URI_THEME',    'theme');
define('UA_FORMACTION',   UA_INDEXPAGE.( isset($_GET[UA_URI_PAGE]) ? '='.$_GET[UA_URI_PAGE] : '') );


// This is to reject certain settings in UU since we don't need them, or want them displayed
define('UA_REJECT_INI'    ,'CHECKEDADDONS,CHECKEDSVLIST,EXELOC,FILELOCATION,SELECTEDACCT,EXE1,EXE1LOCATION,EXE2,EXE2LOCATION,EXE3,EXE3LOCATION,EXEUULAUNCH,EXEWOWLAUNCH,USERAGENT');

// Database Table names
define('UA_TABLE_ADDONS',   $config['table_prefix'] . 'addons');
define('UA_TABLE_CONFIG',   $config['table_prefix'] . 'config');
define('UA_TABLE_FILES',    $config['table_prefix'] . 'files');
define('UA_TABLE_LOGOS',    $config['table_prefix'] . 'logos');
define('UA_TABLE_SETTINGS', $config['table_prefix'] . 'settings');
define('UA_TABLE_STATS',    $config['table_prefix'] . 'stats');
define('UA_TABLE_USERS',    $config['table_prefix'] . 'users');
define('UA_TABLE_SVLIST',   $config['table_prefix'] . 'svlist');
