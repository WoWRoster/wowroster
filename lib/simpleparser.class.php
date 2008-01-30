<?php
/**
 * WoWRoster.net WoWRoster
 *
 * WoWRoster Simple Class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.9.9
 * @package    WoWRoster
 * @subpackage RosterClass
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

require_once(ROSTER_LIB . 'simple.class.php');

/**
 * WoWRoster Simple Parser
 *
 * Allows converion of XML data to a simple class object
 *
 */
class SimpleParser
{
	var $parser;
	var $error_code;
	var $error_string;
	var $current_line;
	var $current_column;
	var $data;
	var $datas = array();

	/**
 	 * Parse method
 	 * parses XML data and converts it to a simple class object
	 *
	 * @param string $data
	 * @return object
	 */
	function parse( $data )
	{
		$this->data = array();
		$this->datas = array();
		$this->parser = xml_parser_create('UTF-8'); //'UTF-8'
		xml_set_object($this->parser, $this);
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_set_element_handler($this->parser, 'tag_open', 'tag_close');
		xml_set_character_data_handler($this->parser, 'cdata');

		if( !xml_parse($this->parser, $data) )
		{
			//$this->data = array;
			$this->data = new SimpleClass;
			$this->error_code = xml_get_error_code($this->parser);
			$this->error_string = xml_error_string($this->error_code);
			$this->current_line = xml_get_current_line_number($this->parser);
			$this->current_column = xml_get_current_column_number($this->parser);
		}
		else
		{
			$this->data = $this->datas[0];
		}

		xml_parser_free($this->parser);

		return $this->data;
	}

	/**
 	 * tag_open method
	 *
	 * @param string $parser
	 * @param string $tag
	 * @param array $attribs
	 */
	function tag_open( $parser , $tag , $attribs )
	{
		$node = new SimpleClass();
		$node->setArray($attribs);
		$node->setProp("_TAGNAME", $tag);
		$this->datas[] = $node;
	}

	/**
 	 * cdata method
	 *
	 * @param string $parser
	 * @param string $cdata
	 */
	function cdata( $parser , $cdata )
	{
		$this->datas[count($this->datas)-1]->setProp("_CDATA", trim($cdata));
	}

	/**
 	 * tag_close method
	 *
	 * @param string $parser
	 * @param string $tag
	 */
	function tag_close( $parser , $tag )
	{
		if( count($this->datas) > 1 )
		{
			$child = array_pop($this->datas);

			if( count($this->datas) > 0 )
			{
				$parent = &$this->datas[count($this->datas)-1];
				$tag = $child->_TAGNAME;

				if( $parent->hasProp($tag) )
				{
					if( is_array($parent->$tag) )
					{
						//Add to children array
						$array = &$parent->$tag;
						$array[] = $child;
					}
					else
					{
						//Convert node to an array
						$children = array();
						$children[] = $parent->$tag;
						$children[] = $child;
						$parent->$tag = $children;
					}
				}
				else
				{
					$parent->setProp($tag, $child);
				}
			}
		}
	}
}
