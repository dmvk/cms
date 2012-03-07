<?php

namespace Moes\Security;

use Moes\Doctrine\EntityRepository;
use Nette\Security\Permission;

class Authorizator extends Permission
{

	public function __construct(RoleRepository $repository)
	{
		$roles = $repository->findAll();

		foreach ($roles as $role) {
			$this->addRole($role->name);
		}
	}

}