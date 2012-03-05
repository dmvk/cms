<?php

namespace Moes\Security;

use Moes\Doctrine\EntityRepository;

class Authorizator extends \Nette\Security\Permission
{

	public function __construct(EntityRepository $repository)
	{
		$roles = $repository->findAll();

		foreach ($roles as $role) {
			$this->addRole($role->name);
		}
	}

}