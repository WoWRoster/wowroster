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
	
	$server = isset($roster->data['server']) ? $roster->data['server'] : false;
	$data = $roster->api->Char->getCharInfo($server, $char, '4');
	$equip = array (
		'0' => 'head',	'1' => 'neck',	'2' => 'shoulder',	'14' => 'back',	'4' => 'chest',	'3' => 'shirt',
		'18' => 'tabard',	'8' => 'wrist',	'9' => 'hands',	'5' => 'waist',	'6' => 'legs',	'7' => 'feet',	'10' => 'finger1',
		'11' => 'finger2',	'12' => 'trinket1',	'13' => 'trinket2',	'15' => 'mainHand',	'17' => 'ranged');
	list($num1,$num2,$num3) = explode('-',$slots);
	$x= array($num1,$num2,$num3);

	$error = true;
	$msg = array();
	foreach ($x as $var => $id)
	{
		if (!array_key_exists($equip[$id],$data['items']))
		{
			$msg[]= '<span style="color: #7eff00;">'.$equip[$id].' Unequiped</span>';
		}
		else
		{
			$msg[]= '<span style="color: #f00;">'.$equip[$id].' NOT Unequiped</span>';
			$error = false;
		}		
	}
	$rw=null;
	if ($error)
	{
		$sqlquery2 = "SELECT * FROM `".$roster->db->table('members')."` WHERE `name` = '".$char."'";
		$result2 = $roster->db->query($sqlquery2);
		$rw = $roster->db->fetch($result2);
		//print_r($rw);
	}
	
	
	$msg = implode('<br>',$msg);
	$response = array(
		'ok' => $error,
		'cla55' => $roster->locale->wordings['enUS']['id_to_class'][$data['class']],
		'level' => $data['level'],
		'thumb' => $data['thumbnail'],
		'rank'	=> (isset($rw['guild_rank']) ? $rw['guild_rank'] : ''),
		'title'	=> (isset($rw['guild_title']) ? $rw['guild_title'] : ''),
		'msg' => '<br>'.$msg
		);
	echo json_encode($response);
	?>