<?php

namespace BackendModule;

/**
 * @Secured(role="admin")
 */
class CategoryPresenter extends BasePresenter
{

	private function loadCategory($id)
	{
		$category = $this->context->model->categories->find($id);

		if (!$category)
			throw new \Nette\Application\BadRequestException();

		return $category;
	}

	public function actionAdd()
	{
		$this->view = 'form';
	}

	public function actionEdit($id)
	{
		$this['categoryForm']->bind($this->loadArticle($id));
		$this->view = 'form';
	}

	public function handleDelete($id)
	{
		$this->context->model->categories->delete($this->loadCategory($id));

		$this->flashMessage('Kategorie byla smazána');
		$this->redirect('this');
	}

	public function createComponentCategoryForm()
	{
		return $this->context->components->createCategoryForm();
	}

	public function createComponentCategoryGrid()
	{
		return $this->context->components->createCategoryGrid();
	}

}