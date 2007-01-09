<?php
session_start();
$_SESSION[s_id]=session_id();

/* 
* $Date: 2006/07/02 17:45:26 $ 
* $Revision: 0.4.2 $ 
*/ 
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}
require_once( ROSTER_BASE.'lib'.DIR_SEP.'luaparser.php' );

if ($_REQUEST['OPERATION']=="GETDATA"){
    $roster_show_header = false;  // Turn off roster header
    $roster_show_menu = false;    // Turn off roster menu
    $roster_show_footer = false;  // Turn off roster footer 
    sendData();
}
elseif( substr($_SERVER['HTTP_USER_AGENT'], 0, 11) == 'UniUploader'){
    $roster_show_header = false;  // Turn off roster header
    $roster_show_menu = false;    // Turn off roster menu
    $roster_show_footer = false;  // Turn off roster footer
    // Files that we accept for upload
    $filefields[] = 'Shopping.lua';
    
    // Loop through each posted file
    foreach ($_FILES as $filefield => $file)
    {
            $filename = $file['tmp_name'];
            $filemode = '';
		    
            if( substr_count($file['name'],'.gz') > 0 )     // If the file is gzipped
                    $filemode = 'gz';
		    
            foreach( $filefields as $acceptedfile ) // Itterate through all the possible filefields
            {
                    if( $acceptedfile == $file['name'] || $acceptedfile.'.gz' == $file['name'] )
                    {
                        // Filefield is 1 of the kind we accept.
                        if( $roster_conf['authenticated_user'] )
                        {
                            $uploadFound = true;
                            // Parse the lua file into a php array that we can use
                            $data = ParseLuaArray(file($filename));
			            }
                    }
            }
            @unlink($filename);     // Done with the file, we don't need it anymore
    }
																																																																																									    
    filetoDB( $data );
}
else{
    $header_title = $wordings[$roster_conf['roster_lang']]['shopping'];
    
    require 'shoppingList.php'; 
    echo $content;
}


$request = '';

if (!isset ($_REQUEST)){
    echo "NO REQUEST SET! NOTHING TO BE DONE...";
}
else {
$request = $_REQUEST;
}
	 
function sendData(){
     $order = getOrder();
     echo $order;
     exit; //Very important cause if commented UU retrieves Data twice... don´t know why :)
}
							 
function getOrder(){
    global $wowdb;
    global $db_prefix;
    $sel_order = $wowdb->query("SELECT * FROM ".$db_prefix."addon_shopping WHERE order_state ='outbox'");
    $list = "shoppinglist = {\n\t";
    $counter = 1;
           
    //Columns
    // 0 = order_maker
    // 1 = order_quantity
    // 2 = order_item
    // 3 = order_requester 
    // 4 = order_number
    // 5 = order_state
    // 6 = order_maxprice
    // 7 = order_note
    while ($row = $wowdb->getrow($sel_order)){
	if($counter == 1){	
	    $list .= "[".$counter."] = {\n\t\t";
	}
	else{
	    $list .= "\n\t[".$counter."] = {\n\t\t";
	}
	$list .= "[1] = \"".$row["order_maker"]."\",\n\t\t";
	$list .= "[2] = \"".$row["order_quantity"]."\",\n\t\t";
	$list .= "[3] = \"".$row["order_item"]."\",\n\t\t";
	$list .= "[4] = \"".$row["order_requester"]."\",\n\t\t";
	$list .= "[5] = \"".$row["order_number"]."\",\n\t\t";
	$list .= "[6] = \"".$row["order_state"]."\",\n\t\t";
	$list .= "[7] = \"".$row["order_maxprice"]."\",\n\t\t";
	$list .= "[8] = \"".$row["order_note"]."\",\n\t},";
	
	$counter++;
    }
		
    $list .= "\n}";
	    
    return $list;
}

function filetoDB( $list ){
    global $wowdb;
    global $db_prefix;
    $orders = count($list['shoppinglist']);
    $sql_state = '';
    for($i=1;$i<=$orders;$i++){
	$sql_state = $wowdb->query("UPDATE ".$db_prefix."addon_shopping SET order_state ='".$list['shoppinglist'][$i][6]."' WHERE order_number ='".$list['shoppinglist'][$i][5]."';");
	//echo "UPDATE ".$db_prefix."addon_shopping SET order_state ='".$list['shoppinglist'][$i][6]."' WHERE order_number ='".$list['shoppinglist'][$i][5]."';";
	
    }
    //echo "\n\nTest:".$list['shoppinglist'][1][6];
    //echo "\n".$sql_state."\n\n";
    //var_dump($list); //for testing output
}
					
?>