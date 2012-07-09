<?php

if( !isset($user) )
{
include_once ($addon['inc_dir'] . 'conf.php');
}

if(isset($_POST['op']) && $_POST['op']=='alt')
{

	// If the Register form has been submitted
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	$err = array();
	
	$user_id = $roster->auth->uid;
		//$row = $roster->db->fetch($resulta);
		
		$userlink = array(
			'uid' => $user_id,
			'member_id' => $_POST['member_id'],
			'guild_id' => $roster->data['guild_id'],
			'group_id' => '2',
			'is_main' => '0',
			'realm' => $roster->data['server'],
			'region' => $roster->data['region']
		);
		$query2 = 'INSERT INTO `' . $roster->db->table('user_link', 'user') . '` ' . $roster->db->build_query('INSERT', $data2);
		$result2 = $roster->db->query($query2);
		
		$update_sql = "UPDATE `" . $roster->db->table('members') . "`"
					  . " SET `account_id` = '" . $user_id . "'"
					  . " WHERE `member_id` = '".$_POST['member_id']."';";
		$accid = $roster->db->query($update_sql);

	if(count($err))
	{
		$e = implode('<br />',$err);
	}
}

	$num1=$num2=$num3=null;
	function generateUniqueRandoms($min, $max, $count)  
	{
		if($count > $max)  
		{  // this prevents an infinite loop
			echo "ERROR: The array count is greater than the random number maximum.<br>\n";
			echo "Therefore, it is impossible to build an array of unique random numbers.<br>\n";
			break;
		}    
		$numArray = array('0'=>'','1'=>'','2'=>'');
		for($i = 0; $i < $count; $i++)
		{        
			$numArray[$i] = mt_rand($min,$max);         // set random number
			
			for($j = 0; $j < $count; $j++)                 // for each number, check for duplicates
			  if($j != $i)                                 // except for the one you are checking of course
				if($numArray[$i] == $numArray[$j])
				{
					$numArray[$i] = mt_rand(1,10);         // if duplicate, generate new random
					$j = 0;                                // go back through and check new number
				} 
		}
		return $numArray;
	}

		$x = generateUniqueRandoms(0, 18, 3);
		$r = implode(':',$x);
		list($num1,$num2,$num3) = explode(':',$r);

		$s = "
		$(document).ready(function () {  
			$('#xsubmit').attr('disabled', 'true');
			$('input#submit_btn').click(function(){
			//$('#pUsername').keyup(function () {
				var t = this;
				var char = $('input#pUsername').val();
				var validateUsername = $('#validateUsername');
				var pUsername = $('#pUsername');
				var pclass = $('input#class');
				var plevel = $('input#level');
				var prank = $('#rank');
				var pmember_id = $('#member_id');
				var ptitle = $('#title');
				var photo = $('#photo');
				var EQ1 = $('div#EQ1');
				var EQ2 = $('div#EQ2');
				var EQ3 = $('div#EQ3');
			

					if (this.timer) clearTimeout(this.timer);
					validateUsername.removeClass('error').html('<img src=\"".$roster->config['img_url']."canvas-loader.gif\" height=\"18\" width=\"18\" /> checking availability...');
					this.timer = setTimeout(function () {
						$.ajax({
							url: 'index.php?p=user-user-ajaxcvar',
							//url: 'index.php?p=ajaxcvar',
							data: 'action=check_username&server=".$roster->data['server']."&slot=".$num1.'-'.$num2.'-'.$num3."&guild_id=".$roster->data['guild_id']."&character=' + char,
							dataType: 'json',
							type: 'post',
							success: function (j) {
								if (j.ok)
								{
									validateUsername.html(j.msg);
									pUsername.val(char);
									$('div#EQ1').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ1);
									$('div#EQ2').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ2);
									$('div#EQ3').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ3);
									
									pclass.val(j.cla55);
									plevel.val(j.level);
									prank.val(j.rank);
									pmember_id.val(j.member_id);
									ptitle.html(j.title);
									
									photo.html('<img src=\"http://us.battle.net/static-render/us/'+j.thumb+'\" height=\"75\" width=\"75\" /></a>');
									
									$('#xsubmit').removeAttr('disabled');
									$('input#submit_btn').attr('disabled', 'true');
								}
								else
								{
									photo.html('<img src=\"http://us.battle.net/static-render/us/'+j.thumb+'\" height=\"75\" width=\"75\" /></a>');
									validateUsername.html(j.msg);
									$('div#EQ1').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ1);
									$('div#EQ2').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ2);
									$('div#EQ3').removeClass(\"RGaccepy RGdenyed RGquestion\").addClass(j.EEQQ3);
									pclass.val(j.cla55);
									plevel.val(j.level);
									prank.val(j.rank);
									ptitle.html(j.title);
									$('#xsubmit').attr('disabled', 'true');
								}
								
							}

						});
					}, 200);

			});
		});";

		roster_add_js($s,'inline');

	
	function _getItemSlot($value) {
		$ret = '';
		switch ($value) 
		{
			case 0: $ret = "Head";break;		case 1: $ret = "Neck";break;			case 2: $ret = "Shoulder";break;
			case 3: $ret = "Shirt";break;		case 4: $ret = "Chest";break;			case 5: $ret = "Waist";break;
			case 6: $ret = "Legs";break;		case 7: $ret = "Feet";break;			case 8: $ret = "Wrist";break;
			case 9: $ret = "Hands";break;		case 10: $ret = "Finger1";break;		case 11: $ret = "Finger2";break;
			case 12: $ret = "Trinket1";break;	case 13: $ret = "Trinket2";break;		case 14: $ret = "Back";break;
			case 15: $ret = "MainHand";break;	case 16: $ret = "SecondaryHand";break;	case 17: $ret = "Ranged";break;
			case 18: $ret = "Tabard";break;		
		}
		return $ret;
	}
	function _getItemSlotn($value) {
		$ret = '';
		switch ($value) 
		{
			case 0: $ret = "Head";break;		case 1: $ret = "Neck";break;			case 2: $ret = "Shoulder";break;
			case 3: $ret = "Shirt";break;		case 4: $ret = "Chest";break;			case 5: $ret = "Waist";break;
			case 6: $ret = "Legs";break;		case 7: $ret = "Feet";break;			case 8: $ret = "Wrist";break;
			case 9: $ret = "Hands";break;		case 10: $ret = "Finger 0";break;		case 11: $ret = "Finger 1";break;
			case 12: $ret = "Trinket 0";break;	case 13: $ret = "Trinket 1";break;		case 14: $ret = "Back";break;
			case 15: $ret = "MainHand";break;	case 16: $ret = "SecondaryHand";break;	case 17: $ret = "Ranged";break;
			case 18: $ret = "Tabard";break;		
		}
		return $ret;
	}

$form = 'userApp';
//$user->form->newForm('post', makelink('util-accounts-application'), $form, 'formClass', 4);

$user->form->newForm('post', makelink('user-user-alt'), $form, 'formClass', 4);



	$roster->tpl->assign_vars(array(
		'R_EQUIP_1'     => _getItemSlot($num1),
		'R_EQUIP_2'     => _getItemSlot($num2),
		'R_EQUIP_3'     => _getItemSlot($num3),
		'N_EQUIP_1'     => _getItemSlotn($num1),
		'N_EQUIP_2'     => _getItemSlotn($num2),
		'N_EQUIP_3'     => _getItemSlotn($num3),
		'R_IMAGE_PTH'	=> $roster->config['img_url'].'Interface/EmptyEquip/',
		//'R_Q_CHAR'     => @$_REQUEST['character'],
		//'R_Q_RANK'     => @$_REQUEST['rank'],
		'R_MSG_ERROR'	=> (!empty($e) ? $e : ''),
		'CHAR_AUTH'		=> $addon['config']['char_auth'],
		'CNAMETT' 		=> makeOverlib($roster->locale->act['cname_tt'],$roster->locale->act['cname'],'',1,'',',WRAP'),
		'CNAME' 		=> $roster->locale->act['cname'],
		'CLASSTT' 		=> makeOverlib($roster->locale->act['cclass_tt'],$roster->locale->act['cclass'],'',1,'',',WRAP'),
		'CLASS'			=> $roster->locale->act['cclass'],
		'LEVELTT' 		=> makeOverlib($roster->locale->act['clevel_tt'],$roster->locale->act['clevel'],'',1,'',',WRAP'),
		'LEVEL' 		=> $roster->locale->act['clevel'],
		'RANKTT' 		=> makeOverlib($roster->locale->act['cgrank_tt'],$roster->locale->act['cgrank'],'',1,'',',WRAP'),
		'RANK' 			=> $roster->locale->act['cgrank'],
		'XXX'			=> $user->form->getTableOfElements_3(1,$form),
		)
	);


/*
$roster->tpl->set_filenames(array(
	'alt' => $addon['basename'].'alt.html'
	)
);
$roster->tpl->display('alt');	
*/
$roster->tpl->set_filenames(array('alt' => $addon['basename'] . '/alt.html'));
$roster->tpl->display('alt');
			
?>