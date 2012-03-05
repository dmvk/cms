<?php

namespace Moes\Security;

use Moes\Doctrine\EntityRepository;
use Nette\Http\Session;

class UserStorage extends \Nette\Http\UserStorage
{

	/**
	 * @var Identity|NULL
	 */
	private $identity = NULL;

	/**
	 * @var EntityRepository
	 */
	private $repository;

	public function __construct(Session $sessionHandler, EntityRepository $repository)
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