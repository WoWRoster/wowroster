<?php
/**
 * Project: cpFramework - scalable object based modular framework
 * File: plugins/index.php
 *
 * Database layer demo
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
 * @link http://cpframework.org
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author WoWRoster.net
 * @version 1.5.0
 * @copyright 2000-2006 WoWRoster.net
 * @package cpPlugin
 * @subpackage DB
 * @filesource
 *
 * Roster versioning tag
 * $Id$
 */

/**
 * Our security measure, present in any file which does not contain
 * a direct access to our config itself. This is a security measure.
 */
if(!defined('SECURITY'))
{
	die("You may not access this file directly.");
}

// Load DB
cpMain::loadFactory('cpsqlfactory', 'cpsql');

// Drop table if exists
$qry = cpMain::$instance['cpsql']->query_prepare(
		"DROP TABLE IF EXISTS `test`"
	);
if( !$qry->execute() )
{
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br />\n";
}

// Create table
$qry = cpMain::$instance['cpsql']->query_prepare(
		"CREATE TABLE `test` (".
	 	"	`id` INT(11) AUTO_INCREMENT, ".
	 	"	`name` VARCHAR(32) NOT NULL, ".
	 	"	`phone` int(11), ".
	 	"	PRIMARY KEY (`id`) ".
	 	") ENGINE=MyISAM;"
	);
if( !$qry->execute() )
{
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br />\n";
}

// Insert values
$qry = cpMain::$instance['cpsql']->query_prepare(
		"INSERT INTO `test` (`name`, `phone`) VALUES".
		" ('Zanix','235326'), ".
		" ('Pleeg','26597'), ".
		" ('Mathos','63098'); "
	);
if( !$qry->execute() )
{
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br />\n";
}

echo "Affected rows: ".$qry->affected_rows();

$qry->close();

// Select values
$qry = cpMain::$instance['cpsql']->query_prepare(
		"SELECT * FROM `test` ".
		"WHERE `name` LIKE ?;"
	);

$qry->bind_param('s',array(&$name));

$name = 'Zanix';

if( !$qry->execute() )
{
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br />\n";
}

$qry->bind_result(array(&$id, &$name, &$phone));

echo '<table>'."\n";
while($qry->fetch())
{
	echo '<tr><td>'.$id.'<td>'.$name.'<td>'.$phone."\n";
}
echo '</table>'."\n";

$qry->close();

cpMain::$instance['cpsql']->close();
