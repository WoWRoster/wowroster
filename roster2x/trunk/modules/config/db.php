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
cpMain::$instance['cpsql']
	->query_prepare(
		"DROP TABLE IF EXISTS `test`"
	)->execute()
// Create table
	->prepare(
		"CREATE TABLE `test` (".
	 	"	`id` INT(11) AUTO_INCREMENT, ".
	 	"	`name` VARCHAR(32) NOT NULL, ".
	 	"	`phone` int(11), ".
	 	"	PRIMARY KEY (`id`) ".
	 	") ENGINE=MyISAM;"
	)->execute();

$numbers = array(
	array('Zanix',235326),
	array('Pleeg',26597),
	array('Mathos',63098)
);

// Insert values
$qry = cpMain::$instance['cpsql']->query_prepare(
		"INSERT INTO `test` (`name`, `phone`) VALUES".
		" (?,?); "
	);
foreach($numbers as $data)
{
	$qry->reset()
		->bind_param('si',$data)
		->execute();
	echo "Affected rows: ".$qry->affected_rows()."<br>\n";
	echo "Added data ".print_r($data,true)."<br>\n";
}

$qry->close();

// Select values
cpMain::$instance['cpsql']->query_prepare(
		"SELECT * FROM `test` ".
			"WHERE `name` LIKE ?;",
		'select'
	)->bind_param(
		's',
		array(
			&$name
		)
	);

$name = '%at%';

cpMain::$instance['cpsql']->get_query('select')
	->execute()
	->bind_result(
		array(
			&$id,
			&$name,
			&$phone
		)
	);

echo '<table>'."\n";
while(cpMain::$instance['cpsql']->get_query('select')->fetch())
{
	echo '<tr><td>'.$id.'<td>'.$name.'<td>'.$phone."\n";
}
echo '</table>'."\n";

cpMain::$instance['cpsql']->get_query('select')->close();

cpMain::$instance['cpsql']->close();
