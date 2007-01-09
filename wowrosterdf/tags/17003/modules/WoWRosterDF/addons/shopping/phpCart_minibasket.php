<?php
/* 
* $Date: 2006/06/28 21:40:53 $ 
* $Revision: 0.4.2 $ 
*/ 
session_start();
	if ($_SESSION[s_id]!=session_id()){
		session_destroy();
		header("Location:index.php");
		exit();}
include('localization.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="./css/styles.css">
<link rel="stylesheet1" type="text/css" href="default.css">
</head>

<body>
<br>
<?php echo border('syellow','start',$wordings[$roster_conf['roster_lang']]['shopping']); ?>
<table cellpadding="0" cellspacing="0" width="400" "class="header">
  <td colspan="2"></td></tr>
    
  <tr>
    <?
	// If no sessions has been started $_SESSION["cart"] equals null, thus showing the message no items.
	if (!isset($_SESSION["cart"])) {
		$_SESSION["cart"] = NULL;
	}
	
	$itemcount = count($_SESSION["cart"]);
	echo ("<td><div class='headerrow'>".$wordings[$roster_conf['roster_lang']]['youhave']." ".$itemcount." ".$wordings[$roster_conf['roster_lang']]['inbasket']."</div></td>");
	echo ("<td></td>");
	?>
	
  </tr>
  <tr>
    <td><div class='headerrow'><a href="addons/shopping/phpCart_basket.php"><?php echo $wordings[$roster_conf['roster_lang']]['shoppingbasket']; ?></div></a></td>
	<td></td>
  </tr>
  <tr><th colspan="6"></th></tr>
</table>
<?php echo border('syellow','end'); ?>
<br>
</body>
</html>
