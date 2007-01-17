<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: library/language/plugins/lang_index.php
 *
 * This is a language file, it's protected by our security constant
 * to prevent direct access, just in case the file is of importance.
 * You MAY delete all of this header, if the language file doesn't
 * contain any kind of sensitive information.
 *
 * @link http://cpframework.org
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author WoWRoster.net
 * @version 1.5.0
 * @copyright 2000-2006 WoWRoster.net
 * @package cpModule
 * @subpackage Config
 * @filesource
 *
 * Roster versioning tag
 * $Id$
 */

/**
 * This file is never parsed by PHP.
 */
die("You may not access this file directly.");

/**
 * Format: Start each line with LANG_ followed by the language key.
 * The language key may only contain letters and underscores and may
 * not end in an underscore. After the identifier comes a space, then
 * the language string
 */

?>
LANG_DB_TYPE Database engine to use. Currently only MySQLi is supported.
LANG_DB_HOST Database host. Usually localhost.
LANG_DB_USER Database user name.
LANG_DB_PASS Database password.
LANG_DB_NAME Name of the database. Default is R2CMS.
LANG_DB_PREFIX Prefix to use. Default is R2.
LANG_DEF_THEME Default theme.
LANG_DEF_LANG Default language.
LANG_DEF_MODULE Default module to load.
LANG_HIDE_PARAM Nice/SEO url mode.
LANG_REDIRECT_WWW A common issue with developers is that the users like to type www.domain.com when www is actualy a sub domain. As most developers are aware, this causes issues with cookies and tracking. So, by setting this we control the request and redirect them.<br />Options:<br />'www': Redirect to www.PATH_REMOTE (replaces http:// with http://www. (if needed)<br />'http': Redirect to PATH_REMOTE<br />'off': Don't ever redirect domain
LANG_OUTPUT_GZIP Use gzip encoding for templates.
LANG_SMARTY_DEBUG Template debug mode. Enables debug console and forces template recompilation.
