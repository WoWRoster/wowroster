<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Site and Addon Search engine
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['search'];
$roster->output['body_onload'] .= "initARC('search','radioOn','radioOff','checkboxOn','checkboxOff');";


$roster->tpl->assign_vars(array(
	'S_NO_RESULTS'      => false,
	'S_RESULT'          => false,

	'U_SEARCH_LINK'     => makelink('search'),

	'L_SEARCH_FOR'      => $roster->locale->act['search_for'],
	'L_SEARCH'          => $roster->locale->act['search'],
	'L_SEARCH_ONLY'     => $roster->locale->act['search_onlyin'],
	'L_SEARCH_ADVANCED' => $roster->locale->act['search_advancedoptionsfor'],
	'L_SEARCH_RESULTS'  => $roster->locale->act['search_results'],
	'L_RESULTS_COUNT'   => $roster->locale->act['search_results_count'],
	'L_AUTHOR'          => $roster->locale->act['submited_author'],
	'L_DATE'            => $roster->locale->act['submited_date'],
	'L_NO_MATCHES'      => $roster->locale->act['search_nomatches'],
	'L_DID_NOT_FIND'    => $roster->locale->act['search_didnotfind'],
	'L_DATA_SEARCH'     => $roster->locale->act['data_search'],
	'L_GOOGLE_SEARCH'   => $roster->locale->act['google_search'],
	'L_NEXT_MATCHES'    => $roster->locale->act['search_next_matches'],
	'L_PREV_MATCHES'    => $roster->locale->act['search_previous_matches'],

	'SEARCH'            => ''
	)
);

/**
 * We need all the addon data for search
 * So we inject the full addon data into the global array
 */
foreach( $roster->addon_data as $name => $data )
{
	$roster->addon_data[$name] = getaddon($name);

	// Save current locale array
	// Since we add all locales for localization, we save the current locale array
	// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
	$localetemp = $roster->locale->wordings;

	foreach( $roster->multilanguages as $lang )
	{
		$roster->locale->add_locale_file(ROSTER_ADDONS . $data['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
	}

	$roster->addon_data[$name]['fullname'] = ( isset($roster->locale->act[$data['fullname']]) ? $roster->locale->act[$data['fullname']] : $data['fullname'] );

	// Restore our locale array
	$roster->locale->wordings = $localetemp;
	unset($localetemp);

	if( file_exists($roster->addon_data[$name]['search_file']) )
	{
		require($roster->addon_data[$name]['search_file']);
	}

	// Open the lib/search directory for roster core search files
	$tmp_dir = @opendir( ROSTER_LIB . 'search' );

	if( $tmp_dir )
	{
		// Read the files
		while( $file = readdir($tmp_dir) )
		{
			$pfad_info = pathinfo($file);

			if( strtolower($pfad_info['extension']) == strtolower('php') )
			{
				$name = str_replace('.' . $pfad_info['extension'],'',$file);

				$file = ROSTER_LIB . 'search' . DIR_SEP . $file;
				require($file);

				$basename = 'roster_' . $name;
				$roster->addon_data[$basename]['basename'] = $basename;
				$roster->addon_data[$basename]['fullname'] = $roster->locale->act[$name];
				$roster->addon_data[$basename]['search_file'] = $file;
				$roster->addon_data[$basename]['search_class'] = $basename . 'Search';
				$roster->addon_data[$basename]['icon'] = 'inv_misc_gear_02';
			}
		}
		// close the directory
		closedir($tmp_dir);
	}
}


/**
 * Result processing
 */
if( isset($_POST['search']) || isset($_GET['search']) )
{
	// if page is set in the addon search class this will tell the results what page we are looking at
	$page  = isset($_GET['page']) ? intval($_GET['page']) : 0;

	// limit is used to set how many results are able to show with in the addon before showing previous/next pagenation
	$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

	// search is what we are searching for
	$query = ( isset($_POST['search']) ? $_POST['search'] : ( isset($_GET['search']) ? $_GET['search'] : '' ) );

	// variables that can be used in the addon search class which are being defined here
	$url_query = urlencode($query);

	$roster->tpl->assign_vars(array(
		'SEARCH'   => $query,
		'S_RESULT' => true,
		'LIMIT'    => $limit,
		'PAGE'     => $page+1,
		'FIRST'    => ($page*$limit)+1
		)
	);

	$addons = array();
	if( isset($_POST['s_addon']) )
	{
		foreach( $_POST['s_addon'] as $s_addon )
		{
			if( isset($roster->addon_data[$s_addon]) )
			{
				$addons[$s_addon] = $roster->addon_data[$s_addon];
			}
		}
	}
	elseif( isset($_GET['s_addon']) )
	{
		if( isset($roster->addon_data[$_GET['s_addon']]) )
		{
			$addons[$_GET['s_addon']] = $roster->addon_data[$_GET['s_addon']];
		}
	}
	else
	{
		$addons = $roster->addon_data;
	}

	// process all searches
	$total_search_results = 0;
	if( count($addons) > 0 )
	{
		foreach( $addons as $addon )
		{
			if( class_exists($addon['search_class']) )
			{
				$search = new $addon['search_class'];
				$search->data = $addon;
				$search->search($query, $limit, $page);

				if( $search->result_count > 0 )
				{
					$total_search_results += $search->result_count;

					if( !empty($addon['icon']) )
					{
						if( strpos($addon['icon'],'.') !== false )
						{
							$addon['icon'] = ROSTER_PATH . 'addons/' . $addon['basename'] . '/images/' . $addon['icon'];
						}
						else
						{
							$addon['icon'] = $roster->config['interface_url'] . 'Interface/Icons/' . $addon['icon'] . '.' . $roster->config['img_suffix'];
						}
					}
					else
					{
						$addon['icon'] = $roster->config['interface_url'] . 'Interface/Icons/inv_misc_questionmark.' . $roster->config['img_suffix'];
					}

					$search_count = new $addon['search_class'];
					$search_count->data = $addon;
					$search_count->search($query, 0, 0);

					$roster->tpl->assign_block_vars('addon_results', array(
						'BASENAME' => $addon['basename'],
						'FULLNAME' => $addon['fullname'],
						'ICON'     => $addon['icon'],
						'COUNT'    => (($page*$limit))+$search->result_count,
						'TIME'     => round($search->time_search,4),

						'OPEN_TABLE'  => $search->open_table,
						'CLOSE_TABLE' => $search->close_table,

						'TOTAL' => $search_count->result_count,

						'PREV'  => ( $page > 0 ? makelink('search&amp;page=' . ($page-1) . '&amp;search=' . $url_query . '&amp;s_addon=' . $search->data['basename'] . $search->search_url) : '' ),
						'NEXT'  => ( $search->result_count >= $limit ? makelink('search&amp;page=' . ($page+1) . '&amp;search=' . $url_query . '&amp;s_addon=' . $search->data['basename'] . $search->search_url) : '' ),
						)
					);

					foreach( $search->result as $result )
					{
						// if html is set with in the addon search class it will display
						// html is good for creating search results with multi opetions in the display
						// for instance creating a table that displays char names, professions, guild, server all in the same search result
						$roster->tpl->assign_block_vars('addon_results.row', array(
							'ROW_CLASS' => $roster->switch_row_class(),

							'HTML'      => ( isset($result['html']) && $result['html'] != '' ? $result['html'] : '' ),

							'RESULTS_HEADER' => ( isset($result['results_header']) ? $result['results_header'] : '' ),
							'RESULTS_FOOTER' => ( isset($result['results_footer']) ? $result['results_footer'] : '' ),

							'HEADER'     => ( isset($result['header']) ? $result['header'] . '<br />' : '' ),
							'AUTHOR'     => ( isset($result['author']) ? $result['author'] : '' ),
							'DATE'       => ( isset($result['date']) ? readbleDate($result['date']) : '' ),
							'LINK'       => ( isset($result['url']) ? $result['url'] : '' ),
							'TITLE'      => ( isset($result['title']) ? $result['title'] : '' ),
							'SHORT_TEXT' => ( isset($result['short_text']) ? $result['short_text'] : '' ),
							'MORE_TEXT'  => ( isset($result['more_text']) ? $result['more_text'] : '' ),
							'FOOTER'     => ( isset($result['footer']) ? $result['footer'] : '' ),
							)
						);
					}
				}
				unset($search,$search_count);
			}
		}

		$addon = '';
		//this is where we want to set up item searches

		// a loop to add all the non included addons into the did not find box which also has a counter to show the count of results
		if( $total_search_results == 0 )
		{
			$roster->tpl->assign_var('S_NO_RESULTS',true);
		}


		$more = false;
		foreach( $roster->addon_data as $leftover )
		{
			// Continue if we have already searched the addon above
			if( isset($addons[$leftover['basename']]) )
			{
				continue;
			}

			if( class_exists($leftover['search_class']) )
			{
				$search = new $leftover['search_class'];
				$search->data = $leftover;
				$search->search($query, 0, 0);
				if( $search->result_count > 0 )
				{
					$more = true;

					$roster->tpl->assign_block_vars('more_results', array(
						'LINK'     => makelink('search&amp;search=' . $url_query. '&amp;s_addon=' . $leftover['basename']),
						'FULLNAME' => $leftover['fullname'],
						'COUNT'    => $search->result_count,
						)
					);
				}
				unset($search);
			}
		}

		$roster->tpl->assign_var('S_MORE_RESULTS',$more);

		// section for predefined if addon

		// this section we can have links like armory, ala, wowhead, thottbot etc...

		// wow data sites
		foreach( $roster->locale->act['data_links'] as $name => $dlink )
		{
			$roster->tpl->assign_block_vars('data_sites', array(
				'LINK' => $dlink . $url_query,
				'NAME' => $name
				)
			);
		}

		// google links
		foreach( $roster->locale->act['google_links'] as $name => $glink )
		{
			$roster->tpl->assign_block_vars('google_links', array(
				'LINK' => $glink . $url_query,
				'NAME' => $name
				)
			);
		}
	}
}

$s_addon = ( isset($_POST['s_addon']) ? $_POST['s_addon'] : ( isset($_GET['s_addon']) ? $_GET['s_addon'] : array() ) );

/**
 * Build the search form
 */
$i = 1;
foreach( $roster->addon_data as $addon_name => $addon_data )
{
	if( !class_exists($addon_data['search_class']) )
	{
		continue;
	}

	// this is set to show a checkbox for all installed and active addons with search.inc.php files
	// it is set to only show 4 addon check boxes per row and allows for the search only in feature
	if( isset($addon_data['search_class']) )
	{
		$roster->tpl->assign_block_vars('only_search', array(
			'BASENAME' => $addon_data['basename'],
			'FULLNAME' => $addon_data['fullname'],
			'S_DIVIDE' => ( $i && ($i % 4 == 0) ? true : false ),
			'SELECTED' => ( is_array($s_addon) ? ( in_array($addon_data['basename'],$s_addon) ? true : false ) : $addon_data['basename'] == $s_addon )
			)
		);
		$i++;

		$search = new $addon_data['search_class'];

		// include advanced search options
		// the advanced options are defined in the addon search class using $search->options = then build your forms
		if( !empty($search->options) )
		{
			$roster->tpl->assign_block_vars('advanced_search', array(
				'BASENAME'       => $addon_data['basename'],
				'FULLNAME'       => $addon_data['fullname'],
				'SEARCH_OPTIONS' => ( $search->options ? $search->options : '' )
				)
			);
		}
	}
}

$roster->tpl->set_filenames(array('body' => 'search.html'));
$roster->tpl->display('body');
