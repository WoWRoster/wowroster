<?php
/**
 * WoWRoster.net WoWRoster
 *
 * ArmorySync Library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: armorysyncbase.class.php 286 2007-10-31 08:15:23Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class ArmorySyncBase {

    /**
     * helper function for debugging
     *
     * @param string $string
     * @return string date
     */
    function _debug( $level = 0, $ret = false, $info = false, $status = false ) {
        global $roster, $addon;

        if ( $level > $addon['config']['armorysync_debuglevel'] ) {
            return;
        }
        $timestamp = round((format_microtime() - ARMORYSYNC_STARTTIME), 4);
		if( version_compare(phpversion(), '4.3.0','>=') ) {
			$tmp = debug_backtrace();
			$trace = $tmp[1];
        }
        $array = array(
            'time' => $timestamp,
            'file' => isset($trace['file']) ? str_replace($addon['dir'], '', $trace['file']) : 'armorysync.class.php',
            'line' => isset($trace['line']) ? $trace['line'] : '',
            'function' => isset($trace['function']) ? $trace['function'] : '',
            'class' => isset($trace['class']) ? $trace['class'] : '',
            //'object' => isset($trace['object']) ? $trace['object'] : '',
            //'type' => isset($trace['type']) ? $trace['class'] : '',
            'args' => ( $addon['config']['armorysync_debugdata'] != 0 && isset($trace['args']) && !is_object($trace['args']) ) ? $trace['args'] : '',
            'ret' => ( $addon['config']['armorysync_debugdata'] != 0 && isset($ret) && !is_object($ret)) ? $ret : '',
            'info' => isset($info) ? $info : '',
            'status' => isset($status) ? $status : '',
                                        );
        if ( !($level > $addon['config']['armorysync_debuglevel']) ) {
			$this->debugmessages[] = $array;
		}
		if ( $level == 0 ) {
			$this->errormessages[] = $array;
		}
    }
}
