<?php
/**
 * WoWRoster.net WoWRoster
 *
 * ArmorySyncJobAjax Library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: armorysyncjobajax.class.php 359 2008-01-31 14:59:51Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once ($addon['dir'] . 'inc/armorysyncjob.class.php');

class ArmorySyncJobAjax extends ArmorySyncJob {

	var $xmlIndent = 1;

    var $functions = array(
                        array(
                            'link' => '_link',
                            'prepare_update' => '_prepareUpdate',
                            'update_status' => '_updateStatus',
                            'show_status' => '_showStatus',
                            'get_ajax_status' => '_getAjaxStatus'
                        ),
                        array(
                            'link' => '_link',
                            'prepare_update' => '_prepareUpdateMemberlist',
                            'update_status' => '_updateStatusMemberlist',
                            'show_status' => '_showStatusMemberlist',
                            'get_ajax_status' => '_getAjaxStatusMemberList'
                        ),
                    );

    /**
     * fetch insert jobid, fill jobqueue
     *
     */
    function startAjaxStatusUpdate( $id = 0 ) {
        global $roster, $addon;

        $this->_checkEnv();

        if ( ! $this->isAuth ) {
            return array( 'status' => 103, 'errmsg' => 'Not authorized' );
        }
        //return array( 'result' => 'Das ist ein Test', 'status' => 2 );

        if ( isset($_GET['job_id']) ) {
            $this->jobid = $_GET['job_id'];
        }
        if ( isset($_POST['job_id']) ) {
            $this->jobid = $_POST['job_id'];
        }

        $functions = $this->functions[$this->isMemberList];
        $ret = $this->$functions['update_status']();

        $status = $this->$functions['get_ajax_status']();
        $this->_debug( 2, null, 'Started ajax status update', 'OK');

        $result = "\n";
        if ( count( $this->errormessages ) > 0 ) {
            foreach ( $this->errormessages as $message ) {

				$result .= $this->_xmlEncode(
					'errormessage', array( 'target' => 'armorysync_error_table'), null,
					array(
						array('emesg', array( 'type' => 'line' ), $message['line']),
						array('emesg', array( 'type' => 'time' ), $message['time']),
						array('emesg', array( 'type' => 'file' ), $message['file']),
						array('emesg', array( 'type' => 'class' ), $message['class']),
						array('emesg', array( 'type' => 'function' ), $message['function']),
						array('emesg', array( 'type' => 'info' ), $message['info']),
						array('emesg', array( 'type' => 'as_status' ), $message['status']),
						array('edata', array( 'type' => 'args' ), aprint($message['args'], '', 1)),
						array('edata', array( 'type' => 'ret' ), aprint($message['ret'], '', 1)),
					)
				 );
            }
        }

		if( is_array($roster->error->report) )
		{
			foreach( $roster->error->report as $file => $errors )
			{
				$roster->tpl->assign_block_vars('php_debug', array(
					'FILE' => substr($file, strlen(ROSTER_BASE)),
					)
				);
				foreach( $errors as $error )
				{
					$result .= $this->_xmlEncode(
						'errormessage', array( 'target' => 'armorysync_error_table'), null,
						array(
							array('emesg', array( 'type' => 'line' ), null),
							array('emesg', array( 'type' => 'time' ), null),
							array('emesg', array( 'type' => 'file' ), substr($file, strlen(ROSTER_BASE))),
							array('emesg', array( 'type' => 'class' ), null),
							array('emesg', array( 'type' => 'function' ), null),
							array('emesg', array( 'type' => 'info' ), $error),
							array('emesg', array( 'type' => 'as_status' ), null),
							array('edata', array( 'type' => 'args' ), null),
							array('edata', array( 'type' => 'ret' ), null),
						)
					 );
				}
			}
		}

        if ( $addon['config']['armorysync_debuglevel'] > 0 && count( $this->debugmessages ) > 0 ) {
            foreach ( $this->debugmessages as $message ) {

				$result .= $this->_xmlEncode(
					'debugmessage', array( 'target' => 'armorysync_debug_table'), null,
					array(
						array('dmesg', array( 'type' => 'line' ), $message['line']),
						array('dmesg', array( 'type' => 'time' ), $message['time']),
						array('dmesg', array( 'type' => 'file' ), $message['file']),
						array('dmesg', array( 'type' => 'class' ), $message['class']),
						array('dmesg', array( 'type' => 'function' ), $message['function']),
						array('dmesg', array( 'type' => 'info' ), $message['info']),
						array('dmesg', array( 'type' => 'as_status' ), $message['status']),
						array('ddata', array( 'type' => 'args' ), aprint($message['args'], '', 1)),
						array('ddata', array( 'type' => 'ret' ), aprint($message['ret'], '', 1)),
					)
				 );
            }
        }

        $result .= $status;

        if ( $ret ) {
            $reloadTime = $addon['config']['armorysync_reloadwaittime'] * 500;
			$result .= $this->_xmlEncode('reload', array('reloadTime' => $reloadTime), '');
        }
		$result .= "  ";
        return array(   'result' => $result,
                        'status' => 0 );
    }

    /**
     * statusbox Memberlist output with ajax ( experimental )
     *
     * @param int $jobid
     */
    function _getAjaxStatusMemberlist( $jobid = 0 ) {
        global $roster;

        $ret = $this->_getAjaxStatus( $jobid, 1 );
        $this->_debug( 1, htmlspecialchars($ret), 'Prepared ajax meberlist status', 'OK');
        return $ret;
    }

    /**
     * statusbox output with ajax ( experimental )
     *
     * @param int $jobid
     */
    function _getAjaxStatus( $jobid = 0, $memberlist = false ) {
        global $roster, $addon;

        $result = "";

        $perc = 0;
        if ( $this->total == 0 ) {
            $perc = 100;
        } else {
            $perc = round ($this->done / $this->total * 100);
        }


		$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'bar', 'targetId' => 'progress_bar'), $perc);
		$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'text', 'targetId' => 'progress_text'), "$perc% ". $roster->locale->act['complete']. " ($this->done / $this->total)");
        if (isset($this->active_member['name']) && $this->active_member['name'] != '' ) {
			$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'text', 'targetId' => 'progress_next'), $roster->locale->act['next_to_update']. $this->active_member['name']);
        } else {
            $result .= $this->_xmlEncode('statusInfo', array( 'type' => 'text', 'targetId' => 'progress_next') );
        }

        $member = $this->active_member;

        if ( $member ) {
			$id = $memberlist ? $member['guild_id'] : $member['member_id'];

			if ( $id ) {
				foreach ( array( 'guild_info', 'character_info', 'skill_info', 'reputation_info', 'equipment_info', 'talent_info' ) as $key ) {
					if ( $memberlist && $key !== 'guild_info' ) {
						continue;
					}
					if ( isset( $member[$key] ) && $member[$key] == 1 && ( $key == 'guild_info' || $key == 'character_info' ) ) {
						$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'image', 'targetId' => 'as_status_'. $key. '_'. $id ), ROSTER_PATH. "img/pvp-win.gif" );
					} elseif ( isset( $member[$key] ) && $member[$key] == 0 ) {
						$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'image', 'targetId' => 'as_status_'. $key. '_'. $id ), ROSTER_PATH. "img/pvp-loss.gif" );
					} elseif ( isset( $member[$key] ) && ( $key != 'character_info' || ( $memberlist && $key == 'guild_info' ) || ( ! $memberlist && $key != 'guild_info' ) ) ) {
						$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'text', 'targetId' => 'as_status_'. $key. '_'. $id), $member[$key] );
					} else {
						$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'image', 'targetId' => 'as_status_'. $key. '_'. $id ), ROSTER_PATH. "img/blue-question-mark.gif" );
					}
				}

				if ( isset( $member['starttimeutc'] ) ) {
					$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'text', 'targetId' => "as_status_starttimeutc_". $id), $this->_getLocalisedTime($member['starttimeutc']) );
				}

				if (isset( $member['stoptimeutc'] ) ) {
					$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'text', 'targetId' => "as_status_stoptimeutc_". $id), $this->_getLocalisedTime($member['stoptimeutc']) );
				}

				if ( !$memberlist && $member['log'] ) {
					$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'image', 'targetId' => 'as_status_log_'. $id ), ROSTER_PATH. "img/note.gif" );
					$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'overlib', 'overlibType' => 'charLog', 'targetId' => 'as_status_log_'. $id ), str_replace("'", '"', $member['log'] ) );

				} elseif( $member['log'] ) {
					$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'image', 'targetId' => 'as_status_log_'. $id ), ROSTER_PATH. "img/note.gif" );
					$result .= $this->_xmlEncode('statusInfo', array( 'type' => 'overlib', 'overlibType' => 'memberlistLog', 'targetId' => 'as_status_log_'. $id ), str_replace("'", '"', $member['log'] ) );
				}
			}
		}

        $this->_debug( 2, htmlspecialchars($result), 'Prepared ajax status', 'OK');
        return $result;
    }

    /**
     * Encode for ajax XML transfer
     *
     * @param string $tagname
     * @param array $attributes
     * @param string $content
     * @param array $subs
     */
    function _xmlEncode( $tagname = false, $attributes = array(), $content = '', $subs = array() ) {

        if ( $tagname ) {

			$this->xmlIndent++;
			$indent = '';
			for ( $i = 1; $i <= $this->xmlIndent; $i++ ) {
				$indent .= "  ";
			}
			$encContent = urlencode( $content );
			$multi = strlen( $encContent ) > 4000;
			$tag = $indent. "<". $tagname;
			foreach ( $attributes as $key => $value ) {
				$tag .= " ". $key. "=\"". urlencode($value). "\"";
			}
			if ( $multi ) {
				$tag .= " multipart=\"1\">\n";
			} elseif ( is_array($subs) && count(array_keys($subs)) > 0 ) {
				$tag .= ">\n";
			} else {
				$tag .= ">";
			}

			if ( is_array($subs) ) {
				foreach ( $subs as $sub ) {
					list( $subTag, $subAttributes, $subContent ) = $sub;
					$subSubs = array();
					if ( is_array($sub) && isset($sub[3]) ) {
						$subSubs = $sub[3];
					}
					$tag .= $this->_xmlEncode( $subTag, $subAttributes, $subContent, $subSubs );
				}
			}

			if ( $multi ) {
				$i = 1;
				while ( strlen($encContent) > 0 ) {
					 $subEncContent = substr($encContent, 0, 4000);
					 $encContent = substr($encContent, 4000);
					 $tag .= $indent. "  <multi part=\"". $i++. "\">";
					 $tag .= $subEncContent;
					 $tag .= "</multi>\n";
				}
			} else {
				$tag .= $encContent;
			}

			if ( $multi || is_array($subs) && count(array_keys($subs)) > 0 ) {
				$tag .= $indent. "</". $tagname. ">\n";
			} else {
				$tag .= "</". $tagname. ">\n";
			}
			$this->xmlIndent--;

			$this->_debug( 3, $tag, 'Encoded data for XML transfer', 'OK');
			return $tag;
		} else {
			$this->_debug( 0, $tag, 'Encoded data for XML transfer', 'Failed');
		}
    }
}
