<?php

namespace Plummer\Calendarful;

interface CalendarInterface
{
	public function addEvent(EventInterface $event);

	public function addRecurrenceType(RecurrenceInterface $recurrenceType);

	public function getEvents($fromDate, $toDate, $limit = null);
}
