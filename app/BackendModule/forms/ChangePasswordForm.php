<?php

namespace BackendModule;

use Moes\Doctrine\EntityRepository;
use Moes\Security\Identity;

class ChangePasswordForm extends \Moes\Form\BaseForm
{

	/**
	 * @var EntityRepository
	 */
	private $repository;

	/**
	 * @var Identity
	 */
	private $identity;

	public function __construct(EntityRepository $repository, Identity $identity)
	{
		parent::__construct();
		$this->repository = $repository;
		$this->identity = $identity;
	}

	protected function init()
	{
		$this->addPassword("current", "Současné heslo")
			->setRequired("Zadejte prosím stávající heslo");

		$this->addPassword("password", "Nové heslo")
			->setRequired("Zadejte prosím nové heslo")
			->addRule(self::MIN_LENGTH, "Nové heslo musí mít alespoň %d znaků", 6);

		$this->addPassword("passwordre", "Nové heslo znovu")
			->setRequired("Zadejte prosím heslo ještě jednou pro kontrolu")
			->addRule(self::EQUAL, "Hesla se neshoduji", $this["password"]);

		$this->addSubmit("save", "Uložit");
	}

	public function process($form)
	{
		if (!$this->identity->verifyPassword($form["current"]->value)) {
			$form->addError("Současné heslo nesouhlasí");
		} else {
			$this->identity->setPassword($form["password"]->value);

			$this->repository->save($this->identity);

			$this->presenter->flashMessage("Heslo bylo změněno", "success");
			$this->presenter->redirect("Dashboard:");
		}
	}

}