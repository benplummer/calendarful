<?php

namespace Plummer\Calendar;

class Calendar extends CalendarAbstract
{
	public function addEvent(EventInterface $event)
	{
		$event->setCalendar($this);

		$this->events[] = $event;
	}

	public function addRecurrenceType(RecurrenceInterface $recurrenceType)
	{
		$this->recurrenceTypes[$recurrenceType->getLabel()] = $recurrenceType;
	}

	public function getEvents()
	{
		
	}
}