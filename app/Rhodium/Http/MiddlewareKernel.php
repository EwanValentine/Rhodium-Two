<?php

namespace Rhodium\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Rhodium\Middleware\AbstractMiddleware;

/**
 * MiddlewareKernel
 *
 * @author    Ewan Valentine <ewan.valentine89@gmail.com>
 * @copyright Rhodium 2015
 */
class MiddlewareKernel extends AbstractMiddleware
{
	protected $handling = false;

	public function __construct()
	{
		$this->push($this);
	}

	public function handle( Request $request, Response $response )
	{
		if ($this->handling) {
            return;
        }

        $this->handling = true;
        $this->next()->handle($request, $response);
        $response->send();
        $this->handling = false;
	}
}
