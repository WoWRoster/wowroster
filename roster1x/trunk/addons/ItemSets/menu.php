<?php
$versions['versionDate']['itemsetsmenu'] = '$Date: 2006/03/16 14:00:00 $'; 
$versions['versionRev']['itemsetsmenu'] = '$Revision: 1.01 $'; 
$versions['versionAuthor']['itemsetsmenu'] = '$Author: Gorgar $'; 

$config['menu_name'] = 'Item Sets';    //<- This is just a general name and can be called anything, as it is used in the array, I generally use the name of the addon.

$config['menu_min_user_level'] = 0;     //<- Do not change, its for a future use :)

$config['menu_index_file'] = array();   //<- Do not change, EVER

$config['menu_index_file'][0][0] = '&amp;tierselect&amp;classfilter';  // request query variables delimited by &amp;
$config['menu_index_file'][0][1] = 'Item Sets';         // menu link text
?>