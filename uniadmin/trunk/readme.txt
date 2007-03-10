UniAdmin 0.7.6 (updated 9th March 2007)
===============================================
UniAdmin is a back-end web-based tool for managing the configuration of and logos in UniUploader and auto-updating WoW addons.

If you don't know what UniUploader is, then... well... you're a bit ahead of yourself then aren't you? ;-)


Table of Contents
=================
   1 - Requirements
   2 - Installation
   3 - Upgrading
   4 - Thanks
   5 - FAQ
   6 - Support
   7 - License
   8 - Known Bugs / Gotchyas
   9 - Change Log
  10 - The Future


1 - Requirements
================
- A web server (Apache, IIS, or any software able to run php)
- PHP 4.3 or higher (http://www.php.net)
- MySQL Database 4.0 or higher (http://www.mysql.com)


2 - Installation
================
1. Create a new database (eg. uniadmin)
2. Upload the contents of the zip file to your webserver
3. After FTPing, CHMOD the following folders to 0777, or change NTFS file permissions for these folders to make them
   available as "Everyone - Write" on a Windows machine.

[folders]
  addon_temp
  addon_zips
  cache
  logos

4. Go to your UniAdmin install on the web and follow the instructions

The admin user is created on installation
Read the help page for additional info.


3 - Upgrading
=============
1. Run ?p=upgrade and follow the instructions


   3a - Upgrade from v0.7.0
   ========================
   Upgrading from v0.7.0 to a higher version will force you to install fresh.
   There is no upgrade from v0.7.0


   3b - Upgrade from v0.7.5
   ========================
   It is suggested that you clear all your addons after upgrading
   This is because the auto "Full Path" scanner has been implemented
   javaUniUploader and phpUniUploader require this new setting to function properly


4 - Thanks
==========
sturmy   - French localization
fubu2k   - German localization
Carasak
Shadowsong
Zajsoft  - Great modifications to AddOn uploading, providing a better .toc file scanner
Zeryl    - Thanks for help with parsing strings into multi-dimensional arrays
           o Thanks for the WoWAce module code


5 - FAQ
======
Q. I'm not sure what I set SYNCHROURL and PRIMARYURL to in the Settings Management page.
A. SYNCHROURL is the URL path to the UniAdmin interface.php, eg. http://www.myserver.com/uniadmin/interface.php
   PRIMARYURL is the URL path to the upload page of the system you are sending data to, eg. for the WoW Roster
   it would be http://www.myserver.com/roster/update.php . For other systems, please check with the site
   owner or their on-line help for the appropriate URL.

Q. I'm still confused about the settings in the Settings Management page and how to configure them.
A. 1. First, hover your mouse over each setting, you'll get a tooltip with the corresponding part of the UniUploader interface.
   2. If you are still confused
     a. Manually configure UniUploader with the settings needed for your config.
       - Open the settings.ini and you'll see all the settings just like in the Settings Management page.
     b. Or just upload your copy of settings.ini to the settings page in UniAdmin.

Q. How do I reset a password
A. 1. Go to http://gdataonline.com/makehash.php
   2. Type desired password in
   3. Generate the hash
   4. Put the hash in the password field of users table in UniAdmin database - for the correct user(s)


6 - Support
===========
For any support issues, questions, comments, feedback, or suggestions
please go to the support forums here - http://www.wowroster.net/Forums/viewforum/f=24.html


7 - License
===========
UniAdmin is licensed under a Creative Commons
"Attribution-NonCommercial-ShareAlike 2.5" license.
See http://creativecommons.org/licenses/by-nc-sa/2.5/ for the short
summary, and
http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode for the
full license information.

Regarding Attribution:
- Keep the credits in the footer of the UniAdmin pages.
- Include this license with all modified versions of UniAdmin.

Regarding ShareAlike:
- Any changes made to the code (including, but not limited to,
  HTML, PHP, CSS, SQL, images, and Javascript) must be archived
  and accessible for public download. You may, of course, remove
  username, password, and database host information from the archive.

For any reuse or distribution, you must make clear to others the
license terms of this work. Any of these conditions can be waived if
you get permission from the dev team at wowroster.net.



UniAdmin uses the following libraries as well

- PclZip Library -  PHP Class to create and manage ZIP files
  http://www.phpconcept.net
  Licensed under GNU/LGPL - http://www.gnu.org/licenses/lgpl.html
  File located at [include/pcl.lib.php]
  You may upgrade this file at anytime with the release version


- The Overlib tooltip library by Erik Bosrup
  http://www.bosrup.com/web/overlib
  An unnamed license applies, available at http://www.bosrup.com/web/overlib/?License
  File located at [overlib/overlib.js]


- Table sorting and pagination javascript by Brian
  http://www.frequency-decoder.com
  Licensed under a Creative Commons Attribution-ShareAlike 2.5 license - http://creativecommons.org/licenses/by-sa/2.5
  File located at [styles/default/tablesort.js]


- The installer and upgrader is based on EQdkp's
  This concerns the files:
    modules/install.php
    modules/upgrade.php

- The templating system is based on EQdkp's
  This concerns include/template.php

  These 3 files are licensed under the GNU General Public License,
  which is available at http://gnu.org/copyleft/gpl.html


- MiniXML - http://minixml.psychogenic.com
  PHP class for generating and parsing XML
  Licensed under the GNU General Public License - http://gnu.org/copyleft/gpl.html


8 - Known Bugs / Gotchyas
=========================
Bug: Addon zip files that have more than one addon in them may show up funny
Solution: After you upload an addon zip, edit the info on the addon details page


9 - Change Log
===============
~ Fixed
+ Added
! Changed

v0.7.6
~ slashes are now stripped in stats module
~ ADDVARVAL2 is now a password field since it's usually a password
~ Edited pclzip.lib.php detection of windows to something that servers don't block
~ Removed curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    It isn't needed and some servers block this option
~ get_remote_contents file get function in include/uniadmin.php
    This was causing the no toc errors for wowace addons
~ Removed umask in write_file function in include/uniadmin.php
~ Added improper module name detection in index.php, to eliminate remote file inclusion hacks
~ Reduced the queries on the stats page down to 9, Thanks alot PleegWat!
~ Sorting on stats page
~ Added user agent matching for jUU so compat mode setting will be active
~ Logo file paths are now determined by the current url
~ settings.ini scanner will not scan certain values (IE: account name)
~ SQL queries will never show to anonymous users
~ Links in installer will now properly point to index.php instead of install.php
~ XML output is now encoded properly with the right headers
~ TOC scanner, found a few addons' toc files that it didn't catch properly
! UA will now die with an error if php is not 4.3 and higher
! The Help tab is now "selected" when there is no page defined in the url
! UA now only accepts and scans certain file types for addons
    lua,toc,txt,tga,blp,ttf,xml,wav,mp3,nopatch
    If there are other, NON-executable file extentions, let us know!
    PclZip has an option to run a pre-extract function
    function pclzip_pre_extract() in include/uniadmin.php
    Files not on the allowed list are not even extracted
! Addon note tooltip now shows over entire 'name' cell
! Tables that use the js sort are not initially sorted, improving page load times
! Error message rows are now reddish
! Set header() to xml for addon output and settings xml output
! Logo module has been edited so different image file types could be used in the future
! Removed `download_url` field from logo table, `filename` is used now
! All remote addons (wowace) will now be stored locally
    This is so UA admins can control what addon version UU users download
! Full path addon detection
    There is now 3 options [automatic] [yes] [no]
     - Automatic will attempt to auto-detect if the addon should be treated as full path or not
    Addon XML variable "full_path" is now set if the addon should be treated as full path
     - 0 = extract to WoW/Interface/AddOns/
     - 1 = extract to WoW/
! Addon xml file list is only outputted if there are addons in the UA db
! Addon xml output is now sorted by required/optional, then by name
! Logo output is sorted now sorted numerically
! Setting and sv list output is sorted by name
! function get_file_ext() now uses pathinfo
! addon xml filename="" attribute is now the full url path to the file
+ addon_zip folder scanning
    UniAdmin now scans this folder and presents you with addon zips that have not been scanned into the database
    Use this feature to ftp larger addon zips to UniAdmin
+ Addon notes to addon xml output
+ New get settings mode, xml output
    Use ?OPERATION=GETSETTINGSXML
+ Now using the minixml library to generate xml output in interface.php
    http://minixml.psychogenic.com
    This allows proper formatting and escaping
+ New constant for allowed logo image types
    Logo image types allowed are 'jpg,jpeg,png,ico,gif'
    Others could have been added, but some formats are too large to be downloaded quickly
+ Global addon deletion, to delete every addon from your UniAdmin install
+ Error handling class
    Catchable PHP errors are now displayed at the bottom of the page above sql queries
+ is_ua_admin() for an easier way to check if the user is an admin


v0.7.5
~ Removed all dead files
~ Massive code cleanup
! Overhauled the addons page
~ Addon file paths are now determined by the current url
~ Simplified main SQL query, now only one query
~ SVLIST is now scanned when exporting settings.ini
~ Fixed level 1 users so they can change locale and theme
~ Not using $pipe_sep in interface.php
~ Made a call to $uniadmin->config() after changing settings in UA config so they will show correctly
~ Notice userN when editing self user in User Config page
~ Fixed error on UA config page dealing with default style
~ index.php to use the page variable constant defined in constants.php
+ Added installer and upgrader
    Moved files around in zip package now that UA has an installer
    PRIMARYURL, SYNCHROURL, RETRDATAURL are set with default values on install
    If config.php doesn't exist, UA will direct to the installer
+ Homepage and filename to xml in interface.php
+ Implemented code from Zajsoft (thanks a bunch!)
+ Addon list sorting
+ File size is now calculated
+ Directory tree file listing (html list output)
    Big thanks to Zeryl on this, without him, this would not be here
+ Added password confirmation
    If you edit yourself, you need to enter old password
    If your level > user, then no old password is needed
+ If adding a new user, and there is a form validation error, some of the info will be still in the form (name, level, lang, style)
+ Added ua_die() to kill UA when needed with a message and debug info
+ Added remote checking for new UA version
    There is an option to turn this off in uniadmin config
+ Added WoWAce module, now you can get addons from wowace.com
+ Added a function to grab a remote file's contents $uniadmin->get_remote_contents()
! Addon zips are loaded on the assumption that they extract to Interface/AddOns/, there is a switch on the upload form to change this action
! Simplified the upload process
    Only 3 fields; Required, Full Path, Select File
! Greatly improved .toc file detection
    Most needed fields are scanned from the .toc file
    You can use the addon details page to edit fields
! get_toc() changed to get_toc_val()
    This can get any value from the .toc file such as Interface, Version, X-Website, etc...
! Merged many addon.php functions ( require_addon, optional_addon, enable_addon, disable_addon ) into one function toggle_addon()
! Moved addon functions to include/addon_lib.php
! Moved debug config to the UA settings page
! Removed all extra ?>
! Another massive interface overhaul
    Added js styling for overlib
    Finally all html moved into themes and out of php code
    Made the pie charts smaller on the stats page
    Removed uploaded and status idication when no logo's are uploaded
    Added meta tag the prevents IE from showing the image toolbar
! Moved URL detection to include/uniadmin.php
! Simplified module detection and inclusion in index.php
! Changed $uniadmin->debug() to $uniadmin->error()
! Changed some calls in $uniadmin that used $uniadmin to $this
! Changed $uniadmin->ls() to be able to not traverse directories if needed
! Moved interface.php to modules dir, interface.php still exists in the root, but includes index.php and sets $_GET['p'] = 'interface';
! Changed uniadmin config text strings in locale files
    Using "title|tootip" format now
! Changed menu generation to give more variables so menu can be styled easily in themes


Beta 0.7.0
~ UA is now mySQL 5 compatible
~ Areas that were not using the dynamic database table names
    Thanks DreadPickle  http://www.wowroster.net/Forums/viewtopic/t=260.html
~ Pie charts for php 5.x
~ On logo page, hitting the upload button will not upload a blank logo
~ On addon page, hitting the upload button will not upload a blank addon
~ Uploading addons will now try to chmod and moveuploaded file and report any errors nicely
~ Addons uploaded with an already existing addon in UA will be updated and will not be inserted as a new addon
~ Addon parsing now checks to see if you are uploading a .zip file
~ The temp_anaylize folder will now be  on addon processing errors
+ Display templating
+ TOC scanning and display for addons
+ Required/Optional addons selection
    UniUploader 2.5 will give the option to download optional addons
    UniUploader < 2.5 will not even see optional addons
+ required="(0|1)" and toc="0000" to the XML output in interface.php
    UniUploader < 2.5 should ignore this
+ UniAdmin is now fully localized
    English only at this time
+ Database layer code
+ $user object. Holds locale strings, user info, etc...
+ $uniadmin object. Holds UA config info and some common functions
+ UniUploader settings.ini file importing and exporting
! Help, addons, logo, and settings pages can now be viewed by guests
! Updated pclzip.lib from v2.3 to v2.5. zip handling should be faster/better
! Changed initial sql to not set any UU sync settings to enabled
! Permissions for certain actions have been changed
    Look at the help page for more info
! Addon parser now uses .toc filename for insertion into the db
! Addon parser will now reject uploaded addon zip files with no .toc file
! Revamped look and feel of the interface
! All pages are now accessed by ?p= GET variable through index.php
    interface.php can still be accessed alone, because UU < v2.5 needs it this way
! Using POST rather then GET all the buttons (delete, change, modify, etc...)
! Using error_reporting(E_ALL), removed all php notices
! Password fields when adding/editing users to actual password fields
! Addon files table to use addon_id and not addon name
! Removed all the unused overlib code
! Updated help page with more info
! UniUploader related images updated to version 2.5.0
! All UA configuration has moved to a config page
! Settings page has been changed
    Each setting now has a specific input type
! Improved debug to include all SQL queries and page rendertimes
! Improved messages display
! Removed even more outdated settings from the db


Beta 0.6.1
~ Support systems with short_open_tags set to Off in the PHP config


Beta 0.6.0
~ Security cookie bug
+ Support for new UU functionality
+ A new setting
+ Overlib tooltips w/pics for each setting
+ view.php unsecure file for viewing addon list


Beta .50
+ Support for UniUploader 2.0, and removed support for previous versions of UU
+ A couple minor things to the help screen.
+ "sv management" to the settings screen
! Removed a bunch of outdated settings from the db


Beta .40
~ Addon Updater and interface including index.htm and index.html
+ User management
+ setting descriptions
! bunch of technical changes that nobody cares about (cant remember :P )
! Cosmetic Changes
! Deleted obsolete settings from database


Beta .31
~ Default 'PARSEVAR1' in uniadmin.sql.
    This was being set as 'MyProfile' which would break UniUploader Pre-Parse. Corrected to 'myProfile'
! Updated this install.txt file


10 - The Future
===============
To-do list for future versions of UA
