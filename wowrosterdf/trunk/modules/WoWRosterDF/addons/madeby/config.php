<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Set the tablename and create the config class ]----
$tablename = MADEBY_CONFIG_TABLE;
require_once($addonDir.'lib/config.lib.php');

// ----[ Process data if available ]------------------------
$password_message = $config->processData();
if (!empty($password_message)) $password_message .= '<br />';
$password_message .= $roster_login->getMessage();

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build the menu ]-----------------------------------
$menu = border('sgray','start','Config Menu').'
<div style="width:145px;">
  <ul id="config_tabs" class="tab_menu">'."\n".
  $config->buildConfigMenu()."\n".
//  '<li><a href=add.code.here>'.$wordings[$roster_conf['roster_lang']]['admin']['recipe_maint'].'</a>
//    </ul>
'</div>
'.border('sgray','end');

//  <!--   <li><a href="http://www.wowroster.net/wiki/index.php/Roster:Addon:AltMonitor" target="_new">Documentation</a></li>
//    <li><a href="?roster_addon_name='.$_GET['roster_addon_name'].'&amp;action=update" target="_new">'.$wordings[$roster_conf['roster_lang']]['updMainAlt'].'</a>
//  -->
  
  
// ---[ Call functions to build the rest ]------------------
$html = $config->buildConfigPage();

$jscript = $config->writeJScript();


// ----[ Render the entire page ]---------------------------
print '
<span class="title_text">MadeBy Configuration</span><br />'.
$password_message.
'<br /><br />
<table width="450px">
  <tr>
    <td valign="top" align="left" width="220px">
      <br /><br />'.$menu.'
    </td>
    <td valign="top" align="right" width="220px">
      '.$config->form_start.$config->submit_button.$html.$config->form_end.'
    </td>
  </tr>
</table><br />'.MADEBY_VERSION.'
'.$jscript;

if( $wowdb->sqldebug )
{
	if( is_array($queries) )
	{
		print "<!--\n";
		foreach( $queries as $sql )
		{
			print "$sql\n";
		}
		print "-->\n";
	}
}
?>