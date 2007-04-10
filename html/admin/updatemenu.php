
<!-- Begin Roster Menu for update.php -->
<table class="bodyline" cellspacing="1" cellpadding="2">
  <tr class="membersRow1">
    <td>
      <span style="font-size:14px;"><a href="<?php print $website_address.'">'.$guild_name.'</a></span>
      <span style="font-size:10px;color:#FFFFFF;"> of '.$server_name." ($server_type)"; ?></span></td>
  </tr>
  <tr class="membersRow1">
    <td align="center"><span style="font-size:12px;color:#FFFFFF;">
      <a href="<?php print $roster_dir ?>/index.php"><?php print $wordings[$roster_lang]['roster'] ?></a> - 
      <a href="<?php print $roster_dir ?>/index.php?s=class"><?php print $wordings[$roster_lang]['byclass'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexAlt.php"><?php print $wordings[$roster_lang]['alternate'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexStat.php"><?php print $wordings[$roster_lang]['menustats'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexHonor.php"><?php print $wordings[$roster_lang]['menuhonor'] ?></a> - 
      <a href="<?php print $roster_dir ?>/tradeskills.php"><?php print $wordings[$roster_lang]['professions'] ?></a><br />
      <a href="<?php print $roster_dir ?>/indexInst.php"><?php print $wordings[$roster_lang]['keys'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexquests.php"><?php print $wordings[$roster_lang]['team'] ?></a> - 
<?php

if ($show_guildbank)
print '      <a href="'.$roster_dir.'/guildbank'.$guildbank_ver.'.php">'.$wordings[$roster_lang]['guildbank'].'</a> - 
';

if ($show_raid)
print '      <a href="'.$roster_dir.'/indexRaid.php">'.$wordings[$roster_lang]['raid'].'</a> - 
';

?>
        <a href="<?php print $roster_dir ?>/indexSearch.php"><?php print $wordings[$roster_lang]['search'] ?></a> - 
        <a href="<?php print $roster_dir ?>/credits.php"><?php print $wordings[$roster_lang]['credit'] ?></a> - 
        <a href="<?php print $roster_dir ?>/admin/update.php"><?php print $wordings[$roster_lang]['upprofile'] ?></a></span></td>
  </tr>
</table>
<br />
<!-- End Roster Menu for update.php -->