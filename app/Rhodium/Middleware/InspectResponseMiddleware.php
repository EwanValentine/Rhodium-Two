<?php

namespace Rhodium\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Rhodium\Container\CoreContainer;
use Rhodium\Middleware\AbstractMiddleware;

class InspectResponseMiddleware extends AbstractMiddleware
{
	public function handle( Request $request, Response $response )
  	{
  		dr( $response );

 		$response = $this->next()->handle( $request, $response );

    	return $response;
  	}
}