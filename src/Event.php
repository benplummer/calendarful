<?php

namespace Plummer\Calendar;

class Event
{
	protected $name;

	protected $dateStart;

	protected $dateEnd;

	protected $occurrenceDateStart;

	protected $calendar;

	protected $recurrenceType;
	
	protected function __construct($name, $dateStart, $dateEnd, &$calendar, $occurrenceDateStart = null)
	{
		$this->name = $name;
		$this->dateStart = $dateStart;
		$this->dateEnd = $dateEnd;
		$this->calendar = $calendar;
		$this->occurrenceDateStart = $occurrenceDateStart;
	}

	public static function make($name, $dateStart, $dateEnd, Calendar $calendar = null, $occurrenceDateStart = null)
	{
		return new static($name, $dateStart, $dateEnd, $calendar, $occurrenceDateStart);
	}

	public function setRecurrenceType(RecurrenceInterface $recurrenceType)
	{
		$this->recurrenceType = $recurrenceType;
	}

	public function setCalendar(Calendar $calendar)
	{
		$this->calendar = $calendar;
	}
}