<?php

namespace Moes\WebLoader;

use Nette\DI\Container;
use WebLoader;

abstract class JavaScriptLoader extends WebLoader\Nette\JavaScriptLoader
{

	public function __construct(Container $context)
	{
		$wwwDir = $context->parameters['wwwDir'];
		$basePath = $context->httpRequest->url->basePath;

		$files = new WebLoader\FileCollection(WWW_DIR);
		$files->addFiles($this->getFiles());

		$compiler = WebLoader\Compiler::createJsCompiler($files, "$wwwDir/temp");

		parent::__construct($compiler, $basePath . "temp");
	}

	abstract protected function getFiles();
}