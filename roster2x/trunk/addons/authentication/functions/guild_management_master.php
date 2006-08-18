<?php
#-----------| Guild Management |-----------#
class Guild_Management_Master extends Interface_Helper
{
	function list_guilds()
	{
		print '<table border="0" cellpadding="1" cellspacing="1" width="100%">';
		print '<tr class="sc_menuTH"><td> Guild </td><td colspan="2"> Options </td></tr>';
		$guilds = $this->get_guild();
		sort($guilds);
		$i = 1;
		$line = NULL;
		for($j=0; $j<count($guilds); $j++)
		{
			if($i == 1) { $line_color = 'sc_row1'; $i = 0; }
			else { $line_color = 'sc_row2'; $i = 1; }
			$line .= '<tr class="'.$line_color.'">';
			$line .= '<td width="60%" valign="middle">'.stripslashes(base64_decode($guilds[$j]['application_define_name'])).'</td>';
			$line .= '<a href="index.php?action=change&object=guild&id='.$guilds[$j]['application_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Change 
						</td></a>';
			$line .= '<a href="index.php?action=delete&object=guild&id='.$guilds[$j]['application_id'].'">
						<td align="center" valign="middle" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
						Remove 
						</td></a>';			
			$line .= '</tr>';
		}
		print $line;
		print '</table>';
		print '<br><br>';
	}
	
	function new_edit_guild_field($get=NULL)
	{	
		if(!empty($get)&&@$get['action']=='change' && @$get['object']=='guild') 
		{
			$filter = array('filters' => array('application_id' => $get['id']));
			$guild = $this->get_guild($filter);
			$guild = $guild[0];
		}
		print '<form method="post">
					<table border="0" cellpadding="1" cellspacing="1" width="100%">
						<tr class="sc_menuTH">
							<td colspan="2">
								 New / Edit Guild Name 
							</td>
						</tr>
						<tr>
							<td width="60%">';
								if(!empty($guild))
								{ print '
									<input name="guild_input" type="text" value="'.stripslashes(base64_decode($guild['application_define_name'])).'" size="30" style="cursor:text;">
									<input name="original" value="'.stripslashes(base64_decode($guild['application_define_name'])).'" type="hidden">
								'; } 
								else 
								{ print '
									<input name="guild_input" type="text" size="30">
								'; } 
								print '
							</td>
							<td>
								<input name="object" value="guild" type="hidden">
								<input name="action" value="new_edit" type="hidden">
								<input name="guild_submit" type="submit" value="Submit" class="sc_menuClick" onmousedown="this.style.background = \'#778899\'" onmouseup="this.style.background = \'#7A7772\'" onmouseover="this.style.background = \'#7A7772\'" onmouseout="this.style.background = \'#2E2D2B\'">
							</td>
						</tr>
					</table>
				</form>';
	}
}
?>