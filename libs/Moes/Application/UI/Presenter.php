<?php

namespace Moes\Application\UI;

use Moes\Logger\ILogger;
use Nette\Application\ForbiddenRequestException;
use Nette\Security\IAuthorizator;
use Nette\Security\User;

class Presenter extends \Nette\Application\UI\Presenter
{

	/**
	 * {@inheritdoc}
	 */
	public function checkRequirements($element)
	{
		$user = $this->user;

		if ($element->hasAnnotation("Secured")) {
			if (!$user->isLoggedIn()) {
				if ($user->getLogoutReason() === User::INACTIVITY) {
					$this->flashMessage("Byl jsi odhlášen, protože jsi nebyl dlouho aktivní.");
				}

				$this->flashMessage("Pro vstup do této části webu se musíš přihlásit.");
				$this->redirect(":Backend:Sign:in", array("backlink" => $this->storeRequest()));
			}

			$secured = (array) $element->getAnnotation("Secured");

			if (isset($secured["role"]) && !$user->isInRole($secured["role"])) {
				throw new ForbiddenRequestException();
			}
		}

		// @todo dodelat autorizaci pomoci Resouce a Privilege

		if ($element->hasAnnotation("Resource")) {
			$privilege = $element->hasAnnotation("Privilege") ? $element->getAnnotation("Privilege") : IAuthorizator::ALL;
			if (!$user->isAllowed($element->getAnnotation("Resource"), $privilege)) {
				throw new ForbiddenRequestException();
			}
		}
	}

	private function logAction($action, $message)
	{
		$this->context->logger->logAction($this->name, $action, $message, $this->user->identity);
	}

	public function logCreateAction($message)
	{
		$this->logAction(ILogger::CREATE, $message);
	}

	public function logReadAction($message)
	{
		$this->logAction(ILogger::READ, $message);
	}

	public function logUpdateAction($message)
	{
		$this->logAction(ILogger::UPDATE, $message);
	}

	public function logDeleteAction($message)
	{
		$this->logAction(ILogger::UPDATE, $message);
	}

	public function logOtherAction($message)
	{
		$this->logAction(ILogger::UPDATE, $message);
	}

}