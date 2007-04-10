<?php
/* 
* $Date: 2006/01/17 09:29:23 $ 
* $Revision: 1.7 $ 
*/ 

if (eregi("membersRep.php",$_SERVER['PHP_SELF'])) {
    die("You can't access this file directly!");
}


// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$lang]."') from `".ROSTER_GUILDTABLE."` where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
        $guildId = $row[0];
        $updateTime = $row[1];
} else {
        die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

$content .= "<br><center>\n";

$query = "SELECT m.name member, r.faction, r.name fct_name, r.value,
            IF (r.standing = 'unfriendly' OR r.standing = 'hostile' OR r.standing = 'hated',
              -( substring( r.value, 1, locate('/', r.value)-1) + 0 ),
               ( substring( r.value, 1, locate('/', r.value)-1) + 0 )) as curr_rep,
            IF(r.standing = 'unfriendly' OR r.standing = 'hostile' OR r.standing = 'hated',
              -( substring( r.value, locate('/', r.value)+1, length(r.value)-locate('/', r.value)) + 0 ),
               ( substring( r.value, locate('/', r.value)+1, length(r.value)-locate('/', r.value)) + 0 )) as max_rep,
            r.standing
            FROM `".ROSTER_REPUTATIONTABLE."` r, ".$db_prefix."members m
            WHERE r.member_id = m.member_id";

if ((isset($_REQUEST["factionfilter"])) and (($_REQUEST["factionfilter"])!='All'))
           $query.= " and r.name='".$_REQUEST["factionfilter"]."'";

$query.=  " ORDER BY max_rep desc, r.standing desc, curr_rep desc";




$qry_fct = "select distinct(name) fct_name from ".ROSTER_REPUTATIONTABLE."
                  order by name
                 ";


if (isset($_REQUEST["factionfilter"])){
    $result = mysql_query($query) or die(mysql_error());
    if ($sqldebug) {
            $content .= ("<!--$query-->\n");
    }
}

$result_fct = mysql_query($qry_fct) or die(mysql_error());
if ($sqldebug) {
        $content .= ("<!--$query-->\n");
}


$choiceForm = '<form action="addon.php" method=GET name=myform>
                   <input type="hidden" name="roster_addon_name" value="reputation">'
                 .$wordings[$roster_lang]['faction_filter'].'
                   <select NAME="factionfilter">';

while($row_fct = mysql_fetch_array($result_fct)){

    if ($_REQUEST["factionfilter"]==$row_fct["fct_name"])
      $choiceForm .= '<option VALUE="'.$row_fct["fct_name"].'" selected>'.$row_fct["fct_name"];
    else
      $choiceForm .= '<option VALUE="'.$row_fct["fct_name"].'">'.$row_fct["fct_name"];
}
mysql_free_result($result_fct);


$choiceForm .= '</select>
                 <input type="submit" value="'.$wordings[$roster_lang]['applybutton'].'" />
               </form>';

$content .=($choiceForm);

if (isset($_REQUEST["factionfilter"])){

      $tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">';
      $borderTop = '<tr><th colspan="6" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>';
      $tableHeaderRow = '<tr>
              <th class="rankbordercenterleft"><div class="membersHeader">'.$wordings[$roster_lang]['rep_name'].'</div></th>
              <th class="membersHeader">'.$wordings[$roster_lang]['rep_group'].'</th>
              <th class="membersHeader">'.$wordings[$roster_lang]['rep_faction'].'</th>
              <th class="membersHeader">'.$wordings[$roster_lang]['rep_status'].'</th>
              <th class="membersHeader">'.$wordings[$roster_lang]['rep_value'].'</th>
              <th class="rankbordercenterright"><div class="membersHeaderRight">'.$wordings[$roster_lang]['rep_max'].'</div></th>
              </tr>';
      $borderBottom = '<tr><th colspan="6" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th></tr>';
      $tableFooter = '</table>';

      $content .=($tableHeader);
      $content .=($borderTop);

      // Counter for row striping
      $striping_counter = 0;
      if (isset($_GET['s'])) {
              $current_sorting = $_GET['s'];
      } else {
              $current_sorting = '';
      }

      $last_value = '';
      $content .=($tableHeaderRow);
      while($row = mysql_fetch_array($result)){
         // Striping rows
              $content .=('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");

              // Increment counter so rows are colored alternately
              ++$striping_counter;

              $content .=('<td class="rankbordercenterleft"><div class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['member'].'</div></td>');
              $content .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['faction'].'</td>');
              $content .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['fct_name'].'</td>');
              $content .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['standing'].'</td>');
              $content .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['curr_rep'].'</td>');
              $content .=('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$row['max_rep'].'</div></td>');
              $content .=('</tr>');
      }
      $content .=($borderBottom);
      $content .=($tableFooter);
      $content .= "\n</center>\n";
}
mysql_free_result($result);
?>

</table>