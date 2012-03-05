<?php

namespace BackendModule;

class UserForm extends \Moes\Doctrine\EntityForm
{

	private $roles;

	public function __construct($repository, $roles)
	{
		parent::__construct($repository);
		$this->roles = $roles;
	}

	protected function init()
	{
//		$this->addText("username", "Username")->setRequired();
		$this->addText("email", "E-mail")->addRule(self::EMAIL)->setRequired();
		$this->addPassword("password", "Password")->setRequired();

		$this->addEntitySelect('role', 'Role', $this->roles)->setPrompt('------');;
//		$this->addPassword("passwordre", "Password-re")
//				->addRule(self::EQUAL, "Hesla se neshoduji", $this["password"])
//				->setRequired();

		$this->addSubmit("save", "Uložit");
	}

	public function process($form)
	{
		if ($form->entity) {
			// update
			$entity = $form->entity;
			$this->repository->setData($entity, $form->values);

			$this->presenter->logUpdateAction($entity->email);
			$this->presenter->flashMessage("Uživatel byl upraven", "success");
		} else {
			$entity = $this->repository->createNew((array) $form->values);

			$this->presenter->logCreateAction($entity->email);
			$this->presenter->flashMessage("Uživatel byl vytvořen", "success");
		}

		$this->repository->save($entity);
		$this->presenter->redirect("default");
	}

}