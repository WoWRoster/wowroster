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

require_once ROSTER_API . 'resource/Resource.php';

/**
 * Realm resource.
 *
 * @throws ResourceException If no methods are defined.
 */
class battlepet extends Resource {
	protected $methods_allowed = array(
		'abilities',
		'species',
		'stats'
	);

	public function getAbilities($AbilityID)
	{
		$data = $this->consume('abilities', array(
			'data' => '',
			'dataa' => '',
			'server' => '',
			'name' => $AbilityID,
			'header'=>"Accept-language: enUS\r\n"
		));

		return $data;
	}
	public function getSpecies($SpeciesID)
	{
		$data = $this->consume('species', array(
			'data' => '',
			'dataa' => '',
			'server' => '',
			'name' => $SpeciesID,
			'header'=>"Accept-language: enUS\r\n"
		));

		return $data;
	}
	public function getStats($SpeciesID)
	{
		$data = $this->consume('stats', array(
			'data' => '',
			'dataa' => '',
			'server' => '',
			'name' => $SpeciesID,
			'header'=>"Accept-language: enUS\r\n"
		));

		return $data;
	}
}
