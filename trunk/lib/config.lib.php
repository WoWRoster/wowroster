<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Control Panel library
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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

/**
 * Roster Control Panel library
 *
 * @package    WoWRoster
 * @subpackage ConfigAPI
 */
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
		global $roster;

		$this->tablename = $tablename;
		$this->where = $where;
		$this->prefix = $prefix;
		$this->form_start = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"" . $this->prefix . "config\" onsubmit=\"return confirm('" . $roster->locale->act['confirm_config_submit'] . "');\">\n";
		$this->submit_button = "<div class=\"config-submit\"><input type=\"submit\" value=\"" . $roster->locale->act['config_submit_button'] . "\" />\n<input type=\"reset\" name=\"Reset\" value=\"" . $roster->locale->act['config_reset_button'] . "\" onclick=\"return confirm('" . $roster->locale->act['confirm_config_reset'] . "')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" /></div>\n";
		$this->form_end = "</form>\n";

		// Color Picker JS
		roster_add_js('js/colorpicker.js');
		$jscript =
			'$(function() {
				var ' . $this->prefix . 'tabs=new tabcontent(\'' . $this->prefix . 'tabs\');
				' . $this->prefix . 'tabs.init();

				$(".color-picker").ColorPicker({
					onSubmit: function(hsb, hex, rgb, el) {
						$(el).val("#" + hex.toUpperCase());
						$(el).next().css("background-color", "#" + hex.toUpperCase());
						$(el).ColorPickerHide();
					},
					onShow: function (colpkr) {
						$(colpkr).fadeIn(500);
						return false;
					},
					onBeforeShow: function () {
						$(this).ColorPickerSetColor(this.value);
					},
					onHide: function (colpkr) {
						$(colpkr).fadeOut(500);
						return false;
					}
				})
				.bind("keyup", function(){
					$(this).ColorPickerSetColor(this.value);
					$(this).next().css("background-color", this.value);
				})
				.next().click(function(){
					$(this).prev().click();
				});
			});';
		roster_add_js($jscript, 'inline', 'footer');
		roster_add_css('templates/' . $roster->tpl->tpl . '/colorpicker.css', 'theme');
	}

	/**
	 * Build the config page menu
	 * When a parameter is passed, all dynamic config api generated links will be changed from # to makelink(string)
	 *
	 * @param string $in_config | Replace # with makelink(string)
	 * @return string $menu | HTML code for menu/linklist.
	 */
	function buildConfigMenu( $in_config=false )
	{
		global $roster;

		$menu = '<ul id="' . $this->prefix . 'tabs" class="tab_menu">' . "\n";

		if (is_array($this->db_values['menu']))
		{
			foreach($this->db_values['menu'] as $values)
			{
				$menu_type = explode('{',$values['form_type']);

				switch ($menu_type[0])
				{
					// in the left menu bar, we print external links and all page/config block types.
					case 'link':
						$menu .= '<li' . ( ($values['value'] == ROSTER_PAGE_NAME) ? ' class="selected"' : '' ) . '>'
							. '<span class="ui-icon ui-icon-link" style="float:right;"></span>'
							. '<span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . $this->createInlineTip($values['tooltip'],$values['description']) . '></span>'
							. '<a href="' . $values['value'] . '">'
							. $values['description'] . '</a></li>' . "\n";
						break;

					case 'newlink':
						$menu .= '<li' . ( ($values['value'] == ROSTER_PAGE_NAME) ? ' class="selected"' : '' ) . '>'
							. '<span class="ui-icon ui-icon-extlink" style="float:right;"></span>'
							. '<span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . $this->createInlineTip($values['tooltip'],$values['description']) . '></span>'
							. '<a href="' . $values['value'] . '" target="_blank">'
							. $values['description'] . '</a></li>' . "\n";
						break;

					case 'makelink':
						$menu .= '<li' . ( ($values['value'] == ROSTER_PAGE_NAME) ? ' class="selected"' : '' ) . '>'
							. '<span class="ui-icon ui-icon-link" style="float:right;"></span>'
							. '<span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . $this->createInlineTip($values['tooltip'],$values['description']) . '></span>'
							. '<a href="' . makelink($values['value']) . '">'
							. $values['description'] . '</a></li>' . "\n";
						break;

					case 'makenewlink':
						$menu .= '<li' . ( ($values['value'] == ROSTER_PAGE_NAME) ? ' class="selected"' : '' ) . '>'
							. '<span class="ui-icon ui-icon-newwin" style="float:right;"></span>'
							. '<span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . $this->createInlineTip($values['tooltip'],$values['description']) . '></span>'
							. '<a href="' . makelink($values['value']) . '" target="_blank">'
							. $values['description'] . '</a></li>' . "\n";
						break;

					case 'page': 	// all pages are the same here
					case 'pageframe':
					case 'pagehide':
					case 'blockframe':
					case 'blockhide':
					case 'function':
						$menu .= '<li' . ( !$in_config && ($values['name'] == $this->db_values['master']['startpage']['value']) ? ' class="selected"' : '' ) . '>'
							. ( $in_config ? '<span class="ui-icon ui-icon-link" style="float:right;"></span>' : '' )
							. '<span class="ui-icon ui-icon-help" style="float:left;cursor:help;" ' . $this->createInlineTip($values['tooltip'],$values['description']) . '></span>'
							. '<a href="' . ( !$in_config ? '#' : makelink($in_config) ) . '" rel="' . $values['name'] . '">' . $values['description'] . '</a></li>' . "\n";
						break;

					case 'hr':
						$menu .= "<li><hr /></li>\n";

					default:
						break;
				}
			}
		}

		$menu .= "</ul>\n";

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

		foreach($this->db_values['menu'] as $values)
		{
			$type = explode('{',$values['form_type']);
			$page = '<div id="' . $values['name'] . '">' . "\n";

			$type[1] = ( isset($type[1]) ? $type[1] : '');

			$header_text = $values['description'];

			switch ($type[0])
			{
				case 'page':
					$page .= $this->buildPage($values['name'],$type[1]);
					$addpage = true;
					break;

				case 'pageframe':
					$page .= '
<div class="tier-2-a">
	<div class="tier-2-b">
' . ($header_text ? '		<div class="tier-2-title">' . $header_text . "</div>\n" : '') .
$this->buildPage($values['name'],$type[1]) . '
	</div>
</div>
';
					$addpage = true;
					break;

				case 'pagehide':
					$addpage = true;
					$page .= '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title" style="cursor:pointer;" onclick="showHide(\'table_' . $values['name'] . '\',\'img_' . $values['name'] . '\',\'' . $roster->config['theme_path'] . '/images/button_open.png\',\'' . $roster->config['theme_path'] . '/images/button_close.png\');">
			' . ($header_text ? $header_text : '&nbsp;') . '
			<img style="float:right;" id="img_' . $values['name'] . '" src="' . $roster->config['theme_path'] . '/images/button_open.png" alt="" />
		</div>
		<div id="table_' . $values['name'] . '">
' . $this->buildPage($values['name'],$type[1]) . '
		</div>
	</div>
</div>
';
					break;

				case 'blockframe':
					$addpage = true;
					$page .= '
<div class="tier-2-a">
	<div class="tier-2-b">
' . ($header_text ? '		<div class="tier-2-title">' . $header_text . "</div>\n" : '') .
$this->buildBlock($values['name']) . '
	</div>
</div>
';
					break;

				case 'blockhide':
					$addpage = true;
					$page .= '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title" style="cursor:pointer;" onclick="showHide(\'table_' . $values['name'] . '\',\'img_' . $values['name'] . '\',\'' . $roster->config['theme_path'] . '/images/button_open.png\',\'' . $roster->config['theme_path'] . '/images/button_close.png\');">
			' . ($header_text ? $header_text : '&nbsp;') . '
			<img style="float:right;" id="img_' . $values['name'] . '" src="' . $roster->config['theme_path'] . '/images/button_open.png" alt="" />
		</div>
		<div id="table_' . $values['name'] . '">
' . $this->buildBlock($values['name']) . '
		</div>
	</div>
</div>
';
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

	/**
	 * Build a set of config blocks
	 *
	 * @param string $page pagename of the page to render
	 * @return string $html HTML code for the block
	 */
	function buildPage($page, $columns)
	{
		global $roster;

		// Only print a table is there is more than 1 column
		if( $columns > 1 )
		{
			$html = '<table style="width:100%;" cellspacing="0" cellpadding="0"><tr><td align="center" valign="top">';
		}
		else
		{
			$html = '';
		}
		$i = 1;

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
					$html .= '
<div class="tier-2-a">
	<div class="tier-2-b">
' . ($header_text ? '		<div class="tier-2-title">' . $header_text . "</div>\n" : '') .
$this->buildPage($values['name'],$type[1]) . '
	</div>
</div>
';
					break;

				case 'pagehide':
					$html .= '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title" style="cursor:pointer;" onclick="showHide(\'table_' . $values['name'] . '\',\'img_' . $values['name'] . '\',\'' . $roster->config['theme_path'] . '/images/button_open.png\',\'' . $roster->config['theme_path'] . '/images/button_close.png\');">
			' . ($header_text ? $header_text : '&nbsp;') . '
			<img style="float:right;" id="img_' . $values['name'] . '" src="' . $roster->config['theme_path'] . '/images/button_open.png" alt="" />
		</div>
		<div id="table_' . $values['name'] . '">
' . $this->buildPage($values['name'],$type[1]) . '
		</div>
	</div>
</div>
';
					break;

				case 'blockframe':
					$html .= '
<div class="tier-2-a">
	<div class="tier-2-b">
' . ($header_text ? '		<div class="tier-2-title">' . $header_text . "</div>\n" : '') .
	$this->buildBlock($values['name']) . '
	</div>
</div>
';
					break;

				case 'blockhide':
					$html .= '
<div class="tier-2-a">
	<div class="tier-2-b">
		<div class="tier-2-title" style="cursor:pointer;" onclick="showHide(\'table_' . $values['name'] . '\',\'img_' . $values['name'] . '\',\'' . $roster->config['theme_path'] . '/images/button_open.png\',\'' . $roster->config['theme_path'] . '/images/button_close.png\');">
			' . ($header_text ? $header_text : '&nbsp;') . '
			<img style="float:right;" id="img_' . $values['name'] . '" src="' . $roster->config['theme_path'] . '/images/button_open.png" alt="" />
		</div>
		<div id="table_' . $values['name'] . '">
' . $this->buildBlock($values['name']) . '
		</div>
	</div>
</div>
';
					break;

				case 'function':
					$html .= $type[1]($values);
					break;

				default:
					break;
			}

			// Only print a table is there is more than 1 column
			if( $columns > 1 && $i < $columns )
			{
				if ($i % $columns)
				{
					$html .= '</td><td align="center" valign="top">';
				}
				else
				{
					$html .= '</td></tr><tr><td align="center" valign="top">';
				}
			}
			++$i;
		}

		// Only print a table is there is more than 1 column
		if( $columns > 1 )
		{
			$html .= '</td></tr></table>';
		}
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
		global $roster;

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

					$input_field = '<input name="' . $this->prefix . $values['name'] . '" type="text" value="' . htmlentities($values['value']) . '" size="' . $length[1] . '" maxlength="' . $length[0] . '" />';
					break;

				case 'radio':
					$options = explode('|',$input_type[1]);
					$input_field .= '<div class="radioset">';
					foreach( $options as $value )
					{
						$vals = explode('^',$value);
						$input_field .= '<input type="radio" id="rad_' . $this->prefix . $this->radio_num . '" name="' . $this->prefix . $values['name'] . '" value="' . $vals[1] . '" ' . ( $values['value'] == $vals[1] ? 'checked="checked"' : '' ) . ' /><label for="rad_' . $this->prefix . $this->radio_num . '"' . ( $values['value'] == $vals[1] ? ' class="selected"' : '' ) . '>' . $vals[0] . "</label>\n";
						$this->radio_num++;
					}
					$input_field .= '</div>';
					break;

				case 'select':
					$options = explode('|',$input_type[1]);
					$input_field .= '<select name="' . $this->prefix . $values['name'] . '">' . "\n";
					$select_one = 1;
					foreach( $options as $value )
					{
						$vals = explode('^',$value);
						if( $values['value'] == $vals[1] && $select_one )
						{
							$input_field .= '  <option value="' . $vals[1] . '" selected="selected">' . $vals[0] . '</option>' . "\n";
							$select_one = 0;
						}
						else
						{
							$input_field .= '  <option value="' . $vals[1] . '">' . $vals[0] . '</option>' . "\n";
						}
					}
					$input_field .= '</select>';
					break;

				case 'color':
					$input_field .= '<input type="text" maxlength="7" size="8" class="color-picker"'
						. ' value="' . $values['value'] . '" name="' . $this->prefix . 'color_' . $values['name'] . '"'
						. ' id="' . $this->prefix . 'color_' . $values['name'] . '" />'
						. '<div class="color-display" style="background-color:' . $values['value'] . ';"></div>' . "\n";
					break;

				case 'access':
					$values['title'] = '';
					$input_field = $roster->auth->rosterAccess($values);
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
/*
			$html .= '
		<tr class="membersRowColor' . (($i%2)+1) . '">
			<td class="membersRowCell">
				<span class="ui-icon ui-icon-help" style="cursor:help;" ' . $this->createInlineTip($values['tooltip'],$values['description']) . '></span>
				' . $values['description'] . '
			</td>
			<td class="membersRowCell" style="text-align:right;">' . $input_field . '</td>
		</tr>';
*/
			$html .= '
<div class="tier-3-a">
	<div class="tier-3-b">
		<div class="config">
			<div class="config-name">
				<span class="ui-icon ui-icon-help" style="cursor:help;" ' . $this->createInlineTip($values['tooltip'],$values['description']) . '></span>
				' . $values['description'] . '
			</div>
			<div class="config-input">
				' . $input_field . '
			</div>
		</div>
	</div>
</div>';

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
			return false;
		}
		// Update only the changed fields
		foreach( $_POST as $settingName => $settingValue )
		{
			// we use an array now to we must implode it!
			if (is_array($settingValue))
			{
				$settingValue = implode(":",$settingValue);
			}
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
							$settingValue = '#' . strtoupper($settingValue);
						}
						else
						{
							$settingValue = strtoupper($settingValue);
						}
					}
				}

				// Update the css_js_query_string
				$update_sql[] = "UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . $roster->db->escape(base_convert(REQUEST_TIME, 10, 36)) . "' WHERE `id` = '99';";

				if( !empty($this->db_values) && isset($this->db_values['all']) && isset($this->db_values['all'][$settingName]))
				{
					if( $this->db_values['all'][$settingName]['value'] != $settingValue )
					{
						$update_sql[] = "UPDATE `" . $this->tablename . "` SET `config_value` = '" . $roster->db->escape($settingValue) . "' WHERE (" . $this->where . ") AND `config_name` = '" . $roster->db->escape($settingName) . "';";
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
		if( isset($update_sql) && is_array($update_sql) && count($update_sql) > 0 )
		{
			foreach( $update_sql as $sql )
			{
				$queries[] = $sql;

				$result = $roster->db->query($sql);
				if( !$result )
				{
					$roster->set_message('There was an error saving settings.', '', 'error');
					$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
					return false;
				}
			}
			$roster->set_message('Settings have been changed');
			return true;
		}
		else
		{
			$roster->set_message('No changes have been made');
			return true;
		}
	}

	/**
	 * Get config data
	 *
	 * @return bool true failure
	 */
	function getConfigData()
	{
		global $roster;

		$sql = "SELECT * FROM `" . $this->tablename . "` WHERE (" . $this->where . ") ORDER BY `id` ASC;";

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

				if( $roster->config['debug_mode'] > 0 )
				{
				$db_val_line = '<ul class="ul-no-m"><li>db name: <span style="color:#FF9900;">' . $row['config_name'] . '</span></li></ul>';
				}
				else
				{
					$db_val_line = '';
				}

				// Get description and tooltip
				if( isset($roster->locale->act['admin'][$row['config_name']]) )
				{
					$desc_tip = explode('|',$roster->locale->act['admin'][$row['config_name']],2);
					$this->db_values[$setitem][$arrayitem]['description'] = $desc_tip[0];
					$this->db_values[$setitem][$arrayitem]['tooltip'] = ( isset($desc_tip[1]) ? $desc_tip[1] : '' ) . $db_val_line;
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

			return true;
		}
		else
		{
			$roster->set_message('There was a database error while fetching config data.', '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			return false;
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


	/**
	 * Create an inline tooltip for use in an html tag
	 *
	 * @param string $content | Content in tooltip
	 * @param string $caption | Text in the caption
	 * @return string ( Overlib styled tooltip )
	 */
	function createInlineTip( $content , $caption )
	{
		return makeOverlib($content,$caption,'',2,'',',WRAP');
	}

}
