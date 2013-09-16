<?php

namespace Rixxi\WatchProcess\DI;

use Kdyby;
use Nette;
use Rixxi;


class ProcessExtension extends Nette\DI\CompilerExtension implements Kdyby\Doctrine\DI\IEntityProvider, Kdyby\Doctrine\DI\ITargetEntityProvider
{
	use Rixxi\Modular\DI\CompilerExtensionSugar;


	public function getEntityMappings()
	{
		return array(
			'Rixxi\\WatchProcess\\Entities' => __DIR__ . '/../Entities',
		);
	}


	public function getTargetEntityMappings()
	{
		return array( 'Rixxi\WatchProcess\Entities\IProcess' => 'Rixxi\WatchProcess\Entities\Process' );
	}


	public function loadConfiguration()
	{
		$doctrine = $this->getCompilerExtension('Kdyby\\Doctrine\\DI\\OrmExtension');

		$this->loadConfig('console');

		$container = $this->getContainerBuilder();

		$container->addDefinition($this->prefix('dao'))
			->setFactory($doctrine->prefix('@dao'), array('Rixxi\\WatchProcess\\Entities\\IProcess'));

		$container->addDefinition($this->prefix('model'))
			->setClass('Rixxi\\WatchProcess\\Model', array($this->prefix('@dao')));
	}

}
