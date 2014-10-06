<?php

namespace Plummer\Calendar;

class CalendarFactory
{
	public static function fromRegistry(CalendarAbstract $calendar, RegistryInterface $eventsRegistry, RegistryInterface $recurrencesRegistry)
	{
		$calendar->addEvents($eventsRegistry->getAll());
		$calendar->addRecurrenceTypes($recurrencesRegistry->getAll());

		return $calendar;
	}
}