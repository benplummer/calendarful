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

		$this->events = array_values($this->events);
	}
}
