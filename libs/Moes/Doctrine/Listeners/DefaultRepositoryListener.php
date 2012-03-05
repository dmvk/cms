<?php

namespace Moes\Doctrine\Listeners;

use Nette;
use Doctrine;

class DefaultRepositoryListener implements Doctrine\Common\EventSubscriber
{

	public function getSubscribedEvents()
	{
		return array(Doctrine\ORM\Events::loadClassMetadata);
	}

	public function loadClassMetadata(Doctrine\ORM\Event\LoadClassMetadataEventArgs $args)
	{
		$meta = $args->getClassMetadata();
		
		if ($meta->isMappedSuperclass)
			return;

		if (!$meta->customRepositoryClassName)
			$meta->setCustomRepositoryClass('Moes\Doctrine\EntityRepository');

		$type = new Nette\Reflection\ClassType($meta->customRepositoryClassName);
	}

}
