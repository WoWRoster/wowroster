<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /templates/sc_menu.tpl
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id$
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}
?>

<!-- Begin SigConfig Menu -->
<?php print border('sgray','start','Config Menu'); ?>
<div style="border:1px solid #212121; width:145px;">
  <ul id="siggen_menu" class="tab_menu">
    <li class="selected"><a href="#" rel="t1">Main Settings</a></li>
    <li><a href="#" rel="t2">Backgrounds</a></li>
    <li><a href="#" rel="t4">eXp Bar</a></li>
    <li><a href="#" rel="t5">Level Bubble</a></li>
    <li><a href="#" rel="t6">Skills Display</a></li>
    <li><a href="#" rel="t7">Char/Class/PvP Logo</a></li>
    <li><a href="#" rel="t8">Text Config</a></li>
    <li><a href="http://www.wowroster.net/wiki/index.php/Roster:Addon:SigGen" target="_blank">Documentation</a></li>
  </ul>
</div>
<?php print border('sgray','end'); ?>
<!-- End SigConfig Menu -->