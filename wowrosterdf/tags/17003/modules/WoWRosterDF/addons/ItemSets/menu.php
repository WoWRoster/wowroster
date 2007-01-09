<?php
$versions['versionDate']['itemsetslang'] = '$Date: 2006/06/18 17:00:00 $'; 
$versions['versionRev']['itemsetslang'] = '$Revision: 1.7.0 $'; 
$versions['versionAuthor']['itemsetslang'] = '$Author: Gorgar, PoloDude, Zeryl $'; 

$config['menu_name'] = 'Item Sets';    //<- This is just a general name and can be called anything, as it is used in the array, I generally use the name of the addon.

$config['menu_min_user_level'] = 0;     //<- Do not change, its for a future use :)

$config['menu_index_file'] = array();   //<- Do not change, EVER

$config['menu_index_file'][0][0] = '&amp;tierselect&amp;classfilter';  // request query variables delimited by &amp;
$config['menu_index_file'][0][1] = 'Item Sets';         // menu link text
?>