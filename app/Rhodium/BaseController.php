<?php

namespace Rhodium;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Rhodium\Core;

class BaseController
{
    protected static $core;

    public function __construct()
    {

    }

    public static function core ( Core $core )
    {
        self::$core = $core;
    }

    public function view( $view, $params = array() )
    {
        $view = explode( ':', $view );

        $bundle = $view[0];
        $view = $view[1];

        $path = $bundle . '/Views/' . $view . '.html.twig';

        if ( !empty( $params ) ) {
            $view = self::$core['twig']->render( $path, $params );
        } else{
            $view = self::$core['twig']->render( $path );
        }

        return new Response( $view );
    }

    public function api($json)
    {
        return new Response(json_encode($json), 200, array('Content-Type' => 'application/json'));
    }
}
