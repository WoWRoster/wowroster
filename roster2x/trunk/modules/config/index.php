<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: modules/config/index.php
 *
 * The configuration module. Contains the configuration interface.
 *
 * -http://en.wikipedia.org/wiki/Modular
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
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
	die("You may not access this file directly.");
}

// Get post vars
$cfgfile = isset($_POST['file'])?$_POST['file']:'cpconf';
$save = isset($_POST['save'])?$_POST['save']:FALSE;

// Load classes
cpMain::loadClass('smarty','smarty');

$config = cpMain::$instance['cpconfig']->loadConfigMeta($cfgfile);

// Preprocess the subtypes.
foreach( $config as $name => &$values )
{
	if( isset($values['meta']['subtype']) )
	{
		switch( $values['meta']['type'] )
		{
			// These all have the same subtypes, currently.
			case 'select': case 'selectm': case 'radio': case 'check':
			{
				switch( $values['meta']['subtype'] )
				{
					case 'dir':
					{
						$values['meta']['option'] = array();
						$dir = PATH_LOCAL.$values['meta']['dir'];
						if (is_dir($dir) && $handle = opendir($dir))
						{
							while (false !== ($file = readdir($handle)))
							{
								// First condition gets rid of ., .., .svn, .htaccess, etc
								if (substr($file,0,1) != '.' && is_dir($dir.DIR_SEP.$file))
								{
									$values['meta']['option'][] = $file;
								}
							}
						}
						break;
					}
					case 'file':
					{
						$values['meta']['option'] = array();
						$dir = PATH_LOCAL.$values['meta']['dir'];
						if (is_dir($dir) && $handle = opendir($dir))
						{
							while (false !== ($file = readdir($handle)))
							{
								// First condition gets rid of ., .., .svn, .htaccess, etc
								if (substr($file,0,1) != '.' && !is_dir($dir.DIR_SEP.$file))
								{
									$values['meta']['option'][] = $file;
								}
							}
						}
						break;
					}
					default:
					{
						break;
					}
				}
				break;
			}
			default:
			{
				break;
			}
		}
	}
}

if( $save )
{
	// Process fields, validate data before writing.
	$submit = array();
	$status = array();
	foreach( $_POST as $name => $value )
	{
		if( substr($name,0,7) == 'config_' )
		{
			// Get the correct name of config_ fields
			$name = substr($name,7);
		}
		else
		{
			// Skip the rest (file, submit)
			continue;
		}		
		$old = $config[$name]['value'];
		$meta = $config[$name]['meta'];
		switch( $meta['type'] )
		{
			case 'password':
				if( !isset($value[1]) )
				{
					// Bad data passed
					$status[] = 'Bad data for password field '.$name;
					break;
				}
				elseif( $value[0] != $value[1] )
				{
					// Passwords not equal
					$status[] = 'Passwords not equal for '.$name;
				}
				elseif( empty($value[0]) )
				{
					// Fail silently: Password unchanged
					break;
				}
				else
				{
					$value = $value[1];
					// and fallthrough to text field
				}
			case 'text':
				if( $old == $value)
				{
					// Fail silently: value unchanged
					break;
				}
				elseif( isset($meta['maxlength']) && strlen($value) < $meta['maxlength'] )
				{
					// Value too long
					$status[] = 'Value too long for '.$name;
					break;
				}
				else
				{
					$submit[$name] = $value;
					break;
				}
			case 'select': case 'radio': // Both the same type
				if( $old == $value)
				{
					// Fail silently: value unchanged
					break;
				}
				elseif( !in_array($value, $meta['option']) )
				{
					// Somehow the posted value isn't an option
					$status[] = 'Selected value is not a valid option for '.$name;
					break;
				}
				else
				{
					$submit[$name] = (string)$value;
					break;
				}
			case 'selectm': case 'check': // Both the same type
				if( $old == $value )
				{
					// Fail silently: value unchanged
					break;
				}
				elseif( !(bool)(array_diff($value, $meta['option']) ) )
				{
					// The posted values contain values that aren't options
					$status[] = 'At least one of the selected values is not a valid option for '.$name;
					break;
				}
				else
				{
					$submit[$name] = (array)$value;
					break;
				}
			case 'bool':
				if( $old == (bool)$value )
				{
					// Fail silently: value unchanged
					break;
				}
				else
				{
					$submit[$name] = (bool)$value;
					break;
				}
			default:
				break;
		}
	}

	cpMain::$instance['cpconfig']->writeConfig($cfgfile, $submit);
	$status[] = 'Config file "'.$cfgfile.'": '.count($submit).' settings changed. File written.';
	cpMain::$instance['smarty']->assign('status', $status);
}
else
{
	cpMain::$instance['smarty']->assign('status', array());
}

// Assign output vars
cpMain::$instance['smarty']->assign('file',$cfgfile);
cpMain::$instance['smarty']->assign('config',$config);

// Assign output function
cpMain::$instance['smarty']->register_function('phpdata','phpdata');

function phpdata($params, &$smarty)
{
	if( ($params['data']) === null)
	{
		return '';
	}
	else
	{
		return var_export($params['data'],true);
	}
}
