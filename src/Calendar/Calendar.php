<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;
use Plummer\Calendarful\RegistryInterface;

class Calendar implements CalendarInterface, \IteratorAggregate
{
	protected $events;

	protected $recurrenceFactory;

	public function __construct(RecurrenceFactoryInterface $recurrenceFactory = null)
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

		$this->processRecurringEvents($fromDate, $toDate, $limit);

		$this->removeOveriddenEvents();

		$this->events = $limit ? array_slice(array_values($this->events), 0, $limit) : array_values($this->events);

		return $this;
	}

	public function sort()
	{
		usort($this->events, function($event1, $event2) {
			if($event1->getStartDate() == $event2->getStartDate()) {
				return $event1->getId() < $event2->getId() ? -1 : 1;
			}
			return $event1->getStartDate() < $event2->getStartDate() ? -1 : 1;
		});

		return $this;
	}

	protected function processRecurringEvents(\DateTime $fromDate, \DateTime $toDate, $limit = null)
	{
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
			else if($event->getStartDate() <= $toDate && $event->getEndDate() >= $fromDate) {
				return true;
			}

			return false;
		});
	}

	protected function removeOveriddenEvents()
	{
		// Events need to be sorted by date and id (both ascending) in order for overridden occurrences not to show
		$this->sort();
		$events = array();

		// New events array is created with the occurrence overrides replacing the relevant occurrences
		array_walk($this->events, function($event) use (&$events) {
			$events[($event->getOccurrenceDate() ?: $event->getStartDate()).'.'.($event->getParentId() ?: $event->getId())] = $event;
		});

		$this->events = $events;
	}
}