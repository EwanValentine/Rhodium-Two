<?php 

namespace Rhodium\Services;

use Rhodium\Core;

use Knp\Console\Application as ConsoleApplication;
use Knp\Console\ConsoleEvents;
use Knp\Console\ConsoleEvent;

use Rhodium\Services\ServiceProviderInterface;

class ConsoleService implements ServiceProviderInterface
{
	public function register( Core $core )
	{
		$core['console'] = function ( $core ) {
			
			$application = new ConsoleApplication ( 
				$core,
				$core['console.project.directory'],
				$core['console.name'],
				$core['console.version']
			);

			$core['dispatcher']->dispatch( ConsoleEvent::INIT, new ConsoleEvent( $application ) );

			return $application;
		};
	}
}