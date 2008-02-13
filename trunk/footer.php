<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Overall footer for Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.6.0
 * @package    WoWRoster
 */

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

define('ROSTER_FOOTER_INC',true);

$totaltime = round(format_microtime() - ROSTER_STARTTIME, 2);

$error_report = $roster->error->stop();

// Assign template vars
$roster->tpl->assign_vars(array(
	'S_PROCESSTIME'     => $roster->config['processtime'],
	'S_DEBUG_MODE'      => $roster->config['debug_mode'] && is_array($error_report),
	'S_SQL_WIN'         => $roster->config['sql_window'],
	'S_DESCRIBE'        => $roster->config['sql_window'] == 2,

	'PROCESSTIME'        => $totaltime,
	'QUERYCOUNT'         => $roster->db->query_count,

	'ROSTER_PATH'        => ROSTER_PATH,
	'ROSTER_BODY'        => (!empty($roster->config['roster_bg']) ? ' style="background-image:url(' . $roster->config['roster_bg'] . ');"' : '')
		. (!empty($roster->output['body_attr']) ? ' ' . $roster->output['body_attr'] : '')
		. (!empty($roster->output['body_onload']) ? ' onload="' . $roster->output['body_onload'] . '"' : ''),
	'WEBSITE_ADDRESS'    => $roster->config['website_address'],
	'HEADER_LOGO'        => $roster->config['logo'],
	'IMG_URL'            => $roster->config['img_url'],
	'INTERFACE_URL'      => $roster->config['interface_url'],
	'ROSTER_VERSION'     => $roster->config['version'],
	'ROSTER_CREDITS'     => sprintf($roster->locale->act['roster_credits'], makelink('credits')),
	)
);

if( $roster->config['debug_mode'] )
{
	if( is_array($error_report) )
	{
		foreach( $error_report as $file => $errors )
		{
			$roster->tpl->assign_block_vars('php_debug', array(
				'FILE' => substr($file, strlen(ROSTER_BASE)),
				)
			);
			foreach( $errors as $error )
			{
				$roster->tpl->assign_block_vars('php_debug.row', array(
					'ROW_CLASS' => $roster->switch_row_class(),
					'ERROR' => $error,
					)
				);
			}
		}

		$roster->tpl->assign_vars(array(
			'PHP_DEBUG_B_S' => border('sred','start','PHP Errors'),
			'PHP_DEBUG_B_E' => border('sred','end'),
			)
		);
	}
}

if( $roster->config['sql_window'] )
{
	if( count($roster->db->queries) > 0 )
	{
		foreach( $roster->db->queries as $file => $queries )
		{
			$roster->tpl->assign_block_vars('sql_debug', array(
				'FILE' => substr($file, strlen(ROSTER_BASE)),
				)
			);
			foreach( $queries as $query )
			{
				$roster->tpl->assign_block_vars('sql_debug.row', array(
					'ROW_CLASS' => $roster->switch_row_class(),
					'LINE'      => $query['line'],
					'TIME'      => $query['time'],
					'QUERY'     => nl2br(htmlentities($query['query'])) . ( empty($query['error']) ? '' : '<hr />' . nl2br(htmlentities($query['error'])) ),
					'DESCRIBE'  => aprint($query['describe'],'',true),
					)
				);
			}
		}

		$roster->tpl->assign_vars(array(
			'SQL_DEBUG_B_S' => border('sgreen','start',$roster->locale->act['sql_queries']),
			'SQL_DEBUG_B_E' => border('sgreen','end'),
			)
		);
	}
}

$roster->tpl->assign_var('ROSTER_TOOLTIPS',getAllTooltips());

$roster->tpl->set_filenames(array('roster_footer' => 'footer.html'));
$roster->tpl->display('roster_footer');
