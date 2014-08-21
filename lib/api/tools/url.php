<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license	http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version	SVN: $Id$
 * @link	   http://www.wowroster.net
 * @since	  File available since Release 2.2.0
 * @package	WoWRoster
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
			case 'status':
				$q = 'api/wow/realm/status?'.$fields['data'].'';
			break;
			case 'guild':
				$q = 'api/wow/guild/'.$server.'/'.$name . $fields['data'].'&'.$local;
			break;
			case 'team':
				$q = 'api/wow/arena/'.$fields['server'].'/'.$fields['size'].'/'.$fields['name'].'?'.$local;
			break;
			
			case 'item':
				$q = 'api/wow/item/'.$name.'?'.$local;
			break;
			
			case 'itemClass':
				$q = 'api/wow/data/item/classes?'.$local;
			break;
			
			case 'itemSet':
				$q = 'api/wow/item/set/'.$name.'?'.$local;
			break;
			
			case 'recipe':
				$q = 'api/wow/recipe/'.$name.'?'.$local;
			break;
			
			case 'achievement':
				$q = 'api/wow/achievement/'.$name.'?'.$local;
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

			case 'achievements':
				$q = 'api/wow/data/character/achievements?'.$local;
			break;
			
			case 'quests':
				$q = '/api/wow/quest/'.$name.'?'.$local;
			break;
			
			case 'ladder':
				$q = '/api/wow/leaderboard/'.$fields['size'].'?'.$local;
			break;
			
			case 'talent':
				$q = '/api/wow/data/talents?'.$local;
			break;

			case 'abilities':
				$q = '/api/wow/battlePet/ability/'.$name.'?'.$local;
			break;

			case 'species':
				$q = '/api/wow/battlePet/species/'.$name.'?'.$local;
			break;

			case 'stats':
				$q = '/api/wow/battlePet/stats/'.$name.'?'.$local;
			break;
			
			case 'spell':
				$q = '/api/wow/spell/'.$name.'?'.$local;
			break;
			
			case 'challenge':
				$q = '/api/wow/challenge/'.$name.'?'.$local;
			break;
			
			case 'auction':
				$q = 'api/wow/auction/data/'.$fields['server'];
			break;
			
			
			default:
			break;
		}

		return $ui.$q;
	}




}