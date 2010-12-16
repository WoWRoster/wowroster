<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Recipe class and functions
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage Recipe
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Recipe class and functions
 *
 * @package    WoWRoster
 * @subpackage Recipe
 */
class recipe
{
	var $data;
	var $quality_id;
	var $quality;
	var $lang;

	function recipe( $data )
	{
		global $roster, $char;

		$this->data = $data;
		$this->_setQuality($this->data['item_color']);

		$this->lang = ( !is_object($char) ? $roster->config['locale'] : $char->data['clientLocale'] );
	}

	/**
	 * Sets the $quality and $quality_id property
	 * Takes the color of the item
	 *
	 * @param string $color | color of item
	 */
	function _setQuality( $color )
	{
		switch( strtolower( $color ) )
		{
			case 'e6cc80':
				$this->quality_id = '7';
				$this->quality = 'heirloom';
				break;
			case 'ff8800':
				$this->quality_id = '6';
				$this->quality = 'legendary';
				break;
			case 'a335ee':
				$this->quality_id = '5';
				$this->quality = 'epic';
				break;
			case '0070dd':
				$this->quality_id = '4';
				$this->quality = 'rare';
				break;
			case '1eff00':
				$this->quality_id = '3';
				$this->quality = 'uncommon';
				break;
			case 'ffffff':
				$this->quality_id = '2';
				$this->quality = 'common';
				break;
			case '9d9d9d':
				$this->quality_id = '1';
				$this->quality = 'poor';
				break;
			default:
				$this->quality_id = '0';
				$this->quality = 'none';
				break;
		}
	}

	// TPL data the easy way
	function tpl_get_icon()
	{
		global $roster;

		return $roster->config['interface_url'] . 'Interface/Icons/' . $this->data['recipe_texture'] . '.' . $roster->config['img_suffix'];
	}

	// TPL data the easy way
	function tpl_get_tooltip()
	{
		return makeOverlib($this->data['recipe_tooltip'],'',$this->data['item_color'],0,$this->lang);
	}

	// TPL data the easy way
	function tpl_get_itemlink()
	{
		global $roster, $tooltips;

		// Item links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';

		foreach( $roster->locale->wordings[$this->lang]['data_links'] as $key => $ilink )
		{
			$linktip .= '<a href="' . $ilink . urlencode(utf8_decode($this->data['recipe_name'])) . '" target="_blank">' . $key . '</a><br />';
		}
		setTooltip($num_of_tips, $linktip);
		setTooltip('itemlink', $roster->locale->wordings[$this->lang]['data_search']);

		$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		return $linktip;
	}

	function out( $small = false )
	{
		$output = '<div class="item' . ($small ? '-sm' : '') . '">
	<img src="' . $this->tpl_get_icon() . '" alt="" />
	<div class="mask ' . $this->quality . '" ' . $this->tpl_get_tooltip() . '' . $this->tpl_get_itemlink() . '></div>
</div>
';
		return $output;
	}
}

function recipe_get_regents( $member_id )
{
	global $roster;

	$query = "SELECT * FROM `" . $roster->db->table('recipes_reagents') . "` ORDER BY `reagent_id` desc;";

	$result = $roster->db->query($query);
	$regents = array();
	while( $data = $roster->db->fetch($result) )
	{
		$regent = new recipe($data);
		$regents[] = $regent;
	}
	return $regents;
}


function recipe_get_many( $member_id , $search , $sort )
{
	global $roster;

	$query= "SELECT * FROM `" . $roster->db->table('recipes') . "` WHERE `member_id` = '$member_id'" . ($search==''?'':" AND (`recipe_tooltip` LIKE '%" . $search . "%' OR `recipe_name` LIKE '%" . $search . "%')");

	switch( $sort )
	{
		case 'item':
			$query .= " ORDER BY `skill_name` ASC , `difficulty` DESC , `recipe_type` ASC , `recipe_name` ASC";
			break;

		case 'difficulty':
			$query .= " ORDER BY `skill_name` ASC , `difficulty` DESC , `recipe_type` ASC , `recipe_name` ASC";
			break;

		case 'name':
			$query .= " ORDER BY `skill_name` ASC , `recipe_name` ASC , `recipe_type` ASC , `difficulty` DESC";
			break;

		case 'level':
			$query .= " ORDER BY `skill_name` ASC , `level` ASC , `difficulty` DESC , `recipe_name` ASC";
			break;

		case 'type':
			$query .= " ORDER BY `skill_name` ASC , `recipe_type` ASC , `difficulty` DESC , `recipe_name` ASC";
			break;

		case 'reagents':
			$query .= " ORDER BY `skill_name` ASC , `reagents` ASC , `recipe_name` ASC , `recipe_type` ASC , `difficulty` DESC";
			break;

		case 'level':
			$query .= " ORDER BY `level` DESC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		default:
			$query .= " ORDER BY `skill_name` ASC , `difficulty` DESC , `recipe_type` ASC , `recipe_name` ASC";
			break;
	}

	$result = $roster->db->query($query);
	$recipes = array();
	while( $data = $roster->db->fetch($result) )
	{
		$recipe = new recipe($data);
		$recipes[] = $recipe;
	}
	return $recipes;
}


function recipe_get_all( $skill_name , $search , $sort )
{
	global $roster;

	$query= "SELECT DISTINCT `recipe_tooltip`, `recipe_name`, `recipe_type`, `item_color`, `skill_name`, `reagents`, `recipe_texture`, `level`, 1 `difficulty` FROM `" . $roster->db->table('recipes') . "` WHERE `skill_name` = '$skill_name' " . ($search==''?'':" AND (`recipe_tooltip` LIKE '%" . $search . "%' OR `recipe_name` LIKE '%" . $search . "%')") . " GROUP BY `recipe_name`";

	switch ($sort)
	{
		case 'item':
			$query .= " ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
			break;

		case 'name':
			$query .= " ORDER BY  recipe_name ASC ,  recipe_type ASC , `level` DESC , `difficulty` DESC";
			break;

		case 'type':
			$query .= " ORDER BY  recipe_type ASC , `level` DESC , recipe_name ASC , `difficulty` DESC";
			break;

		case 'reagents':
			$query .= " ORDER BY  `reagents` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		case 'level':
			$query .= " ORDER BY `level` DESC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		default:
			$query .= " ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
			break;
	}

	$result = $roster->db->query($query);

	$recipes = array();
	while( $data = $roster->db->fetch($result) )
	{
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}
