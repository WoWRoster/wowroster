<?php
	

// begin achievement functions
class achiv
{
		var $data=array();
	//var $data;
	var $equip = array();
	var $talentbuilds = array();
	var $talent_build_url = array();
	var $char, $guild, $server;
	var $class_id;
	var $armory_db;
	var $achi = array();
	var $crit = array();
	var $catss = array();
	var $locale;
	var $cat = array();
	var $memberID;
	var $icons=array();
	
	
	function builddata($data)
	{
		global $api, $roster, $addon;
		// build our structure
		$this->buildarch();
		//$this->buildcrit();
		$cat = $this->list_all();
		$e = 0;
		$achi = $this->achi;
		$interface='http://www.wowroster.net/Interface/Icons/';//Interface/Icons/';
		$imgpath = '/templates/default/achievements/images/';
		
		$achData = array();
		$achDate = array();
		$sqlquery2 = "SELECT * FROM `" . $roster->db->table('achievements', $addon['basename']) . "` WHERE `member_id` = '$this->memberID' ORDER BY `achie_date` DESC";
		$result2 = $roster->db->query($sqlquery2);
		$e = 0;
		while($row = $roster->db->fetch($result2))
		{
			$achData[] = $row['achie_id'];
			$achDate[$row['achie_id']] = $row['achie_date'];
			$e++;
		}
		foreach ($cat as $id => $title)
		{
			$roster->tpl->assign_block_vars('info',array(
							'ID'   => isset($title['sub'][$id]) ? 's'.$id : 's'.$id,
							'NAME' => $title['name'],
							'TOGGLER' => ' sub'.$id.''
						)
					);	
				$ach = '';	
			if( $ach != 'Name')
			{
				$k = array();
				foreach ($achi[$id] as $ach => $da)
				{
					if (isset($da['Name']) && is_numeric($da['Points']))
					{
						if ($this->iscomp($ach,$da['Name'],$achData))
						{
							$bg = $imgpath . 'achievement_bg.jpg';
							$shild = $complete = 1;
							$date = $this->convert_date($achDate[$ach]);
							$datex = $achDate[$ach];
							$crttt = '1';
						}
						else
						{
							$bg = $imgpath.'achievement_bg_locked.jpg';
							$shild = $complete = 0;
							$datex = $date = '';
							$crttt = '1';
						}
						if ($id == '81' && $complete != 0)
						{
							$shild = 2;
						}
						if ($id == '81' && $complete == 0)
						{
							$shild = 3;
						}
						
						if ($id == '81' && $complete == 0)
						{
						}
						else
						{
							$k[] = array(
								'BACKGROUND' => $bg,
								'NAME'       => $da['Name'],
								'DESC'       => $da['Desc'],
								'STATUS'     => $complete,
								'DATE'       => $date,
								'DATEX'       => $datex,
								'POINTS'     => $da['Points'],
								'CRITERIA'   => $crttt,
								'SHIELD'     => $shild,
								'ICON'       => $interface . $da['icon'] . '.png',
							);
							$this->icons[] = "'".$interface . $da['icon'] . ".png'";
						}
					}
				}
				//$this->sksort($h,'DATEX');
				$srt = $this->sortByOneKey($k, 'DATEX', false);
				//foreach ($h as $pices)
				foreach ($srt as $pices)
				{
					$roster->tpl->assign_block_vars('info.achv',$pices);
				}
				
			}
				
			if (isset($title['sub']) && is_array($title['sub']))
			{
				foreach ($title['sub'] as $ids => $r)
				{
					$roster->tpl->assign_block_vars('sub'.$id.'',array(
						'ID'       => 's'.$r['id'],
						'NAME'     => $r['name']
						)
					);
					
					if ($id == $ids)
					{
						$idd = $id;
					}
					else
					{
						$idd = 's' . $ids;
					}
						$roster->tpl->assign_block_vars('info',array(
							'ID'   => $idd,
							'NAME' => $r['name']
							)
						);
						$h = array();
					foreach ($achi[$ids] as $ach => $da)
					{
						if($ach != 'Name' && is_numeric($da['Points']))
						{
							if ($this->iscomp($ach,$da['Name'],$achData))
							{
								$bg = $imgpath . 'achievement_bg.jpg';
								$complete = 1;
								$date = $this->convert_date($achDate[$ach]);
								$datex = $achDate[$ach];
								$crttt = '1';
							}
							else
							{
								$bg = $imgpath.'achievement_bg_locked.jpg';
								$complete = 0;
								$datex = $date = '';
								$crttt = '1';
							}
							
						$h[] = array(
									'BACKGROUND' => $bg,
									'NAME'       => $da['Name'],
									'DESC'       => $da['Desc'],
									'STATUS'     => $complete,
									'DATE'       => $date,
									'DATEX'       => $datex,
									'POINTS'     => $da['Points'],
									'CRITERIA'   => $crttt,
									'SHIELD'     => '',
									'ICON'       => $interface . $da['icon'] . '.png',
							);
							$this->icons[] = "'".$interface . $da['icon'] . ".png'";
						}
					}
					//$this->sksort($h,'DATEX');
					$srt = $this->sortByOneKey($h, 'DATEX', false);
					//foreach ($h as $pices)
					foreach ($srt as $pices)
					{
						$roster->tpl->assign_block_vars('info.achv',$pices);
					}
				}
			}	
		}
			
		return true;
	}
	
	function buildcrittooltip($ach)
	{
		$error = false;
		$tt = '<table width="520"><tr>';
		$i = 0;
		$t = 2;
		foreach ($this->crit[$ach] as $id => $info)
		{
			if (empty($info['Desc']))
			{
				$error = true;
			}
			$s1 = '';
			$s2 = '';
			if ($info['complete'] == 'unlocked')
			{
				$s1 = '<font color="#1eff00">';
				$s2 = '</font>';
			}
			
			$tt .= '<td>'.$s1.'- '.$info['Desc'].''.$s2.'</td>';
			$i++;
			if ($i == $t)
			{
				$tt .='</tr><tr>';
				$i=0;
			}
		}
		$tt .= '</tr></table>';
		if (!$error)
		{
		return $tt;
		}
		else
		{
			return '1';
		}
	}
	function convert_date($date)
	{
		global $roster;
		$date = ($date / 1000);
		$date = date('D M jS Y, g:ia',$date);
		return $date;
	}
	
	function sortByOneKey(array $array, $key, $asc = true)
	{
		$result = array();
			
		$values = array();
		foreach ($array as $id => $value)
		{
			$values[$id] = isset($value[$key]) ? $value[$key] : '';
		}
			
		if ($asc) {
			asort($values);
		}
		else {
			arsort($values);
		}
			
		foreach ($values as $key => $value)
		{
			$result[$key] = $array[$key];
		}
			
		return $result;
	}


	function sksort(&$array, $subkey="id", $sort_ascending=false) 
	{

		if (count($array))
        $temp_array[key($array)] = array_shift($array);

		foreach($array as $key => $val){
			$offset = 0;
			$found = false;
			foreach($temp_array as $tmp_key => $tmp_val)
			{
				if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
				{
					$temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                                            array($key => $val),
                                            array_slice($temp_array,$offset)
                                          );
					$found = true;
				}
				$offset++;
			}
			if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
		}

		if ($sort_ascending) $array = array_reverse($temp_array);

		else $array = $temp_array;
	}


	function list_all() 
	{
		global $roster,$addon,$catss;

		$catss = array();
		$catss = $catss + $this->getcatsubs();
		$dlidd = "SELECT DISTINCT `achi_cate`, c_id,p_id FROM `" . $roster->db->table('achie', $addon['basename']) . "` order by `c_id` asc";
		$resultdl = $roster->db->query($dlidd);
		while($row = $roster->db->fetch($resultdl))
		{
			if ($row['p_id'] == '-1')
			{
				$catss[$row['c_id']]['name'] = $row['achi_cate'];
				$catss[$row['c_id']]['id'] = $row['c_id'];
				$main = $row['c_id'];
			}

		}

		return $catss;
	}
	
	function getcatsubs()
	{
		global $roster,$addon,$catss;

		$catss = array();
		$dlid = "SELECT * FROM `" . $roster->db->table('achie', $addon['basename']) . "` WHERE `p_id` != '-1'  order by `c_id` ASC";
		$resultd = $roster->db->query($dlid);
		while($row = $roster->db->fetch($resultd))
		{
			$catss[$row['p_id']]['sub'][$row['c_id']] = array('name' => $row['achi_cate'],'id' => $row['c_id']);
		}
		return $catss;
	}
	
	function buildarch()
	{
		global $roster,$addon,$catss;
		
		$query =	"SELECT * FROM `" . $roster->db->table('achie', $addon['basename']) . "` ORDER BY `c_id` DESC ";
		$ret = $roster->db->query($query);
		$this->achi = array();
		while( $row = $roster->db->fetch($ret) )
		{
			$this->achi[$row['c_id']]['Name'] = $row['achi_cate'];
			$this->achi[$row['c_id']][$row['achie_id']]=array(
					'Name'=>$row['achie_name'],
					'Desc'=>$row['achie_desc'],
					'Points'=>$row['achie_points'],
					'icon'=>$row['achie_icon'],
					'achi_ID'=>$row['achie_id']
				);
		}
	}
	
	function buildcrit()
	{
	global $roster,$addon,$catss;
		
		$sqlquery2 = "SELECT * FROM `" . $roster->db->table('criteria', $addon['basename']) . "` WHERE `member_id` = '$this->memberID' ORDER BY `crit_id` DESC";
		$result2 = $roster->db->query($sqlquery2);
		$e = 0;
		$critData = array();
		while($row2 = $roster->db->fetch($result2))
		{
			$critData[$row2['crit_id']] = array('status'=>'unlocked','Value'=>$row2['crit_value']);
			$e++;
		}	
		
		$query =	"SELECT * FROM `" . $roster->db->table('crit', $addon['basename']) . "` ORDER BY `crit_achie_id` DESC ";
		$ret = $roster->db->query($query);
		$this->crit = array();
		while( $row = $roster->db->fetch($ret) )
		{
			if ($row['crit_desc'] != '')
			{
				if (isset($critData[$row['crit_id']]) && $critData[$row['crit_id']]['status'] == 'unlocked')
				{
					$complete = 'unlocked';
				}
				else
				{
					$complete = 'locked';
				}
				$c = ($critData[$row['crit_id']]['status'] ? 'unlocked' : 'locked');
				$this->crit[$row['crit_achie_id']][]=array(
								'id'=>$row['crit_id'],
								'Desc'=>$row['crit_desc'],
								'Value'=>$critData[$row['crit_id']]['Value'],
								'complete' => $c
					);
			}
		}

	}
	
	function iscomp($ach_id,$ach_name,$achi_data)
	{
		if (in_array($ach_id, $achi_data))
		{
			return true;//'<font color="green">'.$ach_name.'</font>';
		}
		else
		{
			return false;//'<font color="blue">'.$ach_name.'</font>';
		}

	}

	function in_multiarray($elem, $array)
    {
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top)
        {
            if($array[$bottom] == $elem)
                return true;
            else 
                if(is_array($array[$bottom]))
                    if($this->in_multiarray($elem, ($array[$bottom])))
                        return true;
                    
            $bottom++;
        }        
        return false;
    }
	function out($char)
	{
		global $roster,$addon, $addon;
		$this->data = $char;
		$this->memberID = $roster->data['member_id'];
		$this->builddata($this->data);	
		
		$roster->tpl->assign_var('S_TALENT_TAB',false);
		$roster->tpl->assign_var('S_ACHIV',true);
		
		$roster->tpl->set_filenames(array('char' => $addon['basename'] . '/achiv.html'));
		return $roster->tpl->fetch('char');
	}
	
}



$js = '
$(document).ready(function () {
	$(\'#menu li\').children(\'ul\').slideUp(\'fast\');
	$(\'#amain > div#s81\').show();
	var parent;
	$(\'#menu li\').click(function(e) {
		if ($(this).parents(\'li\').size() > 0 ) 
		{
			//Has parent LI, so this is a child comment
			$(this).siblings().removeClass("selected2"); //Remove any "active" class
			$(this).addClass("selected2"); //Add "active" class to selected tab
			$(\'div#amain > div\').hide();
			$("#amain > div#"+this.id).show();
			parent = true;
			return true;
		}
		else
		{
			if (!parent)
			{
				//Has no parent LI, top level comment
				$(\'div#amain > div\').hide();
				$("li.menu_head").removeClass(" selected"); //Remove any "active" class
				$(this).addClass(" selected");
				$(\'#menu li\').children(\'ul\').slideUp(\'fast\');
				$("#amain > div#"+this.id).show();
				$(\'li.menu_head#\'+this.id+\' > ul#sub\').slideDown(\'400\').show();
			}
			parent=false;
		}
	});

});';
roster_add_js($js, 'inline', 'footer', false, false);


$sqlquery2 = "SELECT * FROM `".$roster->db->table('players')."` WHERE `member_id` = '".$roster->data['member_id']."'";
$result2 = $roster->db->query($sqlquery2);
$row = $roster->db->fetch($result2);

$char = array();
foreach($row as $var => $value)
{
$char[$var] = $value;
}

//print_r($char);
$achiv = new achiv;
$body =  $achiv->out($char);
$images = implode(",",$achiv->icons);
/*
$jjs = "
$.fn.preload = function() {
this.each(function(){
$('<img/>')[0].src = this;
});
}
// Usage:
$([".$images."]).preload();";
*
$jjs = "
function preload(arrayOfImages) {
	$(arrayOfImages).each(function(){
		$('<img/>')[0].src = this;
		// Alternatively you could use:
		// (new Image()).src = this;     
	}); 
}
preload([
".$images."
]);
";
roster_add_js($jjs, 'inline');
*/
echo $body;