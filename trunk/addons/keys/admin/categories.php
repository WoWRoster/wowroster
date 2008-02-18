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

if( isset( $_POST['process'] ) && $_POST['process'] == 'process' )
{
	if( substr( $_POST['action'], 0, 4 ) == 'del_' )
	{
		$cat = substr( $_POST['action'], 4, ( $keypos = strpos( $_POST['action'], '_', 4 ) ) - 4 );
		$key = substr( $_POST['action'], $keypos + 1 );

		$query = "DELETE FROM `" . $roster->db->table('categories', 'keys') . "` WHERE `category` = '" . $cat ."' AND `key` = '" . $key . "';";
		$roster->db->query($query);
	}
	elseif( $_POST['action'] == 'add' )
	{
		$query = "INSERT INTO `" . $roster->db->table('categories', 'keys') . "` (`category`,`key`) VALUES ('" . $_POST['category'] . "','" . $_POST['key'] . "');";
		$roster->db->query($query);
	}
}

$table = "<table class='bodyline'>\n"
	. "\t<thead>\n"
	. "\t\t<tr>\n"
	. "\t\t\t<th class='membersHeader'>Category</th>\n"
	. "\t\t\t<th class='membersHeader'>Key</th>\n"
	. "\t\t\t<th class='membersHeaderRight'>Delete</th>\n"
	. "\t\t</tr>\n"
	. "\t</thead>\n"
	. "\t<tfoot>\n"
	. "\t\t<tr>\n"
	. "\t\t\t<td><input class='wowinput128' type='text' name='category' value='' /></td>\n"
	. "\t\t\t<td>\n"
	. "\t\t\t\t<select name='key'>\n";

$query = "SELECT DISTINCT key_name "
	. "FROM `" . $roster->db->table('keys', 'keys') . "` "
	. "ORDER BY `key_name` ";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$table .= "\t\t\t\t\t<option name='" . $row['key_name'] . "'>" . $row['key_name'] . "</option>\n";
}

$table .= "\t\t\t\t</select>\n"
	. "\t\t\t</td>\n"
	. "\t\t\t<td>"
	. "\t\t\t\t" . '<button type="submit" class="input" onclick="setvalue(\'action\',\'add\');">' . "\n"
	. "\t\t\t\t\tAdd\n"
	. "\t\t\t\t</button>\n"
	. "\t\t\t</td>\n"
	. "\t\t</tr>\n"
	. "\t</tfoot>\n"
	. "\t<tbody>\n";

$query = "SELECT `category`, `key` "
	. "FROM `" . $roster->db->table('categories', 'keys') . "` "
	. "ORDER BY `category`, `key` ";

$result = $roster->db->query($query);

while( $row = $roster->db->fetch($result) )
{
	$table .= "\t\t<tr>\n"
		. "\t\t\t<td class='membersRow1'>" . $row['category'] . "</td>\n"
		. "\t\t\t<td class='membersRow1'>" . $row['key'] . "</td>\n"
		. "\t\t\t<td class='membersRowRight1'>\n"
		. "\t\t\t\t" . '<button type="submit" class="input" onclick="setvalue(\'action\',\'del_' . $row['category'] . '_' . $row['key'] . '\');">' . "\n"
		. "\t\t\t\t\tDelete\n"
		. "\t\t\t\t</button>\n"
		. "\t\t\t</td>\n"
		. "\t\t</tr>\n";
}

$table .= "\t</tbody>\n"
	. "</table>\n";

$roster->db->free_result($result);

$body .= '<form action="' . makelink() . '" method="post">' . "\n"
	. "\t" . '<input type="hidden" id="action" name="action" value="" />' . "\n"
	. "\t" . '<input type="hidden" name="process" value="process" />' . "\n"
	. messagebox($table, 'Key Categories', 'syellow')
	. '</form>' . "\n";
