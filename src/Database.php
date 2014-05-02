<?php

namespace Toadsuck\Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use Prelude\Dsn\DsnParser;

class Database
{
	public static $driver = [];

	public static function init($config = null)
	{
		$defaults = [
			'driver'	=> 'mysql',
			'host'		=> 'localhost',
			'database'	=> 'mysql',
			'username'	=> 'root',
			'password'	=> null,
			'charset'	=> 'utf8',
			'collation'	=> 'utf8_unicode_ci',
			'prefix'	=> null
		];

		$capsule = new Capsule;

		if (is_string($config)) {
			$config = self::parseDsn($config);
		}

		if (is_array($config)) {

			// if the array is an array of arrays (i.e. check
			if (self::checkMultiConnectionArray($config)) {

				// if we have an array of connections, iterate through them.  connections should be stored in the form of name => conn_info
				foreach ($config as $connection_name => $connection_info) {

					// if it's a dsn string, then parse it
					if (is_string($connection_info)) {
						$connection_info = self::parseDsn($connection_info);
					}

					// now merge it into our options
					$options[$connection_name] = array_merge($defaults, $connection_info);
				}
			} else {
				$options['default'] = array_merge($defaults, $config);
			}
		} else {
			$options['default'] = $defaults;
		}

		// add each connection, then set as global and boot
		foreach ($options as $name => $info) {
			$capsule->addConnection($info, $name);
			self::$driver[$name] = $options['driver'];
		}

		$capsule->setAsGlobal();
		$capsule->bootEloquent();
	}

	public static function random($connection_name = 'default')
	{
		if (self::$driver[$connection_name] == 'sqlite') {
			return 'random()';
		} else {
			return 'rand()';
		}
	}

	public static function parseDsn($string = null)
	{
		$opts = null;

		if (!empty($string)) {
			$dsn = (object) DsnParser::parseUrl($string)->toArray();

			$opts = [
				'driver'	=> $dsn->driver,
				'host'		=> $dsn->host,
				'database'	=> $dsn->dbname,
				'username'	=> $dsn->user,
				'password'	=> isset($dsn->pass) ? $dsn->pass : null
			];
		}

		return $opts;
	}

	public function checkMultiConnectionArray($config)
	{
		foreach ($config as $info) {
			// we have an array of connection info, so it's multi-connection
			if (is_array($info)) {
				return true;
			}

			try {
				// try to parse the DSN, if we have a driver, then it's a sign of a DSN string and is multi-connection
				$dsn = (object)DsnParser::parseUrl($info)->toArray();
				if (!empty($dsn->driver)) {
					return true;
				} else {
					return false;
				}
			} catch (\Prelude\Dsn\DsnException $ex) {
				return false;
			}
		}
	}
}
