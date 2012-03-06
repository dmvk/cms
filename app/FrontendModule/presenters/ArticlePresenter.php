<?php

namespace FrontendModule;

class ArticlePresenter extends BasePresenter
{

	public function actionShow($slug)
	{
		$article = $this->context->model->articles->findOneBySlug($slug);

		if (!$article)
			throw new \Nette\Application\BadRequestException();

		$this->template->article = $article;
	}

	public function createComponentComments()
	{
		return new Comment\Control($this->context->repository->article);
	}

}
