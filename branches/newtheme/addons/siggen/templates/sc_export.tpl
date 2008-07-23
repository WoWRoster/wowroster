<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /templates/sc_export.tpl
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
 * @version $Id: sc_export.tpl 409 2008-07-02 01:58:28Z Zanix $
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}
?>

<!-- Begin Settings Import/Export Box -->
<div id="t10" style="display:none;">
<?php print border('spurple','start','<div style="width:187px;"><img src="'.$roster->config['img_url'].'blue-question-mark.gif" style="float:right;" alt="" />'.$functions->createTip( 'Import and Export your SigGen Settings','Import/Export Settings' ).'</div>'); ?>
    <table width="198" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center"><form method="post" action="<?php print makelink(); ?>" enctype="multipart/form-data" name="import_settings" onsubmit="submitonce(this)" style="display:inline;">
          <?php print $functions->createTip( '<span class=&quot;red&quot;>This WILL OVERWRITE ALL your settings in this config</span>','Import Settings' ); ?><br />
          <input type="hidden" name="sc_op" value="import" />
          <input name="userfile" type="file" /><br /><br />
          <input type="submit" value="Import" name="import" /></form></td>
      </tr>
      <tr>
        <td class="sc_row_right2" align="center"><form method="post" action="<?php print makelink(); ?>" enctype="multipart/form-data" name="export_settings" onsubmit="submitonce(this)" style="display:inline;">
          <input type="hidden" name="sc_op" value="export" />
          <?php print $functions->createTip( '<span class=&quot;red&quot;>This SAVES ALL of your settings to a file</span>','Export Settings' ); ?><br />
          <input type="submit" value="Export" name="export" /></form></td>
      </tr>
    </table>
<?php print border('spurple','end'); ?>
<!-- End Settings Import/Export Box -->

<br />

<!-- Begin Settings Reset Box -->
  <form id="reset_settings" method="post" action="<?php print makelink(); ?>" enctype="multipart/form-data" name="reset_settings" onsubmit="submitonce(this)">
  <div id="resetdbCol">
<?php print border('sgray','start','<div style="cursor:pointer;width:187px;" onclick="swapShow(\'resetdbCol\',\'resetdb\')"><img src="'.$roster->config['theme_path'].'/images/plus.gif" style="float:right;" alt="+" />Reset to Defaults</div>'); ?>
<?php print border('sgray','end'); ?>
  </div>
  <div id="resetdb" style="display:none;">
<?php print border('sgray','start','<div style="cursor:pointer;width:187px;" onclick="swapShow(\'resetdbCol\',\'resetdb\')"><img src="'.$roster->config['theme_path'].'/images/minus.gif" style="float:right;" alt="-" />Reset to Defaults</div>'); ?>
    <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center">
          <input type="checkbox" class="checkBox" id="confirm_reset" name="confirm_reset" value="1" /><label for="confirm_reset">Check to confirm reset</label></td>
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
</div>
