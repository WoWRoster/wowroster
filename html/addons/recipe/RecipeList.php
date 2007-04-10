<?php
/* 
* $Date: 2006/02/03 23:38:52 $ 
* $Revision: 1.10 $ 
*/ 
require_once('.'.$sep.'lib'.$sep.'recipes.php');


$server_name_escape = $wowdb->escape($server_name);
$server = $_REQUEST['server'];
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$lang]."') from `".ROSTER_GUILDTABLE."` where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
        $guildId = $row[0];
        $updateTime = $row[1];
        $content .= ("<!--$guildId $updateTime-->\n");
} else {
        die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

$qry_prof  = "select distinct( skill_name) proff from ".ROSTER_RECIPESTABLE." where skill_name != '".$wordings[$roster_lang]['First Aid']."' and skill_name != '".$wordings[$roster_lang]['poisons']."' and skill_name != '".$wordings[$roster_lang]['Mining']."' order by skill_name";

$result_prof = mysql_query($qry_prof) or die(mysql_error());
if ($sqldebug) {
        $content .= ("<!--$query $sqldebug-->\n");
}



$choiceForm = '<form action="addon.php" method=GET name=myform>
				<input type="hidden" name="roster_addon_name" value="recipe">
                 <table>
                 <th class="copy">'.$wordings[$roster_lang]['professionfilter'].'</th>
                 <td class="copy"><select NAME="proffilter">';



while($row_prof = mysql_fetch_array($result_prof)){

    if ($_REQUEST["proffilter"]==$row_prof["proff"])
      $choiceForm .= '<option VALUE="'.$row_prof["proff"].'" selected>'.$row_prof["proff"];
    else
      $choiceForm .= '<option VALUE="'.$row_prof["proff"].'">'.$row_prof["proff"];
}



mysql_free_result($result_prof);


$choiceForm .= '</select></td>
                <th>'.$wordings[$roster_lang]['search'].'</th><td><input type="text" name="filterbox"';
if (isset($_REQUEST['filterbox'])) {
   $choiceForm .= ' value="'.$_REQUEST['filterbox'].'"';
}

$choiceForm .= '></td>
                <td><input type="submit" value="'.$wordings[$roster_lang]['applybutton'].'" /></td>
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
	$content .=  "<table><tr><td style='width:600px' align='middle'><a id='top_menu'></a> - ";
	$qry_recipe_type = 'select distinct r.recipe_type from '.ROSTER_RECIPESTABLE.' r where r.skill_name = "'. $_REQUEST["proffilter"].'" order by r.recipe_type asc';
        $content .= ("<!--$qry_recipe_type $sqldebug-->\n");
    $result_recipe_type = mysql_query($qry_recipe_type) or die(mysql_error());
	if ($sqldebug) {
        $content .= ("<!--$qry_recipe_type $sqldebug-->\n");
	}
    while($row_recipe_type = mysql_fetch_array($result_recipe_type)){
		$content .=  '<a href="addon.php?roster_addon_name=recipe'.$current_page.'#'.$row_recipe_type['recipe_type'].'">'.$row_recipe_type['recipe_type'].'</a> -  ';
	}
	$content .=  "</td></tr></table>";
	foreach ($recipes as $recipe) {

	  if ($recipe_type != $recipe->data['recipe_type']) {
		$recipe_type = $recipe->data['recipe_type'];
		if ($active_table == 1) {
			$content .=  '</table>';
			$active_table = 0;
		}
		$content .=  '<table class="bodyline" cellspacing=\"1\"><br><br><div text-align="center"><a href="addon.php?roster_addon_name=recipe'.$current_page.'#top_menu" id="'.$recipe_type.'">'.$recipe_type.'</a></div>	<tr class="membersHeader">';

    	if ($display_recipe_icon) {
    		$content .=  '<td class="rankingHeader">&nbsp;'.$wordings[$roster_lang]['item'].'&nbsp;</td>';}
    	if ($display_recipe_name) {
    		$content .=  '<td class="rankingHeader">&nbsp;'.$wordings[$roster_lang]['name'].'&nbsp;</td>';}
    	if ($display_recipe_level) {
    		$content .=  '<td class="rankingHeader">&nbsp;'.$wordings[$roster_lang]['level'].'&nbsp;</td>';}
    	if ($display_recipe_tooltip) {
    		$content .=  '<td class="rankingHeader">&nbsp;'.$wordings[$roster_lang]['itemdescription'].'&nbsp;</td>';}
    	if ($display_recipe_type) {
    		$content .=  '<td class="rankingHeader">&nbsp;'.$wordings[$roster_lang]['type'].'&nbsp;</td>';}
    	if ($display_recipe_reagents) {
    		$content .=  '<td class="rankingHeader">&nbsp;'.$wordings[$roster_lang]['reagents'].'&nbsp;</td>';}
    	if ($display_recipe_makers) {
    		$content .=  '<td class="rankingHeader">&nbsp;'.$wordings[$roster_lang]['whocanmakeit'].'&nbsp;</td>';}
    	$content .=  '</tr>';
	  }
    // while($row_main = mysql_fetch_array($result_main)){
      $qry_users = "select m.name, r.difficulty, s.skill_level
                    from ".ROSTER_MEMBERSTABLE." m, ".ROSTER_RECIPESTABLE." r, ".ROSTER_SKILLSTABLE." s
                    where r.member_id = m.member_id and r.member_id = s.member_id and r.skill_name = s.skill_name
                    and recipe_name = '".addslashes($recipe->data['recipe_name'])."' order by m.name";
      $result_users = mysql_query($qry_users) or die(mysql_error());
      $users = '';
      $break_counter = 0;
      while($row_users = mysql_fetch_array($result_users)){
         if ($break_counter == $display_recipe_makers_count) {
         	$users .= '<br>&nbsp;';
         	$break_counter = 0;
         }
		 if (substr($row_users['skill_level'],0,strpos($row_users['skill_level'],':')) < 300) {
	         $users .= '<a onMouseover="ol_width=50;return overlib(\''.$row_users['skill_level'].'\');" onMouseout="return nd();" class="difficulty_'.$row_users['difficulty'].'" href="./char.php?name='.$row_users['name'].'&server='.$server_name_escape.'&action=recipes">'.$row_users['name'].'</a>, ';
	     } else {
	         $users .= '<a onMouseover="ol_width=50;return overlib(\''.$row_users['skill_level'].'\');" onMouseout="return nd();" class="difficulty_1" href="./char.php?name='.$row_users['name'].'&server='.$server_name_escape.'&action=recipes">'.$row_users['name'].'</a>, ';
	     }
	     $break_counter++;

      }
      mysql_free_result($result_users);

      $users = rtrim($users,', ');


// Increment counter so rows are colored alternately
      ++$rc;

      $table_cell_start = '<td align="center" valign="middle">';


      $thottURL='<a href="http://www.thottbot.com/index.cgi?i='.
                    str_replace(' ', '+',$recipe->data['recipe_name']).'" target="_thottbot">'.
                    $recipe->data['recipe_name'].'</a>';

      if ($display_recipe_tooltip) {
      	$tooltip = '';
      	foreach (explode("\n", $recipe->data['recipe_tooltip']) as $line ) {
	  			$class='tooltipline';
	  			if( $first_line ) {
	  				$color = $difficultycolor . ";font-weight: bold";
	  				$first_line = False;
	  				$class='tooltipheader';
	  			} else {
	  				if( substr( $line, 0, 2 ) == '|c' ) {
	  					$color = substr( $line, 4, 6 ).'; font-size: 10px;';
	  					$line = substr( $line, 10, -2 );
	  				} else if ( substr( $line, 0, 4 ) == $wordings[$roster_lang]['tooltip_use'] ) {
	  					$color = '00ff00; font-size: 10px;';
	  				} else if ( substr( $line, 0, 8 ) == $wordings[$roster_lang]['tooltip_requires'] ) {
	  					$color = 'ff0000; font-size: 10px;';
	  				} else if ( substr( $line, 0, 10 ) == $wordings[$roster_lang]['tooltip_reinforced'] ) {
	  					$color = '00ff00; font-size: 10px;';
	  				} else if ( substr( $line, 0, 6 ) == $wordings[$roster_lang]['tooltip_equip'] ) {
	  					$color = '00ff00; font-size: 10px;';
	  				} else if ( substr( $line, 0, 6 ) == $wordings[$roster_lang]['tooltip_chance'] ) {
	  					$color = '00ff00; font-size: 10px;';
	  				} else if ( substr( $line, 0, 8 ) == $wordings[$roster_lang]['tooltip_enchant'] ) {
	  					$color = '00ff00; font-size: 10px;';
	  				} else if ( substr( $line, 0, 9 ) == $wordings[$roster_lang]['tooltip_soulbound'] ) {
	  					$color = '00ffff; font-size: 10px;';
	  				} else {
	  					$color='ffffff; font-size: 10px;';
	  				}
	  			}
	  			$line = preg_replace('|\\>|','&#8250;', $line );
	  			$line = preg_replace('|\\<|','&#8249;', $line );
	  			if( $line != '') {
	  				$tooltip = $tooltip."<span class=\"$class\" style=\"color:#$color;width:250;\">$line</span><br>";
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
      	$content .=  $table_cell_start.'<div style="width:250;"><table><tr><td>'.$tooltip.'</td></tr></table></div></td>';}
      if ($display_recipe_type) {
		$content .=  $table_cell_start.'&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>';}
      if ($display_recipe_reagents) {
      	$content .=  $table_cell_start.'&nbsp;'.str_replace('<br>','&nbsp;<br>&nbsp;',$recipe->data['reagents']).'</td>';}
      if ($display_recipe_makers) {
      	$content .=  $table_cell_start.'&nbsp;'.$users.'&nbsp;</td>';}
	  $content .=  '</tr>';



    }
	$content .=  '</table>';

  } else {
       $content .=  $wordings[$roster_lang]['dnotpopulatelist'];

  }

}


?>