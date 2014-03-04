<?php

namespace Toadsuck\Core\Tests;

use Toadsuck\Core\Tests\App\Controllers as Controllers;

class ControllerTests extends \PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$GLOBALS['TOADSUCK_BASE_PATH'] = __DIR__;
		ob_start();
	}

	public function __destruct()
	{
		echo ob_get_clean();
	}

	public function testBasicControllerAction()
	{
		$controller = new Controllers\Home(['app_dir' => $this->getAppDir()]);
		$controller->index();

		$this->expectOutputString('HOME\INDEX');
	}

	public function testCanOutputJson()
	{
		$controller = new Controllers\Home(['app_dir' => $this->getAppDir()]);
		$controller->json(['foo' => 'bar']);

		$this->expectOutputString('{"foo":"bar"}');
	}

	public function testCanOutputJsonp()
	{
		$controller = new Controllers\Home(['app_dir' => $this->getAppDir()]);
		$controller->jsonp(['foo' => 'bar']);

		$this->expectOutputString('callback({"foo":"bar"});');
	}

	public function testCanRenderTemplate()
	{
		$controller = new Controllers\Home(['app_dir' => $this->getAppDir()]);
		$controller->renderTemplate();

		$this->expectOutputString('<p>bar</p>');
	}

	public function testCanOutputTemplate()
	{
		$controller = new Controllers\Home(['app_dir' => $this->getAppDir()]);
		$controller->renderTemplate();

		$this->expectOutputString('<p>bar</p>');
	}
	
	public function testCanRedirectExternal()
	{
		$controller = new Controllers\Home(['app_dir' => $this->getAppDir()]);
		$controller->redirect('http://www.example.com');
		
		$this->expectOutputRegex('/Redirecting to http:\/\/www.example.com/');
	}

	protected function getAppDir()
	{
		return __DIR__ .	DIRECTORY_SEPARATOR . 'resources';
	}
}
