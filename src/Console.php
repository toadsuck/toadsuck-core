<?php

namespace Toadsuck\Core;

use Toadsuck\Core\Config;

class Console
{
	public $config		= NULL;
	public $base_path	= NULL;
	
	public function __construct()
	{
		// Set up configs.
		$this->config = new Config();
	}
}
