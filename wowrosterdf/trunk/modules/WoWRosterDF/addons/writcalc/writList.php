<?php
/******************************
 * Argent Dawn Writ Calculator
 * By Rihlsul
 * www.ironcladgathering.com
 * v 1.0  (7/24/2006 5:45 PM)
 ******************************/
require_once(ROSTER_BASE.'lib'.DIR_SEP.'recipes.php');
	
$content .= ''."\n";
if (isset($_REQUEST['calculate']))
{	
	$globalBOM = array();
	// -- First, build the writ by writ details, which feeds the global Picking List (aka globalBOM)
	$detailList = border('syellow','start',$wordings[$roster_conf['roster_lang']]['perwritdetails']);
	$detailList .= '<table cellspacing="0" cellpadding="0" class="wowroster" width="70%">'."\n";
	$detailList .= "  <tr>"."\n";
	$detailList .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['writname'].":</th>"."\n";
	$detailList .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['writs'].":</th>"."\n";
	$detailList .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['qty'].":</th>"."\n";
	$detailList .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['reagents'].'</th>'."\n";
	$detailList .= '    <th class="membersHeader" width="70">'.$wordings[$roster_conf['roster_lang']]['whocanmakeit'].'</th>'."\n";
	$detailList .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['rawmats'].'</th>'."\n";
	$detailList .= '  </tr>'."\n";
	$rc = 0;
	foreach ($writArray as $key => $writName) {
		++$rc;
		$writQty = $writArray[$key]['qty'];
		$writItem = $writArray[$key]['item'];
		$writShortName = $writArray[$key]['shortname'];
		if (isset($_REQUEST['qty'.$writShortName]) && ($_REQUEST['qty'.$writShortName] <> 0)) {
			$qtyofwrit = $_REQUEST['qty'.$writShortName];
			$detailList .= '  <tr>'."\n";
			//$detailList .= '    <td class="membersRow'.(($rc%2)+1).'">'.$key.'</td>'."\n"."\n";
			$detailList .= '    <td class="membersRow'.(($rc%2)+1).'">'.str_replace(" - ",":<br />",$key).'</td>'."\n"."\n";
			$detailList .= '    <td class="membersRow'.(($rc%2)+1).'">'.$qtyofwrit.'</td>'."\n";
			$detailList .= '    <td class="membersRow'.(($rc%2)+1).'">'.$writQty.'</td>'."\n"."\n";
			
			// ----The Reagents section----
			$detailList .= '    <td class="membersRow'.(($rc%2)+1).'">';
			$recipes = writ_recipe_get_all($writArray[$key]['item'], 'type' ,$wordings, $roster_conf );
			if( isset( $recipes[0] ) )
			{
				foreach ($recipes as $recipe)
				{
					$qry_users = "select m.name, r.difficulty, s.skill_level ".
						"from ".ROSTER_MEMBERSTABLE." m, ".ROSTER_RECIPESTABLE." r, ".ROSTER_SKILLSTABLE." s ".
						"where r.member_id = m.member_id and r.member_id = s.member_id and r.skill_name = s.skill_name ".
						"and recipe_name = '".addslashes($recipe->data['recipe_name'])."' order by m.name";
		
					$result_users = $wowdb->query($qry_users) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_users);
					$users = '';
					$data = '';
					$billofmaterials = array();
					$break_counter = 0;
					while($row_users = $wowdb->fetch_array($result_users))
					{
						if ($break_counter == $display_recipe_makers_count)
						{
							$users .= '<br />&nbsp;';
							$break_counter = 0;
						}
						elseif( $users != '' )
						{
							$users .= ', ';
						}
						if (substr($row_users['skill_level'],0,strpos($row_users['skill_level'],':')) < 300)
						{
							$users .= '<a onmouseover="return overlib(\''.$row_users['skill_level'].'\',WRAP);" onmouseout="return nd();" class="difficulty_'.$row_users['difficulty'].'" href="index.php?name='.$module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes">'.$row_users['name'].'</a>'."\n";
						}
						else
						{
							$users .= '<a onmouseover="return overlib(\''.$row_users['skill_level'].'\',WRAP);" onmouseout="return nd();" class="difficulty_1" href="index.php?name='.$module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes">'.$row_users['name'].'</a>'."\n";
						}
						$break_counter++;
					}
					$wowdb->free_result($result_users);
					$users = rtrim($users,', ');
					$users = rtrim($users,'<br>');
					$reagents = $recipe->data['reagents'];
					
					$currReagents = parseReagents($reagents);
					foreach ($currReagents as $reagLine) {
						$tempLine = explode("x",$reagLine);
						$tempLine[0] = trim($tempLine[0]);
						$tempLine[1] = trim($tempLine[1]);
						$tempInt = $qtyofwrit * $writQty * $tempLine[1];
						
						$recipes2 = writ_recipe_get_all($tempLine[0], 'type' , $wordings, $roster_conf);
						if( isset( $recipes2[0] ) )
						{
							// recipe found, it's a made item, parse it for raw mats
							foreach ($recipes2 as $recipe2)
							{
								$reagents2 = $recipe2->data['reagents'];
								$currReagents2 = parseReagents($reagents2);
								foreach ($currReagents2 as $reagLine2) {
									$tempLine2 = explode("x",$reagLine2);
									$tempLine2[0] = trim($tempLine2[0]);
									$tempLine2[1] = trim($tempLine2[1]);
									$tempInt2 = $tempInt * $tempLine2[1];
									
									$recipes3 = writ_recipe_get_all($tempLine2[0], 'type' , $wordings, $roster_conf);
									if( isset( $recipes3[0] ) )
									{
										// recipe found, it's a made item, parse it for raw mats
										foreach ($recipes3 as $recipe3)
										{
											$reagents3 = $recipe3->data['reagents'];
											$currReagents3 = parseReagents($reagents3);
											foreach ($currReagents3 as $reagLine3) {
												//$data .= $reagLine."<br /> ";
												$tempLine3 = explode("x",$reagLine3);
												$tempLine3[0] = trim($tempLine3[0]);
												$tempLine3[1] = trim($tempLine3[1]);
												$tempInt3 = $tempInt2 * $tempLine3[1];
												$billofmaterials[$tempLine3[0]] = $billofmaterials[$tempLine3[0]] + $tempInt3;
												$globalBOM[$tempLine3[0]] = $globalBOM[$tempLine3[0]] + $tempInt3;
											}
										}
									}
									else
									{
										// this item is a raw material
										$billofmaterials[$tempLine2[0]] = $billofmaterials[$tempLine2[0]] + $tempInt2;
										$globalBOM[$tempLine2[0]] = $globalBOM[$tempLine2[0]] + $tempInt2;
									}
								}
							}
						}
						else
						{
							// this item is a raw material
							$billofmaterials[$tempLine[0]] = $billofmaterials[$tempLine[0]] + $tempInt;
							$globalBOM[$tempLine[0]] = 1 * $globalBOM[$tempLine[0]] + $tempInt;
						}
					}
					foreach ($billofmaterials as $BOMkey => $bomItem) {
						$data .= $BOMkey." x".$bomItem."<br />";
					}
				}
			}
			else
			{
				$reagents = $wordings[$roster_conf['roster_lang']]['norecipefound'];
				$users = $wordings[$roster_conf['roster_lang']]['norecipefound'];
				$data = $wordings[$roster_conf['roster_lang']]['norecipefound'];
			}
				
			$detailList .= $reagents.'    </td>'."\n";
			// ---- End Reagents -----
			
			$detailList .= '    <td width="70" class="membersRow'.(($rc%2)+1).'" align="center" valign="middle">'.$users.'</td>'."\n";
			$detailList .= '    <td class="membersRow'.(($rc%2)+1).'">'.$data.'</td>'."\n";
			$detailList .= '  </tr>'."\n"; 
		}
	}
	$detailList .= '</table>'."\n";
	$detailList .= border('syellow','end');
	
	// -- Now we build the summarized listing
	$summaryList = border('sblue','start',$wordings[$roster_conf['roster_lang']]['total'].$wordings[$roster_conf['roster_lang']]['rawmats']);
	$rc = 0;
	$summaryList .= '<table cellspacing="0" cellpadding="0" class="wowroster" width="50%">'."\n";
	$summaryList .= "  <tr>"."\n";
	$summaryList .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['Reagents']."</th>"."\n";
	$summaryList .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['Total']."</th>"."\n";
	$summaryList .= '  </tr>'."\n";
	
	ksort($globalBOM);
	foreach ($globalBOM as $gbItem => $gbQty) {
		++$rc;
		$summaryList .= '  <tr>'."\n";
		$summaryList .= '    <td class="membersRow'.(($rc%2)+1).'">'.$gbItem.'</td>'."\n"."\n";
		$summaryList .= '    <td class="membersRow'.(($rc%2)+1).'">'.$gbQty.'</td>'."\n"."\n";
		$summaryList .= '  </tr>'."\n";
	}
	$summaryList .= '</table>'."\n";
	$summaryList .= border('sblue','end');
	
	$content .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">'."\n";
	$content .= '  <tr>'."\n";
	$content .= '    <td valign="top">'."\n";
	$content .= '<!--   BEGIN Summary List TD element -->'."\n";
	$content .= $summaryList."\n";	
	$content .= '<!--   END Summary List TD element -->'."\n";
	$content .= '</td>'."\n";
	$content .= '    <td width="20">&nbsp;</td>'."\n";
	$content .= '    <td valign="top">'."\n";
	$content .= '<!--   BEGIN Detailed List TD element -->'."\n";
	$content .= $detailList."\n";
	$content .= '<!--   END Detailed List TD element -->'."\n";
	$content .= '</td>'."\n";
	$content .= '  </tr>'."\n";
	$content .= '</table>'."\n";

}
else
{
	$content .= '<form action="" method="POST" name="myform">'."\n";
    $content .= '<input type="hidden" name="name" value="'.$module_name.'">'."\n";
	$content .= '<input type="hidden" name="file" value="addon">'."\n";
	$content .= '<input type="hidden" name="roster_addon_name" value="writcalc">'."\n";
	$content .= '<input type="hidden" name="calculate" value="true">'."\n";
	$content .= border('syellow','start');
	$content .= '<table cellspacing="0" cellpadding="0" class="wowroster">'."\n";
	$content .= "  <tr>"."\n";
	$content .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['calc'].' '.$wordings[$roster_conf['roster_lang']]['qty'].":</td>"."\n";
	$content .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['writname'].":</td>"."\n";
	$content .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['qty'].":</td>"."\n";
	$content .= '    <th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['itemdescription']."</td>"."\n";
	$content .= "  </tr>";
	$rc = 0;
	foreach ($writArray as $key => $writName) {
	   ++$rc;
		$writQty = $writArray[$key]['qty'];
		$writItem = $writArray[$key]['item'];
		$writShortName = $writArray[$key]['shortname'];
	   $content .= '  <tr>'."\n";
	   $content .= '    <td class="membersRow'.(($rc%2)+1).'"><input name="qty'.$writShortName.'" type="text" value="0" size="3" maxlength="2"></td>'."\n"."\n";
	   $content .= '    <td class="membersRow'.(($rc%2)+1).'">'.$key.'</td>'."\n"."\n";
	   $content .= '    <td class="membersRow'.(($rc%2)+1).'">'.$writQty.'</td>'."\n";
	   $content .= '    <td class="membersRow'.(($rc%2)+1).'">'.$writItem.'</td>'."\n";
	   $content .= '  </tr>'."\n"; 
	}
	$content .= '
		<tr class="wowroster">
			<td colspan="4" class="membersRow2"><center><input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['calc'].'" /></center></td>
		</tr>
	</table>'.border('syellow','end');
	$content .= '</form><br />';
}

function writ_recipe_get_all($search, $sort, $wordings, $roster_conf )
{
	global $wowdb;

	if (isset($server))
	{
		$server = $wowdb->escape( $server );
	}

	if (stripos($search,'Leather') && 
				((stripos($search,$wordings[$roster_conf['roster_lang']]['ruggedleather']) !== false) ||
			     (stripos($search,$wordings[$roster_conf['roster_lang']]['thickleather']) !== false) ||
				 (stripos($search,$wordings[$roster_conf['roster_lang']]['heavyleather']) !== false) ||
				 (stripos($search,$wordings[$roster_conf['roster_lang']]['mediumleather']) !== false) ||
				 (stripos($search,$wordings[$roster_conf['roster_lang']]['lightleather']) !== false))
		) {
		return;
	}
	else
	{
		$query= "SELECT distinct recipe_name, skill_name, reagents, level, 1 difficulty FROM `".ROSTER_RECIPESTABLE."` WHERE (recipe_name LIKE '".$search."' AND NOT (`recipe_tooltip` LIKE '%".$wordings[$roster_conf['roster_lang']]['plans']."%')) AND (skill_name != '".$wordings[$roster_conf['roster_lang']]['First Aid']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['poisons']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['Mining']."')";
	}
	
	switch ($sort)
	{
		case 'item':
			$query .= " ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
			break;

		case 'name':
			$query .= " ORDER BY  recipe_name ASC ,  recipe_type ASC , `level` DESC , `difficulty` DESC";
			break;

		case 'type':
			$query .= " ORDER BY  recipe_type ASC , `level` DESC , recipe_name ASC , `difficulty` DESC";
			break;

		case 'reagents':
			$query .= " ORDER BY  `reagents` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		case 'level':
			$query .= " ORDER BY `level` DESC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		default:
			$query .= " ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
			break;
	}

	$result = $wowdb->query( $query );
	$recipes = array();
	while( $data = $wowdb->fetch_assoc( $result ) ) {
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}

function parseReagents($list) {
	$items = array();
	$items = explode("<br>",$list);	
	return $items;
}
?>
