<?php 

namespace TestBundle\Config;

use Rhodium\BundleInterface;

class TestBundle implements BundleInterface
{
	public function getNamespace()
	{
		return __NAMESPACE__;
	}

	public function getPath()
	{
		return 'TestBundle';
	}

	public function getName()
	{
		return 'TestBundle';
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