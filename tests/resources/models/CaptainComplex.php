<?php

namespace Toadsuck\Core\Tests\App\Models;

use Toadsuck\Core\Model;

class CaptainComplex extends Model
{
	public $timestamps = false;
	public $connection = 'example';
	public $table = 'captains';
}
