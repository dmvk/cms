<?php

namespace Moes\Routers;

use Nette;
use Nette\Application\Request;

class ConsoleRouter extends Nette\Object implements Nette\Application\IRouter
{
	private $callback;

	public function __construct(Nette\DI\Container $container)
	{
		$this->callback = callback(function() use ($container) {
			$container->console->application->run();
		});
	}

	public function match(Nette\Http\IRequest $httpRequest)
	{
		return PHP_SAPI !== 'cli' ? NULL : new Request('Nette:Micro', 'CLI', array('callback' => $this->callback));
	}

	public function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		return NULL;
	}
}
