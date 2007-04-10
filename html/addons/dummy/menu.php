<?php
/* 
* $Date: 2005/12/28 21:59:55 $ 
* $Revision: 1.11 $ 
*/ 

$config['menu_name'] = 'Dummy Page';
$config['menu_min_user_level'] = 0;
$config['menu_index_file'] = array();

$config['menu_index_file'][0][0] = '&amp;display_text=1'; //request query variables delimited by & 
$config['menu_index_file'][0][1] = $wordings[$roster_lang]['dummyLink1']; //menu link text

$config['menu_index_file'][1][0] = '&amp;display_text=1&amp;display_image=1';
$config['menu_index_file'][1][1] = $wordings[$roster_lang]['dummyLink2'];



?>