<?php

namespace TestBundle\Config;

use Rhodium\StandardLibrary\EventDispatcher\Event;

class TestEvent extends Event
{
	private $title;

	public function __construct($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}
}