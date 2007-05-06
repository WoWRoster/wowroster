<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Honor ranking list
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$striping_counter = 0;
$tableHeader = '
<!-- Begin HSLIST -->
'.border('sgray','start','<div style="cursor:pointer;width:400px;" onclick="showHide(\'hs_table\',\'hs_img\',\''.$roster->config['img_url'].'minus.gif\',\''.$roster->config['img_url'].'plus.gif\');">
	<div style="display:inline;float:right;"><img id="hs_img" src="'.$roster->config['img_url'].'plus.gif" alt="" /></div>
'.$roster->locale->act['hslist'].'</div>').'
<table width="100%" cellpadding="0" cellspacing="0" class="bodyline" id="hs_table" style="display:none;">'."\n";


$tableFooter = "</table>\n".border('sgray','end')."\n<!-- End HSLIST -->\n";

function rankLeft($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function rankMid($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function rankRight($sc)
{
	print '    <td class="membersRowRight'.$sc.'">';
}

print($tableHeader);



//Highest Lifetime Rank
$query = "SELECT `name`, `lifetimeRankName` FROM `".$roster->db->table('players')."` ORDER BY `lifetimeHighestRank`DESC, `lifetimeHK` DESC LIMIT 0 , 1";
$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $roster->db->fetch( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="'.makelink('guildhonor&amp;s=lifetimeRankName').'">'.$roster->locale->act['hslist1'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	if ($row['lifetimeRankName'] == '')
	{
		print('&nbsp;');
	}
	else
	{
		print($row['lifetimeRankName']);
	}
	print("</td>\n  </tr>\n");
}

//Highest LifeTime HKs
$query = "SELECT `name`, `lifetimeHK` FROM `".$roster->db->table('players')."` ORDER BY `lifetimeHK` DESC, `lifetimeHighestRank` DESC LIMIT 0 , 1";
$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $roster->db->fetch( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="'.makelink('guildhonor&amp;s=lifetimeHK').'">'.$roster->locale->act['hslist2'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['lifetimeHK']);
	print("</td>\n  </tr>\n");
}

//Highest honorpoints
$query = "SELECT `name`, `honorpoints` FROM `".$roster->db->table('players')."` ORDER BY `honorpoints` DESC LIMIT 0 , 1";
$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $roster->db->fetch( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="'.makelink('guildhonor&amp;s=honorpoints').'">'.$roster->locale->act['hslist3'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['honorpoints']);
	print("</td>\n  </tr>\n");
}

//Highest arenapoints
$query = "SELECT `name`, `arenapoints` FROM `".$roster->db->table('players')."` ORDER BY `arenapoints` DESC LIMIT 0 , 1";
$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$row = $roster->db->fetch( $result );

if ($row) {
	// Striping rows
	print("  <tr>\n");

	// Increment counter so rows are colored alternately
	++$striping_counter;
	rankLeft((($striping_counter % 2) +1));
	print('<a href="'.makelink('guildhonor&amp;s=arenapoints').'">'.$roster->locale->act['hslist4'].'</a></td>'."\n");
	rankMid((($striping_counter % 2) +1));
	print($row['name']);
	print("</td>\n");
	rankRight((($striping_counter % 2) +1));
	print($row['arenapoints']);
	print("</td>\n  </tr>\n");
}

print($tableFooter);
$roster->db->free_result($result);
