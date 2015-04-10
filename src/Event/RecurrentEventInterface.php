<?php

namespace Plummer\Calendarful\Event;

/**
 * Interface RecurrentEventInterface
 *
 * A common interface for recurrent events.
 *
 * Any event objects to be used within a calendar implementation must
 * implement this interface and consist of properties related to the
 * methods below.
 *
 * @package Plummer\Calendarful
 * @abstract
 */
interface RecurrentEventInterface extends EventInterface
{
	/**
	 * Get the recurrence type of the event.
	 *
	 * This is usually a string that matches up to the label of a recurrence type.
	 *
	 * @return mixed
	 * @abstract
	 */
	public function getRecurrenceType();

	/**
	 * Set the recurrence type of the event.
	 *
	 * @param  string	$type
	 * @abstract
	 */
	public function setRecurrenceType($type = null);

	/**
	 * Get the until date of the event.
	 *
	 * @return \DateTime
	 * @abstract
	 */
	public function getRecurrenceUntil();
}
