<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;
use Plummer\Calendarful\RegistryInterface;

class Calendar implements CalendarInterface, \IteratorAggregate
{
	protected $events;

	protected $recurrenceFactory;

	public function addRecurrenceFactory(RecurrenceFactoryInterface $recurrenceFactory)
	{
		$this->recurrenceFactory = $recurrenceFactory;
	}

	public function getIterator()
	{
		if($this->events === null) {
			throw new \Exception('This calendar needs to be populated with events.');
		}

		return new \ArrayIterator($this->events);
	}

	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit = null)
	{
		$filters = [
			'fromDate' => $fromDate,
			'toDate' => $toDate
		];

		$this->events = $eventsRegistry->get($filters);

		if($this->recurrenceFactory) {
			foreach($this->recurrenceFactory->getRecurrenceTypes() as $label => $recurrence) {
				$recurrenceType = new $recurrence();

				$this->events += $recurrenceType->generateOccurrences($this->events, $fromDate, $toDate, $limit);
			}
		}

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

		$this->events = array_values($this->events);

		return $this;
	}

	public function sort()
	{
		usort($this->events, function($event1, $event2) {
			if($event1->getStartDateFull() == $event2->getStartDateFull()) {
				return 0;
			}
			return $event1->getStartDateFull() < $event2->getStartDateFull() ? -1 : 1;
		});

		return $this;
	}
}