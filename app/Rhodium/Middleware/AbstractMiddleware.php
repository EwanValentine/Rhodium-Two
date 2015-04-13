<?php 

namespace Rhodium\Middleware;

abstract class AbstractMiddleware implements MiddlewareInterface
{
	protected $next;

	public function next()
	{
		return $this->next;
	}

	public function push( MiddlewareInterface $next )
	{
		if ( !is_null ( $this->next ) ) {
			$next->push( $this->next );
		}

		$this->next = $next;
	}
}