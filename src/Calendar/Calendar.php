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

		return $this->events;
	}

	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit = null)
	{
		$filters = [
			'fromDate' => $fromDate->format('Y-m-d'),
			'toDate' => $toDate->format('Y-m-d')
		];

		// Filter events within the date range
		$this->events = array_filter($eventsRegistry->get($filters), function($event) use ($fromDate, $toDate) {
			if($event->getStartDateFull() <= $toDate && $event->getEndDate() >= $fromDate->format('Y-m-d')) {
				return true;
			}
			else if($event->getRecurrenceType()) {
				if($event->getRecurrenceUntil() === null || $event->getRecurrenceUntil() >= $fromDate->format('Y-m-d')) {
					return true;
				}
			}

			return false;
		});

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
			else if($event->getStartDateFull() <= $toDate->format('Y-m-d') && $event->getEndDate() >= $fromDate->format('Y-m-d')) {
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
