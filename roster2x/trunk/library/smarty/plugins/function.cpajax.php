<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_ajax: AJAX-enabled Smarty plugins
 *
 * File:    function.ajax_call.php<br>
 * Type:    function<br>
 * Name:    ajax_call<br>
 * Purpose: AJAX-enabled Smarty plugins<br>
 * Install: Drop into the plugin directory<br>
 * License: This software is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.<br><br>
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * @link http://kpumuk.info/ajax/smarty_ajax/
 * @copyright 2006 Dmytro Shteflyuk
 * @author  Dmytro Shteflyuk <kpumuk@kpumuk.info>
 * @version 0.1
 * @param Smarty
 *
 * Roster versioning tag
 * $Id$
 */

function smarty_function_cpajax($params, &$smarty)
{

	//function cpAjax(url, pageElementARG, paramstringARG, pageElementTypeARG, callMessageARG, errorMessageARG)

	$url = isset($params['url']) ? $params['url'] : $_SERVER['PHP_SELF'];

	$method = isset($params['method']) ? $params['method'] : 'get';

	$element = isset($params['element']) ? $params['element'] : NULL;

	$elementType = isset($params['elementType']) ? $params['elementType'] : NULL;

	$callback = isset($params['callback']) ? $params['callback'] : 'undefined';

	$params_func = isset($params['params_func']) ? $params['params_func'] : 'undefined';

	$callMessage = isset($params['callMessage']) ? $params['callMessage'] : NULL;

	$errorMessage = isset($params['errorMessage']) ? $params['errorMessage'] : NULL;

	return 'cpAjax(\'' . $url . '\', \'' . $method . '\', \'' . $parameters . '\', ' . $callback . ', ' . $params_func . '); return false;';

}

?>
