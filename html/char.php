<?php
$versions['versionDate']['char'] = '$Date: 2006/02/03 23:38:52 $'; 
$versions['versionRev']['char'] = '$Revision: 1.9 $'; 
$versions['versionAuthor']['char'] = '$Author: zanix $';

require_once 'conf.php';

if( isset($_GET['name']) )
	$name = $_GET['name'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>[<?php echo $guild_name; ?> Roster] Character Stats for: <?php echo $name ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo $char_style ?>">
  <script type="text/javascript" src="<?php echo $overlib ?>"></script>
  <script type="text/javascript" src="<?php echo $profile ?>"></script>
</head>
<body>
<?php

if($show_charPageLogo)
	echo '
  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="bodyline">
    <tr>
      <td valign="top" align="center"><a href="'.$website_address.'">
        <img src="'.$logo.'" alt="" border="0"></a></td>
    </tr>
  </table>
';
else
	echo '
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
';

echo "<br /><br />\n";

include 'memberDetails.php';

?>
</body>
</html>