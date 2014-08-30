<?php

namespace Plummer\Calendar;

abstract class CalendarAbstract implements CalendarInterface
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

	public function addRecurrenceTypes(array $recurrenceTypes)
	{
		foreach($recurrenceTypes as $recurrenceType) {
			$this->addRecurrenceType($recurrenceType);
		}
	}

	abstract public function addEvent(EventInterface $event);

	abstract public function addRecurrenceType(RecurrenceInterface $recurrenceType);

	abstract public function getEvents();
}
