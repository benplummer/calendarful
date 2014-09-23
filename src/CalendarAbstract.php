<?php

namespace Plummer\Calendar;

abstract class CalendarAbstract implements CalendarInterface
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
		if($events instanceof \Iterator) {
			$this->events->append($events);
		}
		elseif(is_array($events)) {
			foreach($events as $key => $event) {
				$this->events[$key] = $event;
			}
		}
	}

	public function addRecurrenceTypes($recurrenceTypes)
	{
		if($recurrenceTypes instanceof \Iterator) {
			$this->recurrenceTypes->append($recurrenceTypes);
		}
		elseif(is_array($recurrenceTypes)) {
			foreach($recurrenceTypes as $key => $recurrenceType) {
				$this->recurrenceTypes[$key] = $recurrenceType;
			}
		}
	}

	abstract public function addEvent(EventInterface $event);

	abstract public function addRecurrenceType(RecurrenceInterface $recurrenceType);

	abstract public function getEvents();
}
