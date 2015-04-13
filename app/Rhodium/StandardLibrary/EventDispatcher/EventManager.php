<?php 

namespace Rhodium\StandardLibrary\EventDispatcher;

use Rhodium\StandardLibrary\EventDispatcher\PublisherInterface;
use Rhodium\StandardLibrary\EventDispatcher\SubscriberInterface;

/**
 * Rhodium Standard Library
 *
 * Event Dispatcher - EventManager
 *
 * @author 		Ewan Valentine <ewan.valentine89@gmail.com>
 * @copyright 	Rhodium 2014
 */
class EventManager
{
	private $listeners = [];

	public function listen($event, $callback)
	{
		$this->listeners[$event][] = $callback;
	}

	public function dispatch($event, PublisherInterface $event)
	{
		foreach ($this->listeners[$event] as $listener) {
			call_user_func_array($listener, array($event));
		}
	}

	public function addSubscriber(SubscriberInterface $subscriber)
	{
		$listeners = $subscriber->getSubscribedEvents();

		foreach ($listeners as $event => $listener) {
			$this->listen($event, array($subscriber, $listener));
		}
	}
}