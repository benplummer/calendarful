<?php

namespace Plummer\Calendar;

class Event implements EventInterface
{
	protected $name;

	protected $startDate;

	protected $endDate;

	protected $parentDate;

	protected $calendar;

	protected $parent;
	
	protected function __construct($name, $startDate, $endDate)
	{
		$this->name = $name;
		$this->startDate = $startDate;
		$this->endDate = $endDate;
	}

	public static function make($name, $startDate, $endDate, $occurrenceDate = null)
	{
		return new static($name, $startDate, $endDate, $occurrenceDate);
	}

	public function setCalendar(Calendar &$calendar)
	{
		$this->calendar = $calendar;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getStartDate()
	{
		return $this->startDate;
	}

	public function getEndDate()
	{
		return $this->endDate;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function getParentDate()
	{
		return $this->parentDate;
	}
}