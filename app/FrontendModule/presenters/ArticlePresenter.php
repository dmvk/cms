<?php

namespace FrontendModule;

class ArticlePresenter extends BasePresenter
{

	public function actionShow($slug)
	{
		$article = $this->context->model->articles->findOneBySlug($slug);

		if (!$article)
			throw new \Nette\Application\BadRequestException();

		$this["comments"]->group = $article->commentGroup;
		
		$this->template->title = $article->title;
		$this->template->article = $article;
	}

	public function createComponentComments()
	{
		return $this->context->components->createComments();
	}

}
