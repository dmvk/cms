<?php

namespace Moes\Logger;

use Moes\Doctrine\EntityRepository;
use Moes\Security\Identity;

class Logger extends EntityRepository implements ILogger
{

	public function logAction($presenter, $action, $message, Identity $user)
	{
		$entity = $this->createNew();

		$entity->presenter = $presenter;
		$entity->action = $action;
		$entity->message = $message;
		$entity->user = $user;

		$this->getEntityManager()->persist($entity);
	}
	
	
	public function findAllOrderedByTimestamp()
	{
		$entity = $this->getEntityName();
		return $this->createQuery("SELECT a FROM $entity a ORDER BY a.timestamp DESC")->getResult();
	}

}