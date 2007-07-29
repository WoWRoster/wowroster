<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: enUS.php 1126 2007-07-27 05:14:27Z Zanix $
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Template
 */

/**
 * Include a template file
 *
 * @param string $file
 *		Template filename
 */
function include_template( $file, $data = array() )
{
	global $roster, $addon;

	if( file_exists($file = $addon['dir'] . DIR_SEP . 'template' . DIR_SEP . $roster->config['locale'] . DIR_SEP . $file) )
	{
		include( $file );
	}
	elseif( file_exists($file = $addon['dir'] . DIR_SEP . 'template' . DIR_SEP . 'enUS' . DIR_SEP . $file) )
	{
		include( $file );
	}
	else
	{
		die_quietly('Failed to open template file '.$file,'News',basename(__FILE__),__LINE__);
	}
}
