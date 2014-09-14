<?php

namespace Plummer\Calendar;

use Plummer\Calendar\Recurrence\Daily;

class CalendarFactory
{
	public static function fromRegistry(RegistryInterface $registry)
	{
		return static::fromIterator($registry->getIterator());
	}

	public static function fromIterator(\Iterator $iterator)
	{
		$calendar = Calendar::make($iterator, static::getDefaultRecurrenceTypes());

		return $calendar;
	}

	public static function getDefaultRecurrenceTypes()
	{
		return [
			new Daily()
		];
	}
}