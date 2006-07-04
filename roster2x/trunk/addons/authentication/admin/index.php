<?php

require_once '../auth_conf.php';
require_once '../functions/auth_class.php';
require_once '../functions/interface_helper_class.php';
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
						<li><a href="#" onclick="return expandcontent('t5', this)">Permissions</a></li>		
						<li><a href="#" onclick="return expandcontent('t6', this)">CMS Authentication</a></li>
						<li><a href="#" onclick="return expandcontent('t7', this)">Language</a></li>
						<li><input name="submit" type="submit" value="Logout" class="sc_menuClick" onmousedown="this.style.background = '#778899'" onmouseup="return expandcontent('login', this)" onmouseover="this.style.background = '#7A7772'" onmouseout="this.style.background = '#2E2D2B'" style="width:100%; margin-right:-2px;" align="left"></li>
						<?php } ?>
						<li><a href="../../../">Back to Roster</a></li>
						<li><a href="http://www.wowprofilers.com/wiki/index.php/Roster:Addon:Authentication" target="_new">Documentation</a></li>
					</ul>
				</div>
			
			</td>
		</form>
		<td valign="top">
			<?php if(!$LU->isLoggedIn()){ show_login();	} else { ?>
				<div id="t1" style="display:none">
					<table width="400px" style="border:1px solid #212121; font-weight:bold;">
						<form method="post">
						<tr>
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Guild Management</td>
						</tr>
						<tr>
							<td>
								<?php $IH->list_guilds(); ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php $IH->new_edit_guild_field(@$_GET); ?>
							</td>
						</tr>
						</form>
					</table>
				</div>
				<div id="t2" style="display:none">
					<table width="400px" style="border:1px solid #212121; font-weight:bold;">
						<form method="post">
						<tr>
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Area Management</td>
						</tr>
						<tr>
							<td>
								<?php $IH->list_areas(); ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php $IH->new_edit_area_field(@$_GET); ?>
							</td>
						</tr>
						</form>
					</table>
				</div>
				<div id="t3" style="display:none">
					<table width="400px" style="border:1px solid #212121; font-weight:bold;">
						<form method="post">
						<tr>
							<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Group Management</td>
						</tr>
						<tr>
							<td>
								<?php $IH->list_groups(); ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php $IH->new_edit_group_field(@$_GET); ?>
							</td>
						</tr>
						</form>
					</table>
				</div>
				<div id="t4" style="display:none">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr valign="top">
							<td>
								<table width="500px" style="border:1px solid #212121; font-weight:bold;">
									<form method="post">
									<tr>
										<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">User Management</td>
									</tr>
									<tr>
										<td>
											<?php $IH->users(array('action'=>@$_GET['action'], 'id'=>@$_GET['id'])); ?>
										</td>
									</tr>
									</form>
								</table>
							</td>
							<td style="padding-left:3px; ">
								<table width="150px" style="border:1px solid #212121; font-weight:bold;">
									<form method="post">
									<tr>
										<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Search Users by</td>
									</tr>
									<tr>
										<td>
											<?php $IH->users('search_box'); ?>
										</td>
									</tr>
									</form>
								</table>
								<br />
								<table width="150px" style="border:1px solid #212121; font-weight:bold;">
									<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post">
									<tr>
										<td class="sc_menuTH" align="center" style="font-size:14px; font-weight:bold;">Create User</td>
									</tr>
									<tr>
										<td>
											<?php $IH->users('new_user_box'); ?>
										</td>
									</tr>
									</form>
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
			<?php }// end if($LU->isLoggedIn()) ?>
		</td>
	</tr>
</table>
