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
<form method="post" action="<?php echo makelink('util-news-comment&amp;id=' . $_GET['id']) ?>">
<label for="author">Name: </label><input name="author" id="author" type="text" maxlength="16" size="16" value="" />
<br />
<?php if($addon->config['comm_html']>=0) {?>
<input type="radio" id="html_on" name="html" value="1"<?php echo $addon->config['comm_html']?' checked="checked"':''?>/><label for="html_on">Enable HTML</label>
<input type="radio" id="html_off" name="html" value="0"<?php echo $addon->config['comm_html']?'':' checked="checked"'?>/><label for="html_off">Disable HTML</label>
<br />
<?php } ?>
<textarea name="comment" id="comment" cols="60" rows="20"></textarea>
<input type="hidden" name="process" value="process" />
<input type="submit" value="Add comment"/>
</form>
