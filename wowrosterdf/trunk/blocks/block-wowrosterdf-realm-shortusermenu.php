<?php
/*********************************************
  CPG Dragonfly™ CMS
  ********************************************
  Copyright © 2004 - 2005 by CPG-Nuke Dev Team
  http://www.dragonflycms.com

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  $Source: /cvs/html/blocks/block-User_Info_small.php,v $
  $Revision: 9.13 $
  $Author: nanocaiordo $
  $Date: 2006/06/11 14:07:58 $
Encoding test: n-array summation ∑ latin ae w/ acute ǽ
********************************************************/
if (!defined('CPG_NUKE')) { exit; }

global $prefix, $user_prefix, $db, $sec_code, $userinfo, $MAIN_CFG, $CPG_SESS;
$content = '';

if (is_active('wowrosterdf'))
{
    $content .= '<center><img src="'.getlink('wowrosterdf&amp;file=realmstatus').'" alt="Realm Status"/></center>';
	$content .= '<hr />';
}

// number online
$result = $db->sql_query('SELECT COUNT(*), guest FROM '.$prefix.'_session GROUP BY guest ORDER BY guest');
$online_num = array(0, 0, 0, 0);
while ($row = $db->sql_fetchrow($result)) {
	$online_num[$row[1]] = intval($row[0]);
}
$db->sql_freeresult($result);
// number of members
list($numusers) = $db->sql_ufetchrow('SELECT COUNT(*) FROM '.$user_prefix."_users WHERE user_id > 1 AND user_level > 0",SQL_NUM);
// users registered today
$day = L10NTime::tolocal((mktime(0,0,0,date('n'),date('j'),date('Y'))-date('Z')), $userinfo['user_dst'], $userinfo['user_timezone']);
list($userCount[0]) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_regdate>='".$day."'", SQL_NUM);
// users registered yesterday
list($userCount[1]) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_regdate<'".$day."' AND user_regdate>='".($day-86400)."'", SQL_NUM);
// latest member
list($lastuser) = $db->sql_ufetchrow('SELECT username FROM '.$user_prefix.'_users WHERE user_active = 1 AND user_level > 0 ORDER BY user_id DESC LIMIT 0,1',SQL_NUM);

if(is_user()) {
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
	$content .= '<a title="'._ACCOUNTOPTIONS.'" href="'.getlink('Your_Account').'"><img src="images/blocks/logout.gif" alt="" border="0" /></a>&nbsp;<a title="'._ACCOUNTOPTIONS.'" href="'.getlink('Your_Account').'">'._Your_AccountLANG.'</a><br />
	<a title="'._LOGOUTACCT.'" href="'.getlink('Your_Account&amp;op=logout&amp;redirect', false).'"><img src="images/blocks/login.gif" alt="" border="0" /></a>&nbsp;<a title="'._LOGOUTACCT.'" href="'.getlink('Your_Account&amp;op=logout&amp;redirect', false).'">'._LOGOUT.'</a><br />';
} else {
	if (isset($_GET['redirect']) && !isset($CPG_SESS['user']['redirect'])) { $CPG_SESS['user']['redirect'] = $CPG_SESS['user']['uri']; }
	$redirect = isset($CPG_SESS['user']['redirect']) ? $CPG_SESS['user']['redirect'] : get_uri();
	$content .= '<center><img src="images/blocks/no_avatar.gif" alt="" /><br />'._BWEL.' <b>'._ANONYMOUS.'</b></center>
	<hr /><form action="'.$redirect.'" method="post" enctype="multipart/form-data" accept-charset="utf-8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td>'._NICKNAME.'</td><td align="right"><input type="text" name="ulogin" size="10" maxlength="25" /></td></tr>
	<tr><td><label for="user_password">'._PASSWORD.'</label></td><td align="right"><input type="password" id="user_password" name="user_password" size="10" maxlength="20" /></td></tr>';
	if ($sec_code & 2) {
		$content .='<tr><td>'._SECURITYCODE.'</td><td>'.generate_secimg().'</td></tr>
		<tr><td>'._TYPESECCODE.'</td><td align="right"><input type="text" name="gfx_check" size="10" maxlength="8" /></td></tr>';
	}
	$content .=	'<tr><td align="right" colspan="2"><input type="submit" value="'._LOGIN.'" /></td></tr>
	</table></form>';
	// don't show register link unless allowuserreg is yes
	if ($MAIN_CFG['member']['allowuserreg']) $content .=  '<div align="center">(<a title="'._BREG.'" href="'.getlink('Your_Account&amp;file=register').'">'._BREG.'</a>)</div>';
}
if (is_admin()) {
	$content .= '<a title="'._LOGOUTADMINACCT.'" href="'.adminlink('logout').'"><img src="images/blocks/login.gif" alt="" border="0" /></a>&nbsp;<a title="'._LOGOUTADMINACCT.'" href="'.adminlink('logout').'">'._ADMIN.' '._LOGOUT."</a><br />\n";
}

$content .= '<hr />
<img src="images/blocks/group-1.gif" alt="" /> <b><u>'._BMEMP.':</u></b><br />
<img src="images/blocks/ur-moderator.gif" alt="" /> '._BLATEST.': <a href="'.getlink("Your_Account&amp;profile=$lastuser").'"><b>'.$lastuser.'</b></a><br />
<img src="images/blocks/ur-author.gif" alt="" /> '._BTD.': <b>'.$userCount[0].'</b><br />
<img src="images/blocks/ur-admin.gif" alt="" /> '._BYD.': <b>'.$userCount[1].'</b><br />
<img src="images/blocks/ur-guest.gif" alt="" /> '._BOVER.': <b>'.$numusers.'</b><br />
<hr />
<img src="images/blocks/group-1.gif" alt="" /> <b><u>'._BVISIT.':</u></b><br />
<img src="images/blocks/ur-member.gif" alt="" /> '._BMEM.': <b>'.$online_num[0].'</b><br />
<img src="images/blocks/ur-anony.gif" alt="" /> '._BVIS.': <b>'.$online_num[1].'</b><br />
<img src="images/blocks/ur-anony.gif" alt="" /> '._BOTS.': <b>'.$online_num[3].'</b><br />
<img src="images/blocks/ur-registered.gif" alt="" /> '._STAFF.': <b>'.$online_num[2].'</b>
<hr />
<b><u>'._STAFFONL.':</u></b><br />';
// staff online
$result = $db->sql_query("SELECT a.uname, u.user_id FROM ".$prefix."_session AS a LEFT JOIN ".$user_prefix."_users AS u ON u.username = a.uname WHERE guest = 2 ORDER BY a.uname");
if($db->sql_numrows($result) < 1) {
	$content .= '<br /><i>'._STAFFNONE.'</i>';
} else {
	$num = 0;
	while($row = $db->sql_fetchrow($result)) {
		$num++;
		if ($num < 10) { $content .= '0'; }
		$content .= "$num: ";
		if($row['user_id'] > 1) {
			$content .= '<a href="'.getlink('Your_Account&amp;profile='.$row['user_id']).'">'.$row['uname'].'</a><br />';
		}
		else { $content .=	$row['uname'].'<br />'; }
	}
}
$db->sql_freeresult($result);