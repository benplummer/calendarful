<?php

namespace Plummer\Calendarful;

class CalendarFactory
{
	public static function fromRegistry(CalendarAbstract $calendar, RegistryInterface $eventsRegistry, RegistryInterface $recurrencesRegistry)
	{
		$events = $eventsRegistry->hasFilters() ?
			$eventsRegistry->getFiltered() :
			$eventsRegistry->getAll();

		$recurrences = $recurrencesRegistry->hasFilters() ?
			$recurrencesRegistry->getFiltered() :
			$recurrencesRegistry->getAll();

		$calendar->addEvents($events);
		$calendar->addRecurrenceTypes($recurrences);

		return $calendar;
	}
}