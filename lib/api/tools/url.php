<?php
// this classes only purpose is to build the url for the curl function
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
	
		$name = str_replace('+' , '%20' , urlencode($name));
		$server = str_replace('+' , '%20' , urlencode($server));


		switch ($class)
		{
			case 'character':
				// /api/wow/character/{realm}/{name}
				$q = $ui.'character/'.$server.'/'.$name.$fields['data'].'';
			break;
			case 'status':
				/*
				With that, an example URL and it’s payload would look like this:
				http://us.battle.net/api/wow/realm/status?realm=Medivh

				The following url would return the entire list of realms and their statuses:
				http://us.battle.net/api/wow/realm/status

				Multiple realms can also be specified:
				http://us.battle.net/api/wow/realm/status?realm=Medivh&realm=Blackrock 
				*/
				$q = $ui.'realm/status?'.$fields['data'].'';
			break;
			case 'guild':
				// /api/wow/guild/{realm}/{name}
				$q = $ui.'guild/'.$server.'/'.$name.'/'.$fields['data'].'';
			break;
			case 'team':
				// /api/wow/arena/{realm}/{size}/{name} (size being 2v2, 3v3 or 5v5)
				$q = $ui.'arena/'.$server.'/'.$field['data'].'/'.$name.'';
			break;
			case 'talents':
				// http://us.battle.net/wow/talents/class/3?jsonp=Talents
				$q = $ui.'talents/class/'.$name.'?jsonp=';
			break;
			
			default:
			break;
		}
	
		//echo '<br>---- ['.$class.'] --| '.$q.' |--<br>';
		return $q;
	}




}