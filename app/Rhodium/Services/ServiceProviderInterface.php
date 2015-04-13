<?php 

namespace Rhodium\Services;

use Rhodium\Core;

interface ServiceProviderInterface
{
	public function register( Core $core );
}