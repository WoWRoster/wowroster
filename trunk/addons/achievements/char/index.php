<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Achievement info
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: index.php 509 2010-02-17 05:43:24Z Ulminia $
 * @link       http://ulminia.zenutech.com
 * @package    Achievements
 */

include( $addon['dir'] . 'inc/functions.php' );
$fachv = new achv;

$data = $fachv->getConfigDatamod($roster->data['member_id']);
$data2 = $fachv->getConfigDatamod2($roster->data['member_id']);

//echo '<pre>';
//print_r($data2);
//aprint($data);
$first_tab = ' class="selected"';
//$menu .= '<li class="selected">';
$imgext = $roster->config['img_suffix'];
$e = '0';
$xxx = '';
$sx = '0';

if (isset($_GET['cat']))
{
	$catee = $_GET['cat'];
}
else
{
	$catee = '00';
}  
$roster->tpl->assign_block_vars('menue',array(
	'ID'       => '00',
	'LINK'     => makelink('&amp;cat=00'),
	'NAME'     => $roster->locale->act['Summary'],
	'SELECTED' => true,
	)
);

// this will rebuild the status page for us......
$status = array();

$total='0';
foreach($data as $catagory => $cid)
{
	$catt='0';
	$catc='0';
	foreach($cid as $achv => $achv_info)
	{
		$sx++;

		$roster->tpl->assign_block_vars('menue',array(
			'ID'       => $catagory,
			'LINK'     => makelink('&amp;cat=' . $catagory),
			'NAME'     => htmlspecialchars($roster->locale->act[$achv]),
			'SELECTED' => (isset($sx) && $sx == 0 ? true : false)
			)
		);

		$status[$catagory]= array();
		$e++;

		$roster->tpl->assign_block_vars('body',array(
			'ID'   => $catagory,
			'NAME' => $achv,
			'MEN'  => $e,
			)
		);

		$sxx = '0';

		foreach ($achv_info as $achva => $dat)
		{
			$sxx++;

			if ($achva == $achv)
			{
				$idd = 's' . $catagory;
			}
			else
			{
				$idd = 's' . $dat['menue'];
			}

			$roster->tpl->assign_block_vars('sub'.$catagory.'',array(
				'ID'       => $idd,
				'NAME'     => $achva,
				'SELECTED' => (isset($sxx) && $sxx == 1 ? true : false)
				)
			);

			$roster->tpl->assign_block_vars('body.menue2',array(
				'ID'       => 's' . $dat['menue'],
				'NAME'     => $achva,
				'SELECTED' => (isset($sxx) && $sxx == 1 ? true : false)
				)
			);

			$roster->tpl->assign_block_vars('body.info',array(
				'ID'   => 's' . $dat['menue'],
				'NAME' => $achva
				)
			);


			foreach ($dat['info'] as $a =>$b)
			{
				$xxx++;
				$catt++;
				$total++;
				if ($b['achv_complete'] == '1')
				{
					$bg = $addon['tpl_image_path'] . 'achievement_bg.jpg';
					$complete = 1;
					$catc++;
				}
				if ($b['achv_complete'] == '0')
				{
					$bg = $addon['tpl_image_path'] . 'achievement_bg_locked.jpg';
					$complete = 0;
				}

				$d = explode('<br>', $b['achv_criteria']);
				$ct = count($d);

				$u = '0';
				$achvg = '<td>';
				foreach($d as $g)
				{
					if (preg_match('/( Completed )/i', $g))
					{
//						echo 'A match was found.';
						$color = '#7eff00';
					}
					else 
					{
//						echo 'A match was not found.';
						$color = '#4169E1';
					}
					$achvg .= '<span style="color:' . $color . ';">' . $g . '</span><br />';
					$u++;
					if ($u == round(($ct/2)))
					{
						$achvg .= '</td><td>';
					}
				}

				$achvg .= '</td>';
				if ($b['achv_date'] != '000-00-00')
				{
					$dat = $b['achv_date'];
				}
				else
				{
					$dat = '';
				}

//				echo $b['achv_title'] . '<br />';

				if (isset($b['achv_progress']))
				{
					$bar = true;
				}
				else
				{
					$bar = false;
				}

				$roster->tpl->assign_block_vars('body.info.achv',array(
					//'ID'       => 's' . $dat['menue'],
					'IDS'        => $xxx,
					'BACKGROUND' => $bg,
					'NAME'       => stripslashes($b['achv_title']),
					'DESC'       => stripslashes($b['achv_disc']),
					'STATUS'     => $complete,
					'DATE'       => $dat,
					'POINTS'     => $b['achv_points'],
					'CRITERIA'   => $achvg,
					'SHIELD'     => $addon['tpl_image_path'],
					'BAR'        => $bar,
					'ICON'       => $roster->config['interface_url'] . 'Interface/Icons/' . strtolower($b['achv_icon']) . '.' . $imgext,
					)
				);

				if (isset($b['achv_progress']))
				{
//					echo '<pre>';
//					print_r($b);

					$wdth = '';

					$roster->tpl->assign_block_vars('body.info.achv.bar',array(
						'WIDTHX' => $b['achv_progress_width'],
						'STANDINGX' => $b['achv_progress'],
						)
					);
				}
			}
		}

		$status[$catagory]['name'] = $achv;
		$status[$catagory]['complete'] = $catc;
		$status[$catagory]['total'] = $catt;
	}
}

$roster->tpl->assign_block_vars('body2',array(
	'ID' => '00',
	'NAME' => $roster->locale->act['Summary'],
	'TOTAL' => $total,
	'MEN' => '00',
	'IMG_PATH' => $addon['url_path'],
	'RECENT' => $roster->locale->act['Recent'],
	)
);

foreach($status as $cat => $datx)
{
	$c = $datx['complete'];
	$t = $datx['total'];
//	$width=$achv->bar_width($c,$t);

	$roster->tpl->assign_block_vars('body2.' . $cat,array(
		'NAME'     => $datx['name'],
		'WIDTH'    => $fachv->bar_width($c,$t),
		'STANDING' => $datx['complete'] . ' / ' . $datx['total'],
		)
	);
}

$roster->tpl->set_handle('body', $addon['basename'] . '/index.html');
$roster->tpl->display('body');