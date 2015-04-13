<?php

namespace Rhodium\EventListeners;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class ExceptionListener
{
	public function exception( FlattenException $exception )
    {
        $msg = 'Awww sh*t! ('.$exception->getMessage().')';

        $trace = $exception->getTrace();

        foreach ( $trace as $row ) {
        	$traceList = "<p><b>Namespace: " . $row['namespace'] . "</b></p>";
        	$traceList .= "<p><b>File: " . $row['file'] . "</b></p>";
        	$traceList .= "<p><b>Line: " . $row['line'] . "</b></p>";
        	$traceList .= "<br />";
        }

        $data = file_get_contents( __DIR__.'/resources/plain.stub' );

        $data = str_replace('{{message}}', $msg, $data );
        $data = str_replace('{{code}}', $exception->getStatusCode(), $data );
        $data = str_replace('{{trace}}', $traceList, $data );

        return new Response( $exception, $exception->getStatusCode() );
    }
}
