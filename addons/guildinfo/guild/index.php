<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays the guild information text
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['guildinfo'];

$guild_info_text = empty($roster->data['guild_info_text']) ? '&nbsp;' : $roster->data['guild_info_text'];

$gxp = explode(':',$roster->data['guild_xp']);
$gxpc = explode(':',$roster->data['guild_xpcap']);

		$query = "SELECT * FROM `" . $roster->db->table('news',$addon['basename']) . "` WHERE `guild_id` = '".$roster->data['guild_id']."' ORDER BY `Date` desc;";
		$result = $roster->db->query($query);

		$return_string = '';
		if( $roster->db->num_rows($result) > 0 )
		{
			while( $row = $roster->db->fetch($result, SQL_ASSOC) )
			{
				//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
				$roster->tpl->assign_block_vars('news', array(
					'DATE'    => $row['Display_date'],
					'TEXT'    =>sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
					)
				);
			}
		}
		
		
		
		
		
		$querya = "SELECT * FROM `" . $roster->db->table('ranks',$addon['basename']) . "` WHERE `guild_id` = '".$roster->data['guild_id']."' ORDER BY `TotalXP` desc Limit 10;";
		$resulta = $roster->db->query($querya);

		$return_string = '';
		if( $roster->db->num_rows($resulta) > 0 )
		{
			while( $rowa = $roster->db->fetch($resulta, SQL_ASSOC) )
			{
				//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
				$roster->tpl->assign_block_vars('top10', array(
					'NAME'    => $rowa['Member'],
					'TOTALXP'    => $rowa['TotalXP'],
					'TOTALRANK'    => $rowa['TotalRank'],
					//'TEXT'    =>sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
					)
				);
			}
		}
		
		$queryb = "SELECT * FROM `" . $roster->db->table('ranks',$addon['basename']) . "` WHERE `guild_id` = '".$roster->data['guild_id']."' ORDER BY `WeeklyXP` desc Limit 10;";
		$resultb = $roster->db->query($queryb);

		$return_string = '';
		if( $roster->db->num_rows($resultb) > 0 )
		{
			$wx = 1;
			while( $rowb = $roster->db->fetch($resultb, SQL_ASSOC) )
			{
				//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
				$roster->tpl->assign_block_vars('top10w', array(
					'NAME'    => $rowb['Member'],
					'TOTALXP'    => $rowb['WeeklyXP'],
					'TOTALRANK'    => $wx,
					//'TEXT'    =>sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
					)
				);
				$wx++;
			}
		}
		
		
		





$roster->tpl->assign_vars(array(

	'PROGRESSBAR' => _getgxpBar($gxp[0], $gxp[1], 'normal'),
	'PROGRESSBAR2' => _getgxpBar($gxpc[0], $gxpc[1], 'rested'),
	'TITLE' => '',
	'NEXT' => '',
	'LEVEL' => $roster->data['guild_level'],
	'INFO' => $guild_info_text,
	)
);



	$select_tab = (isset($_GET['t']) ? $_GET['t'] : 'profile');

	$roster->tpl->assign_block_vars('tabs',array(
			'NAME'     => $roster->locale->act['guildinfo'],
			'VALUE'    => 'profile',
			'SELECTED' => $select_tab == 'profile' ? true : false
			)
		);
	
	foreach ($roster->locale->act['NEWS_FILTER'] as $id => $name)
	{
		//echo $id.' - '.$name.' --<br>';
		
		$roster->tpl->assign_block_vars('tabs',array(
			'NAME'     => $name,
			'VALUE'    => 'ginfo'.$id,
			'SELECTED' => $select_tab == 'profile' ? true : false
			)
		);
		$roster->tpl->assign_block_vars('gtabs', array(
				'NAME'     => $name,
				'BLOCKID'    => 'ginfo'.$id,
			)
		);
		
		$query = "SELECT * FROM `" . $roster->db->table('news',$addon['basename']) . "` WHERE `guild_id` = '".$roster->data['guild_id']."' AND `Typpe` = '".($id-1)."' ORDER BY `Date` desc;";
		$result = $roster->db->query($query);

		$return_string = '';
		if( $roster->db->num_rows($result) > 0 )
		{
			while( $row = $roster->db->fetch($result, SQL_ASSOC) )
			{
				//echo $row['Achievement'].'<br> '.sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']).'<br>';
				$roster->tpl->assign_block_vars('gtabs.news', array(
					'DATE'    => $row['Display_date'],
					'TEXT'    =>sprintf($roster->locale->act['NEWS_FORMAT'][$row['Typpe']], $row['Member'], $row['Achievement']),
					)
				);
			}
		}
		
		
	}
	
	
$roster->tpl->set_handle('body', $addon['basename'] . '/info.html');
$roster->tpl->display('body');


	function _getgxpBar($step, $total, $txt) {
        global $roster;

        $perc = 0;
        if ( $total == 0 ) {
            $perc = 100;
        } else {
            $perc = round ($step / $total * 100);
        }
        $per_left = 100 - $perc;
/*        $pb = "<table class=\"main_roster_menu\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\" align=\"center\" width=\"200\" id=\"Table1\">";
        $pb .= "<tr>";
        $pb .= "    <td id=\"progress_text\" class=\"header\" colspan=\"2\" align=\"center\">";
        $pb .= "        $perc% ". $roster->locale->act['complete']. " ($step / $total)";
        $pb .= "    </td>";
        $pb .= "</tr>";
        $pb .= "<tr id=\"progress_bar\">";
        if ( $perc ) {
            $pb .= "	<td bgcolor=\"#660000\" height=\"12px\" width=\"$perc%\">" ;
            $pb .= "	</td>";
        }
        if ( $per_left ) {
            $pb .= "	<td bgcolor=\"#FFF7CE\" height=\"12px\" width=\"$per_left%\">";
            $pb .= "        </td>";
        }
        $pb .= "</tr>";
        $pb .= "</table>";
*/

		$pb = '	<div class="bar">
					<div class="xp-bar '.$txt.'">
						<div class="fill '.$txt.'" style="width: '.$perc.'%;"></div>
						<div class="text">XP: '.$step.' / '.$total.' ('.$perc.'%)</div>
					</div>
				</div>';
        //$this->_debug( 3, $pb, 'Fetched progressbar', $pb ? 'OK' : 'Failed');
        return $pb;
    }
	
	
	