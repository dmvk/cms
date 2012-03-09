<?php

namespace BackendModule;

/**
 * @Secured(role="administrator")
 */
class PagePresenter extends BasePresenter
{

	private function loadPage($id)
	{
		$page = $this->context->model->pages->find($id);

		if (!$page)
			throw new \Nette\Application\BadRequestException();

		return $page;
	}

	public function actionAdd()
	{
		$this['pageForm-slug']->setDisabled();
		$this->view = 'form';
	}

	public function actionDiff($id, $version)
	{
		$page = $this->loadPage($id);
		$revision = $this->context->doctrine->versionManager->getVersion($page, $version);

		// @todo sluzba???
		$diff = new \Moes\Utils\SimpleDiff();

		$this->template->diff = $diff->htmlDiff($revision->getVersionedData("text"), $page->text);

		// need this for links
		$this->template->id = $id;
		$this->template->version = $version;
	}

	public function actionEdit($id)
	{
		$page = $this->loadPage($id);

		$this->template->versions = $this->context->doctrine->versionManager->getVersions($page);
		$this['pageForm']->bind($page);
		$this->view = 'form';
	}

	public function handleDelete($id)
	{
		$this->context->model->pages->delete($this->loadPage($id));

		$this->flashMessage('Stránka byla odstraněna');
		$this->redirect('this');
	}

	public function handleRevert($id, $version)
	{
		$this->context->doctrine->versionManager->revert($this->loadPage($id), $version);

		// @todo tohle by mel resit VersionManager
		$this->context->doctrine->entityManager->flush();

		$this->flashMessage('Revize byla obnovena');
		$this->redirect('edit', array($id));
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