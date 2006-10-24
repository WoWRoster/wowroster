<?php
session_start();
	if ($_SESSION[s_id]!=session_id()){
		session_destroy();
		header("Location:index.php");
		exit();}
/* 
* $Date: 2006/07/05 19:38:52 $ 
* $Revision: 0.4.2 $  ORIGINAL by cybrey thx :) very good work
*/ 
require_once(BASEDIR.DIR_SEP.'modules'.DIR_SEP.$module_name.DIR_SEP.'lib'.DIR_SEP.'recipes.php');
include ($addonDir.'lib/sql.php');

//Installation for DB if not exists
$result_install = $wowdb->query("SHOW TABLES LIKE '".$db_prefix."addon_shopping';");
$table_exist = $wowdb->fetch_assoc($result_install);
if(empty($table_exist)){
    installDB();
    }
else{
include ($addonDir.'phpCart_minibasket.php');

$server_name_escape = $wowdb->escape($roster_conf['server_name']);
$server = $_REQUEST['server'];
$guild_name_escape = $wowdb->escape($roster_conf['guild_name']);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$lang]."') from `".ROSTER_GUILDTABLE."` where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$querymembers = "SELECT name from `".ROSTER_MEMBERSTABLE."` order by name ASC";
$result = mysql_query($query) or die(mysql_error());
$resultmembers = mysql_query($querymembers) or die(mysql_error());
$members = array();
while ($row2 = mysql_fetch_row($resultmembers)) {
        array_push($members,$row2[0]);
} 
$_SESSION["members"] = $members;
if ($row = mysql_fetch_row($result)) {
        $guildId = $row[0];
        $updateTime = $row[1];
        $content .= ("<!--$guildId $updateTime-->\n");
} else {
        die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

$qry_prof  = "select distinct( skill_name) proff from ".ROSTER_RECIPESTABLE." where skill_name != '".$wordings[$roster_conf['roster_lang']]['First Aid']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['poisons']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['Mining']."' order by skill_name";

$result_prof = mysql_query($qry_prof) or die(mysql_error());
if ($sqldebug) {
        $content .= ("<!--$query $sqldebug-->\n");
}



$choiceForm = '<form action="" method=GET name=myform>
               <input type="hidden" name="name" value="'.$module_name.'">
			   <input type="hidden" name="file" value="addon">
				<input type="hidden" name="roster_addon_name" value="shopping">
                 <table>
                 <th class="copy">'.$wordings[$roster_conf['roster_lang']]['professionfilter'].'</th>
                 <td class="copy"><select NAME="proffilter">';



while($row_prof = mysql_fetch_array($result_prof)){

    if ($_REQUEST["proffilter"]==$row_prof["proff"])
      $choiceForm .= '<option VALUE="'.$row_prof["proff"].'" selected>'.$row_prof["proff"];
    else
      $choiceForm .= '<option VALUE="'.$row_prof["proff"].'">'.$row_prof["proff"];
}


mysql_free_result($resultmembers);
mysql_free_result($result_prof);


$choiceForm .= '</select></td>
                <th>'.$wordings[$roster_conf['roster_lang']]['search'].'</th><td><input type="text" name="filterbox"';
if (isset($_REQUEST['filterbox'])) {
   $choiceForm .= ' value="'.$_REQUEST['filterbox'].'"';
}

$choiceForm .= '></td>
                <td><input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['applybutton'].'" /></td>
                </table>
                </form><br>';

$content .=  $choiceForm;




if (isset($_REQUEST["proffilter"])){
  $recipes = recipe_get_all( $_REQUEST["proffilter"],($_REQUEST["filterbox"]?$_REQUEST["filterbox"]:''), ($_REQUEST["sort"]?$_REQUEST["sort"]:'type') );
  if( isset( $recipes[0] ) ) {
	$current_page = '&proffilter='.$_REQUEST["proffilter"].'&filterbox='.($_REQUEST["filterbox"]?$_REQUEST["filterbox"]:'').($_REQUEST["sort"]?'&sort='.$_REQUEST["sort"]:'');
    $rc = 0;

	$recipe_type = '';
	$active_table = 0;
	$first_table = true;
	$content .=  "<table><tr><td style='width:600px' align='middle'><a id='top_menu'></a> - ";
	$qry_recipe_type = 'select distinct r.recipe_type from '.ROSTER_RECIPESTABLE.' r where r.skill_name = "'. $_REQUEST["proffilter"].'" order by r.recipe_type asc';
        $content .= ("<!--$qry_recipe_type $sqldebug-->\n");
    $result_recipe_type = mysql_query($qry_recipe_type) or die(mysql_error());
	if ($sqldebug) {
        $content .= ("<!--$qry_recipe_type $sqldebug-->\n");
	}
    while($row_recipe_type = mysql_fetch_array($result_recipe_type)){
		$content .=  '<a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=shopping'.$current_page.'#'.$row_recipe_type['recipe_type'].'">'.$row_recipe_type['recipe_type'].'</a> -  ';
	}
	$content .=  "</td></tr></table><br>";

    foreach ($recipes as $recipe)
    {
        if ($recipe_type != $recipe->data['recipe_type'])
        {
                $recipe_type = $recipe->data['recipe_type'];
                if(!$first_table)
                {
                    $content .=  '</table>'.border('syellow','end').'<br />';
                }
                $first_table = false;
																															
                $content .= border('syellow','start','<a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=shopping#top_menu" id="'.$recipe_type.'">'.$recipe_type.'</a>').
                        '<table class="bodyline" cellspacing="0">'."\n";
																						
                //$content .= '<tr>'."\n";
                //$content .= '<td colspan="14" class="membersHeaderRight"><div align="center"></div></td>'."\n";
                //$content .= '</tr>';
                $content .= '<tr>'."\n";
																																																										
		if ($display_recipe_icon)
                {
		    $content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['item'].'&nbsp;</th>'."\n";
		}					                               
		if ($display_recipe_name)
		{
		    $content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['name'].'&nbsp;</th>'."\n";
		}
		if ($display_recipe_level)
		{
		    $content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['level'].'&nbsp;</th>'."\n";
		}
		if ($display_recipe_tooltip)
		{
		    $content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['itemdescription'].'&nbsp;</th>'."\n";
		}
		if ($display_recipe_type)
		{
		    $content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['type'].'&nbsp;</th>'."\n";
		}
		if ($display_recipe_reagents)
		{
		    $content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['reagents'].'&nbsp;</th>'."\n";
		}
		if ($display_recipe_makers) {
    		$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['whocanmakeit'].'&nbsp;</td>';}
		if ($display_shopping_card) {
    		$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['shopping'].'&nbsp;</td>';}
		$content .=  '</tr>';
	  }
    // while($row_main = mysql_fetch_array($result_main)){
      $qry_users = "select m.name, r.difficulty, s.skill_level
                    from ".ROSTER_MEMBERSTABLE." m, ".ROSTER_RECIPESTABLE." r, ".ROSTER_SKILLSTABLE." s
                    where r.member_id = m.member_id and r.member_id = s.member_id and r.skill_name = s.skill_name
                    and recipe_name = '".addslashes($recipe->data['recipe_name'])."' order by m.name";
      $result_users = mysql_query($qry_users) or die(mysql_error());
      $users = '';
	  $make ='';
      $break_counter = 0;
      while($row_users = mysql_fetch_array($result_users)){
         if ($break_counter == $display_recipe_makers_count) {
         	$users .= '<br>&nbsp;';
			$break_counter = 0;
         }
		 if (substr($row_users['skill_level'],0,strpos($row_users['skill_level'],':')) < 300) {
	         $users .= '<a onMouseover="ol_width=50;return overlib(\''.$row_users['skill_level'].'\');" onMouseout="return nd();" class="difficulty_'.$row_users['difficulty'].'" href="index.php?name='.$module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&server='.$server_name_escape.'&action=recipes">'.$row_users['name'].'</a>, ';
	     } else {
	         $users .= '<a onMouseover="ol_width=50;return overlib(\''.$row_users['skill_level'].'\');" onMouseout="return nd();" class="difficulty_1" href="index.php?name='.$module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&server='.$server_name_escape.'&action=recipes">'.$row_users['name'].'</a>, ';
	     }
	     $make .= $row_users['name'].',';
		 $break_counter++;

      }
      mysql_free_result($result_users);
	  
	  $make_new = str_replace('\""','',$make);
	  $make = rtrim($make_new,', ');
      $users = rtrim($users,', ');


// Increment counter so rows are colored alternately
      ++$rc;

      //$table_cell_start = '<td align="center" valign="middle">';
      $table_cell_start = '<td class="membersRow'.(($rc%2)+1).'" align="center" valign="middle">';

      $thottURL='<a href="http://www.thottbot.com/index.cgi?i='.
                    str_replace(' ', '+',$recipe->data['recipe_name']).'" target="_thottbot">'.
                    $recipe->data['recipe_name'].'</a>';


	if ($display_recipe_tooltip)
        {
	    $tooltip = '';
	    $first_line = true;
	    foreach (explode("\n", $recipe->data['recipe_tooltip']) as $line )
	    {
	        if( $first_line )
	        {
	            $color = substr( $recipe->data['item_color'], 2, 6 ) . '; font-size: 12px; font-weight: bold';
	            $first_line = False;
	        }
	        else
	        {
	            if( substr( $line, 0, 2 ) == '|c' )
	            {
	                $color = substr( $line, 4, 6 ).';';
	                $line = substr( $line, 10, -2 );
	            } else if ( substr( $line, 0, 4 ) == $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) {
	                $color = '00ff00;';
	            } else if ( substr( $line, 0, 8 ) == $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) {
	                $color = 'ff0000;';
	            } else if ( substr( $line, 0, 10 ) == $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) {
	                $color = '00ff00;';
		    } else if ( substr( $line, 0, 6 ) == $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) {
			$color = '00ff00;';
                    } else if ( substr( $line, 0, 6 ) == $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) {
                        $color = '00ff00;';
                    } else if ( substr( $line, 0, 8 ) == $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) {
                        $color = '00ff00;';
                    } else if ( substr( $line, 0, 9 ) == $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) {
                        $color = '00ffff;';
	            } elseif ( strpos( $line, '"' ) ) {
	                $color = 'ffd517;';
	            } else {
	                $color='ffffff;';
	            }
	    }
	    $line = preg_replace('|\\>|','&#8250;', $line );
	    $line = preg_replace('|\\<|','&#8249;', $line );
	    if( strpos($line,"\t") )
	    {
	        $line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
	        $line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>'."\n";
	        $tooltip .= $line;
	    }
	    elseif( $line != '')
	    {
                $tooltip .= "<span style=\"color:#$color\">$line</span><br />\n";
            }
        }
	$users = rtrim($users,'<br>');
    }
																																																																																																																																																																																																																																																																																													
      $content .=  '<tr class="membersRow'.(($rc%2)+1).'">';
      if ($display_recipe_icon) {
	      $content .=  $table_cell_start.'<div class="equip">';
	      $content .=  $recipe->out();
	      $content .=  '</div></td>';
	  }
      if ($display_recipe_name) {
      	$content .=  $table_cell_start.'&nbsp;'.$thottURL.'&nbsp;</td>';}
      if ($display_recipe_level) {
      	$content .=  $table_cell_start.'&nbsp;'.$recipe->data['level'].'&nbsp;</td>';}
      if ($display_recipe_tooltip) {
      	$content .=  $table_cell_start.'<table style="width:220;white-space:normal;align:left;"><tr><td>'.$tooltip.'</td></tr></table></td>';}
      if ($display_recipe_type) {
		$content .=  $table_cell_start.'&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>';}
      if ($display_recipe_reagents) {
      	$content .=  $table_cell_start.'&nbsp;'.str_replace('<br>','&nbsp;<br>&nbsp;',$recipe->data['reagents']).'</td>';}
      if ($display_recipe_makers) {
      	$content .=  $table_cell_start.'&nbsp;'.$users.'&nbsp;</td>';}
	  if ($display_shopping_card) {

		//Added this line for the shopping-card symbol to show...      	
		$recipe_short = str_replace(" ", "+", $recipe->data['recipe_name']);
		$content .=  $table_cell_start.'<div><a href=addons/shopping/phpCart_manage.php?act=add&pid="'.$recipe_short.'"&make="'.$make.'"><img src="addons/shopping/img/Einkaufskorb3.gif" width="35" height="35" border="1" alt="'.$recipe->data['recipe_name'].'"></a></div></td>';
	  
	  
		}
	  $content .=  '</tr>';



    }
	$content .=  '</table></form>'.border('syellow','end');

  } else {
       $content .=  $wordings[$roster_conf['roster_lang']]['dnotpopulatelist'];

  }

}

}

//function to install the desired table
function installDB(){
    global $wowdb;
    global $sql_install;
        
    $wowdb->query($sql_install)
	or die_quietly( $wowdb->error(),'Error','shoppingList.php','301',$sql_install );
    echo "<span style=\"color:red;font-size:24px\">Installing MySql-Tables...</span>";
    echo "<br><span style=\"color:green;font-size:24px\">Finished installation...<br><br>Click <a href=\"index.php?name=".$module_name."&amp;file=addon&amp;roster_addon_name=shopping\">HERE</a> to continue\n</span><br><br><br>";
}

?>