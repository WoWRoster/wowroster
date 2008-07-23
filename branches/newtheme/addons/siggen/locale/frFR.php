<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /locale/frFR.php
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
 * @version $Id: frFR.php 363 2008-02-07 05:16:09Z Zanix $
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

// translated to frFR by Harut

// Translation Information
	// Format: $lang['translate']['Localized Name']='English Name';
	// 'Localized Name' MUST be the same as what is stored in the database
	// 'English Name' MUST be the same as what is stored in a 'enUS' database
		// Example: $lang['translate']['Nachtelf']='Night Elf';
		// Example: $lang['translate']['Night Elf']='Night Elf';

// The main locale arrays 'class_to_en' and 'race_to_en' are added automatically

// Gender Translation
$lang['translate']['Homme']        = 'Male';
$lang['translate']['Femme']        = 'Female';


/**
 * SigGen Config localization
 * 'bout time I added this huh?
 */


// All SigGen Config strings here
// Some of these use format strings for sprintf()
// Keep the use of %1$s or %2$d for these so variables do not get switched around

// Also, keep everything html encoded
// That means <br /> not \n, &quot; not ", etc...

$lang['menu_siggen_config'] = 'SigGen Config';
$lang['menu_siggen_char'] = 'SigGen|Lists all SigGen images for this character';
$lang['siggen_install_desc'] = 'Signature and Avatar Generator';
$lang['title_siggen_config'] = 'SigGen Config v%1$s';
$lang['sql_queries'] = 'SigGen SQL Queries';
$lang['sql_messages'] = 'SigGen Messages';

$lang['fatal_error'] = '<span class="red">Fatal Error</span>';
$lang['no_gd_error'] = 'GD Functions are not available<br />SigGen REQUIRES GD with FreeType support';
$lang['config_notfound'] = 'SigGen &quot;Configuration&quot; file not included';
$lang['functions_notfound'] = 'SigGen Config &quot;Functions Class&quot; file not found<br />[<span class="green">%1$s</span>]';
$lang['upgrade_siggen'] = 'Please upgrade SigGen via RosterCP-&gt;Manage Addons';
$lang['saved_folder_created'] = 'Saved Signatures folder created';
$lang['saved_folder_not_created_manual'] = 'Saved Signatures folder COULD NOT be created<br />Create it manually';
$lang['saved_folder_chmoded'] = 'Saved Signatures folder is now writable';
$lang['saved_folder_not_chmoded_manual'] = 'Saved Signatures folder COULD NOT be set writable<br />Manually set write access';
$lang['custom_folder_created'] = 'Custom Images folder created';
$lang['custom_folder_not_created_manual'] = 'Custom Images folder COULD NOT be created<br />Create it manually';
$lang['custom_folder_chmoded'] = 'Custom Images folder is now writable';
$lang['custom_folder_not_chmoded_manual'] = 'Custom Images folder COULD NOT be set writable<br />Manually set write access';
$lang['cannot_find_main_images'] = '<span class="red">Fatal Error:</span> Cannot find Main Images folder [<span class="green">%1$s</span>]<br /><br />This <span class="red"><u>MUST</u></span> be fixed before you do <span class="red"><u>ANYTHING</u></span> else';
$lang['cannot_find_char_images'] = 'Cannot find Character Images folder<br />Make sure it is set correctly [<span class="green">%1$s</span>]';
$lang['cannot_find_font_folder'] = 'Cannot find Fonts folder<br />Make sure it is set correctly [<span class=\"green\">%1$s</span>]';
$lang['cannot_find_class_images'] = 'Cannot find Class Images folder<br />Make sure it is set correctly [<span class="green">%1$s</span>]';
$lang['cannot_find_backg_images'] = 'Cannot find Background Images folder<br />Make sure it is set correctly<br />[<span class="green">%1$s</span>]';
$lang['cannot_find_pvp_images'] = 'Cannot find PvP Logo Images folder<br />Make sure it is set correctly<br />[<span class="green">%1$s</span>]';
$lang['cannot_find_frame_images'] = 'Cannot find Frame Images folder<br />Make sure it is set correctly<br />[<span class="green">%1$s</span>]';
$lang['cannot_find_level_images'] = 'Cannot find Level Bubble Images folder<br />Make sure it is set correctly<br />[<span class="green">%1$s</span>]';
$lang['cannot_find_border_images'] = 'Cannot find Border Images folder<br />Make sure it is set correctly<br />[<span class="green">%1$s</span>]';
$lang['cannot_find_custom_folder'] = 'Custom Images folder does not exist<br />It is required if you want to upload custom user images<br />Click <a href="%1$s">HERE</a> to try to create [<span class="green">%2$s</span>]<br />Custom Image uploading is temporarily disabled';
$lang['cannot_writeto_custom_folder'] = 'Custom Images folder is not writable<br />Click <a href="%1$s">HERE</a> to try to chmod [<span class="green">%2$s</span>]<br />Custom Image uploading is temporarily disabled';
$lang['cannot_find_save_folder'] = 'Saved Signatures Folder does not exist<br />It is required when &quot;Save Image Mode&quot; is turned on<br />Click <a href="%1$s">HERE</a> to try to create [<span class="green">%2$s</span>]<br />Save Image functions are temporarily disabled';
$lang['cannot_writeto_save_folder'] = 'Saved Signatures Folder is not writable<br />Write access is required when &quot;Save Image Mode&quot; is turned on<br />Click <a href="%1$s">HERE</a> to try to chmod [<span class="green">%2$s</span>]<br />Save Image functions are temporarily disabled';
$lang['safemode_on'] = 'PHP <span class="green">safe_mode</span> is turned <span class="red">on</span><br />Image upload/delete functions <u>might</u> not operate properly';
$lang['iniset_off'] = 'PHP <span class="green">ini_set</span> is <span class="red">disabled</span> on this server<br />SigGen will not report all errors on created images';
$lang['cannot_check_version'] = 'Could not check for latest version of SigGen';
$lang['new_siggen_available'] = 'There is a new version of SigGen available <span class="green">v%1$s</span><br />Get it <a href="http://www.wowroster.net/Forums/viewforum/f=38.html" target="_blank">HERE</a>';

$lang['select_image_upload'] = 'Please select an image to upload';
$lang['select_name_upload'] = 'Please select a name before uploading an image';
$lang['image_mustbe_right'] = 'The image must be .png, .gif, or .jpg';
$lang['image_a_copy'] = 'You are uploading an exact copy of an existing image';
$lang['image_upload_failed'] = 'Upload of [<span class="purple">%1$s</span>] failed<br />You might not have write access to [<span class="green">%2$s</span>]';
$lang['image_upload_success'] = 'Upload of [<span class="purple">%1$s</span>] to [<span class="green">%2$s</span>] successful';
$lang['select_image_delete'] = 'Please select an image to delete';
$lang['image_deleted'] = '<span class="green">%1$s</span>: <span class="red">Deleted</span>';
$lang['image_delete_failed'] = '%1$s: Could not be deleted. I do not know why';
$lang['settings_changed'] = 'Settings have been changed';
$lang['settings_reset'] = 'Settings have been restored to defaults';
$lang['cannot_find_file'] = 'Cannot find file [%1$s]';
$lang['reset_checkbox'] = 'To reset, make sure you check the &quot;Check to confirm reset&quot; checkbox';
$lang['install_success'] = '%1$s was successfull...<br />Click <a href="%2$s">HERE</a> to continue<br />';

$lang['select_import_file'] = 'Please select a siggen_saved file to upload';
$lang['import_mustbe_right'] = 'The file must be [<span class="green">%1$s</span>]';
$lang['import_upload_success'] = 'SigGen import successful';
$lang['import_upload_failed'] = 'SigGen import failed';
$lang['import_upload_failed_ver'] = 'SigGen import failed<br />Import file version does not match current version<br /><div align="right">This version [<span class="green">%1$s</span>]<br />File version [<span class="red">%2$s</span>]';

$lang['config_exists'] = '%1$s config already exists, choose another name';
$lang['config_created'] = '%1$s config created';
$lang['config_deleted'] = '%1$s config deleted';
$lang['config_cannot_delete'] = 'You cannot delete a config mode that doesn\'t exist %1$s';
$lang['config_cannot_delete_default'] = 'You cannot delete a default config';
$lang['config_invalid'] = 'Config name contains invalid characters<br />Allowed characters are (a-z 0-9 _ -)';
