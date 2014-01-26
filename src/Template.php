<?php

namespace Toadsuck\Core;

/**
 * Extension to Plates to work better with our routing.
 */
class Template extends \League\Plates\Template
{

	public function __construct(\League\Plates\Engine $engine)
	{
		parent::__construct($engine);
	}

	public function uri($resource)
	{
		return $_SERVER['SCRIPT_NAME'] . $resource;
	}

	public function asset($resource)
	{
		return dirname($_SERVER['SCRIPT_NAME']) . $resource;
	}
	
	/**
	 * Output the content instead of just render.
	 */
	public function output($view, $data = null)
	{
		echo $this->render($view, $data);
	}
}
