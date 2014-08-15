<?php

namespace Plummer\Calendar;

class Calendar
{
	protected $name;

	protected $events;

	protected $recurrenceTypes;

	protected function __construct($name, array $events, array $recurrenceTypes)
	{
		$this->name = $name;
		$this->addEvents($events);
		$this->addRecurrenceTypes($recurrenceTypes);
	}

	public static function make($name, array $events = [], array $recurrenceTypes = [])
	{
		return new static($name, $events, $recurrenceTypes);
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
