<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>[Guild Roster] {$page_title}</title>
	<script type="text/javascript" src="include/overlib/overlib.js"></script>
	<script type="text/javascript" src="themes/{$roster_conf.theme}/main_js.js"></script>
	<link href="themes/{$roster_conf.theme}/css_style.css" rel="stylesheet" type="text/css" />
</head>


<body class="roster_body">


<!-- Set OverLib DIV -->
<div id="overDiv" style="position:absolute;visibility:hidden;z-index:1000;"></div>
<!-- End OverLib DIV -->


<!-- Header Logo -->
<div id="roster_header">
	<img src="themes/{$roster_conf.theme}/images/headerlogo.jpg" alt="Roster Logo" />
</div>
<!-- End Header Logo -->


<!-- Roster Menus Container -->
<div id="roster_menu">
{include file='roster_menu.tpl'}
</div>
<!-- END Roster Menus Container -->


<!-- Roster Main Container -->
<div id="roster_main">

<!-- End Roster Header -->
