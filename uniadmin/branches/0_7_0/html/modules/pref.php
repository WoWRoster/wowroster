<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

if( $user->data['level'] < UA_ID_ADMIN )
{
	display_page($user->lang['access_denied'],$user->lang['access_denied']);
	exit();
}

// Get Operation
$op = ( isset($_POST[UA_URI_OP]) ? $_POST[UA_URI_OP] : '' );

// Decide What To Do
switch( $op )
{
	case UA_URI_PROCESS:
		process_update();
		break;

	default:
		break;
}
main();








/**
 * UA Preferences Page Functions
 */


/**
 * Main Display
 */
function main( )
{
	global $db, $uniadmin, $user;

	$sql = "SELECT * FROM `".UA_TABLE_CONFIG."` ORDER BY `config_name` ASC;";
	$result = $db->query($sql);

	$form = '
<form name="ua_mainsettings" method="post" enctype="multipart/form-data" action="'.UA_FORMACTION.'">
<table class="ua_table" align="center">
	<tr>
		<th colspan="4" class="table_header">'.$user->lang['uniadmin_config_settings'].'</th>
	</tr>
	<tr>
		<td class="data_header">'.$user->lang['setting_name'].'</td>
		<td class="data_header">'.$user->lang['value'].'</td>
	</tr>';


	while( $row = $db->fetch_record($result) )
	{
		$setname = $row['config_name'];
		$setvalue = $row['config_value'];

		$td_class = 'data'.$uniadmin->switch_row_class();

		$form .= '
	<tr>
		<td class="'.$td_class.'" onmouseover="return overlib(\''.$user->lang['admin'][$setname].'\',CAPTION,\''.$setname.'\',VAUTO);" onmouseout="return nd();">
			<img src="'.$uniadmin->url_path.'images/blue-question-mark.gif" alt="[?]" /> '.$setname.'</td>
		<td class="'.$td_class.'">';


		// Figure out input type
		$input_field = '';
		$input_type = explode('{',$row['form_type']);

		switch( $input_type[0] )
		{
			case 'text':
				$length = explode('|',$input_type[1]);
				$input_field = '<input class="input" name="'.$setname.'" type="text" value="'.$setvalue.'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
				break;

			case 'radio':
				$options = explode('|',$input_type[1]);
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					$input_field .= '<label><input type="radio" name="'.$setname.'" value="'.$vals[1].'" '.( $setvalue == $vals[1] ? 'checked="checked"' : '' ).' />'.$user->lang[$vals[0]]."</label>\n";
				}
				break;

			case 'select':
				$options = explode('|',$input_type[1]);
				$input_field .= '<select class="input" name="'.$setname.'">'."\n";
				$select_one = 1;
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					if( $setvalue == $vals[1] && $select_one )
					{
						$input_field .= '  <option value="'.$vals[1].'" selected="selected">&gt;'.$vals[0].'&lt;</option>'."\n";
						$select_one = 0;
					}
					else
					{
						$input_field .= '  <option value="'.$vals[1].'">'.$vals[0].'</option>'."\n";
					}
				}
				$input_field .= '</select>';
				break;

			case 'function':
				$input_field = $input_type[1]();
				break;

			case 'display':
				$input_field = $setvalue;
				break;

			default:
				$input_field = $setvalue;
				break;
		}

		$form .= $input_field.'</td>'."\n";

		$form .= '	</tr>'."\n";
	}

	$form .= '	<tr>
		<td class="data_header" colspan="4" align="center"><input type="hidden" name="'.UA_URI_OP.'" value="'.UA_URI_PROCESS.'" />
			<input class="submit" type="submit" value="'.$user->lang['update_settings'].'" /></td>
	</tr>
</table>
</form>';

	display_page($form,$user->lang['title_config']);
}

/**
 * Process Config Update
 */
function process_update( )
{
	global $uniadmin;

	foreach( $_POST as $settingName => $settingValue )
	{
		if( $settingName != UA_URI_OP )
		{
			$set = $uniadmin->config_set($settingName,$settingValue);
		}
	}
}

?>