<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;
use Plummer\Calendarful\RegistryInterface;

/**
 * Class Calendar
 *
 * This is the default calendar implementation. Once populated with events using an
 * event registry, the calendar instance can be looped over to access the events.
 * The events will also consist of recurring event occurrences if a recurrence factory
 * is involved in the process.
 *
 * @package Plummer\Calendarful
 */
class Calendar implements CalendarInterface, \IteratorAggregate
{
	/**
	 * @var array
	 */
	protected $events;

	/**
	 * @var RecurrenceFactoryInterface
	 */
	protected $recurrenceFactory;

	/**
	 * Create a calendar instance with an optional recurrence factory if recurring events
	 * are desired.
	 *
	 * @param RecurrenceFactoryInterface $recurrenceFactory
	 */
	public function __construct(RecurrenceFactoryInterface $recurrenceFactory = null)
	{
		$this->recurrenceFactory = $recurrenceFactory;
	}

	/**
	 * Alternative method of creating a calendar instance that allows chaining.
	 *
	 * @param RecurrenceFactoryInterface $recurrenceFactory
	 * @return static
	 */
	public static function create(RecurrenceFactoryInterface $recurrenceFactory = null)
	{
		return new static($recurrenceFactory);
	}

	/**
	 * Gets the array iterator of events if there are events otherwise an exception is thrown.
	 *
	 * @return \ArrayIterator
	 * @throws \Exception
	 */
	public function getIterator()
	{
		if($this->events === null) {
			throw new \Exception('This calendar needs to be populated with events.');
		}

		return new \ArrayIterator($this->events);
	}

	/**
	 * Populates the calendar with events using an event registry and filters the results based
	 * on other parameters.
	 *
	 * Occurrences of recurring events are also generated at this stage.
	 *
	 * @param RegistryInterface $eventsRegistry
	 * @param \DateTime $fromDate
	 * @param \DateTime $toDate
	 * @param null $limit
	 * @param array $extraFilters
	 * @return $this
	 */
	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit = null, Array $extraFilters = array())
	{
		if($fromDate > $toDate) {
			throw new \RangeException("'From' date should be before the 'to' date.");
		}

		$filters = array_merge(
			array(
				'fromDate' => $fromDate,
				'toDate' => $toDate,
				'limit' => $limit
			),
			$extraFilters
		);

		$this->events = $eventsRegistry->get($filters);

		$this->processRecurringEvents($fromDate, $toDate, $limit);

		$this->removeOveriddenEvents();

		$this->removeOutOfRangeEvents($fromDate, $toDate);

		$this->events = $limit ? array_slice(array_values($this->events), 0, $limit) : array_values($this->events);

		return $this;
	}

	/**
	 * Sorts the events into ascending order based on their start dates.
	 *
	 * @return $this
	 */
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

	/**
	 * Gets the number of events the calendar contains.
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->events);
	}

	/**
	 * Generates the occurrences for recurring events if a recurrence factory is present.
	 *
	 * @param \DateTime $fromDate
	 * @param \DateTime $toDate
	 * @param null $limit
	 */
	protected function processRecurringEvents(\DateTime $fromDate, \DateTime $toDate, $limit = null)
	{
		if($this->recurrenceFactory) {
			foreach($this->recurrenceFactory->getRecurrenceTypes() as $label => $recurrence) {
				$recurrenceType = new $recurrence();

				$occurrences = $recurrenceType->generateOccurrences($this->events, $fromDate, $toDate, $limit);

				$this->events = array_merge($this->events, $occurrences);
			}
		}

		// Remove recurring events that do not occur within the date range
		$this->events = array_filter($this->events, function($event) use ($fromDate, $toDate) {

			if(! $event->getRecurrenceType()) {
				return true;
			}
			else if($event->getStartDate() <= $toDate->format('Y-m-d H:i:s') && $event->getEndDate() >= $fromDate->format('Y-m-d H:i:s')) {
				return true;
			}

			return false;
		});
	}

	/**
	 * Removes the occurrences of recurring events that have been overridden.
	 */
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

	/**
	 * Remove any events, particularly occurrences of recurring events, that are out of the date
	 * range provided.
	 *
	 * @param \DateTime $fromDate
	 * @param \DateTime $toDate
	 */
	protected function removeOutOfRangeEvents(\DateTime $fromDate, \DateTime $toDate)
	{
		// Remove events that do not occur within the date range
		$this->events = array_filter($this->events, function($event) use ($fromDate, $toDate) {
			if($event->getStartDate() <= $toDate->format('Y-m-d H:i:s') && $event->getEndDate() >= $fromDate->format('Y-m-d H:i:s')) {
				return true;
			}

			return false;
		});
	}
}