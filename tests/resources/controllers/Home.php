<?php

namespace Toadsuck\Core\Tests\App\Controllers;

use Toadsuck\Core\Controller;

class Home extends Controller
{
	public function __construct($opts)
	{
		parent::__construct($opts);
	}

	public function index()
	{
		print 'HOME\INDEX';
	}
	
	public function renderTemplate()
	{
		echo $this->template->render('foo', ['foo' => 'bar']);
	}

	public function outputTemplate()
	{
		$this->template->output('foo', ['foo' => 'bar']);
	}
}
