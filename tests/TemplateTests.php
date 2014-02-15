<?php

namespace Toadsuck\Core\Tests;

use Toadsuck\Core\Template;

class TemplateTests extends \PHPUnit_Framework_TestCase
{
	public function testEscapeClassVars()
	{
		$template = new Template(__DIR__ . '/resources/views/');

		$template->layout('layouts/default');

		$template->foo = '<foo>';
		$output = $template->render('foo');

		$this->assertRegExp('/&lt;foo&gt;/', $output);
	}

	public function testEscapeViewVars()
	{
		$template = new Template(__DIR__ . '/resources/views/');

		$template->layout('layouts/default');

		$output = $template->render('foo', ['foo' => '<foo>']);

		$this->assertRegExp('/&lt;foo&gt;/', $output);
	}

	public function testUnguardViewVars()
	{
		$template = new Template(__DIR__ . '/resources/views/');

		$template->layout('layouts/default');
		$template->unguard('foo');

		$output = $template->render('foo', ['foo' => '<foo>']);

		$this->assertRegExp('/\<foo\>/', $output);
	}

	public function testUnguardClassVars()
	{
		$template = new Template(__DIR__ . '/resources/views/');

		$template->layout('layouts/default');
		$template->unguard('foo');

		$template->foo = '<foo>';
		$output = $template->render('foo');

		$this->assertRegExp('/\<foo\>/', $output);
	}
}
