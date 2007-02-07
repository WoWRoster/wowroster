<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /templates/sc_resetdb.tpl
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

<!-- Begin Settings Reset Box -->
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data" name="reset_settings" onsubmit="submitonce(this)">
  <div id="resetdbCol">
<?php print border('sgray','start','<div style="cursor:pointer;width:187px;" onclick="swapShow(\'resetdbCol\',\'resetdb\')"><img src="'.$roster_conf['img_url'].'plus.gif" style="float:right;" alt="+" />Reset to Defaults</div>'); ?>
<?php print border('sgray','end'); ?>
  </div>
  <div id="resetdb" style="display:none;">
<?php print border('sgray','start','<div style="cursor:pointer;width:187px;" onclick="swapShow(\'resetdbCol\',\'resetdb\')"><img src="'.$roster_conf['img_url'].'minus.gif" style="float:right;" alt="-" />Reset to Defaults</div>'); ?>
    <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center">Check to confirm reset<br />
          <input type="checkbox" class="checkBox" name="confirm_reset" value="1" /></td>
      </tr>
      <tr>
        <td class="sc_row_right2" align="center">
          <input type="hidden" name="sc_op" value="reset_defaults" />
          <input class="button" type="submit" value="Default Settings" name="resetDefault" /></td>
      </tr>
    </table>
<?php print border('sgray','end'); ?>
  </div>
  </form>
<!-- End Settings Reset Box -->
