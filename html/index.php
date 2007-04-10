<?php require 'conf.php'; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>[<?php print $guild_name; ?> Roster]</title>
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet2 ?>">
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet1 ?>">
<style type="text/css"> 
/* This is the border line & background colour round the entire page */
.bodyline       { background-color: #000000; border: 1px #212121 solid; }
</style>
</head>
<body>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
  <tr><td align="center">
  	<table border=0 cellpadding=8 cellspacing=0 width="100%">
  		<tr><td width="100%" class="bodyline">
  			<table border=0 cellpadding=0 cellspacing=0 width="100%">
  				<tr><td align="center" width="100%" class="bodyline">
  					<a href="<?php print $website_address ?>"><img src="<?php print $logo ?>" alt="" border="0"></a><br>
  				</td>
  				<tr><td align="center">
  					<br><font color="#FFFFFF">WoW Roster Version <?php print $version ?></font></b><br><br>
  					<a href="#update"><font size="4"><?php echo ($update_link[$roster_lang]); ?></font></a><br><br>


<?php require 'membersList.php'; ?>

					</td></tr>
					<tr><td>
						<hr>
						<a name="update"></a>

<?php echo $update_instruct[$roster_lang]; ?>
<?php if ($show_pvplist == 1){ 
echo $update_instructpvp[$roster_lang];
} ?>
						<hr><small>
	
<?php echo $credits[$roster_lang];?>

						</small>
					</td></tr>
				</table>
			</td></tr>
		</table>
	</td></tr>
</table>
</body>
</html>