<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.0
 * @package    WoWRoster
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Profession Search
 *
 * @package    WoWRoster
 * @subpackage Search
 */
class roster_professionsSearch
{
	var $options;
	var $result = array();
	var $result_count = 0;
	var $start_search;
	var $stop_search;
	var $time_search;
	var $open_table;
	var $close_table;
	var $search_url;
	var $data = array();    // Addon data

	var $minlvl;
	var $maxlvl;
	var $quality;
	var $quality_sql;

	// class constructor
	function roster_professionsSearch()
	{
		global $roster;

		require_once (ROSTER_LIB . 'recipes.php');

		$this->open_table = '<tr><th class="membersHeader ts_string">' . $roster->locale->act['item'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['level'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['name'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['type'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['reagents'] . '</th>'
						  . '<th class="membersHeaderRight ts_string">' . $roster->locale->act['character'] . '</th></tr>';

		$this->minlvl = isset($_POST['recipe_minle']) ? (int)$_POST['recipe_minle'] : ( isset($_GET['recipe_minle']) ? (int)$_GET['recipe_minle'] : '' );
		$this->maxlvl = isset($_POST['recipe_maxle']) ? (int)$_POST['recipe_maxle'] : ( isset($_GET['recipe_maxle']) ? (int)$_GET['recipe_maxle'] : '' );
		$this->quality = isset($_POST['recipe_quality']) ? $_POST['recipe_quality'] : ( isset($_GET['recipe_quality']) ? $_GET['recipe_quality'] : array() );

		// Set up next/prev search link
		$this->search_url  = ( $this->minlvl != '' ? '&amp;recipe_minle=' . $this->minlvl : '' );
		$this->search_url .= ( $this->maxlvl != '' ? '&amp;recipe_maxle=' . $this->maxlvl : '' );

		// Assemble sql for item quality
		if( count($this->quality) > 0 )
		{
			$i = 0;
			$this->quality_sql = array();
			foreach( $this->quality as $color )
			{
				$this->quality_sql[] = "`recipes`.`item_color` = '$color'";
				$this->search_url .= '&amp;recipe_quality[' . $i++ . ']=' . $color;
			}
			$this->quality_sql = ' AND (' . implode(' OR ',$this->quality_sql) . ')';
		}

		$this->options = '
	<label for="recipe_minle">' . $roster->locale->act['level'] . ':</label>
	<input type="text" name="recipe_minle" id="recipe_minle" size="3" maxlength="3" value="' . $this->minlvl . '" /> -
	<input type="text" name="recipe_maxle" id="recipe_maxle" size="3" maxlength="3" value="' . $this->maxlvl . '" /><br />
	<label for="recipe_quality">' . $roster->locale->act['quality'] . ':</label><br />
	<select name="recipe_quality[]" id="recipe_quality" size="7" multiple="multiple">
		<option value="9d9d9d" style="color:#9d9d9d;"' . ( in_array('9d9d9d',$this->quality) ? ' selected="selected"' : '' ) . '>' . $roster->locale->act['poor'] . '</option>
		<option value="ffffff" style="color:#cecece;"' . ( in_array('ffffff',$this->quality) ? ' selected="selected"' : '' ) . '>' . $roster->locale->act['common'] . '</option>
		<option value="1eff00" style="color:#1eff00;"' . ( in_array('1eff00',$this->quality) ? ' selected="selected"' : '' ) . '>' . $roster->locale->act['uncommon'] . '</option>
		<option value="0070dd" style="color:#0070dd;"' . ( in_array('0070dd',$this->quality) ? ' selected="selected"' : '' ) . '>' . $roster->locale->act['rare'] . '</option>
		<option value="a335ee" style="color:#a335ee;"' . ( in_array('a335ee',$this->quality) ? ' selected="selected"' : '' ) . '>' . $roster->locale->act['epic'] . '</option>
		<option value="ff8800" style="color:#ff8800;"' . ( in_array('ff8800',$this->quality) ? ' selected="selected"' : '' ) . '>' . $roster->locale->act['legendary'] . '</option>
		<option value="e6cc80" style="color:#e6cc80;"' . ( in_array('ff8800',$this->quality) ? ' selected="selected"' : '' ) . '>' . $roster->locale->act['heirloom'] . '</option>
	</select>';
	}

	function search( $search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page*$limit;

		$sql = "SELECT `players`.`name`, `players`.`member_id`, `players`.`server`, `players`.`region`, `recipes`.*"
			 . " FROM `" . $roster->db->table('recipes') . "` AS recipes,`" . $roster->db->table('players') . "` AS players"
			 . " WHERE `recipes`.`member_id` = `players`.`member_id`"
				. " AND (`recipes`.`recipe_name` LIKE '%$search%' OR `recipes`.`recipe_tooltip` LIKE '%$search%')"
				. ( $this->minlvl != '' ? " AND `recipes`.`level` >= '$this->minlvl'" : '' )
				. ( $this->maxlvl != '' ? " AND `recipes`.`level` <= '$this->maxlvl'" : '' )
				. $this->quality_sql
			 . " ORDER BY `recipes`.`recipe_name` ASC, `recipes`.`recipe_type` ASC"
			 . ( $limit > 0 ? " LIMIT $first," . $limit : '' ) . ';';

		//calculating the search time
		$this->start_search = format_microtime();

		$result = $roster->db->query($sql);

		$this->stop_search = format_microtime();
		$this->time_search = $this->stop_search - $this->start_search;

		$nrows = $roster->db->num_rows($result);

		$x = ($limit > $nrows) ? $nrows : ($limit > 0 ? $limit : $nrows);
		if( $nrows > 0 && $limit > 0 )
		{
			while( $x > 0 )
			{
				$row = $roster->db->fetch($result);
				$icon = new recipe($row);

				$item['html'] = '<td class="SearchRowCell">' . $icon->out(true) . '</td>'
							  . '<td class="SearchRowCell">' . $icon->data['level'] . '</td>'
							  . '<td class="SearchRowCell"><span style="color:#' . $icon->data['item_color'] . '">[' . $icon->data['recipe_name'] . ']</span></td>'
							  . '<td class="SearchRowCell">' . $icon->data['skill_name'] . '<br />' . $icon->data['recipe_type'] . '</td>'
							  . '<td class="SearchRowCell">' . str_replace('<br>','<br />',$icon->data['reagents']) . '</td>'
							  . '<td class="SearchRowCellRight"><a href="' . makelink('char-info-recipes&amp;a=c:' . $row['member_id'] . '#' . strtolower(str_replace(' ','',$icon->data['skill_name']))) . '"><strong>' . $row['name'] . '</strong></a></td>';

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}
		else
		{
			$this->result_count = $nrows;
		}
		$roster->db->free_result($result);
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
