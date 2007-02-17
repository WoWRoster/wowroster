<?php
/**
 * Roster config file
 * $Id$
 */

/**
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
	die("You may not access this file directly.");
}

#' db_type type=select option[]=cpmysqli
// Database engine to use. Only mysqli is available at the moment
$config['db_type'] = 'cpmysqli';
#' db_host type=text
// Database host. Usually localhost.
$config['db_host'] = 'localhost';
#' db_user type=text
// Database user name.
$config['db_user'] = 'R2CMS';
#' db_pass type=password
// Database password
$config['db_pass'] = 'password';
#' db_name type=text
// Database name. Default is R2CMS
$config['db_name'] = 'R2CMS';
#' db_prefix type=text
// Database prefix. Default is R2_
$config['db_prefix'] = 'R2_';

#' def_theme type=select subtype=dir dir=themes
// Default theme
$config['def_theme'] = 'default';
#' def_lang type=select subtype=dir dir=language
// Default language
$config['def_lang'] = 'english';
#' def_module type=select subtype=dir dir=modules
// If the default page is a module, what module?
$config['def_module'] = 'config';
#' hide_param type=bool
// Use param-value/param2-value2 linking method
$config['hide_param'] = true;
#' redirect_www type=select option[]=www option[]=http option[]=off
// A common issue with developers is that the users like to type
// www.domain.com when www is actualy a sub domain as most
// developers are aware, this causes issues with cookies and tracking
// so by setting this we control the request and redirect them.
//
// Options:
// 'www' Redirect to www.PATH_REMOTE (replaces http:// with http://www. (if needed)
// 'http' Redirect to PATH_REMOTE
// 'off' Don't ever redirect domain
$config['redirect_www'] = 'off';
#' output_gzip type=bool
// Use gzip encoding for templates
$config['output_gzip'] = false;
#' smarty_debug type=bool
// Enable the debug console for templates and forces recompilation of templates
$config['smarty_debug'] = false;
#' r2_debug type=bool
// Enable debugging in R2
$config['r2_debug'] = true;
