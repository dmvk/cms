<?php

namespace Moes\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Moes\Doctrine\Entities\IdentifiedEntity;
use Nette\Security\IRole;

/**
 * @Entity
 * @Table(name="acl_roles") 
 */
class Role extends IdentifiedEntity implements IRole
{

	/**
	 * @Column(length=128, unique=true)
	 */
	private $name;

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	// IRole interface

	public function getRoleId()
	{
		return $this->name;
	}

}