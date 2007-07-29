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
$roster->output['body_onload'] .= 'initARC(\'addnews\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

print border('sgreen','start','Add News');
?>
<form method="post" action="<?php echo makelink('util-news')?>" id="addnews">
<label for="author">Name:</label> <input class="wowinput128" name="author" id="author" type="text" maxlength="16" size="16" value="" />
<label for="title">Title:</label> <input class="wowinput192" name="title" id="title" type="text" size="32" value="" />
<br />
<br />
<?php
if($addon['config']['news_html']>=0) {?>
<input type="radio" id="html_on" name="html" value="1"<?php echo $addon['config']['news_html']?' checked="checked"':''?>/><label for="html_on">Enable HTML</label>
<input type="radio" id="html_off" name="html" value="0"<?php echo $addon['config']['news_html']?'':' checked="checked"'?>/><label for="html_off">Disable HTML</label>
<br />
<br />
<?php } ?>
<textarea class="input" name="news" id="news" cols="85" rows="20"></textarea>
<br />
<br />
<input type="hidden" name="process" value="process" />
<input type="submit" value="Add News"/>
</form>
<?php print border('sgreen','end'); ?>