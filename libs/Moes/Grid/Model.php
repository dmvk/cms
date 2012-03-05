<?php

namespace Moes\Grid;

use Moes,
    Nette;

class Model extends Nette\Object
{

	/**
	 * @var \Doctrine\ORM\QueryBuilder
	 */
	private $qb;

	public function __construct(\Doctrine\ORM\EntityRepository $repository)
	{
		$this->qb = $repository->createQueryBuilder('a');
	}
	
	/**
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getQb()
	{
		return $this->qb;
	}

	public function getItems()
	{
		return $this->qb->getQuery()->getResult();
	}

	public function setPaginator(Nette\Utils\Paginator $paginator)
	{
		// naklonovanej qb - jen kvuli count
		$qb = clone $this->qb;
		
		$paginator->itemCount = $qb->select('count(a)')
			->getQuery()
			->getSingleScalarResult();

		// nastaveni qb podle paginatoru
		$this->qb->setMaxResults($paginator->length);
		$this->qb->setFirstResult($paginator->offset);
	}

}
