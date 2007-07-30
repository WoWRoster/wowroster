<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Templates
*/
$roster->output['body_onload'] .= 'initARC(\'editnews\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

print border('sgreen','start','Edit News');
?>
<form method="post" action="<?php echo makelink('util-news')?>" id="editnews">
<label for="author">Name:</label> <input class="wowinput128" name="author" id="author" type="text" maxlength="16" size="16" value="<?php echo $data['author']; ?>" />
<label for="title">Title:</label> <input class="wowinput192" name="title" id="title" type="text" size="32" value="<?php echo $data['title']; ?>" />
<br />
<br />
<?php
if($addon['config']['news_html']>=0) {?>
<input type="radio" id="html_on" name="html" value="1"<?php echo $addon['config']['news_html']?' checked="checked"':''?>/><label for="html_on">Enable HTML</label>
<input type="radio" id="html_off" name="html" value="0"<?php echo $addon['config']['news_html']?'':' checked="checked"'?>/><label for="html_off">Disable HTML</label>
<br />
<br />
<?php } ?>
<textarea class="input" name="news" id="news" cols="85" rows="20"><?php echo $data['content']; ?></textarea>
<br />
<br />
<input type="hidden" name="process" value="process" />
<input type="hidden" name="id" value="<?php echo $data['news_id']; ?>" />
<input type="submit" value="Edit News"/>
</form>
<?php print border('sgreen','end'); ?>