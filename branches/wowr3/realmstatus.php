<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Realmstatus Image generator
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage RealmStatus
 */

// WOW Server Status
// Version 3.2
// Copyright 2005 Nick Schaffner
// http://53x11.com

// EDITED BY: http://www.wowroster.net for use in wowroster
// Most other changes by Zanix


if( !defined('IN_ROSTER') )
{
	define('IN_ROSTER', true);
}

$roster_root_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

require_once ($roster_root_path . 'settings.php');
require_once(ROSTER_LIB . 'simpleparser.class.php');

function statussw($status)
{
	switch ($status)
	{
		case '0':
			$q = 'error';
		break;
		case '1':
			$q = 'up';
		break;
		case '2':
			$q = 'down';
		break;
		case 'false':
			$q = 'error';
		break;
		default:
		break;
	}
		
	return $q;
}
//==========[ GET FROM CONF.PHP ]====================================================

if( isset($_GET['r']) )
{
	list($region, $realmname) = explode('-', urldecode(trim(stripslashes($_GET['r']))), 2);
	$region = strtoupper($region);
}
elseif( isset($realmname) )
{
	list($region, $realmname) = explode('-', trim(stripslashes($realmname)), 2);
	$region = strtoupper($region);
}
else
{
	$realmname = '';
}

if( isset($_GET['d']) )
{
	$generate_image = ($_GET['d'] == '0' ? false : true);
}
elseif( isset($roster->config['rs_display']) )
{
	$generate_image = ($roster->config['rs_display'] == 'image' ? true : false);
}
else
{
	$generate_image = true;
}

//piss the xml noise we are going json
//$xmlsource = 'http://wowfeeds.wipeitau.com/RealmStatus.php?location=' . $region . '&rn=' . $realmname . '&output=XML';
//$xmlsource = 'http://wowfeeds.wipeitau.com/RealmStatus.php?location=' . $region . '&rn=' . $realmname . '&callback=?';//'http://www.wowroster.net/realmstatus/'.$realmname.'.xml';
//'http://' . $region . '.battle.net/api/wow/realm/status?realm=' . $realmname . '';

//==========[ OTHER SETTINGS ]=========================================================

// Path to image folder
$image_path = ROSTER_BASE . 'img' . DIR_SEP . 'realmstatus' . DIR_SEP;

#--[ MYSQL CONNECT AND STORE ]=========================================================


// Read info from Database
$querystr = "SELECT * FROM `" . $roster->db->table('realmstatus') . "`"
	. " WHERE `server_region` = '" . $roster->db->escape($region) . "'"
	. " AND `server_name` = '" . $roster->db->escape($realmname) . "';";

$sql = $roster->db->query($querystr);
if( $sql && $roster->db->num_rows($sql) > 0 )
{
	$realmData = $roster->db->fetch($sql, SQL_ASSOC);
}
else
{
	$realmData['server_name']   = '';
	$realmData['server_region'] = '';
	$realmData['servertype']    = '';
	$realmData['serverstatus']  = '';
	$realmData['serverpop']     = '';
	$realmData['timestamp']     = '0';
}

//==========[ STATUS GENERATION CODE ]=================================================


// Check timestamp, update when ready
$current_time = time();

if( $current_time >= ($realmData['timestamp'] + ($roster->config['rs_timer'] * 600)) )
{
	$xmlsource = $roster->api->Realm->getRealmStatus($realmname);
	$r = $xmlsource;

	$d = $r['realms']['0'];

	if( $d !== false )
	{
		$realmType = str_replace('(', '',$d['type']);
		$realmType = str_replace(')', '',$realmType);

		$realmData['server_region'] = $region;
		$realmData['servertype']    = strtoupper($realmType);
		$realmData['serverstatus']  = strtoupper(statussw($d['status']));
		$realmData['serverpop']     = strtoupper($d['population']);

		$err = 0;
	}
	else
	{
		$err = 1;
	}

	//==========[ WRITE INFO TO DATABASE ]=================================================

	if( !$err ) // Don't write to DB if there has been an error
	{
		$values['servertype'] = $realmData['servertype'];
		$values['serverstatus'] = $realmData['serverstatus'];
		$values['serverpop'] = $realmData['serverpop'];
		$values['timestamp'] = $current_time;

		if( $realmname == $realmData['server_name'] )
		{
			$querystr = "UPDATE `" . $roster->db->table('realmstatus') . "`"
				. " SET " . $roster->db->build_query('UPDATE', $values)
				. " WHERE `server_name` = '" . $roster->db->escape($realmname) . "';";
		}
		else
		{
			$values['server_name'] = $realmname;
			$values['server_region'] = $region;
			$querystr = "INSERT INTO `" . $roster->db->table('realmstatus') . "` "
				. $roster->db->build_query('INSERT', $values) . ";";
			$realmData['server_name'] = $realmname;
		}

		$roster->db->query($querystr);
	}
}

//==========[ "NOW, WHAT TO DO NEXT?" ]================================================


// Error control
if( $realmData['serverstatus'] == 'DOWN' || $realmData['serverstatus'] == 'MAINTENANCE' || $realmData['serverpop'] == 'N/A')
{
	$realmData['serverstatus'] = 'DOWN';
	$realmData['serverpop'] = 'OFFLINE';
	$realmData['serverpopcolor'] = $roster->config['rs_color_error'];
}

// Check to see if data from the DB is non-existant
if( empty($realmData['serverstatus']) || empty($realmData['serverpop']) )
{
	$err = 1;
}
else
{
	$err = 0;
}

// Set to Unknown values upon error
if( $err )
{
	$realmData['servertype'] = 'UNKNOWN';
	$realmData['serverstatus'] = 'UNKNOWN';
	$realmData['serverpop'] = 'NOSTATUS';
	$realmData['serverpopcolor'] = $roster->config['rs_color_error'];
	$realmData['servertypecolor'] = $roster->config['rs_color_error'];
	$realmData['servertype'] = ($realmData['servertype'] != '' ? $realmData['servertype'] : '');
}
else
{
	if( $realmData['serverpop'] == ' ' )
	{
		$realmData['serverpopcolor'] = $roster->config['rs_color_low'];
	}
	else
	{
		$realmData['serverpopcolor'] = $roster->config['rs_color_' . strtolower($realmData['serverpop'])];
	}
	$realmData['servertypecolor'] = $roster->config['rs_color_' . strtolower($realmData['servertype'])];
	$realmData['serverpop'] = $realmData['serverpop'];
	$realmData['servertype'] = ($realmData['servertype'] != '' ? $realmData['servertype'] : '');
}

// Generate image or text?
if( $generate_image )
{
	img_output($realmData, $err, $image_path);
}
else
{
	echo text_output($realmData);
}

return;

//==========[ TEXT OUTPUT MODE ]=======================================================
function text_output( $realmData )
{
	global $roster;

	// If there is no data, then we want to output blank text
	$realmData['servertype'] = $realmData['servertype'] != '' ? $roster->locale->act['rs'][$realmData['servertype']] : $realmData['servertype'];

	$outtext = '
<div style="position:relative;width:272px;height:35px;font-family:arial;font-weight:bold;background:transparent url(' . ROSTER_URL . $roster->config['img_url'] . 'realmstatus/background.png) no-repeat;">
	<div style="position:absolute;width:272px;height:35px;background:transparent url(' . ROSTER_URL . $roster->config['img_url'] . 'realmstatus/' . strtolower($realmData['serverpop']) . '.png) no-repeat;">
		<div style="position:absolute;bottom:-1px;left:30px;color:' . $roster->config['rs_color_server'] . ';' . ($roster->config['rs_color_shadow'] ? 'text-shadow:' . $roster->config['rs_color_shadow'] . ' 1px 1px 0;' : '') . 'font-size:24px;">' . $realmData['server_name'] . '</div>
		<div style="position:absolute;bottom:3px;right:5px;color:' . $realmData['serverpopcolor'] . ';' . ($roster->config['rs_color_shadow'] ? 'text-shadow:' . $roster->config['rs_color_shadow'] . ' 1px 1px 0;' : '') . 'font-size:10px;">' . $roster->locale->act['rs'][$realmData['serverpop']] . '</div>
		<div style="position:absolute;bottom:22px;left:35px;color:' . $realmData['servertypecolor'] . ';' . ($roster->config['rs_color_shadow'] ? 'text-shadow:' . $roster->config['rs_color_shadow'] . ' 1px 1px 0;' : '') . 'font-size:10px;">' . $realmData['servertype'] . '</div>
		<div style="position:absolute;bottom:2px;left:1px;width:32px;height:32px;background:transparent url(' . ROSTER_URL . $roster->config['img_url'] . 'realmstatus/' . strtolower($realmData['serverstatus']) . '-icon.png) no-repeat;"></div>
	</div>
</div>
';
	return $outtext;
}

//==========[ IMAGE GENERATOR ]========================================================


function img_output( $realmData , $err , $image_path )
{
	global $roster;

	$vadj = 0;

	$serverfont = $roster->config['rs_font_server'];
	$typefont = $roster->config['rs_font_type'];
	$serverpopfont = $roster->config['rs_font_pop'];

	require(ROSTER_LIB . 'roster_gd.php');
	$roster_gd = new RosterGD();

	$shadow = array('color' => $roster->config['rs_color_shadow'], 'distance' => 1, 'direction' => 45, 'spread' => 1);

	$bkg_img = ROSTER_BASE . 'img' . DIR_SEP . 'realmstatus' . DIR_SEP . 'background.png';
	$pop_img = ROSTER_BASE . 'img' . DIR_SEP . 'realmstatus' . DIR_SEP . strtolower($realmData['serverpop']) . '.png';
	$arrow_img = ROSTER_BASE . 'img' . DIR_SEP . 'realmstatus' . DIR_SEP . strtolower($realmData['serverstatus']) . '-icon.png';

	$bkg_img_info = getimagesize($bkg_img);
	$roster_gd->make_image($bkg_img_info[0], $bkg_img_info[1]);
	$roster_gd->combine_image($bkg_img, 0, 0);

	// If there is no data, then we want to output blank text
	$realmData['servertype'] = $realmData['servertype'] != '' ? $roster->locale->act['rs'][$realmData['servertype']] : $realmData['servertype'];

	$roster_gd->write_text($roster->config['rs_size_type'], 0, 30, 8, $realmData['servertypecolor'], 0, $typefont, $realmData['servertype'], 'left', array(), $shadow);
	$roster_gd->write_text($roster->config['rs_size_pop'], 0, 267, 30, $realmData['serverpopcolor'], 0, $serverpopfont, $roster->locale->act['rs'][$realmData['serverpop']], 'right', array(), $shadow);
	$roster_gd->write_text($roster->config['rs_size_server'], 0, 30, 30, $roster->config['rs_color_server'], 0, $serverfont, $realmData['server_name'], 'left', array(), $shadow);

	$roster_gd->combine_image($pop_img, 0, 0);
	$roster_gd->combine_image($arrow_img, 1, 1);

	$roster_gd->get_image('png');
	$roster_gd->finish();

}
