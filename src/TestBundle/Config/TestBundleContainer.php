<?php 

namespace TestBundle\Config;

use Rhodium\Core;
use Rhodium\ContainerInterface;

class TestBundleContainer implements ContainerInterface
{
	public function __construct(Core $core)
	{
		$this->core = $core;
		$this->core['test'] = 'This is a test... lol';
	}

	public function getServices()
	{
		return $this->core;
	}
}