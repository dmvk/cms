<?php

namespace Moes\Security;

use Moes\Doctrine\EntityRepository;

class FacebookAuthenticator
{

	private $repository;

	public function __construct(IdentityRepository $repository)
	{
		$this->repository = $repository;
	}

	public function authenticate(array $facebook)
	{
		$user = $this->repository->findOneByEmail($facebook["email"]);

		if (!$user) {
			$user = $this->repository->registerFacebookUser($facebook);
		}

		return $user;
	}

}