<?php

namespace Plummer\Calendar;

class Event
{
	protected $name;

	protected $dateStart;

	protected $dateEnd;

	protected $occurrenceDateStart;

	protected $parent;

	protected $recurrenceType;
	
	protected function __construct($name, $dateStart, $dateEnd, $parent = null, $occurrenceDateStart = null)
	{
		$this->name = $name;
		$this->dateStart = $dateStart;
		$this->dateEnd = $dateEnd;
		$this->parent = $parent;
		$this->occurrenceDateStart = $occurrenceDateStart;
	}

	public static function make($name, $dateStart, $dateEnd, $parent = null, $occurrenceDateStart = null)
	{
		return new static($name, $dateStart, $dateEnd, $parent, $occurrenceDateStart);
	}

	public function setRecurrenceType(RecurrenceInterface $recurrenceType)
	{
		$this->recurrenceType = $recurrenceType;
	}
}