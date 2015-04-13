<?php 

namespace Rhodium\Exceptions;

use Exception;

interface ExceptionInterface 
{
	public function display( Exception $exception );
}