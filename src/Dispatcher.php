<?php

namespace Toadsuck\Core;

use Aura\Router\RouterFactory;

class Dispatcher
{
	public $opts = [];
	public $router = null;
	public $namespace = 'Toadsuck\Skeleton';
	public $app_dir = null;

	public function __construct($opts = [])
	{
		foreach ($opts as $key => $value) {
			$this->$key = $value;
		}

		$this->opts = $opts;

		$this->getRoutes();
	}

	public function dispatch()
	{
		$path = $this->getUrlPath();

		$route = $this->router->match($path, $_SERVER);

		if (! $route) {
			  // no route object was returned
			return $this->pageNotFound('No matching route');
		}

		// does the route indicate a controller?
		if (isset($route->params['controller'])) {
			  // take the controller class directly from the route
			  $controller = ucfirst(strtolower($route->params['controller']));
		} else {
			  // use a default controller
			  $controller = 'Home';
		}

		// does the route indicate an action?
		if (isset($route->params['action'])) {
			  // take the action method directly from the route
			  $action = $route->params['action'];
		} else {
			  // use a default action
			  $action = 'index';
		}

		// does the route indicate an id?
		if (isset($route->params['id'])) {
			  // take the action method directly from the route
			  $id = $route->params['id'];
		} else {
			  // use a default action
			  $id = null;
		}

		// instantiate the controller class
		$class = join('\\', [$this->namespace, 'Controllers', $controller]);

		if (!class_exists($class)) {
			return $this->pageNotFound('Controller not found.');
		} else {
			$page = new $class();

			// invoke the action method with the id
			$page->$action($id);
		}
	}

	/**
	 * What routes have been configured for this app?
	 */
	public function getRoutes()
	{
		$router_factory = new RouterFactory;
		$router = $router_factory->newInstance();

		$routes_file = $this->getAppResourcePath('config/routes.php');

		if (file_exists($routes_file)) {
			// Let the app specify it's own routes.
			include_once($routes_file);
		} else {
			// Fall back on some sensible defaults.
			// Add some basic routes
			$router->add(null, null);

			$router->add(null, '/');

			$router->add(null, '/{controller}');

			// add a simple unnamed route with params
			$router->add(null, '/{controller}/{action}');

			$router->add(null, '/{controller}/{action}/{id}');
		}

		$this->router = $router;
	}

	/**
	 * Determine what the requested path is so we can pass it to the router.
	 */
	public function getUrlPath()
	{
		$path = '/';

		if (array_key_exists('PATH_INFO', $_SERVER)) {
			$path = $_SERVER['PATH_INFO'];
		} else {
			// get the route based on the path and server
			$path = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
		}

		if ($path == '/' . basename($_SERVER['SCRIPT_NAME'])) {
			$path = '/';
		}
		
		return $path;
	}

	public function pageNotFound($message = null)
	{
		header('HTTP/1.0 404 Not Found');

		if (!empty($message)) {
			print $message;
		}

		exit;
	}

	public function getAppResourcePath($file = null)
	{
		return $this->getAppDir() . '/' . $file;
	}

	public function getAppDir()
	{
		return array_key_exists('app_dir', $this->opts) ? $this->opts['app_dir'] . '/src' : dirname(__DIR__) . '/src';
	}
}