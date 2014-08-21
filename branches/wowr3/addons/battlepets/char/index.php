<?php

	$member_id = $roster->data['member_id'];
	$member_name = $roster->data['name'];
	$member_realm = $roster->data['server'];
	$member_str = $member_name . '@' . $roster->data['region'] . '-' . $member_realm;

	$queryx = "SELECT * FROM `".$roster->db->table('pets',$addon['basename'])."` WHERE `member_id`='" . $member_id . "' ORDER BY `level` DESC";
	$resultx = $roster->db->query( $queryx );
	
	$pets = array();
	while($data = $roster->db->fetch($resultx))
	{
		$color = _rarity($data['Rarity']);
		$pets[$data['id']]["Data"] = array(
					'ICON'		=>	$roster->config['interface_url'].'Interface/Icons/'.$data['Texture'].'.'.$roster->config['img_suffix'],
					'QUALITY'	=>	_setQuality( $data['Rarity'] ),
					'NAME'		=>	$data['Name'],
					'NAMECOLOR'	=>	_rarity($data['Rarity']),
					'DESC'		=>	$data['Description'],
					'HEALTH'	=>	$data['Health'].'/'.$data['MaxHealth'],
					'TOOLTIP'	=>	makeOverlib($data['Description'].'<br /><br />'.$data['SText'], $data['Name'],'', 2, '', ', WIDTH, 350'),
					'SPEED'		=>	$data['Speed'],
					'POWER'		=>	$data['Power'],
					'SOURCE'	=>	$data['SText'],
					'LEVEL'		=>	$data['Level'],
					'SPECIES'	=>	petclass($data['Type']),
					'TYPE'		=>	$data['Type'],
		);
		$pets[$data['id']]["Spells"] = array(
			'1' => array( 'ID'=>	'',	'ICON'=>'','TOOLTIP'=>''),
			'2' => array( 'ID'=>	'','ICON'=>	'',	'TOOLTIP'=>	''),
			'4' => array( 'ID'=>	'',	'ICON'=>'',	'TOOLTIP'=>	''),
			'10' => array( 'ID'=>	'',	'ICON'=>'',	'TOOLTIP'=>	''),
			'15' => array( 'ID'=>	'',	'ICON'=>'',	'TOOLTIP'=>	''),
			'20' => array( 'ID'=>'','ICON'=>'','TOOLTIP'=>	''),
		);

		$querys = "SELECT * FROM `".$roster->db->table('pets_spells',$addon['basename'])."` WHERE `bpet_id`='" . $data['id'] . "' ORDER BY `spell_level` ASC";
		$results = $roster->db->query( $querys );
		$i=1;
		while($spell = $roster->db->fetch($results))
		{
			$i++;
			$tooltip = $spell['spell_tooltip'];
			$tooltip .= '<br/>Strong: <img src=\"'.$addon['url_path'] .'images/BattleBar-AbilityBadge-Strong-Small.png\" width=16 height=16></a> '.$spell['spell_strong'];
			$tooltip .= '<br />Weak: <img src=\"'.$addon['url_path'] .'images/BattleBar-AbilityBadge-Weak-Small.png\" width=16 height=16></a> '.$spell['spell_weak'];
			if ($spell['spell_turns'] > 1)
			{
				$tooltip .= '<br />Lasts '.$spell['spell_turns'].' turns';
			}
			if ($spell['spell_cd'] > 0)
			{
				$tooltip .= '<br />'.$spell['spell_cd'].' turn cooldown';
			}
			$tooltip .= '<br />Level : '.$spell['spell_level'];
			
			$pets[$data['id']]["Spells"][$spell['spell_level']] = array(
					'ID'		=>	$i,
					'ICON'		=>	$roster->config['interface_url'].'Interface/Icons/'.$spell['spell_texture'].'.'.$roster->config['img_suffix'],
					'TOOLTIP'	=>	makeOverlib($tooltip, $spell['spell_name'], '', 2),
				);

			if ($i == 6)
			{
				$i=0;
			}
		}
				
	}
	
	foreach ($pets as $index => $data)
	{
		$roster->tpl->assign_block_vars('pet',array(
					'ICON'		=>	$data['Data']['ICON'],
					'QUALITY'	=>	$data['Data']['QUALITY'],
					'NAME'		=>	$data['Data']['NAME'],
					'NAMECOLOR'	=>	$data['Data']['NAMECOLOR'],
					'DESC'		=>	$data['Data']['DESC'],
					'HEALTH'	=>	$data['Data']['HEALTH'],
					'SPEED'		=>	$data['Data']['SPEED'],
					'POWER'		=>	$data['Data']['POWER'],
					'SOURCE'	=>	$data['Data']['SOURCE'],
					'LEVEL'		=>	$data['Data']['LEVEL'],
					'TOOLTIP'	=>	$data['Data']['TOOLTIP'],
					'SPECIES'	=>	$data['Data']['SPECIES'],
					'TYPE'		=>	$data['Data']['TYPE'],
				)
			);
		foreach($data['Spells'] as $id => $spell)
		{
			$roster->tpl->assign_block_vars('pet.skills',array(
					'LEVEL'		=> $id,
					'ID'		=>	$spell['ID'],
					'ICON'		=>	$spell['ICON'],
					'TOOLTIP'	=>	$spell['TOOLTIP'],
				)
			);
		}
	}
	$roster->tpl->set_filenames(array(
		'bpets' => $addon['basename'] . '/char.html',
			));
			
	$roster->tpl->display('bpets');

	function petclass($id)
	{
		$ret = '';
		switch ($id) {
			case 1: $ret = 'Humanoid';break;
			case 2: $ret = 'Dragon';break;
			case 3: $ret = 'Flying';break;
			case 4: $ret = 'Undead';break;
			case 5: $ret = 'Critter';break;
			case 6: $ret = 'Magical';break;
			case 7: $ret = 'Elemental';break;
			case 8: $ret = 'Beast';break;
			case 9: $ret = 'Water';break;
			case 10: $ret = 'Mechanical';break;
		}
		return $ret;
	}
	
	function _rarity($value) {
		$ret = '';
		switch ($value) {
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
	
	function _setQuality( $color )
	{
		switch( strtolower( $color ) )
		{
			case '7':
				return 'heirloom';
				break;
			case '6':
				return 'legendary';
				break;
			case '5':
				return 'epic';
				break;
			case '4':
				return 'rare';
				break;
			case '3':
				return 'uncommon';
				break;
			case '2':
				return 'common';
				break;
			case '1':
				return 'poor';
				break;
			default:
				return 'none';
			break;
		}
	}
