<?php
$versions['versionDate']['credits'] = '$Date: 2005/12/30 20:40:52 $'; 
$versions['versionRev']['credits'] = '$Revision: 1.6 $'; 
$versions['versionAuthor']['credits'] = '$Author: mordon $';

require_once 'conf.php';

$header_title = $wordings[$roster_lang]['credit'];
include 'roster_header.tpl';

// Establish our connection and select our database
$link = mysql_connect( $db_host, $db_user, $db_passwd ) or die( "Could not connect to desired database. <a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>" );
mysql_select_db( $db_name ) or die( "Could not select desired database. <a href=\"docs/\" target=\"_new\">Click here for installation instructions.</a>" );

include_once 'lib/menu.php';
?>

</td>
        </tr>
        <tr>
          <td align="left"><br />  
<?php echo $creditspage[$roster_lang];

include 'roster_footer.tpl';
?>