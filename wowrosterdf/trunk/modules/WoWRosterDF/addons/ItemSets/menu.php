<?php
$versions['versionDate']['itemsets'] = '$Date: 2006/08/29 $'; 
$versions['versionRev']['itemsets'] = '$Revision: 1.7.3 $'; 
$versions['versionAuthor']['itemsets'] = '$Author: Gorgar, PoloDude, Zeryl $'; 

$config['menu_name'] = 'Item Sets';    //<- This is just a general name and can be called anything, as it is used in the array, I generally use the name of the addon.

$config['menu_min_user_level'] = 0;     //<- Do not change, its for a future use :)

$config['menu_index_file'] = array();   //<- Do not change, EVER

$config['menu_index_file'][0][0] = '&amp;tierselect&amp;classfilter';  // request query variables delimited by &amp;
$config['menu_index_file'][0][1] = $wordings[$roster_conf['roster_lang']]['ItemSets'];         // menu link text
?>