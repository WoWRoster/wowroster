<?php
	
	
	/*
	
if($_POST['submit']=='Register')
{
	// If the Register form has been submitted
	
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>32)
	{
		$err[]='Your username must be between 3 and 32 characters!';
	}
	
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	{
		$err[]='Your username contains invalid characters!';
	}
	
	if(!checkEmail($_POST['email']))
	{
		$err[]='Your email is not valid!';
	}
	
	if(!count($err))
	{
		// If there are no errors
		
		$pass = md5($_POST['password']);
		// Generate a random password
		
		$_POST['email'] = mysql_real_escape_string($_POST['email']);
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		// Escape the input data
		
		
		mysql_query("INSERT INTO ".USER_TABLE." (usr,pass,email,regIP,dt,access)
						VALUES(
						
							'".$_POST['username']."',
							'".$pass."',
							'".$_POST['email']."',
							'".$_SERVER['REMOTE_ADDR']."',
							NOW(),
							'7'
							
						)");
		
		if(mysql_affected_rows($link)==1)
		{
			send_mail(	'demo-test@tutorialzine.com',
						$_POST['email'],
						'Registration System Demo - Your New Password',
						'Your password is: '.$pass);

			$_SESSION['msg']['reg-success']='We sent you an email with your new password!';
		}
		else
		{
			$err[]='This username is already taken!';
		}
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
	
	header("Location: index.php");
	exit;
}
*/
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
		$('input#xsubmit').attr('disabled', 'true');
		$('input#submit_btn').click(function(){
		//$('#pUsername').keyup(function () {
			var t = this;
			var char = $('input#pUsername').val();
			var validateUsername = $('#validateUsername');
			var pUsername = $('#pUsername');
			var pclass = $('input#class');
			var plevel = $('input#level');
			var prank = $('input#rank');
			var ptitle = $('#title');
			var photo = $('#photo');
			var EQ1 = $('div#EQ1');
			var EQ2 = $('div#EQ2');
			var EQ3 = $('div#EQ3');
		

				if (this.timer) clearTimeout(this.timer);
				validateUsername.removeClass('error').html('<img src=\"".$roster->config['img_url']."canvas-loader.gif\" height=\"18\" width=\"18\" /> checking availability...');
				this.timer = setTimeout(function () {
					$.ajax({
						url: 'index.php?p=guild-main-ajaxcvar',
						data: 'action=check_username&slot=".$num1.'-'.$num2.'-'.$num3."&character=' + char,
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
								ptitle.val(j.title);
								
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
								ptitle.val(j.title);
								
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
			case 9: $ret = "Hands";break;		case 10: $ret = "Finger0";break;		case 11: $ret = "Finger1";break;
			case 12: $ret = "Trinket0";break;	case 13: $ret = "Trinket1";break;		case 14: $ret = "Back";break;
			case 15: $ret = "MainHand";break;	case 16: $ret = "Off Hand";break;	case 17: $ret = "Ranged";break;
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
	)
);


$roster->tpl->set_filenames(array(
	'register' => 'register.html'
	)
);
$roster->tpl->display('register');	
	
	
?>