<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: /index.php
 *
 * This file is available publicly, it runs and controls all
 * methods.
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://cpframework.org
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Chris Stockton
 * @version 1.5.0
 * @copyright 2000-2006 Chris Stockton
 * @package cpFramework
 * @filesource
 *
 * Roster versioning tag
 * $Id$
 */

/**
 * Inject our configuration file into the global scope.
 */
require("config.php");

/**
 * Require our common header, to initialize our class objects and
 * common shared activity.
 */
require(PATH_LOCAL . "library".DIRECTORY_SEPERATOR."common".DIRECTORY_SEPERATOR."common.header.php");

/**
 * We set our publicly available variables before doing anything these are
 * contained within our main class in a static variable.
 */
cpMain::$system['method_name'] = NULL;
cpMain::$system['method_mode'] = NULL;
cpMain::$system['method_type'] = NULL;
cpMain::$system['method_path'] = NULL;
cpMain::$system['template_path'] = NULL;

/**
 * Redirect handling based off the SYSTEM_REDIRECT_REQUEST.
 */
if(SYSTEM_REDIRECT_REQUEST !== 'off')
{
    if(preg_match('/(^www\.+)/', $_SERVER['HTTP_HOST']) && SYSTEM_REDIRECT_REQUEST === 'http')
    {
        header("Location: " . PATH_REMOTE);
    }
     elseif(!preg_match('/(www\.+)/', $_SERVER['HTTP_HOST']) && SYSTEM_REDIRECT_REQUEST === 'www')
    {
        header("Location: " . preg_replace('/(http:\/\/|www\.)+/', 'http://www.', PATH_REMOTE));
    }
}

/**
 * Perform our autoloading
 */
foreach(file(PATH_LOCAL . "autoload.php") as $key => $value)
{
    (preg_match('/^[a-zA-Z0-9\.\-]+$/', $value = trim($value))) ? ((is_file($file = PATH_LOCAL . "library".DIRECTORY_SEPERATOR."autoload".DIRECTORY_SEPERATOR . $value . ".php")) ? include($file) : cpMain::cpErrorFatal("Autoload Error, Please consult the manual to see the proper directory hiearchy and system functionality. Remember, you can delete files out of the autoload list easily by opening autoload.php in your base directory. The path the system was looking for (or at least 1 of the paths we checked) is: " . $file, __LINE__, __FILE__)) : NULL;
}

/**
 * Shall we use a search friendly urls?
 */
if(SYSTEM_FRIENDLY_URLS)
{

    /**
     * Get our get vars from the seo friendly URL, simple regex is very powerfull. Assuming
     * you are using htaccess and have mod_rewrite running on your server. This feature can
     * be disabled all together.
     *
     * Matches:
     * foo1-bar1/2foo-bar2/something-else.html
     * foo1-bar1-2foo-bar2-something-else.html
     * foo1-bar1-2foo-bar2-something-else.html
     * foo1-bar1.2foo-bar2.something-else.html
     * foo1-bar1.2foo-bar2.something-else.html
     * foo1-bar1/2foo-bar2/something-else/a.html
     * foo1-bar1/2foo-bar2/something-else/
     *  -- And more, you get the picture... --
     *
     * All result in:
     *   ($_GET = Array ( [foo1] => bar1 [2foo] => bar2 [something] => else))
     *
     *
     */
    preg_match_all("/(\w+)\-(\w+)/i", $_SERVER['REQUEST_URI'], $matches);

    /**
     * Inject our variables directly into the _GET super global, we do this to prevent bad practice
     * as placing them into the global scope with variable variables, or utilizing our system
     * array. The _GET scope it is. Please don't argue me this practice as no logic will defeat
     * me in my own mind : )
     */
    foreach($matches[1] as $key => $value)
    {
        $_GET[$value] = $matches[2][$key];
    }
}

/**
 * Determine the users module/plugin request within switch function.
 */
switch(((isset($_GET['plugin']) Xor isset($_GET['module']))) ? (isset($_GET['module'])) ? "module" : "plugin" : "undefined")
{

    /**
     * The users request is for module usage, we must set variables defining
     * the library path and the mode in which the module shall be ran. We
     * then define the method type - being module.
     */
    case 'module':
    cpMain::$system['method_name'] = (isset($_GET['module'])) ? $_GET['module'] : NULL;
    cpMain::$system['method_mode'] = (isset($_GET['mode'])) ? $_GET['mode'] : $_GET['module'];
    cpMain::$system['method_path'] = cpMain::$system['method_name'] . DIRECTORY_SEPERATOR . cpMain::$system['method_mode'];
    cpMain::$system['method_type'] = "modules";
    break;

    /**
     * The users request is for plugin usage, we must set the variables defining
     * the library path, mode and name. As well as the type - being plugin.
     */
    case 'plugin':
    cpMain::$system['method_name'] = (isset($_GET['plugin'])) ? $_GET['plugin'] : NULL;
    cpMain::$system['method_mode'] = (isset($_GET['plugin'])) ? $_GET['plugin'] : NULL;
    cpMain::$system['method_path'] = (isset($_GET['plugin'])) ? $_GET['plugin'] : NULL;
    cpMain::$system['method_type'] = "plugins";
    break;

    /**
     * The users request is invalid or perhaps simply undefined. Theirfore we
     * direct them to the default method. Setting variables accordingly.
     */
    case 'undefined':
    cpMain::$system['method_name'] = (SYSTEM_DEFAULT_METHOD == "modules") ? SYSTEM_DEFAULT_MODULE : SYSTEM_DEFAULT_PLUGIN;
    cpMain::$system['method_mode'] = (SYSTEM_DEFAULT_METHOD == "modules") ? SYSTEM_DEFAULT_MODE : SYSTEM_DEFAULT_PLUGIN;
    cpMain::$system['method_path'] = (SYSTEM_DEFAULT_METHOD == "modules") ? cpMain::$system['method_name'] . DIRECTORY_SEPERATOR . cpMain::$system['method_mode'] : cpMain::$system['method_name'];
    cpMain::$system['method_type'] = SYSTEM_DEFAULT_METHOD;
    break;

}

/**
 * Include the module/plugin core based on the method type and set path.
 */
((is_file($var = PATH_LOCAL . "library".DIRECTORY_SEPERATOR . cpMain::$system['method_type'] . DIRECTORY_SEPERATOR . cpMain::$system['method_path'] . ".php")) ? require($var) : cpMain::cpErrorFatal("Error Loading Requested Method, the path the system was looking for (or at least 1 of the paths we checked) is: " . $var, __LINE__, __FILE__));

/**
 * We only initialize our template system only if the method chosen requires its
 * usage as I want the capability of non-template driven implimentations
 * of this system to remain possible. As its general purpose is to be a
 * invaluable tool across ALL development enviroments.
 */
if(is_object((isset(cpMain::$instance['smarty']) ? cpMain::$instance['smarty'] : NULL)))
{

    /**
     * Make sure the specified module/plugin has a available theme (tempalte file)
     */
    if(is_object((isset(cpMain::$instance['cpusers']) ? cpMain::$instance['cpusers'] : NULL)))
    {
        cpMain::$instance['cpusers']->data['user_theme'] = ((is_file("themes".DIRECTORY_SEPERATOR . cpMain::$instance['cpusers']->data['user_theme']. DIRECTORY_SEPERATOR . cpMain::$system['method_path'] . ".tpl")) ? cpMain::$instance['cpusers']->data['user_theme'] : SYSTEM_DEFAULT_THEME);
    }

    /**
     * Configure smarty
     */
    cpMain::$instance['smarty']->template_dir = PATH_LOCAL . 'library'.DIRECTORY_SEPERATOR.'templates'.DIRECTORY_SEPERATOR.'default/';
    cpMain::$instance['smarty']->compile_dir = PATH_LOCAL . 'library'.DIRECTORY_SEPERATOR.'class'.DIRECTORY_SEPERATOR.'smarty'.DIRECTORY_SEPERATOR.'templates_c'.DIRECTORY_SEPERATOR;
    cpMain::$instance['smarty']->plugins_dir = array(SMARTY_DIR . 'plugins', 'resources'.DIRECTORY_SEPERATOR.'plugins');

    /**
     * Set our CONSTANTS provided by our system
     */
    cpMain::$instance['smarty']->assign("TEMPLATE_PATH", PATH_REMOTE);

    /**
     * We only inject our language into the template if the users specifies.
     * Notice the location of this as it will only work if the users requires
     * the template class to be called upon, module authors make sure you realize
     * that this option only injects the language into the template, it's not
     * required for multi lingual functionality, as the language is injected
     * automaticaly if the lang_(method).php file exists for the users. So, one
     * important practice is to include the default language (english) with every
     * module in case your module relies on the multi lingual template variables
     * to be present.
     */
    if(is_object((isset(cpMain::$instance['cplang']) ? cpMain::$instance['cplang'] : NULL)))
    {

        /**
         * Load the language to our template class
         */
        foreach(cpMain::$instance['cplang']->lang as $key => $value)
        {
            cpMain::$instance['smarty']->assign($key, $value);
        }
    }

    /**
     * Build the template for the specified block
     */
    ((is_file((cpMain::$system['template_path'] !== "") ? $var = cpMain::$system['template_path'] : $var = PATH_LOCAL . "library".DIRECTORY_SEPERATOR."templates".DIRECTORY_SEPERATOR . cpMain::$instance['cpusers']->data['system_theme'] . DIRECTORY_SEPERATOR . cpMain::$system['method_type'] . DIRECTORY_SEPERATOR . cpMain::$system['method_path'] . ".tpl")) ? cpMain::$instance['smarty']->display($var) : ((is_file($var = PATH_LOCAL . "library".DIRECTORY_SEPERATOR."templates".DIRECTORY_SEPERATOR . SYSTEM_DEFAULT_THEME . DIRECTORY_SEPERATOR . cpMain::$system['method_type'] . DIRECTORY_SEPERATOR . cpMain::$system['method_path'] . ".tpl"))
    ? cpMain::$instance['smarty']->display($var) : cpMain::cpErrorFatal("Error Loading Requested Template, the path the system was looking for (or at least 1 of the paths we checked) is: " . $var, __LINE__, __FILE__)));

}

/**
 * Require our common footer, to denitialize the system and carry
 * out end routines and procedure.
 */
require(PATH_LOCAL . "library".DIRECTORY_SEPERATOR."common".DIRECTORY_SEPERATOR."common.footer.php");

?>
