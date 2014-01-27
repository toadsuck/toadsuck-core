<?php

namespace Toadsuck\Core;

/**
 * Thin wrapper for Fuel\Config.
 */
class Config
{
	public $config = null;
	
	public function __construct($environment = null, $path = null)
	{
		// Set up configs.
		class_alias('Fuel\Common\Arr', 'Arr');
		$this->config = new \Fuel\Config\Container($environment);
		$this->config->setConfigFolder('');
		
		if (!empty($path)) {
			$this->config->addPath($path);
		}
		
		return $this->config;
	}
	
	public function __call($method, $args = [])
	{
		return call_user_func_array([$this->config, $method], $args);
	}
}
