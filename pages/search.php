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

require_once ROSTER_BASE . 'settings.php';

$roster->output['title'] = $roster->locale->act['search'];
$roster->output['body_onload'] .= 'initARC(\'search\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

include_once(ROSTER_BASE . 'header.php');


$roster_menu = new RosterMenu;
$roster_menu->makeMenu($roster->output['show_menu']);


$output = "<br />\n";

/*Create an array of active addons with search.inc.php capabilities*/
foreach( $roster->addon_data as $name => $data )
{
	$roster->addon_data[$name] = getaddon($data['basename']);
	if( file_exists($roster->addon_data[$name]['search_file']) )
	{
		include_once($roster->addon_data[$name]['search_file']);
		$sclass = $data['basename'] . '_search';
		if( class_exists($sclass) )
		{
			$roster->addon_data[$name]['search_class'] = $sclass;
		}

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
	}
}

//this is the start of the main form
if( !isset($_POST['search']) && !isset($_GET['search']) )
{
	$addon_icon = $roster->config['img_url'] . 'blue-question-mark.gif';

	print border('sgreen','start', '<img src="' . $roster->config['img_url'] . 'blue-question-mark.gif" alt="?" style="float:right;" />' . $roster->locale->act['search_for']);
	echo  '<br /><form id="search" name="search" action="' . makelink() . '" method="post" enctype="multipart/form-data" >'
		. '<input size="25" type="text" name="search" value="" class="wowinput192" />'
		. '<input type="submit" value="' . $roster->locale->act['search'] . '" /><br />'
		. '<br />';


	echo '<div class="header_text sgoldborder">';
	echo $roster->locale->act['search_onlyin'];
	echo '</div>';

	$i = 0;
	$s_only = $s_adv = '';
	foreach( $roster->addon_data as $s_addon )
	{
		// this is set to show a checkbox for all installed and active addons with search.inc.php files
		// it is set to only show 4 addon check boxes per row and allows for the search only in feature
		if( isset($s_addon['search_class']) )
		{
			$search = new $s_addon['search_class'];

			if( $i && ($i % 4 == 0) )
			{
				$s_only .= "<br />\n";
			}

			$s_only .= '<input type="checkbox" id="' . $s_addon['basename'] . '" name="s_addon[]" value="' . $s_addon['basename'] . '" />'
					 . '<label for="' . $s_addon['basename'] . '">' . $s_addon['fullname'] . '</label>';

			// include advanced search options
			// the advanced options are defined in the addon search class using $search->options = then build your forms
			if( $search->options )
			{
				$s_adv .= '<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\'' . $s_addon['basename'] . '_options\',\'' . $s_addon['basename'] . '_img_options\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
			<img src="' . $roster->config['img_url'] . 'plus.gif" style="float:right;" alt="" id="' . $s_addon['basename'] . '_img_options" />' . $roster->locale->act['search_advancedoptionsfor'] . ' ' . $s_addon['fullname'] . '
			</div>';
				$s_adv .= '<div id="' . $s_addon['basename'] . '_options" style="display:none;">';
				$s_adv .= $search->options;
				$s_adv .= '</div>';
			}
			$i++;
		}
	}

	echo $s_only . $s_adv . '</form>';

	print border('sgreen','end');

}
else
{
	// if page is set in the addon search class this will tell the results what page we are looking at
	$page  = isset($_GET['page']) ? intval($_GET['page']) : 0;

	// limit is used to set how many results are able to show with in the addon before showing previous/next pagenation
	$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

	// search is what we are searching for
	$query = isset($_POST['search']) ? $_POST['search'] : $_GET['search'];

	// variables that can be used in the addon search class which are being defined here
	$sql_query = $query;
	$the_query = $query;
	$url_query = urlencode($query);

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

	echo '<br />';

	// process all searches
	$total_search_results = 0;
	if( count($addons) > 0 )
	{
		//this is the main border for the search results which also displays the search key words
		print border('sgreen','start', '<img src="' . $roster->config['img_url'] . 'blue-question-mark.gif' . '" alt="" style="float:right;" />&nbsp;' . $roster->locale->act['search_results'] . ': ' . $the_query);

		foreach( $addons as $addon )
		{
			if( isset($addon['search_class']) )
			{
				$search = new $addon['search_class'];
				$search->data = $addon;
				$search->search($sql_query, $url_query, $limit, $page);

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
					$search_count->search($sql_query, $url_query, 0, 0);

					// I added this to save space on the page but when closed the size of the results table gets very small
					echo '<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\''  . $addon['basename'] . '\',\''  . $addon['basename'] . '_search_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
					<img src="' . $addon['icon'] . '" style="float:left;" alt="" id="'  . $addon['basename'] . '_item_img" width="16" height="16" />
					<img src="' . $roster->config['img_url'] . 'minus.gif" style="float:right;" alt="" id="'  . $addon['basename'] . '_search_img"/>' . $addon['fullname'] . ' <small>(' . $search->result_count . ' of ' . $search_count->result_count . ' ' . $roster->locale->act['search_results_count'] . ' ' . $search->time_search . ')</small></div>';

					echo '<div id="' . $addon['basename'] . '">';
					echo '<table width="100%" cellspacing="0" cellpadding="0">';
					if( isset($search->open_table) )
					{
						echo $search->open_table;
					}

					$alt_counter = 0;
					foreach( $search->result as $result )
					{
						//my attempt to add style
						$alt_counter = ($alt_counter % 2) + 1;
						$stripe_class = ' class="SearchRowAltColor' . $alt_counter . '"';


						if( isset($result['results_header']) )
						{
							echo $result['results_header'];
						}

						echo '<tr' . $stripe_class . '>';
						// if html is set with in the addon search class it will display
						// html is good for creating search results with multi opetions in the display
						// for instance creating a table that displays char names, professions, guild, server all in the same search result
						if( isset($result['html']) && $result['html'] != '' )
						{
							echo $result['html'];
						}
						else
						{
							echo '<td class="SearchRowCellRight">';

							// layout the search results based on data we are given...
							// header per addon good for setting the main display
							if( isset($result['header']) )
							{
								echo $result['header'] . '<br />';
							}

							// we will display the author name and date submited if it is set in the addon search class
							if( isset($result['author']) )
							{
								echo $roster->locale->act['submited_author'] . ' ' . $result['author'];
							}
							if( isset($result['date']) )
							{
								echo  ' ' . $roster->locale->act['submited_date'] . ' ' . readbleDate($result['date']);
							}
							if( isset($result['author']) || isset($result['date']) )
							{
								echo '<br />';
							}

							// this is the url link for each result should link to an addon installed and actiaved with in roster
							echo '<a href="' . $result['url'] . '"><strong>' . $result['title'] . '</strong></a><br />';

							// we will display a short desc. if it is set in the addon search class
							if( isset($result['short_text']) )
							{
								echo '<span style="white-space:normal">' . $result['short_text']
									. ((isset($result['more_text']) && $result['more_text']) ? ' <a href="' . $result['url'] . '"><strong>(more)</strong></a>' : '')
									. '</span><br />';
							}


							// if isset stuff that can be added as addons get more advanced
							// this is set to allow a footer for each result in the addon query
							// could be good for external links or footer display code
							// if (isset($result['date'])) echo  $result['date'];
							if( isset($result['footer']) )
							{
								echo '<div style="padding-left:8px;">' . $result['footer'] . '</div><br />';
							}

							//if this is set this will display a footer only for the addon its self great for credits
							if( isset($result['results_footer']) )
							{
								echo $result['results_footer'];
							}

							echo '</td>';
						}
						echo '</tr>';
					}
					echo '</table>';

					// if there are previous/next links set in the addon search class the pagnation will be displayed
					if( $search->link_prev || $search->link_next )
					{
						echo '<div class="SearchNextPrev" align="center">'
							. ($search->link_prev ? ' [ ' . $search->link_prev . ' ] ' : '')
							. ($search->link_next ? ' [ ' . $search->link_next . ' ] ' : '')
							. '</div>';
					}

					echo '</div>';
				}
				unset($search);
			}
		}

		$addon = '';
		if( isset($search->close_table))
		{
			echo $search->close_table;
		}

		// a loop to add all the non included addons into the did not find box which also has a counter to show the count of results
		if( !$total_search_results )
		{
			$message = $roster->locale->act['search_nomatches'];
			echo '<div class="header_text sredborder">' . $message . '</div>';
		}

		print border('sgreen','end');



		// Didn't find what you were looking for section
		echo '<br />' . border('sblue','start',$roster->locale->act['search_didnotfind']);

		$more = '';
		foreach( $roster->addon_data as $leftover )
		{
			if( isset($leftover['search_class']) )
			{
				$search = new $leftover['search_class'];
				$search->data = $leftover;
				$search->search($sql_query, $url_query, 0, 0);
				if( $search->result_count > 0 )
				{
					$more .= '<li><a href="' . makelink("search&amp;search=$url_query&amp;s_addon=" . $leftover['basename']) . '">' . $leftover['fullname'] . '</a> (' . $search->result_count . ' ' . $roster->locale->act['search_results_count'] . ')</li>';
				}
				unset($search);
			}
		}

		echo ( !empty($more) ? "<div class=\"SearchRowCellRight\"><ul style=\"text-align:left;\">$more</ul></div>" : '' );

		// section for predefined if addon

		$url_query = urlencode($query);

		// this section we can have links like armory, ala, wowhead, thottbot etc...

		// wow data sites
		echo '<table cellspacing="0" cellpadding="0" width="100%"><tr class="search-other"><td valign="top" width="50%" class="SearchRowCell">';
		echo '<strong>' . $roster->locale->act['data_search'] . '</strong>
		<div align="left">';
		echo '<ul>';

		$data_link = '';
		foreach( $roster->locale->act['data_links'] as $name => $dlink )
		{
			$data_link .= '<li><a href="' . $dlink . $url_query . '" target="_blank">' . $name . '</a></li>';
		}
		echo $data_link;
		echo '</ul></div></td>';

		// google links
		echo '<td valign="top" width="50%" class="SearchRowCellRight"><strong>'. $roster->locale->act['google_search'] .'</strong><div align="left">';
		echo '<ul>';
		$google_link = '';
		foreach( $roster->locale->act['google_links'] as $name => $glink )
		{
			$google_link .= '<li><a href="' . $glink . $url_query . '" target="_blank">' . $name . '</a></li>';
		}
		echo $google_link;
		echo '</ul></div></td>';
		echo '</tr></table>';
		// close the main search results table

		print border('sgreen','end');
	}
	echo '<br />';
	// if there are no results let them know
}

include_once(ROSTER_BASE . 'footer.php');
