<?php
require_once("games/engine/AbstractEngine.class.php");

/**
 * Unreal 2
 *
 * @author      Jan Altensen (Stricted)
 * @copyright   2013-2014 Jan Altensen (Stricted)
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
abstract class Unreal2Engine extends AbstractEngine {
	/**
	 * protocol
	 * @var	string
	 */
	protected $protocol = 'udp';
	
	/**
	 * recive data from gameserver
	 *
	 * @return	string
	 */
	protected function receive () {
		$data = '';
		while (!$this->containsCompletePacket($data)) {
			$data .= fread($this->socket, 8192);
		}
		
		return $data;
	}
	
	/**
	 * get server data
	 *
	 * @return	array
	 */
	protected function getServerData () {
		$this->data = $this->command("\x79\x00\x00\x00\x00");
		
		$server = array();
		$this->data = substr($this->data, 22);
		$server['name'] = $this->splitData('string');
		$server['map'] = $this->splitData('string');
		$server['game'] = $this->splitData('string');
		$server['playercount'] = $this->splitData('int32');
		$server['maxplayers'] = $this->splitData('int32');
		$server['ping'] = $this->splitData('int32');
		
		return $server;
	}
}
