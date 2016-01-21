<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;
use Plummer\Calendarful\Event\EventRegistryInterface;

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
class Calendar implements CalendarInterface
{
	/**
	 * @var EventInterface[]
	 */
	protected $events;

	/**
	 * @var EventInterface[]
	 */
	protected $recurrentEvents;

	/**
	 * @var EventInterface[]
	 */
	protected $allEvents;

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
	 * @param  RecurrenceFactoryInterface $recurrenceFactory
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
		if ($this->allEvents === null) {
			throw new \Exception('This calendar needs to be populated with events.');
		}

		return new \ArrayIterator($this->allEvents);
	}

	/**
	 * Populates the calendar with events using an event registry and filters the results based
	 * on other parameters.
	 *
	 * Occurrences of recurring events are also generated at this stage.
	 *
	 * @param  EventRegistryInterface	$eventsRegistry
	 * @param  \DateTime				$fromDate
	 * @param  \DateTime				$toDate
	 * @param  int						$limit
	 * @param  mixed[]					$extraFilters
	 * @return static
	 * @throws \RangeException
	 */
	public function populate(EventRegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit = null, array $extraFilters = array())
	{
		if ($fromDate > $toDate) {
			throw new \RangeException("'From' date should be before the 'to' date.");
		}

		$filters = array_merge(
			array(
				'fromDate' => $fromDate,
				'toDate' => $toDate,
				'limit' => $limit,
			),
			$extraFilters
		);

		$this->allEvents = $this->events = $eventsRegistry->getEvents($filters);

		if ($this->recurrentEvents = $eventsRegistry->getRecurrentEvents($filters)) {
			$this->processRecurringEvents($fromDate, $toDate, $limit);
			$this->removeOveriddenEvents();
		}

		$this->removeOutOfRangeEvents($fromDate, $toDate);

		$this->allEvents = $limit ? array_slice(array_values($this->allEvents), 0, $limit) : array_values($this->allEvents);

		return $this;
	}

	/**
	 * Sorts the events into ascending order based on their start dates.
	 *
	 * @return static
	 */
	public function sort()
	{
		usort($this->allEvents, function ($event1, $event2) {
			if ($event1->getStartDate() == $event2->getStartDate()) {
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
		return count($this->allEvents);
	}

	/**
	 * Generates the occurrences for recurring events if a recurrence factory is present.
	 *
	 * @param \DateTime	$fromDate
	 * @param \DateTime	$toDate
	 * @param int		$limit
	 */
	protected function processRecurringEvents(\DateTime $fromDate, \DateTime $toDate, $limit = null)
	{
		if ($this->recurrenceFactory) {
			foreach ($this->recurrenceFactory->getRecurrenceTypes() as $recurrence) {
				$recurrenceType = new $recurrence();

				$occurrences = $recurrenceType->generateOccurrences($this->recurrentEvents, $fromDate, $toDate, $limit);

				$this->recurrentEvents = array_merge($this->recurrentEvents, $occurrences);
			}
		}

		// Remove recurring events that do not occur within the date range
		$this->recurrentEvents = array_filter($this->recurrentEvents, function ($event) use ($fromDate, $toDate) {

			if (! $event->getRecurrenceType()) {
				return true;
			} elseif ($event->getStartDate() <= $toDate  && $event->getEndDate() >= $fromDate) {
				return true;
			}

			return false;
		});

		$this->allEvents = array_merge($this->allEvents, $this->recurrentEvents);
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
		array_walk($this->allEvents, function ($event) use (&$events) {
			if ($event->getOccurrenceDate()) {
			    $events[$event->getOccurrenceDate()->format('Y-m-d H:i:s').'.'.($event->getParentId() ?: $event->getId())] = $event;
			} elseif (!isset($events[$event->getStartDate()->format('Y-m-d H:i:s').'.'.($event->getParentId() ?: $event->getId())])) {
			    $events[$event->getStartDate()->format('Y-m-d H:i:s').'.'.($event->getParentId() ?: $event->getId())] = $event;
			}
		});

		$this->allEvents = $events;
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
		$this->allEvents = array_filter($this->allEvents, function ($event) use ($fromDate, $toDate) {

			if ($event->getStartDate() <= $toDate && $event->getEndDate() >= $fromDate) {
				return true;
			}

			return false;
		});
	}
}
