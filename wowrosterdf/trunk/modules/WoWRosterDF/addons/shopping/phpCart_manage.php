<?
/* 
* $Date: 2006/06/28 19:50:53 $ 
* $Revision: 0.4.2 $ 
*/ 
session_start();
	if ($_SESSION[s_id]!=session_id()){
		session_destroy();
		header("Location:index.php");
		exit();}
		include "functions_cart.php";
	//	include "zip.inc.php";

if (isset($_POST["UpdateChg"]) || isset($_POST["maker_wahl0"]) || isset($_POST["members"]) && isset($_SESSION["cart"])) {
	$_SESSION["sendto"] = $_POST["members"];
		$i = 0;
		$size = count($_POST["eid"]);
		$del_items = 0;

		for ($i = 0; $i <= $size-1; $i++) {
			$_SESSION["cart"][$i][3] = $_POST["maker_wahl".$i];
						
			// call remove bad characters function
			$badsymbols = array(" ","-","+","*","/",".");
			$_POST["newquan"][$i] = str_replace($badsymbols,"", $_POST["newquan"][$i]);
		
			if (is_numeric($_POST["newquan"][$i])) {
				// if any quantity's equal 0 then remove from cart
				if ($_POST["newquan"][$i] == 0) {
					unset($_SESSION["cart"][$i-$del_items]);
					$_SESSION["itemcount"]--;
				}
				// update quantity in cart.
				if (array_key_exists($_POST["eid"][$i], $_SESSION["cart"])) {
					$_SESSION["cart"][$i][1] = $_POST["newquan"][$i];			
				}
			} // END IF NUMERIC
		}
		$_SESSION["cart"] = array_values($_SESSION["cart"]);
		
		//Testvariable in Session
		if(isset($_POST["maker_wahl"])){
		$_SESSION["test"] = $_POST["maker_wahl"];
		}
		header ("location:".$_SERVER['HTTP_REFERER']);
	
} // END BASKET QUANTITY
elseif (isset($_POST["UpdateChg"]) || isset($_POST["maker_wahl0"]) || isset($_POST["members"]) && !isset($_SESSION["cart"])) {
		header ("location:".$_SERVER['HTTP_REFERER']);
}
// TEXT LINKS
if (isset($_GET["act"])) {

	// ADD ITEM!
	if ($_GET["act"] == "add") {
		$pid = str_replace("\\", "", $_GET["pid"]);
		$pid = str_replace("\"", "", $pid);
		$i = 0;
		$item_exists=0;
		$pointer =0;
		$size = count($_SESSION["cart"]);
		for ($i = 0; $i <= $size-1; $i++) {
			if($_SESSION["cart"][$i][0] == $pid)
				{$item_exists=1;$pointer = $i;}
			}
		
		if (!isset($_SESSION["cart"])) {
			// add first item
			add_item_to_cart($pid,1,$_GET["make"]);

		} else if ($item_exists) {
			// add 1 to quantity if item in cart already
			$_SESSION["cart"][$pointer][1]+= 1;
			header ("location:".$_SERVER['HTTP_REFERER']);
			
		} else {
			// add any other items after first item
			add_item_to_cart($pid,1,$_GET["make"]);
			
		}
		
	}	
	
	// DELETE ITEM!
	if ($_GET["act"] == "del") {
		del_item($_GET["pid"]);
	}

} // END ISSET

if (isset($_REQUEST["Bestellen"]) && isset($_SESSION["cart"])){
    changeNumbertoMember();
    if(!isset($_SESSION["sendto"])) {
       $_SESSION["sendto"] = $_SESSION["members"][0];
           }
    $datei = fopen ("save/Shopping_list.lua", "w");
    if (!$datei){
       echo "<p>Datei konnte zum Schreiben nicht geöffnet werden.\n";
       exit;
       }
    $content = '';
    $i = 0;
    $size = count($_SESSION["cart"]);
    $content .= "shoppinglist = {\n";
    for ($i = 0; $i <= $size-1; $i++) {
	$content .= "\t{ \"".$_SESSION["cart"][$i][3]."\", ".$_SESSION["cart"][$i][1].", \"".$_SESSION["cart"][$i][0]."\", \"".$_SESSION["sendto"]."\",},\n";
              }
    $content .= "}";
    fwrite ($datei, $content);
    fclose($datei);
    $path = "/addons/shopping/save/Shopping_list.lua";
       
    if($useUU ==0){
        createZIP($content);
    }
    else{
	insertOrder();
	$_SESSION["order_state"] = "outbox";
	unset($_SESSION["cart"]);
	unset($_SESSION["itemcount"]);
	header ("location:".$_SERVER['HTTP_REFERER']);
	
    }
    
}
?>
</body>
</html>
<?
//ob_end_flush();
?>