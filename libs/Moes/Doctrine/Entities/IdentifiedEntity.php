<?php

namespace Moes\Doctrine\Entities;

/**
 * @MappedSuperclass
 */
abstract class IdentifiedEntity extends \Nette\Object implements IEntity
{

	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	public function getId()
	{
		return $this->id;
	}

}