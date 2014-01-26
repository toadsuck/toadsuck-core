# Toadsuck.Core

## Front controller and other core libraries used by the [Toadsuck Project](http://toadsuck.github.io)

This library provides a basic front controller to provide routing and dispatching for your PHP app.

Routing is implemented using [Aura.Router](https://github.com/auraphp/Aura.Router) from [The Aura Project for PHP](http://auraphp.com/).

See [Toadsuck.Skeleton](https://github.com/toadsuck/toadsuck-skeleton) for an example implementation, or keep reading.

## Installation
Installation of this package is easy with Composer. If you aren't familiar with the Composer Dependency Manager for PHP, [you should read this first](https://getcomposer.org/doc/00-intro.md).

If you don't already have [Composer](https://getcomposer.org) installed (either globally or in your project), you can install it like this:

``` bash
$ curl -sS https://getcomposer.org/installer | php
```

Create a file named composer.json someplace (usually in the root of your project) with the following content.

``` json
{
	"require": {
		"joshmoody/mock-data": "dev-master"
	}
}
```

## Example Usage:

Assume the following directory structure:

```
src/
	controllers/
		Home.php
vendor/
web/
	index.php
composer.json

```

- Your application resides in `src/` and has a base namespace of `Example\Project`
- Your controllers are in `src/controllers/` ahd have a namespace of `Example\Project\Controllers`
- Your web/index.php contains the following content:

``` php
<?php

namespace Example\Project;

# Filename: web/index.php

$app_dir = dirname(__DIR__);

// Use Composer's autoloader.
require_once $app_dir . '/vendor/autoload.php';

// Pass a couple options to our dispatcher.
$opts = ['app_dir' => $app_dir, 'namespace' => __NAMESPACE__];

$app = new \Toadsuck\Core\Dispatcher($opts);

// Dispatch the request.
$app->dispatch();
```
### Default Routes
Some sensible default routes are provided out of the box.

``` php
$router->add(null, null);
$router->add(null, '/');
$router->add(null, '/{controller}');
$router->add(null, '/{controller}/{action}');
$router->add(null, '/{controller}/{action}/{id}');
```

The following will all call the `index()` method of your `Home` controller.

- http://example.com/path/to/index.php
- http://example.com/path/to/index.php/home
- http://example.com/path/to/index.php/home/index

This one will also call `index`, but will also pass the value "1" to the index method.

- http://example.com/path/to/index.php/home/index/1

Likewise, you can call the `bar()` method of the `Foo` controller like this:

- http://example.com/path/to/index.php/foo/bar

If you want to supply different routes, just create a `config` directory under source with a 
file named `routes.php` that defines the routes you need.


```
src/
	config/
		routes.php
```

For more information on routing options, see <https://github.com/auraphp/Aura.Router>.