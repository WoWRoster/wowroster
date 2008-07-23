<?php
/**
 * WoWRoster.net WoWRoster
 *
 * ArmorySyncJob Library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: armorysyncjob.class.php 371 2008-02-17 16:00:51Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once ($addon['dir'] . 'inc/constants.php');
require_once ($addon['dir'] . 'inc/armorysyncbase.class.php');

class ArmorySyncJob extends ArmorySyncBase {

    var $jobid;
    var $members = array();
    var $active_member = array();
    var $time_started;
    var $title;

    var $done;
    var $total;

    var $message;

    var $ArmorySync;
    var $id = 0;
    var $isMemberList = 0;
    var $isAuth = 0;
    var $link;
    var $dataNotAccepted = 0;
	var $util_type = '';

    var $header;

    var $debugmessages = array();
    var $errormessages = array();

    var $functions = array(
                        array(
                            'link' => '_link',
                            'prepare_update' => '_prepareUpdate',
                            'update_status' => '_updateStatus',
                            'show_status' => '_showStatus',
                        ),
                        array(
                            'link' => '_link',
                            'prepare_update' => '_prepareUpdateMemberlist',
                            'update_status' => '_updateStatusMemberlist',
                            'show_status' => '_showStatusMemberlist',
                        ),
                    );

    /**
     * Initialize ArmorySync class
     *
     */
    function _init() {
        global $addon;

        if ( ! is_object( $this->ArmorySync ) ) {
            require_once ($addon['dir'] . 'inc/armorysync.class.php');
            $this->ArmorySync = new ArmorySync();
            $this->ArmorySync->debugmessages = &$this->debugmessages;
            $this->ArmorySync->errormessages = &$this->errormessages;
        }
        $this->_debug( 3, null, 'Get ArmorySync object', 'OK');
    }

    /**
     * Show error on deprecated Roster
     *
     */
    function _showErrorRosterDeprecated() {
        global $roster, $addon;

        $html = sprintf( $roster->locale->act['roster_deprecated_message'], ROSTER_VERSION, ARMORYSYNC_VERSION, ARMORYSYNC_REQUIRED_ROSTER_VERSION);
        $out = messagebox( $html , "<span class=\"title_text\">". $roster->locale->act['roster_deprecated']."</span>" , $style='sred' , '400px' );
        print $out;
        $this->_debug( 3, $out, 'Printed error message', 'OK');
    }

    /**
     * Check if Roster is new enough
     *
     */
    function _isRequiredRosterVersion() {
        $ret = version_compare( ARMORYSYNC_REQUIRED_ROSTER_VERSION, ROSTER_VERSION, '<=');
        $this->_debug( 2, $ret, 'Check required Roster version', $ret ? 'OK': 'Failed' );
        return $ret;
    }

    /**
     * Show error on deprecated Roster
     *
     */
    function _showErrorArmorySyncNotUpgraded() {
        global $roster, $addon;

        $html = sprintf( $roster->locale->act['armorysync_not_upgraded_message'], ARMORYSYNC_VERSION, $addon['version']);
        $out = messagebox( $html , "<span class=\"title_text\">". $roster->locale->act['armorysync_not_upgraded']."</span>" , $style='sred' , '400px' );
        $this->_debug( 3, $out, 'Printed error message', 'OK');
        print $out;
    }

    /**
     * Check if ArmorySync is new enough
     *
     */
    function _isRequiredArmorySyncVersion() {
        global $addon;
        $ret =  version_compare( ARMORYSYNC_VERSION, $addon['version'], '<=');
        $this->_debug( 2, $ret, 'Check required ArmorySync version', $ret ? 'OK': 'Failed' );
        return $ret;
    }

    /**
     * Check if ArmorySync cache dir is writeable
     *
     */
    function _isCacheWriteable() {
        global $addon;
		$dir = $addon['dir']. 'cache';
        $ret = is_writable($dir);
        $this->_debug( 2, $ret, 'Check ArmorySync cache dir writeable', $ret ? 'OK': 'Failed' );
        return $ret;
    }

    /**
     * Show error on cache dir not writeable
     *
     */
    function _showErrorCacheNotWriteable() {
        global $roster, $addon;

        $html = $roster->locale->act['cache_not_writable_message'];
        $out = messagebox( $html , "<span class=\"title_text\">". $roster->locale->act['cache_not_writable'].
						  "</span>" , $style='sred' , '400px' );
        $this->_debug( 3, $out, 'Printed error message', 'OK');
        print $out;
    }

    /**
     * Check if max_execution_time is high enough on Character update method
     *
     */
    function _isMaxExTimeHighEnoughOnCharachterUpdate() {
        global $addon;
		$method = $addon['config']['armorysync_fetch_method'];
		$maxExTime = get_cfg_var('max_execution_time');
		$assumedFailedFetches = 50 / 100 * 20; // 20% of 50 fetches
		$fetchTimeout = $addon['config']['armorysync_fetch_timeout'];
		$retrys = $addon['config']['armorysync_fetch_retrys'];
		$needed = $assumedFailedFetches * $fetchTimeout * $retrys;
        $ret = $method == 1 && $maxExTime <= $needed ? false : true;
        $this->_debug( 2, $ret, 'Check MaxExTime on Character Update method', $ret ? 'OK': 'Failed' );
        return $ret;
    }

    /**
     * Show error on cache dir not writeable
     *
     */
    function _showErrorMexExTimeToLow() {
        global $roster, $addon;

		$maxExTime = get_cfg_var('max_execution_time');
		$assumedFailedFetches = 50 / 100 * 20; // 20% of 50 fetches
		$fetchTimeout = $addon['config']['armorysync_fetch_timeout'];
		$retrys = $addon['config']['armorysync_fetch_retrys'];
		$needed = $assumedFailedFetches * $fetchTimeout * $retrys;

        $html = sprintf( $roster->locale->act['max_execution_time_low_message'],
						$maxExTime, $fetchTimeout, $retrys, $needed);
        $out = messagebox( $html , "<span class=\"title_text\">". $roster->locale->act['max_execution_time_low'].
						  "</span>" , $style='sred' , '500px' );
        $this->_debug( 3, $out, 'Printed error message', 'OK');
        print $out;
    }

    /**
     * fetch insert jobid, fill jobqueue
     *
     */
    function start() {
        global $roster, $addon;

        $this->_showHeader();
        $this->_checkEnv();

        if ( ! $this->isAuth ) {
            $this->_showFooter();
            return;
        }
        if ( ! $this->_isRequiredRosterVersion() ) {
            $this->_showErrorRosterDeprecated();
            return;
        }

        if ( ! $this->_isRequiredArmorySyncVersion() ) {
            $this->_showErrorArmorySyncNotUpgraded();
            return;
        }

        if ( ! $this->_isCacheWriteable() ) {
            $this->_showErrorCacheNotWriteable();
            return;
        }

        if ( ! $this->_isMaxExTimeHighEnoughOnCharachterUpdate() ) {
            $this->_showErrorMexExTimeToLow();
            return;
        }

        if ( !$this->id && isset($roster->pages[2]) && $roster->pages[2] == 'add' && !isset($_POST['process']) ) {

            $this->_showAddScreen();

        } elseif ( !$this->id && isset($roster->pages[2]) && $roster->pages[2] == 'add' && isset($_POST['process']) && $_POST['process'] == 'addguild' ) {

			$this->isMemberList = 1;
			$this->_startAddGuild();

        } elseif ( !$this->id && isset($roster->pages[2]) && $roster->pages[2] == 'add' && isset($_POST['process']) && $_POST['process'] == 'addchar' ) {

            $this->_startAddChar();

        } elseif ( $this->id && $addon['config']['armorysync_skip_start'] == 0 && !( isset($_GET['job_id']) || isset($_POST['job_id']) ) ) {

            $this->_showStartPage();

        } elseif ( $this->id && ( isset($_GET['job_id']) || isset($_POST['job_id']) || $addon['config']['armorysync_skip_start'] == 1 ) ) {

            $this->_startSyncing();

        } else {

            $this->_showErrors();
        }
        $this->_showFooter();
        $roster->output['html_head'] = $this->header;
        $this->_debug( 1, null, 'Job done', 'OK');
    }


    /**
     * fetch insert jobid, fill jobqueue
     *
     */
    function _showErrors() {
        global $roster;
        if ( $roster->scope == 'char' ) {
            $html = $roster->locale->act['error_no_character']. "<br />&nbsp;&nbsp;".
                    $roster->locale->act['error_use_menu']. "&nbsp;&nbsp;";
        } elseif ( $roster->scope == 'guild' ) {
            $html = $roster->locale->act['error_no_guild']. "<br />&nbsp;&nbsp;".
                    $roster->locale->act['error_use_menu']. "&nbsp;&nbsp;";
        } elseif ( $roster->scope == 'realm' ) {
            $html = $roster->locale->act['error_no_realm']. "<br />&nbsp;&nbsp;".
                    $roster->locale->act['error_use_menu']. "&nbsp;&nbsp;";
        }
        $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
        $this->_debug( 3, $out, 'Printed error message', 'OK');
        print $out;
    }


    /**
     * fetch insert jobid, fill jobqueue
     *
     */
    function _startSyncing() {
        global $roster;

        if ( isset($_GET['job_id']) ) {
            $this->jobid = $_GET['job_id'];
        }
        if ( isset($_POST['job_id']) ) {
            $this->jobid = $_POST['job_id'];
        }
        $functions = $this->functions[$this->isMemberList];
        if ( $this->jobid == 0 ) {
            if ( $this->$functions['prepare_update']() ) {
                $ret = $this->$functions['update_status']();
                $this->$functions['show_status']();
                if ( $ret ) {
                    $this->$functions['link']();
                }
            } else {
                $this->_nothingToDo();
            }
        } else {
            $ret = $this->$functions['update_status']();
            //if ( $ret ) {
            //    $ret = $this->$functions['update_status']();
            //}
            $this->$functions['show_status']();
            if ( $ret ) {
                $this->$functions['link']();
            }
        }
        $this->_debug( 1, null, 'Finished sync job', 'OK');
    }

    /**
     * Adding new guild to roster
     *
     */
    function _startAddGuild() {
        global $roster;
        $out = '';
        if ( isset($_POST['action']) && $_POST['action'] == 'add' ) {

            if ( isset($_POST['name']) && isset($_POST['server']) && isset($_POST['region']) ) {

                $name = urldecode(trim(stripslashes( $_POST['name'] )));
                $server = urldecode(trim(stripslashes( $_POST['server'] )));
                $region = strtoupper($_POST['region']);

                if ( $region == "EU" || $region == "US" ) {
                    if ( $this->_checkGuildExist( $name, $server, $region ) ) {

                        if ( $id = $this->_insertGuild( $name, $server, $region ) ) {

                            if ( $this->_insertUploadRule( $name, $server, $region, 0 ) ) {
                                if ( $this->_prepareUpdateMemberlist( $id, $name, $server, $region ) ) {
                                    $ret = $this->_updateStatusMemberlist();
                                    $link = makelink('guild-armorysync-memberlist&a=g:'. $id);
                                    $this->_showStatusMemberlist();
                                    if ( $ret ) {
										$this->util_type = 'addguild';
                                        $this->_link();//_guildMemberlist( $id )
                                    }
                                    $this->_debug( 1, null, 'Added guild', 'OK');
                                } else {
                                    $this->_debug( 0, null, 'Added guild', 'Failed. No job found');
                                }
                            } else {
                                $html = "&nbsp;&nbsp;".
                                        $roster->locale->act['error_uploadrule_insert'].
                                        "&nbsp;&nbsp;";
                                $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                            }
                        } else {
                            $html = "&nbsp;&nbsp;".
                                    $roster->locale->act['error_guild_insert'].
                                    "&nbsp;&nbsp;";
                            $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                        }
                    } else {
                        $html = "&nbsp;&nbsp;".
                                $roster->locale->act['error_guild_notexist'].
                                "&nbsp;&nbsp;";
                        $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                    }
                } else {
                    $html = "&nbsp;&nbsp;".
                            $roster->locale->act['error_wrong_region'].
                            "&nbsp;&nbsp;";
                    $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                }
            } else {
                $html = "&nbsp;&nbsp;".
                        $roster->locale->act['error_missing_params'].
                        "&nbsp;&nbsp;";
                $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
            }
        }
        if ( $out ) {
            $this->_debug( 1, $out, 'Added guild', 'Failed');
            print $out;
        }
    }

    /**
     * Adding new character to roster
     *
     */
    function _startAddChar() {
        global $roster;
        $out = '';
        if ( isset($_POST['action']) && $_POST['action'] == 'add' ) {

            if ( isset($_POST['name']) && isset($_POST['server']) && isset($_POST['region']) ) {

                $name = urldecode(trim(stripslashes( $_POST['name'] )));
                $server = urldecode(trim(stripslashes( $_POST['server'] )));
                $region = strtoupper($_POST['region']);

                if ( $region == "EU" || $region == "US" ) {
                    if ( $this->_checkCharExist( $name, $server, $region ) ) {

                        if ( $id = $this->_insertChar( $name, $server, $region ) ) {

                            if ( $this->_insertUploadRule( $name, $server, $region, 2 ) ) {
                                if ( $this->_prepareUpdate( $id, $name, $server, $region ) ) {
                                    $ret = $this->_updateStatus();
                                    $this->_showStatus();
                                    if ( $ret ) {
										$this->util_type = 'addchar';
                                        $this->_link();
                                    }
                                    $this->_debug( 1, null, 'Added char', 'OK');
                                } else {
                                    $this->_debug( 0, null, 'Added char', 'Failed. No job found');
                                }
                            } else {
                                $html = "&nbsp;&nbsp;".
                                        $roster->locale->act['error_uploadrule_insert'].
                                        "&nbsp;&nbsp;";
                                $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                            }
                        } else {
                            $html = "&nbsp;&nbsp;".
                                    $roster->locale->act['error_char_insert'].
                                    "&nbsp;&nbsp;";
                            $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                        }
                    } else {
                        $html = "&nbsp;&nbsp;".
                                $roster->locale->act['error_char_notexist'].
                                "&nbsp;&nbsp;";
                        $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                    }
                } else {
                    $html = "&nbsp;&nbsp;".
                            $roster->locale->act['error_wrong_region'].
                            "&nbsp;&nbsp;";
                    $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
                }
            } else {
                $html = "&nbsp;&nbsp;".
                        $roster->locale->act['error_missing_params'].
                        "&nbsp;&nbsp;";
                $out = messagebox( $html , $roster->locale->act['error'] , $style='sred' , '' );
            }
        }
        if ( $out ) {
            $this->_debug( 1, $out, 'Added guild', 'Failed');
            print $out;
        }
    }

    /**
     * fetch insert jobid, fill jobqueue
     *
     */
    function _checkEnv() {
        global $roster;
        if ( $roster->pages[0] == 'ajax') {
            $this->isMemberlist = $_POST['memberlist'];
            if ( $_POST['scope'] == 'char') {
                $this->isAuth = $this->_checkAuth('armorysync_char_update_access');
            } elseif ( $_POST['scope'] == 'guild') {
                if ( isset( $_POST['page']) && $_POST['page'] == 'memberlist' ) {
                    $this->isAuth = $this->_checkAuth('armorysync_guild_memberlist_update_access');
                    $this->isMemberList = 1;
                } else {
                    $this->isAuth = $this->_checkAuth('armorysync_guild_update_access');
                }
            } elseif ( $_POST['scope'] == 'realm') {
                $this->isAuth = $this->_checkAuth('armorysync_realm_update_access');
            } elseif ( $_POST['scope'] == 'util') {
				if ( isset($_POST['util_type']) ) {
					if ( $_POST['util_type'] == 'addguild' ) {
						$this->isMemberList = 1;
					}
				}
                $this->isAuth = $this->_checkAuth('armorysync_guild_add_access');
            }
        } elseif ( $roster->scope == 'char' ) {
            $this->id = $roster->data['member_id'];
            $this->title = "<span class=\"title_text\">". $roster->locale->act['armorySyncTitle_Char']. "</span>\n";
            $this->isAuth = $this->_checkAuth('armorysync_char_update_access');
        } elseif ( $roster->scope == 'guild' && isset( $roster->pages[2] ) && $roster->pages[2] == 'memberlist' ) {
            $this->id = $roster->data['guild_id'];
            $this->title = "<span class=\"title_text\">". $roster->locale->act['armorySyncTitle_Guildmembers']. "</span>\n";
            $this->isMemberList = 1;
            $this->isAuth = $this->_checkAuth('armorysync_guild_update_access');
        } elseif ( $roster->scope == 'guild' ) {
            $this->id = $roster->data['guild_id'];
            $this->title = "<span class=\"title_text\">". $roster->locale->act['armorySyncTitle_Guild']. "</span>\n";
            $this->isAuth = $this->_checkAuth('armorysync_guild_memberlist_update_access');
        } elseif ( $roster->scope == 'realm' ) {
            $this->id = $roster->data['server'];
            $this->title = "<span class=\"title_text\">". $roster->locale->act['armorySyncTitle_Realm']. "</span>\n";
            $this->isAuth = $this->_checkAuth('armorysync_realm_update_access');
        } elseif ( $roster->scope == 'util' ) {
            $this->title = "<span class=\"title_text\">". $roster->locale->act['armorySyncTitle_Guildmembers']. "</span>\n";
			$this->id = isset($_POST['job_id']) ? $_POST['job_id'] : null;
            //$this->isMemberList = 1;
            $this->isAuth = $this->_checkAuth('armorysync_guild_add_access');
        } else {
            $this->_debug( 0, array( '$_GET' => $_GET, '$_POST' => $_POST, 'scope' => $roster->scope, 'data' => $roster->data ), 'Checking environment', 'Failed');
            return;
        }
        $this->_debug( 2, array( '$_GET' => $_GET, '$_POST' => $_POST, 'scope' => $roster->scope, 'data' => $roster->data ), 'Checking environment', 'OK');
    }

    /**
     * fetch insert jobid, fill jobqueue
     *
     */
    function _prepareUpdate( $id = 0, $name = false , $server = false , $region = false ) {
        global $roster, $addon;

        if ( ! $id ) {
            $id = isset($roster->data['member_id']) ? $roster->data['member_id'] : 0;
        }
        if ( ! $name ) {
            $name = isset($roster->data['name']) ? $roster->data['name'] : false;
        }
        if ( ! $server ) {
            $server = isset($roster->data['server']) ? $roster->data['server'] : false;
        }
        if ( ! $region ) {
            $region = isset($roster->data['region']) ? $roster->data['region'] : false;
        }

        $this->time_started = gmdate('Y-m-d H:i:s');

        if ( $roster->scope == 'char' || $roster->scope == 'util' ) {

            $this->members = array(
                        array(
                                'member_id' => $id,
                                'name' => $name,
                                'guild_id' => $roster->data['guild_id'] ? $roster->data['guild_id'] : 0,
                                'guild_name' => $roster->data['guild_name'] ? $roster->data['guild_name'] : '',
                                'server' => $server,
                                'region' => $region ) );
        } elseif ( $roster->scope == 'guild' ) {

            $this->members = $this->_getGuildMembersToUpdate();
        } elseif ( $roster->scope == 'realm' ) {

            $this->members = $this->_getRealmMembersToUpdate();
        }

        if ( array_keys( $this->members ) ) {

            $this->jobid = $this->_insertJobID($this->time_started);
            $this->_insertMembersToJobqueue($this->jobid, $this->members);
            $this->_debug( 1, true, 'Prepared character update job', 'OK');
            return true;
        }
        $this->_debug( 1, false, 'Prepared character update job', 'Failed');
        return false;
    }

    /**
     * fetch insert jobid, fill jobqueue
     *
     */
    function _prepareUpdateMemberlist( $id = 0, $name = false , $server = false , $region = false ) {
        global $roster, $addon;

        if ( ! $id ) {
            $id = $roster->data['guild_id'];
        }
        if ( ! $name ) {
            $name = $roster->data['guild_name'];
        }
        if ( ! $server ) {
            $server = $roster->data['server'];
        }
        if ( ! $region ) {
            $region = $roster->data['region'];
        }


        $this->time_started = gmdate('Y-m-d H:i:s');

        $this->members = array(
                    array(
                            'name' => false,
                            'member_id' => false,
                            'guild_id' => $id,
                            'guild_name' => $name,
                            'server' => $server,
                            'region' => $region ) );

        if ( array_keys( $this->members ) ) {

            $this->jobid = $this->_insertJobID($this->time_started);
            $this->_insertMembersToJobqueue($this->jobid, $this->members);
            $this->_debug( 1, true, 'Prepared memberlist update job', 'OK');
            return true;
        }
        $this->_debug( 1, false, 'Prepared memberlist update job', 'Failed');
        return false;
    }

    /**
     * statusbox output
     *
     * @param int $jobid
     */
    function _nothingToDo() {
        global $roster;

        $html = '<span class="title_text">&nbsp;&nbsp;'. $roster->locale->act['nothing_to_do']. '&nbsp;&nbsp;</span>';

        $out = messagebox( $html , $title=$this->title , $style='syellow' , $width='' );
        $this->_debug( 3, $out, 'Printed error message', 'OK');
        print $out;
    }


    /**
     * statusbox output with templates
     *
     * @param int $jobid
     */
    function _showStatus( $jobid = 0, $memberlist = false ) {
        global $roster, $addon;

        $jscript = "<script type=\"text/javascript\" src=\"". $addon['url_path']. "js/armorysync.js\"></script>\n";

        if ( $addon['config']['armorysync_pic_effects'] &&
            (   $addon['config']['armorysync_pic1_show'] ||
                $addon['config']['armorysync_pic2_show'] ||
                $addon['config']['armorysync_pic3_show'] ) ) {

            $jscript .= "<script type=\"text/javascript\" src=\"". $addon['url_path']. "js/prototype.js\"></script>\n";
            $jscript .= "<script type=\"text/javascript\" src=\"". $addon['url_path']. "js/scriptaculous.js\"></script>\n";
            $jscript .= "<script type=\"text/javascript\" src=\"". $addon['url_path']. "js/effects.js\"></script>\n";
        }

        $jscript .= '
<script type="text/javascript">
    var armorysync_debuglevel = '. $addon['config']['armorysync_debuglevel']. ';
    var armorysync_debugdata = '. $addon['config']['armorysync_debugdata']. ';
</script>
';
    //function armorysync_debuglevel() { return '. $addon['config']['armorysync_debuglevel']. '; }
    //function armorysync_debugdata() { return '. $addon['config']['armorysync_debugdata']. '; }

        $this->header .= $jscript;

        $members = $this->members;

        $status = isset($_POST['StatusHidden']) ? $_POST['StatusHidden'] :
                    ( $addon['config']['armorysync_status_hide'] ? 'ON' : 'OFF' );
        $display = ( $status == 'ON' ) ? 'none' : '';
        $icon = ROSTER_PATH. ( $status == 'ON' ? 'img/plus.gif' : 'img/minus.gif' );
        $style = 'syellow';

        $roster->tpl->assign_vars(array(
                'IMAGE_PATH' => $addon['image_path'],

                'USE_EFFECTS' => $addon['config']['armorysync_pic_effects'],
                'SHOW_PIC_TABLE' => (   $addon['config']['armorysync_pic1_show'] ||
                                        $addon['config']['armorysync_pic2_show'] ||
                                        $addon['config']['armorysync_pic3_show'] ),

                'PIC1_SHOW' => ( $addon['config']['armorysync_pic1_show'] && $addon['config']['armorysync_pic1_min_rows'] <= $this->total ) ? true: false,
                'PIC1_LEFT' => $addon['config']['armorysync_pic1_pos_left'],
                'PIC1_TOP' => $addon['config']['armorysync_pic1_pos_top'],
                'PIC1_HIGHT' => $addon['config']['armorysync_pic1_size'],

                'PIC2_SHOW' => ( $addon['config']['armorysync_pic2_show'] && $addon['config']['armorysync_pic2_min_rows'] <= $this->total ) ? true: false,
                'PIC2_LEFT' => $addon['config']['armorysync_pic2_pos_left'],
                'PIC2_TOP' => $addon['config']['armorysync_pic2_pos_top'],
                'PIC2_HIGHT' => $addon['config']['armorysync_pic2_size'],

                'PIC3_SHOW' => ( $addon['config']['armorysync_pic3_show'] && $addon['config']['armorysync_pic3_min_rows'] <= $this->total ) ? true: false,
                'PIC3_LEFT' => $addon['config']['armorysync_pic3_pos_left'],
                'PIC3_TOP' => $addon['config']['armorysync_pic3_pos_top'],
                'PIC3_HIGHT' => $addon['config']['armorysync_pic3_size'],

                'LINK' => ( $this->link ? $this->link : makelink() ),
                'DEBUG' => $addon['config']['armorysync_xdebug_php'] ? "<input type=\"hidden\" name=\"XDEBUG_SESSION_START\" value=\"". $addon['config']['armorysync_xdebug_idekey']. "\" />" : "",
                'STATUSHIDDEN' => $status,
                'JOB_ID' => $this->jobid,
                'DISPLAY' => $display,
                'ICON' => $icon,
                'START_BORDER' => border( $style, 'start', '', '848px' ),
                'STYLE' => $style,
                'TITLE' => $this->title,
                'PROGRESSBAR' => $this->_getProgressBar($this->done, $this->total),
                )
                                 );

        if (isset($this->active_member['name']) || isset($this->active_member['guild_name'])) {
            $roster->tpl->assign_var( 'NEXT', $roster->locale->act['next_to_update']. ( $memberlist ? $this->active_member['guild_name'] : $this->active_member['name'] ) );
        } else {
            $roster->tpl->assign_var( 'NEXT', false );
        }

        if ( !$memberlist ) {
            $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => $roster->locale->act['name']));
        }
        $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => $roster->locale->act['guild']));
        $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => $roster->locale->act['realm']));
        $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => "Infos<br />". $roster->locale->act['guild_short']));

        if ( ! $memberlist ) {
            $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => "Infos<br />". $roster->locale->act['character_short']));
            $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => "Infos<br />". $roster->locale->act['skill_short']));
            $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => "Infos<br />". $roster->locale->act['reputation_short']));
            $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => "Infos<br />". $roster->locale->act['equipment_short']));
            $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => "Infos<br />". $roster->locale->act['talents_short']));
        }

        $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => $roster->locale->act['started']));
        $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => $roster->locale->act['finished']));
        $roster->tpl->assign_block_vars('head_col', array('HEAD_TITLE' => "Log" ));

        $l = 1;
        $roster->tpl->assign_var('CHARLIST', !$memberlist);
        foreach ( $members as $member ) {

            $array = array();
            $array['COLOR'] = $roster->switch_row_class();
            $array['ASID'] = $memberlist ? $member['guild_id'] : $member['member_id'];
            $array['NAME'] = $member['name'];
            $array['GUILD'] = $member['guild_name'];
            $array['SERVER'] = $member['region']. "-". $member['server'];

            foreach ( array( 'guild_info', 'character_info', 'skill_info', 'reputation_info', 'equipment_info', 'talent_info' ) as $key ) {
                if ( $memberlist && $key !== 'guild_info' ) {
                    continue;
                }
                if ( isset( $member[$key] ) && $member[$key] == 1 && ( $key == 'guild_info' || $key == 'character_info' ) ) {
                    $array[strtoupper($key)] = "<img src=\"". ROSTER_PATH. "img/pvp-win.gif\" alt=\"\"/>";
                } elseif ( isset( $member[$key] ) && $member[$key] == 0 ) {
                    $array[strtoupper($key)] = "<img src=\"". ROSTER_PATH. "img/pvp-loss.gif\" alt=\"\" />";
                } elseif ( isset( $member[$key] ) && ( $key != 'character_info' || ( $memberlist && $key == 'guild_info' ) || ( ! $memberlist && $key != 'guild_info' ) ) ) {
                    $array[strtoupper($key)] = $member[$key];
                } else {
                    $array[strtoupper($key)] = "<img src=\"". ROSTER_PATH. "img/blue-question-mark.gif\" alt=\"?\" />";
                }
            }

            $array['STARTTIMEUTC'] = isset( $member['starttimeutc'] ) ? $this->_getLocalisedTime($member['starttimeutc']) : "<img src=\"". ROSTER_PATH. "img/blue-question-mark.gif\" alt=\"?\"/>";
            $array['STOPTIMEUTC'] = isset( $member['stoptimeutc'] ) ? $this->_getLocalisedTime($member['stoptimeutc']) : "<img src=\"". ROSTER_PATH. "img/blue-question-mark.gif\" alt=\"?\"/>";

            if ( !$memberlist && $member['log'] ) {
                $array['LOG'] = "<img src=\"". ROSTER_PATH. "img/note.gif\"". makeOverlib( $member['log'] , $roster->locale->act['update_log'] , '' ,0 , '' , ',WRAP' ). " alt=\"\" />";
            } elseif( $member['log'] ) {
                $array['LOG'] = "<img src=\"". ROSTER_PATH. "img/note.gif\"". makeOverlib( "<div style=\"height:300px;width:500px;overflow:auto;\">". $member['log']. "</div>", $roster->locale->act['update_log'] , '' ,0 , '' , ',STICKY, WRAP, CLOSECLICK' ). " alt=\"\" />";
            } else {
                $array['LOG'] = "<img src=\"". ROSTER_PATH. "img/no_note.gif\" alt=\"\" />";
            }


            $roster->tpl->assign_block_vars('body_row', $array );
            $l++;
        }

        $roster->tpl->assign_var('STOP_BORDER', border( 'syellow', 'end' ));


        $roster->tpl->set_filenames(array(
                'status_head' => $addon['basename'] . '/status_head.html',
                'status_body' => $addon['basename'] . '/status_body.html',
                ));

        $roster->tpl->display('status_head');
        $roster->tpl->display('status_body');
        $this->_debug( 1, null, 'Printed status window', 'OK');
    }

    /**
     * create header
     *
     *
     */
    function _showHeader() {
        global $roster, $addon;

        $roster->tpl->assign_vars( array (
            'SHOW_LOGO' => $addon['config']['armorysync_logo_show'],
            'IMAGE_PATH' => $addon['image_path'],
            'LEFT' => $addon['config']['armorysync_logo_pos_left'],
            'TOP' => $addon['config']['armorysync_logo_pos_top'],
            'HIGHT' => $addon['config']['armorysync_logo_size'],
            ));
        $roster->tpl->set_filenames(array(
                'header' => $addon['basename'] . '/header.html',
                ));
        $roster->tpl->display('header');
        $this->_debug( 3, null, 'Printed header', 'OK');
    }

    /**
     * create footer
     *
     * @param int $jobid
     */
    function _showFooter() {
        global $roster, $addon;

        //aprint($this->debugmessages[0]['ret']);

        $roster->tpl->assign_vars( array (
            'IMAGE_PATH' => $addon['image_path'],
            'ARMORYSYNC_VERSION' => $addon['version']. ' by poetter',
            'ARMORYSYNC_CREDITS' => $roster->locale->act['armorysync_credits'],
            'ERROR' => count( $this->errormessages ) > 0,
            'DEBUG' => $addon['config']['armorysync_debuglevel'],
            'DEBUG_DATA' => $addon['config']['armorysync_debugdata'],
            'D_START_BORDER' => border( 'sblue', 'start', 'ArmorySync Debugging '. ( $addon['config']['armorysync_debugdata'] ? 'Infos & Data' : 'Infos'), '100%' ),
            'E_START_BORDER' => border( 'sred', 'start', 'ArmorySync Error '. ( $addon['config']['armorysync_debugdata'] ? 'Infos & Data' : 'Infos'), '100%' ),
            'RUNTIME' => round((format_microtime() - ARMORYSYNC_STARTTIME), 4),
            'S_SQL_WIN' => $addon['config']['armorysync_sqldebug'],
            ));

        $this->_debug( 3, null, 'Printed footer', 'OK');

		if ($roster->switch_row_class(false) != 1 ) {
			$roster->switch_row_class();
		}

        foreach ( $this->errormessages as $message ) {
            $roster->tpl->assign_block_vars('e_row', array(
                'FILE' => $message['file'],
                'LINE' => $message['line'],
                'TIME' => $message['time'],
                'CLASS' => $message['class'],
                'FUNC' => $message['function'],
                'INFO' => $message['info'],
                'STATUS' => $message['status'],
                'ARGS' => aprint($message['args'], '', 1),
                'RET'  => aprint($message['ret'], '' , 1),
                'ROW_CLASS1' => $addon['config']['armorysync_debugdata'] ? 1 : $roster->switch_row_class(),
                'ROW_CLASS2' => 1,
                'ROW_CLASS3' => 1,
                ));
        }

        $roster->tpl->assign_var( 'E_STOP_BORDER', border( 'sred', 'end', '', '' ) );

		if ($roster->switch_row_class(false) != 1 ) {
			$roster->switch_row_class();
		}

        foreach ( $this->debugmessages as $message ) {
            $roster->tpl->assign_block_vars('d_row', array(
                'FILE' => $message['file'],
                'LINE' => $message['line'],
                'TIME' => $message['time'],
                'CLASS' => $message['class'],
                'FUNC' => $message['function'],
                'INFO' => $message['info'],
                'STATUS' => $message['status'],
                'ARGS' => aprint($message['args'], '', 1),
                'RET'  => aprint($message['ret'], '' , 1),
                'ROW_CLASS1' => $addon['config']['armorysync_debugdata'] ? 1 : $roster->switch_row_class(),
                'ROW_CLASS2' => 1,
                'ROW_CLASS3' => 1,
                ));
        }

        $roster->tpl->assign_var( 'D_STOP_BORDER', border( 'sblue', 'end', '', '' ) );

        if( $addon['config']['armorysync_sqldebug'] )
        {
            if( count($roster->db->queries) > 0 )
            {
                foreach( $roster->db->queries as $file => $queries )
                {
                    if (!preg_match('#[\\\/]{1}addons[\\\/]{1}armorysync[\\\/]{1}inc[\\\/]{1}[a-z_.]+.php$#', $file)) {
                        continue;
                    }
                    $roster->tpl->assign_block_vars('sql_debug', array(
                        'FILE' => substr($file, strlen(ROSTER_BASE)),
                        )
                    );
                    foreach( $queries as $query )
                    {
                        $roster->tpl->assign_block_vars('sql_debug.row', array(
                            'ROW_CLASS' => $roster->switch_row_class(),
                            'LINE'      => $query['line'],
                            'TIME'      => $query['time'],
                            'QUERY'     => nl2br(htmlentities($query['query'])),
                            )
                        );
                    }
                }

                $roster->tpl->assign_vars(array(
                    'SQL_DEBUG_B_S' => border('sgreen','start',$roster->locale->act['sql_queries']),
                    'SQL_DEBUG_B_E' => border('sgreen','end'),
                    )
                );
            }
        }

        $roster->tpl->set_filenames( array (
                'footer' => $addon['basename'] . '/footer.html',
                ));
        $roster->tpl->display('footer');
    }

    /**
     * create footer
     *
     * @param int $jobid
     */
    function _showAddScreen() {
        global $roster, $addon;

        $body = '';
        $body .= '<form action="' . makelink() . '" method="post" id="allow">
        <input type="hidden" id="addguild" name="action" value="" />
        <input type="hidden" name="process" value="addguild" />
        <input type="hidden" name="block" value="allow" />';

        $body .= $this->_ruletableHead('sgreen',$roster->locale->act['armorysync_guildadd'],'addguild','', $roster->locale->act['guildname']);
        $body .= $this->_ruletableFoot('sgreen','addguild','');

        $body .= '</form>';

        $body .= "<br />\n";
        $body .= '<form action="' . makelink() . '" method="post" id="allow">
        <input type="hidden" id="addchar" name="action" value="" />
        <input type="hidden" name="process" value="addchar" />
        <input type="hidden" name="block" value="allow" />';

        $body .= $this->_ruletableHead('sgreen',$roster->locale->act['armorysync_charadd'],'addchar','', $roster->locale->act['charname']);
        $body .= $this->_ruletableFoot('sgreen','addchar','');

        $body .= '</form>';
        $body .= "<br />\n";
        $body .= "<br />\n";
        $body .= messagebox($roster->locale->act['armorysync_add_helpText'],'<img src="' . $roster->config['img_url'] . 'blue-question-mark.gif" alt="?" style="float:right;" />' . $roster->locale->act['armorysync_add_help'],'sgray', '500px');
        $body .= "<br />\n";
        $this->_debug( 1, $body, 'Printed add screen', 'OK');
        print $body;
    }

    /**
     * statusbox Memberlist output
     *
     * @param int $jobid
     */
    function _ruletableHead( $style , $title , $type , $mode, $name )
    {
        global $roster;

        $output = border($style,'start',$title) . '
<table class="bodyline" cellspacing="0" cellpadding="0">
        <thead>
                <tr>
';

        //$name = $roster->locale->act['guildname'];

        $output .= '
                        <th class="membersHeader" ' . makeOverlib($name) . '> ' . $name . '</th>
                        <th class="membersHeader" ' . makeOverlib($roster->locale->act['realmname']) . '> ' . $roster->locale->act['server'] . '</th>
                        <th class="membersHeader" ' . makeOverlib($roster->locale->act['regionname']) . '> ' . $roster->locale->act['region'] . '</th>
                        <th class="membersHeaderRight">&nbsp;</th>
                </tr>
        </thead>
        <tbody>' . "\n";
        $this->_debug( 3, $output, 'Fetched header of rule table', 'OK');
        return $output;
    }


    /**
     * statusbox Memberlist output
     *
     * @param int $jobid
     */
    function _ruletableFoot( $style , $type , $mode )
    {
        global $roster;

        $output = "\n\t\t<tr>\n";

        $output .= '
                        <td class="membersRow2"><input class="wowinput128" type="text" name="name" value="" /></td>
                        <td class="membersRow2"><input class="wowinput128" type="text" name="server" value="" /></td>
                        <td class="membersRow2"><input class="wowinput64" type="text" name="region" value="" /></td>
                        <td class="membersRowRight2"><button type="submit" class="input" onclick="setvalue(\'' . $type . '\',\'add\');">' . $roster->locale->act['add'] . '</button></td>
                </tr>
        </tbody>
</table>
' . border($style,'end');
        $this->_debug( 3, $output, 'Fetched footer of rule table', 'OK');
        return $output;
    }

    /**
     * statusbox Memberlist output
     *
     * @param int $jobid
     */
    function _showStatusMemberlist( $jobid = 0 ) {
        global $roster;

        $this->_showStatus( $jobid, 1 );
        $this->_debug( 1, null, 'Printed memberlist status', 'OK');
    }

    /**
     * this is the main logic of the syncjob.
     *
     *
     * @param int $jobid
     */
    function _updateStatus( $jobid = 0 ) {
        global $roster, $addon;

        $this->_init();
        $this->active_member = $this->_isPostSyncStatus( $this->jobid );
        $active_member = $this->active_member;

        if ( ! isset ($active_member['name']) ) {
            $this->active_member = $this->_getNextMemberToUpdate( $this->jobid );
            $active_member = $this->active_member;
            $cleanup = 0;
            if ( isset ($active_member['name']) ) {
                $this->active_member['starttimeutc'] = gmdate('Y-m-d H:i:s');
                if ( $this->_updateMemberJobStatus( $this->jobid, $this->active_member ) ) {
                    $ret = true;
                }
            } else {
                $cleanup = 1;
                $ret = false;
            }
            $this->members = $this->_getMembersFromJobqueue( $this->jobid );
            list ( $this->done, $this->total ) = $this->_getJobProgress($this->jobid);
            if ( $cleanup ) {
                $this->_cleanUpJob( $this->jobid );
            }
            $this->_debug( $ret ? 2 : 1, $ret, 'Updated charcter job status', $ret ? 'OK': 'FINISHED');
            return $ret;
        } else {
			if ( $addon['config']['armorysync_fetch_method'] == 0 ) {
				if ( ! $this->ArmorySync->synchMemberByIDPerPage( $active_member['server'], $active_member['member_id'], $active_member['name'], $active_member['region'], $active_member['guild_id']) ) {
					$this->dataNotAccepted = 1;
				}
			} elseif ( $addon['config']['armorysync_fetch_method'] == 1 ) {
				if ( ! $this->ArmorySync->synchMemberByID( $active_member['server'], $active_member['member_id'], $active_member['name'], $active_member['region'], $active_member['guild_id']) ) {
					$this->dataNotAccepted = 1;
				}
			} else {
				if ( ! $this->ArmorySync->synchMemberByIDSmartSteps( $active_member['server'], $active_member['member_id'], $active_member['name'], $active_member['region'], $active_member['guild_id']) ) {
					$this->dataNotAccepted = 1;
				}
			}

            $this->active_member['guild_info'] = isset($this->ArmorySync->status['guildInfo']) ? $this->ArmorySync->status['guildInfo'] : null;
            $this->active_member['character_info'] = isset($this->ArmorySync->status['characterInfo']) ? $this->ArmorySync->status['characterInfo'] : null;
            $this->active_member['skill_info'] = isset($this->ArmorySync->status['skillInfo']) ? $this->ArmorySync->status['skillInfo'] : null;
            $this->active_member['reputation_info'] = isset($this->ArmorySync->status['reputationInfo']) ? $this->ArmorySync->status['reputationInfo'] : null;
            $this->active_member['equipment_info'] = isset($this->ArmorySync->status['equipmentInfo']) ? $this->ArmorySync->status['equipmentInfo'] : null;
            $this->active_member['talent_info'] = isset($this->ArmorySync->status['talentInfo']) ? $this->ArmorySync->status['talentInfo'] : null;
			if ( $this->ArmorySync->updateDone == true ) {
				$this->active_member['stoptimeutc'] = gmdate('Y-m-d H:i:s');
			}
            $this->active_member['log'] = $this->ArmorySync->message;
            if ( $this->_updateMemberJobStatus( $this->jobid, $this->active_member ) ) {
                $this->members = $this->_getMembersFromJobqueue( $this->jobid );
                list ( $this->done, $this->total ) = $this->_getJobProgress($this->jobid);
                $this->_debug( 2, true, 'Updated charcter job status', 'OK');
                return true;
            } else {
                $this->_debug( 0, false, 'Updated charcter job status', 'Failed');
                return false;
            }
        }
    }

    function _updateStatusMemberlist( $jobid = 0 ) {
        global $roster;

        $this->_init();
        $this->active_member = $this->_isPostSyncStatus( $this->jobid );
        $active_member = $this->active_member;

        if ( ! isset ($active_member['guild_name']) ) {
            $this->active_member = $this->_getNextGuildToUpdate( $this->jobid );
            $active_member = $this->active_member;
            $cleanup = 0;
            if ( isset ($active_member['guild_name']) ) {
                $this->active_member['starttimeutc'] = gmdate('Y-m-d H:i:s');
                if ( $this->_updateGuildJobStatus( $this->jobid, $this->active_member ) ) {
                    $ret = true;
                }
            } else {
                $cleanup = 1;
                $ret = false;
            }
            $this->members = $this->_getMembersFromJobqueue( $this->jobid );
            list ( $this->done, $this->total ) = $this->_getJobProgress($this->jobid);
            if ( $cleanup ) {
                $this->_cleanUpJob( $this->jobid );
            }
            $this->_debug( 1, $ret, 'Updated memberlist job status', $ret ? 'OK': 'FINISHED');
            return $ret;
        } else {
            if ( ! $this->ArmorySync->synchGuildByID( $active_member['server'], $active_member['guild_id'], $active_member['guild_name'], $active_member['region']) ) {
                $this->dataNotAccepted = 1;
            }

            $this->active_member['guild_info'] = $this->ArmorySync->status['guildInfo'];
            $this->active_member['stoptimeutc'] = gmdate('Y-m-d H:i:s');
            $this->active_member['log'] = $this->ArmorySync->message;
            if ( $this->_updateGuildJobStatus( $this->jobid, $this->active_member ) ) {
                $this->members = $this->_getMembersFromJobqueue( $this->jobid );
                list ( $this->done, $this->total ) = $this->_getJobProgress($this->jobid);
                $this->_debug( 1, true, 'Updated memberlist job status', 'OK');
                return true;
            } else {
                $this->_debug( 0, false, 'Updated memberlist job status', 'Failed');
                return false;
            }
        }
    }

    // Helper functions

    /**
     * Check if guild exists in armory
     *
     * @param string $time
     * @return string
     */
    function _checkGuildExist( $name, $server, $region ) {
        global $addon;

        require_once ($addon['dir'] . 'inc/armorysync.class.php');

        $this->_init();
        $ret = $this->ArmorySync->checkGuildInfo( $name, $server, $region );
        $this->_debug( 1, $ret, 'Checked guild on existenz', $ret ? 'OK' : 'Failed');
        return $ret;
    }
    /**
     * Check if guild exists in armory
     *
     * @param string $time
     * @return string
     */
    function _checkCharExist( $name, $server, $region ) {
        global $addon;

        require_once ($addon['dir'] . 'inc/armorysync.class.php');

        $this->_init();
        $ret = $this->ArmorySync->checkCharInfo( $name, $server, $region );
        $this->_debug( 1, $ret, 'Checked char on existenz', $ret ? 'OK' : 'Failed');
        return $ret;
    }
    /**
     * Create localised time based on utc + offset;
     *
     * @param string $time
     * @return string
     */
    function _getLocalisedTime ( $time = false ) {
        global $roster;

        $offset = $roster->config['localtimeoffset'] * 60 * 60;
        $stamp = strtotime( $time );
        $stamp += $offset;
        $ret = date($roster->locale->act['phptimeformat'], $stamp);
        $this->_debug( $ret ? 3 : 0, $ret, 'Fetched localized time', $ret ? 'OK' : 'Failed');
        return $ret;
    }
    /**
     * Creates a progress bar
     *
     */
    function _getProgressBar($step, $total) {
        global $roster;

        $perc = 0;
        if ( $total == 0 ) {
            $perc = 100;
        } else {
            $perc = round ($step / $total * 100);
        }
        $per_left = 100 - $perc;
        $pb = "<table class=\"main_roster_menu\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\" align=\"center\" width=\"200\" id=\"Table1\">";
        $pb .= "<tr>";
        $pb .= "    <td id=\"progress_text\" class=\"header\" colspan=\"2\" align=\"center\">";
        $pb .= "        $perc% ". $roster->locale->act['complete']. " ($step / $total)";
        $pb .= "    </td>";
        $pb .= "</tr>";
        $pb .= "<tr id=\"progress_bar\">";
        if ( $perc ) {
            $pb .= "	<td bgcolor=\"#660000\" height=\"12\" width=\"$perc%\">" ;
            $pb .= "	</td>";
        }
        if ( $per_left ) {
            $pb .= "	<td bgcolor=\"#FFF7CE\" height=\"12\" width=\"$per_left%\">";
            $pb .= "        </td>";
        }
        $pb .= "</tr>";
        $pb .= "</table>";
        $this->_debug( 3, $pb, 'Fetched progressbar', $pb ? 'OK' : 'Failed');
        return $pb;
    }


    /**
     * scope based __link call
     *
     */
    function _checkAuth( $scope = false ) {
        global $roster, $addon;

        if ( !$scope ) {
            return false;
        }

        $roster_login = new RosterLogin();
        if( ! $roster_login->getAuthorized( $addon['config'][$scope] ) )
        {
            print
            '<span class="title_text">'. $this->title. '</span><br />'.
            $roster_login->getMessage().
            $roster_login->getLoginForm($addon['config'][$scope]);
            $ret = false;
        } else {
            $ret = true;
        }
        $this->_debug( 2, $ret, 'Checked authentication', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Create java reload code
     *
     * @param string $link
     */

    function _link ( $link = '' ) {
        global $roster, $addon;

        $reloadTime = $addon['config']['armorysync_reloadwaittime'] * 500;
        $link = ROSTER_URL. 'index.php?p=ajax-addon-armorysync-status_update&cont=doUpdateStatus';
		$posts = 'job_id='. $this->jobid. '&memberlist='. $this->isMemberList. '&scope='. $roster->scope. '&page='. ( isset($roster->pages[2]) ? $roster->pages[2] : '' ). '&ARMORYSYNC_STARTTIME='. ARMORYSYNC_STARTTIME;

		$postadd = '';

		if ( $this->util_type ) {
			$postadd .= "&util_type=".$this->util_type;
		}

		if ( $addon['config']['armorysync_use_ajax'] ) {
            if ( $addon['config']['armorysync_xdebug_ajax'] ) {
                $postadd .= '&XDEBUG_SESSION_START='. $addon['config']['armorysync_xdebug_idekey'];
            }
            $header = '
<script type="text/javascript">
<!--
    function nextStep() {
        loadXMLDoc(\''. $link. '\',\''. $posts. $postadd. '\');
    }
    self.setTimeout(\'nextStep()\', '. $reloadTime. ');
//-->
    </script>
';
//XDEBUG_SESSION_START=test

        } else {

            $header = '
<script type="text/javascript">
<!--
    function nextMember() {
        document.linker.submit();
    }
    self.setTimeout(\'nextMember()\', '. $reloadTime. ');
//-->
</script>
';
        }
        $this->_debug( 2, htmlspecialchars($header), 'Printed reload java code', $header ? 'OK' : 'Failed');
        $this->header .= $header;
    }

    /**
     * Create java reload code
     *
     * @param string $link
     */

    function _showStartPage () {
        global $roster, $addon;

        $message = '<br />';
        if ( $roster->scope == 'char' ) {
            $message .= sprintf( $roster->locale->act['start_message'], $roster->locale->act['start_message_the_char'], $roster->data['name'], $roster->locale->act['start_message_this_char']);
        } elseif ( $roster->scope == 'guild' ) {
            $message .= sprintf( $roster->locale->act['start_message'], $roster->locale->act['start_message_the_guild'], $roster->data['guild_name'], $roster->locale->act['start_message_this_guild']);
        } elseif ( $roster->scope == 'realm' ) {
            $message .= sprintf( $roster->locale->act['start_message'], $roster->locale->act['start_message_the_realm'], $roster->data['region'].'-'.$roster->data['server'], $roster->locale->act['start_message_this_realm']);
        }

        $message .= '<img src="' . $roster->config['img_url'] . 'blue-question-mark.gif" alt="?" />
                    <br /><br />
                    <form action="' . makelink() . '" method="post" id="allow">
                    <input type="hidden" id="start" name="action" value="" />
                    <input type="hidden" name="job_id" value="" />
                    <button type="submit" class="input" onclick="setvalue(\'job_id\',\'0\');setvalue(\'start\',\'start\');">' . $roster->locale->act['start'] . '</button>
                    </form>
                    <br />';

        $out = messagebox( $message, $this->title,'sred', '500px');
        $this->_debug( 1, $out, 'Printed start page', $out ? 'OK' : 'Failed');
        print $out;
    }

    // DB functions

    /**
     * Get realm members that match prerequesists from db for update
     *
     * @return array ()
     */
    function _getRealmMembersToUpdate(){
        global $roster;

        $ret = $this->_getMembersToUpdate("members.server = \"". $roster->data['server']. "\" AND members.region = \"". $roster->data['region']. "\" AND NOT members.guild_id = 0 AND " );
        $this->_debug( 3, $ret, 'Fetched realm members to update from DB', $ret ? 'OK' : 'EMPTY');
        return $ret;
    }

    /**
     * Get guild members that match prerequesists from db for update
     *
     * @return array ()
     */
    function _getGuildMembersToUpdate(){
        global $roster;

        $ret = $this->_getMembersToUpdate("members.guild_id = ". $roster->data['guild_id']. " AND " );
        $this->_debug( 3, $ret, 'Fetched guild members to update from DB', $ret ? 'OK' : 'EMPTY');
        return $ret;
    }

    /**
     * Get that match prerequesists from db for update
     *
     * @return array ()
     */
    function _getMembersToUpdate( $where = false ){
        global $roster, $addon;

        $query =    "SELECT members.member_id, members.name, " .
                    "guild.guild_id, guild.guild_name, guild.server, guild.region ".
                    "FROM `".$roster->db->table('members')."` members ".
                    "LEFT JOIN `".$roster->db->table('guild')."` guild " .
                    "ON members.guild_id = guild.guild_id " .
                    "LEFT JOIN `". $roster->db->table('updates',$addon['basename']). "` updates ".
                    "ON members.member_id = updates.member_id ".
                    "WHERE ". $where.
                    "members.level >= " . $addon['config']['armorysync_minlevel'] . " " .
                    "AND ( ".
                    "   ISNULL(updates.dateupdatedutc) ".
                    "   OR ".
                    "   updates.dateupdatedutc <= DATE_SUB(UTC_TIMESTAMP(), INTERVAL " . $addon['config']['armorysync_synchcutofftime'] . " DAY) ".
                    " ) ".
                    "ORDER BY members.member_id;";
                    //"ORDER BY members.member_id ".
                    //"LIMIT 5;";



        $result = $roster->db->query($query);
        if( $roster->db->num_rows($result) > 0 ) {
            $ret = $roster->db->fetch_all();
        } else {
            $ret = array();
        }
        $this->_debug( 2, $ret, 'Fetched members to update from DB', $ret ? 'OK' : 'EMPTY');
        return $ret;
    }

    /**
     * Get guild members that match prerequesists from db for update
     *
     * @param string $starttimeutc
     * @return int $jobid
     */
    function _insertJobID( $starttimeutc = '' ) {
        global $roster, $addon;

        $query =    "INSERT INTO ". $roster->db->table('jobs',$addon['basename']). " ".
                    "SET starttimeutc=".'"'. $starttimeutc. '"'.";";

        $result = $roster->db->query($query);
        $ret = false;
        if ( $result ) {
            $query = "SELECT LAST_INSERT_ID();";
            $jobid = $roster->db->query_first($query);
            if ( $jobid ) {
                $ret = $jobid;
            }
        }
        $this->_debug( $ret ? 2 : 0, $ret, 'Fetched job id from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Get job starttime from db
     *
     * @param int $jobid
     * @return string $starttime
     */
    function _getJobStartTime( $jobid = 0 ) {
        global $roster, $addon;

        $query =    "SELECT starttimeutc ".
                    "FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid;
        $ret = $roster->db->query_first($query);
        $this->_debug( $ret ? 2 : 0, $ret, 'Fetched job start time from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Inserts members to jobqueue
     *
     * @param int $jobid
     * @param array $members
     * @return bool
     */
    function _insertMembersToJobqueue( $jobid = 0, $members = array() ) {
        global $roster, $addon;

        $ret = false;
        if ( array_keys( $members ) ) {

            $query =    "INSERT INTO ". $roster->db->table('jobqueue',$addon['basename']). " ".
                        "VALUES ";
            foreach ( $members as $member ) {
                $query .= "(".
                            $jobid. ", ".
                            ( $member['member_id'] ? $member['member_id'] : 0 ). ", ".
                            '"'.$roster->db->escape($member['name']). '"'.", ".
                            ( $member['guild_id'] ? $member['guild_id'] : 0 ). ", ".
                            '"'.$roster->db->escape($member['guild_name']). '"'.", ".
                            '"'.$roster->db->escape($member['server']). '"'.", ".
                            '"'.$roster->db->escape($member['region']). '"'.", ".
                            "NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL), ";
            }
            $query = preg_replace('/, $/', ';', $query);
            $result = $roster->db->query($query);
            if ( $result ) {
                $ret = true;
            }
        }
        $this->_debug( $ret ? 2 : 0, $ret, 'Inserted members to jobqueue table', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Fetches members from jobqueue
     *
     * @param int $jobid
     * @return array $members
     */
    function _getMembersFromJobqueue( $jobid = 0 ) {
        global $roster, $addon;

        $ret = array();
        $query =    "SELECT * ".
                    "FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid. " ".
                    "ORDER BY member_id;";


        $result = $roster->db->query($query);
        if( $roster->db->num_rows($result) > 0 ) {
            $ret = $roster->db->fetch_all();
        }
        $this->_debug( $ret ? 2 : 0, $ret, 'Fetched members in jobqueue table from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Fetches member which status was updated last
     *
     * @param int $jobid
     * @return array $member
     */
    function _isPostSyncStatus ( $jobid = 0 ) {
        global $roster, $addon;

        $ret = false;
        $query =    "SELECT * ".
                    "FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid. " ".
                    "AND NOT ISNULL(starttimeutc) AND ISNULL(stoptimeutc);";

        $result = $roster->db->query($query);
        if( $roster->db->num_rows($result) > 0 ) {
            $member = $roster->db->fetch_all();
            $ret = $member[0];
        }
        $this->_debug( 2, $ret, 'Check if post sync status from DB', $ret ? 'YES' : 'NO');
        return $ret;
    }

    /**
     * Fetches job progress
     *
     * @param int $jobid
     * @return array $progress
     */
    function _getJobProgress ( $jobid = 0 ) {
        $ret = array($this->_getJobDone($jobid), $this->_getJobTotal($jobid));
        $this->_debug( 3, $ret, 'Created job progress array', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Fetches total number of members to sync
     *
     * @param int $jobid
     * @return array $progress
     */
    function _getJobTotal ( $jobid = 0 ) {
        global $roster, $addon;

        $ret = 0;
        $query =    "SELECT ".
                    "COUNT(member_id) as total ".
                    "FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid. ";";

        $result = $roster->db->query_first($query);
        if( $result ) {
            $ret = $result;
        }
        $this->_debug( $ret ? 3 : 0, $ret, 'Fetched total members to update from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Fetches total number of members to sync
     *
     * @param int $jobid
     * @return array $progress
     */
    function _getJobDone ( $jobid = 0 ) {
        global $roster, $addon;

        $ret = 0;
        $query =    "SELECT ".
                    "COUNT(member_id) as done ".
                    "FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid. " ".
                    "AND NOT ISNULL(stoptimeutc);";


        $result = $roster->db->query_first($query);
        if( $result ) {
            $ret = $result;
        }
        $this->_debug( $ret !== false ? 3 : 0, $ret, 'Fetched total members updated from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Fetches member which status will be updated next
     *
     * @param int $jobid
     * @return array $member
     */
    function _getNextMemberToUpdate ( $jobid = 0 ) {
        $ret = $this->_getNextToUpdate( $jobid, 'member_id' );
        $this->_debug( 3, $ret, 'Fetched next member to update from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Fetches guild which status will be updated next
     *
     * @param int $jobid
     * @return array $member
     */
    function _getNextGuildToUpdate ( $jobid = 0 ) {
        $ret = $this->_getNextToUpdate( $jobid, 'guild_id' );
        $this->_debug( 3, $ret, 'Fetched next guild to update from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Fetches next whatever which status will be updated next
     *
     * @param int $jobid
     * @param string $field
     * @return array $member
     */
    function _getNextToUpdate ( $jobid = 0, $field = false ) {
        global $roster, $addon;

        if ( $field == false ) {
            return false;
        }

        $ret = array();
        $query =    "SELECT MIN(". $field. ") ". $field. " ".
                    "FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid. " ".
                    "AND ISNULL(starttimeutc) AND ISNULL(stoptimeutc);";
        $id = $roster->db->query_first($query);
        if ( $id ) {

            $query =    "SELECT * ".
                        "FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                        "WHERE job_id=". $jobid. " ".
                        "AND ". $field. "=". $id. ";";
            $result = $roster->db->query($query);
            if( $roster->db->num_rows($result) > 0 ) {
                $next = $roster->db->fetch_all();
                $ret = $next[0];
            }
        }
        $this->_debug( 3, $ret, 'Fetched next to update from DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Updates Members job status in jobqueue
     *
     * @param int $jobid
     * @param array $member
     * @return bool
     */
    function _updateMemberJobStatus ( $jobid = 0, $member = array() ) {
        $ret = $this->_updateJobStatus( $jobid, $member, 'member_id' );
        $this->_debug( 3, $ret, 'Updated character job status in DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Updates Guilds job status in jobqueue
     *
     * @param int $jobid
     * @param array $member
     * @return bool
     */
    function _updateGuildJobStatus ( $jobid = 0, $member = array() ) {
        $ret = $this->_updateJobStatus( $jobid, $member, 'guild_id' );
        $this->_debug( 3, $ret, 'Updated memberlist job status in DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Updates job status in jobqueue
     *
     * @param int $jobid
     * @param array $member
     * @return bool
     */
    function _updateJobStatus ( $jobid = 0, $member = array(), $field = false ) {
        global $roster, $addon;

        if ( $field == false ) {
            return false;
        }

        $query =    "UPDATE `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "SET ";

        $set = '';
        isset ( $member['guild_info'] ) ? $set .= "guild_info=". '"'.$roster->db->escape($member['guild_info']). '"'. ", " : 1;
        isset ( $member['character_info'] ) ? $set .= "character_info=". $member['character_info']. ", " : 1;
        isset ( $member['skill_info'] ) ? $set .= "skill_info=". $member['skill_info']. ", " : 1;
        isset ( $member['reputation_info'] ) ? $set .= "reputation_info=". $member['reputation_info']. ", " : 1;
        isset ( $member['equipment_info'] ) ? $set .= "equipment_info=". '"'. $member['equipment_info']. '"'. ", " : 1;
        isset ( $member['talent_info'] ) ? $set .= "talent_info=". $member['talent_info']. ", " : 1;

        isset ( $member['starttimeutc'] ) ? $set .= "starttimeutc=".'"'. $roster->db->escape($member['starttimeutc']). '"'.", " : 1;
        isset ( $member['stoptimeutc'] ) ? $set .= "stoptimeutc=".'"'. $roster->db->escape($member['stoptimeutc']). '"'.", " : 1;
        isset ( $member['log'] ) ? $set .= "log=".'"'. $roster->db->escape($member['log']). '"'.", " : 1;
        $set = preg_replace( '/, $/', ' ', $set );
        $query .= $set;

        $query .=   "WHERE job_id=". $jobid. " ".
                    "AND ". $field. "=". $member[$field]. ";";

        $result = $roster->db->query($query);
        if ( $result ) {
            if ( ! $this->dataNotAccepted && isset ( $member['stoptimeutc'] ) && $field == 'member_id' && isset ( $member['character_info'] ) ) {
                $query =    "INSERT INTO `". $roster->db->table('updates',$addon['basename']). "` ".
                            "SET ".
                            "member_id=". $member[$field].", ".
                            "dateupdatedutc='". $roster->db->escape(gmdate('Y-m-d H:i:s')). "' ".
                            "ON DUPLICATE KEY UPDATE ".
                            "dateupdatedutc='". $roster->db->escape(gmdate('Y-m-d H:i:s')). "';";
                if ( !$roster->db->query($query) ) {
                    die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
                }
            }
            $ret = true;
        } else {
            $ret = false;
        }
        $this->_debug( 2, $ret, 'Updated job status in DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Deletes job from jobqueue
     *
     * @param int $jobid
     */
    function _cleanUpJob ( $jobid = 0 ) {
        global $roster, $addon;

        $query =    "DELETE FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid. ";";
        $result = $roster->db->query($query);

        $query =    "DELETE FROM `". $roster->db->table('jobs',$addon['basename']). "` ".
                    "WHERE job_id=". $jobid. ";";
        $result = $roster->db->query($query);

        $query =    "SELECT job_id ".
                    "FROM `". $roster->db->table('jobs',$addon['basename']). "` ".
                    "WHERE starttimeutc <= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 3 HOUR);";
        $result = $roster->db->query($query);
        if( $roster->db->num_rows($result) > 0 ) {
            $array = $roster->db->fetch_all();
            foreach ( $array as $job ) {
                $job_id = $job['job_id'];
                $query =    "DELETE FROM `". $roster->db->table('jobqueue',$addon['basename']). "` ".
                            "WHERE job_id=". $job_id. ";";
                $result = $roster->db->query($query);

                $query =    "DELETE FROM `". $roster->db->table('jobs',$addon['basename']). "` ".
                            "WHERE job_id=". $job_id. ";";
                $result = $roster->db->query($query);
            }
        }
        $this->_debug( 2, true, 'Deleted old jobs from DB', true ? 'OK' : 'Failed');
    }

    /**
     * Inserts UploadRule
     *
     * @param string $name
     * @param string $server
     * @param string $region
     */
    function _insertUploadRule( $name, $server, $region, $type ) {
        global $roster;

        $query =    "SELECT ".
                    "rule_id ".
                    "FROM `". $roster->db->table('upload'). "` ".
                    "WHERE ".
                    "name='". $roster->db->escape($name). "' ".
                    "AND server='". $roster->db->escape($server). "' ".
                    "AND region='". strtoupper($region). "';";
        $id = $roster->db->query_first($query);

        if ( ! $id ) {
            $query =    "INSERT ".
                        "INTO `". $roster->db->table('upload'). "` ".
                        "(`name`,`server`,`region`,`type`,`default`) VALUES ".
                        "('" . $roster->db->escape($name) . "','" . $roster->db->escape($server) . "','" . strtoupper($region) . "','". $type. "','0');";

            if ( !$roster->db->query($query) ) {
                    die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
            } else {
                $ret = true;
            }
        } else {
            $ret = true;
        }
        $this->_debug( 2, $ret, 'Inserted upload rule to DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Inserts new guild
     *
     * @param string $name
     * @param string $server
     * @param string $region
     */
    function _insertGuild( $name, $server, $region ) {
        global $roster;

        $query =    "SELECT ".
                    "guild_id ".
                    "FROM `". $roster->db->table('guild'). "` ".
                    "WHERE ".
                    "`guild_name`='". $roster->db->escape($name). "' ".
                    "AND `server`='". $roster->db->escape($server). "' ".
                    "AND `region`='". $roster->db->escape($region). "';";
        $ret = $roster->db->query_first($query);

        if ( ! $ret ) {

            $query =    "INSERT ".
                        "INTO `". $roster->db->table('guild'). "` ".
                        "SET ".
                        "`guild_name`='". $roster->db->escape($name). "', ".
                        "`server`='". $roster->db->escape($server). "', ".
                        "`region`='". $roster->db->escape($region). "';";

            if ( !$roster->db->query($query) ) {
                die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
            } else {
                $query = "SELECT LAST_INSERT_ID();";
                $ret = $roster->db->query_first($query);
            }
        }
        $this->_debug( 2, $ret, 'Inserted guild to DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }

    /**
     * Inserts new char
     *
     * @param string $name
     * @param string $server
     * @param string $region
     */
    function _insertChar( $name, $server, $region ) {
        global $roster;

        $query =    "SELECT ".
                    "member_id ".
                    "FROM `". $roster->db->table('members'). "` ".
                    "WHERE ".
                    "`name`='". $roster->db->escape($name). "' ".
                    "AND `server`='". $roster->db->escape($server). "' ".
                    "AND `region`='". $roster->db->escape($region). "';";
        $ret = $roster->db->query_first($query);

        if ( ! $ret ) {

            $query =    "INSERT ".
                        "INTO `". $roster->db->table('members'). "` ".
                        "SET ".
                        "`name`='". $roster->db->escape($name). "', ".
                        "`server`='". $roster->db->escape($server). "', ".
                        "`region`='". $roster->db->escape($region). "';";

            if ( !$roster->db->query($query) ) {
                die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
            } else {
                $query = "SELECT LAST_INSERT_ID();";
                $ret = $roster->db->query_first($query);
            }
        }
        $this->_debug( 2, $ret, 'Inserted char to DB', $ret ? 'OK' : 'Failed');
        return $ret;
    }
}
