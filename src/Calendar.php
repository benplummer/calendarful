<?php

namespace Plummer\Calendar;

class Calendar extends CalendarAbstract
{
	public function __construct($name)
	{
		$this->name = $name;
	}

	public function addEvent(EventInterface $event)
	{
		$event->setCalendar($this);

		$this->events[$event->getId().'.'.$event->getStartDate()] = $event;
	}

	public function addRecurrenceType(RecurrenceInterface $recurrenceType)
	{
		$this->recurrenceTypes[$recurrenceType->getLabel()] = $recurrenceType;
	}

	public function getEvents($fromDate, $toDate, $limit = null)
	{
        $this->events = array_filter($this->events, function($event) use($fromDate, $toDate) {
            if($event->getStartDate() <= $toDate && $event->getEndDate() >= $fromDate) {
                return true;
            } 

            return false;
        });

        return $this->events;
    }
}
