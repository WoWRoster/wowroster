<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /trigger.php
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
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id$
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Start the following scripts when "update.php" is called
 *
 * Available variables
 *   - $wowdb       = roster's db layer
 *   - $member_id   = character id from the database ( ex. 24 )
 *   - $member_name = character's name ( ex. 'Jonny Grey' )
 *   - $roster_conf = The entire roster config array
 *   - $mode        = when you want to run the trigger
 *      = 'char'  - during a character update
 *      = 'guild' - during a guild update
 *
 * You may need to do some fancy coding if you need more variables
 *
 * You can just print any needed output
 */
//----------[ INSERT UPDATE TRIGGER BELOW ]-----------------------


// The following is an example "trigger.php" file from zanix's SigGen

//------[ Get DB settings ]-----------------------

$siggen_sql_str = "SHOW TABLES LIKE '".ROSTER_SIGCONFIGTABLE."';";
$siggen_result = $wowdb->query($siggen_sql_str);
$siggen_set = $wowdb->fetch_assoc($siggen_result);

// Use Roster's detection of UniUploader
if( !isset($htmlout) )
{
	global $htmlout;
}

if( !empty($siggen_set) )
{
	// Read SigGen Config data from Database
	$config_str = "SELECT `config_id`,`trigger`,`guild_trigger`,`uniup_compat`,`main_image_size_w`,`main_image_size_h` FROM `".ROSTER_SIGCONFIGTABLE."`;";
	$config_sql = $wowdb->query($config_str);
	if( $config_sql )
	{
		while( $siggen_row = $wowdb->fetch_assoc($config_sql) )
		{
			$SigGenConfig[$siggen_row['config_id']]['id'] = $siggen_row['config_id'];
			$SigGenConfig[$siggen_row['config_id']]['trigger'] = $siggen_row['trigger'];
			$SigGenConfig[$siggen_row['config_id']]['guild_trigger'] = $siggen_row['guild_trigger'];
			$SigGenConfig[$siggen_row['config_id']]['uniup'] = $siggen_row['uniup_compat'];
			$SigGenConfig[$siggen_row['config_id']]['w'] = ($siggen_row['main_image_size_w']*0.2);
			$SigGenConfig[$siggen_row['config_id']]['h'] = ($siggen_row['main_image_size_h']*0.2);
		}
		$wowdb->free_result($config_sql);
	}
	unset($siggen_row,$config_str,$config_sql,$siggen_sql_str,$siggen_result);
}

if( !isset($SigGenConfig) || empty($SigGenConfig) ) { return; }

foreach( $SigGenConfig as $single_config )
{
	if( ($mode == 'char' && $single_config['trigger']) || ($mode == 'guild' && $single_config['guild_trigger']) )
	{
		if( $htmlout == 1 )
		{
			print 'Saving '.ucfirst($single_config['id']).'-[ <img src="'.$roster_conf['roster_dir'].'/addons/siggen/siggen.php?mode='.$single_config['id'].'&amp;etag=0&amp;name='.urlencode(utf8_decode($member_name)).'" width="'.$single_config['w'].'" height="'.$single_config['h'].'" alt="" /> ]<br />'."\n";
		}
		elseif( $single_config['uniup'] && $htmlout == 0 )
		{
			if( ini_get('allow_url_fopen') )
			{
				if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
					$server_name = 'http://'.((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']);
				else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
					$server_name = 'http://'.((!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']);
			    $server_path = $server_name.str_replace('/update.php', '/', $_SERVER['PHP_SELF']);

			    //print $server_path;
				$temp = @readfile($server_path.'addons/siggen/siggen.php?mode='.$single_config['id'].'&etag=0&saveonly=1&name='.urlencode(utf8_decode($member_name)));
				if( $temp != false )
					print '- Saving '.ucfirst($single_config['id'])."\n";
				else
					print '- Could not save '.ucfirst($single_config['id']).": readfile() failed\n";

				unset($temp);
			}
			else
			{
				print 'Cannot save '.ucfirst($single_config['id']).", &quot;allow_url_fopen&quot; is disabled on your server\n".
					"Disable &quot;UniUploader Fix&quot; in SigGen Config\n";
			}
		}
	}
}

unset($SigGenConfig,$single_config);
