<?php 

namespace Rhodium;

interface ContainerInterface
{
	public function __construct(Core $core);
	public function getServices();
}