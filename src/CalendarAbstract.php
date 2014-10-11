<?php

namespace Plummer\Calendar;

abstract class CalendarAbstract implements CalendarInterface, \IteratorAggregate
{
	protected $name;

	private $events;

	protected $lastEventsIteratorResult;

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
		if($this->lastEventsIteratorResult) {
			return $this->lastEventsIteratorResult;
		}

		return new \ArrayIterator($this->events);
	}

	public function limit($limit, $offset = 0)
	{
		$this->lastEventsIteratorResult = new \LimitIterator($this->getIterator(), $offset, $limit);

		return $this;
	}

	abstract public function addEvent(EventInterface $event);

	abstract public function addRecurrenceType(RecurrenceInterface $recurrenceType);

	abstract public function getEvents();
}
