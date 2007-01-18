<?php
/******************************
* WoWRoster.net  UniAdmin
* Copyright 2002-2006
* Licensed under the Creative Commons
* "Attribution-NonCommercial-ShareAlike 2.5" license
*
* Short summary
*  http://creativecommons.org/licenses/by-nc-sa/2.5/
*
* Full license information
*  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
* -----------------------------
*
* $Id$
*
******************************/

if( !defined('IN_UNIADMIN') )
{
	exit('Detected invalid access to this file!');
}

// %1\$<type> prevents a possible error in strings caused
//      by another language re-ordering the variables
// $s is a string, $d is an integer, $f is a float

// <title> Titles
$lang['title_help'] = 'Help';
$lang['title_addons'] = 'AddOns';
$lang['title_logo'] = 'Logos';
$lang['title_settings'] = 'Settings';
$lang['title_stats'] = 'Statistics';
$lang['title_users'] = 'Users';
$lang['title_config'] = 'UniAdmin Config';
$lang['title_login'] = 'Login';


// Help page text
$lang['help'] = array(
array(	'header' => 'Intro',
'text'   => '
<p>I bet you\'re wondering either what this is and/or how to use it, so:</p>
<p>This is a system used to keep the users (who use UniUploader) addons, logos, and settings updated.<br />
When you upload an addon to this system, and hit [Update] in UU, UU will look up the &quot;Synchronization URL&quot; (the one in the frame on the left)<br />
and proceed to download any update(s) that are in any way different than the copy stored on the user\'s hard drive.<br />
UU will then replace the addon with the new copy of the addon from this system.</p>'),

array(	'header' => 'AddOns',
'text'   => '
<p>The uploaded addon must be in zip form only.<br />
The zip file must have the following directory structure: [folder],{file}, and not literally &quot;addonName&quot; or &quot;addonfile&quot;<br />
The AddOn Name is the same as the name of the folder that the AddOn\'s files are in</p>
<pre>[Interface]
     [AddOns]
          [addonName]
               {addonfile}
               {addonfile}
               {addonfile}
               {addonfile}
etc.</pre>'),

array(	'header' => 'Logos',
'text'   => '
<p>This changes the logos displayed in UniUploader/jUniUploader<br />
Logo 1 is displayed on the [Settings] tab<br />
Logo 2 is displayed on the [About] tab</p>'),

array(	'header' => 'Settings',
'text'   => '
<p>You can make sure your user\'s critical UU settings are up to date with this, be VERY careful with some of them, as some of them might get your users angry at you, and if you set something wrong you could loose contact with all of your users LOL<br />
If the setting is a 1 or zero that means it is a check mark in UU that should be: checked (1) or not checked (0).</p>
<p>The saved variables list is the actual list of files that you want UU to upload to the URL(s).</p>'),

array(	'header' => 'Statistics',
'text'   => '
<p>Basicly this shows who is accessing UniAdmin</p>
<p>The table shows each access</p>
<ul>
	<li> &quot;Action&quot; - What the client is asking for</li>
	<li> &quot;IP Address&quot; - The client\'s IP address</li>
	<li> &quot;Date/Time&quot; - The date/time accessed</li>
	<li> &quot;User Agent&quot; - What software is accessing it</li>
	<li> &quot;Host Name&quot; - The user\'s Host Name</li>
</ul>
<p>Below the table is nifty pie charts for how UniAdmin was accessed</p>'),

array(	'header' => 'Users',
'text'   => '
<p>There are 3 &quot;user levels&quot;</p>
<p>(Shows highest action available)</p>
<dl>
	<dt>level 1 (basic user) has access to</dt>
	<dd>1, 2, 3, 4, 5.3</dd>

	<dt>level 2 (power user) has access to</dt>
	<dd>1.1, 2, 3.1, 4, 5.7</dd>

	<dt>level 3 (administrator) has access to everything</dt>
	<dd>1.2, 2, 3.2, 4, 5.10, 6</dd>
	<dd>&nbsp;</dd>
</dl>
<p>There shouldn\'t have to be more than 1 or 2 &quot;level 3&quot; users in UniAdmin</p>
<hr />
<p>Access items key:</p>
<ul>
	<li> 1: AddOn Management
		<ul>
			<li> 1.1: Manage AddOns</li>
			<li> 1.2: Add/Delete AddOns</li>
		</ul></li>
	<li> 2: Logo Management</li>
	<li> 3: Settings Management
		<ul>
			<li> 3.1: Add/Remove SavedVariable Files</li>
			<li> 3.2: settings.ini upload/download</li>
		</ul></li>
	<li> 4: Statistics Management</li>
	<li> 5: User Management
		<ul>
			<li> 5.1: Change own language</li>
			<li> 5.2: Change own password</li>
			<li> 5.3: Delete own user</li>
			<li> 5.4: Change own username</li>
			<li> 5.5: Add level 1 users</li>
			<li> 5.6: Change level 1 user info (username, password, language)</li>
			<li> 5.7: Delete level 1 users</li>
			<li> 5.8: Add any level users</li>
			<li> 5.9: Delete any user</li>
			<li> 5.10: Change any user info (username, password, level, language)</li>
		</ul></li>
	<li> 6: UniAdmin Configuration</li>
</ul>'),
);


// Column Headers
$lang['name'] = 'Name';
$lang['toc'] = 'TOC';
$lang['required'] = 'Required';
$lang['version'] = 'Version';
$lang['uploaded'] = 'Uploaded';
$lang['enabled'] = 'Enabled';
$lang['disabled'] = 'Disabled';
$lang['files'] = 'Files';
$lang['url'] = 'URL';
$lang['delete'] = 'Delete';
$lang['disable_enable'] = 'Disable / Enable';
$lang['update_file'] = 'Update File';
$lang['updated'] = 'Updated';
$lang['setting_name'] = 'Setting Name';
$lang['description'] = 'Description';
$lang['value'] = 'Value';
$lang['filename'] = 'Filename';
$lang['row'] = 'Row';
$lang['action'] = 'Action';
$lang['ip_address'] = 'IP Address';
$lang['date_time'] = 'Date/Time';
$lang['user_agent'] = 'User Agent';
$lang['host_name'] = 'Host Name';



// Submit Buttons
$lang['login'] = 'Login';
$lang['logout'] = 'Logout';
$lang['on'] = 'On';
$lang['off'] = 'Off';
$lang['no'] = 'No';
$lang['yes'] = 'Yes';
$lang['add'] = 'Add';
$lang['remove'] = 'Remove';
$lang['enable'] = 'Enable';
$lang['disable'] = 'Disable';
$lang['modify'] = 'Modify';
$lang['check'] = 'Check';
$lang['proceed'] = 'Proceed';
$lang['reset'] = 'Reset';
$lang['submit'] = 'Submit';
$lang['upgrade'] = 'Upgrade';
$lang['update_logo'] = 'Update Logo %1$d';
$lang['update_settings'] = 'Update Settings';
$lang['show'] = 'Show';
$lang['add_update_addon'] = 'Add / Update AddOn';
$lang['import'] = 'Import';
$lang['export'] = 'Export';
$lang['go'] = 'Go';


// Form Element Descriptions
$lang['current_password'] = 'Current Password';
$lang['current_password_note'] = 'You must confirm your current password if you wish to change your username or password';
$lang['confirm_password'] = 'Confirm Password';
$lang['confirm_password_note'] = 'You only need to confirm your new password if you changed it above';
$lang['language'] = 'Language';
$lang['new_password'] = 'New Password';
$lang['new_password_note'] = 'You only need to supply a new password if you want to change it';
$lang['change_username'] = 'Change Username';
$lang['change_password'] = 'Change Password';
$lang['change_userlevel'] = 'Change Userlevel';
$lang['change_language'] = 'Change Language';
$lang['basic_user_level_1'] = 'basic user (level 1)';
$lang['power_user_level_2'] = 'power user (level 2)';
$lang['admin_level_3'] = 'administrator (level 3)';
$lang['password'] = 'Password';
$lang['username'] = 'Username';
$lang['users'] = 'Users';
$lang['add_user'] = 'Add User';
$lang['modify_user'] = 'Modify User';
$lang['current_users'] = 'Current Users';
$lang['select_file'] = 'Select file';
$lang['userlevel'] = 'User Level';
$lang['addon_management'] = 'AddOn Management';
$lang['addon_uploaded'] = '%1$s was uploaded successfully';
$lang['view_addons'] = 'View AddOns';
$lang['required_addon'] = 'Required AddOn';
$lang['homepage'] = 'Homepage';
$lang['logged_in_as'] = 'Logged in as [%1$s]';
$lang['logo_table'] = 'Logo %1$d';
$lang['logo_uploaded'] = 'Logo %1$d was uploaded successfully';
$lang['uniuploader_sync_settings'] = 'UniUploader Sync Settings';
$lang['uniadmin_config_settings'] = 'UniAdmin Config Settings';
$lang['manage_svfiles'] = 'Manage SavedVariable Files';
$lang['add_svfiles'] = 'Add SavedVariable Files';
$lang['svfiles'] = 'SavedVariable Files';
$lang['image_missing'] = 'IMAGE MISSING';
$lang['stats_limit'] = 'row(s) starting from record #';
$lang['user_modified'] = 'User %1$s modified';
$lang['user_added'] = 'User %1$s added';
$lang['user_deleted'] = 'User %1$s deleted';
$lang['access_denied'] = 'Access Denied';
$lang['settings_file'] = 'settings.ini File';
$lang['import_file'] = 'Import File';
$lang['export_file'] = 'Export File';
$lang['settings_updated'] = 'Settings Updated';
$lang['download'] = 'Download';
$lang['user_style'] = 'User Style';
$lang['change_style'] = 'Change Style';
$lang['fullpath_addon'] = 'Full Path Addon';
$lang['addon_details'] = 'AddOn Details';
$lang['manage'] = 'Manage';
$lang['optional'] = 'Optional';
$lang['notes'] = 'Notes';
$lang['half'] = 'Half';
$lang['full'] = 'Full';


// Pagination
$lang['next_page'] = 'Next Page';
$lang['page'] = 'Page';
$lang['previous_page'] = 'Previous Page';


// Miscellaneous
$lang['time_format'] = 'M jS, Y g:ia';
$lang['syncro_url'] = 'Synchronization URL';
$lang['verify_syncro_url'] = 'click to verify';
$lang['guest_access'] = 'Guest Access';
$lang['interface_ready'] = 'UniUploader Update Interface Ready...';


// Addon Management
$lang['addon_required_tip'] = 'When checked, UniUploader will require this addon for download';
$lang['addon_fullpath_tip'] = 'This is for addons that extract directly to the World of Warcraft directory<br /><br />- [yes] Extract addon to WoW/<br />- [no] Extract to WoW/Interface/AddOns/';
$lang['addon_selectfile_tip'] = 'Select an addon to upload';



// UU Sync Settings

// Each setting for the UniUploader Setting Sync is listed here
// The keyname has to be exactly the same as the name in the DB and the name thats is used in UniUploader
// Any text must be html entity encoded first!
// Each group is separated by section based on the settings.ini file

// settings
$lang['LANGUAGE'] = 'Language';
$lang['PRIMARYURL'] = 'Upload SavedVariable files to this URL';
$lang['PROGRAMMODE'] = 'Program mode';
$lang['AUTODETECTWOW'] = 'Auto detect WoW';
$lang['OPENGL'] = 'Run WoW in OpenGL mode';
$lang['WINDOWMODE'] = 'Run WoW in window mode';

// updater
$lang['UUUPDATERCHECK'] = 'Check for UniUploader updates/upgrades';
$lang['SYNCHROURL'] = 'URL for synchronization with UniAdmin';
$lang['ADDONAUTOUPDATE'] = 'AddOn auto-update';
$lang['UUSETTINGSUPDATER'] = 'Sync UniUploader settings with UniAdmin';

// options
$lang['AUTOUPLOADONFILECHANGES'] = 'Auto upload on SavedVariable file changes';
$lang['ALWAYSONTOP'] = 'Set UniUploader always on top';
$lang['SYSTRAY'] = 'Display UniUploader in the system tray';
$lang['ADDVAR1CH'] = 'Additional variable 1';
$lang['ADDVARNAME1'] = 'Additional variable 1 name (default-&gt;username)';
$lang['ADDVARVAL1'] = 'Additional variable 1 value';
$lang['ADDVAR2CH'] = 'Additional variable 2';
$lang['ADDVARNAME2'] = 'Additional variable 2 name (default-&gt;password)';
$lang['ADDVARVAL2'] = 'Additional variable 2 value';
$lang['ADDVAR3CH'] = 'Additional variable 3';
$lang['ADDVARNAME3'] = 'Additional variable 3 name';
$lang['ADDVARVAL3'] = 'Additional variable 3 value';
$lang['ADDVAR4CH'] = 'Additional variable 4';
$lang['ADDVARNAME4'] = 'Additional variable 4 name';
$lang['ADDVARVAL4'] = 'Additional variable 4 value';
$lang['ADDURL1CH'] = 'Additional URL 1';
$lang['ADDURL1'] = 'Additional URL 1 location';
$lang['ADDURL2CH'] = 'Additional URL 2';
$lang['ADDURL2'] = 'Additional URL 2 location';
$lang['ADDURL3CH'] = 'Additional URL 3';
$lang['ADDURL3'] = 'Additional URL 3 location';
$lang['ADDURL4CH'] = 'Additional URL 4';
$lang['ADDURL4'] = 'Additional URL 4 location';

// advanced
$lang['AUTOLAUNCHWOW'] = 'Auto-Launch WoW';
$lang['WOWARGS'] = 'Launch program arguments';
$lang['STARTWITHWINDOWS'] = 'Start UniUploader with windows';
$lang['USELAUNCHER'] = 'Launch using WoW launcher';
$lang['STARTMINI'] = 'Start minimized';
$lang['SENDPWSECURE'] = 'MD5 encrypt variable 2 value (password field) before sending';
$lang['GZIP'] = 'gZip compression';
$lang['DELAYUPLOAD'] = 'Upload delay';
$lang['DELAYSECONDS'] = 'Upload delay seconds';
$lang['RETRDATAFROMSITE'] = 'Web==&gt;WoW - Retrieve data';
$lang['RETRDATAURL'] = 'Web==&gt;WoW - Data retrieval URL';
$lang['WEBWOWSVFILE'] = 'Web==&gt;WoW - Write to SavedVariable filename';
$lang['DOWNLOADBEFOREWOWL'] = 'Web==&gt;WoW - Initiate Before UU Launches WoW';
$lang['DOWNLOADBEFOREUPLOAD'] = 'Web==&gt;WoW - Initiate Before UU Uploads';
$lang['DOWNLOADAFTERUPLOAD'] = 'Web==&gt;WoW - Initiate After UU Uploads';

// END UU Sync Strings


// BEGIN UA CONFIG SETTINGS

$lang['admin']['addon_folder'] = 'Specify the folder addon zip archives will be saved';
$lang['admin']['default_lang'] = 'Default language of the UniAdmin interface<br /><br />Values here are automatically scanned from the languages directory';
$lang['admin']['default_style'] = 'The default display style';
$lang['admin']['enable_gzip'] = 'Enable gzip compression when displaying UniAdmin Pages';
$lang['admin']['interface_url'] = 'Specify the location of interface.php here<br /><br />Use %url% to insert the base url<br />Default is &quot;%url%?p=interface&quot; or &quot;%url%interface.php&quot;';
$lang['admin']['logo_folder'] = 'Specify the folder UniUploader logos will be saved';
$lang['admin']['temp_analyze_folder'] = 'Specify the folder addon zip archives will be extracted to and anaylized';
$lang['admin']['UAVer'] = 'Current UniAdmin version<br />You cannot change this setting';
$lang['admin']['ua_debug'] = 'Debugging for UniAdmin<br /><br />- [no] No debugging<br />- [half] Show query count and rendertime in the footer<br />- [full] Show query count, rendertime, and SQL query window in the footer';

// END UA CONFIG SETTINGS


// Debug
$lang['queries'] = 'Queries';
$lang['debug'] = 'Debug';
$lang['messages'] = 'Messages';


// Error messages
$lang['error'] = 'UniAdmin Error';
$lang['error_invalid_login'] = 'You have provided an incorrect or invalid username or password';
$lang['error_delete_addon'] = 'Delete AddOn Error';
$lang['error_enable_addon'] = 'Enable AddOn Error';
$lang['error_disable_addon'] = 'Disable AddOn Error';
$lang['error_require_addon'] = 'Require AddOn Error';
$lang['error_optional_addon'] = 'Optional AddOn Error';
$lang['error_no_addon_in_db'] = 'No AddOns in Database';
$lang['error_no_addon_uploaded'] = 'No AddOn Uploaded';
$lang['error_no_files_addon'] = 'No files were detected in the uploaded AddOn';
$lang['error_no_toc_file'] = 'No \'.toc\' file was detected in the uploaded AddOn';
$lang['error_unzip'] = 'Zip Handling Error';
$lang['error_pclzip'] = 'PCLZip Unrecoverable Error: [%1$s]';
$lang['error_addon_process'] = 'AddOn Processing Error';
$lang['error_zip_file'] = 'The uploaded addon <u>must</u> be a zip file';

$lang['error_no_ini_uploaded'] = 'settings.ini file was not uploaded';
$lang['error_ini_file'] = 'The uploaded file <u>must</u> be settings.ini from UniUploader';

$lang['error_chmod'] = 'Could not chmod [%1$s]<br />chmod Manually and/or check file persmissions';
$lang['error_mkdir'] = 'Could not mkdir [%1$s]<br />mkdir Manually and/or check file persmissions';
$lang['error_unlink'] = 'Could not unlink(delete) [%1$s]<br />Delete Manually and/or check file persmissions';
$lang['error_move_uploaded_file'] = 'Could not move [%1$s] to [%2$s]<br />Check php upload settings and file persmissions';

$lang['error_no_uploaded_logo'] = 'No Logo Uploaded';
$lang['error_logo_format'] = 'The uploaded file <u>must</u> be a GIF image';


// SQL Error Messages
$lang['sql_error'] = 'SQL Error';
$lang['sql_error_addons_list'] = 'Could not get AddOns List';
$lang['sql_error_addons_disable'] = 'AddOn with ID:%1$d could not be disabled';
$lang['sql_error_addons_enable'] = 'AddOn with ID:%1$d could not be enabled';
$lang['sql_error_addons_require'] = 'AddOn with ID:%1$d could not be set to required';
$lang['sql_error_addons_optional'] = 'AddOn with ID:%1$d could not be set to optional';
$lang['sql_error_addons_delete'] = 'AddOn with ID:%1$d could not be deleted from the database<br />Remove Manually';
$lang['sql_error_addons_insert'] = 'Could not insert main addon data';
$lang['sql_error_addons_files_insert'] = 'Could not insert addon files\' data';

$lang['sql_error_logo_toggle'] = 'Could not set logo %1$s';
$lang['sql_error_logo_remove'] = 'Could not remove logo id=%1$d from database';
$lang['sql_error_logo_insert'] = 'Could not insert logo in the database';

$lang['sql_error_settings_update'] = 'Could not update setting =&gt; %1$s, value =&gt; %2$s, enabled =&gt; %3$d';
$lang['sql_error_settings_sv_insert'] = 'Could not insert savedvariable filename &quot;%1$s&quot;';
$lang['sql_error_settings_sv_remove'] = 'Could not remove savedvariable filename &quot;%1$s&quot;';

$lang['sql_error_user_modify'] = 'Could not update user info for &quot;%1$s&quot;';
$lang['sql_error_user_add'] = 'Could not add user &quot;%1$s&quot;';
$lang['sql_error_user_delete'] = 'Could not delete user &quot;%1$s&quot;';

