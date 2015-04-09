<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\Event\EventRegistryInterface;

/**
 * Interface CalendarInterface
 *
 * A common interface for calendars to use.
 *
 * @package Plummer\Calendarful
 * @abstract
 */
interface CalendarInterface extends \Countable, \IteratorAggregate
{
	/**
	 * Populate the calendar with events persisted from the event registry.
	 *
	 * @param  EventRegistryInterface	$eventsRegistry
	 * @param  \DateTime				$fromDate
	 * @param  \DateTime				$toDate
	 * @param  int						$limit
	 * @param  array					$extraFilters
	 * @return static
	 * @abstract
	 */
	public function populate(EventRegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit = null, array $extraFilters = array());

	/**
	 * Sort the events that the calendar contains.
	 *
	 * @return static
	 */
	public function sort();
}
