<?php
require_once 'conf.php';
$name = $_REQUEST['name'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>[<?php print $guild_name; ?> Roster] Character Stats for: <?php print $name ?></title>
	<link rel="stylesheet" type="text/css" href="<?php print $stylesheet ?>">
	<link rel="stylesheet" type="text/css" href="<?php print $stylesheet1 ?>">
	<link rel="stylesheet" type="text/css" href="<?php print $stylesheet3 ?>">
	<script type="text/javascript" src="<?php print $overlib ?>"></script>
	<script type="text/javascript" src="<?php print $profile ?>"></script>
	<style type="text/css">
		/* This is the border line & background colour round the entire page */
		.bodyline { background-color: #000000; border: 1px #212121 solid; }
	</style>
</head>
<body>
<div align="center">
<table border="0" cellpadding="0" cellspacing="0">

<?php
if($show_charPageLogo) {
	print '<tr><td valign="top" align="center">
    <a href="'.$website_address.'"><img src="'.$logo.'" alt="" border="0"></a>
    <br><br>
  </td></tr>';
}

include 'memberDetails.php';
?>
</div>
		</td>
	</tr>
</table>
</body>
</html>