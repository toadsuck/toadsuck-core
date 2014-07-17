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
		$this->markTestIncomplete();
		$_SERVER['TS_CONTROLLER'] = 'testcontroller';
		$config = new Config($this->getAppDir());
		$this->assertEquals('testcontroller', $config->getRequestedController());
	}

	public function testgetRequestedControllerShouldReturnDefault()
	{
		$this->markTestIncomplete();
		unset($_SERVER['TS_CONTROLLER']);
		$config = new Config($this->getAppDir());
		$this->assertEquals('none', $config->getRequestedController('none'));
	}

	public function testCanGetRequestedAction()
	{
		$this->markTestIncomplete();
		$_SERVER['TS_ACTION'] = 'testaction';
		$config = new Config($this->getAppDir());
		$this->assertEquals('testaction', $config->getRequestedAction());
	}

	public function testgetRequestedActionShouldReturnDefault()
	{
		$this->markTestIncomplete();
		unset($_SERVER['TS_ACTION']);
		$config = new Config($this->getAppDir());
		$this->assertEquals('none', $config->getRequestedAction('none'));
	}

	public function testGetBaseUrlShouldReturnConfigItem()
	{
		$_SERVER['SERVER_NAME'] = 'localhost';
		$config = new Config($this->getAppDir());
		$this->assertEquals('http://test.server.name/toadsuck/', $config->getBaseUrl());
	}

	public function testGetSiteUrlShouldReturnConfigItem()
	{
		$this->markTestIncomplete();
		$config = new Config($this->getAppDir());
		$this->assertEquals('http://test.server.name/toadsuck/phpunit', $config->getSiteUrl());
	}

	public function testGetSiteUrlShouldReturnConfigItemWithArgs()
	{
		$this->markTestIncomplete();
		$config = new Config($this->getAppDir());
		$this->assertEquals('http://test.server.name/toadsuck/phpunit/test', $config->getSiteUrl('test'));
	}
	
	protected function getAppDir()
	{
		return __DIR__ .	DIRECTORY_SEPARATOR . 'resources';
	}
}
