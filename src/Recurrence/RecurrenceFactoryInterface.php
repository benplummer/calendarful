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
     * @param $type
     * @return mixed
     * @abstract
     */
    public function createRecurrenceType($type);

    /**
     * Get all of the recurrence types from the factory.
     *
     * @return mixed
     * @abstract
     */
    public function getRecurrenceTypes();
}
