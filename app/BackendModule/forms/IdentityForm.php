<?php

namespace BackendModule;

use Moes\Security\Authorizator;
use Moes\Security\IdentityRepository;

class IdentityForm extends \Moes\Doctrine\EntityForm
{

	private $roles;

	public function __construct(IdentityRepository $identityRepository, Authorizator $authorizator)
	{
		parent::__construct($identityRepository);
		$this->roles = $authorizator->roles;
	}

	protected function init()
	{
		$this->addText("email", "Email")
			->setRequired()
			->addRule(self::EMAIL);

		$this->addSelect('role', 'Role')
			->setItems($this->roles, FALSE)
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
		
		if(empty($values["password"])) {
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