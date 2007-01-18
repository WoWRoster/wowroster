<?php

/**
 * Smarty language plugin
 * @package Smarty
 * @subpackage plugins
 *
 * $Id$
 */
function smarty_function_lang($params, &$smarty)
{
	if( !isset($params['key']) )
	{
		$smarty->trigger_error('Lang: Missing required parameter "key"');
	}
	$key = 'LANG_'.strtoupper($params['key']);
	
	if( !cpMain::isClass('cplang') )
	{
		$smarty->trigger_error('Lang: Language class not loaded');
	}
	
	if( isset(cpMain::$instance['cplang']->lang[$key]) )
	{
		return cpMain::$instance['cplang']->lang[$key];
	}
	elseif( isset($params['default']) )
	{
		return $params['default'];
	}
	else
	{
		return;
	}
}
