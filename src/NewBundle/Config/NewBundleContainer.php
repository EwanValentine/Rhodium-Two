<?php 

namespace NewBundle\Config;

use Rhodium\Core;
use Rhodium\ContainerInterface;

class NewBundleContainer implements ContainerInterface
{
	public function __construct( Core $core )
	{
		$this->core = $core;
	}

	public function setServices(array $services)
	{
		
	}

	public function getServices()
	{
		return $this;
	}
}