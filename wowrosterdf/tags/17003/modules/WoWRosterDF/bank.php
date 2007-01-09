<?php
/* 
* $Date: 2006/01/11 19:45:26 $ 
* $Revision: 1.4 $ 
*/ 


if ( !defined( 'CPG_NUKE' ) ) {
	exit;
}
if (isset($_GET['display'])) {
	$display = $_GET['display'];
} else {
	$display = '';
}
require_once( BASEDIR.'modules/'.$module_name.'/settings.php' );
switch($op) {
case "catbank":
	//print 'hello';
	require (BASEDIR.'modules/'.$module_name.'/addons/guildbank/gbank.php');
break;

case "guildbank":
	//print 'hello';
	require(ROSTER_BASE.'guildbank.php');
break;
case "guildbank2":
	//print 'hello';
	require(ROSTER_BASE.'guildbank2.php');
defualt:
 include_once(ROSTER_BASE.'roster_header.tpl');
	include_once(ROSTER_LIB.'menu.php');
	$url = '<a href="'.getlink('&amp;file=');

$menu_cell = '      <td class="menubarHeader" align="center" valign="middle">';

print '<div align="center">'."\n";


print border('sorange','start','Guild Bank');

print '  <table cellpadding="3" cellspacing="0" class="menubar">'."\n<tr>\n";

echo $menu_cell.$url.'&file=bank&amp;display=catbank">Catagreized Bank</a></td>'."\n";	
echo $menu_cell.$url.'&file=bank&amp;display=guildbank2">Bankers</a></td>'."\n";
echo $menu_cell.$url.'&file=bank&amp;display=guildbank">'.$wordings[$roster_conf['roster_lang']]['guildbank'].'</a></td>'."\n";


print "  </tr>\n</table>\n";

print border('sorange','end');

echo '<br>';
	 print
			'<div >
				'.border('sred','start').'
				<center>Must Be Logged in to use this service!</center>
				'.border('sred','end').'
			</div>';
    include_once(ROSTER_BASE.'roster_footer.tpl');
break;}



?>