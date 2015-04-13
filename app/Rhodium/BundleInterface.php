<?php 

namespace Rhodium;

interface BundleInterface
{
	public function getNamespace();
	public function getPath();
	public function getName();
	public function registerCommands();
	public function registerContainer();
	public function registerEntities();
}