<?php


define ('TABLE_SERVER', 'server_list');

class ServersControll {

	public static $_loadedServers = [];
	private static $_servers = NULL;

	public function __construct () {
		if (static::$_servers === NULL) {
			$query = sprintf('SELECT * FROM `%s` ORDER BY `cratedAt` ASC;', TABLE_SERVER);

			Database::setQuery ($query); // set query for later use

			Arrays::prepare(static::$_servers); // tell system var static::$_servers is an array

			static::$_servers = Database::multiFetch (); // get multi dimensioanl fetch from query
			static::$_loadedServers[]['safe'] = false;
		}
	}

	public static function loadServer ($server_id, array &$informations) {
		if ( !isset (static::$_loadedServers[$server_id]) || (isset (static::$_loadedServers[(int) $server_id]) && static::$_loadedServers[(int) $server_id]['safe'] != true)) {

			Database::prepare ('SELECT * FROM `server_list` WHERE `serverID` = :serverId;');
			Database::set (':serverId', (int) $server_id);

			$return = Database::fetch();

			if ($return) {

				if (isset (static::$_loadedServers[(int) $server_id]) && static::$_loadedServers[(int) $server_id]['safe'] != true) {
					static::$_loadedServers[(int) $server_id]['safe'] = (array_diff (static::$_loadedServers[(int) $server_id], $return) ? false : true);
				}

				static::$_loadedServers[(int) $server_id] = $return;

				if (!in_array ($static::$_loadedServers[(int) $server_id]['serverID'], static::$_servers)) {
					static::$_loadedServers[(int) $server_id] = ['safe' => false];
					static::$_servers[] = static::$_loadedServers[(int) $server_id];
				}

				$informations = static::$_loadedServers[(int) $server_id];

			} else {
				throw new Exception('Invalid server id.') ;
			}

		} else {
			$informations = static::$_loadedServers[(int) $server_id];
		}
	}

	public static function refreshServer ($server_id) {
		unset(static::$_loadedServers[(int) $server_id]);

		$returnInfos = [];

		stsic::loadServer ($server_id, $returnInfos);

		return $returnInfos;
	}

	public static function getServerInfos ($server_id, array &$informations, $live = false) {
		if ($live) {
			unset (static::$_loadedServers[(int) $server_id]);

			if(($key = array_search((int) $server_id, static::$_servers)) !== false) {
 			   unset(static::$_servers[$key]);
			}

			self::loadServer($server_id, $informations);

		} else {
			$informations = static::$_loadedServers[(int) $server_id];
		}
	}

	public static function getServersInformations (array &$informations, $live = false) {
		if ($live) {
			static::$_servers = NULL;
			new Database();
		}

		$informations = static::$_loadedServers;
	}

	public static function switchServer ($server_id) {

		$status = [];

		self::serverStatus ($server_id, $status);

		try {
			if ($status['status']) {
				SSHController::sendSignal($status, Signals::SHUT_DOWN);
			} else if (!$status['status'] && !$status['error']){
				SSHController::sendSignal($status, Signals::POWER_UP); // sendSignal (&$status, $signal = SHUT_DOWN)
			} else {
				throw new Exception('Unknows server status.');
			}
		} catch (Exception $ex) {
			unset ($ex);

			throw new Exception ('SSHController not implemented.');
		};

		return $status;

	}

	public static function serverStatus ($server_id, array &$status) {
		if (!isset (static::$_loadedServers[(int) $server_id])) {
			$status = ['status' => 0, 'error' => 'Server is not loaded'];
		} else {
			try {
				$status = SSHController::fetch (static::$_loadedServers[(int) $server_id]);
			} catch (Exception $ex) {
				unset ($ex);

				throw new Exception ('SSHController not implemented.');
			}
		}
	}
}
