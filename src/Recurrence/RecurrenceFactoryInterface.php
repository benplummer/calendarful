<?php

namespace Plummer\Calendarful\Recurrence;

/**
 * Interface RecurrenceFactoryInterface
 *
 * An interface for different recurrence factories specifying they need to
 * provide implementations for getting the type/s.
 *
 * @package Plummer\Calendarful
 * @abstract
 */
interface RecurrenceFactoryInterface
{
	/**
	 * Instantiates a recurrence type and returns it.
	 *
	 * @param string $type
	 * @return RecurrenceInterface
	 * @abstract
	 */
	public function createRecurrenceType($type);

	/**
	 * Get all of the recurrence type class names from the factory.
	 *
	 * @return string[]
	 * @abstract
	 */
	public function getRecurrenceTypes();
}
