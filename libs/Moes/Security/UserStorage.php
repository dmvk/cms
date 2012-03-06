<?php

namespace Moes\Security;

use Nette\Http\Session;

class UserStorage extends \Nette\Http\UserStorage
{

	/**
	 * @var Identity|NULL
	 */
	private $identity = NULL;

	/**
	 * @var IdentityRepository
	 */
	private $repository;

	public function __construct(Session $sessionHandler, IdentityRepository $repository)
	{
		parent::__construct($sessionHandler);
		$this->repository = $repository;
	}

	/**
	 * {@inheritdoc} 
	 */
	public function getIdentity()
	{
		$session = $this->getSessionSection(FALSE);

		if ($this->identity === NULL && $session && $session->identity instanceof Identity) {
			$this->identity = $session->identity->load($this->repository);
		}

		return $this->identity;
	}

}