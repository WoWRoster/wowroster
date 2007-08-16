<?php
/**
 * WoWRoster.net WoWRoster
 *
 * License Information
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = 'License';
include_once(ROSTER_BASE . 'header.php');

require_once (ROSTER_LIB.'item.php');

$roster_menu = new RosterMenu;
$roster_menu->makeMenu($roster->output['show_menu']);

print "<br />\n";

print messagebox("<div align=\"left\">
<p align=\"center\"><span class=\"headline_3\">WoWRoster is licensed under a Creative Commons<br />
&quot;Attribution-NonCommercial-ShareAlike 2.5&quot; license</span></p>

<br />

<p>Short summary: <a href=\"http://creativecommons.org/licenses/by-nc-sa/2.5\" target=\"_blank\">http://creativecommons.org/licenses/by-nc-sa/2.5</a><br />
Full license: <a href=\"http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode\" target=\"_blank\">http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode</a></p>

<br />

<strong>Regarding Attribution:</strong>
<ul>
	<li>Keep the credits in the footer of the WoWRoster pages</li>
	<li>Include this license (license.txt) with all modified versions of the WoWRoster</li>
</ul>
<strong>Regarding ShareAlike:</strong>
<ul>
	<li>Any changes made to the WoWRoster code must be archived and accessible for public download<br />
	<li>Including, but not limited to:
		<ul>
		<li>HTML, TXT, PHP, CSS, SQL, images, and Javascript</li>
		</ul></li>
	<li>You may, of course, remove username, password, and database host information from the archive</li>
</ul>

<p>For any reuse or distribution, you must make clear to others the license terms of this work<br />
Any of these conditions can be waived if you get permission from the dev team at <a href=\"http://www.wowroster.net\" target=\"_blank\">wowroster.net</a></p>

<hr />

<p align=\"center\"><span class=\"headline_3\">Serveral external libraries are included with WoWRoster<br />
that are not included under the main WoWRoster license</span></p>

<br />

<p>These are:</p>

<br />

<strong>Tab Content Script</strong> - DynamicDrive
<ul>
	<li><a href=\"http://www.dynamicdrive.com\" target=\"_blank\">http://www.dynamicdrive.com</a></li>
	<li>DynamicDrive Terms of Use <a href=\"http://www.dynamicdrive.com/notice.htm\" target=\"_blank\">http://www.dynamicdrive.com/notice.htm</a></li>
	<li>File located at [js/tabcontent.js]</li>
</ul>

<strong>Color Pallet Script</strong> - DhtmlGoodies
<ul>
	<li><a href=\"http://www.dynamicdrive.com\" target=\"_blank\">http://www.dynamicdrive.com</a></li>
	<li>DynamicDrive Terms of Use <a href=\"http://www.dynamicdrive.com/notice.htm\" target=\"_blank\">http://www.dynamicdrive.com/notice.htm</a></li>
	<li>File located at [js/color_functions.js]</li>
</ul>

<strong>Overlib tooltip library</strong> - Erik Bosrup
<ul>
	<li><a href=\"http://www.bosrup.com/web/overlib\" target=\"_blank\">http://www.bosrup.com/web/overlib</a></li>
	<li>Overlib License: <a href=\"http://www.bosrup.com/web/overlib/?License\" target=\"_blank\">http://www.bosrup.com/web/overlib/?License</a></li>
	<li>File located at [js/overlib.js]</li>
</ul>

<strong>DHTML Drag & Drop library</strong> - Walter Zorn
<ul>
	<li><a href=\"http://www.walterzorn.com/dragdrop/dragdrop_e.htm\" target=\"_blank\">http://www.walterzorn.com/dragdrop/dragdrop_e.htm</a></li>
	<li>GNU Lesser General Public License: <a href=\"http://gnu.org/copyleft/lesser.html\" target=\"_blank\">http://gnu.org/copyleft/lesser.html</a></li>
	<li>File located at [js/wz_dragdrop.js]</li>
</ul>

<strong>Modified EQdkp installer</strong>
<ul>
	<li><a href=\"http://www.eqdkp.com\" target=\"_blank\">http://www.eqdkp.com</a></li>
	<li>The installer was based on the EQdkp installer</li>
	<li>GNU General Public License: <a href=\"http://gnu.org/copyleft/gpl.html\" target=\"_blank\">http://gnu.org/copyleft/gpl.html</a></li>
	<li>This concerns the files:
		<ul>
			<li>install.php</li>
			<li>update.php</li>
			<li>install/template.php</li>
		</ul>
	</li>
</ul>

<strong>MiniXML Library</strong>
<ul>
	<li><a href=\"http://minixml.psychogenic.com\" target=\"_blank\">http://minixml.psychogenic.com</a></li>
	<li>GNU General Public License: <a href=\"http://gnu.org/copyleft/gpl.html\" target=\"_blank\">http://gnu.org/copyleft/gpl.html</a></li>
	<li>This concerns the files:
		<ul>
			<li>lib/minixml.lib.php</li>
			<li>lib/minixml/doc.inc.php</li>
			<li>lib/minixml/element.inc.php</li>
			<li>lib/minixml/LICENSE</li>
			<li>lib/minixml/node.inc.php</li>
			<li>lib/minixml/treecomp.inc.php</li>
		</ul>
	</li>
</ul>
</div>",'WoWRoster License');

include_once(ROSTER_BASE . 'footer.php');
