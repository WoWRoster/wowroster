<?php
$versions['versionDate']['eventcalendar'] = '$Date: 2006/08/28 $'; 
$versions['versionRev']['eventcalendar'] = '$Revision: 1.0 $'; 
$versions['versionAuthor']['eventcalendar'] = '$Author: PoloDude $';



global $ec_wordings, $roster_conf;

require_once BASEDIR.'modules/'.$module_name.'/lib/wowdb.php';

include("event_info.php");

echo '<br/>';

include("event_attendance.php");

?>