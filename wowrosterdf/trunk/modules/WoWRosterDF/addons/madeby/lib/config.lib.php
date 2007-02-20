<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class config
{
	var $db_values;
	var $conf_arrays;
	var $tablename;

	var $form_start = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"submitonce(this)\">\n";
	var $submit_button = "<input type=\"submit\" value=\"Save Settings\" />\n<input type=\"reset\" name=\"Reset\" value=\"Reset\" />\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n<br /><br />\n";
	var $form_end = "</form>\n";


	/**
	 * Constructor. We define the config table to work with here.
	 */
	function config( $tablename)
	{
		$this->tablename = $tablename;
	}

	/**
	 * Build the JScript call to select the initial page
	 *
	 * @return string $JScript | HTML code for JScript to open default page
	 */

	function writeJScript()
	{
		$JScript = '<script type="text/javascript">
			initializetabcontent("config_tabs")
			</script>';
		return $JScript;
	}

	/**
	 * Build the config page menu
	 *
	 * @return string $menu | HTML code for menu/linklist.
	 *          Note: Does not produce the <ul></ul> tags for the list, so you can add your own extra links at the end.
	 */

	function buildConfigMenu()
	{
		global $wordings, $roster_conf;

		$menu = '<!-- Begin Config Menu -->'."\n";

		foreach($this->conf_arrays as $type)
		{
			$menu .= '    <li'.(($type == $this->db_values['master']['startpage']['value']) ? ' class="selected"' : '').'><a href="#" rel="'.$type.'">'.$wordings[$roster_conf['roster_lang']]['admin'][$type].'</a></li>'."\n";
		}

		$menu .='<!-- End Config Menu -->';
		return $menu;
	}

	/**
	 * Build the config page body
	 *
	 * @return string $html | HTML code for main page body.
	 */

	function buildConfigPage()
	{
		global $wordings, $roster_conf;

		// Build the page
		$html = '';
		foreach($this->conf_arrays as $type)
		{
			$i = 0;
			$html .= "<div id=\"$type\" style=\"display:none;\">\n".border('sblue','start',$wordings[$roster_conf['roster_lang']]['admin'][$type])."\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

			foreach($this->db_values[$type] as $values)
			{
				// Here is my nifty auto form generator
				// Takes `form_type` from the db and parses it for form type values and labels
				// Any un-handled form type will cause this file to just display the current value

				// Figure out input type
				$input_field = '';
				$input_type = explode('{',$values['form_type']);

				switch ($input_type[0])
				{
					case 'text':
						$length = explode('|',$input_type[1]);
						$input_field = '<input name="config_'.$values['name'].'" type="text" value="'.$values['value'].'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
						break;

					case 'radio':
						$options = explode('|',$input_type[1]);
						foreach( $options as $value )
						{
							$vals = explode('^',$value);
							$input_field .= '<label class="'.( $values['value'] == $vals[1] ? 'blue' : 'white' ).'"><input class="checkBox" type="radio" name="config_'.$values['name'].'" value="'.$vals[1].'" '.( $values['value'] == $vals[1] ? 'checked="checked"' : '' ).' />'.$vals[0]."</label>\n";
						}
						break;

					case 'select':
						$options = explode('|',$input_type[1]);
						$input_field .= '<select name="config_'.$values['name'].'">'."\n";
						$select_one = 1;
						foreach( $options as $value )
						{
							$vals = explode('^',$value);
							if( $values['value'] == $vals[1] && $select_one )
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
						$input_field = $values['value'];
						break;

					default:
						$input_field = $values['value'];
						break;
				}

				$html .= '
			<tr>
				<td class="membersRow'.(($i%2)+1).'">'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</td>
				<td class="membersRowRight'.(($i%2)+1).'"><div align="right">'.$input_field.'</div></td>
			</tr>';

				$i++;
			}
			$html .= "</table>\n".border('sblue','end')."\n</div>\n";
		}

		return $html;
	}

	/**
	 * Process Data for entry to the database
	 */
	function processData()
	{
		global $wowdb, $queries;

		if (!(array_key_exists('process',$_POST) && ($_POST['process'] == 'process'))) {
			return '';
		}

		$wowdb->reset_values();

		// Update only the changed fields
		foreach( $_POST as $settingName => $settingValue )
		{
			if( substr($settingName,0,7) == 'config_' )
			{
				$settingName = str_replace('config_','',$settingName);

				$get_val = "SELECT `config_value` FROM `".$this->tablename."` WHERE `config_name` = '".$settingName."';";
				$result = $wowdb->query($get_val)
					or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$get_val);

				$config = $wowdb->fetch_assoc($result);

				if( $config['config_value'] != $settingValue && $settingName != 'process' )
				{
					$update_sql[] = "UPDATE `".$this->tablename."` SET `config_value` = '".$wowdb->escape( $settingValue )."' WHERE `config_name` = '".$settingName."';";
				}
			}
		}

		// Update DataBase
		if( is_array($update_sql) )
		{
			foreach( $update_sql as $sql )
			{
				$queries[] = $sql;

				$result = $wowdb->query($sql);
				if( !$result )
				{
					return '<span style="color:#0099FF;font-size:11px;">Error saving settings</span><br />MySQL Said:<br /><pre>'.$wowdb->error().'</pre><br />';
				}
			}
			return '<span style="color:#0099FF;font-size:11px;">Settings have been changed</span>';
		}
		else
		{
			return '<span style="color:#0099FF;font-size:11px;">No changes have been made</span>';
		}
	}



	/**
	 * Get roster config data
	 *
	 * @return error string on failure
	 */
	function getConfigData ()
	{
		global $wowdb, $wordings, $roster_conf;

		$sql = "SELECT * FROM `".$this->tablename."` ORDER BY `id` ASC;";

		// Get the current config values
		$results = $wowdb->query($sql);
		if( $results && $wowdb->num_rows($results) > 0 )
		{
			while($row = $wowdb->fetch_assoc($results))
			{
				$setitem = stripslashes($row['config_type']);
				$arrayitem = stripslashes($row['config_name']);

				$this->db_values[$setitem][$arrayitem]['id'] = $row['id'];
				$this->db_values[$setitem][$arrayitem]['name'] = stripslashes($row['config_name']);
				$this->db_values[$setitem][$arrayitem]['config_type'] = stripslashes($row['config_type']);
				$this->db_values[$setitem][$arrayitem]['value'] = stripslashes($row['config_value']);
				$this->db_values[$setitem][$arrayitem]['form_type'] = stripslashes($row['form_type']);

				// Get description and tooltip
				$desc_tip = explode('|',$wordings[$roster_conf['roster_lang']]['admin'][$row['config_name']]);

				$this->db_values[$setitem][$arrayitem]['description'] = $desc_tip[0];

				$db_val_line = '<br /><br /><span style="color:#FFFFFF;font-size:10px;">db name: <span style="color:#0099FF;font-size:10px;">'.$row['config_name'].'</span></span>';
				$this->db_values[$setitem][$arrayitem]['tooltip'] = $desc_tip[1].$db_val_line;

			}

			$this->conf_arrays = explode('|',$this->db_values['master']['config_list']['value']);
			return;
		}
		else
		{
			return $wowdb->error();
		}
	}


	/**
	 * Create a tooltip
	 *
	 * @param string $disp_text | Text to hover over
	 * @param string $content | Content in tooltip
	 * @param string $caption | Text in the caption
	 * @return string ( Overlib styled tooltip )
	 */
	function createTip( $disp_text , $content , $caption )
	{
		$content = str_replace("'","\'", $content);
		$content = str_replace('"','&quot;', $content);

		$caption = str_replace("'","\'", $caption);
		$caption = str_replace('"','&quot;', $caption);

		$tipsettings = ",WRAP";

		if( !empty($caption) )
			$caption2 = ",CAPTION,'$caption'";

		$tip = "<div style=\"cursor:help;\" onmouseover=\"return overlib('$content'$caption2$tipsettings);\" onmouseout=\"return nd();\">$disp_text</div>";

		return $tip;
	}

	/**
	* Applies addslashes() to the provided data
	*
	* @param    mixed   $data   Array of data or a single string
	* @return   mixed           Array or string of data
	*/
	function slash_global_data(&$data)
	{
	    if ( is_array($data) )
	    {
	        foreach ( $data as $k => $v )
	        {
	            $data[$k] = ( is_array($v) ) ? slash_global_data($v) : addslashes($v);
	        }
	    }
	    return $data;
	}
}

$config = new config($tablename);
?>
