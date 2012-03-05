<?php

namespace Moes\Doctrine;

use Nette;
use Nette\Reflection\ClassType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

class EntityForm extends \Moes\Form\BaseForm
{

	private $entity;
	private $repository;

	public function __construct($repository)
	{
		parent::__construct();
		$this->repository = $repository;
	}

	/**
	 * @author Jan Marek
	 */
	public function bind($entity)
	{
		$this->entity = $entity;
		$type = new ClassType($entity);

		foreach ($this->components as $input) {

			if ($type->hasMethod('get' . ucfirst($input->name))) {
				$method = 'get' . ucfirst($input->name);
			} elseif ($type->hasMethod('is' . ucfirst($input->name))) {
				$method = 'is' . ucfirst($input->name);
			} else {		
				continue;
			}
			
			$value = $entity->$method();

			if ($value instanceof Entities\IEntity) {
				$value = $value->id;
			} elseif ($value instanceof ArrayCollection || $value instanceof PersistentCollection) {
				$value = array_map(function ($entity) {
					return $entity->id;
				}, $value->toArray());
			}

			$input->setDefaultValue($value);
		}
	}

	public function getEntity()
	{
		return $this->entity;
	}

	public function getRepository()
	{
		return $this->repository;
	}

	public function addEntitySelect($name, $label = null, array $items = null, $idKey = 'id', $nameKey = 'name')
	{
		return $this[$name] = new Forms\EntitySelect($label, $items, $idKey, $nameKey);
	}

	public function addEntityMultiSelect($name, $label = null, array $items = null, $size = null, $idKey = 'id', $nameKey = 'name')
	{
		return $this[$name] = new Forms\EntityMultiSelect($label, $items, $size, $idKey, $nameKey);
	}

}
