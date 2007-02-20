<?php
/*
* $Date: 2006/02/28 $
* $Revision: 2.0 $
*/

require_once ROSTER_BASE.'addons/maxres/maxres.php';
require_once ROSTER_BASE.'lib/item.php';


if (!defined('CPG_NUKE')) { exit; }

$server_name_escape = $wowdb->escape($roster_conf['server_name']);
$guild_name_escape = $wowdb->escape($roster_conf['guild_name']);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
if ($row = $wowdb->fetch_array($result)) {
    $guildId = $row[0];
    $updateTime = $row[1];
} else {
    die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}
$wowdb->free_result($result);

$script_url = basename($_SERVER['PHP_SELF']);
if(isset($_SERVER['QUERY_STRING']))
    {$script_url .= '?'.$_SERVER['QUERY_STRING'];}

$script_filename = $module_name.'&amp;file=addon&amp;roster_addon_name=maxres';
$addon_name = $_REQUEST['roster_addon_name'];
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
    $result_install = $wowdb->query("ALTER TABLE `".ROSTER_PLAYERSTABLE."` ADD `max_res_all` INT NULL, ADD `max_res_fire` INT NULL , ADD `max_res_nat` INT NULL , ADD `max_res_arc` INT NULL , ADD `max_res_fro` INT NULL , ADD `max_res_shad` INT NULL ;");
    $wowdb->free_result($result_install);
    }
}

if ( $patched == 0 && !isset($_REQUEST['install']) ) {
    die("We need to add 4 columns to your player table to the hold the max resists. Click <a href='$script_url&amp;install=install'>Here</a> to install.");
}

$icon_size = $roster_conf['index_iconsize']; 

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
    $charURLserver = "server";
    } else {
    $charURLname = "name";
    $charURLserver = "server";
}

if($name == "" and $resist == "")
{
#join the tables. These are small tables thankfully
$query = "SELECT members.member_id, members.name, members.class, members.level, players.clientLocale, " .
                    "players.server, players.max_res_all, players.max_res_fire, players.max_res_nat, players.max_res_arc, players.max_res_fro, players.max_res_shad".
                    " FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON members.member_id = players.member_id AND members.guild_id = $guildId";

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

$result = $wowdb->query($query) or die($wowdb->error());

if ($roster_conf['sqldebug']) {
    $content .= ("\n<!--$query-->\n");
}

function borderTop( $text='' )
{
        return "<br />\n".border('syellow','start',$text)."\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
}

// Display explanation text
$content .= border('syellow','start', '');
$content .= '<table width="500" align="center"><tr><td><font style="font-size: xx-small; color: #CCC;">'.$wordings[$roster_conf['roster_lang']]['maxResTitleInfo'].'</font></td></tr></table>';
$content .= border('syellow','end')."<br />";

// Start constructing the big table

$tableHeaderRow = '<tr>
    <th class="membersHeader" width="12%"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=name').'">'.$wordings[$roster_conf['roster_lang']]['name'].'</a></th>
    <th class="membersHeader"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=class').'">'.$wordings[$roster_conf['roster_lang']]['class'].'</a></th>
    <th class="membersHeader"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=level').'">'.$wordings[$roster_conf['roster_lang']]['level'].'</a></th>
    <th class="membersHeader"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=s_res_all').'">'.$wordings[$roster_conf['roster_lang']]['res_all'].'</a></th>
    <th class="membersHeader"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=s_res_fire').'">'.$wordings[$roster_conf['roster_lang']]['res_fire'].'</a></th>
    <th class="membersHeader"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=s_res_nat').'">'.$wordings[$roster_conf['roster_lang']]['res_nature'].'</a></th>
    <th class="membersHeader"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=s_res_arc').'">'.$wordings[$roster_conf['roster_lang']]['res_arcane'].'</a></th>
    <th class="membersHeader"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=s_res_fro').'">'.$wordings[$roster_conf['roster_lang']]['res_frost'].'</a></th>
    <th class="membersHeaderRight"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$addon_name.'&amp;&amp;s=s_res_shad').'">'.$wordings[$roster_conf['roster_lang']]['res_shadow'].'</a></th>
</tr>
';

$borderBottom = "</table>\n".border('syellow','end');

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
                $content .= borderTop(class_divider($row['class'])).$tableHeaderRow;
                $striping_counter = 0;
            }
            $last_value = $row['class'];
        } elseif ($current_sorting == 'level') {
            if ( $last_value != $row['level']) {
                if ($striping_counter != 0) {
                    $content .= $borderBottom;
                }
		$content .= borderTop('<div class="membersGroup">Level '.$row['level'].'</div>').$tableHeaderRow;
                $striping_counter = 0;
            }
            $last_value = $row['level'];
        } else {
            if ($striping_counter == 0) {
                $content .= borderTop('<div class="membersGroup">Max. Resists</div>').$tableHeaderRow;
           }
        }

        //echo $client_lang;
        $client_lang     = $row['clientLocale'];

        // Increment counter so rows are colored alternately
        ++$striping_counter;

        $res_all	= $row['max_res_all'];
        $res_fire	= $row['max_res_fire'];
        $res_nat	= $row['max_res_nat'];
        $res_arc	= $row['max_res_arc'];
        $res_fro	= $row['max_res_fro'];
        $res_shad	= $row['max_res_shad'];

	// Prepare class column
	
	// Class Icon
	if( $roster_conf['index_classicon'] == 1 ) {
		foreach ($roster_conf['multilanguages'] as $language) {
			$icon_name = $wordings[$language]['class_iconArray'][$row['class']];
			if( strlen($icon_name) > 0 ) break;
		}
		$icon_name = 'Interface/Icons/'.$icon_name;
		$classcol = '<img class="membersRowimg" width="'.$roster_conf['index_iconsize'].'" height="'.$roster_conf['index_iconsize'].'" src="'.$roster_conf['interface_url'].$icon_name.'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';
	}
	else
	{
		$classcol = '';
	}

	// Class name coloring
	if ( $roster_conf['index_class_color'] == 1 ) {
		$classcol .= '<span class="class'.$row['class'].'txt">'.$row['class'].'</span>';
	}
	else {
		$classcol .= $row['class'];
	}	

        // Echoing cells w/ data
	$content .= "<tr>\n";
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.'<a href="'.getlink($module_name.'&amp;file=char&amp;'.$charURLname.'='.$row['name'].'&'.$charURLserver.'='.$row['server']).'">'.$row['name'].'</a></td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$classcol.'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['level'].'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_all'],$res_all).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_fire'],$res_fire).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_nature'],$res_nat).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_arcane'],$res_arc).'</td>';
        $content .= '<td class="membersRow'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_frost'],$res_fro).'</td>';
        $content .= '<td class="membersRowRight'. (($striping_counter % 2) +1) .'">'.ResistLink($row['name'],$server,$wordings[$roster_conf['roster_lang']]['res_shadow'],$res_shad).'</td>';
        $content .= "</tr>\n";
    }

}

$content .= '</table>';
$content .= border('syellow','end');

mysql_free_result($result);

echo $content;

//Individual Equip
} else {

echo '<table width="60%"><tr><td align="center"><font style="color: #CBA300; font-weight: bold;">'.$wordings[$roster_conf['roster_lang']]['MaxRes'].'</font></td></tr>';

echo '<tr><td align="center"><font style="font-size: xx-small; color: #CBA300; font-weight: bold;">'.$wordings[$roster_conf['roster_lang']]['maxResIndivInfo'].' <a href="'.$script_filename.'">'.$wordings[$roster_conf['roster_lang']]['goBack'].'</a>.</font></td></tr></table>';

$url = '<a href="?name='.$name.'&amp;server='.$server;

$menu_cell = '      <td class="menubarHeader" align="center" valign="middle">';
?>
  <link rel="stylesheet" type="text/css" href="<?php echo $roster_conf['stylesheet'] ?>">
  

<div align="<?php print $roster_conf['char_bodyalign']; ?>">

<?php

$date_char_data_updated = DateCharDataUpdated($name);

$maxres = char_get_userinfo( $name, $roster_conf['server_name'] ); ?>

<br />
<table border="0" cellpadding="0" cellspacing="0" >
  <tr><td colspan="2">

<?php echo '<span class="lastupdated">'.$wordings[$roster_conf['roster_lang']]['lastupdate'].': '.$date_char_data_updated."</span>\n"; ?>

  </td>    </tr>
  <tr>
    <td align="left">
<?php

    $maxres->out();

    echo "</td><td valign=\"top\">\n";

    $maxres->Summaryout();

    //echo "</td>\n";
    echo "</tr>\n</table>";
    //echo border('syellow','end');
}

// Class divider. Shameless copy from memberslist.php

function class_divider ( $text )
{
        global $wordings, $roster_conf;

        // Class Icon
        foreach ($roster_conf['multilanguages'] as $language)
        {
                $icon_name = $wordings[$language]['class_iconArray'][$text];
                if( strlen($icon_name) > 0 ) break;
        }

        $icon_name = 'Interface/Icons/'.$icon_name;

        $icon_value = '<img class="membersRowimg" width="16" height="16" src="'.$roster_conf['interface_url'].$icon_name.'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';

        return '<div class="membersGroup">'.$icon_value.$text.'</div>';

}

?>


