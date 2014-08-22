<?php

namespace Plummer\Calendar;

class CalendarFactory
{
	public static function fromRegistry(RegistryInterface $registry)
	{
		return static::fromIterator($registry->getIterator());
	}

	public static function fromIterator(Iterator $iterator)
	{

	}
}