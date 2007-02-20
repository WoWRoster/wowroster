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
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Set the tablename and create the config class ]----
$tablename = ROSTER_ALT_CONFIG_TABLE;
include($addonDir.'config.lib.php');

// ----[ Process data if available ]------------------------
$password_message .= $config->processData();

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build the menu ]-----------------------------------
$menu = border('sgray','start','Config Menu').'
<div style="width:145px;">
  <ul id="config_tabs" class="tab_menu">'."\n".
  $config->buildConfigMenu()."\n".
'   <li><a href="http://www.wowroster.net/wiki/index.php/Roster:Addon:AltMonitor" target="_new">'.$wordings[$roster_conf['roster_lang']]['documentation'].'</a></li>
    <li><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$_GET['roster_addon_name'].'&amp;action=update').'" target="_new">'.$wordings[$roster_conf['roster_lang']]['updMainAlt'].'</a>
    <li><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$_GET['roster_addon_name'].'&amp;action=uninstall_message').'">'.$wordings[$roster_conf['roster_lang']]['uninstall'].'</a>
  </ul>
</div>
'.border('sgray','end');

// ---[ Call functions to build the rest ]------------------
$html = $config->buildConfigPage();

$jscript = $config->writeJScript();


// ----[ Render the entire page ]---------------------------
print '
<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['AltMonitor_config_page'].'</span><br />'.
$password_message.
'<br /><br />
<table width="100%">
  <tr>
    <td valign="top" align="left">
      '.$menu.'
    </td>
    <td valign="top" align="center">
      '.$config->form_start.$config->submit_button.$html.$config->form_end.'
    </td>
  </tr>
</table>'.$jscript;




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
