<?php

namespace Toadsuck\Core;

/**
 * Extension to Plates to work better with our routing.
 */
class Template extends \League\Plates\Template
{
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
	 * Sanitize the data before rendering.
	 */
	public function render($view, array $data = null)
	{
		// Sanitize variables already in the template.
		foreach (get_object_vars($this) as $key => $value) {
			if ($key != 'engine') {
				$this->$key = $this->scrub($value);
			}
		}
	
		// Also sanitize any variables we are passing in to the tempalte
		$data = $this->scrub($data);

		return parent::render($view, $data);
	}
	
	/**
	 * Output the content instead of just render.
	 */
	public function output($view, array $data = null)
	{
		echo $this->render($view, $data);
	}
	
	public function addFolder($name, $path)
	{
		$this->engine->addFolder($name, $path);
	}

	/**
	 * Recursively sanitize output
	 */
	public function scrub($var)
	{
		if (is_string($var)) {
			// Sanitize strings
			return $this->escape($var);
	
		} elseif (is_array($var)) {
			// Santize arrays.
			while (list($key) = each($var)) {
				$var[$key] = $this->scrub($var[$key]);
			}
	
			return $var;
		} elseif (is_object($var)) {
			// Sanitize objects
			$values = get_object_vars($var);
	
			foreach ($values as $key => $value) {
				$var->$key = $this->scrub($value);
			}
			return $var;
	
		} else {
			// Not sure what this is. null or bool? Just return it.
			return $var;
		}
	}
}
