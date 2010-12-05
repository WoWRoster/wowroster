<?php
/**
 * WoWRoster.net WoWRoster
 *
 * License Information
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] = 'License Information';

echo '
<div class="container">
	<div class="tier-1-a">
		<div class="tier-1-b">
			<div class="tier-1-c">
				<div class="tier-1-title">License Information</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">WoWRoster is licensed under the GNU General Public License v3.</div>

						<div class="info-text-h">
							<p>This program is free software: you can redistribute it and/or modify
							it under the terms of the GNU General Public License as published by
							the Free Software Foundation, either version 3 of the License, or
							(at your option) any later version.</p>

							<br />

							<p>This program is distributed in the hope that it will be useful,
							but WITHOUT ANY WARRANTY; without even the implied warranty of
							MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
							GNU General Public License for more details.</p>

							<br />

							<p>You should have received a copy of the GNU General Public License
							along with this program. If not, see <a href="http://www.gnu.org/licenses" target="_blank">http://www.gnu.org/licenses</a>.</p>
						</div>
					</div>
				</div>

				<br />

				<div class="tier-6-a">
					<div class="tier-6-b">
						<div class="text" style="text-align: center;">
							Serveral external libraries are included with WoWRoster
							that are may not be included under the main WoWRoster license
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">jQuery Javascript Library</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://jquery.com" target="_blank">http://jquery.com</a></li>
								<li>jQuery is provided under the following <a href="http://docs.jquery.com/Licensing" target="_blank">MIT and GPL licenses</a>.</li>
								<li>File located at [js/jquery.js]</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">Tab Content Script - DynamicDrive</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://www.dynamicdrive.com" target="_blank">http://www.dynamicdrive.com</a></li>
								<li>DynamicDrive Terms of Use <a href="http://www.dynamicdrive.com/notice.htm" target="_blank">http://www.dynamicdrive.com/notice.htm</a></li>
								<li>File located at [js/tabcontent.js]</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">Overlib tooltip library - Erik Bosrup</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://www.bosrup.com/web/overlib" target="_blank">http://www.bosrup.com/web/overlib</a></li>
								<li>Overlib License: <a href="http://www.bosrup.com/web/overlib/?License" target="_blank">http://www.bosrup.com/web/overlib/?License</a></li>
								<li>File located at [js/overlib.js]</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">DHTML Drag & Drop library - Walter Zorn</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://www.walterzorn.com/dragdrop/dragdrop_e.htm" target="_blank">http://www.walterzorn.com/dragdrop/dragdrop_e.htm</a></li>
								<li>GNU Lesser General Public License: <a href="http://gnu.org/copyleft/lesser.html" target="_blank">http://gnu.org/copyleft/lesser.html</a></li>
								<li>File located at [js/wz_dragdrop.js]</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">Modified EQdkp installer</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://www.eqdkp.com" target="_blank">http://www.eqdkp.com</a></li>
								<li>The installer was based on the EQdkp installer</li>
								<li>GNU General Public License: <a href="http://gnu.org/copyleft/gpl.html" target="_blank">http://gnu.org/copyleft/gpl.html</a></li>
								<li>This concerns the files:
									<ul>
										<li>install.php</li>
										<li>pages/upgrade.php</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">DragonFly CMS Template Engine</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://www.dragonflycms.org" target="_blank">http://www.dragonflycms.org</a></li>
								<li>GNU General Public License: <a href="http://gnu.org/copyleft/gpl.html" target="_blank">http://gnu.org/copyleft/gpl.html</a></li>
								<li>This concerns the files:
									<ul>
										<li>lib/template.php</li>
										<li>lib/template_enc.php</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">MiniXML Library</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://minixml.psychogenic.com" target="_blank">http://minixml.psychogenic.com</a></li>
								<li>GNU General Public License: <a href="http://gnu.org/copyleft/gpl.html" target="_blank">http://gnu.org/copyleft/gpl.html</a></li>
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
						</div>
					</div>
				</div>

				<div class="tier-2-a">
					<div class="tier-2-b">
						<div class="tier-2-title">NicEdit</div>

						<div class="info-text-h">
							<ul>
								<li><a href="http://www.nicedit.com" target="_blank">http://www.nicedit.com</a></li>
								<li>MIT License: <a href="http://www.nicedit.com/license.php" target="_blank">http://www.nicedit.com/license.php</a></li>
								<li>This concerns the files:
									<ul>
										<li>js/nicEdit.js</li>
										<li>img/nicEditorIcons.gif</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>';
