<?php
/** 
 * Dev.PKComp.net WoWRoster Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    Accounts 
 * @subpackage Locale enUS
 */ 

// -[ enUS Localization ]- 

// Menu Panel
$lang['menupanel_acc_menu']		= 'Account';

// Menu Buttons
$lang['acc_menu'] = array(
	'index' => 'My Account|Displays your characters, guilds, and realms.',
	'register' => 'Register|Displays the account registration page.',
	'chars' => 'My Characters|Displays your characters.',
	'guilds' => 'My Guilds|Displays your guilds.',
	'realms' => 'My Realms|Displays your realms.',
	'settings' => 'Settings|Displays the user settings.',
);

// Interface wordings
$lang['acc_int'] = array(
	'register' => 'Register',
	'no_register' => 'Not registered yet?',
	'uname' => 'User Name',
	'fanme' => 'First Name',
	'lname' => 'Last Name',
	'email' => 'E-Mail',
	'group' => 'Group Password',
	'login_txt' => 'Please enter your username and password.',
	'no_login' => 'You must be logged in to view this page.',
	'reg_txt' => 'Please fill in the following fields (fields with * are required).',
	'activation' => 'Enter your new password here, ',
	'no_access' => 'You are not allowed to view this page!',
	'main_page' => 'Accounts Main Page',
	'forgot' => 'Forgot your username/password?',
	'forgot_txt' => 'Please enter the e-mail address that you used during registration.',
	'remember' => 'Remember login?',
	'login' => 'Login',
	'logged_out' => 'You have successfully been logged out!',
	'click' => 'Click here.',
	'user_group' => 'User Group',
	'conf_mail' => 'Confirmation E-Mail',
);

// Page names
$lang['acc_page'] = array(
	'main' => 'Accounts Main',
	'user_admin' => 'User Admin',
	'no_access' => 'Access Denied!',
	'register' => 'Accounts Registration',
	'application' => 'User Application',
	'registration' => 'User Registration',
	'user_act' => 'User Activation',
	'pass_act' => 'Password Activation',
	'settings' => 'My Settings',
	'realms' => 'My Realms',
	'guilds' => 'My Guilds',
	'chars' => 'My Characters',
);

// Config page names

$lang['admin']['acc_display']	= 'Configuration|Configure options specific to accounts.';
$lang['admin']['acc_perms']		= 'Page Permissions|Set which groups can view which pages.';
$lang['admin']['acc_user']		= 'User Config|Configure your account.';
$lang['admin']['acc_plugin']	= 'Manage Plugins|Install plugins to extend the user system.';
$lang['admin']['acc_recruit']	= 'Recruitment Config|Configure your recruitment settings.';
$lang['admin']['acc_register']	= 'Registration Config|Configure the settings for user registration.';
$lang['admin']['acc_session']	= 'Session Config|Configure the settings for accounts sessions.';

// Plugins Installer 
$lang['pagebar_plugininst']		= 'Manage Plugins';
$lang['installer_plugininfo']	= 'Description';

// Settings on config config
$lang['admin']['acc_char_conf'] 	= 'Character Config|Should users configure their character viewing options?';
$lang['admin']['acc_realm_conf']	= 'Realm Config|Should users configure their realm viewing options?';
$lang['admin']['acc_guild_conf']  	= 'Guild Config|Should users configure their guild viewing options?';
$lang['admin']['acc_save_login']  	= 'Save Login|Use a cookie to remember the client login?';
$lang['admin']['acc_cookie_name']	= 'Cookie Name|Name for your session cookies.';
$lang['admin']['acc_auto_act']  	= 'Auto Activation|Should users be activated automatically?';
$lang['admin']['acc_admin_copy']  	= 'Activation Copy|Should the admin recieve a copy of the activation email?';
$lang['admin']['acc_admin_mail']  	= 'Admin E-Mail|Please enter the administrator email.';
$lang['admin']['acc_admin_name']  	= 'Admin Name|Please enter the administrators name.';
$lang['admin']['acc_pass_length']	= 'Password Length|Please enter the minimum length for user passwords.';
$lang['admin']['acc_uname_length']	= 'Username Length|Please enter the minimum length for usernames.';

// Settings on perms config
$lang['admin']['acc_use_perms']		= 'Use Permissions|Should page permissions be used?';
$lang['admin']['acc_min_access']	= 'Access Level|Minimum level for page access.';
$lang['admin']['acc_admin_level']	= 'Admin Level|The level for administration access.';

// Settings on user config
$lang['admin']['acc_uname']			= 'Username|Edit your username.';
$lang['admin']['acc_fname']			= 'First Name|Edit your first name.';
$lang['admin']['acc_lname']			= 'Last Name|Edit your last name.';
$lang['admin']['acc_pass']			= 'Password|Edit your password.';
$lang['admin']['acc_email']			= 'E-Mail|Edit your email address.';
$lang['admin']['acc_city']			= 'City|Edit your city.';
$lang['admin']['acc_country']		= 'Country|Edit your country.';
$lang['admin']['acc_homepage']		= 'Homepage|Edit the url to your homepage.';
$lang['admin']['acc_notes']			= 'Notes|Edit your notes.';
$lang['admin']['acc_extra_info']	= 'Extra Info|Add any extra info.';

//Settings on plugin config
$lang['admin']['acc_use_plugins']	= 'Use Plugins|Should plugins be used?';

//Settings on recruitment config
$lang['admin']['acc_use_recruit']	= 'Use Recruitment|Should recruitment be used?';
$lang['admin']['acc_rec_status']	= 'Recruitment Status|Current status for recruitment.';
$lang['admin']['acc_rec_druid']		= 'Druid|Current recruitment level.';
$lang['admin']['acc_rec_hunter']	= 'Hunter|Current recruitment level.';
$lang['admin']['acc_rec_mage']		= 'Mage|Current recruitment level.';
$lang['admin']['acc_rec_paladin']	= 'Paladin|Current recruitment level.';
$lang['admin']['acc_rec_priest']	= 'Priest|Current recruitment level.';
$lang['admin']['acc_rec_rouge']		= 'Rouge|Current recruitment level.';
$lang['admin']['acc_rec_shaman']	= 'Shaman|Current recruitment level.';
$lang['admin']['acc_rec_warlock']	= 'Warlock|Current recruitment level.';
$lang['admin']['acc_rec_warrior']	= 'Warrior|Current recruitment level.';

// Settings on registration config
$lang['admin']['acc_reg_text']		= 'Registration Text|Edit the welcome text on registration.';

// Settings on session config
$lang['admin']['acc_sess_time']		= 'Session Time|Edit the length of time before a session is ended.';

// User class messages
$lang['acc_user'] = array(
	'msg10' => 'Username and/or password did not match to the database.',
	'msg11' => 'Username and/or password is empty!',
	'msg12' => 'Sorry, a user with this login and/or e-mail address already exist.',
	'msg13' => 'Please check your e-mail and follow the instructions.',
	'msg14' => 'Sorry, an error occurred please try again.',
	'msg15' => 'Sorry, an error occurred please try again later.',
	'msg16' => 'The e-mail address is not valid.',
	'msg17' => 'The field login (min. %d char.) is required.',
	'msg18' => 'Your request has been processed. Login to continue.',
	'msg19' => 'Sorry, cannot activate your account.',
	'msg20' => 'There is no account to activate.',
	'msg21' => 'Sorry, this activation key is not valid!',
	'msg22' => 'Sorry, there is no active account that matches with this e-mail address.',
	'msg23' => 'Please check your e-mail to get your new password.',
	'msg25' => 'Sorry, cannot activate your password.',
	'msg26' => 'New user request...',
	'msg27' => 'Please check your e-mail and activate your modification(s).',
	'msg28' => 'Your request must be processed...',
	'msg29' => 'Hello %s,\r\n\r\nto activate your request click the following link:\r\n%s&uid=%d&act_key=%s',
	'msg30' => 'Your account is modified.',
	'msg31' => 'This e-mail address already exists, please use another one.',
	'msg32' => 'The field password (min. %d char) is required.',
	'msg33' => 'Hello %s,\r\n\r\nthe new e-mail address must be validated, click the following link:\r\n%s&uid=%d&validate=%s',
	'msg34' => 'There is no e-mail address for validation.',
	'msg35' => 'Hello %s,\r\n\r\nEnter your new password next, please click the following link to enter the form:\r\n%s&uid=%d&act_key=%s',
	'msg36' => "Your request has been processed and is pending validation by the admin. \r\nYou will get an e-mail if it's done.",
	'msg37' => "Hello %s,\r\n\r\nThe account is active and it's possible to login now.\r\n\r\nClick on this link to access the login page:\r\n%s\r\n\r\nkind regards\r\n%s",
	'msg38' => 'The confirmation password does not match the password. Please try again.',
	'msg39' => 'New user registration on %s:\r\n\r\nClick here to enter the admin page:\r\n\r\n%s',
);

// Profile class messages
$lang['acc_profile'] = array(
	'msg1' => 'There is no profile data at the moment.',
	'msg2' => 'Your profile data is up to date.',
	'msg3' => 'There was an error during update, please try again.',
);

// Memberslist Wordings
$lang['online_at_update']       	= 'Online at Update';
$lang['second']                 	= '%s second ago';
$lang['seconds']                	= '%s seconds ago';
$lang['minute']                 	= '%s minute ago';
$lang['minutes']                	= '%s minutes ago';
$lang['hour']                   	= '%s hour ago';
$lang['hours']                  	= '%s hours ago';
$lang['day']                    	= '%s day ago';
$lang['days']                   	= '%s days ago';
$lang['week']                   	= '%s week ago';
$lang['weeks']                  	= '%s weeks ago';
$lang['month']                  	= '%s month ago';
$lang['months']                 	= '%s months ago';
$lang['year']                   	= '%s year ago';
$lang['years']                  	= '%s years ago';

$lang['accounts']					= 'Accounts';
$lang['motd']						= 'MOTD';

$lang['xp_to_go']               	= '%1$s XP until level %2$s';

// Realmlist Wordings
$lang['acc_rs'] = array(
	'up' => 'Up',
	'down' => 'Down',
	'maintenance' => 'Maintenance',
	'rppvp' => 'RP-PvP',
	'pve' => 'Normal',
	'pvp' => 'PvP',
	'rp' => 'RP',
	'low' => 'Low',
	'medium' => 'Medium',
	'high' => 'High',
	'max' => 'Max',
	'error' => 'Unknown',
);
$lang['servertype']					= 'Realm Type';
$lang['serverstatus']				= 'Realm Status';
$lang['serverpop']					= 'Realm Population';

// Profile Settings
$lang['acc_settings_set']			= 'Settings|Edit the display settings for your characters.';
$lang['acc_settings_prof']			= 'Profile|Edit your profile information.';

$lang['tab1']						= 'Character';
$lang['tab2']						= 'Pet';
$lang['tab3']						= 'Reputation';
$lang['tab4']						= 'Skills';
$lang['tab5']						= 'PvP';

$lang['mailbox'] 					= 'Mailbox';
$lang['spellbook'] 					= 'Spellbook';
$lang['talents'] 					= 'Talents';
$lang['item_bonuses'] 				= 'Item Bonuses';