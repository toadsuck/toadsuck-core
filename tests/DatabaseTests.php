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

	public function testCanQueryORM()
	{
		$this->databaseInit();

		$result = \Toadsuck\Core\Tests\App\Models\Captain::where('last_name', 'Kirk')->first();
		$this->assertEquals('James', $result->first_name);
	}

	public function testChainedQueryBuilder()
	{
		$this->databaseInit();

		$result = (object) \Illuminate\Database\Capsule\Manager::table('captains')->where('last_name', 'Kirk')->first();
		$this->assertEquals('James', $result->first_name);
	}

	public function testUnhainedQueryBuilder()
	{
		$this->databaseInit();

		$query = \Illuminate\Database\Capsule\Manager::table('captains');
		$query->where('last_name', 'Kirk');

		$result = (object) $query->first();
		$this->assertEquals('James', $result->first_name);
	}

	public function testORMChainedQuery()
	{
		$this->databaseInit();

		$result = (object) \Toadsuck\Core\Tests\App\Models\Captain::queryBuilder()->where('last_name', 'Kirk')->first();
		$this->assertEquals('James', $result->first_name);
	}

	public function testORMUnchainedQuery()
	{
		$this->databaseInit();

		$query = \Toadsuck\Core\Tests\App\Models\Captain::queryBuilder();
		$query->where('last_name', 'Kirk');
		$result = (object) $query->first();
		$this->assertEquals('James', $result->first_name);
	}

	public function testORMQueryBuilderShouldReturnEmptyArray()
	{
		$this->databaseInit();

		$result = \Toadsuck\Core\Tests\App\Models\Captain::search(['first_name' => 'James', 'last_name' => 'Picard']);

		$this->assertTrue(empty($result));
	}

	public function testORMQueryBuilderShouldReturnOneResult()
	{
		$this->databaseInit();

		$result = \Toadsuck\Core\Tests\App\Models\Captain::search(['first_name' => 'James']);

		$this->assertTrue(count($result) == 1);
	}

	public function testORMQueryBuilderShouldReturnThreeResults()
	{
		$this->databaseInit();

		$result = \Toadsuck\Core\Tests\App\Models\Captain::search(['first_name' => 'J']);

		$this->assertTrue(count($result) == 3);
	}

	protected function getTestDsn()
	{
		return ['driver' => 'sqlite','database' => __DIR__ . '/resources/storage/example.sqlite'];
	}

	protected function databaseInit()
	{
		Database::init($this->getTestDsn());
	}
}
