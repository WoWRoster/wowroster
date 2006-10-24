<?php
$versions['versionDate']['spellbooks'] = '$Date: 2006/10/06 $'; 
$versions['versionRev']['spellbooks'] = '$Revision: 1.7.0 $'; 
$versions['versionAuthor']['spellbooks'] = '$Author: Ghuda $'; 

$config['menu_name'] = 'Spell books';    //<- This is just a general name and can be called anything, as it is used in the array, I generally use the name of the addon.

$config['menu_min_user_level'] = 0;     //<- Do not change, its for a future use :)

$config['menu_index_file'] = array();   //<- Do not change, EVER

$config['menu_index_file'][0][0] = '&amp;classfilter';  // request query variables delimited by &amp;
$config['menu_index_file'][0][1] = $wordings[$roster_conf['roster_lang']]['spellbooks'];         // menu link text
?>