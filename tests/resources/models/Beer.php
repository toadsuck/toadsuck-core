<?php

namespace Toadsuck\Core\Tests\App\Models;

use Toadsuck\Core\Model;

class Beer extends Model
{
	public $timestamps = false;
	public $connection = 'beers';
}