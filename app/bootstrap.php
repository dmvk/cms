<?php

use Nette\Application\Routers\Route;

$params = array(
	"appDir" => __DIR__,
	"libsDir" => __DIR__ . "/../libs"
);

// loads Nette
require $params["libsDir"] . "/Nette/loader.php";

// Shortcut for barDump
function dd($var) {
	Nette\Diagnostics\Debugger::barDump($var);
}

// configuration
$configurator = new Nette\Config\Configurator();

$configurator->enableDebugger(__DIR__ . "/../log");
$configurator->setTempDirectory(__DIR__ . "/../temp");

$configurator->createRobotLoader()
	->addDirectory($params["appDir"])
	->addDirectory($params["libsDir"])
	->register();

// extensions
$configurator->onCompile[] = function($cfg, $compiler) {	
	$compiler->addExtension("doctrine", new Moes\Config\Extensions\DoctrineExtension());
	$compiler->addExtension("console", new Moes\Config\Extensions\ConsoleExtension());
	$compiler->addExtension("model", new Moes\Config\Extensions\DummyExtension());
	$compiler->addExtension("components", new Moes\Config\Extensions\DummyExtension());
};

$configurator->addParameters($params);

// loads config.neon
$configurator->addConfig(__DIR__ . "/config/config.neon");

// creates container
$container = $configurator->createContainer();

$container->session->setExpiration('+ 14 days');
if ($container->session->exists()) {
	$container->session->start();
}

// routing
$router = $container->router;

// router for CLI
$router[] = new Moes\Routers\ConsoleRouter($container);

$router[] = new Route("index.php", "Frontend:Homepage:default", Route::ONE_WAY);

$router[] = new Route("admin/<presenter>/<action>", array(
	"module" => "Backend",
	"presenter" => "Dashboard",
	"action" => "default"
));

$router[] = new Route("clanek/<slug>", array(
	"module" => "Frontend",
	"presenter" => "Article",
	"action" => "show"
));

$router[] = new Route("<slug>", array(
	"presenter" => "Frontend:Page",
	"action" => "show"
));

$router[] = new Route("<presenter>/<action>[/<id>]", "Frontend:Homepage:default");

// run
//$container->application->run();