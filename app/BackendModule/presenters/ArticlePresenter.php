<?php

namespace BackendModule;

use Moes\Logger\ILogger;

/**
 * @Secured(role="admin")
 */
class ArticlePresenter extends BasePresenter
{

	private function loadArticle($id)
	{
		$article = $this->context->model->articles->find($id);

		if (!$article)
			throw new \Nette\Application\BadRequestException();

		return $article;
	}

	public function actionAdd()
	{
		$this["articleForm-slug"]->setDisabled();
		$this["articleForm-publish"]->setDisabled();
		$this->view = "form";
	}

	public function actionEdit($id)
	{
		$this["articleForm"]->bind($this->loadArticle($id));
		$this->view = "form";
	}

	public function handleTrash($id)
	{
		$article = $this->loadArticle($id);
		$this->context->model->articles->trash($article);

		$this->logUpdateAction("Přesunut do koše: $article->title");
		$this->flashMessage("Článek '$article->title' byl přesunut do koše", "error");
		$this->redirect("this");
	}
	
	public function handlePublish($id)
	{
		$article = $this->loadArticle($id);
		$this->context->model->articles->publish($article);

		$this->logUpdateAction("Publikován: $article->title");
		$this->flashMessage("Článek '$article->title' byl publikován.", "success");
		$this->redirect("this");		
	}

	public function handleRestore($id)
	{
		$article = $this->loadArticle($id);
		$this->context->model->articles->draft($article);

		$this->logUpdateAction("Obnoven: $article->title");
		$this->flashMessage("Článek '$article->title' byl obnoven.", "success");
		$this->redirect("this");
	}

	public function createComponentArticleForm()
	{
		return $this->context->components->createArticleForm();
	}

	public function createComponentArticleGrid()
	{
		return $this->context->components->createArticleGrid();
	}

	public function createComponentArticleTrashGrid()
	{
		return $this->context->components->createArticleTrashGrid();
	}

}