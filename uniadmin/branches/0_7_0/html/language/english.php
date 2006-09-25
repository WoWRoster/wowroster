<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

// %1\$<type> prevents a possible error in strings caused
//      by another language re-ordering the variables
// $s is a string, $d is an integer, $f is a float

// <title> Titles
$lang['title_help'] = 'Help';
$lang['title_addons'] = 'Addons';
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

	array(	'header' => 'Addons',
			'text'   => '
<p>The uploaded addon must be in zip form only.<br />
The zip file must have the following directory structure: [folder],{file}, and not literally &quot;addonName&quot; or &quot;addonfile&quot;<br />
The Addon Name is the same as the name of the folder that the Addon\'s files are in</p>
<pre>[Interface]
     [Addons]
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
<p>There are 3 &quot;user levels&quot;.</p>
<p>Access items key:</p>
<ul>
	<li> 1: addon management</li>
	<li> 2: logo management</li>
	<li> 3: settings management</li>
	<li> 4: statistics management</li>
	<li> 5: user management
		<ul>
			<li> 5.1: change own password</li>
			<li> 5.2: change own username</li>
			<li> 5.3: change level 1 usernames &amp; passwords</li>
			<li> 5.4: add level 1 users</li>
			<li> 5.5: add any level users</li>
			<li> 5.6: delete level 1 users</li>
			<li> 5.7: delete own username</li>
			<li> 5.8: delete any username</li>
			<li> 5.9: change any user\'s level</li>
		</ul></li>
</ul>

<dl>
	<dt>level 1 (basic user) has access to</dt>
	<dd>1, 2, 3, 4, 5.1, 5.7</dd>

	<dt>level 2 (power user) has access to</dt>
	<dd>1, 2, 3, 4, 5.1, 5.2, 5.3, 5.4, 5.6, 5.7</dd>

	<dt>level 3 (administrator) has access to</dt>
	<dd>1, 2, 3, 4, 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 5.8, 5.9 (everything)</dd>
	<dd>&nbsp;</dd>
	<dd>There shouldn\'t have to be more than 1 or 2 &quot;level 3&quot; users in UniAdmin</dd>
</dl>'),
);


// Column Headers
$lang['name'] = 'Name';
$lang['toc'] = 'TOC';
$lang['required'] = 'Required';
$lang['version'] = 'Version';
$lang['uploaded'] = 'Uploaded';
$lang['enabled'] = 'Enabled';
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
$lang['add_update_addon'] = 'Add / Update Addon';


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
$lang['addon_management'] = 'Addon Management';
$lang['view_addons'] = 'View Addons';
$lang['required_addon'] = 'Required Addon';
$lang['homepage'] = 'Homepage';
$lang['logged_in_as'] = 'Logged in as [%1$s]';
$lang['logo_table'] = 'Logo %1$d';
$lang['uniuploader_sync_settings'] = 'UniUploader Sync Settings';
$lang['manage_svfiles'] = 'Manage SavedVariable Files';
$lang['add_svfiles'] = 'Add SavedVariable Files';
$lang['image_missing'] = 'IMAGE MISSING';
$lang['stats_limit'] = 'row(s) starting from record #';
$lang['user_modified'] = 'User %1$s modified';
$lang['user_added'] = 'User %1$s added';
$lang['user_deleted'] = 'User %1$s deleted';


// Pagination
$lang['next_page'] = 'Next Page';
$lang['page'] = 'Page';
$lang['previous_page'] = 'Previous Page';


// Miscellaneous
$lang['time_format'] = 'M jS, Y g:ia';
$lang['syncro_url'] = 'Synchronization URL';
$lang['verify_syncro_url'] = 'click to verify';
$lang['guest_access'] = 'Guest Access';




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
$lang['ADDONAUTOUPDATE'] = 'Addon auto-update';
$lang['UUSETTINGSUPDATER'] = 'Sync UniUploader settings with UniAdmin';

// options
$lang['AUTOUPLOADONFILECHANGES'] = 'Auto upload on SavedVariable file changes';
$lang['ALWAYSONTOP'] = 'Set UniUploader always on top';
$lang['SYSTRAY'] = 'Display UniUploader in the system tray';
$lang['USERAGENT'] = 'The User-Agent UU will use';
$lang['ADDVAR1CH'] = 'Additional variable 1';
$lang['ADDVARNAME1'] = 'Additional variable 1 (default-&gt;username) name';
$lang['ADDVARVAL1'] = 'Additional variable 1 value';
$lang['ADDVAR2CH'] = 'Additional variable 2';
$lang['ADDVARNAME2'] = 'Additional variable 2 (default-&gt;password) name';
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
$lang['RETRDATAFROMSITE'] = 'Web=&gt;WoW - Retrieve data';
$lang['RETRDATAURL'] = 'Web=&gt;WoW - Data retrieval URL';
$lang['WEBWOWSVFILE'] = 'Web=&gt;WoW - Write to SavedVariable filename';
$lang['DOWNLOADBEFOREWOWL'] = 'Web=&gt;WoW - Initiate Before UU Launches WoW';
$lang['DOWNLOADBEFOREUPLOAD'] = 'Web=&gt;WoW - Initiate Before UU Uploads';
$lang['DOWNLOADAFTERUPLOAD'] = 'Web=&gt;WoW - Initiate After UU Uploads';

// unknown
$lang['SYNCHROAUTOURL'] = '(UU 1.x) Synchronization Auto-URL';
$lang['AUTOPATH'] = '(UU 1.x) Auto-Path';
$lang['PREPARSE'] = '(UU 1.x) Pre-Parse';
$lang['PARSEVAR2CH'] = '(UU 1.x) Pre-Parse variable 2';
$lang['PARSEVAR3CH'] = '(UU 1.x) Pre-Parse variable 3';
$lang['PARSEVAR4CH'] = '(UU 1.x) Pre-Parse variable 4';
$lang['PARSEVAR5CH'] = '(UU 1.x) Pre-Parse variable 5';
$lang['PARSEVAR1'] = '(UU 1.x) Pre-Parse variable 1 name';
$lang['PARSEVAR2'] = '(UU 1.x) Pre-Parse variable 2 name';
$lang['PARSEVAR3'] = '(UU 1.x) Pre-Parse variable 3 name';
$lang['PARSEVAR4'] = '(UU 1.x) Pre-Parse variable 4 name';
$lang['PARSEVAR5'] = '(UU 1.x) Pre-Parse variable 5 name';
$lang['RETRDATA'] = '(UU 1.x) Retrieve data';
$lang['ADDURLFFNAME1'] = '(UU 1.x) Additional URL 1 file-field name';
$lang['ADDURLFFNAME2'] = '(UU 1.x) Additional URL 2 file-field name';
$lang['ADDURLFFNAME3'] = '(UU 1.x) Additional URL 3 file-field name';
$lang['ADDURLFFNAME4'] = '(UU 1.x) Additional URL 4 file-field name';

// END UU Sync Strings



// Debug
$lang['queries'] = 'Queries';
$lang['debug'] = 'Debug';
$lang['messages'] = 'Messages';


// Settings
$lang['default_locale'] = 'Default Locale';


// Error messages
$lang['error'] = 'UniAdmin Error';
$lang['error_invalid_login'] = 'You have provided an incorrect or invalid username or password';
$lang['error_delete_addon'] = 'Delete Addon Error';
$lang['error_enable_addon'] = 'Enable Addon Error';
$lang['error_disable_addon'] = 'Disable Addon Error';
$lang['error_require_addon'] = 'Require Addon Error';
$lang['error_optional_addon'] = 'Optional Addon Error';
$lang['error_no_addon_in_db'] = 'No Addons in Database';
$lang['error_no_addon_uploaded'] = 'No Addon Uploaded';
$lang['error_no_files_addon'] = 'No files were detected in the uploaded AddOn';
$lang['error_no_toc_file'] = 'No \'.toc\' file was detected in the uploaded AddOn';

$lang['error_unzip'] = 'Zip Handling Error';
$lang['error_pclzip'] = 'PCLZip Unrecoverable Error: [%1$s]';
$lang['error_addon_process'] = 'Addon Processing Error';
$lang['error_chmod'] = 'Could not chmod [%1$s]<br />chmod Manually and/or check file persmissions';
$lang['error_mkdir'] = 'Could not mkdir [%1$s]<br />mkdir Manually and/or check file persmissions';
$lang['error_unlink'] = 'Could not unlink(delete) [%1$s]<br />Delete Manually and/or check file persmissions';
$lang['error_move_uploaded_file'] = 'Could not move [%1$s] to [%2$s]<br />Check php upload settings and file persmissions';

$lang['error_no_uploaded_logo'] = 'No Logo Uploaded';
$lang['error_logo_format'] = 'The Uploaded file MUST be a GIF IMAGE!';


// SQL Error Messages
$lang['sql_error'] = 'SQL Error';
$lang['sql_error_addons_list'] = 'Could not get Addons List';
$lang['sql_error_addons_disable'] = 'Addon with ID:%1$d could not be disabled';
$lang['sql_error_addons_enable'] = 'Addon with ID:%1$d could not be enabled';
$lang['sql_error_addons_require'] = 'Addon with ID:%1$d could not be set to required';
$lang['sql_error_addons_optional'] = 'Addon with ID:%1$d could not be set to optional';
$lang['sql_error_addons_delete'] = 'Addon with ID:%1$d could not be deleted from the database<br />Remove Manually';
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


?>