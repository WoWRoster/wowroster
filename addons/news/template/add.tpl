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
<form method="post" action="<?php echo makelink('util-news')?>">
<label for="author">Name: </label><input name="author" id="author" type="text" maxlength="16" size="16" value="" />
<br />
<label for="title">Title: </label><input name="title" id="title" type="text" size="32" value="" />
<br />
<textarea name="news" id="news" cols="60" rows="20"></textarea>
<input type="hidden" name="process" value="process" />
<input type="submit" value="Add news"/>
</form>
