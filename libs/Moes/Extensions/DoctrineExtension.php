<?php

namespace Moes\Config\Extensions;

use Doctrine;

class DoctrineExtension extends \Nette\Config\CompilerExtension
{

	private $defaults = array(
	);

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		// cache
		$builder->addDefinition($this->prefix("cache"))
			->setClass("Moes\Doctrine\Cache");

		// logger
		$builder->addDefinition($this->prefix("logger"))
			->setClass("Doctrine\DBAL\Logging\SQLLogger")
			->setFactory("Moes\Doctrine\Diagnostics\Panel::register");

		$builder->addDefinition($this->prefix("metadataDriver"))
			->setClass("Doctrine\ORM\Mapping\Driver\Driver")
			->setFactory("Moes\Config\Extensions\DoctrineExtension::createMetadataDriver", array($config["configuration"]["entityDirs"]));

		// configuration
		$configuration = $builder->addDefinition($this->prefix("configuration"))
			->setClass("Doctrine\ORM\Configuration")
			->addSetup("setMetadataCacheImpl")
			->addSetup("setMetadataDriverImpl")
			->addSetup("setQueryCacheImpl")
			->addSetup("setAutoGenerateProxyClasses", array(TRUE)) // !production
			->addSetup("setProxyDir", array($config["configuration"]["proxyDir"]))
			->addSetup("setProxyNamespace", array($config["configuration"]["proxyNamespace"]));

		if (!$builder->parameters["productionMode"]) {
			$configuration->addSetup("setSQLLogger");
		}
		// event manager
		$builder->addDefinition($this->prefix("eventManager"))
			->setClass("Doctrine\Common\EventManager")
			->setFactory("Moes\Config\Extensions\DoctrineExtension::createEventManager", array($config["database"], "@container"));

		// entity manager
		$builder->addDefinition($this->prefix("entityManager"))
			->setClass("Doctrine\ORM\EntityManager")
			->setFactory("Doctrine\ORM\EntityManager::create", array($config["database"]));
	}

	public static function createMetadataDriver($paths)
	{
		Doctrine\Common\Annotations\AnnotationRegistry::registerFile(LIBS_DIR . "/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");

		$reader = new Doctrine\Common\Annotations\SimpleAnnotationReader();
		$reader->addNamespace("Doctrine\ORM\Mapping");

		$reader = new Doctrine\Common\Annotations\CachedReader($reader, new Doctrine\Common\Cache\ArrayCache());

		return new Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, (array) $paths);
	}

	/**
	 * @param array $db
	 * @param \Nette\DI\IContainer $container
	 * @return Doctrine\Common\EventManager 
	 */
	public static function createEventManager($db, \Nette\DI\IContainer $container)
	{
		$evm = new Doctrine\Common\EventManager;

		if (isset($db["driver"]) && $db["driver"] == "pdo_mysql" && isset($db["charset"])) {
			$evm->addEventSubscriber(
				new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit($db["charset"])
			);
		}

		foreach (array_keys($container->findByTag("doctrineListener")) as $name) {
			$evm->addEventSubscriber($container->getService($name));
		}

		return $evm;
	}

}
