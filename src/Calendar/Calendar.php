<?php

namespace Plummer\Calendarful;

use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;

class Calendar implements CalendarInterface, \IteratorAggregate
{
	protected $name;

	protected $events;

	protected $recurrenceFactory;

	public function __construct($name, RecurrenceFactoryInterface $recurrenceFactory)
	{
		$this->name = $name;
		$this->recurrenceFactory = $recurrenceFactory;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getIterator()
	{
		if($this->events === null) {
			throw new \Exception('This calendar needs to be populated with events.');
		}

		return $this->events;
	}

	public function limit($limit, $offset = 0)
	{
		$this->events = new \LimitIterator($this->getIterator(), $offset, $limit);
	}

	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit)
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
