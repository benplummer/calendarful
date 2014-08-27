<?php

namespace Plummer\Calendar;

class Calendar
{
	protected $events;

	protected $recurrenceTypes;

	protected function __construct(\Iterator $events, $recurrenceTypes)
	{
		$this->events = $events;
		$this->addRecurrenceTypes($recurrenceTypes);
	}

	public static function make(\Iterator $events, $recurrenceTypes = [])
	{
		return new static($events, $recurrenceTypes);
	}

	public function addEvents(array $events)
	{
		foreach($events as $event) {
			$this->addEvent($event);
		}
	}

	public function addEvent(Event $event)
	{
		$event->setCalendar($this);

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
