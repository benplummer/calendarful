<?php

namespace Plummer\Calendarful\Calendar;

use Plummer\Calendarful\RegistryInterface;

/**
 * Interface CalendarInterface
 *
 * A common interface for calendars to use.
 *
 * @package Plummer\Calendarful
 * @abstract
 */
interface CalendarInterface
{
	/**
	 * Populate the calendar with events persisted from the event registry.
	 *
	 * @param RegistryInterface 	$eventsRegistry
	 * @param \DateTime 			$fromDate
	 * @param \DateTime 			$toDate
	 * @param integer|null 			$limit
	 * @param array 				$extraFilters
	 * @return mixed
	 * @abstract
	 */
	public function populate(RegistryInterface $eventsRegistry, \DateTime $fromDate, \DateTime $toDate, $limit = null, Array $extraFilters = array());

	/**
	 * Sort the events that the calendar contains.
	 *
	 * @return mixed
	 */
	public function sort();
}
