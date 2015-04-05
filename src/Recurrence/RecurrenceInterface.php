<?php

namespace Plummer\Calendarful\Recurrence;

/**
 * Interface RecurrenceInterface
 *
 * A common interface for recurrence types.
 *
 * @package Plummer\Calendarful\Recurrence
 * @abstract
 */
interface RecurrenceInterface
{
	/**
	 * Get the label of the recurrence type.
	 *
	 * This usually matches up with the recurrence type property of events.
	 *
	 * @return string
	 * @abstract
	 */
	public function getLabel();

	/**
	 * Get the maximum limit of event occurrences that the recurrence type should return.
	 *
	 * @return string
	 * @abstract
	 */
	public function getLimit();

	/**
	 * Generate the occurrences for recurring events of the relevant type.
	 *
	 * @param  EventInterface[]	$events
	 * @param  \DateTime		$fromDate
	 * @param  \DateTime		$toDate
	 * @param  int|null			$limit
	 * @return EventInterface[]
	 * @abstract
	 */
	public function generateOccurrences(Array $events, \DateTime $fromDate, \DateTime $toDate, $limit = null);
}
