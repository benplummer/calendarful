<?php

namespace Plummer\Calendarful\Calendar;

/**
 * Class CalendarFactory
 *
 * The Calendar Factory stores different implementations of the Calendar Interface.
 * When requested it can create specific implementations if they have been stored or return
 * all of them.
 *
 * @package Plummer\Calendarful
 */
class CalendarFactory implements CalendarFactoryInterface
{
	/**
	 * @var string[]
	 */
	private $calendarTypes = array();

	/**
	 * Stores calendar type class paths when provided with a key and an instance or
	 * class path of an implementation.
	 *
	 * @param string					$type
	 * @param string|CalendarInterface	$calendarType
	 */
	public function addCalendarType($type, $calendarType)
	{
		if (is_string($calendarType) and !class_exists($calendarType)) {
			throw new \InvalidArgumentException("Class {$calendarType} does not exist.");
		} elseif (!in_array('Plummer\Calendarful\Calendar\CalendarInterface', class_implements($calendarType, false))) {
			throw new \InvalidArgumentException('File or File path required.');
		}

		$this->calendarTypes[$type] = is_string($calendarType) ?
			$calendarType :
			get_class($calendarType);
	}

	/**
	 * Get all of the stored calendar types.
	 *
	 * @return string[]|null
	 */
	public function getCalendarTypes()
	{
		return $this->calendarTypes;
	}

	/**
	 * Creates and returns an instance of a stored calendar type.
	 *
	 * @param  string				$type
	 * @return CalendarInterface
	 * @throws OutOfBoundsException
	 */
	public function createCalendar($type)
	{
		if (!isset($this->calendarTypes[$type])) {
			throw new \OutOfBoundsException("A calendar type called {$type} does not exist within the factory.");
		}

		$calendar = new $this->calendarTypes[$type]();

		return $calendar;
	}
}
