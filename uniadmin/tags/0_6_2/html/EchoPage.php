<?php

function EchoPage($body, $subTitle = 'Index'){
	global $config;
	echo "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
	<head>
		<title>UniUpdater Administration Panel Ver .".$config['UAVer']." - $subTitle</title>
		<script type='text/javascript' src='./overlib/overlib.js'><!-- overLIB (c) Erik Bosrup --></script>
		<script type='text/javascript' src='./overlib/overlib_hideform.js'></script>
	".$config['css1']."
	</head>
	<body>
	".$loginForm."



		<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div>
		".$config['header']."
		$body
	</body>
</html>";
}

?>