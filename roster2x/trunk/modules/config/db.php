<?php
/**
 * Database layer demo
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
cpMain::loadClass('cpsql', 'cpsql');

// Drop table if exists
$qry = cpMain::$instance['cpsql']->query_prepare(
		"DROP TABLE IF EXISTS `test`"
	);
if( !$qry->execute() )
{
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br>\n";
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
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br>\n";
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
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br>\n";
}

echo "Affected rows: ".$qry->affected_rows();

$qry->close();

// Select values
$qry = cpMain::$instance['cpsql']->query_prepare(
		"SELECT * FROM `test` ".
		"WHERE `name` LIKE ?;"
	);

$qry->bind_param('s',array(&$name));

$name = '%at%';

if( !$qry->execute() )
{
	echo 'Errno: '.$qry->errno().': '.$qry->error()."<br>\n";
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
