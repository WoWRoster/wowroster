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

<!-- Begin Settings Import/Export Box -->
<?php print border('spurple','start','<div style="width:187px;"><img src="'.$roster_conf['img_url'].'blue-question-mark.gif" style="float:right;" alt="" />'.$functions->createTip( 'Import and Export your SigGen Settings','Import/Export Settings' ).'</div>'); ?>
    <table width="198" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center"><form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data" name="import_settings" onsubmit="submitonce(this)">
          <?php print $functions->createTip( '<span class=&quot;red&quot;>This WILL OVERWRITE ALL your settings in this config</span>','Import Settings' ); ?>
          <input type="hidden" name="sc_op" value="import" />
          <input class="inputbox" name="userfile" type="file" /><br /><br />
          <input type="submit" value="Import" name="import" /></form></td>
      </tr>
      <tr>
        <td class="sc_row_right2" align="center"><form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data" name="export_settings" onsubmit="submitonce(this)">
          <input type="hidden" name="sc_op" value="export" />
          <?php print $functions->createTip( '<span class=&quot;red&quot;>This SAVES ALL of your settings to a file</span>','Export Settings' ); ?>
          <input type="submit" value="Export" name="export" /></form></td>
      </tr>
    </table>
<?php print border('spurple','end'); ?>
<!-- End Settings Import/Export Box -->
