<?php

namespace Moes\Config\Extensions;

use Nette;

class RepositoryExtension extends Nette\Config\CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix("page"))
			->setClass("Moes\Doctrine\EntityRepository")
			->setFactory("@Doctrine\ORM\EntityManager::getRepository", array("Model\Page"));

		$builder->addDefinition($this->prefix("article"))
			->setClass("Model\ArticleRepository")
			->setFactory("@Doctrine\ORM\EntityManager::getRepository", array("Model\Article"));

		$builder->addDefinition($this->prefix("category"))
			->setClass("Moes\Doctrine\EntityRepository")
			->setFactory("@Doctrine\ORM\EntityManager::getRepository", array("Model\Category"));

		$builder->addDefinition($this->prefix("user"))
			->setClass("Moes\Doctrine\EntityRepository")
			->setFactory("@Doctrine\ORM\EntityManager::getRepository", array("Moes\Security\Identity"));
		
		$builder->addDefinition($this->prefix("role"))
			->setClass("Moes\Doctrine\EntityRepository")
			->setFactory("@Doctrine\ORM\EntityManager::getRepository", array("Moes\Security\Role"));

		$builder->addDefinition("logger")
			->setClass("Moes\Logger\Logger")
			->setFactory("@Doctrine\ORM\EntityManager::getRepository", array("Moes\Logger\Action"));
	}

}