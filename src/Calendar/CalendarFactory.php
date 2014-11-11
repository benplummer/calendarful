<?php

namespace Plummer\Calendarful;

use Plummer\Calendarful\Calendar\CalendarFactoryInterface;
use Plummer\Calendarful\Recurrence\RecurrenceFactoryInterface;

class CalendarFactory implements CalendarFactoryInterface
{
	public function createCalendar($name, RecurrenceFactoryInterface $recurrenceFactory = null)
	{
		$calendar = new Calendar($name, $recurrenceFactory);

		return $calendar;
	}
}