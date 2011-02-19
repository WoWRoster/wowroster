<?php
/**
 * WoWRoster.net WoWRoster
 *
 * RosterCP (Control Panel)
 * After Install Guide
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
{
	exit('Detected invalid access to this file!');
}

$data_present = $roster->db->query_first("SELECT `name` FROM `" . $roster->db->table('upload') . "` WHERE `default` = 1;");

if( empty($data_present) )
{
	$roster->set_message($roster->locale->act['guide_already_complete'], $roster->locale->act['setup_guide'], 'error');
	$body .= messagebox(sprintf($roster->locale->act['guide_next_text'], makelink('rostercp-install'), makelink('rostercp-upload'), makelink('rostercp-armory_data')), $roster->locale->act['guide_next']);
	return;
}


include(ROSTER_LIB . 'install.lib.php');

$roster->tpl->assign_vars(array(
	'U_ROSTERCP' => makelink('rostercp'),

	'S_STEP_1'   => false,
	'S_STEP_2'   => false,
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

	$name = post_or_db('name');
	$server = post_or_db('server');
	$region = strtoupper(substr(post_or_db('region'),0,2));

	$roster->tpl->assign_vars(array(
		'S_STEP_1'     => true,

		'NAME'   => $name,
		'SERVER' => $server,
		'REGION' => $region,

		'L_NAME_TIP'   => makeOverlib($roster->locale->act['guildname']),
		'L_SERVER_TIP' => makeOverlib($roster->locale->act['realmname']),
		'L_REGION_TIP' => makeOverlib($roster->locale->act['regionname']),
		)
	);
}

function guide_step2()
{
	global $roster;

	$name = post_or_db('name');
	$server = post_or_db('server');
	$region = strtoupper(substr(post_or_db('region'),0,2));

	if( !empty($name) && !empty($server) && !empty($region) )
	{
		$query = "UPDATE `" . $roster->db->table('upload') . "` SET `default` = '0';";

		if( !$roster->db->query($query) )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}

		$query  = "INSERT INTO `" . $roster->db->table('upload') . "`"
				. " (`name`,`server`,`region`,`type`,`default`)"
				. " VALUES ('" . $name . "','" . $server . "','" . $region . "','0','1');";

		if( !$roster->db->query($query) )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
		}
		$roster->set_message($roster->locale->act['guide_complete']);
		$body .= messagebox(sprintf($roster->locale->act['guide_next_text'], makelink('rostercp-install'), makelink('rostercp-upload'), makelink('rostercp-armory_data')), $roster->locale->act['guide_next']);

		$roster->tpl->assign_var('S_STEP_2',true);
	}
	else
	{
		$roster->set_message($roster->locale->act['upload_rules_error'],'','error');
		guide_step1();
	}
}

$roster->tpl->set_handle('guide','admin/install_guide.html');
$body .= $roster->tpl->fetch('guide');


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
