<?php
/*
* $Date: 2006/02/28 $
* $Revision: 2.0 $
*/

require_once 'maxres.php';
require_once ROSTER_BASE.'lib/item.php';




$server_name_escape = $wowdb->escape($roster_conf['server_name']);
$guild_name_escape = $wowdb->escape($roster_conf['guild_name']);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
if ($row = $wowdb->fetch_array($result)) {
    $guildId = $row[0];
    $updateTime = $row[1];
} else {
include ('header');
    die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
include ('footer');
}

$script_url = basename($_SERVER['PHP_SELF']);
if(isset($_SERVER['QUERY_STRING']))
    {$script_url .= '?'.$_SERVER['QUERY_STRING'];}

$addon_name = $_REQUEST['roster_addon_name'];
$script_filename = '&amp;file=addon&amp;roster_addon_name='.$addon_name;
$patched = 0;

//check for db patch
$result_checkdb = mysql_query ("SELECT * FROM `".ROSTER_PLAYERSTABLE."` LIMIT 1");
$i = 0;
while ($i < mysql_num_fields($result_checkdb)) {
   $meta = mysql_fetch_field($result_checkdb, $i);
    if ($meta->name == 'max_res_all') {
        $patched = 1;
    }
   $i++;
}
mysql_free_result($result_checkdb);

if( $_REQUEST['install'] == 'install' ) {
    if ( $patched == 0 ) {
    $result_install = mysql_query ("ALTER TABLE `".ROSTER_PLAYERSTABLE."` ADD `max_res_all` INT NULL, ADD `max_res_fire` INT NULL , ADD `max_res_nat` INT NULL , ADD `max_res_arc` INT NULL , ADD `max_res_fro` INT NULL , ADD `max_res_shad` INT NULL ;");
    }
}

if ( $patched == 0 && !isset($_REQUEST['install']) ) {


    die("We need to add 4 columns to your player table to the hold the max resists. Click <a href='$script_url&amp;install=install'>Here</a> to install.");


}

$icon_size = "$TS_iconsize";

if( isset($_REQUEST['resist']) )
    $resist = $_REQUEST['resist'];
else
    $resist = "";

if( isset($_REQUEST['maxresname']) )
    $name = $_REQUEST['maxresname'];
else
    $name = "";

if( isset($_REQUEST['name']) )
    $URLname = $_REQUEST['name'];
else
    $URLname = "";

if($URLname != "") {
    $charURLname = "cname";
    $charURLserver = "cserver";
    } else {
    $charURLname = "name";
    $charURLserver = "server";
}

echo '<link rel="stylesheet" type="text/css" href="'.$roster_dir."/addons/".$addon_name."/style.css\">\n";

if($name == "" and $resist == "")
{
#join the tables. These are small tables thankfully
$query = "SELECT members.member_id, members.name, members.class, members.level, players.clientLocale, " .
                    "players.server, players.max_res_all, players.max_res_fire, players.max_res_nat, players.max_res_arc, players.max_res_fro, players.max_res_shad".
                    " FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON members.member_id = players.member_id AND members.guild_id = $guildId".
                    " AND ( players.max_res_all > 0 OR players.max_res_all is null OR players.max_res_fire > 0 OR players.max_res_fire is null OR players.max_res_nat > 0 OR players.max_res_nat is null ".
                    " OR players.max_res_arc > 0 OR players.max_res_arc is null OR players.max_res_fro > 0 OR players.max_res_fro is null OR players.max_res_shad > 0 OR players.max_res_shad is null)";

// Add custom primary and secondary ORDER BY definitions
if (isset($_GET['s'])) {
    $switchString = ($_GET['s'])?$_GET['s']:'';
} else {
    $switchString = '';
}
switch ($switchString) {
    case 'name':
        $query .= " ORDER BY members.name ASC";
        break;
    case 'class':
        $query .= " ORDER BY members.class ASC, members.name ASC";
        break;
    case 'level':
        $query .= " ORDER BY members.level DESC, members.name ASC";
        break;
    case 's_res_all':
        $query .= " ORDER BY players.max_res_all DESC, members.name ASC";
        break;
    case 's_res_fire':
        $query .= " ORDER BY players.max_res_fire DESC, members.name ASC";
        break;
    case 's_res_nat':
        $query .= " ORDER BY players.max_res_nat DESC, members.name ASC";
        break;
    case 's_res_arc':
        $query .= " ORDER BY players.max_res_arc DESC, members.name ASC";
        break;
    case 's_res_fro':
        $query .= " ORDER BY players.max_res_fro DESC, members.name ASC";
        break;
    case 's_res_shad':
        $query .= " ORDER BY players.max_res_shad DESC, members.name ASC";
        break;
    default:
        $query .= " ORDER BY members.name ASC";
}

$result = mysql_query($query) or die(mysql_error());

if ($sqldebug) {
    $content .= ("<!--$query-->\n");
}
$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">';
$tableHeaderRow = '<tr>
    <td class="membersHeader" width="12%"><a href="'.getlink($script_filename.'&amp;s=name').'">'.$wordings[$roster_conf['roster_lang']]['name'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=class').'">'.$wordings[$roster_conf['roster_lang']]['class'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=level').'">'.$wordings[$roster_conf['roster_lang']]['level'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=s_res_all').'">'.$wordings[$roster_conf['roster_lang']]['res_all'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=s_res_fire').'">'.$wordings[$roster_conf['roster_lang']]['res_fire'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=s_res_nat').'">'.$wordings[$roster_conf['roster_lang']]['res_nature'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=s_res_arc').'">'.$wordings[$roster_conf['roster_lang']]['res_arcane'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=s_res_fro').'">'.$wordings[$roster_conf['roster_lang']]['res_frost'].'</a></td>
    <td class="membersHeader"><a href="'.getlink($script_filename.'&amp;s=s_res_shad').'">'.$wordings[$roster_conf['roster_lang']]['res_shadow'].'</a></td>
</tr>';

$tableFooter = '</table>';

echo border('syellow','start', 'Max Resists');
$content .= '<table width="500" align="center"><tr><td><font style="font-size: xx-small; color: #CCC;">'.$wordings[$roster_conf['roster_lang']]['maxResTitleInfo'].'</font></td></tr></table>';

$content .= $tableHeader;

// Counter for row striping
$striping_counter = 0;
if (isset($_GET['s'])) {
    $current_sorting = $_GET['s'];
} else {
    $current_sorting = '';
}

$last_value = '';
while($row = mysql_fetch_array($result)) {
    if ($row['server']) {
        // Adding grouping dividers
        if ($current_sorting == 'class') {
            if ($last_value != $row['class']) {
                if ($striping_counter != 0) {
                    $content .= $borderBottom;
                }
                $content .='<tr><td colspan="9" class="membersGroup" id="'.$current_sorting.'-'.$row['class'].'">'.$row['class']."</td></tr>\n";
                $content .= $borderTop;
                $content .= $tableHeaderRow;
                $striping_counter = 0;
            }
            $last_value = $row['class'];
        } elseif ($current_sorting == 'level') {
            if ( $last_value != $row['level']) {
                if ($striping_counter != 0) {
                    $content .= $borderBottom;
                }
                $content .= '<tr><td colspan="9" class="membersGroup" id="'.$current_sorting.'-'.$row['level'].'">'.$row['level']."</td></tr>\n";
                $content .= $borderTop;
                $content .= $tableHeaderRow;
                $striping_counter = 0;
            }
            $last_value = $row['level'];
        } else {
            if ($striping_counter == 0) {
                $content .= $borderTop;
                $content .= $tableHeaderRow;
           }
        }

        //echo $client_lang;
        $client_lang     = $row['clientLocale'];

        // Increment counter so rows are colored alternately
        ++$striping_counter;

        $res_all    = getres($row['member_id'], $client_lang, $wordings[$client_lang]['res_all']);
        $res_fire    = getres($row['member_id'], $client_lang, $wordings[$client_lang]['Fire Resistance']);
        $res_nat    = getres($row['member_id'], $client_lang, $wordings[$client_lang]['Nature Resistance']);
        $res_arc    = getres($row['member_id'], $client_lang, $wordings[$client_lang]['Arcane Resistance']);
        $res_fro    = getres($row['member_id'], $client_lang, $wordings[$client_lang]['Frost Resistance']);
        $res_shad    = getres($row['member_id'], $client_lang, $wordings[$client_lang]['Shadow Resistance']);

        $max_res = ("UPDATE ".ROSTER_PLAYERSTABLE." SET `max_res_all`='$res_all', `max_res_fire`='$res_fire', `max_res_nat`='$res_nat', `max_res_arc`='$res_arc', `max_res_fro`='$res_fro', `max_res_shad`='$res_shad' WHERE ".ROSTER_PLAYERSTABLE.".member_id='".$row["member_id"]."'");
        $max_res_result = mysql_query($max_res) or die(mysql_error());

        // Echoing cells w/ data
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.'<a href="'.getlink('&amp;file=char&amp;'.$charURLname.'='.$row['name'].'&amp;'.$charURLserver.'='.$row['server']).'">'.$row['name'].'</a></td>';
        $icon_name_wow = array_search( $row['class'], $wordings[$roster_conf['roster_lang']] );
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'"><img class="membersRowimg" width="'.$icon_size.'" height="'.$icon_size.'" src="'.$img_url.'class/class_'.$icon_name_wow.'.jpg" alt="">&nbsp;'.$row['class'].'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['level'].'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_all'],$res_all).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_fire'],$res_fire).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_nature'],$res_nat).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_arcane'],$res_arc).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_frost'],$res_fro).'</td>';
        $content .= '<td class="membersRow"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_shadow'],$res_shad).'</div></td>';

        $content .= '</tr>';
    }

}

$content .= $borderBottom ;
// not sure why, but i had to comment this to make it work.
//$content .= $tableFooter;
mysql_free_result($result);

echo $content;

//Individual Equip
} else {

echo '<table width="60%"><tr><td align="center"><font style="color: #CBA300; font-weight: bold;">'.$wordings[$roster_conf['roster_lang']]['MaxRes'].'</font></td></tr>';

echo '<tr><td align="center"><font style="font-size: xx-small; color: #CBA300; font-weight: bold;">'.$wordings[$roster_conf['roster_lang']]['maxResIndivInfo'].' <a href="'.getlink($script_filename).'">'.$wordings[$roster_conf['roster_lang']]['goBack'].'</a>.</font></td></tr></table>';

?>

<div align="<?php print $roster_conf['char_bodyalign']; ?>">

<?php

$maxres = char_get_userinfo( $name, $roster_conf['server_name'] ); ?>

<br />
<table border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td align="left">
<?php

    $maxres->out();

    echo "</td><td valign=\"top\">\n";

    $maxres->Summaryout();

    echo "</td>\n";
}
?>

</tr></table>
<?php echo border('syellow','end'); ?>
