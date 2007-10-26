<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$skill_name = isset($_POST['skill']) ? $_POST['skill'] : 'Unarmed';

include_once ($addon['dir'] . 'inc/memberslist.php');

$memberlist = new memberslist;

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`level`, '.
	'`members`.`zone`, '.
	"(UNIX_TIMESTAMP( `members`.`last_online`)*1000+".($roster->config['localtimeoffset']*3600000).") AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_online', ".
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`guild_title`, '.

	'`alts`.`main_id`, '.

	'`players`.`server`, '.
	'`players`.`race`, '.
	'`players`.`sex`, '.
	'`players`.`exp`, '.
	'`players`.`clientLocale`, '.

	'`talenttable`.`talents`, '.

	'`skills`.`skill_level`, '.
	'`skills`.`skill_name` '.

	'FROM `'.$roster->db->table('members').'` AS members '.
	'INNER JOIN `'.$roster->db->table('players').'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'INNER JOIN `'.$roster->db->table('skills').'` AS skills ON `members`.`member_id` = `skills`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('alts',$addon['basename']).'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.

	"LEFT JOIN (SELECT `member_id` , GROUP_CONCAT( CONCAT( `tree` , '|', `pointsspent` , '|', `background` ) ORDER BY `order`) AS 'talents' ".
		'FROM `'.$roster->db->table('talenttree').'` '.
		'GROUP BY `member_id`) AS talenttable ON `members`.`member_id` = `talenttable`.`member_id` '.

	'WHERE `members`.`guild_id` = "'.$roster->data['guild_id'].'" '.
		'AND `skills`.`skill_name` = "'.$skill_name.'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';

$always_sort = ' `members`.`level` DESC, `members`.`name` ASC';

$FIELD['name'] = array(
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => array($memberlist,'name_value'),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'js_type' => 'ts_string',
	'display' => $addon['config']['stats_class'],
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => array($memberlist,'level_value'),
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_level'],
);

$FIELD['skill_level'] = array (
	'lang_field' => 'skill_level',
	'order' => array( "`skill_level` DESC" ),
	'order_d' => array( "`skill_level` ASC" ),
	'value' => 'skill_value',
	'js_type' => 'ts_number',
	'display' => $addon['config']['stats_str'],
);

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'memberslist');

$menu = '';
// Start output
if ( $addon['config']['stats_motd'] == 1 )
{
	$menu .= $memberlist->makeMotd();
}

$roster->output['before_menu'] .= $menu;

echo $memberlist->makeFilterBox();

echo $memberlist->makeToolBar('horizontal');

echo skill_dropdown();

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

function skill_dropdown()
{
	global $roster;

	$query = 'SELECT DISTINCT `skill_order`, `skill_type`, `skill_name` '.
		'FROM `'.$roster->db->table('skills').'` '.
		'ORDER BY `skill_order`;';

	$result = $roster->db->query($query);

	$output = '<form name="skillpicker" action="'.makelink().'" method="post">'."\n";
	$output .= '  <select name="skill" onchange="document.forms[\'skillpicker\'].submit();">'."\n";
	$type = null;
	while( $row = $roster->db->fetch($result) )
	{
		if( $row['skill_type'] !== $type )
		{
			if( $type !== null )
			{
				$output .= '    </optgroup>'."\n";
			}
			$output .= '    <optgroup label="'.$row['skill_type'].'">'."\n";
			$type = $row['skill_type'];
		}
		$output .= '      <option value="'.$row['skill_name'].'">'.$row['skill_name'].'</option>'."\n";
	}
	if( $type == null )
	{
		return '';
	}
	$output .= '    </optgroup>'."\n";
	$output .= '  </select>'."\n";
	$output .= '</form>'."\n";

	return $output;
}

function skill_value( $row )
{
	global $roster, $addon;

	list($value, $maxvalue) = explode( ':', $row['skill_level'] );
	$skill_name = $row['skill_name'];
	$barwidth = ceil($value/$maxvalue*273);

	$output = '<div style="display:none;">'.$value.'</div>';
	$output = '<div class="skill_bar">'."\n";
	if( $maxvalue == '1' )
	{
		$output .= '  <div style="position:absolute;"><img src="'.$addon['image_url'].'skill/bar_grey.gif" alt="" /></div>'."\n";
		$output .= '  <div class="text">'.$row['skill_name'].'</div>';
	}
	else
	{
		$output .= '  <div style="position:absolute;clip:rect(0px '.$barwidth.'px 15px 0px);"><img src="'.$addon['image_url'].'skill/bar.gif" alt="" /></div>'."\n";
		$output .= '  <div class="text">'.$skill_name.'<span class="text_num">'.$value.' / '.$maxvalue.'</span></div>'."\n";
	}
	$output .= '</div>'."\n";

	return $output;
}
