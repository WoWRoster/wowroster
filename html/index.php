<?php
$versions['versionDate']['index'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['index'] = '$Revision: 1.7 $'; 
$versions['versionAuthor']['index'] = '$Author: mordon $';

require_once 'conf.php';
include 'roster_header.tpl';
?>
            <a href="#update"><font size="4"><?php echo ($update_link[$roster_lang]); ?></font></a><br><br>

<?php require 'membersList.php'; ?>

          </td>
        </tr>
        <tr>
          <td>
            <hr>
            <a name="update"></a>

<?php
echo $update_instruct[$roster_lang];

if ($show_pvplist == 1)
	echo $update_instructpvp[$roster_lang];

include 'roster_footer.tpl';
?>