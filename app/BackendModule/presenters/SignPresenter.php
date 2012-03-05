<?php

namespace BackendModule;

use Nette\Application\UI,
    Nette\Security as NS;

/**
 * @todo Refractoring 
 */
class SignPresenter extends BasePresenter
{

	/** @persistent */
	private $backlink;

	public function actionIn()
	{
		$facebook = $this->context->facebook->getLoginUrl(array(
			"scope" => "email",
			"redirect_uri" => $this->link("//fbLogin")
		));

		$this->template->facebook = $facebook;
	}

	public function actionFbLogin()
	{
		$me = $this->context->facebook->api("/me");

		$identity = $this->context->facebookAuthenticator->authenticate($me);

		$this->user->login($identity);

		$this->flashMessage("Byl jsi přihlášen přes Facebook", "alert-info");
		$this->redirect("Dashboard:");
	}

	/**
	 * Sign in form component factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText("username", "E-mail:")
			->setRequired("Please provide a username.");

		$form->addPassword("password", "Password:")
			->setRequired("Please provide a password.");

		$form->addCheckbox("remember", "Remember me on this computer");

		$form->addSubmit("send", "Sign in");

		$form->onSuccess[] = callback($this, "signInFormSubmitted");
		return $form;
	}

	public function signInFormSubmitted($form)
	{
		try {
			$values = $form->getValues();
			if ($values->remember) {
				$this->getUser()->setExpiration("+ 14 days", FALSE);
			} else {
				$this->getUser()->setExpiration("+ 20 minutes", TRUE);
			}
			$this->getUser()->login($values->username, $values->password);

			$this->application->restoreRequest($this->backlink); // Restore

			$this->redirect(":Backend:Dashboard:");
		} catch (NS\AuthenticationException $e) {
			$this->flashMessage($e->getMessage(), "alert-error");
			$form->addError($e->getMessage());
		}
	}

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage("You have been signed out.");
		$this->redirect("in");
	}

}