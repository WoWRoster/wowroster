<?php

include("config.php");

$help = "


<table class='uuTABLE'>
<tr>
<td>

<p>

I bet you're wondering either what this is and/or how to use it, so:<br>
<br>
This is a system used to keep the users (who use UniUploader) addons, logos, and settings updated.  When you upload an addon to this system, and hit \"update\" in UU, UU will look up the \"Synchronization URL\" (the one in the frame on the left) and proceed to download any update(s) that are in any way different than the copy stored on the user's hard drive, UU will then replace the addon with the new copy of the addon from this system.<br>
<br>
Rules:<br>
The uploaded addon must be in zip form only.<br>
the zip file must have the following directory structure: [folder],{file}, and not literally \"addonName\" or \"addonfile\"<br>
<font color='red'>The zip file must contain no more than 1 wow addon</font><br><br>
[Interface]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Addons]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[addonName]<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{addonfile}<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{addonfile}<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{addonfile}<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{addonfile}<br>
<br>
etc.<br>
<br>
<br>
Settings:<BR>
You can make sure your user's critical UU settings are up to date with this, be VERY careful with some of them, as some of them might get your users angry at you, and if you set something wrong you could loose contact with all of your users LOL<BR>
If the setting is a 1 or zero that means it is a check mark in UU that should be: checked (1) or not checked (0).<br>
<br>
The saved variables list is the actual list of files that you want UU to upload to the URL(s).
<BR>
Logo updater:<BR>
<BR>
statistics:<BR>
<BR>
User Management:<BR>
There are 3 \"user levels\".<BR>
<BR>
access items:<BR>
#1: addon management<BR>
#2: logo management<BR>
#3: settings management<BR>
#4: statistics management<BR>
#5.1: change own password<BR>
#5.2: change own username<BR>
#5.3: change level 1 usernames &amp; passwords<BR>
#5.4: add level 1 users<BR>
#5.5: add any level users<BR>
#5.6: delete level 1 users<BR>
#5.7: delete own username<BR>
#5.8: delete any username<BR>
#5.9: change any user's level<BR>
<BR>
<BR>
level1 (basic user) has access to:<BR>
1,2,3,4,5.1,5.7<BR>
<BR>
level2 (power user) has access to:<BR>
1,2,3,4,5.1,5.2,5.3,5.4,5.6,5.7<BR>
<BR>
level3 (administrator) has access to:<BR>
1,2,3,4,5.1,5.2,5.3,5.4,5.5,5.6,5.7,5.8,5.9 (everything)<BR>

There should'nt have to be more than 1 or 2 \"level 3\" users in the database.


</p>

<td>

</tr>

</table>
";

EchoPage("
		<br>
		<br>
		<center>
			$help
		</center>

","help");
?>