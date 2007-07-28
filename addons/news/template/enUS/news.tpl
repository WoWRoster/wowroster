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
 * @subpackage Templates
*/
?>
<h1><?php echo $data['title']?></h1>
(<?php echo $data['author'] ?> - <?php echo $data['date_format'] ?>)
<hr />
<?php echo nl2br(htmlentities($data['content']))?>
<hr />
<a href="<?php echo makelink('util-news-comment&amp;id=' . $data['news_id']) ?>">
<?php echo $data['comm_count'] == 0?'no':$data['comm_count']?> comment<?php echo $data['comm_count'] != 1?'s':''?></a>
