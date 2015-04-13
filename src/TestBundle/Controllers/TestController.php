<?php 

namespace TestBundle\Controllers;

use Rhodium\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use TestBundle\Config\TestEvent;
use TestBundle\Config\TestPublisher;

use Rhodium\StandardLibrary\EventDispatcher\EventManager;

/**
 * TestController
 *
 * Just to test controller functionality.
 */
class TestController extends BaseController
{	
	public function indexAction( Request $request )
	{
		$eventManager = new EventManager();
		$publisher = new TestPublisher($eventManager);

		$eventManager->listen('test.signal', function(TestPublisher $publisher) {
			dr($publisher->getTitle());
		});

		return $this->view('TestBundle:home_view');
	}
}