<?php

namespace Plummer\Calendar;

class Calendar
{
	protected $events;

	public function addEvent(Event $event)
	{
		$this->events[] = $event;
	}
}