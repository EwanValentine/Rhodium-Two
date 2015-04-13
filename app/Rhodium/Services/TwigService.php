<?php 

namespace Rhodium\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Rhodium\Core;

class TwigService implements ServiceProviderInterface
{
	protected $core;

	public function register( Core $core )
	{
		$core['twig.path'] = array();
		$core['twig.options'] = array();

		$core['twig'] = function ( $c ) {

			$core['twig.options'] = array_replace (
                array (
                    'charset'          => $c['charset'],
                    'debug'            => $c['app.mode'],
                    'strict_variables' => $c['app.mode'],
                ), $c['twig.options']
            );

            $c['twig.loader'] = new \Twig_Loader_Filesystem( $c['twig.path'] );
			return new \Twig_Environment( $c['twig.loader'] );
		}; 
	}
}