<?php
#-----------| Area Management |-----------#
class Area_Management_Master extends Interface_Helper
{
	function list_areas()
	{
		print '<table border="0" cellpadding="1" cellspacing="1" width="100%">';
		print '<tr class="sc_menuTH"><td width="28%"> <a class="sc_titletext" href="?sort=area" style="font-size:11px">Area</a> </td><td> <a class="sc_titletext" href="?sort=guild" style="font-size:11px">Guild</a> </td><td colspan="2"> Options </td></tr>';
		$areas = $this->get_area();
		foreach ($areas as $key => $row) {
		   $area_id[$key]  = $row['area_id'];
		   $guild_id[$key]  = $row['application_id'];
		   $area_name[$key]  = $row['area_define_name'];
		}
		if(!isset($_GET['sort'])||$_GET['sort']=='guild'){
			array_multisort($guild_id, SORT_ASC, SORT_NUMERIC, $area_name, SORT_DESC, SORT_STRING, $areas);
		}
		else
		{
			array_multisort($area_name, SORT_ASC, SORT_STRING, $guild_id, SORT_DESC, SORT_NUMERIC, $areas);
		}

		$i = 1;
		$line = NULL;
		for($j=0; $j<count($areas); $j++)
		{
			$filter = array('filters' => array('application_id' => $areas[$j]['application_id']));
			$guild = $this->get_guild($filter);
			if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
			else { $line_color = 'sc_row2'; $i = 1; }
			$line .= '<tr class="'.$line_color.'">';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($areas[$j]['area_define_name'])).'</td>';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($guild[0]['application_define_name'])).'</td>';
			$line .= '<a href="index.php?action=change&object=area&id='.$areas[$j]['area_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Change </td></a>';
			$line .= '<a href="index.php?action=delete&object=area&id='.$areas[$j]['area_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Remove 
						</td></a>';			
			$line .= '</tr>';
		}
		print $line;
		print '</table>';
		print '<br><br>';
	}
	
	function new_edit_area_field($get=NULL)
	{	
		if(!empty($get)&&@$get['action']=='change'&& @$get['object']=='area') 
		{
			$filter = array('filters' => array('area_id' => $get['id']));
			$area = $this->get_area($filter);
			$area = $area[0];
			$filter = array('filters' => array('application_id' => $area['application_id']));
			$guild = $this->get_guild($filter);
			$guild = $guild[0];
		}
		$all_guilds = $this->get_guild();
		print '<form method="post">
					<table border="0" cellpadding="1" cellspacing="1" width="100%">
						<tr class="sc_menuTH">
							<td>
								 New / Edit Area Name 
							</td>
							<td colspan="2">
								 Select Guild 
							</td>
						</tr>
						<tr>
							<td width="60%">';
								if(!empty($area))
								{ print '
									<input name="area_input" type="text" value="'.stripslashes(base64_decode($area['area_define_name'])).'" style="cursor:text;">
									<input name="original" value="'.$area['area_id'].'" type="hidden">
									<input name="guild_id" value="'.$area['application_id'].'" type="hidden">
								'; } 
								else 
								{ print '
									<input name="area_input" type="text" >
								'; } 
								print '
							</td>
							<td>';
								if(@$get['action']!=='change' && @$get['object']!=='area')
								{ 
									print '<select name="guild_dropdown">';
										for($i=0; $i<count($all_guilds); $i++)
										{
											print '<option value="'.$all_guilds[$i]['application_id'].'">'.stripslashes(base64_decode($all_guilds[$i]['application_define_name'])).'</option>';
										}
									print '</select>';
								}
								print '
							</td>
							<td>
								<input name="object" value="area" type="hidden">
								<input name="action" value="new_edit" type="hidden">
								<input name="area_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
							</td>
						</tr>
					</table>
				</form>';
	}
}
?>