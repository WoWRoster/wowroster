<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /inc/update_hook.php
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
 * @version $Id: update_hook.php 360 2008-02-02 00:49:25Z Zanix $
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}


/**
 * Addon Update class
 * This MUST be the same name as the addon basename
 */
class siggenUpdate
{
	var $messages = '';	    // Update messages
	var $data = array();	// Addon config data automatically pulled from the addon_config table
	var $gendata = array(); // SigGen specific data since the port doesn't use Roster's Config API
	var $files = array();


	/**
	 * Class instantiation
	 * The name of this function MUST be the same name as the class name
	 *
	 * @param array $data	| Addon data
	 * @return recipe
	 */
	function siggenUpdate($data)
	{
		global $roster;

		$this->data = $data;

		// Read SigGen Config data from Database
		$config_str = "SELECT `config_id`,`trigger`,`guild_trigger`,`uniup_compat`,`main_image_size_w`,`main_image_size_h`,`cleardir`,`save_images_dir` FROM `" . $roster->db->table('config',$this->data['basename']) . "`;";

		$config_sql = $roster->db->query($config_str);
		if( $config_sql )
		{
			while( $row = $roster->db->fetch($config_sql, SQL_ASSOC) )
			{
				$this->gendata[$row['config_id']]['trigger'] = $row['trigger'];
				$this->gendata[$row['config_id']]['guild_trigger'] = $row['guild_trigger'];
				$this->gendata[$row['config_id']]['uniup'] = $row['uniup_compat'];
				$this->gendata[$row['config_id']]['clear'] = $row['cleardir'];

				$row['save_images_dir'] = str_replace( '/',DIR_SEP,$row['save_images_dir'] );
				$row['save_images_dir'] = str_replace( '%r',ROSTER_BASE,$row['save_images_dir'] );
				$row['save_images_dir'] = str_replace( '%s',$this->data['dir'],$row['save_images_dir'] );
				$this->gendata[$row['config_id']]['save_dir'] = $row['save_images_dir'];

				$this->gendata[$row['config_id']]['w'] = ($row['main_image_size_w']*0.2);
				$this->gendata[$row['config_id']]['h'] = ($row['main_image_size_h']*0.2);
			}
			$roster->db->free_result();
		}
	}

	/**
	 * Resets addon messages
	 */
	function reset_messages()
	{
		$this->messages = '';
	}

	function guild_pre( $data )
	{
		foreach( $this->gendata as $config )
		{
			if( $config['clear'] )
			{
				$this->cleardir($config['save_dir']);
			}
		}
		return true;
	}

	function guild( $data , $memberid )
	{
		global $roster;

		foreach( $this->gendata as $config => $sigdata )
		{
			if( $sigdata['guild_trigger'] )
			{
				$this->generate($memberid, $config, $sigdata);
			}
		}

		return true;
	}

	function char( $data , $memberid )
	{
		global $roster;

		foreach( $this->gendata as $config => $sigdata )
		{
			if( $sigdata['trigger'] )
			{
				$this->generate($memberid, $config, $sigdata);
			}
		}

		return true;
	}

	function generate( $member_id , $config , $data )
	{
		global $update;

		if( $update->textmode == false )
		{
			$this->messages .= 'Saving ' . $config . '-[ <img src="' . makelink('util-' . $this->data['basename'] . '-' . $config . '&amp;etag=0&amp;member=' . $member_id,true) . '" width="' . $data['w'] . '" height="' . $data['h'] . '" alt="" /> ]<br />' . "\n";
		}
		elseif( $data['uniup'] && $update->textmode == true )
		{
			if( ini_get('allow_url_fopen') )
			{
				$temp = @readfile(str_replace('&amp;','&',makelink('util-' . $this->data['basename'] . '-' . $config . '&amp;etag=0&amp;saveonly=1&amp;member=' . $member_id,true)));

				if( $temp != false )
				{
					$this->messages .= '- Saving ' . $config . "\n";
				}
				else
				{
					$this->messages .= '- Could not save ' . $config . ": function readfile() failed\n";
					return false;
				}

				unset($temp);
			}
			else
			{
				$this->messages .= 'Cannot save ' . $config . ", &quot;allow_url_fopen&quot; is disabled on your server\n"
								.  "Disable &quot;UniUploader Fix&quot; in RosterCP->SigGen\n";
				return false;
			}
		}

		return true;
	}

	/**
	 * Removes a file or directory
	 *
	 * @param string $dir
	 * @return bool
	 */
	function rmdirr( $dir )
	{
		if( is_dir($dir) && !is_link($dir) )
		{
			return ( $this->cleardir($dir) ? rmdir($dir) : false );
		}
		return @unlink($dir);
	}

	/**
	 * Clears a directory of files
	 *
	 * @param string $dir
	 * @return bool
	 */
	function cleardir( $dir )
	{
		$no_delete = array('.','..','.svn','need_write_access.txt');

		if( !($dir = dir($dir)) )
		{
			return false;
		}
		while( false !== $item = $dir->read() )
		{
			if( !in_array($item,$no_delete) && !$this->rmdirr($dir->path . DIR_SEP . $item) )
			{
				$dir->close();
				return false;
			}
		}
		$dir->close();
		return true;
	}
}
