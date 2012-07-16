<?php
/**
 * WoWRoster.net WoWRoster
 *
 * APrint: Structured information display solution
 *
 * APrint is a debugging tool (PHP5 only), which displays structured information
 * about any PHP variable. It is a nice replacement for print_r() or var_dump()
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.1.0
 * @package    WoWRoster
 * @subpackage aPrint
 */

/**
 * This constant sets the maximum strings of strings that will be shown
 * as they are. Longer strings will be truncated with this length, and
 * their 'full form' will be shown in a child node.
 */
if (!defined('APRINT_TRUNCATE_LENGTH')) {
	define('APRINT_TRUNCATE_LENGTH', 50);
}

/**
 * APrint
 *
 * This class stores the APrint API for rendering and
 * displaying the structured information it is reporting
 *
 * @package APrint
 */
class APrint {

	/**
	 * Return APrint version
	 *
	 * @return string
	 * @access public
	 * @static
	 */
	public static function version() {
		return '0.1.0';
	}

	/**
	 * Prints a debug backtrace
	 *
	 * @access public
	 * @static
	 */
	public static function backtrace() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return APrint::dump(debug_backtrace());
	}

	/**
	 * Prints a list of all currently declared classes.
	 *
	 * @access public
	 * @static
	 */
	public static function classes() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all currently declared classes.</div>'
			. APrint::dump(get_declared_classes());
	}

	/**
	 * Prints a list of all currently declared interfaces (PHP5 only).
	 *
	 * @access public
	 * @static
	 */
	public static function interfaces() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all currently declared interfaces.</div>'
			. APrint::dump(get_declared_interfaces());
	}

	/**
	 * Prints a list of all currently included (or required) files.
	 *
	 * @access public
	 * @static
	 */
	public static function includes() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all currently included (or required) files.</div>'
			. APrint::dump(get_included_files());
	}

	/**
	 * Prints a list of all currently declared functions.
	 *
	 * @access public
	 * @static
	 */
	public static function functions() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all currently declared functions.</div>'
			. APrint::dump(get_defined_functions());
	}

	/**
	 * Prints a list of all currently declared constants.
	 *
	 * @access public
	 * @static
	 */
	public static function defines() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all currently declared constants (defines).</div>'
			. APrint::dump(get_defined_constants());
	}

	/**
	 * Prints a list of all currently loaded PHP extensions.
	 *
	 * @access public
	 * @static
	 */
	public static function extensions() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all currently loaded PHP extensions.</div>'
			. APrint::dump(get_loaded_extensions());
	}

	/**
	 * Prints a list of all HTTP request headers.
	 *
	 * @access public
	 * @static
	 */
	public static function headers() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all HTTP request headers.</div>'
			. APrint::dump(getAllHeaders());
	}

	/**
	 * Prints a list of the specified directories under your <i>include_path</i> option.
	 *
	 * @access public
	 * @static
	 */
	public static function path() {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of the specified directories under your <code><b>include_path</b></code> option.</div>'
			. APrint::dump(explode(PATH_SEPARATOR, ini_get('include_path')));
	}

	/**
	 * Prints a list of all the values from an INI file.
	 *
	 * @param string $ini_file
	 *
	 * @access public
	 * @static
	 */
	public static function ini($ini_file) {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// read it
		if (!$_ = @parse_ini_file($ini_file, 1)) {
			return false;
		}

		// render it
		return '<div class="aprint-title">This is a list of all the values from the
	<code><b>' . (realpath($ini_file) ? realpath($ini_file) : $ini_file) . '</b></code> INI file.
</div>' . APrint::dump($_);
	}

	/**
	 * Dump information about a variable
	 *
	 * @param mixed $data,...
	 * @access public
	 * @static
	 */
	public static function dump($data) {

		// disabled ?
		if (!APrint::_debug()) {
			return false;
		}

		// Begin Output Buffering
		ob_start();

		// more arguments ?
		if (func_num_args() > 1) {
			$_ = func_get_args();
			foreach ($_ as $d) {
				echo APrint::dump($d);
			}
			return ob_get_clean();
		}

		// the js?
		echo APrint::_js();

		// find caller
		$_ = debug_backtrace();
		while ($d = array_pop($_)) {
			if ((strtolower(@$d['function']) == 'aprint') || (strtolower(@$d['class']) == 'aprint')) {
				break;
			}
		}

		// the content
		echo '
<div class="aprint-root">
	<ul class="aprint-node aprint-first">';

		APrint::_dump($data);

		echo '
		<li class="aprint-footnote">';
		if (isset($d['file'])) {
			echo '
			<span class="aprint-call">Called from <code>' . str_replace(ROSTER_BASE,'',$d['file']) . '</code>, line <code>' . $d['line'] . '</code></span>';
		}
		echo '
		</li>
	</ul>
</div>';

		// flee the hive
		$_recursion_marker = APrint::_marker();
		if ($hive = & APrint::_hive($dummy)) {
			foreach ($hive as $i => $bee) {
				if (is_object($bee)) {
					unset($hive[$i]->$_recursion_marker);
				}
				else {
					unset($hive[$i][$_recursion_marker]);
				}
			}
		}

		// End Output Buffering
		return ob_get_clean();
	}

	/**
	 * Print the Javascript
	 *
	 * @return boolean
	 * @access private
	 * @static
	 */
	private static function _js() {

		static $_js = false;

		// already set ?
		if ($_js) {
			return;
		}

		$_js = <<<JSCRIPT

<script type="text/javascript">
<!--
/**
 * APrint JS Class
 */
$(function() {
	// Add an icon to expandable nodes
	$('li.aprint-child').find('div.aprint-expand')
		.prepend('<span class="ui-icon ui-icon-carat-1-e"></span>')
		.end()
		.find('div.aprint-nest').hide();

	// Add a click function to each expandable node
	$('div.aprint-expand').click(function() {
		$(this).find('.ui-icon').toggleClass('ui-icon-carat-1-e').toggleClass('ui-icon-carat-1-s');
		$(this).next().slideToggle('fast');
	});

	// Hover effect for aprint list element
	$('.aprint-element').hover(function() {
	 	$(this).addClass('aprint-hover');
	}, function() {
	 	$(this).removeClass('aprint-hover');
	});

});
//-->
</script>
JSCRIPT;

		return $_js;
	}

	/**
	 * Enable APrint
	 *
	 * @return boolean
	 * @access public
	 * @static
	 */
	public static function enable() {
		return true === APrint::_debug(true);
	}

	/**
	 * Disable APrint
	 *
	 * @return boolean
	 * @access public
	 * @static
	 */
	public static function disable() {
		return false === APrint::_debug(false);
	}

	/**
	 * Get\Set APrint state: whether it is enabled or disabled
	 *
	 * @param boolean $state
	 * @return boolean
	 * @access private
	 * @static
	 */
	private static function _debug($state = null) {

		static $_ = true;

		// set
		if (isset($state)) {
			$_ = (boolean)$state;
		}

		// get
		return $_;
	}

	/**
	 * Dump information about a variable
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _dump(&$data, $name = '...') {

		// object ?
		if (is_object($data)) {
			return APrint::_object($data, $name);
		}

		if (is_array($data)) {
			return APrint::_array($data, $name);
		}

		if (is_resource($data)) {
			return APrint::_resource($data, $name);
		}

		if (is_float($data)) {
			return APrint::_float($data, $name);
		}

		if (is_integer($data)) {
			return APrint::_integer($data, $name);
		}

		if (is_bool($data)) {
			return APrint::_boolean($data, $name);
		}

		if (is_string($data)) {
			return APrint::_string($data, $name);
		}

		if (is_null($data)) {
			return APrint::_null($name);
		}
	}

	/**
	 * Return the marked used to stain arrays
	 * and objects in order to detect recursions
	 *
	 * @return string
	 * @access private
	 * @static
	 */
	private static function _marker() {

		static $_recursion_marker;
		if (!isset($_recursion_marker)) {
			$_recursion_marker = uniqid('aprint');
		}

		return $_recursion_marker;
	}

	/**
	 * Adds a variable to the hive of arrays and objects which
	 * are tracked for whether they have recursive entries
	 *
	 * @param mixed &$bee either array or object, not a scallar value
	 * @return array all the bees
	 *
	 * @access private
	 * @static
	 */
	private static function &_hive(&$bee) {

		static $_ = array();

		// new bee ?
		if (!is_null($bee)) {

			// stain it
			$_recursion_marker = APrint::_marker();
			(is_object($bee))
				? @($bee->$_recursion_marker++)
				: @($bee[$_recursion_marker]++);

			$_[0][] =& $bee;
		}

		// return all bees
		return $_[0];
	}

	/**
	 * Render a dump for the properties of an array or objeect
	 *
	 * @param mixed &$data
	 * @access private
	 * @static
	 */
	private static function _vars(&$data) {

		$_is_object = is_object($data);

		// test for references in order to
		// prevent endless recursion loops
		$_recursion_marker = APrint::_marker();
		$_r = (($_is_object) ? @$data->$_recursion_marker : @$data[$_recursion_marker]);
		$_r = (integer)$_r;

		// recursion detected
		if ($_r > 0) {
			return APrint::_recursion();
		}

		// stain it
		APrint::_hive($data);

		// render it
		echo '
<div class="aprint-nest">
	<ul class="aprint-node">';

		// keys ?
		$keys = (($_is_object) ? array_keys(get_object_vars($data)) : array_keys($data));

		// itterate
		foreach ($keys as $k) {

			// skip marker
			if ($k === $_recursion_marker) {
				continue;
			}

			// get real value
			if ($_is_object) {
				$v = & $data->$k;
			}
			else {
				$v = & $data[$k];
			}

			APrint::_dump($v, $k);
		}
		echo '
	</ul>
</div>';
	}

	/**
	 * Render a block that detected recursion
	 *
	 * @access private
	 * @static
	 */
	private static function _recursion() {
		echo '
<div class="aprint-nest">
	<ul class="aprint-node">
		<li class="aprint-child">
			<div class="aprint-element">
				<a class="aprint-name"><big>&#8734;</big></a>
				(<em class="aprint-type">Recursion</em>)
			</div>
		</li>
	</ul>
</div>';
	}

	/**
	 * Render a dump for an array
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _array(&$data, $name) {
		echo '
<li class="aprint-child">
	<div class="aprint-element' . (count($data) > 0 ? ' aprint-expand' : '') . '">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type">Array, <strong	class="aprint-array-length">' . (count($data) == 1 ? '1 element' : count($data) . ' elements') . '</strong></em>)';

		// callback ?
		if (is_callable($data)) {
			$_ = array_values($data);
			echo '
		<span class="aprint-callback"> | (<em class="aprint-type">Callback</em>)
			<strong class="aprint-string">' . htmlSpecialChars($_[0]) . '::' . htmlSpecialChars($_[1]) . '();</strong>
		</span>';
		}
		echo '
	</div>';

		if (count($data)) {
			APrint::_vars($data);
		}
		echo '
</li>';
	}

	/**
	 * Render a dump for an object
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _object(&$data, $name) {
		echo '
<li class="aprint-child">
	<div class="aprint-element' . (count($data) > 0 ? ' aprint-expand' : '') . '">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type">Object</em>)
		<strong class="aprint-class">' . get_class($data) . '</strong>
	</div>';

		if (count($data)) {
			APrint::_vars($data);
		}
		echo '
</li>';
	}

	/**
	 * Render a dump for a resource
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _resource($data, $name) {
		echo '
<li class="aprint-child">
	<div class="aprint-element">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type">Resource</em>)
		<strong class="aprint-resource">' . get_resource_type($data) . '</strong>
	</div>
</li>';
	}

	/**
	 * Render a dump for a NULL value
	 *
	 * @param string $name
	 * @return string
	 * @access private
	 * @static
	 */
	private static function _null($name) {
		echo '
<li class="aprint-child">
	<div class="aprint-element">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type aprint-null">NULL</em>)
	</div>
</li>';
	}

	/**
	 * Render a dump for a boolean value
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _boolean($data, $name) {
		echo '
<li class="aprint-child">
	<div class="aprint-element">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type">Boolean</em>)
		<strong class="aprint-boolean">' . ($data ? 'TRUE' : 'FALSE') . '</strong>
	</div>
</li>';
	}

	/**
	 * Render a dump for a integer value
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _integer($data, $name) {
		echo '
<li class="aprint-child">
	<div class="aprint-element">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type">Integer</em>)
		<strong class="aprint-integer">' . $data . '</strong>
	</div>
</li>';
	}

	/**
	 * Render a dump for a float value
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _float($data, $name) {
		echo '
<li class="aprint-child">
	<div class="aprint-element">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type">Float</em>)
		<strong class="aprint-float">' . $data . '</strong>
	</div>
</li>';
	}

	/**
	 * Render a dump for a string value
	 *
	 * @param mixed $data
	 * @param string $name
	 * @access private
	 * @static
	 */
	private static function _string($data, $name) {

		// extra ?
		$_extra = false;
		$_ = $data;
		if (strlen($data) > APRINT_TRUNCATE_LENGTH) {
			$_ = substr($data, 0, APRINT_TRUNCATE_LENGTH - 3) . '...';
			$_extra = true;
		}

		echo '
<li class="aprint-child">
	<div class="aprint-element' . ($_extra ? ' aprint-expand' : '') . '">
		<a class="aprint-name">' . $name . '</a>
		(<em class="aprint-type">String, <strong class="aprint-string-length">' . strlen($data) . ' characters</strong> </em>)
		<strong class="aprint-string">' . htmlSpecialChars($_) . '</strong>';

		// callback ?
		if (is_callable($data)) {
			echo '
		<span class="aprint-callback"> | (<em class="aprint-type">Callback</em>)
			<strong class="aprint-string">' . htmlSpecialChars($_) . '();</strong>
		</span>';
		}

		echo '
	</div>';

		if ($_extra) {
			echo '
	<div class="aprint-nest">
		<ul class="aprint-node">
			<li class="aprint-child">
				<div class="aprint-preview">' . htmlSpecialChars($data) . '</div>
			</li>
		</ul>
	</div>';
		}
		echo '
</li>';
	}
}
