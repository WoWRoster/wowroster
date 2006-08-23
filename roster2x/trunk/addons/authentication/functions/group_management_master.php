<?php
#-----------| Group Management |-----------#	
class Group_Management_Master extends Interface_Helper
{	
	function list_groups()
	{
		print '<table border="0" cellpadding="1" cellspacing="1" width="530px">';
		print '<tr class="sc_menuTH"><td><a class="sc_titletext" href="?sort=guild" style="font-size:11px"> Guild </a></td><td width="25%"><a class="sc_titletext" href="?sort=name" style="font-size:11px"> Group </a></td><td width="25%"><a class="sc_titletext" href="?sort=description" style="font-size:11px"> Description </a></td><td width="25%" colspan="2"> Options </td></tr>';
		$filter = array('fields' => 
							array('group_id', 'group_define_name', 'description'),
						'filters' => 
							array('language_id' => 'enUS'));
		$groups = $this->get_group($filter);
		foreach ($groups as $key => $row) {
			$decoded = stripslashes(base64_decode($row['group_define_name']));
			$filter = array('fields' => array('application_define_name'), 
							'filters' => array('application_id' => substr($decoded, 0, strpos($decoded, '_'))));
			$guild[$key] = $this->get_guild($filter);
			$guild[$key] = $guild[$key][0];
			$group_name[$key]  = $row['group_define_name'];
			$group_description[$key]  = $row['description'];
		}
		if(!isset($_GET['sort'])||$_GET['sort']=='name'){
			array_multisort($group_name, SORT_ASC, SORT_STRING, $guild, SORT_ASC, SORT_STRING, $group_description, SORT_DESC, SORT_STRING, $groups);
		}
		elseif(@$_GET['sort']=='guild')
		{
			array_multisort($guild, SORT_DESC, SORT_STRING, $groups);
		}
		else
		{
			array_multisort($group_description, SORT_ASC, SORT_STRING, $group_name, SORT_DESC, SORT_NUMERIC, $guild, SORT_ASC, SORT_STRING, $groups);
		}
		$i = 1;
		$line = NULL;
		for($j=0; $j<count($groups); $j++)
		{
			if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
			else { $line_color = 'sc_row2'; $i = 1; }
			$line .= '<tr class="'.$line_color.'">';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($guild[$j]['application_define_name'])).'</td>';
			$line .= '<td valign="middle">'.ltrim(stristr(stripslashes(base64_decode($groups[$j]['group_define_name'])), '_'), '_').'</td>';
			$line .= '<td valign="middle">'.stripslashes(base64_decode($groups[$j]['description'])).'</td>';
			$line .= '<a href="index.php?action=change&object=group&id='.$groups[$j]['group_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Change </td></a>';
			$line .= '<a href="index.php?action=delete&object=group&id='.$groups[$j]['group_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Remove 
						</td></a>';			
			$line .= '</tr>';
		}
		print $line;
		print '</table>';
	}
	
	function new_edit_group_field($get=NULL)
	{	
		if(!empty($get)&&@$get['action']=='change'&& @$get['object']=='group') 
		{
			$filter = array('fields' => 
								array('group_id', 'group_define_name', 'description'), 
							'filters' => 
								array('group_id' => @$get['id'], 'language_id' => 'enUS'));
			$group = $this->get_group($filter);
			$group = $group[0];
		}
		$all_guilds = $this->get_guild();
		print '<form method="post">
					<table border="0" cellpadding="1" cellspacing="1" width="530px">
						<tr class="sc_menuTH">
							<td>
								 New / Edit Group Name 
							</td>
							<td>
								 Description
							</td>
							<td colspan="2">
								 Select Guild 
							</td>
						</tr>
						<tr>
							<td>';
								if(!empty($group))
								{ 
								$decoded = stripslashes(base64_decode($group['group_define_name']));
								$guild_id = substr($decoded, 0, strpos($decoded, '_'));
								print '
									<input name="group_input" type="text" value="'.ltrim(stristr($decoded, '_'), '_').'" style="cursor:text;">
									<input name="original_grp" value="'.$group['group_id'].'" type="hidden">
									<input name="original_grp_name" value="'.$group['group_define_name'].'" type="hidden">
									<input name="guild_id" value="'.$guild_id.'" type="hidden">
							</td>
							<td>
									<input name="description" type="text" value="'.stripslashes(base64_decode($group['description'])).'" style="cursor:text;">
									<input name="original_desc" value="'.$group['description'].'" type="hidden">
							</td>
								'; } 
								else 
								{ print '
									<input name="group_input" type="text" >
							</td>
							<td>
									<input name="description" type="text">
							</td>
								'; } 
								print '
							<td>';
								if(@$get['action']!=='change' && @$get['object']!=='group')
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
								<input name="object" value="group" type="hidden">
								<input name="action" value="new_edit" type="hidden">
								<input name="group_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
							</td>
						</tr>
					</table>
				</form>';
	}
}
?>