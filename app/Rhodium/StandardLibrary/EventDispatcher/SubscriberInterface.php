<?php 

namespace Rhodium\StandardLibrary\EventDispatcher;

use Rhodium\StandardLibrary\EventDispatcher\PublisherInterface;

/**
 * Rhodium Standard Library
 *
 * Event Dispatcher - Subscriber Interface
 *
 * @author 		Ewan Valentine <ewan.valentine89@gmail.com>
 * @copyright 	Rhodium 2014
 */
interface SubscriberInterface
{
	public function update(PublisherInterface $publisher);

	public static function getSubscribedEvents();
}