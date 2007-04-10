<?php
// XML parsing by Swipe
// http://wowprofilers.com

class xmlParser {
	var $xml_obj = null;
	var $output = array();

	function xmlParser() {
		$this->xml_obj = xml_parser_create();
		xml_set_object( $this->xml_obj, $this );
		xml_set_character_data_handler( $this->xml_obj, 'dataHandler' );
		xml_set_element_handler( $this->xml_obj, 'startHandler', 'endHandler' );
	}

	function parse( $path ) {
		if ( !( $fp = fopen( $path, 'r' ) ) ) {
			die( "Cannot open XML data file: $path" );
			return false;
		}

		while ( $data = fread( $fp, 4096 ) )
			if ( !xml_parse( $this->xml_obj, $data, feof( $fp ) ) ) {
				die ( sprintf( "XML error: %s at line %d",
				xml_error_string( xml_get_error_code( $this->xml_obj ) ),
				xml_get_current_line_number( $this->xml_obj ) ) );
				xml_parser_free( $this->xml_obj );
			}
		return true;
	}

	function startHandler( $parser, $name, $attribs ) {
		$_content = array( 'name' => $name );
		if ( !empty( $attribs ) )
			$_content['attrs'] = $attribs;
		array_push( $this->output, $_content );
	}

	function dataHandler( $parser, $data ) {
		if( !empty( $data ) ) {
			$_output_idx = count( $this->output );
			$this->output[$_output_idx - 1]['content'] = $data;
		}
	}

	function endHandler( $parser, $name ) {
		if ( count( $this->output ) > 1 ) {
			$_data = array_pop( $this->output );
			$_output_idx = count( $this->output );
			$this->output[$_output_idx - 1]['child'][] = $_data;
		}
	}
}
?>