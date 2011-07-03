<?php

require_once 'resource/Realm.php';
require_once 'resource/Char.php';
require_once 'resource/Guild.php';
require_once 'resource/Talents.php';

class WowAPI {
	/**
	 * @var \Realm Realm object instance.
	 */
	public $Realm; // realm object
	public $Char; // char object
	public $Guild; // guild Object
	public $Team; // team Object
	public $Talents; // char name nyi
	public $realmn; // realm name nyi
	public $guildn; // guild name nyi
	
	//now to test and see if we have what we need
	
	public function __construct($region='us') {
		// Check for required extensions
		if (!function_exists('curl_init')) {
			throw new Exception('Curl is required for api usage.');
		}

		if (!function_exists('json_decode')) {
			throw new Exception('JSON PHP extension required for api usage.');
		}

		$this->Realm = new Realm($region);
		$this->Char = new character($region);
		$this->Guild = new guild($region);
		//$this->Team = new team($region);
		$this->Talents = new talents($region);
	}
	
	
}
//eof api.php
?>