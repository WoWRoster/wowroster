<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 * @subpackage MemberList Plugins
 */

class guild_rep
{

	var $members_list_select;
	var $members_list_table;
	var $members_list_where = array();
	var $members_list_fields = array();
	
	public function __construct()
	{
		global $roster;
		$this->members_list_select = '`rep`.`curr_rep`, `rep`.`max_rep`, `rep`.`AtWar`, `rep`.`Standing`, `rep`.`name` AS \'repname\', IF( `rep`.`Standing` IS NULL OR `rep`.`Standing` = \'\', 1, 0 ) AS `repisnull`, ';

		$this->members_list_table = 'LEFT JOIN `'.$roster->db->table('reputation').'` AS rep ON `members`.`member_id` = `rep`.`member_id` ';

		$this->members_list_where['guild_rep'] = "`rep`.`name` = '".$roster->data['guild_name']."' OR `rep`.`name` IS NULL";

		$this->members_list_fields['guild_rep'] = array (
				'lang_field' => 'reputation',
				'value'      => 'guild_rep_function::guild_rep',
				'order'      => array( '`rep`.`max_rep` ASC','`rep`.`curr_rep` ASC' ),
				'order_d'    => array( '`rep`.`max_rep` DESC','`rep`.`curr_rep` DESC' ),
				'filter'     => false,
				'display'    => 5
			);
	}
}
abstract class guild_rep_function
{
	public function guild_rep ( $row, $field )
	{
		global $roster, $member_list_where;

		if (isset($row['curr_rep']))
		{
			$percentage = round(($row['curr_rep']/$row['max_rep'])*100);
			$toolTip = ' ['.$row['curr_rep'].' / '.$row['max_rep'].' ] ';
			$toolTiph = $row['Standing'];
			$tooltip = makeOverlib($toolTip,$toolTiph,'',2,'',',WRAP');
			$cell_value = '<div ' . $tooltip . ' style="cursor:default;"><div class="levelbarParent" style="width:70px;"><div class="levelbarChild">' . $row['Standing'] . '</div></div>';
			$cell_value .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="70">';
			$cell_value .= '<tr>';
			$cell_value .= '<td style="background-image: url(\'' . $roster->config['theme_path'] . '/images/bars/' . strtolower($row['Standing']).'.gif\');" width="' . $percentage . '%"><img src="' . $roster->config['img_url'] . 'pixel.gif" height="14" width="1" alt="" /></td>';
			$cell_value .= '<td width="' . (100 - $percentage) . '%"></td>';
			$cell_value .= "</tr>\n</table>\n</div>\n";
		
			return '<div style="display:none;">' . str_pad($row['Standing'],2,'0',STR_PAD_LEFT) . '</div>' . $cell_value;
		}
		else
		{
			$cell_value = '';//'<div ' . $tooltip . ' style="cursor:default;">' . $row['Standing'] . '</div>';
			return $cell_value;
		}
	}
}

	
?>