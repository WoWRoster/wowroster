<?php require_once 'conf.php'; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>[<?php print $guild_name; ?> Roster]  <?php print $wordings[$roster_lang]['pvplist'] ?></title>
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet2 ?>">
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet1 ?>">
</head>
<body>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center">
<table border=0 cellpadding=8 cellspacing=0 width="1000">
<tr><td width="1000" class="bodyline">
<table border=0 cellpadding=0 cellspacing=0 width="1000">
<tr><td width="1000" class="bodyline">
<p align="center"><a href="<?php print $website_address ?>">
<img src="<?php print $logo ?>" alt="" border="0"></a><br>
</td>
<tr><td align="center">
<br><br>

<?php require 'guild-pvp.php'; ?>

<br><br><br><br>
</td></tr>
</table>
</td></tr></table>
</td></tr></table>
</body>
</html>