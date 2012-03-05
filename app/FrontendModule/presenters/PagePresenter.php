<?php

namespace FrontendModule;

class PagePresenter extends BasePresenter
{

	public function actionShow($slug)
	{
		$page = $this->context->repository->page->findOneBySlug($slug);

		if (!$page)
			throw new \Nette\Application\BadRequestException();

		$this->template->page = $page;
	}
}