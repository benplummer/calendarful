<?php

namespace Plummer\Calendar;

abstract class CalendarAbstract implements CalendarInterface, \IteratorAggregate
{
	protected $name;

	protected $events;

	protected $lastEventsIteratorResult;

	protected $recurrenceTypes;

	public function addEvents($events)
	{
		foreach($events as $event) {
			$this->addEvent($event);
		}
	}

	public function addRecurrenceTypes($recurrenceTypes)
	{
		foreach($recurrenceTypes as $recurrenceType) {
			$this->addRecurrenceType($recurrenceType);
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
