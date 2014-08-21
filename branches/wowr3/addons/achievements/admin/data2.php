<?php
	
	require_once (ROSTER_LIB . 'update.lib.php');
	$update = new update();	

if (isset($_POST['process']) && $_POST['process'] == 'process')
{
	$a = $roster->api->Data->getGuildAchieInfo();
	$rx = 0;
	$rc = 0;
	$q = "TRUNCATE TABLE `" . $roster->db->table('g_achie', $addon['basename']) . "`;";
	$r = $roster->db->query($q);
	
	$q = "TRUNCATE TABLE `" . $roster->db->table('g_crit', $addon['basename']) . "`;";
	$r = $roster->db->query($q);
	
	foreach($a['achievements'] as $order => $cat)
	{
		//echo $cat['name'].' - '.$cat['id'].'<br>';
		if (isset($cat['achievements']))
		{
			foreach($cat['achievements'] as $d => $achi)
			{
				$tooltip = '<div style="width:100%;style="color:#FFB100""><span style="float:right;">' . $achi['points'] . ' Points</span>' . $achi['title'] . '</div><br>' . $achi['description'] . '';
				$crit='';
				$crit .= '<br><div class="meta-achievements"><ul>';
				foreach ($achi['criteria'] as $r => $d)
				{
					$crit .= '<li><div id="crt'.$d['id'].'">'.$d['description'].'</div></li>';
					$update->reset_values();
					$update->add_value('c_id',				$cat['id']);
					$update->add_value('p_id',				'-1');
					$update->add_value('crit_achie_id',	$achi['id']);
					$update->add_value('crit_id',		$d['id']);
					$update->add_value('crit_desc',		$d['description']);
					$querystr = "INSERT INTO `" . $roster->db->table('g_crit', $addon['basename']) . "` SET " . $update->assignstr;
					$result = $roster->db->query($querystr);
					//echo $querystr.'<br>';
					$rc++;
				}
				$crit .= '</ul></div>';
					
				$tooltip .= $crit;
					
				$update->reset_values();
				$update->add_value('achie_name',		$achi['title']);
				$update->add_value('achie_desc',		$achi['description']);
				$update->add_value('achie_points',		$achi['points']);
				$update->add_value('achie_id',			$achi['id']);
				$update->add_value('achie_icon',		$achi['icon']);
				$update->add_value('achie_tooltip',		$tooltip);
				$update->add_value('achie_isAccount',	$achi['accountWide']);
				$update->add_value('factionId',			$achi['factionId']);
				$update->add_value('c_id',				$cat['id']);
				$update->add_value('p_id',				'-1');
				$update->add_value('achi_cate',			$cat['name']);
				
				$querystr = "INSERT INTO `" . $roster->db->table('g_achie', $addon['basename']) . "` SET " . $update->assignstr;
				$result = $roster->db->query($querystr);
				//echo$querystr.'<br>';
				$rx++;
				//*/
			}
		}
		if (isset($cat['categories']))
		{
			foreach($cat['categories'] as $corder => $sub)
			{
				//echo '--'.$sub['name'].' - '.$sub['id'].'<br>';
				foreach($sub['achievements'] as $d => $achi)
				{	
					$tooltip = '<div style="width:100%;style="color:#FFB100""><span style="float:right;">' . $achi['points'] . ' Points</span>' . $achi['title'] . '</div><br>' . $achi['description'] . '';
					$crit='';
					$crit .= '<br><div class="meta-achievements"><ul>';
					foreach ($achi['criteria'] as $r => $d)
					{
						$crit .= '<li>'.$d['description'].'</li>';
						
						$update->reset_values();
						$update->add_value('c_id',				$sub['id']);
						$update->add_value('p_id',				$cat['id']);
						$update->add_value('crit_achie_id',	$achi['id']);
						$update->add_value('crit_id',		$d['id']);
						$update->add_value('crit_desc',		$d['description']);
						$querystr = "INSERT INTO `" . $roster->db->table('g_crit', $addon['basename']) . "` SET " . $update->assignstr;
						$result = $roster->db->query($querystr);
						//echo $querystr.'<br>';
						$rc++;
					}
					$crit .= '</ul></div>';
						
					$tooltip .= $crit;
					
					$update->reset_values();
					$update->add_value('achie_name',		$achi['title']);
					$update->add_value('achie_desc',		$achi['description']);
					$update->add_value('achie_points',		$achi['points']);
					$update->add_value('achie_id',			$achi['id']);
					$update->add_value('achie_icon',		$achi['icon']);
					$update->add_value('achie_tooltip',		$tooltip);
					$update->add_value('achie_isAccount',	$achi['accountWide']);
					$update->add_value('factionId',			$achi['factionId']);
					$update->add_value('c_id',				$sub['id']);
					$update->add_value('p_id',				$cat['id']);
					$update->add_value('achi_cate',			$sub['name']);
					
					$querystr = "INSERT INTO `" . $roster->db->table('g_achie', $addon['basename']) . "` SET " . $update->assignstr;
					$result = $roster->db->query($querystr);
					//echo $querystr.'<br>';
					$rx++;
					//*/
				}
			}
		}
	}
	//echo 'Achievements '.$rx.'<br>Criteria '.$rc.' Updated<br>';
}
	$querystra = "SELECT * FROM `" . $roster->db->table('g_achie', $addon['basename']) . "`;";
	$resulta = $roster->db->query($querystra);
	$classr = $roster->db->num_rows($resulta);
	
	$querystrb = "SELECT * FROM `" . $roster->db->table('g_crit', $addon['basename']) . "`;";
	$resultb = $roster->db->query($querystrb);
	$classb = $roster->db->num_rows($resultb);
	
	
	$roster->tpl->assign_vars(array(
			'A_ROW'	=> $classr,
			'C_ROW'	=> $classb,
		)
	);
	/**
 * Make our menu from the config api
 */
// ----[ Set the tablename and create the config class ]----
include(ROSTER_LIB . 'config.lib.php');
$config = new roster_config( $roster->db->table('addon_config'), '`addon_id` = "' . $addon['addon_id'] . '"' );

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build the page items using lib functions ]---------
$menu .= $config->buildConfigMenu('rostercp-addon-' . $addon['basename']);

$roster->tpl->set_filenames(array('body' => $addon['basename'].'/admin.html'));
$body = $roster->tpl->fetch('body');
