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
		'quests',
		'itemtooltip',
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
	
	public function getItemInfo($itemID,$gem0=null,$gem1=null,$gem2=null,$enchant=null,$es=false) {
		
		if (empty($itemID))
		{
			throw new ResourceException('No Item ID given Given.');
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
	
	
}
