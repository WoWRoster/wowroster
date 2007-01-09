<?
/* 
* $Date: 2006/06/28 19:40:53 $ 
* $Revision: 0.4.2 $ 
*/ 

ob_start();
session_start();
	if ($_SESSION[s_id]!=session_id()){
		session_destroy();
		header("Location:index.php");
		exit();}
require('../../settings.php');                  // "settings.php" from WoWRoster		
require_once 'conf.php';
include('localization.php');
include('zip.inc.php');
include('functions_cart.php');
include('roster_header.tpl');


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phpCart</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="./css/styles.css">
<link rel="stylesheet" type="text/css" href="default.css">
<!--<link href="phpCart_style.css" rel="stylesheet" type="text/css">-->
</head>

<body>
				<form name="update" method="post" action="phpCart_manage.php">
				<!--<table width="800" cellpadding="0" cellspacing="0">
				<th colspan="7" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th>
				<tr><th width="15%" class="rankbordercenterleft"><div class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['delete']?></div></th>		
				<th width="20%" class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['quantity']?></th>
				<th colspan="4" width="100%" class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['product'] ?></th>
				<th width="80" class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['maker'] ?></th>
				<th class='rankbordercenterright'></th></tr>-->
				
				<?php echo border('syellow','start',$wordings[$roster_conf['roster_lang']]['shopping']); ?>
				<table width="800" cellpadding="0" cellspacing="0">
				<th colspan="5"><span></span><span></span></th>
				<tr><th width="60"><div class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['delete']?></div></th>		
				<th width="60" class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['quantity']?></th>
				<th width="500" class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['product'] ?></th>
				<th width="180" class="membersHeadercenter"><?php echo $wordings[$roster_conf['roster_lang']]['maker'] ?></th>
				<th></th></tr>
				
<?

$itemcount = 0;
// If no sessions has been started $_SESSION["cart"] equals null, thus showing the message no items.
if (!isset($_SESSION["cart"])) {
	$_SESSION["cart"] = NULL;
}

if (validate() == TRUE && $_SESSION["cart"] != NULL) {
					
	foreach ($_SESSION["cart"] as $key => $session_data) {
		
		list($ses_id, $ses_quan) = $session_data;
			// call database connect function
			db_connect();
			$sel_products = mysql_query("SELECT * FROM $mysql_tablename WHERE recipe_name like '".$ses_id."'");
			$item = mysql_fetch_array($sel_products);
			//create Dropdownlist for makers
			$make_string = strtr($session_data[2],'\"',"");
			$makers = explode(",",$make_string);
			$selected = 0;
			//var_dump($_SESSION["cart"]);
			if ($_SESSION["cart"][$itemcount][3] != NULL){
				$selected = $_SESSION["cart"][$itemcount][3];
				}

	?>
				<tr>
				<td align="center"><div class="deleteHeadercenter"><a href="<? echo "phpCart_manage.php?act=del&pid=".$itemcount; ?>"><img src="img/icon_del.gif" width="13" height="13" align="bottom" border="0"></a></div></td>
				<td class="membersHeadercenter"><input name="newquan[]" type="text" id="newquan[]3" value="<? echo $ses_quan; ?>" size="5" maxlength="4">
				<input name="eid[]" type="hidden" id="eid[]" value="<? echo $itemcount; ?>"></td>
				<td class="product"><? echo $item["recipe_name"]; ?></td>
				<td><?php print createOptionListValue($makers,$selected,'maker_wahl'.$itemcount++ ); ?></td>
				<td></td>
				<!--<td colspan="4" width="100%" class="rankbordercenterright">&nbsp;</td></td>-->
				
				</tr>
	<?php
	} // end foreach loop
	
}
elseif ($_SESSION["cart"] == NULL && $_SESSION["order_state"] == null){
	echo "<td align='center' colspan=\"4\"><div class='membersRow1'><center><p>".$wordings[$roster_conf['roster_lang']]['emptybasket']."</p></center></div></td><td>";
}
elseif ($_SESSION["cart"] == NULL && $_SESSION["order_state"] == "outbox") {
	echo "<td align='center' colspan=\"4\"><div class='membersRow1'><center><p>".$wordings[$roster_conf['roster_lang']]['ordersend']."</p></center></div></td><td>";
}
else {
	
	echo "<td align='center' colspan=\"4\"><div class='membersRow1'><center><p>".$wordings[$roster_conf['roster_lang']]['error']."</p></center></div></td><td>";

}
	?>
	<tr><td colspan="6"><span></span><span></span><br></td></tr>
	<?php 
	@$sendto;
	if (@$_SESSION["sendto"] != NULL ){
	    $sendto = @$_SESSION["sendto"];
	}
	else{
	    $sendto = $_SESSION["members"][0];
	}
	?>
	<table width="800" cellpadding="0" cellspacing="0">
	<tr>
	    <td align="center"> <img src="img/icon_del.gif" width="13" height="13"> - <?php echo $wordings[$roster_conf['roster_lang']]['delete'] ?></td>
	    <td><? if ($_SESSION["cart"] != NULL) { echo "<input name=\"UpdateChg\" type=\"submit\" id=\"UpdateChg\" value=\"Update\">"; } ?></td>
	    <td align="center"><a href="../../addon.php?roster_addon_name=shopping"><?php echo $wordings[$roster_conf['roster_lang']]['continue'] ?></a></td>
	</tr>
	<tr></tr>
	<tr></tr>
	<tr>
	    <td colspan="3"><br><br><div align="center"><?php echo $wordings[$roster_conf['roster_lang']]['sendto']; print createOptionListValue($_SESSION["members"],$sendto,"members"); ?></div></td>
	</tr>
	<?php
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
	$path = $roster_conf['roster_dir']."/addons/shopping/save/Shopping_list.lua";

	?>
				
	<tr>
	    <td colspan="3" align="center"><br>
	    <input name="Bestellen" type="submit" id="Bestellen" value="<?php echo $wordings[$roster_conf['roster_lang']]['order'] ?>">
	    </td>
	</tr>
	<tr>
	    <td>
	        <br>
	    </td>
	</tr>
    </table>
    </form>
<?php echo border('syellow','end'); ?>
</body>
</html>
<?
ob_end_flush();
?>