<?php
/**
 * Battle.net WoW API PHP SDK
 *
 * This software is not affiliated with Battle.net, and all references
 * to Battle.net and World of Warcraft are copyrighted by Blizzard Entertainment.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   WoWAPI-PHP-SDK
 * @author	  Chris Saylor
 * @author	  Daniel Cannon <daniel@danielcannon.co.uk>
 * @copyright Copyright (c) 2011, Chris Saylor
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link	  https://github.com/cjsaylor/WoWAPI-PHP-SDK
 * @version    SVN: $Id$
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
