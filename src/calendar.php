<?php

namespace Plummer\Calendar;

class Calendar
{
	protected $events;

	public function addEvents(array $events)
	{
		foreach($events as $event) {
			$this->addEvent($event);
		}
	}

	public function addEvent(Event $event)
	{
		$this->events[] = $event;
	}
}