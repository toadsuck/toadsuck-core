<?php

namespace Toadsuck\Core;

/**
 * Thin wrapper for Fuel\Config.
 */
class Config
{
	public $config = null;
	public $base_path = null;
	
	public function __construct($base_path = null)
	{
		// We need to know paths to our resources.
		if (!empty($base_path)) {
			$this->base_path = $base_path;
		} elseif (array_key_exists('TOADSUCK_BASE_PATH', $GLOBALS)) {
			$this->base_path = rtrim($GLOBALS['TOADSUCK_BASE_PATH'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'src';
		} else {
			throw new \Exception('base_path not defined!');
		}
		
		// determine our environment and config path
		$environment = $this->getEnvironment();
		$path = $this->resolvePath('config');
		
		// Set up configs.
		if (!class_exists('Arr')) {
			class_alias('Fuel\Common\Arr', 'Arr');
		}

		$this->config = new \Fuel\Config\Container($environment);
		$this->config->setConfigFolder('');
		
		if (!empty($path)) {
			$this->config->addPath($path);
		}
		
		return $this->config;
	}
	
	public function resolvePath($resource)
	{
		return rtrim($this->base_path . DIRECTORY_SEPARATOR . $resource, DIRECTORY_SEPARATOR);
	}
	
	public function getEnvironment()
	{
		$environment_file = $this->resolvePath('config/environment');
		
		if (file_exists($environment_file)) {
			$environment = trim(file_get_contents($environment_file));
		} else {
			$environment = 'local';
		}
		
		return $environment;
	}
	
	public function __call($method, $args = [])
	{
		return call_user_func_array([$this->config, $method], $args);
	}
}
