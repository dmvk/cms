<?php

namespace Moes\Doctrine;

use Nette;
use PDOException;

class EntityRepository extends \Doctrine\ORM\EntityRepository
{

	public function createNew(array $values = NULL)
	{
		$class = $this->getEntityName();

		$entity = new $class;

		if ($values !== NULL) {
			$this->setData($entity, $values);
		}

		return $entity;
	}

	public function delete($entity)
	{
		$this->getEntityManager()->remove($entity);
		$this->getEntityManager()->flush();
	}

	public function save($entity)
	{
		try {
			$this->getEntityManager()->persist($entity);
			$this->getEntityManager()->flush();
		} catch (PDOException $e) {
			$this->processException($e);
		}
	}

	private function processException(PDOException $e)
	{
		$info = $e->errorInfo;

		if ($info[0] == 23000 && $info[1] == 1062) {
			// unique key
			throw new Exceptions\DuplicateEntryException($e->getMessage(), NULL, $e);
		} else {
			throw new Exceptions\Exception($e->getMessage(), NULL, $e);
		}
	}

	public function flush()
	{
		$this->getEntityManager()->flush();
	}

	public function getReference($id)
	{
		return $this->getEntityManager()->getReference($this->getEntityName(), $id);
	}

	public function setData($entity, $values)
	{
		foreach ($values as $key => $value) {
			$entity->$key = $value;
		}
	}

	public function createQuery($dql)
	{
		return $this->getEntityManager()->createQuery($dql);
	}

}