<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Light weight Xml Parser class using php's xml handling functions
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    WoWRoster
 * @subpackage XMLParser
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Light weight Xml Parser class using php's xml handling functions
 *
 * @package    WoWRoster
 * @subpackage XMLParser
 */
class XmlParser
{
	var $parser;
	var $errorCode;
	var $errorString;
	var $currentLine;
	var $currentColumn;
	var $data = array();
	var $datas = array();

	function XmlParser( $encoding = 'UTF-8' )
	{
		$this->__construct($encoding);
	}

	function __construct( $encoding = 'UTF-8' )
	{
		$this->parser = xml_parser_create($encoding);
		xml_set_object($this->parser, $this);
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_set_element_handler($this->parser, 'StartElementHandler', 'EndElementHandler');
		xml_set_character_data_handler($this->parser, 'CharacterDataHandler');
	}

	function Parse( $data )
	{
		if( !xml_parse($this->parser, $data) )
		{
			$this->data = array();
			$this->errorCode = xml_get_error_code($this->parser);
			$this->errorString = xml_error_string($this->errorCode);
			$this->currentLine = xml_get_current_line_number($this->parser);
			$this->currentColumn = xml_get_current_column_number($this->parser);
		}
		else
		{
			$this->data = $this->data['child'];
		}
		xml_parser_free($this->parser);
	}

	function startElementHandler( $parser , $tag , $attribs )
	{
		$this->data['child'][$tag][] = array(
			'data' => '',
			'attribs' => $attribs,
			'child' => array()
		);
		$this->datas[] = & $this->data;
		$this->data = & $this->data['child'][$tag][count($this->data['child'][$tag]) - 1];
	}

	function CharacterDataHandler( $parser , $data )
	{
		$this->data['data'] .= trim($data);
	}

	function EndElementHandler( $parser , $tag )
	{
		$this->data = & $this->datas[count($this->datas)-1];
		array_pop($this->datas);
	}

	function getParsedData()
	{
		return $this->data;
	}
}
