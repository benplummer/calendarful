<?php

namespace Plummer\Calendarful\Calendar;

class CalendarFactory implements CalendarFactoryInterface
{
	private $calendarTypes = [];

	public function addCalendarType($type, $calendarType)
	{
		if(!in_array('Plummer\Calendarful\Calendar\CalendarInterface', class_implements($calendarType))) {
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
			throw new \Exception('The type passed does not exist.');
		}

		$calendar = new $this->calendarTypes[$type]();

		return $calendar;
	}
}