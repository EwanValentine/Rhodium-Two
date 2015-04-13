<?php 

namespace TestBundle\Config;

use Rhodium\StandardLibrary\EventDispatcher\EventManager;

class TestPublisher 
{
	private $event;

	public function __construct(EventManager $event)
	{
		$this->event = $event;
	}

	public function getTitle()
	{
		return 'Test title';
	}

	public function getDispatcher()
	{
		return $this->event;
	}

	public function updateTest()
	{
		$event = new TestEvent($this->getTitle());
		
		$this->getDispatcher()->dispatch('test.signal', $event);
	}
}