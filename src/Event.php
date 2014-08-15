<?php

namespace Plummer\Calendar;

class Event
{
	protected $uniqueId;

	protected $name;

	protected $dateStart;

	protected $dateEnd;

	protected $occurrenceDateStart;

	protected $calendar;
	
	protected function __construct($uniqueId, $name, $dateStart, $dateEnd, $occurrenceDateStart = null)
	{
		$this->uniqueId = $uniqueId;
		$this->name = $name;
		$this->dateStart = $dateStart;
		$this->dateEnd = $dateEnd;
		$this->occurrenceDateStart = $occurrenceDateStart;
	}

	public static function make($name, $dateStart, $dateEnd, $occurrenceDateStart = null)
	{
		return new static($name, $dateStart, $dateEnd, $occurrenceDateStart);
	}

	public function setCalendar(Calendar &$calendar)
	{
		$this->calendar = $calendar;
	}
}