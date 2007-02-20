<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

class AltMonitorUpdate
{
	// Update messages
	var $messages;
	
	// Character data cache
	var $chars = array();
	
	// Doesn't do anything at the moment
	function guild_pre($guild)
	{
		global $wowdb, $roster_conf, $addon_conf, $wordings;
	}
	
	// The regex-based alt detection
	function guild($member_id, $member_name, $char)
	{
		global $wowdb, $roster_conf, $addon_conf, $wordings;
		
		// --[ Fetch full member data ]--
		$query =
			"SELECT * ".
			"FROM `".ROSTER_ALT_TABLE."` ".
			"WHERE `member_id`=".$member_id;

		$result = $wowdb->query( $query );

		if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

		if ( $row = $wowdb->fetch_array( $result )) {
			// Check manual record
			if ( $row['alt_type'] & 0xC ) {
				$wowdb->free_result( $result );
				$this->messages .= " - <span style='color:yellow;'>Manual or per-character entry</span>\n";
				return '';
			}
			else
			{
				$wowdb->free_result( $result );
			}
		}

		// --[ Use regex to parse the main name ]--
		if(preg_match($addon_conf['AltMonitor']['getmain_regex'],$char[$addon_conf['AltMonitor']['getmain_field']], $regs))
		{
			$main_name = $regs[$addon_conf['AltMonitor']['getmain_match']]; // We have a regex result.
		}
		else if($addon_conf['AltMonitor']['defmain'])
		{
			$main_name = $member_name;			// No regex result; assume the character is a main
		}
		else
		{
			$main_name = '';				// No regex result; assume the character is mainless alt
		}

		$this->messages .= " - <span style='color:green;'>Main: $main_name</span>\n";

		// If the main name is equal to this config field then this char is a main, and we should set the $main_name accordingly
		if($main_name == $addon_conf['AltMonitor']['getmain_main'])
		{
			$main_name = $member_name;
		}


		// --[ Get the main's member ID. We handle the 2 easy cases (Main and mainless alt) first ]--
		if ( $main_name == $member_name ) {
			$this->messages .= " - <span style='color:green;'>Main</span>\n";
			$main_id = $member_id;

			// --[ Look up if there are alts for this main ]--
			$query =
				"SELECT COUNT(member_id) ".
				" FROM `".ROSTER_ALT_TABLE."` as `alts`".
				" WHERE `alts`.`main_id`=".$member_id;

			$result = $wowdb->query( $query );

			if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

			$row = mysql_fetch_array( $result );

			if ($row[0] == 1) {
				$alt_type = ALTMONITOR_MAIN_NO_ALTS;
				$this->messages .= " - <span style='color:green;'>No alts</span>\n";
			}
			else {
				$alt_type = ALTMONITOR_MAIN_ALTS;
				$this->messages .= " - <span style='color:green;'>With alts</span>\n";
			}
		}
		elseif ( $main_name == '' ) {
			$this->messages .= " - <span style='color:red;'>Mainless alt</span>\n";
			$main_id = 0;
			$alt_type = ALTMONITOR_ALT_NO_MAIN;
		}
		else {
			$this->messages .= " - <span style='color:green;'>Alt of $main_name</span>\n";
			// --[ Get the main's member ID ]--
			$query =
				"SELECT `members`.`member_id`, `members`.`name`".
				" FROM `".ROSTER_MEMBERSTABLE."` as `members`".
				" WHERE `members`.`name`='".$main_name."'";

			if ( $roster_conf['sqldebug'] ) echo "<!--$query-->\n";

			$result = $wowdb->query( $query );

			if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

			if ( $row = $wowdb->fetch_array( $result )) {
				$main_id = $row['member_id'];
				$wowdb->free_result( $result );

				// --[ Alt of alt check ]--
				if ( $addon_conf['AltMonitor']['altofalt'] == 'leave' ) {
					$alt_type = ALTMONITOR_ALT_WITH_MAIN;	// Don't check if we're allowing alt of alt in the database
				}
				else {
					$query =			// Lookup main's alt_type
						"SELECT `member_id`, `main_id`, `alt_type`".
						" FROM `".ROSTER_ALT_TABLE."`".
						" WHERE `member_id`=".$main_id;

					if ( $roster_conf['sqldebug'] ) echo "<!--$query-->\n";

					$result = $wowdb->query( $query );

					if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

					if ( $row = $wowdb->fetch_array( $result )) {
						if ( ( $row['alt_type'] & 2 ) == 0 ) { // Alt of main
							$alt_type = ALTMONITOR_ALT_WITH_MAIN;
							$this->messages .= " - <span style='color:green;'>Alt of Main</span>\n";
						}
						elseif ( $addon_conf['AltMonitor']['altofalt'] == 'main' ) {
							// The main is an alt so the member is being made a main
							$this->messages .= " - <span style='color:red;'>Alt of Alt</span>\n";

							$main_id = $member_id;

							// --[ Look up if there are alts for this main ]--
							$query =
								"SELECT COUNT(member_id) ".
								" FROM `".ROSTER_ALT_TABLE."` as `alts`".
								" WHERE `alts`.`main_id`=".$member_id;

							$result = $wowdb->query( $query );

							if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

							$row = $wowdb->fetch_array( $result );

							if ($row[0] == 1) {
								$alt_type = ALTMONITOR_MAIN_NO_ALTS;
								$this->messages .= " - <span style='color:red;'>Main without alts</span>\n";
							}
							else {
								$alt_type = ALTMONITOR_MAIN_ALTS;
								$this->messages .= " - <span style='color:red;'>Main with alts </span>\n";
							}
						}
						elseif ( $addon_conf['AltMonitor']['altofalt'] == 'alt' ) {
							$main_id = 0;
							$alt_type = ALTMONITOR_ALT_NO_MAIN;
							$this->messages .= " - <span style='color:red;'>Alt of Alt</span>\n";
							$this->messages .= " - <span style='color:red;'>Mainless alt</span>\n";
						}
						else {
							$alt_type = $row['alt_type'] & 3; // don't accidentically set this to manual
							$main_id = $row['main_id'];
						}
					}
					else {
						$this->messages .= " - <span style='color:red;'>Mainless alt</span>\n";
						$alt_type = ALTMONITOR_ALT_NO_MAIN;
					}
				}
			}
			else
			{
				if($addon_conf['AltMonitor']['invmain'])
				{
					// --[ Main name invalid, so we're making this a main ]--
					$main_id = $member_id;

					// --[ Look up if there are alts for this main ]--
					$query =
						"SELECT COUNT(member_id) ".
						" FROM `".ROSTER_ALT_TABLE."` as `alts`".
						" WHERE `alts`.`main_id`=".$member_id;

					$result = $wowdb->query( $query );

					if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

					$row = $wowdb->fetch_array( $result );

					if ($row[0] == 1) {
						$alt_type = ALTMONITOR_MAIN_NO_ALTS;
						$this->messages .= "<span style='color:red;'>$member_name has invalid main name $main_name, so we're making him a main. He doesn't have alts. </span>\n";
					}
					else {
						$alt_type = ALTMONITOR_MAIN_ALTS;
						$this->messages .= "<span style='color:red;'>$member_name has invalid main name $main_name, so we're making him a main. He has alts. </span>\n";
					}
				}
				else
				{
					$main_id = 0;			// Invalid regex result; assume the character is mainless alt
					$alt_type = ALTMONITOR_ALT_NO_MAIN;
				}

				$wowdb->free_result( $result );
			}
		}


		// -[ Start DB update code ]-

		$query = "SELECT `member_id` FROM `".ROSTER_ALT_TABLE."` WHERE `member_id`='$member_id'";

		$result = $wowdb->query( $query );

		if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

		$update = $wowdb->num_rows( $result ) == 1;
		$wowdb->free_result($result);

		$wowdb->reset_values();

		$wowdb->add_value( 'member_id', $member_id );

		$wowdb->add_value( 'main_id', $main_id );

		$wowdb->add_value( 'alt_type', $alt_type );

		if( $update )
			$querystr = "UPDATE `".ROSTER_ALT_TABLE."` SET ".$wowdb->assignstr." WHERE `member_id` = '$member_id'";
		else
			$querystr = "INSERT INTO `".ROSTER_ALT_TABLE."` SET ".$wowdb->assignstr;

		$result = $wowdb->query( $querystr );

		if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }
		
		return '';
	}

	// Throwing away the old records
	function guild_post($guild)
	{
		global $wowdb, $roster_conf, $addon_conf, $wordings;

		$query = "DELETE `".ROSTER_ALT_TABLE."` ".
			"FROM `".ROSTER_ALT_TABLE."` ".
			"LEFT JOIN `".ROSTER_MEMBERSTABLE."` USING (`member_id`) ".
			"WHERE `".ROSTER_MEMBERSTABLE."`.`member_id` IS NULL ";

		if( $wowdb->query($query) )
		{
			$this->messages .= ' - '.$wowdb->affected_rows().' records without matching member records deleted';
		}
		else
		{
			return('Old records not deleted. MySQL said: '.$wowdb->error());
		}
	}
	
	// Doesn't do anything at the moment
	function char_pre($chars)
	{
		global $wowdb, $roster_conf, $addon_conf, $wordings;
		
	}
	
	// Add the member record to the local data array
	function char($member_id, $member_name, $char)
	{
		global $wowdb, $roster_conf, $addon_conf, $wordings;
		
		// --[ Fetch full member data ]--
		$query =
			"SELECT * ".
			"FROM `".ROSTER_ALT_TABLE."` ".
			"WHERE `member_id`=".$member_id;

		$result = $wowdb->query( $query );

		if ( !$result ) { return("$member_name not updated, failed at line ".__LINE__); }

		if ( $row = $wowdb->fetch_array( $result )) {
			// Check manual record
			if ( $row['alt_type'] & 0x8 ) {
				$wowdb->free_result( $result );
				$this->messages .= " - <span style='color:yellow;'>Manual entry</span>\n";
				return '';
			}
			else
			{
				$wowdb->free_result( $result );
			}
		}
		else
			$wowdb->free_result( $result );

		// --[ Add record to the cache of chars we'll be updating ]--
		$this->chars[$member_id] = $char;
		$this->chars[$member_id]['main_id'] = $row['main_id'];
		$this->chars[$member_id]['alt_type'] = $row['alt_type'];
		
		return '';
	}
	
	// Does the actual update.
	function char_post($chars)
	{
		global $wowdb, $roster_conf, $addon_conf, $wordings;
	
		if( empty($this->chars) ) { return ''; }
		
		// Decide upon a main: Highest leveled among those with highest guild rank
		$maxrank = 11;
		$maxlevel = 0;
		
		foreach($this->chars as $char)
		{
			if( $char['Rank'] < $maxrank )
			{
				$maxrank = $char['Rank'];
			}
		}
		
		foreach($this->chars as $member_id => $char)
		{
			if( $char['Rank'] == $maxrank && $char['Level'] > $maxlevel )
			{
				$maxlevel = $char['Level'];
				$mainid = $member_id;
			}
		}
		
		// And the update code
		$inclause = implode(',',array_diff(array_keys($this->chars),array($mainid)));
		
		if( empty($inclause) )
		{
			$query = "UPDATE `".ROSTER_ALT_TABLE."` SET `main_id` = '".$mainid."', `alt_type` = '".ALTMONITOR_MAIN_MANUAL_NO_ALTS."' WHERE `member_id` = '".$mainid."'";
			if( $wowdb->query($query) )
			{
				$this->messages .= ' - '.$this->chars[$mainid]['name'].' written as main without alts';
			}
	
		}
		else
		{
			$query = "UPDATE `".ROSTER_ALT_TABLE."` SET `main_id` = '".$mainid."', `alt_type` = '".ALTMONITOR_ALT_MANUAL_WITH_MAIN."' WHERE `member_id` IN (".$inclause.")";
			if( $wowdb->query($query) )
			{
				$this->messages .= ' - '.$wowdb->affected_rows().' alts added to main '.$this->chars[$mainid]['name'];
			}
			else
			{
				return('Alts not written. MySQL said: '.$wowdb->error());
			}
			
			$query = "UPDATE `".ROSTER_ALT_TABLE."` SET `main_id` = '".$mainid."', `alt_type` = '".ALTMONITOR_MAIN_MANUAL_WITH_ALTS."' WHERE `member_id` = '".$mainid."'";
			if( $wowdb->query($query) )
			{
				$this->messages .= ' - Main written';
			}
			else
			{
				return('Main not written. MySQL said: '.$wowdb->error());
			}
		}
		
		return '';
	}
}

$GLOBALS['AltMonitorUpdate'] = new AltMonitorUpdate;

?>
