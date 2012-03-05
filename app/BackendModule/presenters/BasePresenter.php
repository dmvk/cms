<?php

namespace BackendModule;

use Moes\Application\UI\Presenter;
use Moes\Logger\ILogger;

abstract class BasePresenter extends Presenter
{

	protected function beforeRender()
	{
		if ($this->isAjax()) {
			$this->invalidateControl('flash');
		}

		$this->template->identity = $this->user->identity;
		$this->template->registerHelper("timeAgoInWords", "Moes\Latte\Helpers::timeAgoInWords");
	}

	public function createComponentCss()
	{
		return $this->context->createBackendCss();
	}

	public function createComponentJs($name)
	{
		return $this->context->createBackendJs();
	}

}