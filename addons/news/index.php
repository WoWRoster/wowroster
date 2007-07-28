<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: enUS.php 1126 2007-07-27 05:14:27Z Zanix $
 * @link       http://www.wowroster.net
 * @package    News
*/

$query = "SELECT `news`.*, "
		. "DATE_FORMAT(  DATE_ADD(`news`.`date`, INTERVAL " . $roster->config['localtimeoffset'] . " HOUR ), '" . $roster->locale->act['timeformat'] . "' ) AS 'date_format', "
		. "COUNT(`comments`.`comment_id`) comm_count "
		. "FROM `" . $roster->db->table('news','news') . "` news "
		. "LEFT JOIN `" . $roster->db->table('comments','news') . "` comments USING (`news_id`) "
		. "GROUP BY `news`.`news_id`"
		. "ORDER BY `news`.`date` DESC;";

$result = $roster->db->query($query);

if( $roster->db->num_rows($result) == 0 )
{
	echo $roster->locale->act['no_news'];
}

include( $addon['dir'] . 'template' . DIR_SEP . 'news_head.tpl' );

while( $news = $roster->db->fetch($result) )
{
	include( $addon['dir'] . 'template' . DIR_SEP . 'news.tpl' );
}

include( $addon['dir'] . 'template' . DIR_SEP . 'news_foot.tpl' );

