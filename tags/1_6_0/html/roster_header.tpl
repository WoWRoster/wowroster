<?php 
$versions['versionDate']['rosterheader'] = '$Date: 2006/02/03 23:38:52 $'; 
$versions['versionRev']['rosterheader'] = '$Revision: 1.11 $'; 
$versions['versionAuthor']['rosterheader'] = '$Author: zanix $';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>[<?php print $guild_name; ?> Roster] <?php print $header_title; ?></title>
  <link rel="stylesheet" type="text/css" href="<?php print $roster_dir ?>/<?php print $stylesheet2 ?>">
  <link rel="stylesheet" type="text/css" href="<?php print $roster_dir ?>/<?php print $stylesheet1 ?>">
<?php print $more_css; ?>
  <script type="text/javascript" src="<?php print $roster_dir ?>/<?php print $overlib; ?>"></script>
</head>
<body>
<table border="0" cellpadding="8" cellspacing="0" width="100%">
  <tr>
    <td width="100%" class="bodyline">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td align="center" width="100%" class="bodyline"><a href="<?php print $website_address ?>">
            <img src="<?php print $roster_dir ?>/<?php print $logo ?>" alt="" border="0"></a><br /></td>
        </tr>
        <tr>
          <td align="center"><br />
            <b><font color="#FFFFFF">WoW Roster Version <?php print $version ?></font></b>
            <br /><br />
<!-- End Roster Header -->
