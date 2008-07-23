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
 * @version    SVN: $Id: armorysync.class.php 373 2008-02-24 13:55:12Z poetter $
 * @link       http://www.wowroster.net
 * @package    ArmorySync
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

require_once ($addon['dir'] . 'inc/armorysyncbase.class.php');
require_once(ROSTER_LIB . 'simpleparser.class.php');

class ArmorySync extends ArmorySyncBase {

    var $memberName = '';
    var $memberId = 0;
    var $guildId = 0;
    var $server = '';
    var $region = '';

    var $content = array();
    var $data = array();
	var $gemList = array();
	var $compareGem = array();

    var $message;
    var $debuglevel = 0;
    var $debugmessages = array();
    var $errormessages = array();

	var $ppUpdate = array();
	var $ppProgress = false;

	var $simpleParser;

	var $retrys = 2;

	var $updateDone = false;
	var $dataIncomplete = false;

    var $datas = array();

    var $status = array(    'guildInfo' => null,
                            'characterInfo' => null,
                            'skillInfo' => null,
                            'reputationInfo' => null,
                            'equipmentInfo' => null,
                            'talentInfo' => null,
                        );

    /**
     * syncronises one member with blizzards armory
     *
     * @param string $server
     * @param int $memberId
     * @return bool
     */
    function ArmorySync() {
		global $addon;
		$this->ppUpdate = array(
				'jobs' => array(
					array( 'type' => 'charInfo', 'retry' => 0 ),
					array( 'type' => 'skillInfo', 'retry' => 0 ),
					array( 'type' => 'repInfo', 'retry' => 0 ),
					array( 'type' => 'equipInfo', 'retry' => 0, 'subjobs' => array() ),
					array( 'type' => 'talentInfo', 'retry' => 0 ),
					array( 'type' => 'update', 'retry' => 0 ),
				),
				'data' => array(),
				'status' => array(),
		);
		$this->retrys = $addon['config']['armorysync_fetch_retrys'];
	}

    /**
     * syncronises one member with blizzards armory
     *
     * @param string $server
     * @param int $memberId
     * @return bool
     */
    function synchMemberByIDSmartSteps( $server, $memberId = 0, $memberName = false, $region = false, $guildId = 0 ) {
        global $addon, $roster;

        $this->server = $server;
        $this->memberId = $memberId;
        $this->memberName = $memberName;
        $this->region = $region;
        $this->guildId = $guildId;

		require_once(ROSTER_LIB . 'cache.php');
		$cache = new RosterCache;
		$cache->cache_dir = $addon['dir']. 'cache'. DIR_SEP;
		$cache->object_ttl = 600;

		$cacheTag = 'job_'.$server.$memberId.$region.$guildId;

		if ( $cache->check($cacheTag) ) {
			$this->ppUpdate = $cache->get($cacheTag);
			$this->data = $this->ppUpdate['data'];
			$this->status = $this->ppUpdate['status'];
			unset($this->ppUpdate['data']);
		}

		$maxExTime = get_cfg_var('max_execution_time');
		if ( $maxExTime < 10 ) {
			$this->_debug( 0, false, "Your max_execution_time of ". $maxExTime . "secs is very low. Try per page update!", "Aborting");
			$this->status = array(
								'guildInfo' => 0,
								'characterInfo' => 0,
								'skillInfo' => 0,
								'reputationInfo' => 0,
								'equipmentInfo' => 0,
								'talentInfo' => 0,
							);
			$this->updateDone = true;
			return false;
		}

		$breakTime = $maxExTime - $addon['config']['armorysync_fetch_timeout'] - 1;
		if ( $breakTime <= $addon['config']['armorysync_fetch_timeout'] ) {
			$this->_debug( 0, false, "Your fetch timeout of ". $addon['config']['armorysync_fetch_timeout'] . "secs is very high. Try lowering it!", "Aborting");
			$this->status = array(
								'guildInfo' => 0,
								'characterInfo' => 0,
								'skillInfo' => 0,
								'reputationInfo' => 0,
								'equipmentInfo' => 0,
								'talentInfo' => 0,
							);
			$this->updateDone = true;
			return false;
		}
		$totalTime = round(format_microtime() - ROSTER_STARTTIME, 2);

		while ( $totalTime < $breakTime ) {

			$step = $this->ppUpdate['jobs'][0];

			switch ($step['type']) {
				case 'charInfo':
					$this->_ppCharInfo();
					if ( $this->updateDone ) {
						$cache->cleanCache( 'obj_'. md5( $cacheTag));
						return false;
					}
					break;
				case 'skillInfo':
					$this->_ppSkillInfo();
					break;
				case 'repInfo':
					$this->_ppRepInfo();
					break;
				case 'equipInfo';
					$this->_ppEquipInfo();
					break;
				case 'talentInfo':
					$this->_ppTalentInfo();
					break;
				case 'update':
					$ret = $this->_ppUpdate();
					$cache->cleanCache( 'obj_'. md5( $cacheTag));
					return $ret;
					break;
			}
			if ( !$addon['config']['armorysync_update_incomplete'] && $this->updateDone ) {
				$this->_debug( 0, false, "Char: ". $this->memberName. " has incomplete data", "Aborting");
				$cache->cleanCache( 'obj_'. md5( $cacheTag));
				return false;
			}
			$totalTime = round(format_microtime() - ROSTER_STARTTIME, 2);
		}

		$this->_debug( 1, false, "Char: ". $this->memberName. " - Getting close to max_execution_time", "Reloading");

		if ( ! $this->updateDone ) {
			$this->ppUpdate['data'] = $this->data;
			$this->ppUpdate['status'] = $this->status;
			$cache->put($this->ppUpdate, $cacheTag);
		} else {
			$cache->cleanCache( 'obj_'. md5( $cacheTag));
		}
	}

    /**
     * syncronises one member with blizzards armory
     *
     * @param string $server
     * @param int $memberId
     * @return bool
     */
    function synchMemberByIDPerPage( $server, $memberId = 0, $memberName = false, $region = false, $guildId = 0 ) {
        global $addon, $roster;

        $this->server = $server;
        $this->memberId = $memberId;
        $this->memberName = $memberName;
        $this->region = $region;
        $this->guildId = $guildId;

		require_once(ROSTER_LIB . 'cache.php');
		$cache = new RosterCache;
		$cache->cache_dir = $addon['dir']. 'cache'. DIR_SEP;
		$cache->object_ttl = 600;

		$cacheTag = 'job_'.$server.$memberId.$region.$guildId;

		if ( $cache->check($cacheTag) ) {
			$this->ppUpdate = $cache->get($cacheTag);
			$this->data = $this->ppUpdate['data'];
			$this->status = $this->ppUpdate['status'];
			unset($this->ppUpdate['data']);
		}

		$step = $this->ppUpdate['jobs'][0];

		switch ($step['type']) {
			case 'charInfo':
				$this->_ppCharInfo();
				break;
			case 'skillInfo':
				$this->_ppSkillInfo();
				break;
			case 'repInfo':
				$this->_ppRepInfo();
				break;
			case 'equipInfo';
				$this->_ppEquipInfo();
				break;
			case 'talentInfo':
				$this->_ppTalentInfo();
				break;
			case 'update':
				$ret = $this->_ppUpdate();
				$cache->cleanCache( 'obj_'. md5( $cacheTag));
				return $ret;
				break;
		}

		if ( ! $this->updateDone ) {
			$this->ppUpdate['data'] = $this->data;
			$this->ppUpdate['status'] = $this->status;
			$cache->put($this->ppUpdate, $cacheTag);
		} else {
			$cache->cleanCache( 'obj_'. md5( $cacheTag));
		}
	}

    /**
     * char info part of per page update
     *
     */
	function _ppCharInfo() {
		$ret = $this->_getCharacterInfo();

		if ( $ret ) {
			foreach ( array_keys($this->data["Equipment"]) as $slot ) {
				$this->ppUpdate['jobs'][3]['subjobs'][] = array( 'type' => 'itemInfo', 'slot' => $slot, 'retry' => 0);
			}
			array_shift($this->ppUpdate['jobs']);
		} elseif ( $this->ppUpdate['jobs'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: CharInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->ppUpdate['jobs'][0]['retry']++;
		} else {
			$tmp = array_pop($this->ppUpdate['jobs']);
			$this->ppUpdate['jobs'] = array($tmp);
			$this->status = array(
								'guildInfo' => 0,
								'characterInfo' => 0,
								'skillInfo' => 0,
								'reputationInfo' => 0,
								'equipmentInfo' => 0,
								'talentInfo' => 0,
							);
			$this->updateDone = true;
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: CharInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: CharInfo failed", "I give up");
		}
	}

    /**
     * skill info part of per page update
     *
     */
	function _ppSkillInfo() {
		global $addon;
		$ret = $this->_getSkillInfo();
		if ( $ret ) {
			array_shift($this->ppUpdate['jobs']);
		} elseif ( $this->ppUpdate['jobs'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: SkillInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->ppUpdate['jobs'][0]['retry']++;
		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['skillInfo'] = 0;
				$this->status['reputationInfo'] = 0;
				$this->status['equipmentInfo'] = 0;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: SkillInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: SkillInfo failed", "I give up");
		}
	}

    /**
     * reputation info part of per page update
     *
     */
	function _ppRepInfo() {
		global $addon;
		$ret = $this->_getReputationInfo();
		if ( $ret ) {
			array_shift($this->ppUpdate['jobs']);
		} elseif ( $this->ppUpdate['jobs'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: RepInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->ppUpdate['jobs'][0]['retry']++;
		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['reputationInfo'] = 0;
				$this->status['equipmentInfo'] = 0;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: RepInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: RepInfo failed", "I give up");
		}
	}

    /**
     * char info part of per page update
     *
     */
	function _ppEquipInfo() {
		$subStep = $this->ppUpdate['jobs'][0]['subjobs'][0];

		switch ($subStep['type']) {
			case 'itemInfo':
				$this->_ppItemInfo($subStep);
				break;
			case 'itemTooltip':
				$this->_ppItemTooltip($subStep);
				break;
			case 'gemCheckCache':
				$this->_ppGemCheckCache($subStep);
				break;
			case 'gemSearch':
				$this->_ppGemSearch($subStep);
				break;
		}

		if ( count(array_keys($this->ppUpdate['jobs'][0]['subjobs'])) == 0 ) {
			array_shift($this->ppUpdate['jobs']);
		}
	}

    /**
     * char info part of per page update
     *
     */
	function _ppItemInfo( $subStep = false ) {
		global $addon;
		$ret = $this->_getEquipmentInfo($subStep['slot']);

		if ( $ret ) {
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
			array_unshift($this->ppUpdate['jobs'][0]['subjobs'], array( 'type' => 'itemTooltip', 'slot' => $subStep['slot'], 'retry' => 0 ) );
		} elseif ( $this->ppUpdate['jobs'][0]['subjobs'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " - Failed", "Retry: ". $this->ppUpdate['jobs'][0]['subjobs'][0]['retry']);
			$this->ppUpdate['jobs'][0]['subjobs'][0]['retry']++;
		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
			unset($this->data['Equipment'][$subStep['slot']]);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " - Failed", "Retry: ". $this->ppUpdate['jobs'][0]['subjobs'][0]['retry']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " - Failed", "I give up");
		}
	}

    /**
     * char info part of per page update
     *
     */
	function _ppItemTooltip( $subStep = false ) {
		global $addon;
		$ret = $this->_getEquipmentTooltip($subStep['slot']);

		if ( $ret ) {
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
			if ( isset($this->data['Equipment'][$subStep['slot']]['Gem']) ) {
				foreach ( array_reverse(array_keys($this->data['Equipment'][$subStep['slot']]['Gem'])) as $gemSlot ) {
					array_unshift($this->ppUpdate['jobs'][0]['subjobs'], array( 'type' => 'gemCheckCache', 'slot' => $subStep['slot'], 'gemSlot' => $gemSlot, 'retry' => 0 ));
				}

			}
		} elseif ( $this->ppUpdate['jobs'][0]['subjobs'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " - Failed", "Retry: ". $this->ppUpdate['jobs'][0]['subjobs'][0]['retry']);
			$this->ppUpdate['jobs'][0]['subjobs'][0]['retry']++;
		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
			unset($this->data['Equipment'][$subStep['slot']]);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " - Failed", "Retry: ". $this->ppUpdate['jobs'][0]['subjobs'][0]['retry']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " - Failed", "I give up");
		}
	}

    /**
     * gem search part of per page update
     *
     */
	function _ppGemSearch( $subStep = false ) {

		if ( count(array_keys($subStep['gemList'])) ) {
			$this->_ppGemSearchArmory($subStep);
		} elseif ( count(array_keys($subStep['compareGems'])) ) {
			$this->_ppGemFetchAndCompare($subStep);
		} elseif ( count(array_keys($subStep['gemTooltip']))) {
			$this->_ppGemFetchTooltip($subStep);
		} else {
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
		}

	}

    /**
     * gem search part of per page update
     *
     */
	function _ppGemFetchTooltip( $subStep = false ) {
		global $addon;
		$gem = $this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']];
		$id =  substr( array_shift(explode(':', $gem['Item'])), 2 );

		$gemTooltip = str_replace("\n", "<br>", $this->_getItemTooltip($id));

		if ( $gemTooltip ) {
			$this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']]['Tooltip'] = $gemTooltip;
			$this->_ppGemToCache($this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']]);
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
		} elseif( $this->ppUpdate['jobs'][0]['subjobs'][0]['gemTooltip'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " GemTooltip: ". $gem['Name']. " - Failed", "Retry: ". $this->ppUpdate['jobs'][0]['subjobs'][0]['gemTooltip'][0]['retry'] );
			$this->ppUpdate['jobs'][0]['subjobs'][0]['gemTooltip'][0]['retry']++;
		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
			unset($this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']]);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " GemTooltip: ". $gem['Name']. " - Failed", "I give up" );
		}
	}

    /**
     * gem search part of per page update
     *
     */
	function _ppGemFetchAndCompare( $subStep = false ) {
		global $addon;
		$compareGem = $subStep['compareGems'][0]['object'];
		$slot = $subStep['slot'];
		$gemSlot = $subStep['gemSlot'];

		$ret = $this->_getGemInfo($compareGem);

		if ( $ret ) {
			array_shift($this->ppUpdate['jobs'][0]['subjobs'][0]['compareGems']);

			$gem = $this->data['Equipment'][$slot]['Gem'][$gemSlot];
			$compareRet = $this->_compareGemInfo($slot, $gemSlot, $gem);

			if ( $compareRet ) {
				$this->ppUpdate['jobs'][0]['subjobs'][0]['compareGems'] = array();
				$this->ppUpdate['jobs'][0]['subjobs'][0]['gemTooltip'] = array( 'type' => 'gemTooltip', 'slot' => $subStep['slot'], 'gemSlot' => $subStep['gemSlot'], 'retry' => 0 );
			}

		} elseif( $subStep['compareGems'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " GemSlot: ". $gemSlot. " GemInfo: ". $compareGem->name. " - Failed", "Retry: ". $this->ppUpdate['jobs'][0]['subjobs'][0]['compareGem'][0]['retry']);
			$this->ppUpdate['jobs'][0]['subjobs'][0]['compareGems'][0]['retry']++;
		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs'][0]['subjobs'][0]['compareGems']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " GemSlot: ". $gemSlot. " GemInfo: ". $compareGem->name. " - Failed", "I give up");

		}
	}

    /**
     * gem armory search part of per page update
     *
     */
	function _ppGemSearchArmory( $subStep = false ) {
		global $addon;
		$gem = $this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']];
		$gemType = $subStep['gemList'][0]['gemType'];
		$ret = $this->_getGemList( $gem, $gemType );

		if ( $ret ) {
			array_shift($this->ppUpdate['jobs'][0]['subjobs'][0]['gemList']);

			foreach ( $this->gemList as $gemFound ) {
				array_push($this->ppUpdate['jobs'][0]['subjobs'][0]['compareGems'], array( 'type' => 'compareGem', 'object' => $gemFound, 'retry' => 0 ) );
			}
		} elseif( $subStep['gemList'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " GemSearch: ". $gemType. " - Failed", "Retry: ". $subStep['gemList'][0]['retry']);
			$this->ppUpdate['jobs'][0]['subjobs'][0]['gemList'][0]['retry']++;

		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs'][0]['subjobs'][0]['gemList']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: ItemInfo Slot: ". $subStep['slot']. " GemSearch: ". $gemType. " - Failed", "I give up");
		}
	}

    /**
     * gem cache check part of per page update
     *
     */
	function _ppGemToCache( $gem = false ) {
		global $roster, $addon;

		require_once(ROSTER_LIB . 'cache.php');
		$cache = new RosterCache;
		$cache->cache_dir = $addon['dir']. 'cache'. DIR_SEP;
		$cache->object_ttl = 604800;

		$gems = array();
		$cacheTag = "gems";

		if ( $cache->check($cacheTag) ) {
			$gems = $cache->get($cacheTag);
		}

		$icon = $gem['Icon'];
		$enchant = $gem['_tmp_enchant'];

		$gems[$icon][$enchant] = $gem;

		$cache->put( $gems, $cacheTag );
		$this->_debug( 1, null, "GemInfo: ". $gem['Name']. " - Put to cache", "OK");
	}

    /**
     * gem cache check part of per page update
     *
     */
	function _ppGemCheckCache( $subStep = false ) {
		global $roster, $addon;

		require_once(ROSTER_LIB . 'cache.php');
		$cache = new RosterCache;
		$cache->cache_dir = $addon['dir']. 'cache'. DIR_SEP;
		$cache->object_ttl = 604800;

		$gems = array();
		$cacheTag = "gems";

		if ( $cache->check($cacheTag) ) {
			$gems = $cache->get($cacheTag);
		}

		$icon = $this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']]['Icon'];
		$enchant = $this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']]['_tmp_enchant'];
		if ( isset($gems[$icon]) && isset($gems[$icon][$enchant]) ) {
			$this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']] = $gems[$icon][$enchant];
			$idA = explode( ':', $this->data["Equipment"][$subStep['slot']]['Item'] );
			$idA[1 + $subStep['gemSlot']] = array_shift( explode(':', $gems[$icon][$enchant]['Item']));
			$this->data["Equipment"][$subStep['slot']]['Item'] = implode( ':', $idA );

			array_shift($this->ppUpdate['jobs'][0]['subjobs']);
			$this->_debug( 1, false, "Char: ". $this->memberName. " Slot: ". $subStep['slot']. " Geminfo: ". $this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']]['Name']. " - Got info from cache", "OK");
		} else {
			array_shift($this->ppUpdate['jobs'][0]['subjobs']);

			if ( isset($roster->locale->act['gems'][$icon]) ) {
				$gemType = $roster->locale->act['gems'][$icon];
				array_unshift($this->ppUpdate['jobs'][0]['subjobs'], array( 'type' => 'gemSearch', 'slot' => $subStep['slot'], 'gemSlot' => $subStep['gemSlot'], 'gemList' => array(), 'compareGems' => array(), 'gemTooltip' => array(), 'retry' => 0) );
				if ( is_array($gemType) ) {
					foreach ( $gemType as $gem ) {
						array_unshift($this->ppUpdate['jobs'][0]['subjobs'][0]['gemList'], array('type' => 'gemList', 'gemType' => $gem, 'retry' => 0 ) );
					}
				} else {
					array_unshift($this->ppUpdate['jobs'][0]['subjobs'][0]['gemList'], array('type' => 'gemList', 'gemType' => $gemType, 'retry' => 0 ) );
				}
				$this->_debug( 1, false, "Char: ". $this->memberName. " Slot: ". $subStep['slot']. " Geminfo: ". $this->data['Equipment'][$subStep['slot']]['Gem'][$subStep['gemSlot']]['Icon']. " - No info from cache", "OK");
			} else {
				if ( !$addon['config']['armorysync_update_incomplete'] ) {
					$this->updateDone = true;
				}
				$this->_debug( 0, $gem['Icon'], 'Unlocalized gem found for icon: '. $icon,  'PROBLEM' );
			}
		}
	}

    /**
     * Talent info part of per page update
     *
     */
	function _ppTalentInfo() {
		global $addon;
		$ret = $this->_getTalentInfo();
		if ( $ret ) {
			array_shift($this->ppUpdate['jobs']);
		} elseif ( $this->ppUpdate['jobs'][0]['retry'] < $this->retrys ) {
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: TalentInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->ppUpdate['jobs'][0]['retry']++;
		} else {
			if ( !$addon['config']['armorysync_update_incomplete'] ) {
				$this->updateDone = true;
				$this->status['talentInfo'] = 0;
			}
			array_shift($this->ppUpdate['jobs']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: TalentInfo failed", "Retry: ". $this->ppUpdate['jobs'][0]['retry']);
			$this->_debug( 0, false, "Char: ". $this->memberName. " Step: TalentInfo failed", "I give up");
		}
	}

    /**
     * Talent info part of per page update
     *
     */
	function _ppUpdate() {
		global $roster, $addon, $update;

		if ( $this->status['characterInfo'] ) {
			include_once(ROSTER_LIB . 'update.lib.php');
			$update = new update;
			$update->fetchAddonData();
			$update->uploadData['characterprofiler']['myProfile'][$this->server]['Character'][$this->data['Name']] = $this->data;
			$this->message = $update->processMyProfile();
			$tmp = explode( "\n", $this->message);
			$this->message = implode( '', $tmp);
			$this->updateDone = true;

			if ( strpos( $this->message, sprintf($roster->locale->act['upload_data'],$roster->locale->act['char'],$this->memberName,$this->server,$this->region)) ) {
				$this->_debug( 1, true, 'Synced armory data for '. $this->memberName. ' with roster',  'OK' );
				return true;
			} else {
				$this->_debug( 1, false, 'Synced armory data for '. $this->memberName. ' with roster',  'Failed' );
				return false;
			}
		} else {
			$this->updateDone = true;
			$this->message = "No infos for ". $this->memberName. "<br>Character has probalby not been updated for a while";
			$this->_debug( 1, false, 'Synced armory data '. $this->memberName. ' with roster',  'Failed' );
			return false;
		}
	}

    /**
     * syncronises one member with blizzards armory
     *
     * @param string $server
     * @param int $memberId
     * @return bool
     */
    function synchMemberByID( $server, $memberId = 0, $memberName = false, $region = false, $guildId = 0 ) {
        global $addon, $roster, $update;

        $this->server = $server;
        $this->memberId = $memberId;
        $this->memberName = $memberName;
        $this->region = $region;
        $this->guildId = $guildId;

        $this->_getRosterData();
		$this->updateDone = true;
		if ( !$addon['config']['armorysync_update_incomplete'] && $this->dataIncomplete ) {
			$this->_debug( 0, $this->data, 'Incomplete data',  'Aborting' );
			return false;
		} elseif ( $this->status['characterInfo'] ) {
            include_once(ROSTER_LIB . 'update.lib.php');
            $update = new update;
            $update->fetchAddonData();
            $update->uploadData['characterprofiler']['myProfile'][$this->server]['Character'][$this->data['Name']] = $this->data;
            $this->message = $update->processMyProfile();
            $tmp = explode( "\n", $this->message);
            $this->message = implode( '', $tmp);
            if ( strpos( $this->message, sprintf($roster->locale->act['upload_data'],$roster->locale->act['char'],$memberName,$server,$region)) ) {
                $this->_debug( 1, true, 'Synced armory data for '. $this->memberName. ' with roster',  'OK' );
                return true;
            } else {
                $this->_debug( 1, false, 'Synced armory data for '. $this->memberName. ' with roster',  'Failed' );
                return false;
            }
        } else {
            $this->message = "No infos for ". $this->memberName. "<br>Character has probably not been updated for a while";
            $this->_debug( 1, false, 'Synced armory data '. $this->memberName. ' with roster',  'Failed' );
            return false;
        }
    }

    /**
     * syncronises one member with blizzards armory
     *
     * @param string $server
     * @param int $memberId
     * @return bool
     */
    function synchGuildByID( $server, $memberId = 0, $memberName = false, $region = false ) {
        global $addon, $roster, $update;

        $this->server = $server;
        $this->guildId = $memberId;
        $this->region = $region;
        $this->memberName = $memberName;
        $this->_getGuildInfo();
        if ( $this->status['guildInfo'] ) {
            include_once(ROSTER_LIB . 'update.lib.php');
            $update = new update;
            $update->fetchAddonData();

            $update->uploadData['characterprofiler']['myProfile'][$this->server]['Guild'][$this->data['name']] = $this->data;
            $this->message = $update->processGuildRoster();
            $tmp = explode( "\n", $this->message);
            $this->message = implode( '', $tmp);

            $this->_debug( 1, true, 'Synced armory data '. $this->memberName. ' with roster',  'OK' );
            return true;
        }
        $this->_debug( 0, false, 'Synced armory data '. $this->memberName. ' with roster',  'Failed' );
        return false;
    }

    /**
     * executes a given method till it returns true or retrys are exceeded
     *
     */
    function _doMethodWithRetrys( $function = false, $arg1 = false, $arg2 = false, $arg3 = false ) {
		global $roster, $addon;

		$ret = false;
		if ( method_exists($this, $function)) {
			$retry = 0;
			while ( ! ( $ret = $this->$function($arg1, $arg2, $arg3) ) ) {
				$retry++;
				if ( $retry > $this->retrys ) {
					$this->_debug( 0, null, 'Method: '. $function. " returned false",  'I give up' );
					break;
				}
				$this->_debug( 1, null, 'Method: '. $function. " returned false - Retrying",  'Retry: '. $retry );
			}
		}
		return $ret;
	}

    /**
     * fetches seperate parts of the character sheet
     *
     */
    function _getRosterData() {
		global $roster, $addon;

		$this->_doMethodWithRetrys('_getCharacterInfo');
		$this->updateDone = true;
        if ( $this->status['characterInfo'] ) {

			if ( !$this->_doMethodWithRetrys('_getSkillInfo') && !$addon['config']['armorysync_update_incomplete'] ) {
				$this->dataIncomplete = true;
				return;
			}
			if ( !$this->_doMethodWithRetrys('_getReputationInfo') && !$addon['config']['armorysync_update_incomplete'] ) {
				$this->dataIncomplete = true;
				return;
			}

			require_once(ROSTER_LIB . 'cache.php');
			$cache = new RosterCache;
			$cache->cache_dir = $addon['dir']. 'cache'. DIR_SEP;
			$cache->object_ttl = 604800;

			$gems = array();
			$cacheTag = "gems";
			$cacheWriteBack = false;

			if ( $cache->check($cacheTag) ) {
				$gems = $cache->get($cacheTag);
			}

			for ( $i = -1; $i <= 18; $i++ ) {
				$slot = $this->_getItemSlot( $i );
				if ( isset( $this->data["Equipment"][$slot] ) ) {

					$this->_doMethodWithRetrys('_getEquipmentInfo', $slot);
					if ( isset($this->data["Equipment"][$slot]['Name']) ) {
						if ( !$this->_doMethodWithRetrys('_getEquipmentTooltip', $slot) && !$addon['config']['armorysync_update_incomplete'] ) {
							$this->dataIncomplete = true;
							return;
						}
						if ( isset($this->data["Equipment"][$slot]['Gem'] ) ) {

							foreach ( $this->data["Equipment"][$slot]['Gem'] as $key => $gem ) {

								$needUnset = true;
								if ( isset( $gems[$gem['Icon']] ) && isset( $gems[$gem['Icon']][$gem['_tmp_enchant']])) {

									$this->data["Equipment"][$slot]['Gem'][$key] = $gems[$gem['Icon']][$gem['_tmp_enchant']];
									$needUnset = false;
									$this->_debug( 1, $this->data, 'Gem: '. $gems[$gem['Icon']][$gem['_tmp_enchant']]['Name']. ' - Got data from cache',  'OK' );
								} elseif ( isset($roster->locale->act['gems'][$gem['Icon']]) ) {

									$gemType = $roster->locale->act['gems'][$gem['Icon']];
									$this->gemList = array();

									if ( is_array( $gemType ) ) {
										foreach ( $gemType as $type ) {
											$this->_doMethodWithRetrys('_getGemList', $gem, $type);
										}
									} else {
										$this->_doMethodWithRetrys('_getGemList', $gem, $gemType);
									}

									if ( count(array_keys($this->gemList)) ) {
										$gemFound = false;
										foreach ( $this->gemList as $searchListGem ) {

											$this->_doMethodWithRetrys('_getGemInfo', $searchListGem);
											if ( is_object($this->compareGem) ) {
												if ( $this->_compareGemInfo( $slot, $key, $gem ) ) {

													$gemFound = true;
													$gemTooltip = str_replace("\n", "<br>", $this->_doMethodWithRetrys('_getItemTooltip', $searchListGem->id));
													if ( $gemTooltip ) {

														$this->data["Equipment"][$slot]['Gem'][$key]['Tooltip'] = $gemTooltip;
														$needUnset = false;
														if ( ! isset($gems[$gem['Icon']][$gem['_tmp_enchant']]) ) {

															$gems[$gem['Icon']][$gem['_tmp_enchant']] = $this->data["Equipment"][$slot]['Gem'][$key];
															$cacheWriteBack = true;
														}
													} elseif ( !$addon['config']['armorysync_update_incomplete'] ) {
														$this->dataIncomplete = true;
														return;
													}
													break;
												}
											}
										}
										if ( !$gemFound ) {
											$this->_debug( 0, $gem['Icon'], 'No gems found for icon '. $gem['Icon']. 'on Armory search. Check your locales! ',  'PROBLEM' );
											if ( !$addon['config']['armorysync_update_incomplete'] ) {
												$this->dataIncomplete = true;
												return;
											}
										}
									} else {
										$this->_debug( 0, $gem['Icon'], 'No gems found for icon '. $gem['Icon']. 'on Armory search. Check your locales! ',  'PROBLEM' );
										if ( !$addon['config']['armorysync_update_incomplete'] ) {
											$this->dataIncomplete = true;
											return;
										}
									}
								} else {
									$this->_debug( 0, $gem['Icon'], 'Unlocalized gem found for icon: '. $gem['Icon'],  'PROBLEM' );
								}
								if ( $needUnset ) {
									unset( $this->data["Equipment"][$slot]['Gem'][$key] );
								} else {
									$itemIdArray = explode(':', $this->data["Equipment"][$slot]['Item']);
									$gemId = array_shift(explode(':', $this->data["Equipment"][$slot]['Gem'][$key]['Item']));
									$itemIdArray[$key+1] = $gemId;
									$this->data["Equipment"][$slot]['Item'] = implode(':', $itemIdArray);
								}
							}
						}
					} else {
						unset($this->data["Equipment"][$slot]);
						if ( !$addon['config']['armorysync_update_incomplete'] ) {
							$this->dataIncomplete = true;
							return;
						}
					}
				}
			}
			if ( $cacheWriteBack ) {
				$gems = $cache->put($gems, $cacheTag);
			}

			if ( !$this->_doMethodWithRetrys('_getTalentInfo') && !$addon['config']['armorysync_update_incomplete'] ) {
				$this->dataIncomplete = true;
				return;
			}
            $this->_debug( 1, $this->data, 'Parsed all armory data',  'OK' );
        } else {
			$this->status = array(  'guildInfo' => 0,
									'characterInfo' => 0,
									'skillInfo' => 0,
									'reputationInfo' => 0,
									'equipmentInfo' => 0,
									'talentInfo' => 0,
								);
            $this->_debug( 1, $this->data, 'Parsed all armory data',  'Failed' );
        }
    }

    /**
     * fetches guild info
     *
     */
    function _getGuildInfo() {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');
        $armory = new RosterArmory;
        //$armory->region = $roster->data['region'];
        $armory->region = $this->region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

        $ret = false;
		$retry = 1;
		while ( $ret == false ) {

			$content = array();
			$cacheTag = 'guildInfo'. $this->memberName. $this->server. $this->region. 'xml';
			$fromCache = false;

			if ( $roster->cache->check($cacheTag) ) {
				$this->_initSimpleParser();
				$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
				$fromCache = true;
			} else {
				$content = $armory->fetchArmory( $armory->guildInfo, false, $this->memberName, $this->server, false,'simpleClass' );
			}

			if ( $this->_checkContent( $content, array( 'guildInfo', 'guild' ) ) ) {

				if ( ! $fromCache ) {
					$roster->cache->put($armory->xml, $cacheTag);
				}

				$guild = $content->guildInfo->guild;

				//$this->data['Ranks'] = $this->_getGuildRanks( $roster->data['guild_id'] );
				//$this->data['timestamp']['init']['datakey'] = $roster->data['region'];
				$this->data['Ranks'] = $this->_getGuildRanks( $this->guildId );
				$this->data['timestamp']['init']['datakey'] = $this->region;
				$this->data['timestamp']['init']['TimeStamp'] = time();
				$this->data['timestamp']['init']['Date'] = date('Y-m-d H:i:s');
				$this->data['timestamp']['init']['DateUTC'] = gmdate('Y-m-d H:i:s');

				$this->data['GPprovider'] = "armorysync";
				$this->data['GPversion'] = "2.6.0";
				//$this->data['name'] = $roster->data['guild_name'];
				$this->data['name'] = $this->memberName;
				$this->data['Info'] = ''; //$roster->data['guild_info_text'];

				//$members = $this->_getGuildMembers( $roster->data['guild_id'] );
				$members = $this->_getGuildMembers( $this->guildId );

				$min = 60;
				$hour = 60 * $min;
				$day = 24 * $hour;
				$month = 30 * $day;
				$year = 365 * $day;

				foreach ( $guild->members->character as $char ) {
					$player = array();
					$player['name'] = $char->name;
					$player['Class'] = $char->class;
					if ( substr($player["Class"] ,0,1) == 'J' && substr($player["Class"] ,-3) == 'ger' ) {
							$player["Class"] = utf8_encode('Jäger');
					}
					$player['Level'] = $char->level;
					$player['Rank'] = $char->rank;
					if ( array_key_exists ( $char->name, $members ) ) {
						$player['Note'] = $members[$char->name]['note'];
						$player['Zone'] = $members[$char->name]['zone'];
						$player['Status'] = $members[$char->name]['status'];
						$player['Online'] = "0";

						$curtime = time();
						$diff = $curtime - strtotime( $members[$char->name]['last_online'] );
						$years = floor( $diff / $year );
						$diff -= $years * $year;
						$months = floor( $diff / $month );
						$diff -= $months * $month;
						$days = floor ( $diff / $day );
						$diff -= $days * $day;
						$hours = floor ( $diff / $hour );

						$player['LastOnline'] = $years. ":". $months. ":". $days. ":". $hours;

						$members[$char->name]['done'] = 1;
					} else {
						$player['Online'] = "1";
					}
					$this->data['Members'][$char->name] = $player;
					if ( ! isset($this->status['guildInfo']) ) {
						$this->status['guildInfo'] = 1;
					} else {
						$this->status['guildInfo'] += 1;
					}
				}

				if ( isset($roster->addon_data['guildbank']) && $roster->addon_data['guildbank']['active'] == 1 ) {
					$guildbank = getaddon('guildbank');
				}

				foreach ( $members as $member ) {
					if ( ! array_key_exists( 'done', $member ) ) {
						if ( is_int( array_search( $member['guild_title'], explode( ',', $addon['config']['armorysync_protectedtitle'] ) ) )
							||
							( isset($guildbank) && strstr($member[$guildbank['config']['banker_fieldname']], $guildbank['config']['banker_rankname']) )
							) {

							$player['name'] = $member['name'];
							$player['Class'] = $member['class'];
							$player['Level'] = $member['level'];
							$player['Rank'] = $member['guild_rank'];
							$player['Note'] = $member['note'];
							$player['Zone'] = $member['zone'];
							$player['Status'] = $member['status'];
							$player['Online'] = "0";

							$curtime = time();
							$diff = $curtime - strtotime( $member['last_online'] );
							$years = floor( $diff / $year );
							$diff -= $years * $year;
							$months = floor( $diff / $month );
							$diff -= $months * $month;
							$days = floor ( $diff / $day );
							$diff -= $days * $day;
							$hours = floor ( $diff / $hour );

							$player['LastOnline'] = $years. ":". $months. ":". $days. ":". $hours;

							$this->data['Members'][$member['name']] = $player;
						}
					}
				}
				$ret = true;
			} else {
				$ret = false;
				$this->status['guildInfo'] = 0;
				$this->_debug( 1, $this->data, 'Parsed guild info',  'Retry: '. $retry );
			}
			$retry++;
			if ( $retry > 3 ) {
				break;
			}
		}
		$this->_debug( 1, $ret, 'Parsed guild info',  $ret == true ? 'OK' : 'I give up' );
		return $ret;
    }

    /**
     * fetches guild info
     *
     */
    function checkGuildInfo( $name = false, $server = false, $region = false ) {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');
        $armory = new RosterArmory;
        $armory->region = $region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

        $ret = false;
		$retry = 1;
		while ( $ret == false ) {
			$content = array();
			$cacheTag = 'guildInfo'. $name. $server. $region. 'xml';
			$fromCache = false;

			if ( $roster->cache->check($cacheTag) ) {
				$this->_initSimpleParser();
				$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
				$fromCache = true;
			} else {
				$content = $armory->fetchArmory( $armory->guildInfo, false, $name, $server, false,'simpleClass' );
			}

			if ( $this->_checkContent( $content, array('guildInfo', 'guild' ) ) ) {

				if ( ! $fromCache ) {
					$roster->cache->put($armory->xml, $cacheTag);
				}

				$ret = true;
			} else {
				$this->_debug( 1, false, 'Checked guild on existence',  'Retry: '. $retry );
				$ret = false;
			}
			$retry++;
			if ( $retry > 3 ) {
				break;
			}
		}
		$this->_debug( 1, $ret, 'Checked guild on existence',  $ret == true ? 'OK' : 'I give up' );
		return $ret;
    }

    /**
     * fetches guild info
     *
     */
    function checkCharInfo( $name = false, $server = false, $region = false ) {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');
        $armory = new RosterArmory;
        $armory->region = $region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);
		$this->_setUserAgent($armory);

        $ret = false;
		$retry = 1;
		while ( $ret == false ) {
			$content = array();
			$cacheTag = 'characterInfo'. $name. $server. $region. 'xml';
			$fromCache = false;

			if ( $roster->cache->check($cacheTag) ) {
				$this->_initSimpleParser();
				$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
				$fromCache = true;
			} else {
				$content = $armory->fetchArmory( $armory->characterInfo, $name, false, $server, false, 'simpleClass' );
			}

			if ( $this->_checkContent( $content, array('characterInfo', 'characterTab' ) ) ) {

				if ( ! $fromCache ) {
					$roster->cache->put($armory->xml, $cacheTag);
				}

				$ret = true;
			} else {
				$this->_debug( 1, false, 'Checked char on existence',  'Retry: '. $retry );
				$ret = false;
			}
			$retry++;
			if ( $retry > $this->retrys ) {
				break;
			}
		}
		$this->_debug( 1, $ret, 'Checked char on existence',  $ret == true ? 'OK' : 'I give up' );
		return $ret;
    }

    /**
     * fetches character info
     *
     */
    function _getCharacterInfo() {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');
        $armory = new RosterArmory;
        //$armory->region = $roster->data['region'];
        $armory->region = $this->region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);
		$this->_setUserAgent($armory);

		$content = array();
		$cacheTag = 'characterInfo'. $this->memberName. $this->server. $this->region. 'xml';
		$fromCache = false;

		if ( $roster->cache->check($cacheTag) ) {
			$this->_initSimpleParser();
			$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
			$fromCache = true;
		} else {
			$content = $armory->fetchArmory( $armory->characterInfo, $this->memberName, false, $this->server, false, 'simpleClass' );
		}

        if ( $this->_checkContent($content, array('characterInfo', 'character' ) ) &&
			 $this->_checkContent($content, array('characterInfo', 'characterTab' ) ) ) {

            if ( ! $fromCache ) {
				$roster->cache->put($armory->xml, $cacheTag);
			}

			$char = $content->characterInfo->character;
            $tab = $content->characterInfo->characterTab;

            $rank = $this->_getMemberRank( $this->memberId );

            $this->data["Name"] = $char->name;
            $this->data["Level"] = $char->level;
            $this->data["Server"] = $char->realm;
            if ($char->hasProp("guildName") ) {
                    $this->data["Guild"]["Name"] = $char->guildName;
            } elseif ($char->hasProp("name")) {
                    $this->data["Guild"]["Name"] = $char->name;
            }
            $this->data["Guild"]["Title"] = $rank['guild_title'];
            $this->data["Guild"]["Rank"] = $rank['guild_rank'];
            $this->data["CPprovider"] = 'armorysync';
            $this->data["CPversion"] = '2.6.0';

            $this->data["Honor"]["Lifetime"]["HK"] = $tab->pvp->lifetimehonorablekills->value;
            //$this->data["Honor"]["Lifetime"]["Name"] = $char->title;
			$this->data["Honor"]["Lifetime"]["Name"] = str_replace( ' %s', '', $tab->title->value);
            $this->data["Honor"]["Session"] = array();
            $this->data["Honor"]["Yesterday"] = array();
            $this->data["Honor"]["Current"] = array();

            $this->data["Attributes"]["Stats"]["Intellect"] = $tab->baseStats->intellect->base. ":" . ($tab->baseStats->intellect->effective - $tab->baseStats->intellect->base) . ":0";
            $this->data["Attributes"]["Stats"]["Agility"] = $tab->baseStats->agility->base. ":" . ($tab->baseStats->agility->effective - $tab->baseStats->agility->base) . ":0";
            $this->data["Attributes"]["Stats"]["Stamina"] = $tab->baseStats->stamina->base . ":" . ($tab->baseStats->stamina->effective - $tab->baseStats->stamina->base) . ":0";
            $this->data["Attributes"]["Stats"]["Strength"] = $tab->baseStats->strength->base . ":" . ($tab->baseStats->strength->effective - $tab->baseStats->strength->base) . ":0";
            $this->data["Attributes"]["Stats"]["Spirit"] = $tab->baseStats->spirit->base . ":" . ($tab->baseStats->spirit->effective - $tab->baseStats->spirit->base) . ":0";

            $this->data["Attributes"]["Defense"]["DodgeChance"] = $tab->defenses->dodge->percent;
            $this->data["Attributes"]["Defense"]["ParryChance"] = $tab->defenses->parry->percent;
            $this->data["Attributes"]["Defense"]["BlockChance"] = $tab->defenses->block->percent;
            $this->data["Attributes"]["Defense"]["ArmorReduction"] = $tab->defenses->armor->percent;

            $this->data["Attributes"]["Defense"]["Armor"] = $tab->baseStats->armor->base . ":" . ($tab->baseStats->armor->effective - $tab->baseStats->armor->base) . ":0";
            $this->data["Attributes"]["Defense"]["Defense"] = $tab->defenses->defense->value . ":" . $tab->defenses->defense->plusDefense . ":0";
            $this->data["Attributes"]["Defense"]["BlockRating"] = $tab->defenses->block->rating. ":0". ":0";
            $this->data["Attributes"]["Defense"]["ParryRating"] = $tab->defenses->parry->rating. ":0". ":0";
            $this->data["Attributes"]["Defense"]["DefenseRating"] = $tab->defenses->defense->rating. ":0". ":0";
            $this->data["Attributes"]["Defense"]["DodgeRating"] = $tab->defenses->dodge->rating. ":0". ":0";

            $this->data["Attributes"]["Defense"]["Resilience"]["Melee"] = $tab->defenses->resilience->value;
            // ??? $this->data["Attributes"]["Defense"]["Resilience"]["Ranged"]
            // ??? $this->data["Attributes"]["Defense"]["Resilience"]["Spell"]

            $this->data["Attributes"]["Resists"]["Frost"] = $tab->resistances->frost->value . ":0:0";
            $this->data["Attributes"]["Resists"]["Arcane"] = $tab->resistances->arcane->value . ":0:0";
            $this->data["Attributes"]["Resists"]["Fire"] = $tab->resistances->fire->value . ":0:0";
            $this->data["Attributes"]["Resists"]["Shadow"] = $tab->resistances->shadow->value . ":0:0";
            $this->data["Attributes"]["Resists"]["Nature"] = $tab->resistances->nature->value . ":0:0";
            $this->data["Attributes"]["Resists"]["Holy"] = $tab->resistances->holy->value . ":0:0";

            $this->data["Attributes"]["Melee"]["AttackPower"] = $tab->melee->power->base . ":". ($tab->melee->power->effective - $tab->melee->power->base ). ":0";
            $this->data["Attributes"]["Melee"]["HitRating"] = $tab->melee->hitRating->value . ":0:0";
            $this->data["Attributes"]["Melee"]["CritRating"] = $tab->melee->critChance->rating . ":0:0";
            $this->data["Attributes"]["Melee"]["HasteRating"] = $tab->melee->mainHandSpeed->hasteRating . ":0:0";
			$this->data["Attributes"]["Melee"]["Expertise"] = $tab->melee->expertise->rating . ":0:0";

            $this->data["Attributes"]["Melee"]["CritChance"] = $tab->melee->critChance->percent;
            $this->data["Attributes"]["Melee"]["AttackPowerDPS"] = $tab->melee->power->increasedDps;

            //if ( $tab->melee->mainHandWeaponSkill->value > 0 ) {

                $this->data["Attributes"]["Melee"]["MainHand"]["AttackSpeed"] = $tab->melee->mainHandDamage->speed;
                $this->data["Attributes"]["Melee"]["MainHand"]["AttackDPS"] = $tab->melee->mainHandDamage->dps;
                //$this->data["Attributes"]["Melee"]["MainHand"]["AttackSkill"] = $tab->melee->mainHandWeaponSkill->value;
                $this->data["Attributes"]["Melee"]["MainHand"]["DamageRange"] = $tab->melee->mainHandDamage->min . ":" . $tab->melee->mainHandDamage->max;
                //$this->data["Attributes"]["Melee"]["MainHand"]["AttackRating"] = $tab->melee->mainHandWeaponSkill->rating;
				$this->data["Attributes"]["Melee"]["MainHand"]["AttackRating"] = $tab->melee->hitRating->value;
            //}

            //if ( $tab->melee->offHandWeaponSkill->value > 0 ) {

                $this->data["Attributes"]["Melee"]["OffHand"]["AttackSpeed"] = $tab->melee->offHandDamage->speed;
                $this->data["Attributes"]["Melee"]["OffHand"]["AttackDPS"] = $tab->melee->offHandDamage->dps;
                //$this->data["Attributes"]["Melee"]["OffHand"]["AttackSkill"] = $tab->melee->offHandWeaponSkill->value;
                $this->data["Attributes"]["Melee"]["OffHand"]["DamageRange"] = $tab->melee->offHandDamage->min . ":" . $tab->melee->mainHandDamage->max;
                //$this->data["Attributes"]["Melee"]["OffHand"]["AttackRating"] = $tab->melee->offHandWeaponSkill->rating;
				$this->data["Attributes"]["Melee"]["OffHand"]["AttackRating"] = $tab->melee->hitRating->value;
            //}

            // ??? $this->data["Attributes"]["Melee"]["DamageRangeTooltip"] = "";
            // ??? $this->data["Attributes"]["Melee"]["AttackPowerTooltip"] = "";


            if ( $tab->ranged->weaponSkill->value > 0 ) {

                $this->data["Attributes"]["Ranged"]["AttackPower"] = $tab->ranged->power->base . ":". ( $tab->ranged->power->effective - $tab->ranged->power->base ). ":0";
                $this->data["Attributes"]["Ranged"]["HitRating"] = $tab->ranged->hitRating->value. ":0:0";
                $this->data["Attributes"]["Ranged"]["CritRating"] = $tab->ranged->critChance->rating. ":0:0";
                $this->data["Attributes"]["Ranged"]["HasteRating"] = $tab->ranged->speed->hasteRating. ":0:0";

                $this->data["Attributes"]["Ranged"]["CritChance"] = $tab->ranged->critChance->percent;
                $this->data["Attributes"]["Ranged"]["AttackPowerDPS"] = $tab->ranged->power->increasedDps;

                $this->data["Attributes"]["Ranged"]["AttackSpeed"] = $tab->ranged->speed->value;
                $this->data["Attributes"]["Ranged"]["AttackDPS"] = $tab->ranged->damage->dps;
                $this->data["Attributes"]["Ranged"]["AttackSkill"] = $tab->ranged->weaponSkill->value;
                $this->data["Attributes"]["Ranged"]["DamageRange"] = $tab->ranged->damage->min . ":" . $tab->ranged->damage->max;
                $this->data["Attributes"]["Ranged"]["AttackRating"] = $tab->ranged->weaponSkill->rating;
            }

            $this->data["Attributes"]["Spell"]["HitRating"] = $tab->spell->hitRating->value . ":0:0";
            $this->data["Attributes"]["Spell"]["CritRating"] = $tab->spell->critChance->rating . ":0:0";
            $this->data["Attributes"]["Spell"]["HasteRating"] = "0:0:0"; // ???

            $this->data["Attributes"]["Spell"]["CritChance"] = min ( array (
                                                                    $tab->spell->critChance->arcane->percent,
                                                                    $tab->spell->critChance->fire->percent,
                                                                    $tab->spell->critChance->frost->percent,
                                                                    $tab->spell->critChance->holy->percent,
                                                                    $tab->spell->critChance->nature->percent,
                                                                    $tab->spell->critChance->shadow->percent ) );

            $this->data["Attributes"]["Spell"]["ManaRegen"] = $tab->spell->manaRegen->notCasting . ":". $tab->spell->manaRegen->casting;
            $this->data["Attributes"]["Spell"]["Penetration"] = $tab->spell->penetration->value;
            $this->data["Attributes"]["Spell"]["BonusDamage"] = min ( array(
                                                                    $tab->spell->bonusDamage->arcane->value,
                                                                    $tab->spell->bonusDamage->fire->value,
                                                                    $tab->spell->bonusDamage->frost->value,
                                                                    $tab->spell->bonusDamage->holy->value,
                                                                    $tab->spell->bonusDamage->nature->value,
                                                                    $tab->spell->bonusDamage->shadow->value ) );
            $this->data["Attributes"]["Spell"]["BonusHealing"] = $tab->spell->bonusHealing->value;

            $this->data["Attributes"]["Spell"]["SchoolCrit"]["Holy"] = $tab->spell->critChance->holy->percent;
            $this->data["Attributes"]["Spell"]["SchoolCrit"]["Frost"] = $tab->spell->critChance->frost->percent;
            $this->data["Attributes"]["Spell"]["SchoolCrit"]["Arcane"] = $tab->spell->critChance->arcane->percent;
            $this->data["Attributes"]["Spell"]["SchoolCrit"]["Fire"] = $tab->spell->critChance->fire->percent;
            $this->data["Attributes"]["Spell"]["SchoolCrit"]["Shadow"] = $tab->spell->critChance->shadow->percent;
            $this->data["Attributes"]["Spell"]["SchoolCrit"]["Nature"] = $tab->spell->critChance->nature->percent;

            $this->data["Attributes"]["Spell"]["School"]["Holy"] = $tab->spell->bonusDamage->holy->value;
            $this->data["Attributes"]["Spell"]["School"]["Frost"] = $tab->spell->bonusDamage->frost->value;
            $this->data["Attributes"]["Spell"]["School"]["Arcane"] = $tab->spell->bonusDamage->arcane->value;
            $this->data["Attributes"]["Spell"]["School"]["Fire"] = $tab->spell->bonusDamage->fire->value;
            $this->data["Attributes"]["Spell"]["School"]["Shadow"] = $tab->spell->bonusDamage->shadow->value;
            $this->data["Attributes"]["Spell"]["School"]["Nature"] = $tab->spell->bonusDamage->nature->value;

			if ( $this->_checkContent( $tab, array( 'buffs', 'spell' ) ) ) {
				if ( is_array($tab->buffs->spell) ) {
					foreach ( $tab->buffs->spell as $spell ) {
						$buffName = $spell->name;
						$this->data["Attributes"]["Buffs"][$buffName]["Name"] = $buffName;
						$this->data["Attributes"]["Buffs"][$buffName]["Icon"] = $spell->icon;
						$this->data["Attributes"]["Buffs"][$buffName]["Tooltip"] = $spell->effect;
					}
				} else {
					$spell = $tab->buffs->spell;
					$buffName = $spell->name;
					$this->data["Attributes"]["Buffs"][$buffName]["Name"] = $buffName;
					$this->data["Attributes"]["Buffs"][$buffName]["Icon"] = $spell->icon;
					$this->data["Attributes"]["Buffs"][$buffName]["Tooltip"] = $spell->effect;
				}
			}

			if ( $this->_checkContent( $tab, array( 'debuffs', 'spell' ) ) ) {
				if ( is_array($tab->debuffs->spell) ) {
					foreach ( $tab->debuffs->spell as $spell ) {
						$buffName = $spell->name;
						$this->data["Attributes"]["Buffs"][$buffName]["Name"] = $buffName;
						$this->data["Attributes"]["Buffs"][$buffName]["Icon"] = $spell->icon;
						$this->data["Attributes"]["Buffs"][$buffName]["Tooltip"] = $spell->effect;
					}
				} else {
					$spell = $tab->debuffs->spell;
					$this->data["Attributes"]["Buffs"][$buffName]["Name"] = $buffName;
					$this->data["Attributes"]["Buffs"][$buffName]["Icon"] = $spell->icon;
					$this->data["Attributes"]["Buffs"][$buffName]["Tooltip"] = $spell->effect;
				}
			}

            $this->data["TalentPoints"] = ($char->level > 0) ? $char->level - $tab->talentSpec->treeOne - $tab->talentSpec->treeTwo - $tab->talentSpec->treeThree - 9 : 0;
            $this->data["Race"] = $char->race;
            $this->data["RaceId"] = $char->raceId;
            $this->data["RaceEn"] = preg_replace( "/\s/", "", $roster->locale->act['race_to_en'][$char->race] );
            if ( $this->data["RaceEn"] == "Undead" ) {
                $this->data["RaceEn"] = "Scourge";
            }
            $this->data["Class"] = $char->class;

            // This is an ugly workaround for an encoding error in the armory
            if ( substr($this->data["Class"] ,0,1) == 'J' && substr($this->data["Class"] ,-3) == 'ger' ) {
                    $this->data["Class"] = utf8_encode('Jäger');
            }
            // This is an ugly workaround for an encoding error in the armory

            $this->data["ClassId"] = $char->classId;
            $this->data["ClassEn"] = $roster->locale->act['class_to_en'][$this->data["Class"]];
			$this->data["Faction"] = $char->faction;
			$this->data["FactionEn"] = $roster->locale->act['faction_to_en'][$char->faction];
            $this->data["Health"] = $tab->characterBars->health->effective;
            $this->data["Mana"] = $tab->characterBars->secondBar->effective;
            if ( $tab->characterBars->secondBar->type == "m" ) {
                $this->data["Power"] = $roster->locale->act['mana'];
            } elseif ( $tab->characterBars->secondBar->type == "r" ) {
                $this->data["Power"] = $roster->locale->act['rage'];
            } elseif ( $tab->characterBars->secondBar->type == "e" ) {
                $this->data["Power"] = $roster->locale->act['energy'];
            } elseif ( $tab->characterBars->secondBar->type == "f" ) {
                $this->data["Power"] = $roster->locale->act['focus'];
            }
            $this->data["Sex"] = $char->gender;

            // This is an ugly workaround for a "What do I kmnow" thing in the armory / french Homme
			if ( $this->data["Sex"] == "(M)" ) {
				$this->data["Sex"] = "Homme";
			}
            // This is an ugly workaround for a "What do I kmnow" thing in the armory / french Homme

            // This is an ugly workaround for a "What do I kmnow" thing in the armory / french Femme
			if ( $this->data["Sex"] == "(F)" ) {
				$this->data["Sex"] = "Femme";
			}
            // This is an ugly workaround for a "What do I kmnow" thing in the armory / french Femme

            // This is an ugly workaround for an encoding error in the armory / german Männlich
            if ( substr($this->data["Sex"],0,1) == 'M' && substr($this->data["Sex"],-6) == 'nnlich' ) {
                    $this->data["Sex"] = utf8_encode('Männlich');
            }
            // This is an ugly workaround for an encoding error in the armory / german Männlich

            $this->data["SexId"] = $char->genderId;

            $this->data["Money"]["Copper"] = 0;
            $this->data["Money"]["Silver"] = 0;
            $this->data["Money"]["Gold"] = 0;
            $this->data["Experience"] = 0;

            //$this->data["Hearth"] = "";

            $this->data["timestamp"]["init"]["DateUTC"] = $this->_getDate($char->lastModified);
            //$this->data['timestamp']['init']['datakey'] = $roster->data['region']. ":";
            $this->data['timestamp']['init']['datakey'] = $this->region. ":";
            // $this->data["TimePlayed"] = 0; //Needed, otherwise WoWDB will kick out this character
            // $this->data["TimeLevelPlayed"] = 0; //Needed, otherwise WoWDB will kick out this character

            // $this->data["Quests"] = array(); //No info for this from Armory
            // $this->data["Inventory"] = array(); //No info for this from Armory

            $this->data["Locale"] = $roster->config['locale'];

            $this->data["Inventory"] = array();
            $this->data["Equipment"] = array();
            $this->data["Skills"] = array();
            $this->data["Reputation"] = array();
            $this->data["Talents"] = array();

            $this->status['characterInfo'] = 1;
            $this->status['guildInfo'] = 1;

			if ( $this->_checkContent( $tab, array( 'items', 'item' ) ) ) {
				$equip = $tab->items->item;
				foreach($equip as $item) {

					$slot = $this->_getItemSlot($item->slot);
					$this->data["Equipment"][$slot] = array();

					$this->data["Equipment"][$slot]['Icon'] = $item->icon;

					$this->data["Equipment"][$slot]['Item'] = $item->id;
					$this->data["Equipment"][$slot]['Item'] .= ":". $item->permanentenchant;
					$this->data["Equipment"][$slot]['Item'] .= ":". "0"; // GemId0
					$this->data["Equipment"][$slot]['Item'] .= ":". "0"; // GemId1
					$this->data["Equipment"][$slot]['Item'] .= ":". "0"; // GemId2
					$this->data["Equipment"][$slot]['Item'] .= ":". "0"; // ???
					$this->data["Equipment"][$slot]['Item'] .= ":". "0"; // ???
					$this->data["Equipment"][$slot]['Item'] .= ":". $item->seed;
				}
			}

            $this->_debug( 1, true, 'Char: '. $this->memberName. ' Parsed character infos',  'OK' );
			return true;

        } else {
            $this->status['characterInfo'] = 0;
            $this->status['guildInfo'] = 0;
            $this->_debug( 1, false, 'Char: '. $this->memberName. ' Parsed character infos',  'Failed' );
			return false;
        }
    }

    /**
     * fetches character equipment info
     *
     */
    function _getEquipmentInfo( $slot = false ) {
        global $roster, $addon;

		$equipcount = count( array_keys($this->data["Equipment"]) );
		if ( ! isset($this->status['equipmentInfo']) ) {
			$this->status['equipmentInfo'] = "0/". $equipcount;
		}

		if ( ! $slot == false && isset($this->data["Equipment"][$slot]) ) {
	        include_once(ROSTER_LIB . 'armory.class.php');
            $armory = new RosterArmory;
            $armory->region = $this->region;
            $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

			$id = array_shift( explode( ":", $this->data["Equipment"][$slot]["Item"]));

			$content = array();
			$cacheTag = 'itemTooltip'. $this->memberName. $this->server. $this->region. $id. 'xml';
			$fromCache = false;

			if ( $roster->cache->check($cacheTag) ) {
				$this->_initSimpleParser();
				$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
				$fromCache = true;
			} else {
				$content = $armory->fetchArmory( $armory->itemTooltip, $this->memberName, false, $this->server, $id, 'simpleClass' );
			}

            if ( $this->_checkContent( $content, array( 'itemTooltips', 'itemTooltip' ) ) ) {

				if ( ! $fromCache ) {
					$roster->cache->put($armory->xml, $cacheTag);
				}

                $tooltip = $content->itemTooltips->itemTooltip;
                $this->data["Equipment"][$slot]['Name'] = $tooltip->name->_CDATA;
                $this->data["Equipment"][$slot]['Color'] = $this->_getItemColor($tooltip->overallQualityId->_CDATA);
                //$this->data["Equipment"][$slot]['Tooltip'] = $itemToolTipHtml;

				if ( $this->_checkContent( $tooltip, array( 'socketData', 'socket' ) ) ) {

					if ( is_array($tooltip->socketData->socket) ) {
						$t = 1;
						foreach ( $tooltip->socketData->socket as $gem ) {

							if ( $gem->hasProp('icon') && $gem->icon ) {
								$this->data["Equipment"][$slot]['Gem'][$t]['Icon'] = $gem->icon;
								$this->data["Equipment"][$slot]['Gem'][$t]['_tmp_enchant'] = $gem->enchant;
							}
							$t++;
						}
					} elseif ( is_object($tooltip->socketData->socket) ) {

						$gem = $tooltip->socketData->socket;
						if ( $gem->hasProp('icon') && $gem->icon ) {
							$this->data["Equipment"][$slot]['Gem'][1]['Icon'] = $gem->icon;
							$this->data["Equipment"][$slot]['Gem'][1]['_tmp_enchant'] = $gem->enchant;
						}
					}
				}

				if ( ! isset($this->status['equipmentInfo']) ) {
					$this->status['equipmentInfo'] = "1/". $equipcount;
				} else {
					$tmp = array_shift( explode( "/", $this->status['equipmentInfo']));
					$tmp++;
					$this->status['equipmentInfo'] = $tmp. "/". $equipcount;
				}
				$this->_debug( 1, true, 'Char: '. $this->memberName. ' Slot: '. $slot. ' Parsed equipment details', 'OK' );
				return true;
			} else {
				$this->_debug( 1, false, 'Char: '. $this->memberName. ' Slot: '. $slot. ' Parsed equipment details', 'Failed' );
				return false;
			}
		}
	}

    /**
     * fetches character equipment info
     *
     */
    function _getEquipmentTooltip( $slot = false ) {
		$id = array_shift( explode(':', $this->data["Equipment"][$slot]['Item']) );
		if ( $this->data["Equipment"][$slot]['Tooltip'] = $this->_getItemTooltip($id) ) {
			$this->_debug( 1, true, 'Char: '. $this->memberName. ' Slot: '. $slot. ' Fetched equipment tooltip', 'OK' );
			return true;
		} else {
			$this->_debug( 1, true, 'Char: '. $this->memberName. ' Slot: '. $slot. ' Fetched equipment tooltip', 'Failed' );
			return false;
		}
	}

    /**
     * fetches list of gems
     *
     */
    function _getGemList( $gem = array(), $gemType = false ) {
		global $roster, $addon;

		include_once(ROSTER_LIB . 'armory.class.php');
		$armory = new RosterArmory;
		$armory->region = $this->region;
		$armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

		$content = $armory->fetchArmory( $armory->search, false, false, false, $gemType, 'simpleClass' );

		if ( $this->_checkContent( $content, array( 'armorySearch', 'searchResults', 'items', 'item' ) ) ) {

			$matchCount = 0;
			if ( is_array($content->armorySearch->searchResults->items->item) ) {
				foreach ( $content->armorySearch->searchResults->items->item as $item ) {
					if ( $item->icon == $gem['Icon'] ) {
						$this->gemList[] = $item;
						$matchCount++;
					}
				}
			} else {
				$item = $content->armorySearch->searchResults->items->item;
				if ( $item->icon == $gem['Icon'] ) {
					$this->gemList[] = $item;
					$matchCount++;
				}
			}
			if ( $matchCount ) {
				$this->_debug( 1, true, 'GemType: '. $gemType. ' lookup. - Found '. $matchCount. ' matching gem(s)', 'OK' );
				return true;
			} else {
				$this->_debug( 1, true, 'GemType: '. $gemType. ' lookup. - No matching gems found. Check your locale file!', 'Failed' );
				return true;
			}
		} else {
			$this->_debug( 1, true, 'GemType: '. $gemType. ' lookup', 'Failed' );
			return false;
		}
	}

    /**
     * fetches gem info
     *
     */
    function _getGemInfo( $gem = array() ) {
		global $roster, $addon;

		$this->compareGem = array();

		include_once(ROSTER_LIB . 'armory.class.php');
		$armory = new RosterArmory;
		$armory->region = $this->region;
		$armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

		$content = array();
		$cacheTag = 'itemTooltip'. $gem->id. 'xml';
		$fromCache = false;

		if ( $roster->cache->check($cacheTag) ) {
			$this->_initSimpleParser();
			$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
			$fromCache = true;
		} else {
			$content = $armory->fetchArmory( $armory->itemTooltip, false, false, false, $gem->id, 'simpleClass' );
		}

		if ( $this->_checkContent($content, array('itemTooltips', 'itemTooltip')) ) {

            if ( ! $fromCache ) {
				$roster->cache->put($armory->xml, $cacheTag);
			}

			$this->compareGem = $content->itemTooltips->itemTooltip;
			$this->_debug( 1, true, 'Gem: '. $gem->name. ' - Parsed gem info', 'OK' );
			return true;
		}
		$this->_debug( 1, true, 'Gem: '. $gem->name. ' - Parsed gem info', 'Failed' );
		return false;
	}

    /**
     * compares gem info and sets to socket if matches
     *
     */
    function _compareGemInfo( $slot = false, $key = 1, $gem = array() ) {

		if ( $this->compareGem->hasProp('gemProperties') && $this->compareGem->gemProperties->_CDATA == $gem['_tmp_enchant'] ) {

			$this->data["Equipment"][$slot]['Gem'][$key]['Name'] = $this->compareGem->name->_CDATA;
			$this->data["Equipment"][$slot]['Gem'][$key]['Item'] = '99'. $this->compareGem->id->_CDATA. ":0:0:0:0:0:0:0";
			$this->data["Equipment"][$slot]['Gem'][$key]['Color'] = $this->_getItemColor($this->compareGem->overallQualityId->_CDATA);
			//unset( $this->data["Equipment"][$slot]['Gem'][$key]['_tmp_enchant'] );

			$idA = explode( ':', $this->data["Equipment"][$slot]['Item'] );
			$idA[1 + $key] = '99'. $this->compareGem->id->_CDATA;
			$this->data["Equipment"][$slot]['Item'] = implode( ':', $idA );

			$this->_debug( 1, true, 'Gem: '. $this->compareGem->name->_CDATA. ' - Matched gem properties', 'OK' );
			return true;
		} else {

			$this->_debug( 1, false, 'Gem: '. $this->compareGem->name->_CDATA. ' - Mismatched gem properties', 'OK' );
			return false;
		}
	}

    /**
     * fetches character skill info
     *
     */
    function _getSkillInfo() {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');
        $armory = new RosterArmory;
        $armory->region = $this->region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

		$content = array();
		$cacheTag = 'characterSkills'. $this->memberName. $this->server. $this->region. 'xml';
		$fromCache = false;

		if ( $roster->cache->check($cacheTag) ) {
			$this->_initSimpleParser();
			$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
			$fromCache = true;
		} else {
			$content = $armory->fetchArmory( $armory->characterSkills, $this->memberName, false, $this->server, false, 'simpleClass' );
		}

        if ( $this->_checkContent( $content, array( 'characterInfo', 'skillTab' ) ) ) {

            if ( ! $fromCache ) {
				$roster->cache->put($armory->xml, $cacheTag);
			}

            $skillSets = $content->characterInfo->skillTab->skillCategory;

            foreach ($skillSets as $skillSet) {
                $type = $skillSet->name;
                $this->data["Skills"][$type] = array();

                if (is_array($skillSet->skill)) {
                    foreach($skillSet->skill as $skill) {
                        if ( $skill->key == 'lockpicking' || $skill->key == 'poisons' ) {
                            $this->data["Skills"][$type][$skill->name] = $skill->value . ":" . (($skill->max > 0) ? $skill->max : $skill->value);
                        } else {
                            $this->data["Skills"][$type][$skill->name] = $skill->value . ":" . (($skill->max > 0) ? $skill->max : 1);
                        }
                        $this->status['skillInfo'] += 1;
                    }
                } else {
                    $skill = $skillSet->skill;
                    $this->data["Skills"][$type][$skill->name] = $skill->value . ":" . (($skill->max > 0) ? $skill->max : 1);
                    $this->status['skillInfo'] += 1;
                }

                $this->data["Skills"][$type]["Order"] = $this->_getSkillOrder($type);
            }
            $this->_debug( 1, true, 'Char: '. $this->memberName. ' Parsed skill info', 'OK' );
			return true;
        } else {
			$this->status['skillInfo'] = 0;
            $this->_debug( 1, false, 'Char: '. $this->memberName. ' Parsed skill info', 'Failed' );
			return false;
        }
    }

    /**
     * fetches character reputation info
     *
     */
    function _getReputationInfo() {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');
        $armory = new RosterArmory;
        $armory->region = $this->region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

		$content = array();
		$cacheTag = 'characterReputation'. $this->memberName. $this->server. $this->region. 'xml';
		$fromCache = false;

		if ( $roster->cache->check($cacheTag) ) {
			$this->_initSimpleParser();
			$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
			$fromCache = true;
		} else {
			$content = $armory->fetchArmory( $armory->characterReputation, $this->memberName, false, $this->server, false, 'simpleClass' );
		}

        if ( $this->_checkContent( $content, array( 'characterInfo', 'reputationTab') ) ) {

            if ( ! $fromCache ) {
				$roster->cache->put($armory->xml, $cacheTag);
			}

            $factionReputation = $content->characterInfo->reputationTab->factionCategory;

            $this->data["Reputation"]["Count"] = 0;

            if (is_array($factionReputation)) {
                foreach ($factionReputation as $factionRep) {
                    $factionType = $factionRep->name;

                    if (is_array($factionRep->faction)) {
                        foreach($factionRep->faction as $faction) {
                            $this->_setFactionRep( $factionType, $faction);
                        }
                    } else {
                        $this->_setFactionRep( $factionType, $factionRep->faction);
                    }
                }
            } else {
                $factionRep = $factionReputation;
                $factionType = $factionRep->name;
                if (is_array($factionRep->faction)) {
                    foreach($factionRep->faction as $faction) {
                        $this->_setFactionRep( $factionType, $faction);
                    }
                } else {
                    $this->_setFactionRep( $factionType, $factionRep->faction);
                }
            }
            $this->_debug( 1, true, 'Char: '. $this->memberName. ' Parsed reputation info', 'OK' );
			return true;
        } else {
			$this->status['reputationInfo'] = 0;
            $this->_debug( 1, false, 'Char: '. $this->memberName. ' Parsed reputation info', 'Failed' );
			return false;
        }
    }

    /**
     * fetches character talent info
     *
     */
    function _getTalentInfo() {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');
        $armory = new RosterArmory;
        $armory->region = $this->region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

		$content = array();
		$cacheTag = 'characterTalents'. $this->memberName. $this->server. $this->region. 'xml';
		$fromCache = false;

		if ( $roster->cache->check($cacheTag) ) {
			$this->_initSimpleParser();
			$content = $this->simpleParser->parse($roster->cache->get($cacheTag));
			$fromCache = true;
		} else {
			$content = $armory->fetchArmory( $armory->characterTalents, $this->memberName, false, $this->server, false, 'simpleClass' );
		}

        if ( $this->_checkContent( $content, array( 'characterInfo', 'talentTab') ) ) {

            if ( ! $fromCache ) {
				$roster->cache->put($armory->xml, $cacheTag);
			}

            $armoryTalents = $content->characterInfo->talentTab->talentTree->value;
            $talentArray = preg_split('//', $armoryTalents, -1, PREG_SPLIT_NO_EMPTY);
            $dl_class = $roster->locale->act['class_to_en'][$this->data["Class"]];
            $class = strtolower($dl_class);
            $locale = $roster->config['locale'];

            require_once ($addon['dir'] . 'inc/armorysynctalents.class.php');
            $ast = new ArmorySyncTalents();
			$ast->debugmessages = &$this->debugmessages;
			$ast->errormessages = &$this->errormessages;
            $talents = $ast->getTalents(  $class );

            require_once ($addon['dir'] . 'inc/talenticons_'. $class. '.php');

            $pointsSpent = array();
            $dl_talentTree = array();

			$this->status['talentInfo'] = 0;

            for ($i = 0; $i < sizeof($talentArray); $i++) {
                    $talentName = $talents['talent'][$i][1];
                    $talentX = $talents['talent'][$i][3];
                    $talentY = $talents['talent'][$i][4];
                    $talentRank = $talentArray[$i];
                    $talentMax = $talents['talent'][$i][2];
                    $talentDesc = $talents['rank'][$i][(($talentRank > 0) ? $talentRank -1 : 0)];
                    $talentDesc = str_replace("\\\r\n", '', $talentDesc);
                    $talentDesc = str_replace("\t", '', $talentDesc);
                    $talentTree = $talents['tree'][
                                                    $i <= $talents['treeStartStop'][0] ? 0 :
                                                    ( $i <= $talents['treeStartStop'][1] ? 1 : 2 )
                                                   ];
                    $talentTreeOrder =  $i <= $talents['treeStartStop'][0] ? 1 :
                                        ( $i <= $talents['treeStartStop'][1] ? 2 : 3 );
					if ( isset($talentIcons[$class][$talentTreeOrder][$talentX][$talentY]) ) {
						$talentPic = $talentIcons[$class][$talentTreeOrder][$talentX][$talentY];
					} else {
						$this->_debug( 0, null, 'Char: '. $this->memberName. ' - No talent icon found for '. $class. ' '. $talentTreeOrder. '/'. $talentX. '/'. $talentY, 'PROBLEM' );
					}
                    if ( array_key_exists ( $talentTree, $pointsSpent ) ) {
                        $pointsSpent[$talentTree] += $talentRank;
                    } else {
                        $pointsSpent[$talentTree] = $talentRank;
                    }
                    $this->status['talentInfo'] += $talentRank;

                    $talent = array(
                            "Location" => $talentY . ":" . $talentX,
                            "Tooltip" => $talentName . "<br>". $roster->locale->act['misc']['Rank']. " " . $talentRank ."/" . $talentMax . "<br>" . $talentDesc,
                            "Icon" => $talentPic,
                            "Rank" => $talentRank . ":" . $talentMax
                    );

                    $this->data["Talents"][$talentTree][$talentName] = $talent;
                    $this->data["Talents"][$talentTree]["Order"] = $talentTreeOrder;
                    if (!isset($dl_talentTree[$talentTree])) {
                        $dl_talentTree[$talentTree] = $this->_getDelocalisedTalenttree($talentTree, $dl_class);
                    }
                    if (!isset($this->data["Talents"][$talentTree]["Background"])) {
                        $this->data["Talents"][$talentTree]["Background"] = $this->_getTalentBackground($dl_class, $dl_talentTree[$talentTree]);
                    }
                    $this->data["Talents"][$talentTree]["PointsSpent"] = $pointsSpent[$talentTree];
            }
            $this->_debug( 1, true, 'Char: '. $this->memberName. ' Parsed talent info', 'OK' );
			return true;
        } else {
			$this->status['talentInfo'] = 0;
            $this->_debug( 1, false, 'Char: '. $this->memberName. ' Parsed talent info', 'Failed' );
			return false;
        }
    }
    // Helper functions

    /**
     * helper function to get classes for content
     *
     * @param string $class
     * @param string $tree
     * @return string
     */
    function _checkContent( $object = false, $keys = array( ) ) {

        if ( is_object ($object ) && count (array_keys ( $keys ) ) !== 0 ) {

            $subobject = $object;

            foreach ( $keys as $key ) {
                if ( $subobject->hasProp($key) ) {
                    $subobject = $subobject->$key;
                } else {
                    $this->_debug( 3, $object, 'Checked XML content', 'Failed' );
                    return false;
                }
            }
            $this->_debug( 2, true, 'Checked XML content', 'OK' );
            return true;
        } else {
            $this->_debug( 2, false, 'Checked XML content', 'Failed' );
            return false;
        }
    }

    /**
     * helper function to get classes for content
     *
     * @param string $class
     * @param string $tree
     * @return string
     */
    function _setUserAgent( $armory ) {
		global $roster;

		$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ';
		switch ( $roster->config['locale'] ) {
			case 'enUS': $userAgent .= 'en-US';
				break;

			case 'deDE': $userAgent .= 'de-DE';
				break;

			case 'frFR': $userAgent .= 'fr-FR';
				break;

			case 'esES': $userAgent .= 'es-ES';
				break;
		}

		$userAgent .= '; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';
		$armory->setUserAgent($userAgent);

	}

    /**
     * helper function to get classes for content
     *
     * @param string $class
     * @param string $tree
     * @return string
     */
    function _setFactionRep( $factionType, $faction = array() ) {
        $this->data["Reputation"][$factionType][$faction->name] = array();
        $this->data["Reputation"][$factionType][$faction->name]["Value"] = $this->_getRepValue($faction->reputation) . ":" . $this->_getRepCap($faction->reputation);
        $this->data["Reputation"][$factionType][$faction->name]["Standing"] = $this->_getRepStanding($faction->reputation);
        $this->data["Reputation"][$factionType][$faction->name]["AtWar"] = $this->_getRepAtWar($faction->reputation);
        $this->status['reputationInfo'] += 1;
        $this->_debug( 2, $this->data["Reputation"][$factionType][$faction->name], 'Set reputation for single faction', 'OK' );
    }

    /**
     * helper function to get talent background
     *
     * @param string $class
     * @param string $tree
     * @return string
     */
    function _getTalentBackground($class, $tree) {
        $background = $class . str_replace(" ", "", $tree);

        switch ($tree) {
            case "Elemental":
            case "Feral":
                $background .= "Combat";
                break;
            case "Retribution":
                $background = $class . "Combat";
                break;
            case "Affliction":
                $background = $class . "Curses";
                break;
            case "Demonology":
                $background = $class . "Summoning";
                break;
        }
        $this->_debug( 2, $background, 'Looked up talent background', 'OK' );
        return $background;
    }

    /**
     * helper function to delocalise character classes
     *
     * @param string $class
     * @return string
     */
    function _getDelocalisedClass($class) {
        global $addon, $roster;
        $ret = $class;

        foreach ( $roster->locale->act['Classes'] as $key => $value ) {
                $value = $value;
                if ( $value == $class ) {
                        $ret = $key;
                        break;
                }
        }
        $this->_debug( 2, $ret, 'Delocalized class', 'OK' );
        return $ret;
    }

    /**
     * helper function to delocalise talent trees
     *
     * @param string $tree
     * @param string $class
     * @return string
     */
    function _getDelocalisedTalenttree($tree, $class) {
        global $addon, $roster;
        $ret = $tree;

        foreach ( $roster->locale->act['Talenttrees'][$class] as $key => $value ) {
                $value = $value;
                if ( $value == $tree ) {
                        $ret = $key;
                        break;
                }
        }
        $this->_debug( 2, $ret, 'Delocalized talenttree', 'OK' );
        return $ret;
    }

    /**
     * helper function to get item tooltips
     *
     * @param int $value
     * @return string
     */
    function _getItemTooltip( $itemId = 0 ) {
        global $roster, $addon;

        include_once(ROSTER_LIB . 'armory.class.php');

        $armory = new RosterArmory;
        //$armory->region = $roster->data['region'];
        $armory->region = $this->region;
        $armory->setTimeOut( $addon['config']['armorysync_fetch_timeout']);

		$content = array();
		$cacheTag = 'itemTooltip'. $this->memberName. $this->server. $this->region. $itemId. 'html';
		$fromCache = false;

		if ( $roster->cache->check($cacheTag) ) {
			$content = $roster->cache->get($cacheTag);
			$fromCache = true;
		} else {
			$content = $armory->fetchArmory( $armory->itemTooltip, $this->memberName, false, $this->server, $itemId, 'html' );
		}
		if ( $content ) {

			if ( ! $fromCache ) {
				$roster->cache->put($content, $cacheTag);
			}

			$html = $content;
            $content = str_replace("\n", "", $content );
            $content = str_replace("\r", "", $content );
			$content = str_replace("\t", "", $content );
            //$content = str_replace('<span class="tooltipRight">', "\t", $content );
			$content = preg_replace('/<img class="socketImg p".*?>(.*?)<br>/', '|cffffffff${1}|r<br>', $content );
			//$content = preg_replace('/(?<=<span class="">)(\+\d+&nbsp;</span><span class="">\w+)(?=</span>)/' , '|cffffffff${1}|r', $content );
			$content = str_replace("<br/>", "%__BRTAG%", $content );
            $content = str_replace("<br>", "%__BRTAG%", $content );
			$content = str_replace('&nbsp;', ' ', $content );
			$content = preg_replace('/\s\s+/', '', $content );

            // This is an ugly workaround for an encoding error in the armory
			$content = preg_replace('/J.{3}ger/', 'Jäger', $content );
            // This is an ugly workaround for an encoding error in the armory

			$tmpA = explode( "%__BRTAG%", $content);

			$content = '';
			foreach ( $tmpA as $tmp ) {
				$tmp = preg_replace( '/(.*?)<span class="tooltipRight">(.*?)<\/span>(.*)/', "\${1}\${3}\t\${2}", $tmp );
				$content .= $tmp. "%__BRTAG%";
			}

            $content = strip_tags( $content );



			$content = str_replace("%__BRTAG%", "\n", $content );
			$content = preg_replace('/\s+\n/', "\n", $content );
            $content = utf8_encode($this->_unhtmlentities( $content ));
            $content = str_replace($roster->locale->act['bindings']['bind_on_pickup'], $roster->locale->act['bindings']['bind'], $content);
            $content = str_replace($roster->locale->act['bindings']['bind_on_equip'], $roster->locale->act['bindings']['bind'], $content);

			if ( $roster->locale->curlocale == 'frFR' ) {
				$content = preg_replace( '/(\d+) Armure/', "Armure: \${1}", $content );
				$content = preg_replace( '/(\d+) Blocage/', "Bloquer: \${1}", $content );
				$content = preg_replace( '/Classes : (.+)/', "Classes  : \${1}", $content );
				$content = preg_replace( '/Durabilit.*?:/', utf8_encode('Durabilité:'), $content);
				$content = preg_replace( '/\+\s(\d+)/', "+\${1}", $content );

			}

			//$test = "|". str_replace( "\n", "|\n|", $content). "|";
			//$test = str_replace( "\t", "|\n|", $test);
			//$test1 = htmlentities($test);
			//$test2 = utf8_decode($test);
			//$test3 = utf8_encode($test);
			//$test4 = '|';
			//$test5 = '|';
			//
			//$testtmp = str_replace( "\n", "", $content);
			//$testtmp = str_replace( "\t", "", $testtmp);
			//
			//for ( $i = 0; $i < strlen($testtmp); $i++ ) {
			//	$char = substr( $testtmp, $i, 1);
			//	$test4 .= sprintf('%3s|', $char);
			//	$test5 .= sprintf('%3d|', ord($char));
			//}
			//
			//
			//$cmp = "html=>\n". $html.
			//		//"\n\nResult=>\n". $content.
			//		//"\n\nhtmlentities=>\n". $test1.
			//		//"\n\nutf8_decode=>\n". $test2.
			//		//"\n\nutf8_encode=>\n". $test3.
			//		"\n\nord=>\n". $test4. "\n". $test5;

            $this->_debug( 2, $content, 'Fetched item tooltip', 'OK' );
            return $content;
        }
        $this->_debug( 2, '', 'Fetched item tooltip', 'Failed' );
        return false;
    }

    /**
     * helper function to get item tooltips
     *
     * @param int $value
     * @return string
     */
    //function _getGemTooltip( $itemId = 0 ) {
    //    global $roster, $addon;
    //
    //    include_once(ROSTER_LIB . 'armory.class.php');
    //
    //    $armory = new RosterArmory;
    //    //$armory->region = $roster->data['region'];
    //    $armory->region = $this->region;
    //    if ( $content = $armory->fetchItemInfoHTML( $itemId, $roster->config['locale'] ) ) {
    //
    //        //$pos = $this->_striposB($content, '<div class="displayTable">');
    //        $pos = $this->_striposB($content, '<div class="myTable">');
    //        if ($pos != 0){
    //                //Need to trim garbage off..
    //                $content = substr ($content, $pos);
    //        }
    //        $pos = 0;
    //        $pos = $this->_striposB($content, '</div>');
    //        $pos += 6;
    //        $len = strlen($content) - $pos;
    //        $len *= -1;
    //        if ($pos != 0){
    //                //Need to trim garbage off..
    //                $content = substr ($content, 0, $len);
    //        }
    //
    //        $content = str_replace("\n", "", $content );
    //        $content = str_replace("<br>", "%__BRTAG%", $content );
    //
    //        $content = strip_tags( $content );
    //
    //        $content = str_replace("%__BRTAG%", "<br />", $content );
    //        $content = mb_convert_encoding( $content, "UTF-8", "HTML-ENTITIES");
    //        return $content;
    //    }
    //    return false;
    //}

    /**
     * helper function to strip of a string
     *
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    function _striposB($haystack, $needle){
        $ret = strpos($haystack, stristr( $haystack, $needle ));
        $this->_debug( 3, $ret, 'Fetched item tooltip', 'OK' );
        return $ret;
    }

    /**
     * helper function to get hex value for item color
     *
     * @param int $value
     * @return string hex color
     */
    function _getItemColor($value) {
        $ret = '';
        switch ($value) {
            case 5: $ret = "ff8000"; //Orange
				break;
            case 4: $ret = "a335ee"; //Purple
				break;
            case 3: $ret = "0070dd"; //Blue
				break;
            case 2: $ret = "1eff00"; //Green
				break;
            case 1: $ret = "ffffff"; //White
				break;
            default: $ret = "9d9d9d"; //Grey
				break;
        }
        $this->_debug( 2, $ret, 'Determined item color', 'OK' );
        return $ret;
    }

    /**
     * helper function to get string value for item slot
     *
     * @param int $value
     * @return string slot
     */
    function _getItemSlot($value) {
        $ret = '';
        switch ($value) {
			case -1: $ret = "Ammo";
				break;
            case 0: $ret = "Head";
				break;
            case 1: $ret = "Neck";
				break;
            case 2: $ret = "Shoulder";
				break;
            case 3: $ret = "Shirt";
				break;
            case 4: $ret = "Chest";
				break;
            case 5: $ret = "Waist";
				break;
            case 6: $ret = "Legs";
				break;
            case 7: $ret = "Feet";
				break;
            case 8: $ret = "Wrist";
				break;
            case 9: $ret = "Hands";
				break;
            case 10: $ret = "Finger0";
				break;
            case 11: $ret = "Finger1";
				break;
            case 12: $ret = "Trinket0";
				break;
            case 13: $ret = "Trinket1";
				break;
            case 14: $ret = "Back";
				break;
            case 15: $ret = "MainHand";
				break;
            case 16: $ret = "SecondaryHand";
				break;
            case 17: $ret = "Ranged";
				break;
            case 18: $ret = "Tabard";
				break;
        }
        $this->_debug( 2, $ret, 'Determined item slot', 'OK' );
        return $ret;
    }

    /**
     * helper function to get numerical value for skill order
     *
     * @param int $value
     * @return int SkillOrder
     */
    function _getSkillOrder($value) {
        global $roster, $addon;
        $ret = 0;
        switch ($value) {
            case $roster->locale->act['Skills']['Class Skills']: $ret = 1;
				break;
            case $roster->locale->act['Skills']['Professions']: $ret = 2;
				break;
            case $roster->locale->act['Skills']['Secondary Skills']: $ret = 3;
				break;
            case $roster->locale->act['Skills']['Weapon Skills']: $ret = 4;
				break;
            case $roster->locale->act['Skills']['Armor Proficiencies']: $ret = 5;
				break;
            case $roster->locale->act['Skills']['Languages']: $ret = 6;
				break;
            default: $ret = 0;
				break;
        }
        $this->_debug( 2, $ret, 'Determined skill order', 'OK' );
        return $ret;
    }

    /**
     * helper function to get relative value for reputation
     *
     * @param int $value
     * @return int RepValue
     */
    function _getRepValue($value) {
        $value = abs($value);

        if ($value >= 42000 && $value < 43000) { $value -= 42000; }
        elseif ($value >= 21000 && $value < 42000) { $value -= 21000; }
        elseif ($value >= 9000 && $value < 21000) { $value -= 9000;  }
        elseif ($value >= 3000 && $value < 9000) { $value -= 3000; }
        elseif ($value >= -3000 && $value < 3000) { $value -= 0;  }
        elseif ($value >= -6000 && $value < -3000) { $value -= 3000; }
        elseif ($value >= -42000 && $value < -6000) { $value -= 6000; }

        $this->_debug( 2, $value, 'Determined reputation value', 'OK' );
        return $value;
    }

    /**
     * helper function to get cap value for reputation
     *
     * @param int $value
     * @return int RepCap
     */
    function _getRepCap($value) {
        $ret = 0;
        if ($value >= 42000 && $value < 43000) { $ret = 1000; }
        if ($value >= 21000 && $value < 42000) { $ret = 21000; }
        if ($value >= 9000 && $value < 21000) { $ret = 12000; }
        if ($value >= 3000 && $value < 9000) { $ret = 6000; }
        if ($value >= -6000 && $value < 3000) { $ret = 3000; }
        if ($value >= -42000 && $value < -6000) { $ret = 36000; }

        $this->_debug( 2, $ret, 'Determined reputation cap', 'OK' );
        return $ret;
    }

    /**
     * helper function to get war status for reputation
     *
     * @param int $value
     * @return bool
     */
    function _getRepAtWar($value) {
        if ($value >= -3000) { $ret = 0; }
        else { $ret = 1; }
        $this->_debug( 2, $ret, 'Determined reputation at war', 'OK' );
        return $ret;
    }

    /**
     * helper function to get localized string for reputation
     *
     * @param int $value
     * @return string RepStanding
     */
    function _getRepStanding($value) {
        global $roster, $addon;

        $ret = '';
        if ($value >= 42000 && $value < 43000) { $ret = $roster->locale->act['exalted']; }
        if ($value >= 21000 && $value < 42000) { $ret = $roster->locale->act['revered']; }
        if ($value >= 9000 && $value < 21000) { $ret = $roster->locale->act['honored']; }
        if ($value >= 3000 && $value < 9000) { $ret = $roster->locale->act['friendly']; }
        if ($value >= 0 && $value < 3000) { $ret = $roster->locale->act['neutral']; }
        if ($value >= -3000 && $value < 0) { $ret = $roster->locale->act['unfriendly']; }
        if ($value >= -6000 && $value < -3000) { $ret = $roster->locale->act['hostile']; }
        if ($value >= -42000 && $value < -6000) { $ret = $roster->locale->act['hated']; }

        $this->_debug( 2, $ret, 'Determined reputation standing', 'OK' );
        return $ret;
    }

    /**
     * helper function mbconvert workaround
     *
     * @param int $value
     * @return string RepStanding
     */
    function _unhtmlentities($string)
    {
        // Ersetzen numerischer Darstellungen
        $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
        $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
        // Ersetzen benannter Zeichen
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $ret = strtr($string, $trans_tbl);
        $this->_debug( 3, $ret, 'Removed HTML entities', 'OK' );
        return $ret;
    }

    /**
     * helper function for non enUS strtodate
     *
     * @param string $string
     * @return string date
     */
    function _getDate($string)
    {
        global $roster;

        if ( $roster->locale->curlocale == 'esES') {
            $string = str_replace( 'de ', '', $string );
        }
        if ( $roster->locale->curlocale != 'enUS') {
            $array = explode(" ", $string );
            $array[1] = $roster->locale->act['month_to_en'][$array[1]];
            $string = implode( " ", $array );
        }
        $ret = date('Y/m/d H:i:s', strtotime($string));
        $this->_debug( 2, $ret, 'Converted date', 'OK' );
        return $ret;
    }

    // DB functions

    /**
     * db function to get member name by its id
     *
     * @param int $memberId
     * @return string $memberName
     */
    function _getGuildMembers( $guildID ) {
        global $roster, $addon;

        $query =	"SELECT * ".
                        "FROM ". $roster->db->table('members'). " AS members ".
                        "WHERE ".
                        "members.guild_id=". $guildID;
        $result = $roster->db->query($query);
        if( $roster->db->num_rows($result) > 0 ) {

            $members = $roster->db->fetch_all();
            $array = array();
            foreach ( $members as $member ) {
                $array[$member['name']] = $member;
            }
            $this->_debug( 3, $array, 'Fetched guild members from DB', 'OK' );
            return $array;
        } else {
            $this->_debug( 3, array(), 'Fetched guild members from DB', 'Failed' );
            return array();
        }
    }

    /**
     * db function to get member name by its id
     *
     * @param int $memberId
     * @return string $memberName
     */
    function _getNamefromID( $memberID ) {
        global $roster, $addon;

        $query =	"SELECT ".
                        "members.name ".
                        "FROM ". $roster->db->table('members'). " AS members ".
                        "WHERE ".
                        "members.member_id=". $memberID;
        $memberName = $roster->db->query_first( $query );

        if ( $membername ) {
            $this->_debug( 3, $array, 'Fetched member name from DB', 'OK' );
            return $memberName;
        } else {
            $this->_debug( 3, $array, 'Fetched member name from DB', 'Failed' );
            return $memberName;
        }
    }

    /**
     * db function to get member name by its id
     *
     * @param int $memberId
     * @return string $memberName
     */
    function _getGuildRanks( $guild_id ) {
        global $roster, $addon;

		$array = array();
		$ranks = array();
		if ( $addon['config']['armorysync_rank_set_order'] >= 1 ) {
			$query =	"SELECT ".
							"guild_rank, guild_title ".
							"FROM ". $roster->db->table('members'). " AS members ".
							"WHERE ".
							"members.guild_id=". $guild_id. " ".
							"AND NOT members.guild_title='' ".
							"GROUP BY guild_rank, guild_title ".
							"ORDER BY guild_rank;";
			$result = $roster->db->query($query);
			if( $roster->db->num_rows($result) > 0 ) {

				$tmp = $roster->db->fetch_all();
				foreach ( $tmp as $rank ) {
					$ranks[$rank['guild_rank']] = $rank['guild_title'];
				}
			}
		}

		if ( $addon['config']['armorysync_rank_set_order'] == 3 ) {
            for ( $i = 0; $i <= 9; $i++ ) {
				$array[$i]['Title'] = isset($ranks[$i]) && $ranks[$i] != '' ?
										$ranks[$i] :
										( $addon['config']['armorysync_rank_'. $i] != '' ?
											$addon['config']['armorysync_rank_'. $i] :
											( $i == 0 ?
												$roster->locale->act['guildleader'] :
												$roster->locale->act['rank']. $i ) );
			}
		}
		elseif ( $addon['config']['armorysync_rank_set_order'] == 2 ) {
            for ( $i = 0; $i <= 9; $i++ ) {
				$array[$i]['Title'] = $addon['config']['armorysync_rank_'. $i] != '' ?
										$addon['config']['armorysync_rank_'. $i] :
										( isset($ranks[$i]) && $ranks[$i] != '' ?
											$ranks[$i] :
											( $i == 0 ?
												$roster->locale->act['guildleader'] :
												$roster->locale->act['rank']. $i ) );
			}
		}
		elseif ( $addon['config']['armorysync_rank_set_order'] == 1 ) {
            for ( $i = 0; $i <= 9; $i++ ) {
				$array[$i]['Title'] = isset($ranks[$i]) && $ranks[$i] != '' ?
										$ranks[$i] :
										( $i == 0 ?
											$roster->locale->act['guildleader'] :
											$roster->locale->act['rank']. $i );
			}
		}
		elseif ( $addon['config']['armorysync_rank_set_order'] == 0 ) {
            for ( $i = 0; $i <= 9; $i++ ) {
				$array[$i]['Title'] = $i == 0 ?
										$roster->locale->act['guildleader'] :
										$roster->locale->act['rank']. $i;
			}
		}
		$this->_debug( 3, $array, 'Fetched guild ranks from DB', 'OK' );
		return $array;
    }

    /**
     * db function to get members guild_rank and guild_title by its id
     *
     * @param int $memberId
     * @return string $memberName
     */
    function _getMemberRank( $member_id ) {
        global $roster, $addon;

        $query =	"SELECT ".
                        "guild_rank, guild_title ".
                        "FROM ". $roster->db->table('members'). " AS members ".
                        "WHERE ".
                        "members.member_id=". $member_id. ";";
        $result = $roster->db->query($query);
        if( $roster->db->num_rows($result) > 0 ) {

            $ranks = $roster->db->fetch_all();
            $this->_debug( 3, $ranks[0], 'Fetched member rank from DB', 'OK' );
            return $ranks[0];
        } else {
            $array = array();
            $this->_debug( 3, $array, 'Fetched member rank from DB', 'Failed' );
            return $array;
        }
    }

	/**
	 * Private function that includes simpleparser class if needed and then creates
	 * a new SimpleParser() object if needed
	 *
	 * @return void
	 */
	function _initSimpleParser()
	{
		if( !is_object($this->simpleParser) )
		{
			require_once(ROSTER_LIB . 'simpleparser.class.php');
			$this->simpleParser = new simpleParser();
		}

	}

}
