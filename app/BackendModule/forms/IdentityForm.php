<?php

namespace BackendModule;

use Moes\Security\IdentityRepository;
use Moes\Security\RoleRepository;

class IdentityForm extends \Moes\Doctrine\EntityForm
{

	private $roles;

	public function __construct(IdentityRepository $identityRepository, RoleRepository $roleRepository)
	{
		parent::__construct($identityRepository);
		$this->roles = $roleRepository->findAll();
	}

	protected function init()
	{
		$this->addText("email", "Email")
			->setRequired()
			->addRule(self::EMAIL);

		$this->addEntitySelect('role', 'Role', $this->roles)
			->setRequired();

		$this->addPassword("password", "Heslo")
			->addCondition(self::FILLED)
			->addRule(self::MIN_LENGTH, "Heslo musí být dlouhé alespoň %d znaků.", 6);			

		$this->addPassword("passwordre", "Heslo znovu")
				->addRule(self::EQUAL, "Hesla se neshoduji", $this["password"])
				->addConditionOn($this["password"], self::FILLED);

		$this->addSubmit("save", "Uložit");
	}

	public function process($form)
	{
		$values = (array) $form->values;
		
		// @todo tohle by melo umet setData

		unset($values["passwordre"]);
		
		if($values["password"] === NULL) {
			unset($values["password"]);
		}
			
		if ($form->entity) {
			// update
			$entity = $form->entity;
			$this->repository->setData($entity, $values);

			$this->presenter->logUpdateAction($entity->email);
			$this->presenter->flashMessage("Uživatel byl upraven", "success");
		} else {
			$entity = $this->repository->createNew($values);

			$this->presenter->logCreateAction($entity->email);
			$this->presenter->flashMessage("Uživatel byl vytvořen", "success");
		}

		$this->repository->save($entity);
		$this->presenter->redirect("default");
	}

}