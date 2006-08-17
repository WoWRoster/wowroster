<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function debug($debugString)
{
	global $uadebug;

	$uadebug[] = $debugString;
}

function message($messageString)
{
	global $uamessages;

	$uamessages[] = $messageString;
}

?>