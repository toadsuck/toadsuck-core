<?php

namespace Toadsuck\Core\Tests;

use Toadsuck\Core\Database;

class DatabaseTests extends \PHPUnit_Framework_TestCase
{
	public function testCanParseDsn()
	{
		$dsn = Database::parseDsn('mysql://un:pw@hostname/dbname');

		$this->assertArrayHasKey('driver', $dsn);
		$this->assertArrayHasKey('host', $dsn);
		$this->assertArrayHasKey('database', $dsn);
		$this->assertArrayHasKey('username', $dsn);
		$this->assertArrayHasKey('password', $dsn);
		
		$this->assertEquals('mysql', $dsn['driver']);
		$this->assertEquals('hostname', $dsn['host']);
		$this->assertEquals('un', $dsn['username']);
		$this->assertEquals('pw', $dsn['password']);
	}

	public function testCanParseDsnNoPassword()
	{
		$dsn = Database::parseDsn('mysql://un@hostname/dbname');

		$this->assertArrayHasKey('driver', $dsn);
		$this->assertArrayHasKey('host', $dsn);
		$this->assertArrayHasKey('database', $dsn);
		$this->assertArrayHasKey('username', $dsn);
		$this->assertArrayHasKey('password', $dsn);

		$this->assertEquals('mysql', $dsn['driver']);
		$this->assertEquals('hostname', $dsn['host']);
		$this->assertEquals('un', $dsn['username']);
		$this->assertEquals(null, $dsn['password']);

		$dsn = Database::parseDsn('mysql://un:@hostname/dbname');

		$this->assertArrayHasKey('driver', $dsn);
		$this->assertArrayHasKey('host', $dsn);
		$this->assertArrayHasKey('database', $dsn);
		$this->assertArrayHasKey('username', $dsn);
		$this->assertArrayHasKey('password', $dsn);

		$this->assertEquals('mysql', $dsn['driver']);
		$this->assertEquals('hostname', $dsn['host']);
		$this->assertEquals('un', $dsn['username']);
		$this->assertEquals(null, $dsn['password']);
	}
}
