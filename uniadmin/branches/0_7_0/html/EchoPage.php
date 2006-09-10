<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function EchoPage($body, $subTitle = 'Index')
{
	global $config, $loginForm, $uamessages, $uadebug;

	echo "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
	<title>UniAdmin Panel v".$config['UAVer']." - $subTitle</title>
	<link rel='stylesheet' type='text/css' media='screen' href='./css.php'>
	<script type='text/javascript' src='./overlib/overlib.js'><!-- overLIB (c) Erik Bosrup --></script>
	<script type='text/javascript' src='./overlib/overlib_hideform.js'></script>
</head>
<body>

<table width='100%' border='0' cellspacing='1' cellpadding='2'>
  <tr>
    <td width='201' valign='top'><a href='".UA_INDEXPAGE."'><img src='images/logo.png' alt='UniAdmin' /></a></td>
    <td width='100%' valign='top'>
      <span class='maintitle'>UniAdmin v".$config['UAVer']."</span><br />
      ".(isset($subTitle) ? "<span class='subtitle'>".$subTitle.'</span>' : '')."<br />
      ".$loginForm."<br />";

	if( isset($config['menu']) )
	{
		echo "
	<span class='ua_menu'>
".$config['menu']."
	</span>
	<br /><br />
	<span class='sync_url'>
		Synchronization URL (click to verify):
		<a href='".$config['IntLocation']."' target='_blank'>".$config['IntLocation']."</a>
	</span>";
	}

	echo "
    </td>
  </tr>
</table>
<br />
<br />";

	if( !empty($uamessages) && is_array($uamessages) )
	{
		echo "<table class='uuTABLE' width='30%' align='center'>
	<tr>
		<th class='tableHeader'>Messages</th>
	</tr>
";
		$i=0;
		foreach( $uamessages as $message )
		{
			if($i % 2)
				$tdClass = 'data2';
			else
				$tdClass = 'data1';

			echo "<tr>\n<td class='$tdClass'>$message</td>\n</tr>\n";

			$i++;
		}
		echo "</table>\n<br />\n";
	}

	if( !empty($uadebug) && is_array($uadebug) )
	{
		echo "<table class='uuTABLE' width='30%' align='center'>
	<tr>
		<th class='debugHeader'>Debug</th>
	</tr>
";
		$i=0;
		foreach( $uadebug as $message )
		{
			if($i % 2)
				$tdClass = 'data2';
			else
				$tdClass = 'data1';

			echo "<tr>\n<td class='$tdClass'>$message</td>\n</tr>\n";

			$i++;
		}
		echo "</table>\n<br />\n";
	}

	echo $body.
"
<br />
<!--
    If you use this software and find it to be useful, we ask that you retain the copyright notice below.
//-->
<div class='ua_hr'><hr /></div>
<br />
<div align='center'>
	<span class='copyright'><a href='http://www.wowroster.net/' target='_blank'>UniAdmin</a> v".$config['UAVer']."<br />
	&copy; 2006 The WoWRoster Dev Team</span><br />
</div>
</body>
</html>";
}

?>