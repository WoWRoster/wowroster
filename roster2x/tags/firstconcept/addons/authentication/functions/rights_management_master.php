<?php
#-----------| Rights Management |-----------#	
class Rights_Management_Master extends Interface_Helper
{
	function group_rights()
	{
		$previous_guild = NULL;
		$line = NULL;
		
		$line .= '<table border="0" cellpadding="1" cellspacing="1" width="530px">';
					
		$filter = array('fields' => array('group_id', 'group_define_name'));
		$groups = $this->get_group($filter);
		foreach ($groups as $key => $row) {
			$decoded = stripslashes(base64_decode($row['group_define_name']));
			$filter = array('fields' => array('application_define_name'), 
							'filters' => array('application_id' => substr($decoded, 0, strpos($decoded, '_'))));
			$groups[$key]['guild'] = $this->get_guild($filter);
			$groups[$key]['guild'] = $groups[$key]['guild'][0];
			$guild_name[$key] = $groups[$key]['guild']['application_define_name'];
			$group_name[$key] = $decoded;
		}
		// sort by guild name, then group name
		array_multisort($guild_name, SORT_ASC, SORT_STRING, $group_name, SORT_ASC, SORT_STRING, $groups);

		for($j=0; $j<count($groups); $j++)
		{
			$decoded_group_name = stripslashes(base64_decode($groups[$j]['group_define_name']));
			$decoded_guild_name = stripslashes(base64_decode($groups[$j]['guild']['application_define_name']));
			// get all areas belonging to the guild of the current group group
			$filter = array('filters' => array('application_id' => substr($decoded_group_name, 0, strpos($decoded_group_name, '_'))));
			$areas = $this->get_area($filter);
			if($previous_guild != $decoded_guild_name) {
				$line .= '
					<tr class="sc_menuTH">
						<td colspan="4">'.$decoded_guild_name.'</td>
					</tr>
					<tr class="sc_menuTH">
						<td width="20%"> Group </td>
						<td width="20%"> has the right to </td>
						<td width="25%"> Area </td>
					</tr>';
			}
			$previous_guild = $decoded_guild_name;
			$line .= '
				<form method="post" action="?display=group">
					<tr class="sc_row1">
						<td valign="middle" style="cursor:pointer;"><div onclick="return toggleShow(\''.$j.'\', this)" style="width:100%;">'.ltrim(stristr($decoded_group_name, '_'), '_').'</div></td>
						<td valign="middle">';
			// This shows which rights are saved by ticking the boxes after a refresh
			if(@$_REQUEST['group_id'] == $groups[$j]['group_id'])
			{
				$filter = array('filters' => array('group_id' => $groups[$j]['group_id'], 'area_id' => $_REQUEST['area_id']),
								'fields' => array('right_id', 'right_define_name'));
				$tick_rights = $this->get_group($filter);
				$filter = array('filters' => array('area_id' => $_REQUEST['area_id']));
				$tick_area = $this->get_area($filter);
				if(!empty($tick_rights))
				{
					foreach($tick_rights as $right)
					{
						if($right['right_define_name'] == 'View')
							$ticked['view'] = true;
						if($right['right_define_name'] == 'Use')
							$ticked['use'] = true;
						if($right['right_define_name'] == 'Edit')
							$ticked['edit'] = true;
					}
				}
			}
			if(@empty($rights_translation)){
				$filter = array('filters' => array('section_type' => LIVEUSER_SECTION_RIGHT, 'language_id' => 'enUS'), 'fields' => array('name', 'description'));
				$rights_translation = $this->get_translation($filter);
			}

			$line .='		<label style="border-left-color:#FFFFCC; border-left-width:1px; border-left-style:solid;"><input name="view" type="checkbox" value="true" '; if(@$ticked['view']){ $line .= 'checked'; } $line .='>'. stripslashes(base64_decode($rights_translation[0]['name'])) .'</label>
							<label style="border-left-color:#CCFFCC; border-left-width:1px; border-left-style:solid;"><input name="use" type="checkbox" value="true" '; if(@$ticked['use']){ $line .= 'checked'; } $line .='>'. stripslashes(base64_decode($rights_translation[1]['name'])) .'</label>
							<label style="border-left-color:#9999CC; border-left-width:1px; border-left-style:solid;"><input name="edit" type="checkbox" value="true" '; if(@$ticked['edit']){ $line .= 'checked'; } $line .='>'. stripslashes(base64_decode($rights_translation[2]['name'])) .'</label>';
			$ticked = array();
			$line .='	</td>
						<td valign="middle">
							<select name="area_id" style="width:50%;" onChange="this.form.submit()">';
							if(@$_REQUEST['group_id'] == $groups[$j]['group_id'])
							{ // If its a postback, display the previously selected area, else display the standard 'Select' one
								$line .=' <option value="'.$tick_area[0]['area_id'].'">'.stripslashes(base64_decode($tick_area[0]['area_define_name'])).'</option>';
							}else{
								$line .='	<option> Select </option>';
							}
							foreach($areas as $area)
							{
			$line .='			<option value="'.$area['area_id'].'">'.stripslashes(base64_decode($area['area_define_name'])).'</option>';
							}
			$line .='		</select>
							<input name="group_id" value="'.$groups[$j]['group_id'].'" type="hidden">
							<input name="object" value="rights" type="hidden">
							<input name="action" value="group_rights" type="hidden">
							<input type="submit" name="save" value="Set" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="return expandcontent(\'login\', this)" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'"  style="width:80px;">
						</td>
					</tr>
					<tr id="'.$j.'" style="display:none" class="sc_row2">
						<td colspan="4">';
							foreach($areas as $area)
							{
								$filter = array('filters' => array('group_id' => $groups[$j]['group_id'], 'area_id' => $area['area_id']),
												'fields' => array('right_id', 'right_define_name'));
								$group_rights = $this->get_group($filter);
								if(!empty($group_rights))
								{
			$line .='			<fieldset style="width:30%">
									<legend>'.stripslashes(base64_decode($area['area_define_name'])).'</legend>';
										foreach($group_rights as $right)
										{
											if($right['right_define_name'] == 'View') $right_name = $rights_translation[0]['name'];
											elseif($right['right_define_name'] == 'Use') $right_name = $rights_translation[1]['name'];
											elseif($right['right_define_name'] == 'Edit') $right_name = $rights_translation[2]['name'];
			$line .='						<label style="border-left-color:#FFFFFF; border-left-width:1px; border-left-style:solid;">'.stripslashes(base64_decode($right_name)).'</label>&nbsp;&nbsp;';
										}
			$line .='			</fieldset>&nbsp;&nbsp;';
								}
							}
			$line .='		<input name="group_id" value="'.$groups[$j]['group_id'].'" type="hidden">
						</td>
					</tr>
				</form>';
		}
		$line .= '</table>';
		print $line;
	}
	function personal_rights()
	{
		
	}
}
?>