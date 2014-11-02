<?php

namespace Plummer\Calendarful;

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
		// Filter events within the date range
        $this->events = array_filter($this->events, function($event) use ($fromDate, $toDate) {
            if($event->getStartDateFull() <= $toDate && $event->getEndDate() >= $fromDate) {
                return true;
            }
            else if($event->getRecurrenceType()) {
                if($event->getRecurrenceUntil() === null || $event->getRecurrenceUntil() >= $fromDate) {
                    return true;
                }
            }

            return false;
        });

		// Generate occurrences for recurring events
        $this->generateOccurrences($fromDate, $toDate, $limit);

		// Remove recurring events that do not occur within the date range
		$this->events = array_filter($this->events, function($event) use ($fromDate, $toDate) {
			if(! $event->getRecurrenceType()) {
				return true;
			}
			else if($event->getStartDateFull() <= $toDate && $event->getEndDate() >= $fromDate) {
				return true;
			}
			else {
				return false;
			}
		});

		//Restructure events under their relevant day
		foreach($this->events as $id_and_date => $event) {

			list($id, $date) = explode('.', $id_and_date);

			$events[$date][] = $event;
		}

		foreach($events as $date => &$event) {
			usort($event, function($e1, $e2) use ($date) {

				if($e1->getStartDateFull() == $e2->getStartDateFull()) {
					return 0;
				}

				return $e1->getStartDateFull() < $e2->getStartDateFull() ? -1 : 1;
			});
		}

		ksort($events);

		$this->events = $events;

        return $this->events;
    }
}
