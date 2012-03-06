<?php

namespace Moes\Config\Extensions;

use Nette;
use Symfony\Component\Console;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

class ConsoleExtension extends Nette\Config\CompilerExtension {

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		
		// DBAL commands
		$container->addDefinition($this->prefix('DBALRunSql'))
				->setClass('Doctrine\DBAL\Tools\Console\Command\RunSqlCommand')
				->addTag('consoleCommnad');

		$container->addDefinition($this->prefix('DBALImport'))
				->setClass('Doctrine\DBAL\Tools\Console\Command\ImportCommand')
				->addTag('consoleCommand');

		// console commands - ORM
		$container->addDefinition($this->prefix('ORMCreate'))
				->setClass('Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand')
				->addTag('consoleCommand');
		$container->addDefinition($this->prefix('ORMUpdate'))
				->setClass('Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand')
				->addTag('consoleCommand');
		$container->addDefinition($this->prefix('ORMDrop'))
				->setClass('Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand')
				->addTag('consoleCommand');
		$container->addDefinition($this->prefix('ORMGenerateProxies'))
				->setClass('Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand')
				->addTag('consoleCommand');
		$container->addDefinition($this->prefix('ORMRunDql'))
				->setClass('Doctrine\ORM\Tools\Console\Command\RunDqlCommand')
				->addTag('consoleCommand');
		
		// console application
		$container->addDefinition($this->prefix('application'))
				->setClass('Symfony\Component\Console\Application')
				->setFactory('Moes\Config\Extensions\ConsoleExtension::createApplication');
	}

	public static function createApplication(Nette\DI\Container $container)
	{
		$app = new Console\Application(Nette\Framework::NAME . " Command Line Interface", Nette\Framework::VERSION);

		$helperSet = new Console\Helper\HelperSet;
		$helperSet->set(new EntityManagerHelper($container->doctrine->entityManager), 'em');
		$helperSet->set(new Console\Helper\DialogHelper, 'dialog');

		$app->setHelperSet($helperSet);
		$app->setCatchExceptions(FALSE);

		$commands = array();

		foreach (array_keys($container->findByTag('consoleCommand')) as $name) {
			$commands[] = $container->getService($name);
		}

		$app->addCommands($commands);

		return $app;
	}
}