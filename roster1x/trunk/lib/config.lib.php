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
	var $tablename;

	var $form_start;
	var $submit_button;
	var $form_end;
	var $jscript;

	var $formpages = '';
	var $nonformpages = '';

	/**
	 * Constructor. We define the config table to work with here.
	 */
	function config( $tablename )
	{
		global $wordings, $roster_conf;

		$this->tablename = $tablename;
		$this->form_start = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('".$wordings[$roster_conf['roster_lang']]['confirm_config_submit']."') && submitonce(this);\">\n";
		$this->submit_button = "<input type=\"submit\" value=\"Save Settings\" />\n<input type=\"reset\" name=\"Reset\" value=\"Reset\" onClick=\"return confirm('".$wordings[$roster_conf['roster_lang']]['confirm_config_reset']."')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n<br /><br />\n";
		$this->form_end = "</form>\n";
		$this->jscript = "\n<script type=\"text/javascript\">\ninitializetabcontent(\"config_tabs\")\n</script>\n";
	}

	/**
	 * Build the config page menu
	 *
	 * @return string $menu | HTML code for menu/linklist.
	 */
	function buildConfigMenu()
	{
		global $wordings, $roster_conf;

		$menu = '<!-- Begin Config Menu -->'."\n".
			border('sgray','start',$wordings[$roster_conf['roster_lang']]['roster_config_menu'])."\n".
			'<div style="width:145px;">'."\n".
			'  <ul id="config_tabs" class="tab_menu">'."\n";

		if (is_array($this->db_values['menu']))
		{
			foreach($this->db_values['menu'] as $values)
			{
				$menu_type = explode('{',$values['form_type']);
				$URL = str_replace(array('%addon%','%roster%'),array($_REQUEST['addon'],ROSTER_URL),$values['value']);

				switch ($menu_type[0])
				{
					// in the left menu bar, we print external links and all page/config block types.
					case 'link':
						$menu .= '    <li><a href="'.$URL.'"'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</a></li>'."\n";
						break;

					case 'newlink':
						$menu .= '    <li><a href="'.$URL.'" target="_blank"'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</a></li>'."\n";
						break;

					case 'page': 	// all pages are the same here
					case 'pageframe':
					case 'pagehide':
					case 'blockframe':
					case 'blockhide':
						$menu .= '    <li'.(($values['name'] == $this->db_values['master']['startpage']['value']) ? ' class="selected"' : '').'><a href="#" rel="'.$values['name'].'"'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</a></li>'."\n";
						break;

					default:
						break;
				}
			}
		}

		$menu .= '  </ul>'."\n".'</div>'."\n".border('sgray','end').'<!-- End Config Menu -->';
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

		if (is_array($this->db_values['menu']))
		{
			foreach($this->db_values['menu'] as $values)
			{
				$type = explode('{',$values['form_type']);
				$page = '<div id="'.$values['name'].'" style="display:none;">'."\n";

				$header_text = explode('|',$wordings[$roster_conf['roster_lang']]['admin'][$values['name']]);
				$header_text = $header_text[0];

				switch ($type[0])
				{
					case 'page':
						$page .= $this->buildPage($values['name'],$type[1]);
						$addpage = true;
						break;

					case 'pageframe':
						$page .= border('sblue','start',$header_text)."\n";
						$page .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
						$page .= $this->buildPage($values['name'],$type[1]);
						$page .= "</table>\n";
						$page .= border('sblue','end')."\n";
						$addpage = true;
						break;

					case 'pagehide':
						$page .= '<div id="'.$values['name'].'Hide" style="display:none;">'."\n";
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
						$page .= border('sblue','end');
						$page .= '</div>'."\n";
						$page .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
						$page .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
						$page .= $this->buildPage($values['name'],$type[1]);
						$page .= "</table>\n";
						$page .= border('sblue','end');
						$page .= '</div>'."\n";
						$addpage = true;
						break;

					case 'blockframe':
						$page .= border('sblue','start',$header_text)."\n";
						$page .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
						$page .= $this->buildBlock($values['name'],$type[1]);
						$page .= "</table>\n";
						$page .= border('sblue','end')."\n";
						$addpage = true;
						break;

					case 'blockhide':
						$page .= '<div id="'.$values['name'].'Hide" style="display:none;">'."\n";
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
						$page .= border('sblue','end');
						$page .= '</div>'."\n";
						$page .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
						$page .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
						$page .= $this->buildBlock($values['name'],$type[1]);
						$page .= "</table>\n";
						$page .= border('sblue','end');
						$page .= '</div>'."\n";
						$addpage = true;
						break;

					case 'function':
						$this->nonformpages .= $type[1]();
						$addpage = false;

					default:
						$addpage = false;
						break;
				}
				$page .= "</div>\n";
				if ($addpage)
				{
					$this->formpages .= $page;
				}
			}
		}
	}

	/**
	 * Build a set of config blocks
	 *
	 * @param string $page pagename of the page to render
	 * @return string $html HTML code for the block
	 */
	function buildPage($page,$columns)
	{
		global $wordings, $roster_conf;

		$html = '<table><tr><td>';
		$i = 0;


		foreach($this->db_values[$page] as $values)
		{
			$header_text = explode('|',$wordings[$roster_conf['roster_lang']]['admin'][$values['name']]);
			$header_text = $header_text[0];

			$type = explode('{',$values['form_type']);
			switch ($type[0])
			{
				case 'page':
					$html .= $this->buildPage($values['name'],$type[1]);
					break;

				case 'pageframe':
					$html .= border('sblue','start',$header_text)."\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
					$html .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
					$html .= $this->buildPage($values['name'],$type[1]);
					$html .= "</table>\n";
					$html .= border('sblue','end');
					break;

				case 'pagehide':
					$html .= '<div id="'.$values['name'].'Hide" style="display:none;">'."\n";
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
					$html .= border('sblue','end');
					$html .= '</div>'."\n";
					$html .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
					$html .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
					$html .= $this->buildPage($values['name'],$type[1]);
					$html .= "</table>\n";
					$html .= border('sblue','end');
					$html .= '</div>'."\n";
					break;

				case 'blockframe':
					$html .= border('sblue','start',$header_text)."\n";
					$html .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
					$html .= $this->buildBlock($values['name']);
					$html .= "</table>\n";
					$html .= border('sblue','end')."\n";
					break;

				case 'blockhide':
					$html .= '<div id="'.$values['name'].'Hide" style="display:none;">'."\n";
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
					$html .= border('sblue','end');
					$html .= '</div>'."\n";
					$html .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
					$html .= '<table cellspacing="0" cellpadding="0" class="bodyline">'."\n";
					$html .= $this->buildBlock($values['name']);
					$html .= '</table>'."\n";
					$html .= border('sblue','end');
					$html .= '</div>'."\n";
					break;

				case 'function':
					$html .= $type[1]();
					break;

				default:
					break;
			}
			if ((++$i) % $columns)
			{
				$html .= '<td>';
			}
			else
			{
				$html .= '<tr><td>';
			}
		}
		$html .= '</table>';
		return $html;
	}

	/**
	 * Build one block of config options
	 *
	 * @param string $block pagename of the block to render
	 * @return string $html HTML code for the block
	 */
	function buildBlock($block)
	{
		global $roster_login;
		$i = 0;
		foreach($this->db_values[$block] as $values)
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

				case 'access':
					$input_field = $roster_login->accessConfig($values);
					break;

				case 'function':
					$input_field = $input_type[1]($values);
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
			<td class="membersRow'.(($i%2)+1).'"><div '.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</div></td>
			<td class="membersRowRight'.(($i%2)+1).'"><div align="right">'.$input_field.'</div></td>
		</tr>';

			$i++;
		}
		return $html;
	}

	/**
	 * Process Data for entry to the database
	 */
	function processData()
	{
		global $wowdb, $queries;

		if( !(array_key_exists('process',$_POST) && ($_POST['process'] == 'process')) )
		{
			return '';
		}

		$wowdb->reset_values();

		// Update only the changed fields
		foreach( $_POST as $settingName => $settingValue )
		{
			if( substr($settingName,0,7) == 'config_' )
			{
				$settingName = str_replace('config_','',$settingName);

				// Fix directories
				if( $settingName == 'img_url' || $settingName == 'interface_url' )
				{
					// Replace back-slashes
					$settingValue = preg_replace('|\\\\|','/',$settingValue );

					// Check for directories defined with no '/' at the end
					// and with a '/' at the beginning
					if( substr($settingValue, -1, 1) != '/' )
					{
						$settingValue .= '/';
					}
					if( substr($settingValue, 0, 1) == '/' )
					{
						$settingValue = substr($settingValue, 1);
					}
				}

				// Fix roster url
				if( $settingName == 'roster_dir' )
				{
					// Replace back-slashes
					$settingValue = preg_replace('|\\\\|','/',$settingValue );

					// Check for directories defined with no '/' at the end
					// and take it off
					if( substr($settingValue, -1, 1) == '/' )
					{
						$settingValue = substr($settingValue, 0, -1);
					}
				}

				$get_val = "SELECT `config_value` FROM `".$this->tablename."` WHERE `config_name` = '".$settingName."';";
				$result = $wowdb->query($get_val);

				if( !$result )
				{
					die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$get_val);
				}

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
			return '<span style="color:#0099FF;font-size:11px;">Settings have been changed</span><br />';
		}
		else
		{
			return '<span style="color:#0099FF;font-size:11px;">No changes have been made</span><br />';
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
		$tipsettings = ",WRAP";

		$content = str_replace("'","\'", $content);
		$content = str_replace('"','&quot;', $content);

		if( !empty($caption) )
		{
			$caption = str_replace("'","\'", $caption);
			$caption = str_replace('"','&quot;', $caption);
			$caption = ",CAPTION,'$caption'";
		}

		$tip = " style=\"cursor:help;\" onmouseover=\"return overlib('$content'$caption$tipsettings);\" onmouseout=\"return nd();\">$disp_text";

		return $tip;
	}

}

$config = new config($tablename);
?>
