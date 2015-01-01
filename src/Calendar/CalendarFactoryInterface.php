<?php

namespace Plummer\Calendarful\Calendar;

/**
 * Interface CalendarFactoryInterface
 *
 * An interface for different calendar factories specifying they need to
 * provide implementations for getting the type/s.
 *
 * @package Plummer\Calendarful
 * @abstract
 */
interface CalendarFactoryInterface
{
    /**
     * Instantiates a calendar and returns it.
     *
     * @param $type
     * @return mixed
     * @abstract
     */
    public function createCalendar($type);

    /**
     * Get all of the calendar types from the factory.
     *
     * @return mixed
     * @abstract
     */
    public function getCalendarTypes();
}
