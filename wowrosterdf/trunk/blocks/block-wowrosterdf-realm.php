<?php
/*********************************************
  CPG Dragonflyâ„¢ CMS
  ********************************************
  Copyright Â© 2004 - 2005 by CPG-Nuke Dev Team
  http://www.dragonflycms.com

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  $Source: /cvs/html/blocks/block-User_Info_micro.php,v $
  $Revision: 9.0 $
  $Author: trevor $
  $Date: 2005/02/24 16:09:22 $
Encoding test: n-array summation âˆ‘ latin ae w/ acute Ç½
********************************************************/
if (!defined('CPG_NUKE')) { exit; }

global $prefix, $user_prefix, $db, $gfx_chk, $userinfo, $MAIN_CFG;
$content = '';
$module_name= 'wowrosterdf';
if (is_active($module_name))
{
    $content .= '<center><img src="'.getlink($module_name.'&amp;file=realmstatus').'" alt="Realm Status"/></center>';
	$content .= '<hr/>';
}

if( is_user() )
{
    $content .= '<center>';
    if ($userinfo['user_avatar_type'] == 1) {
        $avatar = $MAIN_CFG['avatar']['path'].'/'.$userinfo['user_avatar'];
    } else if ($userinfo['user_avatar_type'] == 2) {
        $avatar = $userinfo['user_avatar'];
    } else if ($userinfo['user_avatar_type'] == 3) {
        $avatar = $MAIN_CFG['avatar']['gallery_path'].'/'.$userinfo['user_avatar'];
    } else {
        $avatar = 'images/blocks/no_avatar.gif';
    }
    $content .= "<img src=\"$avatar\" alt=\"\" />";
    $content .= '<br />'._BWEL." <b>$userinfo[username]</b><br /><img src=\"images/spacer.gif\" height=\"8\" alt=\"\" /></center>\n";
    if (is_active('Private_Messages')) {
        $pm = $userinfo['user_new_privmsg']+$userinfo['user_unread_privmsg'];
        $content .= '&nbsp;<a title="'._READSEND.'" href="'.getlink('Private_Messages').'"><img src="images/blocks/email.gif" alt="" border="0" /></a>&nbsp;&nbsp;<a title="'._READSEND.'" href="'.getlink('Private_Messages').'">'._INBOX.'</a>';
        $content .= '&nbsp;&nbsp;'._NEW.": <b>$pm</b><br />\n";
    }
    $content .= '<a title="'._ACCOUNTOPTIONS.'" href="'.getlink('Your_Account').'"><img src="images/blocks/logout.gif" alt="" border="0" /></a>&nbsp;<a title="'._ACCOUNTOPTIONS.'" href="'.getlink('Your_Account').'">'._Your_AccountLANG.'</a>
    <a title="'._LOGOUTACCT.'" href="'.getlink('Your_Account&amp;op=logout', false).'"><br /><img src="images/blocks/login.gif" alt="" border="0" /></a>&nbsp;<a title="'._LOGOUTACCT.'" href="'.getlink('Your_Account&amp;op=logout', false).'">'._LOGOUT.'</a><br />';
} else {
    $content .= '<center><img src="images/blocks/no_avatar.gif" alt="" /><br />'._BWEL.' <b>'._ANONYMOUS.'</b></center>
    <hr /><form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr><td>'._NICKNAME.'</td><td align="right"><input type="text" name="ulogin" size="10" maxlength="25" /></td></tr>
    <tr><td>'._PASSWORD.'</td><td align="right"><input type="password" name="user_password" size="10" maxlength="20" /></td></tr>
    <tr><td>';
    if ($gfx_chk == 2 || $gfx_chk == 4 || $gfx_chk == 5 || $gfx_chk == 7) {
        $content .= _SECURITYCODE.'</td><td>'.generate_secimg().'</td></tr>
    <tr><td>'._TYPESECCODE.'</td><td align="right"><input type="text" name="gfx_check" size="10" maxlength="8" /></td></tr>
    <tr><td>';
    }
    // don't show register link unless allowuserreg is yes
    $content .= ($MAIN_CFG['member']['allowuserreg'] ? '(<a href="'.getlink('Your_Account&amp;file=register').'">'._BREG.'</a>)' : '').'</td>
    <td align="right"><input type="submit" value="'._LOGIN.'" />
    </td></tr></table></form>
';
}
if (is_admin()) {
    $content .= '<a title="'._LOGOUTADMINACCT.'" href="'.adminlink('logout').'"><img src="images/blocks/login.gif" alt="" border="0" /></a>&nbsp;<a title="'._LOGOUTADMINACCT.'" href="'.adminlink('logout').'">'._ADMIN.' '._LOGOUT."</a><br />\n";
}
