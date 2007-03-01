<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$header_title = $wordings[$roster_conf['roster_lang']]['keys'];
include_once (ROSTER_BASE.'roster_header.tpl');

require_once (ROSTER_LIB.'item.php');

include_once( ROSTER_LIB.'menu.php');
print "<br />\n";

print messagebox(nl2br("<div align=\"left\">
WoW Roster is licensed under a Creative Commons
&quot;Attribution-NonCommercial-ShareAlike 2.5&quot; license. See
http://creativecommons.org/licenses/by-nc-sa/2.5/ for the short
summary, and
http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode for the
full license information.

Regarding Attribution:

- Keep the credits in the footer of the roster pages.

- Include this license with all modified versions of the roster.

Regarding ShareAlike:

- Any changes made to the roster code (including, but not limited to,
  HTML, PHP, CSS, SQL, images, and Javascript) must be archived
  and accessible for public download. You may, of course, remove
  username, password, and database host information from the archive.

For any reuse or distribution, you must make clear to others the
license terms of this work. Any of these conditions can be waived if
you get permission from the dev team at wowroster.net.


Serveral javascript libraries are included with roster that are not
included under the main roster license. These are:

- The Tab Content Script from DynamicDrive. The DynamicDrive license
  applies, available at http://www.dynamicdrive.com/notice.htm.

- The Overlib tooltip library by Erik Bosrup. An unnamed license
  applies, available at http://www.bosrup.com/web/overlib/?License


The installer was based on the EQdkp installer. This concerns the files:
- install.php
- update.php
- install/template.php

These 3 files are licensed under the GNU General Public License, which is
available at http://gnu.org/copyleft/gpl.html
</div>"),'WoWRoster License');

include_once (ROSTER_BASE.'roster_footer.tpl');
