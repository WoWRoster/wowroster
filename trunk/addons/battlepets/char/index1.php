<?php


$functions = array(

		'LOOT' => 'event_LOOT',
		'ACHIEVEMENT' => 'event_ACHIEVEMENT',
		'CRITERIA' => 'event_CRITERIA',
		'BOSSKILL' => 'event_BOSSKILL',

		);
function convert_date($date)
	{
		$date = date('Y-m-d H:i:s',$date);
		return $date;
	}
	
function event_LOOT( $data )
{
	global $roster, $tooltips;
	
	require_once (ROSTER_LIB . 'item.php');
	//$x = new item();
	// lets be fancy now...
	$item = $roster->api->Data->getItemInfo($data['itemId']);
	$item_color = $roster->api->Data->_setQualityc($item['quality']);
	$html_tooltip = $roster->api->Item->item($item,null,null);
	$i = array();
	$i['item_id'] 			= $item['id'].':0:0:0:0:0';
	$i['item_name'] 		= $item['name'];
	$i['item_level'] 		= $item['itemLevel'];
	$i['level'] 			= $item['requiredLevel'];
	$i['item_texture'] 		= $item['icon'];
	$i['item_tooltip']		= $html_tooltip;
	$i['item_color'] 		= $item_color;
	$i['item_quantity'] 	= $item['quality'];
	$i['item_slot'] 				= '';
	$i['item_parent'] 			= '';
	$i['member_id'] 		= '';
	$x = new item($i,'full');
	
	$it = $x->html_tooltip;
	$item_id = $item['id'];
	
	$tooltip = makeOverlib($it, '', '' , 2, '', ', WIDTH, 325');

	$num_of_tips = (count($tooltips)+1);
	$linktip = '';

	foreach( $roster->locale->wordings[$roster->config['locale']]['itemlinks'] as $key => $ilink )
	{
		$linktip .= '<a href="' . $ilink . $item_id . '" target="_blank">' . $key . '</a><br />';
	}
	setTooltip($num_of_tips, $linktip);
	setTooltip('itemlink', $roster->locale->wordings[$roster->config['locale']]['itemlink']);

	$linktip = ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';
	$ts = ($data['timestamp'] / 1000);
/*8	echo 'Obtained <span style="color:#' . $item_color . ';font-weight:bold;text-align: center;" ' . $tooltip . $linktip . '>'.
	'<div class="item-sm"><img src="http://www.wowroster.net/Interface/Icons/'.$item['icon'].'.png" /><span class="mask"></span></a></div>' . $item['name'] . '</span><br>';*/
	echo '<li>
			<dl>
				<dd>
					<a href="http://battle.net/wow/en/item/'.$item['id'].'" target"_blank">
					<span  class="icon-frame frame-36" >
					<img src="http://www.wowroster.net/Interface/Icons/'.$item['icon'].'.png" />
					</span>
					</a>
					Obtained <span style="color:#' . $item_color . ';font-weight:bold;text-align: center;" ' . $tooltip . $linktip . '>' . $item['name'] . '</span></a>.
				</dd>
				<dt>'.convert_date($ts).'</dt>
			</dl>
			</li>';
}
function event_ACHIEVEMENT( $data )
{
	$tooltip_text = '<div style="width:100%;style="color:#FFB100""><span style="float:right;">' . $data['achievement']['points'] . ' Points</span>' . $data['achievement']['title'] . '</div><br>' . $data['achievement']['description'] . '';
	$crit='';
	if ($data['featOfStrength'] != 1)
	{
	$crit .= '<br><div class="meta-achievements"><ul>';
	foreach ($data['achievement']['criteria'] as $r => $d)
	{
		$crit .= '<li>'.$d['description'].'</li>';
	}
	$crit .= '</ul></div>';
	}
	$tooltip_text .= $crit;
	//echo 'Earned the achievement "'.$data['achievement']['title'].'"<br>';
	$tooltip = makeOverlib($tooltip_text, '', '' , 0, '', ', WIDTH, 325');
	$ts = ($data['timestamp'] / 1000);
	echo '<li class="ach ">
	<dl>
		<dd>
			<span  class="icon-frame frame-36" >
				<img src="http://www.wowroster.net/Interface/Icons/'.$data['achievement']['icon'].'.png" />
			</span>
			Earned the achievement <span style="color:#FFB100" ' . $tooltip . '>'.$data['achievement']['title'].'</span> for '.$data['achievement']['points'].' points.
		</dd>
		<dt>'.convert_date($ts).'</dt>
	</dl>
	</li>
';
}
function event_CRITERIA( $data )
{
	//echo 'Completed step "'.$data['criteria']['description'].'" in '.$data['achievement']['title'].'<br>';
	$tooltip_text = '<div style="width:100%;style="color:#FFB100""><span style="float:right;">' . $data['achievement']['points'] . ' Points</span>' . $data['achievement']['title'] . '</div><br>' . $data['achievement']['description'] . '';
	$crit='';
	if ($data['featOfStrength'] != 1)
	{
	$crit .= '<br><div class="meta-achievements"><ul>';
	foreach ($data['achievement']['criteria'] as $r => $d)
	{
		$crit .= '<li>'.$d['description'].'</li>';
	}
	$crit .= '</ul></div>';
	}
	$tooltip_text .= $crit;
	//echo 'Earned the achievement "'.$data['achievement']['title'].'"<br>';
	$tooltip = makeOverlib($tooltip_text, '', '' , 0, '', ', WIDTH, 325');
	$ts = ($data['timestamp'] / 1000);
	echo '<li class="crit">
	<dl>
		<dd>
			<a href="achievement#96:15070:a5865" class="icon" data-achievement="5865"></a>
			Completed step <strong>'.$data['criteria']['description'].'</strong> of achievement <span style="color:#FFB100" ' . $tooltip . '>'.$data['achievement']['title'].'</span>.
		</dd>
		<dt>'.convert_date($ts).'</dt>
	</dl>
</li>';
}
function event_BOSSKILL( $data )
{
	//echo 'has killed '.$data['name'].' ('.$data['quantity'].')<br>';
	$ts = ($data['timestamp'] / 1000);
	echo '<li class="ach ">
	<dl>
		<dd>
			<span  class="icon-frame frame-36" >
				<img src="http://www.wowroster.net/Interface/Icons/'.$data['achievement']['icon'].'.png" />
			</span>
			'.$data['quantity'].' <span style="color:#FFB100">'.$data['achievement']['title'].'</span> Kills.
		</dd>
		<dt>'.convert_date($ts).'</dt>
	</dl>
	</li>
';
}
roster_add_css($addon['dir'] . 'style.css','module');

	$member_name = $roster->data['name'];
	$member_realm = $roster->data['server'];
	$member_str = $member_name . '@' . $roster->data['region'] . '-' . $member_realm;

$a = $roster->api->Char->getCharInfo($member_realm,$member_name,'16');
//echo 'Current feed info for '.$member_str.'<br><hr><br>';
echo '<div class="container">
	<div class="tier-1-a">
		<div class="tier-1-b">
			<div class="tier-1-c">
				<div class="tier-1-title">Activity Feed for '.$member_str.'</div>
				<div class="tier-2-a">
					<div class="tier-2-b">';
$type =  array();
echo '
<ul class="activity-feed">';

foreach ($a['feed'] as $event => $info)
{
	//$type[$info['type']]['count']++;
	//echo $info['type'].'<br>';
	$functions[$info['type']]($info);

}
echo '</ul></div></div></div></div></div></div>';
?>