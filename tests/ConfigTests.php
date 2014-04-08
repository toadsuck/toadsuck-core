<?php

namespace Toadsuck\Core\Tests;

use Toadsuck\Core\Config;

class ConfigTests extends \PHPUnit_Framework_TestCase
{
	public function testCanGetEnvironment()
	{
		$config = new Config($this->getAppDir());
		
		$this->assertEquals('dev', $config->getEnvironment());
	}

	public function testCanResolvePath()
	{
		$config = new Config($this->getAppDir());
		
		$this->assertEquals($this->getAppDir() . DIRECTORY_SEPARATOR . 'views', $config->resolvePath('views'));
	}
	
	public function testCanLoadDefaultConfig()
	{
		$config = new Config($this->getAppDir());
		$config->load('default');
		$this->assertEquals('bar', $config->get('foo'));
	}

	public function testCanLoadEnvironmentConfig()
	{
		$config = new Config($this->getAppDir());
		$config->load('envopts');
		$this->assertEquals('test', $config->get('env'));
	}

	public function testCanGetRequestedController()
	{
		$_SERVER['TS_CONTROLLER'] = 'testcontroller';
		$config = new Config($this->getAppDir());
		$this->assertEquals('testcontroller', $config->getRequestedController());
	}

	public function testgetRequestedControllerShouldReturnDefault()
	{
		unset($_SERVER['TS_CONTROLLER']);
		$config = new Config($this->getAppDir());
		$this->assertEquals('none', $config->getRequestedController('none'));
	}

	public function testCanGetRequestedAction()
	{
		$_SERVER['TS_ACTION'] = 'testaction';
		$config = new Config($this->getAppDir());
		$this->assertEquals('testaction', $config->getRequestedAction());
	}

	public function testgetRequestedActionShouldReturnDefault()
	{
		unset($_SERVER['TS_ACTION']);
		$config = new Config($this->getAppDir());
		$this->assertEquals('none', $config->getRequestedAction('none'));
	}
	
	public function testGetBaseUrlShouldReturnConfigItem()
	{
		$config = new Config($this->getAppDir());
		$this->assertEquals('http://test.server.name/toadsuck/', $config->getBaseUrl());
	}
	
	public function testGetBaseUrlShouldReturnConfigItemWithArgs()
	{
		$config = new Config($this->getAppDir());
		$this->assertEquals('http://test.server.name/toadsuck/test/', $config->getBaseUrl('test/'));
		$this->assertEquals('http://test.server.name/toadsuck/foo/bar', $config->getBaseUrl('foo/bar'));
	}
	
	public function testGetSiteUrlShouldReturnConfigItem()
	{
		$config = new Config($this->getAppDir());
		$this->assertEquals('http://test.server.name/toadsuck/phpunit', $config->getSiteUrl());
	}
	
	public function testGetSiteUrlShouldReturnConfigItemWithArgs()
	{
		$config = new Config($this->getAppDir());
		$this->assertEquals('http://test.server.name/toadsuck/phpunit/test', $config->getSiteUrl('test'));
	}
	
	protected function getAppDir()
	{
		return __DIR__ .	DIRECTORY_SEPARATOR . 'resources';
	}
}
