<?php


function GetUsername(){
	global $_COOKIE;
	$BigCookie = explode("|",$_COOKIE['UA']);
	return $BigCookie[0];
}



?>