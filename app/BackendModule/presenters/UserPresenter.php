<?php

namespace BackendModule;

use Nette;

/**
 * @Secured(role="administrator")
 */
class UserPresenter extends BasePresenter
{

	private function loadUser($id)
	{
		$user = $this->context->model->identities->find($id);

		if (!$user)
			throw new Nette\Application\BadRequestException();

		return $user;
	}

	public function actionAdd()
	{
		$this["identityForm-password"]->setRequired();
		$this->view = "form";
	}

	public function actionEdit($id)
	{
		$this["identityForm"]->bind($this->loadUser($id));
		$this->view = "form";
	}

	public function handleDelete($id)
	{
		$user = $this->loadUser($id);
		
		$this->context->model->identities->delete($user);
		
		$this->flashMessage("Uživatel byl odstraněn", "warning");
		$this->redirect("this");
	}

	public function createComponentIdentityForm()
	{
		return $this->context->components->createIdentityForm();
	}

	public function createComponentUserGrid()
	{
		return $this->context->components->createUserGrid();
	}
	
	public function createComponentChangePasswordForm()
	{
		return $this->context->components->createChangePasswordForm();
	}

}