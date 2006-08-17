<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

$help = "

<table class='uuTABLE' width='100%'>
	<tr>
		<th class='tableHeader'>Help</th>
	</tr>
	<tr>
		<td class='dataHeader'>Intro</td>
	</tr>
	<tr>
		<td class='data1'>
			<p>I bet you're wondering either what this is and/or how to use it, so:</p>
			<p>This is a system used to keep the users (who use UniUploader) addons, logos, and settings updated.<br />
				When you upload an addon to this system, and hit [Update] in UU, UU will look up the &quot;Synchronization URL&quot; (the one in the frame on the left)<br />
				and proceed to download any update(s) that are in any way different than the copy stored on the user's hard drive.<br />
				UU will then replace the addon with the new copy of the addon from this system.</p></td>
	</tr>
	<tr>
		<td class='dataHeader'>Addons</td>
	</tr>
	<tr>
		<td class='data1'>
			<p>The uploaded addon must be in zip form only.<br />
				The zip file must have the following directory structure: [folder],{file}, and not literally &quot;addonName&quot; or &quot;addonfile&quot;<br />
				The Addon Name is the same as the name of the folder that the Addon's files are in</p>
<pre>[Interface]
     [Addons]
          [addonName]
               {addonfile}
               {addonfile}
               {addonfile}
               {addonfile}
etc.</pre></td>
	</tr>
	<tr>
		<td class='dataHeader'>Logos</td>
	</tr>
	<tr>
		<td class='data1'>
			<p>This changes the logos displayed in UniUploader/jUniUploader<br />
				Logo 1 is displayed on the [Settings] tab
				Logo 2 is displayed on the [About] tab</p></td>
	</tr>
	<tr>
		<td class='dataHeader'>Settings</td>
	</tr>
	<tr>
		<td class='data1'>
			<p>You can make sure your user's critical UU settings are up to date with this, be VERY careful with some of them, as some of them might get your users angry at you, and if you set something wrong you could loose contact with all of your users LOL<br />
				If the setting is a 1 or zero that means it is a check mark in UU that should be: checked (1) or not checked (0).</p>
			<p>The saved variables list is the actual list of files that you want UU to upload to the URL(s).</p></td>
	</tr>
	<tr>
		<td class='dataHeader'>Statistics</td>
	</tr>
	<tr>
		<td class='data1'>
			<p>Basicly this shows who is accessing UniAdmin</p>
			<p>The table shows each access</p>
			<ul>
				<li> &quot;Action&quot; - What the client is asking for</li>
				<li> &quot;IP Address&quot; - The client's IP address</li>
				<li> &quot;Date/Time&quot; - The date/time accessed</li>
				<li> &quot;User Agent&quot; - What software is accessing it</li>
				<li> &quot;Host Name&quot; - The user's Host Name</li>
			</ul>
			<p>Below the table is nifty pie charts for how UniAdmin was accessed</p></td>
	</tr>
	<tr>
		<td class='dataHeader'>Users</td>
	</tr>
	<tr>
		<td class='data1'>
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
			<li> 5.9: change any user's level</li>
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
	<dd>There shouldn't have to be more than 1 or 2 &quot;level 3&quot; users in UniAdmin</dd>
</dl></td>
	</tr>
</table>
";

EchoPage($help,'Help');
?>