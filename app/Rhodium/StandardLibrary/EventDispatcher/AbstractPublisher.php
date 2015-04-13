<?php 

namespace Rhodium\StandardLibrary\EventDispatcher;

use Rhodium\StandardLibrary\EventDispatcher\PublisherInterface;
use Rhodium\StandardLibrary\EventDispatcher\SubscriberInterface;

/**
 * Rhodium Standard Library
 *
 * Event Dispatcher - Abstract Publisher
 *
 * @author 		Ewan Valentine <ewan.valentine89@gmail.com>
 * @copyright 	Rhodium 2014
 */
abstract AbstractPublisher implements PublisherInterface
{
	private $observers;

	public function notify()
	{
		foreach ($this->observers as $observers) {
			$observers->update($this);
		}
	}

	public function addSubscriber(SubscriberInterface $subscriber)
	{
		$this->observers[] = $subscriber;
	}
}