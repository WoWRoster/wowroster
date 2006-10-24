<?php
/*******************************
 * $Id$
 *******************************/
if (is_admin())
{
	$config['menu_name'] = 'SigGen';
	$config['menu_min_user_level'] = 0;
	$config['menu_index_file'] = array();

	$config['menu_index_file'][0][0] = '';
	$config['menu_index_file'][0][1] = 'SigGen';
}
?>