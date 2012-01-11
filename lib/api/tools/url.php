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

class url {

	/**********************************************
	*	This function builds the urls for the function calls
	* @params
	* $ui - the url for the api ie us.battle.net/api/wow/
	* $class - the type of data being collected
	* $server - the name of the server the info it comming from
	* $name - iter character guild or team name
	* $fields - extra data used for additional info multiple realm names
	*			character event calls and team size
	**********************************************/
	public function BuildUrl($ui,$class,$server,$name,$fields)
	{
		global $roster;
		$name = str_replace('+' , '%20' , urlencode($name));
		$server = str_replace('+' , '%20' , urlencode($server));
		$local = 'locale='.$roster->config['api_url_locale'];

		switch ($class)
		{
			case 'character':
				$q = 'api/wow/character/'.$server.'/'.$name.$fields['data'].'&'.$local;
			break;
			case 'cfeed':
			////http://us.battle.net/wow/en/character/zangarmarsh/Ulminia/feed
				$q = 'wow/en/character/'.$server.'/'.$name.'/feed/';
			break;
			case 'status':
				$q = 'api/wow/realm/status?'.$fields['data'].'';
			break;
			case 'guild':
				$q = 'api/wow/guild/'.$server.'/'.$name . $fields['data'].'&'.$local;
			break;
			case 'team':
				$q = 'api/wow/arena/'.$field['server'].'/'.$field['size'].'/'.$field['name'].'?'.$local;
			break;
			
			case 'item':
				$q = 'api/wow/item/'.$name.'?'.$local;
			break;
			
			case 'gperks':
				$q = 'api/wow/data/guild/perks?'.$local;
			break;
			
			case 'gachievements':
				$q = 'api/wow/data/guild/achievements?'.$local;
			break;
			case 'grewards':
				$q = 'api/wow/data/guild/rewards?'.$local;
			
			case 'races':
				$q = 'api/wow/data/character/races?'.$local;
			break;

			case 'achievement':
				$q = 'api/wow/data/character/achievements?'.$local;
			break;
			
			case 'quests':
				$q = '/api/wow/quest/'.$name.'?'.$local;
			break;
			
			case 'ladder':
				$q = '/api/wow/pvp/arena/'.$field['server'].'/'.$field['size'].'?'.$local;
			break;
			case 'talents':
				// http://us.battle.net/wow/talents/class/3?jsonp=Talents
				if ($name =='0')
				{
				$q = 'wow/talents/pet?jsonp=?'.$local;
				}
				else
				{
				$q = 'wow/talents/class/'.$name.'?jsonp=?'.$local;
				}
			break;
			
			default:
			break;
		}
		return $ui.$q;
	}




}