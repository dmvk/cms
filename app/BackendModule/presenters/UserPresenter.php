<?php

namespace BackendModule;

use Nette;

/**
 * @Secured(role="admin")
 */
class UserPresenter extends BasePresenter
{

	private function loadUser($id)
	{
		$user = $this->context->repository->user->find($id);

		if (!$user)
			throw new Nette\Application\BadRequestException();

		return $user;
	}

	public function actionAdd()
	{
		$this->view = "form";
	}

	public function actionEdit($id)
	{
		$this["userForm"]->bind($this->loadUser($id));
		$this->view = "form";
	}

	public function createComponentUserForm()
	{
		return $this->context->createUserForm();
	}

	public function createComponentUserGrid()
	{
		return $this->context->createUserGrid();
	}

}