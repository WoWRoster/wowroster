<?php

function EchoPage($body, $subTitle = 'Index'){
	global $config;
	echo "
<html>
	<head>
		<title>UniUpdater Administration Panel Ver .".$config['UAVer']." - $subTitle</title>
		<script type='text/javascript' src='./overlib/overlib.js'><!-- overLIB (c) Erik Bosrup --></script>
	</head>
	".$config['css1']."
	<body>
	

  
		<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div>
		".$config['header']."
		$body
	</body>
</html>";
}

?>