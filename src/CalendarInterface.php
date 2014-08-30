<?php

namespace Plummer\Calendar;

interface CalendarInterface
{
	public function addEvent(EventInterface $event);

	public function addRecurrenceType(RecurrenceInterface $recurrenceType);

	public function getEvents();
}