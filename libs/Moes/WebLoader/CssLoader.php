<?php

namespace Moes\WebLoader;

use WebLoader;

abstract class CssLoader extends WebLoader\Nette\CssLoader
{

	public function __construct($context)
	{
		$wwwDir = $context->parameters['wwwDir'];
		$basePath = $context->httpRequest->url->basePath;

		$files = new WebLoader\FileCollection($wwwDir);
		$files->addFiles($this->getFiles());

		$compiler = WebLoader\Compiler::createCssCompiler($files, "$wwwDir/temp");

		// filters
		$compiler->addFileFilter(new WebLoader\Filter\LessFilter);
		$compiler->addFileFilter(new WebLoader\Filter\CssUrlsFilter($wwwDir, $basePath));

//		$compiler->setJoinFiles(FALSE);

		parent::__construct($compiler, $basePath . "temp");
	}

	abstract protected function getFiles();
}