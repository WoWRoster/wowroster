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
 * @version
 * @link       http://www.wowroster.net
 * @package    WoWRoster
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

require_once ROSTER_BASE. 'settings.php';

$roster->output['title'] = $roster->locale->act['search'];
$roster->output['body_onload'] .= 'initARC(\'s_addon\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';
$roster->output['body_onload'] .= 'initARC(\'searchform\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

include_once (ROSTER_BASE.'roster_header.tpl');

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu($roster->output['show_menu']);


$output = "<br />\n";

// Create an array of active addons with search.inc capabilities.
$addonlist = array();
foreach( $roster->addon_data as $name => $data )
{
        $addon_search_file = ROSTER_ADDONS . $data['basename'] . DIR_SEP . 'inc' . DIR_SEP . 'search.inc';
        if( file_exists($addon_search_file) )
        {
                include_once($addon_search_file);
                $sclass = $data['basename'] . '_search';
		$basename = $data['basename'];
                if( class_exists($sclass) )
                {
						
                        $addonlist[$basename]['search_class'] = $sclass;
                        $addonlist[$basename]['addon'] = $data['basename'];
                        $addonlist[$basename]['basename'] = ($data['fullname'] != '') ? $data['fullname'] : $data['basename'];
                }
        }
}
asort($addonlist);



if (!isset($_POST['search']) && !isset($_GET['search'])) {
        $addon_icon = $roster->config['img_url'].'blue-question-mark.gif';



//this is the start of the main form
print   border('sgreen','start', $roster->locale->act['search_for'] .'<img src="'.$roster->config['img_url'].'blue-question-mark.gif" alt="?" style="float:right;" />');
        echo  '<br /><form id="s_addon" action="' . makelink() . '" method="post" enctype="multipart/form-data" >'
                .'<input size="25" type="text" name="search" value="" class="wowinput192" />'
				.'<input type="submit" value="' . $roster->locale->act['search'] . '" /><br />'
                .'<br />';

        
       echo  '<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\'sonly\',\'data_search_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
			<img src="'.$roster->config['img_url'] . (($open)?'minus':'plus') . '.gif" style="float:right;" alt="" id="sonly_img"/>' . $roster->locale->act['search_onlyin'] . '
			</div>';
		echo '<div id="sonly" >';	
	    echo  '<table border="0" ><tr>';
		

		
        $i = 0;
		//this is set to show a checkbox for all installed and active addons with search.inc files
		//it is set to only show 4 addon check boxes per row and allows for the search only in feature
        foreach ($addonlist as $s_addon) {
                if ($i && ($i % 4 == 0)) {
                         echo '</tr><tr>';
                         echo "\n";
                }
				
                echo  '<td><input type="checkbox"  id="'. $s_addon['addon'] .'" name="$s_addon[]" value="'. $s_addon['addon'] .'" /></td>'.
                         '<td><label for="' . $s_addon['addon'] . '">' . $s_addon['basename'] . '</label></td>';


                $i++;
        }

        echo  '</tr></table>';
		echo  '</div>';
		//include advanced search options
		//the advanced options are defined in the addon search class using $search->options = then build your form/s
        foreach ($addonlist as $s_addon) {
                if (class_exists($s_addon['search_class'])) {
                        $search = new $s_addon['search_class'];
                        if ($search->options) {
								
								echo '<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\'' . $s_addon['basename'] . '\',\'data_search_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
			<img src="'.$roster->config['img_url'] . (($open)?'minus':'plus') . '.gif" style="float:right;" alt="" id="data_search_img"/>' . $roster->locale->act['search_advancedoptionsfor'] . ' ' . $s_addon['basename'] . ':
			</div>'; 
								echo '<div id="' . $s_addon['basename'] . '" style="display:none;">';
                                echo  '<table width="100%" ><tr><td><br />' . $search->options . '<br /></td></tr></table>';
								echo '</div>';
                        }
                }
        }
        echo '</form>';

print   border('sblue','end');

}
else {
		//if page is set in the addon search class this will tell the results what page we are looking at
        $page  = isset($_GET['page']) ? intval($_GET['page']) : 0;
		//limit is used to set how many results are able to show with in the addon before showing previous/next pagenation
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
		//search is what we are searching for
        $query = isset($_POST['search']) ? $_POST['search'] : $_GET['search'];
        //variables that can be used in the addon search class which are being defined here
		$sql_query = $query;
        $the_query = $query;
        $url_query = urlencode($query);

        $addons = array();
        if (isset($_POST['s_addon'])) {
                foreach ($_POST['s_addon'] as $s_addon) {
                        if (isset($addonlist[$s_addon])) $addons[$s_addon] = $addonlist[$s_addon];
                }
        } else if (isset($_GET['s_addon'])) {
                if (isset($addonlist[$_GET['s_addon']])) $addons[$_GET['s_addon']] = $addonlist[$_GET['s_addon']];
        } else {
                $addons = $addonlist;
        }
        
	
	echo '<br />';
	// process all searches
        $total_search_results = 0;
        if ($addons) {
			 //this is the main border for the search results which also displays the search key words
			 print border('sgreen','start', '<img src="'.$roster->config['img_url'].'blue-question-mark.gif' . '" alt="" style="float:right;" />&nbsp;'.$roster->locale->act['search_results'] . ': '.$the_query.'&nbsp;');
			 
			  
			 
                foreach ($addons as $addon) {
                        if (class_exists($addon['search_class'])) {
                                $search = new $addon['search_class'];
                                $search->search($sql_query, $url_query, $limit, $page);
                                if ($search->result_count > 0) {
                                        $total_search_results += $search->result_count;
                                        //I added this to save space on the page but when closed the size of the results table gets very small
										echo '<div class="header_text sgoldborder" style="cursor:pointer;" onclick="showHide(\''  . $search->basename . '\',\'data_search_img\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
			<img src="'.$roster->config['img_url'] . (($open)?'minus':'plus') . '.gif" style="float:right;" alt="" id="data_search_img"/>'  . $search->basename . ' (' .$search->result_count . ' ' . $roster->locale->act['search_results_count'] . ')
			</div>'; 
										echo '<div id="'  . $search->basename . '" >';
										echo '<table>';
                                        
										$alt_counter = 0;
                                        foreach ($search->result as $result) {
										//my attempt to add style
										$alt_counter = ($alt_counter % 2) + 1;
										$stripe_class = ' class="membersRowAltColor'.$alt_counter.'"';
												
												echo '<tbody><tr '. $stripe_class .'><td  class="membersRowCell" width="100%">';
                                                if (isset($result['results_header'])) echo $result['results_header'];
												//if html is set with in the addon search class it will display 
												//html is good for creating search results with multi opetions in the display
												//for instance creating a table that displays char names, professions, guild, server all in the same search result
                                                if (isset($result['html']) && $result['html'] != '') {
                                                        echo $result['html'];
                                                } else {
                                                        // layout the search results based on data we are given...
														//header per addon good for setting the main display
                                                        if (isset($result['header'])) echo $result['header'] . '<br />';
                                                        
                                                        //this is the url link for each result should link to an addon installed and actiaved with in roster
														 echo '<font class="option"><a href=' . $result['url'] . '><strong>' . $result['title'] . '</strong></a></font><br />';
                                                        

                                                        //if isset stuff that can be added as addons get more advanced
														//this is set to allow a footer for each result in the addon query
														//could be good for external links or footer display code
														//if (isset($result['date'])) echo  $result['date'];
                                                        if (isset($result['footer'])) echo '<div style="padding-left: 8px;">'. $result['footer'] . '</div><br />';

                                                        
														//if this is set this will display a footer only for the addon its self great for credits
                                                        if (isset($result['results_footer'])) echo $result['results_footer'];
                                                }
												echo '</td></tr></tbody>';
                                        }
										echo '</table>';
										echo '</div>';
										//if there are previous/next links set in the addon search class the pagnation will be displayed
                                        if ($search->link_prev || $search->link_next) {
												
                                                echo '<div align="center" >'.
                                                         ($search->link_prev ? ' [ ' . $search->link_prev . ' ] ' : '').
                                                         ($search->link_next ? ' [ ' . $search->link_next . ' ] ' : '').
                                                         '</div>';
														 echo '<br />';
														 
                                        }
                        				
										
                                }
                                unset($search);
                        }
                        unset($addonlist[$addon['addon']]);
						
                }
				  $addon = '';
		//a loop to add all the non included addons into the did not find box which also has a counter to show the count  of results
		if (!$total_search_results) {
				$message = $roster->locale->act['search_momatches'];
				
				echo '<div class="header_text sredborder">' . $message . '</div>';
                
        }
		
		echo '<table><div class="header_text sgoldborder">'. $roster->locale->act['search_didnotfind'] .'</div><div><br />';
        foreach ($addonlist as $leftover) {
                if (class_exists($leftover['search_class'])) {
                        $search = new $leftover['search_class'];
                        $search->search($sql_query, $url_query, 64, 0);
                        if ($search->result_count > 0) {
                                echo '<li><a href="'. makelink("search&amp;search=$url_query&amp;s_addon=" . $leftover['addon']) . '">' . $search->basename . '</a> (' .$search->result_count . ' ' . $roster->locale->act['search_results_count'] . ')</li>';
                        }
                        unset($search);
                }
        }
        //section for predefined if addon
		echo '</div>';


        //$url_query = urlencode("$sitename $query");

        $url_query = urlencode($query);
        //this section we can have links like armory, ala, wowhead, thottbot etc...
	//wow data sites
		echo '<tr><td>';
		echo '<div ><strong>' . $roster->locale->act['data_search'] . '</strong></div><div align="left">';
		echo '<ul>';   		     
		echo '<li><a href="http://www.wowhead.com/?search='.$url_query.'" target="new">WoWHead</a></li>';
    	echo '<li><a href="http://wow.allakhazam.com/search.html?q='.$url_query.'" target="new">Allakhazam</a></li>';
    	echo '<li><a href="http://www.thottbot.com/index.cgi?s='.$url_query.'" target="new">Thottbot</a></li>';
    	echo '<li><a href="http://wwndata.worldofwar.net/search.php?search='.$url_query.'" target="new">WWN Data</a></li>';
		echo '</ul></div></td>';
	//wow data sites
		echo '<td><div ><strong>' . $roster->locale->act['itemlink'] . '</strong></div><div align="left">';
		echo '<ul>';   		     
		echo '<li><a href="http://www.wowhead.com/?items&amp;filter=na='.$url_query.'" target="new">WoWHead</a></li>';
        echo '<li><a href="http://wow.allakhazam.com/search.html?q='.$url_query.'" target="new">Allakhazam</a></li>';
        echo '<li><a href="http://www.thottbot.com/index.cgi?i='.$url_query.'" target="new">Thottbot</a></li>';
        echo '<li><a href="http://wwndata.worldofwar.net/search.php?search='.$url_query.'" target="new">WWN Data</a></li>';
		echo '</ul></div></td>';
        //google links
		echo '<td><div ><strong>Google</strong></div><div align="left">';
		echo '<ul>';
        echo '<li><a href="http://www.google.com/search?q='.$url_query.'" target="new">Google</a></li>';
        echo '<li><a href="http://groups.google.com/groups?q='.$url_query.'" target="new">Google Groups</a></li>';
        echo '<li><a href="http://images.google.com/images?q='.$url_query.'" target="new">Google Images</a></li>';
        echo '<li><a href="http://news.google.com/news?q='.$url_query.'" target="new">Google News</a></li>';
        echo '<li><a href="http://froogle.google.com/froogle?q='.$url_query.'" target="new">Froogle</a></li>';
        echo '</ul></div></td>';
		echo '</tr></table>';
		//close the main search results table
		
		print border('sblue','end');
        }
        echo '<br />';
		//if there are no results let them know
        

      }
include_once (ROSTER_BASE.'roster_footer.tpl');
