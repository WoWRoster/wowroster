<?php
function debug($debugString){
	global $config;
	if ($config['debugSetting']){
		echo "<font color='red'><b>DEBUG</b>:</font> $debugString<br>";
	}
}

?>