<?php

/**
 * db_layer.php
 * This file is a collection of database specific technical data, as well as
 * convenience functions to allow more abstraction to the core logic.
 * $Id$
 * @author Chris Moates <six@mox.net>
 */

$db_options = array(
		    'debug'       => 2,
		    'portability' => DB_PORTABILITY_ALL ^ DB_PORTABILITY_LOWERCASE,
		    );

$dsn['roster'] = $roster_conf['dbtype'].'://'.$roster_conf['db_user'].':'.$roster_conf['db_pass'].'@'.$roster_conf['db_host'].'/'.$roster_conf['db_name'];

foreach($dsn as $database=>$dsnstring){
    $db[$database] =& DB::connect($dsnstring, $db_options);
    if(DB::isError($db[$database])){
	$errorMsg = "There has been an error with this tool connecting to it's supporting database.<br/>\n";
	$errorMsg .="Please try reloading this page. If the problem persists, report it to your administrator.<br/>\n";
	$errorMsg .="The error message reported from the server was: ".$db[$database]->getMessage()."<br/>\n";
	die($errorMsg);
    }
    $db[$database]->setFetchMode(DB_FETCHMODE_ASSOC);
}

/**
 * Close all the database connections
 *
 */
function db_close(){
    global $db;
    foreach($db as $this_conn){
	$this_conn->disconnect();
    }
}

/**
 * Check for a db error
 *
 * @param string $db
 */
function db_check_error($db){
    if(DB::isError($db)){
	print("<pre>\n");
	echo 'Standard Message: ' . $db->getMessage() . "\n";
	echo 'Standard Code: ' . $db->getCode() . "\n";
	echo 'DBMS/User Message: ' . $db->getUserInfo() . "\n";
	echo 'DBMS/Debug Message: ' . $db->getDebugInfo() . "\n";
	print("</pre>\n");
	exit;
    }
}

/**
 * Wrapper for Pear::DB->execute()
 * @var array sth prepared statement array from our db_prepare()
 * @return resource the resource handle for the query.
 */
function db_execute(){
    global $db;
    // This function requires a minimum of two arguments, an sth and a variable to substitute.
    $numargs = func_num_args();
    if($numargs < 2){
	return(false);
    }
    // Decode our sth to pull the actual sth and database name
    $our_sth = func_get_arg(0);
    $sth = $our_sth["sth"];
    $database = $our_sth["db"];
    // Take our n-length variable list and turn it into an array to pass to db->execute()
    for($i=1;$i<$numargs;$i++){
	$data[]=func_get_arg($i);
    }
    // Now execute the query on the proper database, with the proper data, and return the $res
    $res =& $db[$database]->execute($sth, $data);
    db_check_error($res);
    return($res);
}

/**
 * Wrapper for Pear::DB->getAll()
 * @var string SQL statement
 * @return array results
 */
function db_getAll($sql, $database = "roster"){
    global $dsn;
    global $db;
    if($dsn[$database]==""){
	die("Database $database does not have a connection");
    }
    $r =& $db[$database]->getAll($sql);
    return($r);
}

/**
 * Wrapper for Pear::DB->prepare()
 * This function allows our code to not have to deal with tracking database handles, etc.
 * @var string SQL to be prepared
 * @var string database to prepare against (default roster)
 */
function db_prepare($sql, $database = "roster"){
    global $db;
    $sth = $db[$database]->prepare($sql);
    db_check_error($sth);
    $retval = array("sth"=>$sth, "db"=>$database);
    return($retval);
}

/**
 * Wrapper function to allow code to not have to deal with handling errors for SQL, etc.
 * @var string sql is the query to be performed
 * @var string database is the database to query against (default roster)
 * @return resource handle
 */
function db_query($sql, $database = "roster"){
    global $dsn, $db, $queries_list;
    
    if($dsn[$database]==""){
	die("Database $database does not have a connection.");
    }
    // Write the SQL output to the error log if debug is active,
    // and put it in $queries_list for later inspection.
    if(GetConfigValue("debug")==true && GetConfigValue('debug_database_queries')==true){
	debug_print($sql);
    }
    $res =& $db[$database]->query($sql);
    db_check_error($res);
    return $res;
}

/**
 * Wrapper function to write an insert query
 * @param string table_name
 * @param array keys
 * @param array values
 */
function db_query_insert_array($table, $keys, $values, $database="roster"){
    global $dsn, $db;
    if($dsn[$database]==""){
	die("Database $database does not have a connection.");
    }
    foreach($keys as $key){
	$safe_keys[] = "`".$key."`";
    }
    foreach($values as $value){
	$safe_values[] = "'".escape($value)."'";
    }
    $sql = "INSERT INTO `$table` (".implode(",", $safe_keys).") VALUES (".implode(",", $safe_values).")";
    return(db_query($sql));
}

/** 
 * Wrapper function to allow code not to have to deal with
 * pear DBs autoprepare.  This way i can avoide using the $db global
 */

function auto_prepare_queries($field_array ,$service, $database = "roster"){
    global $db;
    $handler = $db[$database]->autoPrepare($field_array{$service}['table'], $field_array{$service}{'fields'}, DB_AUTOQUERY_INSERT);
    if (DB::isError($var))
            die($var->getMessage());

    return $handler;
}

function preped_execute($sth, $table_values, $database = "roster"){
    global $db;
    $res =& $db[$database]->execute($sth, $table_values);
    if (DB::isError($res)){
	echo 'Standard Message: ' . $res->getMessage() . "<br><br>\n";
	echo 'Standard Code: ' . $res->getCode() . "<br><br>\n";
	echo 'DBMS/User Message: ' . $res->getUserInfo() . "<br><br>\n";
	echo 'DBMS/Debug Message: ' . $res->getDebugInfo() . "<br><br>\n";
	exit;
    } 

    return $res;
}

/**
 * This is the beginnings of a function to make stuff safe for the DB.
 * Right now, it simply calls the quoteSmart function from Pear::DB.
 * @var string String to be "made safe"
 * @return string Safe version of input
 */
function make_db_safe($string){
    global $db;
    return($db['roster']->quoteSmart($string));
}

/**
 * Take a string and try to make it "database safe" by escaping it
 * This is basically taken from the old wowdb code of R1.6
 * 
 * @param string $string
 * @return string 
 */
function escape( $string )
{
    static $has_real;
    if(!isset($has_real)){
	$has_real = version_compare( phpversion(), '4.3.0', '>');
    }
    if( $has_real )
      return mysql_real_escape_string( $string );
    else
      return mysql_escape_string( $string );
}

?>
