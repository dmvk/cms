<?php

namespace BackendModule;

use Nette\Security\AuthenticationException;
use Nette\Security\User;

class LoginForm extends \Moes\Form\BaseForm
{

	/**
	 * @var User
	 */
	private $user;

	public function __construct(User $user)
	{
		parent::__construct();
		$this->user = $user;
	}

	public function init()
	{
		$this->addText("username", "E-mail")
			->setRequired("Please provide a username.");

		$this->addPassword("password", "Heslo")
			->setRequired("Please provide a password.");

		$this->addCheckbox("remember", "Zapamatuj si mě na tomto počítači");

		$this->addSubmit("send", "Sign in");
	}

	public function process($form)
	{
		try {
			$values = $form->getValues();

			if ($values->remember) {
				$this->user->setExpiration("+ 14 days", FALSE);
			} else {
				$this->user->setExpiration("+ 20 minutes", TRUE);
			}

			$this->user->login($values->username, $values->password);

			$this->presenter->flashMessage("Byl jsi přihlášen.", "success");

			$this->presenter->restoreRequest($this->presenter->backlink);
			$this->presenter->redirect(":Backend:Dashboard:");
		} catch (AuthenticationException $e) {
			$this->presenter->flashMessage($e->getMessage(), "alert-error");
//			$form->addError($e->getMessage());
		}
	}

}
