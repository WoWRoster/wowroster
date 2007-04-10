<?php
$versions['versionDate']['addon'] = '$Date: 2006/01/11 19:45:26 $'; 
$versions['versionRev']['addon'] = '$Revision: 1.8 $'; 
$versions['versionAuthor']['addon'] = '$Author: podunk $';

include 'conf.php';
require_once('.'.$sep.'lib'.$sep.'wowdb.php');  // roster wowdb
$sep = DIRECTORY_SEPARATOR;
$link = mysql_connect($db_host, $db_user, $db_passwd) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not connect");
mysql_select_db($db_name) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not select DB");
$addon = '.'.$sep.'addons'.$sep.$_REQUEST['roster_addon_name'].$sep."index.php";
$addonDir = '.'.$sep.'addons'.$sep.$_REQUEST['roster_addon_name'];
$cssFile = $addonDir.$sep.$roster_conf['template'].'.css';
$localizationFile = $addonDir.$sep.'localization.php';
$configFile = $addonDir.$sep.'conf.php';
$css = '';
//make the header/menu/footer show by default
$roster_show_header = true;
$roster_show_menu = true;
$roster_show_footer = true;


if (file_exists($addon))
{
	//set the css for the template set in conf.php
	if (file_exists($cssFile))
	{
		$lines = file($cssFile);
		foreach ($lines as $line) {
			$css .= $line;
		}
	}
	//set localization variables
	if(file_exists($localizationFile))
	{
		include($localizationFile);
	}
	//set addon specific conf.php settings
	if(file_exists($configFile))
	{
		include($configFile);
	}
	//addon will now assign its output to $content
	ob_start();
	include $addon;
	$content = ob_get_contents(); 
	ob_end_clean();
}
else
{
	$content = '<b>The addon "'.$_REQUEST['roster_addon_name'].'" does not exist!</b>';
}

//everything after this line will have to be changed to integrate into smarty! ;)

// pass all the css to $more_css which is a placeholder in roster_header for more css style defines
if ($css != '') $more_css = "<STYLE type=\"text/css\">\n<!--\n$css\n-->\n</STYLE>\n";

if ($roster_show_header) include 'roster_header.tpl';
if ($roster_show_menu) include 'lib/menu.php';
echo $content;
if ($roster_show_footer) include 'roster_footer.tpl';

?>