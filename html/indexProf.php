<?php require_once 'conf.php'; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>[<?php print $guild_name; ?> Roster] <?php print $wordings[$roster_lang]['professions'] ?></title>
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet1 ?>">
</head>
<body>
	
<?php require 'ProfList.php'; ?>

</body>
</html>