<?php

namespace Moes\Security;

use Moes\Doctrine\EntityRepository;

class IdentityRepository extends EntityRepository
{

	public function registerFacebookUser($facebook)
	{
		$user = $this->createNew();

		$user->email = $facebook["email"];
		$user->facebook = $facebook;

		$role = $this->createQuery("SELECT r FROM Moes\Security\Role r WHERE r.name = 'registered'")->getSingleResult();
		
		if (!$role) {
			throw new \Nette\InvalidStateException();
		}
		
		$user->role = $role;

		$this->save($user);
		
		return $user;
	}

}