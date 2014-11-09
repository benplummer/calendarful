<?php

namespace Plummer\Calendarful;

use Plummer\Calendarful\Calendar\CalendarFactoryInterface;
use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;

class CalendarFactory implements CalendarFactoryInterface
{
	private $calendars;

	public function addCalendars(Array $calendars)
	{
		foreach($calendars as $calendar) {
			$this->addCalendar($calendar);
		}
	}

	public function addCalendar(CalendarInterface $calendar) {
		$this->calendars[$calendar->getName()] = $calendar;
	}

	public function createCalendar($name, RecurrenceFactoryInterface $recurrenceFactory = null)
	{
		$calendar = $this->calendars[$name];

		$calendar->addRecurrenceTypes($recurrenceFactory);

		return $calendar;
	}
}