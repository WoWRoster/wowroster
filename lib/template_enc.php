<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Template Parser
 * Modified from the EQDkp Project
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage Template
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class RosterTplEncode
{
	// The all seeing all doing compile method. Parts are inspired by or directly
	// from Smarty
	function compile( $code , $no_echo = false , $echo_var = '' )
	{
		// Remove any "loose" php ... we want to give admins the ability
		// to switch on/off PHP for a given template. Allowing unchecked
		// php is a no-no. There is a potential issue here in that non-php
		// content may be removed ... however designers should use entities
		// if they wish to display < and >
		$match_php_tags = array('#\<\?php .*?\?\>#is', '#\<\script language="php"\>.*?\<\/script\>#is', '#\<\?.*?\?\>#s', '#\<%.*?%\>#s');
		$code = preg_replace($match_php_tags, '', $code);

		// Pull out all block/statement level elements and seperate plain text
		preg_match_all('#<!-- PHP -->(.*?)<!-- ENDPHP -->#s', $code, $matches);
		$php_blocks = $matches[1];
		$code = preg_replace('#<!-- PHP -->(.*?)<!-- ENDPHP -->#s', '<!-- PHP -->', $code);

		preg_match_all('#<!-- INCLUDE ([a-zA-Z0-9\_\-\+\.\/]+?) -->#', $code, $matches);
		$include_blocks = $matches[1];
		$code = preg_replace('#<!-- INCLUDE ([a-zA-Z0-9\_\-\+\.\/]+?) -->#', '<!-- INCLUDE -->', $code);

		preg_match_all('#<!-- INCLUDEPHP ([a-zA-Z0-9\_\-\+\.\\\\\/]+?) -->#', $code, $matches);
		$includephp_blocks = $matches[1];
		$code = preg_replace('#<!-- INCLUDEPHP ([a-zA-Z0-9\_\-\+\.\/]+?) -->#', '<!-- INCLUDEPHP -->', $code);

		preg_match_all('#<!-- (.*?) (.*?)?[ ]?-->#s', $code, $blocks);
		$text_blocks = preg_split('#<!-- (.*?) (.*?)?[ ]?-->#s', $code);
		if( $text_blocks[count($text_blocks)-1] == '' )
		{
			unset($text_blocks[count($text_blocks)-1]);
		}
		for( $i = 0; $i < count($text_blocks); $i++ )
		{
			RosterTplEncode::compile_var_tags($text_blocks[$i]);
		}

		$compile_blocks = array();
		$block_else_level = array();
		$this->block_names = array();
		for( $curr_tb = 0; $curr_tb < count($text_blocks); $curr_tb++ )
		{
			if( isset($blocks[1][$curr_tb]) )
			{
				switch ($blocks[1][$curr_tb])
				{
					case 'BEGIN':
						$block_else_level[] = false;
						$compile_blocks[] = '<?php ' . RosterTplEncode::compile_tag_block($blocks[2][$curr_tb]) . ' ?>';
						break;

					case 'BEGINELSE':
						$block_else_level[count($block_else_level) - 1] = true;
						$compile_blocks[] = '<?php }} else { ?>';
						break;

					case 'END':
						array_pop($this->block_names);
						$compile_blocks[] = '<?php ' . ((array_pop($block_else_level)) ? '}' : '}}') . ' ?>';
						break;

					case 'IF':
						$compile_blocks[] = '<?php ' . RosterTplEncode::compile_tag_if($blocks[2][$curr_tb], false) . ' ?>';
						break;

					case 'ELSE':
						$compile_blocks[] = '<?php } else { ?>';
						break;

					case 'ELSEIF':
						$compile_blocks[] = '<?php ' . RosterTplEncode::compile_tag_if($blocks[2][$curr_tb], true) . ' ?>';
						break;

					case 'ENDIF':
						$compile_blocks[] = '<?php } ?>';
						break;

					case 'DEFINE':
						$compile_blocks[] = '<?php ' . RosterTplEncode::compile_tag_define($blocks[2][$curr_tb], true) . ' ?>';
						break;

					case 'UNDEFINE':
						$compile_blocks[] = '<?php ' . RosterTplEncode::compile_tag_define($blocks[2][$curr_tb], false) . ' ?>';
						break;

					case 'INCLUDE':
						$temp = array_shift($include_blocks);
						$compile_blocks[] = '<?php $this->_tpl_include(\''.$temp."'); ?>";
						$this->_tpl_include($temp, false);
						break;

					case 'INCLUDEPHP':
						/*	$compile_blocks[] = "<?php include('".$this->root.'/'.array_shift($includephp_blocks))."'); ?>"; */
						break;

					case 'PHP':
						$compile_blocks[] = '<?php '.array_shift($php_blocks).' ?>';
						break;

					default:
						RosterTplEncode::compile_var_tags($blocks[0][$curr_tb]);
						$trim_check = trim($blocks[0][$curr_tb]);
						$compile_blocks[] = (!$no_echo) ? ((!empty($trim_check)) ? $blocks[0][$curr_tb] : '') : ((!empty($trim_check)) ? $blocks[0][$curr_tb] : '');
						break;
				}
			}
		}
		$template_php = '';
		for( $i = 0; $i < count($text_blocks); $i++ )
		{
			$trim_check_text = trim($text_blocks[$i]);
			$trim_check_block = isset($compile_blocks[$i]) ? trim($compile_blocks[$i]) : '';
			$template_php .= (!$no_echo) ? ((!empty($trim_check_text)) ? $text_blocks[$i] : '') . ((!empty($compile_blocks[$i])) ? $compile_blocks[$i] : '') : ((!empty($trim_check_text)) ? $text_blocks[$i] : '') . ((!empty($compile_blocks[$i])) ? $compile_blocks[$i] : '');
		}
		// There will be a number of occassions where we switch into and out of
		// PHP mode instantaneously. Rather than "burden" the parser with this
		// we'll strip out such occurences, minimising such switching
		$template_php = str_replace(' ?><?php ', '', $template_php);
		return  (!$no_echo) ? $template_php : "\$$echo_var .= '" . $template_php . "'";
	}

	function compile_var_tags( &$text_blocks )
	{
		// change template varrefs into PHP varrefs
		$varrefs = array();
		// This one will handle varrefs WITH namespaces
		preg_match_all('#\{(([a-z0-9\-_]+?\.)+?)(\$)?([A-Z0-9\-_]+?)\}#', $text_blocks, $varrefs);
		for( $j = 0; $j < count($varrefs[1]); $j++ )
		{
			$namespace = $varrefs[1][$j];
			$varname = $varrefs[4][$j];
			$new = RosterTplEncode::generate_block_varref($namespace, $varname, true, $varrefs[3][$j]);
			$text_blocks = str_replace($varrefs[0][$j], $new, $text_blocks);
		}
		// This will handle the remaining root-level varrefs
		$text_blocks = preg_replace('#\{L_([a-z0-9\-_]*?)\}#is', "<?php echo ((isset(\$this->_tpldata['.'][0]['L_\\1'])) ? \$this->_tpldata['.'][0]['L_\\1'] : '{ '.ucfirst(strtolower(str_replace('_', ' ', '\\1'))).' }'); ?>", $text_blocks);
		$text_blocks = preg_replace('#\{([a-z0-9\-_]*?)\}#is', "<?php echo \$this->_tpldata['.'][0]['\\1']; ?>", $text_blocks);
		$text_blocks = preg_replace('#\{\$([a-z0-9\-_]*?)\}#is', "<?php echo \$this->_tpldata['DEFINE']['.']['\\1']; ?>", $text_blocks);
		return;
	}

	function compile_tag_block( $tag_args )
	{
		// Allow for control of looping (indexes start from zero):
		// foo(2)	: Will start the loop on the 3rd entry
		// foo(-2)   : Will start the loop two entries from the end
		// foo(3,4)  : Will start the loop on the fourth entry and end it on the fourth
		// foo(3,-4) : Will start the loop on the fourth entry and end it four from last
		if( preg_match('#^(.*?)\(([\-0-9]+)(,([\-0-9]+))?\)$#', $tag_args, $match) )
		{
			$tag_args = $match[1];
			$loop_start = ($match[2] < 0) ? '$_' . $tag_args . '_count ' . ($match[2] - 1) : $match[2];
			$loop_end = ($match[4]) ? (($match[4] < 0) ? '$_' . $tag_args . '_count ' . $match[4] : ($match[4] + 1)) : '$_' . $tag_args . '_count';
		}
		else
		{
			$loop_start = 0;
			$loop_end = '$_' . $tag_args . '_count';
		}
		$tag_template_php = '';
		array_push($this->block_names, $tag_args);
		if( count($this->block_names) < 2 )
		{
			// Block is not nested.
			$tag_template_php = '$_' . $tag_args . "_count = (isset(\$this->_tpldata['$tag_args'])) ?  count(\$this->_tpldata['$tag_args']) : 0;";
		}
		else
		{
			// This block is nested.
			// Generate a namespace string for this block.
			$namespace = implode('.', $this->block_names);
			// Get a reference to the data array for this block that depends on the
			// current indices of all parent blocks.
			$varref = RosterTplEncode::generate_block_data_ref($namespace, false, false);
			// Create the for loop code to iterate over this block.
			$tag_template_php = '$_' . $tag_args . '_count = (isset(' . $varref . ')) ? count(' . $varref . ') : 0;';
		}
		$tag_template_php .= 'if ($_' . $tag_args . '_count) {';
		$tag_template_php .= 'for ($this->_' . $tag_args . '_i = ' . $loop_start . '; $this->_' . $tag_args . '_i < ' . $loop_end . '; $this->_' . $tag_args . '_i++){';
		return $tag_template_php;
	}

	//
	// Compile IF tags - much of this is from Smarty with
	// some adaptions for our block level methods
	//
	function compile_tag_if( $tag_args , $elseif )
	{
		/* Tokenize args for 'if' tag. */
		preg_match_all('/(?:
						 "[^"\\\\]*(?:\\\\.[^"\\\\]*)*"	 |
						 \'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\' |
						 [(),]							  |
						 [^\s(),]+)/x', $tag_args, $match);
		$tokens = $match[0];
		$is_arg_stack = array();
		for( $i = 0; $i < count($tokens); $i++ )
		{
			$token = &$tokens[$i];
			switch( $token )
			{
				case '!':
				case '%':
				case '!==':
				case '==':
				case '===':
				case '>':
				case '<':
				case '!=':
				case '<>':
				case '<<':
				case '>>':
				case '<=':
				case '>=':
				case '&&':
				case '||':
				case '|':
				case '^':
				case '&':
				case '~':
				case ')':
				case ',':
				case '+':
				case '-':
				case '*':
				case '/':
				case '@':
					break;

				case 'eq':
					$token = '==';
					break;

				case 'ne':
				case 'neq':
					$token = '!=';
					break;

				case 'lt':
					$token = '<';
					break;

				case 'le':
				case 'lte':
					$token = '<=';
					break;

				case 'gt':
					$token = '>';
					break;

				case 'ge':
				case 'gte':
					$token = '>=';
					break;

				case 'and':
					$token = '&&';
					break;

				case 'or':
					$token = '||';
					break;

				case 'not':
					$token = '!';
					break;

				case 'mod':
					$token = '%';
					break;

				case '(':
					array_push($is_arg_stack, $i);
					break;

				case 'is':
					$is_arg_start = ($tokens[$i-1] == ')') ? array_pop($is_arg_stack) : $i-1;
					$is_arg	   = implode('	', array_slice($tokens, $is_arg_start, $i - $is_arg_start));
					$new_tokens   = RosterTplEncode::_parse_is_expr($is_arg, array_slice($tokens, $i+1));
					array_splice($tokens, $is_arg_start, count($tokens), $new_tokens);
					$i = $is_arg_start;

				default:
					if( preg_match('#^(([a-z0-9\-_]+?\.)+?)?(\$)?([A-Z]+[A-Z0-9\-_]+)$#s', $token, $varrefs) )
					{
						$token = (!empty($varrefs[1])) ? RosterTplEncode::generate_block_data_ref(substr($varrefs[1], 0, -1), true, $varrefs[3]) . '[\'' . $varrefs[4] . '\']' : (($varrefs[3]) ? '$this->_tpldata[\'DEFINE\'][\'.\'][\'' . $varrefs[4] . '\']' : '$this->_tpldata[\'.\'][0][\'' . $varrefs[4] . '\']');
					}
					break;
			}
		}
		return (($elseif) ? '} elseif (' : 'if (') . (implode(' ', $tokens) . ') { ');
	}

	function compile_tag_define( $tag_args , $option )
	{
		preg_match('#^(([a-z0-9\-_]+?\.)+?)?\$([A-Z][A-Z0-9_\-]*?) = (\'?)(.*?)(\'?)$#', $tag_args, $match);
		if( empty($match[3]) || empty($match[5]) )
		{
			trigger_error("'$tag_args' is no proper &lt;!-- DEFINE --&gt; format.", E_USER_WARNING);
			return;
		}

		// Are we a string?
		if( $match[4] && $match[6] )
		{
			// Can't use addslashes as we don't know what value has magic_quotes_sybase.
			$match[5] = "'" . addcslashes(str_replace(array('\\\'', '\\\\'), array('\'', '\\'), $match[5]), "\0..\37\177\\\"'") . "'"; //"
		}
		else
		{
			preg_match('#(true|false|\.)#i', $match[5], $type);

			switch( strtolower($type[1]) )
			{
				case 'true':
				case 'false':
					$match[5] = strtoupper($match[5]);
					break;
				case '.':
					$match[5] = doubleval($match[5]);
					break;
				default:
					$match[5] = intval($match[5]);
					break;
			}
		}

		if( $option )
		{
			return (($match[1]) ? RosterTplEncode::generate_block_data_ref(substr($match[1], 0, -1), true, true) . '[\'' . $match[3] . '\']' : '$this->_tpldata[\'DEFINE\'][\'.\'][\'' . $match[3] . '\']') . ' = ' . $match[5] . ';';
		}
		else
		{
			return 'unset(' . (($match[1]) ? RosterTplEncode::generate_block_data_ref(substr($match[1], 0, -1), true, true) . '[\'' . $match[3] . '\']' : '$this->_tpldata[\'DEFINE\'][\'.\'][\'' . $match[3] . '\']') . ');';
		}
	}

	// This is from Smarty
	function _parse_is_expr( $is_arg , $tokens )
	{
		$expr_end =	0;
		$negate_expr = false;
		if( ($first_token = array_shift($tokens)) == 'not' )
		{
			$negate_expr = true;
			$expr_type = array_shift($tokens);
		}
		else
		{
			$expr_type = $first_token;
		}
		switch( $expr_type )
		{
			case 'even':
				if( $tokens[$expr_end] == 'by' )
				{
					++$expr_end;
					$expr_arg =	$tokens[$expr_end++];
					$expr =	"!(($is_arg	/ $expr_arg) % $expr_arg)";
				}
				else
				{
					$expr =	"!($is_arg % 2)";
				}
				break;

			case 'odd':
				if( $tokens[$expr_end] == 'by' )
				{
					++$expr_end;
					$expr_arg =	$tokens[$expr_end++];
					$expr =	"(($is_arg / $expr_arg)	% $expr_arg)";
				}
				else
				{
					$expr =	"($is_arg %	2)";
				}
				break;

			case 'div':
				if( $tokens[$expr_end] == 'by' )
				{
					++$expr_end;
					$expr_arg =	$tokens[$expr_end++];
					$expr =	"!($is_arg % $expr_arg)";
				}
				break;

			default:
				break;
		}
		if( $negate_expr )
		{
			$expr =	"!($expr)";
		}
		array_splice($tokens, 0, $expr_end,	$expr);
		return $tokens;
	}

	/**
	 * Generates a reference to the given variable inside the given (possibly nested)
	 * block namespace. This is a string of the form:
	 * ' . $this->_tpldata['parent'][$_parent_i]['$child1'][$_child1_i]['$child2'][$_child2_i]...['varname'] . '
	 * It's ready to be inserted into an "echo" line in one of the templates.
	 * NOTE: expects a trailing "." on the namespace.
	 */
	function generate_block_varref( $namespace , $varname , $echo = true , $defop = false )
	{
		# Strip the trailing period.
		$namespace = substr($namespace, 0, strlen($namespace) - 1);
		# Get a reference to the data block for this namespace.
		$varref = RosterTplEncode::generate_block_data_ref($namespace, true, $defop);
		# Append the variable reference.
		$varref .= "['$varname']";
		return (($echo) ? "<?php echo $varref; ?>" : $varref);
	}

	/**
	 * Generates a reference to the array of data values for the given
	 * (possibly nested) block namespace. This is a string of the form:
	 * $this->_tpldata['parent'][$_parent_i]['$child1'][$_child1_i]['$child2'][$_child2_i]...['$childN']
	 *
	 * If $include_last_iterator is true, then [$_childN_i] will be appended to the form shown above.
	 * NOTE: does not expect a trailing "." on the blockname.
	 */
	function generate_block_data_ref( $blockname , $include_last_iterator , $defop )
	{
		# Get an array of the blocks involved.
		$blocks = explode('.', $blockname);
		$blockcount = count($blocks) - 1;
		$varref = '$this->_tpldata' . (($defop) ? '[\'DEFINE\']' : '');
		# Build up the string with everything but the last child.
		for( $i = 0; $i < $blockcount; $i++ )
		{
			$varref .= "['" . $blocks[$i] . "'][\$this->_" . $blocks[$i] . '_i]';
		}
		# Add the block reference for the last child.
		$varref .= "['" . $blocks[$blockcount] . "']";
		# Add the iterator for the last child if requried.
		if ($include_last_iterator)
		{
			$varref .= '[$this->_' . $blocks[$blockcount] . '_i]';
		}
		return $varref;
	}

	function compile_write( &$handle , &$data )
	{
		$filename = ereg_replace('/', '#', $this->filename[$handle]);
		$filename = $this->cachepath.$filename.'.inc';
		file_writer($filename, $data);
		return;
	}
}
