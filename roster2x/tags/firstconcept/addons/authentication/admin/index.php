<?php

require_once '../auth_conf.php';
require_once '../functions/auth_class.php';
require_once '../functions/interface_helper.php';
require_once '../functions/login.php';
include_once '../css/sc_java.js';

$Auth = new Authentication;
$IH = new Interface_Helper;

$IH->treat_get_post($_REQUEST);

?>
<link href="../css/default.css" rel="stylesheet" type="text/css">

<table bgcolor="#000000">
	<tr valign="top">
		<form method="post">
			<td>
				<div id="tab_menu" style="border:1px solid #212121; width:130px; font-weight:bold;">
					<ul>
						<?php if(do_login($_POST)){ ?>
						<li><a href="#" onclick="return expandcontent('t1', this)">Guild Management</a></li>
						<li><a href="#" onclick="return expandcontent('t2', this)">Area Management</a></li>
						<li><a href="#" onclick="return expandcontent('t3', this)">Group Management</a></li>
						<li><a href="#" onclick="return expandcontent('t4', this)">User Management</a></li>
						<li><a href="#" onclick="return expandcontent('t5', this)">Rights</a></li>		
						<li><a href="#" onclick="return expandcontent('t6', this)">CMS Authentication</a></li>
						<li><a href="#" onclick="return expandcontent('t7', this)">Language</a></li>
						<li><input name="submit" type="submit" value="Logout" class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseup="return expandcontent('login', this)" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'" style="width:100%; margin-right:-2px;" align="left"></li>
						<?php } ?>
						<li><a href="../../../">Back to Roster</a></li>
						<li><a href="http://www.wowroster.net/wiki/index.php/Roster:Addon:Authentication" target="_new">Documentation</a></li>
					</ul>
				</div>
			
			</td>
		</form>
		<td valign="top">
			<?php if(!$LU->isLoggedIn()){ show_login();	} else { ?>
				<div id="t1" style="display:none">
					<table width="400px" style="border:1px solid #212121; font-weight:bold;">
						<tr>
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Guild Management</td>
						</tr>
						<tr>
							<td>
								<?php $IH->gui('Guild_Management_Master', 'list_guilds'); ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php $IH->gui('Guild_Management_Master', 'new_edit_guild_field', @$_GET); ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="t2" style="display:none">
					<table width="400px" style="border:1px solid #212121; font-weight:bold;">
						<tr>
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Area Management</td>
						</tr>
						<tr>
							<td>
								<?php $IH->gui('Area_Management_Master', 'list_areas'); ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php $IH->gui('Area_Management_Master', 'new_edit_area_field', @$_GET); ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="t3" style="display:none">
					<table width="400px" style="border:1px solid #212121; font-weight:bold;">
						<tr>
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Group Management</td>
						</tr>
						<tr>
							<td>
								<?php $IH->gui('Group_Management_Master', 'list_groups'); ?>
							</td>
						</tr>
						<tr>
							<td>
								<br><br>
								<?php $IH->gui('Group_Management_Master', 'new_edit_group_field', @$_GET); ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="t4" style="display:none">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr valign="top">
							<td>
								<table width="500px" style="border:1px solid #212121; font-weight:bold;">
									<tr>
										<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">User Management</td>
									</tr>
									<tr>
										<td>
											<?php $IH->gui('User_Management_Master', 'users', array('action'=>@$_GET['action'], 'id'=>@$_GET['id'])); ?>
										</td>
									</tr>
								</table>
							</td>
							<td style="padding-left:3px; ">
								<table width="150px" style="border:1px solid #212121; font-weight:bold;">
									<tr>
										<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Search Users by</td>
									</tr>
									<tr>
										<td>
											<?php $IH->gui('User_Management_Master', 'users', 'search_box'); ?>
										</td>
									</tr>
								</table>
								<br />
								<table width="150px" style="border:1px solid #212121; font-weight:bold;">
									<tr>
										<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Create User</td>
									</tr>
									<tr>
										<td>
											<?php $IH->gui('User_Management_Master', 'users', 'new_user_box'); ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<a href="index.php">
								<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseup="this.style.background = '#7A7772'" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'">
								Back to the user list
								</td>
							</a>
						</tr>
					</table>
				</div>
				<div id="t5" style="display:none">
					<table width="540px" style="border:1px solid #212121; font-weight:bold;">
						<tr style="cursor:pointer;">
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;"><div onclick="return toggleShow('group_rights_management', this)" style="width:100%;">Group Rights Management</div></td>
						</tr>
						<?php if(@$_GET['display'] != 'group') { ?>
						<tr id="group_rights_management" style="display:none;">
						<?php } else { ?>
						<tr id="group_rights_management">
						<?php } ?>
							<td>
								<?php $IH->gui('Rights_Management_Master', 'group_rights'); ?>
							</td>
						</tr>
						<tr style="cursor:pointer;">
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;"><div onclick="return toggleShow('personal_rights_management', this)" style="width:100%;">Personal Rights Management</div></td>
						</tr>
						<?php if(@$_GET['display'] != 'personal') { ?>
						<tr id="personal_rights_management" style="display:none;">
						<?php } else { ?>
						<tr id="personal_rights_management">
						<?php } ?>
							<td>
								<?php $IH->gui('Rights_Management_Master', 'personal_rights'); ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="t6" style="display:none">
					<table width="500px" style="border:1px solid #212121; font-weight:bold;">
						<tr style="cursor:pointer;">
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;"><div onclick="return toggleShow('cms_userbase_synchronisation', this)" style="width:100%;">CMS Userbase Synchronisation List</div></td>
						</tr>
						<?php if(@!$_GET['adapter']) { ?>
						<tr id="cms_userbase_synchronisation">
						<?php }else{ ?>
						<tr id="cms_userbase_synchronisation" style="display:none;">
						<?php } ?>
							<td>
								<?php $IH->gui('CMS_Sync', 'list_adapters'); ?>
							</td>
						</tr>
						<?php if(@$_GET['adapter']) { ?>
						<tr style="cursor:pointer;">
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;"><div onclick="return toggleShow('cms_show_adapter', this)" style="width:100%;"><?php print urldecode(@$_GET['name']); ?> Userbase Synchronisation</div></td>
						</tr>
						<tr id="cms_show_adapter">
							<td>
								<?php $IH->gui('CMS_Sync', 'show_adapter', array('adapter'=>@$_GET['adapter'], 'name'=>@$_GET['name'])); ?>
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
			<?php }// end if($LU->isLoggedIn()) ?>
		</td>
	</tr>
</table>
