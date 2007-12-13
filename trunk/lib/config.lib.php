<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Control Panel library
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage ConfigAPI
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class roster_config
{
	var $db_values;
	var $tablename;
	var $where;
	var $prefix;

	var $form_start;
	var $submit_button;
	var $form_end;
	var $jscript;

	var $formpages = '';
	var $nonformpages = '';

	var $radio_num = 0;
	var $check_num = 0;

	/**
	 * Constructor. We define the config table to work with here.
	 */
	function roster_config( $tablename, $where='1', $prefix='config_' )
	{
		global $roster_login, $roster;

		$roster->output['body_onload'] .= 'initARC(\'config\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

		$this->tablename = $tablename;
		$this->where = $where;
		$this->prefix = $prefix;
		$this->form_start = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('".$roster->locale->act['confirm_config_submit']."') &amp;&amp; submitonce(this);\">\n";
		$this->submit_button = "<br /><br />\n<input type=\"submit\" value=\"".$roster->locale->act['config_submit_button']."\" />\n<input type=\"reset\" name=\"Reset\" value=\"".$roster->locale->act['config_reset_button']."\" onclick=\"return confirm('".$roster->locale->act['confirm_config_reset']."')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n";
		$this->form_end = "</form>\n";
		$this->jscript  = "\n<script type=\"text/javascript\">\ninitializetabcontent(\"".$this->prefix."tabs\")\n</script>\n";
		$this->jscript .= "<script type=\"text/javascript\" src=\"". ROSTER_PATH ."js/color_functions.js\"></script>\n";
	}

	/**
	 * Build the config page menu
	 *
	 * @return string $menu | HTML code for menu/linklist.
	 */
	function buildConfigMenu()
	{
		global $roster;

		$menu = '<!-- Begin Config Menu -->'."\n".
			border('sgray','start',$roster->locale->act['roster_config_menu'])."\n".
			'<div style="width:145px;">'."\n".
			'  <ul id="'.$this->prefix.'tabs" class="tab_menu">'."\n";

		if (is_array($this->db_values['menu']))
		{
			foreach($this->db_values['menu'] as $values)
			{
				$menu_type = explode('{',$values['form_type']);

				switch ($menu_type[0])
				{
					// in the left menu bar, we print external links and all page/config block types.
					case 'link':
						$menu .= '    <li><a href="'.$values['value'].'"'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</a></li>'."\n";
						break;

					case 'newlink':
						$menu .= '    <li><a href="'.$values['value'].'" target="_blank"'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</a></li>'."\n";
						break;

					case 'makelink':
						$menu .= '    <li><a href="'.makelink($values['value']).'"'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</a></li>'."\n";
						break;

					case 'makenewlink':
						$menu .= '    <li><a href="'.makelink($values['value']).'" target="_blank"'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</a></li>'."\n";
						break;

					case 'page': 	// all pages are the same here
					case 'pageframe':
					case 'pagehide':
					case 'blockframe':
					case 'blockhide':
					case 'function':
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
		global $roster;

		if (is_array($this->db_values['menu']))
		{
			foreach($this->db_values['menu'] as $values)
			{
				$type = explode('{',$values['form_type']);
				$page = '<div id="'.$values['name'].'" style="display:none;">'."\n";

				$type[1] = ( isset($type[1]) ? $type[1] : '');

				$header_text = explode('|',$roster->locale->act['admin'][$values['name']],2);
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
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster->config['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
						$page .= border('sblue','end');
						$page .= '</div>'."\n";
						$page .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster->config['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
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
						$page .= $this->buildBlock($values['name']);
						$page .= "</table>\n";
						$page .= border('sblue','end')."\n";
						$addpage = true;
						break;

					case 'blockhide':
						$page .= '<div id="'.$values['name'].'Hide" style="display:none;">'."\n";
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster->config['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
						$page .= border('sblue','end');
						$page .= '</div>'."\n";
						$page .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
						$page .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster->config['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
						$page .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";
						$page .= $this->buildBlock($values['name']);
						$page .= "</table>\n";
						$page .= border('sblue','end');
						$page .= '</div>'."\n";
						$addpage = true;
						break;

					case 'function':
						$this->nonformpages .= $type[1]();
						$addpage = false;
						break;

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
		global $roster;

		$html = '<table><tr><td align="center">';
		$i = 0;

		foreach($this->db_values[$page] as $values)
		{
			if( isset($roster->locale->act['admin'][$values['name']]) )
			{
				$header_text = explode('|',$roster->locale->act['admin'][$values['name']],2);
				$header_text = $header_text[0];
			}
			else
			{
				$header_text = '';
			}

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
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster->config['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
					$html .= border('sblue','end');
					$html .= '</div>'."\n";
					$html .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster->config['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
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
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Hide','".$values['name']."Show')\"><img src=\"".$roster->config['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\" />".$header_text."</div>");
					$html .= border('sblue','end');
					$html .= '</div>'."\n";
					$html .= '<div id="'.$values['name'].'Show" style="display:inline">'."\n";
					$html .= border('sblue','start',"<div style=\"cursor:pointer;\" onclick=\"swapShow('".$values['name']."Show','".$values['name']."Hide')\"><img src=\"".$roster->config['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\" />".$header_text."</div>");
					$html .= '<table cellspacing="0" cellpadding="0" class="bodyline">'."\n";
					$html .= $this->buildBlock($values['name']);
					$html .= '</table>'."\n";
					$html .= border('sblue','end');
					$html .= '</div>'."\n";
					break;

				case 'function':
					$html .= $type[1]($values);
					break;

				default:
					break;
			}
			if ((++$i) % $columns)
			{
				$html .= '</td><td>';
			}
			else
			{
				$html .= '</td></tr><tr><td>';
			}
		}
		$html .= '</td></tr></table>';
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
		global $roster_login, $roster;

		$i = 0;
		$html = '';
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

					if( $length[1] < 20 )
					{
						$text_class = '64';
					}
					elseif( $length[1] < 30 )
					{
						$text_class = '128';
					}
					elseif( $length[1] < 40 )
					{
						$text_class = '192';
					}
					else
					{
						$text_class = '';
					}

					$input_field = '<input class="wowinput'.$text_class.'" name="'.$this->prefix.$values['name'].'" type="text" value="'.htmlentities($values['value']).'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
					break;

				case 'radio':
					$options = explode('|',$input_type[1]);
					foreach( $options as $value )
					{
						$vals = explode('^',$value);
						$input_field .= '<input type="radio" id="rad_'.$this->radio_num.'" name="'.$this->prefix.$values['name'].'" value="'.$vals[1].'" '.( $values['value'] == $vals[1] ? 'checked="checked"' : '' ).' /><label for="rad_'.$this->radio_num.'" class="'.( $values['value'] == $vals[1] ? 'blue' : 'white' ).'">'.$vals[0]."</label>\n";
						$this->radio_num++;
					}
					break;

				case 'select':
					$options = explode('|',$input_type[1]);
					$input_field .= '<select name="'.$this->prefix.$values['name'].'">'."\n";
					$select_one = 1;
					foreach( $options as $value )
					{
						$vals = explode('^',$value);
						if( $values['value'] == $vals[1] && $select_one )
						{
							$input_field .= '  <option value="'.$vals[1].'" selected="selected">-[ '.$vals[0].' ]-</option>'."\n";
							$select_one = 0;
						}
						else
						{
							$input_field .= '  <option value="'.$vals[1].'">'.$vals[0].'</option>'."\n";
						}
					}
					$input_field .= '</select>';
					break;

				case 'color':
					$input_field .= '<input type="text" class="colorinput" maxlength="7" size="10" style="background-color:'.$values['value'].';" value="'.$values['value'].'" name="'.$this->prefix.'color_'.$values['name'].'" id="'.$this->prefix.'color_'.$values['name'].'" /><img src="'.$roster->config['img_url'].'color/select_arrow.gif" style="cursor:pointer;vertical-align:middle;margin-bottom:2px;" onclick="showColorPicker(this,document.getElementById(\''.$this->prefix.'color_'.$values['name'].'\'))" alt="" />'."\n";
					break;

				case 'access':
					$input_field = $roster_login->rosterAccess($values);
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
			<td class="membersRow'.(($i%2)+1).'"><div'.$this->createTip($values['description'],$values['tooltip'],$values['description']).'</div></td>
			<td class="membersRowRight'.(($i%2)+1).'"><div align="right">'.$input_field.'</div></td>
		</tr>';

			$i++;
		}
		return $html;
	}

	/**
	 * Process Data for entry to the database
	 */
	function processData( &$config )
	{
		global $queries, $roster, $addon;

		if( !(array_key_exists('process',$_POST) && ($_POST['process'] == 'process')) )
		{
			return '';
		}

		// Update only the changed fields
		foreach( $_POST as $settingName => $settingValue )
		{
			// Remove the extra slashes added by settings.php
			$settingValue = stripslashes($settingValue);

			if( substr($settingName,0,strlen($this->prefix)) == $this->prefix )
			{
				// Get rid of the prefix
				$settingName = substr($settingName,strlen($this->prefix));

				// Fix directories
				if( $settingName == 'img_url' || $settingName == 'interface_url' )
				{
					// Replace back-slashes
					$settingValue = preg_replace('|\\\\|','/',$settingValue );

					// Check for directories defined with no '/' at the end
					// and with a '/' at the beginning
					if( substr($settingValue, 0, 1) == '/' )
					{
						$settingValue = substr($settingValue, 1);
					}
					if( substr($settingValue, -1, 1) != '/' )
					{
						$settingValue .= '/';
					}
				}

				// Fix color boxes
				if( substr($settingName, 0, 6) == 'color_' )
				{
					// Get rid of the color prefix
					$settingName = substr($settingName, 6);

					if( $settingValue != '' )
					{
						if( substr($settingValue, 0, 1) != '#' )
						{
							$settingValue = '#'.strtoupper($settingValue);
						}
						else
						{
							$settingValue = strtoupper($settingValue);
						}
					}
				}

				if( !empty($this->db_values) && isset($this->db_values['all']) && isset($this->db_values['all'][$settingName]))
				{
					if( $this->db_values['all'][$settingName]['value'] != $settingValue )
					{
						$update_sql[] = "UPDATE `".$this->tablename."` SET `config_value` = '".$roster->db->escape($settingValue)."' WHERE (".$this->where.") AND `config_name` = '".$roster->db->escape($settingName)."';";
					}
					if( $this->db_values['all'][$settingName]['value'] == $config[$settingName] )
					{
						$config[$settingName] = $settingValue;
					}
					$this->db_values['all'][$settingName]['value'] = $settingValue;
				}
			}
		}

		// Update DataBase
		if( isset($update_sql) && is_array($update_sql) && count($update_sql)>0 )
		{
			foreach( $update_sql as $sql )
			{
				$queries[] = $sql;

				$result = $roster->db->query($sql);
				if( !$result )
				{
					return '<span style="color:#0099FF;font-size:11px;">Error saving settings</span><br />MySQL Said:<br /><pre>'.$roster->db->error().'</pre><br />';
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
	 * Get config data
	 *
	 * @return error string on failure
	 */
	function getConfigData()
	{
		global $roster;

		$sql = "SELECT * FROM `".$this->tablename."` WHERE (".$this->where.") ORDER BY `id` ASC;";

		// Get the current config values
		$results = $roster->db->query($sql);
		if( $results && $roster->db->num_rows($results) > 0 )
		{
			while($row = $roster->db->fetch($results))
			{
				$setitem = $row['config_type'];
				$arrayitem = $row['config_name'];

				$this->db_values[$setitem][$arrayitem]['id'] = $row['id'];
				$this->db_values[$setitem][$arrayitem]['name'] = $row['config_name'];
				$this->db_values[$setitem][$arrayitem]['config_type'] = $row['config_type'];
				$this->db_values[$setitem][$arrayitem]['value'] = $row['config_value'];
				$this->db_values[$setitem][$arrayitem]['form_type'] = $row['form_type'];

				$db_val_line = '<br /><br /><span style="color:#FFFFFF;font-size:10px;">db name: <span style="color:#0099FF;">'.$row['config_name'].'</span></span>';

				// Get description and tooltip
				if( isset($roster->locale->act['admin'][$row['config_name']]) )
				{
					$desc_tip = explode('|',$roster->locale->act['admin'][$row['config_name']],2);
					$this->db_values[$setitem][$arrayitem]['description'] = $desc_tip[0];
					$this->db_values[$setitem][$arrayitem]['tooltip'] = $desc_tip[1].$db_val_line;
				}
				else
				{
					$desc_tip = '';
					$this->db_values[$setitem][$arrayitem]['description'] = '';
					$this->db_values[$setitem][$arrayitem]['tooltip'] = $db_val_line;
				}
				$this->db_values['all'][$arrayitem] =& $this->db_values[$setitem][$arrayitem];
			}

			$roster->db->free_result($results);

			return;
		}
		else
		{
			return $roster->db->error();
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
		$tip = makeOverlib($content,$caption,'',2,'',',WRAP');
		$tip = " style=\"cursor:help;\" $tip>$disp_text";

		return $tip;
	}

}
