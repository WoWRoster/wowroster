<?php
/**
 * WoWRoster.net WoWRoster
 *
 * RosterCP (Control Panel)
 * After Install Guide
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Check log-in ]-------------------------------------
if( ! $roster->auth->getAuthorized( ROSTERLOGIN_ADMIN ) )
{
	echo '<span class="title_text">' . $roster->locale->act['setup_guide'] . '</span><br />'
		. $roster->auth->getLoginForm();

	return;
}
// ----[ End Check log-in ]---------------------------------

$roster->output['body_onload'] .= 'initARC(\'guide\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';


include(ROSTER_LIB . 'install.lib.php');


$roster->tpl->assign_vars(array(
	'U_ROSTERCP' => makelink('rostercp'),

	'MESSAGE' => '',

	'L_SETUP_GUIDE' => $roster->locale->act['setup_guide'],

	'S_STEP_1' => false,
	'S_STEP_2' => false,
	)
);


$STEP = ( isset($_POST['guide_step']) ? $_POST['guide_step'] : '1' );


/**
 * Figure out what we're doing...
 */
switch( $STEP )
{
	case 1:
		guide_step1();
		break;
	case 2:
		guide_step2();
		break;
	default:
		guide_step1();
		break;
}


function guide_step1()
{
	global $roster;

	$roster->tpl->assign_vars(array(
		'S_STEP_1' => true,

		'L_DEFAULT_DATA'      => $roster->locale->act['default_data'],
		'L_DEFAULT_DATA_HELP' => $roster->locale->act['default_data_help'],

		'L_NAME'              => $roster->locale->act['name'],
		'L_NAME_TIP'          => makeOverlib( $roster->locale->act['guildname'] ),
		'L_SERVER'            => $roster->locale->act['server'],
		'L_SERVER_TIP'        => makeOverlib($roster->locale->act['realmname']),
		'L_REGION'            => $roster->locale->act['region'],
		'L_REGION_TIP'        => makeOverlib($roster->locale->act['regionname']),
		)
	);
}

function guide_step2()
{
	global $roster;

	$roster->tpl->assign_vars(array(
		'S_STEP_2' => true,
		)
	);

	$name = post_or_db('name');
	$server = post_or_db('server');
	$region = post_or_db('region');

	if( !empty($name) || !empty($server) || !empty($region) )
	{
		$query  = "INSERT INTO `" . $roster->db->table('upload') . "`"
				. " (`name`,`server`,`region`,`type`,`default`)"
				. " VALUES ('" . $name . "','" . $server . "','" . strtoupper($region) . "','0','1');";

		if( !$roster->db->query($query) )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}
	}
	else
	{
		$roster->tpl->assign_var('MESSAGE',messagebox($roster->locale->act['upload_rules_error'],'','sred'));
	}
}

$roster->tpl->set_handle('guide','install_guide.html');
$roster->tpl->display('guide');


/**
 * Checks if a POST field value exists;
 * If it does, we use that one, otherwise we use the optional database field value,
 * or return a null string if $db_row contains no data
 *
 * @param    string  $post_field POST field name
 * @param    array   $db_row     Array of DB values
 * @param    string  $db_field   DB field name
 * @return   string
 */
function post_or_db( $post_field , $db_row = array() , $db_field = '' )
{
	if ( @sizeof($db_row) > 0 )
	{
		if ( $db_field == '' )
		{
			$db_field = $post_field;
		}

		$db_value = $db_row[$db_field];
	}
	else
	{
		$db_value = '';
	}
	return( (isset($_POST[$post_field])) || (!empty($_POST[$post_field])) ) ? $_POST[$post_field] : $db_value;
}
