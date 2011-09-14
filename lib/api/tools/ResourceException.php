<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.2.0
 * @package    WoWRoster
 */

/**
 * Resource Exception
 */
class ResourceException extends Exception {
	public function __construct($message=null, $code=500, Exception $previous = null) {
		if (is_array($message) && isset($message['status'], $message['reason'])) {
			$message = $message['status']. ": " . $message['reason'];
		} elseif($message === null) {
			$message = 'Unknown error occurred.';
		}
		parent::__construct($message, $code, $previous);
	}
}
