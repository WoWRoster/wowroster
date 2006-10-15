<?php
/******************************
 * WoWRoster.net  UniAdmin
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

foreach( $user->lang['help'] as $help_text )
{
	$tpl->assign_block_vars('help_row', array(
	    'HELP_HEADER' => $help_text['header'],
	    'HELP_TEXT'   => $help_text['text'])
	);
}

$uniadmin->set_vars(array(
    'page_title'    => $user->lang['title_help'],
    'template_file' => 'help.html',
    'display'       => true)
);

?>