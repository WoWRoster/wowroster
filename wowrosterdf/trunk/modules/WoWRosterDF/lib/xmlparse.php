<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

// XML parsing by Swipe
// http://wowroster.net

//DF security
if (!defined('CPG_NUKE')) { exit; }
//Roster security
/*
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}*/

class xmlParser
{
	var $xml_obj = null;
	var $output = array();
	var $message;

	function xmlParser()
	{
		$this->xml_obj = xml_parser_create('');

		xml_set_object( $this->xml_obj, $this );
		xml_set_character_data_handler( $this->xml_obj, 'dataHandler' );
		xml_set_element_handler( $this->xml_obj, 'startHandler', 'endHandler' );
	}

	function parse( $path )
	{
		if (function_exists('curl_init'))
		{
			$fp = curl_init( $path );
			$ch = 1;
		}
		else
		{
			$fp = @fopen( $path, 'r' );
			$ch = 0;
		}

		if ( !$fp )
		{
			$this->message = "Cannot open XML data file: $path";
			return false;
		}

		if ( !$ch )
		{
			while ( $data = fread( $fp, 4096 ) )
			{
				if ( !xml_parse( $this->xml_obj, $data, feof( $fp ) ) )
				{
					$this->message = sprintf( "XML error: %s at line %d",
					xml_error_string( xml_get_error_code( $this->xml_obj ) ),
					xml_get_current_line_number( $this->xml_obj ) );

					xml_parser_free( $this->xml_obj );
					return false;
				}
			}
		}
		else
		{
			curl_setopt($fp, CURLOPT_HEADER, 0);
			curl_setopt($fp, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($fp);
			if ( curl_errno($fp) )
			{
				$ch_err = 1;
			}
			else
			{
				$ch_err = 0;
			}

			curl_close($fp);

			if( !xml_parse( $this->xml_obj, $data, $ch_err ) )
			{
					$this->message = sprintf( "XML error: %s at line %d",
					xml_error_string( xml_get_error_code( $this->xml_obj ) ),
					xml_get_current_line_number( $this->xml_obj ) );

					xml_parser_free( $this->xml_obj );
					return false;
			}
		}
		return true;
	}

	function startHandler( $parser, $name, $attribs )
	{
		$_content = array( 'name' => $name );
		if ( !empty( $attribs ) )
			$_content['attrs'] = $attribs;
		array_push( $this->output, $_content );
	}

	function dataHandler( $parser, $data )
	{
		if( !empty( $data ) )
		{
			$_output_idx = count( $this->output );
			$this->output[$_output_idx - 1]['content'] = $data;
		}
	}

	function endHandler( $parser, $name )
	{
		if ( count( $this->output ) > 1 )
		{
			$_data = array_pop( $this->output );
			$_output_idx = count( $this->output );
			$this->output[$_output_idx - 1]['child'][] = $_data;
		}
	}
}
