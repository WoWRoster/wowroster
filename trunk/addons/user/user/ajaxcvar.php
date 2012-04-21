<?php
	define('IN_ROSTER', true);
//equire('../../../settings.php');

	$roster->output['show_header'] = false;
	$roster->output['show_menu'] = false;
	$roster->output['show_footer'] = false;

	if (isset($_POST['character']))
	{
		$char = $_POST['character'];
	}
	else
	{
		$char = $_GET['character'];
	}
	
	if (isset($_POST['slot']))
	{
		$slots = $_POST['slot'];
	}
	else
	{
		$slots = $_GET['slot'];
	}
	
	if (isset($_POST['server']))
	{
		$server = $_POST['server'];
	}
	else
	{
		$server = $_GET['server'];
	}
	
	if (isset($_POST['guild_id']))
	{
		$guild_id = $_POST['guild_id'];
	}
	else
	{
		$guild_id = $_GET['guild_id'];
	}
	
	$data = $roster->api->Char->getCharInfo($server, $char, '4');
	$equip = array (
		'0' => 'head',	'1' => 'neck',	'2' => 'shoulder',	'14' => 'back',	'4' => 'chest',	'3' => 'shirt',
		'18' => 'tabard',	'8' => 'wrist',	'9' => 'hands',	'5' => 'waist',	'6' => 'legs',	'7' => 'feet',	'10' => 'finger1',
		'11' => 'finger2',	'12' => 'trinket1',	'13' => 'trinket2',	'15' => 'mainHand','16' => 'offHand', '17' => 'ranged');
	list($num1,$num2,$num3) = explode('-',$slots);
	$x= array($num1,$num2,$num3);

	$error = true;
	$msg = array();
	$u=0;
	$mag = array();
	foreach ($x as $var => $id)
	{
		if (!array_key_exists($equip[$id],$data['items']))
		{
			$u++;
			$msg[]= '<span style="color: #7eff00;">'.$equip[$id].' Unequiped</span>';
			$mag[$u] = 'RGaccepy';
			
		}
		else
		{
			$msg[]= '<span style="color: #f00;">'.$equip[$id].' NOT Unequiped</span>';
			$u++;
			$mag[$u] = 'RGdenyed';
			$error = false;
		}		
	}
	$rw=null;

	$sqlquery2 = "SELECT * FROM `".$roster->db->table('members')."` WHERE `name` = '".$char."' AND `guild_id` = '".$guild_id."'";
	$result2 = $roster->db->query($sqlquery2);
	$rw = $roster->db->fetch($result2);

	if (isset($rw['name']) && $rw['name'] = $char)
	{
		$msg = implode('<br>',$msg);
		$response = array(
			'ok' => $error,
			'cla55' => $roster->locale->wordings['enUS']['id_to_class'][$data['class']],
			'level' => $data['level'],
			'member_id' => $rw['member_id'],
			'thumb' => $data['thumbnail'],
			'rank'	=> (isset($rw['guild_rank']) ? $rw['guild_rank'] : ''),
			'title'	=> (isset($rw['guild_title']) ? $rw['guild_title'] : ''),
			'EEQQ1' => $mag[1],
			'EEQQ2' => $mag[2],
			'EEQQ3' => $mag[3],
			'msg' => '<br>'.$msg
			);
	}
	else
	{
		$msg = 'Sorry you are not in the guild member list';
		$response = array(
			'ok' => false,
			'cla55' => 'x',
			'level' => 'x',
			'thumb' => $data['thumbnail'],
			'rank'	=> 'x',
			'title'	=> 'x',
			'EEQQ1' =>'RGdenyed',
			'EEQQ2' => 'RGdenyed',
			'EEQQ3' => 'RGdenyed',
			'msg' => '<br>'.$msg
			);
	}
	echo json_encode($response);
	?>