<?
/* 
* $Date: 2006/07/04 17:45:26 $ 
* $Revision: 0.4.2 $ 
*/

include ($addonDir.'conf.php');
require_once ($addonDir.'zip.inc.php');
session_start();
	if ($_SESSION[s_id]!=session_id()){
		session_destroy();
		header("Location:index.php");
		exit();}

error_reporting( E_ALL );

//edit the follwoing lines
$mysql_server = "localhost";
$mysql_username = "localdef";
$mysql_pwd = "defence";
$mysql_dbname = "localdefence";
$table_prefix = "cms_wowrosterdf_roster_";		//chang to your prefix

//no need to edit :)
$mysql_tablename = $table_prefix."recipes";


// database connect function
function db_connect () {

		global $mysql_server, $mysql_username, $mysql_pwd, $mysql_dbname;
		
		$db = mysql_connect($mysql_server, $mysql_username, $mysql_pwd, false, 128) or die("Problem connecting");	
		mysql_select_db($mysql_dbname,$db) or die("Problem selecting database");
	
	}
	
	
// generate random string for cookie and session
function setstp () {

		settype($str,"string");
		
		// generate random number
		for ($i=0;$i<20;$i++) {
		
		  $str .= chr (rand (1, 255));
		
		}
		
		// encode string to 40 characters.
		$sha = sha1 ($str);
		// set cookie with value and set session with the same value.
		setcookie ("SESSSEC", $sha, NULL);
		$_SESSION["CookieChk"]['SESSSEC'] = $sha;
			
	}


// add item to cart
function add_item_to_cart($id,$quantity,$make) {
		
		
		$item_count = 0;
		if(isset($_SESSION["itemcount"])) {
			
			$item_count = ($_SESSION["itemcount"]);
			$_SESSION["itemcount"] = $_SESSION["itemcount"]+1;
			writeLog("Es sind ".$_SESSION["itemcount"]." Waren im Korb (in der Schleife)");
			}
		else {
			$_SESSION["itemcount"] = 1;
			}
        // set cookie and store value in session
		setstp();	
		// call database connect function
		db_connect();
		// get product id from database
		global $mysql_tablename;
		$id = str_replace("\\", "", $id);
		$sel_products = mysql_query("SELECT * FROM $mysql_tablename WHERE recipe_name like '".$id."'");
		$item = mysql_fetch_array($sel_products);
		// returns the number of rows in a result, if 1 item exists if 0 item doesn't exists.
		$num_rows = mysql_num_rows($sel_products);
				
		// if item exists then add item to cart
		if ($num_rows >= 1) {
		
		$make = str_replace("\\", "", $make);
		$make = str_replace("\"", "", $make);
	
		//fill the session
		$_SESSION["cart"][$item_count][0] = $item["recipe_name"];
		$_SESSION["cart"][$item_count][1] = $quantity;
		$_SESSION["cart"][$item_count][2] = $make;
		$_SESSION["cart"][$item_count][3] = '0';
		mysql_free_result($sel_products);
		header ("location:".$_SERVER['HTTP_REFERER']);
		
		}
	}


// check cookie and session and then show cart
function validate() {
			
		if (!isset($_COOKIE['SESSSEC'])) {
			$valid = FALSE;
		// probable attempt at Session Fixation, you should probably log this
		} elseif (!isset($_SESSION["CookieChk"]['SESSSEC'])) {
			$valid = FALSE;
		// umm, this shouldn't occur, but yeah, do whatever you want, maybe log an error or something, probably not needed except to notice bugs in your app....
		} elseif ($_COOKIE["SESSSEC"] == $_SESSION["CookieChk"]['SESSSEC']) {			
			$valid = TRUE;
			setstp();
		} else {
			$valid = FALSE;
		// very Proably attempt at session hijacking, because while both items exist they don't match, definately log this
		} 
		return $valid;
	}
	

// delete item from cart
function del_item($id) {
		
		// call database connect function
		db_connect();
		global $mysql_tablename;
		$id = str_replace("\\", "", $id);
		$sel_products = mysql_query("SELECT * FROM $mysql_tablename WHERE recipe_name like '".$id."'");
		$item = mysql_fetch_array($sel_products);
		//unset item in array and gibe new index	
		unset($_SESSION["cart"][$id]);
		$_SESSION["itemcount"]--;
		$_SESSION["cart"] = array_values($_SESSION["cart"]); 
				
		header ("location:".$_SERVER['HTTP_REFERER']);
	}
	
	
/* ----[ Create an HTML option list ]-----------------------
	return string ( an html option list )
	arguments
	$values = array with $values
	$selected = what will be selected
	$select_field = what to match selected with
*/
	function createOptionListValue( $values , $selected , $id )
	{
		if( !empty($selected) ){
			$select_one = 1;}
		else $select_one = 0;
		

		$option_list = "\n  <select class=\"list\" name=\"{$id}\" onChange=\"this.form.submit()\">\n    ";


		foreach( $values as $name => $value )
		{
			if( $selected == $value && $select_one )
			{
				$option_list .= "    <option value=\"{$value}\" selected=\"selected\">{$value}</option>\n";
				$select_one = 0;
			}
			else
			{
				$option_list .= "    <option value=\"{$value}\">{$value}</option>\n";
			}
		}
		$option_list .= "  </select>";

		return $option_list;
	}
	
	//change the Member ID to Member-Name
	function changeNumbertoMember()
			{
			$i = 0;
			$size = count($_SESSION["cart"]);
			for ($i = 0; $i <= $size-1; $i++) {
				if(is_numeric($_SESSION["cart"][$i][3])){
					$membername = explode(",",$_SESSION["cart"][$i][3]);
					$_SESSION["cart"][$i][3] = $membername[($_SESSION["cart"][$i][3])];
					}
				}			
			}
	
	//this is for debuggibg set $display_logfile = 1 in conf.php and this will write a shopping.log in save folder
	function writeLog($content){
		global $display_logfile;
		if ($display_logfile){
			$datei = fopen ("save/shopping.log", "a");
			if (!$datei){
				echo "<p>Datei konnte zum Schreiben nicht geöffnet werden.\n";
				exit;
			}
			fwrite ($datei, getTime().$content."\n");
			fclose($datei);
			}
		}

	function getTime(){
		setlocale (LC_TIME, "deDE");
		$date = strftime("%d.%m.%Y  %H.%M:%S   ");
		return $date;
	
		}
	
	function createZIP($content){
	    $zipfile = new zipfile();
	     
	    // add the binary data stored in the string 'filedata'
	    $zipfile -> add_file($content, 'Shopping_list.lua');	    
	    
	    // write out the ZIP file
	    $zipfilename = "save/Shopping_list.zip"; //Path für die ZIP-Datei
	    $fd = fopen ($zipfilename, "wb");
	    $out = fwrite ($fd, $zipfile -> file());
	    fclose ($fd); 	 
	    
	    
	    $mime_type = 'application/x-zip';
	    header("Content-Type: application/x-zip");
	    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	    if (getenv("HTTP_USER_AGENT") == 'IE') {
		header("Content-Disposition: inline; filename=\"Shopping_list.lua\"");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
	    }
	    else {
		header("Content-Disposition: attachment; filename=\"Shopping_list.lua\"");
		header("Pragma: no-cache");
		}
	    
	    $host  = $_SERVER['HTTP_HOST'];
	    $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	    $extra = 'save/Shopping_list.zip';
	    header("Location: http://$host$uri/$extra");
			 
	}
	
	function insertOrder(){
	    db_connect();
	    global $table_prefix;
	    $i=0;
	    $size = count($_SESSION["cart"]);
	    for($i=0; $i <= $size-1; $i++){
	 
		//check if maker is more than two members (workaround needs to be changed for future)
		$string = explode(",",$_SESSION["cart"][$i][3]); // changed (thx  Rihlsul)
		$num = count($string);
		
		if($num > 1){
		    $_SESSION["cart"][$i][3] = $string[0];
		}
		
		mysql_query("INSERT INTO `".$table_prefix."addon_shopping` (`order_number`, `order_item`, `order_quantity`,
		    `order_maker`, `order_requester`, `order_maxprice`, `order_note`,
		    `order_price_demand`, `order_date`, `order_state`, `order_info`)
		    VALUES ('','".$_SESSION["cart"][$i][0]."','".$_SESSION["cart"][$i][1]."','".$_SESSION["cart"][$i][3]."','".$_SESSION["sendto"]."',
		    '0', '', '', NOW(), 'outbox', '');");
		    
		    } 
	}	
//ob_end_flush();
?>