<?php
namespace Toadsuck\Core;

use Rize\UriTemplate;

class Uri
{
	public function __construct()
	{

	}

	public function setRouter(Aura\Router\Router $router = null)
	{
		$this->router = $router;
	}

	/**
	 * Construct a uri from a named route.
	 *
	 * Passthru to aura/router::generate()
	 * @param type $name
	 * @param type $data
	 */
	public function route($name = null, $params = [])
	{
		return $this->router->generate($name, $params);
	}

	/**
	 * Construct a uri from the given uri and parameters.
	 *
	 * @param type $base
	 * @param type $data
	 */
	public function get($uri, $params = [], $query_string = false)
	{
		$template = new UriTemplate;

		if ($query_string) {
			return rtrim($uri, '/') . '?' . http_build_query($params);
		} else {
			if ((is_string($params) || is_int($params)) && preg_match('/\{id\}/', $uri)){
				return $template->expand($uri, ['id' => $params]);
			} else {
				return $template->expand($uri, $params);
			}
		}
	}
}