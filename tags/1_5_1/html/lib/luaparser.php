<?php

function lua_parse( $filename ) {

	if (!$filename) {
		//print "You must specify the full path to your SavedVariables.lua file.</br>";
		//print "Please hit the \"Back\" button now on your browser and try again.</br></br>";
		
	} else {

	$stack = array( array( "",  array()) );
	$stack_pos = 0; 
	$lines = file( $filename );
	$last_line = "";
	foreach( $lines as $line ){
		if( substr( $line, -2, 1 ) == '\\' ) {
			$last_line .= substr($line, 0, -2) . "\n";
			continue;
		}
		$line = $last_line . $line;
		$last_line = "";
		if( strstr( $line, "=" ) ) {
			list($name, $value) = explode( "=", $line, 2 );
			$name = preg_replace( "/^\s*/","", $name );
			$name = preg_replace( "/\s*$/","", $name );
			if( substr($name,0,2) == "[\"" ) {
				$name = substr($name, 2, -2);
			} else if( substr($name, 0, 1) == "[" ) {
				$name = intval(substr($name, 1, -1));
			}
			$value = preg_replace( "/^\s*/", "", $value );
			$value = preg_replace( "/\s*$/", "", $value );
			if( $value == "{" ) {
				$stack_pos++;
				$stack[$stack_pos] = array($name, array());
			} else {
				if( preg_match("/^\"([^\"\\\\]|\\\\.)*\"/", $value, $matches ) ) {
					$value = substr($matches[0],1,-1);
					$value = preg_replace( "/\\\\(.)/", "\\1", $value );
				} else if( preg_match("/^-?[0-9]+\\.[0-9]+/", $value, $matches ) ) {
					$value = floatval($matches[0]);
				} else if( preg_match("/^-?[0-9+]+/", $value, $matches ) ) {
					$value = intval($matches[0]);
				} else if( preg_match("/^(True|False)/", $value, $matches ) ) {
					if( $matches[0] == "True" ) {
						$value = True;
					} else {
						$value = False;
					}
				} else if( preg_match("/^nil/", $value ) ) {
					$value = NULL;
				}
				$stack[$stack_pos][1][$name] = $value;
			}
		} else if( preg_match( "/^\s*}/", $line ) ) {
			$hash = $stack[$stack_pos];
			$stack_pos--;
			$stack[$stack_pos][1][$hash[0]] = $hash[1];
		}
	}
	return $stack[0][1];
	}
}