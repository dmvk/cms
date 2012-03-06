<?php

namespace FrontendModule;

class PagePresenter extends BasePresenter
{

	public function actionShow($slug)
	{
		$page = $this->context->model->pages->findOneBySlug($slug);

		if (!$page)
			throw new \Nette\Application\BadRequestException();

		$this->template->title = $page->title;
		$this->template->page = $page;
	}
}