<?php

namespace Toadsuck\Core;

/**
 * Extension to Plates to work better with our routing.
 */
class Template extends \League\Plates\Template
{
	public $toadsuck_base_path = null;
	public $engine;
	
	public function __construct($path = null)
	{
		
		// Create a new engine with the proper path to views directory.
		$this->engine = new \League\Plates\Engine($path);
		
		parent::__construct($this->engine);
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
	
	public function addFolder($name, $path)
	{
		$this->engine->addFolder($name, $path);
	}
}
