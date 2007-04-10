<?php
function SelectQuery($table,$fieldtoget,$field,$current,$fieldid,$urltorun)  {
     /*table, field, current option if matching to existing data (EG: $row['state'])
      and you want the drop down to be preselected on their current data, the id field from that table (EG: stateid)*/
     //
    $sql = "SELECT ".$fieldtoget." FROM ".$table." ORDER BY quests.".$field." ASC";

   // Check SQL for debug only when changing
   //print $sql;

   // execute SQL query and get result
   $sql_result = mysql_query($sql)
   or die("Couldn't execute query: ".$sql);

   // put data into drop-down list box
  while ($row = mysql_fetch_assoc($sql_result))
     {
      $id = $row["$fieldid"];//must leave double quote
      $optiontocompare = addslashes($row["$field"]);//must leave double quote
      $optiontodisplay = $row["$field"];//must leave double quote

      if ($current == $optiontocompare)
        {
         $option_block .= "<OPTION value=\"$urltorun=$id\" selected>$optiontodisplay</OPTION>";
        }
      else
       {
        $option_block .= "<OPTION value=\"$urltorun=$id\" >$optiontodisplay</OPTION>";
       }
     }
  // dump out the list
  return $option_block;
}

require_once 'conf.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);

$query = "SELECT guild_id, DATE_FORMAT(update_time, '%b %d %l%p') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' AND server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
  $guildId = $row[0];
  $updateTime = $row[1];
} else {
  die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

// The next two lines call the function SelectQuery and use it to populate and return the code that lists the dropboxes for quests and for zones
$option_blockzones = selectQuery("`".ROSTER_QUESTSTABLE."` quests,`".ROSTER_MEMBERSTABLE."` members WHERE quests.member_id = members.member_id","DISTINCT quests.zone","zone",addslashes($_GET['zoneid']),"zone","indexquests.php?zoneid");
$option_blockquests = selectQuery("`".ROSTER_QUESTSTABLE."` quests,`".ROSTER_MEMBERSTABLE."` members WHERE quests.member_id = members.member_id","DISTINCT quests.quest_name","quest_name",addslashes($_GET['questid']),"quest_name","indexquests.php?questid");

// Don't forget the menu !!
print('<table><td>');
include 'lib/menu.php';
print('</td><td>');
include 'lib/thot_search.php';
print('</td></table>');


print('<br><br>');?>
<table bgcolor="#292929" cellspacing="0" cellpadding="4" border="0">
	<tr><td bgcolor="#000000" colspan="100%" class="rankbordertop">
		<span class="rankbordertopleft"></span>
		<span class="rankbordertopright"></span>
	</td></tr>
  <tr>
  	<td bgcolor="#000000" class="rankbordercenterleft">
    <td valign="center">

   </td>
    <td valign="center">
    </td>
    <td bgcolor="#000000" class="rankbordercenterright">
  </tr>
  <tr>
  	<td bgcolor="#000000" class="rankbordercenterleft">
    <td align="center" colspan="2"><div class="membersRow"> 
<?    
print $wordings[$roster_lang]['search1'];
print('<br><br>');
print('<form>');
print($wordings[$roster_lang]['search2'].':&nbsp;<select name="zoneid" onChange="top.location.href=this.options[this.selectedIndex].value"><OPTION value="">Not Selected....</OPTION>' . $option_blockzones . '</select>');
print('&nbsp;&nbsp;'.$wordings[$roster_lang]['search3'].':&nbsp;');
print('<select name="questid" onChange="top.location.href=this.options[this.selectedIndex].value"><OPTION value="">Not Selected....</OPTION>' . $option_blockquests . '</select>');
print('<br><br>');
print('</form>');
?>
		</div>
		</td>
		<td bgcolor="#000000" class="rankbordercenterright">
  </tr>
  <tr><td bgcolor="#000000" colspan="100%" class="rankborderbot">
  	<span class="rankborderbotleft"></span>
  	<span class="rankborderbotright"></span>
  </td></tr>
</table>
<?
if (isset($_GET['zoneid']) or isset($_GET['questid'])) {
$zquery = "SELECT DISTINCT zone FROM `".ROSTER_QUESTSTABLE."` WHERE zone = '".$_GET['zoneid']."' ORDER BY zone";

$zresult = mysql_query($zquery) or die(mysql_error());
if ($sqldebug) {
print ("<!--$query-->");
}
while($zrow = mysql_fetch_array($zresult))
{
print('<h1>'.$zrow['zone'].'</h1>');

$qquery = "SELECT DISTINCT quest_name";
$qquery .= " FROM `".ROSTER_QUESTSTABLE."`";
$qquery .= " WHERE zone = '" .$_GET['zoneid'] . "'";
$qquery .= " ORDER BY quest_name";

$qresult = mysql_query($qquery) or die(mysql_error());
if ($sqldebug) {
print ("<!--$query-->");
}

while($qrow = mysql_fetch_array($qresult))
{

print('<br>'.$qrow['quest_name'].'<br>');

$query = "SELECT q.zone, q.quest_name, q.quest_level, p.name, p.server";
$query .= " FROM `".ROSTER_QUESTSTABLE."` q, `".ROSTER_PLAYERSTABLE."` p";
$query .= " WHERE q.zone = '" .$_GET['zoneid'] . "' AND q.member_id = p.member_id AND q.quest_name = '" . addslashes($qrow['quest_name']) . "'";
$query .= " ORDER BY q.zone, q.quest_name, q.quest_level, p.name";

  print ("<!--$query-->");

$result = mysql_query($query) or die(mysql_error());
if ($sqldebug) {
  print ("<!--$query-->");
}
$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">
';
$borderTop = '<tr><th colspan="8" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>
';
$tableHeaderRow = '<tr>
    <th class="rankbordercenterleft"><div class="membersHeader">Zone</div></th>
    <th class="membersHeader">Quest Name</th>
    <th class="membersHeader">Quest Level</th>
    <th class="rankbordercenterright"><div class="membersHeader">Member</div></th>
  </tr>
';
$borderBottom = '<tr><th colspan="8" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th></tr>
';
$tableFooter = '</table>
';

print($tableHeader);
print($borderTop);
print($tableHeaderRow);

while($row = mysql_fetch_array($result))
{

  print('<tr>');

  // Increment counter so rows are colored alternately
  ++$striping_counter;

  // Echoing cells w/ data
  print('<td class="rankbordercenterleft"><div class="membersRow'. (($striping_counter % 2) +1) .'">');
  print($row['zone']);
  print('</div></td>');

  print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['quest_name'].'</td>');
  print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['quest_level'].'</td>');
  print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">');
  if ($row['server']) {

print('<a href="javascript:void(0)"'.
'onClick="w = window.open(\'char.php?name='.$row['name'].'&server='.$row['server'].'&action=character\',\'PopUp\',\'width=650,height=750,left=200,top=200,screenX=200,screenY=200,fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=yes,directories=no,location=no\'); return true;"'.
'>'.$row['name'].'</a>');

  } else {
    print($row['name']);
  }
print('</div></td>');
  print("</tr>\n");
}

print($borderBottom);
print($tableFooter);
mysql_free_result($result);
}
}
  print "<br /><br /><p id=\"last_update\" class=\"last_update\">Last updated $updateTime</p>";
}

if (isset($_GET['questid'])) {

$qnquery = "SELECT DISTINCT quest_name FROM `".ROSTER_QUESTSTABLE."` WHERE quest_name = '" .$_GET['questid'] . "' ORDER BY quest_name";

$qnresult = mysql_query($qnquery) or die(mysql_error());

if ($sqldebug) {
  print ("<!--$query-->");
}

while($qnrow = mysql_fetch_array($qnresult))
{
print('<h1>'.$qnrow['quest_name'].'</h1><br />');

$query = "SELECT q.zone, q.quest_name, q.quest_level, p.name, p.server";
$query .= " FROM `".ROSTER_QUESTSTABLE."` q, `".ROSTER_PLAYERSTABLE."` p";
$query .= " WHERE q.member_id = p.member_id AND q.quest_name = '" . addslashes($qnrow['quest_name'])  . "'";
$query .= " ORDER BY q.zone, q.quest_name, q.quest_level, p.name";

$result = mysql_query($query) or die(mysql_error());
if ($sqldebug) {
  print ("<!--$query-->");
}
$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">
';
$borderTop = '<tr><th colspan="8" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>
';
$tableHeaderRow = '<tr>
    <th class="rankbordercenterleft"><div class="membersHeader">Member</div></th>
    <th class="membersHeader">Quest Level</th>
    <th class="rankbordercenterright"><div class="membersHeader">Zone</div></th>
  </tr>
';
$borderBottom = '<tr><th colspan="8" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th></tr>
';
$tableFooter = '</table>
';


print($tableHeader);
print($borderTop);
print($tableHeaderRow);

while($row = mysql_fetch_array($result))
{

  print('<tr>');

  // Increment counter so rows are colored alternately
  ++$striping_counter;

  // Echoing cells w/ data
  print('<td class="rankbordercenterleft"><div class="membersRow'. (($striping_counter % 2) +1) .'">');
  if ($row['server']) {

  print('<a href="javascript:void(0)"'.
'onClick="w = window.open(\'char.php?name='.$row['name'].'&server='.$row['server'].'&action=character\',\'PopUp\',\'width=650,height=750,left=200,top=200,screenX=200,screenY=200,fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=yes,directories=no,location=no\'); return true;"'.
'>'.$row['name'].'</a>');

  } else {
    print($row['name']);
  }
  print('</div></td>');
  print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['quest_level'].'</td>');
  print('<td class="rankbordercenterright"><div class="membersRowRight'. (($striping_counter % 2) +1) .'">');
  print($row['zone']);
  print('</div></td>');
  print("</tr>\n");
}

print($borderBottom);
print($tableFooter);
mysql_free_result($result);
}
  print "<br /><br /><p id=\"last_update\" class=\"last_update\">Last updated $updateTime</p>";
}

?>