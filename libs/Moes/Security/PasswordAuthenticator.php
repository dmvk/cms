<?php

namespace Moes\Security;

use Nette\Security as NS;
use Moes\Doctrine\EntityRepository;

class PasswordAuthenticator implements NS\IAuthenticator
{

	/**
	 * @var EntityRepository
	 */
	private $repository;

	public function __construct(EntityRepository $repository)
	{
		$this->repository = $repository;
	}

	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		$user = $this->repository->findOneByEmail($email);

		if (!$user) {
			throw new NS\AuthenticationException("Uživatel s emailem '$email' nebyl nalezen.", self::IDENTITY_NOT_FOUND);
		}

		if (!$user->verifyPassword($password)) {
			throw new NS\AuthenticationException("Špatné heslo.", self::INVALID_CREDENTIAL);
		}

		return $user;
	}

}
