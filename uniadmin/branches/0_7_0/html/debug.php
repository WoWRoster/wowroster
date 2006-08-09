<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function debug($debugString)
{
	global $config;

	if ($config['debugSetting'])
	{
		echo "<span style='color:red;'><b>DEBUG</b>:</span> $debugString<br>";
	}
}

?>