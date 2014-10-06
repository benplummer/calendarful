<?php

namespace Plummer\Calendar;

abstract class CalendarAbstract implements CalendarInterface, \IteratorAggregate
{
	protected $name;

	protected $events;

	protected $recurrenceTypes;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function addEvents($events)
	{
		foreach($events as $key => $event) {
			$this->events[$key] = $event;
		}
	}

	public function addRecurrenceTypes($recurrenceTypes)
	{
		foreach($recurrenceTypes as $key => $recurrenceType) {
			$this->recurrenceTypes[$key] = $recurrenceType;
		}
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->events);
	}

	abstract public function addEvent(EventInterface $event);

	abstract public function addRecurrenceType(RecurrenceInterface $recurrenceType);

	abstract public function getEvents();
}
