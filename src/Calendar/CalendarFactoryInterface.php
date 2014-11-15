<?php

namespace Plummer\Calendarful\Calendar;

interface CalendarFactoryInterface
{
	public function createCalendar($type);

	public function getCalendarTypes();
}