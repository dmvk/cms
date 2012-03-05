<?php

namespace Moes\Security;

use Nette;

class FacebookAuthenticator implements Nette\Security\IAuthenticator
{

	private $repository;

	public function __construct($repository)
	{
		$this->repository = $repository;
	}

	public function authenticate(array $fbUser)
	{
		$user = $this->repository->findOneByEmail($fbUser['email']);

		if ($user) {
			$this->updateMissingData($user, $fbUser);
		} else {
			$user = $this->register($fbUser);
		}

		return $user;
	}

	public function register(array $me)
	{
		$user = $this->repository->createNew();
		$user->email = $me['email'];

		$this->updateMissingData($user, $me);

		return $user;
	}

	public function updateMissingData($user, array $me)
	{
		$user->facebook = $me;
		$this->repository->save($user);
	}

}