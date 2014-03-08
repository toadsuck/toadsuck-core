<?php

namespace Toadsuck\Core;

use Toadsuck\Core\Config;

class Console
{
	public $config = null;

	public function __construct($opts = [])
	{
		// Where is our app's source code?
		$app_dir = array_key_exists('app_dir', $opts) ? $opts['app_dir'] : null;

		// Set up configs.
		$this->config = new Config();
	}
}
