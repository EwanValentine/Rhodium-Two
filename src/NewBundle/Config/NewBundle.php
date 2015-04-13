<?php 

namespace NewBundle\Config;

use Rhodium\BundleInterface;

class NewBundle implements BundleInterface
{
	public function getNamespace()
	{
		return __NAMESPACE__;
	}

	public function getPath()
	{
		return 'NewBundle';
	}

	public function getName()
	{
		return 'NewBundle';
	}

	public function registerCommands()
	{

	}

	public function registerContainer()
	{	
		return $this->getNamespace() . '\\' . $this->getName() . 'Container';
	}

	public function registerEntities()
	{
		
	}

	public function register()
	{
		return $this->registerContainer();
	}
}