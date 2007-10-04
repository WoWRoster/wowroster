<?php
/**
* WoWRoster.net WoWRoster
*
* AutoRecruit configuration
*
* LICENSE: Licensed under the Creative Commons
*          "Attribution-NonCommercial-ShareAlike 2.5" license
*
* @copyright  2002-2007 WoWRoster.net
* @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
* @link       http://www.wowroster.net
* @package    AutoRecruit
*/
 
if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}
 
if( isset($_POST['process']) && $_POST['process'] != '' )
{
        $roster_config_message = processData();
}
 
$menu_select = array();
 
// Get the scope select data
$query = "SELECT `guild_name`, CONCAT(`region`,'-',`server`), `guild_id` FROM `" . $roster->db->table('guild') . "`"
           . " ORDER BY `region` ASC, `server` ASC, `guild_name` ASC;";
 
$result = $roster->db->query($query);
 
if( !$result )
{
    die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$query);
}
 
$guilds=0;
while( $data = $roster->db->fetch($result,SQL_NUM) )
{
        $menu_select[$data[1]][$data[2]] = $data[0];
        $guilds++;
}
 
$options='';
 
if( $guilds > 1 )
{
        foreach( $menu_select as $realm => $guild )
        {
                $options .= '      <optgroup label="' . $realm . '">'. "\n";
                foreach( $guild as $id => $name )
                {
                        $options .= '         <option value="' . makelink("&amp;guild=$id") . '"' . ( $id == $_GET['guild'] ? ' selected="selected"' : '' ) . '>' . $name . '</option>' . "\n";
                }
                $options .= '      </optgroup>';
        }
}
 
$body = 'Select A Guild
<form action="' . makelink() . '" name="realm_select" method="post">
        <select name="guild" onchange="window.location.href=this.options[this.selectedIndex].value;">
        <option>----------</option>
' . $options . '
        </select>
</form>';
 
$body = messagebox($body,'','sgreen');
 
$guildset = $_GET['guild'];
 
// Build the AutoRecruit controls
if( $guildset )
{
        $sqlstring = 'SELECT * FROM ' . $roster->db->table('recruitment',$addon['basename']) . ' WHERE `guild_id` = ' . $guildset ;
        $result = $roster->db->query($sqlstring);
        if( $result )
        {
                if( $roster->db->num_rows() == 0 )
                {
                        $guild_setup = 'INSERT INTO `' . $roster->db->table('recruitment',$addon['basename']) . '` (`guild_id`, `app_link`, `min_level`, `max_druid`, `max_hunter`, `max_mage`, `max_paladin`, `max_priest`, `max_rogue`, `max_shaman`, `max_warlock`, `max_warrior`) VALUES (' . $guildset . ', "http://" , 70, 0, 0, 0, 0, 0, 0, 0, 0, 0)';
                        $new_guild = $roster->db->query($guild_setup);
                        $result = $roster->db->query($sqlstring);
                }
 
                $alldata = $roster->db->fetch_all($result);
        
                // Get Class Names
                foreach( $roster->multilanguages as $locale )
                {
                        $druids[]   = $roster->locale->wordings[$locale]['Druid'];
                        $hunters[]  = $roster->locale->wordings[$locale]['Hunter'];
                        $mages[]    = $roster->locale->wordings[$locale]['Mage'];
                        $paladins[] = $roster->locale->wordings[$locale]['Paladin'];
                        $priests[]  = $roster->locale->wordings[$locale]['Priest'];
                        $rogues[]   = $roster->locale->wordings[$locale]['Rogue'];
                        $shamans[]  = $roster->locale->wordings[$locale]['Shaman'];
                        $warlocks[] = $roster->locale->wordings[$locale]['Warlock'];
                        $warriors[] = $roster->locale->wordings[$locale]['Warrior'];
                }
        
                // Glude the arrays together
                $druids   = implode("','",$druids);
                $hunters  = implode("','",$hunters);
                $mages    = implode("','",$mages);
                $paladins = implode("','",$paladins);
                $priests  = implode("','",$priests);
                $rogues   = implode("','",$rogues);
                $shamans  = implode("','",$shamans);
                $warlocks = implode("','",$warlocks);
                $warriors = implode("','",$warriors);
 
                // Count Class SQL
                $druid_sql   = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $druids   . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $hunter_sql  = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $hunters  . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $mage_sql    = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $mages    . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $paladin_sql = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $paladins . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $priest_sql  = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $priests  . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $rogue_sql   = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $rogues   . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $shaman_sql  = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $shamans  . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $warlock_sql = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $warlocks . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
                $warrior_sql = "SELECT count(`member_id`) FROM `" . $roster->db->table('members') . "` WHERE `class` IN('" . $warriors . "') AND `guild_id` =  " . $guildset . " AND `level` >= " . $alldata[0]['min_level'];
        
                // Actual Count
                $druid_count   = $roster->db->query_first($druid_sql);
                $hunter_count  = $roster->db->query_first($hunter_sql);
                $mage_count    = $roster->db->query_first($mage_sql);
                $paladin_count = $roster->db->query_first($paladin_sql);
                $priest_count  = $roster->db->query_first($priest_sql);
                $rogue_count   = $roster->db->query_first($rogue_sql);
                $shaman_count  = $roster->db->query_first($shaman_sql);
                $warlock_count = $roster->db->query_first($warlock_sql);
                $warrior_count = $roster->db->query_first($warrior_sql);
        }
        else
        {
                roster_die('There was an error in the query','ERROR');
        }
 
        $body .= "<br /><div id=\"ar_disp\">\n" . border('sblue','start',$roster->locale->act['admin']['autorecruit_title'] ) . "\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
 
        $body .= '
        <tr>
                <th class="membersHeader">' . $roster->locale->act['admin']['setting'] . '</th>
                <th class="membersHeaderRight" colspan="2">' . $roster->locale->act['admin']['threshold'] . '</th>
        </tr>
        <tr>
                <td class="membersRow1">' . $roster->locale->act['admin']['app_link'] . '</td>
                <td class="membersRowRight1" colspan="2"><input name="ar_app_link" type="text" size="40" maxlength="80" value="' . $alldata[0]['app_link'] . '"/></td>
        </tr>
        <tr>
                <td class="membersRow2">' . $roster->locale->act['admin']['min_level'] . '</td>
                <td class="membersRowRight2" colspan="2"><input name="ar_min_level" type="text" size="5" maxlength="2" value="' . $alldata[0]['min_level'] . '"/></td>
        </tr>
        <tr>
                <th class="membersHeader">' . $roster->locale->act['admin']['setting'] . '</th>
                <th class="membersHeader">' . $roster->locale->act['admin']['actual'] . '</th>
                <th class="membersHeaderRight">' . $roster->locale->act['admin']['threshold'] . '</th>
        </tr>
        <tr>
                <td class="membersRow2">' . $roster->locale->act['Druid'] . '</td>
                <td class="membersRow2">' . $druid_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_druid" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_druid'] . '"/></td>
        </tr>
        <tr>
                <td class="membersRow1">' . $roster->locale->act['Hunter'] . '</td>
                <td class="membersRow1">' . $hunter_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_hunter" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_hunter'] . '"/></td>
        </tr>
        <tr>    
                <td class="membersRow2">' . $roster->locale->act['Mage'] . '</td>
                <td class="membersRow2">' . $mage_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_mage" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_mage'] . '"/></td>
        </tr>
        <tr>    
                <td class="membersRow1">' . $roster->locale->act['Paladin'] . '</td>
                <td class="membersRow1">' . $paladin_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_paladin" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_paladin'] . '"/></td>
        </tr>
        <tr>    
                <td class="membersRow2">' . $roster->locale->act['Priest'] . '</td>
                <td class="membersRow2">' . $priest_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_priest" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_priest'] . '"/></td>
        </tr>
        <tr>    
                <td class="membersRow1">' . $roster->locale->act['Rogue'] . '</td>
                <td class="membersRow1">' . $rogue_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_rogue" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_rogue'] . '"/></td>
        </tr>
        <tr>    
                <td class="membersRow2">' . $roster->locale->act['Shaman'] . '</td>
                <td class="membersRow2">' . $shaman_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_shaman" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_shaman'] . '"/></td>
        </tr>
        <tr>    
                <td class="membersRow1">' . $roster->locale->act['Warlock'] . '</td>
                <td class="membersRow1">' . $warlock_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_warlock" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_warlock'] . '"/></td>
        </tr>
        <tr>    
                <td class="membersRow2">' . $roster->locale->act['Warrior'] . '</td>
                <td class="membersRow2">' . $warrior_count . '</td>
                <td class="membersRowRight2"><input name="ar_max_warrior" type="text" size="5" maxlength="2" value="' . $alldata[0]['max_warrior'] . '"/></td>
        </tr>
';
        $body .= "</table>\n" . border('sblue','end') . "\n</div>\n";
}
else
{
        $body .= 'No Data';
}
 
                $roster->output['body_onload'] .= 'initARC(\'config\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';
                
                $body = $roster_login->getMessage() . "<br />
<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('" . $roster->locale->act['confirm_config_submit'] . "');submitonce(this);\">
        $body
<br /><br />\n<input type=\"submit\" value=\"" . $roster->locale->act['config_submit_button'] . "\" />\n<input type=\"reset\" name=\"Reset\" value=\"" . $roster->locale->act['config_reset_button'] . "\" onclick=\"return confirm('" . $roster->locale->act['confirm_config_reset'] . "')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n
</form>";
 
/**
* Process Data for entry to the database
*
* @return string Settings changed or not changed
*/
function processData( )
{
        global $roster, $guildset;
 
        $update_sql = array();
 
        // Update only the changed fields
        foreach( $_POST as $settingName => $settingValue )
        {
                if( substr($settingName,0,3) == 'ar_' )
                {
                        $settingName = str_replace('ar_','',$settingName);
                        
                        if( $settingName != 'process' )
                        {
                                $update_sql += array($settingName,$settingValue);
                        }
                }
        }
 
        $update_sql = "UPDATE `" . $roster->db->table('recruitment') . "`"
                                . " SET " . $roster->db->build_query('UPDATE',$update_sql)
                                . " WHERE `guild_id` = '$guildset';";
 
        // Update DataBase
        $result = $roster->db->query($update_sql);
        if( !$result )
        {
                return '<span style="color:#0099FF;font-size:11px;">Error saving settings</span><br />MySQL Said:<br /><pre>' . $roster->db->error() . '</pre><br />';
        }
        return '<span style="color:#0099FF;font-size:11px;">Settings have been changed</span>';
} 
