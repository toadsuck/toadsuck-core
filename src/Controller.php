<?php

namespace Toadsuck\Core;

use Toadsuck\Core\Template;
use Toadsuck\Core\Config;

class Controller
{
	public $plates = null;
	public $template = null;
	public $base_path = null;
	public $config = null;
		
	public function __construct()
	{
		// We need to know paths to our resources.
		$this->base_path = rtrim($GLOBALS['TOADSUCK_BASE_PATH'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'src';
		
		// Set up the template engine.
		$this->template = new Template($this->resolvePath('views'));

		// Set up configs.
		$this->config = new Config($this->getEnvironment(), $this->resolvePath('config'));
	}
	
	public function httpError($code = '404', $message = null)
	{
		switch($code) {
			case '404':
				header("HTTP/1.0 404 Not Found");
				print "404 Page Not Found";
				break;
			default:
				header("HTTP/1.0 " . $code);
				print $message;
				break;
		}
		
		exit;
	}

	/**
	 * Internal or External Redirect to the specified url
	 */
	public function redirect($url)
	{
		if (!preg_match('/^http/', $url)){
			// Internal redirect
			$url = DIRECTORY_SEPARATOR . trim($url, DIRECTORY_SEPARATOR);
			$url = $this->template->uri($url);
		}
		
		header("Location: $url");
		exit;
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

	public function __call($method = null, $args = null)
	{
		$this->httpError('404', 'Method does not exist');
	}
}
