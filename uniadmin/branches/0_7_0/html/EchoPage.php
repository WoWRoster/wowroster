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

<span class='page_title'>UniAdmin Panel v".$config['UAVer']." - $subTitle</span>
<br />
".$loginForm."
<br />";

	if( !empty($uamessages) && is_array($uamessages) )
	{
		echo "<table class='uuTABLE' width='30%'>
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
		echo "<table class='uuTABLE' width='30%'>
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

if( isset($config['menu']) )
{
	echo "

Synchronization URL (click to verify):
 <a href='".$config['IntLocation']."' target='_blank'><span style='color:red;'>".$config['IntLocation']."</span></a>
<br />
<br />

<table width='100%' border='0' cellspacing='0' cellpadding='4'>
<tr>
<td valign='top' width='145'>
".$config['menu']."
</td>
<td valign='top'>
$body
</td>
</tr>
</table>";
}
else
{
	echo $body;
}


echo "
</body>
</html>";
}

?>