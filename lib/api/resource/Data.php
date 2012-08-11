<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.2.0
 * @package    WoWRoster
 */
require_once 'Resource.php';

/**
 * Realm resource.
 *
 * @throws ResourceException If no methods are defined.
 */
class Data extends Resource {

	protected $region;
	
	protected $methods_allowed = array(
		'races',
		'classes',
		'item',
		'achievement',
		'gachievements',
		'quests',
		'itemtooltip',
		'itemSet',
		'recipe',
		'achievement',
	);

	
	public function getRacesInfo() 
	{
		
			$data = $this->consume('races', array(
			'data' => '',
			'dataa' => 'races',
			'server' => '',
			'name' => '',
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		return $data;
	}
	public function getQuestInfo($id) 
	{
		
			$data = $this->consume('quests', array(
			'data' => '',
			'dataa' => $id.'-quests',
			'server' => '',
			'name' => $id,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		return $data;
	}
	
	public function getClassesInfo() 
	{
		
			$data = $this->consume('classes', array(
			'data' => '',
			'dataa' => '',
			'server' => '',
			'name' => $class,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));

		return $data;
	}
	
	public function getItemInfo($itemID,$gem0=null,$gem1=null,$gem2=null,$enchant=null,$es=false) 
	{
		$item = $this->CacheCheck($itemID);
		if (empty($itemID))
		{
			throw new ResourceException('No Item ID given Given.');
		} 
		else if (is_array($item))
		{
			$data = $item;
		}
		else
		{
			
			$data = $this->consume('item', array(
			'data' => '',
			'dataa' => $itemID.'',
			'server' => '',
			'name' => $itemID,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
			if ($data['itemClass'] == '3')
			{
				$this->InsertGCache($data);
			}
			else
			{
				$this->InsertICache($data);
			}
		}
		return $data;
	}
	
	public function getGuildAchieInfo() {
		
			$data = $this->consume('gachievements', array(
			'data' => '',
			'dataa' => $achiID.'-achiv',
			'server' => '',
			'name' => $achiID,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		return $data;
	}
	
	
	public function getRecipe($id) {
		
			$data = $this->consume('recipe', array(
			'data' => '',
			'server' => '',
			'name' => $id,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		return $data;
	}
	public function getAchievement($id) {
		
			$data = $this->consume('achievement', array(
			'data' => '',
			'server' => '',
			'name' => $id,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		return $data;
	}
		
	public function getItemSet($set) {
		
			$data = $this->consume('itemSet', array(
			'data' => '',
			'server' => '',
			'name' => $set,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		return $data;
	}

	public function getAchievInfo() {
		
			$data = $this->consume('achievement', array(
			'data' => '',
			'dataa' => $achiID.'-achiv',
			'server' => '',
			'name' => $achiID,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		return $data;
	}
	
	public function InsertICache($data)
	{
		global $roster, $update;
		
		$tooltip = $roster->api->Item->item($data,null,null);
		require_once (ROSTER_LIB . 'update.lib.php');
		$update = new update();
		$update->reset_values();
		$update->add_value('item_name' , $data['name']);
		$update->add_value('item_color' , $this->_setQualityc( $data['quality'] ));
		$update->add_value('item_id' , ''.$data['id'].'');
		$update->add_value('item_texture' , $data['icon']);
		$update->add_value('item_rarity' , $data['quality']);
		$update->add_value('item_tooltip' , $tooltip);
		$update->add_value('item_type' , $roster->api->Item->itemclass[$data['itemClass']]);
		$update->add_value('item_subtype' , $roster->api->Item->itemSubClass[$data['itemClass']][$data['itemSubClass']]);
		$update->add_value('level' , $data['requiredLevel']);
		$update->add_value('item_level' , $data['itemLevel']);
		$update->add_value('locale' , $roster->config['api_url_locale']);
		$update->add_value('timestamp' , time() );
		$update->add_value('json' ,json_encode($data, true));
		$querystr = "INSERT INTO `" .$roster->db->table('api_items') . "` SET " . $update->assignstr;
		$result = $roster->db->query($querystr);
	}
	public function InsertGCache($data)
	{
		global $roster, $update;
		$tooltip = $roster->api->Item->item($data,null,null);
		$tooltip = str_replace('<br /><br />', "<br />", $tooltip);

		require_once (ROSTER_LIB . 'update.lib.php');
		$update = new update();
		$update->reset_values();
		$update->add_value('gem_id' , $data['id'] );
		$update->add_value('gem_name' , $data['name'] );
		$update->add_value('gem_color' , strtolower($data['gemInfo']['type']['type']) );
		$update->add_value('gem_tooltip' , $tooltip );
		$update->add_value('gem_texture' , $data['icon'] );
		$update->add_value('gem_bonus' , $data['gemInfo']['bonus']['name'] );
		$update->add_value('locale' , $roster->config['api_url_locale']);
		$update->add_value('timestamp' , time() );
		$update->add_value('json' ,json_encode($data, true));
		$querystr = "INSERT INTO `" .$roster->db->table('api_gems') . "` SET " . $update->assignstr;
		$result = $roster->db->query($querystr);
	}
	public function CacheCheck($id)
	{
		global $roster;
		
		$sql = "SELECT * FROM `" .$roster->db->table('api_items') . "` WHERE `item_id` = '".$id."' ";
		$result = $roster->db->query($sql);
		if ($roster->db->num_rows($result) == 0)
		{
			$sqlg = "SELECT * FROM `" .$roster->db->table('api_gems') . "` WHERE `gem_id` = '".$id."' ";
			$resultg = $roster->db->query($sqlg);
			if ($roster->db->num_rows($resultg) == 0)
			{
				return false;
			}
			else
			{
				$rowg = $roster->db->fetch($resultg);
				return json_decode($rowg['json'], true);
			}
			
		}
		else
		{
			$row = $roster->db->fetch($result);
			return json_decode($row['json'], true);
		}
		
		return false;
		
	}
	
	
	
	public function _setQualityc( $color )
	{
		$ret = '';
		switch ($color) {
			case 5: $ret = "ff8000"; //Orange
				break;
			case 4: $ret = "a335ee"; //Purple
				break;
			case 3: $ret = "0070dd"; //Blue
				break;
			case 2: $ret = "1eff00"; //Green
				break;
			case 1: $ret = "ffffff"; //White
				break;
			default: $ret = "9d9d9d"; //Grey
				break;
		}
		return $ret;
	}
	
}
