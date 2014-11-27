<?php

namespace Mocks;

use Plummer\Calendarful\RegistryInterface;

class MockEventRegistry implements RegistryInterface
{
	private $events = array();

	public function __construct()
	{
		$this->events[1] = new MockEvent(
			1,
			'2014-11-27 10:00:00',
			'2014-11-27 11:30:00'
		);
	}

	public function get(Array $filters = array())
	{
		$return = array();

		foreach($filters as $filter) {
			if(isset($this->events[$filter])) {
				$return[] = $this->events[$filter];
			}
		}

		return $return;
	}
}