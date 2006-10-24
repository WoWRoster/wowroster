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

require_once( BASEDIR.'modules/'.$module_name.'/settings.php' );

$name = (isset($_GET['cname']) ? $_GET['cname'] : '');

// Get char page mode
$action = (isset($_GET['action']) ? $_GET['action'] : 'character' );

switch($action)
{
	case 'character':
		$header_title = $name;
		break;
	case 'bags':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['character'];
		break;
	case 'bank':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['bank'];
		break;
	case 'quests':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['quests'];
		break;
	case 'recipes':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['recipes'];
		break;
	case 'bg':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['bglog'];
		break;
	case 'pvp':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['pvplog'];
		break;
	case 'duels':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['duellog'];
		break;
	case 'spellbook':
		$header_title = $name.' '._BC_DELIM.' '.$wordings[$roster_conf['roster_lang']]['spellbook'];
		break;
	default:
		$header_title = $name;
		break;
}

include_once (ROSTER_BASE.'roster_header.tpl');

include_once (ROSTER_BASE.'memberdetails.php');

include_once (ROSTER_BASE.'roster_footer.tpl');
?>