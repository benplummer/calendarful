<?php

namespace Plummer\Calendarful\Calendar;

class CalendarFactory implements CalendarFactoryInterface
{
	private $calendarTypes = [];

	public function addCalendarType($type, $calendarType)
	{
		if(is_string($calendarType) and !class_exists($calendarType)) {
			throw new \InvalidArgumentException("Class {$calendarType} des not exist.");
		}
		else if(!in_array('Plummer\Calendarful\Calendar\CalendarInterface', class_implements($calendarType, false))) {
			throw new \InvalidArgumentException('File or File path required.');
		}

		$this->calendarTypes[$type] = is_string($calendarType) ?
			$calendarType :
			get_class($calendarType);
	}

	public function getCalendarTypes()
	{
		return $this->calendarTypes;
	}

	public function createCalendar($type)
	{
		if(!isset($this->calendarTypes[$type])) {
			throw new \OutOfBoundsException("A calendar type called {$type} does not exist within the factory.");
		}

		$calendar = new $this->calendarTypes[$type]();

		return $calendar;
	}
}