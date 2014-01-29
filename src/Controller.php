<?php

namespace Toadsuck\Core;

use Toadsuck\Core\Template;
use Toadsuck\Core\Config;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class Controller
{
	public $plates = null;
	public $template = null;
	public $base_path = null;
	public $config = null;
	public $request = null;
		
	public function __construct()
	{
		// We need to know paths to our resources.
		$this->base_path = rtrim($GLOBALS['TOADSUCK_BASE_PATH'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'src';
		
		// Set up the template engine.
		$this->template = new Template($this->resolvePath('views'));

		// Set up configs.
		$this->config = new Config($this->getEnvironment(), $this->resolvePath('config'));
		
		// Get info about the HTTP Request
		$this->request = Request::createFromGlobals();

		// Setup the session
		$this->session = new Session();
		$this->session->start();
	}

	/**
	 * Internal or External Redirect to the specified url
	 */
	public function redirect($url)
	{
		if (!preg_match('/^http/', $url)) {
			// Internal redirect
			$url = DIRECTORY_SEPARATOR . trim($url, DIRECTORY_SEPARATOR);
			$url = $this->template->uri($url);
		}
		
		$response = new RedirectResponse($url);
		$response->send();
	}

	public function json($content = [])
	{
		$response = new JsonResponse();
		$response->setData($content);
		$response->send();
	}
	
	public function jsonp($content = [], $jsonCallback = 'callback')
	{
		$response = new JsonResponse();
		$response->setData($content);
		$response->setCallback($jsonCallback);
		$response->send();
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
		// Send a 404 for any methods that don't exist.
		$response = new Response('Not Found', 404, ['Content-Type' => 'text/plain']);
		$response->send();
	}
}
