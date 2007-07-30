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
$roster->output['body_onload'] .= 'initARC(\'editcomment\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';
?>
<br />
<?php
print border('sblue','start','Edit Comment');
?>
<form method="post" action="<?php echo makelink('util-news-comment&amp;id=' . $_GET['id']) ?>" id="editcomment">
<label for="author">Name: </label><input class="wowinput128" name="author" id="author" type="text" maxlength="16" size="16" value="<?php echo $data['author'] ?>" />
<br />
<?php if($addon['config']['comm_html']>=0) {?>
<input type="radio" id="html_on" name="html" value="1"<?php echo $addon['config']['comm_html']?' checked="checked"':''?>/><label for="html_on">Enable HTML</label>
<input type="radio" id="html_off" name="html" value="0"<?php echo $addon['config']['comm_html']?'':' checked="checked"'?>/><label for="html_off">Disable HTML</label>
<br />
<?php } ?>
<textarea class="input" name="comment" id="comment" cols="85" rows="20"><?php echo $data['content']; ?></textarea>
<input type="hidden" name="process" value="process" />
<input type="hidden" name="comment_id" value="<?php echo $data['comment_id']; ?>" />
<br />
<br />
<input type="submit" value="Edit Comment"/>
</form>
<?php print border('sblue','end'); ?>