<?php
$versions['versionDate']['wowdb'] = '$Date: 2006/01/26 09:42:59 $'; 
$versions['versionRev']['wowdb'] = '$Revision: 1.39 $'; 
$versions['versionAuthor']['wowdb'] = '$Author: anthonyb $';

class wowdb
{
	var $db;
	var $assignstr;
	var $sqldebug;

	function connect( $host, $user, $password, $name )
	{
		$db = mysql_connect($host, $user, $password) or die( "Could not connect to db" );
		mysql_select_db( $name ) or die( "could not select db" );
	}


	function query( $querystr )
	{
		$result = mysql_query($querystr) or die(mysql_error());
		return $result;
	}


	function getrow( $result )
	{
		return mysql_fetch_assoc( $result );
	}


	function escape( $string )
	{
		if( version_compare( phpversion(), '4.3.0', '>' ) )
			return mysql_real_escape_string( $string );
		else
			return mysql_escape_string( $string );
	}


	function closeDb()
	{
		// Closing connection
		mysql_close($db) or die(mysql_error());
	}


	function closeQuery($query)
	{
		// Free resultset
		mysql_free_result($query) or die(mysql_error());
	}


	function setSQLDebug($sqldebug)
	{
		$this->sqldebug = $sqldebug;
	}


##
# Updating code
##
	function reset_values()
	{
		$this->assignstr = '';
	}


	function add_value( $row_name, $row_data )
	{
		if( $this->assignstr != '' )
			$this->assignstr .= ',';

		if( ! is_numeric($row_data) )
			$row_data = "'" . $this->escape( $row_data ) . "'";

		$this->assignstr .= " `$row_name` = $row_data";
	}


	function add_time( $row_name, $date)
	{
		if( $this->assignstr != '' )
			$this->assignstr .= ',';

		// 01/01/2000 23:00:00.000
		$row_data = "'".$date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':00'."'";
		$this->assignstr .= " `$row_name` = $row_data";
	}


	function add_pvp2time( $row_name, $date)
	{
		if( $this->assignstr != '' )
			$this->assignstr .= ',';

		$date_str = strtotime($date);
		$p2newdate = date('Y-m-d G:i:s',$date_str);
		$row_data = "'".$p2newdate."'";
		$this->assignstr .= " `$row_name` = $row_data";
	}


	function insert_item( $item )
	{
		$this->reset_values();
		$this->add_value('member_id', $item['member_id'] );
		$this->add_value('item_name', $item['item_name'] );
		$this->add_value('item_parent', $item['item_parent'] );
		$this->add_value('item_slot', $item['item_slot'] );
		$this->add_value('item_color', $item['item_color'] );
		$this->add_value('item_id', $item['item_id'] );
		$this->add_value('item_texture', $item['item_texture'] );
		$this->add_value('item_tooltip', $item['item_tooltip'] );
		if( isset( $item['item_quantity'] ) )
			$this->add_value('item_quantity', $item['item_quantity'] );

		$querystr = "INSERT INTO `".ROSTER_ITEMSTABLE."` SET ".$this->assignstr;

		if ($this->sqldebug)
			$messages = "<!-- $querystr -->\n";

		mysql_query($querystr) or die(mysql_error());

		return $messages;
	}


	function insert_quest( $quest )
	{
		$this->reset_values();
		$this->add_value('member_id', $quest['member_id'] );
		$this->add_value('quest_name', $quest['quest_name'] );
		$this->add_value('quest_index', $quest['quest_index'] );
		$this->add_value('quest_level', $quest['quest_level'] );
		$this->add_value('zone', $quest['zone'] );
		$this->add_value('quest_tag', $quest['quest_tag'] );
		$this->add_value('is_complete', $quest['is_complete'] );
		$querystr = "INSERT INTO `".ROSTER_QUESTSTABLE."` SET ".$this->assignstr;
		if ($this->sqldebug)
			$messages = "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());

		return $messages;
	}


	function make_quest( $item_data, $member_id, $zone)
	{
		$item = array();
		$item['member_id'] = $member_id;
		//$item['quest_name'] = $item_data['Title'];
		//Fix quest name if too many 'quest' addons cause level number to be added to title
		$item['quest_name'] = preg_replace("/^(\[[[:digit:]]{1,2}(D|R|\+)?\] )?/","",$item_data['Title']);
		$item['quest_tag'] = $item_data['Tag'];
		$item['quest_index'] = 0;
		$item['quest_level'] = $item_data['Level'];
		$item['zone'] = $zone;
		if ($item_data['Complete'] == 'Complete')
			$item['is_complete'] = 1;

		return $item;
	}


	function tooltip( $tipdata )
	{
		$tooltip = '';
		if( !is_array( $tipdata ) )
			$tipdata = explode( '<br>', $tipdata );

		foreach( $tipdata as $tip )
		{
			$tooltip .= $tip."\n";
		}
		return $tooltip;
	}


	function make_item( $item_data, $member_id, $parent, $slot_name )
	{
		$item = array();
		$item['member_id'] = $member_id;
		$item['item_name'] = $item_data['Name'];
		$item['item_parent'] = $parent;
		$item['item_slot'] = $slot_name;
		$item['item_color'] = $item_data['Color'];
		$item['item_id'] = $item_data['Item'];
		$item['item_texture'] = $item_data['Texture'];
		if( isset( $item_data['Quantity'] ) )
			$item['item_quantity'] = $item_data['Quantity'];

		$item['item_tooltip'] = $this->tooltip( $item_data['Tooltip'] );
		return $item;
	}


	function insert_recipe( $recipe )
	{
		$this->reset_values();
		$this->add_value('member_id', $recipe['member_id'] );
		$this->add_value('recipe_name', $recipe['recipe_name'] );
		$this->add_value('recipe_type', $recipe['recipe_type'] );
		$this->add_value('skill_name', $recipe['skill_name'] );
		$this->add_value('difficulty', $recipe['difficulty'] );
		$this->add_value('reagents', $recipe['reagents'] );
		$this->add_value('recipe_texture', $recipe['recipe_texture'] );
		$this->add_value('recipe_tooltip', $recipe['recipe_tooltip'] );
		if (strpos($recipe['recipe_tooltip'], 'Requires Level')!=0) {
		$this->add_value('level', (rtrim(substr($recipe['recipe_tooltip'], strpos($recipe['recipe_tooltip'], 'Requires Level') + 15,2))) );
		}
		$querystr = "INSERT INTO `".ROSTER_RECIPESTABLE."` SET ".$this->assignstr;
		if ($this->sqldebug)
			$messages = "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());

		return $messages;
	}


	function make_recipe( $recipe_data, $member_id, $parent, $recipe_type, $recipe_name )
	{
		$recipe = array();
		$recipe['member_id'] = $member_id;
		$recipe['recipe_name'] = $recipe_name;
		$recipe['recipe_type'] = $recipe_type;
		$recipe['skill_name'] = $parent;
		$recipe['difficulty'] = $recipe_data['Difficulty'];
		$recipe['reagents'] = $recipe_data['Reagents'];
		$recipe['recipe_texture'] = $recipe_data['Texture'];
		$recipe['recipe_tooltip'] = $this->tooltip( $recipe_data['Tooltip'] );
		return $recipe;
	}


	function do_quests( $data, $member_id )
	{
		$messages .=  "* Updating: Quests<br />\n";

		//first delete the stale data
		$quests = $data['Quests'];
		if ($quests != NULL)
		{
			$querystr = "DELETE FROM `".ROSTER_QUESTSTABLE."` WHERE member_id = $member_id";
			mysql_query($querystr) or die(mysql_error());
		}
		if ($quests == NULL)
			$messages .=  "Quests are NULL, not removing old quests from database<br />\n";

		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";

		//then process quests
   	if (!empty($quests))

		foreach( array_keys($quests) as $zone )
		{
			$zoneInfo = $quests[$zone];
			foreach( array_keys( $zoneInfo ) as $slot)
			{
				$slotInfo = $zoneInfo[$slot];
				$level = $slotInfo['Level'];
				$item = $this->make_quest( $slotInfo, $member_id, $zone);
				$messages .= $this->insert_quest( $item );
			}
		}
		return $messages;
	}


	function do_recipes( $data, $member_id )
	{
		$messages .=  "* Updating: Recipes<br />\n";
		// first delete the stale data
		if ($data['Professions']!=NULL)
		{
			$querystr = "DELETE FROM `".ROSTER_RECIPESTABLE."` WHERE member_id = $member_id";
			mysql_query($querystr) or die(mysql_error());
		}
		if ($data['Professions']==NULL)
			$messages .=  "Recipes are NULL, not removing old recipes from database<br />\n";

		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";
		// then process recipes
		$prof = $data['Professions'];
		if(is_array($prof))
		{
			$prof = $data['Professions'];
			foreach( array_keys($prof) as $skill_name )
			{
				$skill = $prof[$skill_name];
				foreach( array_keys( $skill) as $recipe_type )
				{
					$item = $skill[$recipe_type];
					foreach(array_keys($item) as $recipe_name)
					{
						$recipeDetails = $item[$recipe_name];
						$recipe = $this->make_recipe( $recipeDetails, $member_id, $skill_name, $recipe_type, $recipe_name );
						$messages .= $this->insert_recipe( $recipe );
					}
				}
			}
		}
		return $messages;
	}


	function do_items( $data, $member_id )
	{
		$messages .= "";
		$messages .=  "* Updating: Items<br />\n";
		$equip = $data['Equipment'];
		if ($equip)
		{
			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = $member_id AND item_parent = 'equip'";
			mysql_query($querystr) or die(mysql_error());
			if ($this->sqldebug)
				$messages .=  "<!-- $querystr -->\n";
		}
		foreach( array_keys($equip) as $slot_name )
		{
			$slot = $equip[$slot_name];
			$item = $this->make_item( $slot, $member_id, 'equip', $slot_name );
			$messages .= $this->insert_item( $item );
		}

		$inv = $data['Inventory'];
		if ($inv)
		{
			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = $member_id AND UPPER(item_parent) LIKE 'BAG%' AND item_parent != 'bags'";
			mysql_query($querystr) or die(mysql_error());
			if ($this->sqldebug)
				$messages .=  "<!-- $querystr -->\n";

			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = $member_id AND item_parent = 'bags' AND UPPER(item_slot) LIKE 'BAG%'";
			mysql_query($querystr) or die(mysql_error());
			if ($this->sqldebug)
				$messages .=  "<!-- $querystr -->\n";
		}
		foreach( array_keys( $inv ) as $bag_name )
		{
			$bag = $inv[$bag_name];
			$item = $this->make_item( $bag, $member_id, 'bags', $bag_name );
			// quantity for a bag means number of slots it has
			$item['item_quantity'] = $bag['Slots'];
			$messages .= $this->insert_item( $item );
			foreach( array_keys( $bag['Contents'] ) as $slot_name )
			{
				$slot = $bag['Contents'][$slot_name];
				$item = $this->make_item( $slot, $member_id, $bag_name, $slot_name );
				$messages .= $this->insert_item( $item );
			}
		}

		$inv = $data['Bank'];
		if(isset($inv['Contents']))
		{
			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = $member_id AND UPPER(item_parent) LIKE 'BANK%'";
			mysql_query($querystr) or die(mysql_error());
			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = $member_id AND item_parent = 'bags' AND UPPER(item_slot) LIKE 'BANK%'";
			mysql_query($querystr) or die(mysql_error());
		}
		else
		{
			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE member_id = $member_id AND item_parent = 'bags' AND item_slot = 'Bank Contents'";
			mysql_query($querystr) or die(mysql_error());
		}
		if ($inv)
		{
			$messages .=  "* Updating: Bank<br />\n";
			// Make a special "Bank" container.
			$item = array();
			$item['member_id'] = $member_id;
			$item['item_name'] = 'Bank Contents';
			$item['item_parent'] = 'bags';
			$item['item_slot'] = 'Bank Contents';
			$item['item_color'] = 'ffffffff';
			$item['item_id'] = $item_data['Item'];
			$item['item_texture'] = 'Interface\Icons\INV_Misc_Bag_07';
			$item['item_quantity'] = 24;
			$messages .= $this->insert_item( $item );
			$bag = $inv;

			foreach( array_keys( $bag['Contents'] ) as $slot_name )
			{
				$slot = $bag['Contents'][$slot_name];
				$item = $this->make_item( $slot, $member_id, 'Bank Contents', $slot_name );
				$messages .= $this->insert_item( $item );
			}
			foreach( array_keys( $inv ) as $bag_name )
			{
				$messages .=  "<!-- $bag_name -->";
				if ($bag_name != 'Contents')
				{
					$bag = $inv[$bag_name];
					$dbname = 'Bank '.$bag_name;
					$item = $this->make_item( $bag, $member_id, 'bags', $dbname );
					// quantity for a bag means number of slots it has
					$item['item_quantity'] = $bag['Slots'];
					$messages .= $this->insert_item( $item );

					foreach( array_keys( $bag['Contents'] ) as $slot_name )
					{
						$slot = $bag['Contents'][$slot_name];
						$item = $this->make_item( $slot, $member_id, $dbname, $slot_name );
						$messages .= $this->insert_item( $item );
					}
				}
			}
			$messages .=  "\n";
		}
		return $messages;
	}


	function do_reputation( $data, $member_id )
	{
		$messages .=  "* Updating: Reputation<br />\n";
		//first delete the stale data
		$querystr = "DELETE FROM `".ROSTER_REPUTATIONTABLE."` WHERE member_id = $member_id";
		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";

		mysql_query($querystr) or die(mysql_error());
		$count = $data['Reputation']['Count'];
		foreach( array_keys( $data['Reputation'] ) as $factions )
		{
			$faction_name = $data['Reputation'][$factions];
			if ($faction_name != $count)
			{
				foreach( array_keys( $faction_name ) as $faction )
				{
					$this->reset_values();
					$this->add_value('member_id', $member_id );
					$this->add_value('faction', $factions );
					$this->add_value('name', $faction );
					$this->add_value('Value', $data['Reputation'][$factions][$faction]['Value'] );
					$this->add_value('AtWar', $data['Reputation'][$factions][$faction]['AtWar'] );
					$this->add_value('Standing', $data['Reputation'][$factions][$faction]['Standing']);
					$querystr = "INSERT INTO `".ROSTER_REPUTATIONTABLE."` SET ".$this->assignstr;
					if ($this->sqldebug)
						$messages .=  "<!-- $querystr -->\n";

					mysql_query($querystr) or die(mysql_error());
				}
			}
		}
		return $messages;
	}


	function do_skills( $data, $member_id )
	{
		$messages .=  "* Updating: Skills<br />\n";
		//first delete the stale data
		$querystr = "DELETE FROM `".ROSTER_SKILLSTABLE."` WHERE member_id = $member_id ";
		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());
		foreach( array_keys( $data['Skills'] ) as $skill_type )
		{
			$sub_skill = $data['Skills'][$skill_type];
			$order = $sub_skill['Order'];
			foreach( array_keys( $sub_skill ) as $skill_name )
			{
				if( $skill_name != 'Order' )
				{
					$this->reset_values();
					$this->add_value('member_id', $member_id );
					$this->add_value('skill_type', $skill_type );
					$this->add_value('skill_name', $skill_name );
					$this->add_value('skill_order', $order );
					$this->add_value('skill_level', $sub_skill[$skill_name] );
					$querystr = "INSERT INTO `".ROSTER_SKILLSTABLE."` SET ".$this->assignstr;
					if ($this->sqldebug)
						$messages .=  "<!-- $querystr -->\n";
					mysql_query($querystr) or die(mysql_error());
				}
			}
		}
		return $messages;
	}


	function do_spellbook( $data, $member_id )
	{
		// first delete the stale data
		$querystr = "DELETE FROM `".ROSTER_SPELLTABLE."` WHERE member_id = $member_id";
		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());

		// then process Spellbook Tree
		$querystr = "DELETE FROM `".ROSTER_SPELLTREETABLE."` WHERE member_id = $member_id";
		if ($this->sqldebug)
			$messages .= "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());

		// then process spellbook

		$spellbook = $data['SpellBook'];
		if(is_array($spellbook))
		{
			foreach( array_keys( $spellbook ) as $spell_type )
			{
				$data_spell_type = $spellbook[$spell_type];
				foreach( array_keys( $data_spell_type ) as $spell )
				{
					$data_spell = $data_spell_type[$spell];

					if(is_array($data_spell))
					{
						foreach( array_keys( $data_spell ) as $spell_name )
						{
							$data_spell_name = $data_spell[$spell_name];

							$this->reset_values();
							$this->add_value('member_id', $member_id );
							$this->add_value('spell_type', $spell_type );
							$this->add_value('spell_name', $spell_name );
							$this->add_value('spell_texture', $data_spell_name['Texture'] );
							$this->add_value('spell_rank', $data_spell_name['Rank'] );
							$querystr = "INSERT INTO `".ROSTER_SPELLTABLE."` SET ".$this->assignstr;
							if ($this->sqldebug)
							$messages = "<!-- $querystr -->\n";

							mysql_query($querystr) or die(mysql_error());
						}
					}
				}
				$this->reset_values();
				$this->add_value('member_id', $member_id );
				$this->add_value('spell_type', $spell_type );
				$this->add_value('spell_texture', $data_spell_type['Texture'] );
				$querystr = "INSERT INTO `".ROSTER_SPELLTREETABLE."` SET ".$this->assignstr;
				if ($this->sqldebug)
					$messages .= "<!-- $querystr -->\n";

				mysql_query($querystr) or die(mysql_error());

				$messages .=  "* Updating: Spellbook<br />\n";
			}
		}
		return $messages;
	}


	function do_talents( $data, $member_id )
	{
		$messages .=  "* Updating: Talents<br />\n";
		// first delete the stale data
		$querystr = "DELETE FROM `".ROSTER_TALENTSTABLE."` WHERE member_id = $member_id";
		if ($this->sqldebug)
			$messages .= "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());

		// then process Talents
		$querystr = "DELETE FROM `".ROSTER_TALENTTREETABLE."` WHERE member_id = $member_id";
		if ($this->sqldebug)
			$messages .= "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());

		if($data['Talents'])
		{
			foreach( array_keys( $data['Talents'] ) as $talent_tree )
			{
				$data_talent_tree = $data['Talents'][$talent_tree];
				foreach( array_keys( $data_talent_tree ) as $talent_skill )
				{
					$data_talent_skill = $data_talent_tree[$talent_skill];
					if( $talent_skill == 'Order' )
						$tree_order = $data_talent_skill;
					elseif ( $talent_skill == 'PointsSpent' )
						$tree_pointsspent = $data_talent_skill;
					elseif ( $talent_skill == 'Background')
						$tree_background = $data_talent_skill;
					else
					{
						$this->reset_values();
						$this->add_value('member_id', $member_id );
						$this->add_value('name', $talent_skill );
						$this->add_value('tree', $talent_tree );
						$this->add_value('tooltip', $data_talent_skill['Tooltip'] );
						$this->add_value('texture', $data_talent_skill['Texture'] );
						$this->add_value('row', substr($data_talent_skill['Location'], 0, 1) );
						$this->add_value('column', substr($data_talent_skill['Location'], 2, 1) );
						$this->add_value('rank', substr($data_talent_skill['Rank'], 0, 1) );
						$this->add_value('maxrank', substr($data_talent_skill['Rank'], 2, 1) );
						$querystr = "INSERT INTO `".ROSTER_TALENTSTABLE."` SET ".$this->assignstr;
						if ($this->sqldebug)
							$messages .= "<!-- $querystr -->\n";
						mysql_query($querystr) or die(mysql_error());
					}
				}
				$this->reset_values();
				$this->add_value('member_id', $member_id );
				$this->add_value('tree', $talent_tree );
				$this->add_value('background', $tree_background );
				$this->add_value('pointsspent', $tree_pointsspent );
				$this->add_value('order', $tree_order );
				$querystr = "INSERT INTO `".ROSTER_TALENTTREETABLE."` SET ".$this->assignstr;
				if ($this->sqldebug)
					$messages .= "<!-- $querystr -->\n";
				mysql_query($querystr) or die(mysql_error());
			}
		}
		return $messages;
	}

	function remove_guild_members($guild_id, $date)
	{
		$querystr = "SELECT member_id, name FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_id = '$guild_id' and update_time < '".
			$date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':00'."'";
		if ($this->sqldebug)
			$messages .= "<!-- $querystr -->\n";

		$result = mysql_query($querystr) or die(mysql_error());
		$inClause = '';
		$num = mysql_num_rows($result);
		while ( $row = mysql_fetch_row($result) )
		{
			if ($inClause != '')
				$inClause.= ',';
			$inClause .= $row[0];
			$messages .= ("$row[1] will be removed from the guild<br />\n");
		}
		$this->closeQuery($result);
		
		// now that we have our inclause, time to do some deletes
		if ($num > 0)
		{
			$messages .= '* Removing '.$num.' members ';
			$messages .= '; their profiles, skills, items, quests, PvP, recipes, talents, talent trees, spellbook, spellbook tree, pets, reputation';
			$querystr = "DELETE FROM `".ROSTER_MEMBERSTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_ITEMSTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_QUESTSTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_RECIPESTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_TALENTSTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_TALENTTREETABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_SPELLTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_SPELLTREETABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_PETSTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$querystr = "DELETE FROM `".ROSTER_REPUTATIONTABLE."` WHERE `member_id` IN ($inClause)";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());
			
			$messages .= (".<br />\n-DONE<br />\n");
		}
		return $messages;
	}

	function remove_guild_members_id($guild_id)
	{
		$querystr = "SELECT guild_id FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_id = '$guild_id'";
		if ($this->sqldebug)
			$messages .= "<!-- $querystr -->\n";
		$result = mysql_query($querystr) or die(mysql_error());
		if ( $row = mysql_fetch_row( $result ) )
		{
			$inClause .= $row[0];
		}
		$this->closeQuery($result);
		
		$querystr = "SELECT guild_id FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_id != '$guild_id'";
		if ($this->sqldebug)
			$messages .= "<!-- $querystr -->\n";
			$result = mysql_query($querystr) or die(mysql_error());
			$num = mysql_num_rows($result);
		$this->closeQuery($result);
		
			// now that we have our inclause, time to do some deletes
		if ($num > 0)
		{
			$messages .= '* Removing '.$num.' members ';
			$messages .= '* Removing members since guild ids dont match';

			$querystr = "DELETE FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` != $inClause";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());
			
			$querystr = "DELETE FROM `".ROSTER_GUILDTABLE."` WHERE `guild_id` != $inClause";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());
			
			$querystr = "DELETE FROM `".ROSTER_PLAYERSTABLE."` WHERE `guild_id` != $inClause";
			if ($this->sqldebug)
				$messages .= "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());

			$messages .= (".<br />\n-Removed members that did not match Guild Name ,DONE<br />\n");
		}
		return $messages;
	}
	
	function get_guild_info ($realmName,$guildName)
	{
		$guild_name_escape = $this->escape( $guildName );
		$server_escape = $this->escape( $realmName );
		$querystr = "SELECT guild_id, guild_name, server, guild_motd, guild_num_members, current_time FROM `".ROSTER_GUILDTABLE."` WHERE ".
			"guild_name = '$guild_name_escape' AND server = '$server_escape'";

		if ($this->sqldebug)
			$output[0] .= "<!-- $querystr -->\n";
		$result = mysql_query($querystr) or die(mysql_error());
		$retval = mysql_fetch_assoc( $result );
		$this->closeQuery($result);
		$output[1] = $retval;
		return $output;
	}


	function update_guild( $realmName, $guildName, $guildFaction, $guildMotd, $guildNumMembers, $currentTime, $guildDateUpdatedUTC, $GPversion )
	{
		$guildInfoOutput = $this->get_guild_info($realmName,$guildName);
		$guildInfo = $guildInfoOutput[1];
		$output[0] .= $guildInfoOutput[0];
		$this->reset_values();
		$this->add_value( 'guild_name', $guildName );
		$this->add_value( 'server', $realmName );
		$this->add_value( 'faction', $guildFaction );
		$this->add_value( 'guild_motd', $guildMotd );
		$this->add_value( 'guild_num_members', $guildNumMembers );
		$this->add_time( 'update_time', $currentTime );
		$this->add_value( 'guild_dateupdatedutc', $guildDateUpdatedUTC );
		$this->add_value( 'GPversion', $GPversion );

		if ($guildInfo)
		{
			$guildId = $guildInfo['guild_id'];
			$querystr = "UPDATE `".ROSTER_GUILDTABLE."` SET ".$this->assignstr." WHERE guild_id = '$guildId'";
			$output[1] = $guildId;
		}
		else
			$querystr = "INSERT INTO `".ROSTER_GUILDTABLE."` SET ".$this->assignstr;

		if ($this->sqldebug)
			$output[0] .= "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());
		if (!$guildInfo)
		{
			$guildInfo = $this->get_guild_info($realmName,$guildName);
			$output[0] .= $guildInfo[0];
			$output[1]  = $guildInfo[1]['guild_id'];
		}

		return $output;
	}


	function update_guild_member( $guildId, $name, $char, $currentTimestamp )
	{
		$name_escape = $this->escape( $name );
		$querystr = "SELECT member_id FROM `".ROSTER_MEMBERSTABLE."` WHERE name = '$name_escape' and guild_id = '$guildId'";
		if ($this->sqldebug)
			$output .= "<!-- $querystr -->\n";
		$result = mysql_query($querystr) or die(mysql_error());
		$memberInfo = mysql_fetch_assoc( $result );
		if ($memberInfo)
			$memberId = $memberInfo['member_id'];

		$this->closeQuery($result);

		$this->reset_values();
		$this->add_value( 'name', $name_escape);
		$this->add_value( 'guild_id', $guildId);
		$this->add_value( 'class', $char['Class']);
		$this->add_value( 'level', $char['Level']);
		$this->add_value( 'note', $char['Note']);
		$this->add_value( 'guild_rank', $char['RankIndex']);
		$this->add_value( 'guild_title', $char['Rank']);
		$this->add_value( 'officer_note', $char['OfficerNote']);
		$this->add_value( 'zone', $char['Zone']);
		$this->add_value( 'group', $char['Group']);
		$this->add_time( 'update_time', getDate($currentTimestamp));
		if ($char['Online'])
		{
			$this->add_value( 'online', 1);
			$this->add_time('last_online', getDate($currentTimestamp));
		}
		else
		{
			$this->add_value( 'online', 0);
			$lastOnline = $char['LastOnline'];
			$lastOnlineYears = intval($lastOnline['Year']);
			$lastOnlineMonths = intval($lastOnline['Month']);
			$lastOnlineDays = intval($lastOnline['Day']);
			$lastOnlineHours = intval($lastOnline['Hour']);
			# use strtotime instead
			#      $lastOnlineTime = $currentTimestamp - 365 * 24* 60 * 60 * $lastOnlineYears
			#                        - 30 * 24 * 60 * 60 * $lastOnlineMonths
			#                        - 24 * 60 * 60 * $lastOnlineDays
			#                        - 60 * 60 * $lastOnlineHours;
			$timeString = '-';
			if ($lastOnlineYears > 0)
				$timeString .= $lastOnlineYears.' Years ';
			if ($lastOnlineMonths > 0)
				$timeString .= $lastOnlineMonths.' Months ';
			if ($lastOnlineDays > 0)
				$timeString .= $lastOnlineDays.' Days ';
			if ($lastOnlineHours > 0)
				$timeString .= $lastOnlineHours.' Hours ';

			$lastOnlineTime = strtotime($timeString,$currentTimestamp);
			$this->add_time( 'last_online', getDate($lastOnlineTime));
		}

		if ($memberId)
		{
			$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr." WHERE member_id = '$memberId'";
			$output .= "Updating member - [ $name ]<br />\n";
		}
		else
		{
			$querystr = "INSERT INTO `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr;
			$output .= "Adding member - [ $name ]<br />\n";
		}
		if ($this->sqldebug)
			$output .= "<!-- $querystr -->\n";
		mysql_query($querystr) or die(mysql_error());

		return $output;
	}


	function update_pvp2($guildId, $name, $data )
	{
		$output .= ""; //this will hold all the stuff this function used to print/echo
		$name_escape = $this->escape( $name );
		$querystr = "SELECT member_id FROM `".ROSTER_MEMBERSTABLE."` WHERE name = '$name_escape' and guild_id = '$guildId'";

		if ($this->sqldebug)
			$output .= "<!-- $querystr -->\n";

		$result = mysql_query($querystr) or die(mysql_error());
		$memberInfo = mysql_fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
		{
			$memberId = $memberInfo['member_id'];
			$member_id = $memberId;
		}
		else
		{
			$output .= "$name is not in the list of guild members so PVP2 info will not be inserted.<br />\n";
			return $output;
		}
		// process pvp
		$output .=  "* Updating: PvP data<br />\n";
		// loop through each index fought
		foreach( array_keys($data) as $index)
		{
			$playerInfo = $data[$index];
			$playerName = $playerInfo['name'];
			$playerDate = date('Y-m-d G:i:s', strtotime($playerInfo['date']));
			// skip if entry already there
			$querystr = "SELECT guild FROM `".ROSTER_PVP2TABLE."` WHERE `index` = '$index' and member_id = '$memberId' and name = '$playerName' ".
				"and date = '$playerDate'";
			$result = mysql_query($querystr) or die(mysql_error());
			$memberInfo = mysql_fetch_assoc( $result );
			$this->closeQuery($result);
			if ($memberInfo)
			{}
			else
			{
				$output .=  '* Adding pvp2 data for ['.$playerInfo['name']."]<br />\n";
				$name = $playerInfo['name'];
				$race = $playerInfo['race'];
				$guild = $playerInfo['guild'];
				$class = $playerInfo['class'];
				$enemy = $playerInfo['enemy'];
				$win = $playerInfo['win'];
				$zone = $playerInfo['zone'];
				$subzone = $playerInfo['subzone'];
				$datebattle = $playerInfo['date'];
				$leveldiff = $playerInfo['lvlDiff'];
				$bgflag = $playerInfo['bg'];
				$rank = $playerInfo['rank'];
				$honor = $playerInfo['honor'];
				$this->reset_values();
				$this->add_value('member_id', $member_id);
				$this->add_value('index', $index);
				$this->add_pvp2time('date', $datebattle);
				$this->add_value('name', $name);
				$this->add_value('guild', $guild);
				$this->add_value('race', $race);
				$this->add_value('class', $class);
				$this->add_value('zone', $zone);
				$this->add_value('subzone', $subzone);
				$this->add_value('leveldiff', $leveldiff);
				$this->add_value('enemy', $enemy);
				$this->add_value('win', $win);
				$this->add_value('bg', $bgflag);
				$this->add_value('rank', $rank);
				$this->add_value('honor', $honor);
				$querystr = "INSERT INTO `".ROSTER_PVP2TABLE."` SET ".$this->assignstr;
				if ($this->sqldebug)
					$output .= "<!-- $querystr -->\n";
				mysql_query($querystr) or die(mysql_error());
			}
		}

		// now calculate ratio
		$querystr = "SELECT Count('win') as wins FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '1' and member_id = '$memberId' AND leveldiff < 8 ".
			"AND enemy = '1'";
		$wins = 0;
		$result = mysql_query($querystr) or die(mysql_error());
		$memberInfo = mysql_fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
			$wins = $memberInfo['wins'];

		$output .= 'Wins:'.$wins."<br />\n";
		$querystr = "SELECT Count('win') as losses FROM `".ROSTER_PVP2TABLE."` WHERE `win` = '0' and member_id = '$memberId' AND leveldiff <8 ".
			"AND enemy = '1'";
		$losses = 0;
		$result = mysql_query($querystr) or die(mysql_error());
		$memberInfo = mysql_fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
			$losses = $memberInfo['losses'];

		$output .= 'Losses:'.$losses."<br />\n";
		if ($losses == 0 || $wins == 0)
		{
			if ($losses == 0 && $wins == 0)
				$ratio = 0;
			else
				$ratio = 99999;

		}
		else
			$ratio = $wins / $losses;

		$querystr = "UPDATE `".ROSTER_PLAYERSTABLE."` SET `pvp_ratio` = ".$ratio." WHERE `member_id` = '$memberId'";
		if ($this->sqldebug)
			$output .= "<!-- $querystr -->\n";
		$result = mysql_query($querystr) or die(mysql_error());
		$output .= 'Set PvP ratio to '.$ratio."<br />\n";
		return $output;
	}


	function update_account( $memberId, $name, $data )
	{
		if($data['Account'] == $name)
		{
			$name_escape = $this->escape( $name );
			$querystr = "SELECT name FROM `".ROSTER_ACCOUNTTABLE."`";
			$result = mysql_query($querystr) or die(mysql_error());
			$update = mysql_num_rows( $result ) == 1;
			$this->closeQuery($result);

			$this->reset_values();

			$this->add_value( 'name', $data['Account'] );
			$this->add_value( 'hash', $data['Hash'] );

			if( $update )
				$querystr = "UPDATE `".ROSTER_ACCOUNTTABLE."` SET ".$this->assignstr." WHERE `name` = '$name_escape'";
			else
				$querystr = "INSERT INTO `".ROSTER_ACCOUNTTABLE."` SET ".$this->assignstr;

			if ($this->sqldebug)
				$messages .=  "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());
			
			$messages .= '* Assigning '.$name.' as Main Character <br>\n';
		}
		else
		{
			$messages .= '* Assigning '.$name.' to your Main Character <br>\n';
		}
		
		$name_escape = $this->escape( $data['Account'] );
		$querystr = "SELECT account_id FROM `".ROSTER_ACCOUNTTABLE."` WHERE name = '$name_escape'";
		$result = mysql_query($querystr) or die(mysql_error());
		$accountInfo = mysql_fetch_assoc( $result );
		
		if ($accountInfo)
		{
			$this->reset_values();
			
			$account_id = $accountInfo['account_id'];
			$this->add_value( 'account_id', $account_id );

			$display = $data['Display'];
			$this->add_value( 'inv', $display['Inventory'] );
			$this->add_value( 'talents', $display['Talents'] );
			$this->add_value( 'quests', $display['Quests'] );
			$this->add_value( 'bank', $display['Bank'] );
			
		}
		
		$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId'";
		$result = mysql_query($querystr) or die(mysql_error());
		return $messages;
	}

	function update_pets( $memberId, $data )
	{
		$petname = $data['Name'];
		$petname_escape = $this->escape($petname);
		$messages .=  "<!-- $petname -->\n";
		if (!empty($petname))
		{
			$querystr = "SELECT member_id, name FROM `".ROSTER_PETSTABLE."` WHERE member_id = '$memberId' and name LIKE  '".$petname_escape."'";

			if ($this->sqldebug)
				$messages .=  "<!-- $querystr --> \n";

			$result = mysql_query($querystr) or die(mysql_error());
			$update = mysql_num_rows( $result ) == 1;
			$this->closeQuery($result);

			$this->reset_values();

			$this->add_value( 'member_id', $memberId );
			$this->add_value( 'name', $petname );
			$this->add_value( 'slot', $data['Slot'] );

			$this->add_value( 'stat_int', $data['Stats']['Intellect'] );
			$this->add_value( 'stat_agl', $data['Stats']['Agility'] );
			$this->add_value( 'stat_sta', $data['Stats']['Stamina'] );
			$this->add_value( 'stat_str', $data['Stats']['Strength'] );
			$this->add_value( 'stat_spr', $data['Stats']['Spirit'] );

			$resist = explode(':', $data['Resists']['Frost']);
			$this->add_value( 'res_frost', $resist[1] );
			$resist = explode(':', $data['Resists']['Arcane']);
			$this->add_value( 'res_arcane', $resist[1] );
			$resist = explode(':', $data['Resists']['Fire']);
			$this->add_value( 'res_fire', $resist[1] );
			$resist = explode(':', $data['Resists']['Shadow']);
			$this->add_value( 'res_shadow', $resist[1] );
			$resist = explode(':', $data['Resists']['Nature']);
			$this->add_value( 'res_nature', $resist[1] );

			$this->add_value( 'level', $data['Level'] );
			$this->add_value( 'health', $data['Health'] );
			$this->add_value( 'mana', $data['Mana'] );
			$this->add_value( 'armor', $data['Armor'] );
			$this->add_value( 'defense', $data['Defense'] );
			$this->add_value( 'xp', $data['Experience'] );
			$this->add_value( 'usedtp', $data['TalentPointsUsed'] );
			$this->add_value( 'totaltp', $data['TalentPoints'] );
			$this->add_value( 'type', $data['Type'] );
			$this->add_value( 'loyalty', $data['Loyalty']);
			$this->add_value( 'icon', $data['Icon']);

			$attack = $data['Melee Attack'];
			$this->add_value( 'melee_power', $attack['AttackPower'] );
			$this->add_value( 'melee_rating', $attack['AttackRating'] );
			$this->add_value( 'melee_range', $attack['DamageRange'] );
			$this->add_value( 'melee_rangetooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
			$this->add_value( 'melee_powertooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );

			$messages .= '* About to update pet ['.$petname_escape."]<br />\n";

			if( $update )
				$querystr = "UPDATE `".ROSTER_PETSTABLE."` SET ".$this->assignstr." WHERE `member_id` = '$memberId' and name = '".$petname_escape."'";
			else
				$querystr = "INSERT INTO `".ROSTER_PETSTABLE."` SET ".$this->assignstr;

			if ($this->sqldebug)
				$messages .=  "<!-- $querystr -->\n";
			mysql_query($querystr) or die(mysql_error());
		}
		return $messages;
	}


	function update_char( $guildId, $name, $data )
	{
		//echo "###########################";
		$messages = ''; //this var will hold everything that this function used to print
		$name_escape = $this->escape( $name );
		$querystr = "SELECT member_id FROM `".ROSTER_MEMBERSTABLE."` WHERE name = '$name_escape' and guild_id = '$guildId'";

		if ($this->sqldebug)
			$messages .= "<!-- $querystr -->\n";

		$result = mysql_query($querystr) or die(mysql_error());
		$memberInfo = mysql_fetch_assoc( $result );
		$this->closeQuery($result);
		if ($memberInfo)
			$memberId = $memberInfo['member_id'];
		else
		{
			$messages .= "$name is not in the list of guild members so their data will not be inserted.<br />";
			return $messages;
		}

		$quest = $data['Quest'];
		if ($quest)
		{
			$messages .=  "Character Profiler out of date.  Please update to the latest version to continue.<br />\n";
			return $messages;
		}

		// Expose this for later functions
		$data['CharName'] = $name;
		//print '<pre>';
		//print_r( $data );

		//start mod - update level in members table
		$querystr = "UPDATE `".ROSTER_MEMBERSTABLE."` SET `level` = '".$data['Level']."' WHERE `member_id` =$memberId LIMIT 1 ";

		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";

		$result = mysql_query($querystr) or die(mysql_error());
		//end mod

		$querystr = "SELECT member_id FROM `".ROSTER_PLAYERSTABLE."` WHERE member_id = '$memberId'";

		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";

		$result = mysql_query($querystr) or die(mysql_error());
		$update = mysql_num_rows( $result ) == 1;
		$this->closeQuery($result);

		$this->reset_values();

		$this->add_value( 'name', $name );
		$this->add_value( 'guild_id', $guildId );

		// NEW VALES FOR MY WOWPROFILERS ROSTER
		$this->add_value( 'dodge', $data['DodgePercent'] );
		$this->add_value( 'parry', $data['ParryPercent'] );
		$this->add_value( 'block', $data['BlockPercent'] );
		$this->add_value( 'mitigation', $data['MitigationPercent'] );
		$this->add_value( 'crit', $data['CritPercent'] );

		// BEGIN HONOR VALUES
		$this->add_value( 'sessionHK', $data['Honor']['SessionHK'] );
		$this->add_value( 'sessionDK', $data['Honor']['SessionDK'] );
		$this->add_value( 'yesterdayHK', $data['Honor']['YesterdayHK'] );
		$this->add_value( 'yesterdayDK', $data['Honor']['YesterdayDK'] );
		$this->add_value( 'yesterdayContribution', $data['Honor']['YesterdayContribution'] );
		$this->add_value( 'lastweekHK', $data['Honor']['LastWeekHK'] );
		$this->add_value( 'lastweekDK', $data['Honor']['LastWeekDK'] );
		$this->add_value( 'lastweekContribution', $data['Honor']['LastWeekContribution'] );
		$this->add_value( 'lastweekRank', $data['Honor']['LastWeekRank'] );
		$this->add_value( 'lifetimeHK', $data['Honor']['LifetimeHK'] );
		$this->add_value( 'lifetimeDK', $data['Honor']['LifetimeDK'] );
		$this->add_value( 'lifetimeRankName', $data['Honor']['LifetimeRankName'] );
		$this->add_value( 'lifetimeHighestRank', $data['Honor']['LifetimeHighestRank'] );
		$ncc = sscanf($data['Honor']['RankInfo'], "(%s %d)", $st, $RankInt);
		$this->add_value( 'RankInfo', (int)$RankInt );
		$this->add_value( 'RankName', $data['Honor']['RankName'] );
		$this->add_value( 'RankIcon', $data['Honor']['RankIcon'] );
		$this->add_value( 'Rankexp', $data['Honor']['Current']['Progress'] );
		$this->add_value( 'TWContribution', $data['Honor']['ThisWeek']['Contribution'] );
		$this->add_value( 'TWHK', $data['Honor']['ThisWeek']['HK'] );
		// END HONOR VALUES

		$this->add_value( 'stat_int', $data['Stats']['Intellect'] );
		$this->add_value( 'stat_agl', $data['Stats']['Agility'] );
		$this->add_value( 'stat_sta', $data['Stats']['Stamina'] );
		$this->add_value( 'stat_str', $data['Stats']['Strength'] );
		$this->add_value( 'stat_spr', $data['Stats']['Spirit'] );

		$stat = explode(':', $data['Stats']['Intellect']);
		$this->add_value( 'stat_int2', $stat[1] );
		$stat = explode(':', $data['Stats']['Agility']);
		$this->add_value( 'stat_agl2', $stat[1] );
		$stat = explode(':', $data['Stats']['Stamina']);
		$this->add_value( 'stat_sta2', $stat[1] );
		$stat = explode(':', $data['Stats']['Strength']);
		$this->add_value( 'stat_str2', $stat[1] );
		$stat = explode(':', $data['Stats']['Spirit']);
		$this->add_value( 'stat_spr2', $stat[1] );

		$this->add_value( 'race', $data['Race'] );

		$resist = explode(':', $data['Resists']['Frost']);
		$this->add_value( 'res_frost', $resist[1] );
		$resist = explode(':', $data['Resists']['Arcane']);
		$this->add_value( 'res_arcane', $resist[1] );
		$resist = explode(':', $data['Resists']['Fire']);
		$this->add_value( 'res_fire', $resist[1] );
		$resist = explode(':', $data['Resists']['Shadow']);
		$this->add_value( 'res_shadow', $resist[1] );
		$resist = explode(':', $data['Resists']['Nature']);
		$this->add_value( 'res_nature', $resist[1] );

		$this->add_value( 'armor', $data['Armor'] );
		$this->add_value( 'level', $data['Level'] );
		$this->add_value( 'server', $data['Server'] );
		$this->add_value( 'defense', $data['Defense'] );
		$this->add_value( 'talent_points', $data['TalentPoints'] );

		$this->add_value( 'money_c', $data['Money']['Copper'] );
		$this->add_value( 'money_s', $data['Money']['Silver'] );
		$this->add_value( 'money_g', $data['Money']['Gold'] );

		$this->add_value( 'exp', $data['Experience'] );
		$this->add_value( 'class', $data['Class'] );
		$this->add_value( 'health', $data['Health'] );
		$this->add_value( 'mana', $data['Mana'] );
		$this->add_value( 'sex', $data['Sex'] );
		$this->add_value( 'hearth', $data['Hearth'] );
		$this->add_value( 'dateupdatedutc', $data['DateUTC'] );
		$this->add_value( 'CPversion', $data['CPversion'] );

		if ($data['TimePlayed'] < 0 )
		{
			$messages .= "Timeplayed Null, Not updating timeplayed<br />\n";
		}
		else
		{
		$this->add_value( 'timeplayed', $data['TimePlayed'] );
		}
		if ($data['TimeLevelPlayed'] < 0 )
		{
			$messages .= "Timelevelplayed Null, Not updating timeplayed<br />\n";
		}
		else
		{
			$this->add_value( 'timelevelplayed', $data['TimeLevelPlayed'] );
		}

		if( isset( $data['Melee Attack'] ) )
		{
			$attack = $data['Melee Attack'];
			$this->add_value( 'melee_power', $attack['AttackPower'] );
			$this->add_value( 'melee_rating', $attack['AttackRating'] );
			$this->add_value( 'melee_range', $attack['DamageRange'] );
			$this->add_value( 'melee_range_tooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
			$this->add_value( 'melee_power_tooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );
		}

		if( isset( $data['Ranged Attack'] ) )
		{
			$attack = $data['Ranged Attack'];
			$this->add_value( 'ranged_power', $attack['AttackPower'] );
			$this->add_value( 'ranged_rating', $attack['AttackRating'] );
			$this->add_value( 'ranged_range', $attack['DamageRange'] );
			$this->add_value( 'ranged_range_tooltip', $this->tooltip( $attack['DamageRangeTooltip'] ) );
			$this->add_value( 'ranged_power_tooltip', $this->tooltip( $attack['AttackPowerTooltip'] ) );
		}

		// Capture client language
		$this->add_value( 'clientLocale', $data['Locale'] );

	        $messages .=  "* About to update player<br />\n";

		if( $update )
			$querystr = "UPDATE `".ROSTER_PLAYERSTABLE."` SET ".$this->assignstr." WHERE member_id = '$memberId'";
		else
		{
			$this->add_value( 'member_id', $memberId);
			$querystr = "INSERT INTO `".ROSTER_PLAYERSTABLE."` SET ".$this->assignstr;
		}

		if ($this->sqldebug)
			$messages .=  "<!-- $querystr -->\n";

		mysql_query($querystr) or die(mysql_error());

		$messages .= $this->do_items( $data, $memberId );
		$messages .= $this->do_skills( $data, $memberId );
		$messages .= $this->do_recipes( $data, $memberId );
		$messages .= $this->do_spellbook( $data, $memberId );
		$messages .= $this->do_talents( $data, $memberId );
		$messages .= $this->do_reputation( $data, $memberId );
		$messages .= $this->do_quests( $data, $memberId );

		// Adding pet info
		if( isset( $data['Pets'] ) )
		{
			$petsdata = $data['Pets'];
			foreach( array_keys( $petsdata ) as $pet )
			{
				$petinfo = $petsdata[$pet];
				//print_r( $petinfo );
				//print '</pre>';
				$messages .= $this->update_pets( $memberId, $petinfo );
			}
		}
		
		// built in Auth system
		if( isset( $data['Roster'] ) )
		{
			$rosterdata = $data['Roster'];
			$messages .= $this->update_account( $memberId, $name, $rosterdata);
		}

		return $messages;
	} //-END function update_char()

} //-END CLASS

$wowdb = new wowdb;
$wowdb->setSQLDebug($sqldebug);

?>
