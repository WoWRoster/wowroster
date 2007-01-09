<?php
 
$config['menu_name'] = 'Latest Loots';    //<- This is just a general name and can be called anything, as it is used in the array, I generally use the name of the addon.
 
$config['menu_min_user_level'] = 0;     //<- Do not change, its for a future use :)
 
$config['menu_index_file'] = array();   //<- Do not change, EVER
 
$config['menu_index_file'][0][0] = '';                   //<- additional request query variables delimited by &amp; if needed
$config['menu_index_file'][0][1] = $wordings[$roster_conf['roster_lang']]['LatestLoot'];   //<- menu link text, using the additional language settings in the addons localization.php file
?>