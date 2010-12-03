<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Vault
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Vault Search
 *
 * @package    Vault
 * @subpackage Search
 */
class vaultSearch
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
	function vaultSearch()
	{
		global $roster;

		require_once (ROSTER_LIB . 'item.php');

		$this->open_table = '<tr><th class="membersHeader ts_string">' . $roster->locale->act['item'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['level'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['name'] . '</th>'
						  . '<th class="membersHeaderRight ts_string">' . $roster->locale->act['page'] . '</th></tr>';

		$this->minlvl = isset($_POST['vault_minle']) ? (int)$_POST['vault_minle'] : ( isset($_GET['vault_minle']) ? (int)$_GET['vault_minle'] : '' );
		$this->maxlvl = isset($_POST['vault_maxle']) ? (int)$_POST['vault_maxle'] : ( isset($_GET['vault_maxle']) ? (int)$_GET['vault_maxle'] : '' );
		$this->quality = isset($_POST['vault_quality']) ? $_POST['vault_quality'] : ( isset($_GET['vault_quality']) ? $_GET['vault_quality'] : array() );

		// Set up next/prev search link
		$this->search_url  = ( $this->minlvl != '' ? '&amp;vault_minle=' . $this->minlvl : '' );
		$this->search_url .= ( $this->maxlvl != '' ? '&amp;vault_maxle=' . $this->maxlvl : '' );

		// Assemble sql for item quality
		if( count($this->quality) > 0 )
		{
			$i = 0;
			$this->quality_sql = array();
			foreach( $this->quality as $color )
			{
				$this->quality_sql[] = "`item_color` = '$color'";
				$this->search_url .= '&amp;vault_quality[' . $i++ . ']=' . $color;
			}
			$this->quality_sql = ' AND (' . implode(' OR ',$this->quality_sql) . ')';
		}

		//advanced options for searching items
		$this->options = '
	<label for="vault_minle">' . $roster->locale->act['level'] . ':</label>
	<input type="text" name="vault_minle" id="vault_minle" size="3" maxlength="3" value="' . $this->minlvl . '" /> -
	<input type="text" name="vault_maxle" id="vault_maxle" size="3" maxlength="3" value="' . $this->maxlvl . '" /><br />
	<label for="vault_quality">Quality:</label><br />
	<select name="vault_quality[]" id="vault_quality" size="6" multiple="multiple">
		<option value="9d9d9d" style="color:#9d9d9d;"' . ( in_array('9d9d9d',$this->quality) ? ' selected="selected"' : '' ) . '>Poor</option>
		<option value="ffffff" style="color:#ffffff;"' . ( in_array('ffffff',$this->quality) ? ' selected="selected"' : '' ) . '>Common</option>
		<option value="1eff00" style="color:#1eff00;"' . ( in_array('1eff00',$this->quality) ? ' selected="selected"' : '' ) . '>Uncommon</option>
		<option value="0070dd" style="color:#0070dd;"' . ( in_array('0070dd',$this->quality) ? ' selected="selected"' : '' ) . '>Rare</option>
		<option value="a335ee" style="color:#a335ee;"' . ( in_array('a335ee',$this->quality) ? ' selected="selected"' : '' ) . '>Epic</option>
		<option value="ff8800" style="color:#ff8800;"' . ( in_array('ff8800',$this->quality) ? ' selected="selected"' : '' ) . '>Legendary</option>
	</select>';
	}

	function search( $search , $limit=10 , $page=0 )
	{
		global $roster;

		include_once($this->data['inc_dir'] . 'vault_item.php');

		// Get all the vault page names first
		$sql = "SELECT `guild_id`, `item_slot`, `item_name`, `item_texture` FROM `" . $roster->db->table('addons_vault_items') . "` WHERE `item_parent` = 'vault';";   
		$result = $roster->db->query($sql);
		$x = $roster->db->num_rows($result);

		$tab_name = array();
		while( $x > 0 )
		{ 
			$row = $roster->db->fetch($result);
			$tab_name[$row['guild_id']][$row['item_slot']] = array(
				'name' => $row['item_name'],
				'icon' => $row['item_texture']
				);
			$x--;
		}
		$roster->db->free_result($result);

		// Search the items
		$first = $page * $limit;

		$sql = "SELECT *, SUM(item_quantity) AS total_quantity"
			 . " FROM `" . $roster->db->table('addons_vault_items') . "`"
			 . " WHERE (`item_parent` LIKE 'Tab%') AND (`item_name` LIKE '%$search%' OR `item_tooltip` LIKE '%$search%')"
				. ( $this->minlvl != '' ? " AND `level` >= '$this->minlvl'" : '' )
				. ( $this->maxlvl != '' ? " AND `level` <= '$this->maxlvl'" : '' )
				. $this->quality_sql
			 . " GROUP BY `item_name`"
			 . ( $limit > 0 ? " LIMIT $first," . $limit : '' ) . ';';

		// calculating the search time
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
				$row['item_quantity'] = $row['total_quantity']; // Totals quantity, found on all pages
				$icon = new VaultItem($row, false);

				$item['html'] = '<td class="SearchRowCell">' . $icon->out() . '</td>'
							  . '<td class="SearchRowCell">' . $icon->requires_level . '</td>'
							  . '<td class="SearchRowCell"><span style="color:#' . $icon->color . '">[' . $icon->name . ']</span></td>'
							  . '<td class="SearchRowCellRight"><a href="' . makelink('guild-vault&amp;a=g:' . $row['guild_id']) . '">'
							  . '<img src="' . $roster->config['interface_url'] . 'Interface/Icons/' . $tab_name[$row['guild_id']][$row['item_parent']]['icon'] . '.' . $roster->config['img_suffix'] . '" style="width:16px;height:16px;" alt="" /> '
							  . $tab_name[$row['guild_id']][$row['item_parent']]['name'] . '</a></td>';

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
