<?php

namespace Toadsuck\Core\Tests;

use Toadsuck\Core\Uri;

class UriTests extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}

	public function tearDown()
	{
	}

	public function testCanGetLocalUriArray()
	{
		$uri = new Uri;
		$this->assertEquals('/home/foo/1', $uri->get('/home/foo/{id}', ['id'=> 1]));
	}

	public function testCanGetLocalUriInt()
	{
		$uri = new Uri;
		$this->assertEquals('/home/foo/1', $uri->get('/home/foo/{id}', 1));
	}

	public function testCanGetLocalUriString()
	{
		$uri = new Uri;
		$this->assertEquals('/home/foo/bar', $uri->get('/home/foo/{id}', 'bar'));
	}

	public function testCanGetLocalUriQueryString()
	{
		$uri = new Uri;

		$expected = '/home/foo?name=Josh&location=AR';
		$pattern = '/home/foo';
		$params = ['name' => 'Josh', 'location' => 'AR'];
		$this->assertEquals($expected, $uri->get($pattern, $params, true));

		// Make sure it works with trailing slash too.
		$pattern_slash = '/home/foo/';
		$this->assertEquals($expected, $uri->get($pattern_slash, $params, true));
	}

	public function testCanGetLocalUriPlaceholders()
	{
		$uri = new Uri;

		$expected = '/home/foo?name=Josh&location=AR';
		$pattern = '/home/foo?name={name}&location={location}';
		$params = ['name' => 'Josh', 'location' => 'AR'];
		$this->assertEquals($expected, $uri->get($pattern, $params));
	}
}
