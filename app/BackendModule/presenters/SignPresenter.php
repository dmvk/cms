<?php

namespace BackendModule;

class SignPresenter extends BasePresenter
{

	/** @persistent */
	public $backlink;

	public function actionIn()
	{
		$facebook = $this->context->facebook->getLoginUrl(array(
			"scope" => "email",
			"redirect_uri" => $this->link("//facebookLogin")
			));

		$this->template->facebook = $facebook;
	}

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage("Byl jsi odhlášen.");
		$this->redirect("in");
	}

	public function actionFacebookLogin()
	{
		$me = $this->context->facebook->api("/me");

		$this->user->login(
			$this->context->facebookAuthenticator->authenticate($me)
		);

		$this->flashMessage("Byl jsi přihlášen přes facebook.", "success");

		$this->application->restoreRequest($this->backlink);
		$this->redirect("Dashboard:");
	}

	protected function createComponentSignInForm()
	{
		return $this->context->components->createLoginForm();
	}

}