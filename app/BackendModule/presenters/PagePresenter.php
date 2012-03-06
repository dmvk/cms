<?php

namespace BackendModule;

use Doctrine\DBAL\LockMode;

/**
 * @Secured(role="admin")
 */
class PagePresenter extends BasePresenter
{

	private function loadPage($id)
	{
		$page = $this->context->model->pages->find($id, $lockMode);

		if (!$page)
			throw new \Nette\Application\BadRequestException();

		return $page;
	}

	public function actionAdd()
	{
		$this['pageForm-slug']->setDisabled();
		$this->view = 'form';
	}

	public function actionEdit($id)
	{
		$this['pageForm']->bind($this->loadPage($id));
		$this->view = 'form';
	}

	public function handleDelete($id)
	{
		$this->context->model->pages->delete($this->loadPage($id));

		$this->flashMessage('Stránka byla odstraněna');
		$this->redirect('this');
	}

	protected function createComponentPageForm($name)
	{
		return $this->context->components->createPageForm();
	}

	protected function createComponentPageGrid()
	{
		return $this->context->components->createPageGrid();
	}

}