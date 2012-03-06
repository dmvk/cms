<?php

namespace FrontendModule;

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->registerHelper("texy", callback($this->context->texy, "process"));
	}

	protected function createComponentCss()
	{
		return $this->context->components->createFrontendCss();
	}

	protected function createComponentJs()
	{
		return $this->context->components->createFrontendJs();
	}

}
