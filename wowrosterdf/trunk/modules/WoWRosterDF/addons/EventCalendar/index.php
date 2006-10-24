<?php
$versions['versionDate']['eventcalendar'] = '$Date: 2006/08/28 $'; 
$versions['versionRev']['eventcalendar'] = '$Revision: 1.0 $';
$versions['versionAuthor']['eventcalendar'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }
require_once('/var/www/vhosts/localdefence.net/httpdocs/modules/wowrosterdf/addons/EventCalendar/functions.php');

//$event = $_REQUEST['event'];
if(isset($_REQUEST['event']))
{
        $event = $_REQUEST['event'];
}
else {

        $event = '';
}

// Check if tables already excists
//if(!$wowdb->query("SELECT * FROM ".$db_prefix."events")){
	//require('/var/www/vhosts/localdefence.net/httpdocs/modules/wowrosterdf/addons/EventCalendar/install_db.php');
//}
//else{
	if($display == '')
	{
		if($event != '')
		{
			require 'eventview.php';	
		}else{
			require 'eventlist.php';
		}
	}
	// Addon version check
	if($display == 'versioncheck')
	{
		require 'addon_versioncheck.php';
	}
//}

?>