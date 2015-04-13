<?php 

namespace Rhodium\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface MiddlewareInterface
{
	public function push( MiddlewareInterface $next );
	public function next();
	public function handle( Request $request, Response $response );
}