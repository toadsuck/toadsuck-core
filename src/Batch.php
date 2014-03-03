<?php

namespace Toadsuck\Core;

use Toadsuck\Core\Config;
use Toadsuck\Core\Database as DB;

class Batch
{
	public $config		= NULL;
	public $base_path	= NULL;
	
	public function __construct()
	{
		// We need to know paths to our resources.
		$this->base_path = rtrim($GLOBALS['TOADSUCK_BASE_PATH'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'src';
		
		// Set up configs.
		$this->config = new Config($this->getEnvironment(), $this->resolvePath('config'));
		$this->config->load('database');
		
		// Set up Database connection
		DB::init($this->config->get('dsn'));
	}
	
	protected function resolvePath($resource)
	{
		return rtrim($this->base_path . DIRECTORY_SEPARATOR . $resource, DIRECTORY_SEPARATOR);
	}
	
	protected function getEnvironment()
	{
		$environment_file = $this->resolvePath('config/environment');
		
		if (file_exists($environment_file)) {
			$environment = trim(file_get_contents($environment_file));
		} else {
			$environment = 'local';
		}
		
		return $environment;
	}
}
