<?php

namespace Plummer\Calendar;

class Calendar
{
	protected $events;

	protected $recurrenceTypes;

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

	public function addRecurrenceTypes(array $recurrenceTypes)
	{
		foreach($recurrenceTypes as $recurrenceType) {
			$this->addRecurrenceType($recurrenceType);
		}
	}

	public function addRecurrenceType(RecurrenceInterface $recurrenceType)
	{
		$this->recurrenceTypes[] = $recurrenceType;
	}
}