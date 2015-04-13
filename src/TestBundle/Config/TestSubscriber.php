<?php 

namespace TestBundle\Config;

use Rhodium\StandardLibrary\EventDispatcher\SubscriberInterface;

class TestSubscriber implements SubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'test.signal' => 'Listener',
		)
	}

	public function Listener(TestEvent $testEvent)
	{
		dr($testEvent);
	}
}